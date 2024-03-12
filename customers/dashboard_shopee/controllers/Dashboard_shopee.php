<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard_shopee extends MY_Customers
{

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$this->template->title('Dashboard Shopee');
		$this->setTitlePage('Dashboard Shopee');
		$this->setJs('dashboard_shopee');
		$this->template->build('v_dashboard');
	}
}
