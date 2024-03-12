<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Tokopedia extends MY_Cron
{
	protected $source = 3; //tokopedia
	public function __construct()
	{
		parent::__construct();

		$this->load->library(array('integrations/auth', 'tokopedia/orders'));
	}

	public function index()
	{
		print_r($this->source);
	}

	public function access_token()
	{
		pre($this->auth->auth_process_tokopedia($this->source));
	}

	public function orders($channel_id = null, $from_date = null, $to_date = null)
	{
		if ($channel_id == null) {
			pre('Channel ID is required');
		}
		if ($from_date == null && $to_date == null) {
			$to_date = strtotime(date('Y-m-d H:i:s'));
			$from_date = strtotime('-2 days -12 hours', $to_date);
		}

		if ($from_date !== null && $to_date !== null) {

			$time_difference = abs($to_date - $from_date);

			if ($time_difference > 259200) {
				pre('Error: The difference between from_date and to_date is more than 3 days.');
			}
		}

		$process = $this->orders->get_order_list($channel_id, $from_date, $to_date);
		pre($process);
	}


	public function new_order_test1()
	{
		$data_order = '{
			"fs_id":18400,
			"order_id":1697164674,
			"invoice_ref_num":"INV/20231030/MPL/3537822418",
			"products":[
			   {
				  "id":3453374488,
				  "name":"Berrybenka - Sandal Gunung Wanita Sully Strappy Eva Sandal - Navy, 36",
				  "notes":"",
				  "currency":"Rp.",
				  "weight":0.3,
				  "total_weight":0.3,
				  "price":65000,
				  "total_price":65000,
				  "quantity":1,
				  "sku":"BESOSHBL36-ZL",
				  "addon_summary":{
					 "addons":null,
					 "total":0,
					 "total_price":0,
					 "total_price_str":"",
					 "total_quantity":0
				  },
				  "is_eligible_pof":false
			   }
			],
			"customer":{
			   "id":217531938,
			   "name":"",
			   "phone":"",
			   "email":"",
			   "user_status":0
			},
			"recipient":{
			   "name":"",
			   "phone":"",
			   "address":{
				  "address_full":"",
				  "district":"Tangerang",
				  "city":"Kota Tangerang",
				  "province":"Banten",
				  "country":"Indonesia",
				  "postal_code":"15117",
				  "district_id":1640,
				  "city_id":146,
				  "province_id":11,
				  "geo":"-6.210851494721516,106.63085795938969"
			   }
			},
			"shop_id":5537315,
			"warehouse_id":27627,
			"shop_name":"Berrybenka",
			"payment_id":2431711765,
			"payment_date":"2023-10-30T21:24:22Z",
			"logistics":{
			   "shipping_id":1,
			   "sp_id":1,
			   "district_id":1640,
			   "city_id":146,
			   "province_id":11,
			   "geo":"-6.210851494721516,106.63085795938969",
			   "shipping_agency":"JNE",
			   "service_type":"Reguler"
			},
			"amt":{
			   "ttl_product_price":65000,
			   "shipping_cost":10000,
			   "insurance_cost":500,
			   "ttl_amount":75500,
			   "voucher_amount":0,
			   "toppoints_amount":0
			},
			"dropshipper_info":{
			   "name":"",
			   "phone":""
			},
			"voucher_info":{
			   "voucher_code":"DDN50WA3LIGPU7BXXH8V",
			   "voucher_type":0,
			   "voucher_amount":0
			},
			"device_type":"ios",
			"create_time":1698701023,
			"order_status":220,
			"custom_fields":{
			   
			},
			"accept_partial":false,
			"encryption":{
			   "secret":"kgpmA8FtgBZbtaTyn+cSp6+VpkfeRlfcErMKbOiA5Z034RlGSgSS7ZlKhVMFRccjtHR36VNQ4uEAGlomzkFMYFYB3H+uKQFh09QsJsDuzDkwFyNK1fH0TXYSokXrIImZAouYw3swmnk3hzz/PGjiSiymFnzESd6D9I2On9EtBAxMtsN9FP1ttjs9QTEsYoyU7dhF/8ioN/q8s4BTpCBzCmfgYTfcyDjU5tG1v3kM546RB8Qot6gq7VHY/rcU7yCRrLEBNZf4sJ8iwa2e5i19Ts1queGMLnIcomVcVuLRqIksmOhE/9SuMnImtYm8Be7nTgWKsQjrYVQ2Uu7tCrJgAw==",
			   "content":"3YVjs+xDtuApVKmgGPfBdfqUPfO9fmH0EJsDd4OBVzeP6bmR7YOcOJURNrPco53SXfVjU+AektMDmRBAMUmgZdzGzyoZngIwR/OkkRBcZl9n7JMgXHTuDGlPnBVB00cWv2Rq0pJXLVdD5MRYS++d5QEWdoAJ52f9NDDpIgs05ZSddVPJrade8eugkgiWjL7/iigfNu1N0bZhhywTZHrH+3f/+ZCw/PWCfZEC2tosc9sQV4UHYtzswnwzRlpIF+NBPFqtWbiGdwoaggZisB+txT5W5knwC5gGNsFHk/FE/9vBYH4PKxxwpxrmQubL58h6UwVHbXHPeno1GZXdyOQYeAw5vGTuU8VdOcG3iU1TbPdf5bliPCqpJCEYDPhGSACA"
			},
			"bundle_detail":{
			   "bundle":null,
			   "non_bundle":null,
			   "total_product":0
			},
			"addon_info":{
			   "all_level":null,
			   "order_level":null,
			   "label":"",
			   "icon_url":""
			},
			"is_plus":false,
			"shipment_fulfillment":{
			   "accept_deadline":"2023-10-31 21:24:22 +0000 UTC",
			   "confirm_shipping_deadline":"2023-11-01 21:24:22 +0000 UTC",
			   "item_delivered_deadline":""
			},
			"is_bmgm":false,
			"group_type":1
		 }
		 ';
		$decode_data_webhook = json_decode($data_order);
		$process = $this->orders->process_order($decode_data_webhook);
		pre($process);
	}

	public function new_order_test()
	{

		$data_order = '{"fs_id":18400,"order_id":1701966269,"order_status":500,"invoice_ref_num":"INV\/20231106\/MPL\/3549839845","shop_id":5537315}';
		$decode_data_webhook = json_decode($data_order);
		$process = $this->orders->process_order($decode_data_webhook);
		pre($process);
	}
}