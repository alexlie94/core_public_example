<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Supplier_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
		$this->load->helper('api');
		$this->max_data = 100;
		$this->table = 'users_ms_suppliers';
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
				$data['supplier_name'] 	= isset($row['supplier_name']) ? $row['supplier_name'] : '';
				$data['email'] 			= isset($row['email']) ? $row['email'] : '';
				$data['address'] 		= isset($row['address']) ? $row['address'] : '';
				$data['phone'] 			= isset($row['phone']) ? $row['phone'] : '';

				$unique_field = array('from_table' => $this->table, 'unique_field' => 'supplier_name');

				$token_data = get_jwt_data($token);

				$validation = form_validation($data,$token_data->company_id,$unique_field);

<<<<<<< HEAD
=======
				$data['supplier_code'] 		  = isset($row['supplier_code']) ? $row['supplier_code'] : null;
>>>>>>> dev-api-2
				$data['users_ms_companys_id'] = $token_data->company_id;
				$data['created_by'] 		  = $token_data->fullname;
				$data['updated_by'] 		  = $token_data->fullname;

				if($validation['error'] === true){
					throw new Exception($validation['message'], 400); 
				}

				$insert = $this->db->insert($this->table,$data);
				
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