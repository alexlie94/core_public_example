<?php

use PhpParser\Node\Stmt\TryCatch;

defined('BASEPATH') or exit('No direct script access allowed');

class Shopee_model extends MY_OrderMarketplace
{

	protected $auto_pickup  = 1;
	protected $_source_id = 2;
	public function __construct()
	{
		parent::__construct();

		$this->load->library('queue');
		date_default_timezone_set('UTC');
	}

	function get_address_list()
	{

		$this->db->trans_begin();
		try {

			$channel_list = $this->get_all_channel_data_by_source_id($this->_source_id);
			if (!$channel_list) {
				throw new Exception('The configuration data has not been activated');
			}
			foreach ($channel_list as $channel_data) {
				$config = [];
				$config['partner_id']        	= intVal($channel_data->app_keys);
				$config['secret_key']    		= $channel_data->secret_keys;
				$config['shop_id']       		= $channel_data->shop_id;
				$config['access_token']  		= $channel_data->access_token;
				$config['host']       			= $channel_data->source_url;

				$config['path'] = '/api/v2/logistics/get_address_list';
				$config['timestamp']     		= time();

				$string     = $config['partner_id'] . $config['path'] . $config['timestamp'] . $config['access_token'] .  $config['shop_id'];
				$sign       = hash_hmac('sha256', $string, $config['secret_key']);

				$param = array(
					'access_token' 		=> $config['access_token'],
					'partner_id' 		=> $config['partner_id'],
					'shop_id' 			=> $config['shop_id'],
					'sign' 				=> $sign,
					'timestamp' 		=> $config['timestamp'],
				);

				$url     = create_url($config['host'], $config['path'], $param);

				$data    = get_request_curl($url);

				if ($data->error !== "") {
					if ($data->error === 'error_auth') {

						$this->change_status_auth_when_error($channel_data->auth_id, $data->message);
					}
					throw new Exception($data->message);
				}

				$list = $data->response->address_list;
				if (!empty($list)) {
					foreach ($list as $row) {

						if (!empty($row->address_type)) {
							$addressTypeString = implode(",", $row->address_type);
						} else {
							$addressTypeString = null;
						}
						$array = array(
							'users_ms_companys_id' =>  $channel_data->company_id,
							'admins_ms_sources_id' =>  $channel_data->source_id,
							'users_ms_channels_id' =>  $channel_data->channel_id,
							'address_id' =>  $row->address_id,
							'region' =>  $row->region,
							'state' =>  $row->state,
							'city' =>  $row->city,
							'address' =>  $row->address,
							'zipcode' =>  $row->zipcode,
							'district' =>  $row->district,
							'town' =>  $row->town,
							'address_type' =>  $addressTypeString,
							'updated_by' =>  'API'
						);

						$check_address = $this->check_warehouse_source_address($channel_data, $row->address_id);

						if ($check_address) {
							$this->db->where('users_ms_companys_id',  $channel_data->company_id);
							$this->db->where('admins_ms_sources_id',  $channel_data->source_id);
							$this->db->where('users_ms_channels_id',  $channel_data->channel_id);
							$this->db->where('address_id',  $row->address_id);
							$this->db->update('users_ms_warehouse_source_address', $array);
						} else {
							$this->db->insert('users_ms_warehouse_source_address', $array);
						}
					}
				}
			}

			$this->db->trans_commit();
			return ['success' => true, 'messages' => 'success'];
		} catch (Exception $e) {
			$this->db->trans_rollback();
			return ['success' => false, 'messages' => $e->getMessage()];
		}
	}
	// 83840
	function get_order_list($channel_id)
	{
		try {

			$channel_data = $this->get_channel_data_by_channel_id($channel_id, 'order');
			if (!$channel_data) {
				throw new Exception('The configuration data has not been activated');
			}
			$config = [];
			$config['partner_id']        	= intVal($channel_data->app_keys);
			$config['secret_key']    		= $channel_data->secret_keys;
			$config['shop_id']       		= $channel_data->shop_id;
			$config['access_token']  		= $channel_data->access_token;
			$config['host']       			= $channel_data->source_url;

			$cursor = 0;
			$page_size = 10;
			$get_last_update_orders = $this->get_last_update_orders($channel_data->company_id, $channel_data->channel_id);
			if ($get_last_update_orders) {
				$last_update =  $get_last_update_orders->local_updated_at + 1;
				$time_to = time();

				$time_diff = $time_to - $last_update;

				// Jika selisih waktu lebih dari 15 hari (1296000 detik dalam 15 hari)
				if ($time_diff > 1296000) {
					$time_from = $time_to - 1296000;
				} else {
					$time_from = $last_update;
				}
			} else {
				$time_to = time();
				$time_from = $time_to - 7200;
			}
			while (1) {

				$config['path'] = '/api/v2/order/get_order_list';
				$config['timestamp']     		= time();

				$string     = $config['partner_id'] . $config['path'] . $config['timestamp'] . $config['access_token'] .  $config['shop_id'];
				$sign       = hash_hmac('sha256', $string, $config['secret_key']);

				$param = array(
					'time_range_field' 	=> 'update_time',
					'time_from' 		=> $time_from,
					'time_to' 			=> $time_to,
					'page_size'	 		=> $page_size,
					'cursor' 			=> $cursor,
					'response_optional_fields'  => 'order_status',
					'access_token' 		=> $config['access_token'],
					'partner_id' 		=> $config['partner_id'],
					'shop_id' 			=> $config['shop_id'],
					'sign' 				=> $sign,
					'timestamp' 		=> $config['timestamp'],
				);

				$url     = create_url($config['host'], $config['path'], $param);

				$data    = get_request_curl($url);
				if ($data->error !== "") {
					if ($data->error === 'error_auth') {
						$this->change_status_auth_when_error($channel_data->auth_id, $data->message);
					}
					throw new Exception($data->message);
				}

				$list = $data->response;
				// pre($list);
				if (empty($list->order_list)) {
					throw new Exception("Order list is empty");
				}

				foreach ($list->order_list as $row) {

					$json['data']['items'] = [];
					$json['data']['ordersn'] = $row->order_sn;
					$json['data']['status'] = $row->order_status;
					$json['data']['completed_scenario'] = "";
					$json['data']['update_time'] = $config['timestamp'];
					$json['shop_id'] = $config['shop_id'];
					$json['code'] = 3;
					$json['timestamp'] = $config['timestamp'];
					$this->queue->shopee_order_push(json_encode($json));
					echo "success queuing order = " .  $row->order_sn;
				}

				$cursor = $page_size + $cursor;
				echo "next cursor = " . $cursor . "<br>";
				echo "more = " . $list->more . "<br>";
				continue;
			}
		} catch (Exception $e) {
			return $e->getMessage();
		}
	}
	function process_order($data_webhook)
	{
		try {

			$channel_data = $this->get_channel_data_by_shop_id($data_webhook->shop_id, 'order');

			if (!$channel_data) {
				throw new Exception('The configuration data has not been activated');
			}


			$data_source['company_id'] 			= $channel_data->company_id;
			$data_source['source_id'] 			= $channel_data->source_id;
			$data_source['auth_id'] 			= $channel_data->auth_id;
			$data_source['partner_id']  		= intVal($channel_data->app_keys);
			$data_source['secret_key'] 			= $channel_data->secret_keys;
			$data_source['shop_id']        		= $channel_data->shop_id;
			$data_source['access_token']   		= $channel_data->access_token;
			$data_source['host']          		= $channel_data->source_url;

			$ordersn 							= $data_webhook->data->ordersn;

			$get_order_detail = $this->get_order_detail($data_source, $ordersn);


			if (!$get_order_detail['status']) {
				throw new Exception($get_order_detail['msg']);
			}
			$data = [];
			foreach ($get_order_detail['data']->order_list as $header) {
				$order_status  = $this->get_management_status($data_source['source_id'], NULL, $header->order_status, 'order');
				if (!$order_status) {
					throw new Exception('Order status not configured');
				}
				// pre($header);
				$header->order_status_id = $order_status;
				if ($header->order_status == 'READY_TO_SHIP') {
					$get_shipping_parameter = $this->get_shipping_parameter($data_source, $ordersn);

					$header->pickup_message = $get_shipping_parameter['msg'];
					if (!$get_shipping_parameter) {
						throw new Exception($get_shipping_parameter['msg']);
					}
					if (!$get_shipping_parameter['pickup_info']) {
						$header->pickup_info = null;
					} else {
						$header->pickup_info = json_encode($get_shipping_parameter['pickup_info']);
					}
				}

				$data = $this->create_order($channel_data, $header);
			}
			$process = $this->insert_order($data);
			if ($process) {
				return true;
			} else {
				throw new Exception('Error process create order');
			}
		} catch (Exception $e) {
			echo $e->getMessage();
			return false;
		}
	}

