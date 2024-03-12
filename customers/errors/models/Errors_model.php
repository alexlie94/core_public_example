<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Errors_model extends MY_Model
{
    private $tabel = '';
    public function __construct()
    {
        $this->_tabel = $this->tabel;
        parent::__construct();
    }

}