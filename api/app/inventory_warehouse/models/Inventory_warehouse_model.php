<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Inventory_warehouse_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('api');
		$this->max_data = 100;
		$this->table = 'users_ms_inventory_receiving';
		$this->t_receiving_logs = 'users_ms_inventory_receiving_logs';
		$this->t_putaway = 'users_ms_inventory_putaway';
		$this->t_putaway_logs = 'users_ms_inventory_putaway_logs';
		$this->t_storage = 'users_ms_inventory_storages';
		$this->t_storage_logs = 'users_ms_inventory_storages_logs';
		$this->t_picking = 'users_ms_inventory_picking';
		$this->t_picking_logs = 'users_ms_inventory_picking_logs';
		$this->t_packing = 'users_ms_inventory_packing';
		$this->t_packing_logs = 'users_ms_inventory_packing_logs';
		$this->t_shipping = 'users_ms_inventory_shipping';
		$this->t_shipping_logs = 'users_ms_inventory_shipping_logs';
		$this->t_warehouse = 'users_ms_warehouses';
		$this->t_brand = 'users_ms_brands';
		$this->t_supplier = 'users_ms_suppliers';
		$this->t_product_variant = 'users_ms_product_variants';
	}

	public function receiving_insert($param, $token)
	{
		try {

			if (count($param) > $this->max_data) {
				throw new Exception("Maximum data is " . $this->max_data . "", 400);
			}

			$this->db->trans_start();

			$token_data = get_jwt_data($token);

			foreach ($param as $row) {
				$warehouse_id = isset($row['users_ms_warehouses_id']) ? $row['users_ms_warehouses_id'] : '';
				$brand_name = isset($row['brand_name']) ? $row['brand_name'] : '';
				$supplier_name = isset($row['supplier_name']) ? $row['supplier_name'] : '';

				$po_number = isset($row['po_number']) ? $row['po_number'] : '';
				$cek_po_number = $this->get_id_from_table($this->table, $token_data->company_id, 'po_number', $po_number);
				if ($cek_po_number) {
					throw new Exception('PO Number ' . $po_number . ' Already Exist', 400);
				}

				$data = array();
				$data['users_ms_warehouses_id'] = $warehouse_id;
				$data['po_number'] = $po_number;
				$data['brand_name'] = $brand_name;
				$data['supplier_name'] = $supplier_name;
				$data['publisher_name'] = isset($row['publisher_name']) ? $row['publisher_name'] : '';
				$data['qty'] = isset($row['qty']) ? $row['qty'] : '';
				$data['qty_receiving'] = isset($row['qty_receiving']) ? $row['qty_receiving'] : '';
				$data['status'] = isset($row['status']) ? $row['status'] : '';

				$validation = form_validation($data);

				$data['users_ms_companys_id'] = $token_data->company_id;
				$data['created_by'] 		  = $token_data->company_id;
				$data['updated_by'] 		  = $token_data->company_id;

				if ($validation['error'] === true) {
					throw new Exception($validation['message'], 400);
				}

				$insert = $this->db->insert($this->table, $data);
				if (!$insert) {
					throw new Exception("Internal Server Error", 500);
				}

				$data_logs = array();
				$data_logs['po_number'] = $po_number;
				$data_logs['sku'] = isset($row['sku']) ? $row['sku'] : '';
				$data_logs['qty'] = isset($row['qty']) ? $row['qty'] : '';
				$data_logs['qty_receiving'] = isset($row['qty_receiving']) ? $row['qty_receiving'] : '';

				$validation_logs = form_validation($data_logs);
				if ($validation_logs['error'] === true) {
					throw new Exception($validation_logs['message'], 400);
				}

				$data_logs['users_ms_companys_id'] = $token_data->company_id;
				$data_logs['created_by'] = $token_data->company_id;
				$data_logs['updated_by'] = $token_data->company_id;

				$insert_logs = $this->db->insert($this->t_receiving_logs, $data_logs);
				if (!$insert_logs) {
					throw new Exception("Internal Server Error", 500);
				}
			}

			$this->db->trans_complete();

			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				return FALSE;
			} else {
				return api_response(false, 200, 'OK');
				$this->db->trans_commit();
				return TRUE;
			}
		} catch (Exception $e) {
			$error_message 	= $e->getMessage();
			$error_code 	= $e->getCode();

			$response = api_response(true, $error_code, $error_message);
			return $response;
		}
	}

	public function putaway_insert($param, $token)
	{
		try {

			if (count($param) > $this->max_data) {
				throw new Exception("Maximum data is " . $this->max_data . "", 400);
			}

			$this->db->trans_start();

			$token_data = get_jwt_data($token);

			foreach ($param as $row) {
				$warehouse_id = isset($row['users_ms_warehouses_id']) ? $row['users_ms_warehouses_id'] : null;
				$brand_name = isset($row['brand_name']) ? $row['brand_name'] : '';
				$supplier_name = isset($row['supplier_name']) ? $row['supplier_name'] : '';

				$po_number = isset($row['po_number']) ? $row['po_number'] : '';
				$cek_po_number = $this->get_id_from_table($this->table, $token_data->company_id, 'po_number', $po_number);
				if ($cek_po_number) {
					throw new Exception('PO Number ' . $po_number . ' Already Exist', 400);
				}

				$data = array();
				$data['users_ms_warehouses_id'] = $warehouse_id;
				$data['po_number'] = $po_number;
				$data['brand_name'] = $brand_name;
				$data['supplier_name'] = $supplier_name;
				$data['publisher_name'] = isset($row['publisher_name']) ? $row['publisher_name'] : '';
				$data['qty'] = isset($row['qty']) ? $row['qty'] : 0;
				$data['qty_receiving'] = isset($row['qty_receiving']) ? $row['qty_receiving'] : '';
				$data['qty_putaway'] = isset($row['qty_putaway']) ? $row['qty_putaway'] : '';
				$data['status'] = isset($row['status']) ? $row['status'] : '';

				$validation = form_validation($data);

				$data['users_ms_companys_id'] = $token_data->company_id;
				$data['created_by'] 		  = $token_data->company_id;
				$data['updated_by'] 		  = $token_data->company_id;

				if ($validation['error'] === true) {
					throw new Exception($validation['message'], 400);
				}

				$insert = $this->db->insert($this->t_putaway, $data);
				if (!$insert) {
					throw new Exception("Internal Server Error", 500);
				}

				$data_logs = array();
				$data_logs['po_number'] = $po_number;
				$data_logs['sku'] = isset($row['sku']) ? $row['sku'] : '';
				$data_logs['qty'] = isset($row['qty']) ? $row['qty'] : 0;
				$data_logs['qty_receiving'] = isset($row['qty_receiving']) ? $row['qty_receiving'] : '';
				$data_logs['qty_putaway'] = isset($row['qty_putaway']) ? $row['qty_putaway'] : '';

				$validation_logs = form_validation($data_logs);
				if ($validation_logs['error'] === true) {
					throw new Exception($validation_logs['message'], 400);
				}

				$data_logs['users_ms_companys_id'] = $token_data->company_id;
				$data_logs['created_by'] = $token_data->company_id;
				$data_logs['updated_by'] = $token_data->company_id;

				$insert_logs = $this->db->insert($this->t_putaway_logs, $data_logs);
				if (!$insert_logs) {
					throw new Exception("Internal Server Error", 500);
				}
			}

			$this->db->trans_complete();

			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				return FALSE;
			} else {
				return api_response(false, 200, 'OK');
				$this->db->trans_commit();
				return TRUE;
			}
		} catch (Exception $e) {
			$error_message 	= $e->getMessage();
			$error_code 	= $e->getCode();

			$response = api_response(true, $error_code, $error_message);
			return $response;
		}
	}

	public function storage_insert($param, $token)
	{
		try {

			if (count($param) > $this->max_data) {
				throw new Exception("Maximum data is " . $this->max_data . "", 400);
			}

			$this->db->trans_start();

			$token_data = get_jwt_data($token);

			foreach ($param as $row) {
				$trx_number = isset($row['trx_number']) ? $row['trx_number'] : '';
				$cek_trx_number = $this->get_id_from_table($this->t_storage, $token_data->company_id, 'trx_number', $trx_number);
				if ($cek_trx_number) {
					throw new Exception('Transaction Number ' . $trx_number . ' Already Exist', 400);
				}

				$data = array();
				$data['users_ms_warehouses_id'] = isset($row['users_ms_warehouses_id']) ? $row['users_ms_warehouses_id'] : '';
				$data['users_ms_product_variants_id'] = isset($row['users_ms_product_variants_id']) ? $row['users_ms_product_variants_id'] : '';
				$data['sku'] = isset($row['sku']) ? $row['sku'] : '';
				$data['qty'] = isset($row['qty']) ? $row['qty'] : '';
				$data['trx_number'] = $trx_number;

				$validation = form_validation($data);

				$data['users_ms_companys_id'] = $token_data->company_id;
				$data['created_by'] 		  = $token_data->company_id;
				$data['updated_by'] 		  = $token_data->company_id;

				if ($validation['error'] === true) {
					throw new Exception($validation['message'], 400);
				}

				$insert = $this->db->insert($this->t_storage, $data);
				if (!$insert) {
					throw new Exception("Internal Server Error", 500);
				}

				$data_logs = array();
				$data_logs['trx_number'] = $trx_number;
				$data_logs['trx_type'] = isset($row['trx_type']) ? $row['trx_type'] : '';
				$data_logs['sku'] = isset($row['sku']) ? $row['sku'] : '';
				$data_logs['qty_trx'] = isset($row['qty_trx']) ? $row['qty_trx'] : 0;
				$data_logs['qty_old'] = isset($row['qty_old']) ? $row['qty_old'] : '';
				$data_logs['qty_new'] = isset($row['qty_new']) ? $row['qty_new'] : '';

				$validation_logs = form_validation($data_logs);
				if ($validation_logs['error'] === true) {
					throw new Exception($validation_logs['message'], 400);
				}

				$data_logs['users_ms_companys_id'] = $token_data->company_id;
				$data_logs['created_by'] = $token_data->company_id;
				$data_logs['updated_by'] = $token_data->company_id;

				$insert_logs = $this->db->insert($this->t_storage_logs, $data_logs);
				if (!$insert_logs) {
					throw new Exception("Internal Server Error", 500);
				}
			}

			$this->db->trans_complete();

			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				return FALSE;
			} else {
				return api_response(false, 200, 'OK');
				$this->db->trans_commit();
				return TRUE;
			}
		} catch (Exception $e) {
			$error_message 	= $e->getMessage();
			$error_code 	= $e->getCode();

			$response = api_response(true, $error_code, $error_message);
			return $response;
		}
	}

	public function picking_insert($param, $token)
	{
		try {

			if (count($param) > $this->max_data) {
				throw new Exception("Maximum data is " . $this->max_data . "", 400);
			}

			$this->db->trans_start();

			$token_data = get_jwt_data($token);

			foreach ($param as $row) {
				$purchase_code = isset($row['purchase_code']) ? $row['purchase_code'] : '';
				$check_purchase_code = $this->get_id_from_table($this->t_picking, $token_data->company_id, 'purchase_code', $purchase_code);
				if ($check_purchase_code) {
					throw new Exception('Purchase Code ' . $purchase_code . ' Already Exist', 400);
				}

				$data = array();
				$data['users_ms_warehouses_id'] = isset($row['users_ms_warehouses_id']) ? $row['users_ms_warehouses_id'] : '';
				$data['purchase_code'] = $purchase_code;
				$data['customer_name'] = isset($row['customer_name']) ? $row['customer_name'] : '';
				$data['customer_email'] = isset($row['customer_email']) ? $row['customer_email'] : '';
				$data['qty'] = isset($row['qty']) ? $row['qty'] : '';
				$data['qty_picking'] = isset($row['qty_picking']) ? $row['qty_picking'] : '';
				$data['assignee'] = isset($row['assignee']) ? $row['assignee'] : '';
				$data['status'] = isset($row['status']) ? $row['status'] : '';

				$validation = form_validation($data);

				$data['users_ms_companys_id'] = $token_data->company_id;
				$data['created_by'] 		  = $token_data->company_id;
				$data['updated_by'] 		  = $token_data->company_id;

				if ($validation['error'] === true) {
					throw new Exception($validation['message'], 400);
				}

				$insert = $this->db->insert($this->t_picking, $data);
				if (!$insert) {
					throw new Exception("Internal Server Error", 500);
				}

				$data_logs = array();
				$data_logs['purchase_code'] = $purchase_code;
				$data_logs['sku'] = isset($row['sku']) ? $row['sku'] : '';
				$data_logs['qty'] = isset($row['qty']) ? $row['qty'] : 0;
				$data_logs['qty_picking'] = isset($row['qty_picking']) ? $row['qty_picking'] : '';

				$validation_logs = form_validation($data_logs);
				if ($validation_logs['error'] === true) {
					throw new Exception($validation_logs['message'], 400);
				}

				$data_logs['users_ms_companys_id'] = $token_data->company_id;
				$data_logs['created_by'] = $token_data->company_id;
				$data_logs['updated_by'] = $token_data->company_id;

				$insert_logs = $this->db->insert($this->t_picking_logs, $data_logs);
				if (!$insert_logs) {
					throw new Exception("Internal Server Error", 500);
				}
			}

			$this->db->trans_complete();

			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				return FALSE;
			} else {
				return api_response(false, 200, 'OK');
				$this->db->trans_commit();
				return TRUE;
			}
		} catch (Exception $e) {
			$error_message 	= $e->getMessage();
			$error_code 	= $e->getCode();

			$response = api_response(true, $error_code, $error_message);
			return $response;
		}
	}

	public function packing_insert($param, $token)
	{
		try {

			if (count($param) > $this->max_data) {
				throw new Exception("Maximum data is " . $this->max_data . "", 400);
			}

			$this->db->trans_start();

			$token_data = get_jwt_data($token);

			foreach ($param as $row) {
				$purchase_code = isset($row['purchase_code']) ? $row['purchase_code'] : '';
				$check_purchase_code = $this->get_id_from_table($this->t_packing, $token_data->company_id, 'purchase_code', $purchase_code);
				if ($check_purchase_code) {
					throw new Exception('Purchase Code ' . $purchase_code . ' Already Exist', 400);
				}

				$data = array();
				$data['users_ms_warehouses_id'] = isset($row['users_ms_warehouses_id']) ? $row['users_ms_warehouses_id'] : '';
				$data['purchase_code'] = $purchase_code;
				$data['customer_name'] = isset($row['customer_name']) ? $row['customer_name'] : '';
				$data['customer_email'] = isset($row['customer_email']) ? $row['customer_email'] : '';
				$data['qty'] = isset($row['qty']) ? $row['qty'] : '';
				$data['qty_packing'] = isset($row['qty_packing']) ? $row['qty_packing'] : '';
				$data['assignee'] = isset($row['assignee']) ? $row['assignee'] : '';
				$data['status'] = isset($row['status']) ? $row['status'] : '';

				$validation = form_validation($data);

				$data['users_ms_companys_id'] = $token_data->company_id;
				$data['created_by'] 		  = $token_data->company_id;
				$data['updated_by'] 		  = $token_data->company_id;

				if ($validation['error'] === true) {
					throw new Exception($validation['message'], 400);
				}

				$insert = $this->db->insert($this->t_packing, $data);
				if (!$insert) {
					throw new Exception("Internal Server Error", 500);
				}

				$data_logs = array();
				$data_logs['purchase_code'] = $purchase_code;
				$data_logs['sku'] = isset($row['sku']) ? $row['sku'] : '';
				$data_logs['qty'] = isset($row['qty']) ? $row['qty'] : 0;
				$data_logs['qty_packing'] = isset($row['qty_packing']) ? $row['qty_packing'] : '';

				$validation_logs = form_validation($data_logs);
				if ($validation_logs['error'] === true) {
					throw new Exception($validation_logs['message'], 400);
				}

				$data_logs['users_ms_companys_id'] = $token_data->company_id;
				$data_logs['created_by'] = $token_data->company_id;
				$data_logs['updated_by'] = $token_data->company_id;

				$insert_logs = $this->db->insert($this->t_packing_logs, $data_logs);
				if (!$insert_logs) {
					throw new Exception("Internal Server Error", 500);
				}
			}

			$this->db->trans_complete();

			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				return FALSE;
			} else {
				return api_response(false, 200, 'OK');
				$this->db->trans_commit();
				return TRUE;
			}
		} catch (Exception $e) {
			$error_message 	= $e->getMessage();
			$error_code 	= $e->getCode();

			$response = api_response(true, $error_code, $error_message);
			return $response;
		}
	}

	public function shipping_insert($param, $token)
	{
		try {

			if (count($param) > $this->max_data) {
				throw new Exception("Maximum data is " . $this->max_data . "", 400);
			}

			$this->db->trans_start();

			$token_data = get_jwt_data($token);

			foreach ($param as $row) {
				$purchase_code = isset($row['purchase_code']) ? $row['purchase_code'] : '';
				$check_purchase_code = $this->get_id_from_table($this->t_shipping, $token_data->company_id, 'purchase_code', $purchase_code);
				if ($check_purchase_code) {
					throw new Exception('Purchase Code ' . $purchase_code . ' Already Exist', 400);
				}

				$data = array();
				$data['users_ms_warehouses_id'] = isset($row['users_ms_warehouses_id']) ? $row['users_ms_warehouses_id'] : '';
				$data['purchase_code'] = $purchase_code;
				$data['customer_name'] = isset($row['customer_name']) ? $row['customer_name'] : '';
				$data['customer_email'] = isset($row['customer_email']) ? $row['customer_email'] : '';
				$data['qty'] = isset($row['qty']) ? $row['qty'] : '';
				$data['qty_shipping'] = isset($row['qty_shipping']) ? $row['qty_shipping'] : '';
				$data['assignee'] = isset($row['assignee']) ? $row['assignee'] : '';
				$data['status'] = isset($row['status']) ? $row['status'] : '';

				$validation = form_validation($data);

				$data['users_ms_companys_id'] = $token_data->company_id;
				$data['created_by'] 		  = $token_data->company_id;
				$data['updated_by'] 		  = $token_data->company_id;

				if ($validation['error'] === true) {
					throw new Exception($validation['message'], 400);
				}

				$insert = $this->db->insert($this->t_shipping, $data);
				if (!$insert) {
					throw new Exception("Internal Server Error", 500);
				}

				$data_logs = array();
				$data_logs['purchase_code'] = $purchase_code;
				$data_logs['sku'] = isset($row['sku']) ? $row['sku'] : '';
				$data_logs['qty'] = isset($row['qty']) ? $row['qty'] : 0;
				$data_logs['qty_shipping'] = isset($row['qty_shipping']) ? $row['qty_shipping'] : '';

				$validation_logs = form_validation($data_logs);
				if ($validation_logs['error'] === true) {
					throw new Exception($validation_logs['message'], 400);
				}

				$data_logs['users_ms_companys_id'] = $token_data->company_id;
				$data_logs['created_by'] = $token_data->company_id;
				$data_logs['updated_by'] = $token_data->company_id;

				$insert_logs = $this->db->insert($this->t_shipping_logs, $data_logs);
				if (!$insert_logs) {
					throw new Exception("Internal Server Error", 500);
				}
			}

			$this->db->trans_complete();

			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				return FALSE;
			} else {
				return api_response(false, 200, 'OK');
				$this->db->trans_commit();
				return TRUE;
			}
		} catch (Exception $e) {
			$error_message 	= $e->getMessage();
			$error_code 	= $e->getCode();

			$response = api_response(true, $error_code, $error_message);
			return $response;
		}
	}

	function get_id_from_table($table, $company_id, $field, $value)
	{
		$this->db->where($field, $value);
		$this->db->where('users_ms_companys_id', $company_id);
		$query = $this->db->get($table);
		$result = $query->row();


		if ($result) {
			return $result->id;
		} else {
			return false;
		}
	}

	function get_last_update_from_table($table, $token)
	{
		try {
			$token_data = get_jwt_data($token);
			$this->db->select('updated_at');
			$this->db->where('users_ms_companys_id', $token_data->company_id);
			$this->db->order_by('updated_at desc');
			$this->db->limit(1);
			$query = $this->db->get($table);
			$result = $query->row_array();
			if (!$result) {
				throw new Exception('Data Not Found', 404);
			}
			return api_response(false, 200, 'OK', $result);
		} catch (Exception $e) {
			$error_message 	= $e->getMessage();
			$error_code 	= $e->getCode();
			$response = api_response(true, $error_code, $error_message);
			return $response;
		}
	}
}
