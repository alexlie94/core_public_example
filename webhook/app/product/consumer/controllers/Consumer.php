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
		echo 'muncul';
	}

	public function price_update_pull()
	{
		echo "Running";
		$this->queue->prices_pull();
	}

	public function single_publish_pull()
	{
		echo "Running";
		$this->queue->single_publish_pull();
	}
}
