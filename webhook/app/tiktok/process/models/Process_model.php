<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Process_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set('UTC');

		$this->_orders = 'users_tr_orders';
		$this->_order_details = 'users_tr_order_details';
		$this->_inventory_storages = 'users_ms_inventory_storages';
		$this->_inventory_storages_logs = 'users_ms_inventory_storages_logs';
	}

	public function process_order($data)
	{
		$this->db->trans_begin();
		try {

			$check_order = $this->check_order_by_local_id($data['local_order_id']);

			if (!$check_order) {
				$trx_number = mkautono_no_auth($this->_orders, 'trx_number', 'SO', $data['users_ms_companys_id']);
				$data['trx_number'] = $trx_number;
				$item = $data['item_list'];

				for ($x = 0; $x < count($item); $x++) {
					if ($item[$x]['error_code'] < 1) {
						$check_stock = $this->check_stock($item[$x], $data['users_ms_warehouses_id']);
						if ($check_stock) {
							if ($check_stock->final_qty < 0) {
								$item[$x]['error_code'] = 2;
								$item[$x]['error_message'] = 'Out of stock';
							} else {
								$item[$x]['error_code'] = 0;
								$item[$x]['error_message'] = 'Success';
							}
						} else {
							$item[$x]['error_code'] = 4;
							$item[$x]['error_message'] = 'The SKU is not listed in inventory';
						}
					}
				}

				$data['error_code'] = count(array_filter($item, fn ($item) => $item['error_code'] !== 0)) > 0 ? 1 : $data['error_code'];
				$data['error_message'] = $data['error_code'] > 0 ? 'Some items are experiencing issues.' : 'Success';

				unset($data['item_list']);
				$header = $this->db->insert($this->_orders, $data);
				if (!$header) {
					throw new Exception('Error insert data header');
				}

				$tr_order_id = function ($item) {
					$insert_id = $this->db->insert_id();
					$item['users_tr_orders_id'] = $insert_id;
					return $item;
				};

				$item = array_map($tr_order_id, $item);

				$detail = $this->db->insert_batch($this->_order_details, $item);

				if (!$detail) {
					throw new Exception('Error insert data detail');
				}

				if ($data['error_code'] < 1) {
					for ($x = 0; $x < count($item); $x++) {
						$check_stock = $this->check_stock($item[$x], $data['users_ms_warehouses_id']);

						$qty_trx = $item[$x]['quantity_purchased'];
						$qty_old = $check_stock->qty;
						$qty_new = $check_stock->final_qty;

						$storage = array(
							'qty' => $qty_new,
							'trx_number' => $trx_number
						);

						$this->db->where('users_ms_companys_id', $check_stock->users_ms_companys_id);
						$this->db->where('users_ms_warehouses_id', $check_stock->users_ms_warehouses_id);
						$this->db->where('sku', $check_stock->sku);
						$update_storage = $this->db->update($this->_inventory_storages, $storage);
						if (!$update_storage) {
							throw new Exception('Error update data storage');
						}

						$storage_log = array(
							'users_ms_companys_id' => $check_stock->users_ms_companys_id,
							'trx_number' => $trx_number,
							'trx_type' => 3, //order marketplace
							'sku' => $check_stock->sku,
							'qty_trx' => $qty_trx,
							'qty_old' => $qty_old,
							'qty_new' => $qty_new,
						);

						$insert_storage_log = $this->db->insert($this->_inventory_storages_logs, $storage_log);

						if (!$insert_storage_log) {
							throw new Exception('Error insert data storage log');
						}
					}
				}
			} else {
				if ($check_order->error_code < 1) {
					unset($data['trx_number']);
					unset($data['error_code']);
					unset($data['error_message']);
					unset($data['item_list']);

					$this->db->where('users_ms_companys_id', $check_order->users_ms_companys_id);
					$this->db->where('users_ms_channels_id', $check_order->users_ms_channels_id);
					$this->db->where('local_order_id', $check_order->local_order_id);
					$update_order = $this->db->update($this->_orders, $data);
					if (!$update_order) {
						throw new Exception('Error update data order');
					}
				}
			}


			// echo '<pre>';
			// print_r($a);
			// die;

			$this->db->trans_commit();
			return true;
		} catch (Exception $e) {
			$this->db->trans_rollback();
			return false;
		}
	}

	public function check_order_by_local_id($local_id)
	{
		$query = $this->db->query("SELECT 
										error_code,
										users_ms_companys_id,
										users_ms_channels_id,
										local_order_id
								   FROM users_tr_orders 
								   WHERE local_order_id = {$local_id}
								");
		return $query->row();
	}

	public function check_stock($item, $warehouse_id)
	{
		$query = $this->db->query("SELECT *,(qty - {$item['quantity_purchased']}) as final_qty
										FROM {$this->_inventory_storages} t1
										WHERE t1.sku = '{$item['product_sku']}'
										AND t1.users_ms_companys_id = {$item['users_ms_companys_id']}
										AND t1.users_ms_warehouses_id = {$warehouse_id}
									");

		$result = $query->row();

		return $result;
	}

	public function get_channel_data_by_shop_id($shop_id, $endpoint_code)
	{
		$query = $this->db->query("SELECT 
										t1.users_ms_companys_id,
										t1.id as channel_id,
										t1.channel_name,
										t2.id as source_id,
										t2.source_name,
										t2.source_url,
										t2.app_keys,
										t2.secret_keys,
										t3.shop_id,
										t3.access_token,
										t4.endpoint_url
								   FROM users_ms_channels t1
								   INNER JOIN admins_ms_sources t2 ON t1.admins_ms_sources_id = t2.id AND t2.status = 1 AND t2.deleted_at IS NULL
								   INNER JOIN users_ms_authenticate_channels t3 ON t3.sources_id = t2.id AND t3.channels_id = t1.id AND t3.status = 1 AND t3.deleted_at IS NULL
								   INNER JOIN admins_ms_endpoints t4 ON t4.admins_ms_sources_id = t2.id AND t4.endpoint_code = '{$endpoint_code}' AND t4.status = 1 AND t4.deleted_at IS NULL
								   INNER JOIN admins_ms_company_endpoints t5 ON t5.admins_ms_endpoints_id = t4.id AND t5.users_ms_companys_id = t1.users_ms_companys_id AND t5.status = 1 AND t5.deleted_at IS NULL
								   WHERE t3.shop_id = {$shop_id}
								   AND t1.status = 1
								   AND t1.deleted_at IS NULL
								    ");
		return $query->row();
	}

	public function get_product_by_sku($company_id, $sku)
	{
		$query = $this->db->query("SELECT 
										t1.id product_id,
										t1.product_name,
										t2.id variant_id,
										t2.sku
								   FROM users_ms_products t1
								   INNER JOIN users_ms_product_variants t2 ON t2.users_ms_products_id = t1.id AND t2.users_ms_companys_id = {$company_id} AND t2.deleted_at IS NULL
								   WHERE t1.users_ms_companys_id = {$company_id}
								   AND t1.deleted_at IS NULL
								   AND t2.sku = '{$sku}'
								    ");
		return $query->row();
	}

	public function get_management_status($source_id, $status_id, $status_name, $type)
	{
		if ($status_id !== NULL) {
			$where = 't1.source_status_code = ' . $status_id;
		} elseif ($status_name !== NULL) {
			$where = "t1.source_status_name = '" . $status_name . "'";
		} else {
			return false;
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
			return false;
		}
	}
}
