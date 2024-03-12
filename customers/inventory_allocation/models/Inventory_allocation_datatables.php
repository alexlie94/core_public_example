<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Inventory_allocation_datatables extends MY_ModelCustomer
{
    use MY_Tables;

    public function __construct()
    {
        $this->_tabel = $this->_table_products;
        parent::__construct();
    }

    public function getChannelSource($sendQuery = false)
    {
        $query = "SELECT 
                a.admins_ms_sources_id,
                a.id AS users_ms_channels_id,
                b.source_name,
                a.channel_name
            FROM
                {$this->_table_users_ms_channels} a
            INNER JOIN {$this->_table_admins_ms_sources} b ON b.id = a.{$this->_table_admins_ms_sources}_id
            WHERE a.{$this->_table_users_ms_companys}_id = {$this->_users_ms_companys_id} 
            AND a.deleted_at is null AND b.deleted_at is null AND a.status = 1 and b.status = 1 ORDER BY a.{$this->_table_admins_ms_sources}_id asc";

        if($sendQuery){
            $result = $query;
        }else{
            $result = $this->db->query($query)->result();
        }

        return $result; 
    }

    public function query($productID)
    {

        $forGrowCommerce = true;

        $queryChannel = $this->getChannelSource(true);

        $queryFirst = "SELECT 
                b.id AS users_ms_product_variants_id,
                b.sku,
                b.product_size,
                IFNULL(c.quantity, 0) AS quantity,
                d.users_ms_channels_id AS users_ms_channels_id_available,
                d.users_ms_channels_id AS users_ms_channels_id_reserved,
                IFNULL(f.available,0) as available,
                IFNULL(f.reserved,0) as reserved
            FROM
                users_ms_products a
                    INNER JOIN
                users_ms_product_variants b ON b.users_ms_products_id = a.id
                    LEFT JOIN
                users_ms_product_bb_inventories c ON c.products_id = a.id
                    AND c.product_variants_id = b.id
                    LEFT JOIN
                ({$queryChannel}) d ON 1 = 1
                    LEFT JOIN
                users_ms_inventory_allocations e ON e.admins_ms_sources_id = d.admins_ms_sources_id
                    AND e.users_ms_channels_id = d.users_ms_channels_id and e.users_ms_products_id = a.id
                    LEFT JOIN 
                users_ms_inventory_allocation_details f ON f.users_ms_inventory_allocations_id = e.id and f.users_ms_product_variants_id = b.id
            WHERE
                a.id = {$productID} AND a.users_ms_companys_id = {$this->_users_ms_companys_id}";


        $channel = $this->getChannelSource();

        $selectedQuerySecond = "";
        $selectedQueryThird = "";

        $available = 'a.available';
        if($forGrowCommerce){
            $available = 'a.quantity';
        }

        foreach($channel as $ky => $val){
            $selectedQuerySecond .= "CASE WHEN a.users_ms_channels_id_available = {$val->users_ms_channels_id} THEN {$available} END AS '{$val->admins_ms_sources_id}_{$val->users_ms_channels_id}_available',";
            $selectedQuerySecond .= "CASE WHEN a.users_ms_channels_id_reserved = {$val->users_ms_channels_id} THEN a.reserved END AS '{$val->admins_ms_sources_id}_{$val->users_ms_channels_id}_reserved',";
        
            $selectedQueryThird .= "SUM(a.{$val->admins_ms_sources_id}_{$val->users_ms_channels_id}_available) as {$val->admins_ms_sources_id}_{$val->users_ms_channels_id}_available,";
            $selectedQueryThird .= "SUM(a.{$val->admins_ms_sources_id}_{$val->users_ms_channels_id}_reserved) as {$val->admins_ms_sources_id}_{$val->users_ms_channels_id}_reserved,";
        }

        $selectedQuerySecond = trim($selectedQuerySecond,",");
        $selectedQueryThird = trim($selectedQueryThird,",");
        
        $querySecond = "SELECT 
                a.users_ms_product_variants_id,
                a.sku,
                a.product_size,
                a.quantity,
                {$selectedQuerySecond}
            FROM ({$queryFirst}) a";

        $queryThird = "SELECT 
                a.users_ms_product_variants_id,
                a.sku,
                a.product_size,
                a.quantity,
                {$selectedQueryThird}
            FROM ({$querySecond}) a GROUP BY a.users_ms_product_variants_id,a.sku,a.product_size,a.quantity";

        $result = $this->db->query($queryThird)->result();

        return $result;
    }

    public function getOfflineStore($sendQuery = false)
    {
        $query = "SELECT 
                id AS users_ms_offline_stores_id, offline_store_name
            FROM
                users_ms_offline_stores
            WHERE
                status = 1 AND deleted_at IS NULL
                    AND users_ms_companys_id = {$this->_users_ms_companys_id}
            ORDER BY id DESC";

        if($sendQuery){
            $result = $query;
        }else{
            $result = $this->db->query($query)->result();
        }

        return $result;
    }

    public function queryOffline($productID)
    {
        $queryOfflineStores = $this->getOfflineStore(true);

        $queryFirst = "SELECT 
                b.id AS users_ms_product_variants_id,
                b.sku,
                b.product_size,
                IFNULL(c.quantity, 0) AS quantity,
                d.users_ms_offline_stores_id,
                IFNULL(f.available,0) as available, 
                IFNULL(f.reserved,0) as reserved
            FROM
                users_ms_products a
                    INNER JOIN
                users_ms_product_variants b ON b.users_ms_products_id = a.id
                    LEFT JOIN
                users_ms_product_bb_inventories c ON c.products_id = a.id
                    AND c.product_variants_id = b.id
                    LEFT JOIN
                ({$queryOfflineStores}) d ON 1 = 1
                    LEFT JOIN
                users_ms_inventory_allocation_offlines e ON e.users_ms_offline_stores_id = d.users_ms_offline_stores_id
                    AND e.users_ms_products_id = a.id
                    LEFT JOIN
                users_ms_inventory_allocation_offline_details f ON f.users_ms_inventory_allocation_offlines_id = e.id
                    AND f.users_ms_product_variants_id = b.id
            WHERE
                a.id = {$productID} AND a.users_ms_companys_id = {$this->_users_ms_companys_id}";

        $offlineStore = $this->getOfflineStore();

        $selectedQuerySecond = "";
        $selectedQueryThird = "";

        foreach($offlineStore as $ky => $val){
            $selectedQuerySecond .= "CASE WHEN a.users_ms_offline_stores_id = {$val->users_ms_offline_stores_id} THEN a.reserved END as '{$val->users_ms_offline_stores_id}_reserved',";
            $selectedQueryThird .= "SUM(a.{$val->users_ms_offline_stores_id}_reserved) as {$val->users_ms_offline_stores_id}_reserved,";
        }

        $selectedQuerySecond = trim($selectedQuerySecond,",");
        $selectedQueryThird = trim($selectedQueryThird, ",");

        $querySecond = "SELECT 
            a.users_ms_product_variants_id,
            a.sku,
            a.product_size,
            a.quantity,
            {$selectedQuerySecond} 
            FROM ({$queryFirst}) a";

        $queryThird = "SELECT 
            a.users_ms_product_variants_id,
            a.sku,
            a.product_size,
            a.quantity,
            {$selectedQueryThird}
            FROM ({$querySecond}) a GROUP BY a.users_ms_product_variants_id,a.sku,a.product_size,a.quantity";

        $result = $this->db->query($queryThird)->result();

        return $result;
    }
}