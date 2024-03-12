<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Process_orders
{

	protected $ci;
	protected $db;
	protected $helper;

	public function __construct()
	{
		$this->ci = &get_instance();
		$this->db = $this->ci->db;
		$this->ci->load->library(array('integrations/helper'));
		$this->helper = $this->ci->helper;
	}


	function insert_order($data)
	{
		$this->db->trans_begin();
		try {

			$check_order = $this->helper->check_order_by_local_id($data['local_order_id']);

			if (!$check_order) {
				$item = $data['item_list'];

				// for ($x = 0; $x < count($item); $x++) {

				// 	if ($item[$x]['error_code'] < 1) {
				// 		$check_stock = $this->helper->check_stock($item[$x], $data['users_ms_warehouses_id']);

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

				$data['error_code'] = count(array_filter($item, fn($item) => $item['error_code'] !== 0)) > 0 ? 1 : $data['error_code'];
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
