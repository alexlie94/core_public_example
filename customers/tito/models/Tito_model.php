<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Tito_model extends MY_ModelCustomer
{
	use MY_Tables;

	public function __construct()
	{
		$this->_tabel = $this->_table_users_ms_tito;
		parent::__construct();
		$this->load->helper('metronic');
	}

	public function getStatus()
	{
		try {
			$this->db->select('*');
			$this->db->from("{$this->_table_ms_lookup_values}");
			$this->db->where('deleted_at IS NULL');
			$this->db->where('lookup_config', 'tito_status');
			return $this->db->get()->result_array();
		} catch (Exception $e) {
			return $e->getMessage();
		}
	}

	public function getWarehouse()
	{
		try {
			$this->db->select('*');
			$this->db->from("{$this->_table_ms_master_warehouse}");
			$this->db->where('deleted_at IS NULL');
			$this->db->where(["{$this->_table_users_ms_companys}_id" => $this->_users_ms_companys_id]);
			return $this->db->get()->result_array();
		} catch (Exception $e) {
			return $e->getMessage();
		}
	}

	public function getWarehouseCustom($id)
	{
		try {
			$get = $this->get(['id' => $id]);

			$this->db->select('*');
			$this->db->from("{$this->_table_ms_master_warehouse}");
			$this->db->where('deleted_at IS NULL');
			$this->db->where(["{$this->_table_users_ms_companys}_id" => $this->_users_ms_companys_id]);
			$this->db->where("id != '{$get->from_users_ms_warehouses_id}'");
			return $this->db->get()->result_array();
		} catch (Exception $e) {
			return $e->getMessage();
		}
	}

	public function getWarehouseById($id)
	{
		try {
			$this->db->select('*');
			$this->db->from("{$this->_table_ms_master_warehouse}");
			$this->db->where('deleted_at IS NULL');
			$this->db->where(["{$this->_table_users_ms_companys}_id" => $this->_users_ms_companys_id]);
			$this->db->where("id", $id);
			return $this->db->get()->row_array();
		} catch (Exception $e) {
			return $e->getMessage();
		}
	}

	public function getWarehouseId($id)
	{
		try {
			$this->db->select('*');
			$this->db->from("{$this->_table_ms_master_warehouse}");
			$this->db->where('deleted_at IS NULL');
			$this->db->where(["{$this->_table_users_ms_companys}_id" => $this->_users_ms_companys_id]);
			$this->db->where("id != {$id}");
			return $this->db->get()->result();
		} catch (Exception $e) {
			return $e->getMessage();
		}
	}

	public function getTitoById($id, $detail_id)
	{
		try {
			$this->db->select('*');
			$this->db->from("{$this->_table_users_ms_tito_details} a");
			$this->db->join("{$this->_table_users_ms_tito} b", "b.id = a.users_ms_tito_id", "LEFT");
			$this->db->where('a.deleted_at IS NULL');
			$this->db->where('b.deleted_at IS NULL');
			$this->db->where(["b.{$this->_table_users_ms_companys}_id" => $this->_users_ms_companys_id]);
			$this->db->where('a.id', $detail_id);
			$this->db->where('a.users_ms_tito_id', $id);
			return $this->db->get()->row_array();
		} catch (Exception $e) {
			return $e->getMessage();
		}
	}

	public function getTitoDetailsById($id)
	{
		try {
			$this->db->select('
            b.id,
            b.users_ms_inventory_storages_id,
            b.sku,
            b.product_name,
            b.brand_name,
            b.warehouse_name,
            b.qty,
            c.qty as qty_inv_storage,
            b.qty_received,
            b.qty_lost,
            b.description
            ');
			$this->db->from("{$this->_table_users_ms_tito} a");
			$this->db->join("{$this->_table_users_ms_tito_details} b", "b.users_ms_tito_id = a.id", "LEFT");
			$this->db->join("{$this->_table_users_ms_inventory_storages} c", "c.id = b.users_ms_inventory_storages_id", "LEFT");
			$this->db->where('a.deleted_at IS NULL');
			$this->db->where('b.deleted_at IS NULL');
			$this->db->where(["a.{$this->_table_users_ms_companys}_id" => $this->_users_ms_companys_id]);
			$this->db->where('b.users_ms_tito_id', $id);
			$this->db->order_by('b.id');
			return $this->db->get()->result_array();
		} catch (Exception $e) {
			return $e->getMessage();
		}
	}

	public function show()
	{
		$this->datatables->select(
			"a.id,
            a.to_number,
            a.ti_number,
            a.created_at,
            a.qty,
            a.qty_received,
            a.assignee,
            a.status",
			false
		);

		$this->datatables->from("{$this->_tabel} a");
		$this->datatables->where(["a.{$this->_table_users_ms_companys}_id" => $this->_users_ms_companys_id]);
		$this->datatables->where('a.deleted_at IS NULL');
		$button = "<button type=\"button\" class=\"btn btn-outline btn-outline-dashed btn-outline-warning btn-active-light-warning hover-scale btn-sm fw-bold me-2 mb-2 btnEdit \" data-status=\"$2\" data-url=\"" . base_url("tito/update/$1") . "\" data-id =\"$1\" data-fullscreenmodal=\"1\" data-type=\"modal\" {{disabled}}><i class=\"bi bi-pencil-square fs-4 me-2\"></i>Edit</button>";
		$button .= "<button class=\"btn btn-outline btn-outline-dashed btn-outline-info btn-active-light-info hover-scale btn-sm fw-bold me-2 mb-2 btnSend\" data-status=\"$2\" data-url=\"" . base_url("tito/send/$1") . "\" data-id =\"$1\" data-fullscreenmodal=\"1\" data-type=\"modal\" {{disabledSend}}><i class=\"bi bi-send fs-4 me-2\"></i>Send</button>";
		$button .= "<button type=\"button\" id=\"btnPreview\" class=\"btn btn-outline btn-outline-dashed btn-outline-dark btn-active-light-dark hover-scale btn-sm fw-bold me-2 mb-2 btnPreview\" data-status=\"$2\" data-url=\"" . base_url("tito/preview_tito/$1") . "\" data-id =\"$1\" data-fullscreenmodal=\"1\" data-type=\"modal\" {{hiddenView}}><i class=\"bi bi-eye-fill fs-4 me-2\"></i>View</button>";
		$this->datatables->add_column('action', $button, "id,status");

		$fieldSearch = [
			"a.to_number",
			"a.ti_number",
			"a.created_at",
			"a.qty",
			"a.qty_received",
			"a.assignee",
			"a.status",
		];
		$this->_searchDefaultDatatables($fieldSearch);

		$filters = !empty($this->input->post('filters')) ? $this->input->post('filters') : false;
		if ($filters !== false && is_array($filters)) {
			$searchX = [];
			foreach ($filters as $ky => $val) {
				$value = $val['value'];
				if (!empty($value)) {
					switch ($val['name']) {
						case 'status':
							if ($value != "") {
								$this->datatables->where('a.status', $value);
							}
							break;
						case 'search_by':
							$field = $value;
							$searchX['field'] = isset($field) ? $field : "";
							break;
						case 'search_by1':
							$field = $value;
							$searchX['value'] = isset($field) ? $field : "";
							break;
						case 'searchDate':
							$field = $value;
							$searchX['value'] = isset($field) ? $field : "";
							break;
					}
				}
			}
			if (!empty($searchX)) {
				if (isset($searchX['value'])) {
					switch ($searchX['field']) {
						case 'id':
							$this->datatables->where("a.{$searchX['field']} = '{$searchX['value']}'");
							break;
						case 'ti_number':
							$this->datatables->where("a.{$searchX['field']} = '{$searchX['value']}'");
							break;
						case 'start_date':
							$dateValue = explode("to", $searchX['value']);
							$fromDate = $dateValue[0];
							$toDate = $dateValue[1];
							$this->datatables->where("a.{$searchX['field']} BETWEEN '{$fromDate}' AND '{$toDate}'");
							break;
						case 'end_date':
							$dateValue = explode("to", $searchX['value']);
							$fromDate = $dateValue[0];
							$toDate = $dateValue[1];
							$this->datatables->where("a.{$searchX['field']} BETWEEN '{$fromDate}' AND '{$toDate}'");
							break;

						default:
							if (!empty($searchX['field'])) {
								$this->datatables->where("a.{$searchX['field']} LIKE '%{$searchX['value']}%'");
							}
							break;
					}
				}
			}
		}

		//untuk export

		if (isset($_GET['searchby'])) {
			$search_by = $_GET['searchby'];
			if (isset($_GET['searchby1'])) {
				$search_by1 = $_GET['searchby1'];
				if ($search_by != "" && $search_by1 != "") {
					$this->datatables->where("a.{$search_by}= '{$search_by1}'");
				}
			}
			if (isset($_GET['status'])) {
				$batchLocation = $_GET['status'];
				if ($search_by != "" && $batchLocation != "") {
					$this->datatables->where("a.{$search_by}= '{$batchLocation}'");
				}
			}
			if (isset($_GET['from']) && isset($_GET['to'])) {
				$from = $_GET['from'];
				$to = $_GET['to'];
				if ($_GET['searchby'] == "start_date" && $from != "" && $to != "") {
					$this->datatables->where("a.start_date BETWEEN '{$from}' AND '{$to}'");
				}
				if ($_GET['searchby'] == "end_date" && $from != "" && $to != "") {
					$this->datatables->where("a.end_date BETWEEN '{$from}' AND '{$to}'");
				}
			}
		}

		return $this->datatables->generate();
	}

	public function _validate()
	{
		$response = array('success' => false, 'validate' => true, 'messages' => []);
		$response['type'] = !empty($this->input->post('id')) ? 'update' : 'insert';

		$role = array('trim', 'required', 'xss_clean');

		$this->form_validation->set_rules('from_warehouse', 'From', $role);
		$this->form_validation->set_rules('to_warehouse', 'To', $role);
		$this->form_validation->set_rules('assignee', 'Assignee', $role);

		$this->form_validation->set_error_delimiters('<div class="' . VALIDATION_MESSAGE_FORM . '">', '</div>');

		if ($this->form_validation->run() === false) {
			$response['validate'] = false;
			foreach ($this->input->post() as $key => $value) {
				$response['messages'][$key] = form_error($key);
			}
		}

		return $response;
	}

	public function save()
	{
		$this->db->trans_begin();

		try {

			$response = self::_validate();

			if (!$response['validate']) {
				throw new Exception("Error Processing Request", 1);
			}

			$id = clearInput($this->input->post('id'));
			$from_warehouse = clearInput($this->input->post('from_warehouse'));
			$to_warehouse = clearInput($this->input->post('to_warehouse'));
			$assignee = clearInput($this->input->post('assignee'));
			$desc = clearInput($this->input->post('desc'));

			$data_array = array(
				'from_users_ms_warehouses_id' => $from_warehouse,
				'to_users_ms_warehouses_id' => $to_warehouse,
				'to_number' => mkautono($this->_tabel, 'to_number', 'TO'),
				'ti_number' => '',
				'assignee' => $assignee,
				'description' => $desc,
			);

			if (empty($id)) {

				$process = $this->insert($data_array);

				if (!$process) {
					$response['messages'] = 'Failed Insert Data Tito';
					throw new Exception;
				}

				$product_id = $this->input->post('product_id');

				for ($yu = 0; $yu < count($product_id); $yu++) {
					$detail_id = $this->input->post('detail_id')[$yu];
					$inv_storage_id = $this->input->post('product_id')[$yu];
					$product_sku = $this->input->post('product_sku')[$yu];
					$product_name = $this->input->post('product_name')[$yu];
					$brand_name = $this->input->post('brand_name')[$yu];
					$warehouse_name = $this->input->post('warehouse_name')[$yu];
					$qty_sku = $this->input->post('qty_sku')[$yu];
					$cek_detail_id = $this->getTitoById($id, $detail_id);

					$data_array_detail = [
						'users_ms_tito_id' => $process,
						'users_ms_inventory_storages_id' => $inv_storage_id,
						'sku' => $product_sku,
						'product_name' => $product_name,
						'brand_name' => $brand_name,
						'warehouse_name' => $warehouse_name,
						'qty' => $qty_sku,
					];
					$this->insertCustomWithoutCompany($data_array_detail, $this->_table_users_ms_tito_details);
				}

				$this->db->select("SUM(qty) as quantity");
				$this->db->from("{$this->_table_users_ms_tito_details}");
				$this->db->where('deleted_at IS NULL', null, false);
				$this->db->where('users_ms_tito_id', $process);
				$data_check = $this->db->get()->row_array();

				$data_update = [
					'qty' => $data_check['quantity']
				];

				$this->db->where('id', $process);
				$this->db->update($this->_tabel, $data_update);

				$response['messages'] = "Successfully Insert Data Tito";
			} else {
				$data = $this->get(array('id' => $id));

				if (!$data) {
					$response['messages'] = 'Data update invalid';
					throw new Exception;
				}

				$tito_detail = $this->getTitoDetailsById($id);

				$product_id = $this->input->post('product_id');
				$tito_detail_id = [];
				for ($yu = 0; $yu < count($product_id); $yu++) {
					$detail_id = $this->input->post('detail_id')[$yu];
					$inv_storage_id = $this->input->post('product_id')[$yu];
					$product_sku = $this->input->post('product_sku')[$yu];
					$product_name = $this->input->post('product_name')[$yu];
					$brand_name = $this->input->post('brand_name')[$yu];
					$qty_sku = $this->input->post('qty_sku')[$yu];
					$cek_detail_id = $this->getTitoById($id, $detail_id);

					if (empty($cek_detail_id)) {
						$data_array_detail = [
							'users_ms_tito_id' => $id,
							'users_ms_inventory_storages_id' => $inv_storage_id,
							'sku' => $product_sku,
							'product_name' => $product_name,
							'brand_name' => $brand_name,
							'qty' => $qty_sku,
						];
						$this->insertCustomWithoutCompany($data_array_detail, $this->_table_users_ms_tito_details);
					} else {
						$tito_detail_id[] = $detail_id = $this->input->post('detail_id')[$yu];
						$data_update = [
							'qty' => $qty_sku,
						];
						$this->db->where('id', $detail_id);
						$this->db->update($this->_table_users_ms_tito_details, $data_update);
					}
				}

				if (count($tito_detail) > 0) {
					foreach ($tito_detail as $data => $nilai) {
						$cari = array_search($nilai['id'], $tito_detail_id);
						if ($cari === false) {
							$delete_array = array(
								'deleted_at' => date('Y-m-d H:i:s')
							);
							$this->db->where('id', $nilai['id']);
							$this->db->where('users_ms_tito_id', $id);
							$this->db->update($this->_table_users_ms_tito_details, $delete_array);
						}
					}
				}

				$process = $this->update(array('id' => $id), $data_array);

				if (!$process) {
					$response['messages'] = 'Failed update data user';
					throw new Exception;
				}

				$this->db->select("SUM(qty) as quantity");
				$this->db->from("{$this->_table_users_ms_tito_details}");
				$this->db->where('deleted_at IS NULL', null, false);
				$this->db->where('users_ms_tito_id', $id);
				$data_check = $this->db->get()->row_array();

				$data_update = [
					'qty' => $data_check['quantity']
				];

				$this->db->where('id', $id);
				$this->db->update($this->_tabel, $data_update);

				$response['messages'] = 'Successfully Update Data Transfer Out';
			}

			$this->db->trans_commit();
			$response['success'] = true;
			return $response;
		} catch (Exception $e) {
			$this->db->trans_rollback();
			return $response;
		}
	}

	public function getItems($id)
	{
		try {
			$get = $this->get(['id' => $id]);
			$get_warehouse_from = $this->db->get_where($this->_table_ms_master_warehouse, array('id' => $get->from_users_ms_warehouses_id))->row_array();
			$get_warehouse_to = $this->db->get_where($this->_table_ms_master_warehouse, array('id' => $get->to_users_ms_warehouses_id))->row_array();
			$get_status = $this->db->get_where($this->_table_ms_lookup_values, array('lookup_config' => 'tito_status', 'lookup_code' => $get->status))->row_array();

			$table = [
				'id' => $get->id,
				'to_number' => $get->to_number,
				'from_users_ms_warehouses_id' => $get_warehouse_from['warehouse_name'],
				'to_users_ms_warehouses_id' => $get_warehouse_to['warehouse_name'],
				'assignee' => $get->assignee,
				'description' => $get->description,
				'status' => $get_status['lookup_name'],
			];

			return $table;
		} catch (Exception $e) {
			return $e->getMessage();
		}
	}

	public function proses_delet_data($id)
	{
		$this->db->trans_begin();
		try {
			if ($id == null) {
				throw new Exception("Failed delete item", 1);
			}

			$get = $this->get(array('id' => $id));

			if (!$get) {
				throw new Exception("Failed delete item", 1);
			}

			$delete_array = array(
				'deleted_at' => date('Y-m-d H:i:s')
			);
			$this->db->where('users_ms_tito_id', $id);
			$this->db->update($this->_table_users_ms_tito_details, $delete_array);
			$softDelete = $this->softDelete($id);

			if (!$softDelete) {
				throw new Exception("Failed delete item", 1);
			}

			$this->db->trans_commit();
			$output =
				[
					"error" => false,
				];

			return $output;
		} catch (Exception $e) {
			$this->db->trans_rollback();
			return $e->getMessage();
		}
	}

	public function _getProduct($id)
	{
		$this->db->select(
			'a.id,
            b.sku,
            c.product_name,
            c.brand_name,
            d.warehouse_name,
            a.qty'
		);
		$this->db->from("{$this->_table_users_ms_inventory_storages} a");
		$this->db->join("{$this->_table_products_variants} b", 'b.id = a.users_ms_product_variants_id', 'LEFT');
		$this->db->join("{$this->_table_products} c", 'c.id = b.users_ms_products_id', 'LEFT');
		$this->db->join("{$this->_table_ms_master_warehouse} d", 'd.id = a.	users_ms_warehouses_id', 'LEFT');
		$this->db->where('a.deleted_at is null', null, false);
		$this->db->where('a.users_ms_companys_id', $this->_users_ms_companys_id);
		$this->db->where('a.users_ms_warehouses_id', $id);
		$this->db->order_by('a.updated_at', 'DESC');

		$query = $this->db->get();

		return $query;
	}

	public function _getProductBySKU($sku)
	{
		$this->db->select(
			'a.id,
            a.users_ms_warehouses_id,
            b.sku,
            c.product_name,
            c.brand_name,
            d.warehouse_name,
            a.qty'
		);
		$this->db->from("{$this->_table_users_ms_inventory_storages} a");
		$this->db->join("{$this->_table_products_variants} b", 'b.id = a.users_ms_product_variants_id', 'LEFT');
		$this->db->join("{$this->_table_products} c", 'c.id = b.users_ms_products_id', 'LEFT');
		$this->db->join("{$this->_table_ms_master_warehouse} d", 'd.id = a.	users_ms_warehouses_id', 'LEFT');
		$this->db->where('a.deleted_at is null', null, false);
		$this->db->where('a.users_ms_companys_id', $this->_users_ms_companys_id);
		$this->db->where('a.sku', $sku);
		$this->db->order_by('a.updated_at', 'DESC');

		$query = $this->db->get()->row_array();

		return $query;
	}

	public function show_products_data($id)
	{
		$rProduct = $this->_getProduct($id)->result();

		$datas = [];

		foreach ($rProduct as $res) {
			$row = [
				'product_id' => $res->id,
				'product_sku' => $res->sku,
				'product_name' => $res->product_name,
				'brand_name' => $res->brand_name,
				'warehouse_name' => $res->warehouse_name,
				'qty' => $res->qty,
			];

			$datas[] = $row;
		}

		return $datas;
	}

	public function process_data($dataPush = [])
	{
		$get_data_upload = json_decode($dataPush['data_upload'], true);
		$get_data_from_warehouse = json_decode($dataPush['from_warehouse'], true);
		$rData = [];

		foreach ($get_data_upload as $res) {
			if (isset($res['SKU(*)']) && !empty($res['SKU(*)']) && $res['SKU(*)'] != "") {
				$sku = $res['SKU(*)'];
				$getDataItem = $this->_getProductBySKU($sku);
				if (!empty($getDataItem)) {
					if ($getDataItem['users_ms_warehouses_id'] == $get_data_from_warehouse) {
						$inv_storage_id = $getDataItem['id'];
						$sku_value = $res['SKU(*)'];
						$product_name = $getDataItem['product_name'];
						$brand_name = $getDataItem['brand_name'];
						$warehouse_name = $getDataItem['warehouse_name'];
						if (!empty($res['QTY(*)'])) {
							if ($res['QTY(*)'] > $getDataItem['qty']) {
								$qty = $res['QTY(*)'] . "<span class='badge badge-light-danger fw-bold'>Quantity Not Enough</span>";
								$validate_check = 2;
							} else {
								$qty = $res['QTY(*)'];
								$validate_check = 1;
							}
						} else {
							$qty = $res['QTY(*)'] . "<span class='badge badge-light-danger fw-bold'>Quantity is Required</span>";
							$validate_check = 2;
						}
					} else {
						$sku_value = $res['SKU(*)'] . "<span class='badge badge-light-danger fw-bold'>SKU is Not Exist</span>";
						$product_name = "<span class='badge badge-light-danger fw-bold'>Product is Not Exist</span>";
						$brand_name = "<span class='badge badge-light-danger fw-bold'>Brand is Not Exist</span>";
						$warehouse_name = "<span class='badge badge-light-danger fw-bold'>Warehouse is Not Exist</span>";
						$validate_check = 2;
					}
				} else {
					$sku_value = $res['SKU(*)'] . "<span class='badge badge-light-danger fw-bold'>Data is Not Exist</span>";
					$product_name = "<span class='badge badge-light-danger fw-bold'>Data is Not Exist</span>";
					$brand_name = "<span class='badge badge-light-danger fw-bold'>Data is Not Exist</span>";
					$warehouse_name = "<span class='badge badge-light-danger fw-bold'>Data is Not Exist</span>";
					$qty = "<span class='badge badge-light-danger fw-bold'>Data is Not Exist</span>";
					$validate_check = 2;
				}

				$row =
					[
						'product_id' => $inv_storage_id,
						'product_sku' => $sku_value,
						'product_name' => $product_name,
						'brand_name' => $brand_name,
						'warehouse_name' => $warehouse_name,
						'qty' => $qty,
						'data_qty' => $getDataItem['qty'],
						'validate' => $validate_check,
					];
				array_push($rData, $row);
			}
		}

		$output =
			[
				"data" => $rData,
			];

		echo json_encode($output);
	}

	function change_status_tito($id)
	{
		$this->db->trans_begin();
		try {
			if ($id == null) {
				throw new Exception("Failed update item", 1);
			}

			$get = $this->get(array('id' => $id));

			if (!$get) {
				throw new Exception("Failed update item", 1);
			}

			$change_status_array = array(
				'status' => 2
			);
			$this->db->where('id', $id);
			$process = $this->db->update($this->_table_users_ms_tito, $change_status_array);

			if (!$process) {
				throw new Exception("Failed change item", 1);
			}

			$this->db->trans_commit();
			$output =
				[
					"error" => false,
				];

			return $output;
		} catch (Exception $e) {
			$this->db->trans_rollback();
			return $e->getMessage();
		}
	}

	public function resultDataInventory($data = [])
	{
		$this->db->select(
			'a.id,
            b.sku,
            c.product_name,
            c.brand_name,
            d.warehouse_name,
            a.qty'
		);
		$this->db->from("{$this->_table_users_ms_inventory_storages} a");
		$this->db->join("{$this->_table_products_variants} b", 'b.users_ms_products_id = a.users_ms_product_variants_id', 'LEFT');
		$this->db->join("{$this->_table_products} c", 'c.id = b.users_ms_products_id', 'LEFT');
		$this->db->join("{$this->_table_ms_master_warehouse} d", 'd.id = a.	users_ms_warehouses_id', 'LEFT');
		if (!empty($data['value_input'])) {
			$this->db->group_start();
			$this->db->or_like('b.sku', $data['value_input']);
			$this->db->or_like('c.product_name', $data['value_input']);
			$this->db->or_like('c.brand_name', $data['value_input']);
			$this->db->or_like('d.warehouse_name', $data['value_input']);
			$this->db->or_like('a.qty', $data['value_input']);
			$this->db->group_end();
		}
		$this->db->where('a.deleted_at is null', null, false);
		$this->db->where('a.users_ms_companys_id', $this->_users_ms_companys_id);
		$this->db->where('a.users_ms_warehouses_id', $data['from_warehouse']);
		$this->db->order_by('a.updated_at', 'DESC');

		return $this->db->get();
	}

	public function manageListSku($getData = [])
	{
		$data = $this->resultDataInventory($getData)->result();

		$rdata = [];
		foreach ($data as $vdata) {
			$row = [
				'id' => $vdata->id,
				'product_sku' => $vdata->sku,
				'product_id' => $vdata->id,
				'product_name' => $vdata->product_name,
				'brand_name' => $vdata->brand_name,
				'warehouse_name' => $vdata->warehouse_name,
				'qty' => $vdata->qty,
			];

			$rdata[] = $row;
		}

		$output = [
			'data' => $rdata,
		];

		//output to json format
		echo json_encode($output);
	}
}
