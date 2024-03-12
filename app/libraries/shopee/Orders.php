<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Orders
{

	protected $ci;
	protected $process;
	protected $db;
	protected $queue;
	public function __construct()
	{
		$this->ci = &get_instance();
		$this->ci->load->library('shopee/process_orders');
		$this->ci->load->library('queue');
		$this->process = $this->ci->process_orders;
		$this->db = $this->ci->db;
		$this->queue = $this->ci->queue;

		date_default_timezone_set('UTC');
	}

	function get_data_source($channel_id)
	{
		try {
			$channel_data = $this->process->get_channel_data_by_channel_id($channel_id, 'order');
			if (!$channel_data) {
				throw new Exception('The configuration data has not been activated');
			}
			$config = [];
			$config['partner_id'] = intVal($channel_data->app_keys);
			$config['secret_key'] = $channel_data->secret_keys;
			$config['shop_id'] = $channel_data->shop_id;
			$config['access_token'] = $channel_data->access_token;
			$config['host'] = $channel_data->source_url;
			$config['auth_id'] = $channel_data->auth_id;

			return ['status' => true, 'data' => $config];
		} catch (Exception $e) {
			return ['status' => false, 'msg' => $e->getMessage()];
		}
	}

	function get_order_list($channel_id)
	{
		try {

			$channel_data = $this->process->get_channel_data_by_channel_id($channel_id, 'order');
			if (!$channel_data) {
				throw new Exception('The configuration data has not been activated');
			}
			$config = [];
			$config['partner_id'] = intVal($channel_data->app_keys);
			$config['secret_key'] = $channel_data->secret_keys;
			$config['shop_id'] = $channel_data->shop_id;
			$config['access_token'] = $channel_data->access_token;
			$config['host'] = $channel_data->source_url;

			$cursor = 0;
			$page_size = 50;
			$get_last_update_orders = $this->process->get_last_update_orders($channel_data->company_id, $channel_data->channel_id);
			if ($get_last_update_orders) {
				$last_update = $get_last_update_orders->local_updated_at + 1;
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
				$config['timestamp'] = time();

				$string = $config['partner_id'] . $config['path'] . $config['timestamp'] . $config['access_token'] . $config['shop_id'];
				$sign = hash_hmac('sha256', $string, $config['secret_key']);

				$param = array(
					'time_range_field' => 'update_time',
					'time_from' => $time_from,
					'time_to' => $time_to,
					'page_size' => $page_size,
					'cursor' => $cursor,
					// 'order_status' => 'PROCESSED',
					'response_optional_fields' => 'order_status',
					'access_token' => $config['access_token'],
					'partner_id' => $config['partner_id'],
					'shop_id' => $config['shop_id'],
					'sign' => $sign,
					'timestamp' => $config['timestamp'],
				);

				$url = create_url($config['host'], $config['path'], $param);

				$data = get_request_curl($url);
				if ($data->error !== "") {
					if ($data->error === 'error_auth') {
						$this->process->change_status_auth_when_error($channel_data->auth_id, $data->message);
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
					echo "success queuing order = " . $row->order_sn;
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
	function process_order($data_webhook, $show_error = 1)
	{
		try {
			$channel_data = $this->process->get_channel_data_by_shop_id($data_webhook->shop_id, 'order');

			if (!$channel_data) {
				throw new Exception('The configuration data has not been activated');
			}


			$data_source['company_id'] = $channel_data->company_id;
			$data_source['source_id'] = $channel_data->source_id;
			$data_source['auth_id'] = $channel_data->auth_id;
			$data_source['partner_id'] = intVal($channel_data->app_keys);
			$data_source['secret_key'] = $channel_data->secret_keys;
			$data_source['shop_id'] = $channel_data->shop_id;
			$data_source['access_token'] = $channel_data->access_token;
			$data_source['host'] = $channel_data->source_url;

			$ordersn = $data_webhook->data->ordersn;

			$get_order_detail = $this->get_order_detail($data_source, $ordersn);

			if (!$get_order_detail['status']) {
				throw new Exception($get_order_detail['msg']);
			}
			$data = [];
			foreach ($get_order_detail['data']->order_list as $header) {
				$order_status = $this->process->get_management_status($data_source['source_id'], NULL, $header->order_status, 'order');
				if (!$order_status) {
					throw new Exception('Order status not configured');
				}
				$header->order_status_id = $order_status;

				// package list	
				$header->package_list = isset($header->package_list) ? json_encode($header->package_list) : null;
				// end package list

				// get escrow detail
				$get_escrow_detail = $this->get_escrow_detail($data_source, $ordersn);
				// pre($get_escrow_detail);
				if (!$get_escrow_detail['status']) {
					throw new Exception($get_escrow_detail['msg']);
				}
				// pre($get_escrow_detail);
				if ($get_escrow_detail['data'] == null) {

					throw new Exception('Escrow Detail : Not Found');
				}

				$get_esc = $get_escrow_detail['data'];
				$get_income = $get_esc->order_income;

				$header->original_shipping_price = $get_income->actual_shipping_fee;
				$header->shipping_discount_amount = $get_income->shopee_shipping_rebate;
				$header->shipping_price = $header->original_shipping_price - $header->shipping_discount_amount;

				$header->commission_fee = $get_income->commission_fee;
				$header->tax_price = $get_income->service_fee;
				$header->voucher_from_seller = $get_income->voucher_from_seller;
				$header->voucher_from_channel = $get_income->voucher_from_shopee;
				$header->seller_discount = $get_income->seller_discount;

				$header->original_price = $get_income->original_price;
				$header->subtotal = $get_income->original_cost_of_goods_sold;
				$header->total_price = $get_income->escrow_amount_after_adjustment;
				$header->channel_total_price = $header->subtotal + $header->shipping_price;

				// end escrow detail

				// pickup info
				$get_shipping_parameter = $this->get_shipping_parameter($data_source, $ordersn);
				if (!$get_shipping_parameter['status']) {
					throw new Exception($get_shipping_parameter['msg']);
				}

				if (!$get_shipping_parameter['pickup_info']) {
					$header->pickup_info = null;
				} else {
					$header->pickup_info = $get_shipping_parameter['pickup_info'];
				}

				$header->pickup_message = $get_shipping_parameter['msg'];

				// end pickup info


				//get tracking number
				$get_tracking_number = $this->get_tracking_number($data_source, $ordersn);

				if (!$get_tracking_number['status']) {
					throw new Exception($get_tracking_number['msg']);
				}

				if (!$get_tracking_number['data']) {
					$header->tracking_number = null;
				} else {
					$header->tracking_number = $get_tracking_number['data'];
				}

				if ($header->tracking_number != null and $header->order_status == 'PROCESSED') {
					$arr_create_shipping_doc = [
						"order_list" => [
							[
								"order_sn" => $ordersn,
								"tracking_number" => $header->tracking_number,
							]
						]
					];

					$create_shipping_document = $this->create_shipping_document($data_source, $arr_create_shipping_doc);

					if (!$create_shipping_document['status']) {
						throw new Exception($create_shipping_document['msg']);
					}

					if (isset($create_shipping_document['data']->result_list[0])) {
						$header->shipping_label_send = 1;
					} else {
						$header->shipping_label_send = 0;
						$header->shipping_label_msg = $create_shipping_document['msg'];
					}

					if ($header->shipping_label_send) {
						unset($arr_create_shipping_doc['order_list'][0]['tracking_number']);

						$get_shipping_document_result = $this->get_shipping_document_result($data_source, $arr_create_shipping_doc);

						if (isset($get_shipping_document_result['data']->result_list[0])) {
							$header->shipping_label_status = $get_shipping_document_result['data']->result_list[0]->status;
						} else {
							$header->shipping_label_msg = $get_shipping_document_result['msg'];
						}
					}
				}

				$header->tracking_number_message = $get_tracking_number['msg'];
				// end tracking number

				//get tracking info
				$get_tracking_info = $this->get_tracking_info($data_source, $ordersn);

				if (!$get_tracking_info['status']) {
					throw new Exception($get_tracking_info['msg']);
				}

				if (!$get_tracking_info['data']) {
					$header->tracking_info = null;
				} else {
					$header->tracking_info = $get_tracking_info['data'];
				}

				$header->tracking_number_message = $get_tracking_info['msg'];
				// end tracking info

				$data = $this->create_order($channel_data, $header);
			}

			$process = $this->process->insert_order($data);
			if ($process) {
				return true;
			} else {
				throw new Exception('Error process create order');
			}
		} catch (Exception $e) {
			if ($show_error) {
				echo $e->getMessage();
			}
			return false;
		}
	}

	function get_order_detail($data_source, $ordersn)
	{
		$path = '/api/v2/order/get_order_detail';
		$timestamp = time();


		$string = $data_source['partner_id'] . $path . $timestamp . $data_source['access_token'] . $data_source['shop_id'];
		$sign = hash_hmac('sha256', $string, $data_source['secret_key']);


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
			"response_optional_fields" => $fields,
			'shop_id' => $data_source['shop_id'],
			'sign' => $sign,
			'timestamp' => $timestamp,
		);

		$url = create_url($data_source['host'], $path, $param);

		$data = get_request_curl($url);
		// pre($data);
		if (!$data) {
			return ['status' => false, 'msg' => 'Order Detail : Cannot return data from marketplace API'];
		}

		if ($data->error !== "") {
			if ($data->error === 'error_auth') {
				$this->process->change_status_auth_when_error($data_source['auth_id'], $data->message);
			}
			return ['status' => false, 'msg' => $data->message];
		}

		$data_json = $data->response;

		return ['status' => true, 'data' => $data_json];
	}

	function get_shipping_parameter($data_source, $ordersn)
	{
		$path = '/api/v2/logistics/get_shipping_parameter';
		$timestamp = time();


		$string = $data_source['partner_id'] . $path . $timestamp . $data_source['access_token'] . $data_source['shop_id'];
		$sign = hash_hmac('sha256', $string, $data_source['secret_key']);

		$param = array(
			'access_token' => $data_source['access_token'],
			'order_sn' => $ordersn,
			'partner_id' => $data_source['partner_id'],
			'shop_id' => $data_source['shop_id'],
			'sign' => $sign,
			'timestamp' => $timestamp,
		);

		$url = create_url($data_source['host'], $path, $param);

		$data = get_request_curl($url);
		if (!$data) {
			return ['status' => true, 'pickup_info' => false, 'msg' => 'Shipping Parameter : Cannot return data pickup info from marketplace API'];
		}

		if ($data->error !== "") {
			if ($data->error === 'error_auth') {
				$this->process->change_status_auth_when_error($data_source['auth_id'], $data->message);

				return ['status' => false, 'pickup_info' => false, 'msg' => $data->message];
			}

			return ['status' => true, 'pickup_info' => false, 'msg' => $data->message];
		}

		$data_json = isset($data->response) ? json_encode($data->response->pickup) : null;

		return ['status' => true, 'pickup_info' => $data_json, 'msg' => $data->message];
	}

	function get_tracking_info($data_source, $ordersn)
	{
		$path = '/api/v2/logistics/get_tracking_info';
		$timestamp = time();


		$string = $data_source['partner_id'] . $path . $timestamp . $data_source['access_token'] . $data_source['shop_id'];
		$sign = hash_hmac('sha256', $string, $data_source['secret_key']);


		$param = array(
			'access_token' => $data_source['access_token'],
			'order_sn' => $ordersn,
			'partner_id' => $data_source['partner_id'],
			'shop_id' => $data_source['shop_id'],
			'sign' => $sign,
			'timestamp' => $timestamp,
		);

		$url = create_url($data_source['host'], $path, $param);

		$data = get_request_curl($url);
		// pre($data);
		if (!$data) {
			return ['status' => false, 'data' => false, 'msg' => 'Tracking Info : Cannot return data from marketplace API'];
		}

		if ($data->error !== "") {
			if ($data->error === 'error_auth') {
				$this->process->change_status_auth_when_error($data_source['auth_id'], $data->message);
				return ['status' => false, 'data' => false, 'msg' => $data->message];
			}
			return ['status' => true, 'data' => false, 'msg' => $data->message];
		}

		$data_json = isset($data->response) ? json_encode($data->response) : null;
		return ['status' => true, 'data' => $data_json, 'msg' => $data->message];
	}
	function get_tracking_number($data_source, $ordersn)
	{
		$path = '/api/v2/logistics/get_tracking_number';
		$timestamp = time();


		$string = $data_source['partner_id'] . $path . $timestamp . $data_source['access_token'] . $data_source['shop_id'];
		$sign = hash_hmac('sha256', $string, $data_source['secret_key']);


		$param = array(
			'access_token' => $data_source['access_token'],
			'order_sn' => $ordersn,
			'partner_id' => $data_source['partner_id'],
			'shop_id' => $data_source['shop_id'],
			'sign' => $sign,
			'timestamp' => $timestamp,
		);

		$url = create_url($data_source['host'], $path, $param);

		$data = get_request_curl($url);
		// pre($data);
		if (!$data) {
			return ['status' => false, 'data' => false, 'msg' => 'Tracking Number : Cannot return data from marketplace API'];
		}

		if ($data->error !== "") {
			if ($data->error === 'error_auth') {
				$this->process->change_status_auth_when_error($data_source['auth_id'], $data->message);
				return ['status' => false, 'data' => false, 'msg' => $data->message];
			}
			return ['status' => true, 'data' => false, 'msg' => $data->message];
		}

		$data_json = isset($data->response) ? $data->response->tracking_number : null;
		return ['status' => true, 'data' => $data_json, 'msg' => $data->message];
	}
	function ship_order($data_source, $ship_order_data)
	{
		$path = '/api/v2/logistics/ship_order';
		$timestamp = time();

		$string = $data_source['partner_id'] . $path . $timestamp . $data_source['access_token'] . $data_source['shop_id'];
		$sign = hash_hmac('sha256', $string, $data_source['secret_key']);

		$param = array(
			'access_token' => $data_source['access_token'],
			'partner_id' => $data_source['partner_id'],
			'shop_id' => $data_source['shop_id'],
			'sign' => $sign,
			'timestamp' => $timestamp,
		);

		$url = create_url($data_source['host'], $path, $param);

		$data = post_request_curl($url, $ship_order_data);
		if (!$data) {
			return ['status' => false, 'msg' => 'Ship Order : Cannot return data from marketplace API'];
		}

		if ($data->error !== "") {
			if ($data->error === 'error_auth') {
				$this->process->change_status_auth_when_error($data_source['auth_id'], $data->message);
				return ['status' => false, 'data' => false, 'msg' => $data->message];
			}
			return ['status' => true, 'data' => false, 'msg' => $data->message];
		}


		$data_json = isset($data->response) ? json_encode($data->response) : null;
		return ['status' => true, 'data' => $data_json, 'msg' => $data->message];
	}
	function create_order($channel_data, $data_order)
	{
		$data['users_ms_companys_id'] = $channel_data->company_id;
		$data['admins_ms_sources_id'] = $channel_data->source_id;
		$data['users_ms_channels_id'] = $channel_data->channel_id;
		$data['users_ms_warehouses_id'] = 1;
		$data['source_name'] = $channel_data->source_name;
		$data['channel_name'] = $channel_data->channel_name;

		$data['order_status_id'] = $data_order->order_status_id;
		$data['local_order_id'] = $data_order->order_sn;
		$data['local_shop_id'] = $channel_data->shop_id;
		$data['local_warehouse_id'] = "";
		$data['local_order_status'] = $data_order->order_status;


		$data['buyer_id'] = $data_order->buyer_user_id;
		$data['buyer_name'] = $data_order->buyer_username;


		// $data['recipient_id'] = $data_order->buyer_user_id;
		$data['recipient_name'] = $data_order->recipient_address->name;
		$data['recipient_phone'] = $data_order->recipient_address->phone;
		$data['recipient_email'] = isset($data_order->buyer_email) ? $data_order->buyer_email : "";
		$data['recipient_address_1'] = $data_order->recipient_address->full_address;
		$data['recipient_address_2'] = "";
		$data['recipient_city'] = $data_order->recipient_address->city;
		$data['recipient_country'] = $data_order->recipient_address->region;
		$data['recipient_province'] = $data_order->recipient_address->state;
		$data['recipient_district'] = $data_order->recipient_address->district;
		$data['recipient_zipcode'] = $data_order->recipient_address->zipcode;
		$data['recipient_full_address'] = $data_order->recipient_address->full_address;


		$data['shipping_provider_id'] = isset($data_order->shipping_provider_id) ? $data_order->shipping_provider_id : "";
		$data['shipping_provider_name'] = $data_order->shipping_carrier;
		$data['shipping_provider_type'] = $data_order->checkout_shipping_carrier;;
		$data['shipping_description'] = "";

		$data['payment_method'] = $data_order->payment_method;
		$data['is_cashless'] = $data_order->cod ? 0 : 1;
		$data['is_cod'] = $data_order->cod;

		$data['voucher_code'] = "";
		$data['discount_reason'] = "";

		$data['original_shipping_price'] = $data_order->original_shipping_price;
		$data['shipping_discount_amount'] = $data_order->shipping_discount_amount;
		$data['shipping_price'] = $data_order->shipping_price;

		$data['commission_fee'] = $data_order->commission_fee;
		$data['tax_price'] = $data_order->tax_price;
		$data['voucher_from_seller'] = $data_order->voucher_from_seller;
		$data['voucher_from_channel'] = $data_order->voucher_from_channel;
		$data['seller_discount'] = $data_order->seller_discount;
		$data['insurance_fee'] = 0;
		$data['discount_amount'] = 0;

		$data['original_price'] = $data_order->original_price;
		$data['subtotal'] = $data_order->subtotal;
		$data['total_price'] = $data_order->total_price;
		$data['channel_total_price'] = $data_order->channel_total_price;

		$data['note'] = $data_order->message_to_seller;

		$data['package_list'] = isset($data_order->package_list) ? $data_order->package_list : null;
		$data['tracking_info_message'] = isset($data_order->tracking_info_message) ? $data_order->tracking_info_message : "";
		$data['tracking_info'] = isset($data_order->tracking_info) ? $data_order->tracking_info : null;
		$data['pickup_message'] = isset($data_order->pickup_message) ? $data_order->pickup_message : "";
		$data['pickup_info'] = isset($data_order->pickup_info) ? $data_order->pickup_info : null;
		$data['tracking_number_message'] = isset($data_order->tracking_number_message) ? $data_order->tracking_number_message : "";
		$data['tracking_number'] = isset($data_order->tracking_number) ? $data_order->tracking_number : "";

		if ($data['local_order_status'] == "PROCESSED") {
			$data['shipping_label_send'] = $data_order->shipping_label_send;
			$data['shipping_label_status'] =  isset($data_order->shipping_label_status) ? $data_order->shipping_label_status : "";
			$data['shipping_label_msg'] =  isset($data_order->shipping_label_msg) ? $data_order->shipping_label_msg : "";
		}

		$data['error_code'] = 0;
		$data['error_message'] = "Success";
		$data['local_ordered_at'] = $data_order->create_time;
		$data['local_created_at'] = $data_order->create_time;
		$data['local_updated_at'] = $data_order->update_time;

		$data['item_list'] = [];
		foreach ($data_order->item_list as $detail) {
			$product_data = $this->process->get_product_by_sku($channel_data->company_id, $detail->model_sku);

			$item = [];
			$item['users_ms_companys_id'] = $channel_data->company_id;
			$item['users_tr_orders_id'] = "";
			$item['local_order_id'] = $data_order->order_sn;
			$item['local_item_id'] = $detail->item_id;
			$item['local_item_name'] = $detail->item_name;
			$item['local_item_sku'] = $detail->model_sku;
			$item['local_image'] = $detail->image_info->image_url;
			$item['product_id'] = isset($product_data->product_id) ? $product_data->product_id : NULL;
			$item['product_name'] = isset($product_data->product_name) ? $product_data->product_name : NULL;
			$item['product_sku'] = isset($product_data->sku) ? $product_data->sku : NULL;
			$item['product_original_price'] = $detail->model_original_price;
			$item['product_discount_price'] = $detail->model_discounted_price > 0 ? $detail->model_discounted_price : 0;
			$item['quantity_purchased'] = $detail->model_quantity_purchased;
			$item['local_note'] = "";
			if (!isset($product_data->product_id)) {
				$item['error_code'] = 3;
				$item['error_message'] = "Product not listed.";
			} elseif (!isset($product_data->sku)) {
				$item['error_code'] = 1;
				$item['error_message'] = "SKU not listed.";
			} else {
				$item['error_code'] = 0;
				$item['error_message'] = "Success";
			}


			$data['item_list'][] = $item;
		}

		return $data;
	}
	function create_shipment($local_order_id, $data_shipment)
	{
		$this->db->trans_begin();
		try {

			$data_order = $this->process->check_order_by_local_id($local_order_id);
			if (!$data_order) {
				throw new Exception('Order not found');
			}
			$channel_data = $this->get_data_source($data_order->users_ms_channels_id);

			if (!$channel_data['status']) {
				throw new Exception($channel_data['msg']);
			}

			// $ship_order = $this->ship_order($channel_data,$data_shipment);
			$ship_order = ['status' => true, 'msg' => "success"];
			if (!$ship_order['status']) {
				throw new Exception($ship_order['msg']);
			}
			$data = array(
				'pickup_selected' => $data_shipment,
				'pickup_message' => $ship_order['msg']
			);
			$this->db->where('id', $data_order->users_tr_orders_id);
			$update = $this->db->update('users_tr_orders', $data);

			if (!$update) {
				throw new Exception('Cannot update data Shipment');
			}

			create_log_order($data_order->users_ms_companys_id, $data_order->users_tr_orders_id, 3, strtotime(date('Y-m-d H:i:s')), 'API');

			$this->db->trans_commit();
			return ['status' => true, 'msg' => 'Success create shipment'];
		} catch (Exception $e) {
			$this->db->trans_rollback();
			return ['status' => false, 'msg' => $e->getMessage()];
		}
	}

	function create_shipping_document($data_source, $data_arr)
	{
		try {

			$path = '/api/v2/logistics/create_shipping_document';
			$timestamp = time();

			$string = $data_source['partner_id'] . $path . $timestamp . $data_source['access_token'] . $data_source['shop_id'];
			$sign = hash_hmac('sha256', $string, $data_source['secret_key']);

			$param = array(
				'access_token' => $data_source['access_token'],
				'partner_id' => $data_source['partner_id'],
				'shop_id' => $data_source['shop_id'],
				'sign' => $sign,
				'timestamp' => $timestamp,
			);

			$url = create_url($data_source['host'], $path, $param);

			// pre($url);
			$data = post_request_curl($url, json_encode($data_arr));
			if (!$data) {
				return ['status' => false, 'msg' => 'Create Shipping Document : Cannot return data from marketplace API'];
			}

			if ($data->error !== "") {
				if ($data->error === 'error_auth') {
					$this->process->change_status_auth_when_error($data_source['auth_id'], $data->message);
					return ['status' => false, 'data' => false, 'msg' => $data->message];
				}
				return ['status' => true, 'data' => false, 'msg' => $data->message];
			}


			$data_json = isset($data->response) ? $data->response : null;
			return ['status' => true, 'data' => $data_json, 'msg' => $data->message];
		} catch (Exception $e) {
			return ['status' => false, 'msg' => $e->getMessage()];
		}
	}

	function get_shipping_document_result($data_source, $data_arr)
	{
		try {
			$path = '/api/v2/logistics/get_shipping_document_result';
			$timestamp = time();

			$string = $data_source['partner_id'] . $path . $timestamp . $data_source['access_token'] . $data_source['shop_id'];
			$sign = hash_hmac('sha256', $string, $data_source['secret_key']);

			$param = array(
				'access_token' => $data_source['access_token'],
				'partner_id' => $data_source['partner_id'],
				'shop_id' => $data_source['shop_id'],
				'sign' => $sign,
				'timestamp' => $timestamp,
			);

			$url = create_url($data_source['host'], $path, $param);

			// pre($url);
			$data = post_request_curl($url, json_encode($data_arr));
			if (!$data) {
				return ['status' => false, 'msg' => 'Get Shipping Document Result : Cannot return data from marketplace API'];
			}

			if ($data->error !== "") {
				if ($data->error === 'error_auth') {
					$this->process->change_status_auth_when_error($data_source['auth_id'], $data->message);
					return ['status' => false, 'data' => false, 'msg' => $data->message];
				}
				return ['status' => true, 'data' => false, 'msg' => $data->message];
			}


			$data_json = isset($data->response) ? $data->response : null;
			return ['status' => true, 'data' => $data_json, 'msg' => $data->message];
		} catch (Exception $e) {
			return ['status' => false, 'msg' => $e->getMessage()];
		}
	}

	function get_shipping_document_result_webhook($data_webhook)
	{

		$this->db->trans_begin();
		try {

			$data_order = $this->process->check_order_by_local_id($data_webhook->data->ordersn, $data_webhook->shop_id);

			if (!$data_order) {
				throw new Exception('Order not found');
			}

			$arr_update = array('shipping_label_status' => $data_webhook->data->status);
			$this->db->where('local_order_id', $data_webhook->data->ordersn);
			$this->db->update('users_tr_orders', $arr_update);
			$this->db->trans_commit();
			return true;
		} catch (Exception $e) {
			$this->db->trans_rollback();
			return false;
		}
	}

	function download_shipping_document($local_order_id, $data_arr)
	{
		try {

			$data_order = $this->process->check_order_by_local_id($local_order_id);
			if (!$data_order) {
				throw new Exception('Order not found');
			}
			$channel_data = $this->get_data_source($data_order->users_ms_channels_id);

			if (!$channel_data['status']) {
				throw new Exception($channel_data['msg']);
			}

			$data_source = $channel_data['data'];
			$path = '/api/v2/logistics/download_shipping_document';
			$timestamp = time();

			$string = $data_source['partner_id'] . $path . $timestamp . $data_source['access_token'] . $data_source['shop_id'];
			$sign = hash_hmac('sha256', $string, $data_source['secret_key']);

			$param = array(
				'access_token' => $data_source['access_token'],
				'partner_id' => $data_source['partner_id'],
				'shop_id' => $data_source['shop_id'],
				'sign' => $sign,
				'timestamp' => $timestamp,
			);

			$url = create_url($data_source['host'], $path, $param);

			// pre($url);
			$data = post_request_curl_file($url, $data_arr);
			if (!$data) {
				return ['status' => false, 'msg' => 'Download Shipping Label : Cannot return data from marketplace API'];
			}

			if (isset(json_decode($data)->error)) {

				$err = json_decode($data);
				if ($err->error !== "") {
					if ($err->error === 'error_auth') {
						$this->process->change_status_auth_when_error($data_source['auth_id'], $err->message);
						return ['status' => false, 'data' => false, 'msg' => $err->message];
					}
					return ['status' => true, 'data' => false, 'msg' => $err->message];
				}
			}


			return ['status' => true, 'data' => $data, 'msg' => 'Download Print Label onprogress'];
		} catch (Exception $e) {
			return ['status' => false, 'msg' => $e->getMessage()];
		}
	}

	function get_escrow_detail($data_source, $local_order_id)
	{
		try {

			$path = '/api/v2/payment/get_escrow_detail';
			$timestamp = time();

			$string = $data_source['partner_id'] . $path . $timestamp . $data_source['access_token'] . $data_source['shop_id'];
			$sign = hash_hmac('sha256', $string, $data_source['secret_key']);

			$param = array(
				'access_token' => $data_source['access_token'],
				'partner_id' => $data_source['partner_id'],
				'shop_id' => $data_source['shop_id'],
				'sign' => $sign,
				'timestamp' => $timestamp,
				'order_sn'	=> $local_order_id
			);

			$url = create_url($data_source['host'], $path, $param);

			$data = get_request_curl($url);
			if (!$data) {
				return ['status' => false, 'msg' => 'Get escrow detail : Cannot return data from marketplace API'];
			}

			if ($data->error) {

				if ($data->error !== "") {
					if ($data->error === 'error_auth') {
						$this->process->change_status_auth_when_error($data_source['auth_id'], $data->message);
						return ['status' => false, 'data' => false, 'msg' => $data->message];
					}
					return ['status' => true, 'data' => false, 'msg' => $data->message];
				}
			}


			$data_json = isset($data->response) ? $data->response : null;
			return ['status' => true, 'data' => $data_json, 'msg' => $data->message];
		} catch (Exception $e) {
			return ['status' => false, 'msg' => $e->getMessage()];
		}
	}

	function get_escrow_detail_by_local_order_id($local_order_id)
	{
		try {

			$data_order = $this->process->check_order_by_local_id($local_order_id);
			if (!$data_order) {
				throw new Exception('Order not found');
			}
			$channel_data = $this->get_data_source($data_order->users_ms_channels_id);

			if (!$channel_data['status']) {
				throw new Exception($channel_data['msg']);
			}

			$data_source = $channel_data['data'];
			$path = '/api/v2/payment/get_escrow_detail';
			$timestamp = time();

			$string = $data_source['partner_id'] . $path . $timestamp . $data_source['access_token'] . $data_source['shop_id'];
			$sign = hash_hmac('sha256', $string, $data_source['secret_key']);

			$param = array(
				'access_token' => $data_source['access_token'],
				'partner_id' => $data_source['partner_id'],
				'shop_id' => $data_source['shop_id'],
				'sign' => $sign,
				'timestamp' => $timestamp,
				'order_sn'	=> $local_order_id
			);

			$url = create_url($data_source['host'], $path, $param);

			// pre($url);
			$data = get_request_curl($url);
			if (!$data) {
				return ['status' => false, 'msg' => 'Get escrow detail : Cannot return data from marketplace API'];
			}

			if ($data->error) {

				if ($data->error !== "") {
					if ($data->error === 'error_auth') {
						$this->process->change_status_auth_when_error($data_source['auth_id'], $data->message);
						return ['status' => false, 'data' => false, 'msg' => $data->message];
					}
					return ['status' => true, 'data' => false, 'msg' => $data->message];
				}
			}


			return ['status' => true, 'data' => $data, 'msg' => 'Success'];
		} catch (Exception $e) {
			return ['status' => false, 'msg' => $e->getMessage()];
		}
	}
}
