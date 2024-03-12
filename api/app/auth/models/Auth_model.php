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

	public function auth_tiktok()
	{

		$this->db->trans_begin();
		try {

			$id_channel = $_GET['state'];

			$source = $this->get_channel_source($id_channel);

			$app_keys    	= $source->app_keys;
			$secret_keys  	= $source->secret_keys;
			$auth_code  	= $_GET['code'];
			$grant_type 	= 'authorized_code';
			$path       	= "/api/v2/token/get";
			$auth_host      = $source->source_auth_url;
			$host       	= $source->source_url;


			$param = array(
				"app_key"       => $app_keys,
				"auth_code"     => $auth_code,
				"app_secret"    => $secret_keys,
				"grant_type"    => $grant_type,
			);

			$url     		= create_url($auth_host, $path, $param);
			$data_return    = get_request_curl($url);


			if ($data_return->code > 0) {
				$access = array(
					'users_ms_companys_id' => $source->users_ms_companys_id,
					'sources_id' => $source->id,
					'channels_id' => $id_channel,
					'message' => $data_return->message,
					'status' => 0,
				);
			} else {
				$data = $data_return->data;

				$timestamp = time();
				// get shop id
				$param_sign_shop = array(
					"timestamp"     => $timestamp,
					"app_key"       => $app_keys,
				);
				$path_shop           = "/api/shop/get_authorized_shop";
				$sign = generate_signature_tiktok($path_shop, $param_sign_shop, $secret_keys);
				$param_shop = array(
					"timestamp"     => $timestamp,
					"app_key"       => $app_keys,
					"access_token"  => $data->access_token,
					'sign'          => $sign
				);

				$url     		= create_url($host, $path_shop, $param_shop);
				$shop_data    	= get_request_curl($url);

				if ($shop_data->code > 0) {
					throw new Exception('Sorry, we encountered an error while retrieving shop data. Please reach out to our support team for assistance.');
				}

				$shop_name = $data->seller_name;
				$shop_result = array_filter($shop_data->data->shop_list, function ($shop) use ($shop_name) {
					return $shop->shop_name === $shop_name;
				});

				if (empty($shop_result)) {
					throw new Exception('Sorry, the shop you are looking for could not be found.');
				}

				$access = array(
					'users_ms_companys_id' => $source->users_ms_companys_id,
					'sources_id' => $source->id,
					'channels_id' => $id_channel,
					'shop_id' => $shop_result[0]->shop_id,
					'shop_name' => $data->seller_name,
					'refresh_token' => $data->refresh_token,
					'refresh_token_expire' => $data->refresh_token_expire_in,
					'access_token' => $data->access_token,
					'access_token_expire' => $data->access_token_expire_in,
					'message' => $data_return->message,
					'status' => 1,
				);
			}

			$query = $this->db->query("SELECT *
							  FROM users_ms_authenticate_channels
							  WHERE users_ms_companys_id = {$source->users_ms_companys_id}
							  AND sources_id = {$source->id}
							  AND channels_id = {$id_channel}
							");
			$check = $query->num_rows();

			if ($check < 1) {
				$this->db->insert('users_ms_authenticate_channels', $access);
			} else {
				$this->db->where('users_ms_companys_id', $source->users_ms_companys_id);
				$this->db->where('sources_id', $source->id);
				$this->db->where('channels_id', $id_channel);
				$this->db->update('users_ms_authenticate_channels', $access);
			}

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


	public function auth_shopee($channel_id)
	{

		$this->db->trans_begin();
		try {


			$source = $this->get_channel_source($channel_id);

			$partner_id    	= $source->app_keys;
			$secret_key  	= $source->secret_keys;
			$code  			= $_GET['code'];
			$shop_id  		= $_GET['shop_id'];
			$path       	= "/api/v2/auth/token/get";
			$auth_host      = $source->source_auth_url;
			$host       	= $source->source_url;

			$timest = time();
			$body = array("code" => $code,  "shop_id" => intVal($shop_id), "partner_id" => intVal($partner_id));
			$baseString = sprintf("%s%s%s", $partner_id, $path, $timest);
			$sign = hash_hmac('sha256', $baseString, $secret_key);
			$url = sprintf("%s%s?partner_id=%s&timestamp=%s&sign=%s", $host, $path, $partner_id, $timest, $sign);

			$data_return    = post_request_curl($url, $body);


			if ($data_return->error === NULL || $data_return->error === "") {
				$access = array(
					'users_ms_companys_id' => $source->users_ms_companys_id,
					'sources_id' => $source->id,
					'channels_id' => $channel_id,
					'shop_id' =>  $shop_id,
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
			} else {
				$response['success'] = false;
			}

			return $response;
		} catch (Exception $e) {
			$this->db->trans_rollback();
			$response['success'] = false;
			$response['messages'] = $e->getMessage();
			return $response;
		}
	}
}
