<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
		$this->load->helper('api');
		$this->table = 'users';
    }

	public function get_access_login($param)
	{
		$email 		= clearInput($param['email']);
		$password 	= clearInput($param['password']);

		try {

			$query = $this->db->query("SELECT * 
									   FROM {$this->table} t1
									   WHERE email = '{$email}'
									   AND user_api = 1
									   AND status = 1
									");
			$result = $query->row();

			if (!$result) {
                throw new Exception("Email Not Registered", 401); 
            }

            if (!password_verify($password, $result->password)) {
                throw new Exception("Password Not Match", 401); 
            }
			$token = generate_jwt(array(
										'user_id' => $result->id,
										'company_id' => $result->users_ms_companys_id,
										'fullname' => $result->fullname,
									),
								 );

			$this->db->where('id',$result->id);
			$update = $this->db->update($this->table,array('api_token' =>$token));

			if($update < 1){
                throw new Exception("Internal Server Error", 500); 
			}
		

			$response = api_response(false,200,'OK',array(),$token);

			return $response;

        } catch (Exception $e) {
            $error_message 	= $e->getMessage();
			$error_code 	= $e->getCode();
			
			$response = api_response(true,$error_code,$error_message);
			return $response;
        }
		
	}	

   
}