<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Publish_test extends MY_Cron
{
	protected $_model;
	public function __construct()
	{
		parent::__construct();

		$this->load->model('Publish_test_model');
		$this->load->library('shopee/product/Product_push');
		// $this->_model = $this->Shopee_model;
	}

	public function index()
	{
		echo 'hy';
	}

	public function get_product()
	{
		$data_webhook = '{"users_ms_product_publishes_id":15,"users_ms_products_id":"383","products_name":"Kutang Batman","admins_ms_sources_id":"2","users_ms_channels_id":"13","category_id":"100251","category_name":"Beauty &gt; Beauty Sets &amp; Packages","brand_id":"1014129","brand_name":"AA Glowskin","shipping_list":[{"id":"8005","name":"Hemat"},{"id":"8003","name":"Reguler"}],"description":"sacsdcdcsdc hahahaha shdshdsdsd sdasdsdsad asdhashdasdash sdasdhasdsd","condition":"new","sku_list":[{"sku":"WAAAPAFFM0-Q0","qty":"0","price":"123","size":"M","color":"Tomato"},{"sku":"WAAASH30DD-38","qty":"0","price":"123","size":"XL","color":"Dark Orange"},{"sku":"WAKUPA80M0-D7","qty":"0","price":"123","size":"M","color":"Olive"},{"sku":"WAKUPA80S0-7B","qty":"0","price":"123","size":"S","color":"Olive"},{"sku":"WAKUPAFFS0-ZN","qty":"0","price":"123","size":"S","color":"Tomato"}],"color_list":[{"color":"Tomato","image":"383_kutang_batman_OZAU.png"},{"color":"Dark Orange","image":"383_kutang_batman_SQXU.jpeg"},{"color":"Olive","image":"383_kutang_batman_ZAIA.jpeg"}],"image_list":[["383_kutang_batman_3NHM.png"],["383_kutang_batman_OZAU.png"],["383_kutang_batman_SQXU.jpeg"]],"weight":"12","length":"11","width":"11","height":"11"}';

		$decode_data_webhook = json_decode($data_webhook);

		$process = $this->product_push->product_push($decode_data_webhook);

		if ($process) {
			echo "Success Insert Bro,Selamat!!!!";
			print_r($process);
		} else {
			echo "Gagal Kenapa Ya??? =";
			print_r($process);
		}
	}
}
