<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Inventory_display_defaults_model extends MY_ModelCustomer
{
    use MY_Tables;

    public function __construct()
    {
        $this->_tabel = $this->_table_users_ms_inventory_display_defaults;
        parent::__construct();
    }
}