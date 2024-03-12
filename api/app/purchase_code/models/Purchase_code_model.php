<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Purchase_code_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
		$this->load->helper('api');
		$this->max_data = 100;
		$this->table = 'users_ms_inventory_requisition_headers';
		$this->table_detail = 'users_ms_inventory_requisition_details';
		$this->t_suppliers = 'users_ms_suppliers';
		$this->t_brands = 'users_ms_brands';
		$this->t_warehouses = 'users_ms_warehouses';
		$this->t_ownership_types = 'users_ms_ownership_types';
		$this->t_products = 'users_ms_products';
		$this->t_product_variants = 'users_ms_product_variants';
		$this->t_inventory_storages_logs = 'users_ms_inventory_storages_logs';
		$this->t_inventory_storages = 'users_ms_inventory_storages';
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

				$data = array();
				$data['referensi_number'] = isset($row['po_number']) ? $row['po_number'] : '';

				$unique_field = array(
					'from_table' => $this->table, 
					'unique_field' => 'referensi_number' 
				);

				$validation = form_validation($data,$token_data->company_id,$unique_field);

				if($validation['error'] === true){
					throw new Exception($validation['message'], 400); 
				}

				$supplier_name = isset($row['supplier_name']) ? $row['supplier_name'] : null;
				$id_supplier = $this->get_id_from_table($this->t_suppliers,$token_data->company_id,'supplier_name',$supplier_name);

				if(!$id_supplier){
					throw new Exception('Supplier '.$supplier_name.' not registed', 400); 
				}

				$brand_name = isset($row['brand_name']) ? $row['brand_name'] : null;
				$id_brand = $this->get_id_from_table($this->t_brands,$token_data->company_id,'brand_name',$brand_name);

				if(!$id_brand){
					throw new Exception('Brand '.$brand_name.' not registed', 400); 
				}

				$warehouse_name = isset($row['warehouse_name']) ? $row['warehouse_name'] : null;
				$id_warehouse = $this->get_id_from_table($this->t_warehouses,$token_data->company_id,'warehouse_name',$warehouse_name);

				if(!$id_warehouse){
					throw new Exception('Warehouse '.$warehouse_name.' not registed', 400); 
				}

				$ownership_type_name = isset($row['ownership_type_name']) ? $row['ownership_type_name'] : null;
				$id_ownership_type = $this->get_id_from_table($this->t_ownership_types,$token_data->company_id,'ownership_type_name',$ownership_type_name);

				if(!$id_ownership_type){
					throw new Exception('Ownership Type '.$ownership_type_name.' not registed', 400); 
				}

				
				$data['po_number'] = isset($row['po_number']) ? $row['po_number'] : '';
				$data['users_ms_suppliers_id'] 			= $id_supplier;
				$data['users_ms_brands_id'] 			= $id_brand;
				$data['users_ms_warehouses_id'] 		= $id_warehouse;
				$data['users_ms_ownership_types_id'] 	= $id_ownership_type;
				$data['description'] 					= isset($row['description']) ? $row['description'] : null;
				$data['status'] 						= 2; // release
 				$data['po_type'] 						= 1; // api

				$data['users_ms_companys_id'] = $token_data->company_id;
				$data['created_by'] 		  = $token_data->fullname;
				$data['updated_by'] 		  = $token_data->fullname;

				$insert_header = $this->db->insert($this->table ,$data);
					
				if(!$insert_header){
					throw new Exception("Internal Server Error", 500); 
				}

				$id_header = $this->db->insert_id();

				foreach($row['product'] as $row_detail){
					$data_detail = array();
					$data_detail['sku'] = isset($row_detail['sku']) ? $row_detail['sku'] : null;
					$data_detail['type'] = isset($row_detail['type']) ? $row_detail['type'] : null;
					$data_detail['quantity'] = isset($row_detail['quantity'])  ? $row_detail['quantity'] : 0;
					$data_detail['price'] = isset($row_detail['price']) ? $row_detail['price'] : 0;
					$data_detail['material_cost'] = isset($row_detail['material_cost']) ? $row_detail['material_cost'] : 0;
					$data_detail['service_cost'] = isset($row_detail['service_cost']) ? $row_detail['service_cost'] : 0;
					$data_detail['overhead_cost'] = isset($row_detail['overhead_cost']) ? $row_detail['overhead_cost'] : 0;
	
					$validation_detail = form_validation($data_detail,$token_data->company_id);

					if($validation['error'] === true){
						throw new Exception($validation['message'], 400); 
					}
					
					$query = $this->db->query("SELECT
													t1.category_name,
													t1.id product_id,
													t1.product_name product_name,
													t1.brand_name,
													t2.id product_variant_id,
													t2.product_size,
													t2.color,
													t2.product_size
											   FROM {$this->t_products} t1
											   INNER JOIN {$this->t_product_variants} t2
											   WHERE t2.SKU = '{$row_detail['sku']}'
											   AND t2.users_ms_companys_id = {$token_data->company_id}
											 ");
					$product = $query->row_array();

					$data_detail['category_name'] 			= isset($product['category_name']) ? $product['category_name'] : null;
					$data_detail['users_ms_products_id'] 	= isset($product['product_id']) ? $product['product_id'] : null;
					$data_detail['product_name'] 			= isset($product['product_name']) ? $product['product_name'] : null;
					$data_detail['brand_name'] 				= isset($product['brand_name']) ? $product['brand_name'] : null;
					$data_detail['color'] 					= isset($product['color']) ? $product['color'] : null;
					$data_detail['product_size'] 			= isset($product['product_size']) ? $product['product_size'] : null;

					$data_detail['description'] = isset($row_detail['description']) ? $row_detail['description'] : null;
					$data_detail['users_ms_inventory_requisition_headers_id'] 	= $id_header;
					$data_detail['users_ms_companys_id'] 						= $token_data->company_id;
					$data_detail['created_by'] 		  							= $token_data->fullname;
					$data_detail['updated_by'] 		  							= $token_data->fullname;
	
					if($validation_detail['error'] === true){
						throw new Exception($validation_detail['message'], 400); 
					}
	
					$insert_detail = $this->db->insert($this->table_detail ,$data_detail);
					
					if(!$insert_detail){
						throw new Exception("Internal Server Error", 500); 
					}

					$query = $this->db->query("SELECT
													t1.qty
											   FROM {$this->t_inventory_storages} t1
											   WHERE t1.SKU = '{$row_detail['sku']}'
											   AND t1.users_ms_companys_id = {$token_data->company_id}
											 ");
					$inventory = $query->row_array();

					if(!$inventory){
						throw new Exception("Internal Server Error", 500); 
					}

					$qty_new = $row_detail['quantity'] + $inventory['qty'];


					$data_storage = array();
					$data_storage['users_ms_companys_id'] 	= $token_data->company_id;
					$data_storage['users_ms_product_variants_id'] 			= $product['product_variant_id'];
					$data_storage['sku'] 					= $row_detail['sku'];
					$data_storage['qty'] 					= $qty_new;
					$data_storage['trx_number'] 			= $row['po_number'];
					$data_storage['users_ms_warehouses_id'] = $id_warehouse;
					// $data_storage['trx_type'] 				= 4 ; //purchase_order
					$data_storage['created_by'] 			= $token_data->fullname;
					$data_storage['updated_by'] 			= $token_data->fullname;

					$insert_storage = $this->db->insert($this->t_inventory_storages ,$data_storage);

					if(!$insert_storage){
						throw new Exception("Internal Server Error", 500); 
					}
					
					$data_storage_log = array();
					$data_storage_log['users_ms_companys_id'] 	= $token_data->company_id;
					$data_storage_log['trx_number'] 			= $row['po_number'];
					$data_storage_log['trx_type'] 				= 4 ; //purchase_order
					$data_storage_log['sku'] 					= $row_detail['sku'];
					$data_storage_log['qty_trx'] 				= $row_detail['quantity'];
					$data_storage_log['qty_old'] 				= $inventory['qty'];
					$data_storage_log['qty_new'] 				= $qty_new;
					$data_storage_log['created_by'] 			= $token_data->fullname;
					$data_storage_log['updated_by'] 			= $token_data->fullname;

					
					$insert_storage_log = $this->db->insert($this->t_inventory_storages_logs,$data_storage_log);

					if(!$insert_storage_log){
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

	function get_id_from_table($table,$company_id,$field,$value)
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

	function get_id_from_table_multiple_param($table,$company_id,$data){
		
		$selectData = array();
		foreach ($data as $column => $value) {
			if($value !== ''){
				$selectData[] = "$column = '$value'";
			}else{
				return false;
			}
		}
		$condition = implode(' AND ', $selectData);

		$query = $this->db->query("SELECT * 
								   FROM {$table} 
								   WHERE users_ms_companys_id = {$company_id} 
								   AND ({$condition})"
								 );
		$result = $query->result();

		if(!$result){
			return false;
		}else{
			return $result;
		}

	}
}