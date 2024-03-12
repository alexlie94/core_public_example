<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends MY_Logintemplate {

    public function __construct()
    {
        $this->_background_login = MEDIA.'/auth/bg11.png';
        $this->_js = JS_LOGIN_OWNER;
        $this->_login = 'login2';
        $this->_logo_image = MEDIA."/logos/default.svg";
        parent::__construct();
        $this->load->model('Login_model','loginmodel');
    }

    public function index()
    {
        $this->template->title('Login Admin');
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