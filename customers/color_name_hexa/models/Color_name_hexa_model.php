<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Color_name_hexa_model extends MY_Model
{
    use MY_Tables;

    public function __construct()
    {
        $this->_tabel = $this->_table_ms_color_name_hexa;
        parent::__construct();
    }
}