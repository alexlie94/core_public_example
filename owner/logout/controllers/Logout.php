<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Logout extends MY_Owner
{

	public function __construct()
	{
		$this->_controller_except = 'logout';
		parent::__construct();
	}

    public function index()
    {
        $this->session->sess_destroy();
		redirect('login');
    }
}
