<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Launching_group_details_model extends MY_ModelCustomer
{
    use MY_Tables;

    public function __construct()
    {
        $this->_tabel = 'users_ms_launching_group_details';
        parent::__construct();
    }
}