	function get_order_detail($data_source, $ordersn)
	{
		$path           	= '/api/v2/order/get_order_detail';
		$timestamp      	= time();


		$string     = $data_source['partner_id'] . $path . $timestamp . $data_source['access_token'] .  $data_source['shop_id'];
		$sign         = hash_hmac('sha256', $string, $data_source['secret_key']);


		$fields_array = array(
			'estimated_shipping_fee',
			'recipient_address',
			'buyer_email',
			'buyer_user_id',
			'buyer_username',
			'item_list',
			'actual_shipping_fee_confirmed',
			"actual_shipping_fee",
			'fulfillment_flag',
			'package_list',
			'shipping_carrier',
			'payment_method',
			'total_amount',
			'invoice_data',
			'checkout_shipping_carrier',
			'note'
		);
		$fields = "%5B" . implode('%2C', $fields_array) . "%5D";

		$param = array(
			'access_token' => $data_source['access_token'],
			'order_sn_list' => $ordersn,
			'partner_id' => $data_source['partner_id'],
			"response_optional_fields"  => $fields,
			'shop_id' => $data_source['shop_id'],
			'sign' => $sign,
			'timestamp' => $timestamp,
		);

		$url     = create_url($data_source['host'], $path, $param);

		$data    = get_request_curl($url);
		// pre($data);
		if (!$data) {
			return ['status' => false, 'msg' => 'Cannot return data from marketplace API'];
		}

		if ($data->error !== "") {
			if ($data->error === 'error_auth') {
				$this->change_status_auth_when_error($data_source['auth_id'], $data->message);
			}
			return ['status' => false, 'msg' => $data->message];
		}

		$data_json = $data->response;

		return ['status' => true, 'data' => $data_json];
	}

