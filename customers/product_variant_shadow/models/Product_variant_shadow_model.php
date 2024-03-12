<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Product_variant_shadow_model extends MY_ModelCustomer
{
	use MY_Tables;

	public function __construct()
	{
		$this->_tabel = $this->_table_users_ms_product_variant_shadows;
		parent::__construct();
	}

	public function listSku($users_ms_product_shadows_id)
	{
		$query = "SELECT 
				d.users_ms_product_shadows_id,c.product_name,b.general_color_id,b.variant_color_id,a.sku
			FROM
				users_ms_product_variant_shadows a
				INNER JOIN users_ms_product_variants b ON b.id = a.users_ms_product_variants_id
				INNER JOIN users_ms_products c ON c.id = b.users_ms_products_id
				INNER JOIN users_ms_product_shadows d ON d.id = a.users_ms_product_shadows_id
			WHERE
				a.users_ms_product_shadows_id = {$users_ms_product_shadows_id} and a.users_ms_companys_id = {$this->_users_ms_companys_id}";

		return $this->db->query($query)->result();
	}
}