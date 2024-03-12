<?php
defined('BASEPATH') or exit('No direct script access allowed');
require FCPATH . 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Dashboard_marketplace extends MY_Customers
{

	public function __construct()
	{
		$this->_function_except = ['account', 'settings', 'show', 'process', 'status', 'paging', 'upload_data', 'export_so', 'export_sales_order', 'show_sales_order', 'list_date_apex_chart', 'data_display', 'data_shadow', 'data_inventory_group', 'data_pending_action'];
		parent::__construct();
	}

	public function index()
	{
		$this->template->title('Dashboard Marketplace');
		$this->setTitlePage('Dashboard Marketplace');
		$this->assetsBuild(['datatables']);
		$this->setJs('dashboard_marketplace');
		$currentHour = date('H'); // Ambil jam saat ini dalam format 24 jam (00-23)
		if ($currentHour >= 0 && $currentHour < 9) {
			$greeting = "Good Morning";
		} elseif ($currentHour >= 9 && $currentHour < 12) {
			$greeting = "Good Day";
		} elseif ($currentHour >= 12 && $currentHour < 18) {
			$greeting = "Good Afternoon";
		} else {
			$greeting = "Good Evening";
		}

		$currentDateTime = date('Y-m-d H:i:s');
		$data['greeting'] = $greeting;
		$data['UpdateInfo'] = $this->dashboard_marketplace_model->update_info($currentDateTime);
		$data['sources'] = $this->dashboard_marketplace_model->getSource();
		$this->template->build('v_dashboard', $data);
	}

	public function show($date)
	{
		isAjaxRequestWithPost();
		$this->function_access('view');
		$data = $this->dashboard_marketplace_model->order_marketplace($date);
		echo json_encode($data);
	}

	public function show_sales_order($param)
	{
		isAjaxRequestWithPost();
		$this->function_access('view');
		$data = $this->dashboard_marketplace_model->sales_order_marketplace($param);
		$data2 = $this->list_date_apex_chart($param);
		$output = array(
			'data1' => $data,
			'data2' => $data2
		);
		echo json_encode($output);
	}

	public function export_so()
	{
		isAjaxRequestWithPost();

		$get['sources'] = $this->dashboard_marketplace_model->getSource();
		$get['warehouse'] = $this->dashboard_marketplace_model->getDataWarehouse();
		$get['status_so'] = $this->dashboard_marketplace_model->getSalesOrderStatus();

		$data = [
			'title_modal' => 'Export Data Sales Order',
			'url_form' => base_url() . 'dashboard_marketplace/export_sales_order',
			'content' => $this->load->view('v_form', $get, true),
			'buttonSave' => true,
			'buttonID' => 'btnExportSalesOrder',
			'buttonName' => 'Save',
			'buttonTypeSave' => 'redirect',
		];

		$html = $this->load->view($this->_v_modal, $data, true);

		echo json_encode(['html' => $html]);
		exit();
	}

	public function list_date_apex_chart($param)
	{
		$param_value = explode('to', $param);
		// Tanggal awal (start date)
		$start_date = $param_value[0];
		// Tanggal akhir (end date)
		$end_date = $param_value[1];
		// Mengonversi tanggal ke format timestamp
		$start_timestamp = strtotime($start_date);
		$end_timestamp = strtotime($end_date);
		// Menghitung selisih tanggal dalam detik
		$date_diff = $end_timestamp - $start_timestamp;
		// Mengonversi selisih detik ke jumlah hari
		$jumlah_hari = floor($date_diff / (60 * 60 * 24));
		switch ($jumlah_hari) {
			// case ($jumlah_hari > 7):
			// 	// Inisialisasi tanggal awal
			// 	$current_date = $start_timestamp;
			// 	$display_current_date = $start_timestamp;

			// 	$data_array_1 = array();
			// 	$data_array_2 = array();
			// 	// Looping menggunakan while
			// 	while ($current_date <= $end_timestamp) {
			// 		// Konversi timestamp ke tanggal dalam format yang diinginkan (misalnya, Y-m-d)
			// 		$current_date_formatted = date("Y-m-d", $current_date);
			// 		$date_param = $param . 'to' . $current_date_formatted;
			// 		// aambil data berdasarkan tanggal di model
			// 		$data_array_1[] = array(
			// 			'tanggal' => $current_date_formatted,
			// 			'sub_total' => $this->dashboard_marketplace_model->sales_order_marketplace_chart($date_param),
			// 		);

			// 		// Tambahkan satu hari ke tanggal saat ini
			// 		$current_date += 86400; // 86400 detik = 1 hari
			// 	}

			// 	while ($display_current_date <= $end_timestamp) {
			// 		$display_current_date_formatted = date("Y-m-d", $display_current_date);
			// 		$data_array_2[] = array(
			// 			'display_tanggal' => $display_current_date_formatted
			// 		);
			// 		$display_current_date += 86400 * 2;
			// 	}

			// 	$result = array(
			// 		'date_per_day' => $data_array_1,
			// 		'display_date' => $data_array_2
			// 	);

			// 	return $result;
			// 	break;

			default:
				// Inisialisasi tanggal awal
				$current_date = $start_timestamp;
				$display_current_date = $start_timestamp;

				$data_array_1 = array();
				$data_array_2 = array();
				// Looping menggunakan while
				while ($current_date <= $end_timestamp) {
					// Konversi timestamp ke tanggal dalam format yang diinginkan (misalnya, Y-m-d)
					$current_date_formatted = date("Y-m-d", $current_date);
					$date_param = $param . 'to' . $current_date_formatted;
					// aambil data berdasarkan tanggal di model
					$data_array_1[] = array(
						'tanggal' => $current_date_formatted,
						'sub_total' => $this->dashboard_marketplace_model->sales_order_marketplace_chart($date_param),
					);

					// Tambahkan satu hari ke tanggal saat ini
					$current_date += 86400; // 86400 detik = 1 hari
				}

				while ($display_current_date <= $end_timestamp) {
					$display_current_date_formatted = date("Y-m-d", $display_current_date);
					$data_array_2[] = array(
						'display_tanggal' => $display_current_date_formatted
					);
					$display_current_date += 86400;
				}

				$result = array(
					'date_per_day' => $data_array_1,
					'display_date' => $data_array_2
				);

				return $result;
				break;
		}
	}

	public function export_sales_order()
	{
		# code...
	}

	public function data_display()
	{
		isAjaxRequestWithPost();
		$this->function_access('view');
		$data = $this->dashboard_marketplace_model->get_data_inventory_display();
		echo json_encode($data);
	}

	public function data_shadow()
	{
		isAjaxRequestWithPost();
		$this->function_access('view');
		$data = $this->dashboard_marketplace_model->get_data_inventory_display_shadow();
		echo json_encode($data);
	}

	public function data_inventory_group()
	{
		isAjaxRequestWithPost();
		$this->function_access('view');
		$data = $this->dashboard_marketplace_model->get_data_inventory_group();
		echo json_encode($data);
	}

	public function data_pending_action()
	{
		isAjaxRequestWithPost();
		$this->function_access('view');
		$data = $this->dashboard_marketplace_model->get_data_pending_actions();
		echo json_encode($data);
	}
}