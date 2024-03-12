<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Tito extends MY_Customers
{

	public function __construct()
	{
		$this->_function_except = ['account', 'settings', 'show', 'process', 'status', 'paging', 'upload_data', 'getWarehouse', 'add_sku', 'process_delete_id', 'productList', 'getWarehouseById', 'mass_upload', 'change_status', 'getSkuOnInvStorage', 'preview_tito', 'listProductSku'];
		parent::__construct();
		$this->_searchBy = [
			'ti_number' => 'TI Number',
			'created_at' => 'Date Created',
			'assignee' => 'Assignee'
		];
	}

	public function index()
	{
		$this->template->title('Transfer Out');
		$this->setTitlePage('Transfer Out');
		$this->assetsBuild(['datatables', 'repeater', 'xlsx']);
		$this->setJs('tito');
		$data = [
			'searchBy' => $this->_searchBy,
			'status' => $this->tito_model->getStatus(),
		];
		$header_table = array('No', 'TO Number', 'TI Number', 'Date Created', 'Qty', 'Qty Received', 'PIC', 'Status', 'Action');
		$this->setTable($header_table, true);
		$this->template->build('v_show', $data);
	}

	public function show()
	{
		isAjaxRequestWithPost();
		$this->function_access('view');
		echo $this->tito_model->show();
	}

	public function insert()
	{
		isAjaxRequestWithPost();
		$get['warehouse_id'] = $this->tito_model->getWarehouse();
		$data = array(
			'title_modal' => 'Transfer Out',
			'url_form' => base_url() . "tito/process",
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

		$response = $this->tito_model->save();
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

			$dataItems = $this->tito_model->getItems($id);
			$dataItems['warehouse_id'] = $this->tito_model->getWarehouse();
			$dataItems['warehouse_id_custom'] = $this->tito_model->getWarehouseCustom($id);
			$dataItems['tito_details_data'] = $this->tito_model->getTitoDetailsById($id);

			if (!is_array($dataItems)) {
				throw new Exception($dataItems, 1);
			}

			$data = array(
				'title_modal' => 'Edit Transfer Out',
				'url_form' => base_url() . "tito/process",
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

	public function getWarehouse($id)
	{
		$data = $this->tito_model->getWarehouseId($id);
		echo json_encode($data);
	}

	public function getWarehouseById($id)
	{
		$data = $this->tito_model->getWarehouseById($id);
		echo json_encode($data);
	}

	public function add_sku()
	{
		isAjaxRequestWithPost();

		$data = [
			'title_modal' => 'Select Brand',
			'url_form' => '',
			'form' => $this->load->view('v_form3', '', true),
			'buttonCloseID' => 'btnCloseModalFullscreen2',
			'buttonID' => 'selectProductList',
			'buttonName' => 'Next',
		];

		$html = $this->load->view($this->_v_form_modal, $data, true);

		echo json_encode(['html' => $html]);
		exit();
	}

	public function productList($id)
	{
		$get_product_list = $this->tito_model->show_products_data($id);

		$output = [
			'draw' => 10,
			'recordsTotal' => 100,
			'recordsFiltered' => 10,
			'data' => $get_product_list,
		];

		echo json_encode($output);
	}

	public function listProductSku()
	{
		isAjaxRequestWithPost();

		$getData = [
			'from_warehouse' => isset($_POST['from_warehouse']) ? $_POST['from_warehouse'] : '',
			'value_input' => isset($_POST['value_input']) ? $_POST['value_input'] : ''
		];

		$this->tito_model->manageListSku($getData);
	}

	public function process_delete_id()
	{
		$id = $_POST['id'];
		$processing_data = $this->tito_model->proses_delet_data($id);

		echo json_encode($processing_data);
	}

	function mass_upload()
	{
		isAjaxRequestWithPost();

		$data = [
			'title_modal' => 'Mass Upload',
			'url_form' => '',
			'form' => $this->load->view('mass_upload', '', true),
			'buttonCloseID' => 'btnCloseModalFullscreen2',
			'buttonID' => 'btnProcessMassUpload',
			'buttonName' => 'Next',
		];

		$html = $this->load->view($this->_v_form_modal, $data, true);

		echo json_encode(['html' => $html]);
		exit();
	}

	function upload_data()
	{
		$getPushData = [
			'data_upload' => $_POST['dataUpload'],
			'from_warehouse' => $_POST['from_warehouse']
		];
		// $getData = json_decode($_POST['dataUpload'], true);

		$processing_data = $this->tito_model->process_data($getPushData);
	}

	function change_status()
	{
		$id = $_POST['id'];
		$processing_data = $this->tito_model->change_status_tito($id);

		echo json_encode($processing_data);
	}

	public function getSkuOnInvStorage($sku)
	{
		$data = $this->tito_model->getSkuOnInventoryStorage($sku);
		echo json_encode($data);
	}

	function preview_tito($id)
	{
		isAjaxRequestWithPost();
		try {
			if ($id == null) {
				throw new Exception("Failed to request Edit", 1);
			}

			$dataItems = $this->tito_model->getItems($id);
			$dataItems['warehouse_id'] = $this->tito_model->getWarehouse();
			$dataItems['warehouse_id_custom'] = $this->tito_model->getWarehouseCustom($id);
			$dataItems['tito_details_data'] = $this->tito_model->getTitoDetailsById($id);

			if (!is_array($dataItems)) {
				throw new Exception($dataItems, 1);
			}

			$data = array(
				'title_modal' => 'Preview',
				'url_form' => base_url() . "tito/process",
				'form' => $this->load->view('preview', $dataItems, true),
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
}