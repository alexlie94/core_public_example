<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Owner {

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->template->title('Dashboard Admin');
        $this->setTitlePage('Dashboard');
        $this->template->build('v_dashboard');
    }
}