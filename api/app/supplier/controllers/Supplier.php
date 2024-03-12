<?php
require_once APPPATH . '/libraries/REST_Controller.php';

class Supplier extends REST_Controller 
{
	public function __construct()
    {
        parent::__construct();
		$this->load->helper('api');
		$this->load->model('Supplier_model');
		$this->model = $this->Supplier_model;
		$this->load->library('jwt');
    }


	public function index_post()
	{
		$check = check_header($this->input);

		if($check['error'] === true){
			$this->response($check,$check['status_code']);
		}
		
		
		$data = json_decode($this->input->raw_input_stream, true);
		
		$insert = $this->model->insert($data,$check['access_token']);

		$this->response($insert, $insert['status_code']);
		
	}
	

}