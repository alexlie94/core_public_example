<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard_marketplace_model extends MY_ModelCustomer
{
    use MY_Tables;

    public function __construct()
    {
        $this->_tabel = $this->_table_users;
        parent::__construct();
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

    public function order_marketplace($date)
    {
        $dateRange = explode('to', $date);
        $startDate = $dateRange[0];
        $endDate = $dateRange[1];
        $data = $this->db->query(
            "SELECT 
                channel_name,
                source_icon,
                pending_payment,
                open_orders,
                not_shipped,
                ready_to_ship,
                NULL AS sum_pending_payment,
                NULL AS sum_open_orders,
                NULL AS sum_not_shipped,
                NULL AS sum_ready_to_ship,
                NULL AS total_order
            FROM (
                SELECT 
                    b.channel_name,
                    c.source_icon,
                    SUM(CASE WHEN a.order_status_id = '1' THEN 1 ELSE 0 END) AS pending_payment, 
                    SUM(CASE WHEN a.order_status_id = '2' THEN 1 ELSE 0 END) AS open_orders, 
                    SUM(CASE WHEN a.order_status_id = '7' THEN 1 ELSE 0 END) AS not_shipped, 
                    SUM(CASE WHEN a.order_status_id = '3' THEN 1 ELSE 0 END) AS ready_to_ship
                FROM `{$this->_table_users_tr_orders}` `a` 
                LEFT JOIN `{$this->_table_users_ms_channels}` `b` ON `b`.`id` = `a`.`users_ms_channels_id` 
                LEFT JOIN `{$this->_table_admins_ms_sources}` `c` ON `c`.`id` = `b`.`admins_ms_sources_id` 
                WHERE `a`.`users_ms_companys_id` = '1'
                    AND `a`.`deleted_at` IS NULL 
                    AND `a`.`created_at` BETWEEN '{$startDate} 00:00:00' AND '{$endDate} 23:59:59'
                GROUP BY `b`.`id`
            ) AS subquery1
            
            UNION
            
            SELECT 
                NULL AS channel_name,
                NULL AS source_icon,
                NULL AS pending_payment,
                NULL AS open_orders,
                NULL AS not_shipped,
                NULL AS ready_to_ship,
                SUM(CASE WHEN yu.order_status_id = '1' THEN 1 ELSE 0 END) AS sum_pending_payment, 
                SUM(CASE WHEN yu.order_status_id = '2' THEN 1 ELSE 0 END) AS sum_open_orders, 
                SUM(CASE WHEN yu.order_status_id = '7' THEN 1 ELSE 0 END) AS sum_not_shipped, 
                SUM(CASE WHEN yu.order_status_id = '3' THEN 1 ELSE 0 END) AS sum_ready_to_ship, 
                SUM(CASE WHEN yu.users_ms_companys_id = 1 AND yu.deleted_at IS NULL THEN 1 ELSE 0 END) AS total_order
            FROM `{$this->_table_users_tr_orders}` `yu` 
            WHERE `yu`.`users_ms_companys_id` = '1'
            AND `yu`.`deleted_at` IS NULL 
            AND `yu`.`created_at` BETWEEN '{$startDate} 00:00:00' AND '{$endDate} 23:59:59'"
        )->result_array();

        return $data;
    }

    public function getSource()
    {
        try {
            $this->db->select('*');
            $this->db->from("{$this->_table_admins_ms_sources}");
            $this->db->where('deleted_at IS NULL');
            $this->db->where('status', 1);
            return $this->db->get()->result_array();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function getDataWarehouse()
    {
        $this->db->select('*');
        $this->db->from($this->_table_ms_master_warehouse);
        $this->db->where('deleted_at IS NULL');
        $this->db->where(["{$this->_table_users_ms_companys}_id" => $this->_users_ms_companys_id]);
        return $this->db->get()->result_array();
    }

    public function getSalesOrderStatus()
    {
        $this->db->select('*');
        $this->db->from($this->_table_ms_lookup_values);
        $this->db->where('deleted_at IS NULL');
        $this->db->where('lookup_config', 'order_status');
        return $this->db->get()->result_array();
    }

    public function sales_order_marketplace($param)
    {
        $param_value = explode('to', $param);
        $startDate = $param_value[0];
        $endDate = $param_value[1];
        $previous_day = $param_value[3];
        $source_id = $param_value[2];
        if ($source_id != 0) {
            $data = $this->db->query(
                "SELECT 
                    COUNT(*) as orders,
                    SUM(d.quantity_purchased) as items_sold,
                    SUM(a.subtotal) as sub_total,
                    NULL as sum_orders,
                    NULL as sum_items_sold,
                    NULL as sum_sub_total,
                    NULL as p_sum_orders,
                    NULL as p_sum_items_sold,
                    NULL as p_sum_sub_total,
                    c.source_icon,
                    b.channel_name
                FROM {$this->_table_users_tr_orders} a
                LEFT JOIN {$this->_table_users_ms_channels} b ON b.id = a.users_ms_channels_id
                LEFT JOIN {$this->_table_admins_ms_sources} c ON c.id = b.admins_ms_sources_id
                INNER JOIN users_tr_order_details d ON d.users_tr_orders_id = a.id
                WHERE a.users_ms_companys_id = '1' 
                AND a.deleted_at IS NULL
                AND a.source_name = c.source_name
                AND d.error_code = 0 
                AND a.order_status_id NOT IN (6, 7)
                AND c.id = {$source_id}
                AND a.created_at BETWEEN '{$startDate} 00:00:00' AND '{$endDate} 23:59:59'
                GROUP BY b.id

                UNION

                SELECT 
                    NULL as orders,
                    NULL as items_sold,
                    NULL as sub_total,
                    NULL as sum_orders,
                    NULL as sum_items_sold,
                    NULL as sum_sub_total,
                    COUNT(*) as p_sum_orders,
                    SUM(yud.quantity_purchased) as p_sum_items_sold,
                    SUM(yu.subtotal) as p_sum_sub_total,
                    NULL as source_icon,
                    NULL as source_name
                FROM {$this->_table_users_tr_orders} yu
                LEFT JOIN {$this->_table_users_ms_channels} bas ON bas.id = yu.users_ms_channels_id
                LEFT JOIN {$this->_table_admins_ms_sources} ko ON ko.id = bas.admins_ms_sources_id
                INNER JOIN {$this->_table_users_tr_order_details} yud ON yud.users_tr_orders_id = yu.id
                WHERE yu.users_ms_companys_id = '1' 
                AND yu.deleted_at IS NULL
                AND yud.error_code = 0
                AND yu.order_status_id NOT IN (6, 7)
                AND yu.created_at BETWEEN '{$startDate} 00:00:00' AND '{$previous_day} 23:59:59'
                AND ko.id = {$source_id}

                UNION

                SELECT 
                    NULL as orders,
                    NULL as items_sold,
                    NULL as sub_total,
                    COUNT(*) as sum_orders,
                    SUM(yud.quantity_purchased) as sum_items_sold,
                    SUM(yu.subtotal) as sum_sub_total,
                    NULL as p_sum_orders,
                    NULL as p_sum_items_sold,
                    NULL as p_sum_sub_total,
                    NULL as source_icon,
                    NULL as source_name
                FROM {$this->_table_users_tr_orders} yu
                LEFT JOIN {$this->_table_users_ms_channels} bas ON bas.id = yu.users_ms_channels_id
                LEFT JOIN {$this->_table_admins_ms_sources} ko ON ko.id = bas.admins_ms_sources_id
                INNER JOIN {$this->_table_users_tr_order_details} yud ON yud.users_tr_orders_id = yu.id
                WHERE yu.users_ms_companys_id = '1' 
                AND yu.deleted_at IS NULL
                AND yud.error_code = 0
                AND yu.order_status_id NOT IN (6, 7)
                AND yu.created_at BETWEEN '{$startDate} 00:00:00' AND '{$endDate} 23:59:59'
                AND ko.id = {$source_id}"
            )->result_array();
        } else {
            $data = $this->db->query(
                "SELECT 
                    COUNT(*) as orders,
                    SUM(d.quantity_purchased) as items_sold,
                    SUM(a.subtotal) as sub_total,
                    NULL as sum_orders,
                    NULL as sum_items_sold,
                    NULL as sum_sub_total,
                    NULL as p_sum_orders,
                    NULL as p_sum_items_sold,
                    NULL as p_sum_sub_total,
                    c.source_icon,
                    b.channel_name
                FROM {$this->_table_users_tr_orders} a
                LEFT JOIN {$this->_table_users_ms_channels} b ON b.id = a.users_ms_channels_id
                LEFT JOIN {$this->_table_admins_ms_sources} c ON c.id = b.admins_ms_sources_id
                INNER JOIN users_tr_order_details d ON d.users_tr_orders_id = a.id
                WHERE a.users_ms_companys_id = '1' 
                AND a.deleted_at IS NULL
                AND a.source_name = c.source_name
                AND d.error_code = 0 
                AND a.order_status_id NOT IN (6, 7)
                AND a.created_at BETWEEN '{$startDate} 00:00:00' AND '{$endDate} 23:59:59'
                GROUP BY b.id

                UNION

                SELECT 
                    NULL as orders,
                    NULL as items_sold,
                    NULL as sub_total,
                    NULL as sum_orders,
                    NULL as sum_items_sold,
                    NULL as sum_sub_total,
                    COUNT(*) as p_sum_orders,
                    SUM(yud.quantity_purchased) as p_sum_items_sold,
                    SUM(yu.subtotal) as p_sum_sub_total,
                    NULL as source_icon,
                    NULL as source_name
                FROM {$this->_table_users_tr_orders} yu
                LEFT JOIN {$this->_table_users_ms_channels} bas ON bas.id = yu.users_ms_channels_id
                LEFT JOIN {$this->_table_admins_ms_sources} ko ON ko.id = bas.admins_ms_sources_id
                INNER JOIN {$this->_table_users_tr_order_details} yud ON yud.users_tr_orders_id = yu.id
                WHERE yu.users_ms_companys_id = '1' 
                AND yu.deleted_at IS NULL
                AND yud.error_code = 0
                AND yu.order_status_id NOT IN (6, 7)
                AND yu.created_at BETWEEN '{$startDate} 00:00:00' AND '{$previous_day} 23:59:59'

                UNION

                SELECT 
                    NULL as orders,
                    NULL as items_sold,
                    NULL as sub_total,
                    COUNT(*) as sum_orders,
                    SUM(yud.quantity_purchased) as sum_items_sold,
                    SUM(yu.subtotal) as sum_sub_total,
                    NULL as p_sum_orders,
                    NULL as p_sum_items_sold,
                    NULL as p_sum_sub_total,
                    NULL as source_icon,
                    NULL as source_name
                FROM {$this->_table_users_tr_orders} yu
                LEFT JOIN {$this->_table_users_ms_channels} bas ON bas.id = yu.users_ms_channels_id
                LEFT JOIN {$this->_table_admins_ms_sources} ko ON ko.id = bas.admins_ms_sources_id
                INNER JOIN {$this->_table_users_tr_order_details} yud ON yud.users_tr_orders_id = yu.id
                WHERE yu.users_ms_companys_id = '1' 
                AND yu.deleted_at IS NULL
                AND yud.error_code = 0
                AND yu.order_status_id NOT IN (6, 7)
                AND yu.created_at BETWEEN '{$startDate} 00:00:00' AND '{$endDate} 23:59:59'"
            )->result_array();
        }

        return $data;
    }

    public function sales_order_marketplace_chart($param)
    {
        $param_value = explode('to', $param);
        $source_id = $param_value[2];
        $chart_date = $param_value[4];
        if ($source_id != 0) {
            $data = $this->db->query(
                "SELECT
                    SUM(yu.subtotal) as sum_sub_total
                FROM {$this->_table_users_tr_orders} yu
                LEFT JOIN {$this->_table_users_ms_channels} bas ON bas.id = yu.users_ms_channels_id
                LEFT JOIN {$this->_table_admins_ms_sources} ko ON ko.id = bas.admins_ms_sources_id
                INNER JOIN {$this->_table_users_tr_order_details} yud ON yud.users_tr_orders_id = yu.id
                WHERE yu.users_ms_companys_id = '1' 
                AND yu.deleted_at IS NULL
                AND yud.error_code = 0
                AND yu.order_status_id NOT IN (6, 7)
                AND yu.created_at BETWEEN '{$chart_date} 00:00:00' AND '{$chart_date} 23:59:59'
                AND ko.id = {$source_id}"
            )->row_array();
        } else {
            $data = $this->db->query(
                "SELECT 
                    SUM(yu.subtotal) as sum_sub_total
                FROM {$this->_table_users_tr_orders} yu
                LEFT JOIN {$this->_table_users_ms_channels} bas ON bas.id = yu.users_ms_channels_id
                LEFT JOIN {$this->_table_admins_ms_sources} ko ON ko.id = bas.admins_ms_sources_id
                INNER JOIN {$this->_table_users_tr_order_details} yud ON yud.users_tr_orders_id = yu.id
                WHERE yu.users_ms_companys_id = '1' 
                AND yu.deleted_at IS NULL
                AND yud.error_code = 0
                AND yu.order_status_id NOT IN (6, 7)
                AND yu.created_at BETWEEN '{$chart_date} 00:00:00' AND '{$chart_date} 23:59:59'"
            )->row_array();
        }

        return $data;
    }

    public function get_data_inventory_display()
    {
        $data = $this->db->query(
            "SELECT 
                COUNT(*) as all_inv_display,
                (SELECT COUNT(*) FROM users_ms_inventory_displays yu WHERE yu.users_ms_companys_id = '1' AND yu.deleted_at IS NULL AND yu.admins_ms_sources_id = c.id AND yu.display_status = 6) as live,
                (SELECT COUNT(*) FROM users_ms_inventory_displays yu WHERE yu.users_ms_companys_id = '1' AND yu.deleted_at IS NULL AND yu.admins_ms_sources_id = c.id AND yu.display_status = 3) as inactive,
                (SELECT COUNT(*) FROM users_ms_inventory_displays yu WHERE yu.users_ms_companys_id = '1' AND yu.deleted_at IS NULL AND yu.admins_ms_sources_id = c.id AND yu.display_status = 7 AND yu.display_status = 9) as pending_action,
                (SELECT sum(IF(yudha.qty < 20, 1, 0)) FROM users_ms_inventory_displays yu LEFT JOIN users_ms_products yud ON yud.id = yu.users_ms_products_id LEFT JOIN users_ms_product_variants yudh ON yudh.users_ms_products_id = yud.id LEFT JOIN users_ms_inventory_storages yudha ON yudha.users_ms_product_variants_id = yudh.id WHERE yu.users_ms_companys_id = '1' AND yu.deleted_at IS NULL AND yu.admins_ms_sources_id = c.id) as out_of_stock,
                NULL as all_sku,
                NULL as count_in_stock,
                NULL as count_of_stock,
                c.source_icon,
                b.channel_name
            FROM users_ms_inventory_displays a
            LEFT JOIN users_ms_channels b ON b.id = a.users_ms_channels_id
            LEFT JOIN admins_ms_sources c ON c.id = a.admins_ms_sources_id
            LEFT JOIN users_ms_inventory_display_details d ON d.users_ms_inventory_displays_id = a.id
            WHERE a.users_ms_companys_id = '1' 
            AND a.deleted_at IS NULL
            GROUP BY b.id
            
            UNION
            
            SELECT 
                NULL as all_inv_display,
                NULL as live,
                NULL as inactive,
                NULL as pending_action,
                NULL as out_of_stock,
                COUNT(xyz.id) as all_sku,
                SUM(IF(axyu.qty >= 10, 1, 0)) as count_in_stock,
                SUM(IF(axyu.qty <= 10, 1, 0)) as count_out_of_stock,
                NULL as source_icon,
                NULL as source_name
            FROM users_ms_inventory_displays xy
            LEFT JOIN users_ms_product_variants xyz ON xyz.users_ms_products_id = xy.users_ms_products_id
            LEFT JOIN users_ms_inventory_storages axyu ON axyu.users_ms_product_variants_id = xyz.id
            WHERE xy.users_ms_companys_id = '1' 
            AND xy.deleted_at IS NULL"
        )->result_array();
        return $data;
    }

    public function get_data_inventory_display_shadow()
    {
        $data = $this->db->query(
            "SELECT 
                COUNT(*) as all_inv_display,
                (SELECT COUNT(*) FROM users_ms_inventory_display_shadows yu WHERE yu.users_ms_companys_id = '1' AND yu.deleted_at IS NULL AND yu.admins_ms_sources_id = c.id AND yu.display_status = 6) as live,
                (SELECT COUNT(*) FROM users_ms_inventory_display_shadows yu WHERE yu.users_ms_companys_id = '1' AND yu.deleted_at IS NULL AND yu.admins_ms_sources_id = c.id AND yu.display_status = 3) as inactive,
                (SELECT COUNT(*) FROM users_ms_inventory_display_shadows yu WHERE yu.users_ms_companys_id = '1' AND yu.deleted_at IS NULL AND yu.admins_ms_sources_id = c.id AND yu.display_status = 7 AND yu.display_status = 9) as pending_action,
                (SELECT sum(IF(yudha.qty < 20, 1, 0)) FROM users_ms_inventory_display_shadows yu LEFT JOIN users_ms_product_variant_shadows yudh ON yudh.users_ms_product_shadows_id = yu.users_ms_product_shadows_id INNER JOIN users_ms_product_variants mm ON yudh.users_ms_product_variants_id = mm.id LEFT JOIN users_ms_inventory_storages yudha ON yudha.users_ms_product_variants_id = mm.id WHERE yu.users_ms_companys_id = '1' AND yu.deleted_at IS NULL AND yu.admins_ms_sources_id = c.id) as out_of_stock,
                NULL as all_sku,
                NULL as count_in_stock,
                NULL as count_out_of_stock,
                c.source_icon,
                b.channel_name
            FROM users_ms_inventory_display_shadows a
            LEFT JOIN users_ms_channels b ON b.id = a.users_ms_channels_id
            LEFT JOIN admins_ms_sources c ON c.id = a.admins_ms_sources_id
            WHERE a.users_ms_companys_id = '1' 
            AND a.deleted_at IS NULL
            GROUP BY b.id
            
            UNION
            
            SELECT 
                NULL as all_inv_display,
                NULL as live,
                NULL as inactive,
                NULL as pending_action,
                NULL as out_of_stock,
                COUNT(xyz.id) as all_sku,
                SUM(IF(axyu.qty >= 10, 1, 0)) as count_in_stock,
                SUM(IF(axyu.qty <= 10, 1, 0)) as count_out_of_stock,
                NULL as source_icon,
                NULL as source_name
            FROM users_ms_inventory_display_shadows xy
            LEFT JOIN users_ms_product_variant_shadows xyz ON xyz.users_ms_product_shadows_id = xy.users_ms_product_shadows_id
            INNER JOIN users_ms_product_variants mn ON xyz.users_ms_product_variants_id = mn.id
            LEFT JOIN users_ms_inventory_storages axyu ON axyu.users_ms_product_variants_id = mn.id
            WHERE xy.users_ms_companys_id = '1' 
            AND xy.deleted_at IS NULL"
        )->result_array();
        return $data;
    }

    public function get_data_inventory_group()
    {
        $data = $this->db->query(
            "SELECT 
            COUNT(*) as all_inv_group,
            (SELECT COUNT(*) FROM users_ms_launching_groups yu WHERE yu.users_ms_companys_id = '1' AND yu.deleted_at IS NULL AND yu.admins_ms_sources_id = c.id AND yu.display_status = 6) as live,
            (SELECT COUNT(*) FROM users_ms_launching_groups yu WHERE yu.users_ms_companys_id = '1' AND yu.deleted_at IS NULL AND yu.admins_ms_sources_id = c.id AND yu.display_status = 3) as inactive,
            (SELECT COUNT(*) FROM users_ms_launching_groups yu WHERE yu.users_ms_companys_id = '1' AND yu.deleted_at IS NULL AND yu.admins_ms_sources_id = c.id AND yu.display_status = 7 AND yu.display_status = 9) as pending_action,
            (SELECT sum(IF(yudha.qty < 20, 1, 0)) FROM users_ms_launching_groups yu LEFT JOIN users_ms_launching_group_details yudh ON yudh.users_ms_launching_groups_id = yu.id INNER JOIN users_ms_product_variants mm ON yudh.users_ms_products_id = mm.users_ms_products_id LEFT JOIN users_ms_inventory_storages yudha ON yudha.users_ms_product_variants_id = mm.id WHERE yu.users_ms_companys_id = '1' AND yu.deleted_at IS NULL AND yu.admins_ms_sources_id = c.id) as out_of_stock,
            NULL as all_sku,
            NULL as count_in_stock,
            NULL as count_out_of_stock,
            c.source_icon,
            b.channel_name
        FROM users_ms_launching_groups a
        LEFT JOIN users_ms_channels b ON b.id = a.users_ms_channels_id
        LEFT JOIN admins_ms_sources c ON c.id = a.admins_ms_sources_id
        WHERE a.users_ms_companys_id = '1' 
        AND a.deleted_at IS NULL
        GROUP BY b.id
        
        UNION
        
        SELECT 
            NULL as all_inv_group,
            NULL as live,
            NULL as inactive,
            NULL as pending_action,
            NULL as out_of_stock,
            COUNT(xyz.id) as all_sku,
            SUM(IF(axyu.qty >= 10, 1, 0)) as count_in_stock,
            SUM(IF(axyu.qty <= 10, 1, 0)) as count_out_of_stock,
            NULL as source_icon,
            NULL as source_name
        FROM users_ms_launching_group_details xy
        LEFT JOIN users_ms_product_variants xyz ON xyz.users_ms_products_id = xy.users_ms_products_id
        LEFT JOIN users_ms_inventory_storages axyu ON axyu.users_ms_product_variants_id = xyz.id
        WHERE xy.users_ms_companys_id = '1' 
        AND xy.deleted_at IS NULL"
        )->result_array();
        return $data;
    }

    public function get_data_pending_actions()
    {
        $data = $this->db->query(
            ""
        )->result_array();
        return $data;
    }
}
