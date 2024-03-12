<?php

use PhpParser\Node\Stmt\Switch_;

use function PHPUnit\Framework\isEmpty;

defined('BASEPATH') or exit('No direct script access allowed');

class Inventory_warehouse_model extends MY_ModelCustomer
{
    use MY_Tables;

    public function __construct()
    {
        $this->_tabel = $this->_table_users;
        $this->_tabel = $this->_table_ms_inventory_warehouse_type1;
        parent::__construct();
    }

    public function getDataWarehouse($getData = '')
    {
        $this->db->select('*');
        $this->db->from($this->_table_ms_master_warehouse);
        $this->db->where('deleted_at IS NULL');
        $this->db->where(["{$this->_table_users_ms_companys}_id" => $this->_users_ms_companys_id]);

        if (!empty($getData)) {
            $this->db->where('id', $getData);
            return $this->db->get()->row_array();
        } else {
            return $this->db->get()->result_array();
        }
    }

    public function lastUpdateStorage($getData = '')
    {
        $this->db->select('updated_at');
        $this->db->from($this->_table_users_ms_inventory_storages);
        $this->db->order_by('updated_at desc');
        // $this->db->where('deleted_at IS NULL');
        $this->db->where(["{$this->_table_users_ms_companys}_id" => $this->_users_ms_companys_id]);
        $this->db->limit(1);
        return $this->db->get()->row_array();
    }

    public function lastUpdateReceiving($getData = '')
    {
        $this->db->select('updated_at');
        $this->db->from($this->_table_users_ms_inventory_receiving);
        $this->db->order_by('updated_at desc');
        // $this->db->where('deleted_at IS NULL');
        $this->db->where(["{$this->_table_users_ms_companys}_id" => $this->_users_ms_companys_id]);
        $this->db->limit(1);
        return $this->db->get()->row_array();
    }

    public function lastUpdatePutaway($getData = '')
    {
        $this->db->select('updated_at');
        $this->db->from($this->_table_users_ms_inventory_putaway);
        $this->db->order_by('updated_at desc');
        // $this->db->where('deleted_at IS NULL');
        $this->db->where(["{$this->_table_users_ms_companys}_id" => $this->_users_ms_companys_id]);
        $this->db->limit(1);
        return $this->db->get()->row_array();
    }

    public function lastUpdatePacking($getData = '')
    {
        $this->db->select('updated_at');
        $this->db->from($this->_table_users_ms_inventory_packing);
        $this->db->order_by('updated_at desc');
        // $this->db->where('deleted_at IS NULL');
        $this->db->where(["{$this->_table_users_ms_companys}_id" => $this->_users_ms_companys_id]);
        $this->db->limit(1);
        return $this->db->get()->row_array();
    }

    public function lastUpdatePicking($getData = '')
    {
        $this->db->select('updated_at');
        $this->db->from($this->_table_users_ms_inventory_picking);
        $this->db->order_by('updated_at desc');
        // $this->db->where('deleted_at IS NULL');
        $this->db->where(["{$this->_table_users_ms_companys}_id" => $this->_users_ms_companys_id]);
        $this->db->limit(1);
        return $this->db->get()->row_array();
    }

    public function lastUpdateShipping($getData = '')
    {
        $this->db->select('updated_at');
        $this->db->from($this->_table_users_ms_inventory_shipping);
        $this->db->order_by('updated_at desc');
        // $this->db->where('deleted_at IS NULL');
        $this->db->where(["{$this->_table_users_ms_companys}_id" => $this->_users_ms_companys_id]);
        $this->db->limit(1);
        return $this->db->get()->row_array();
    }

    public function sumStorageDefault()
    {
        $this->db->select('sum(qty) as total');
        $this->db->from($this->_table_users_ms_inventory_storages);
        $this->db->where('deleted_at IS NULL');
        $this->db->where(["{$this->_table_users_ms_companys}_id" => $this->_users_ms_companys_id]);
        return $this->db->get()->row_array();
    }

    public function sumReceivingInproDefault()
    {
        $this->db->select('sum(qty) as total');
        $this->db->from($this->_table_users_ms_inventory_receiving);
        $this->db->where('deleted_at IS NULL');
        $this->db->where('status', 1);
        $this->db->where(["{$this->_table_users_ms_companys}_id" => $this->_users_ms_companys_id]);
        return $this->db->get()->row_array();
    }

    public function sumReceivingClosedDefault()
    {
        $this->db->select('sum(qty) as total');
        $this->db->from($this->_table_users_ms_inventory_receiving);
        $this->db->where('deleted_at IS NULL');
        $this->db->where('status', 2);
        $this->db->where(["{$this->_table_users_ms_companys}_id" => $this->_users_ms_companys_id]);
        return $this->db->get()->row_array();
    }

    public function sumPutawayInproDefault()
    {
        $this->db->select('sum(qty) as total');
        $this->db->from($this->_table_users_ms_inventory_putaway);
        $this->db->where('deleted_at IS NULL');
        $this->db->where('status', 1);
        $this->db->where(["{$this->_table_users_ms_companys}_id" => $this->_users_ms_companys_id]);
        return $this->db->get()->row_array();
    }

