<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Roleaccess_model extends MY_Model
{
    use MY_Tables;

    public function __construct()
    {
        $this->_tabel = $this->_table_admins_ms_role_accesscontrols;
        parent::__construct();
    }
}