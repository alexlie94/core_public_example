<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Product_publish extends MY_Customers
{

	public function __construct()
	{
		$this->_function_except = [
			'show_display', 'publish_marketplace',
			'get_category_api', 'get_brand_api', 'process', 'process_single', 'get_view_form', 'get_category_api_single'
		];

		$this->load->helper('api');
		parent::__construct();
	}

	public function index()
	{
		$this->template->title('Product Publish');
		$this->setTitlePage('Product Publish');
		$this->assetsBuild(['datatables']);
		$this->setJs('product_publish');

		$data = [];

		$this->template->build('v_form', $data);
	}

	public function process()
	{
		isAjaxRequestWithPost();
		$this->function_access('insert');

		$response = $this->product_publish_model->save();
		echo json_encode($response);
		exit();
	}

	public function process_single()
	{
		isAjaxRequestWithPost();
		$this->function_access('insert');

		$response = $this->product_publish_model->save_single();
		echo json_encode($response);
		exit();
	}

	public function show_display()
	{
		isAjaxRequestWithPost();

		$this->product_publish_model->manageDataDisplay();
	}

	public function publish_marketplace()
	{
		$this->template->title('Publish Marketplace');
		$this->setTitlePage('Publish Marketplace');
		$this->assetsBuild(['datatables']);

		$params =  json_decode(base64_decode($_GET['params']));

		switch ($params->form_type) {
			case 'single':
				$this->setJs('publish_marketplace_single');
				$this->form_single($params);
				break;
			case 'multiple':
				$this->setJs('publish_marketplace_multiple');
				$this->form_multiple($params);
				break;
		}
	}

	private function form_single($params)
	{
		$get_source = $params->source;
		$get_channel = $params->channel;
		$get_product_id = $params->product_id;

		$get_data = $this->product_publish_model->data_show_display_single($get_source, $get_channel, $get_product_id)->row();

		$product_data = json_encode($get_data);
		$data_convert = json_decode($product_data, true);

		$get_data_variant = $this->product_publish_model->getDataProductVariants($get_product_id)->result();

		$get_variant_color =  $this->product_publish_model->getDataVariantsColor($get_product_id)->result();

		$image_arr = [];
		$image_display = $this->product_publish_model->showImageNotDefault($get_product_id)->result();
		foreach ($image_display as $value) {
			$get_source_name = $value->source_name;
			$toLowerConvert = strtolower($get_source_name);
			$source_name = str_replace(' ', '_', $toLowerConvert);
			$channel_name = $value->channel_name;
			$image_name = $value->image_name;
			$status_image = $value->status_name;

			if ($source_name === 0) {
				continue;
			}

			if (isset($image_arr[$source_name])) {
				if (array_key_exists($channel_name, $image_arr[$source_name])) {
					$image_arr[$source_name][$channel_name][] = $image_name . ':' . $status_image;
				} else {
					$image_arr[$source_name][$channel_name] = [$image_name . ':' . $status_image];
				}
			} else {
				$image_arr[$source_name] = [$channel_name => [$image_name . ':' . $status_image]];
			}
		}

		$data =
			[
				'product_id' => $get_product_id,
				'product' => $data_convert,
				'sources_id' => $get_source,
				'sources' => $get_data->source_name,
				'channels_id' => $get_channel,
				'channels' => $get_data->channel_name,
				'image' => check_image_file('./assets/uploads/products_image/', $get_data->image_name),
				'product_variants' => $get_data_variant,
				'image_arr' => $image_arr,
				'variants' => $get_variant_color,
				'url_form' => base_url() . "product_publish/process_single",
			];

		$this->template->build('v_publish_single', $data);
	}

	private function form_multiple($params)
	{
		$get_product_id 	= $params->product_id;
		$get_data 			= $this->product_publish_model->data_show_display($get_product_id)->row();
		// $get_data_variant 	= $this->product_publish_model->getDataProductVariants()->get_all(['users_ms_products_id'=>$get_product_id]);
		$product_data 		= json_encode($get_data);
		$data 				= json_decode($product_data, true);

		$split_coma = explode(',', $get_data->source_per_channel);
		$sources = [];
		$channels = [];
		$data_channel = [];
		foreach ($split_coma as $key) {
			$part = explode('||', $key);

			if (count($part) == 2) {
				$keys = $part[0];
				$value = $part[1];

				if (array_key_exists($keys, $data_channel)) {
					$data_channel[$keys][] = $value;
				} else {
					$data_channel[$keys] = [$value];
				}
			}

			array_push($sources, $part[0]);
			array_push($channels, $part[1]);
		}

		$data =
			[
				'product' => $data,
				'sources' => array_unique($sources),
				'channels' => $channels,
				'channel_cek' => $data_channel,
				'image' => check_image_file('./assets/uploads/products_image/', $get_data->image_name),
				'product_variants' => [],
				'url_form' => base_url() . "product_publish/process",
			];

		$this->template->build('v_publish_multiple', $data);
	}

	public function get_category_api()
	{
		isAjaxRequestWithPost();

		$category = get_master_marketplace('category', 2);
		$shipping = get_master_marketplace('shipping', 2);

		$output = array(
			'category' 	=> $category,
			'shipping'  => $shipping
		);

		echo json_encode($output);
	}

	public function get_category_api_single()
	{
		isAjaxRequestWithPost();
		$source = $_POST['sources_id'];
		$channel = $_POST['channels_id'];

		$category = get_master_marketplace('category', $source, $channel);
		$shipping = get_master_marketplace('shipping', $source, $channel);

		$output = array(
			'category' 	=> $category,
			'shipping'  => $shipping
		);

		echo json_encode($output);
	}

	public function get_brand_api()
	{
		isAjaxRequestWithPost();

		$response = $this->product_publish_model->manageBrandListApi();

		echo json_encode($response);
		exit();
	}

	public function get_view_form()
	{
		$source = $_POST['source'];
		switch ($source) {
			case 'Shopee':
				$this->load->model('publish_marketplace_model', 'marketplace');
				$data['channel'] 		= $this->marketplace->get_channel($source)['list_channel'];
				$data['images'] 		= $this->marketplace->get_channel($source)['list_img'];
				$data['product'] 		= $this->marketplace->get_channel($source)['list_product'];
				$data['url_form'] 		= base_url() . "product_publish/process_multiple";
				$v_shopee 				= $this->load->view('v_shopee', $data, true);
				$output = array(
					"v_marketplace"		=> $v_shopee,
					"error" 		=> false
				);
				echo json_encode($output);
				break;
			case 'Tokopedia':
				$this->load->model('publish_marketplace_model', 'marketplace');
				$data['channel'] 	= $this->marketplace->get_channel($source)['list_channel'];
				$v_shopee 			= $this->load->view('v_tokopedia', $data, true);
				$output = array(
					"v_marketplace"	=> $v_shopee,
					"error" 		=> false
				);
				echo json_encode($output);
				break;
		}
	}
}
