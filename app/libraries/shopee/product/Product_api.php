<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Product_api
{

	protected $ci;
	protected $process;
	protected $db;
	protected $queue;
	protected $_users_ms_companys_id;
	protected $_created_by;


	public function __construct()
	{
		$this->ci = &get_instance();

		$load_library = [
			'queue',
			'shopee/product/product_model_api',
		];

		$this->ci->load->library($load_library);

		$this->ci->load->helper('api');
		$this->model = $this->ci->product_model_api;
		$this->db = $this->ci->db;
		$this->queue = $this->ci->queue;

		date_default_timezone_set('UTC');
	}

	private function config_api($channel_id)
	{

		$result = $this->model->check_all_data_by_channel_id($channel_id);

		if ($result) {
			if ($result->shop_id == '' || $result->shop_id == null) {
				return false;
			} else {

				$config =
					[
						'partner_id' => intVal($result->app_keys),
						'secret_key' => $result->secret_keys,
						'shop_id' => $result->shop_id,
						'access_token' => $result->access_token,
						'host' => $result->source_url,
						'timestamp' => time()
					];

				return $config;
			}
		} else {
			return false;
		}
	}

	private function build_multipart_data($boundary, $image_name)
	{
		$data = '';

		$imageData   = file_get_contents('../assets/uploads/products_image/' . $image_name);

		$data       .= "--$boundary\r\n";
		$data       .= 'Content-Disposition: form-data; name="image"; filename="' . $image_name . '"' . "\r\n";
		$data       .= 'Content-Type: image/png' . "\r\n\r\n";
		$data       .= $imageData . "\r\n";
		$data       .= "--$boundary--\r\n";

		return $data;
	}

	private function build_image_id($channel, $image_name)
	{
		$config = $this->config_api($channel);

		$config['path'] = '/api/v2/media_space/upload_image';

		$string     = $config['partner_id'] . $config['path'] . $config['timestamp'] . $config['access_token'] .  $config['shop_id'];
		$sign       = hash_hmac('sha256', $string, $config['secret_key']);

		$boundary = uniqid();

		$fields_array = array(
			'image' => $this->build_multipart_data($boundary, $image_name)
		);

		$rawbody = implode("\r\n", $fields_array);

		$param = array(
			"timestamp"      => $config['timestamp'],
			"access_token"   => $config['access_token'],
			"partner_id"     => $config['partner_id'],
			"shop_id"        => $config['shop_id'],
			"sign"           => $sign
		);

		$url     = create_url($config['host'], $config['path'], $param);
		$data    = post_image_request_url($boundary, $url, $rawbody);

		if ($data->error === 'error_auth') {
			return $data->message;
		} else {
			return $data->response->image_info->image_id;;
		}
	}

	public function post_product($all_data)
	{

		$set_channel_id = $all_data->users_ms_channels_id;

		$config = $this->config_api($set_channel_id);

		$config['path'] = '/api/v2/product/add_item';

		$string     = $config['partner_id'] . $config['path'] . $config['timestamp'] . $config['access_token'] .  $config['shop_id'];
		$sign       = hash_hmac('sha256', $string, $config['secret_key']);

		$image_list = [];
		$getImageName = $all_data->image_list;

		foreach ($getImageName as $file) {
			array_push($image_list, $this->build_image_id($set_channel_id, $file[0]));
		}

		$shipping_list = [];
		$get_shipping_list = $all_data->shipping_list;
		foreach ($get_shipping_list as $shipping) {

			$row = [
				'enabled'       => true,
				'is_free'       => false,
				'logistic_id'   => intVal($shipping->id)
			];

			$shipping_list[] = $row;
		}

		$fields_array = array(
			'description'   => $all_data->description,
			'item_name'     => $all_data->products_name,
			'category_id'   => intVal($all_data->category_id),
			"brand" =>  array(
				"brand_id" => intVal($all_data->brand_id),
				"original_brand_name" => $all_data->brand_name
			),
			'item_status' => 'NORMAL',
			'image' => array(
				'image_id_list' => $image_list
			),
			'weight' => floatVal($all_data->weight),
			'dimension' => array(
				"package_height" => floatVal($all_data->height),
				"package_length" => floatVal($all_data->length),
				"package_width" => floatVal($all_data->width)
			),
			'condition' => $all_data->condition,
			'logistic_info' => $shipping_list,
			"original_price" => 75000,
			"seller_stock" => array(array(
				"stock" =>  10
			))
		);

		$rawbody = json_encode($fields_array);

		$param = array(
			"timestamp"      => $config['timestamp'],
			"access_token"   => $config['access_token'],
			"partner_id"     => $config['partner_id'],
			"shop_id"        => $config['shop_id'],
			"sign"           => $sign
		);

		$url     = create_url($config['host'], $config['path'], $param);
		$data    = post_request_curl($url, $rawbody);

		if ($data->error === 'error_auth') {
			return $data->message;
		} elseif (!empty($data->error)) {
			return $data->message;
		} else {
			return $data->response;
		}
	}

	private function searchKey($searchValue, $arrayList)
	{
		$foundKey = '';

		foreach ($arrayList as $key => $subArray) {
			if (in_array($searchValue, $subArray)) {
				$foundKey = $key;
				break;
			}
		}

		return $foundKey;
	}

	public function post_product_variant($item_id, $all_data)
	{
		$set_channel_id = $all_data->users_ms_channels_id;

		$config = $this->config_api($set_channel_id);

		$config['path'] = '/api/v2/product/init_tier_variation';

		$string     = $config['partner_id'] . $config['path'] . $config['timestamp'] . $config['access_token'] .  $config['shop_id'];
		$sign       = hash_hmac('sha256', $string, $config['secret_key']);

		$color_list = [];
		$size_list = [];
		$sku_list = [];

		$get_color = $all_data->color_list;
		$get_sku_list = $all_data->sku_list;

		foreach ($get_color as $value) {
			$row = [
				"image" => [
					"image_id" => $this->build_image_id($set_channel_id, $value->image)
				],
				"option" => $value->color,
			];

			$color_list[] = $row;
		}

		foreach ($get_sku_list as $sku) {
			$row_size = [
				"option" => $sku->size,
			];

			$optionExists = false;
			foreach ($size_list as $size) {
				if ($size['option'] === $row_size['option']) {
					$optionExists = true;
					break;
				}
			}

			if (!$optionExists) {
				$size_list[] = $row_size;
			}
		}

		foreach ($get_sku_list as $sku) {

			$price = str_replace('.', '', $sku->price);
			$color = $this->searchKey($sku->color, $color_list);
			$size = $this->searchKey($sku->size, $size_list);

			$row = [
				"tier_index"        => [intVal($color), intVal($size)],
				"model_sku"         => $sku->sku,
				"normal_stock"      => intVal($sku->sku),
				"original_price"    => 1000,
				// "original_price"    => floatVal($price),
				"seller_stock"      => array(
					array(
						"stock" =>  2
					)
				)
			];

			$sku_list[] = $row;
		}

		$fields_array = array(
			"item_id"   => intVal($item_id),
			"tier_variation"    =>
			[
				[
					"name"          => "warna",
					"option_list"   => $color_list
				],
				[
					"name"          => "ukuran",
					"option_list"   => $size_list
				]
			],
			"model"     => $sku_list
		);

		$rawbody = json_encode($fields_array);

		$param = array(
			"timestamp"      => $config['timestamp'],
			"access_token"   => $config['access_token'],
			"partner_id"     => $config['partner_id'],
			"shop_id"        => $config['shop_id'],
			"sign"           => $sign
		);

		$url     = create_url($config['host'], $config['path'], $param);
		$data    = post_request_curl($url, $rawbody);

		if ($data->error === 'error_auth') {
			return $data->message;
		} elseif (!empty($data->error)) {
			return $data->message;
		} else {
			return $data->response;
		}
	}
}
