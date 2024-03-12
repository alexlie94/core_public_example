<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Category extends MY_Customers
{

	public function __construct()
	{
		$this->_function_except = ['account', 'settings', 'show', 'process', 'status', 'paging', 'upload_data', 'add_parent', 'massUpload'];
		parent::__construct();
	}

	public function Index()
	{
		$this->template->title('Category');
		$this->setTitlePage('Data Category');
		$this->assetsBuild(['datatables']);
		$this->setJs('category');

		$data = [
			'searchBy' => ['categories_code' => 'Category Code', 'categories_name' => 'Category Name', 'categories_name' => 'Category Link'],
			'lookupValue' => $this->db
				->get_where('admins_ms_lookup_values', ['lookup_config' => 'products_status'])->result(),
		];

		$header_table = ['no', 'category code', 'category name', 'category link', 'action'];

		$this->setTable($header_table, true);

		$this->template->build('v_show', $data);
	}

	public function show()
	{
		// isAjaxRequestWithPost();
		$this->function_access('view');
		$this->_custom_button_on_table = [
			[
				'button' => 'update',
				'type' => 'modal',
				'url' => base_url() . "category/update/$1",
			],
			[
				'button' => 'delete',
				'type' => 'confirm',
				'title' => 'Item',
				'confirm' => 'Are you sure you want to delete this item ?',
				'url' => base_url() . "category/delete/$1",
			],
		];

		$button = $this->setButtonOnTable();

		echo $this->category_model->show($button);
	}

	public function insert()
	{
		isAjaxRequestWithPost();

		$get['dataCategory'] = $this->category_model->get_all();

		$data = [
			'title_modal' => 'Add New Category',
			'url_form' => base_url() . 'category/process',
			'form' => $this->load->view('v_form', $get, true),
		];

		$html = $this->load->view($this->_v_form_modal, $data, true);

		echo json_encode(['html' => $html]);
		exit();
	}

	public function process()
	{
		isAjaxRequestWithPost();
		if (!empty($this->input->post('id'))) {
			$this->function_access('update');
		} else {
			$this->function_access('insert');
		}

		$response = $this->category_model->save();

		echo json_encode($response);
		exit();
	}

	public function update($id)
	{
		isAjaxRequestWithPost();
		try {
			$get['dataCategory'] = $this->category_model->get_all();
			$get['dataItems'] = $this->category_model->getItems($id);

			$data = [
				'title_modal' => 'Edit Category',
				'url_form' => base_url() . 'category/process',
				'form' => $this->load->view('v_form', $get, true),
			];

			$html = $this->load->view($this->_v_form_modal, $data, true);
			$response['html'] = $html;
			echo json_encode($response);
			exit();
		} catch (Exception $e) {
			$response['failed'] = true;
			$response['message'] = $e->getMessage();
			echo json_encode($response);
			exit();
		}
	}

	public function delete($id = null)
	{
		isAjaxRequestWithPost();
		$response = ['text' => 'Successfully delete item', 'success' => true];
		try {
			$process = $this->category_model->deleteData($id);
			if ($process !== true) {
				throw new Exception($process, 1);
			}
			echo json_encode($response);
			exit();
		} catch (Exception $e) {
			$response['text'] = $e->getMessage();
			$response['success'] = false;
			echo json_encode($response);
			exit();
		}
	}

	public function upload_data()
	{
		$getData = json_decode($_POST['dataUpload'], true);

		$this->category_model->proccess_data($getData);
	}

	public function add_parent()
	{
		$this->category_model->manage_add_parent();
	}

	public function massUpload()
	{
		isAjaxRequestWithPost();
		$data = array(
			'title_modal' => 'Mass Upload Category',
			'url_form' => base_url() . 'category/process',
			'form' => $this->load->view('v_mass_upload', '', true),
			'buttonID' => 'saveMassUpload',
			'buttonCloseID' => 'btnCloseModalMassUpload',
		);

		$html = $this->load->view($this->_v_form_modal, $data, true);

		echo json_encode(array('html' => $html));
		exit();
	}
}
