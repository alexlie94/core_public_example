<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Inventory_group_defaults_model extends MY_ModelCustomer
{
    use MY_Tables;

    public function __construct()
    {
        $this->_tabel = 'users_ms_inventory_groups_defaults';
        parent::__construct();
    }
}