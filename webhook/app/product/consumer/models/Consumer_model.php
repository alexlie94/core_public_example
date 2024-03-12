<?php

use PhpParser\Node\Stmt\TryCatch;

defined('BASEPATH') or exit('No direct script access allowed');

class Consumer_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function process_order($data)
    {
        try {
            //code...
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
