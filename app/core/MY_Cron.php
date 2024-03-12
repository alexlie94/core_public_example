<?php
defined('BASEPATH') or exit('No direct script access allowed');
class MY_Cron extends MX_Controller
{
	protected $_ci;
	protected $_cron_access;

	public function __construct()
	{
		$this->_ci = &get_instance();
		parent::__construct();

		// $this->_cron_access();
	}

	function _cron_access()
	{
        $currentURL = $this->uri->segment(1);
		echo $currentURL;
	}



}
