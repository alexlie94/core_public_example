<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Consumer extends MX_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->library('queue_tiktok');
	}

	public function index()
	{
		$this->output->enable_profiler(TRUE);
	}

	public function pull_order(){
		echo "Running";
		$this->queue_tiktok->pull_order_status();

	}
}