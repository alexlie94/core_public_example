<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Shopee extends MY_Cron
{
	protected $source = 2; //shopee
	public function __construct()
	{
		parent::__construct();

		$this->load->library(array('shopee/orders', 'integrations/auth'));
		$this->load->library(array('shopee/product/product_push', 'product_push'));
	}

	public function index()
	{
		print_r($this->source);
	}

	public function refresh_token()
	{
		pre($this->auth->auth_process_expiry_by_source_id($this->source));
	}

	public function orders($channel_id = null)
	{
		if ($channel_id == null) {
			pre('Channel ID is required');
		}
		$process = $this->orders->get_order_list($channel_id);
		pre($process);
	}

	// public function get_address_list()
	// {
	// 	$process = $this->orders->get_address_list();
	// 	pre($process);
	// }

	public function order_test_alex()
	{

		$data_webhook = '{"users_ms_product_publishes_id":17,"users_ms_products_id":"383","products_name":"Kutang Batman","admins_ms_sources_id":"2","users_ms_channels_id":"13","category_id":"100419","category_name":"Women Clothes &gt; Socks &amp; Stockings &gt; Others","brand_id":"1014019","brand_name":"A Forever Fairness","shipping_list":[{"id":"8005","name":"Hemat"},{"id":"8003","name":"Reguler"}],"description":"alex alex alex alex alex alex alex alex alex alex alex alex","condition":"new","sku_list":[{"sku":"WAAAPAFFM0-Q0","qty":"0","price":"","size":"M","color":"Tomato"},{"sku":"WAAASH30DD-38","qty":"0","price":"","size":"XL","color":"Dark Orange"},{"sku":"WAKUPA80M0-D7","qty":"0","price":"","size":"M","color":"Olive"},{"sku":"WAKUPA80S0-7B","qty":"0","price":"","size":"S","color":"Olive"},{"sku":"WAKUPAFFS0-ZN","qty":"0","price":"","size":"S","color":"Tomato"}],"color_list":[{"color":"Tomato","image":"383_kutang_batman_OZAU.png"},{"color":"Dark Orange","image":"383_kutang_batman_SQXU.jpeg"},{"color":"Olive","image":"383_kutang_batman_ZAIA.jpeg"}],"image_list":[["383_kutang_batman_3NHM.png"],["383_kutang_batman_OZAU.png"],["383_kutang_batman_SQXU.jpeg"]],"weight":"23","length":"44","width":"44","height":"44"}';

		$decode_data_webhook = json_decode($data_webhook);

		$process = $this->product_push->product_push($decode_data_webhook);

		if ($process) {
			echo "Success =";
			print_r($process);
		} else {
			echo "Gagal     =";
			print_r($process);
		}
	}

	public function order_test()
	{

		$data_webhook = '{"users_ms_product_publishes_id":17,"users_ms_products_id":"383","products_name":"Kutang Batman","admins_ms_sources_id":"2","users_ms_channels_id":"13","category_id":"100419","category_name":"Women Clothes &gt; Socks &amp; Stockings &gt; Others","brand_id":"1014019","brand_name":"A Forever Fairness","shipping_list":[{"id":"8005","name":"Hemat"},{"id":"8003","name":"Reguler"}],"description":"alex alex alex alex alex alex alex alex alex alex alex alex","condition":"new","sku_list":[{"sku":"WAAAPAFFM0-Q0","qty":"0","price":"","size":"M","color":"Tomato"},{"sku":"WAAASH30DD-38","qty":"0","price":"","size":"XL","color":"Dark Orange"},{"sku":"WAKUPA80M0-D7","qty":"0","price":"","size":"M","color":"Olive"},{"sku":"WAKUPA80S0-7B","qty":"0","price":"","size":"S","color":"Olive"},{"sku":"WAKUPAFFS0-ZN","qty":"0","price":"","size":"S","color":"Tomato"}],"color_list":[{"color":"Tomato","image":"383_kutang_batman_OZAU.png"},{"color":"Dark Orange","image":"383_kutang_batman_SQXU.jpeg"},{"color":"Olive","image":"383_kutang_batman_ZAIA.jpeg"}],"image_list":[["383_kutang_batman_3NHM.png"],["383_kutang_batman_OZAU.png"],["383_kutang_batman_SQXU.jpeg"]],"weight":"23","length":"44","width":"44","height":"44"}';

		$decode_data_webhook = json_decode($data_webhook);

		$process = $this->orders->process_order($decode_data_webhook);

		if ($process) {
			echo "Success =";
			print_r($process);
		} else {
			echo "Gagal     =";
			print_r($process);
		}
	}


	public function get_shipping_document_status_test()
	{
		$data_webhook = '{"data": {
							"ordersn": "2311071NACRK3X",
							"package_number": "OFG153057256215806",
							"status": "READY"
						},
						"shop_id": 81219906,
						"code": 15,
						"timestamp": 1699457425
						}';

		$decode_data_webhook = json_decode($data_webhook);

		$process = $this->orders->get_shipping_document_result_webhook($decode_data_webhook);

		if ($process) {
			echo "Success =";
			print_r($process);
		} else {
			echo "Gagal     =";
			print_r($process);
		}
	}

	public function get_escrow_detail()
	{
		$local_order_id = '23110712DMSVR9';

		$process = $this->orders->get_escrow_detail_by_local_order_id($local_order_id);

		pre($process);
	}
}