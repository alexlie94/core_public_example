<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
		
		$this->load->model('Tiktok_model');
		$this->model = $this->Tiktok_model;
    }

    public function index()
    {
       echo"tiktok cron";
    }

	public function get_category(){
		$action =  $this->model->get_category();

		print_r($action);
	}
}
