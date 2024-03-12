<?php
defined('BASEPATH') or exit('No direct script access allowed');

require FCPATH . 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Product_image_model extends MY_ModelCustomer
{
	use MY_Tables;
	public function __construct()
	{
		$this->_tabel = $this->_table_products_images;
		parent::__construct();
		$this->load->helper('metronic');
		$this->_table_price_global = 'users_ms_price_to_days';
	}

	public function get_product_by_id()
	{
		$this->_ci->load->model('products/products_model', 'products_model');
		return $this->_ci->products_model;
	}

	public function get_size_by_id_product($id)
	{
		$query = $this->db->query("SELECT 
										GROUP_CONCAT(DISTINCT product_size ORDER BY product_size ASC)  as size
									FROM  {$this->_table_products_variants}
									WHERE users_ms_products_id = {$id}
									AND deleted_at IS NULL
								 ");

		return $query->row();
	}

	public function get_product_status()
	{
		$query = $this->db->query("SELECT * FROM {$this->_table_ms_lookup_values} WHERE lookup_config = 'products_status' ");
		return $query->result();
	}

	public function get_variant_by_id()
	{
		$this->_ci->load->model('products/products_variants_model', 'products_variants_model');
		return $this->_ci->products_variants_model;
	}

	public function get_supplier()
	{
		$this->_ci->load->model('suppliers_data/suppliers_data_model', 'suppliers_data_model');
		return $this->_ci->suppliers_data_model;
	}

	public function get_brand()
	{
		$this->_ci->load->model('brand/brand_model', 'brand_model');
		return $this->_ci->brand_model;
	}

	public function get_category()
	{
		$this->_ci->load->model('category/category_model', 'category_model');
		return $this->_ci->category_model;
	}

	public function get_management_type()
	{
		$this->_ci->load->model('management_type/management_type_model', 'management_type_model');
		return $this->_ci->management_type_model;
	}

	public function get_matrix()
	{
		$this->_ci->load->model('matrix/matrix_model', 'matrix_model');
		return $this->_ci->matrix_model;
	}

	public function get_general_color()
	{
		$query = $this->db->query("SELECT id,color_name,color_hexa FROM {$this->_table_ms_color} WHERE parent_color_id = 0");
		return $query->result();
	}

	public function get_variant_color_by_parent($parent_id)
	{
		$query = $this->db->query("SELECT id,color_name,color_hexa FROM {$this->_table_ms_color} WHERE parent_color_id = {$parent_id}");
		return $query->result();
	}

	public function get_category_by_parent($parent_id)
	{
		$query = $this->db->query("SELECT * FROM {$this->_table_category} WHERE parent_categories_id = {$parent_id} AND users_ms_companys_id = {$this->_users_ms_companys_id}");
		return $query->result();
	}

	public function get_management_type_by_parent($parent_id)
	{
		$query = $this->db->query("SELECT * FROM {$this->_table_users_ms_management_type} WHERE parent_management_type_id = {$parent_id} AND users_ms_companys_id = {$this->_users_ms_companys_id}");
		return $query->result();
	}
	public function getProduct()
	{
		return $this->db->get('ms_products')->result_array();
	}

	function convertToBase64($imagePath)
	{
		$finfo = new finfo(FILEINFO_MIME_TYPE);
		$type = $finfo->file($imagePath);
		return 'data:' . $type . ';base64,' . base64_encode(file_get_contents($imagePath));
	}

	public function show($data = [])
	{

		// $this->check_product_status();
		$this->datatables->select('t1.id as id, 
							t1.product_name, 
							t1.brand_name, 
							t2.lookup_code as status,
							IFNULL(t3.price,t1.product_price) product_price,
							IFNULL(t3.sale_price,t1.product_sale_price) product_sale_price,
						 ', false);
		$this->datatables->select(
			'(SELECT 
								GROUP_CONCAT(DISTINCT product_size ORDER BY product_size ASC) 
							FROM ' . $this->_table_products_variants . ' 
							WHERE users_ms_products_id = t1.id AND deleted_at IS NULL) as product_size',
			FALSE
		);
		$this->datatables->from($this->_table_products . ' t1');
		$this->datatables->join($this->_table_ms_lookup_values . ' t2', 't2.lookup_config = "products_status" AND t2.lookup_code = t1.status', "left");
		$this->datatables->join($this->_table_price_global . ' t3', 't3.users_ms_products_id = t1.id', "left");
		$this->datatables->where('t1.deleted_at', null, false);
		$this->datatables->where(["t1.{$this->_table_users_ms_companys}_id" => $this->_users_ms_companys_id]);

		// $filters = !empty($this->input->post('filters')) ? $this->input->post('filters'): false;

		// $status = [];
		// $brand = [];
		// if($filters !== false && is_array($filters)){
		//     foreach($filters as $ky => $val){
		//         $value = $val['value'];
		//         if(!empty($value)){
		//             switch ($val['name']) {
		//                 case 'product_id_filter':
		//                         $this->datatables->like('t1.id',$value);
		//                     break;
		//                 case 'product_name_filter':
		//                         $this->datatables->like('t1.product_name',$value);
		//                     break;
		//                 case 'brand_filter[]':
		//                         $brand[] = $value;
		//                     break;
		//                 case 'product_status_filter[]':
		//                        $status[] = $value;
		//                     break;
		//             }
		//         }
		//     }
		// }

		// if(count($status) > 0){
		//     $state = [];
		//     // for($i= 0; $i < count($status);$i++){
		//     //     switch ($status[$i]) {
		//     //         case 'enable':
		//     //             $state[] = 1;
		//     //             break;

		//     //         case 'disable':
		//     //             $state[] = 0;
		//     //             break;
		//     //     }
		//     // }

		//     $this->datatables->where_in('t1.status',$status);
		// }

		// if(count($brand) > 0){
		//     $this->datatables->where_in('t1.users_ms_brands_id',$brand);
		// }

		$filters = !empty($this->input->post('filters')) ? $this->input->post('filters') : false;

		if ($filters !== false && is_array($filters)) {

			$getSearchBy = '';
			$setValue = '';
			$setStartDate = '';
			$setEndDate = '';

			$setLookupStatus = [];

			foreach ($filters as $val) {

				if ($val['name'] == 'searchBy') {
					$getSearchBy .= $val['value'];
				}

				if ($val['name'] == 'searchValue') {
					$setValue .= $val['value'];
				}

				if ($val['name'] == 'start_date') {
					$setStartDate .= $val['value'];
				}

				if ($val['name'] == 'end_date') {
					$setEndDate .= $val['value'];
				}

				if ($val['name'] == 'lookup_status_1') {
					$setLookupStatus[] = $val['value'];
				}

				if ($val['name'] == 'lookup_status_2') {
					$setLookupStatus[] = $val['value'];
				}

				if ($val['name'] == 'lookup_status_3') {
					$setLookupStatus[] = $val['value'];
				}

				if ($val['name'] == 'lookup_status_4') {
					$setLookupStatus[] = $val['value'];
				}

				if ($val['name'] == 'lookup_status_5') {
					$setLookupStatus[] = $val['value'];
				}

				if ($val['name'] == 'lookup_status_6') {
					$setLookupStatus[] = $val['value'];
				}
			}

			if (!empty($getSearchBy)) {
				switch ($getSearchBy) {
					case 'id':
						$this->datatables->like('t1.id', $setValue);
						break;
					case 'product_name':
						$this->datatables->like('t1.product_name', $setValue);
						break;
					case 'brand_name':
						$this->datatables->like('t1.brand_name', $setValue);
						break;
					default:
						break;
				}
			}

			if (!empty($setLookupStatus)) {
				$this->datatables->where_in('t1.status', $setLookupStatus);
			}

			if (!empty($setStartDate)) {
				$this->datatables->where('t1.created_at >=', $setStartDate);
			}

			if (!empty($setStartDate) && !empty($setEndDate)) {
				$this->datatables->where('DATE(t1.created_at) >=', $setStartDate);
				$this->datatables->where('DATE(t1.created_at) <=', $setEndDate);
			}

			if (empty($setStartDate) && !empty($setEndDate)) {
				$getDateNow = date('Y-m-d');

				$this->datatables->where('DATE(t1.created_at) >=', $getDateNow);
				$this->datatables->where('DATE(t1.created_at) <=', $setEndDate);
			}
		}

		if ($data !== false && is_array($data)) {
			$getSearchBy = '';
			$setValue = '';
			$setStartDate = '';
			$setEndDate = '';
			$setStatus = '';

			foreach ($data as $key => $value) {
				if ($key == 'searchBy') {
					$getSearchBy .= $value;
				}

				if ($key == 'searchValue') {
					$setValue .= $value;
				}

				if ($key == 'valueStatus') {
					if (!empty($value)) {
						$setStatus .= $value;
					}
				}

				if ($key == 'startDate') {
					$setStartDate .= $value;
				}

				if ($key == 'endDate') {
					$setEndDate .= $value;
				}
			}

			if (!empty($setStatus)) {
				$this->datatables->where_in('t1.status', explode(",", $setStatus));
			}

			if (!empty($getSearchBy)) {
				switch ($getSearchBy) {
					case 'id':
						$this->datatables->like('t1.id', $setValue);
						break;
					case 'product_name':
						$this->datatables->like('t1.product_name', $setValue);
						break;
					case 'brand_name':
						$this->datatables->like('t1.brand_name', $setValue);
						break;
					default:
						break;
				}
			}

			if (!empty($setStartDate)) {
				$this->datatables->where('t1.created_at >=', $setStartDate);
			}

			if (!empty($setStartDate) && !empty($setEndDate)) {
				$this->datatables->where('DATE(t1.created_at) >=', $setStartDate);
				$this->datatables->where('DATE(t1.created_at) <=', $setEndDate);
			}

			if (empty($setStartDate) && !empty($setEndDate)) {
				$getDateNow = date('Y-m-d');

				$this->datatables->where('DATE(t1.created_at) >=', $getDateNow);
				$this->datatables->where('DATE(t1.created_at) <=', $setEndDate);
			}
		}




		$fieldSearch = [
			't1.id',
			't1.product_name',
			't1.product_price',
			't1.product_sale_price',
			't1.brand_name',
			't2.lookup_name'
		];

		$this->_searchDefaultDatatables($fieldSearch);

		$this->datatables->order_by('t1.updated_at desc');
		$buttonRelease = '<button class="btn btn-outline btn-outline-dashed btn-outline-success btn-active-light-success hover-scale btn-sm fw-bold me-2 mb-2 btnList" data-title="Item" data-type="modal" data-url="' . base_url() . 'product_image/list/$1" data-fullscreenmodal="1" data-id="$1"><i class="bi bi-card-list fs-4 me-2"></i>List</button>
		<button class="btn btn-outline btn-outline-dashed btn-outline-warning btn-active-light-warning hover-scale btn-sm fw-bold me-2 mb-2 btnEdit" data-title="Item" data-type="modal" data-url="' . base_url() . 'product_image/edit/$1" data-fullscreenmodal="1" data-id="$1"><i class="bi bi-pencil-square fs-4 me-2"></i>Edit</button>
		<button class="btn btn-outline btn-outline-dashed btn-outline-dark btn-active-light-dark hover-scale btn-sm fw-bold me-2 mb-2 btnView" data-title="Item" data-type="modal" data-url="' . base_url() . 'product_image/view/$1" data-fullscreenmodal="1" data-id="$1"><i class="bi bi-eye-fill fs-4 me-2"></i>View</button>';
		$this->datatables->add_column('action', $buttonRelease, 'id');

		return $this->datatables->generate();
	}

	private function _validate()
	{
		$response = ['success' => false, 'validate' => true, 'messages' => []];
		$response['type'] = !empty($this->input->post('id')) ? 'update' : 'insert';

		$rules = ['trim', 'required', 'xss_clean'];

		for ($i = 0; $i < count($this->input->post('kt_docs_repeater_nested_outer')); $i++) {
			$this->form_validation->set_rules('kt_docs_repeater_nested_outer[' . $i . '][select_product]', 'Product Name', $rules);

			$this->form_validation->set_error_delimiters('<div class="' . VALIDATION_MESSAGE_FORM . '">', '</div>');

			if ($this->form_validation->run() === false) {
				$response['validate'] = false;
				$response['messages']['kt_docs_repeater_nested_outer[' . $i . '][select_product]'] = form_error('kt_docs_repeater_nested_outer[' . $i . '][select_product]');
			}
		}

		return $response;
	}

	public function validate()
	{

		$this->form_validation->set_rules("general_color", 'General Color', 'required');
		if ($_POST['custom_variant_color'] === 'true') {
			$this->form_validation->set_rules('custom_variant_color_name', 'Custom Variant Color Name', 'required');
			$this->form_validation->set_rules('custom_variant_color_code', 'Custom Variant Color Code', 'required');
		} else {
			$this->form_validation->set_rules('variant_color', 'Variant Color', 'required');
		}
		$this->form_validation->set_rules('size', 'Size', 'required');


		if ($this->form_validation->run() == FALSE) {
			$errors = array(
				'general_color' => form_error('general_color'),
				'variant_color' => form_error('variant_color'),
				'size' => form_error('size'),
				'custom_variant_color_name' => form_error('custom_variant_color_name'),
				'custom_variant_color_code' => form_error('custom_variant_color_code'),
			);


			$response = array(
				'success' => false,
				'message' => $errors
			);
		} else {

			$response = array(
				'success' => true,
			);
		}


		return $response;
	}
	public function save()
	{

		$this->db->trans_begin();

		try {
			$validate = $this->validate();

			if ($validate['success'] === false) {
				return $validate;
			} else {

				$data = array();

				$general_color_id = $_POST['general_color'];
				$status = 1;


				if ($_POST['custom_variant_color'] === 'true') {
					// $variant_color_name			= $_POST['custom_variant_color_name'];
					$variant_color_name = $_POST['custom_variant_color_code'];
					$variant_color_code = $_POST['custom_variant_color_code'];

					$color_data = array(
						'parent_color_id' => $general_color_id,
						'color_name' => $_POST['custom_variant_color_name'],
						'color_hexa' => $variant_color_code,
					);
					$i = $this->db->insert($this->_table_ms_color, $color_data);
					$variant_color_id = $this->db->insert_id();
				} else {
					$variant_color_id = $_POST['variant_color'];
					// $variant_color_name			= $this->get_color($variant_color_id)->color_name;
					$variant_color_name = $this->get_color($variant_color_id)->color_hexa;
					$variant_color_code = $this->get_color($variant_color_id)->color_hexa;
				}

				$sizeArray = preg_split('/[\s,\.]+/', $_POST['size'], -1, PREG_SPLIT_NO_EMPTY);

				foreach ($sizeArray as $row) {
					$size = strtoupper($row);
					$validate_sku = array(
						'users_ms_products_id' => $_POST['product_id'],
						'general_color_id' => $general_color_id,
						'variant_color_id' => $variant_color_id,
						// 'variant_color_name' =>$variant_color_name,
						'product_size' => $size
					);
					if ($this->get_variant_by_id()->get($validate_sku)) {
						$response['success'] = false;
						$response['sku_error'] = true;
						$response['message'] = "Variant size : " . $size . " is used";
						return $response;
					} else {
						$insert_data = array(
							'users_ms_products_id' => $_POST['product_id'],
							'sku' => $this->generate_sku($_POST, $variant_color_name, $size),
							'general_color_id' => $general_color_id,
							'variant_color_id' => $variant_color_id,
							'variant_color_name' => $variant_color_name,
							'product_size' => $size
						);

						$insert = $this->insertCustom($insert_data, $this->_table_products_variants);
					}
				}


				$this->db->trans_commit();
				$response['success'] = true;
				$response['message'] = "Success add variant";
				return $response;
			}
		} catch (Exception $e) {
			$this->db->trans_rollback();
			return $response;
		}
	}

	public function upload_image()
	{


		$this->db->trans_begin();

		try {

			$data = array();

			$data['users_ms_products_id'] = $_POST['product_id'];
			$data['variant_color_id'] = $_POST['variant_color_id'];
			$data['general_color_id'] = $_POST['general_color_id'];
			$prodname = str_replace(' ', '_', strtolower($_POST['product_name']));
			$images = json_decode($_POST['img']);

			if (empty($images)) {
				throw new Exception('Image Required', 1);
			}

			foreach ($images as $row) {


				$path = './assets/uploads/products_image/';

				if (!file_exists($path)) {
					mkdir('./assets/uploads/products_image', 0777, true);
				}

				$string_pieces = explode(";base64,", $row);
				$image_type_pieces = explode("image/", $string_pieces[0]);
				$image_type = !empty($image_type_pieces[1]) ? $image_type_pieces[1] : '';


				$nameImage = $_POST['product_id'] . '_' . $prodname . '_' . generate_random_string(4) . '.' . $image_type;
				tf_convert_base64_to_image($row, $path, $nameImage);


				$data['image_name'] = $nameImage;
				$data['image_file'] = $path . $nameImage;
				$this->insert($data);
			}


			$this->check_product_status();

			$this->db->trans_commit();
			$response['success'] = true;
			$response['message'] = "Success add image";
			return $response;
		} catch (Exception $e) {
			$response['success'] = false;
			$response['message'] = "Image is required";
			$this->db->trans_rollback();
			return $response;
		}
	}

	public function generate_sku($post, $variant_color_name, $size)
	{


		$data_product = $this->get_product_by_id()->get(array('id' => $post['product_id']));


		$generate_sku = array(
			'brand_name' => $data_product->brand_name,
			'product_name' => $data_product->product_name,
			'type_name' => 'Pakaian',
			'color_name' => $variant_color_name,
			'product_size' => $size,
		);

		return create_sku($generate_sku);
	}
	public function get_variant($id)
	{
		try {

			$query = $this->db->query("SELECT 
											MAX(t1.id) AS variant_id, 
											t2.id AS product_id, 
											t1.general_color_id, 
											t1.variant_color_id, 
											t2.product_name, 
											t3.color_name AS general_color, 
											t3.color_hexa AS general_color_hexa, 
											t4.color_name AS variant_color, 
											t4.color_hexa AS variant_color_hexa 
										FROM 
											{$this->_table_products_variants} t1
										INNER JOIN 
											{$this->_table_products} t2 ON t1.users_ms_products_id = t2.id 
										LEFT JOIN 
											{$this->_table_ms_color} t3 ON t1.general_color_id = t3.id 
										LEFT JOIN 
											{$this->_table_ms_color} t4 ON t1.variant_color_id = t4.id
										WHERE 
											t2.id = {$id}
										GROUP BY 
											product_id, general_color_id, variant_color_id, product_name, general_color, general_color_hexa, variant_color, variant_color_hexa;
			");

			$res = $query->result();

			if (!$res) {
				throw new Exception('Varaint not Register', 1);
			}

			return $res;
		} catch (Exception $e) {
			return $e->getMessage();
		}
	}

	public function get_list_image()
	{
		$product_id = $_POST['product_id'];
		$general_color_id = $_POST['general_color_id'];
		$variant_color_id = $_POST['variant_color_id'];
		$query = $this->db->query("SELECT * 
									FROM {$this->_table_products_images}
									WHERE users_ms_products_id = {$product_id}
									AND general_color_id = {$general_color_id}
									AND variant_color_id = {$variant_color_id}
									AND deleted_at IS NULL
									");
		return $query->result();
	}

	public function get_list_size()
	{
		$product_id = $_POST['product_id'];
		$general_color_id = $_POST['general_color_id'];
		$variant_color_id = $_POST['variant_color_id'];
		$query = $this->db->query("SELECT * 
									FROM {$this->_table_products_variants}
									WHERE users_ms_products_id = {$product_id}
									AND general_color_id = {$general_color_id}
									AND variant_color_id = {$variant_color_id}
									AND deleted_at IS NULL
									");
		return $query->result();
	}

	public function get_color($id)
	{
		try {

			$query = $this->db->query("SELECT 
											t1.id,
											t1.color_name,
											t1.color_hexa
										FROM {$this->_table_ms_color} t1
										WHERE t1.id = {$id}
									 ");
			$res = $query->row();

			if (!$res) {
				throw new Exception('Color not Register', 1);
			}

			return $res;
		} catch (Exception $e) {
			return $e->getMessage();
		}
	}

	public function validate_edit_product()
	{

		$this->form_validation->set_rules("product", 'Product Name', 'required');
		$this->form_validation->set_rules("supplier", 'Supplier', 'required');
		$this->form_validation->set_rules("brand", 'Brand', 'required');
		$this->form_validation->set_rules('product_price', 'Product Price', 'required');
		$this->form_validation->set_rules('category', 'Category', 'required');


		if ($this->form_validation->run() == FALSE) {
			$errors = array(
				'product' => form_error('product'),
				'supplier' => form_error('supplier'),
				'brand' => form_error('brand'),
				'product_price' => form_error('product_price'),
				'category' => form_error('category'),
			);


			$response = array(
				'success' => false,
				'message' => $errors
			);
		} else {

			$response = array(
				'success' => true,
			);
		}


		return $response;
	}

	public function edit_product()
	{

		$this->db->trans_begin();

		try {

			$validate = $this->validate_edit_product();

			if ($validate['success'] === false) {
				return $validate;
			} else {

				$data = array();

				$data['product_name'] = $_POST['product'];
				$data['users_ms_suppliers_id'] = $_POST['supplier'];
				$data['users_ms_brands_id'] = $_POST['brand'];
				$data['gender'] = $_POST['gender'];
				$data['product_price'] = str_replace('.', '', $_POST['product_price']);
				$data['product_offline_price'] = str_replace('.', '', $_POST['product_offline_price']);
				$data['product_sale_price'] = str_replace('.', '', $_POST['product_sale_price']);
				$data['users_ms_categories_id'] = $_POST['category'];
				$data['category_name'] = $this->get_category()->get(array('id' => $_POST['category']))->categories_name;

				// print_r('test' .$_POST['category_1']);
				if (empty($_POST['category_1'])) {
					$data['users_ms_categories_id_1'] = 0;
					$data['category_name_1'] = null;
				} else {
					$data['users_ms_categories_id_1'] = $_POST['category_1'];
					$data['category_name_1'] = isset($this->get_category()->get(array('id' => $_POST['category_1']))->categories_name) ? $this->get_category()->get(array('id' => $_POST['category_1']))->categories_name : '';
				}

				if (empty($_POST['category_2'])) {
					$data['users_ms_categories_id_2'] = 0;
					$data['category_name_2'] = null;
				} else {
					$data['users_ms_categories_id_2'] = $_POST['category_2'];
					$data['category_name_2'] = isset($this->get_category()->get(array('id' => $_POST['category_2']))->categories_name) ? $this->get_category()->get(array('id' => $_POST['category_2']))->categories_name : '';
				}


				if (empty($_POST['management_type_1'])) {
					$data['users_ms_management_type_id_1'] = 0;
					$data['management_type_name_1'] = null;
				} else {
					$data['users_ms_management_type_id_1'] = $_POST['management_type_1'];
					$data['management_type_name_1'] = isset($this->get_management_type()->get(array('id' => $_POST['management_type_1']))->management_type_name) ? $this->get_management_type()->get(array('id' => $_POST['management_type_1']))->management_type_name : '';
				}

				if (empty($_POST['management_type_2'])) {
					$data['users_ms_management_type_id_2'] = 0;
					$data['management_type_name_2'] = null;
				} else {
					$data['users_ms_management_type_id_2'] = $_POST['management_type_2'];
					$data['management_type_name_2'] = isset($this->get_management_type()->get(array('id' => $_POST['management_type_2']))->management_type_name) ? $this->get_management_type()->get(array('id' => $_POST['management_type_2']))->management_type_name : '';
				}

				if (empty($_POST['management_type_3'])) {
					$data['users_ms_management_type_id_3'] = 0;
					$data['management_type_name_3'] = null;
				} else {
					$data['users_ms_management_type_id_3'] = $_POST['management_type_3'];
					$data['management_type_name_3'] = isset($this->get_management_type()->get(array('id' => $_POST['management_type_3']))->management_type_name) ? $this->get_management_type()->get(array('id' => $_POST['management_type_3']))->management_type_name : '';
				}

				// $data['users_ms_management_type_id_1']	= $_POST['management_type_1'];
				// $data['users_ms_management_type_id_2']	= $_POST['management_type_2'];
				// $data['users_ms_management_type_id_3']	= $_POST['management_type_3'];

				$data['users_ms_matrix_id'] = isset($_POST['matrix']) ? $_POST['matrix'] : '';
				$data['product_description'] = $_POST['product_description'];
				$data['product_short_description'] = $_POST['product_short_description'];
				$data['product_info'] = $_POST['product_info'];
				$data['size_guide_line'] = $_POST['size_guide_line'];


				// echo '<pre>';
				// print_r($data);
				// die;

				$this->db->where('id', $_POST['product_id']);
				$this->db->update($this->_table_products, $data);

				$this->db->trans_commit();
				$response['success'] = true;
				$response['message'] = "Success Edit product";
				return $response;
			}
		} catch (Exception $e) {
			$this->db->trans_rollback();
			return $response;
		}
	}


	public function edit_size($id)
	{

		$this->db->trans_begin();

		try {
			$product_id = $_POST['product_id'];
			$general_color_id = $_POST['general_color_id'];
			$variant_color_id = $_POST['variant_color_id'];
			$product_size = strtoupper($_POST['value']);
			$query = $this->db->query("SELECT *
									FROM {$this->_table_products_variants}
									WHERE users_ms_products_id = {$product_id}
									AND general_color_id = {$general_color_id}
									AND variant_color_id =  {$variant_color_id}
									AND product_size =  '{$product_size}'
									");
			$result = $query->num_rows();

			if ($result > 0) {
				$response['success'] = false;
				$response['message'] = "Size : " . $product_size . " is used";
				return $response;
			}

			$data = array();

			$data['product_size'] = $product_size;

			$this->db->where('id', $id);
			$this->db->update($this->_table_products_variants, $data);

			$this->db->trans_commit();
			$response['success'] = true;
			$response['message'] = "Success Edit product";
			return $response;
		} catch (Exception $e) {
			$this->db->trans_rollback();
			return $response;
		}
	}

	public function getItems($id)
	{
		try {
			$get = $this->db->get_where('ms_product_images', ['id' => $id])->row();

			if (!$get) {
				throw new Exception('Data not Register', 1);
			}

			$table = [
				'id' => $get->id,
				'product_id' => $get->product_id,
				'image_name' => $get->image_name
			];

			return $table;
		} catch (Exception $e) {
			return $e->getMessage();
		}
	}

	public function deleteData($id)
	{
		$this->db->trans_begin();
		try {
			$softDelete = $this->softDeleteCustom($this->_table_products_images, 'id', $id);

			if (!$softDelete) {
				throw new Exception('Failed delete item', 1);
			}

			$this->db->trans_commit();
			return true;
		} catch (Exception $e) {
			$this->db->trans_rollback();
			return $e->getMessage();
		}
	}




	public function get_data_edit_variant($product_id, $general_color_id, $variant_color_id)
	{
		$sql = "SELECT 
					t1.id as id, 
					t1.product_name as product_name, 
					t2.id general_color_id,
					t2.color_name general_color_name,
					t3.id variant_color_id,
					t3.color_name variant_color_name,
					(
						SELECT GROUP_CONCAT(DISTINCT product_size ORDER BY product_size ASC) 
						FROM " . $this->_table_products_variants . " 
						WHERE users_ms_products_id = t1.id
						AND deleted_at IS NULL
						AND general_color_id = {$general_color_id}
						AND variant_color_id = {$variant_color_id}
					) as product_size
				FROM " . $this->_table_products . " t1
				INNER JOIN " . $this->_table_ms_color . " t2 ON t2.id = {$general_color_id}
				LEFT JOIN " . $this->_table_ms_color . " t3 ON t3.id = {$variant_color_id}
				WHERE t1.deleted_at IS NULL
				AND t1.id = {$product_id}";

		$query = $this->db->query($sql);
		$result = $query->row();

		return $result;
	}

	public function validate_edit_variant()
	{

		$this->form_validation->set_rules("general_color", 'General Color', 'required');
		if (isset($_POST['custom_variant_color'])) {
			$this->form_validation->set_rules('custom_variant_color_name', 'Custom Variant Color Name', 'required');
			$this->form_validation->set_rules('custom_variant_color_code', 'Custom Variant Color Code', 'required');
		} else {
			$this->form_validation->set_rules('variant_color', 'Variant Color', 'required');
		}
		// $this->form_validation->set_rules('size', 'Size', 'required');


		if ($this->form_validation->run() == FALSE) {
			$errors = array(
				'general_color' => form_error('general_color'),
				'variant_color' => form_error('variant_color'),
				// 'size' => form_error('size'),
				'custom_variant_color_name' => form_error('custom_variant_color_name'),
				'custom_variant_color_code' => form_error('custom_variant_color_code'),
			);


			$response = array(
				'success' => false,
				'message' => $errors
			);
		} else {

			$response = array(
				'success' => true,
			);
		}


		return $response;
	}
	public function edit_variant()
	{

		$this->db->trans_begin();

		try {
			$validate = $this->validate_edit_variant();

			if ($validate['success'] === false) {
				return $validate;
			} else {

				$data = array();

				$general_color_id = $_POST['general_color'];
				$variant_color_id = $_POST['variant_color'];

				$general_color_id_before = $_POST['general_color_before'];
				$variant_color_id_before = $_POST['variant_color_before'];


				if (isset($_POST['custom_variant_color'])) {
					// $variant_color_name			= $_POST['custom_variant_color_name'];
					$variant_color_name = $_POST['custom_variant_color_code'];
					$variant_color_code = $_POST['custom_variant_color_code'];

					$color_data = array(
						'parent_color_id' => $general_color_id,
						'color_name' => $_POST['custom_variant_color_name'],
						'color_hexa' => $variant_color_code,
					);
					$i = $this->db->insert($this->_table_ms_color, $color_data);
					$variant_color_id = $this->db->insert_id();
				} else {
					$variant_color_id = $_POST['variant_color'];
					// $variant_color_name			= $this->get_color($variant_color_id)->color_name;
					$variant_color_name = $this->get_color($variant_color_id)->color_hexa;
					$variant_color_code = $this->get_color($variant_color_id)->color_hexa;
				}

				if (isset($_POST['size'])) {
					$sizeArray = preg_split('/[\s,\.]+/', $_POST['size'], -1, PREG_SPLIT_NO_EMPTY);

					foreach ($sizeArray as $row) {
						$size = strtoupper($row);
						$validate_sku = array(
							'users_ms_products_id' => $_POST['product_id'],
							'general_color_id' => $general_color_id,
							'variant_color_id' => $variant_color_id,
							// 'variant_color_name' =>$variant_color_name,
							'product_size' => $size
						);
						if (!$this->get_variant_by_id()->get($validate_sku)) {

							$insert_data = array(
								'users_ms_products_id' => $_POST['product_id'],
								'sku' => $this->generate_sku($_POST, $variant_color_name, $size),
								'general_color_id' => $general_color_id,
								'variant_color_id' => $variant_color_id,
								'variant_color_name' => $variant_color_name,
								'product_size' => $size
							);

							$insert = $this->insertCustom($insert_data, $this->_table_products_variants);
						}
					}
				}


				if ($general_color_id != $general_color_id_before || $variant_color_id != $variant_color_id_before) {
					$update = array(
						'general_color_id' => $general_color_id,
						'variant_color_id' => $variant_color_id,
						'variant_color_name' => $variant_color_name,
					);
					$this->db->where('users_ms_products_id', $_POST['product_id']);
					$this->db->where('general_color_id', $general_color_id_before);
					$this->db->where('variant_color_id', $variant_color_id_before);
					$this->db->update($this->_table_products_variants, $update);
				}



				$this->db->trans_commit();
				$response['success'] = true;
				$response['message'] = "Success add variant";
				return $response;
			}
		} catch (Exception $e) {
			$this->db->trans_rollback();
			return $response;
		}
	}


	public function check_product_status()
	{

		$this->db->trans_begin();
		try {

			$update = $this->db->query("
									UPDATE {$this->_table_products}
									SET status = 3
									WHERE deleted_at IS NULL
									AND users_ms_companys_id = {$this->_users_ms_companys_id}
									AND status = 1
									AND id IN (
												SELECT users_ms_products_id
												FROM {$this->_table_batchs_detail} t2
												WHERE t2.users_ms_companys_id = {$this->_users_ms_companys_id}
												AND t2.deleted_at IS NULL
												)
									AND id IN (
												SELECT users_ms_products_id
												FROM {$this->_table_products_images} t3
												WHERE t3.users_ms_companys_id = {$this->_users_ms_companys_id}
												AND t3.deleted_at IS NULL
												)
												
								");


			if (!$update) {
				throw new Exception('', 1);
			}
			$this->db->trans_commit();
		} catch (Exception $e) {
			$this->db->trans_rollback();
		}
	}


	public function generateSpreadsheet($data = [])
	{
		$getData = json_decode($this->show($data));


		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->getStyle('1')->getFont()->setBold(true);
		$sheet->setTitle('Product Variant');

		$columnHeaders = ['PRODUCT ID', 'SKU', 'PRODUCT NAME', 'BRAND NAME', 'GENERAL COLOR', 'VARIANT COLOR', 'SIZE', 'PRICE', 'SALE PRICE', 'OFFLINE PRICE', 'STATUS'];

		foreach ($columnHeaders as $index => $header) {
			$columnLetter = chr(65 + $index);
			$sheet->setCellValue($columnLetter . '1', $header);
			$sheet->getColumnDimension($columnLetter)->setAutoSize(true);
		}

		if (!empty($getData->data)) {

			$idArray = [];
			foreach ($getData->data as $item) {
				$idArray[] = $item->id;
			}

			$idString = implode(',', $idArray);
			$rowIndex = 2;

			$query = $this->db->query("SELECT 
						t1.id AS variant_id, 
						t1.sku,
						t1.product_size,
						t2.id AS product_id, 
						t2.product_name, 
						t2.brand_name,
						IFNULL(t6.price,t2.product_price) product_price,
						IFNULL(t6.sale_price,t2.product_sale_price) product_sale_price,
						IFNULL(t6.offline_price,t2.product_offline_price) product_offline_price,
						t3.color_name AS general_color, 
						t3.color_hexa AS general_color_hexa, 
						t4.color_name AS variant_color, 
						t4.color_hexa AS variant_color_hexa,
						t5.lookup_name as status
					FROM 
						{$this->_table_products_variants} t1
					INNER JOIN 
						{$this->_table_products} t2 ON t1.users_ms_products_id = t2.id 
					LEFT JOIN 
						{$this->_table_ms_color} t3 ON t1.general_color_id = t3.id 
					LEFT JOIN 
						{$this->_table_ms_color} t4 ON t1.variant_color_id = t4.id
					LEFT JOIN 
						{$this->_table_ms_lookup_values} t5 ON t2.status = t5.lookup_code AND t5.lookup_config = 'products_status'
					LEFT JOIN 
						{$this->_table_price_global} t6 ON t6.users_ms_products_id = t2.id
					WHERE 
						t2.id IN ($idString)
					AND t2.users_ms_companys_id = {$this->_users_ms_companys_id}
					ORDER BY t2.id ASC
			");

			$product = $query->result();

			foreach ($product as $row) {

				$rowData = [$row->product_id, $row->sku, $row->product_name, $row->brand_name, $row->general_color, $row->variant_color, $row->product_size, $row->product_price, $row->product_sale_price, $row->product_offline_price, $row->status];

				$columnLetter = 'A';

				foreach ($rowData as $value) {
					$sheet->setCellValue($columnLetter . $rowIndex, $value);
					$columnLetter++;
				}

				$rowIndex++;
			}
		}

		$sheetImage = $spreadsheet->createSheet();
		$sheetImage->setTitle('Product Image');

		$sheetImage->getStyle('1')->getFont()->setBold(true);

		$columnHeaders = ['PRODUCT ID', 'PRODUCT NAME', 'IMAGE NAME'];

		foreach ($columnHeaders as $index => $header) {
			$columnLetter = chr(65 + $index);
			$sheetImage->setCellValue($columnLetter . '1', $header);
			$sheetImage->getColumnDimension($columnLetter)->setAutoSize(true);
		}

		if (!empty($getData->data)) {

			$idArray = [];
			foreach ($getData->data as $item) {
				$idArray[] = $item->id;
			}

			$idString = implode(',', $idArray);
			$rowIndex = 2;

			$query = $this->db->query("SELECT 
						t2.id, 
						t2.product_name,
						t1.image_name
					FROM 
						{$this->_table_products_images} t1
					INNER JOIN 
						{$this->_table_products} t2 ON t1.users_ms_products_id = t2.id 
					WHERE t1.users_ms_products_id IN ($idString)
					AND t2.users_ms_companys_id = {$this->_users_ms_companys_id}
					ORDER BY t1.id ASC
			");

			$image = $query->result();

			foreach ($image as $row) {

				$rowData = [$row->id, $row->product_name, $row->image_name];

				$columnLetter = 'A';

				foreach ($rowData as $value) {
					$sheetImage->setCellValue($columnLetter . $rowIndex, $value);
					$columnLetter++;
				}

				$rowIndex++;
			}
		}

		$writer = new Xlsx($spreadsheet);
		$fileName = 'Product_Media_Report.xlsx';

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="' . $fileName . '"');
		$writer->save('php://output');
	}
}
