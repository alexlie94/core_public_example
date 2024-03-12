<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Products extends MX_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->library('zend');
		$this->zend->load('Zend/Barcode');
	}

	public function index()
	{
		$this->output->enable_profiler(TRUE);
	}

	public function generate($text, $height = 30)
	{
		return Zend_Barcode::render('code128', 'image', array('text' => $text, 'barHeight' => $height), array());
	}

	public function generateqrcode($text, $modulesize)
	{
		$this->load->library("Qr/Qrcode");
		$this->qrcode->setDataString($text);
		$this->qrcode->setModuleSize($modulesize);
		$this->qrcode->initialize();
	}

	public function reader($sku)
	{
		//input your code in here
	}

	public function printlist()
	{
		$action = $this->input->get("action");
		$param  = explode(",", str_replace(array("[", "]"), "", $this->input->get("param")));

		$this->load->view("barcode_print", array("param" => $param, "action" => $action));
	}

	public function printlabel()
	{
		$param  = explode(",", str_replace(array("[", "]"), "", $this->input->get("param")));

		$paramExplode  = explode("|", $param[0]);

		$parameter[]     = array(
			"sku" 			=> $paramExplode[0],
			"brand" 		=> $paramExplode[1],
			"product" 		=> $paramExplode[2],
			"color" 		=> $paramExplode[3],
			"size" 			=> $paramExplode[4],
			"qty" 			=> $paramExplode[5]
		);

		$data_content['all_data']     = $parameter;
		$data_content['sizepx_uid']   = '95x95';
		$data_content['sizepx_sku']   = '67x67';

		$this->load->view("barcode_qr", $data_content);
	}
}
