<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Brand_model extends MY_ModelCustomer
{
	use MY_Tables;

	public function __construct()
	{
		$this->_tabel = $this->_table_ms_brands;
		parent::__construct();
	}

	public function show($button = '')
	{
		$this->datatables->select(
			"a.id as id,
            a.brand_code,
            a.brand_name,
            a.description",
			false,
		);

		$this->datatables->from("{$this->_table_ms_brands} a");
		$this->datatables->where('a.deleted_at is null', null, false);
		$this->datatables->where(["a.{$this->_table_users_ms_companys}_id" => $this->_users_ms_companys_id]);
		$this->datatables->order_by('a.updated_at desc');

		$filters = !empty($this->input->post('filters')) ? $this->input->post('filters') : false;

		if ($filters !== false && is_array($filters)) {

			$getSearchBy = '';
			$setValue = '';

			foreach ($filters as $val) {

				if ($val['name'] == 'searchBy') {
					$getSearchBy .= $val['value'];
				}

				if ($val['name'] == 'searchValue') {
					$setValue .= $val['value'];
				}
			}

			if (!empty($getSearchBy)) {
				switch ($getSearchBy) {
					case 'brand_code':
						$this->datatables->like('a.brand_code', $setValue);
						break;
					case 'brand_name':
						$this->datatables->like('a.brand_name', $setValue);
						break;
					default:
						break;
				}
			}
		}

		$get_value_master_requisition = $this->input->post('master_reqisition');
		if (!empty($get_value_master_requisition)) {
			$this->datatables->like('a.brand_code', $get_value_master_requisition);
			$this->datatables->or_like('a.brand_name', $get_value_master_requisition);
		}

		$fieldSearch = [
			'a.brand_code',
			'a.brand_name',
			'a.description'
		];

		$this->_searchDefaultDatatables($fieldSearch);

		$this->datatables->order_by('a.brand_code asc');
		$this->datatables->order_by('a.updated_at desc');

		// $button = "<button class=\"btn btn-outline btn-outline-dashed btn-outline-success btn-active-light-success hover-scale btn-sm fw-bold me-2 mb-2 btnEdit\" data-type=\"modal\" data-fullscreenmodal=\"0\" data-url=\"" . base_url("brand/update/$1") . "\" data-id =\"$1\"><i class=\"bi bi-pencil-square fs-4 me-2\"></i>Edit</button>";
		// $button .= "<button class=\"btn btn-outline btn-outline-dashed btn-outline-danger btn-active-light-danger hover-scale btn-sm fw-bold mb-2\" data-url=\"" . base_url("brand/delete/$1") . "\" data-type=\"confirm\" data-textconfirm=\"Are you sure you want to delete this item ?\" data-title=\"Item\" data-id =\"$1\"><i class=\"bi bi-trash fs-4 me-2\"></i>Delete</button>";

		$this->datatables->add_column('action', $button, 'id');

		return $this->datatables->generate();
	}

	private function _validate()
	{
		$response = ['success' => false, 'validate' => true, 'messages' => []];

		$response['type'] = !empty($this->input->post('id')) ? 'update' : 'insert';

		$role_validate = ['trim', 'required', 'xss_clean'];

		$this->form_validation->set_rules('brand_name', 'Brand Name', $role_validate);

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
			if ($this->input->post('brand_name_bulk')) {

				for ($i = 0; $i < count($this->input->post('brand_name_bulk')); $i++) {
					$data_array = [
						'brand_code' => mkautono($this->_tabel, 'brand_code', 'BC'),
						'brand_name' => $this->input->post('brand_name_bulk')[$i],
						'description' => !empty($this->input->post('description_bulk')[$i]) ? clearInput($this->input->post('description_bulk')[$i]) : '',
					];

					$execute = $this->insert($data_array);
				}

				if (!$execute) {
					$response['messages'] = 'Data Insert Invalid';
					throw new Exception();
				}

				$response['type'] = 'insert';
				$response['validate'] = true;
				$response['messages'] = 'Successfully Insert Data Brand';
			} else {

				$response = self::_validate();

				if (!$response['validate']) {
					throw new Exception('Error Processing Request', 1);
				}

				$id = clearInput($this->input->post('id'));
				$brand_code = !empty($this->input->post('brand_code')) ? clearInput($this->input->post('brand_code')) : '';
				$brand_name = clearInput($this->input->post('brand_name'));
				$description = !empty($this->input->post('desc')) ? clearInput($this->input->post('desc')) : '';

				$cek_brand_name = $this->get(['brand_name' => $brand_name]);

				if (empty($id)) {

					if (!empty($cek_brand_name)) {
						$response['messages'] = 'Data Already Exists';
						throw new Exception;
					}

					$insert_brand_data = [
						'brand_code' => mkautono($this->_tabel, 'brand_code', 'BC'),
						'brand_name' => $brand_name,
						'description' => $description
					];

					$execute = $this->insert($insert_brand_data);

					if (!$execute) {
						$response['messages'] = 'Data Insert Invalid';
						throw new Exception();
					}

					$response['messages'] = 'Successfully Insert Data Brand';
				} else {

					$data = $this->get(['id' => $id]);

					if (!$data) {
						$response['messages'] = 'Data Update Invalid';
						throw new Exception();
					}

					$cek_duplicat_name = $this->get(['id !=' => $id, 'brand_name' => $brand_name]);

					if (!empty($cek_duplicat_name)) {
						$response['messages'] = 'Data Already Exists';
						throw new Exception;
					}

					$update_brand_data = [
						'brand_name' => $brand_name,
						'description' => $description,
					];

					$execute = $this->update(['id' => $id], $update_brand_data);

					if (!$execute) {
						$response['messages'] = 'Data Update Invalid';
						throw new Exception();
					}

					$response['messages'] = 'Successfully Update Data Brand';
				}
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

			$table = [
				'id' => $get->id,
				'brand_code' => $get->brand_code,
				'brand_name' => $get->brand_name,
				'description' => $get->description,
			];

			return $table;
		} catch (Exception $e) {
			return $e->getMessage();
		}
	}

	public function deleteData($id)
	{
		$this->db->trans_begin();
		try {
			if ($id == null) {
				throw new Exception('Failed delete item', 1);
			}

			$get = $this->get(['id' => $id]);
			if (!$get) {
				throw new Exception('Failed delete item', 1);
			}

			$softDelete = $this->softDelete($id);

			if (!$softDelete) {
				throw new Exception('Failed delete item', 1);
			}

			$this->db->trans_commit();
			return true;
		} catch (Exception $e) {
			$this->db->trans_rollback();
			return $e->getMessage();
		}
	}

	public function manageDataUpload($getData)
	{
		$rData = [];

		foreach ($getData as $res) {

			if (!empty($res['BRAND_NAME_(*)']) || !empty($res['DESCRIPTION'])) {

				$cek_brands = $this->brand_model->get(['brand_name' => $res['BRAND_NAME_(*)']]);

				if (empty($res['BRAND_NAME_(*)'])) {
					$set_brand_name = '<span class="ms-2 badge badge-light-danger fw-bold">Data Empty</span>';
					$validate_check = 2;
				} elseif (!empty($cek_brands)) {
					$set_brand_name = $res['BRAND_NAME_(*)'] . '<span class="ms-2 badge badge-light-danger fw-bold">Already Exist</span>';

					$validate_check = 2;
				} else {

					$set_brand_name = $res['BRAND_NAME_(*)'];
					$validate_check = 1;
				}

				$row =
					[
						'brand_name' => $set_brand_name,
						'description' => $res['DESCRIPTION'],
						'validate' => empty($validate_check) ? '' : $validate_check,
					];

				$rData[] = $row;
			}
		}

		$output =
			[
				"data" => $rData,
			];

		return $output;
	}
}
