<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sales_orders_model extends MY_ModelCustomer
{
	use MY_Tables;

	public function __construct()
	{
		$this->_tabel = $this->_table_users_tr_orders;
		$this->_tabel_detail = $this->_table_users_tr_order_details;
		parent::__construct();
	}

	public function get_channel_by_source($source_id)
	{
		$query = $this->db->query("
			SELECT
				t1.id,
				t1.channel_name
			FROM users_ms_channels t1
			WHERE t1.users_ms_companys_id = {$this->_users_ms_companys_id}
			AND t1.admins_ms_sources_id = {$source_id}
			AND t1.status = 1
			AND t1.deleted_at IS NULL
			ORDER BY t1.id ASC
		");

		$result = $query->result();
		$data = [];
		foreach ($result as $row) {
			$data[] = [
				'id' => $row->id,
				'channel_name' => $row->channel_name,
				'channel_id_name' => strtolower(str_replace(' ', '_', $row->channel_name)),
			];
		}
		return $data;
	}

	public function get_channel_status($channel_id)
	{
		$query = $this->db->query("
			SELECT
				t1.id,
				t1.channel_name,
				t1.users_ms_companys_id,
				t1.admins_ms_sources_id
			FROM users_ms_channels t1
			WHERE t1.users_ms_companys_id = {$this->_users_ms_companys_id}
			AND t1.id = {$channel_id}
			AND t1.status = 1
			AND t1.deleted_at IS NULL
			ORDER BY t1.id ASC
		");

		$result = $query->result();

		$data = [];
		foreach ($result as $header) {
			$dataItem = [
				'id' => $header->id,
				'channel_name' => $header->channel_name,
				'channel_id_name' => strtolower(str_replace(' ', '_', $header->channel_name)),
				'status' => []
			];

			$statusQuery = $this->db->query("
					SELECT 'All' AS lookup_name, 
							88 AS lookup_code
					UNION
					
					SELECT 
						t1.lookup_name,
						t1.lookup_code
					FROM admins_ms_lookup_values t1
					WHERE t1.lookup_config = 'order_status'
						AND t1.deleted_at IS NULL
					
					UNION
					
					SELECT 'Failed' AS lookup_name,
							 99 AS lookup_code
					ORDER BY
						CASE
							WHEN lookup_name = 'All' THEN 1
							WHEN lookup_name = 'Failed' THEN 3
							ELSE 2
						END;
			");

			$statusResult = $statusQuery->result();


			foreach ($statusResult as $status) {
				$statusData = [
					'status_name' => str_replace('', '_', $status->lookup_name),
					'status_code' => $status->lookup_code
				];

				$dataItem['status'][] = $statusData;
			}

			$data[] = $dataItem;
		}
		return $data;
	}

	public function get_count_data_order($post)
	{

		// FILTER
		$filter = json_decode($post['filter']);


		$filter_text = "";

		if ($filter->input !== "") {
			switch ($filter->selected) {
				case "local_order_id":
					$filter_text = "AND t1.local_order_id LIKE '" . $filter->input . "%'";
					break;
				case "sku":
					$filter_text = "AND t1.id IN (SELECT users_tr_orders_id FROM users_tr_order_details WHERE local_item_sku LIKE '" . $filter->input . "%')";
					break;
			}
		}

		if ($filter->order_by === 'last_updated') {
			$order_by = "ORDER BY t1.local_updated_at DESC";
		} else {
			$order_by = "ORDER BY t1.local_updated_at ASC";
		}

		list($start, $end) = explode(' - ', $filter->date_range);
		$start_date = strtotime(date("Y-m-d 00:00:00", strtotime($start)));
		$end_date = strtotime(date("Y-m-d 23:59:59", strtotime($end)));
		$order_date = "AND local_updated_at >= {$start_date}  AND local_updated_at <= {$end_date} ";

		// END FILTER

		switch ($post['status']) {
			case 88:
				$status = "";
				break;
			case 99:
				$status = "";
				break;
			default:
				$status = "AND t1.order_status_id = {$post['status']}";
				break;
		}

		$query = $this->db->query("
								SELECT id
								FROM users_tr_orders t1
								WHERE t1.users_ms_companys_id = {$this->_users_ms_companys_id}
								AND t1.users_ms_channels_id = {$post['channel_id']}
								AND t1.deleted_at IS NULL
								{$status}
								{$filter_text}
								{$order_date}
								{$order_by}
							");

		$resultOrder = $query->num_rows();

		return $resultOrder;
	}

	public function get_data_order($post)
	{

		// FILTER
		$filter = json_decode($post['filter']);


		$filter_text = "";

		if ($filter->input !== "") {
			switch ($filter->selected) {
				case "local_order_id":
					$filter_text = "AND t1.local_order_id LIKE '" . $filter->input . "%'";
					break;
				case "sku":
					$filter_text = "AND t1.id IN (SELECT users_tr_orders_id FROM users_tr_order_details WHERE local_item_sku LIKE '" . $filter->input . "%')";
					break;
			}
		}

		if ($filter->order_by === 'last_updated') {
			$order_by = "ORDER BY t1.local_updated_at DESC";
		} else {
			$order_by = "ORDER BY t1.local_updated_at ASC";
		}


		list($start, $end) = explode(' - ', $filter->date_range);
		$start_date = strtotime(date("Y-m-d 00:00:00", strtotime($start)));
		$end_date = strtotime(date("Y-m-d 23:59:59", strtotime($end)));
		$order_date = "AND local_updated_at >= {$start_date} AND local_updated_at <= {$end_date}";

		// END FILTER

		switch ($post['status']) {
			case 88:
				$status = "";
				break;
			case 99:
				$status = "";
				break;
			default:
				$status = "AND t1.order_status_id = {$post['status']}";
				break;
		}

		$query = $this->db->query("
								SELECT 
									t1.id,
									t1.users_ms_companys_id,
									t1.admins_ms_sources_id,
									t1.users_ms_channels_id,
									t1.users_ms_warehouses_id,
									t1.source_name,
									t1.channel_name,
									t1.order_status_id,
									t1.local_order_id,
									t1.local_invoice_id,
									t1.local_shop_id,
									t1.local_warehouse_id,
									t1.local_order_status,
									t1.recipient_name,
									t1.shipping_provider_name,
									t1.subtotal,
									t1.total_price,
									t1.tracking_number,
									t1.pickup_selected,
									t1.note,
									t1.shipping_label_send,
									t1.shipping_label_status,
									t1.error_code,
									t1.error_message,
									t1.local_updated_at,
									t2.lookup_name AS status_order_name
								FROM users_tr_orders t1
								LEFT JOIN admins_ms_lookup_values t2 ON t2.lookup_code = t1.order_status_id
								WHERE t1.users_ms_companys_id = {$this->_users_ms_companys_id}
								AND t1.users_ms_channels_id = {$post['channel_id']}
								AND t2.lookup_config = 'order_status'
								AND t1.deleted_at IS NULL
								{$status}
								{$filter_text}
								{$order_date}
								{$order_by}
								LIMIT {$post['offset']},{$post['limit']}
							");

		$resultOrder = $query->result();


		foreach ($resultOrder as $order) {
			$queryDetail = $this->db->query("
				SELECT 
				t1.id,
				t1.users_ms_companys_id,
				t1.local_order_id,
				t1.local_item_id,
				t1.local_item_name,
				t1.local_item_sku,
				t1.local_image,
				t1.product_name,
				t1.product_sku,
				t1.product_original_price,
				t1.product_discount_price,
				t1.quantity_purchased,
				t1.local_note,
				t1.error_code,
				t1.error_message
				FROM users_tr_order_details t1
				WHERE t1.users_tr_orders_id = $order->id
			");

			$resultDetail = $queryDetail->result();
			$order->detail = $resultDetail;
		}
		return $resultOrder;
	}

	public function get_detail_order($id)
	{

		$query = $this->db->query("
								SELECT 
								t1.id,
								t1.users_ms_companys_id,
								t1.admins_ms_sources_id,
								t1.users_ms_channels_id,
								t1.users_ms_warehouses_id,
								t1.source_name,
								t1.channel_name,
								t1.order_status_id,
								t1.local_order_id,
								t1.local_invoice_id,
								t1.local_shop_id,
								t1.local_warehouse_id,
								t1.local_order_status,
								t1.recipient_name,
								t1.recipient_phone,
								t1.recipient_email,
								t1.recipient_full_address,
								t1.payment_method,
								t1.is_cod,
								t1.tax_price,
								t1.shipping_price,
								t1.shipping_provider_name,
								t1.tracking_info,
								t1.original_shipping_price,
								t1.shipping_discount_amount,
								t1.voucher_from_seller,
								t1.voucher_from_channel,
								t1.commission_fee,
								t1.subtotal,
								t1.total_price,
								t1.channel_total_price,
								t1.tracking_number,
								t1.note,
								t1.shipping_label_send,
								t1.shipping_label_status,
								t1.error_code,
								t1.error_message,
								t1.local_updated_at,
								t2.lookup_name AS status_order_name
								FROM users_tr_orders t1
								LEFT JOIN admins_ms_lookup_values t2 ON t2.lookup_code = t1.order_status_id
								WHERE t1.id = {$id} 
								AND t1.users_ms_companys_id = {$this->_users_ms_companys_id}
								AND t2.lookup_config = 'order_status'
								AND t1.deleted_at IS NULL
							");

		$order = $query->row_array();

		$queryDetail = $this->db->query("
			SELECT 
			t1.id,
				t1.users_ms_companys_id,
				t1.local_order_id,
				t1.local_item_id,
				t1.local_item_name,
				t1.local_item_sku,
				t1.local_image,
				t1.product_name,
				t1.product_sku,
				t1.product_original_price,
				t1.product_discount_price,
				t1.quantity_purchased,
				t1.local_note,
				t1.error_code,
				t1.error_message
			FROM users_tr_order_details t1
			WHERE t1.users_tr_orders_id = {$order['id']}
		");

		$resultDetail = $queryDetail->result();
		$order['detail'] = $resultDetail;

		$queryLog = $this->db->query("
			SELECT *
			FROM users_tr_order_logs t1
			WHERE t1.users_tr_orders_id = {$order['id']}
			ORDER BY local_updated_at DESC
		");

		$resultLog = $queryLog->result();
		$order['log'] = $resultLog;
		return $order;
	}
	public function get_all_data($source_id)
	{
		$query = $this->db->query("
			SELECT *
			FROM users_ms_channels t1
			WHERE t1.users_ms_companys_id = {$this->_users_ms_companys_id}
			AND t1.admins_ms_sources_id = {$source_id}
			AND t1.status = 1
			AND t1.deleted_at IS NULL
		");

		$result = $query->result();

		$data = [];
		foreach ($result as $header) {
			$dataItem = [
				'id' => $header->id,
				'channel_name' => $header->channel_name,
				'channel_id_name' => strtolower($header->channel_name),
				'status' => [] // Inisialisasi array status
			];

			$statusQuery = $this->db->query("
				SELECT *
				FROM admins_ms_lookup_values t1
				WHERE t1.lookup_config = 'order_status'
				AND t1.deleted_at IS NULL
			");

			$statusResult = $statusQuery->result();

			foreach ($statusResult as $status) {
				$query = $this->db->query("
											SELECT *
											FROM users_tr_orders t1
											WHERE t1.order_status_id = {$status->lookup_code}
											AND t1.users_ms_companys_id = {$header->users_ms_companys_id}
											AND t1.admins_ms_sources_id =  {$header->admins_ms_sources_id}
											AND t1.users_ms_channels_id = {$header->id}
											AND t1.deleted_at IS NULL
										");

				$resultOrder = $query->result();

				$statusData = [
					'status_name' => $status->lookup_name,
					'status_id_name' => strtolower($status->lookup_name),
					'data_order' => $resultOrder
				];

				foreach ($resultOrder as $order) {
					$queryDetail = $this->db->query("
						SELECT *
						FROM users_tr_order_details t1
						WHERE t1.users_tr_orders_id = $order->id
					");

					$resultDetail = $queryDetail->result();
					$order->detail = $resultDetail;
				}

				$dataItem['status'][] = $statusData;
			}

			$data[] = $dataItem; // Tambahkan dataItem ke array data
		}

		return $data;
	}
	public function get_channels()
	{
		$this->_ci->load->model('channels/channels_model', 'channels_model');
		return $this->_ci->channels_model;
	}

	public function show($button = '')
	{
		$this->datatables->select(
			"a.id as id,
            b.channel_name,
            a.local_id,
            a.status,
            a.customer_name,
			a.so_number,
			CONCAT('IDR. ', FORMAT(a.total_price, 0)) AS total_price,
            DATE_FORMAT(a.created_at_order, '%d %M %Y %H:%i') AS created_at_order",
			false,
		);

		$this->datatables->from("{$this->_tabel} a");
		$this->datatables->join("{$this->_table_users_ms_channels} b", "a.users_ms_channels_id = b.id", "left");

		$this->datatables->where('a.deleted_at is null', null, false);
		$this->datatables->where(["a.{$this->_table_users_ms_companys}_id" => $this->_users_ms_companys_id]);

		// $filters = !empty($this->input->post('filters')) ? $this->input->post('filters') : false;

		// if ($filters !== false && is_array($filters)) {
		//     foreach ($filters as $ky => $val) {
		//         $value = $val['value'];
		//         if (!empty($value)) {
		//             switch ($val['name']) {
		//                 case 'filter_brand_code':
		//                     $this->datatables->like('a.brand_code', $value);
		//                     break;
		//                 case 'filter_brand_name':
		//                     $this->datatables->like('a.brand_name', $value);
		//                     break;
		//             }
		//         }
		//     }
		// }

		$fieldSearch = [
			'b.channel_name',
			'a.status',
			'a.customer_name'

		];

		$this->_searchDefaultDatatables($fieldSearch);

		$this->datatables->order_by('a.id desc');
		// echo '<pre>';
		// print_r($button);
		// die;
		$this->datatables->add_column('action', $button, 'id');

		return $this->datatables->generate();
	}
	public function validate()
	{
		$this->form_validation->set_rules('order_date', 'Order Date', 'required');
		$this->form_validation->set_rules('channel', 'Channel', 'required');
		$this->form_validation->set_rules('customer_name', 'Customer Name', 'required');
		$this->form_validation->set_rules('customer_email', 'Customer Email', 'required|valid_email');
		$this->form_validation->set_rules('customer_phone', 'Customer Phone', 'required');
		$this->form_validation->set_rules('address_1', 'Address 1', 'required');
		$this->form_validation->set_rules('postcode', 'Postcode', 'required');
		$this->form_validation->set_rules('payment_method', 'Payment Method', 'required');
		$this->form_validation->set_rules('province', 'Province', 'required');
		$this->form_validation->set_rules('city', 'City', 'required');
		$this->form_validation->set_rules('shipping_price', 'Shipping Price', 'required');

		//  $detail = json_decode($this->input->post('detail'), true);

		//  if (is_array($detail)) {
		// 	$errors = array(); 
		// 	foreach ($detail as $index => $product) {
		// 		$field_name = 'detail[' . $index . ']["qty_input"]';

		// 		$this->form_validation->set_rules($field_name, 'Product ' . ($index + 1) . ' - Qty', 'required|integer');

		// 		if (form_error($field_name)) {
		// 			$errors[$field_name] = form_error($field_name);
		// 		}
		// 	}
		// }

		if ($this->form_validation->run() == FALSE) {
			$errors = array(
				'order_date' => form_error('order_date'),
				'channel' => form_error('channel'),
				'customer_name' => form_error('customer_name'),
				'customer_email' => form_error('customer_email'),
				'customer_phone' => form_error('customer_phone'),
				'address_1' => form_error('address_1'),
				'postcode' => form_error('postcode'),
				'payment_method' => form_error('payment_method'),
				'province' => form_error('province'),
				'city' => form_error('city'),
				'shipping_price' => form_error('shipping_price'),
			);


			$response = array(
				'success' => false,
				'message' => $errors
			);
		} else {

			$response = array(
				'success' => true,
			);
		}


		return $response;
	}

	function get_shop_id_by_channel($channel_id)
	{
		try {
			$query = $this->db->query("SELECT shop_id FROM users_ms_authenticate_channels WHERE channels_id = {$channel_id}");
			$result = $query->row();

			if ($result) {
				return $result;
			} else {
				throw new Exception();
			}
		} catch (Exception $e) {
			return false;
		}
	}
}
