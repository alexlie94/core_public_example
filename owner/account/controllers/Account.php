<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Account extends MY_Owner
{

	public function __construct()
	{
		$this->_controller_except = 'account';
		$this->_function_except = ['index', 'process'];
		parent::__construct();
	}

	public function Index()
	{
		isAjaxRequestWithPost();
		$this->load->model('users/Users_model', 'users_model');
		$get['Account'] = $this->users_model->getProfil();
		$data = array(
			'title_modal' => 'Account Setting',
			'url_form' => base_url() . "account/process",
			'content' => $this->load->view('v_form', $get, true),
			'buttonID' => 'btnProcessModalAccount',
		);
		$html = $this->load->view($this->_v_modal, $data, true);

		echo json_encode(array('html' => $html));
		exit();
	}

	public function process()
	{
		isAjaxRequestWithPost();
		$response = $this->account_model->save();
		echo json_encode($response);
		exit();
	}
}
