<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login extends MY_Logintemplate
{

	public function __construct()
	{
		$this->_background_login = MEDIA.'/misc/auth-bg.png';
		$this->_form_image =  MEDIA.'/custom/form_image.png';
		$this->_logo_image =  MEDIA.'/custom/gc_logo.png';
		parent::__construct();
		$this->load->model('Login_model', 'loginmodel');
	}

	public function index()
	{
		$this->template->title('Login IMS Integrations');
		$this->template->build('v_login');
	}
	
	public function check()
	{

		isAjaxRequestWithPost();
		$response = $this->loginmodel->_check();
		echo json_encode($response);
		exit();

	}

}