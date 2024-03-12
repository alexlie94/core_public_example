<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Process_orders
{

	protected $ci;
	protected $db;

	public function __construct()
	{
		$this->ci = &get_instance();
		$this->db = $this->ci->db;
	}

	function get_all_channel_data_by_source_id($source_id)
	{
		try {
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
										t2.access_token
								FROM admins_ms_sources t1
								INNER JOIN users_ms_authenticate_channels t2 
										ON t1.id = t2.sources_id 
										AND t2.status = 1 
										AND t2.deleted_at IS NULL
								WHERE t1.id = {$source_id}
								AND t1.status = 1
								AND t1.deleted_at IS NULL
					");
			$result = $query->result();
			if (!$result) {
				throw new Exception();
			}
			return $result;
		} catch (Exception $e) {
			return false;
		}
	}
	function get_channel_data_by_channel_id($channel_id, $endpoint_code)
	{
		try {
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
										t2.access_token
								FROM admins_ms_sources t1
								INNER JOIN users_ms_authenticate_channels t2 
										ON t1.id = t2.sources_id 
										AND t2.status = 1 
										AND t2.deleted_at IS NULL
								INNER JOIN admins_ms_endpoints t3 
										ON t1.id = t3.admins_ms_sources_id
										AND t3.title = '{$endpoint_code}'
										AND t3.status = 1
										AND t3.deleted_at IS NULL
								INNER JOIN admins_ms_company_endpoints t4
										ON t2.users_ms_companys_id = t4.users_ms_companys_id
										AND t3.id = t4.admins_ms_endpoints_id
										AND t4.status = 1 AND t4.deleted_at IS NULL
								WHERE t2.channels_id = {$channel_id}
								AND t1.status = 1
								AND t1.deleted_at IS NULL
					");
			$result = $query->row();
			if ($result) {
				if ($result->shop_id == '' || $result->shop_id == null) {
					throw new Exception();
				} else {
					return $result;
				}
			} else {
				throw new Exception();
			}
		} catch (Exception $e) {
			return false;
		}
	}
	function get_channel_data_by_shop_id($shop_id, $endpoint_code)
	{
		try {
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
										t5.channel_name,
										t1.source_name
								FROM admins_ms_sources t1
								INNER JOIN users_ms_authenticate_channels t2 
										ON t1.id = t2.sources_id 
										AND t2.status = 1 
										AND t2.deleted_at IS NULL
								INNER JOIN admins_ms_endpoints t3 
										ON t1.id = t3.admins_ms_sources_id
										AND t3.title = '{$endpoint_code}'
										AND t3.status = 1
										AND t3.deleted_at IS NULL
								INNER JOIN admins_ms_company_endpoints t4
										ON t2.users_ms_companys_id = t4.users_ms_companys_id
										AND t3.id = t4.admins_ms_endpoints_id
										AND t4.status = 1 AND t4.deleted_at IS NULL
								INNER JOIN users_ms_channels t5
										ON t2.channels_id = t5.id
								WHERE t2.shop_id = {$shop_id}
								AND t1.status = 1
								AND t1.deleted_at IS NULL
					");
			$result = $query->row();
			if ($result) {
				if ($result->shop_id == '' || $result->shop_id == null) {

					throw new Exception();
				} else {
					return $result;
				}
			} else {
				throw new Exception();
			}
		} catch (Exception $e) {
			return false;
		}
	}
	function get_last_update_orders($company_id, $source_id)
	{
		try {

			$query = $this->db->query("SELECT local_updated_at 
						FROM users_tr_orders 
						WHERE users_ms_companys_id = {$company_id} 
						AND users_ms_channels_id = {$source_id}
						ORDER BY local_updated_at DESC
						LIMIT 1
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
	public function change_status_auth_when_error($auth_id, $update)
	{
		try {
			$auth_change = array('status' => 0, 'message' => $update);
			$this->db->where('id', $auth_id);
			$update = $this->db->update('users_ms_authenticate_channels', $auth_change);
			if (!$update) {
				throw new Exception();
			}
			return true;
		} catch (Exception $e) {
			return false;
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
										t1.status_id
								FROM admins_ms_management_status t1
								WHERE t1.admins_ms_sources_id = {$source_id}
								AND {$where}
								AND t1.status_type = '{$type}'
							");
			$result = $query->row();

			if ($result) {
				return $result->status_id;
			} else {
				throw new Exception();
			}
		} catch (Exception $e) {
			return false;
		}
	}
	function check_order_by_local_id($local_id, $shop_id = null)
	{
		if ($shop_id !== null) {
			$c_shop_id = "AND users_ms_channels_id IN (SELECT channels_id FROM users_ms_authenticate_channels WHERE shop_id = '{$shop_id}')";
		} else {
			$c_shop_id = '';
		}

		try {
			$query = $this->db->query("SELECT 
									error_code,
									users_ms_companys_id,
									users_ms_channels_id,
									id as users_tr_orders_id,
									local_order_id
							   FROM users_tr_orders 
							   WHERE local_order_id = '{$local_id}'
							   $c_shop_id
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
	function check_warehouse_source_address($channel_data, $address_id)
	{
		try {

			$query = $this->db->query("SELECT *
							   FROM users_ms_warehouse_source_address 
							   WHERE users_ms_companys_id = {$channel_data->company_id}
							   AND admins_ms_sources_id = {$channel_data->source_id}
							   AND users_ms_channels_id = {$channel_data->channel_id}
							   AND address_id = {$address_id}
							");
			$result = $query->num_rows();

			if (!$result) {
				throw new Exception();
			}
			return $result;
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
	function insert_order($data)
	{
		$this->db->trans_begin();
		try {

			$check_order = $this->check_order_by_local_id($data['local_order_id']);

			if (!$check_order) {
				$item = $data['item_list'];

				// for ($x = 0; $x < count($item); $x++) {

				// 	if ($item[$x]['error_code'] < 1) {
				// 		$check_stock = $this->check_stock($item[$x], $data['users_ms_warehouses_id']);

				// 		if ($check_stock) {
				// 			if ($check_stock->final_qty < 0) {
				// 				$item[$x]['error_code'] = 2;
				// 				$item[$x]['error_message'] = 'Out of stock';
				// 			} else {
				// 				$item[$x]['error_code'] = 0;
				// 				$item[$x]['error_message'] = 'Success';
				// 			}
				// 		} else {
				// 			$item[$x]['error_code'] = 4;
				// 			$item[$x]['error_message'] = 'The SKU is not listed in inventory';
				// 		}
				// 	}
				// }

				$data['error_code'] = count(array_filter($item, fn ($item) => $item['error_code'] !== 0)) > 0 ? 1 : $data['error_code'];
				$data['error_message'] = $data['error_code'] > 0 ? 'Some items are experiencing issues.' : 'Success';

				unset($data['item_list']);

				$header = $this->db->insert('users_tr_orders', $data);
				if (!$header) {
					throw new Exception('Error insert data header');
				}


				$insert_id = $this->db->insert_id();
				//insert log
				$tr_order_id = function ($item) {
					$insert_id = $this->db->insert_id();
					$item['users_tr_orders_id'] = $insert_id;
					return $item;
				};


				$item = array_map($tr_order_id, $item);

				$detail = $this->db->insert_batch('users_tr_order_details', $item);

				create_log_order($data['users_ms_companys_id'], $insert_id, 1, $data['local_updated_at'], 'API');



				// if ($data['error_code'] < 1) {
				// 	for ($x = 0; $x < count($item); $x++) {

				// 		$check_update_stock = $this->check_update_stock($data['users_ms_channels_id'], $data['trx_number']);
				// 		if ($check_update_stock < 1) {
				// 			$qty_trx = $item[$x]['quantity_purchased'];
				// 		$qty_old = $check_stock->qty;
				// 		$qty_new = $check_stock->final_qty;

				// 		$storage = array(
				// 			'qty' => $qty_new,
				// 			'trx_number' => $trx_number
				// 		);

				// 		$this->db->where('users_ms_companys_id', $check_stock->users_ms_companys_id);
				// 		$this->db->where('users_ms_warehouses_id', $check_stock->users_ms_warehouses_id);
				// 		$this->db->where('sku', $check_stock->sku);
				// 		$update_storage = $this->db->update('users_ms_inventory_storages', $storage);
				// 		if (!$update_storage) {
				// 			throw new Exception('Error update data storage');
				// 		}

				// 		$storage_log = array(
				// 			'users_ms_companys_id' => $check_stock->users_ms_companys_id,
				// 			'trx_number' => $trx_number,
				// 			'trx_type' => 3, //order marketplace
				// 			'sku' => $check_stock->sku,
				// 			'qty_trx' => $qty_trx,
				// 			'qty_old' => $qty_old,
				// 			'qty_new' => $qty_new,
				// 		);

				// 		$insert_storage_log = $this->db->insert('users_ms_inventory_storages_logs', $storage_log);

				// 		if (!$insert_storage_log) {
				// 			throw new Exception('Error insert data storage log');
				// 		}
				// 		}
				// 	}

				// }


				if (!$detail) {
					throw new Exception('Error insert data detail');
				}
			} else {

				if ($data['error_code'] < 1) {
					unset($data['error_code']);
					unset($data['error_message']);
					foreach ($data['item_list'] as $detail_update) {
						unset($detail_update['users_tr_orders_id']);
						$this->db->where('users_ms_companys_id', $check_order->users_ms_companys_id);
						$this->db->where('users_tr_orders_id', $check_order->users_tr_orders_id);
						$this->db->where('local_order_id', $detail_update['local_order_id']);
						$this->db->where('local_item_sku', $detail_update['local_item_sku']);
						$update_order_details = $this->db->update('users_tr_order_details', $detail_update);
						if (!$update_order_details) {
							throw new Exception('Error update data order');
						}
					}
					// $check_order_by_local_id = $this->check_order_by_local_id($check_order->local_order_id);
					// if ($check_order_by_local_id) {
					// 	$check_update_stock = $this->check_update_stock($check_order->ms_customers_id, $check_order_by_local_id->trx_number);
					// 	if ($check_update_stock < 1) {
					// 		$this->update_stock($data, $data['item_list'], $check_order_by_local_id->trx_number);
					// 	}
					// }

					unset($data['item_list']);
					$this->db->where('users_ms_companys_id', $check_order->users_ms_companys_id);
					$this->db->where('users_ms_channels_id', $check_order->users_ms_channels_id);
					$this->db->where('local_order_id', $check_order->local_order_id);
					$update_order = $this->db->update('users_tr_orders', $data);
					if (!$update_order) {
						throw new Exception('Error update data order');
					}
				}
			}


			$this->db->trans_commit();
			return true;
		} catch (Exception $e) {
			$this->db->trans_rollback();
			return false;
		}
	}
}