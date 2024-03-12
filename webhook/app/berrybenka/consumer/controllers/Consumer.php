<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Consumer extends MX_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->load->library('queue_berrybenka');
	}

	public function index()
	{
		echo 'muncul';
	}

	public function get_order()
	{
		echo "Running";
		$this->queue_berrybenka->pull_orders();
	}
}