    public function sumPutawayClosedDefault()
    {
        $this->db->select('sum(qty) as total');
        $this->db->from($this->_table_users_ms_inventory_putaway);
        $this->db->where('deleted_at IS NULL');
        $this->db->where('status', 2);
        $this->db->where(["{$this->_table_users_ms_companys}_id" => $this->_users_ms_companys_id]);
        return $this->db->get()->row_array();
    }

    public function sumPackingInproDefault()
    {
        $this->db->select('sum(qty) as total');
        $this->db->from($this->_table_users_ms_inventory_packing);
        $this->db->where('deleted_at IS NULL');
        $this->db->where('status', 1);
        $this->db->where(["{$this->_table_users_ms_companys}_id" => $this->_users_ms_companys_id]);
        return $this->db->get()->row_array();
    }

    public function sumPackingClosedDefault()
    {
        $this->db->select('sum(qty) as total');
        $this->db->from($this->_table_users_ms_inventory_packing);
        $this->db->where('deleted_at IS NULL');
        $this->db->where('status', 2);
        $this->db->where(["{$this->_table_users_ms_companys}_id" => $this->_users_ms_companys_id]);
        return $this->db->get()->row_array();
    }

    public function sumPickingInproDefault()
    {
        $this->db->select('sum(qty) as total');
        $this->db->from($this->_table_users_ms_inventory_picking);
        $this->db->where('deleted_at IS NULL');
        $this->db->where('status', 1);
        $this->db->where(["{$this->_table_users_ms_companys}_id" => $this->_users_ms_companys_id]);
        return $this->db->get()->row_array();
    }

    public function sumPickingClosedDefault()
    {
        $this->db->select('sum(qty) as total');
        $this->db->from($this->_table_users_ms_inventory_picking);
        $this->db->where('deleted_at IS NULL');
        $this->db->where('status', 2);
        $this->db->where(["{$this->_table_users_ms_companys}_id" => $this->_users_ms_companys_id]);
        return $this->db->get()->row_array();
    }

    public function sumShippingInproDefault()
    {
        $this->db->select('sum(qty) as total');
        $this->db->from($this->_table_users_ms_inventory_shipping);
        $this->db->where('deleted_at IS NULL');
        $this->db->where('status', 1);
        $this->db->where(["{$this->_table_users_ms_companys}_id" => $this->_users_ms_companys_id]);
        return $this->db->get()->row_array();
    }

    public function sumShippingClosedDefault()
    {
        $this->db->select('sum(qty) as total');
        $this->db->from($this->_table_users_ms_inventory_shipping);
        $this->db->where('deleted_at IS NULL');
        $this->db->where('status', 2);
        $this->db->where(["{$this->_table_users_ms_companys}_id" => $this->_users_ms_companys_id]);
        return $this->db->get()->row_array();
    }

    public function sumStorage($id)
    {
        $this->db->select('sum(qty) as total');
        $this->db->from($this->_table_users_ms_inventory_storages);
        $this->db->where('deleted_at IS NULL');
        $this->db->where('users_ms_warehouses_id', $id);
        $this->db->where(["{$this->_table_users_ms_companys}_id" => $this->_users_ms_companys_id]);
        return $this->db->get()->row_array();
    }

    public function sumReceivingInpro($id)
    {
        $this->db->select('sum(qty) as total');
        $this->db->from($this->_table_users_ms_inventory_receiving);
        $this->db->where('deleted_at IS NULL');
        $this->db->where('status', 1);
        $this->db->where('users_ms_warehouses_id', $id);
        $this->db->where(["{$this->_table_users_ms_companys}_id" => $this->_users_ms_companys_id]);
        return $this->db->get()->row_array();
    }

    public function sumReceivingClosed($id)
    {
        $this->db->select('sum(qty) as total');
        $this->db->from($this->_table_users_ms_inventory_receiving);
        $this->db->where('deleted_at IS NULL');
        $this->db->where('status', 2);
        $this->db->where('users_ms_warehouses_id', $id);
        $this->db->where(["{$this->_table_users_ms_companys}_id" => $this->_users_ms_companys_id]);
        return $this->db->get()->row_array();
    }

    public function sumPutawayInpro($id)
    {
        $this->db->select('sum(qty) as total');
        $this->db->from($this->_table_users_ms_inventory_putaway);
        $this->db->where('deleted_at IS NULL');
        $this->db->where('status', 1);
        $this->db->where('users_ms_warehouses_id', $id);
        $this->db->where(["{$this->_table_users_ms_companys}_id" => $this->_users_ms_companys_id]);
        return $this->db->get()->row_array();
    }

    public function sumPutawayClosed($id)
    {
        $this->db->select('sum(qty) as total');
        $this->db->from($this->_table_users_ms_inventory_putaway);
        $this->db->where('deleted_at IS NULL');
        $this->db->where('status', 2);
        $this->db->where('users_ms_warehouses_id', $id);
        $this->db->where(["{$this->_table_users_ms_companys}_id" => $this->_users_ms_companys_id]);
        return $this->db->get()->row_array();
    }

