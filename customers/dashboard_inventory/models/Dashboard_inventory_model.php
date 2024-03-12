<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard_inventory_model extends MY_ModelCustomer
{
    use MY_Tables;

    public function __construct()
    {
        $this->_tabel = $this->_table_users_ms_product_bb_inventories;
        parent::__construct();
    }

    public function show($button = '')
    {
        $this->datatables->select(
            "a.id,
            b.product_name,
            b.supplier_name,
            b.brand_name,
            b.category_name,
            a.sku,
            a.quantity",
            false
        );
        $this->datatables->from("{$this->_tabel} a");
        $this->datatables->join("{$this->_table_products} b", "b.id = a.products_id", "inner");
        $this->datatables->where(["a.{$this->_table_users_ms_companys}_id" => $this->_users_ms_companys_id]);
        $this->datatables->where("a.deleted_at is null", null, false);
        $this->datatables->where("b.deleted_at is null", null, false);
        $this->datatables->order_by('a.updated_at desc');
        $fieldSearch = [
            "a.id",
            "b.product_name",
            "b.supplier_name",
            "b.brand_name",
            "b.category_name",
            "a.sku",
            "a.quantity",
        ];
        $this->_searchDefaultDatatables($fieldSearch);
        return $this->datatables->generate();
    }

    public function sumStorageDefault()
    {
        $this->db->select('sum(quantity) as total');
        $this->db->from($this->_table_users_ms_product_bb_inventories);
        $this->db->where('deleted_at IS NULL');
        $this->db->where(["{$this->_table_users_ms_companys}_id" => $this->_users_ms_companys_id]);
        return $this->db->get()->row_array();
    }

    public function update_info($currentDateTime)
    {
        $this->db->select(
            "   title,
                content,
                DATE_FORMAT(launch_date,'%d %M %Y') as launch_date,
                DATE_FORMAT(updated_at,'%d %M %Y') as updated_at,
                DATE_FORMAT(created_at,'%d %M %Y') as created_at",
            false
        );
        $this->db->from($this->_table_admins_ms_application_update);
        $this->db->where("launch_date < '{$currentDateTime}'");
        $this->db->where('deleted_at IS NULL');
        $this->db->where('status', 1);
        $this->db->order_by('launch_date desc');
        $this->db->limit(1);
        return $this->db->get()->row_array();
    }
}
