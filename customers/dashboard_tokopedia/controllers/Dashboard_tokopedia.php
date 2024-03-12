<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard_tokopedia extends MY_Customers
{

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$this->template->title('Dashboard Tokopedia');
		$this->setTitlePage('Dashboard Tokopedia');
		$this->setJs('dashboard_tokopedia');
		$this->template->build('v_dashboard');
	}
}
