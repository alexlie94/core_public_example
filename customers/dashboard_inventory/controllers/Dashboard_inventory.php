<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard_inventory extends MY_Customers
{

	public function __construct()
	{
		$this->_function_except = ['account', 'settings', 'show', 'process', 'status', 'paging'];
		parent::__construct();
	}

	public function index()
	{
		$this->template->title('Dashboard Inventory');
		$this->setTitlePage('Dashboard Inventory');
		$this->assetsBuild(['datatables']);
		$this->setJs('dashboard_inventory');
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
		$data['sumQty'] = $this->dashboard_inventory_model->sumStorageDefault();
		$data['UpdateInfo'] = $this->dashboard_inventory_model->update_info($currentDateTime);
		$this->template->build('v_dashboard', $data);
	}

	public function show()
	{
		echo $this->dashboard_inventory_model->show();
	}
}
