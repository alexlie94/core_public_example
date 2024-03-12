<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Product_publish_model extends MY_ModelCustomer
{
	use MY_Tables;
	public function __construct()
	{
		parent::__construct();
		$this->load->library('queue');
	}

	public function getDataProductVariants($product_id)
	{
		$this->db->select("	t0.sku,
							t0.price,
							t0.product_size,
							t1.qty,
							(SELECT color_name  FROM ms_color_name_hexa WHERE id = t0.variant_color_id) as variant_color", false);
		$this->db->from("{$this->_table_products_variants} t0");
		$this->db->join("users_ms_inventory_storages t1", "t1.sku = t0.sku", "left");
		$this->db->join("users_ms_product_images t2", "t2.users_ms_products_id = t0.users_ms_products_id
						AND t2.general_color_id = t0.general_color_id 
						AND t2.variant_color_id = t0.variant_color_id", "left");
		$this->db->where('t0.deleted_at is null', null, false);
		$this->db->where(["t0.{$this->_table_users_ms_companys}_id" => $this->_users_ms_companys_id]);
		$this->db->where('t0.users_ms_products_id', $product_id);
		$this->db->group_by('t0.sku,t0.product_size');
		$this->db->order_by('t0.sku asc');

		return $this->db->get();
	}

	public function getDataVariantsColor($pid)
	{
		$this->db->select(
			"	users_ms_products_id ,
							image_name ,
							(SELECT color_name  FROM ms_color_name_hexa WHERE id = general_color_id) as default_color,
							(SELECT color_name  FROM ms_color_name_hexa WHERE id = variant_color_id) as variant_color",
			false
		);
		$this->db->from("{$this->_table_products_images}");
		$this->db->where('deleted_at is null', null, false);
		$this->db->where(["{$this->_table_users_ms_companys}_id" => $this->_users_ms_companys_id]);
		$this->db->where('users_ms_products_id', $pid);
		$this->db->group_by('general_color_id ,variant_color_id');

		return $this->db->get();
	}

	public function showImageNotDefault($productID)
	{
		$this->db->select('*');
		$this->db->from("(SELECT a.id, a.image_name as image, a.image_name as image_name, IFNULL(b.image_status, 1) as status_id, 
								(SELECT lookup_name FROM admins_ms_lookup_values WHERE lookup_code = IFNULL(b.image_status, 1) AND lookup_config = 'inventory_display_images') AS status_name,
								a.users_ms_products_id,
								d.source_name,
								e.channel_name 
							FROM {$this->_table_products_images} a
							LEFT JOIN {$this->_table_users_ms_inventory_display_details} b 
								ON b.users_ms_product_images_id = a.id 
								and b.users_ms_companys_id = a.users_ms_companys_id 
							INNER JOIN {$this->_table_products} c ON c.id = a.users_ms_products_id
							INNER JOIN admins_ms_sources d ON d.id = b.admins_ms_sources_id
							INNER JOIN users_ms_channels e ON e.id = b.users_ms_channels_id
							INNER JOIN users_ms_inventory_displays f ON f.id = b.users_ms_inventory_displays_id
							WHERE a.deleted_at is null
								AND a.users_ms_companys_id = {$this->_users_ms_companys_id}
								AND c.users_ms_companys_id = {$this->_users_ms_companys_id}
								AND f.display_status = 5
								AND a.users_ms_products_id = {$productID}
						) a");
		$this->db->where_in('status_name', ['Main', 'Selected']);
		$this->db->order_by('status_name', 'asc');

		$query = $this->db->get();
		return $query;
	}

	public function getImageList($source, $channel, $productID)
	{
		$this->db->select('*');
		$this->db->from("(SELECT a.id, a.image_name as image, a.image_name as image_name, IFNULL(b.image_status, 1) as status_id, 
								(SELECT lookup_name FROM admins_ms_lookup_values WHERE lookup_code = IFNULL(b.image_status, 1) AND lookup_config = 'inventory_display_images') AS status_name,
								a.users_ms_products_id,
								d.source_name,
								e.channel_name 
							FROM {$this->_table_products_images} a
							LEFT JOIN {$this->_table_users_ms_inventory_display_details} b 
								ON b.users_ms_product_images_id = a.id 
								and b.users_ms_companys_id = a.users_ms_companys_id 
							INNER JOIN {$this->_table_products} c ON c.id = a.users_ms_products_id
							INNER JOIN admins_ms_sources d ON d.id = b.admins_ms_sources_id
							INNER JOIN users_ms_channels e ON e.id = b.users_ms_channels_id
							INNER JOIN users_ms_inventory_displays f ON f.id = b.users_ms_inventory_displays_id
							WHERE a.deleted_at is null
								AND a.users_ms_companys_id = {$this->_users_ms_companys_id}
								AND c.users_ms_companys_id = {$this->_users_ms_companys_id}
								AND f.display_status = 5
								AND b.admins_ms_sources_id = {$source}
								AND b.users_ms_channels_id = {$channel}
								AND a.users_ms_products_id = {$productID}
						) a");
		$this->db->where_in('status_name', ['Main', 'Selected']);
		$this->db->order_by('status_name', 'asc');

		$query = $this->db->get();
		return $query;
	}

	public function data_show_display($pid = '')
	{
		$this->db->select(
			"	t1.id AS display_id,
				t1.brand_name,
				t1.category_name,
				(   select lookup_name 
                    from admins_ms_lookup_values 
                    where lookup_code = t1.status and lookup_config = 'products_status') as status_name,
				t1.product_name,
				group_concat(t2.source_name,'||',t3.channel_name) AS source_per_channel,
				(	SELECT group_concat(tx0.sku,'||',tx0.product_size,'||',tx1.color_hexa) 
					FROM users_ms_product_variants tx0 
					LEFT JOIN ms_color_name_hexa tx1 ON tx1.id = tx0.general_color_id
					WHERE tx0.users_ms_products_id = t1.id) AS sku,
				(	SELECT count(tx0.sku) 
					FROM users_ms_product_variants tx0 
					LEFT JOIN ms_color_name_hexa tx1 ON tx1.id = tx0.general_color_id
					WHERE tx0.users_ms_products_id = t1.id) AS total_sku,
				(	SELECT ts1.image_name 
				FROM users_ms_product_images ts1
				LEFT JOIN users_ms_inventory_display_details ts2 
					ON ts2.users_ms_product_images_id = ts1.id
					AND ts2.users_ms_companys_id = ts1.users_ms_companys_id 
					AND ts2.admins_ms_sources_id = t2.id
					AND ts2.users_ms_channels_id = t3.id
				WHERE ts2.image_status = 3
				LIMIT 1) as image_name",
			false,
		);

		$this->db->from("{$this->_table_ms_inventory_display1} t0");
		$this->db->join("{$this->_table_products} t1", "t1.id = t0.users_ms_products_id", "left");
		$this->db->join("{$this->_table_admins_ms_sources} t2", "t2.id = t0.admins_ms_sources_id", "left");
		$this->db->join("{$this->_table_users_ms_channels} t3", "t3.id = t0.users_ms_channels_id ", "left");
		$this->db->where('t0.deleted_at is null', null, false);
		$this->db->where(["t0.{$this->_table_users_ms_companys}_id" => $this->_users_ms_companys_id]);
		$this->db->where('t0.display_status', 5);

		if (!empty($pid)) {
			$this->db->where_in('t1.id', $pid);
		}

		$this->db->group_by('t0.users_ms_products_id');
		$this->db->order_by('t0.id desc');

		return $this->db->get();
	}

	public function data_show_display_single($source, $channel, $pid)
	{
		$this->db->select(
			"	t1.id AS display_id,
				t1.brand_name,
				t1.category_name,
				(   select lookup_name 
                    from admins_ms_lookup_values 
                    where lookup_code = t1.status and lookup_config = 'products_status') as status_name,
				t1.product_name,
				group_concat(t2.source_name,'||',t3.channel_name) AS source_per_channel,
				(	SELECT group_concat(tx0.sku,'||',tx0.product_size,'||',tx1.color_hexa) 
					FROM users_ms_product_variants tx0 
					LEFT JOIN ms_color_name_hexa tx1 ON tx1.id = tx0.general_color_id
					WHERE tx0.users_ms_products_id = t1.id) AS sku,
				(	SELECT count(tx0.sku) 
					FROM users_ms_product_variants tx0 
					LEFT JOIN ms_color_name_hexa tx1 ON tx1.id = tx0.general_color_id
					WHERE tx0.users_ms_products_id = t1.id) AS total_sku,
				(	SELECT ts1.image_name 
				FROM users_ms_product_images ts1
				LEFT JOIN users_ms_inventory_display_details ts2 
					ON ts2.users_ms_product_images_id = ts1.id
					AND ts2.users_ms_companys_id = ts1.users_ms_companys_id 
					AND ts2.admins_ms_sources_id = t2.id
					AND ts2.users_ms_channels_id = t3.id
				WHERE ts2.image_status = 3
				LIMIT 1) as image_name,
				t2.source_name,
				t3.channel_name",
			false,
		);
		$this->db->from("{$this->_table_ms_inventory_display1} t0");
		$this->db->join("{$this->_table_products} t1", "t1.id = t0.users_ms_products_id", "left");
		$this->db->join("{$this->_table_admins_ms_sources} t2", "t2.id = t0.admins_ms_sources_id", "left");
		$this->db->join("{$this->_table_users_ms_channels} t3", "t3.id = t0.users_ms_channels_id ", "left");
		$this->db->where('t0.deleted_at is null', null, false);
		$this->db->where(["t0.{$this->_table_users_ms_companys}_id" => $this->_users_ms_companys_id]);
		$this->db->where('t0.display_status', 5);
		$this->db->where('t1.id', $pid);
		$this->db->where('t2.id', $source);
		$this->db->where('t3.id', $channel);

		return $this->db->get();
	}

	public function manageDataDisplay()
	{

		$list = $this->data_show_display()->result();

		$rdata = [];
		foreach ($list as $vdata) {
			$data = array(
				'mode' 		 => 'add',
				'type' 		 => 'inv_display',
				'product_id' => $vdata->display_id,
				'form_type'  => 'multiple'
			);
			$params = json_encode($data);
			$row =
				[
					'id'   			 		=> $vdata->display_id,
					'product_name'   		=> $vdata->product_name,
					'status_product'    	=> $vdata->status_name,
					'source_per_channel'    => $vdata->source_per_channel,
					'sku'    				=> $vdata->sku,
					'total_sku'    			=> $vdata->total_sku,
					'image_name'    		=> $vdata->image_name,
					'brand_name'    		=> $vdata->brand_name,
					'category_name'    		=> $vdata->category_name,
					'params'				=> base64_encode($params)
				];

			$rdata[] = $row;
		}

		$output = [
			"draw" => 10,
			"recordsTotal" => 100,
			"recordsFiltered" => 10,
			"data" => $rdata
		];

		echo json_encode($output);
	}

	public function manageBrandListApi()
	{
		try {
			$response = [];
			$get_api = get_brand_list();
			$key = isset($_GET['q']) ? $_GET['q'] : '';

			if (empty($_GET['category_id'])) {
				$response['messages'] = 'Please Select Category First!!!';
				throw new Exception();
			}

			$data_brand_list = [];
			$rdata = [];

			foreach ($get_api->brand_list as $item) {

				$get_brand_name = strtolower($item->original_brand_name);
				$keys = strtolower($key);

				if (strpos($get_brand_name, $keys) !== false) {
					array_push($data_brand_list, $item);
				}
			}


			foreach ($data_brand_list as $data) {

				$row = array(
					'id'            => $data->brand_id,
					'name'          => $data->original_brand_name,
				);

				$rdata[] = $row;
			}

			$output = array(
				"items"         => $rdata
			);

			return $output;
		} catch (Exception $e) {
			return $response;
		}
	}

	private function _validate()
	{
		$response = ['success' => false, 'validate' => true, 'messages' => []];

		$response['type'] = 'insert';

		$role_validate = ['trim', 'required', 'xss_clean'];

		$this->form_validation->set_rules('category_Shopee', 'Category', $role_validate);
		$this->form_validation->set_rules('brand_Shopee', 'Brand', $role_validate);
		$this->form_validation->set_rules('condition_Shopee', 'Condition', $role_validate);
		$this->form_validation->set_rules('shipping_Shopee', 'Logistic', $role_validate);
		$this->form_validation->set_rules('desc_Shopee', 'Description', $role_validate);

		for ($i = 0; $i < count($this->input->post('sku')); $i++) {
			$this->form_validation->set_rules('price[' . $i . ']', 'Price', $role_validate);
			$this->form_validation->set_rules('weight[' . $i . ']', 'Weight', $role_validate);
			$this->form_validation->set_rules('length[' . $i . ']', 'Length', $role_validate);
			$this->form_validation->set_rules('width[' . $i . ']', 'Width', $role_validate);
			$this->form_validation->set_rules('height[' . $i . ']', 'Height', $role_validate);
		}

		$this->form_validation->set_error_delimiters('<div class="' . VALIDATION_MESSAGE_FORM . '">', '</div>');

		if ($this->form_validation->run() === false) {
			$response['validate'] = false;
			$response['messages']['category_Shopee'] = form_error('category_Shopee');
			$response['messages']['brand_Shopee'] = form_error('brand_Shopee');
			$response['messages']['condition_Shopee'] = form_error('condition_Shopee');
			$response['messages']['shipping_Shopee'] = form_error('shipping_Shopee');
			$response['messages']['desc_Shopee'] = form_error('desc_Shopee');
			for ($i = 0; $i < count($this->input->post('sku')); $i++) {
				$response['messages']['price[' . $i . ']'] = form_error('price[' . $i . ']');
				$response['messages']['weight[' . $i . ']'] = form_error('weight[' . $i . ']');
				$response['messages']['length[' . $i . ']'] = form_error('length[' . $i . ']');
				$response['messages']['width[' . $i . ']'] = form_error('width[' . $i . ']');
				$response['messages']['height[' . $i . ']'] = form_error('height[' . $i . ']');
			}
		}

		return $response;
	}

	private function _validate_single()
	{
		$response = ['success' => false, 'validate' => true, 'messages' => []];

		$response['type'] = 'insert';

		$role_validate = ['trim', 'required', 'xss_clean'];
		$role_validate_desc = ['trim', 'required', 'xss_clean', 'min_length[20]'];

		$this->form_validation->set_rules('category', 'Category', $role_validate);
		$this->form_validation->set_rules('brand', 'Brand', $role_validate);
		$this->form_validation->set_rules('condition', 'Condition', $role_validate);
		$this->form_validation->set_rules('shipping[]', 'Logistic', $role_validate);
		$this->form_validation->set_rules('desc', 'Description', $role_validate_desc);
		$this->form_validation->set_rules('weight', 'Weight', $role_validate);
		$this->form_validation->set_rules('length', 'Length', $role_validate);
		$this->form_validation->set_rules('width', 'Width', $role_validate);
		$this->form_validation->set_rules('height', 'Height', $role_validate);

		$this->form_validation->set_error_delimiters('<div class="' . VALIDATION_MESSAGE_FORM . '">', '</div>');

		if ($this->form_validation->run() === false) {
			$response['validate'] = false;
			$response['messages']['category'] = form_error('category');
			$response['messages']['brand'] = form_error('brand');
			$response['messages']['condition'] = form_error('condition');
			$response['messages']['shipping[]'] = form_error('shipping[]');
			$response['messages']['desc'] = form_error('desc');
			$response['messages']['weight'] = form_error('weight');
			$response['messages']['length'] = form_error('length');
			$response['messages']['width'] = form_error('width');
			$response['messages']['height'] = form_error('height');
		}

		return $response;
	}

	public function save()
	{
		$this->db->trans_begin();
		try {
			$response = self::_validate_single();

			if (!$response['validate']) {
				throw new Exception('Error Processing Request', 1);
			}

			$this->db->trans_commit();
			$response['success'] = true;
			return $response;
		} catch (Exception $e) {
			$this->db->trans_rollback();
			return $response;
		}
	}

	public function save_single()
	{
		$this->db->trans_begin();
		try {
			$response = self::_validate_single();

			if (!$response['validate']) {
				throw new Exception('Error Processing Request', 1);
			}

			$get_post = $this->input->post();

			$get_products_id = clearInput($get_post['products_id']);
			$get_sources_id = clearInput($get_post['sources_id']);
			$get_channels_id = clearInput($get_post['channels_id']);
			$get_ctg_id = clearInput($get_post['ctg_id']);
			$get_category_name = clearInput($get_post['category']);
			$brand_explode = explode(':', $get_post['brand']);
			$get_brand_id = $brand_explode[0];
			$get_brand_name = $brand_explode[1];
			$get_condition = clearInput($get_post['condition']);
			$get_desc = clearInput($get_post['desc']);

			$build_shipping_array = [];
			$get_shipping = $get_post['shipping'];
			foreach ($get_shipping as $item) {
				list($id, $name) = explode(':', $item);
				$build_shipping_array[] = array(
					'id' => $id,
					'name' => $name
				);
			}

			$build_sku_array = [];
			$get_sku = $get_post['sku'];
			for ($i = 0; $i < count($get_sku); $i++) {
				$build_sku_array[] = array(
					'sku' => $get_sku[$i],
					'qty' => $get_post['qty'][$i],
					"price" => $get_post['price'][$i],
					"size" => $get_post['size'][$i],
					"color" => $get_post['color_sku'][$i],
				);
			}

			$build_color = [];
			$get_color = $get_post['color_variation'];
			for ($i = 0; $i < count($get_color); $i++) {
				$build_color[] = array(
					'color' => $get_color[$i],
					'image' => $get_post['image_color_variation'][$i]
				);
			}

			$build_image_array = [];
			$get_image = $get_post['image_name'];

			if (count($get_image) === 0) {
				$response['messages'] = 'You Must Selected Image';
				throw new Exception();
			}

			for ($i = 0; $i < count($get_image); $i++) {
				$build_image_array[] = [$get_image[$i]];
			}

			$data_input =
				[
					"users_ms_products_id" => $get_products_id,
					"admins_ms_sources_id" => $get_sources_id,
					"users_ms_channels_id" => $get_channels_id,
					"category_id" => $get_ctg_id,
					"category_name" => $get_category_name,
					"brand_id" => $get_brand_id,
					"brand_name" => $get_brand_name,
					"shipping_list" => json_encode($build_shipping_array),
					"description" => $get_desc,
					"sku_list" => json_encode($build_sku_array),
					"image_list" => json_encode($build_image_array)
				];

			$execute = $this->insertCustom($data_input, 'users_ms_product_publishes');

			if (!$execute) {
				$response['messages'] = 'Data Insert Invalid';
				throw new Exception();
			}

			$data_rabbit =
				[
					"users_ms_product_publishes_id" => $execute,
					"users_ms_products_id" => $get_products_id,
					"products_name" => $get_post['products_name'],
					"admins_ms_sources_id" => $get_sources_id,
					"users_ms_channels_id" => $get_channels_id,
					"category_id" => $get_ctg_id,
					"category_name" => $get_category_name,
					"brand_id" => $get_brand_id,
					"brand_name" => $get_brand_name,
					"shipping_list" => $build_shipping_array,
					"description" => $get_desc,
					"condition" => $get_condition,
					"sku_list" => $build_sku_array,
					"color_list" => $build_color,
					"image_list" => $build_image_array,
					"weight" => $get_post['weight'],
					"length" => $get_post['length'],
					"width" => $get_post['width'],
					"height" => $get_post['height']
				];

			$this->queue->shopee_prodcut_publish_push(json_encode($data_rabbit));

			$response['messages'] = 'Successfully Insert Data Publish';

			$this->db->trans_commit();
			$response['success'] = true;
			return $response;
		} catch (Exception $e) {
			$this->db->trans_rollback();
			return $response;
		}
	}
}
