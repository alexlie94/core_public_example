<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Consumer extends MX_Controller
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

	public function get_order()
	{
		echo "Running";
		$this->queue->shopee_order_pull();
	}

	public function get_document_status()
	{
		echo "Running";
		$this->queue->shipping_document_status_pull();
	}
}
