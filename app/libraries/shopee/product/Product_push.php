<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Product_push
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
			'shopee/product/product_api',
			'shopee/product/product_model_api'
		];

		$this->ci->load->library($load_library);

		$this->ci->load->helper('api');
		$this->api = $this->ci->product_api;
		$this->model_proccess = $this->ci->product_model_api;

		date_default_timezone_set('UTC');
	}


	public function product_push($data_array)
	{
		try {
			$get_data = $data_array;

			pre($get_data);

			// $add_product_variant = $this->api->post_product_variant('',$get_data);
			// pre($add_product_variant);

			$users_ms_product_publishes_id = $get_data->users_ms_product_publishes_id;

			$add_product = $this->api->post_product($get_data);

			if (isset($add_product->item_id)) {
				$this->model_proccess->save_product($users_ms_product_publishes_id, $add_product);

				$add_product_variant = $this->api->post_product_variant($add_product->item_id, $get_data);

				if (isset($add_product_variant->item_id)) {
					$this->model_proccess->save_product_variant($add_product_variant);
				} else {
					return $add_product_variant;
				}
			} else {
				return $add_product;
			}

			return true;
		} catch (Exception $e) {
			return $e->getMessage();
		}
	}
}
