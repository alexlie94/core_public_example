<?php
defined('BASEPATH') or exit('No direct script access allowed');

require FCPATH . 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Products_model extends MY_ModelCustomer
{
	use MY_Tables;
	public function __construct()
	{
		$this->_tabel = $this->_table_products;
		$this->_tabel_variant = $this->_table_products_variants;
		$this->_tabel_color = 'ms_color_name_hexa';
		$this->_tabel_bb_inventory = 'users_ms_product_bb_inventories';
		parent::__construct();
	}

	public function getDataBrands()
	{
		$this->_ci->load->model('brand/Brand_model', 'brand_model');
		return $this->_ci->brand_model;
	}

	public function getDataSuppliers()
	{
		$this->_ci->load->model('suppliers_data/Suppliers_data_model', 'suppliers_model');
		return $this->_ci->suppliers_model;
	}

	public function getDataCategory()
	{
		$this->_ci->load->model('category/Category_model', 'category_model');
		return $this->_ci->category_model;
	}

	public function getDataProductVariants()
	{
		$this->_ci->load->model('products/Products_variants_model', 'products_variants_model');
		return $this->_ci->products_variants_model;
	}

	public function getDataProductsOwnershipTypes()
	{
		$this->_ci->load->model('ownership_types/Ownership_types_model', 'ownership_types_model');
		return $this->_ci->ownership_types_model;
	}

	public function colorAll()
	{
		$query = "	SELECT a.color_name ,b.color_name as variant_color,a.color_hexa 
					FROM ms_color_name_hexa a
					LEFT JOIN ms_color_name_hexa b ON b.parent_color_id = a.id 
					ORDER BY a.color_name ASC";

		return $this->db->query($query);
	}

	public function getDataGeneralColor()
	{
		$query = "  SELECT *
                    FROM ms_color_name_hexa
                    WHERE parent_color_id = 0 ";

		return $this->db->query($query);
	}

	public function getDataVariantlColor($idParent)
	{
		$query = "  SELECT *
                    FROM ms_color_name_hexa
                    WHERE parent_color_id = $idParent ";

		return $this->db->query($query);
	}

	public function getDataIdColor($id)
	{
		$query = "  SELECT id
                    FROM ms_color_name_hexa
                    WHERE id = '$id' ";

		return $this->db->query($query)->row();
	}

	public function cekDataIdColor($id)
	{
		$query = "  SELECT id,color_name
                    FROM ms_color_name_hexa
                    WHERE id = '$id' ";

		return $this->db->query($query)->row();
	}

	public function getIdColor($name)
	{
		$query = "  SELECT id
                    FROM ms_color_name_hexa
                    WHERE color_name = '$name' 
                    AND parent_color_id  ='0' ";

		return $this->db->query($query)->row();
	}

	public function getIdColorVariant($name)
	{
		$query = "  SELECT id
                    FROM ms_color_name_hexa
                    WHERE color_name = '$name'";

		return $this->db->query($query)->row();
	}

	public function checkProductVariantsExist($name, $color, $size)
	{
		$query = $this->db->select('a.id')
			->from($this->_tabel . ' a')
			->join($this->_tabel_variant . ' b', 'b.users_ms_products_id = a.id', 'INNER')
			->where('a.product_name', $name)
			->where('b.general_color_id', $color)
			->where('b.product_size', $size)
			->get();

		$result = $query->row();

		return $result;
	}

	public function cekProduct($name, $general)
	{
		$this->db->select('a.id');
		$this->db->from('users_ms_products a');
		$this->db->join('users_ms_product_variants b', 'b.users_ms_products_id = a.id', 'left');
		$this->db->where('a.product_name', $name);
		$this->db->where('b.general_color_id', $general);

		$query = $this->db->get();

		return $query;
	}

	public function show($data = [])
	{
		$this->datatables->select(
			"   a.id as id,
                a.product_name,
                a.brand_name,
                a.category_name,
                b.sku ,
                b.product_size,
                b.variant_color_name,
                c.color_name,
                (   select lookup_name 
                    from admins_ms_lookup_values 
                    where lookup_code = a.status and lookup_config = 'products_status') as status_name,
                b.image_name,
                b.id as idVariants,
                DATE_FORMAT(a.created_at,'%d-%m-%Y') as created_at",
			false
		);
		$this->datatables->from("{$this->_tabel} a");
		$this->datatables->join("{$this->_tabel_variant} b", "b.users_ms_products_id = a.id", "inner");
		$this->datatables->join("ms_color_name_hexa c", "c.id = b.general_color_id", "inner");
		$this->datatables->where('a.deleted_at is null AND b.deleted_at is null ', null, false);
		$this->datatables->where(["a.{$this->_table_users_ms_companys}_id" => $this->_users_ms_companys_id]);
		$this->datatables->order_by('b.updated_at desc');

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
					case 'sku':
						$this->datatables->like('b.sku', $setValue);
						break;
					case 'product_name':
						$this->datatables->like('a.product_name', $setValue);
						break;
					case 'brand_name':
						$this->datatables->like('a.brand_name', $setValue);
						break;
					default:
						break;
				}
			}
			if (!empty($setLookupStatus)) {
				$this->datatables->where_in('a.status', $setLookupStatus);
			}

			if (!empty($setStartDate)) {
				$this->datatables->where('a.created_at >=', $setStartDate);
			}

			if (!empty($setStartDate) && !empty($setEndDate)) {
				$this->datatables->where('DATE(a.created_at) >=', $setStartDate);
				$this->datatables->where('DATE(a.created_at) <=', $setEndDate);
			}

			if (empty($setStartDate) && !empty($setEndDate)) {
				$getDateNow = date('Y-m-d');

				$this->datatables->where('DATE(a.created_at) >=', $getDateNow);
				$this->datatables->where('DATE(a.created_at) <=', $setEndDate);
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
				$this->datatables->where_in('a.status', explode(",", $setStatus));
			}

			if (!empty($getSearchBy)) {
				switch ($getSearchBy) {
					case 'sku':
						$this->datatables->like('b.sku', $setValue);
						break;
					case 'product_name':
						$this->datatables->like('a.product_name', $setValue);
						break;
					case 'brand_name':
						$this->datatables->like('a.brand_name', $setValue);
						break;
					default:
						break;
				}
			}

			if (!empty($setStartDate)) {
				$this->datatables->where('a.created_at >=', $setStartDate);
			}

			if (!empty($setStartDate) && !empty($setEndDate)) {
				$this->datatables->where('DATE(a.created_at) >=', $setStartDate);
				$this->datatables->where('DATE(a.created_at) <=', $setEndDate);
			}

			if (empty($setStartDate) && !empty($setEndDate)) {
				$getDateNow = date('Y-m-d');

				$this->datatables->where('DATE(a.created_at) >=', $getDateNow);
				$this->datatables->where('DATE(a.created_at) <=', $setEndDate);
			}
		}

		$fieldSearch = [
			'a.product_name',
			'a.brand_name',
			'a.category_name',
			'b.sku',
			'b.color',
			'b.product_size'
		];

		$this->_searchDefaultDatatables($fieldSearch);

		$buttonPrint = '<button class="btn btn-icon hover-scale btn-sm btnPrint" 
                        data-type="modal" data-fullscreenmodal="0" param="$1|$2|$3|$4|$5"
                        data-url="' . base_url() . 'products/printBarcode" style="background-color: #2E86C1;">
							<i class="fa-solid fa-print fs-4 text-white" ></i>
						</button>';

		$this->datatables->add_column('action', $buttonPrint, 'sku,brand_name,product_name,color_name,product_size');

		return $this->datatables->generate();
	}

	private function validate()
	{
		$response = ['success' => false, 'validate' => true, 'messages' => []];
		$response['type'] = !empty($this->input->post('id')) ? 'update' : 'insert';
		$validation = $this->form_validation;
		$rules = ['trim', 'required', 'xss_clean'];

		$nestedHeader = "kt_docs_repeater_nested_outer";

		$validation->set_rules('select_brand_id', 'Brand', $rules);
		$validation->set_rules('select_supplier_id', 'Supplier', $rules);
		$validation->set_rules('select_category_id', 'Category', $rules);

		for ($i = 0; $i < count($this->input->post('kt_docs_repeater_nested_outer')); $i++) {
			$validation->set_rules($nestedHeader . '[' . $i . '][products_name]', 'Product Name', $rules);
			$validation->set_rules($nestedHeader . '[' . $i . '][select_gender]', 'Gender', $rules);

			$nestedChild = $this->input->post('kt_docs_repeater_nested_outer')[$i]['kt_docs_repeater_nested_inner'];

			for ($j = 0; $j < count($nestedChild); $j++) {
				$validation->set_rules($nestedHeader . '[' . $i . '][kt_docs_repeater_nested_inner]' .
					'[' . $j . '][generate_color]', 'General Color', $rules);
				$validation->set_rules($nestedHeader . '[' . $i . '][kt_docs_repeater_nested_inner]' .
					'[' . $j . '][variant_color]', 'Variant Color', $rules);
				$validation->set_rules($nestedHeader . '[' . $i . '][kt_docs_repeater_nested_inner]' .
					'[' . $j . '][variant_color_name]', 'Variant Color Name', $rules);
				$validation->set_rules($nestedHeader . '[' . $i . '][kt_docs_repeater_nested_inner]' .
					'[' . $j . '][size]', 'Size', $rules);
			}
		}

		$validation->set_error_delimiters('<div class="' . VALIDATION_MESSAGE_FORM . '">', '</div>');

		if ($validation->run() === false) {
			$response['validate'] = false;
			$response['messages']['select_brand_id'] = form_error('select_brand_id');
			$response['messages']['select_supplier_id'] = form_error('select_supplier_id');
			$response['messages']['select_category_id'] = form_error('select_category_id');

			for ($i = 0; $i < count($this->input->post('kt_docs_repeater_nested_outer')); $i++) {
				$response['messages'][$nestedHeader . '[' . $i . '][products_name]'] =
					form_error($nestedHeader . '[' . $i . '][products_name]');

				$response['messages'][$nestedHeader . '[' . $i . '][select_gender]'] =
					form_error($nestedHeader . '[' . $i . '][select_gender]');

				for ($j = 0; $j < count($nestedChild); $j++) {
					$response['messages'][$nestedHeader . '[' . $i . '][kt_docs_repeater_nested_inner]' . '[' . $j . '][generate_color]'] =
						form_error($nestedHeader . '[' . $i . '][kt_docs_repeater_nested_inner]' . '[' . $j . '][generate_color]');
					$response['messages'][$nestedHeader . '[' . $i . '][kt_docs_repeater_nested_inner]' . '[' . $j . '][variant_color]'] = form_error($nestedHeader . '[' . $i . '][kt_docs_repeater_nested_inner]' . '[' . $j . '][variant_color]');
					$response['messages'][$nestedHeader . '[' . $i . '][kt_docs_repeater_nested_inner]' . '[' . $j . '][variant_color_name]'] = form_error($nestedHeader . '[' . $i . '][kt_docs_repeater_nested_inner]' . '[' . $j . '][variant_color_name]');
					$response['messages'][$nestedHeader . '[' . $i . '][kt_docs_repeater_nested_inner]' . '[' . $j . '][size]'] = form_error($nestedHeader . '[' . $i . '][kt_docs_repeater_nested_inner]' . '[' . $j . '][size]');
				}
			}
		}

		return $response;
	}

	private function _validate2()
	{
		$response = ['success' => false, 'validate' => true, 'messages' => []];

		$response['type'] = 'insert';

		$role_validate = ['trim', 'required', 'xss_clean'];
		$validateBrand = ['trim', 'required', 'xss_clean'];
		$validateSupplier = ['trim', 'required', 'xss_clean'];
		$validateCategory = ['trim', 'required', 'xss_clean'];
		$validateSubCategory = ['trim', 'xss_clean'];
		$validateSub2Category = ['trim', 'xss_clean'];
		$validateGender = ['trim', 'required', 'xss_clean'];
		$validateGeneralColor = ['trim', 'required', 'xss_clean'];
		$validateVariantColor = ['trim', 'xss_clean'];
		$validateSize = ['trim', 'required', 'xss_clean'];

		$cek_brand = [
			'cek_brand', function ($value) {
				if (!empty($value) || $value != '') {
					try {
						$cekBrands = $this->getDataBrands()->get(['brand_name' => $value]);

						if (empty($cekBrands)) {
							throw new Exception;
						}

						return true;
					} catch (Exception $e) {
						$this->form_validation->set_message('cek_brand', '{field} Not Exist');
						return false;
					}
				}
			}
		];
		array_push($validateBrand, $cek_brand);

		$cekSupplier = [
			'cekSupplier', function ($value) {
				if (!empty($value) || $value != '') {
					try {
						$cekSupplier = $this->getDataSuppliers()->get(['supplier_name' => $value]);

						if (empty($cekSupplier)) {
							throw new Exception;
						}

						return true;
					} catch (Exception $e) {
						$this->form_validation->set_message('cekSupplier', '{field} Not Exist');
						return false;
					}
				}
			}
		];
		array_push($validateSupplier, $cekSupplier);

		$cekCategory = [
			'cekCategory', function ($value) {
				if (!empty($value) || $value != '') {
					try {
						$cekCategory = $this->getDataCategory()->get(['categories_name' => $value]);

						if (empty($cekCategory)) {
							throw new Exception;
						}

						return true;
					} catch (Exception $e) {
						$this->form_validation->set_message('cekCategory', '{field} Not Exist');
						return false;
					}
				}
			}
		];
		array_push($validateCategory, $cekCategory);

		$cekGender = [
			'cekGender', function ($value) {
				if (!empty($value) || $value != '') {
					try {
						$cekGender = ["man", "woman", "unisex"];

						if (!in_array($value, $cekGender)) {
							throw new Exception;
						}

						return true;
					} catch (Exception $e) {
						$this->form_validation->set_message('cekGender', '{field} Not Exist');
						return false;
					}
				}
			}
		];
		array_push($validateGender, $cekGender);

		$cekGeneralColor = [
			'cekGeneralColor', function ($value) {
				if (!empty($value) || $value != '') {
					try {
						$cek_general_color = $this->db->get_where($this->_tabel_color, ['color_name' => $value])->row();

						if (empty($cek_general_color)) {
							throw new Exception;
						}

						return true;
					} catch (Exception $e) {
						$this->form_validation->set_message('cekGeneralColor', '{field} Not Exist');
						return false;
					}
				}
			}
		];
		array_push($validateGeneralColor, $cekGeneralColor);


		$array1 = $this->input->post();
		$resultArray = array();

		for ($i = 0; $i < count($this->input->post('get_brand_name')); $i++) {

			$key = $array1['get_product_name'][$i] . '-' . $array1['get_general_color'][$i] . '-' . $array1['get_size'][$i];
			$resultArray[] = $key;

			$productName = $this->input->post('get_product_name[]')[$i];
			$generalColor = $this->input->post('get_general_color[]')[$i];
			$parentCategory = $this->input->post('get_category_name[]')[$i];
			$parentSubCategory = $this->input->post('get_sub_category_name[]')[$i];


			$this->form_validation->set_rules('get_brand_name[' . $i . ']', 'Brand', $validateBrand);
			$this->form_validation->set_rules('get_supplier_name[' . $i . ']', 'Supplier', $validateSupplier);
			$this->form_validation->set_rules('get_category_name[' . $i . ']', 'Category', $validateCategory);
			$this->form_validation->set_rules('get_product_name[' . $i . ']', 'Product', $role_validate);
			$this->form_validation->set_rules('get_gender[' . $i . ']', 'Gender', $validateGender);
			$this->form_validation->set_rules('get_general_color[' . $i . ']', 'General Color', $validateGeneralColor);

			$cekSubCategory = [
				'cekSubCategory', function ($value) use ($parentCategory) {
					if (!empty($value) || $value != '') {
						try {

							$cekCategory = $this->getDataCategory()->get(['categories_name' => $parentCategory]);

							if (!empty($cekCategory)) {
								$cek_sub_category = $this->getDataCategory()->get(['parent_categories_id' => $cekCategory->id, 'categories_name' => $value]);
							}

							if (!isset($cek_sub_category)) {
								throw new Exception;
							}

							return true;
						} catch (Exception $e) {
							$this->form_validation->set_message('cekSubCategory', '{field} Not Exist');
							return false;
						}
					}
				}
			];
			array_push($validateSubCategory, $cekSubCategory);

			$this->form_validation->set_rules('get_sub_category_name[' . $i . ']', 'Sub Category', $validateSubCategory);

			$cekSub2Category = [
				'cekSub2Category', function ($value) use ($parentSubCategory) {
					if (!empty($value) || $value != '') {
						try {

							$cekCategory = $this->getDataCategory()->get(['categories_name' => $parentSubCategory]);

							if (!empty($cekCategory)) {
								$cek_sub2_category = $this->getDataCategory()->get(['parent_categories_id' => $cekCategory->id, 'categories_name' => $value]);
							}

							if (!isset($cek_sub2_category)) {
								throw new Exception;
							}

							return true;
						} catch (Exception $e) {
							$this->form_validation->set_message('cekSub2Category', '{field} Not Exist');
							return false;
						}
					}
				}
			];
			array_push($validateSub2Category, $cekSub2Category);

			$this->form_validation->set_rules('get_sub_sub_category_name[' . $i . ']', 'Sub Sub Category', $validateSub2Category);

			$cekVariantColor = [
				'cekVariantColor', function ($value) use ($generalColor) {
					if (!empty($value) || $value != '') {
						try {

							$cek_general_color = $this->db->get_where($this->_tabel_color, ['color_name' => $generalColor]);

							if ($cek_general_color->num_rows() > 0) {
								$cek_variant_color = $this->db->get_where($this->_tabel_color, ['parent_color_id' => $cek_general_color->row()->id, 'color_name' => $value])->row();
							}

							if (!isset($cek_variant_color)) {
								throw new Exception;
							}

							return true;
						} catch (Exception $e) {
							$this->form_validation->set_message('cekVariantColor', '{field} Not Exist');
							return false;
						}
					}
				}
			];
			array_push($validateVariantColor, $cekVariantColor);

			$this->form_validation->set_rules('get_variant_color[' . $i . ']', 'Variant Color', $validateVariantColor);

			$cekDuplicate = [
				'cekDuplicate', function ($value) use ($productName, $generalColor, $resultArray) {
					if (!empty($value) || $value != '') {
						try {

							$resultArrayUnique = array_unique(array_intersect($resultArray, array_unique(array_diff_key($resultArray, array_unique($resultArray)))));

							$combination = $productName . '-' . $generalColor . '-' . $value;


							if (in_array($combination, $resultArrayUnique)) {
								throw new Exception($combination);
							}

							return true;
						} catch (Exception $e) {
							$x = $e->getMessage();
							$this->form_validation->set_message('cekDuplicate', '{field} Duplicate|' . $x . '');
							return false;
						}
					}
				}
			];
			array_push($validateSize, $cekDuplicate);

			$cekSize = [
				'cekSize', function ($value) use ($productName, $generalColor) {
					if (!empty($value) || $value != '') {
						try {

							$cek_general_color = $this->db->get_where($this->_tabel_color, ['color_name' => $generalColor])->row();

							if (!empty($cek_general_color->id)) {
								$cekSize = $this->checkProductVariantsExist($productName, $cek_general_color->id, $value);
							}

							if (!empty($cekSize)) {
								throw new Exception;
							}

							return true;
						} catch (Exception $e) {
							$this->form_validation->set_message('cekSize', '{field} Already Exist');
							return false;
						}
					}
				}
			];
			array_push($validateSize, $cekSize);

			$this->form_validation->set_rules('get_size[' . $i . ']', 'Size', $validateSize);
			array_pop($validateSize);
			array_pop($validateSize);
			array_pop($validateVariantColor);
		}

		$this->form_validation->set_error_delimiters('<div style="margin-top: -2px;margin-bottom: -29px;" class="' . VALIDATION_MESSAGE_FORM . '">', '</div>');

		if ($this->form_validation->run() === false) {
			$response['validate'] = false;
			for ($i = 0; $i < count($this->input->post('get_brand_name')); $i++) {
				$response['messages']['get_brand_name[' . $i . ']'] = form_error('get_brand_name[' . $i . ']');
				$response['messages']['get_supplier_name[' . $i . ']'] = form_error('get_supplier_name[' . $i . ']');
				$response['messages']['get_category_name[' . $i . ']'] = form_error('get_category_name[' . $i . ']');
				$response['messages']['get_product_name[' . $i . ']'] = form_error('get_product_name[' . $i . ']');
				$response['messages']['get_gender[' . $i . ']'] = form_error('get_gender[' . $i . ']');
				$response['messages']['get_general_color[' . $i . ']'] = form_error('get_general_color[' . $i . ']');
				$response['messages']['get_variant_color[' . $i . ']'] = form_error('get_variant_color[' . $i . ']');
				$response['messages']['get_size[' . $i . ']'] = form_error('get_size[' . $i . ']');
				$response['messages']['get_sub_category_name[' . $i . ']'] = form_error('get_sub_category_name[' . $i . ']');
				$response['messages']['get_sub_sub_category_name[' . $i . ']'] = form_error('get_sub_sub_category_name[' . $i . ']');
			}
		}
		// pre($response);
		return $response;
	}

	public function saveMassUpload()
	{

		try {
			$response = self::_validate2();

			if (!$response['validate']) {
				throw new Exception("Error Processing Request", 1);
			}

			$response['success'] = true;
			return $response;
		} catch (Exception $e) {
			return $response;
		}
	}

	public function save()
	{
		$this->db->trans_begin();

		try {
			if ($this->input->post('get_brand_name')) {

				$response = self::_validate2();

				if (!$response['validate']) {
					throw new Exception("Error Processing Request", 1);
				}

				$data = [];
				for ($i = 0; $i < count($this->input->post('get_brand_name')); $i++) {

					$cekBrandId = $this->getDataBrands()
						->get(['brand_name' => $this->input->post('get_brand_name')[$i]]);
					$cekSupplierId = $this->getDataSuppliers()
						->get(['supplier_name' => $this->input->post('get_supplier_name')[$i]]);
					$cekCategoryId = $this->getDataCategory()
						->get(['categories_name' => $this->input->post('get_category_name')[$i]]);

					if (!empty($this->input->post('get_sub_category_name')[$i])) {
						$cekSubCategoryId = $this->getDataCategory()
							->get(['categories_name' => $this->input->post('get_sub_category_name')[$i]]);
					}

					if (!empty($this->input->post('get_sub_category_name')[$i])) {
						$cekSub2CategoryId = $this->getDataCategory()
							->get(['categories_name' => $this->input->post('get_sub_sub_category_name')[$i]]);
					}

					$get_color_variant_name = $this->db->get_where('ms_color_name_hexa', ['color_name' => empty($this->input->post('get_variant_color')[$i]) ? $this->input->post('get_general_color')[$i] : $this->input->post('get_variant_color')[$i]])->row();

					$generateSku = array(
						'brand_name' => $this->input->post('get_brand_name')[$i],
						'product_name' => $this->input->post('get_product_name')[$i],
						'type_name' => $this->input->post('get_category_name')[$i],
						'color_name' => $this->input->post('get_general_color')[$i],
						'product_size' => $this->input->post('get_size')[$i],
					);

					$dataArray = [
						'brand_name' => $this->input->post('get_brand_name')[$i],
						'users_ms_brands_id' => $cekBrandId->id,
						'supplier_name' => $this->input->post('get_supplier_name')[$i],
						'users_ms_suppliers_id' => $cekSupplierId->id,
						'category_name' => $this->input->post('get_category_name')[$i],
						'users_ms_categories_id' => $cekCategoryId->id,
						'category_name_1' => $this->input->post('get_sub_category_name')[$i],
						'users_ms_categories_id_1' => empty($cekSubCategoryId->id) ? '' : $cekSubCategoryId->id,
						'category_name_2' => $this->input->post('get_sub_sub_category_name')[$i],
						'users_ms_categories_id_2' => empty($cekSub2CategoryId->id) ? '' : $cekSub2CategoryId->id,
						'product_name' => $this->input->post('get_product_name')[$i],
						'product_price' => str_replace(".", "", $this->input->post('get_price')[$i]),
						'gender' => $this->input->post('get_gender')[$i],
						'sku' => create_sku($generateSku),
						'general_color_id' => $this->input->post('get_general_color')[$i],
						'variant_color_id' => $this->input->post('get_variant_color')[$i],
						'variant_color_name' => empty($get_color_variant_name->color_hexa) ? '' : $get_color_variant_name->color_hexa,
						'product_size' => $this->input->post('get_size')[$i]
					];

					$data[] = $dataArray;
				}

				$groupedProducts = array();

				foreach ($data as $product) {
					$compositeKey = $product['product_name'] . '-' . $product['general_color_id'];

					$keyData = array_search($compositeKey, array_column($groupedProducts, 'composite_key'));

					$productDetails = array(
						'sku'               => $product['sku'],
						'general_color_id'  => $product['general_color_id'],
						'variant_color_id'  => $product['variant_color_id'],
						'variant_color_name' => $product['variant_color_name'],
						'product_size'      => $product['product_size']
					);

					if ($keyData === false) {
						$groupedProducts[] = array(
							'composite_key'         => $compositeKey,
							'brand_name'            => $product['brand_name'],
							'users_ms_brands_id'    => $product['users_ms_brands_id'],
							'supplier_name'         => $product['supplier_name'],
							'users_ms_suppliers_id' => $product['users_ms_suppliers_id'],
							'category_name'         => $product['category_name'],
							'users_ms_categories_id' => $product['users_ms_categories_id'],
							'category_name_1'       => $product['category_name_1'],
							'users_ms_categories_id_1' => $product['users_ms_categories_id_1'],
							'category_name_2'       => $product['category_name_2'],
							'users_ms_categories_id_2' => $product['users_ms_categories_id_2'],
							'product_name'          => $product['product_name'],
							'product_price'         => str_replace(".", "", $product['product_price']),
							'gender'                => $product['gender'],
							'general_color_id'  => $product['general_color_id'],
							'items'                 => array($productDetails)
						);
					} else {
						array_push($groupedProducts[$keyData]['items'], $productDetails);
					}
				}

				// $groupedProducts = array();
				// foreach ($data as $product) {

				//     $compositeKey = $product['product_name'] . '-' . $product['general_color_id'] . '-' . $product['product_size'];

				//     // echo '<pre>';
				//     // print_r($compositeKey);

				//     $keyData = array_search($compositeKey, array_column($groupedProducts, 'product_name'));

				//     // echo '<pre>';
				//     // print_r(array_column($groupedProducts, 'product_name'));

				//     if ($keyData === false) {
				//         $groupedProducts[] = array(
				//             'brand_name'                => $product['brand_name'],
				//             'users_ms_brands_id'        => $product['users_ms_brands_id'],
				//             'supplier_name'             => $product['supplier_name'],
				//             'users_ms_suppliers_id'     => $product['users_ms_suppliers_id'],
				//             'category_name'             => $product['category_name'],
				//             'users_ms_categories_id'    => $product['users_ms_categories_id'],
				//             'category_name_1'           => $product['category_name_1'],
				//             'users_ms_categories_id_1'  => $product['users_ms_categories_id_1'],
				//             'category_name_2'           => $product['category_name_2'],
				//             'users_ms_categories_id_2'  => $product['users_ms_categories_id_2'],
				//             'product_name'              => $product['product_name'],
				//             'product_price'             => $product['product_price'],
				//             'gender'                    => $product['gender'],
				//             'general_color_name'        => $product['general_color_id'],
				//             'items'                     => [
				//                 '0' => [
				//                     'sku'                   => $product['sku'],
				//                     'general_color_id'      => $product['general_color_id'],
				//                     'variant_color_id'      => $product['variant_color_id'],
				//                     'variant_color_name'    => $product['variant_color_name'],
				//                     'product_size'          => $product['product_size']
				//                 ]
				//             ]
				//         );
				//     } else {
				//         $arrays = array(
				//             'sku'                   => $product['sku'],
				//             'general_color_id'      => $product['general_color_id'],
				//             'variant_color_id'      => $product['variant_color_id'],
				//             'variant_color_name'    => $product['variant_color_name'],
				//             'product_size'          => strtoupper($product['product_size'])
				//         );

				//         array_push($groupedProducts[$keyData]['items'], $arrays);
				//     }
				// }

				for ($i = 0; $i < count($groupedProducts); $i++) {

					$dataInsertProduct = [
						'brand_name'                => $groupedProducts[$i]['brand_name'],
						'users_ms_brands_id'        => $groupedProducts[$i]['users_ms_brands_id'],
						'supplier_name'             => $groupedProducts[$i]['supplier_name'],
						'users_ms_suppliers_id'     => $groupedProducts[$i]['users_ms_suppliers_id'],
						'category_name'             => $groupedProducts[$i]['category_name'],
						'users_ms_categories_id'    => $groupedProducts[$i]['users_ms_categories_id'],
						'category_name_1'           => $groupedProducts[$i]['category_name_1'],
						'users_ms_categories_id_1'  => $groupedProducts[$i]['users_ms_categories_id_1'],
						'category_name_2'           => $groupedProducts[$i]['category_name_2'],
						'users_ms_categories_id_2'  => $groupedProducts[$i]['users_ms_categories_id_2'],
						'product_code'              => mkautono($this->_tabel, 'product_code', 'I'),
						'product_name'              => $groupedProducts[$i]['product_name'],
						'gender'                    => $groupedProducts[$i]['gender'],
						'product_price'             => str_replace(".", "", $groupedProducts[$i]['product_price']),
						'status'                    => 1
					];

					$dataIdGeneral2 = $this->getIdColor($groupedProducts[$i]['general_color_id']);
					$cek = $this->cekProduct($groupedProducts[$i]['product_name'], $dataIdGeneral2->id)->row();


					if (empty($cek)) {
						$execute = $this->insert($dataInsertProduct);
					} else {
						$execute = $cek->id;
					}

					$dataItems = $groupedProducts[$i]['items'];
					foreach ($dataItems as $result) {

						$dataIdGeneral = $this->getIdColor($result['general_color_id']);
						$dataIdVariant = $this->getIdColorVariant($result['variant_color_id']);

						$dataInsertVariant =
							[
								'users_ms_products_id' => $execute,
								'sku' => $result['sku'],
								'general_color_id' => $dataIdGeneral->id,
								'variant_color_id' => !empty($dataIdVariant->id) ? $dataIdVariant->id : '',
								'variant_color_name' => $result['variant_color_name'],
								'product_size' => strtoupper($result['product_size']),
							];

						$execute2 = $this->insertCustom($dataInsertVariant, $this->_tabel_variant);
					}
				}

				if (!$execute || !$execute2) {
					$response['messages'] = 'Failed Insert Mass Upload Product';
					throw new Exception;
				}
			} else {
				$response = self::validate();

				if (!$response['validate']) {
					throw new Exception("Error Processing Request", 1);
				}

				$var_outer = $this->input->post('kt_docs_repeater_nested_outer');

				$brand_id = clearInput($this->input->post('select_brand_id'));
				$category_id = clearInput($this->input->post('select_category_id'));
				$supplier_id = clearInput($this->input->post('select_supplier_id'));

				$brand_name = $this->getDataBrands()->get(['id' => $brand_id])->brand_name;
				$supplier_name = $this->getDataSuppliers()->get(['id' => $supplier_id])->supplier_name;
				$category_name = $this->getDataCategory()->get(['id' => $category_id])->categories_name;

				for ($i = 0; $i < count($var_outer); $i++) {

					$get_sub_category = $this->getDataCategory()->get(['id' => $var_outer[$i]['select_sub_category']])->categories_name;

					if (!empty($var_outer[$i]['select_sub2_category'])) {
						$get_sub2_category = $this->getDataCategory()->get(['id' => $var_outer[$i]['select_sub2_category']])->categories_name;
					}


					$var_inner = $var_outer[$i]['kt_docs_repeater_nested_inner'];

					$products_name = clearInput($var_outer[$i]['products_name']);
					$select_gender = clearInput($var_outer[$i]['select_gender']);

					$data_insert_product = [
						'product_name' => $products_name,
						'gender' => $select_gender,
						'users_ms_brands_id' => $brand_id,
						'brand_name' => $brand_name,
						'users_ms_suppliers_id' => $supplier_id,
						'supplier_name' => $supplier_name,
						'users_ms_categories_id' => $category_id,
						'category_name' => $category_name,
						'users_ms_categories_id_1' => $var_outer[$i]['select_sub_category'],
						'category_name_1' => empty($get_sub_category) ? '' : $get_sub_category,
						'users_ms_categories_id_2' => $var_outer[$i]['select_sub2_category'],
						'category_name_2' => empty($get_sub2_category) ? '' : $get_sub2_category,
						'product_price' => str_replace(".", "", $var_outer[$i]['price']),
						'status' => 1
					];

					$process = $this->insert($data_insert_product);

					foreach ($var_inner as $res) {

						$generate_color = $res['generate_color'];
						$variant_color = $res['variant_color'];
						$variant_color_name = $res['variant_color_name'];
						$product_size = $res['size'];

						$generate_sku = array(
							'brand_name' => $brand_name,
							'product_name' => $products_name,
							'type_name' => $category_name,
							'color_name' => $generate_color,
							'product_size' => $product_size,
						);

						$data_insert_variant =
							[
								'users_ms_products_id' => $process,
								'sku' => create_sku($generate_sku),
								'general_color_id' => $generate_color,
								'variant_color_id' => $variant_color,
								'variant_color_name' => $variant_color_name,
								'product_size' => strtoupper($product_size),
							];


						$process2 = $this->insertCustom($data_insert_variant, $this->_tabel_variant);
					}
				}


				if (!$process || !$process2) {
					$response['messages'] = 'Failed Insert data Product';
					throw new Exception;
				}
			}

			$response['messages'] = "Successfully Insert Data Product";

			$this->db->trans_commit();
			$response['success'] = true;
			return $response;
		} catch (Exception $e) {
			$this->db->trans_rollback();
			return $response;
		}
	}

	public function process_data($getData)
	{
		$datas = json_decode($getData, true);

		$rData = [];
		$validate_check = [];

		if (count($datas) > 0) {
			foreach ($datas as $res) {
				$data_brand = isset($res['BRAND_NAME_(*)']) ? $res['BRAND_NAME_(*)'] : '';
				$data_supplier = isset($res['SUPPLIER_NAME_(*)']) ? $res['SUPPLIER_NAME_(*)'] : '';
				$data_category = isset($res['CATEGORY_NAME_(*)']) ? $res['CATEGORY_NAME_(*)'] : '';
				$data_sub_category = isset($res['SUB_CATEGORY(*)']) ? $res['SUB_CATEGORY(*)'] : '';
				$data_sub2_category = isset($res['SUB_SUB_CATEGORY(*)']) ? $res['SUB_SUB_CATEGORY(*)'] : '';
				$data_product_name = isset($res['PRODUCT_NAME_(*)']) ? $res['PRODUCT_NAME_(*)'] : '';
				$data_gender = isset($res['GENDER_(*)']) ? $res['GENDER_(*)'] : '';
				$data_price = isset($res['PRICE(*)']) ? $res['PRICE(*)'] : '';
				$data_general_color = isset($res['GENERAL_COLOR_(*)']) ? $res['GENERAL_COLOR_(*)'] : '';
				$data_variant_color = isset($res['VARIANT_COLOR(*)']) ? $res['VARIANT_COLOR(*)'] : '';
				$data_size = isset($res['SIZE_(*)']) ? $res['SIZE_(*)'] : '';


				$cekBrands = $this->getDataBrands()->get(['brand_name' => $data_brand]);
				$cekSupplier = $this->getDataSuppliers()->get(['supplier_name' => $data_supplier]);
				$cekCategory = $this->getDataCategory()->get(['categories_name' => $data_category]);
				$cekGender = ["man", "woman", "unisex"];


				if (!empty($cekCategory->id)) {
					$cek_sub_category = $this->getDataCategory()->get(['parent_categories_id' => $cekCategory->id, 'categories_name' => $data_sub_category]);
				}

				if (!empty($cek_sub_category->id)) {
					$cek_sub2_category = $this->getDataCategory()->get(['parent_categories_id' => $cek_sub_category->id, 'categories_name' => $data_sub2_category]);
				}


				$cek_general_color = $this->db->get_where($this->_tabel_color, ['color_name' => $data_general_color])->row();


				if (!empty($data_size) && !empty($cek_general_color)) {
					$cekProduct = $this->checkProductVariantsExist($data_product_name, $cek_general_color->id, $data_size);
				}

				if (!empty($data_variant_color)) {
					$cek_variant_color = $this->db->get_where($this->_tabel_color, ['parent_color_id' => $cek_general_color->id, 'color_name' => $data_variant_color])->row();
				}

				if (empty($data_brand)) {
					$set_brand = $data_brand;
					array_push($validate_check, 3);
				} elseif (empty($cekBrands)) {
					$set_brand = $data_brand;
					array_push($validate_check, 2);
				} else {
					$set_brand = $data_brand;
					array_push($validate_check, 1);
				}

				if (empty($data_supplier)) {
					$set_supplier = $data_supplier;
					array_push($validate_check, 3);
				} elseif (empty($cekSupplier)) {
					$set_supplier = $data_supplier;
					array_push($validate_check, 2);
				} else {
					$set_supplier = $data_supplier;
					array_push($validate_check, 1);
				}

				if (empty($data_category)) {
					$set_category = $data_category;
					array_push($validate_check, 3);
				} elseif (empty($cekCategory)) {
					$set_category = $data_category;
					array_push($validate_check, 2);
				} else {
					$set_category = $data_category;
					array_push($validate_check, 1);
				}

				if (empty($data_product_name)) {
					$set_product_name = $data_product_name;
					array_push($validate_check, 3);
				} else {
					$set_product_name = $data_product_name;
					array_push($validate_check, 1);
				}

				if (empty($data_gender)) {
					$set_sub2_category = $data_gender;
					array_push($validate_check, 3);
				} elseif (!in_array($data_gender, $cekGender)) {
					$set_gender = $data_gender;
					array_push($validate_check, 2);
				} else {
					$set_gender = $data_gender;
					array_push($validate_check, 1);
				}

				if (empty($data_sub_category)) {
					$set_sub_category = $data_sub_category;
					array_push($validate_check, 1);
				} elseif (empty($cek_sub_category)) {
					$set_sub_category = $data_sub_category;
				} else {
					$set_sub_category = $data_sub_category;
					array_push($validate_check, 1);
				}

				if (empty($data_sub2_category)) {
					$set_sub2_category =  $data_sub2_category;
					array_push($validate_check, 1);
				} elseif (empty($cek_sub2_category)) {
					$set_sub2_category = $data_sub2_category;
				} else {
					$set_sub2_category = $data_sub2_category;
					array_push($validate_check, 1);
				}

				if (empty($data_price)) {
					$set_price = $data_price;
					array_push($validate_check, 1);
				} elseif (!is_numeric($data_price)) {
					$set_price = $data_price;
					array_push($validate_check, 5);
				} else {
					$set_price = $data_price;
					array_push($validate_check, 1);
				}

				if (empty($data_general_color)) {
					$set_general_color = $data_general_color;
					array_push($validate_check, 3);
				} elseif (empty($cek_general_color)) {
					$set_general_color = $data_general_color;
					array_push($validate_check, 2);
				} else {
					$set_general_color = $data_general_color;
					array_push($validate_check, 1);
				}

				if (empty($data_variant_color)) {
					$set_variant_color = $data_variant_color;
					array_push($validate_check, 1);
				} elseif (empty($cek_variant_color)) {
					$set_variant_color = $data_variant_color;
					array_push($validate_check, 2);
				} else {
					$set_variant_color = $data_variant_color;
					array_push($validate_check, 1);
				}

				if (empty($data_size)) {
					$set_size = $data_size;
					array_push($validate_check, 3);
				} elseif (!empty($cekProduct)) {
					$set_size = $data_size;
					array_push($validate_check, 4);
				} else {
					$set_size = $data_size;
					array_push($validate_check, 1);
				}

				$row =
					[
						'brand_name'            => $set_brand,
						'supplier_name'         => $set_supplier,
						'category_name'         => $set_category,
						'product_name'          => $set_product_name,
						'gender'                => $set_gender,
						'sub_category_name'     => $set_sub_category,
						'sub_sub_category_name' => $set_sub2_category,
						'price'                 => $set_price,
						'general_color'         => $set_general_color,
						'variant_color'         => $set_variant_color,
						'size'                  => $set_size,
						'validate'              => empty($validate_check) ? '' : $validate_check
					];

				array_push($rData, $row);
			}
		}

		$output =
			[
				"data" => $rData,
			];

		return $output;
	}

	public function process_data_color($get_data)
	{

		$rData = [];

		$getData = $this->getDataVariantlColor($get_data)->result();

		foreach ($getData as $res) {

			$row =
				[
					'id'   => $res->id,
					'parent_color_id'   => $res->parent_color_id,
					'color_name'   => $res->color_name,
					'color_hexa'   => $res->color_hexa,
				];

			array_push($rData, $row);
		}

		$output =
			[
				"data" => $rData,
			];

		return $output;
	}

	public function process_data_color_name($get_data)
	{

		$rData = [];

		$getData = $this->db->get_where('ms_color_name_hexa', ['id' => $get_data])->row();

		$row =
			[
				'color_hexa'   => $getData->color_hexa,
			];

		array_push($rData, $row);

		$output =
			[
				"data" => $rData,
			];

		return $output;
	}

	public function process_sub_category($get_data)
	{

		$rData = [];

		$getData = $this->getDataCategory()->get_all(['parent_categories_id' => $get_data]);

		foreach ($getData as $res) {

			$row =
				[
					'id'   => $res->id,
					'categories_name'   => $res->categories_name,
					'parent_categories_id'   => $res->parent_categories_id,
				];

			array_push($rData, $row);
		}

		$output =
			[
				"data" => $rData,
			];

		return $output;
	}

	public function generateSpreadsheet($data = [])
	{
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();

		$columnHeaders = ['SKU', 'PRODUCT NAME', 'BRAND NAME', 'CATEGORY NAME', 'COLOR', 'SIZE', 'CREATED', 'STATUS'];

		foreach ($columnHeaders as $index => $header) {
			$columnLetter = chr(65 + $index);
			$sheet->setCellValue($columnLetter . '1', $header);
			$sheet->getColumnDimension($columnLetter)->setAutoSize(true);
		}

		$getData = json_decode($this->show($data));

		if ($getData) {
			$rowIndex = 2;

			foreach ($getData->data as $row) {

				$rowData =
					[
						$row->sku,
						$row->product_name,
						$row->brand_name,
						$row->category_name,
						$row->color_name,
						$row->product_size,
						$row->created_at,
						$row->status_name
					];

				$columnLetter = 'A';

				foreach ($rowData as $value) {
					$sheet->setCellValue($columnLetter . $rowIndex, $value);
					$columnLetter++;
				}

				$rowIndex++;
			}
		}

		$writer = new Xlsx($spreadsheet);
		$fileName = 'Product_Report.xlsx';

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="' . $fileName . '"');
		$writer->save('php://output');
	}

	public function downloadCsv()
	{
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();

		$columnHeaders =
			[
				'BRAND NAME (*)',
				'SUPPLIER NAME (*)',
				'CATEGORY NAME (*)',
				'PRODUCT NAME (*)',
				'GENDER (*)',
				'SUB CATEGORY',
				'SUB SUB CATEGORY',
				'PRICE',
				'GENERAL COLOR (*)',
				'VARIANT COLOR',
				'SIZE (*)'
			];

		foreach ($columnHeaders as $index => $header) {
			$columnLetter = chr(65 + $index);
			$sheet->setCellValue($columnLetter . '1', $header);
			$sheet->getColumnDimension($columnLetter)->setAutoSize(true);
		}


		$newSheet = $spreadsheet->createSheet();
		$newSheet->setTitle('Master Brand');

		$newColumnHeadersBrands = [
			'BRAND CODE',
			'BRAND NAME',
		];

		// Set column headers
		foreach ($newColumnHeadersBrands as $index => $header) {
			$columnLetter = chr(65 + $index);
			$newSheet->setCellValue($columnLetter . '1', $header);
			$newSheet->getColumnDimension($columnLetter)->setAutoSize(true);
		}

		$getBrands = $this->getDataBrands()->get_all();

		if ($getBrands) {
			$rowIndex = 2;

			foreach ($getBrands as $row) {

				$rowData =
					[
						$row->brand_code,
						$row->brand_name,
					];

				$columnLetter = 'A';

				foreach ($rowData as $value) {
					$newSheet->setCellValue($columnLetter . $rowIndex, $value);
					$columnLetter++;
				}

				$rowIndex++;
			}
		}

		$newSheet = $spreadsheet->createSheet();
		$newSheet->setTitle('Master Supplier');

		$newColumnHeadersSuppliers = [
			'SUPPLIER CODE',
			'SUPPLIER NAME',
			'EMAIL',
			'ADDRESS',
			'PHONE',
		];

		// Set column headers
		foreach ($newColumnHeadersSuppliers as $index => $header) {
			$columnLetter = chr(65 + $index);
			$newSheet->setCellValue($columnLetter . '1', $header);
			$newSheet->getColumnDimension($columnLetter)->setAutoSize(true);
		}

		$getSuppliers = $this->getDataSuppliers()->get_all();

		if ($getSuppliers) {
			$rowIndex = 2;

			foreach ($getSuppliers as $row) {

				$rowData =
					[
						$row->supplier_code,
						$row->supplier_name,
						$row->email,
						$row->address,
						$row->phone,
					];

				$columnLetter = 'A';

				foreach ($rowData as $value) {
					$newSheet->setCellValue($columnLetter . $rowIndex, $value);
					$columnLetter++;
				}

				$rowIndex++;
			}
		}

		$newSheet = $spreadsheet->createSheet();
		$newSheet->setTitle('Master Category');

		$newColumnHeadersCategory = [
			'CATEGORIES CODE',
			'CATEGORIES NAME',
		];

		// Set column headers
		foreach ($newColumnHeadersCategory as $index => $header) {
			$columnLetter = chr(65 + $index);
			$newSheet->setCellValue($columnLetter . '1', $header);
			$newSheet->getColumnDimension($columnLetter)->setAutoSize(true);
		}

		$getCategory = $this->getDataCategory()->get_all();

		if ($getCategory) {
			$rowIndex = 2;

			foreach ($getCategory as $row) {

				$rowData =
					[
						$row->categories_code,
						$row->categories_name,
					];

				$columnLetter = 'A';

				foreach ($rowData as $value) {
					$newSheet->setCellValue($columnLetter . $rowIndex, $value);
					$columnLetter++;
				}

				$rowIndex++;
			}
		}

		$newSheet = $spreadsheet->createSheet();
		$newSheet->setTitle('Master Color');

		$newColumnHeadersCategory = [
			'GENERAL COLOR NAME',
			'VARIANT COLOR NAME',
			'CODE COLOR HEXA',
		];

		// Set column headers
		foreach ($newColumnHeadersCategory as $index => $header) {
			$columnLetter = chr(65 + $index);
			$newSheet->setCellValue($columnLetter . '1', $header);
			$newSheet->getColumnDimension($columnLetter)->setAutoSize(true);
		}

		$getColor = $this->colorAll()->result();

		if ($getColor) {
			$rowIndex = 2;

			foreach ($getColor as $row) {

				$rowData =
					[
						$row->color_name,
						$row->variant_color,
						$row->color_hexa,
					];

				$columnLetter = 'A';

				foreach ($rowData as $value) {
					$newSheet->setCellValue($columnLetter . $rowIndex, $value);
					$columnLetter++;
				}

				$rowIndex++;
			}
		}

		$writer = new Xlsx($spreadsheet);
		$fileName = 'List_Product.xlsx';

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="' . $fileName . '"');
		$writer->save('php://output');
	}

	public function getProductList($productID = [])
	{
		$this->db->select('product_name,id');
		$this->db->where_in('id', $productID);
		$this->db->where(array('users_ms_companys_id' => $this->_users_ms_companys_id));
		$query = $this->db->get("{$this->_tabel}")->result_array();

		return $query;
	}
}