    public function sumPackingInpro($id)
    {
        $this->db->select('sum(qty) as total');
        $this->db->from($this->_table_users_ms_inventory_packing);
        $this->db->where('deleted_at IS NULL');
        $this->db->where('status', 1);
        $this->db->where('users_ms_warehouses_id', $id);
        $this->db->where(["{$this->_table_users_ms_companys}_id" => $this->_users_ms_companys_id]);
        return $this->db->get()->row_array();
    }

    public function sumPackingClosed($id)
    {
        $this->db->select('sum(qty) as total');
        $this->db->from($this->_table_users_ms_inventory_packing);
        $this->db->where('deleted_at IS NULL');
        $this->db->where('status', 2);
        $this->db->where('users_ms_warehouses_id', $id);
        $this->db->where(["{$this->_table_users_ms_companys}_id" => $this->_users_ms_companys_id]);
        return $this->db->get()->row_array();
    }

    public function sumPickingInpro($id)
    {
        $this->db->select('sum(qty) as total');
        $this->db->from($this->_table_users_ms_inventory_picking);
        $this->db->where('deleted_at IS NULL');
        $this->db->where('status', 1);
        $this->db->where('users_ms_warehouses_id', $id);
        $this->db->where(["{$this->_table_users_ms_companys}_id" => $this->_users_ms_companys_id]);
        return $this->db->get()->row_array();
    }

    public function sumPickingClosed($id)
    {
        $this->db->select('sum(qty) as total');
        $this->db->from($this->_table_users_ms_inventory_picking);
        $this->db->where('deleted_at IS NULL');
        $this->db->where('status', 2);
        $this->db->where('users_ms_warehouses_id', $id);
        $this->db->where(["{$this->_table_users_ms_companys}_id" => $this->_users_ms_companys_id]);
        return $this->db->get()->row_array();
    }

    public function sumShippingInpro($id)
    {
        $this->db->select('sum(qty) as total');
        $this->db->from($this->_table_users_ms_inventory_shipping);
        $this->db->where('deleted_at IS NULL');
        $this->db->where('status', 1);
        $this->db->where('users_ms_warehouses_id', $id);
        $this->db->where(["{$this->_table_users_ms_companys}_id" => $this->_users_ms_companys_id]);
        return $this->db->get()->row_array();
    }

    public function sumShippingClosed($id)
    {
        $this->db->select('sum(qty) as total');
        $this->db->from($this->_table_users_ms_inventory_shipping);
        $this->db->where('deleted_at IS NULL');
        $this->db->where('status', 2);
        $this->db->where('users_ms_warehouses_id', $id);
        $this->db->where(["{$this->_table_users_ms_companys}_id" => $this->_users_ms_companys_id]);
        return $this->db->get()->row_array();
    }

    public function show_receiving()
    {
        $this->datatables->select(
            "a.id as id,
            a.po_number,
            a.brand_name,
            a.supplier_name,
            a.publisher_name,
            a.created_at,
            a.qty,
            a.qty_receiving,
            b.lookup_name,
            a.users_ms_warehouses_id,",
            false,
        );

        $filters = !empty($this->input->post('filters')) ? $this->input->post('filters') : false;
        if ($filters !== false && is_array($filters)) {
            $searchX = [];
            $searchY = [];
            foreach ($filters as $ky => $val) {
                $value = $val['value'];
                if (!empty($value)) {
                    switch ($val['name']) {
                        case 'warehouse_id':
                            if ($value != 0) {
                                $this->datatables->where('a.users_ms_warehouses_id', $value);
                            }
                            break;
                        case 'status_id':
                            $this->datatables->where('a.status', $value);
                            break;
                        case 'search_by':
                            $field = $value;
                            $searchX['field'] = isset($field) ? $field : "";
                            break;
                        case 'search_by1':
                            $field = $value;
                            $searchX['value'] = isset($field) ? $field : "";
                            break;
                        case 'from_val':
                            $from_val = $value;
                            $searchY['from_val'] = isset($from_val) ? $from_val : "";
                            break;
                        case 'to_val':
                            $to_val = $value;
                            $searchY['to_val'] = isset($to_val) ? $to_val : "";
                            break;
                    }
                }
            }
            if (!empty($searchX)) {
                if (isset($searchX['value'])) {
                    switch ($searchX['field']) {
                        case 'po_number':
                            $this->datatables->where("a.{$searchX['field']} = '{$searchX['value']}'");
                            break;

                        default:
                            $this->datatables->where("a.{$searchX['field']} LIKE '%{$searchX['value']}%'");
                            break;
                    }
                }
            }
            if (!empty($searchY)) {
                if (isset($searchY['from_val']) && isset($searchY['to_val'])) {
                    $this->datatables->where("a.created_at BETWEEN '{$searchY['from_val']}' AND '{$searchY['to_val']}'");
                }
            }
        }

        $this->datatables->from("{$this->_table_users_ms_inventory_receiving} a");
        $this->datatables->join("{$this->_table_ms_lookup_values} b", "b.lookup_code = a.status", "inner");
        $this->datatables->where("b.lookup_config", "inventory_warehouse_status");
        $this->datatables->where('a.deleted_at IS NULL');
        $this->datatables->where(["a.{$this->_table_users_ms_companys}_id" => $this->_users_ms_companys_id]);

        //untuk export
        if (isset($_GET['warehouseid'])) {
            $warehouseID = $_GET['warehouseid'];
            if ($warehouseID != "") {
                if ($warehouseID != 0) {
                    $this->datatables->where("a.users_ms_warehouses_id", $_GET['warehouseid']);
                }
            }
        }
        if (isset($_GET['statusid'])) {
            $status = $_GET['statusid'];
            if ($status != "") {
                $this->datatables->where("a.status", $status);
            }
        }
        if (isset($_GET['searchby']) && isset($_GET['searchby1'])) {
            $search_by = $_GET['searchby'];
            $search_by1 = $_GET['searchby1'];
            if ($search_by != "" && $search_by1 != "") {
                $this->datatables->where("a.{$search_by}= '{$search_by1}'");
            }
        }
        if (isset($_GET['from']) && isset($_GET['to'])) {
            $from = $_GET['from'];
            $to = $_GET['to'];
            if ($from != "" && $to != "") {
                $this->datatables->where("a.created_at BETWEEN '{$from}' AND '{$to}'");
            }
        }

        $fieldSearch = [
            "a.po_number",
            "a.brand_name",
            "a.supplier_name",
            "a.publisher_name",
            "a.created_at",
            "a.qty",
            "a.qty_receiving",
            "b.lookup_name",
        ];
        $this->_searchDefaultDatatables($fieldSearch);

        return $this->datatables->generate();
    }

