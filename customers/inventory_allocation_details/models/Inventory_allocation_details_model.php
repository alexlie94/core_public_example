<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Inventory_allocation_details_model extends MY_ModelCustomer
{
    use MY_Tables;

    public function __construct()
    {
        $this->_tabel = $this->_table_ms_inventory_allocation_detail;
        parent::__construct();
    }
}