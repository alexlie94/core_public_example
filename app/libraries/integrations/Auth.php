<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Auth
{
	protected $ci;
	protected $db;
	protected $helper;
	public function __construct()
	{
		$this->ci = &get_instance();
		$this->db = $this->ci->db;
		$this->ci->load->library('integrations/helper');
		$this->helper = $this->ci->helper;

		date_default_timezone_set('UTC');
	}

	public function auth_process_expiry_by_source_id($source_id)
	{
		try {
			$source_data = $this->helper->get_auth_expiry_by_source_id($source_id);
			if (!$source_data['status']) {
				throw new Exception($source_data['msg']);
			}
			switch ($source_id) {
				case '2':
					foreach ($source_data['data'] as $data_source) {
						$path = '/api/v2/auth/access_token/get';
						$timestamp = time();

						$string = sprintf("%s%s%s", $data_source['partner_id'], $path, $timestamp);
						$sign = hash_hmac('sha256', $string, $data_source['secret_key']);

						$param = array(
							'partner_id' => $data_source['partner_id'],
							'sign' => $sign,
							'timestamp' => $timestamp,
						);

						$url = create_url($data_source['host'], $path, $param);
						$data_auth = array(
							'shop_id' => intVal($data_source['shop_id']),
							'refresh_token' => $data_source['refresh_token'],
							'partner_id' => $data_source['partner_id'],
						);

						$data = post_request_curl($url, json_encode($data_auth));
						if (!$data) {
							throw new Exception('Cannot return data from marketplace API');
						}

						if ($data->error === '') {
							$update_data['refresh_token'] = $data->refresh_token;
							$update_data['access_token'] = $data->access_token;
							$update_data['access_token_expire'] = $data->expire_in;
							$update_data['message'] = 'success';
							$update_data['status'] = 1;
						} else {
							$update_data['message'] = $data->message;
							$update_data['status'] = 0;
						}



						$this->db->where('shop_id', $data_source['shop_id']);
						$this->db->where('deleted_at IS NULL');
						$update = $this->db->update('users_ms_authenticate_channels', $update_data);
						if (!$update) {
							throw new Exception('Cannot update data auth');
						}
					}

					break;
			}

			return ['status' => false, 'msg' => 'success'];
			;
		} catch (exception $e) {

			return ['status' => false, 'msg' => $e->getMessage()];
		}
	}

	public function auth_process_tokopedia($source_id = 3)
	{
		try {
			$source_data = $this->helper->get_source_by_id($source_id);
			if (!$source_data['status']) {
				throw new Exception($source_data['msg']);
			}

			$client_id = $source_data['data']->app_keys;
			$client_secret = $source_data['data']->secret_keys;

			$combined = $client_id . ':' . $client_secret;

			$base64 = base64_encode($combined);

			$header = array(
				'Authorization: Basic ' . $base64,
			);


			$path = '/token?grant_type=client_credentials';
			$url = $source_data['data']->source_auth_url . $path;

			$data = post_request_with_header_curl($url, '', $header);
			if (!$data) {
				throw new Exception('Cannot return data from marketplace API');
			}

			if (!isset($data->error)) {
				$data_update['access_token'] = $data->access_token;
				$data_update['access_token_expire'] = $data->expires_in;
				$data_update['status'] = 1;
				$data_update['message'] = 'success';
			} else {
				$data_update['status'] = 0;
				$data_update['message'] = $data->error;

			}

			$this->db->where('sources_id', $source_id);
			$this->db->where('deleted_at IS NULL');
			$update = $this->db->update('users_ms_authenticate_channels', $data_update);
			if (!$update) {
				throw new Exception('Cannot update data auth');
			}

			return ['status' => false, 'msg' => 'success'];

		} catch (Exception $e) {
			return ['status' => false, 'msg' => $e->getMessage()];
		}
	}



}