    public function show_putaway()
    {
        $this->datatables->select(
            "a.id as id,
            a.po_number,
            a.brand_name,
            a.supplier_name,
            a.publisher_name,
            a.created_at,
            a.qty,
            a.qty_receiving,
            a.qty_putaway,
            b.lookup_name,
            a.users_ms_warehouses_id,",
            false,
        );

        $filters = !empty($this->input->post('filters')) ? $this->input->post('filters') : false;
        if ($filters !== false && is_array($filters)) {
            $searchX = [];
            $searchY = [];
            foreach ($filters as $ky => $val) {
                $value = $val['value'];
                if (!empty($value)) {
                    switch ($val['name']) {
                        case 'warehouse_id':
                            if ($value != 0) {
                                $this->datatables->where('a.users_ms_warehouses_id', $value);
                            }
                            break;
                        case 'status_id':
                            $this->datatables->where('a.status', $value);
                            break;
                        case 'search_by':
                            $field = $value;
                            $searchX['field'] = isset($field) ? $field : "";
                            break;
                        case 'search_by1':
                            $field = $value;
                            $searchX['value'] = isset($field) ? $field : "";
                            break;
                        case 'from_val':
                            $from_val = $value;
                            $searchY['from_val'] = isset($from_val) ? $from_val : "";
                            break;
                        case 'to_val':
                            $to_val = $value;
                            $searchY['to_val'] = isset($to_val) ? $to_val : "";
                            break;
                    }
                }
            }
            if (!empty($searchX)) {
                if (isset($searchX['value'])) {
                    switch ($searchX['field']) {
                        case 'po_number':
                            $this->datatables->where("a.{$searchX['field']} = '{$searchX['value']}'");
                            break;

                        default:
                            $this->datatables->where("a.{$searchX['field']} LIKE '%{$searchX['value']}%'");
                            break;
                    }
                }
            }
            if (!empty($searchY)) {
                if (isset($searchY['from_val']) && isset($searchY['to_val'])) {
                    $this->datatables->where("a.created_at BETWEEN '{$searchY['from_val']}' AND '{$searchY['to_val']}'");
                }
            }
        }

        $this->datatables->from("{$this->_table_users_ms_inventory_putaway} a");
        $this->datatables->join("{$this->_table_ms_lookup_values} b", "b.lookup_code = a.status", "inner");
        $this->datatables->where("b.lookup_config", "inventory_warehouse_status");
        $this->datatables->where('a.deleted_at IS NULL');
        $this->datatables->where(["a.{$this->_table_users_ms_companys}_id" => $this->_users_ms_companys_id]);

        //untuk export
        if (isset($_GET['warehouseid'])) {
            $warehouseID = $_GET['warehouseid'];
            if ($warehouseID != "") {
                if ($warehouseID != 0) {
                    $this->datatables->where("a.users_ms_warehouses_id", $_GET['warehouseid']);
                }
            }
        }
        if (isset($_GET['statusid'])) {
            $status = $_GET['statusid'];
            if ($status != "") {
                $this->datatables->where("a.status", $status);
            }
        }
        if (isset($_GET['searchby']) && isset($_GET['searchby1'])) {
            $search_by = $_GET['searchby'];
            $search_by1 = $_GET['searchby1'];
            if ($search_by != "" && $search_by1 != "") {
                $this->datatables->where("a.{$search_by}= '{$search_by1}'");
            }
        }
        if (isset($_GET['from']) && isset($_GET['to'])) {
            $from = $_GET['from'];
            $to = $_GET['to'];
            if ($from != "" && $to != "") {
                $this->datatables->where("a.created_at BETWEEN '{$from}' AND '{$to}'");
            }
        }

        $fieldSearch = [
            "a.po_number",
            "a.brand_name",
            "a.supplier_name",
            "a.publisher_name",
            "a.created_at",
            "a.qty",
            "a.qty_receiving",
            "a.qty_putaway",
            "b.lookup_name",
        ];
        $this->_searchDefaultDatatables($fieldSearch);

        return $this->datatables->generate();
    }

