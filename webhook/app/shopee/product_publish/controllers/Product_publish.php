<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Product_publish extends MX_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->library('queue');
	}

	public function index()
	{
		$this->output->enable_profiler(TRUE);
	}

	public function push_product()
	{
		echo "Running";
		$this->queue->shopee_product_publish_pull();
	}
}