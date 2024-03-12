<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Integrations_model extends MY_ModelCustomer
{
	use MY_Tables;

	public function __construct()
	{
		$this->_tabel = $this->_table_ms_brands;
		parent::__construct();
	}

	public function get_endpoints($id_source)
	{
		$query = $this->db->query("SELECT
										t1.id,
										t1.status,
										t2.title,
										t2.endpoint_url,
										t1.enabled_by_admin
									FROM admins_ms_company_endpoints t1
									INNER JOIN admins_ms_endpoints t2 ON  t2.id = t1.admins_ms_endpoints_id 
									AND t2.status = 1
									AND t2.deleted_at IS NULL
									AND t2.admins_ms_sources_id = {$id_source}
									WHERE  t1.users_ms_companys_id = {$this->_users_ms_companys_id}
									AND t1.deleted_at IS NULL
									ORDER BY t2.status DESC
								");
		return $query->result();
	}

	public function get_data()
	{
		$query =  $this->db->query("
									SELECT t1.id as id_source,
									t1.source_name,
									t1.source_icon,
									t1.source_url,
									t1.source_auth_url,
									t1.app_keys,
									t1.secret_keys,
									t2.id as id_channel,
									t2.channel_name,
									CASE WHEN t1.app_keys != '' OR t1.secret_keys != '' THEN 1 ELSE 0 END as source_key_status,
									CASE WHEN (SELECT status FROM users_ms_company_sources WHERE users_ms_companys_id = {$this->_users_ms_companys_id} AND admins_ms_sources_id = t1.id AND deleted_at IS NULL) = 1 THEN 1 ELSE 0 END as source_status,
									CASE 
										WHEN t3.status IS NULL THEN 0 
										WHEN t3.status = 0 THEN 2 
										WHEN t3.status = 1 THEN 1 
										ELSE 0 
									END as channel_auth_status,
									CASE WHEN EXISTS (
										SELECT 1
										FROM (
											SELECT t1.id as id_source,
													t2.id as id_channel,
													CASE WHEN t1.app_keys != '' OR t1.secret_keys != '' THEN 1 ELSE 0 END as source_key_status,
													CASE WHEN (SELECT status FROM users_ms_company_sources WHERE users_ms_companys_id = {$this->_users_ms_companys_id} AND admins_ms_sources_id = t1.id AND deleted_at IS NULL) = 1 THEN 1 ELSE 0 END as source_status,
													CASE WHEN t3.status = 1 THEN 1 ELSE 0 END as channel_auth_status,
													CASE WHEN 
															(CASE WHEN t1.app_keys != '' OR t1.secret_keys != '' THEN 1 ELSE 0 END = 0 
															OR
															(SELECT status
															FROM users_ms_company_sources 
															WHERE users_ms_companys_id = {$this->_users_ms_companys_id}
															AND admins_ms_sources_id = t1.id
															AND deleted_at IS NULL
															) = 0) THEN 0 ELSE 1
														END as all_status
											FROM admins_ms_sources t1 
											LEFT JOIN users_ms_channels t2 ON t1.id = t2.admins_ms_sources_id AND t2.status = 1 AND t2.deleted_at IS NULL AND t2.users_ms_companys_id = {$this->_users_ms_companys_id} 
											LEFT JOIN users_ms_authenticate_channels t3 ON t2.admins_ms_sources_id = t3.sources_id AND t3.channels_id = t2.id AND t2.deleted_at IS NULL AND t2.users_ms_companys_id = {$this->_users_ms_companys_id} 
											WHERE t1.status = 1 AND t1.deleted_at IS NULL
										) subquery
										WHERE subquery.all_status = 1
									) THEN 1 ELSE 0 END as all_status
								FROM admins_ms_sources t1 
								LEFT JOIN users_ms_channels t2 ON t1.id = t2.admins_ms_sources_id AND t2.status = 1 AND t2.deleted_at IS NULL AND t2.users_ms_companys_id = {$this->_users_ms_companys_id} 
								LEFT JOIN users_ms_authenticate_channels t3 ON t2.admins_ms_sources_id = t3.sources_id AND t3.channels_id = t2.id AND t2.deleted_at IS NULL AND t2.users_ms_companys_id = {$this->_users_ms_companys_id} 
								WHERE t1.status = 1 AND t1.deleted_at IS NULL
								ORDER BY source_key_status DESC, source_status DESC, channel_auth_status DESC;


        ");

		$result = $query->result_array();

		$formatted_sources = array();

		foreach ($result as $source) {

			$data = array(
				'id_source' => $source['id_source'],
				'id_channel' => $source['id_channel'],
				'source_url' => $source['source_url'],
				'source_auth_url' => $source['source_auth_url'],
				'app_keys' => $source['app_keys'],
				'secret_keys' => $source['secret_keys'],
				'all_status' => $source['all_status']
			);
			$data_json = json_encode($data);
			$source_id = $source['id_source'];

			switch ($source['channel_auth_status']) {
				case 1:
					$url = BASE_URL . '/integrations/manage_list/' . $source['id_source'];
					// $channel_auth_name = "<span class='text-white'><i class='bi bi-check-circle  fs-4 px-1 text-white'></i> Connected</span>";
					$channel_auth_name = "Connected";
					$channel_auth_color = "198754";
					$button = '<button type="button" id="btnManage" data-type="modal" 
					data-url="' . $url . '"  class="ms-3 btn btn-sm  btn-light-primary hover-scale fs-8  mt-1 px-2 ">
					<i class="bi bi-gear fs-4 px-1 "></i> Manage
				</button>';
					// 	$channel_auth_status_html = '<span
					// 	class="position-absolute top-0 end-0 translate-middle badge rounded-pill bg-success text-white"
					// 	style="margin-top:20px; margin-right: -56px;">
					// 	<i class="bi bi-check-circle  fs-4 px-1 text-white"></i>
					// 	Connected
					// </span>' ;
					break;
				case 0:
					// $channel_auth_name =  "<span class='text-gray-600'><i class='bi bi-check-circle  fs-4 px-1 text-gray-600'></i> Not Connected</span>";
					$channel_auth_name = "Not Connected";
					$channel_auth_color = "6c757d";
					$button = '<button type="button" id="connect" data-id="' . htmlspecialchars($data_json) . '" class="ms-3 btn btn-sm btn-primary hover-scale fs-8  mt-1 px-2 ">
				Connect
				<i class="bi bi-arrow-right-circle text-white fs-4 px-1 "></i>
				</button>';
					// case 0 :
					// 	$channel_auth_status_html = '<span id="connect" data-id="'.htmlspecialchars($data_json).'" class="ms-3 text-white  fs-8  mt-1 px-2 badge bg-info bg-hover-light text-hover-dark">
					// 	Connect
					// 	<i class="bi bi-arrow-right-circle text-white fs-4 px-1 "></i>
					// </span>';

					// case 0 :
					// 	$channel_auth_status_html = '<span
					// 	class="position-absolute top-0 end-0 translate-middle badge rounded-pill bg-secondary text-gray-600"
					// 	style="margin-top:20px; margin-right: -70px;">
					// 	<i class="bi bi-exclamation-circle fs-4 px-1"></i>
					// 	Not Connected
					// </span>';
					break;
				case 2:
					// $channel_auth_name =  "<span class='text-danger'><i class='bi bi-check-circle  fs-4 px-1 text-danger'></i> Not Connected</span>";
					$channel_auth_name = "Disconnected";
					$channel_auth_color = "dc3545";
					$button = '<button type="button" id="connect" data-id="' . htmlspecialchars($data_json) . '" class="ms-3 btn btn-sm btn-danger hover-scale fs-8  mt-1 px-2 ">
				Reconnect
				<i class="bi bi-arrow-right-circle text-white fs-4 px-1 "></i>
				</button>';
					// case 2 :
					// 	$channel_auth_status_html = '<span
					// 	class="position-absolute top-0 end-0 translate-middle badge rounded-pill bg-danger "
					// 	style="margin-top:20px; margin-right: -65px;">
					// 	<i class="bi bi-x-circle text-white fs-4 px-1"></i>
					// 	Disconnected
					// </span>';
					break;
				default:
					$channel_auth_status_html = '<span
				class="position-absolute top-0 end-0 translate-middle badge rounded-pill bg-danger "
				style="margin-top:20px; margin-right: -50px;">
					<i class="bi bi-question-circle text-white fs-4 px-1"></i>
					Contact Support
				</span>';
					break;
			}

			$channel_data = array(
				'id_channel' => $source['id_channel'],
				'id_source' => $source_id,
				'channel_name' => $source['channel_name'],
				'channel_auth_status' => $source['channel_auth_status'],
				// 'channel_auth_status_html' => $channel_auth_status_html
				'channel_auth_name' => $channel_auth_name,
				'channel_auth_color' => $channel_auth_color,
				'button' => $button,
			);

			if (!isset($formatted_sources[$source_id])) {
				$formatted_sources[$source_id] = array(
					'id_source' => $source_id,
					'source_name' => $source['source_name'],
					'source_icon' => ASSETSGENERAL  . '/uploads/channels_image/' . $source['source_icon'],
					'source_url' => $source['source_url'],
					'source_auth_url' => $source['source_auth_url'],
					'app_keys' => $source['app_keys'],
					'secret_keys' => $source['secret_keys'],
					'source_key_status' => $source['source_key_status'],
					'source_status' => $source['source_status'],
					'all_status' => $source['all_status'],
					'channel' => array()
				);
			}

			$formatted_sources[$source_id]['channel'][] = $channel_data;
		}
		// echo '<pre>';
		// print_r(array_values($formatted_sources));
		// die;
		return array_values($formatted_sources);
	}


	public function connect()
	{
		try {
			$source = $_POST['id_source'];

			switch ($source) {

					// Shopee
				case 2:
					$host   = $_POST['source_url'];
					$path   = "/api/v2/shop/auth_partner";
					$redirect_url = $_POST['source_auth_url'] . '/' . $_POST['id_channel'];


					$partner_id   = $_POST['app_keys'];
					$secret_key = $_POST['secret_keys'];


					$timest = time();
					$baseString = sprintf("%s%s%s", $partner_id, $path, $timest);
					$sign = hash_hmac('sha256', $baseString, $secret_key);
					$url = sprintf("%s%s?partner_id=%s&timestamp=%s&sign=%s&redirect=%s", $host, $path, $partner_id, $timest, $sign, $redirect_url);
					$response['url']     = $url;

					break;
					// Tiktok
				case 4:
					$host   = $_POST['source_auth_url'];
					$path       = '/oauth/authorize';

					$params = [
						'app_key' => $_POST['app_keys'],
						'state' =>  $_POST['id_channel']
					];

					$response['url']     = create_url($host, $path, $params);
					break;
				default:
					throw new Exception("Unable to connect, Please contact support", 0);
					break;
			}
			$response['success'] = true;
			$response['message'] = "Please wait system will automatically redirect.";
			return $response;
		} catch (Exception $e) {
			$response['success'] = false;
			$response['message'] = $e->getMessage();

			return $response;
		}
	}


	public function change_status()
	{

		$this->db->trans_begin();
		try {
			$id = $_POST['id'];
			$status = $_POST['value'] ? 0 : 1;

			$this->db->where('id', $id);
			$this->db->where('users_ms_companys_id', $this->_users_ms_companys_id);
			$this->db->update('admins_ms_company_endpoints', array('status' => $status));

			$this->db->trans_commit();
			$response['success'] = true;
			return $response;
		} catch (Exception $e) {
			$this->db->trans_rollback();
			$response['success'] = false;
			$response['messages'] = $e->getMessage();
			return $response;
		}
	}
}
