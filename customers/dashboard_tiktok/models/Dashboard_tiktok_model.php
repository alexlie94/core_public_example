<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard_tiktok_model extends MY_ModelCustomer
{
    use MY_Tables;

    public function __construct()
    {
        $this->_tabel = $this->_table_users;
        parent::__construct();
    }
}
