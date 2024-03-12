<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Products_variants_model extends MY_ModelCustomer
{
    use MY_Tables;
    public function __construct()
    {
        $this->_tabel = $this->_table_products_variants;
        parent::__construct();
    }
}
