<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Suppliers_data extends MY_Customers
{

	public function __construct()
	{

		$this->_function_except = ['account', 'settings', 'show', 'process', 'status', 'paging', 'upload_data'];
		parent::__construct();
	}

	public function index()
	{
		$this->template->title('Data Suppliers');
		$this->setTitlePage('Data Suppliers');
		$this->assetsBuild(['datatables', 'repeater3']);
		$data = [
			'searchBy' => ['supplier_code' => 'Supplier Code', 'supplier_name' => 'Supplier Name'],
		];
		$header_table = ['no', 'supplier code', 'supplier name', 'email', 'address', 'phone', 'action'];
		$this->setTable($header_table, true);
		$this->setJs('suppliers_data');
		$this->template->build('v_show', $data);
	}

	public function show()
	{
		isAjaxRequestWithPost();
		$this->function_access('view');
		$this->_custom_button_on_table = [
			[
				'button' => 'update',
				'type' => 'modal',
				'url' => base_url() . "suppliers_data/update/$1",
			],
			[
				'button' => 'delete',
				'type' => 'confirm',
				'title' => 'Supplier',
				'confirm' => 'Are you sure you want to delete this item ?',
				'url' => base_url() . "suppliers_data/delete/$1",
			]
		];
		$button = $this->setButtonOnTable();
		echo $this->suppliers_data_model->show($button);
	}

	public function insert()
	{
		isAjaxRequestWithPost();
		$get['brands'] = $this->suppliers_data_model->getDataBrands();
		$get['TypeOwnership'] = $this->suppliers_data_model->getDataOwnershipTypes();

		$data = array(
			'title_modal' => 'Add New Supplier',
			'url_form' => base_url() . "suppliers_data/process",
			'form' => $this->load->view('v_form', $get, true),
		);
		$html = $this->load->view($this->_v_form_modal, $data, true);

		echo json_encode(array('html' => $html));
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

		$response = $this->suppliers_data_model->save();
		echo json_encode($response);
		exit();
	}

	public function update($id)
	{
		isAjaxRequestWithPost();
		try {
			$get['dataItems'] = $this->suppliers_data_model->getItems($id);
			$get['brands'] = $this->suppliers_data_model->getDataBrands();
			$get['TypeOwnership'] = $this->suppliers_data_model->getDataOwnershipTypes();
			$get['suppliers_brands'] = $this->suppliers_data_model->getDataSuppliersBrands($id);

			$data = array(
				'title_modal' => 'Edit Supplier',
				'url_form' => base_url() . "suppliers_data/process",
				'form' => $this->load->view('v_form', $get, true),
			);

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

	public function status($id = null)
	{
		isAjaxRequestWithPost();
		$this->function_access('update');
		$response = array('text' => 'Successfully change status item', 'success' => true);
		try {
			$process = $this->users_model->changeStatus($id);
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

	public function delete($id = null)
	{
		isAjaxRequestWithPost();
		$response = array('text' => 'Successfully delete item', 'success' => true);
		try {
			$process = $this->suppliers_data_model->deleteData($id);
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

		$processing_data = $this->suppliers_data_model->process_data($getData);

		echo json_encode($processing_data);
	}
}
