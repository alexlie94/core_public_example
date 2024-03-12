<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Brand extends MY_Customers
{

	public function __construct()
	{
		$this->_function_except = ['account', 'settings', 'show', 'process', 'status', 'paging', 'upload_data'];
		parent::__construct();
	}

	public function Index()
	{
		$this->template->title('Data Brand');
		$this->setTitlePage('Data Brand');
		$this->assetsBuild(['datatables']);
		$this->setJs('brand');

		$data = [
			'searchBy' => ['brand_code' => 'Brand Code', 'brand_name' => 'Brand Name'],
			'lookupValue' => $this->db
				->get_where('admins_ms_lookup_values', ['lookup_config' => 'products_status'])->result(),
		];

		$header_table = ['no', 'brand code', 'brand name', 'description', 'action'];

		$this->setTable($header_table, true);

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
				'url' => base_url() . "brand/update/$1",
			],
			[
				'button' => 'delete',
				'type' => 'confirm',
				'title' => 'Item',
				'confirm' => 'Are you sure you want to delete this item ?',
				'url' => base_url() . "brand/delete/$1",
			]
		];

		$button = $this->setButtonOnTable();

		echo $this->brand_model->show($button);
	}

	public function insert()
	{
		isAjaxRequestWithPost();

		$data = array(
			'title_modal' => 'Add New Brand',
			'url_form' => base_url() . "brand/process",
			'form' => $this->load->view('v_form', '', true),
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

		$response = $this->brand_model->save();
		echo json_encode($response);
		exit();
	}

	public function update($id)
	{
		isAjaxRequestWithPost();
		try {
			if ($id == null) {
				throw new Exception("Failed to request Edit", 1);
			}

			$dataItems = $this->brand_model->getItems($id);

			if (!is_array($dataItems)) {
				throw new Exception($dataItems, 1);
			}

			$data = array(
				'title_modal' => 'Edit Brand',
				'url_form' => base_url() . "brand/process",
				'form' => $this->load->view('v_form', $dataItems, true),
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

	public function delete($id = null)
	{
		isAjaxRequestWithPost();
		$response = array('text' => 'Successfully delete item', 'success' => true);
		try {
			$process = $this->brand_model->deleteData($id);
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

		$processing_data = $this->brand_model->manageDataUpload($getData);

		echo json_encode($processing_data);
	}
}
