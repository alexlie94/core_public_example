<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Product_shadow_model extends MY_ModelCustomer
{
	use MY_Tables;

	public function __construct()
	{
		$this->_tabel = $this->_table_users_ms_product_shadows;
		parent::__construct();
	}

	public function getLastBatch($productID)
	{
		$this->db->where(array('users_ms_companys_id' => $this->_users_ms_companys_id,'users_ms_products_id' => $productID));
		$this->db->order_by("id desc");
		$get = $this->db->get($this->_tabel)->row();
		return $get;
	}
}