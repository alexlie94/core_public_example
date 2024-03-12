<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
	}

	private function get_channel_source($id_channel)
	{
		$query = $this->db->query("SELECT t2.* ,t1.users_ms_companys_id
								   FROM users_ms_channels t1
								   INNER JOIN admins_ms_sources t2 ON t1.admins_ms_sources_id = t2.id
								   WHERE t1.id = {$id_channel}
								    ");
		return $query->row();
	}
	public function auth_shopee($channel_id)
	{

		$this->db->trans_begin();
		try {


			$source = $this->get_channel_source($channel_id);

			$partner_id = $source->app_keys;
			$secret_key = $source->secret_keys;
			$code = $_GET['code'];
			$shop_id = $_GET['shop_id'];
			$path = "/api/v2/auth/token/get";
			$auth_host = $source->source_auth_url;
			$host = $source->source_url;

			$timest = time();
			$body = array("code" => $code, "shop_id" => intVal($shop_id), "partner_id" => intVal($partner_id));
			$baseString = sprintf("%s%s%s", $partner_id, $path, $timest);
			$sign = hash_hmac('sha256', $baseString, $secret_key);
			$url = sprintf("%s%s?partner_id=%s&timestamp=%s&sign=%s", $host, $path, $partner_id, $timest, $sign);

			$data_return = post_request_curl($url, json_encode($body));



			if ($data_return->error === NULL || $data_return->error === "") {
				$access = array(
					'users_ms_companys_id' => $source->users_ms_companys_id,
					'sources_id' => $source->id,
					'channels_id' => $channel_id,
					'shop_id' => $shop_id,
					'shop_name' => "",
					'refresh_token' => $data_return->refresh_token,
					'access_token' => $data_return->access_token,
					'access_token_expire' => $data_return->expire_in,
					'message' => "success",
					'status' => 1,
				);
			} else {
				$access = array(
					'users_ms_companys_id' => $source->users_ms_companys_id,
					'sources_id' => $source->id,
					'channels_id' => $channel_id,
					'message' => $data_return->message,
					'status' => 0,
				);
			}

			// check shop if exist
			$query = $this->db->query("SELECT *
							  FROM users_ms_authenticate_channels
							  WHERE sources_id = {$source->id}
							  AND shop_id = $shop_id
							  AND channels_id != {$channel_id}
							");
			$check_shop = $query->num_rows();
			if ($check_shop) {
				throw new Exception('Your shop is registered, please ensure that any previously connected shop is disconnected.');
			}

			$query = $this->db->query("SELECT *
							  FROM users_ms_authenticate_channels
							  WHERE users_ms_companys_id = {$source->users_ms_companys_id}
							  AND sources_id = {$source->id}
							  AND channels_id = {$channel_id}
							");
			$check = $query->num_rows();

			if ($check < 1) {
				$this->db->insert('users_ms_authenticate_channels', $access);
			} else {
				$this->db->where('users_ms_companys_id', $source->users_ms_companys_id);
				$this->db->where('sources_id', $source->id);
				$this->db->where('channels_id', $channel_id);
				$this->db->update('users_ms_authenticate_channels', $access);
			}

			$this->db->trans_commit();
			if ($access['status']) {
				$response['success'] = true;
				$response['messages'] = 'Your account has been integrated';
			} else {
				throw new Exception('Unfortunately, an error occurred while integrating your account. Please contact support.');
			}

			return $response;
		} catch (Exception $e) {
			$this->db->trans_rollback();
			$response['success'] = false;
			$response['messages'] = $e->getMessage();
			return $response;
		}
	}
	public function get_source()
	{

		date_default_timezone_set('UTC');

		$query = $this->db->query("SELECT 
										t1.id as id_source,
										t1.source_url,
										t1.app_keys,
										t1.secret_keys,
										t2.id as id_auth,
										t2.refresh_token
									FROM admins_ms_sources t1
									LEFT JOIN users_ms_authenticate_channels t2 ON t1.id = t2.sources_id
									WHERE t1.app_keys != ''
									AND t1.secret_keys != ''
									AND t1.status = 1
									AND t1.deleted_at IS NULL
									AND t2.deleted_at IS NULL
									AND t2.refresh_token_expire < UNIX_TIMESTAMP() + 7200
									AND t2.refresh_token_expire != 0
									ORDER BY t2.refresh_token_expire ASC
									LIMIT 5
								   ");
		return $query->result();
	}
	public function _refresh_token()
	{
		$this->db->trans_begin();
		try {
			$source_data = $this->get_source();
			if (!$source_data) {
				throw new Exception('No expired source');
			}

			foreach ($source_data as $source) {
				switch ($source->id_source) {
					// Tiktok
					case 4:
						$app_keys = $source->app_keys;
						$secret_keys = $source->secret_keys;
						$refresh_token = $source->refresh_token;
						$grant_type = 'refresh_token';
						$path = "/api/v2/token/refresh";
						$host = $source->source_url;

						$param = array(
							"app_key" => $app_keys,
							"refresh_token" => $refresh_token,
							"app_secret" => $secret_keys,
							"grant_type" => $grant_type,
						);

						$url = create_url($host, $path, $param);
						$data_return = get_request_curl($url);

						if (!$data_return) {
							throw new Exception('Something wrong with Request');
						}
						// print_r($data_return);

						if ($data_return->code > 0) {
							$access = array(
								'message' => $data_return->message,
							);
						} else {
							$data = $data_return->data;

							$access = array(
								'refresh_token' => $data->refresh_token,
								'refresh_token_expire' => $data->refresh_token_expire_in,
								'access_token' => $data->access_token,
								'access_token_expire' => $data->access_token_expire_in,
								'message' => $data_return->message,
								'status' => 1,
							);
						}

						$this->db->where('id', $source->id_auth);
						$this->db->update('users_ms_authenticate_channels', $access);

						break;

					default:
						echo "Auth Source " . $source->id_auth . " is not configured";
						break;
				}
			}


			$this->db->trans_commit();
			$response['success'] = true;
			$response['message'] = "success";
			return $response;
		} catch (Exception $e) {
			$this->db->trans_rollback();
			$response['success'] = false;
			$response['message'] = $e->getMessage();
			return $response;
		}

	}

}
