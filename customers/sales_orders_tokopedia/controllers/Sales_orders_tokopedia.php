<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sales_orders_tokopedia extends MY_Customers
{

	protected $source = 3;
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
		$this->source = 3;
		$this->_model = $this->sales_orders_tokopedia_model;
		$this->load->library('tokopedia/orders');
	}

	public function index()
	{
		$this->template->title('Sales Orders - Tokopedia');
		$this->setTitlePage('Sales Orders - Tokopedia');

		$data = array(
			'channel' => $this->_model->get_channel_by_source($this->source),
			'channel_status' => $this->_model->get_channel_status($this->source),
			// 'all_data' => $this->_model->get_all_data($this->source)
		);

		$this->setJs('index');
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

		$this->setJs('_tokopedia');
		$this->template->build('v_detail', $data);
	}


	public function form_create_shipment($id)
	{
		isAjaxRequestWithPost();
		$get['detail'] = $this->_model->get_detail_order($id);

		$data = [
			'title_modal' => 'Create Shipment',
			'url_form' => base_url() . 'sales_orders_tokopedia/process',
			'buttonCloseID' => 'btnCloseCreateShipment',
			'buttonID' => 'btnAddCreateShipment',
			'buttonName' => 'Create Shipment',
			'form' => $this->load->view('sales_orders_tokopedia/v_create_shipment', $get, true)
		];

		$html = $this->load->view($this->_v_form_modal, $data, true);

		echo json_encode(['html' => $html]);
		exit();
	}

	public function add_create_shipment()
	{
		isAjaxRequestWithPost();

		$local_order_id = $_POST['local_order_id'];
		$channel_id = $_POST['channel_id'];

		$data = $this->orders->create_shipment($local_order_id, $channel_id);

		echo json_encode($data);
		exit();
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
				'url' => base_url() . "sales_orders_tokopedia/detail/$1",
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


	public function print_label($order_id, $channel_id)
	{
		try {

			$data = $this->orders->get_shipping_label($order_id, $channel_id);

			if ($data['status']) {
				echo $data['data'];
			} else {
				pre($data);
			}
		} catch (Exception $e) {
		}
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
				"order_id" => $_POST['local_order_id']

			];

			$data = $this->orders->process_order($arr, 0);
			if ($data) {
				$response = ['status' => true, 'msg' => "success sync order"];
			} else {
				throw new Exception("Something went wrong while syncing the order");
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
