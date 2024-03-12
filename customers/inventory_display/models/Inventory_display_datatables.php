<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Inventory_display_datatables extends MY_ModelCustomer
{
	use MY_Tables;

	public function __construct()
	{
		$this->_tabel = $this->_table_users_ms_product_shadows;
		parent::__construct();
	}

    public function _getLookupValues()
	{
		$this->_ci->load->model('lookup_values/Lookup_values_model', 'lookup_values_model');
		return $this->_ci->lookup_values_model;
	}

    private function query($productID)
    {
        $where = "";
        if(!empty($this->input->post('search'))){
            $search = $this->input->post('search');
            $where = "AND ( a.sku LIKE '%{$search['value']}%'";
            $where .= "OR a.product_size LIKE '%{$search['value']}%' )";
        }

        $firstQuery = "SELECT 
                a.id,
                a.users_ms_products_id,
                a.sku,
                c.color_name as genaral_color,
                d.color_name as variant_color,
                a.product_size,
                b.status,
                (SELECT 
                        lookup_name
                    FROM
                        admins_ms_lookup_values
                    WHERE
                        lookup_config = 'products_status'
                            AND lookup_code = b.status) AS status_variant
            FROM
                users_ms_product_variants a
                    INNER JOIN
                users_ms_products b ON b.id = a.users_ms_products_id
                    LEFT JOIN 
                ms_color_name_hexa c ON c.id = a.general_color_id 
                    LEFT  JOIN 
                ms_color_name_hexa d ON d.id = a.variant_color_id
            WHERE
                users_ms_products_id = {$productID} AND a.users_ms_companys_id = {$this->_users_ms_companys_id} {$where} order by a.users_ms_products_id asc, a.sku asc";

        $where2 = "";
        if(!empty($this->input->post('search'))){
            $search = $this->input->post('search');
            $where2 = "AND ( a.sku LIKE '%{$search['value']}%'";
            $where2 .= "OR c.product_size LIKE '%{$search['value']}%' )";
        }

        $secondQuery = "SELECT 
                a.id,
                b.users_ms_product_shadows_id AS users_ms_products_id,
                a.sku,
                d.color_name AS genaral_color,
                e.color_name AS variant_color,
                c.product_size,
                b.status,
                (SELECT 
                        lookup_name
                    FROM
                        admins_ms_lookup_values
                    WHERE
                        lookup_config = 'products_status'
                            AND lookup_code = b.status) AS status_variant
            FROM
                users_ms_product_variant_shadows a
                    INNER JOIN
                users_ms_product_shadows b ON b.id = a.users_ms_product_shadows_id
                    INNER JOIN
                users_ms_product_variants c ON c.id = a.users_ms_product_variants_id
                    LEFT JOIN
                ms_color_name_hexa d ON d.id = c.general_color_id
                    LEFT JOIN
                ms_color_name_hexa e ON e.id = c.variant_color_id
            WHERE
                b.users_ms_products_id = {$productID} AND a.users_ms_companys_id = {$this->_users_ms_companys_id} {$where2} order by b.users_ms_product_shadows_id asc, a.sku asc";

        $length = "";

        if ( !empty($this->input->post('length')) && $this->input->post('length') != -1){
            $start = $this->input->post('start');
            $limit = $this->input->post('length');
            $length = "LIMIT {$start},{$limit}";
        }


        $query = "SELECT * FROM (({$firstQuery}) UNION ALL ({$secondQuery})) a ORDER BY a.users_ms_products_id asc, a.sku asc {$length}";
        
        return $this->db->query($query)->result();
    }

    private function count_all($productID)
    {
        $firstQuery = "SELECT count(id) as total FROM users_ms_product_variants where users_ms_products_id = {$productID} AND users_ms_companys_id = {$this->_users_ms_companys_id}";
        $query1 = $this->db->query($firstQuery)->row();
        $total1 = $query1->total;
        
        $secondQuery= "SELECT 
                count(a.id) as total
            FROM
                users_ms_product_variant_shadows a
                    INNER JOIN
                users_ms_product_shadows b ON b.id = a.users_ms_product_shadows_id
            WHERE b.users_ms_products_id = {$productID} AND a.users_ms_companys_id = {$this->_users_ms_companys_id}";

        $query2 = $this->db->query($secondQuery)->row();
        $total2 = $query2->total;

        return $total1 + $total2;
    }

    public function count_filtered($productID)
    {
        $where = "";
        if(!empty($this->input->post('search'))){
            $search = $this->input->post('search');
            $where = "AND ( sku LIKE '%{$search['value']}%'";
            $where .= "OR   product_size LIKE '%{$search['value']}%' )";
        }

        $firstQuery = "SELECT count(id) as total FROM users_ms_product_variants where users_ms_products_id = {$productID} AND users_ms_companys_id = {$this->_users_ms_companys_id} {$where}";
        $query1 = $this->db->query($firstQuery)->row();
        $total1 = $query1->total;

        $where2 = "";
        if(!empty($this->input->post('search'))){
            $search = $this->input->post('search');
            $where = "AND ( a.sku LIKE '%{$search['value']}%'";
            $where .= "OR c.product_size LIKE '%{$search['value']}%' )";
        }
        
        $secondQuery= "SELECT 
                count(a.id) as total
            FROM
                users_ms_product_variant_shadows a
                    INNER JOIN
                users_ms_product_shadows b ON b.id = a.users_ms_product_shadows_id
                    INNER JOIN 
                users_ms_product_variants c ON c.id = a.users_ms_product_variants_id
            WHERE b.users_ms_products_id = {$productID} AND a.users_ms_companys_id = {$this->_users_ms_companys_id} {$where2}";

        $query2 = $this->db->query($secondQuery)->row();
        $total2 = $query2->total;

        return $total1 + $total2;
    }

    public function show($productID)
    {
        $data = $this->query($productID);

        $output = array(
            "draw" => !empty($this->input->post('draw')) ? $this->input->post('draw') : 0,
            "recordsTotal" => $this->count_all($productID),
            "recordsFiltered" => $this->count_filtered($productID),
            "data" => $data,
        );

        return json_encode($output);


    }
}