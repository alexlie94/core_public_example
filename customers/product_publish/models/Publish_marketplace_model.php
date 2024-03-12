<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Publish_marketplace_model extends MY_ModelCustomer
{
	use MY_Tables;
	public function __construct()
	{
		parent::__construct();
	}

	public function get_channel($source)
	{
		$params 	=  json_decode(base64_decode($_POST['params']));
		$product_id = $params->product_id;
		$source_id 	= $this->db->where("source_name", $source)->limit(1)->get($this->_table_admins_ms_sources)->row()->id;
		$this->db->select('t0.users_ms_products_id
						  ,t0.admins_ms_sources_id
						  ,t0.users_ms_channels_id
						  ,t1.channel_name
						');
		$this->db->from("{$this->_table_users_ms_inventory_displays} t0");
		$this->db->join("{$this->_table_users_ms_channels} t1", "t1.id = t0.users_ms_channels_id");
		$this->db->where('t0.deleted_at is null', null, false);
		$this->db->where(["t0.{$this->_table_users_ms_companys}_id" => $this->_users_ms_companys_id]);
		$this->db->where('t0.users_ms_products_id', $product_id);
		$this->db->where('t0.admins_ms_sources_id', $source_id);

		$output = array(
			'list_channel' 	=> $this->db->get()->result_array(),
			'list_img'		=> $this->inv_display_detail($source),
			'list_product'	=> $this->product_by_sku()
		);
		return $output;
	}

	private function inv_display_detail($source)
	{
		$params 	= json_decode(base64_decode($_POST['params']));
		$product_id = $params->product_id;
		$source_id 	= $this->db->where("source_name", $source)->limit(1)->get($this->_table_admins_ms_sources)->row()->id;
		$this->db->select('t0.id
						  ,t0.users_ms_products_id
						  ,t0.users_ms_channels_id
						  ,t0.users_ms_inventory_displays_id
						  ,t1.image_name
						  ,t1.general_color_id
						  ,t1.variant_color_id
						');
		$this->db->from("{$this->_table_users_ms_inventory_display_details} t0");
		$this->db->where('t0.deleted_at is null', null, false);
		$this->db->where(["t0.{$this->_table_users_ms_companys}_id" => $this->_users_ms_companys_id]);
		$this->db->join("{$this->_table_products_images} t1", "t1.id = t0.users_ms_product_images_id");
		$this->db->where('t0.users_ms_products_id', $product_id);
		$this->db->where('t0.admins_ms_sources_id', $source_id);
		return $this->db->get()->result_array();
	}

	private function product_by_sku()
	{
		$params 	= json_decode(base64_decode($_POST['params']));
		$product_id = $params->product_id;
		$this->db->select(
			"   a.id as id,
                a.product_name,
                a.brand_name,
                a.category_name,
                b.sku ,
                b.product_size,
                b.variant_color_name,
                c.color_name,
                (   select lookup_name 
                    from admins_ms_lookup_values 
                    where lookup_code = a.status and lookup_config = 'products_status') as status_name,
                b.image_name,
                b.id as idVariants,
                DATE_FORMAT(a.created_at,'%d-%m-%Y') as created_at,
				IFNULL(d.qty,0) AS qty",
			false
		);
		$this->db->from("{$this->_table_products} a");
		$this->db->join("{$this->_table_products_variants} b", "b.users_ms_products_id = a.id", "inner");
		$this->db->join("ms_color_name_hexa c", "c.id = b.general_color_id", "inner");
		$this->db->join("{$this->_table_users_ms_inventory_storages} d", "d.users_ms_product_variants_id = b.id", "left");
		$this->db->where('a.deleted_at is null AND b.deleted_at is null ', null, false);
		$this->db->where(["a.{$this->_table_users_ms_companys}_id" => $this->_users_ms_companys_id]);
		$this->db->where('a.id', $product_id);
		$this->db->order_by('b.updated_at desc');
		return $this->db->get()->result_array();
	}
}