    public function show_storage()
    {
        $this->datatables->select(
            "a.id as id,
            a.sku,
            c.product_name,
            c.brand_name,
            c.category_name,
            b.product_size,
            a.qty,",
            false,
        );

        $filters = !empty($this->input->post('filters')) ? $this->input->post('filters') : false;
        if ($filters !== false && is_array($filters)) {
            $searchX = [];
            $searchY = [];
            foreach ($filters as $ky => $val) {
                $value = $val['value'];
                if (!empty($value)) {
                    switch ($val['name']) {
                        case 'warehouse_id':
                            if ($value != 0) {
                                $this->datatables->where('a.users_ms_warehouses_id', $value);
                            }
                            break;
                        case 'search_by':
                            $field = $value;
                            $searchX['field'] = isset($field) ? $field : "";
                            break;
                        case 'search_by1':
                            $field = $value;
                            $searchX['value'] = isset($field) ? $field : "";
                            break;
                    }
                }
            }
            if (!empty($searchX)) {
                if (isset($searchX['value'])) {
                    switch ($searchX['field']) {
                        case 'sku':
                            $this->datatables->where("a.{$searchX['field']} = '{$searchX['value']}'");
                            break;

                        default:
                            $this->datatables->where("c.{$searchX['field']} LIKE '%{$searchX['value']}%'");
                            break;
                    }
                }
            }
        }

        $this->datatables->from("{$this->_table_users_ms_inventory_storages} a");
        $this->datatables->join("{$this->_table_products_variants} b", "b.id = a.users_ms_product_variants_id", "inner");
        $this->datatables->join("{$this->_table_products} c", "c.id = b.users_ms_products_id", "inner");
        $this->datatables->where(["a.{$this->_table_users_ms_companys}_id" => $this->_users_ms_companys_id]);
        $this->datatables->where('a.deleted_at IS NULL');
        $this->datatables->where('b.deleted_at IS NULL');
        $this->datatables->where('c.deleted_at IS NULL');

        $fieldSearch = [
            "a.sku",
            "c.product_name",
            "c.brand_name",
            "c.category_name",
            "b.product_size",
            "a.qty"
        ];

        $this->_searchDefaultDatatables($fieldSearch);

        //untuk export
        if (isset($_GET['warehouseid'])) {
            $warehouseID = $_GET['warehouseid'];
            if ($warehouseID != "") {
                if ($warehouseID != 0) {
                    $this->datatables->where("a.users_ms_warehouses_id", $_GET['warehouseid']);
                }
            }
        }
        if (isset($_GET['searchby']) && isset($_GET['searchby1'])) {
            $search_by = $_GET['searchby'];
            $search_by1 = $_GET['searchby1'];
            if ($search_by != "" && $search_by1 != "") {
                if ($search_by == "") {
                    # code...
                } elseif ($search_by == "sku") {
                    # code...
                    $this->datatables->where("a.{$search_by}= '{$search_by1}'");
                } else {
                    $this->datatables->where("c.{$search_by}= '{$search_by1}'");
                }
            }
        }

        return $this->datatables->generate();
    }

