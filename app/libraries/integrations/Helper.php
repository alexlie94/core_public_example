<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Helper
{

	protected $ci;
	protected $db;
	public function __construct()
	{
		$this->ci = &get_instance();
		$this->db = $this->ci->db;
	}


	function get_integration_data_by_channel_id($channel_id, $endpoint_code = null)
	{
		try {
			if ($endpoint_code !== null) {
				$endpoint = "INNER JOIN admins_ms_endpoints t3 
										ON t1.id = t3.admins_ms_sources_id
										AND t3.title = '{$endpoint_code}'
										AND t3.status = 1
										AND t3.deleted_at IS NULL
								INNER JOIN admins_ms_company_endpoints t4
										ON t2.users_ms_companys_id = t4.users_ms_companys_id
										AND t3.id = t4.admins_ms_endpoints_id
										AND t4.status = 1 AND t4.deleted_at IS NULL";
			} else {
				$endpoint = "";
			}

			$query = $this->db->query("SELECT 
										t1.id as source_id,
										t1.source_url,
										t1.source_auth_url,
										t1.app_id,
										t1.app_keys,
										t1.secret_keys,
										t2.id as auth_id,
										t2.users_ms_companys_id as company_id,
										t2.channels_id as channel_id,
										t2.shop_id,
										t2.access_token
								FROM admins_ms_sources t1
								INNER JOIN users_ms_authenticate_channels t2 
										ON t1.id = t2.sources_id 
										AND t2.status = 1 
										AND t2.deleted_at IS NULL
								{$endpoint}
								WHERE t2.channels_id = {$channel_id}
								AND t1.status = 1
								AND t1.deleted_at IS NULL
					");
			$result = $query->row();
			if ($result) {
				if ($result->shop_id == '' || $result->shop_id == null) {
					throw new Exception('The configuration data has not been activated');
				} else {
					$config = [];
					$config['company_id'] = $result->company_id;
					$config['source_id'] = $result->source_id;
					$config['channel_id'] = $result->channel_id;
					$config['auth_id'] = $result->auth_id;
					$config['app_id'] = intVal($result->app_id);
					$config['partner_id'] = intVal($result->app_keys);
					$config['secret_key'] = $result->secret_keys;
					$config['shop_id'] = $result->shop_id;
					$config['access_token'] = $result->access_token;
					$config['host'] = $result->source_url;

					return ['status' => true, 'data' => $config];
				}
			} else {
				throw new Exception('The configuration data has not been activated');
			}
		} catch (Exception $e) {
			return ['status' => false, 'msg' => $e->getMessage()];
		}
	}

	function get_integration_data_by_shop_id($shop_id, $endpoint_code = null)
	{
		try {
			if ($endpoint_code !== null) {
				$endpoint = "INNER JOIN admins_ms_endpoints t3 
										ON t1.id = t3.admins_ms_sources_id
										AND t3.title = '{$endpoint_code}'
										AND t3.status = 1
										AND t3.deleted_at IS NULL
								INNER JOIN admins_ms_company_endpoints t4
										ON t2.users_ms_companys_id = t4.users_ms_companys_id
										AND t3.id = t4.admins_ms_endpoints_id
										AND t4.status = 1 AND t4.deleted_at IS NULL";
			} else {
				$endpoint = "";
			}

			$query = $this->db->query("SELECT 
										t1.id as source_id,
										t1.source_name as source_name,
										t1.source_url,
										t1.source_auth_url,
										t1.app_id,
										t1.app_keys,
										t1.secret_keys,
										t2.id as auth_id,
										t2.users_ms_companys_id as company_id,
										t2.channels_id as channel_id,
										t2.shop_id,
										t2.access_token,
										t5.channel_name
								FROM admins_ms_sources t1
								INNER JOIN users_ms_authenticate_channels t2 
										ON t1.id = t2.sources_id 
										AND t2.status = 1 
										AND t2.deleted_at IS NULL
								INNER JOIN users_ms_channels t5 
										ON t5.id = t2.channels_id
										AND t5.status = 1 
										AND t5.deleted_at IS NULL
								{$endpoint}
								WHERE t2.shop_id = {$shop_id}
								AND t1.status = 1
								AND t1.deleted_at IS NULL
					");
			$result = $query->row();
			if ($result) {
				if ($result->shop_id == '' || $result->shop_id == null) {
					throw new Exception('The configuration data has not been activated');
				} else {
					$config = [];
					$config['company_id'] = $result->company_id;
					$config['source_id'] = $result->source_id;
					$config['source_name'] = $result->source_name;
					$config['channel_id'] = $result->channel_id;
					$config['channel_name'] = $result->channel_name;
					$config['auth_id'] = $result->auth_id;
					$config['app_id'] = intVal($result->app_id);
					$config['partner_id'] = intVal($result->app_keys);
					$config['secret_key'] = $result->secret_keys;
					$config['shop_id'] = $result->shop_id;
					$config['access_token'] = $result->access_token;
					$config['host'] = $result->source_url;

					return ['status' => true, 'data' => $config];
				}
			} else {
				throw new Exception('The configuration data has not been activated');
			}
		} catch (Exception $e) {
			return ['status' => false, 'msg' => $e->getMessage()];
		}
	}
	function get_integration_data_by_source_id($source_id, $endpoint_code = null)
	{
		try {
			if ($endpoint_code !== null) {
				$endpoint = "INNER JOIN admins_ms_endpoints t3 
										ON t1.id = t3.admins_ms_sources_id
										AND t3.title = '{$endpoint_code}'
										AND t3.status = 1
										AND t3.deleted_at IS NULL
								INNER JOIN admins_ms_company_endpoints t4
										ON t2.users_ms_companys_id = t4.users_ms_companys_id
										AND t3.id = t4.admins_ms_endpoints_id
										AND t4.status = 1 AND t4.deleted_at IS NULL";
			} else {
				$endpoint = "";
			}

			$query = $this->db->query("SELECT 
										t1.id as source_id,
										t1.source_url,
										t1.source_auth_url,
										t1.app_keys,
										t1.secret_keys,
										t2.id as auth_id,
										t2.users_ms_companys_id as company_id,
										t2.channels_id as channel_id,
										t2.shop_id,
										t2.access_token,
										t2.refresh_token
								FROM admins_ms_sources t1
								INNER JOIN users_ms_authenticate_channels t2 
										ON t1.id = t2.sources_id 
										AND t2.status = 1 
										AND t2.deleted_at IS NULL
								{$endpoint}
								WHERE t1.id = {$source_id}
								AND t1.status = 1
								AND t1.deleted_at IS NULL
								AND t2.shop_id != ''
								AND t2.shop_id IS NOT NULL
					");
			$result = $query->result();
			if ($result) {
				$config = [];

				foreach ($result as $row) {
					$config[] = [
						'partner_id' => (int) $row->app_keys,
						'secret_key' => $row->secret_keys,
						'shop_id' => $row->shop_id,
						'access_token' => $row->access_token,
						'refresh_token' => $row->refresh_token,
						'host' => $row->source_url
					];
				}

				return ['status' => true, 'data' => $config];
			} else {
				throw new Exception('The configuration data has not been activated');
			}
		} catch (Exception $e) {
			return ['status' => false, 'msg' => $e->getMessage()];
		}
	}
	function get_auth_expiry_by_source_id($source_id)
	{
		try {
			date_default_timezone_set('Asia/Jakarta');
			switch ($source_id) {
				case 2: //shopee
					$condition = 'AND UNIX_TIMESTAMP(NOW()) > (UNIX_TIMESTAMP(t2.updated_at) + t2.access_token_expire - 3600)';
					break;
				default:
					throw new Exception('Source not registered');
					break;
			}

			$query = $this->db->query("SELECT
										t1.id as source_id,
										t1.source_url,
										t1.source_auth_url,
										t1.app_keys,
										t1.secret_keys,
										t2.id as auth_id,
										t2.users_ms_companys_id as company_id,
										t2.channels_id as channel_id,
										t2.shop_id,
										t2.access_token,
										t2.refresh_token
								FROM admins_ms_sources t1
								INNER JOIN users_ms_authenticate_channels t2 
										ON t1.id = t2.sources_id 
										$condition
										AND t2.status = 1 
										AND t2.deleted_at IS NULL
								WHERE t1.id = {$source_id}
								AND t1.status = 1
								AND t1.deleted_at IS NULL
								AND 
								(t2.shop_id != '' OR t2.shop_id IS NOT NULL)
					");
			$result = $query->result();
			if ($result) {
				$config = [];

				foreach ($result as $row) {
					$config[] = [
						'partner_id' => (int) $row->app_keys,
						'secret_key' => $row->secret_keys,
						'shop_id' => $row->shop_id,
						'access_token' => $row->access_token,
						'refresh_token' => $row->refresh_token,
						'host' => $row->source_url
					];
				}

				return ['status' => true, 'data' => $config];
			} else {
				throw new Exception('No channels expiry');
			}
		} catch (Exception $e) {
			return ['status' => false, 'msg' => $e->getMessage()];
		}
	}
	function get_source_by_id($source_id)
	{
		try {
			$query = $this->db->query("SELECT
										t1.id as source_id,
										t1.source_url,
										t1.source_auth_url,
										t1.app_id,
										t1.app_keys,
										t1.secret_keys
								FROM admins_ms_sources t1
								WHERE t1.id = {$source_id}
								AND t1.status = 1
								AND t1.deleted_at IS NULL
					");
			$result = $query->row();
			if ($result) {
				return ['status' => true, 'data' => $result];
			} else {
				throw new Exception('No channels expiry');
			}
		} catch (Exception $e) {
			return ['status' => false, 'msg' => $e->getMessage()];
		}
	}
	public function get_management_status($source_id, $status_id, $status_name, $type)
	{

		try {

			if ($status_id !== NULL) {
				$where = 't1.source_status_code = ' . $status_id;
			} elseif ($status_name !== NULL) {
				$where = "t1.source_status_name = '" . $status_name . "'";
			} else {
				throw new Exception();
			}
			$query = $this->db->query("SELECT 
										t1.status_id,
										t1.source_status_code,
										t1.source_status_name
								FROM admins_ms_management_status t1
								WHERE t1.admins_ms_sources_id = {$source_id}
								AND {$where}
								AND t1.status_type = '{$type}'
							");
			$result = $query->row();

			if ($result) {
				return $result;
			} else {
				throw new Exception();
			}
		} catch (Exception $e) {
			return false;
		}
	}
	function get_product_by_sku($users_ms_companys_id, $sku)
	{
		try {
			$query = $this->db->query("SELECT 
									t1.id product_id,
									t1.product_name,
									t2.id variant_id,
									t2.sku
							   FROM users_ms_products t1
							   INNER JOIN users_ms_product_variants t2 ON t2.users_ms_products_id = t1.id
								   AND t2.users_ms_companys_id = {$users_ms_companys_id}
								   AND t2.deleted_at IS NULL
							   WHERE t1.users_ms_companys_id = {$users_ms_companys_id}
							   AND t1.deleted_at IS NULL
							   AND t2.sku = '{$sku}'
								");
			$result = $query->row();

			if (!$result) {
				throw new Exception();
			}
			return $result;
		} catch (Exception $e) {
			return false;
		}
	}

	function check_order_by_local_id($local_id)
	{

		try {
			$query = $this->db->query("SELECT 
									error_code,
									users_ms_companys_id,
									users_ms_channels_id,
									id as users_tr_orders_id,
									local_order_id
							   FROM users_tr_orders 
							   WHERE local_order_id = '{$local_id}'
							");
			$result = $query->row();

			if (!$result) {
				throw new Exception();
			}

			return $result;
		} catch (Exception $e) {
			return false;
		}
	}


	function check_stock($item, $warehouse_id)
	{
		try {
			$query = $this->db->query("SELECT *,(qty - {$item['quantity_purchased']}) as final_qty
									FROM {'users_ms_inventory_storages'} t1
									WHERE t1.sku = '{$item['product_sku']}'
									AND t1.users_ms_companys_id = {$item['users_ms_companys_id']}
									AND t1.users_ms_warehouses_id = {$warehouse_id}
								");

			$result = $query->row();

			if (!$result) {
				throw new Exception();
			}
			return $result;
		} catch (Exception $e) {
			return false;
		}
	}
}