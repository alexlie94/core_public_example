<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Logout_model extends MY_Model
{
    use MY_Tables;
	public function __construct()
	{
		$this->_tabel = $this->_table_admins;
		parent::__construct();
	}

}
