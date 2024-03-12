<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard_blibli extends MY_Customers
{

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$this->template->title('Dashboard Blibli');
		$this->setTitlePage('Dashboard Blibli');
		$this->setJs('dashboard_blibli');
		$this->template->build('v_dashboard');
	}
}
