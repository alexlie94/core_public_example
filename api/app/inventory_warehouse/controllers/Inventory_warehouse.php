<?php
require_once APPPATH . '/libraries/REST_Controller.php';

class Inventory_warehouse extends REST_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('api');
		$this->load->model('Inventory_warehouse_model');
		$this->model = $this->Inventory_warehouse_model;
		$this->load->library('jwt');
	}

	public function receiving_post()
	{
		$check = check_header($this->input);

		if ($check['error'] === true) {
			$this->response($check, $check['status_code']);
		}

		$data = json_decode($this->input->raw_input_stream, true);

		$insert = $this->model->receiving_insert($data, $check['access_token']);

		$this->response($insert, $insert['status_code']);
	}

	public function putaway_post()
	{
		$check = check_header($this->input);

		if ($check['error'] === true) {
			$this->response($check, $check['status_code']);
		}

		$data = json_decode($this->input->raw_input_stream, true);

		$insert = $this->model->putaway_insert($data, $check['access_token']);

		$this->response($insert, $insert['status_code']);
	}

	public function storage_post()
	{
		$check = check_header($this->input);

		if ($check['error'] === true) {
			$this->response($check, $check['status_code']);
		}

		$data = json_decode($this->input->raw_input_stream, true);

		$insert = $this->model->storage_insert($data, $check['access_token']);

		$this->response($insert, $insert['status_code']);
	}

	public function picking_post()
	{
		$check = check_header($this->input);

		if ($check['error'] === true) {
			$this->response($check, $check['status_code']);
		}

		$data = json_decode($this->input->raw_input_stream, true);

		$insert = $this->model->picking_insert($data, $check['access_token']);

		$this->response($insert, $insert['status_code']);
	}

	public function packing_post()
	{
		$check = check_header($this->input);

		if ($check['error'] === true) {
			$this->response($check, $check['status_code']);
		}

		$data = json_decode($this->input->raw_input_stream, true);

		$insert = $this->model->packing_insert($data, $check['access_token']);

		$this->response($insert, $insert['status_code']);
	}

	public function shipping_post()
	{
		$check = check_header($this->input);

		if ($check['error'] === true) {
			$this->response($check, $check['status_code']);
		}

		$data = json_decode($this->input->raw_input_stream, true);

		$insert = $this->model->shipping_insert($data, $check['access_token']);

		$this->response($insert, $insert['status_code']);
	}

	public function last_update_get()
	{
		$check = check_header($this->input);
		if ($check['error'] === true) {
			$this->response($check, $check['status_code']);
		}
		$param = $this->input->get('param');
		switch ($param) {
			case 'receiving':
				$table = 'users_ms_inventory_receiving';
				break;
			case 'putaway':
				$table = 'users_ms_inventory_putaway';
				break;
			case 'storage':
				$table = 'users_ms_inventory_storages';
				break;
			case 'picking':
				$table = 'users_ms_inventory_picking';
				break;
			case 'packing':
				$table = 'users_ms_inventory_packing';
				break;
			case 'shipping':
				$table = 'users_ms_inventory_shipping';
				break;
		}
		$data = $this->model->get_last_update_from_table($table, $check['access_token']);
		$this->response($data, $data['status_code']);
	}
}