    public function show_packing()
    {
        $this->datatables->select(
            "a.id as id,
            a.purchase_code,
            a.customer_name,
            a.customer_email,
            a.created_at,
            a.qty,
            a.qty_packing,
            a.assignee,
            b.lookup_name,
            a.users_ms_warehouses_id,",
            false,
        );

        $filters = !empty($this->input->post('filters')) ? $this->input->post('filters') : false;
        if ($filters !== false && is_array($filters)) {
            $searchX = [];
            $searchY = [];
            foreach ($filters as $ky => $val) {
                $value = $val['value'];
                if (!empty($value)) {
                    switch ($val['name']) {
                        case 'warehouse_id':
                            if ($value != 0) {
                                $this->datatables->where('a.users_ms_warehouses_id', $value);
                            }
                            break;
                        case 'status_id':
                            $this->datatables->where('a.status', $value);
                            break;
                        case 'search_by':
                            $field = $value;
                            $searchX['field'] = isset($field) ? $field : "";
                            break;
                        case 'search_by1':
                            $field = $value;
                            $searchX['value'] = isset($field) ? $field : "";
                            break;
                        case 'from_val':
                            $from_val = $value;
                            $searchY['from_val'] = isset($from_val) ? $from_val : "";
                            break;
                        case 'to_val':
                            $to_val = $value;
                            $searchY['to_val'] = isset($to_val) ? $to_val : "";
                            break;
                    }
                }
            }
            if (!empty($searchX)) {
                if (isset($searchX['value'])) {
                    switch ($searchX['field']) {
                        case 'purchase_code':
                            $this->datatables->where("a.{$searchX['field']} = '{$searchX['value']}'");
                            break;

                        default:
                            $this->datatables->where("a.{$searchX['field']} LIKE '%{$searchX['value']}%'");
                            break;
                    }
                }
            }
            if (!empty($searchY)) {
                if (isset($searchY['from_val']) && isset($searchY['to_val'])) {
                    $this->datatables->where("a.created_at BETWEEN '{$searchY['from_val']}' AND '{$searchY['to_val']}'");
                }
            }
        }

        $this->datatables->from("{$this->_table_users_ms_inventory_packing} a");
        $this->datatables->join("{$this->_table_ms_lookup_values} b", "b.lookup_code = a.status", "inner");
        $this->datatables->where("b.lookup_config", "inventory_warehouse_status");
        $this->datatables->where('a.deleted_at IS NULL');
        $this->datatables->where(["a.{$this->_table_users_ms_companys}_id" => $this->_users_ms_companys_id]);

        //untuk export
        if (isset($_GET['warehouseid'])) {
            $warehouseID = $_GET['warehouseid'];
            if ($warehouseID != "") {
                if ($warehouseID != 0) {
                    $this->datatables->where("a.users_ms_warehouses_id", $_GET['warehouseid']);
                }
            }
        }
        if (isset($_GET['statusid'])) {
            $status = $_GET['statusid'];
            if ($status != "") {
                $this->datatables->where("a.status", $status);
            }
        }
        if (isset($_GET['searchby']) && isset($_GET['searchby1'])) {
            $search_by = $_GET['searchby'];
            $search_by1 = $_GET['searchby1'];
            if ($search_by != "" && $search_by1 != "") {
                $this->datatables->where("a.{$search_by}= '{$search_by1}'");
            }
        }
        if (isset($_GET['from']) && isset($_GET['to'])) {
            $from = $_GET['from'];
            $to = $_GET['to'];
            if ($from != "" && $to != "") {
                $this->datatables->where("a.created_at BETWEEN '{$from}' AND '{$to}'");
            }
        }

        $fieldSearch = [
            "a.purchase_code",
            "a.customer_name",
            "a.customer_email",
            "a.created_at",
            "a.qty",
            "a.qty_packing",
            "a.assignee",
            "b.lookup_name",
        ];
        $this->_searchDefaultDatatables($fieldSearch);

        return $this->datatables->generate();
    }

    public function show_picking()
    {
        $this->datatables->select(
            "a.id as id,
            a.purchase_code,
            a.customer_name,
            a.customer_email,
            a.created_at,
            a.qty,
            a.qty_picking,
            a.assignee,
            b.lookup_name,
            a.users_ms_warehouses_id,",
            false,
        );

        $filters = !empty($this->input->post('filters')) ? $this->input->post('filters') : false;
        if ($filters !== false && is_array($filters)) {
            $searchX = [];
            $searchY = [];
            foreach ($filters as $ky => $val) {
                $value = $val['value'];
                if (!empty($value)) {
                    switch ($val['name']) {
                        case 'warehouse_id':
                            if ($value != 0) {
                                $this->datatables->where('a.users_ms_warehouses_id', $value);
                            }
                            break;
                        case 'status_id':
                            $this->datatables->where('a.status', $value);
                            break;
                        case 'search_by':
                            $field = $value;
                            $searchX['field'] = isset($field) ? $field : "";
                            break;
                        case 'search_by1':
                            $field = $value;
                            $searchX['value'] = isset($field) ? $field : "";
                            break;
                        case 'from_val':
                            $from_val = $value;
                            $searchY['from_val'] = isset($from_val) ? $from_val : "";
                            break;
                        case 'to_val':
                            $to_val = $value;
                            $searchY['to_val'] = isset($to_val) ? $to_val : "";
                            break;
                    }
                }
            }
            if (!empty($searchX)) {
                if (isset($searchX['value'])) {
                    switch ($searchX['field']) {
                        case 'purchase_code':
                            $this->datatables->where("a.{$searchX['field']} = '{$searchX['value']}'");
                            break;

                        default:
                            $this->datatables->where("a.{$searchX['field']} LIKE '%{$searchX['value']}%'");
                            break;
                    }
                }
            }
            if (!empty($searchY)) {
                if (isset($searchY['from_val']) && isset($searchY['to_val'])) {
                    $this->datatables->where("a.created_at BETWEEN '{$searchY['from_val']}' AND '{$searchY['to_val']}'");
                }
            }
        }

        $this->datatables->from("{$this->_table_users_ms_inventory_picking} a");
        $this->datatables->join("{$this->_table_ms_lookup_values} b", "b.lookup_code = a.status", "inner");
        $this->datatables->where("b.lookup_config", "inventory_warehouse_status");
        $this->datatables->where('a.deleted_at IS NULL');
        $this->datatables->where(["a.{$this->_table_users_ms_companys}_id" => $this->_users_ms_companys_id]);

        //untuk export
        if (isset($_GET['warehouseid'])) {
            $warehouseID = $_GET['warehouseid'];
            if ($warehouseID != "") {
                if ($warehouseID != 0) {
                    $this->datatables->where("a.users_ms_warehouses_id", $_GET['warehouseid']);
                }
            }
        }
        if (isset($_GET['statusid'])) {
            $status = $_GET['statusid'];
            if ($status != "") {
                $this->datatables->where("a.status", $status);
            }
        }
        if (isset($_GET['searchby']) && isset($_GET['searchby1'])) {
            $search_by = $_GET['searchby'];
            $search_by1 = $_GET['searchby1'];
            if ($search_by != "" && $search_by1 != "") {
                $this->datatables->where("a.{$search_by}= '{$search_by1}'");
            }
        }
        if (isset($_GET['from']) && isset($_GET['to'])) {
            $from = $_GET['from'];
            $to = $_GET['to'];
            if ($from != "" && $to != "") {
                $this->datatables->where("a.created_at BETWEEN '{$from}' AND '{$to}'");
            }
        }

        $fieldSearch = [
            "a.purchase_code",
            "a.customer_name",
            "a.customer_email",
            "a.created_at",
            "a.qty",
            "a.qty_picking",
            "a.assignee",
            "b.lookup_name",
        ];
        $this->_searchDefaultDatatables($fieldSearch);

        return $this->datatables->generate();
    }

