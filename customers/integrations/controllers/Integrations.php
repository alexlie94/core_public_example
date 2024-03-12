<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Integrations extends MY_Customers
{

	public function __construct()
	{
		$this->_function_except = ['account', 'settings', 'connect', 'manage_list', 'change_status', 'success_page', 'error_page'];
		parent::__construct();
	}

	public function Index()
	{
		$this->template->title('Integrations');
		$this->setTitlePage('Integrations');

		$data = array(
			'all_data' => $this->integrations_model->get_data(),
		);
		// echo '<pre>';
		// print_r($data);
		// die;
		$this->setJs('integrations');
		$this->template->build('v_form', $data);
	}

	public function success_page()
	{
		$this->load->view('v_success_page');
	}

	public function error_page()
	{
		$this->load->view('v_error_page');
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

	public function connect()
	{
		// isAjaxRequestWithPost();
		$process = $this->integrations_model->connect();

		echo json_encode($process);
	}

	public function manage_list($id_source)
	{
		isAjaxRequestWithPost();

		$get = array(
			'all_data' => $this->integrations_model->get_endpoints($id_source)
		);

		$data = array(
			'title_modal' => 'Manage',
			'url_form' => base_url() . "product_image/process",
			'form' => $this->load->view('integrations/v_manage_list', $get, true),
			'buttonSave' => false
		);
		$html = $this->load->view($this->_v_form_modal, $data, true);

		echo json_encode(array('html' => $html));
		exit();
	}

	public function change_status()
	{
		isAjaxRequestWithPost();
		$process = $this->integrations_model->change_status();

		echo json_encode($process);
	}
}