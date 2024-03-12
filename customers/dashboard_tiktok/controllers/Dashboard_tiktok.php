<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard_tiktok extends MY_Customers
{

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$this->template->title('Dashboard Tiktok');
		$this->setTitlePage('Dashboard Tiktok');
		$this->setJs('dashboard_tiktok');
		$this->template->build('v_dashboard');
	}
}
