<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Accesscompany_model extends MY_Model
{
    use MY_Tables;

    public function __construct()
    {
        $this->_tabel = $this->_table_users_ms_access;
        parent::__construct();
    }
}