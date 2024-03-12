<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Product_model_api
{

	protected $ci;
	protected $db;

	public function __construct()
	{
		$this->ci = &get_instance();
		$this->db = $this->ci->db;
	}

	public function get_item_id($item_id)
	{
		$query = $this->db->query(
					"	SELECT item_id
						FROM users_ms_product_shopee 
						WHERE item_id = {$item_id} ");

        $result = $query->row();

        return $result;
	}

	public function check_all_data_by_channel_id($channel_id)
	{
		 $query = $this->db->query(
					"SELECT
						t1.id as source_id,
						t1.source_url,
						t1.source_auth_url,
						t1.app_keys,
						t1.secret_keys,
						t2.id as auth_id,
						t2.users_ms_companys_id as company_id,
						t2.channels_id as channel_id,
						t2.shop_id,
						t2.access_token
					FROM admins_ms_sources t1
					INNER JOIN users_ms_authenticate_channels t2
							ON t1.id = t2.sources_id
							AND t2.status = 1
							AND t2.deleted_at IS NULL
					INNER JOIN admins_ms_endpoints t3
							ON t1.id = t3.admins_ms_sources_id
							AND t3.endpoint_url  = 'get_master'
							AND t3.status = 1
							AND t3.deleted_at IS NULL
					INNER JOIN admins_ms_company_endpoints t4
							ON t2.users_ms_companys_id = t4.users_ms_companys_id
							AND t3.id = t4.admins_ms_endpoints_id
							AND t4.status = 1
							AND t4.deleted_at IS NULL
					WHERE t2.channels_id = {$channel_id}
					AND t1.status = 1
					AND t1.deleted_at IS NULL");

        $result = $query->row();

        return $result;
	}

	public function save_product($users_ms_product_publishes_id,$response_data)
	{
		$this->db->trans_begin();
        try {
			$get_response = $response_data;

			$data_insert =
			[
				"users_ms_product_publishes_id" => $users_ms_product_publishes_id,
				"item_id" => $get_response->item_id,
				"category_id" => $get_response->category_id,
				"item_name" => $get_response->item_name,
				"description" => $get_response->description,
				"price_info" => json_encode($get_response->price_info),
				"images" => json_encode($get_response->images),
				"weight" => $get_response->weight,
				"dimension" => json_encode($get_response->dimension),
				"logistic_info" => json_encode($get_response->logistic_info),
				"pre_order" => json_encode($get_response->pre_order),
				"condition" => $get_response->condition,
				"item_status" => $get_response->item_status,
				"brand" => json_encode($get_response->brand),
				"description_type" => $get_response->description_type,
				"seller_stock" => json_encode($get_response->seller_stock)
			];

			$execute = $this->db->insert('users_ms_product_shopee',$data_insert);
            
			if (!$execute) {
				$response['messages'] = 'Data Insert Invalid';
				throw new Exception();
			}
			
            $this->db->trans_commit();
            return true;
        } catch (Exception $e) {
            $this->db->trans_rollback();
            return false;
        }
	}

	public function save_product_variant($response_data)
	{
		$this->db->trans_begin();
        try {
			$get_response = $response_data;

			$data_insert =
			[
				"item_id" => intVal($get_response->item_id),
				"tier_variation" => json_encode($get_response->tier_variation),
				"model" => json_encode($get_response->model)
			];

			$execute = $this->db->insert('users_ms_product_variant_shopee',$data_insert);
            
			if (!$execute) {
				$response['messages'] = 'Data Insert Invalid';
				throw new Exception();
			}
			
            $this->db->trans_commit();
            return true;
        } catch (Exception $e) {
            $this->db->trans_rollback();
            return false;
        }
	}
}