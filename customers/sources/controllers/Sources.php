<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sources extends MY_Owner
{

	public function __construct()
	{
		$this->_function_except = ['getchannel'];
		parent::__construct();
	}
}
