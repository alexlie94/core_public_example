<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sales_orders extends MY_Customers
{

	protected $source;
	protected $_model;
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
			'product_list',
			'products',
			'products_selected',
			'insert_order',
			'get_cities_by_provices',
			'detail',
			'get_data_order',
			'get_data_status_order',
			'form_create_shipment',
			'add_create_shipment',
			'print_label',
			'sync_order'
		];
		parent::__construct();
		$this->source = 2;
		$this->_model = $this->sales_orders_model;
		$this->load->library('shopee/orders');
	}

	public function index()
	{
		$this->template->title('Sales Orders - Shopee');
		$this->setTitlePage('Sales Orders - Shopee');

		$data = array(
			'channel' => $this->_model->get_channel_by_source($this->source),
			'channel_status' => $this->_model->get_channel_status($this->source),
			// 'all_data' => $this->_model->get_all_data($this->source)
		);

		$this->setJs('sales_orders');
		$this->template->build('v_form', $data);
	}

	public function get_data_status_order($channel_id)
	{
		$data = array(
			'channel_status' => $this->_model->get_channel_status($channel_id),
		);
		$html = $this->load->view('v_content', $data, TRUE);

		echo $html;
	}

	public function get_data_order()
	{

		isAjaxRequestWithPost();
		$data = array(
			'data_order' => $this->_model->get_data_order($_POST),
			'count_data_order' => $this->_model->get_count_data_order($_POST),
		);
		$html = $this->load->view('v_content_order', $data, TRUE);

		echo $html;
		exit();
	}

	public function detail($order_id)
	{

		$this->template->title('Order Detail');
		$this->setTitlePage('Order Detail');

		$data = array(
			'detail' => $this->_model->get_detail_order($order_id)
		);

		$this->setJs('sales_orders');
		$this->template->build('v_detail', $data);
	}


	public function form_create_shipment($id)
	{
		isAjaxRequestWithPost();
		$get['detail'] = $this->_model->get_detail_order($id);

		$data = [
			'title_modal' => 'Create Shipment',
			'url_form' => base_url() . 'sales_orders/process',
			'buttonCloseID' => 'btnCloseCreateShipment',
			'buttonID' => 'btnAddCreateShipment',
			'buttonName' => 'Create Shipment',
			'form' => $this->load->view('sales_orders/v_create_shipment', $get, true)
		];

		$html = $this->load->view($this->_v_form_modal, $data, true);

		echo json_encode(['html' => $html]);
		exit();
	}

	public function add_create_shipment()
	{
		isAjaxRequestWithPost();

		$local_order_id = $_POST['local_order_id'];
		$arr_shipment = [
			"order_sn" => $local_order_id,
			"pickup" => [
				"address_id" => $_POST['address_id'],
				"pickup_time_id" => $_POST['time_id'],
			],
		];

		$data_shipment = json_encode($arr_shipment);
		$data = $this->orders->create_shipment($local_order_id, $data_shipment);

		echo json_encode($data);
		exit();
	}

	public function print_label($local_order_id)
	{
		// isAjaxRequestWithPost();
		$arr = [
			"order_list" => [
				[
					"order_sn" => $local_order_id
				]
			]
		];

		$data_arr = json_encode($arr);
		$data = $this->orders->download_shipping_document($local_order_id, $data_arr);
		if ($data['status']) {
			$status = true;
			$filename = $local_order_id . '.pdf';
			// echo $data['msg'];
			// echo json_encode(['status' => $status, 'msg' => $data['msg'], 'data' => $data['data']]);
			// exit();
			$this->load->helper('download');
			force_download($filename, $data['data']);
		} else {
			$status = false;
		}
	}


	public function show()
	{
		isAjaxRequestWithPost();
		$this->function_access('view');
		$this->_custom_button_on_table = [
			[
				'button' => 'detail',
				'type' => 'modal',
				'fullscreen' => TRUE,
				'url' => base_url() . "sales_orders/detail/$1",
			],
		];

		$button = $this->setButtonOnTable();

		$detail = substr($button, 0, strpos($button, '</button>') + 9);
		echo $this->_model->show($detail);
	}

	public function process()
	{
		isAjaxRequestWithPost();
		if (!empty($this->input->post('id'))) {
			$this->function_access('update');
		} else {
			$this->function_access('insert');
		}

		$response = $this->sales_order_model->save();
		echo json_encode($response);
		exit();
	}

	public function sync_order()
	{

		isAjaxRequestWithPost();

		try {
			$shop_id = $this->_model->get_shop_id_by_channel($_POST['channel_id']);
			if (!$shop_id) {
				throw new Exception('Channel Not Registered');
			}
			$arr = (object)[
				"shop_id" => $shop_id->shop_id,
				"data" => (object)
				[
					"ordersn" => $_POST['local_order_id']
				]

			];

			$data = $this->orders->process_order($arr, 0);
			if ($data) {
				$response = ['status' => true, 'msg' => "success sync order"];
			} else {
				throw new Exception("Error sync order");
			}

			echo json_encode($response);
			exit();
		} catch (Exception $e) {
			$response = ['status' => false, 'msg' => $e->getMessage()];
			echo json_encode($response);
			exit();
		}
	}
}