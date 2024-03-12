<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Offline_stores_model extends MY_ModelCustomer
{
    use MY_Tables;

    public function __construct()
    {
        $this->_tabel = $this->_table_users_ms_offline_stores;
        parent::__construct();
    }
}