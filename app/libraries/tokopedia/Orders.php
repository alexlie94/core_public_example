<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Orders
{

	protected $ci;
	protected $helper;
	protected $queue;
	protected $process;
	protected $decrypt;
	protected $db;
	public function __construct()
	{
		$this->ci = &get_instance();
		$this->ci->load->library(array('queue', 'integrations/helper', 'integrations/process_orders', 'toped_decrypt'));
		$this->helper = $this->ci->helper;
		$this->process = $this->ci->process_orders;
		$this->queue = $this->ci->queue;
		$this->decrypt = $this->ci->toped_decrypt;
		$this->db = $this->ci->db;

		date_default_timezone_set('UTC');
	}

	function get_order_list($channel_id, $from_date, $to_date)
	{
		try {
			$integration = $this->helper->get_integration_data_by_channel_id($channel_id, 'order');

			if (!$integration['status']) {
				throw new Exception($integration['msg']);
			}
			$integration_data = $integration['data'];
			$data = [];

			$page = 1;
			$per_page = 10;

			$path =  $integration_data['host'] . '/v2/order/list';


			$header = array(
				'Authorization: Bearer ' . $integration_data['access_token']
			);

			while (true) {
				$params = array(
					'fs_id' => $integration_data['app_id'],
					'shop_id' => $integration_data['shop_id'],
					'from_date' => $from_date,
					'to_date' => $to_date,
					'page' => $page,
					'per_page' => $per_page,
				);

				$url = $path . '?' . http_build_query($params);

				$data = get_request_with_header_curl($url, $header);

				// if(isset($data) == 'invalid_access_token')
				if (!$data) {
					throw new Exception('Cannot return data from marketplace API');
				}

				if (isset($data->header->error_code)) {
					throw new Exception($data->header->messages . ' | ' . $data->header->reason);
				}

				foreach ($data->data as $row) {

					$json['fs_id'] =  $integration_data['app_id'];
					$json['order_id'] = $row->order_id;
					$json['order_status'] = $row->order_status;
					$json['invoice_ref_num'] = $row->invoice_ref_num;
					$json['shop_id'] = $row->shop_id;
					$this->queue->tokopedia_order_push(json_encode($json));
					echo "success queuing order = " . $row->order_id;
				}

				if (empty($data->data)) {
					break;
				}

				$page++;
			}
		} catch (Exception $e) {
			echo $e->getMessage();
			return false;
		}
	}
	function process_order($data_order, $show_error = 1)
	{
		// pre($data_order);
		try {
			$integration = $this->helper->get_integration_data_by_shop_id($data_order->shop_id, 'order');

			if (!$integration['status']) {
				throw new Exception($integration['msg']);
			}
			$integration_data = $integration['data'];
			$data = [];

			$get_detail_order = $this->get_detail_order($integration_data, $data_order);

			if (!$get_detail_order['status']) {
				throw new Exception($get_detail_order['msg']);
			}

			$order_detail = $get_detail_order['data'];

			$order_status = $this->helper->get_management_status($integration_data['source_id'], $order_detail->order_status, NULL, 'order');

			if (!$order_status) {
				throw new Exception('Order status not configured');
			}

			$order_detail->order_status_id = $order_status->status_id;
			$order_detail->local_order_status = $order_status->source_status_name;
			$order_detail->invoice_ref_num = $order_detail->invoice_number;

			$content = $order_detail->encryption->content;
			$secret = $order_detail->encryption->secret;
			$dect  = $this->decrypt->getContent($secret, $content);


			$order_detail->recipient_name = $dect->order_info->destination->receiver_name;
			$order_detail->recipient_phone = $dect->order_info->destination->receiver_phone;
			$order_detail->recipient_full_address = $dect->order_info->destination->address_street;

			$order_detail->buyer_name = $dect->buyer_info->buyer_fullname;
			$order_detail->buyer_phone = $dect->buyer_info->buyer_phone;
			// pre($order_detail);

			// pre($order_detail);
			$data = $this->create_order($integration_data, $order_detail);
			// pre($data);

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

	function get_detail_order($integration_data, $data_order)
	{

		try {

			$header = array(
				'Content-Type: application/json',
				'Authorization: Bearer ' . $integration_data['access_token'],
			);


			$path = "/v2/fs/" . $integration_data['app_id'] . "/order?order_id=" . $data_order->order_id;
			$url = $integration_data['host'] . $path;

			$data = get_request_with_header_curl($url, $header);
			// pre($data);
			if (!$data) {
				throw new Exception('Cannot return data from marketplace API');
			}

			if (isset($data->message)) {
				throw new Exception($data->message);
			}

			if (isset($data->header->error)) {
				throw new Exception($data->header->messages . ' | ' . $data->header->reason);
			}
			return ['status' => true, 'data' => $data->data];
		} catch (Exception $e) {
			return ['status' => false, 'msg' => $e->getMessage()];
		}
	}

	function create_order($integration_data, $data_order)
	{
		$data['users_ms_companys_id'] = $integration_data['company_id'];
		$data['admins_ms_sources_id'] = $integration_data['source_id'];
		$data['users_ms_channels_id'] = $integration_data['channel_id'];
		$data['users_ms_warehouses_id'] = 1;
		$data['source_name'] = $integration_data['source_name'];
		$data['channel_name'] = $integration_data['channel_name'];

		$data['order_status_id'] = $data_order->order_status_id;
		$data['local_order_id'] = $data_order->order_id;
		$data['local_invoice_id'] = $data_order->invoice_ref_num;
		$data['local_shop_id'] = $integration_data['shop_id'];
		$data['local_warehouse_id'] = isset($data_order->order_warehouse->warehouse_id) ? $data_order->order_warehouse->warehouse_id : null;
		$data['local_order_status'] = $data_order->order_status;

		// $data['buyer_id'] = $data_order->buyer_id;
		$data['buyer_name'] = $data_order->buyer_name;
		$data['buyer_phone'] = $data_order->buyer_phone;
		// $data['buyer_email'] = "";

		// $data['recipient_id'] = "";
		$data['recipient_name'] = $data_order->recipient_name;
		$data['recipient_phone'] = $data_order->recipient_phone;
		// $data['recipient_email'] = "";
		$data['recipient_address_1'] = $data_order->recipient_full_address;
		// $data['recipient_address_2'] = "";
		// $data['recipient_city'] = "";
		// $data['recipient_country'] = "";
		// $data['recipient_province'] = "";
		// $data['recipient_district'] = "";
		// $data['recipient_zipcode'] = "";
		$data['recipient_full_address'] = $data_order->recipient_full_address;


		$data['shipping_price'] = $data_order->order_info->shipping_info->shipping_price;
		$data['shipping_provider_id'] = isset($data_order->order_info->shipping_info->shipping_id) ? $data_order->order_info->shipping_info->shipping_id : 0;
		$data['shipping_provider_name'] = isset($data_order->order_info->shipping_info->logistic_name) ? $data_order->order_info->shipping_info->logistic_name : "";
		$data['shipping_provider_type'] = isset($data_order->order_info->shipping_info->logistic_service) ? $data_order->order_info->shipping_info->logistic_service : "";
		$data['shipping_description'] = "";

		$data['payment_method'] = $data_order->payment_info->gateway_name;
		$data['is_cashless'] = $data_order->order_info->shipping_info->isCashless;
		$data['is_cod'] = $data_order->order_info->shipping_info->isCashless ? 0 : 1;

		$data['voucher_code'] = $data_order->payment_info->voucher_code;
		$data['voucher_amount'] = $data_order->promo_order_detail->total_discount_product;
		$data['insurance_fee'] = $data_order->order_info->shipping_info->insurance_price;
		$data['tax_price'] = 0;
		$data['discount_amount'] = $data_order->promo_order_detail->total_discount;
		$data['discount_reason'] = "";
		$data['cashback_amount'] = $data_order->promo_order_detail->total_cashback;
		$data['shipping_discount_amount'] = $data_order->promo_order_detail->total_discount_shipping;
		$data['subtotal'] = $data_order->item_price;
		$data['total_price'] = $data_order->open_amt;
		$data['note'] = $data_order->comment;

		$data['package_list'] = null;
		$data['tracking_info_message'] = "";
		$data['tracking_info'] = json_encode($data_order->order_info->order_history);
		$data['pickup_message'] = "";
		$data['pickup_info'] = null;
		$data['tracking_number_message'] = "";
		$data['tracking_number'] = $data_order->order_info->shipping_info->awb;
		$data['error_code'] = 0;
		$data['error_message'] = "Success";
		$data['local_ordered_at'] = strtotime($data_order->create_time);
		$data['local_created_at'] = strtotime($data_order->create_time);
		$data['local_updated_at'] = strtotime($data_order->update_time);

		$data['item_list'] = [];
		foreach ($data_order->order_info->order_detail as $detail) {
			$product_data = $this->helper->get_product_by_sku($integration_data['company_id'], $detail->sku);
			if (isset($detail->detail_meta->value)) {
				$op_json = json_decode($detail->detail_meta->value, true);
				$original_price = $op_json['original_price'];
				$dc_price = $detail->product_price;
			} else {
				$original_price = $detail->normal_price;
				$dc_price = 0;
			}

			$item = [];
			$item['users_ms_companys_id'] = $integration_data['company_id'];
			$item['users_tr_orders_id'] = "";
			$item['local_order_id'] = $data_order->order_id;
			$item['local_item_id'] = $detail->product_id;
			$item['local_item_name'] = $detail->product_name;
			$item['local_item_sku'] = $detail->sku;
			$item['local_image'] = $detail->product_picture;
			$item['product_id'] = isset($product_data->product_id) ? $product_data->product_id : NULL;
			$item['product_name'] = isset($product_data->product_name) ? $product_data->product_name : NULL;
			$item['product_sku'] = isset($product_data->sku) ? $product_data->sku : NULL;
			$item['product_original_price'] = $original_price;
			$item['product_discount_price'] = $dc_price;
			$item['quantity_purchased'] = $detail->quantity;
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


	function get_shipping_label($order_id, $channel_id)
	{
		try {
			$integration = $this->helper->get_integration_data_by_channel_id($channel_id, 'order');

			if (!$integration['status']) {
				throw new Exception($integration['msg']);
			}
			$integration_data = $integration['data'];
			$data = [];
			$header = array(
				'Authorization: Bearer ' . $integration_data['access_token'],
			);


			$path = "/v1/order/" . $order_id . "/fs/" . $integration_data['app_id'] . "/shipping-label";
			$url = $integration_data['host'] . $path;

			$data = get_request_with_header_curl($url, $header, 0);
			if (!$data) {
				throw new Exception('Cannot return data from marketplace API');
			}

			if (isset(json_decode($data)->header->error_code)) {
				$res = json_decode($data);
				throw new Exception($res->header->messages . ' | ' . $res->header->reason);
			}
			// pre($data);
			return ['status' => true, 'data' => $data];
		} catch (Exception $e) {
			return ['status' => false, 'msg' => $e->getMessage()];
		}
	}

	function create_shipment_bkp($order_id, $channel_id)
	{
		try {


			$integration = $this->helper->get_integration_data_by_channel_id($channel_id, 'order');

			if (!$integration['status']) {
				throw new Exception($integration['msg']);
			}
			$integration_data = $integration['data'];
			$data = [];
			$header = array(
				'Authorization: Bearer ' . $integration_data['access_token'],
				'Content-Type: application/json',
			);

			$data = array(
				'order_id' => intVal($order_id),
				'shop_id' => intVal($integration_data['shop_id'])
			);

			$data_json = json_encode($data);
			$path = "/inventory/v1/fs/" . $integration_data['app_id'] . "/pick-up";
			$url = $integration_data['host'] . $path;
			// pre($data_json);
			$data = post_request_with_header_curl($url, $data_json, $header);
			// pre($data);
			if (!$data) {
				throw new Exception('Cannot return data from marketplace API');
			}

			if (isset($data->header->error_code)) {
				throw new Exception($data->header->messages . ' | ' . $data->header->reason);
			}
			// pre($data);
			return ['status' => true, 'data' => $data, 'msg' => $data->result];
		} catch (Exception $e) {
			return ['status' => false, 'msg' => $e->getMessage()];
		}
	}

	function create_shipment($order_id, $channel_id)
	{
		$this->db->trans_begin();
		try {

			$data_order = $this->helper->check_order_by_local_id($order_id);
			if (!$data_order) {
				throw new Exception('Order not found');
			}
			$data = array(
				'pickup_selected' => json_encode(['status' => 'oke']),
				'pickup_message' => 'success'
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
}
