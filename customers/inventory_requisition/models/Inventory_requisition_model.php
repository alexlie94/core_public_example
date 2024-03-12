<?php
defined('BASEPATH') or exit('No direct script access allowed');

require FCPATH . 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Inventory_requisition_model extends MY_ModelCustomer
{
    use MY_Tables;
    public function __construct()
    {
        $this->_tabel = $this->_table_users_ms_inventory_requisition_headers;
        $this->_table_details = $this->_table_users_ms_inventory_requisition_details;
        parent::__construct();
        $this->load->helper('metronic');
    }

    public function getDataSuppliers()
    {
        $this->_ci->load->model('suppliers_data/Suppliers_data_model', 'suppliers_model');
        return $this->_ci->suppliers_model;
    }

    public function getDataBrands()
    {
        $this->_ci->load->model('brand/Brand_model', 'brand_model');
        return $this->_ci->brand_model;
    }

    public function getDataWarehouse()
    {
        $this->_ci->load->model('master_warehouse/Master_warehouse_model', 'warehouse_model');
        return $this->_ci->warehouse_model;
    }

    public function getDataProducts()
    {
        $this->_ci->load->model('products/Products_model', 'products_model');
        return $this->_ci->products_model;
    }

    public function getDataProductVariants()
    {
        $this->_ci->load->model('products/Products_variants_model', 'products_variants_model');
        return $this->_ci->products_variants_model;
    }

    public function getDataBrandsBySuppliers($id, $search_key)
    {
        $query = "  SELECT  a.*,
                            b.id as brand_id,
                            b.brand_code,
                            b.brand_name,
                            b.description
                    FROM {$this->_table_ms_suppliers_brands} a
                    LEFT JOIN {$this->_table_ms_brands} b on b.id = a.users_ms_brands_id
                    WHERE a.users_ms_companys_id = '{$this->_users_ms_companys_id}'
                        AND ISNULL(a.deleted_at)
                        AND ISNULL(b.deleted_at)
                        AND users_ms_suppliers_id ='$id' ";

        if ($search_key != '') {
            $query .= "AND b.brand_name like '%" . $search_key . "%'";
        }

        return $this->db->query($query);
    }

    public function getDataPurchaseOrderDetail($id = '')
    {
        $this->db->select('*');
        $this->db->from($this->_table_details);
        $this->db->where('deleted_at is null', null, false);
        $this->db->where('users_ms_companys_id', $this->_users_ms_companys_id);
        $this->db->where('users_ms_inventory_requisition_headers_id', $id);

        return $this->db->get();
    }

    public function getDataStorages($sku = '')
    {
        $this->db->select('*');
        $this->db->from($this->_table_users_ms_inventory_storages);
        $this->db->where('deleted_at is null', null, false);
        $this->db->where('users_ms_companys_id', $this->_users_ms_companys_id);
        $this->db->where('sku', $sku);

        return $this->db->get();
    }

    public function resultDataInventory($data = [])
    {
        $this->db->select(' a.id,
                            b.sku,
                            a.product_name,
                            a.brand_name ,
                            a.supplier_name ,
                            a.category_name ,
                            b.product_size,
                            b.variant_color_name,
                            c.color_name');
        $this->db->from('users_ms_products a');
        $this->db->join('users_ms_product_variants b', 'b.users_ms_products_id = a.id', 'left');
        $this->db->join('ms_color_name_hexa c', 'c.id = b.general_color_id', 'left');
        $this->db->where('a.deleted_at is null', null, false);
        $this->db->where('a.users_ms_companys_id', $this->_users_ms_companys_id);
        $this->db->where('a.users_ms_suppliers_id', $data['supplier_id']);
        $this->db->where('a.users_ms_brands_id', $data['brand_id']);

        if (!empty($data['value_input'])) {
            $this->db->like('b.sku', $data['value_input']);
        }

        return $this->db->get();
    }

    public function productResult($sku, $supp_id, $brand_id)
    {
        $this->db->select(' a.id,
                            a.product_name,
                            a.users_ms_brands_id,
                            a.brand_name ,
                            a.users_ms_suppliers_id,
                            a.supplier_name ,
                            a.users_ms_categories_id,
                            a.category_name ,
                            b.sku,
                            b.product_size,
                            b.variant_color_name,
                            c.color_name ');
        $this->db->from('users_ms_products a');
        $this->db->join('users_ms_product_variants b', 'b.users_ms_products_id = a.id', 'left');
        $this->db->join('ms_color_name_hexa c', 'c.id = b.general_color_id', 'left');
        $this->db->where('a.deleted_at is null', null, false);
        $this->db->where('a.users_ms_companys_id', $this->_users_ms_companys_id);
        $this->db->where('b.sku', $sku);
        $this->db->where('a.users_ms_suppliers_id', $supp_id);
        $this->db->where('a.users_ms_brands_id', $brand_id);

        return $this->db->get();
    }

    public function productResultXLSX($supp_id, $brand_id)
    {
        $this->db->select(' a.id,
                            a.product_name,
                            a.users_ms_brands_id,
                            a.brand_name ,
                            a.users_ms_suppliers_id,
                            a.supplier_name ,
                            a.users_ms_categories_id,
                            a.category_name ,
                            b.sku,
                            b.product_size,
                            b.variant_color_name,
                            c.color_name ');
        $this->db->from('users_ms_products a');
        $this->db->join('users_ms_product_variants b', 'b.users_ms_products_id = a.id', 'left');
        $this->db->join('ms_color_name_hexa c', 'c.id = b.general_color_id', 'left');
        $this->db->where('a.deleted_at is null', null, false);
        $this->db->where('a.users_ms_companys_id', $this->_users_ms_companys_id);
        $this->db->where('a.users_ms_suppliers_id', $supp_id);
        $this->db->where('a.users_ms_brands_id', $brand_id);

        return $this->db->get();
    }

    public function show($button = '')
    {
        $this->datatables->select(
            "   a.id,
                a.po_number,
                a.description,
                e.lookup_name as status_name,
                a.status,
                a.created_at,
                b.brand_name,
                c.supplier_name,
                (SELECT sum(quantity) FROM users_ms_inventory_requisition_details WHERE users_ms_inventory_requisition_headers_id  = a.id) as total_qty,
                d.fullname as username
            ",
            false,
        );
        $this->datatables->from("{$this->_tabel} a");
        $this->datatables->join("{$this->_table_ms_brands} b", 'b.id = a.users_ms_brands_id', 'left');
        $this->datatables->join("{$this->_table_ms_suppliers} c", 'c.id = a.users_ms_suppliers_id', 'left');
        $this->datatables->join('users d', 'd.id = a.created_by', 'left');
        $this->datatables->join('admins_ms_lookup_values e', "e.lookup_code = a.status AND e.lookup_config = 'po_status' ", 'left');
        $this->datatables->where('a.users_ms_companys_id', $this->_users_ms_companys_id);
        $this->datatables->where('a.deleted_at is null', null, false);
        $this->datatables->order_by('a.po_number desc');

        $filters = !empty($this->input->post('filters')) ? $this->input->post('filters') : false;

        if ($filters !== false && is_array($filters)) {
            $getSearchBy = '';
            $set_value = '';
            $set_start_date = '';
            $set_end_date = '';

            $set_lookup_status = [];

            foreach ($filters as $ky => $val) {
                if ($val['name'] == 'searchBy') {
                    $getSearchBy .= $val['value'];
                }

                if ($val['name'] == 'searchValue') {
                    $set_value .= $val['value'];
                }

                if ($val['name'] == 'start_date') {
                    $set_start_date .= $val['value'];
                }

                if ($val['name'] == 'end_date') {
                    $set_end_date .= $val['value'];
                }

                if ($val['name'] == 'lookup_status_1') {
                    $set_lookup_status[] = $val['value'];
                }

                if ($val['name'] == 'lookup_status_2') {
                    $set_lookup_status[] = $val['value'];
                }

                if ($val['name'] == 'lookup_status_3') {
                    $set_lookup_status[] = $val['value'];
                }
            }

            if (!empty($getSearchBy)) {
                switch ($getSearchBy) {
                    case 'po_number':
                        $this->datatables->like('a.po_number', $set_value);
                        break;
                    case 'brand_name':
                        $this->datatables->like('b.brand_name', $set_value);
                        break;
                    case 'supplier_name':
                        $this->datatables->like('c.supplier_name', $set_value);
                        break;
                    case 'publisher':
                        $this->datatables->like('d.fullname', $set_value);
                        break;
                }
            }

            if (!empty($set_lookup_status)) {
                $this->datatables->where_in('a.status', $set_lookup_status);
            }

            if (!empty($set_start_date)) {
                $this->datatables->where('a.created_at >=', $set_start_date);
            }

            if (!empty($set_start_date) && !empty($set_end_date)) {
                $this->datatables->where('DATE(a.created_at) >=', $set_start_date);
                $this->datatables->where('DATE(a.created_at) <=', $set_end_date);
            }

            if (empty($set_start_date) && !empty($set_end_date)) {
                $getDateNow = date('Y-m-d');

                $this->datatables->where('DATE(a.created_at) >=', $getDateNow);
                $this->datatables->where('DATE(a.created_at) <=', $set_end_date);
            }
        }

        $buttonRelease = '<button class="btn btn-outline btn-outline-dashed btn-outline-primary btn-active-light-primary hover-scale btn-sm fw-bold me-2 mb-2 btnRelease" data-type="confirm" data-textconfirm="Are you sure you want to release this item ?" data-title="Item" data-type="modal" data-url="' . base_url() . 'inventory_requisition/release/$1" data-fullscreenmodal="1" data-id="$1"><i class="bi bi-upload fs-4 me-2"></i>Release</button>';
        $custom_btn_edit = '<button class="btn btn-outline btn-outline-dashed btn-outline-success btn-active-light-success hover-scale btn-sm fw-bold me-2 mb-2 btnEditPO" data-type="modal" data-url="' . base_url() . 'inventory_requisition/update/$1" data-fullscreenmodal="1" data-id="$1"><i class="bi bi-pencil-square fs-4 me-2"></i>Edit</button>';
        $custom_btn_delete = '<button class="btn btn-outline btn-outline-dashed btn-outline-danger btn-active-light-danger hover-scale btn-sm fw-bold me-2 mb-2 btnDelete" data-type="confirm" data-url="' . base_url() . 'inventory_requisition/delete/$1" data-textconfirm="Are you sure you want to cancel this item ?" data-title="Item" data-id="$1"><i class="bi bi-trash fs-4 me-2"></i>Cancel</button>';
        $buttonDetail = '<button class="btn btn-outline btn-outline-dashed btn-outline-primary btn-sm me-2 btnDetail" data-type="modal" data-url="' . base_url() . 'inventory_requisition/detail/$1" data-fullscreenmodal="1" data-id="$1">Detail</button>';

        $this->datatables->add_column('release', $buttonRelease, 'id');
        $this->datatables->add_column('edit_cstom', $custom_btn_edit, 'id');
        $this->datatables->add_column('dlete_cstom', $custom_btn_delete, 'id');
        $this->datatables->add_column('detail', $buttonDetail, 'id');
        $this->datatables->add_column('action', $button, 'id');

        return $this->datatables->generate();
    }

    private function _validate()
    {
        $response = ['success' => false, 'validate' => true, 'messages' => []];

        $response['type'] = !empty($this->input->post('id')) ? 'update' : 'insert';

        $validateSku = ['trim', 'required', 'xss_clean'];
        $validateqty = ['trim', 'required', 'xss_clean', 'greater_than[0]'];

        $cek_sku = [
            'cek_sku',
            function ($value) {
                if (!empty($value) || $value != '') {
                    try {
                        $cekSku = $this->getDataProductVariants()->get(['sku' => $value]);

                        if (empty($cekSku)) {
                            throw new Exception();
                        }

                        return true;
                    } catch (Exception $e) {
                        $this->form_validation->set_message('cek_sku', '{field} Not Exist');
                        return false;
                    }
                }
            },
        ];
        array_push($validateSku, $cek_sku);

        for ($i = 0; $i < count($this->input->post('sku')); $i++) {
            $this->form_validation->set_rules('sku[' . $i . ']', 'SKU', $validateSku);
            $this->form_validation->set_rules('quantity[' . $i . ']', 'Quantity', $validateqty);
        }

        $this->form_validation->set_error_delimiters('<div style="margin-top: -2px;margin-bottom: -29px;" class="' . VALIDATION_MESSAGE_FORM . '">', '</div>');

        if ($this->form_validation->run() === false) {
            $response['validate'] = false;
            for ($i = 0; $i < count($this->input->post('sku')); $i++) {
                $response['messages']['sku[' . $i . ']'] = form_error('sku[' . $i . ']');
                $response['messages']['quantity[' . $i . ']'] = form_error('quantity[' . $i . ']');
            }
        }

        return $response;
    }

    public function saveMassUpload()
    {

        try {
            $response = self::_validate();

            if (!$response['validate']) {
                throw new Exception("Error Processing Request", 1);
            }

            $response['success'] = true;
            return $response;
        } catch (Exception $e) {
            return $response;
        }
    }

    public function save()
    {
        $this->db->trans_begin();

        try {

            $response = self::_validate();

            if (!$response['validate']) {
                throw new Exception('Error Processing Request', 1);
            }

            $id = empty($this->input->post('id')) ? '' : clearInput($this->input->post('id'));
            $upload_dir = './assets/uploads/requisition_image/';

            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            $data_ms_inventory_requisition = [
                'po_number' => mkautono($this->_tabel, 'po_number', 'PO'),
                'users_ms_suppliers_id' => $this->input->post('url_supplier_id'),
                'users_ms_brands_id' => $this->input->post('url_brand_id'),
                'users_ms_warehouses_id' => $this->input->post('url_warehouse_id'),
                'users_ms_ownership_types_id' => 1,
                'description' => $this->input->post('description_parent'),
                'status' => 1,
                'po_type' => 2,
            ];

            if (empty($id)) {

                $process = $this->insert($data_ms_inventory_requisition);

                for ($i = 0; $i < count($this->input->post('sku')); $i++) {

                    $qty = str_replace(".", "", $this->input->post('quantity')[$i]);
                    $price = str_replace(".", "", $this->input->post('price')[$i]);
                    $material_cost = str_replace(".", "", $this->input->post('material_cost')[$i]);
                    $service_cost = str_replace(".", "", $this->input->post('service_cost')[$i]);
                    $overhead_cost = str_replace(".", "", $this->input->post('overhead_cost')[$i]);

                    if ($_FILES['image_file']['name'][$i] == null) {
                        $new_file_name = '';
                    } else {
                        $original_file_name = $_FILES['image_file']['name'][$i];
                        $file_extension = pathinfo($original_file_name, PATHINFO_EXTENSION);
                        $tmp_file = $_FILES['image_file']['tmp_name'][$i];

                        $setUniqueId = uniqid();

                        $new_file_name = date('Y_m_d') . '_Insert_' . $setUniqueId . '.' . $file_extension;

                        $full_path = $upload_dir . $new_file_name;

                        move_uploaded_file($tmp_file, $full_path);
                    }

                    $data_inventory_requisition_detail = [
                        'users_ms_inventory_requisition_headers_id' => $process,
                        'sku' => $this->input->post('sku')[$i],
                        'category_name' => $this->input->post('categories_name')[$i],
                        'users_ms_products_id' => $this->input->post('product_id')[$i],
                        'product_name' => $this->input->post('product_name')[$i],
                        'brand_name' => $this->input->post('brand_name')[$i],
                        'product_size' => $this->input->post('product_size')[$i],
                        'color' => $this->input->post('color')[$i],
                        'quantity' => $qty,
                        'type' => isset($this->input->post('type')[$i]) ? $this->input->post('type')[$i] : '',
                        'price' => $price,
                        'material_cost' => $material_cost,
                        'service_cost' => $service_cost,
                        'overhead_cost' => $overhead_cost,
                        'description' => $this->input->post('description')[$i],
                        'image_name' => $new_file_name
                    ];

                    $process2 = $this->insertCustom($data_inventory_requisition_detail, $this->_table_details);
                }

                if (!$process || !$process2) {
                    $response['messages'] = 'Data Insert Invalid';
                    throw new Exception();
                }

                $response['messages'] = 'Successfully Insert Data PO';
            } else {

                $data_ms_inventory_requisition_update = [
                    'users_ms_suppliers_id' => !empty($this->input->post('set_supplier_id')) ? $this->input->post('set_supplier_id') : '',
                    'users_ms_brands_id' => !empty($this->input->post('set_brand_id')) ? $this->input->post('set_brand_id') : '',
                    'users_ms_warehouses_id' => !empty($this->input->post('set_warehouse_id')) ? $this->input->post('set_warehouse_id') : '',
                    'users_ms_ownership_types_id' => 1,
                    'description' => $this->input->post('description_parent'),
                    'status' => 1,
                    'po_type' => 2,
                ];

                $process = $this->update(['id' => $id], $data_ms_inventory_requisition_update);

                for ($i = 0; $i < count($this->input->post('sku')); $i++) {

                    $qty = str_replace(".", "", $this->input->post('quantity')[$i]);
                    $price = str_replace(".", "", $this->input->post('price')[$i]);
                    $material_cost = str_replace(".", "", $this->input->post('material_cost')[$i]);
                    $service_cost = str_replace(".", "", $this->input->post('service_cost')[$i]);
                    $overhead_cost = str_replace(".", "", $this->input->post('overhead_cost')[$i]);


                    if ($_FILES['image_file']['name'][$i] == null) {
                        $data_inventory_requisition_detail = [
                            'users_ms_inventory_requisition_headers_id' => $id,
                            'sku' => $this->input->post('sku')[$i],
                            'category_name' => $this->input->post('categories_name')[$i],
                            'users_ms_products_id' => $this->input->post('product_id')[$i],
                            'product_name' => $this->input->post('product_name')[$i],
                            'brand_name' => $this->input->post('brand_name')[$i],
                            'product_size' => $this->input->post('product_size')[$i],
                            'color' => $this->input->post('color')[$i],
                            'quantity' => $qty,
                            'type' => isset($this->input->post('type')[$i]) ? $this->input->post('type')[$i] : '',
                            'price' => $price,
                            'material_cost' => $material_cost,
                            'service_cost' => $service_cost,
                            'overhead_cost' => $overhead_cost,
                            'description' => $this->input->post('description')[$i],
                        ];

                        if ($this->input->post('detail_id')[$i] == 0) {
                            $process2 = $this->insertCustom($data_inventory_requisition_detail, $this->_table_details);
                        } else {
                            $this->db->where('id', $this->input->post('detail_id')[$i]);

                            $process2 = $this->db->update($this->_table_details, $data_inventory_requisition_detail);
                        }
                    } else {

                        $fileExist = $this->db->get_where('users_ms_inventory_requisition_details', ['id' => $this->input->post('detail_id')[$i]])->row();

                        $original_file_name = $_FILES['image_file']['name'][$i];
                        $file_extension = pathinfo($original_file_name, PATHINFO_EXTENSION);
                        $tmp_file = $_FILES['image_file']['tmp_name'][$i];

                        $setUniqueId = uniqid();

                        $new_file_name = date('Y_m_d') . '_Update_' . $setUniqueId . '.' . $file_extension;

                        $full_path = $upload_dir . $new_file_name;

                        if ($fileExist !== null) {
                            if (file_exists($upload_dir . $fileExist->image_name)) {
                                unlink($upload_dir . $fileExist->image_name);
                            }
                        }

                        move_uploaded_file($tmp_file, $full_path);

                        $data_inventory_requisition_detail = [
                            'users_ms_inventory_requisition_headers_id' => $id,
                            'sku' => $this->input->post('sku')[$i],
                            'category_name' => $this->input->post('categories_name')[$i],
                            'users_ms_products_id' => $this->input->post('product_id')[$i],
                            'product_name' => $this->input->post('product_name')[$i],
                            'brand_name' => $this->input->post('brand_name')[$i],
                            'product_size' => $this->input->post('product_size')[$i],
                            'color' => $this->input->post('color')[$i],
                            'quantity' => $qty,
                            'type' => isset($this->input->post('type')[$i]) ? $this->input->post('type')[$i] : '',
                            'price' => $price,
                            'material_cost' => $material_cost,
                            'service_cost' => $service_cost,
                            'overhead_cost' => $overhead_cost,
                            'description' => $this->input->post('description')[$i],
                            'image_name' => $new_file_name
                        ];

                        if ($this->input->post('detail_id')[$i] == 0) {
                            $process2 = $this->insertCustom($data_inventory_requisition_detail, $this->_table_details);
                        } else {
                            $this->db->where('id', $this->input->post('detail_id')[$i]);
                            $process2 = $this->db->update($this->_table_details, $data_inventory_requisition_detail);
                        }
                    }


                }

                // if (!$process || !$process2) {
                //     $response['messages'] = 'Data Update Invalid';
                //     throw new Exception();
                // }

                $response['messages'] = 'Successfully Update Data PO';
            }


            $this->db->trans_commit();
            $response['success'] = true;
            return $response;
        } catch (Exception $e) {
            $this->db->trans_rollback();
            return $response;
        }
    }

    public function deleteData($idsku = '', $id_all = '')
    {
        $this->db->trans_begin();

        try {
            if (!empty($idsku)) {
                $softDelete = $this->softDeleteCustom($this->_table_details, 'id', $idsku);

                if (!$softDelete) {
                    throw new Exception('Failed delete item', 1);
                }
            } else {
                $softDelete = $this->softDeleteCustom($this->_table_details, 'users_ms_inventory_requisition_headers_id', $id_all);
                $softDelete2 = $this->softDelete($id_all);

                if (!$softDelete || !$softDelete2) {
                    throw new Exception('Failed delete item', 1);
                }
            }

            $this->db->trans_commit();
            return true;
        } catch (Exception $e) {
            $this->db->trans_rollback();
            return $e->getMessage();
        }
    }

    public function manageListDetailPO($id)
    {
        $list = $this->getDataPurchaseOrderDetail($id)->result();

        $rdata = [];
        foreach ($list as $vdata) {
            $row = [
                'id_po' => $vdata->id,
                'ms_purchase_order_header_id' => $vdata->users_ms_inventory_requisition_headers_id,
                'sku' => $vdata->sku,
                'category_name' => $vdata->category_name,
                'product_id' => $vdata->users_ms_products_id,
                'product_name' => $vdata->product_name,
                'brand_name' => $vdata->brand_name,
                'product_size' => $vdata->product_size,
                'color' => $vdata->color,
                'quantity' => $vdata->quantity,
                'type' => $vdata->type,
                'price' => $vdata->price,
                'material_cost' => $vdata->material_cost,
                'service_cost' => $vdata->service_cost,
                'overhead_cost' => $vdata->overhead_cost,
                'description' => $vdata->description,
                'image_name' => $vdata->image_name,
            ];

            $rdata[] = $row;
        }

        $output = [
            'draw' => 10,
            'recordsTotal' => 100,
            'recordsFiltered' => 10,
            'data' => $rdata,
        ];

        //output to json format
        echo json_encode($output);
    }

    public function manageBrandData($supplier_id, $get_data)
    {
        $data = $this->getDataBrandsBySuppliers($supplier_id, $get_data)->result();

        $rdata = [];
        foreach ($data as $vdata) {
            $row = [
                'brand_id' => $vdata->brand_id,
                'brand_code' => $vdata->brand_code,
                'brand_name' => $vdata->brand_name,
                'description' => $vdata->description,
            ];

            $rdata[] = $row;
        }

        $output = [
            'data' => $rdata,
        ];

        //output to json format
        echo json_encode($output);
    }

    public function manageListSku($getData = [])
    {
        $data = $this->resultDataInventory($getData)->result();

        $rdata = [];
        foreach ($data as $vdata) {
            $row = [
                'sku' => $vdata->sku,
                'product_id' => $vdata->id,
                'product_name' => $vdata->product_name,
                'brand_name' => $vdata->brand_name,
                'supplier_name' => $vdata->supplier_name,
                'category_name' => $vdata->category_name,
                'product_size' => $vdata->product_size,
                'color' => $vdata->variant_color_name,
                'color_name' => $vdata->color_name,
            ];

            $rdata[] = $row;
        }

        $output = [
            'data' => $rdata,
        ];

        //output to json format
        echo json_encode($output);
    }

    public function manageWarehouseData($get_data)
    {
        if ($get_data === '') {
            $list = $this->getDataWarehouse()->get_all();
        } else {
            $list = $this->getDataWarehouse()->get_all("warehouse_name like '%" . $get_data . "%'");
        }

        $rdata = [];
        foreach ($list as $vdata) {
            $row = [
                'warehouse_id' => $vdata->id,
                'warehouse_name' => $vdata->warehouse_name,
                'email' => $vdata->email,
                'address' => $vdata->address,
                'phone' => $vdata->phone,
            ];
            $rdata[] = $row;
        }

        $output = [
            'draw' => 10,
            'recordsTotal' => 100,
            'recordsFiltered' => 10,
            'data' => $rdata,
        ];

        //output to json format
        echo json_encode($output);
    }

    public function manageSuppliersData($get_data)
    {
        if ($get_data === '') {
            $list = $this->getDataSuppliers()->get_all();
        } else {
            $list = $this->getDataSuppliers()->get_all("supplier_name like '%" . $get_data . "%'");
        }

        $rdata = [];
        foreach ($list as $vdata) {
            $row = [
                'supplier_id' => $vdata->id,
                'supplier_name' => $vdata->supplier_name,
                'email' => $vdata->email,
                'address' => $vdata->address,
                'phone' => $vdata->phone,
            ];

            $rdata[] = $row;
        }

        $output = [
            'draw' => 10,
            'recordsTotal' => 100,
            'recordsFiltered' => 10,
            'data' => $rdata,
        ];

        //output to json format
        echo json_encode($output);
    }

    public function dataUpload($dataPush = [])
    {
        $get_data_upload = json_decode($dataPush['data_upload'], true);
        $get_data_supplier_id = json_decode($dataPush['supplier_id'], true);
        $get_data_brand_id = json_decode($dataPush['brand_id'], true);

        $rData = [];

        if (count($get_data_upload) > 0) {
            foreach ($get_data_upload as $res) {

                $data_sku = isset($res['SKU_(*)']) ? $res['SKU_(*)'] : '';
                $data_type = isset($res['TYPE']) ? $res['TYPE'] : '';
                $data_qty = isset($res['QUANTITY_(*)']) ? $res['QUANTITY_(*)'] : '';
                $data_price = isset($res['PRICE']) ? $res['PRICE'] : '';
                $data_material_cost = isset($res['MATERIAL_COST']) ? $res['MATERIAL_COST'] : '';
                $data_service_cost = isset($res['SERVICE_COST']) ? $res['SERVICE_COST'] : '';
                $data_overhead_cost = isset($res['OVERHEAD_COST']) ? $res['OVERHEAD_COST'] : '';
                $data_desc = isset($res['DESCRIPTION']) ? $res['DESCRIPTION'] : '';

                $validate_check = [];

                $resultProduct = $this->productResult($data_sku, $get_data_supplier_id, $get_data_brand_id)->row();

                $cekType = ['New Product', 'New Variant', 'Re Stock', 'Replenishment'];

                if (empty($data_sku)) {
                    $set_sku = $data_sku;
                    array_push($validate_check, 3);
                } elseif (empty($resultProduct)) {
                    $set_sku = $data_sku;
                    array_push($validate_check, 2);
                } else {
                    $set_sku = $data_sku;
                    array_push($validate_check, 1);
                }

                if (empty($data_type)) {
                    $set_type = $data_type;
                    array_push($validate_check, 3);
                } elseif (!in_array($data_type, $cekType)) {
                    $set_type = $data_type;
                    array_push($validate_check, 2);
                } else {
                    $set_type = $data_type;
                    array_push($validate_check, 1);
                }

                $row = [
                    'product_id' => !empty($resultProduct->id) ? $resultProduct->id : '',
                    'product_name' => !empty($resultProduct->product_name) ? $resultProduct->product_name : '',
                    'sku' => $set_sku,
                    'brand_id' => !empty($resultProduct->users_ms_brands_id) ? $resultProduct->users_ms_brands_id : '',
                    'brand_name' => !empty($resultProduct->brand_name) ? $resultProduct->brand_name : '',
                    'categories_id' => !empty($resultProduct->users_ms_categories_id) ? $resultProduct->users_ms_categories_id : '',
                    'categories_name' => !empty($resultProduct->category_name) ? $resultProduct->category_name : '',
                    'product_size' => !empty($resultProduct->product_size) ? $resultProduct->product_size : '',
                    'color' => !empty($resultProduct->color_name) ? $resultProduct->color_name : '',
                    'qty' => $data_qty,
                    'type' => $set_type,
                    'price' => $data_price,
                    'material_cost' => $data_material_cost,
                    'service_cost' => $data_service_cost,
                    'overhead_cost' => $data_overhead_cost,
                    'description' => $data_desc,
                    'validate' => empty($validate_check) ? '' : $validate_check,
                ];

                $rData[] = $row;

            }
        }

        $output = [
            'data' => $rData,
        ];

        echo json_encode($output);
    }

    public function getProductBySku($dataPush = [])
    {
        $get_data_sku = $dataPush['sku_input'];
        $get_data_supplier_id = $dataPush['supplier_id'];
        $get_data_brand_id = $dataPush['brand_id'];

        $resultProduct = $this->productResult($get_data_sku, $get_data_supplier_id, $get_data_brand_id)->row();

        $rData = [];

        if (!empty($resultProduct)) {
            $row = [
                'product_id' => !empty($resultProduct->id) ? $resultProduct->id : '',
                'product_name' => !empty($resultProduct->product_name) ? $resultProduct->product_name : '',
                'sku' => !empty($resultProduct->sku) ? $resultProduct->sku : '',
                'brand_id' => !empty($resultProduct->users_ms_brands_id) ? $resultProduct->users_ms_brands_id : '',
                'brand_name' => !empty($resultProduct->brand_name) ? $resultProduct->brand_name : '',
                'categories_id' => !empty($resultProduct->users_ms_categories_id) ? $resultProduct->users_ms_categories_id : '',
                'categories_name' => !empty($resultProduct->category_name) ? $resultProduct->category_name : '',
                'product_size' => !empty($resultProduct->product_size) ? $resultProduct->product_size : '',
                'color' => !empty($resultProduct->color_name) ? $resultProduct->color_name : '',
            ];

            $rData[] = $row;
        } else {
            $rData[] = false;
        }

        $output = [
            'data' => $rData,
        ];

        echo json_encode($output);
    }

    public function proccess_release($id)
    {
        $this->db->trans_begin();

        try {
            $process = $this->update(['id' => $id], ['status' => 2]);

            if (!$process) {
                $response['messages'] = 'Data Release Invalid';
                throw new Exception();
            }

            $getDataHeader = $this->get($id);
            $getDataDetail = $this->getDataPurchaseOrderDetail($id)->result_array();

            foreach ($getDataDetail as $res) {
                $getIdSku = $this->getDataProductVariants()->get_all('sku', $res['sku']);

                $getStorage = $this->getDataStorages($res['sku'])->row();

                foreach ($getIdSku as $res_variant) {
                    $data_input = [
                        'trx_number' => $getDataHeader->po_number,
                        'trx_type' => 1,
                        'sku' => $res_variant->sku,
                        'qty_trx' => $res['quantity'],
                        'qty_old' => empty($getStorage) ? 0 : $getStorage->qty,
                        'qty_new' => (empty($getStorage) ? 0 : $getStorage->qty) + $res['quantity'],
                    ];

                    $this->insertCustom($data_input, $this->_table_users_ms_inventory_storages_logs);

                    if (empty($getStorage)) {
                        $data_insert = [
                            'users_ms_warehouses_id' => $getDataHeader->users_ms_warehouses_id,
                            'users_ms_product_variants_id' => $res_variant->id,
                            'sku' => $res_variant->sku,
                            'qty' => $res['quantity'],
                            'trx_number' => $getDataHeader->po_number,
                        ];

                        $this->insertCustom($data_insert, $this->_table_users_ms_inventory_storages);
                    } else {
                        $data_update = [
                            'qty' => $res['quantity'] + (empty($getStorage) ? 0 : $getStorage->qty),
                        ];

                        $this->db->where('sku', $res_variant->sku);
                        $this->db->update($this->_table_users_ms_inventory_storages, $data_update);
                    }
                }
            }

            $response['messages'] = 'Successfully Release Po';

            $this->db->trans_commit();
            $response['type'] = 'insert';
            $response['validate'] = true;
            $response['success'] = true;
            return $response;
        } catch (Exception $e) {
            $this->db->trans_rollback();
            return $response;
        }
    }

    public function downloadXlxs($getData = [])
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $columnHeaders = ['SKU (*)', 'QUANTITY (*)', 'TYPE', 'PRICE', 'MATERIAL COST', 'SERVICE COST', 'OVERHEAD COST', 'DESCRIPTION'];

        foreach ($columnHeaders as $index => $header) {
            $columnLetter = chr(65 + $index);
            $sheet->setCellValue($columnLetter . '1', $header);
            $sheet->getColumnDimension($columnLetter)->setAutoSize(true);
        }

        $newSheet = $spreadsheet->createSheet();
        $newSheet->setTitle('Master Product');

        $newColumnHeadersBrands = ['SKU', 'PRODUCT NAME', 'BRAND NAME', 'SUPPLIER NAME', 'CATEGORY NAME', 'SIZE', 'COLOR'];

        // Set column headers
        foreach ($newColumnHeadersBrands as $index => $header) {
            $columnLetter = chr(65 + $index);
            $newSheet->setCellValue($columnLetter . '1', $header);
            $newSheet->getColumnDimension($columnLetter)->setAutoSize(true);
        }

        $getDataSupplier = $getData['supplier_id'];
        $getDataBrand = $getData['brand_id'];

        $getProduct = $this->productResultXLSX($getDataSupplier, $getDataBrand)->result();

        if ($getProduct) {
            $rowIndex = 2;

            foreach ($getProduct as $row) {

                $rowData =
                    [
                        $row->sku,
                        $row->product_name,
                        $row->brand_name,
                        $row->supplier_name,
                        $row->category_name,
                        $row->product_size,
                        $row->color_name
                    ];

                $columnLetter = 'A';

                foreach ($rowData as $value) {
                    $newSheet->setCellValue($columnLetter . $rowIndex, $value);
                    $columnLetter++;
                }

                $rowIndex++;
            }
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = 'List_Product_PO.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        $writer->save('php://output');
    }

    public function export()
    {
        $searchBy = $this->input->get('searchName');
        $set_value = $this->input->get('valueSearch');
        $status = $this->input->get('valueStatus');
        $set_start_date = $this->input->get('startDate');
        $set_end_date = $this->input->get('endDate');

        $this->db->select(
            "   a.po_number ,
                c.product_name ,
                b.sku ,
                c.brand_name ,
                c.supplier_name ,
                c.category_name ,
                d.fullname as publisher ,
                a.created_at ,
                (SELECT sum(quantity) FROM users_ms_inventory_requisition_details WHERE users_ms_inventory_requisition_headers_id  = a.id) as quantity,
                e.lookup_name as status
            ",
            false,
        );
        $this->db->from("{$this->_tabel} a");
        $this->db->join("{$this->_table_details} b", 'b.users_ms_inventory_requisition_headers_id = a.id', 'left');
        $this->db->join("users_ms_products c", 'c.id = b.users_ms_products_id', 'left');
        $this->db->join('users d', 'd.id = a.created_by', 'left');
        $this->db->join('admins_ms_lookup_values e', "e.lookup_code = a.status AND e.lookup_config = 'po_status' ", 'left');
        $this->db->where('a.users_ms_companys_id', $this->_users_ms_companys_id);
        $this->db->where('a.deleted_at is null', null, false);
        $this->db->order_by('a.po_number,a.po_number asc');

        if (!empty($searchBy)) {
            switch ($searchBy) {
                case 'po_number':
                    $this->datatables->like('a.po_number', $set_value);
                    break;
                case 'brand_name':
                    $this->datatables->like('b.brand_name', $set_value);
                    break;
                case 'supplier_name':
                    $this->datatables->like('c.supplier_name', $set_value);
                    break;
                case 'publisher':
                    $this->datatables->like('d.fullname', $set_value);
                    break;
            }
        }

        if (!empty($status)) {
            $this->datatables->where_in('e.lookup_name', $status);
        }

        if (!empty($set_start_date)) {
            $this->datatables->where('a.created_at >=', $set_start_date);
        }

        if (!empty($set_start_date) && !empty($set_end_date)) {
            $this->datatables->where('DATE(a.created_at) >=', $set_start_date);
            $this->datatables->where('DATE(a.created_at) <=', $set_end_date);
        }

        if (empty($set_start_date) && !empty($set_end_date)) {
            $getDateNow = date('Y-m-d');

            $this->datatables->where('DATE(a.created_at) >=', $getDateNow);
            $this->datatables->where('DATE(a.created_at) <=', $set_end_date);
        }

        $query = $this->db->get()->result_array();


        $data = array(
            'title' => 'Data Inventory Requisition',
            'filename' => 'inventory_requisition',
            'query' => $query,
        );

        $this->excel->process($data);
    }
}