    public function show_shipping()
    {
        $this->datatables->select(
            "a.id as id,
            a.purchase_code,
            a.customer_name,
            a.customer_email,
            a.created_at,
            a.qty,
            a.qty_shipping,
            a.assignee,
            b.lookup_name,
            a.users_ms_warehouses_id,",
            false,
        );

        $filters = !empty($this->input->post('filters')) ? $this->input->post('filters') : false;
        if ($filters !== false && is_array($filters)) {
            $searchX = [];
            $searchY = [];
            foreach ($filters as $ky => $val) {
                $value = $val['value'];
                if (!empty($value)) {
                    switch ($val['name']) {
                        case 'warehouse_id':
                            if ($value != 0) {
                                $this->datatables->where('a.users_ms_warehouses_id', $value);
                            }
                            break;
                        case 'status_id':
                            $this->datatables->where('a.status', $value);
                            break;
                        case 'search_by':
                            $field = $value;
                            $searchX['field'] = isset($field) ? $field : "";
                            break;
                        case 'search_by1':
                            $field = $value;
                            $searchX['value'] = isset($field) ? $field : "";
                            break;
                        case 'from_val':
                            $from_val = $value;
                            $searchY['from_val'] = isset($from_val) ? $from_val : "";
                            break;
                        case 'to_val':
                            $to_val = $value;
                            $searchY['to_val'] = isset($to_val) ? $to_val : "";
                            break;
                    }
                }
            }
            if (!empty($searchX)) {
                if (isset($searchX['value'])) {
                    switch ($searchX['field']) {
                        case 'purchase_code':
                            $this->datatables->where("a.{$searchX['field']} = '{$searchX['value']}'");
                            break;

                        default:
                            $this->datatables->where("a.{$searchX['field']} LIKE '%{$searchX['value']}%'");
                            break;
                    }
                }
            }
            if (!empty($searchY)) {
                if (isset($searchY['from_val']) && isset($searchY['to_val'])) {
                    $this->datatables->where("a.created_at BETWEEN '{$searchY['from_val']}' AND '{$searchY['to_val']}'");
                }
            }
        }

        $this->datatables->from("{$this->_table_users_ms_inventory_shipping} a");
        $this->datatables->join("{$this->_table_ms_lookup_values} b", "b.lookup_code = a.status", "inner");
        $this->datatables->where("b.lookup_config", "inventory_warehouse_status");
        $this->datatables->where('a.deleted_at IS NULL');
        $this->datatables->where(["a.{$this->_table_users_ms_companys}_id" => $this->_users_ms_companys_id]);

        //untuk export
        if (isset($_GET['warehouseid'])) {
            $warehouseID = $_GET['warehouseid'];
            if ($warehouseID != "") {
                if ($warehouseID != 0) {
                    $this->datatables->where("a.users_ms_warehouses_id", $_GET['warehouseid']);
                }
            }
        }
        if (isset($_GET['statusid'])) {
            $status = $_GET['statusid'];
            if ($status != "") {
                $this->datatables->where("a.status", $status);
            }
        }
        if (isset($_GET['searchby']) && isset($_GET['searchby1'])) {
            $search_by = $_GET['searchby'];
            $search_by1 = $_GET['searchby1'];
            if ($search_by != "" && $search_by1 != "") {
                $this->datatables->where("a.{$search_by}= '{$search_by1}'");
            }
        }
        if (isset($_GET['from']) && isset($_GET['to'])) {
            $from = $_GET['from'];
            $to = $_GET['to'];
            if ($from != "" && $to != "") {
                $this->datatables->where("a.created_at BETWEEN '{$from}' AND '{$to}'");
            }
        }

        $fieldSearch = [
            "a.purchase_code",
            "a.customer_name",
            "a.customer_email",
            "a.created_at",
            "a.qty",
            "a.qty_shipping",
            "a.assignee",
            "b.lookup_name",
        ];
        $this->_searchDefaultDatatables($fieldSearch);

        return $this->datatables->generate();
    }

