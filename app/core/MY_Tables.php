<?php defined('BASEPATH') or exit('No direct script access allowed');

trait MY_Tables
{


    //table admin 
    public $_table_admins = 'admins';
    public $_table_admins_ms_access = 'admins_ms_access';
    public $_table_admins_ms_roles = 'admins_ms_roles';
    public $_table_admins_ms_role_accesscontrols = 'admins_ms_role_accesscontrols';
    public $_table_admins_ms_menus = 'admins_ms_menus';
    public $_table_admins_ms_accesscontrols = 'admins_ms_accesscontrols';
    public $_table_ms_lookup_values = 'admins_ms_lookup_values';
    public $_table_admins_ms_sources = 'admins_ms_sources';
    public $_table_admins_ms_provinces = 'admins_ms_provinces';
    public $_table_admins_ms_cities = 'admins_ms_cities';
    public $_table_admins_ms_endpoints = 'admins_ms_endpoints';
    public $_table_admins_ms_company_endpoints = 'admins_ms_company_endpoints';
    public $_table_admins_ms_application_update = 'admins_ms_application_update';
    public $_table_admins_config_cron = 'admins_config_cron';

    //table users 
    public $_table_users_ms_companys = 'users_ms_companys';
    public $_table_users_password_resets = 'users_password_resets';
    public $_table_users = 'users';
    public $_table_users_ms_access = 'users_ms_access';
    public $_table_users_ms_roles = 'users_ms_roles';
    public $_table_users_ms_role_accesscontrols = 'users_ms_role_accesscontrols';
    public $_table_users_ms_menus = 'users_ms_menus';
    public $_table_users_ms_accesscontrols = 'users_ms_accesscontrols';


    //table menu users
    public $_table_category = 'users_ms_categories';
    public $_table_ms_brands = 'users_ms_brands';
    public $_table_products = 'users_ms_products';
    public $_table_products_variants = 'users_ms_product_variants';
    public $_table_products_images = 'users_ms_product_images';
    public $_table_batchs = 'users_ms_batchs';
    public $_table_batchs_detail = 'users_ms_batchs_detail';
    public $_table_ms_suppliers = 'users_ms_suppliers';
    public $_table_ms_suppliers_brands = 'users_ms_supplier_brands';
    public $_table_ms_master_warehouse = 'users_ms_warehouses';
    public $_table_users_ms_inventory_requisition_headers = 'users_ms_inventory_requisition_headers';
    public $_table_users_ms_inventory_requisition_details = 'users_ms_inventory_requisition_details';
    public $_table_ms_inventory_warehouse_type1 = 'ms_inventory_warehouse_type1';
    public $_table_ms_inventory_warehouse_type2 = 'ms_inventory_warehouse_type2';
    public $_table_ms_inventory_warehouse_type3 = 'ms_inventory_warehouse_type3';
    public $_table_ms_inventory_group = 'users_ms_inventory_groups';
    public $_table_ms_inventory_group_detail = 'users_ms_inventory_group_product_details';
    public $_table_ms_purchase_order_detail = 'users_ms_purchase_order_details';
    public $_table_ms_inventory_allocation_product_ch = 'users_ms_inventory_allocation_product_channels';
    public $_table_ms_inventory_allocation_detail = 'users_ms_inventory_allocation_details';
    public $_table_ms_inventory_allocation = 'users_ms_inventory_allocations';
    public $_table_ms_inventory_allocation_history = 'users_ms_inventory_allocation_historys';
    public $_table_ms_inventory_display1 = 'users_ms_inventory_displays';
    public $_table_ms_inventory_display_status = 'users_ms_inventory_display_status';
    public $_table_ms_inventory_display_image = 'users_ms_inventory_display_images';
    public $_table_users_ms_inventory_storages = 'users_ms_inventory_storages';
    public $_table_users_ms_inventory_storages_logs = 'users_ms_inventory_storages_logs';
    public $_table_users_ms_ownership_types = 'users_ms_ownership_types';
    public $_table_users_ms_channels = 'users_ms_channels';
    public $_table_users_ms_product_bb_inventories = 'users_ms_product_bb_inventories';
    public $_table_users_ms_company_sources = 'users_ms_company_sources';
    public $_table_users_ms_authenticate_channels = 'users_ms_authenticate_channels';
    public $_table_users_ms_inventory_receiving = 'users_ms_inventory_receiving';
    public $_table_users_ms_inventory_putaway = 'users_ms_inventory_putaway';
    public $_table_users_ms_inventory_packing = 'users_ms_inventory_packing';
    public $_table_users_ms_inventory_picking = 'users_ms_inventory_picking';
    public $_table_users_ms_inventory_shipping = 'users_ms_inventory_shipping';
    public $_table_users_ms_inventory_receiving_logs = 'users_ms_inventory_receiving_logs';
    public $_table_users_ms_inventory_putaway_logs = 'users_ms_inventory_putaway_logs';
    public $_table_users_ms_inventory_picking_logs = 'users_ms_inventory_picking_logs';
    public $_table_users_ms_inventory_packing_logs = 'users_ms_inventory_packing_logs';
    public $_table_users_ms_inventory_shipping_logs = 'users_ms_inventory_shipping_logs';

    public $_table_users_tr_orders = 'users_tr_orders';
    public $_table_users_tr_order_details = 'users_tr_order_details';

    public $_table_users_ms_management_type = 'users_ms_management_type';
    public $_table_users_ms_matrix = 'users_ms_matrix';


    public $_table_ms_color = 'ms_color_name_hexa';

    public $_table_users_ms_inventory_display_defaults = 'users_ms_inventory_display_defaults';
    public $_table_users_ms_inventory_displays = 'users_ms_inventory_displays';
    public $_table_users_ms_inventory_display_details = 'users_ms_inventory_display_details';
    public $_table_ms_color_name_hexa = "ms_color_name_hexa";
    public $_table_users_ms_offline_stores = 'users_ms_offline_stores';
    public $_table_users_ms_inventory_allocation_offlines = 'users_ms_inventory_allocation_offlines';
    public $_table_users_ms_inventory_allocation_offline_details = 'users_ms_inventory_allocation_offline_details';
    public $_table_users_ms_product_shadows = 'users_ms_product_shadows';
    public $_table_users_ms_product_variant_shadows = 'users_ms_product_variant_shadows';
    public $_table_users_ms_inventory_display_default_shadows = 'users_ms_inventory_display_default_shadows';
    public $_table_users_ms_inventory_display_shadows = 'users_ms_inventory_display_shadows';
    public $_table_users_ms_inventory_display_detail_shadows = 'users_ms_inventory_display_detail_shadows';
    public $_table_users_ms_tito = 'users_ms_tito';
    public $_table_users_ms_tito_details = 'users_ms_tito_details';
}
