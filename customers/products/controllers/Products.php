<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Products extends MY_Customers
{

	public function __construct()
	{
		$this->_function_except = [
			'account',
			'settings',
			'show',
			'process',
			'status',
			'paging',
			'upload_data',
			'variant_color',
			'variant_color_name',
			'sub_category',
			'printBarcode',
			'massUpload',
			'downloadView',
			'processMassUpload',
			'downloadCsv'
		];
		parent::__construct();
		$this->_searchBy = [
			'sku' => 'SKU', 'product_name' => 'Product Name', 'brand_name' => 'Brand Name'
		];
	}

	public function Index()
	{
		$this->template->title('Products');
		$this->setTitlePage('Data Products');
		$this->assetsBuild(['datatables', 'repeater', 'xlsx']);
		$this->setJs('products');

		$data = [
			'searchBy' => $this->_searchBy,
			'lookupValue' => $this->db
				->get_where('admins_ms_lookup_values', ['lookup_config' => 'products_status'])->result(),
		];

		$this->setTable(['no', 'sku', 'product',  'brand', 'category', 'color', 'size', 'created', 'status', 'action'], true);

		$this->template->build('v_show', $data);
	}

	public function show()
	{
		isAjaxRequestWithPost();
		$this->function_access('view');

		echo $this->products_model->show();
	}

	public function insert()
	{
		isAjaxRequestWithPost();
		$get = [
			'brands' => $this->products_model->getDataBrands()->get_all(),
			'suppliers' => $this->products_model->getDataSuppliers()->get_all(),
			'category' => $this->products_model->getDataCategory()->get_all(array('parent_categories_id' => 0)),
			'general_color' => $this->products_model->getDataGeneralColor()->result()
		];

		$data = [
			'title_modal' => 'Product Form',
			'url_form' => base_url() . 'products/process',
			'form' => $this->load->view('v_form', $get, true),
			'buttonCloseID' => 'btnCloseModalFullscreen',
		];

		$html = $this->load->view($this->_v_form_modal, $data, true);

		echo json_encode(['html' => $html]);
		exit();
	}

	public function process()
	{
		isAjaxRequestWithPost();

		$this->function_access('insert');

		$response = $this->products_model->save();

		echo json_encode($response);
		exit();
	}

	public function processMassUpload()
	{
		isAjaxRequestWithPost();

		$response = $this->products_model->saveMassUpload();

		echo json_encode($response);
		exit();
	}

	public function upload_data()
	{
		isAjaxRequestWithPost();
		$getData = $_POST['dataUpload'];

		$processing_data = $this->products_model->process_data($getData);

		echo json_encode($processing_data);
	}

	public function variant_color()
	{
		isAjaxRequestWithPost();
		$getData = json_decode($_POST['general_color_id'], true);

		$processing_data = $this->products_model->process_data_color($getData);

		echo json_encode($processing_data);
	}

	public function variant_color_name()
	{
		isAjaxRequestWithPost();
		$getData = json_decode($_POST['variant_color_id'], true);

		$processing_data = $this->products_model->process_data_color_name($getData);

		echo json_encode($processing_data);
	}

	public function sub_category()
	{
		isAjaxRequestWithPost();
		$getData = json_decode($_POST['parent_category_id'], true);

		$processing_data = $this->products_model->process_sub_category($getData);

		echo json_encode($processing_data);
	}

	public function printBarcode()
	{
		isAjaxRequestWithPost();

		$data = array(
			'title_modal' => 'Print Barcode',
			'url_form' => base_url() . "products/printBarcode",
			'form' => $this->load->view('v_print', '', true),
			'buttonName' => 'Print',
			'buttonID' => 'print'
		);

		$html = $this->load->view($this->_v_form_modal, $data, true);

		echo json_encode(array('html' => $html));
		exit();
	}

	public function massUpload()
	{
		isAjaxRequestWithPost();
		$data = array(
			'title_modal' => 'Mass Upload Product',
			'url_form' => base_url() . 'products/processMassUpload',
			'form' => $this->load->view('v_mass_upload', '', true),
			'buttonID' => 'saveMassUpload',
			'buttonCloseID' => 'btnCloseModalMassUpload',
		);

		$html = $this->load->view($this->_v_form_modal, $data, true);

		echo json_encode(array('html' => $html));
		exit();
	}

	public function downloadView()
	{

		$dataGet = [
			'searchBy' => isset($_GET['searchName']) ? $_GET['searchName'] : '',
			'searchValue' => isset($_GET['valueSearch']) ? $_GET['valueSearch'] : '',
			'valueStatus' => isset($_GET['valueStatus']) ? $_GET['valueStatus'] : '',
			'startDate' => isset($_GET['startDate']) ? $_GET['startDate'] : '',
			'endDate' => isset($_GET['endDate']) ? $_GET['endDate'] : ''
		];

		$this->products_model->generateSpreadsheet($dataGet);
	}

	public function downloadCsv()
	{
		// $csvData = array(
		//     ['BRAND NAME (*)', 
		//     'SUPPLIER NAME (*)', 
		//     'CATEGORY NAME (*)', 
		//     'PRODUCT NAME (*)',
		//     'GENDER (*)', 
		//     'SUB CATEGORY', 
		//     'SUB SUB CATEGORY', 
		//     'PRICE', 
		//     'GENERAL COLOR (*)', 
		//     'VARIANT COLOR', 
		//     'SIZE (*)'
		//     ]
		// );

		$this->products_model->downloadCsv();
	}
}
