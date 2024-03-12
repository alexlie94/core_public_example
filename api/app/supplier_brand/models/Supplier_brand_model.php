<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Supplier_brand_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
		$this->load->helper('api');
		$this->max_data = 100;
		$this->table = 'users_ms_supplier_brands';
		$this->t_suppliers = 'users_ms_suppliers';
		$this->t_brands = 'users_ms_brands';
		$this->t_ownership_types = 'users_ms_ownership_types';
    }

	public function insert($param,$token)
	{
		try {

			if(count($param) > $this->max_data){
				throw new Exception("Maximum data is ".$this->max_data."", 400); 
			}

			$this->db->trans_start();

			$token_data = get_jwt_data($token);
			
			foreach($param as $row){
				$supplier_name = isset($row['supplier_name']) ? $row['supplier_name'] : null;
				$id_supplier = $this->get_id_from_table($this->t_suppliers,$token_data->company_id,'supplier_name',$supplier_name);

				if(!$id_supplier){
					throw new Exception('Supplier '.$supplier_name.' not registed', 400); 
				}

				foreach($row['brand'] as $row_detail){
					$brand_code = isset($row_detail['brand_code']) ? $row_detail['brand_code'] : null;
					$id_brand = $this->get_id_from_table($this->t_brands,$token_data->company_id,'brand_code',$brand_code);
					
					if(!$id_brand){
						throw new Exception('Brand Code'. $brand_code .' not registed', 400); 
					}

					$type_ownership = isset($row_detail['type_ownership']) ? $row_detail['type_ownership'] : null;
					$id_ownership = $this->get_id_from_table($this->t_ownership_types,$token_data->company_id,'ownership_type_code',$type_ownership);
					
					if(!$id_ownership){
						throw new Exception('Ownership Type Code'. $type_ownership .' not registed', 400); 
					}

					$data = array();
					$data['users_ms_suppliers_id'] = $id_supplier;
					$data['users_ms_brands_id'] = $id_brand;
					$data['users_ms_ownership_types_id'] = $id_ownership;
					
					// $unique_field = array('from_table' => $this->table, 'unique_field' => array('users_ms_suppliers_id','users_ms_brands_id','users_ms_ownership_types_id'));

					$validation = form_validation($data);

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

	function get_id_from_table($table,$company_id,$field, $value )
	{
		$this->db->where($field, $value);
		$this->db->where('users_ms_companys_id', $company_id);
		$query = $this->db->get($table); 
		$result = $query->row();
		

		if ($result) {
			return $result->id; 
		} else {
			return false; 
		}
	}
   
}