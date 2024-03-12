<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard_zalora extends MY_Customers
{

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$this->template->title('Dashboard Zalora');
		$this->setTitlePage('Dashboard Zalora');
		$this->setJs('dashboard_zalora');
		$this->template->build('v_dashboard');
	}
}
