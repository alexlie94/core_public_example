<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Brand_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
		$this->load->helper('api');
		$this->max_data = 100;
    }

	public function insert($param,$token)
	{
		try {

			if(count($param) > $this->max_data){
				throw new Exception("Maximum data is ".$this->max_data."", 400); 
			}

			$this->db->trans_start();

			foreach($param as $row){
				$data = array();
				$data['brand_code'] = $row['brand_code'];
				$data['brand_name'] = $row['brand_name'];
				$data['description'] = $row['description'];

				$validation = form_validation($data);

				$token_data = get_jwt_data($token);

				$data['users_ms_companys_id'] = $token_data->company_id;
				$data['created_by'] 		  = $token_data->fullname;
				$data['updated_by'] 		  = $token_data->fullname;

				if($validation['error'] === true){
					throw new Exception($validation['message'], 400); 
				}

				$insert = $this->db->insert('ms_brands',$data);
				
				if(!$insert){
					throw new Exception("Internal Server Error", 500); 
				}

			}

			$this->db->trans_complete();

			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				return FALSE;
			} 
			else {
				return api_response(false,200,'OK');
				$this->db->trans_commit();
				return TRUE;
			}
			
			

		} catch (Exception $e) {
            $error_message 	= $e->getMessage();
			$error_code 	= $e->getCode();
			
			$response = api_response(true,$error_code,$error_message);
			return $response;
        }
		


		
	}	

   
}