	function get_shipping_parameter($data_source, $ordersn)
	{
		$path           	= '/api/v2/logistics/get_shipping_parameter';
		$timestamp      	= time();


		$string     = $data_source['partner_id'] . $path . $timestamp . $data_source['access_token'] .  $data_source['shop_id'];
		$sign         = hash_hmac('sha256', $string, $data_source['secret_key']);

		$param = array(
			'access_token' => $data_source['access_token'],
			'order_sn' => $ordersn,
			'partner_id' => $data_source['partner_id'],
			'shop_id' => $data_source['shop_id'],
			'sign' => $sign,
			'timestamp' => $timestamp,
		);

		$url     = create_url($data_source['host'], $path, $param);

		$data    = get_request_curl($url);
		if (!$data) {
			return ['status' => true, 'pickup_info' => false, 'msg' => 'Cannot return data pickup info from marketplace API'];
		}

		if ($data->error !== "") {
			if ($data->error === 'error_auth') {
				$this->change_status_auth_when_error($data_source['auth_id'], $data->message);
			}
			return ['status' => false, 'pickup_info' => false, 'msg' => $data->message];
		}

		$data_json = $data->response->pickup;

		return ['status' => true, 'pickup_info' => $data_json, 'msg' => $data->message];
	}

	function ship_order($data_source, $ship_order_data)
	{
		$path           	= '/api/v2/logistics/ship_order';
		$timestamp      	= time();

		$string     = $data_source['partner_id'] . $path . $timestamp . $data_source['access_token'] .  $data_source['shop_id'];
		$sign         = hash_hmac('sha256', $string, $data_source['secret_key']);

		$param = array(
			'access_token' => $data_source['access_token'],
			'partner_id' => $data_source['partner_id'],
			'shop_id' => $data_source['shop_id'],
			'sign' => $sign,
			'timestamp' => $timestamp,
		);

		$url     = create_url($data_source['host'], $path, $param);

		$data    = post_request_curl($url, $ship_order_data);
		if (!$data) {
			return ['status' => false, 'msg' => 'Cannot return data ship order from marketplace API'];
		}

		if ($data->error !== "") {
			if ($data->error === 'error_auth') {
				$this->change_status_auth_when_error($data_source['auth_id'], $data->message);
			}
			return ['status' => false, 'msg' => $data->message];
		}


		return ['status' => true, 'msg' => $data->message];
	}
	function get_tracking_info($data_source, $ordersn)
	{
		$path           	= '/api/v2/logistics/get_tracking_info';
		$timestamp      	= time();


		$string     = $data_source['partner_id'] . $path . $timestamp . $data_source['access_token'] .  $data_source['shop_id'];
		$sign         = hash_hmac('sha256', $string, $data_source['secret_key']);


		$param = array(
			'access_token' => $data_source['access_token'],
			'order_sn' => $ordersn,
			'partner_id' => $data_source['partner_id'],
			'shop_id' => $data_source['shop_id'],
			'sign' => $sign,
			'timestamp' => $timestamp,
		);

		$url     = create_url($data_source['host'], $path, $param);

		$data    = get_request_curl($url);
		if (!$data) {
			return ['status' => false, 'msg' => 'Cannot return data from marketplace API'];
		}

		if ($data->error !== "") {
			if ($data->error === 'error_auth') {
				$this->change_status_auth_when_error($data_source['auth_id'], $data->message);
			}
			return ['status' => false, 'msg' => $data->message];
		}

		$data_json = $data->response;
		return ['status' => true, 'data' => $data_json];
	}