    public function show_storage_log()
    {
        $this->datatables->select(
            "id,
            trx_number,
            created_at,
            trx_type,
            sku,
            qty_trx,
            qty_old,
            qty_new",
            false,
        );

        $this->datatables->from("{$this->_table_users_ms_inventory_storages_logs}");
        $this->datatables->where(["{$this->_table_users_ms_companys}_id" => $this->_users_ms_companys_id]);
        $this->datatables->where('deleted_at IS NULL');

        $fieldSearch = [
            "id",
            "trx_number",
            "created_at",
            "trx_type",
            "sku",
            "qty_trx",
            "qty_old",
            "qty_new"
        ];

        $this->_searchDefaultDatatables($fieldSearch);

        return $this->datatables->generate();
    }

    public function show_receiving_log()
    {
        $this->datatables->select(
            "id,
            po_number,
            created_at,
            sku,
            qty,
            qty_receiving",
            false,
        );

        $this->datatables->from("{$this->_table_users_ms_inventory_receiving_logs}");
        $this->datatables->where(["{$this->_table_users_ms_companys}_id" => $this->_users_ms_companys_id]);
        $this->datatables->where('deleted_at IS NULL');

        $fieldSearch = [
            "po_number",
            "created_at",
            "sku",
            "qty",
            "qty_receiving",
        ];

        $this->_searchDefaultDatatables($fieldSearch);

        return $this->datatables->generate();
    }

    public function show_putaway_log()
    {
        $this->datatables->select(
            "id,
            po_number,
            created_at,
            sku,
            qty,
            qty_receiving,
            qty_putaway",
            false,
        );

        $this->datatables->from("{$this->_table_users_ms_inventory_putaway_logs}");
        $this->datatables->where(["{$this->_table_users_ms_companys}_id" => $this->_users_ms_companys_id]);
        $this->datatables->where('deleted_at IS NULL');

        $fieldSearch = [
            "po_number",
            "created_at",
            "sku",
            "qty",
            "qty_receiving",
            "qty_putaway",
        ];

        $this->_searchDefaultDatatables($fieldSearch);

        return $this->datatables->generate();
    }

    public function show_picking_log()
    {
        $this->datatables->select(
            "id,
            purchase_code,
            created_at,
            sku,
            qty,
            qty_picking",
            false,
        );

        $this->datatables->from("{$this->_table_users_ms_inventory_picking_logs}");
        $this->datatables->where(["{$this->_table_users_ms_companys}_id" => $this->_users_ms_companys_id]);
        $this->datatables->where('deleted_at IS NULL');

        $fieldSearch = [
            "purchase_code",
            "created_at",
            "sku",
            "qty",
            "qty_picking",
        ];

        $this->_searchDefaultDatatables($fieldSearch);

        return $this->datatables->generate();
    }

    public function show_packing_log()
    {
        $this->datatables->select(
            "id,
            purchase_code,
            created_at,
            sku,
            qty,
            qty_packing",
            false,
        );

        $this->datatables->from("{$this->_table_users_ms_inventory_packing_logs}");
        $this->datatables->where(["{$this->_table_users_ms_companys}_id" => $this->_users_ms_companys_id]);
        $this->datatables->where('deleted_at IS NULL');

        $fieldSearch = [
            "purchase_code",
            "created_at",
            "sku",
            "qty",
            "qty_packing",
        ];

        $this->_searchDefaultDatatables($fieldSearch);

        return $this->datatables->generate();
    }

    public function show_shipping_log()
    {
        $this->datatables->select(
            "id,
            purchase_code,
            created_at,
            sku,
            qty,
            qty_shipping",
            false,
        );

        $this->datatables->from("{$this->_table_users_ms_inventory_shipping_logs}");
        $this->datatables->where(["{$this->_table_users_ms_companys}_id" => $this->_users_ms_companys_id]);
        $this->datatables->where('deleted_at IS NULL');

        $fieldSearch = [
            "purchase_code",
            "created_at",
            "sku",
            "qty",
            "qty_shipping",
        ];

        $this->_searchDefaultDatatables($fieldSearch);

        return $this->datatables->generate();
    }
}
