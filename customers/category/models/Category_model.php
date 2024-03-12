<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Category_model extends MY_ModelCustomer
{
	use MY_Tables;
	public function __construct()
	{
		$this->_tabel = $this->_table_category;
		parent::__construct();
	}

	public function show($button = '')
	{
		$this->datatables->select(" a.id as id,
                                    a.categories_code,
									a.parent_categories_id,
                                    a.categories_name as catgry_name,
									IF(a.parent_categories_id = 0, a.categories_name, CONCAT_WS(' > ', 
											NULLIF(COALESCE(grandparent.categories_name, ''), ''),
											NULLIF(COALESCE(parent.categories_name, ''), ''),
											a.categories_name
										)) as formatted_catgry_name 
									", false);

		$this->datatables->from("{$this->_table_category} a");

		$this->datatables->join("{$this->_table_category} parent", "parent.id = a.parent_categories_id", "left");
		$this->datatables->join("{$this->_table_category} grandparent", "grandparent.id = parent.parent_categories_id", "left");

		$this->datatables->where("a.deleted_at is null", null, false);
		$this->datatables->where(["a.{$this->_table_users_ms_companys}_id" => $this->_users_ms_companys_id]);

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
					case 'categories_code':
						$this->datatables->like('a.categories_code', $setValue);
						break;
					case 'categories_name':
						$this->datatables->like('a.categories_name', $setValue);
						break;
					default:
						break;
				}
			}
		}

		$fieldSearch = [
			'a.categories_code',
			'a.categories_name',
		];

		$this->_searchDefaultDatatables($fieldSearch);

		$this->datatables->order_by('a.updated_at desc');

		$button = "<button class=\"btn btn-outline btn-outline-dashed btn-outline-success btn-active-light-success hover-scale btn-sm fw-bold me-2 mb-2 btnEdit\" data-type=\"modal\" data-fullscreenmodal=\"0\" data-url=\"" . base_url("category/update/$1") . "\" data-id =\"$1\"><i class=\"bi bi-pencil-square fs-4 me-2\"></i>Edit</button>";
		$button .= "<button class=\"btn btn-outline btn-outline-dashed btn-outline-danger btn-active-light-danger hover-scale btn-sm fw-bold mb-2\" data-url=\"" . base_url("category/delete/$1") . "\" data-type=\"confirm\" data-textconfirm=\"Are you sure you want to delete this item ?\" data-title=\"Item\" data-id =\"$1\"><i class=\"bi bi-trash fs-4 me-2\"></i>Delete</button>";

		$this->datatables->add_column('action', $button, 'id');

		return $this->datatables->generate();
	}

	private function _validate()
	{
		$response = array('success' => false, 'validate' => true, 'messages' => []);
		$response['type'] = !empty($this->input->post('id')) ? 'update' : 'insert';

		$role_cat_name = array('trim', 'required', 'xss_clean');

		$this->form_validation->set_rules('category_name', 'Category Name', $role_cat_name);

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

			if ($this->input->post('category_name_bulk')) {

				$get_name_category = $this->input->post('category_name_bulk');
				$get_parent_category = $this->input->post('parent_category_bulk');

				$parent_data = [];

				for ($i = 0; $i < count($get_name_category); $i++) {

					if (!in_array($get_parent_category[$i], $parent_data) && !empty($get_parent_category[$i])) {
						array_push($parent_data, $get_parent_category[$i]);
					}
				}

				foreach ($parent_data as $list) {

					$datas = [
						'categories_code' => mkautono($this->_tabel, 'categories_code', 'CC'),
						'categories_name' => $list,
						'parent_categories_id' => 0
					];

					$cekParentName = $this->get(['categories_name' => $list]);

					if (empty($cekParentName)) {
						$this->insert($datas);
					}
				}

				for ($i = 0; $i < count($get_name_category); $i++) {

					$get_category_id = $this->get(['categories_name' => $get_name_category[$i]]);

					if (empty($get_parent_category[$i])) {
						if (empty($get_category_id)) {
							$datas = [
								'categories_code' => mkautono($this->_tabel, 'categories_code', 'CC'),
								'categories_name' => $get_name_category[$i],
								'parent_categories_id' => 0
							];

							$this->insert($datas);
						}
					} else {

						$get_parent = $this->get(['categories_name' => $get_parent_category[$i]]);

						if (!empty($get_category_id)) {

							$data_update = [
								'parent_categories_id' => $get_parent->id
							];

							$this->update(['id' => $get_category_id->id], $data_update);
						} else {
							$datas = [
								'categories_code' => mkautono($this->_tabel, 'categories_code', 'CC'),
								'categories_name' => $get_name_category[$i],
								'parent_categories_id' => $get_parent->id
							];

							$this->insert($datas);
						}
					}
				}


				$response['type'] = 'insert';
				$response['validate'] = true;
				$response['messages'] = 'Successfully Insert Data Brand';
			} else {

				$response = self::_validate();

				if (!$response['validate']) {
					throw new Exception("Error Processing Request", 1);
				}

				$id = clearInput($this->input->post('id'));
				$cat_code = !empty($this->input->post('category_code')) ? clearInput($this->input->post('category_code')) : '';
				$cat_name = clearInput($this->input->post('category_name'));
				$parent_id = clearInput($this->input->post('parent_id'));

				$array_category_data = array(
					'categories_code' => mkautono($this->_tabel, 'categories_code', 'CC'),
					'categories_name' => $cat_name,
					'parent_categories_id' => $parent_id
				);

				$array_category_data1 = array(
					'categories_name' => $cat_name,
					'parent_categories_id' => $parent_id
				);

				if (empty($id)) {
					$cek_category_name = $this->get(['categories_name' => $cat_name]);

					if (!empty($cek_category_name)) {
						$response['messages'] = 'Data Already Exists';
						throw new Exception;
					}

					$execute = $this->insert($array_category_data);

					if (!$execute) {
						$response['messages'] = 'Failed Insert Data Category';
						throw new Exception;
					}

					$response['data']['id'] = $execute;
					$response['data']['name'] = $cat_name;
					$response['messages'] = "Successfully Insert Data Category";
				} else {
					$data = $this->get(array('id' => $id));

					if (!$data) {
						$response['messages'] = 'Data Update Invalid';
						throw new Exception();
					}

					$cek_category_name = $this->get(['id !=' => $id, 'categories_name' => $cat_name]);

					if (!empty($cek_category_name)) {
						$response['messages'] = 'Data Already Exists';
						throw new Exception;
					}

					$process = $this->update(array('id' => $id), $array_category_data1);

					if (!$process) {
						$response['messages'] = 'Failed Update Data Category';
						throw new Exception;
					}

					$response['messages'] = 'Successfully Update Data Category';
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

			$get = $this->get(array('id' => $id));

			if (!$get) {
				throw new Exception("Data not Register", 1);
			}

			$table = array(
				'id' => $get->id,
				'categories_code' => $get->categories_code,
				'categories_name' => $get->categories_name,
				'parent_categories_id' => $get->parent_categories_id
			);

			return $table;
		} catch (Exception $e) {
			return $e->getMessage();
		}
	}

	public function deleteData($id)
	{
		$this->db->trans_begin();
		try {
			$cek_use_id = $this->get(['parent_categories_id' => $id]);

			if ($cek_use_id) {
				throw new Exception("Failed delete item", 1);
			}

			$softDelete = $this->softDelete($id);
			if (!$softDelete) {
				throw new Exception("Failed delete item", 1);
			}

			$this->db->trans_commit();
			return true;
		} catch (Exception $e) {
			$this->db->trans_rollback();
			return $e->getMessage();
		}
	}

	public function proccess_data($getData)
	{
		$rData = [];

		$category_data = [];
		$data_parent_name = [];

		// Push Category Name
		foreach ($getData as $res) {

			if (!empty($res['CATEGORY_CODE_(*)'])) {
				array_push($data_parent_name, $res['PARENT_CATEGORY_NAME']);
			}
		}

		//Push Category Id
		foreach (array_unique($data_parent_name) as $list) {
			$get_category_id = $this->get(['categories_name' => $list]);

			if (empty($get_category_id)) {
				$row2 =
					[
						'category_name' => $list,
					];

				array_push($category_data, $row2);
			}
		}

		foreach ($getData as $res) {

			if (!empty($res['CATEGORY_NAME_(*)']) || !empty($res['PARENT_CATEGORY_NAME'])) {

				$cek_category = $this->get(['categories_name' => $res['CATEGORY_NAME_(*)']]);

				if (empty($res['CATEGORY_NAME_(*)'])) {
					$set_category = '<span class="ms-2 badge badge-light-danger fw-bold">Data Empty</span>';
					$validate_check = 2;
				} elseif (!empty($cek_category)) {
					$set_category = $res['CATEGORY_NAME_(*)'] . '<span class="ms-2 badge badge-light-danger fw-bold">Already Exist</span>';
					$validate_check = 2;
				} else {
					$set_category = $res['CATEGORY_NAME_(*)'];
				}

				$row =
					[
						'category_name' => $set_category,
						'parent_category' => $res['PARENT_CATEGORY_NAME'],
						'validate' => empty($validate_check) ? '' : $validate_check,
					];

				$rData[] = $row;
			}
		}



		$output =
			[
				"data" => $rData,
				"category_data" => $category_data
			];

		echo json_encode($output);
	}

	public function manage_add_parent()
	{

		$get_result = $this->get_all();

		$rData = [];

		foreach ($get_result as $res) {

			$row =
				[
					'category_id' => $res->id,
					'category_name' => $res->categories_name,
				];

			$rData[] = $row;
		}

		$output =
			[
				"data" => $rData
			];

		echo json_encode($output);
	}
}
