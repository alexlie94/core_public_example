<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{

	protected $model;

	public function __construct()
	{
		parent::__construct();

		$this->load->model('Auth_model');
		$this->model = $this->Auth_model;
	}

	public function index()
	{
		echo "auth";
	}

	public function shopee($channel_id)
	{
		$auth = $this->model->auth_shopee($channel_id);
		if ($auth['success'] === true) {
			$this->session->set_flashdata('msg_auth_success', $auth['messages']);
			redirect(BASE_URL . '/integrations/success_page');
		} else {
			$this->session->set_flashdata('msg_auth_error', $auth['messages']);
			redirect(BASE_URL . '/integrations/error_page');
		}
	}

}