	function get_tracking_number($data_source, $ordersn)
	{
		$path           	= '/api/v2/logistics/get_tracking_number';
		$timestamp      	= time();


		$string     = $data_source['partner_id'] . $path . $timestamp . $data_source['access_token'] .  $data_source['shop_id'];
		$sign         = hash_hmac('sha256', $string, $data_source['secret_key']);


		$param = array(
			'access_token' => $data_source['access_token'],
			'order_sn' => $ordersn,
			'partner_id' => $data_source['partner_id'],
			'shop_id' => $data_source['shop_id'],
			'sign' => $sign,
			'timestamp' => $timestamp,
		);

		$url     = create_url($data_source['host'], $path, $param);

		$data    = get_request_curl($url);
		if (!$data) {
			return ['status' => false, 'msg' => 'Cannot return data from marketplace API'];
		}

		if ($data->error !== "") {
			if ($data->error === 'error_auth') {
				$this->change_status_auth_when_error($data_source['auth_id'], $data->message);
			}
			return ['status' => false, 'msg' => $data->message];
		}

		$data_json = $data->response;
		return ['status' => true, 'data' => $data_json];
	}
	function create_order($channel_data, $data_order)
	{
		$data['users_ms_companys_id'] 	= $channel_data->company_id;
		$data['admins_ms_sources_id'] 	= $channel_data->source_id;
		$data['users_ms_channels_id'] 	= $channel_data->channel_id;
		$data['users_ms_warehouses_id'] = 1;
		$data['source_name'] 			=  $channel_data->source_name;
		$data['channel_name'] 			=  $channel_data->channel_name;
		$data['trx_number'] 			= "";

		$data['order_status_id'] 	= $data_order->order_status_id;
		$data['local_order_id'] 	= $data_order->order_sn;
		$data['local_shop_id'] 		= $channel_data->shop_id;
		$data['local_warehouse_id'] = "";
		$data['local_order_status'] = $data_order->order_status;


		$data['recipient_id'] 		= $data_order->buyer_user_id;
		$data['recipient_name'] 	= $data_order->recipient_address->name;
		$data['recipient_phone'] 	= $data_order->recipient_address->phone;
		$data['recipient_email'] 	= isset($data_order->buyer_email) ? $data_order->buyer_email : "";
		$data['recipient_address_1'] = $data_order->recipient_address->full_address;
		$data['recipient_address_2'] = "";
		$data['recipient_city'] 	= $data_order->recipient_address->city;
		$data['recipient_country'] 	= $data_order->recipient_address->region;
		$data['recipient_province']	= $data_order->recipient_address->state;
		$data['recipient_district']	= $data_order->recipient_address->district;
		$data['recipient_zipcode']	= $data_order->recipient_address->zipcode;
		$data['recipient_full_address'] = $data_order->recipient_address->full_address;


		$data['shipping_price']			= $data_order->actual_shipping_fee;
		$data['shipping_disc_seller']	= 0;
		$data['shipping_disc_platform']	= 0;
		$data['shipping_awb']			= isset($data_order->tracking_number) ? $data_order->tracking_number : "";
		$data['shipping_provider_id']	= isset($data_order->shipping_provider_id) ? $data_order->shipping_provider_id : "";
		$data['shipping_provider_name']	= $data_order->shipping_carrier;
		$data['shipping_provider_type']	= "";
		$data['shipping_description']	= "";

		$data['payment_method']		= $data_order->payment_method;
		$data['is_cashless']		= $data_order->cod ? 0 : 1;
		$data['is_cod']				= $data_order->cod;

		$data['voucher_code']		= "";
		$data['voucher_seller']		= "";
		$data['insurance_fee']		= "";
		$data['tax_price']			= 0;
		$data['discount_amount']	= "";
		$data['discount_reason']	= "";
		$data['subtotal']			= 0;
		$data['total_price']		= $data_order->total_amount;
		$data['note']				= $data_order->message_to_seller;
		$data['pickup_info']		= isset($data_order->pickup_info) ? $data_order->pickup_info : null;
		$data['pickup_selected']	= isset($data_order->pickup_selected) ? $data_order->pickup_selected : null;
		$data['pickup_message']	= isset($data_order->pickup_message) ? $data_order->pickup_message : null;
		$data['error_code']			= 0;
		$data['error_message']		= "Success";
		$data['local_ordered_at']	= $data_order->create_time;
		$data['local_created_at']	= $data_order->create_time;
		$data['local_updated_at']	= $data_order->update_time;

		$data['item_list'] = [];
		foreach ($data_order->item_list as $detail) {
			$product_data = $this->get_product_by_sku($channel_data->company_id, $detail->model_sku);

			$item = [];
			$item['users_ms_companys_id'] = $channel_data->company_id;
			$item['users_tr_orders_id'] = "";
			$item['local_order_id'] = $data_order->order_sn;
			$item['local_item_id'] = $detail->item_id;
			$item['local_item_name'] = $detail->model_name;
			$item['local_item_sku'] = $detail->model_sku;
			$item['product_id'] = isset($product_data->product_id) ? $product_data->product_id : NULL;
			$item['product_name'] = isset($product_data->product_name) ? $product_data->product_name : NULL;
			$item['product_sku'] = isset($product_data->sku) ? $product_data->sku : NULL;
			$item['product_original_price'] = $detail->model_original_price;
			$item['product_discount_price'] = $detail->model_discounted_price;
			$item['quantity_purchased'] = $detail->model_quantity_purchased;
			$item['local_note'] = "";
			if (!isset($product_data->product_id)) {
				$item['error_code']			= 3;
				$item['error_message']		= "Product not listed.";
			} elseif (!isset($product_data->sku)) {
				$item['error_code']			= 1;
				$item['error_message']		= "SKU not listed.";
			} else {
				$item['error_code']			= 0;
				$item['error_message']		= "Success";
			}


			$data['item_list'][] = $item;
		}

		return $data;
	}
	function create_shipping_document($channel_data, $order_sn)
	{

		$partner_id        	= intVal($channel_data->partner_id);
		$secret_key     	= $channel_data->secret_key;
		$shop_id        	= $channel_data->shop_id;
		$access_token   	= $channel_data->access_token;
		$path           	= "/api/v2/logistics/create_shipping_document";
		$timestamp      	= time();
		$host           	= $channel_data->marketplace_url;


		$string     = $partner_id . $path . $timestamp . $access_token .  $shop_id;
		$sign         = hash_hmac('sha256', $string, $secret_key);
	}
}