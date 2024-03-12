<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Tiktok_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
		$id = 4; //Tiktok ID
    }
	
	public function get_source()
    {
		
		date_default_timezone_set('UTC');

		$query = $this->db->query("SELECT 
										t1.id as id_source,
										t1.source_url,
										t1.app_keys,
										t1.secret_keys,
										t2.id as id_auth,
										t2.refresh_token
									FROM admins_ms_sources t1
									LEFT JOIN users_ms_authenticate_channels t2 ON t1.id = t2.sources_id
									WHERE t1.app_keys != ''
									AND t1.secret_keys != ''
									AND t1.status = 1
									AND t1.deleted_at IS NULL
									AND t2.deleted_at IS NULL
									AND t2.refresh_token_expire < UNIX_TIMESTAMP() + 7200
									AND t2.refresh_token_expire != 0
									ORDER BY t2.refresh_token_expire ASC
									LIMIT 5
								   ");
		return $query->result();
    }

	public function refresh_token()
	{
		$this->db->trans_begin();
		try {
			$source_data = $this->get_source();
			if(!$source_data){
				throw new Exception('No expired source');
			}

			foreach($source_data as $source)
			{
				switch($source->id_source) {
					// Tiktok
					case 4 :
						$app_keys    	= $source->app_keys;
						$secret_keys  	= $source->secret_keys;
						$refresh_token  = $source->refresh_token;
						$grant_type 	= 'refresh_token';
						$path       	= "/api/v2/token/refresh";
						$host       	= $source->source_url;
	
						$param = array(
							"app_key"       => $app_keys,
							"refresh_token" => $refresh_token,
							"app_secret"    => $secret_keys,
							"grant_type"    => $grant_type,
						);
					
						$url     		= create_url($host, $path, $param);
						$data_return    = get_request_curl($url);
						
						if(!$data_return){
							throw new Exception('Something wrong with Request');
						}
						// print_r($data_return);
						
						if($data_return->code > 0 ){
							$access = array(
								'message' => $data_return->message,
							);
						}else{
							$data = $data_return->data;

							$access = array(
											'refresh_token' => $data->refresh_token,
											'refresh_token_expire' => $data->refresh_token_expire_in,
											'access_token' => $data->access_token,
											'access_token_expire' => $data->access_token_expire_in,
											'message' => $data_return->message,
											'status' => 1,
										);
						}

						$this->db->where('id',$source->id_auth);
						$this->db->update('users_ms_authenticate_channels',$access);
	
					break;
	
					default :
						echo"Auth Source ".$source->id_auth." is not configured";
					break;
				}
			}
			

			$this->db->trans_commit();	
			$response['success'] = true;
			$response['message'] = "success";
            return $response;
		} catch(Exception $e){
			$this->db->trans_rollback();
			$response['success'] = false;
			$response['message'] = $e->getMessage();
			return $response;
		}

	}

}
