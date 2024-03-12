<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Inventory_display_model extends MY_ModelCustomer
{
	use MY_Tables;

	public function __construct()
	{
		$this->_tabel = $this->_table_products;
		$this->_tabel_variant = $this->_table_products_variants;
		parent::__construct();
	}

	public function _getLookupValues()
	{
		$this->_ci->load->model('lookup_values/Lookup_values_model', 'lookup_values_model');
		return $this->_ci->lookup_values_model;
	}

	public function _getSources()
	{
		$this->_ci->load->model('sources/Sources_model', 'sources_model');
		return $this->_ci->load->sources_model;
	}

	public function _getChannels()
	{
		$this->_ci->load->model('channels/Channels_model', 'channels_model');
		return $this->_ci->load->channels_model;
	}

	public function _getProducts()
	{
		$this->_ci->load->model('products/Products_model', 'products_model');
		return $this->_ci->products_model;
	}

	public function _getProductVariants()
	{
		$this->_ci->load->model('products/Products_variants_model', 'products_variants_model');
		return $this->_ci->products_variants_model;
	}

	public function _getProductImages()
	{
		$this->_ci->load->model('product_image/Product_image_model', 'product_image_model');
		return $this->_ci->product_image_model;
	}

	public function _getColorNameHexa()
	{
		$this->_ci->load->model('color_name_hexa/Color_name_hexa_model', 'color_name_hexa_model');
		return $this->_ci->color_name_hexa_model;
	}

	public function _getInventoryDisplayDefault()
	{
		$this->_ci->load->model('inventory_display_defaults/Inventory_display_defaults_model', 'inventory_display_defaults_model');
		return $this->_ci->inventory_display_defaults_model;
	}

	public function _getInventoryDisplayNotDefault()
	{
		$this->_ci->load->model('inventory_display_not_defaults/Inventory_display_not_defaults_model', 'inventory_display_not_defaults_model');
		return $this->_ci->inventory_display_not_defaults_model;
	}

	public function _getInvetoryDisplayDetails()
	{
		$this->_ci->load->model('inventory_display_details/Inventory_display_details_model', 'inventory_display_details_model');
		return $this->_ci->inventory_display_details_model;
	}

	public function _getInventoryDisplayDetails()
	{
		$this->_ci->load->model('inventory_display_details/Inventory_display_details_model', 'inventory_display_details_model');
		return $this->_ci->inventory_display_details_model;
	}

	public function _getProductShadows()
	{
		$this->_ci->load->model("product_shadow/Product_shadow_model", 'product_shadow_model');
		return $this->_ci->product_shadow_model;
	}

	public function _getProductVariantShadows()
	{
		$this->_ci->load->model("product_variant_shadow/Product_variant_shadow_model", "product_variant_shadow_model");
		return $this->_ci->product_variant_shadow_model;
	}

	public function _getInventoryDisplayDatatables()
	{
		$this->_ci->load->model('Inventory_display_datatables', 'inventory_display_datatables');
		return $this->_ci->inventory_display_datatables;
	}

	public function _getInventoryDisplayDefaultShadow()
	{
		$this->_ci->load->model('inventory_display_default_shadows/Inventory_display_default_shadows_model', 'inventory_display_default_shadows_model');
		return $this->_ci->inventory_display_default_shadows_model;
	}

	public function _getInventoryDisplayShadows()
	{
		$this->_ci->load->model('inventory_display_shadows/Inventory_display_shadows_model', 'inventory_display_shadows_model');
		return $this->_ci->inventory_display_shadows_model;
	}

	public function _getInventoryDisplayDetailShadows()
	{
		$this->_ci->load->model('inventory_display_detail_shadows/Inventory_display_detail_shadows_model', 'inventory_display_detail_shadows_model');
		return $this->_ci->inventory_display_detail_shadows_model;
	}

	public function show()
	{

		$filters = !empty($this->input->post('filters')) ? $this->input->post('filters') : false;

		$sourceSearch = "";
		$channelSearch = "";

		if ($filters !== false && is_array($filters)) {
			$typeSearch = "";
			$searchValue = "";
			$status = [];
			foreach ($filters as $ky => $val) {
				$value = $val['value'];
				if (!empty($value)) {
					switch ($val['name']) {
						case 'searchBy':
							$typeSearch = $value;
							break;
						case 'searchValue':
							$searchValue = $value;
							break;
						case 'status[]':
							$status[] = $value;
							break;
						case 'source':
							$sourceSearch = $value;
							break;
						case 'channel':
							$channelSearch = $value;
							break;
					}
				}
			}
		}

		$this->datatables->select("
            a.id as id,
            a.id as product_id,
            a.product_name,
            a.product_price,
            a.product_sale_price,
            GROUP_CONCAT(DISTINCT b.product_size) as product_size,
            c.brand_name,
            a.status,
            (SELECT 
                    lookup_name
                FROM
                    admins_ms_lookup_values
                WHERE
                    lookup_config = 'products_status'
                        AND lookup_code = a.status) as status_name", false);

		$this->datatables->join("{$this->_table_products_variants} b", "b.{$this->_table_products}_id = a.id", "inner");
		$this->datatables->join("{$this->_table_ms_brands} c", "c.id = a.{$this->_table_ms_brands}_id", "inner");

		if ($sourceSearch != "" && $channelSearch != "") {
			$this->datatables->join("{$this->_table_users_ms_inventory_displays} d", "d.{$this->_table_products}_id = a.id and d.{$this->_table_users_ms_companys}_id = {$this->_users_ms_companys_id}", "inner");
			$this->datatables->where(array("d.{$this->_table_admins_ms_sources}_id" => $sourceSearch, "d.{$this->_table_users_ms_channels}_id" => $channelSearch));
		}

		$this->datatables->where("a.deleted_at is null", null, false);
		$this->datatables->where(array("a.{$this->_table_users_ms_companys}_id" => $this->_users_ms_companys_id));
		$this->datatables->group_by(array('a.id', 'a.product_code', 'a.product_name', 'a.product_price', 'a.product_sale_price', 'c.brand_name'));

		$this->datatables->order_by('a.updated_at desc');
		$button = "<button type=\"button\" class=\"btn btn-outline btn-outline-dashed btn-outline-success btn-active-light-success hover-scale btn-sm fw-bold me-2 mb-2 btnLaunched \" data-status=\"$2\" data-url=\"" . base_url("inventory_display/launching/$1") . "\" data-id =\"$1\" {{disabled}}><i class=\"bi bi-rocket-takeoff fs-4 me-2\"></i>Launching</button>";
		$button .= "<button type=\"button\" class=\"btn btn-outline btn-outline-dashed btn-outline-dark btn-active-light-dark hover-scale btn-sm fw-bold me-2 mb-2 btnShadow\" data-status=\"$2\" data-url=\"" . base_url("inventory_display/shadow/$1") . "\" data-id =\"$1\" {{disabledShadow}}><i class=\"bi bi-back fs-4 me-2\"></i>Shadow</button>";
		$this->datatables->add_column('action', $button, "id,status");
		$this->datatables->from("{$this->_table_products} a");

		if ($filters !== false && is_array($filters)) {

			if (count($status) > 0) {
				$this->datatables->where_in('a.status', $status);
			}

			if ($typeSearch != "" && $searchValue != "") {
				switch ($typeSearch) {
					case 'productid':
						$this->datatables->where(array('a.id' => $searchValue));
						break;
					case 'productname':
						$this->datatables->like("a.product_name", $searchValue);
						break;
					case 'brandname':
						$this->datatables->like("c.brand_name", $searchValue);
						break;
					case 'datecreated':
						$split = explode(" - ", $searchValue);
						$this->datatables->where(array('a.created_at >=' => $split[0]));
						$this->datatables->where(array('a.created_at <=' => $split[1]));
						break;
					case 'gender':
						$this->datatables->where(array('a.gender' => $searchValue));
						break;

					case 'datemodified':
						$split = explode(" - ", $searchValue);
						$this->datatables->where(array('a.updated_at >=' => $split[0]));
						$this->datatables->where(array('a.updated_at <=' => $split[1]));
						break;
				}
			}
		}

		$fieldSearch = [
			'a.id',
			'a.product_name',
			'c.brand_name'
		];

		$this->_searchDefaultDatatables($fieldSearch);

		return $this->datatables->generate();
	}

	public function getChannel($id)
	{
		$response = array('success' => false, 'messages' => '');
		try {

			if (is_null($id)) {
				throw new Exception("Failed processing get request", 1);
			}

			$get = $this->_getChannels()->get_all_without_delete(array('admins_ms_sources_id' => $id));
			if (!$get) {
				throw new Exception("Failed get Channel", 1);
			}

			$data = [];
			foreach ($get as $ky => $val) {
				$data[] = [
					'id' => $val->id,
					'channel_name' => $val->channel_name,
				];
			}

			$response['success'] = true;
			$response['data'] = $data;
			return $response;
		} catch (Exception $e) {
			$response['messages'] = $e->getMessage();
			return $response;
		}
	}

	private function showImageDefault($id)
	{
		$this->db->select("
            a.id,
            a.image_name as image,
            a.image_name as image_name,
            IFNULL(b.image_status, 1) as status_id,
            (SELECT 
                    lookup_name
                FROM
                    admins_ms_lookup_values
                WHERE
                    lookup_code = IFNULL(b.image_status, 1)
                        AND lookup_config = 'inventory_display_images') AS status_name
        ", FALSE);

		$this->db->from("{$this->_table_products_images} a");
		$this->db->where("a.deleted_at is null", null, false);
		$this->db->where(array("a.users_ms_companys_id" => $this->_users_ms_companys_id));
		$this->db->where(array("c.users_ms_companys_id" => $this->_users_ms_companys_id));
		$this->db->where(array("a.users_ms_products_id" => $id));
		$this->db->join("{$this->_table_users_ms_inventory_display_defaults} b", "b.users_ms_product_images_id = a.id and b.users_ms_companys_id = a.users_ms_companys_id", "left");
		$this->db->join("{$this->_table_products} c", "c.id = a.users_ms_products_id", "inner");

		$this->db->order_by("a.id desc");

		$query = $this->db->get()->result();
		return $query;
	}

	private function showImageNotDefault($source, $channel, $productID)
	{
		$this->db->select("
            a.id,
            a.image_name as image,
            a.image_name as image_name,
            IFNULL(b.image_status, 1) as status_id,
            (SELECT 
                    lookup_name
                FROM
                    admins_ms_lookup_values
                WHERE
                    lookup_code = IFNULL(b.image_status, 1)
                        AND lookup_config = 'inventory_display_images') AS status_name
        ", FALSE);

		$this->db->from("{$this->_table_products_images} a");
		$this->db->where("a.deleted_at is null", null, false);
		$this->db->where(array("a.users_ms_companys_id" => $this->_users_ms_companys_id));
		$this->db->where(array("c.users_ms_companys_id" => $this->_users_ms_companys_id));
		$this->db->where(array("a.users_ms_products_id" => $productID));
		$this->db->join("{$this->_table_users_ms_inventory_display_details} b", "b.users_ms_product_images_id = a.id and b.users_ms_companys_id = a.users_ms_companys_id and b.admins_ms_sources_id = {$source} and b.users_ms_channels_id = {$channel}", "left");
		$this->db->join("{$this->_table_products} c", "c.id = a.users_ms_products_id", "inner");

		$this->db->order_by("a.id desc");

		$query = $this->db->get()->result();
		return $query;
	}

	public function launching($id)
	{
		try {

			if ($id == null) {
				throw new Exception("Failed process launching", 1);
			}

			$getProduct = $this->_getProducts()->get(array('id' => $id, 'status' => 3));
			if (!$getProduct) {
				throw new Exception("Failed process launching get product", 1);
			}

			$getVariant = $this->_getProductVariants()->get_all(array('users_ms_products_id' => $id));
			if (!$getVariant) {
				throw new Exception("Failed process launching get product variant", 1);
			}

			$variant = $this->headerVariantTable($id, $getProduct->product_name, $getVariant);

			return [
				'variant' => $variant,
				'backUrl' => base_url() . "inventory_display",
			];
		} catch (Exception $e) {
			pageError();
		}
	}

	public function headerVariantTable($id, $productName, $data)
	{
		$no = 1;
		$variant = [];
		foreach ($data as $ky => $val) {
			$generalColor = $val->general_color_id;
			$variantColor = $val->variant_color_id;

			$generalColorData = $this->_getColorNameHexa()->get(array('id' => $generalColor));
			$variantColorData = $this->_getColorNameHexa()->get(array('id' => $variantColor));

			$generalColorName = "";
			if (is_object($generalColorData)) {
				$generalColorName = $generalColorData->color_name;
			}

			$variantColorName = "";
			if (is_object($variantColorData)) {
				$variantColorName = $variantColorData->color_name;
			}

			$variant[] = [
				'No' => $no,
				'ProductID' => $id,
				'Product' => $productName,
				'SKU' => $val->sku,
				'General Color' => $generalColorName,
				'Variant Color' => $variantColorName
			];

			$no++;
		}

		return $variant;
	}

	public function default($id)
	{
		try {

			if ($id == null) {
				throw new Exception("Failed process launching", 1);
			}

			$getProduct = $this->_getProducts()->get(array('id' => $id, 'status !=' => 1));
			if (!$getProduct) {
				throw new Exception("Failed process launching get product", 1);
			}

			$getVariant = $this->_getProductVariants()->get_all(array('users_ms_products_id' => $id));
			if (!$getVariant) {
				throw new Exception("Failed process launching get product variant", 1);
			}

			$variant = $this->headerVariantTable($id, $getProduct->product_name, $getVariant);

			$detail = $this->showImageDefault($id);
			if (!$detail) {
				throw new Exception("No Image on product", 1);
			}

			$no = 1;

			$button = "<button class=\"btn btn-outline btn-outline-dashed btn-outline-success btn-sm btnSelect me-5\" data-imagename=\"$2\"  data-imageid =\"$1\" data-url=\"" . base_url("inventory_display/confirmimage") . "\" data-type=\"modal\">Select</button>";
			$button .= "<button class=\"btn btn-outline btn-outline-dashed btn-outline-primary btn-sm btnView me-5\" data-imagename=\"$2\"  data-imageid =\"$1\" data-url=\"" . base_url("inventory_display/viewimage") . "\" data-type=\"modal\">View</button>";
			$button .= "<button class=\"btn btn-outline btn-outline-dashed btn-outline-warning btn-sm btnCancel\" data-imagename=\"$2\"  data-imageid =\"$1\" {{notSelected}} >Cancel</button>";

			$urlImage = base_url("assets/uploads/products_image/");

			$dataArrayDefault = [];

			foreach ($detail as $ky => $val) {
				$image_id = $val->id;
				$image = $val->image;
				$imageName = $val->image_name;
				$statusID = $val->status_id;
				$status = "<span class=\"statusName\" data-imageid='{$image_id}'>{$val->status_name}</span>";

				$buttonAction = $button;
				$buttonAction = str_replace("$1", $image_id, $buttonAction);
				$buttonAction = str_replace("$2", $imageName, $buttonAction);
				$conditionStatus = $statusID == 1 ? "disabled" : "";
				$buttonAction = str_replace("{{notSelected}}", $conditionStatus, $buttonAction);

				$htmlImage = "<div class=\"symbol symbol-50px\"><span class=\"symbol-label\" style=\"background-image:url(" . $urlImage . $imageName . ");\"></span></div>";

				$detailImage[] = [
					'No' => $no,
					'Image' => $htmlImage,
					'Image Name' => $imageName,
					'Action' => $buttonAction,
					'Status' => $status,
				];
				$no++;

				$dataArrayDefault[] = [$image_id, (int) $statusID];
			}

			return [
				'variant' => $variant,
				'detail' => $detailImage,
				'dataArrayDefault' => $dataArrayDefault,
			];
		} catch (Exception $e) {
			return [
				'error' => $e->getMessage(),
			];
		}
	}

	public function _validateProduct($imageID)
	{
		$this->db->select("a.users_ms_products_id");
		$this->db->from("{$this->_table_products_images} a");
		$this->db->join("{$this->_table_products} b", "b.id = a.users_ms_products_id
            AND b.users_ms_companys_id = a.users_ms_companys_id", "inner");
		$this->db->where(array("a.id" => $imageID, "a.users_ms_companys_id" => $this->_users_ms_companys_id));

		return $this->db->get()->row();
	}

	public function defaultProcess()
	{
		$this->db->trans_begin();
		$response = array('success' => false, 'messages' => "Successfully setting image default product");
		try {

			$source = clearInput($this->input->post('source'));
			$channel = clearInput($this->input->post('channel'));
			$productID = clearInput($this->input->post('productid'));

			//check source , check channel
			if ($source != "default" || $channel != "default") {
				throw new Exception("failed request data", 1);
			}

			//check productID 
			$get = $this->_getProducts()->get(array("id" => $productID));
			if (!$get) {
				throw new Exception("Failed request data", 1);
			}

			$images = $this->input->post('images');
			$images = json_decode($images);
			if (!is_array($images) || count($images) < 1) {
				throw new Exception("Failed Processing Requests", 1);
			}

			$users_ms_products_id = $productID;
			$searchMain = false;

			foreach ($images as $ky => $val) {

				$code = $val->value;
				$checkDif = strpos($code, "|");
				if ($checkDif === false) {
					throw new Exception("Failed Processing Requests", 1);
				}

				$data = explode("|", $code);
				if (!is_array($data) || count($data) != 2) {
					throw new Exception("Failed Processing Requests", 1);
				}

				$imageID = $data[0];
				$lookup = $data[1];

				if ((int) $lookup == 3) {
					$searchMain = true;
				}

				if ((int) $lookup > 3) {
					throw new Exception("Failed Processing Request", 1);
				}

				$get = $this->_validateProduct($imageID);
				if (!$get) {
					throw new Exception("Failed Processing Requests", 1);
				}

				if ($users_ms_products_id != $get->users_ms_products_id) {
					throw new Exception("Failed Processing Requests", 1);
				}

				$insertOrUpdate = [
					'users_ms_products_id' => $users_ms_products_id,
					'users_ms_product_images_id' => $imageID,
				];

				$search = $this->_getInventoryDisplayDefault()->get($insertOrUpdate);

				$insertOrUpdate['image_status'] = $lookup;

				if (!$search) {
					//insert
					$insert = $this->_getInventoryDisplayDefault()->insert($insertOrUpdate);
					if (!$insert) {
						throw new Exception("Failed Processing Data", 1);
					}
				} else {
					//update
					$id = $search->id;
					$sync_status = $search->sync_status;
					if ($sync_status == 2) {
						$insertOrUpdate['sync_status'] = 1;
					}
					$update = $this->_getInventoryDisplayDefault()->update(array('id' => $id), $insertOrUpdate);
					if (!$update) {
						throw new Exception("Failed Processing Data", 1);
					}
				}
			}

			if ($searchMain === false) {
				throw new Exception("Failed request data", 1);
			}

			$this->db->trans_commit();
			$response['success'] = true;
			return $response;
		} catch (Exception $e) {
			$this->db->trans_rollback();
			$response['messages'] = $e->getMessage();
			return $response;
		}
	}

	public function getSources()
	{
		$response = ['success' => true, 'messages' => '', 'data' => []];
		try {

			$this->db->select('t0.*');
			$this->db->from("{$this->_table_admins_ms_sources} t0");
			$this->db->join("{$this->_table_users_ms_authenticate_channels} t1", "t1.sources_id = t0.id");
			$this->db->where(["t1.{$this->_table_users_ms_companys}_id" => $this->_users_ms_companys_id]);
			$this->db->where('t0.deleted_at IS NULL');
			$this->db->where('t0.status', 1);
			$this->db->group_by("t0.id");
			$get = $this->db->get()->result();
			if (!$get) {
				throw new Exception("Data Source not found", 1);
			}

			$data = [];
			foreach ($get as $ky => $val) {
				$data[] = [
					'id' => $val->id,
					'source_name' => $val->source_name,
				];
			}

			if (count($data) < 1) {
				throw new Exception("Data Source not found", 1);
			}

			$response['data'] = $data;
			return $response;
		} catch (Exception $e) {
			$response['messages'] = $e->getMessage();
			$response['success'] = false;
			return $response;
		}
	}

	public function getChannels()
	{
		$response = ["success" => true, "messages" => "", 'data' => []];
		try {
			$id = clearInput($this->input->post('sourceID'));
			if (empty($id)) {
				throw new Exception("Failed Processing Request", 1);
			}

			$get = $this->_getChannels()->get_all(array('admins_ms_sources_id' => $id, 'status' => 1));
			if (!$get) {
				throw new Exception("Data Channel not found", 1);
			}

			$data = [];
			foreach ($get as $ky => $val) {
				$data[] = [
					'id' => $val->id,
					'channel_name' => $val->channel_name,
				];
			}

			if (count($data) < 1) {
				throw new Exception("Data Source not found", 1);
			}

			$response['data'] = $data;
			return $response;
		} catch (Exception $e) {
			$response['success'] = false;
			$response['messages'] = $e->getMessage();
			return $response;
		}
	}

	public function notDefault($source, $channel, $productID)
	{
		try {

			$getProduct = $this->_getProducts()->get(array('id' => $productID, 'status !=' => 1));
			if (!$getProduct) {
				throw new Exception("Failed process launching get product", 1);
			}

			$getVariant = $this->_getProductVariants()->get_all(array('users_ms_products_id' => $productID));
			if (!$getVariant) {
				throw new Exception("Failed process launching get product variant", 1);
			}

			$variant = $this->headerVariantTable($productID, $getProduct->product_name, $getVariant);

			$detail = $this->showImageNotDefault($source, $channel, $productID);
			if (!$detail) {
				throw new Exception("Error Processing Request", 1);
			}

			$no = 1;

			$button = "<button class=\"btn btn-outline btn-outline-dashed btn-outline-success btn-sm btnSelect me-5\" data-imagename=\"$2\"  data-imageid =\"$1\" data-url=\"" . base_url("inventory_display/confirmimage") . "\" data-type=\"modal\">Select</button>";
			$button .= "<button class=\"btn btn-outline btn-outline-dashed btn-outline-primary btn-sm btnView me-5\" data-imagename=\"$2\"  data-imageid =\"$1\" data-url=\"" . base_url("inventory_display/viewimage") . "\" data-type=\"modal\">View</button>";
			$button .= "<button class=\"btn btn-outline btn-outline-dashed btn-outline-warning btn-sm btnCancel\" data-imagename=\"$2\"  data-imageid =\"$1\" {{notSelected}} >Cancel</button>";

			$urlImage = base_url("assets/uploads/products_image/");

			$dataArrayDefault = [];

			foreach ($detail as $ky => $val) {
				$image_id = $val->id;
				$image = $val->image;
				$imageName = $val->image_name;
				$statusID = $val->status_id;
				$status = "<span class=\"statusName\" data-imageid='{$image_id}'>{$val->status_name}</span>";

				$buttonAction = $button;
				$buttonAction = str_replace("$1", $image_id, $buttonAction);
				$buttonAction = str_replace("$2", $imageName, $buttonAction);
				$conditionStatus = $statusID == 1 ? "disabled" : "";
				$buttonAction = str_replace("{{notSelected}}", $conditionStatus, $buttonAction);

				$htmlImage = "<div class=\"symbol symbol-50px\"><span class=\"symbol-label\" style=\"background-image:url(" . $urlImage . $imageName . ");\"></span></div>";

				$detailImage[] = [
					'No' => $no,
					'Image' => $htmlImage,
					'Image Name' => $imageName,
					'Action' => $buttonAction,
					'Status' => $status,
				];
				$no++;

				$dataArrayDefault[] = [$image_id, (int) $statusID];
			}

			return [
				'variant' => $variant,
				'detail' => $detailImage,
				'dataArrayDefault' => $dataArrayDefault,
			];
		} catch (Exception $e) {
			return [];
		}
	}


	public function notDefaultProcess()
	{
		$this->db->trans_begin();
		$response = array('success' => false, 'messages' => "Successfully setting image product");
		try {
			$source = clearInput($this->input->post('source'));
			$channel = clearInput($this->input->post('channel'));
			$productID = clearInput($this->input->post('productid'));

			//check source 
			$get = $this->_getSources()->get(array('id' => $source));
			if (!$get) {
				throw new Exception("failed request data", 1);
			}

			//check channel
			$get = $this->_getChannels()->get(array("id" => $channel));
			if (!$get) {
				throw new Exception("Failed request data", 1);
			}

			//check productID
			$get = $this->_getProducts()->get(array("id" => $productID));
			if (!$get) {
				throw new Exception("Failed request data", 1);
			}

			$images = $this->input->post('images');
			$images = json_decode($images);
			if (!is_array($images) || count($images) < 1) {
				throw new Exception("Failed Processing Requests", 1);
			}

			$users_ms_products_id = $productID;
			$searchMain = false;

			$dataHeader = [
				'admins_ms_sources_id' => $source,
				'users_ms_channels_id' => $channel,
				'users_ms_products_id' => $users_ms_products_id,
			];

			//check header 

			$check = $this->_getInventoryDisplayNotDefault()->get($dataHeader);
			$users_ms_inventory_displays_id = "";
			if (!is_object($check)) {
				$dataHeader['display_status_by'] = $this->_user_id;
				$dataHeader['display_status'] = 4;
				$insertHeader = $this->_getInventoryDisplayNotDefault()->insert($dataHeader);
				if (!$insertHeader) {
					throw new Exception("Failed insert data setting product image", 1);
				}
				$users_ms_inventory_displays_id = $insertHeader;
				$response['launch'] = true;
			} else {
				$users_ms_inventory_displays_id = $check->id;

				$updateHeader = $this->_getInventoryDisplayNotDefault()->update(array('id' => $users_ms_inventory_displays_id), $dataHeader);
				if (!$updateHeader) {
					throw new Exception("Failed Processing Request", 1);
				}
			}

			$updateHeaderToPending = false;

			foreach ($images as $ky => $val) {

				$code = $val->value;
				$checkDif = strpos($code, "|");
				if ($checkDif === false) {
					throw new Exception("Failed Processing Requests", 1);
				}

				$data = explode("|", $code);
				if (!is_array($data) || count($data) != 2) {
					throw new Exception("Failed Processing Requests", 1);
				}

				$imageID = $data[0];
				$lookup = $data[1];

				if ((int) $lookup === 3) {
					$searchMain = true;
				}

				if ((int) $lookup > 3) {
					throw new Exception("Failed Processing Request", 1);
				}

				$get = $this->_validateProduct($imageID);
				if (!$get) {
					throw new Exception("Failed Processing Requests", 1);
				}

				if ($users_ms_products_id != $get->users_ms_products_id) {
					throw new Exception("Failed Processing Requests", 1);
				}

				$insertOrUpdate = [
					'users_ms_inventory_displays_id' => $users_ms_inventory_displays_id,
					'admins_ms_sources_id' => $source,
					'users_ms_channels_id' => $channel,
					'users_ms_products_id' => $users_ms_products_id,
					'users_ms_product_images_id' => $imageID,
				];

				$search = $this->_getInvetoryDisplayDetails()->get($insertOrUpdate);

				$insertOrUpdate['image_status'] = $lookup;

				if (!$search) {
					//insert
					$insertOrUpdate['image_status_by'] = $this->_user_id;
					$insertOrUpdate['sync_status_by'] = $this->_user_id;
					$insert = $this->_getInvetoryDisplayDetails()->insert($insertOrUpdate);
					if (!$insert) {
						throw new Exception("Failed Processing Data", 1);
					}
				} else {
					//update
					$id = $search->id;
					if ($search->image_status != $lookup) {
						$insertOrUpdate['image_status_by'] = $this->_user_id;
						$sync_status = $search->sync_status;
						if ($sync_status == 2) {
							$insertOrUpdate['sync_status'] = 1;
							$insertOrUpdate['sync_status_by'] = $this->_user_id;
						}

						//update header menjadi pending 
						$updateHeaderToPending = true;
					}

					$update = $this->_getInvetoryDisplayDetails()->update(array('id' => $id), $insertOrUpdate);
					if (!$update) {
						throw new Exception("Failed Processing Data", 1);
					}
				}
			}

			if ($searchMain === false) {
				throw new Exception("Failed request data", 1);
			}

			if ($updateHeaderToPending) {
				$updateHeader = $this->_getInventoryDisplayNotDefault()->update(array('id' => $users_ms_inventory_displays_id), ['display_status' => 4, 'display_status_by' => $this->_user_id, 'launch_date' => null]);
				if (!$updateHeader) {
					throw new Exception("Error Processing Request", 1);
				}
				$response['launch'] = true;

				//check status product di inventory 
				//$check = $this->_getInventoryDisplayNotDefault()->get(array('users_ms_products_id' => $productID,'display_status >' => 4));
				//update product to incoming 
				// if(!is_object($check)){
				//     $updateStatusProduct = [
				//         'status' => 3,
				//     ];

				//     $updateProduct = $this->_getProducts()->update(array('id' => $productID),$updateStatusProduct);
				//     if(!$updateProduct){
				//         throw new Exception("Failed Processing Request", 1);

				//     }
				// }
			}

			$this->db->trans_commit();
			$response['success'] = true;
			return $response;
		} catch (Exception $e) {
			$this->db->trans_rollback();
			$response['messages'] = $e->getMessage();
			return $response;
		}
	}

	public function setDefaultImage()
	{
		$this->db->trans_begin();
		$response = array('success' => false, 'messages' => "Successfully setting image product");
		try {
			$source = clearInput($this->input->post('source'));
			$channel = clearInput($this->input->post('channel'));
			$productID = clearInput($this->input->post('productid'));

			if (is_null($source) || is_null($channel) || is_null($productID)) {
				throw new Exception("Error Processing Request", 1);
			}

			//check source 
			$get = $this->_getSources()->get(array('id' => $source));
			if (!$get) {
				throw new Exception("failed request data", 1);
			}

			//check channel
			$get = $this->_getChannels()->get(array("id" => $channel));
			if (!$get) {
				throw new Exception("Failed request data", 1);
			}

			//check productID
			$get = $this->_getProducts()->get(array("id" => $productID));
			if (!$get) {
				throw new Exception("Failed request data", 1);
			}

			$checkDataDefault = $this->_getInventoryDisplayDefault()->get(array('users_ms_products_id' => $productID, 'image_status' => 3));
			if (!is_object($checkDataDefault)) {
				throw new Exception("Default Image Status  is <i><b>Image Not Selected</b></i>", 1);
			}

			$detail = $this->showImageDefault($productID);
			if (!$detail) {
				throw new Exception("Failed request data", 1);
			}

			$dataHeader = [
				'admins_ms_sources_id' => $source,
				'users_ms_channels_id' => $channel,
				'users_ms_products_id' => $productID,
			];

			//check header 

			$check = $this->_getInventoryDisplayNotDefault()->get($dataHeader);
			$users_ms_inventory_displays_id = "";
			$statusInsertDetail = false;
			if (!is_object($check)) {
				$dataHeader['display_status'] = 4;
				$dataHeader['display_status_by'] = $this->_user_id;
				$insertHeader = $this->_getInventoryDisplayNotDefault()->insert($dataHeader);
				if (!$insertHeader) {
					throw new Exception("Failed insert data setting product image", 1);
				}
				$users_ms_inventory_displays_id = $insertHeader;
				$statusInsertDetail = true;
			} else {
				$users_ms_inventory_displays_id = $check->id;

				$getDisplayDetail = $this->_getInventoryDisplayDetails()->get(array('users_ms_inventory_displays_id' => $users_ms_inventory_displays_id));
				if (!$getDisplayDetail) {
					throw new Exception("Fail set Default selected image", 1);
				}

				$update = $this->_getInventoryDisplayDetails()->update(['users_ms_inventory_displays_id' => $users_ms_inventory_displays_id, 'users_ms_channels_id' => $channel, 'admins_ms_sources_id' => $source], ['image_status' => 1]);
				if (!$update) {
					throw new Exception("Failed set Default Selected image status", 1);
				}
			}

			$insertOrUpdate = [
				'users_ms_inventory_displays_id' => $users_ms_inventory_displays_id,
				'admins_ms_sources_id' => $source,
				'users_ms_channels_id' => $channel,
				'users_ms_products_id' => $productID,
			];

			if ($statusInsertDetail) {
				foreach ($detail as $ky => $val) {
					$dataInsert = $insertOrUpdate;
					$dataInsert['users_ms_product_images_id'] = $val->id;
					$dataInsert['image_status'] = $val->status_id;
					$dataInsert['sync_status'] = 1;
					$dataInsert['image_status_by'] = $dataInsert['sync_status_by'] = $this->_user_id;
					$insert = $this->_getInventoryDisplayDetails()->insert($dataInsert);
					if (!$insert) {
						throw new Exception("Fail set Default Selected Image", 1);
					}
				}
			} else {
				foreach ($detail as $ky => $val) {
					$dataSearch = $insertOrUpdate;
					$dataSearch['users_ms_product_images_id'] = $val->id;
					$dataUpdate = ['image_status' => $val->status_id, 'image_status_by' => $this->_user_id];

					$search = $this->_getInventoryDisplayDetails()->get($dataSearch);
					if (!$search) {
						$insertData = $dataSearch;
						$insertData['image_status'] = $val->status_id;
						$insertData['image_status_by'] = $this->_user_id;
						$insert = $this->_getInventoryDisplayDetails()->insert($insertData);
						if (!$insert) {
							throw new Exception("Fail set Default Selected image", 1);
						}
					} else {
						$update = $this->_getInventoryDisplayDetails()->update($dataSearch, $dataUpdate);
						if (!$update) {
							throw new Exception("Fail set Default Selected Image", 1);
						}
					}
				}

				//update header 
				$updateHeader = $this->_getInventoryDisplayNotDefault()->update(['id' => $users_ms_inventory_displays_id], ['display_status' => 4, 'display_status_by' => $this->_user_id, 'launch_date' => null]);
				if (!$updateHeader) {
					throw new Exception("Fail set Default Selected Image", 1);
				}
			}

			/*

																					$searchMain = false;

																					$updateHeaderToPending = false;

																					foreach ($detail as $ky => $val) {
																						$users_ms_product_images_id = $val->id;
																						$image_status = $val->status_id;
																						$sync_status = 1;

																						if ($image_status == 3) {
																							$searchMain = true;
																						}

																						$insertOrUpdate['users_ms_product_images_id'] = $users_ms_product_images_id;

																						$search = $this->_getInvetoryDisplayDetails()->get($insertOrUpdate);

																						$insertOrUpdate['image_status'] = $image_status;
																						$insertOrUpdate['sync_status'] = $sync_status;

																						if (!$search) {
																							//insert
																							$insertOrUpdate['image_status_by'] = $this->_user_id;
																							$insertOrUpdate['sync_status_by'] = $this->_user_id;
																							$insert = $this->_getInvetoryDisplayDetails()->insert($insertOrUpdate);
																							if (!$insert) {
																								throw new Exception("Failed Processing Data", 1);
																							}
																						} else {
																							//update
																							$id = $search->id;
																							if ($search->image_status != $image_status) {
																								$insertOrUpdate['image_status_by'] = $this->_user_id;
																								$sync_status = $search->sync_status;
																								if ($sync_status == 2) {
																									$insertOrUpdate['sync_status'] = 1;
																									$insertOrUpdate['sync_status_by'] = $this->_user_id;
																								}

																								//update header menjadi pending 
																								$updateHeaderToPending = true;
																							}
																							$update = $this->_getInvetoryDisplayDetails()->update(array('id' => $id), $insertOrUpdate);
																							if (!$update) {
																								throw new Exception("Failed Processing Data", 1);
																							}
																						}
																					}

																					if ($searchMain === false) {
																						throw new Exception("Failed request data", 1);
																					}

																					if ($updateHeaderToPending) {
																						$updateHeader = $this->_getInventoryDisplayNotDefault()->update(array('id' => $users_ms_inventory_displays_id), ['display_status' => 4, 'display_status_by' => $this->_user_id, 'launch_date' => null]);
																						if (!$updateHeader) {
																							throw new Exception("Error Processing Request", 1);
																						}

																						//check status product di inventory 
																						//$check = $this->_getInventoryDisplayNotDefault()->get(array('users_ms_products_id' => $productID,'display_status >' => 4));
																						//update product to incoming 
																						// if(!is_object($check)){
																						//     $updateStatusProduct = [
																						//         'status' => 3,
																						//     ];

																						//     $updateProduct = $this->_getProducts()->update(array('id' => $productID),$updateStatusProduct);
																						//     if(!$updateProduct){
																						//         throw new Exception("Failed Processing Request", 1);

																						//     }
																						// }


																						$response['launch'] = true;
																					}

																					*/

			$this->db->trans_commit();
			$response['success'] = true;
			$response['launch'] = true;
			return $response;
		} catch (Exception $e) {
			$this->db->trans_rollback();
			$response['messages'] = $e->getMessage();
			return $response;
		}
	}

	public function showLaunching($productID)
	{
		$this->db->select("
            a.users_ms_products_id,
            a.admins_ms_sources_id,
            b.source_name,
            a.users_ms_channels_id,
            c.channel_name,
            IFNULL(a.launch_date,'-') as launch_date,
            a.display_status,
            (select lookup_name from admins_ms_lookup_values where lookup_config = 'inventory_displays' and lookup_code = a.display_status) as status_name
        ", false);

		$this->db->from("{$this->_table_users_ms_inventory_displays} a");
		$this->db->join("{$this->_table_admins_ms_sources} b", "b.id = a.admins_ms_sources_id", "inner");
		$this->db->join("{$this->_table_users_ms_channels} c", "c.id = a.users_ms_channels_id and c.users_ms_companys_id = a.users_ms_companys_id", "inner");
		$this->db->where("a.deleted_at is null", null, false);
		$this->db->where(array("a.users_ms_products_id" => $productID));
		$this->db->where(array("a.users_ms_companys_id" => $this->_users_ms_companys_id));

		$query = $this->db->get()->result();
		if (!$query) {
			$query = [];
		}
		return $query;
	}

	public function launchProductSource()
	{
		$response = ['success' => true, 'messages' => 'Successfully launch product source'];
		$this->db->trans_begin();
		try {
			$source = clearInput($this->input->post('source'));
			$channel = clearInput($this->input->post('channel'));
			$productID = clearInput($this->input->post('productid'));
			$launchDate = $this->input->post('launchdate');

			if (is_null($launchDate) || $launchDate == "") {
				throw new Exception("Failed Processing Request", 1);
			}

			if (is_null($source) || is_null($channel) || is_null($productID)) {
				throw new Exception("Failed Processing Request", 1);
			}

			//check source 
			$get = $this->_getSources()->get(array('id' => $source));
			if (!$get) {
				throw new Exception("failed request data", 1);
			}

			//check channel
			$get = $this->_getChannels()->get(array("id" => $channel));
			if (!$get) {
				throw new Exception("Failed request data", 1);
			}

			//check productID
			$get = $this->_getProducts()->get(array("id" => $productID));
			if (!$get) {
				throw new Exception("Failed request data", 1);
			}

			$arrCheck = [
				'admins_ms_sources_id' => $source,
				'users_ms_channels_id' => $channel,
				'users_ms_products_id' => $productID,
			];

			$check = $this->_getInventoryDisplayNotDefault()->get($arrCheck);
			$users_ms_inventory_displays_id = "";

			if (!is_object($check)) {
				//saving data from default display
				$get = $this->_getInventoryDisplayDefault()->get_all(array('users_ms_products_id' => $productID));
				if (!$get) {
					throw new Exception("Failed Processing Request", 1);
				}

				$dataHeader = [
					'admins_ms_sources_id' => $source,
					'users_ms_channels_id' => $channel,
					'users_ms_products_id' => $productID,
					'display_status_by' => $this->_user_id,
					'display_status' => 4,
				];

				//save header 
				$saveHeader = $this->_getInventoryDisplayNotDefault()->insert($dataHeader);
				if (!$saveHeader) {
					throw new Exception("Failed Processing Request", 1);
				}

				$users_ms_inventory_displays_id = $saveHeader;

				foreach ($get as $ky => $val) {
					$dataDetail = [
						'users_ms_inventory_displays_id' => $users_ms_inventory_displays_id,
						'admins_ms_sources_id' => $source,
						'users_ms_channels_id' => $channel,
						'users_ms_products_id' => $productID,
						'users_ms_product_images_id' => $val->users_ms_product_images_id,
						'image_status_by' => $this->_user_id,
						'image_status' => $val->image_status,
						'sync_status_by' => $this->_user_id,
					];

					$save = $this->_getInvetoryDisplayDetails()->insert($dataDetail);
					if (!$save) {
						throw new Exception("Failed Processing Request", 1);
					}
				}
			} else {
				$users_ms_inventory_displays_id = $check->id;
			}

			//update header inventory display 
			$updateHeader = [
				'display_status_by' => $this->_user_id,
				'display_status' => 5,
				//pending
				'launch_by' => $this->_user_id,
				'launch_date' => $launchDate,
			];

			$updateHeader = $this->_getInventoryDisplayNotDefault()->update(array('id' => $users_ms_inventory_displays_id), $updateHeader);
			if (!$updateHeader) {
				throw new Exception("Failed Processing Request", 1);
			}

			$this->db->trans_commit();
			return $response;
		} catch (Exception $e) {
			$this->db->trans_rollback();
			$response['success'] = false;
			$response['messages'] = $e->getMessage();
			return $response;
		}
	}

	public function export()
	{
		$status = [];
		$searchValue = "";
		$searchBy = $this->input->get('searchBy');
		if ($searchBy != "status") {
			$searchValue = $this->input->get('searchValue');
		} else {
			$status = $this->input->get('status');
		}

		$source = $this->input->get('source');
		$channel = $this->input->get('channel');
		$searchDatatables = $this->input->get('search');

		$this->db->select("a.id as product_id,
            a.product_name,
            a.product_price,
            a.product_sale_price,
            GROUP_CONCAT(DISTINCT b.product_size) as product_size,
            c.brand_name,
            (SELECT 
                    lookup_name
                FROM
                    admins_ms_lookup_values
                WHERE
                    lookup_config = 'products_status'
                        AND lookup_code = a.status) as status_name,
            f.source_name,
            g.channel_name,
            h.image_name,
            (SELECT 
                    lookup_name
                FROM
                    admins_ms_lookup_values
                WHERE
                    lookup_config = 'inventory_displays'
                        AND lookup_code = d.display_status) AS display_status_name,
            d.launch_date", false);

		$this->db->join("{$this->_table_products_variants} b", "b.{$this->_table_products}_id = a.id", "inner");
		$this->db->join("{$this->_table_ms_brands} c", "c.id = a.{$this->_table_ms_brands}_id", "inner");
		if ($source != "" && $channel != "") {
			$this->db->join("{$this->_table_users_ms_inventory_displays} d", "d.{$this->_table_products}_id = a.id and d.{$this->_table_users_ms_companys}_id = {$this->_users_ms_companys_id}", "inner");
		} else {
			$this->db->join("{$this->_table_users_ms_inventory_displays} d", "d.{$this->_table_products}_id = a.id and d.{$this->_table_users_ms_companys}_id = {$this->_users_ms_companys_id}", "left");
		}

		$this->db->join("{$this->_table_users_ms_inventory_display_details} e", "e.{$this->_table_users_ms_inventory_displays}_id = d.id", "left");
		$this->db->join("{$this->_table_admins_ms_sources} f", "f.id = e.{$this->_table_admins_ms_sources}_id", "left");
		$this->db->join("{$this->_table_users_ms_channels} g", "g.id = e.{$this->_table_users_ms_channels}_id", "left");
		$this->db->join("{$this->_table_products_images} h", "h.id = e.{$this->_table_products_images}_id", "left");

		if ($source != "" && $channel != "") {
			$this->db->where(array("d.{$this->_table_admins_ms_sources}_id" => $source, "d.{$this->_table_users_ms_channels}_id" => $channel));
		}

		$this->db->where("a.deleted_at is null", null, false);
		$this->db->where(array("a.{$this->_table_users_ms_companys}_id" => $this->_users_ms_companys_id));

		if ($searchBy != "status" && $searchValue != "") {
			switch ($searchBy) {
				case 'productid':
					$this->db->where(array('a.id' => $searchValue));
					break;
				case 'productname':
					$this->db->like("a.product_name", $searchValue);
					break;
				case 'brandname':
					$this->db->like("c.brand_name", $searchValue);
					break;
				case 'datecreated':
					$split = explode(" - ", $searchValue);
					$this->db->where(array('a.created_at >=' => $split[0]));
					$this->db->where(array('a.created_at <=' => $split[1]));
					break;
				case 'gender':
					$this->db->where(array('a.gender' => $searchValue));
					break;

				case 'datemodified':
					$split = explode(" - ", $searchValue);
					$this->db->where(array('a.updated_at >=' => $split[0]));
					$this->db->where(array('a.updated_at <=' => $split[1]));
					break;
			}
		} else {
			if (count($status) > 0) {
				$this->db->where_in('a.status', $status);
			}
		}

		if ($searchValue == "" && $searchDatatables != "" && $source == "" && $channel == "") {
			$this->db->group_start();
			$this->db->like("a.id", $searchDatatables);
			$this->db->or_like("a.product_name", $searchDatatables);
			$this->db->or_like("c.brand_name", $searchDatatables);
			$this->db->group_end();
		}

		$this->db->group_by(array('a.id', 'a.product_code', 'a.product_name', 'a.product_price', 'a.product_sale_price', 'c.brand_name', "f.source_name", "g.channel_name", "h.image_name", "d.display_status", "d.launch_date"));
		$this->db->order_by('a.updated_at desc');
		$this->db->from("{$this->_table_products} a");

		$query = $this->db->get()->result_array();

		//prosess converting to xlsx
		$data = array(
			'title' => 'Data Inventory Display',
			'filename' => 'inventory_display',
			'query' => $query,
		);

		$this->excel->process($data);
	}

	public function import()
	{
		$response = array('success' => false, 'messages' => 'Failed import file');
		$document = $this->_document_excel;
		try {

			if (empty($_FILES["file"]["tmp_name"])) {
				throw new Exception("File not found", 1);
			}

			if (file_exists($document)) {
				@unlink($document);
			}

			if (!move_uploaded_file($_FILES["file"]["tmp_name"], $document)) {
				throw new Exception("Failed import file", 1);
			}

			$response['success'] = true;
			$response['messages'] = 'successfully import file';
			return $response;
		} catch (Exception $e) {
			$response['messages'] = $e->getMessage();
			return $response;
		}
	}

	public function preview()
	{
		$response = ['success' => false, 'messages' => '', 'data' => ''];
		$document = $this->_document_excel;
		$data = $this->excel->previewCsv($document);
		if (file_exists($document)) {
			@unlink($document);
		}

		try {

			if (!is_array($data) || count($data) == 0) {
				throw new Exception("Failed file Excel", 1);
			}

			if (count($data) < 2) {
				throw new Exception("File Excel is empty", 1);
			}

			$headerExcel = ['sequence', 'product id', 'source name', 'channel name', 'default image ( Y / N / NOT FILLED )', 'image name', 'STATUS (MAIN / SELECTED)'];

			$check = checkHeaderDocument($data, $headerExcel);
			if ($check === false) {
				throw new Exception("Failed Header Column on File", 1);
			}


			$getProducts = $this->_getProducts()->get_all(array('status' => 3));
			if (!$getProducts) {
				throw new Exception("Data Product must be incoming status", 1);
			}

			$getDataSources = $this->_getSources()->get_all(array('status' => 1));
			if (!$getDataSources) {
				throw new Exception("Data Source is not available", 1);
			}

			foreach ($getDataSources as $kySource => $valSource) {
				$getSources[] = (object) [
					'id' => $valSource->id,
					'source_name' => strtolower($valSource->source_name),
				];
			}


			$getDataChannel = $this->_getChannels()->get_all(array('status' => 1));
			if (!$getDataChannel) {
				throw new Exception("Data Channel is not available", 1);
			}

			foreach ($getDataChannel as $kyChannel => $valChannel) {
				$getChannel[] = (object) [
					'id' => $valChannel->id,
					'channel_name' => strtolower($valChannel->channel_name),
					'admins_ms_sources_id' => $valChannel->admins_ms_sources_id,
				];
			}


			$statusDataSaving = true;

			$header = ['SEQUENCE', 'PRODUCT ID', 'PRODUCT NAME', 'SOURCE NAME'];

			$trueData = [];

			$newRowSequence = "";
			$newProductID = "";
			$newSourceName = "";
			$newChannelName = "";
			$dataStatus = ['MAIN', 'SELECTED'];
			$dataStatusOnSequence = [];
			$keyTrueData = 0;

			$iconTrue = '<span class="svg-icon svg-icon-2x">
                    <svg xmlns="http://www.w3.org/2000/svg" style="margin-top: 9px;" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <polygon points="0 0 24 0 24 24 0 24"/>
                            <path d="M9.26193932,16.6476484 C8.90425297,17.0684559 8.27315905,17.1196257 7.85235158,16.7619393 C7.43154411,16.404253 7.38037434,15.773159 7.73806068,15.3523516 L16.2380607,5.35235158 C16.6013618,4.92493855 17.2451015,4.87991302 17.6643638,5.25259068 L22.1643638,9.25259068 C22.5771466,9.6195087 22.6143273,10.2515811 22.2474093,10.6643638 C21.8804913,11.0771466 21.2484189,11.1143273 20.8356362,10.7474093 L17.0997854,7.42665306 L9.26193932,16.6476484 Z" fill="#008000" fill-rule="nonzero" opacity="0.3" transform="translate(14.999995, 11.000002) rotate(-180.000000) translate(-14.999995, -11.000002) "/>
                            <path d="M4.26193932,17.6476484 C3.90425297,18.0684559 3.27315905,18.1196257 2.85235158,17.7619393 C2.43154411,17.404253 2.38037434,16.773159 2.73806068,16.3523516 L11.2380607,6.35235158 C11.6013618,5.92493855 12.2451015,5.87991302 12.6643638,6.25259068 L17.1643638,10.2525907 C17.5771466,10.6195087 17.6143273,11.2515811 17.2474093,11.6643638 C16.8804913,12.0771466 16.2484189,12.1143273 15.8356362,11.7474093 L12.0997854,8.42665306 L4.26193932,17.6476484 Z" fill="#008000" fill-rule="nonzero" transform="translate(9.999995, 12.000002) rotate(-180.000000) translate(-9.999995, -12.000002) "/>
                        </g>
                    </svg>
                </span>';

			$iconFalse = '<span class="svg-icon svg-icon-2x">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" style="margin-top: 9px;"
                        xmlns="http://www.w3.org/2000/svg">
                        <rect opacity="0.5" x="7.05025" y="15.5356" width="12" height="2"
                            rx="1" transform="rotate(-45 7.05025 15.5356)" fill="#ff0000" />
                        <rect x="8.46447" y="7.05029" width="12" height="2" rx="1"
                            transform="rotate(45 8.46447 7.05029)" fill="#ff0000" />
                    </svg>
                </span>';

			$newDefaultImage = "";

			foreach ($data as $key => $value) {
				if ($key <= $check) {
					continue;
				}

				$errorSequence = "";
				$errorProduct = "";
				$productName = "";

				$sequence = $value['A'];
				$productID = $value['B'];

				if (is_null($sequence) || $sequence == "") {
					$errorSequence = formValidationSelf('form_validation_required', 'SEQUENCE');
				} else if (!is_numeric($sequence)) {
					$errorSequence = formValidationSelf('form_validation_numeric', 'SEQUENCE');
				}


				if (is_null($productID) || $productID == "") {
					$errorProduct = formValidationSelf("form_validation_required", 'PRODUCT ID');
				} else if (!is_numeric($productID)) {
					$errorProduct = formValidationSelf('form_validation_numeric', 'PRODUCT ID');
				} else {
					$cariProduct = array_search($productID, array_column($getProducts, 'id'));
					if ($cariProduct === false) {
						$errorProduct = formValidationSelf('form_validation_found', 'PRODUCT ID');
					} else {

						$productName = $getProducts[$cariProduct]->product_name;

						if ($newRowSequence != $sequence && $newRowSequence != "") {
							$cariProductOnTrueData = array_search($productID, array_column($trueData, 'productId'));
							if ($cariProductOnTrueData !== false) {
								$errorProduct = formValidationSelf('form_validation_existexcel', 'PRODUCT ID');
							}
						} else if ($newRowSequence != "") {
							if ($newProductID != $productID && $newProductID != "") {
								$errorProduct = formValidationSelf('form_validation_matchesexcel', 'PRODUCT ID', 'SEQUENCE');
							}
						}
					}
				}

				$errorSourceName = "";
				$sourceName = $value['C'];

				if (is_null($sourceName) || empty($sourceName)) {
					$errorSourceName = formValidationSelf('form_validation_required', "SOURCE NAME");
				} else {
					$cari = array_search(strtolower($sourceName), array_column($getSources, 'source_name'));
					if ($cari === false) {
						$errorSourceName = formValidationSelf('form_validation_found', 'SOURCE NAME');
					} else if ($newRowSequence != "") {
						if ($sequence == $newRowSequence && $newSourceName != $sourceName) {
							$errorSourceName = formValidationSelf('form_validation_matchesexcel', 'SOURCE NAME', 'SEQUENCE');
						}
					}
				}

				$errorChannelName = "";
				$channelName = $value['D'];

				if (is_null($channelName) || empty($channelName)) {
					$errorChannelName = formValidationSelf('form_validation_required', "CHANNEL NAME");
				} else {
					$cari = array_search(strtolower($channelName), array_column($getChannel, 'channel_name'));
					if ($cari === false) {
						$errorChannelName = formValidationSelf('form_validation_found', 'CHANNEL NAME');
					} else if ($newRowSequence != "") {
						if ($sequence == $newRowSequence && $newChannelName != $channelName) {
							$errorChannelName = formValidationSelf('form_validation_matchesexcel', 'CHANNEL NAME', 'SEQUENCE');
						}
					}
				}

				if ($errorSourceName == "") {
					$cariSource = array_search(strtolower($sourceName), array_column($getSources, 'source_name'));
					$sourceNameID = $getSources[$cariSource]->id;

					$getChannelExist = $this->_getChannels()->get(array('admins_ms_sources_id' => $sourceNameID, 'channel_name' => $channelName));
					if (!$getChannelExist) {
						$errorChannelName = formValidationSelf('form_validation_foundparam', 'CHANNEL NAME', 'SOURCE NAME');
					}
				}

				$errorDefaultImage = "";
				$defaultImage = $value['E'];

				$isDefaultImageYes = false;

				if (!is_null($defaultImage) || !empty($defaultImage)) {
					$defaultImage = strtoupper(strtolower($defaultImage));

					if ($sequence == $newRowSequence && $defaultImage != $newDefaultImage) {
						$errorDefaultImage = formValidationSelf('form_validation_matchesexcel', 'DEFAULT IMAGE', 'SEQUENCE');
					} else {

						if ($defaultImage == 'Y' && $errorProduct == "") {
							$this->db->where(array('users_ms_products_id' => $productID));
							$this->db->where("deleted_at IS NULL", null, false);
							$this->db->where(array('users_ms_companys_id' => $this->_users_ms_companys_id));
							$getDefaultImage = $this->db->get("users_ms_inventory_display_defaults")->row();
							if (!$getDefaultImage) {
								$errorDefaultImage = showMessageErrorForm("default image product ID not available.");
							}
							$isDefaultImageYes = true;
						}
					}
				}

				$imageName = $value['F'];
				$errorImageName = "";

				if (is_null($imageName) || empty($imageName)) {
					$errorImageName = formValidationSelf('form_validation_required', "IMAGE NAME");
				} else {

					$getImage = $this->_getProductImages()->get(array('image_name' => $imageName));

					if (!$getImage) {
						$errorImageName = formValidationSelf('form_validation_found', 'IMAGE NAME');
					} else {
						if ($errorProduct == "" && $getImage->users_ms_products_id != $productID) {
							$errorImageName = formValidationSelf('form_validation_foundparam', 'IMAGE NAME', 'PRODUCT ID');
						}
					}
				}

				$status = $value['G'];
				$errorStatus = "";

				if (is_null($status) || empty($status)) {
					$errorStatus = formValidationSelf('form_validation_required', "STATUS");
				} else {
					$status = strtoupper(strtolower($status));
					$cari = array_search($status, $dataStatus);
					if ($cari === false) {
						$status = "";
						$errorStatus = formValidationSelf('form_validation_found', "STATUS");
					} else {
						if ($newRowSequence == "") {
							if ($status != "MAIN") {
								$errorStatus = showMessageErrorForm("The STATUS Field must be MAIN value");
							}

							$dataStatusOnSequence[] = [
								'status' => $status,
								'key' => $keyTrueData,
							];
						} else {
							if ($newRowSequence == $sequence) {
								$trueData[$keyTrueData - 1]['errorStatus'] = "";
								if (empty($trueData[$keyTrueData - 1]['errorSequence']) && empty($trueData[$keyTrueData - 1]['errorProduct']) && empty($trueData[$keyTrueData - 1]['errorSourceName']) && empty($trueData[$keyTrueData - 1]['errorChannelName']) && empty($trueData[$keyTrueData - 1]['errorImageName']) && empty($trueData[$keyTrueData - 1]['errorDefaultImage'])) {
									$trueData[$keyTrueData - 1]['statusRow'] = $iconTrue;
								}
								$dataStatusOnSequence[] = [
									'status' => $status,
									'key' => $keyTrueData,
								];
							}
						}
					}
				}

				if ($isDefaultImageYes) {
					$errorImageName = "";
					$errorStatus = "";
				}

				if (empty($errorSequence) && empty($errorProduct) && empty($errorSourceName) && empty($errorChannelName) && empty($errorImageName) && empty($errorStatus) && empty($errorDefaultImage)) {
					$statusRow = $iconTrue;
				} else {
					$statusRow = $iconFalse;
					$statusDataSaving = false;
				}


				$trueData[] = [
					'sequence' => $sequence,
					'errorSequence' => $errorSequence,
					'productId' => $productID,
					'errorProduct' => $errorProduct,
					'productName' => $productName,
					'sourceName' => $sourceName,
					'errorSourceName' => $errorSourceName,
					'channelName' => $channelName,
					'errorChannelName' => $errorChannelName,
					'defaultImage' => !empty($defaultImage) ? strtoupper(strtolower($defaultImage)) : "",
					'errorDefaultImage' => $errorDefaultImage,
					'imageName' => $imageName,
					'errorImageName' => $errorImageName,
					'status' => $status,
					'errorStatus' => $errorStatus,
					'action' => '<button type="button" class="btn btn-icon btn-danger btnRemoveRow" {{dataInput}}><i class="bi bi-trash3"></i></button>',
					'statusRow' => $statusRow,
				];

				if ($newRowSequence != "" && ($newRowSequence != $sequence || count($data) == (count($trueData) + 1))) {
					//cek status 
					$cari = array_search('MAIN', array_column($dataStatusOnSequence, 'status'));
					if ($cari === false) {

						foreach ($dataStatusOnSequence as $kyMain => $valMain) {
							$kunci = $valMain['key'];
							if ($trueData[$kunci]['defaultImage'] != 'Y') {
								$trueData[$kunci]['errorStatus'] = showMessageErrorForm("one of the STATUSES fields must have the MAIN status in the same SEQUENCE");
								$trueData[$kunci]['statusRow'] = $iconFalse;
							}
						}
					}

					$dataStatusOnSequence = [];

					if (!is_null($status) || !empty($status)) {
						$dataStatusOnSequence[] = [
							'status' => $status,
							'key' => $keyTrueData,
						];
					}
				}

				$newProductID = $productID;
				$newSourceName = $sourceName;
				$newChannelName = $channelName;
				$newDefaultImage = $defaultImage;

				$newRowSequence = $sequence;
				$keyTrueData++;
			}

			$header[] = 'CHANNEL NAME';
			$header[] = 'DEFAULT IMAGE';
			$header[] = 'IMAGE NAME';
			$header[] = "STATUS";
			$header[] = "ACTION";
			$header[] = "";

			$response['data'] = [
				'thead' => $header,
				'tbody' => $trueData,
				'listProduct' => $getProducts,
				'statusDataSaving' => $statusDataSaving,
			];

			$response['success'] = true;

			return $response;
		} catch (Exception $e) {
			$response['messages'] = $e->getMessage();
			return $response;
		}
	}

	public function checkingData()
	{
		$response = $this->validationDataUpload();
		try {
			if ($response['success'] === false) {
				throw new Exception("Error Processing Request", 1);
			}
			return $response;
		} catch (Exception $e) {
			return $response;
		}
	}

	private function validationDataUpload()
	{
		$response = ['success' => false, 'messages' => '', 'buttonName' => '', 'buttonUrl' => ''];

		try {

			$data = $this->input->post();

			if (!is_array($data) || count($data) == 0) {
				throw new Exception("Error Processing Request", 1);
			}

			$statusDataSaving = true;
			$dataStatus = ['MAIN', 'SELECTED'];

			$iconTrue = '<span class="svg-icon svg-icon-2x">
                    <svg xmlns="http://www.w3.org/2000/svg" style="margin-top: 9px;" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <polygon points="0 0 24 0 24 24 0 24"/>
                            <path d="M9.26193932,16.6476484 C8.90425297,17.0684559 8.27315905,17.1196257 7.85235158,16.7619393 C7.43154411,16.404253 7.38037434,15.773159 7.73806068,15.3523516 L16.2380607,5.35235158 C16.6013618,4.92493855 17.2451015,4.87991302 17.6643638,5.25259068 L22.1643638,9.25259068 C22.5771466,9.6195087 22.6143273,10.2515811 22.2474093,10.6643638 C21.8804913,11.0771466 21.2484189,11.1143273 20.8356362,10.7474093 L17.0997854,7.42665306 L9.26193932,16.6476484 Z" fill="#008000" fill-rule="nonzero" opacity="0.3" transform="translate(14.999995, 11.000002) rotate(-180.000000) translate(-14.999995, -11.000002) "/>
                            <path d="M4.26193932,17.6476484 C3.90425297,18.0684559 3.27315905,18.1196257 2.85235158,17.7619393 C2.43154411,17.404253 2.38037434,16.773159 2.73806068,16.3523516 L11.2380607,6.35235158 C11.6013618,5.92493855 12.2451015,5.87991302 12.6643638,6.25259068 L17.1643638,10.2525907 C17.5771466,10.6195087 17.6143273,11.2515811 17.2474093,11.6643638 C16.8804913,12.0771466 16.2484189,12.1143273 15.8356362,11.7474093 L12.0997854,8.42665306 L4.26193932,17.6476484 Z" fill="#008000" fill-rule="nonzero" transform="translate(9.999995, 12.000002) rotate(-180.000000) translate(-9.999995, -12.000002) "/>
                        </g>
                    </svg>
                </span>';

			$iconFalse = '<span class="svg-icon svg-icon-2x">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" style="margin-top: 9px;"
                        xmlns="http://www.w3.org/2000/svg">
                        <rect opacity="0.5" x="7.05025" y="15.5356" width="12" height="2"
                            rx="1" transform="rotate(-45 7.05025 15.5356)" fill="#ff0000" />
                        <rect x="8.46447" y="7.05029" width="12" height="2" rx="1"
                            transform="rotate(45 8.46447 7.05029)" fill="#ff0000" />
                    </svg>
                </span>';

			$validation = [];
			$validationIcon = [];

			$getProducts = $this->_getProducts()->get_all(array('status' => 3));
			if (!$getProducts) {
				throw new Exception("Data Product must be incoming status", 1);
			}

			$getDataSources = $this->_getSources()->get_all(array('status' => 1));
			if (!$getDataSources) {
				throw new Exception("Data Source is not available", 1);
			}

			foreach ($getDataSources as $kySource => $valSource) {
				$getSources[] = (object) [
					'id' => $valSource->id,
					'source_name' => strtolower($valSource->source_name),
				];
			}


			$getDataChannel = $this->_getChannels()->get_all(array('status' => 1));
			if (!$getDataChannel) {
				throw new Exception("Data Channel is not available", 1);
			}

			foreach ($getDataChannel as $kyChannel => $valChannel) {
				$getChannel[] = (object) [
					'id' => $valChannel->id,
					'channel_name' => strtolower($valChannel->channel_name),
					'admins_ms_sources_id' => $valChannel->admins_ms_sources_id,
				];
			}

			$trueData = [];

			$listProduct = $data['productName'];

			$validationProduct = [];
			$validationDefaultImage = [];
			$validationImageName = [];
			$validationMainStatus = [];
			$validationSelectedStatus = [];
			$calculationError = [];

			foreach ($listProduct as $ky => $val) {
				$i = $ky;

				$sequence = $data['sequence'][$i];
				$productID = $data['productid'][$i];
				$sourceName = $data['sourceName'][$i];
				$channelName = $data['channelName'][$i];
				$defaultImage = $data['defaultImage'][$i];
				$imageName = $data['imageName'][$i];
				$status = $data['status'][$i];

				$errorSequence = "";
				if (is_null($sequence) || $sequence == "") {
					$errorSequence = formValidationSelf('form_validation_required', 'SEQUENCE');
				} else if (!is_numeric($sequence)) {
					$errorSequence = formValidationSelf('form_validation_numeric', 'SEQUENCE');
				}

				$validation[] = [
					'type' => "input",
					'name' => "sequence",
					'sequence' => $i,
					'sequenceTable' => $sequence,
					'message' => $errorSequence,
				];

				$errorProduct = "";
				$productName = "";
				$productIndex = "";

				if (is_null($productID) || $productID == "") {
					$errorProduct = formValidationSelf("form_validation_required", 'PRODUCT ID');
				} else if (!is_numeric($productID)) {
					$errorProduct = formValidationSelf('form_validation_numeric', 'PRODUCT ID');
				} else {
					$cariProduct = array_search($productID, array_column($getProducts, 'id'));
					if ($cariProduct === false) {
						$errorProduct = formValidationSelf('form_validation_found', 'PRODUCT ID');
					} else {
						$productName = $getProducts[$cariProduct]->product_name;
						$productIndex = $getProducts[$cariProduct]->id;
					}
				}

				$validation[] = [
					'type' => "input",
					'name' => "productid",
					'sequence' => $i,
					'sequenceTable' => $sequence,
					'message' => $errorProduct,
					'productName' => $productName,
				];

				$errorSourceName = "";
				$sourceIndex = "";

				if (is_null($sourceName) || empty($sourceName)) {
					$errorSourceName = formValidationSelf('form_validation_required', "SOURCE NAME");
				} else {
					$cari = array_search(strtolower($sourceName), array_column($getSources, 'source_name'));
					if ($cari === false) {
						$errorSourceName = formValidationSelf('form_validation_found', 'SOURCE NAME');
					} else {
						$sourceIndex = $getSources[$cari]->id;
					}
				}

				$validation[] = [
					'type' => "input",
					'name' => "sourceName",
					'sequence' => $i,
					'sequenceTable' => $sequence,
					'message' => $errorSourceName,
				];

				$errorChannelName = "";
				$channelIndex = "";

				if (is_null($channelName) || empty($channelName)) {
					$errorChannelName = formValidationSelf('form_validation_required', "CHANNEL NAME");
				} else {
					$cari = array_search(strtolower($channelName), array_column($getChannel, 'channel_name'));
					if ($cari === false) {
						$errorChannelName = formValidationSelf('form_validation_found', 'CHANNEL NAME');
					} else {
						$channelIndex = $getChannel[$cari]->id;
					}
				}

				$validation[] = [
					'type' => "input",
					'name' => "channelName",
					'sequence' => $i,
					'sequenceTable' => $sequence,
					'message' => $errorChannelName,
				];

				$errorSequenceProduct = "";
				if ($errorProduct == "" && $errorSourceName == "" && $errorChannelName == "") {
					$formula = $productIndex . "|" . $sourceIndex . "|" . $channelIndex;
					if (count($validationProduct) == 0) {
						$validationProduct[] = [
							'sequenceTable' => $sequence,
							'check' => $formula,
						];
					} else {
						$cari = array_search($formula, array_column($validationProduct, 'check'));
						if ($cari !== false) {
							$sequenceProduct = $validationProduct[$cari]['sequenceTable'];
							if ($sequenceProduct != $sequence) {
								$errorSequenceProduct = $sequence;
							}
						} else {
							$validationProduct[] = [
								'sequenceTable' => $sequence,
								'check' => $formula,
							];
						}
					}
				}

				if ($errorSequenceProduct != "") {
					foreach ($validation as $kyValidation => $valValidation) {
						if ($valValidation['sequenceTable'] == $errorSequenceProduct && $valValidation['name'] == "productid") {
							$validation[$kyValidation]['message'] = formValidationSelf("form_validation_existexcel", "PRODUCT ID WITH SOURCE NAME AND CHANNEL NAME");
						}

						if ($valValidation['sequenceTable'] == $errorSequenceProduct && $valValidation['name'] == "sourceName") {
							$validation[$kyValidation]['message'] = formValidationSelf("form_validation_existexcel", "PRODUCT ID WITH SOURCE NAME AND CHANNEL NAME");
						}

						if ($valValidation['sequenceTable'] == $errorSequenceProduct && $valValidation['name'] == "channelName") {
							$validation[$kyValidation]['message'] = formValidationSelf("form_validation_existexcel", "PRODUCT ID WITH SOURCE NAME AND CHANNEL NAME");
						}
					}
				} else {
					$getExistData = $this->_getInventoryDisplayNotDefault()->get(array('users_ms_products_id' => $productIndex, 'admins_ms_sources_id' => $sourceIndex, 'users_ms_channels_id' => $channelIndex));
					if ($getExistData) {
						foreach ($validation as $kyValidation => $valValidation) {
							if ($valValidation['sequenceTable'] == $sequence && $valValidation['name'] == "productid") {
								$validation[$kyValidation]['message'] = formValidationSelf("form_validation_existdatabase", "PRODUCT ID WITH SOURCE NAME AND CHANNEL NAME");
							}

							if ($valValidation['sequenceTable'] == $sequence && $valValidation['name'] == "sourceName") {
								$validation[$kyValidation]['message'] = formValidationSelf("form_validation_existdatabase", "PRODUCT ID WITH SOURCE NAME AND CHANNEL NAME");
							}

							if ($valValidation['sequenceTable'] == $sequence && $valValidation['name'] == "channelName") {
								$validation[$kyValidation]['message'] = formValidationSelf("form_validation_existdatabase", "PRODUCT ID WITH SOURCE NAME AND CHANNEL NAME");
							}
						}
					}
				}

				$errorDefaultImage = "";
				$errorDefaultImageIndex = '';
				$isDefaultImageYes = false;

				$validation[] = [
					'type' => "select",
					'name' => "defaultImage",
					'sequence' => $i,
					'sequenceTable' => $sequence,
					'message' => $errorDefaultImage,
				];

				if (!is_null($defaultImage) || !empty($defaultImage)) {
					$defaultImage = strtoupper(strtolower($defaultImage));

					if ($defaultImage == 'Y') {

						$isDefaultImageYes = true;

						$this->db->where(array('users_ms_products_id' => $productID));
						$this->db->where("deleted_at IS NULL", null, false);
						$this->db->where(array('users_ms_companys_id' => $this->_users_ms_companys_id));
						$getDefaultImage = $this->db->get("users_ms_inventory_display_defaults")->row();
						if (!$getDefaultImage) {
							$errorDefaultImage = showMessageErrorForm("The DEFAULT IMAGE field PRODUCT ID not available.");
						} else {

							if (count($validationDefaultImage) == 0) {
								$validationDefaultImage[] = [
									'sequenceTable' => $sequence,
									'check' => $defaultImage,
								];
							} else {
								$cari = array_search($sequence, array_column($validationDefaultImage, 'sequenceTable'));
								if ($cari === false) {
									$validationDefaultImage[] = [
										'sequenceTable' => $sequence,
										'check' => $defaultImage,
									];
								} else {
									$check = $validationDefaultImage[$cari]['check'];
									if ($check != $defaultImage) {
										$errorDefaultImageIndex = $i;
									}
								}
							}
						}
					} else {
						if (count($validationDefaultImage) == 0) {
							$validationDefaultImage[] = [
								'sequenceTable' => $sequence,
								'check' => $defaultImage,
							];
						} else {
							$cari = array_search($sequence, array_column($validationDefaultImage, 'sequenceTable'));
							if ($cari === false) {
								$validationDefaultImage[] = [
									'sequenceTable' => $sequence,
									'check' => $defaultImage,
								];
							} else {
								$check = $validationDefaultImage[$cari]['check'];
								if ($check != $defaultImage) {
									$errorDefaultImageIndex = $i;
								}
							}
						}
					}
				}

				if ($errorDefaultImageIndex != "") {
					foreach ($validation as $kyValidation => $valValidation) {
						if ($valValidation['sequence'] == $errorDefaultImageIndex && $valValidation['name'] == "defaultImage") {
							$validation[$kyValidation]['message'] = formValidationSelf("form_validation_matchesexcel", "DEFAULT IMAGE");
						}
					}
				}

				if ($isDefaultImageYes === false) {

					$errorImageName = "";
					$errorImageNameIndex = "";

					if (is_null($imageName) || empty($imageName)) {
						$errorImageName = formValidationSelf('form_validation_required', "IMAGE NAME");
					} else {

						$getImage = $this->_getProductImages()->get(array('image_name' => $imageName));

						if (!$getImage) {
							$errorImageName = formValidationSelf('form_validation_found', 'IMAGE NAME');
						} else {
							if ($errorProduct == "" && $getImage->users_ms_products_id != $productID) {
								$errorImageName = formValidationSelf('form_validation_foundparam', 'IMAGE NAME', 'PRODUCT ID');
							}
						}
					}

					$validation[] = [
						'type' => "input",
						'name' => "imageName",
						'sequence' => $i,
						'sequenceTable' => $sequence,
						'message' => $errorImageName,
					];

					if ($errorImageName == "") {
						if (count($validationImageName) == 0) {
							$validationImageName[] = [
								'sequenceTable' => $sequence,
								'imageName' => [
									0 => $imageName,
								],
							];
						} else {
							$cari = array_search($sequence, array_column($validationImageName, 'sequenceTable'));
							if ($cari === false) {
								$validationImageName[] = [
									'sequenceTable' => $sequence,
									'imageName' => [
										0 => $imageName,
									],
								];
							} else {
								$dataImage = $validationImageName[$cari]['imageName'];
								foreach ($dataImage as $kyImage => $valImage) {
									if ($valImage == $imageName) {
										$errorImageNameIndex = $i;
									}
								}

								if ($errorImageNameIndex != "") {
									foreach ($validation as $kyValidation => $valValidation) {
										if ($valValidation['sequence'] == $errorImageNameIndex && $valValidation['name'] == "imageName") {
											$validation[$kyValidation]['message'] = formValidationSelf("form_validation_existdataexcel", "IMAGE NAME");
										}
									}
								}
							}
						}
					}

					$errorStatus = "";

					if (is_null($status) || empty($status)) {
						$errorStatus = formValidationSelf('form_validation_required', "STATUS");
					} else {
						$status = strtoupper(strtolower($status));
						$cari = array_search($status, $dataStatus);
						if ($cari === false) {
							$status = "";
							$errorStatus = formValidationSelf('form_validation_found', "STATUS");
						}
					}

					$validation[] = [
						'type' => "select",
						'name' => "status",
						'sequence' => $i,
						'sequenceTable' => $sequence,
						'message' => $errorStatus,
					];

					if ($errorStatus == "") {
						$errorMainStatus = "";
						switch ($status) {
							case 'MAIN':

								if (count($validationMainStatus) == 0) {
									$validationMainStatus[] = [
										'sequenceTable' => $sequence,
										'check' => $status,
									];
								} else {
									$cari = array_search($sequence, array_column($validationMainStatus, 'sequenceTable'));
									if ($cari === false) {
										$validationMainStatus[] = [
											'sequenceTable' => $sequence,
											'check' => $status,
										];
									} else {
										$errorMainStatus = $i;
									}
								}

								break;
						}

						if ($errorMainStatus != "") {
							foreach ($validation as $kyValidation => $valValidation) {
								if ($valValidation['sequence'] == $errorMainStatus && $valValidation['name'] == "status") {
									$validation[$kyValidation]['message'] = showMessageErrorForm("one of the STATUSES fields must have one the MAIN status in the same SEQUENCE");
								}
							}
						}

						if ($status == 'MAIN') {
							$validationSelectedStatus[$sequence] = true;
						}
					}
				}

				if (count($validationIcon) == 0) {
					$validationIcon[] = [
						'sequence' => $i,
						'icon' => $iconTrue,
					];
				} else {
					$cari = array_search($i, array_column($validationIcon, 'sequence'));
					if ($cari === false) {
						$validationIcon[] = [
							'sequence' => $i,
							'icon' => $iconTrue,
						];
					}
				}
			}

			foreach ($validation as $ky => $val) {
				$sequence = $val['sequence'];
				$sequenceTable = $val['sequenceTable'];
				$message = $val['message'];
				$error = 0;
				if ($message != "") {
					$error = 1;
				} else {
					if ($val['name'] == "status") {
						$cari = array_search($sequenceTable, $validationSelectedStatus);
						if ($cari === false) {
							$validation[$ky]['message'] = showMessageErrorForm("one of the STATUSES fields must have the MAIN status in the same SEQUENCE");
							$error = 1;
						}
					}
				}

				if (count($calculationError) == 0 && $error == 1) {
					$calculationError[] = [
						'sequence' => $sequence,
						'error' => true,
					];
				} else if ($error == 1) {
					$cari = array_search($sequence, array_column($calculationError, 'sequence'));
					if ($cari === false) {
						$calculationError[] = [
							'sequence' => $sequence,
							'error' => true,
						];
					}
				}
			}

			if (count($calculationError) > 0) {
				$statusDataSaving = false;
			}

			foreach ($calculationError as $ky => $val) {
				$sequence = $val['sequence'];
				$cari = array_search($sequence, array_column($validationIcon, 'sequence'));
				if ($cari !== false) {
					$validationIcon[$cari]['icon'] = $iconFalse;
				}
			}


			$response['messages'] = "";
			$response['buttonUrl'] = $statusDataSaving ? base_url() . "inventory_display/processupload" : base_url() . "inventory_display/checkingdata";
			$response['buttonName'] = $statusDataSaving ? "Save Change" : "Check Data";
			$response['validation'] = $validation;
			$response['validationIcon'] = $validationIcon;
			$response['success'] = $statusDataSaving;

			return $response;
		} catch (Exception $e) {
			$response['messages'] = $e->getMessage();
			$response['buttonUrl'] = base_url() . "inventory_display/checkingdata";
			$response['buttonName'] = 'Check Data';
			$response['validation'] = "";
			$response['validationIcon'] = "";
			$response['success'] = false;

			return $response;
		}
	}

	public function saveUpload()
	{
		$response = ['success' => false, 'messages' => '', 'buttonName' => '', 'buttonUrl' => ''];

		$this->db->trans_begin();
		try {

			$data = $this->input->post();
			if (!is_array($data) || count($data) == 0) {
				throw new Exception("Error Processing Request1", 1);
			}

			$looping = true;
			$i = 0;
			$trueData = [];

			$sourceNameArray = [];
			$channelNameArray = [];
			$imageNameArray = [];

			while ($looping) {

				if (!isset($data['sequence'][$i]) && empty($data['sequence'][$i])) {
					$looping = false;
					break;
				}

				$sequence = $data['sequence'][$i];
				$productID = $data['productid'][$i];
				$sourceName = $data['sourceName'][$i];
				$channelName = $data['channelName'][$i];
				$defaultImage = $data['defaultImage'][$i];
				$imageName = $data['imageName'][$i];
				$status = $data['status'][$i];

				$searchKey = $sequence . "||" . $productID . "||" . $sourceName . "||" . $channelName;

				if (count($trueData) == 0) {
					$trueData[] = [
						'search' => $searchKey,
						'sequence' => $sequence,
						'productID' => $productID,
						'sourceName' => $sourceName,
						'channelName' => $channelName,
						'defaultImage' => $defaultImage,
						'details' => [
							0 => [
								'imageName' => $imageName,
								'status' => $status,
							]
						]
					];

					$sourceNameArray[] = $sourceName;
					$channelNameArray[] = $channelName;
					$imageNameArray[] = $imageName;
				} else {
					$cari = array_search($searchKey, array_column($trueData, 'search'));
					if ($cari !== false) {
						$trueData[$cari]['details'][] = [
							'imageName' => $imageName,
							'status' => $status,
						];
						$imageNameArray[] = $imageName;
					} else {
						$trueData[] = [
							'search' => $searchKey,
							'sequence' => $sequence,
							'productID' => $productID,
							'sourceName' => $sourceName,
							'channelName' => $channelName,
							'defaultImage' => $defaultImage,
							'details' => [
								0 => [
									'imageName' => $imageName,
									'status' => $status,
								]
							]
						];

						$sourceNameArray[] = $sourceName;
						$channelNameArray[] = $channelName;
						$imageNameArray[] = $imageName;
					}
				}


				$i++;
			}

			//end looping 
			$this->db->select("id,LOWER(source_name) as source_name", false);
			$this->db->where_in("source_name", $sourceNameArray);
			$this->db->where("deleted_at IS NULL", null, false);
			$getSources = $this->db->get("admins_ms_sources")->result_array();
			if (!$getSources) {
				throw new Exception("Error Processing Request2", 1);
			}

			$this->db->select("concat(admins_ms_sources_id,'|',LOWER(channel_name)) as searchkey, id", false);
			$this->db->where_in("channel_name", $channelNameArray);
			$this->db->where(array("users_ms_companys_id" => $this->_users_ms_companys_id));
			$this->db->where("deleted_at IS NULL", null, false);
			$getChannels = $this->db->get("users_ms_channels")->result_array();
			if (!$getChannels) {
				throw new Exception("Error Processing Request3", 1);
			}

			$this->db->select("id,LOWER(image_name) as image_name", false);
			$this->db->where_in('image_name', $imageNameArray);
			$this->db->where(array("users_ms_companys_id" => $this->_users_ms_companys_id));
			$this->db->where("deleted_at IS NULL", null, false);
			$getImages = $this->db->get("users_ms_product_images")->result_array();
			if (!$getImages) {
				throw new Exception("Error Processing Request4", 1);
			}

			foreach ($trueData as $ky => $val) {

				$sourceName = strtolower($val['sourceName']);
				$cari = array_search($sourceName, array_column($getSources, 'source_name'));
				if ($cari === false) {
					throw new Exception("Error Processing Request5", 1);
				}

				$sourceID = $getSources[$cari]['id'];

				$channelName = strtolower($val['channelName']);
				$searchKey = $sourceID . "|" . $channelName;
				$cari = array_search($searchKey, array_column($getChannels, 'searchkey'));
				if ($cari === false) {
					throw new Exception("Error Processing Request6", 1);
				}

				$channelID = $getChannels[$cari]['id'];
				$productID = $val['productID'];

				$header = [
					'admins_ms_sources_id' => $sourceID,
					'users_ms_channels_id' => $channelID,
					'users_ms_products_id' => $productID,
					'display_status_by' => $this->_user_id,
					'display_status' => 4,
					'created_by' => $this->_user_id,
					'updated_by' => $this->_user_id,
				];

				$save = $this->_getInventoryDisplayNotDefault()->insert($header);
				if (!$save) {
					throw new Exception("Fail saving data", 1);
				}

				$users_ms_inventory_displays_id = $save;

				if ($val['defaultImage'] == "Y") {
					$dataDefault = $this->_getInventoryDisplayDefault()->get_all(array('users_ms_products_id' => $productID));
					if (!$dataDefault) {
						throw new Exception("Fail Data Default Selected Image", 1);
					}

					foreach ($dataDefault as $kyDefault => $valDefault) {
						$detail = [
							'users_ms_inventory_displays_id' => $users_ms_inventory_displays_id,
							'admins_ms_sources_id' => $sourceID,
							'users_ms_channels_id' => $channelID,
							'users_ms_products_id' => $productID,
							'users_ms_product_images_id' => $valDefault->users_ms_product_images_id,
							'image_status_by' => $this->_user_id,
							'image_status' => $valDefault->image_status,
							'sync_status_by' => $this->_user_id,
							'sync_status' => 1,
							'created_by' => $this->_user_id,
							'updated_by' => $this->_user_id,
						];

						$save = $this->_getInvetoryDisplayDetails()->insert($detail);
						if (!$save) {
							throw new Exception("Error Processing Request8", 1);
						}
					}
				} else {

					$detail = [];
					foreach ($val['details'] as $kyDetail => $valDetails) {
						$imageName = strtolower($valDetails['imageName']);
						$status = strtolower($valDetails['status']) == 'main' ? 3 : 2;
						$cari = array_search($imageName, array_column($getImages, 'image_name'));
						if ($cari === false) {
							throw new Exception("Error Processing Request7", 1);
						}

						$imageID = $getImages[$cari]['id'];

						$detail = [
							'users_ms_inventory_displays_id' => $users_ms_inventory_displays_id,
							'admins_ms_sources_id' => $sourceID,
							'users_ms_channels_id' => $channelID,
							'users_ms_products_id' => $productID,
							'users_ms_product_images_id' => $imageID,
							'image_status_by' => $this->_user_id,
							'image_status' => $status,
							'sync_status_by' => $this->_user_id,
							'sync_status' => 1,
							'created_by' => $this->_user_id,
							'updated_by' => $this->_user_id,
						];

						$save = $this->_getInvetoryDisplayDetails()->insert($detail);
						if (!$save) {
							throw new Exception("Error Processing Request8", 1);
						}
					}
				}
			}

			$response['success'] = true;
			$response['messages'] = "successfully upload data";
			$this->db->trans_commit();
			return $response;
		} catch (Exception $e) {
			$this->db->trans_rollback();
			$response['messages'] = $e->getMessage();
			return $response;
		}
	}

	//shadow

	public function shadow($id)
	{
		try {

			if ($id == null) {
				throw new Exception("Failed process launching", 1);
			}

			$getLookup = $this->_getLookupValues()->get_all(['lookup_config' => 'products_status']);

			$getProduct = $this->_getProducts()->get(array('id' => $id));
			if (!$getProduct || ($getProduct && $getProduct->status < 3)) {
				throw new Exception("Failed process get product shadow", 1);
			}

			$header = [];

			$keyStatus = array_search($getProduct->status, array_column($getLookup, 'lookup_code'));
			$header[] = [
				'No' => 1,
				'Product ID' => $getProduct->id,
				'Product Name' => $getProduct->product_name,
				'Status' => $keyStatus !== false ? $getLookup[$keyStatus]->lookup_name : "-",
				'Shadow Launch Date' => "-",
				'Action' => "-"
			];

			$users_ms_products_id = $getProduct->id;

			$getProductShadow = $this->_getProductShadows()->get_all(array('users_ms_products_id' => $users_ms_products_id));

			$no = 2;
			foreach ($getProductShadow as $ky => $val) {
				$baseUrlLaunchingShadow = base_url() . "inventory_display/shadowlaunching/{$val->users_ms_product_shadows_id}";
				$keyStatus = array_search($val->status, array_column($getLookup, 'lookup_code'));

				$this->db->where(array('users_ms_product_shadows_id' => $val->id));
				$this->db->where("launch_date >= CURDATE()", null, false);
				$getLaunch = $this->db->get("{$this->_table_users_ms_inventory_display_shadows}")->row();
				$shadowLaunchDate = $getLaunch ? $getLaunch->launch_date : "-";

				$header[] = [
					'No' => $no,
					'Product ID' => $val->users_ms_product_shadows_id,
					'Product Name' => $getProduct->product_name,
					'Status' => $keyStatus !== false ? $getLookup[$keyStatus]->lookup_name : "-",
					'Shadow Launch Date' => $shadowLaunchDate,
					'Action' => $val->status > 2 ? "<button class=\"btn btn-outline btn-outline-dashed btn-outline-primary btn-sm me-2 btnLaunchingShadow\" data-status=\"{$val->status}\" data-color=\"btn-outline-primary\" data-type=\"modal\" data-url=\"{$baseUrlLaunchingShadow}\">Launching</button>" : "-",
				];

				$no++;
			}



			return [
				'header' => $header,
				'backUrl' => base_url() . "inventory_display",
			];
		} catch (Exception $e) {
			pageError();
		}
	}

	public function processAddShadow($productID)
	{
		$response = ['success' => true, 'validate' => true, 'messages' => ""];
		$validate = $this->_validateQty();
		if (!$validate['validate']) {
			return $validate;
		}

		$this->db->trans_begin();
		try {

			$qty = clearInput($this->input->post('qty'));

			$check = $this->_getProducts()->get(array('id' => $productID));
			if (!$check) {
				throw new Exception("Failed Product ID", 1);
			}

			$users_ms_products_id = $check->id;
			$productName = $check->product_name;

			$getBatch = $this->_getProductShadows()->getLastBatch($users_ms_products_id);
			$batch = 1;
			if (is_object($getBatch)) {
				$batch = $getBatch->batch + 1;
			}

			$getVariants = $this->_getProductVariants()->get_all(array('users_ms_products_id' => $productID));
			if (!$getVariants) {
				throw new Exception("Failed Processing Request", 1);
			}

			$doubleSuffix = 0;
			$startBatch = $batch;
			if ($batch > 25) {
				$doubleSuffix = $batch - 25;
			}

			$start = 64;
			$listProduct = [];

			for ($i = 0; $i < $qty; $i++) {
				$suffix = $doubleSuffix > 0 ? strtolower(chr($start + $doubleSuffix)) . "" . strtolower(chr($start + $doubleSuffix)) : strtolower(chr($start + $startBatch));
				if ($batch > 25) {
					$doubleSuffix = $batch - 25;
				}

				$users_ms_product_shadows_id = $users_ms_products_id . "" . $suffix;
				$listProduct[$i] = [
					'users_ms_product_shadows_id' => $users_ms_product_shadows_id,
					'batch' => $batch,
					'status' => 3,
					'users_ms_products_id' => $users_ms_products_id,
				];

				foreach ($getVariants as $ky => $val) {
					$users_ms_product_variants_id = $val->id;
					$sku = $val->sku;

					$generateSku = $sku . "" . $suffix;
					$listProduct[$i]['details'][] = [
						'users_ms_product_variants_id' => $users_ms_product_variants_id,
						'sku' => $generateSku,
					];
				}

				$batch++;
				$start++;
			}

			$sendHeader = [];
			foreach ($listProduct as $ky => $val) {
				$details = $val['details'];
				unset($val['details']);
				$header = $val;

				//saving header 
				$insert = $this->_getProductShadows()->insert($header);
				if (!$insert) {
					throw new Exception("Fail saving data", 1);
				}

				$sendHeader[] = $header;

				$saving_shadows_id = $insert;
				for ($i = 0; $i < count($details); $i++) {
					$details[$i]['users_ms_product_shadows_id'] = $saving_shadows_id;
				}

				//insert batch 
				$insert = $this->_getProductVariantShadows()->insert_batch($details);
				if (!$insert) {
					throw new Exception("fail saving detail data", 1);
				}
			}

			$getLookup = $this->_getLookupValues()->get_all(['lookup_config' => 'products_status']);
			if (!$getLookup) {
				throw new Exception("Error Processing Request", 1);
			}

			for ($i = 0; $i < count($sendHeader); $i++) {
				$baseUrlLaunchingShadow = base_url() . "inventory_display/shadowlaunching/{$sendHeader[$i]['users_ms_product_shadows_id']}";
				$cari = array_search($sendHeader[$i]['status'], array_column($getLookup, 'lookup_code'));
				$sendHeader[$i]['statusName'] = $getLookup[$cari]->lookup_name;
				$sendHeader[$i]['productName'] = $productName;
				$sendHeader[$i]['button'] = "<button class=\"btn btn-outline btn-outline-dashed btn-outline-primary btn-sm me-2 btnLaunchingShadow\" data-status=\"{$sendHeader[$i]['status']}\" data-color=\"btn-outline-primary\" data-type=\"modal\" data-url=\"{$baseUrlLaunchingShadow}\">Launching</button>";
			}

			$this->db->trans_commit();
			$response['messages'] = "Successfully add shadow";
			$response['header'] = $sendHeader;
			return $response;
		} catch (Exception $e) {
			$this->db->trans_rollback();
			$response['messages'] = $e->getMessage();
			$response['success'] = false;
			return $response;
		}
	}

	private function _validateQty()
	{
		$response = array('success' => false, 'validate' => true, 'messages' => []);
		$rule = ['trim', 'required', 'xss_clean', 'numeric'];
		$this->form_validation->set_rules('qty', 'Qty', $rule);

		$this->form_validation->set_error_delimiters('<div class="' . VALIDATION_MESSAGE_FORM . '">', '</div>');

		if ($this->form_validation->run() === false) {
			$response['validate'] = false;
			foreach ($this->input->post() as $key => $value) {
				$response['messages'][$key] = form_error($key);
			}
		}

		return $response;
	}

	public function shadowLaunching($productIDShadow = null)
	{
		try {
			$getShadow = $this->_getProductShadows()->get(array('users_ms_product_shadows_id' => $productIDShadow, 'status' => 3));
			if (!$getShadow) {
				throw new Exception("Error Processing Request", 1);
			}

			$users_ms_product_shadows_id = $getShadow->id;

			$getListSku = $this->_getProductVariantShadows()->listSku($users_ms_product_shadows_id);
			if (!$getListSku) {
				throw new Exception("Error Processing Request", 1);
			}

			$no = 1;
			foreach ($getListSku as $ky => $val) {

				$generalColor = $val->general_color_id;
				$variantColor = $val->variant_color_id;

				$generalColorData = $this->_getColorNameHexa()->get(array('id' => $generalColor));
				$variantColorData = $this->_getColorNameHexa()->get(array('id' => $variantColor));

				$generalColorName = "";
				if (is_object($generalColorData)) {
					$generalColorName = $generalColorData->color_name;
				}

				$variantColorName = "";
				if (is_object($variantColorData)) {
					$variantColorName = $variantColorData->color_name;
				}

				$variants[] = [
					'No' => $no,
					'ProductID' => $val->users_ms_product_shadows_id,
					'Product' => $val->product_name,
					'SKU' => $val->sku,
					'General Color' => $generalColorName,
					'Variant Color' => $variantColorName,
				];

				$no++;
			}

			return [
				'variant' => $variants,
				'backUrl' => base_url() . "inventory_display/shadow/{$getShadow->users_ms_products_id}"
			];
		} catch (Exception $e) {
			pageError();
		}
	}

	public function showLaunchingShadow($productIDShadow)
	{
		$this->db->select("
			d.users_ms_product_shadows_id as users_ms_products_id,
            a.users_ms_product_shadows_id,
            a.admins_ms_sources_id,
            b.source_name,
            a.users_ms_channels_id,
            c.channel_name,
            IFNULL(a.launch_date,'-') as launch_date,
            a.display_status,
            (select lookup_name from admins_ms_lookup_values where lookup_config = 'inventory_displays' and lookup_code = a.display_status) as status_name
        ", false);

		$this->db->from("{$this->_table_users_ms_inventory_display_shadows} a");
		$this->db->join("{$this->_table_admins_ms_sources} b", "b.id = a.admins_ms_sources_id", "inner");
		$this->db->join("{$this->_table_users_ms_channels} c", "c.id = a.users_ms_channels_id and c.users_ms_companys_id = a.users_ms_companys_id", "inner");
		$this->db->join("{$this->_table_users_ms_product_shadows} d", "d.id = a.users_ms_product_shadows_id", "inner");
		$this->db->where("a.deleted_at is null", null, false);
		$this->db->where(array("d.users_ms_product_shadows_id" => $productIDShadow));
		$this->db->where(array("a.users_ms_companys_id" => $this->_users_ms_companys_id));

		$query = $this->db->get()->result();
		if (!$query) {
			$query = [];
		}
		return $query;
	}

	public function notDefaultShadow($source, $channel, $productID)
	{
		try {

			$getProductShadow = $this->_getProductShadows()->get(array('users_ms_product_shadows_id' => $productID, 'status !=' => 1));
			if (!$getProductShadow) {
				throw new Exception("Failed process launching", 1);
			}

			$getListSku = $this->_getProductVariantShadows()->listSku($getProductShadow->id);
			$no = 1;
			foreach ($getListSku as $ky => $val) {

				$generalColor = $val->general_color_id;
				$variantColor = $val->variant_color_id;

				$generalColorData = $this->_getColorNameHexa()->get(array('id' => $generalColor));
				$variantColorData = $this->_getColorNameHexa()->get(array('id' => $variantColor));

				$generalColorName = "";
				if (is_object($generalColorData)) {
					$generalColorName = $generalColorData->color_name;
				}

				$variantColorName = "";
				if (is_object($variantColorData)) {
					$variantColorName = $variantColorData->color_name;
				}

				$variant[] = [
					'No' => $no,
					'ProductID' => $val->users_ms_product_shadows_id,
					'Product' => $val->product_name,
					'SKU' => $val->sku,
					'General Color' => $generalColorName,
					'Variant Color' => $variantColorName,
				];

				$no++;
			}

			$detail = $this->showImageNotDefaultShadow($source, $channel, $productID);

			if (!$detail) {
				throw new Exception("Error Processing Request", 1);
			}

			$no = 1;

			$button = "<button class=\"btn btn-outline btn-outline-dashed btn-outline-success btn-sm btnSelect me-5\" data-imagename=\"$2\"  data-imageid =\"$1\" data-url=\"" . base_url("inventory_display/confirmimage") . "\" data-type=\"modal\">Select</button>";
			$button .= "<button class=\"btn btn-outline btn-outline-dashed btn-outline-primary btn-sm btnView me-5\" data-imagename=\"$2\"  data-imageid =\"$1\" data-url=\"" . base_url("inventory_display/viewimage") . "\" data-type=\"modal\">View</button>";
			$button .= "<button class=\"btn btn-outline btn-outline-dashed btn-outline-warning btn-sm btnCancel\" data-imagename=\"$2\"  data-imageid =\"$1\" {{notSelected}} >Cancel</button>";

			$urlImage = base_url("assets/uploads/products_image/");

			$dataArrayDefault = [];

			foreach ($detail as $ky => $val) {
				$image_id = $val->id;
				$image = $val->image;
				$imageName = $val->image_name;
				$statusID = $val->status_id;
				$status = "<span class=\"statusName\" data-imageid='{$image_id}'>{$val->status_name}</span>";

				$buttonAction = $button;
				$buttonAction = str_replace("$1", $image_id, $buttonAction);
				$buttonAction = str_replace("$2", $imageName, $buttonAction);
				$conditionStatus = $statusID == 1 ? "disabled" : "";
				$buttonAction = str_replace("{{notSelected}}", $conditionStatus, $buttonAction);

				$htmlImage = "<div class=\"symbol symbol-50px\"><span class=\"symbol-label\" style=\"background-image:url(" . $urlImage . $imageName . ");\"></span></div>";

				$detailImage[] = [
					'No' => $no,
					'Image' => $htmlImage,
					'Image Name' => $imageName,
					'Action' => $buttonAction,
					'Status' => $status,
				];
				$no++;

				$dataArrayDefault[] = [$image_id, (int) $statusID];
			}

			return [
				'variant' => $variant,
				'detail' => $detailImage,
				'dataArrayDefault' => $dataArrayDefault,
			];
		} catch (Exception $e) {
			return [];
		}
	}

	private function showImageNotDefaultShadow($source, $channel, $productID)
	{
		$this->db->select("
            a.id,
            a.image_name as image,
            a.image_name as image_name,
            IFNULL(b.image_status, 1) as status_id,
            (SELECT 
                    lookup_name
                FROM
                    admins_ms_lookup_values
                WHERE
                    lookup_code = IFNULL(b.image_status, 1)
                        AND lookup_config = 'inventory_display_images') AS status_name
        ", FALSE);

		$this->db->from("{$this->_table_products_images} a");
		$this->db->where("a.deleted_at is null", null, false);
		$this->db->where(array("a.users_ms_companys_id" => $this->_users_ms_companys_id));
		$this->db->where(array("c.users_ms_companys_id" => $this->_users_ms_companys_id));
		$this->db->where(array("d.users_ms_product_shadows_id" => $productID));
		$this->db->join("{$this->_table_products} c", "c.id = a.users_ms_products_id", "inner");
		$this->db->join("{$this->_table_users_ms_product_shadows} d", "d.users_ms_products_id = c.id", "inner");

		$this->db->join("{$this->_table_users_ms_inventory_display_detail_shadows} b", "b.users_ms_product_images_id = a.id and b.users_ms_companys_id = a.users_ms_companys_id and b.admins_ms_sources_id = {$source} and b.users_ms_channels_id = {$channel} AND b.users_ms_product_shadows_id = d.id", "left");

		$this->db->order_by("a.id desc");

		$query = $this->db->get()->result();
		return $query;
	}

	public function defaultShadow($id)
	{
		try {

			if ($id == null) {
				throw new Exception("Failed process launching", 1);
			}

			$getProduct = $this->_getProductShadows()->get(array('users_ms_product_shadows_id' => $id, 'status !=' => 1));
			if (!$getProduct) {
				throw new Exception("Failed process launching get product", 1);
			}

			$getListSku = $this->_getProductVariantShadows()->listSku($getProduct->id);
			$no = 1;
			foreach ($getListSku as $ky => $val) {

				$generalColor = $val->general_color_id;
				$variantColor = $val->variant_color_id;

				$generalColorData = $this->_getColorNameHexa()->get(array('id' => $generalColor));
				$variantColorData = $this->_getColorNameHexa()->get(array('id' => $variantColor));

				$generalColorName = "";
				if (is_object($generalColorData)) {
					$generalColorName = $generalColorData->color_name;
				}

				$variantColorName = "";
				if (is_object($variantColorData)) {
					$variantColorName = $variantColorData->color_name;
				}

				$variant[] = [
					'No' => $no,
					'ProductID' => $val->users_ms_product_shadows_id,
					'Product' => $val->product_name,
					'SKU' => $val->sku,
					'General Color' => $generalColorName,
					'Variant Color' => $variantColorName,
				];

				$no++;
			}

			$detail = $this->showImageDefaultShadows($id);
			if (!$detail) {
				throw new Exception("No Image on product", 1);
			}

			$no = 1;

			$button = "<button class=\"btn btn-outline btn-outline-dashed btn-outline-success btn-sm btnSelect me-5\" data-imagename=\"$2\"  data-imageid =\"$1\" data-url=\"" . base_url("inventory_display/confirmimage") . "\" data-type=\"modal\">Select</button>";
			$button .= "<button class=\"btn btn-outline btn-outline-dashed btn-outline-primary btn-sm btnView me-5\" data-imagename=\"$2\"  data-imageid =\"$1\" data-url=\"" . base_url("inventory_display/viewimage") . "\" data-type=\"modal\">View</button>";
			$button .= "<button class=\"btn btn-outline btn-outline-dashed btn-outline-warning btn-sm btnCancel\" data-imagename=\"$2\"  data-imageid =\"$1\" {{notSelected}} >Cancel</button>";

			$urlImage = base_url("assets/uploads/products_image/");

			$dataArrayDefault = [];

			foreach ($detail as $ky => $val) {
				$image_id = $val->id;
				$image = $val->image;
				$imageName = $val->image_name;
				$statusID = $val->status_id;
				$status = "<span class=\"statusName\" data-imageid='{$image_id}'>{$val->status_name}</span>";

				$buttonAction = $button;
				$buttonAction = str_replace("$1", $image_id, $buttonAction);
				$buttonAction = str_replace("$2", $imageName, $buttonAction);
				$conditionStatus = $statusID == 1 ? "disabled" : "";
				$buttonAction = str_replace("{{notSelected}}", $conditionStatus, $buttonAction);

				$htmlImage = "<div class=\"symbol symbol-50px\"><span class=\"symbol-label\" style=\"background-image:url(" . $urlImage . $imageName . ");\"></span></div>";

				$detailImage[] = [
					'No' => $no,
					'Image' => $htmlImage,
					'Image Name' => $imageName,
					'Action' => $buttonAction,
					'Status' => $status,
				];
				$no++;

				$dataArrayDefault[] = [$image_id, (int) $statusID];
			}

			return [
				'variant' => $variant,
				'detail' => $detailImage,
				'dataArrayDefault' => $dataArrayDefault,
			];
		} catch (Exception $e) {
			return [
				'error' => $e->getMessage(),
			];
		}
	}

	private function showImageDefaultShadows($id)
	{
		$this->db->select("
            a.id,
            a.image_name as image,
            a.image_name as image_name,
            IFNULL(b.image_status, 1) as status_id,
            (SELECT 
                    lookup_name
                FROM
                    admins_ms_lookup_values
                WHERE
                    lookup_code = IFNULL(b.image_status, 1)
                        AND lookup_config = 'inventory_display_images') AS status_name
        ", FALSE);

		$this->db->from("{$this->_table_products_images} a");
		$this->db->where("a.deleted_at is null", null, false);
		$this->db->where(array("a.users_ms_companys_id" => $this->_users_ms_companys_id));
		$this->db->where(array("c.users_ms_companys_id" => $this->_users_ms_companys_id));
		$this->db->where(array("d.users_ms_product_shadows_id" => $id));
		$this->db->join("{$this->_table_products} c", "c.id = a.users_ms_products_id", "inner");
		$this->db->join("{$this->_table_users_ms_product_shadows} d", "d.users_ms_products_id = c.id", "inner");

		$this->db->join("{$this->_table_users_ms_inventory_display_default_shadows} b", "b.users_ms_product_images_id = a.id and b.users_ms_companys_id = a.users_ms_companys_id  AND b.users_ms_product_shadows_id = d.id", "left");

		$this->db->order_by("a.id desc");

		$query = $this->db->get()->result();
		return $query;
	}

	public function defaultProcessShadow()
	{
		$this->db->trans_begin();
		$response = array('success' => false, 'messages' => "Successfully setting image default product");
		try {

			$source = clearInput($this->input->post('source'));
			$channel = clearInput($this->input->post('channel'));
			$productID = clearInput($this->input->post('productid'));

			//check source , check channel
			if ($source != "default" || $channel != "default") {
				throw new Exception("failed request data", 1);
			}

			//check productID 
			$get = $this->_getProductShadows()->get(array('users_ms_product_shadows_id' => $productID));
			if (!$get) {
				throw new Exception("Failed request data", 1);
			}

			$users_ms_product_shadows_id = $get->id;

			$images = $this->input->post('images');
			$images = json_decode($images);
			if (!is_array($images) || count($images) < 1) {
				throw new Exception("Failed Processing Requests1", 1);
			}

			$users_ms_products_id = $get->users_ms_products_id;
			$searchMain = false;

			foreach ($images as $ky => $val) {

				$code = $val->value;
				$checkDif = strpos($code, "|");
				if ($checkDif === false) {
					throw new Exception("Failed Processing Requests2", 1);
				}

				$data = explode("|", $code);
				if (!is_array($data) || count($data) != 2) {
					throw new Exception("Failed Processing Requests3", 1);
				}

				$imageID = $data[0];
				$lookup = $data[1];

				if ((int) $lookup == 3) {
					$searchMain = true;
				}

				if ((int) $lookup > 3) {
					throw new Exception("Failed Processing Requests4", 1);
				}

				$get = $this->_validateProductShadow($imageID);
				if (!$get) {
					throw new Exception("Failed Processing Requests5", 1);
				}

				if ($users_ms_products_id != $get->users_ms_products_id) {
					throw new Exception("Failed Processing Requests6", 1);
				}

				$insertOrUpdate = [
					'users_ms_product_shadows_id' => $users_ms_product_shadows_id,
					'users_ms_product_images_id' => $imageID,
				];

				$search = $this->_getInventoryDisplayDefaultShadow()->get($insertOrUpdate);

				$insertOrUpdate['image_status'] = $lookup;

				if (!$search) {
					//insert
					$insert = $this->_getInventoryDisplayDefaultShadow()->insert($insertOrUpdate);
					if (!$insert) {
						throw new Exception("Failed Processing Data", 1);
					}
				} else {
					//update
					$id = $search->id;
					$sync_status = $search->sync_status;
					if ($sync_status == 2) {
						$insertOrUpdate['sync_status'] = 1;
					}
					$update = $this->_getInventoryDisplayDefaultShadow()->update(array('id' => $id), $insertOrUpdate);
					if (!$update) {
						throw new Exception("Failed Processing Data", 1);
					}
				}
			}

			if ($searchMain === false) {
				throw new Exception("Failed request data", 1);
			}

			$this->db->trans_commit();
			$response['success'] = true;
			return $response;
		} catch (Exception $e) {
			$this->db->trans_rollback();
			$response['messages'] = $e->getMessage();
			return $response;
		}
	}

	public function _validateProductShadow($imageID)
	{
		$this->db->select("b.id as users_ms_products_id", false);
		$this->db->from("{$this->_table_products_images} a");
		$this->db->join("{$this->_table_products} b", "b.id = a.users_ms_products_id
            AND b.users_ms_companys_id = a.users_ms_companys_id", "inner");
		$this->db->join("{$this->_table_users_ms_product_shadows} c", "c.users_ms_products_id = b.id", "inner");
		$this->db->where(array("a.id" => $imageID, "a.users_ms_companys_id" => $this->_users_ms_companys_id));

		return $this->db->get()->row();
	}

	public function setDefaultImageShadow()
	{
		$this->db->trans_begin();
		$response = array('success' => false, 'messages' => "Successfully setting image product");
		try {
			$source = clearInput($this->input->post('source'));
			$channel = clearInput($this->input->post('channel'));
			$productID = clearInput($this->input->post('productid'));

			if (is_null($source) || is_null($channel) || is_null($productID)) {
				throw new Exception("Error Processing Request", 1);
			}

			//check source 
			$get = $this->_getSources()->get(array('id' => $source));
			if (!$get) {
				throw new Exception("failed request data", 1);
			}

			//check channel
			$get = $this->_getChannels()->get(array("id" => $channel));
			if (!$get) {
				throw new Exception("Failed request data", 1);
			}

			//check product ID shadow 
			$get = $this->_getProductShadows()->get(array('users_ms_product_shadows_id' => $productID));
			if (!$get) {
				throw new Exception("Failed request data", 1);
			}

			$users_ms_product_shadows_id = $get->id;

			$checkDataDefault = $this->_getInventoryDisplayDefaultShadow()->get(array('users_ms_product_shadows_id' => $users_ms_product_shadows_id, 'image_status' => 3));
			if (!is_object($checkDataDefault)) {
				throw new Exception("Default Image Status  is <i><b>Image Not Selected</b></i>", 1);
			}

			$detail = $this->showImageDefaultShadows($productID);
			if (!$detail) {
				throw new Exception("Failed request data", 1);
			}

			$dataHeader = [
				'admins_ms_sources_id' => $source,
				'users_ms_channels_id' => $channel,
				'users_ms_product_shadows_id' => $users_ms_product_shadows_id,
			];

			//check header 
			$check = $this->_getInventoryDisplayShadows()->get($dataHeader);
			$users_ms_inventory_display_shadows_id = "";
			$statusInsertDetail = false;
			if (!is_object($check)) {
				$dataHeader['display_status'] = 4;
				$dataHeader['display_status_by'] = $this->_user_id;
				$insertHeader = $this->_getInventoryDisplayShadows()->insert($dataHeader);
				if (!$insertHeader) {
					throw new Exception("Failed insert data setting product image", 1);
				}
				$users_ms_inventory_display_shadows_id = $insertHeader;
				$statusInsertDetail = true;
			} else {
				$users_ms_inventory_display_shadows_id = $check->id;

				$getDataDisplayDetailShadow = $this->_getInventoryDisplayDetailShadows()->get_all(['users_ms_inventory_display_shadows_id' => $users_ms_inventory_display_shadows_id, 'users_ms_channels_id' => $channel, 'admins_ms_sources_id' => $source]);
				if (!$getDataDisplayDetailShadow) {
					throw new Exception("Failed set Default Selected Image", 1);
				}

				$update = $this->_getInventoryDisplayDetailShadows()->update(['users_ms_inventory_display_shadows_id' => $users_ms_inventory_display_shadows_id, 'users_ms_channels_id' => $channel, 'admins_ms_sources_id' => $source], ['image_status' => 1]);
				if (!$update) {
					throw new Exception("Failed set Default Selected image status", 1);
				}
			}

			$insertOrUpdate = [
				'users_ms_inventory_display_shadows_id' => $users_ms_inventory_display_shadows_id,
				'admins_ms_sources_id' => $source,
				'users_ms_channels_id' => $channel,
				'users_ms_product_shadows_id' => $users_ms_product_shadows_id,
			];

			if ($statusInsertDetail) {
				foreach ($detail as $ky => $val) {
					$dataInsert = $insertOrUpdate;
					$dataInsert['users_ms_product_images_id'] = $val->id;
					$dataInsert['image_status'] = $val->status_id;
					$dataInsert['sync_status'] = 1;
					$dataInsert['image_status_by'] = $dataInsert['sync_status_by'] = $this->_user_id;
					$insert = $this->_getInventoryDisplayDetailShadows()->insert($dataInsert);
					if (!$insert) {
						throw new Exception("Fail set Default Selected Image", 1);
					}
				}
			} else {
				foreach ($detail as $ky => $val) {
					$dataSearch = $insertOrUpdate;
					$dataSearch['users_ms_product_images_id'] = $val->id;
					$dataUpdate = ['image_status' => $val->status_id, 'image_status_by' => $this->_user_id];

					$search = $this->_getInventoryDisplayDetailShadows()->get($dataSearch);
					if (!$search) {
						$insertData = $dataSearch;
						$insertData['image_status'] = $val->status_id;
						$insertData['image_status_by'] = $this->_user_id;
						$insert = $this->_getInventoryDisplayDetailShadows()->insert($insertData);
						if (!$insert) {
							throw new Exception("Fail set Default Selected Image", 1);
						}
					} else {
						$update = $this->_getInventoryDisplayDetailShadows()->update($dataSearch, $dataUpdate);
						if (!$update) {
							throw new Exception("Fail set Default Selected Image", 1);
						}
					}
				}

				//update header 
				$updateHeader = $this->_getInventoryDisplayShadows()->update(['id' => $users_ms_inventory_display_shadows_id], ['display_status' => 4, 'display_status_by' => $this->_user_id, 'launch_date' => null]);
				if (!$updateHeader) {
					throw new Exception("Fail set Default Selected Image", 1);
				}
			}

			$this->db->trans_commit();
			$response['success'] = true;
			$response['launch'] = true;
			return $response;
		} catch (Exception $e) {
			$this->db->trans_rollback();
			$response['messages'] = $e->getMessage();
			return $response;
		}
	}

	public function notDefaultProcessShadow()
	{
		$this->db->trans_begin();
		$response = array('success' => false, 'messages' => "Successfully setting image product");
		try {
			$source = clearInput($this->input->post('source'));
			$channel = clearInput($this->input->post('channel'));
			$productID = clearInput($this->input->post('productid'));

			//check source 
			$get = $this->_getSources()->get(array('id' => $source));
			if (!$get) {
				throw new Exception("failed request data", 1);
			}

			//check channel
			$get = $this->_getChannels()->get(array("id" => $channel));
			if (!$get) {
				throw new Exception("Failed request data", 1);
			}

			//check product ID shadow 
			$get = $this->_getProductShadows()->get(array('users_ms_product_shadows_id' => $productID));
			if (!$get) {
				throw new Exception("Failed request data", 1);
			}

			$users_ms_product_shadows_id = $get->id;

			$images = $this->input->post('images');
			$images = json_decode($images);
			if (!is_array($images) || count($images) < 1) {
				throw new Exception("Failed Processing Requests", 1);
			}

			$users_ms_products_id = $get->users_ms_products_id;
			$searchMain = false;

			$dataHeader = [
				'admins_ms_sources_id' => $source,
				'users_ms_channels_id' => $channel,
				'users_ms_product_shadows_id' => $users_ms_product_shadows_id,
			];

			//check header 

			$check = $this->_getInventoryDisplayShadows()->get($dataHeader);
			$users_ms_inventory_displays_id = "";
			if (!is_object($check)) {
				$dataHeader['display_status_by'] = $this->_user_id;
				$dataHeader['display_status'] = 4;
				$insertHeader = $this->_getInventoryDisplayShadows()->insert($dataHeader);
				if (!$insertHeader) {
					throw new Exception("Failed insert data setting product image", 1);
				}
				$users_ms_inventory_display_shadows_id = $insertHeader;
				$response['launch'] = true;
			} else {
				$users_ms_inventory_display_shadows_id = $check->id;

				$updateHeader = $this->_getInventoryDisplayShadows()->update(array('id' => $users_ms_inventory_displays_id), $dataHeader);
				if (!$updateHeader) {
					throw new Exception("Failed Processing Request", 1);
				}
			}

			$updateHeaderToPending = false;

			foreach ($images as $ky => $val) {

				$code = $val->value;
				$checkDif = strpos($code, "|");
				if ($checkDif === false) {
					throw new Exception("Failed Processing Requests", 1);
				}

				$data = explode("|", $code);
				if (!is_array($data) || count($data) != 2) {
					throw new Exception("Failed Processing Requests", 1);
				}

				$imageID = $data[0];
				$lookup = $data[1];

				if ((int) $lookup === 3) {
					$searchMain = true;
				}

				if ((int) $lookup > 3) {
					throw new Exception("Failed Processing Request", 1);
				}

				$get = $this->_validateProductShadow($imageID);
				if (!$get) {
					throw new Exception("Failed Processing Requests", 1);
				}

				if ($users_ms_products_id != $get->users_ms_products_id) {
					throw new Exception("Failed Processing Requests", 1);
				}

				$insertOrUpdate = [
					'users_ms_inventory_display_shadows_id' => $users_ms_inventory_display_shadows_id,
					'admins_ms_sources_id' => $source,
					'users_ms_channels_id' => $channel,
					'users_ms_product_shadows_id' => $users_ms_product_shadows_id,
					'users_ms_product_images_id' => $imageID,
				];

				$search = $this->_getInventoryDisplayDetailShadows()->get($insertOrUpdate);

				$insertOrUpdate['image_status'] = $lookup;

				if (!$search) {
					//insert
					$insertOrUpdate['image_status_by'] = $this->_user_id;
					$insertOrUpdate['sync_status_by'] = $this->_user_id;
					$insert = $this->_getInventoryDisplayDetailShadows()->insert($insertOrUpdate);
					if (!$insert) {
						throw new Exception("Failed Processing Data", 1);
					}
				} else {
					//update
					$id = $search->id;
					if ($search->image_status != $lookup) {
						$insertOrUpdate['image_status_by'] = $this->_user_id;
						$sync_status = $search->sync_status;
						if ($sync_status == 2) {
							$insertOrUpdate['sync_status'] = 1;
							$insertOrUpdate['sync_status_by'] = $this->_user_id;
						}

						//update header menjadi pending 
						$updateHeaderToPending = true;
					}

					$update = $this->_getInventoryDisplayDetailShadows()->update(array('id' => $id), $insertOrUpdate);
					if (!$update) {
						throw new Exception("Failed Processing Data", 1);
					}
				}
			}

			if ($searchMain === false) {
				throw new Exception("Failed request data", 1);
			}

			if ($updateHeaderToPending) {
				$updateHeader = $this->_getInventoryDisplayShadows()->update(array('id' => $users_ms_inventory_display_shadows_id), ['display_status' => 4, 'display_status_by' => $this->_user_id, 'launch_date' => null]);
				if (!$updateHeader) {
					throw new Exception("Error Processing Request", 1);
				}
				$response['launch'] = true;

				//check status product di inventory 
				//$check = $this->_getInventoryDisplayNotDefault()->get(array('users_ms_products_id' => $productID,'display_status >' => 4));
				//update product to incoming 
				// if(!is_object($check)){
				//     $updateStatusProduct = [
				//         'status' => 3,
				//     ];

				//     $updateProduct = $this->_getProducts()->update(array('id' => $productID),$updateStatusProduct);
				//     if(!$updateProduct){
				//         throw new Exception("Failed Processing Request", 1);

				//     }
				// }
			}

			$this->db->trans_commit();
			$response['success'] = true;
			return $response;
		} catch (Exception $e) {
			$this->db->trans_rollback();
			$response['messages'] = $e->getMessage();
			return $response;
		}
	}

	public function launchProductSourceShadow()
	{
		$response = ['success' => true, 'messages' => 'Successfully launch product source'];
		$this->db->trans_begin();
		try {
			$source = clearInput($this->input->post('source'));
			$channel = clearInput($this->input->post('channel'));
			$productID = clearInput($this->input->post('productid'));
			$launchDate = $this->input->post('launchdate');

			if (is_null($launchDate) || $launchDate == "") {
				throw new Exception("Failed Processing Request", 1);
			}

			if (is_null($source) || is_null($channel) || is_null($productID)) {
				throw new Exception("Failed Processing Request", 1);
			}

			//check source 
			$get = $this->_getSources()->get(array('id' => $source));
			if (!$get) {
				throw new Exception("failed request data", 1);
			}

			//check channel
			$get = $this->_getChannels()->get(array("id" => $channel));
			if (!$get) {
				throw new Exception("Failed request data", 1);
			}

			//check productID
			$get = $this->_getProductShadows()->get(array('users_ms_product_shadows_id' => $productID));
			if (!$get) {
				throw new Exception("Failed request data", 1);
			}

			$users_ms_product_shadows_id = $get->id;


			$arrCheck = [
				'admins_ms_sources_id' => $source,
				'users_ms_channels_id' => $channel,
				'users_ms_product_shadows_id' => $users_ms_product_shadows_id,
			];

			$check = $this->_getInventoryDisplayShadows()->get($arrCheck);
			$users_ms_inventory_display_shadows_id = "";

			if (!is_object($check)) {
				//saving data from default display
				$get = $this->_getInventoryDisplayDefaultShadow()->get_all(array('users_ms_product_shadows_id' => $users_ms_product_shadows_id));
				if (!$get) {
					throw new Exception("Failed Processing Request", 1);
				}

				$dataHeader = [
					'admins_ms_sources_id' => $source,
					'users_ms_channels_id' => $channel,
					'users_ms_product_shadows_id' => $users_ms_product_shadows_id,
					'display_status_by' => $this->_user_id,
					'display_status' => 4,
				];

				//save header 
				$saveHeader = $this->_getInventoryDisplayShadows()->insert($dataHeader);
				if (!$saveHeader) {
					throw new Exception("Failed Processing Request", 1);
				}

				$users_ms_inventory_display_shadows_id = $saveHeader;

				foreach ($get as $ky => $val) {
					$dataDetail = [
						'users_ms_inventory_display_shadows_id' => $users_ms_inventory_display_shadows_id,
						'admins_ms_sources_id' => $source,
						'users_ms_channels_id' => $channel,
						'users_ms_product_shadows_id' => $users_ms_product_shadows_id,
						'users_ms_product_images_id' => $val->users_ms_product_images_id,
						'image_status_by' => $this->_user_id,
						'image_status' => $val->image_status,
						'sync_status_by' => $this->_user_id,
					];

					$save = $this->_getInventoryDisplayDetailShadows()->insert($dataDetail);
					if (!$save) {
						throw new Exception("Failed Processing Request", 1);
					}
				}
			} else {
				$users_ms_inventory_display_shadows_id = $check->id;
			}

			//update header inventory display 
			$updateHeader = [
				'display_status_by' => $this->_user_id,
				'display_status' => 5,
				//pending
				'launch_by' => $this->_user_id,
				'launch_date' => $launchDate,
			];

			$updateHeader = $this->_getInventoryDisplayShadows()->update(array('id' => $users_ms_inventory_display_shadows_id), $updateHeader);
			if (!$updateHeader) {
				throw new Exception("Failed Processing Request", 1);
			}

			$this->db->trans_commit();
			return $response;
		} catch (Exception $e) {
			$this->db->trans_rollback();
			$response['success'] = false;
			$response['messages'] = $e->getMessage();
			return $response;
		}
	}
}
