<?php

use PhpParser\Node\Stmt\TryCatch;

defined('BASEPATH') or exit('No direct script access allowed');

class Consumer_model extends MY_OrderModel
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('process/Process_model');
		$this->process = $this->Process_model;
		// date_default_timezone_set('UTC');
	}

	function process_order($data_webhook)
	{
		try {

			$channel_data = $this->process->get_channel_data_by_shop_id($data_webhook->shop_id, 'get_order');

			if (!$channel_data) {
				throw new Exception('The configuration data has not been activated');
			}
			$app_key        = $channel_data->app_keys;
			$app_secret     = $channel_data->secret_keys;
			$access_token   = $channel_data->access_token;
			$path           = $channel_data->endpoint_url;
			$timestamp      = time();
			$host           = $channel_data->source_url;


			$param = array(
				"timestamp"     => $timestamp,
				"app_key"       => $app_key,
			);

			$sign = generate_signature_tiktok($path, $param, $app_secret);


			$fields_array = array(
				"order_id_list" => [$data_webhook->data->order_id]
			);

			$rawbody = trim(json_encode($fields_array));

			$param = array(
				"timestamp"     => $timestamp,
				"app_key"       => $app_key,
				"access_token"  => $access_token,
				'sign'          => $sign
			);

			$url     = create_url($host, $path, $param);
			$data    = post_request_curl($url, $rawbody);

			// echo '<pre>';
			// print_r($data);
			// die;

			if (!$data) {
				throw new Exception('Failed');
			}

			if ($data->code != 0) {
				throw new Exception($data->message);
			}

			$data_json = $data->data;

			$data = [];
			foreach ($data_json->order_list as $header) {

				$order_status  = $this->process->get_management_status($channel_data->source_id, $header->order_status, NULL, 'order');
				if (!$order_status) {
					throw new Exception('Order status not configured');
				}

				$data['users_ms_companys_id'] 	= $channel_data->users_ms_companys_id;
				$data['users_ms_channels_id'] 	= $channel_data->channel_id;
				$data['users_ms_warehouses_id'] = 1;
				$data['trx_number'] 			= "";
				$data['source_name'] 			=  $channel_data->source_name;
				$data['channel_name'] 			=  $channel_data->channel_name;

				$data['order_status_id'] 	= $order_status;
				$data['local_order_id'] 	= $header->order_id;
				$data['local_shop_id'] 		= $data_webhook->shop_id;
				$data['local_warehouse_id'] = $header->warehouse_id;
				$data['local_order_status'] = $data_webhook->data->order_status;


				$data['recipient_id'] 		= $header->buyer_uid;
				$data['recipient_name'] 	= $header->recipient_address->name;
				$data['recipient_phone'] 	= $header->recipient_address->phone;
				$data['recipient_email'] 	= isset($header->buyer_email) ? $header->buyer_email : "";
				$data['recipient_address_1'] = $header->recipient_address->address_line_list[0];
				$data['recipient_address_2'] = isset($header->recipient_address->address_line_list[1]) ? $header->recipient_address->address_line_list[1] : "";
				$data['recipient_city'] 	= $header->recipient_address->state;
				$data['recipient_country'] 	= $header->recipient_address->city;
				$data['recipient_province']	= $header->recipient_address->district;
				$data['recipient_district']	= $header->recipient_address->state;
				$data['recipient_zipcode']	= $header->recipient_address->zipcode;
				$data['recipient_full_address'] = $header->recipient_address->full_address;


				$data['shipping_price']			= $header->payment_info->shipping_fee;
				$data['shipping_disc_seller']	= $header->payment_info->shipping_fee_seller_discount;
				$data['shipping_disc_platform']	= $header->payment_info->shipping_fee_platform_discount;
				$data['shipping_awb']			= isset($header->tracking_number) ? $header->tracking_number : "";
				$data['shipping_provider_id']	= isset($header->shipping_provider_id) ? $header->shipping_provider_id : "";
				$data['shipping_provider_name']	= isset($header->shipping_provider) ? $header->shipping_provider : "";
				$data['shipping_provider_type']	= "";
				$data['shipping_description']	= "";

				$data['payment_method']		= $header->payment_method_name;
				$data['is_cashless']		= $header->is_cod ? 0 : 1;
				$data['is_cod']				= $header->is_cod;

				$data['voucher_code']		= "";
				$data['voucher_seller']		= "";
				$data['insurance_fee']		= "";
				$data['tax_price']			= $header->payment_info->taxes;
				$data['discount_amount']	= "";
				$data['discount_reason']	= "";
				$data['subtotal']			= $header->payment_info->sub_total;
				$data['total_price']		= $header->payment_info->total_amount;
				$data['note']				= $header->buyer_message;
				$data['error_code']			= 0;
				$data['error_message']		= "Success";
				$data['local_ordered_at']	= $header->create_time;
				$data['local_created_at']	= $header->create_time;
				$data['local_updated_at']	= $header->update_time;

				$data['item_list'] = [];
				foreach ($header->item_list as $detail) {
					$product_data = $this->process->get_product_by_sku($channel_data->users_ms_companys_id, $detail->seller_sku);

					$item = [];
					$item['users_ms_companys_id'] = $channel_data->users_ms_companys_id;
					$item['users_tr_orders_id'] = "";
					$item['local_order_id'] = $header->order_id;
					$item['local_item_id'] = "";
					$item['local_item_name'] = $detail->sku_name;
					$item['local_item_sku'] = $detail->seller_sku;
					$item['product_id'] = isset($product_data->product_id) ? $product_data->product_id : NULL;
					$item['product_name'] = isset($product_data->product_name) ? $product_data->product_name : NULL;
					$item['product_sku'] = isset($product_data->sku) ? $product_data->sku : NULL;
					$item['product_original_price'] = $detail->sku_original_price;
					$item['product_discount_price'] = $detail->sku_platform_discount_total;
					$item['quantity_purchased'] = $detail->quantity;
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
			}

			$process = $this->process->process_order($data);
			if ($process) {
				return true;
			} else {
				throw new Exception('Error process create order');
			}
		} catch (Exception $e) {
			return false;
		}
	}
}
