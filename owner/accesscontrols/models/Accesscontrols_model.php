<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Accesscontrols_model extends MY_Model
{
    use MY_Tables;

    public function __construct()
    {
        $this->_tabel = $this->_table_admins_ms_accesscontrols;
        parent::__construct();
    }

}