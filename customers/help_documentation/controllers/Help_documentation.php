<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Help_documentation extends MY_Customers
{

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$this->template->title('Help Documentation');
		$this->setTitlePage('Help Documentation');
		$this->setJs('help_documentation');
		$this->template->build('v_show');
	}
}
