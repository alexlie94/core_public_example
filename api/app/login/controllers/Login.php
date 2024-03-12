<?php
require_once APPPATH . '/libraries/REST_Controller.php';

class Login extends REST_Controller
{

	public function __construct()
    {
        parent::__construct();
		$this->load->helper('api');
		$this->load->model('Login_model');
		$this->model = $this->Login_model;
    }


	public function index_post(){
		$verify = $this->model->get_access_login($_POST);
		$this->response($verify, $verify['status_code']);
	}

}