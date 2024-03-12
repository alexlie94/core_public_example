<?php

use PhpParser\Node\Expr\Isset_;

defined('BASEPATH') or exit('No direct script access allowed');

class Product_prices_model extends MY_ModelCustomer
{
    use MY_Tables;
    public function __construct()
    {
        $this->_tabel = $this->_table_batchs;
        parent::__construct();
        $this->load->helper('metronic');
    }

    public function getDataProductImage()
    {
        $this->_ci->load->model('product_image/Product_image_model', 'product_image_model');
        return $this->_ci->product_image_model;
    }

    public function show($button = '')
    {
        $this->datatables->select(
            "   a.id,
                a.batch_name,
                a.batch_description,
                if(a.admins_ms_sources_id=99,'Main',c.source_name) as batch_location,
                DATE_FORMAT(a.start_date,'%d %M %Y %H:%i') as start_date,
                DATE_FORMAT(a.end_date,'%d %M %Y %H:%i') as end_date",
            false
        );

        $this->datatables->from("{$this->_table_batchs} a");
        $this->datatables->join("{$this->_table_batchs_detail} b", "b.users_ms_batchs_id = a.id", "inner");
        $this->datatables->join("{$this->_table_admins_ms_sources} c", "c.id = a.admins_ms_sources_id", "left");
        $this->datatables->where(["a.{$this->_table_users_ms_companys}_id" => $this->_users_ms_companys_id]);
        $this->datatables->where('a.deleted_at IS NULL');
        $this->datatables->where('b.deleted_at IS NULL');
        $this->datatables->order_by('a.updated_at desc');
        $this->datatables->group_by('a.id');
        $buttonRelease = '<button class="btn btn-outline btn-outline-dashed btn-outline-warning btn-active-light-warning hover-scale btn-sm fw-bold me-2 mb-2 btnEdit" data-title="Item" data-type="modal" data-url="' . base_url() . 'product_prices/update/$1" data-fullscreenmodal="1" data-id="$1"><i class="bi bi-pencil-square fs-4 me-2"></i>Edit</button>
		<button class="btn btn-outline btn-outline-dashed btn-outline-dark btn-active-light-dark hover-scale btn-sm fw-bold me-2 mb-2 btnView" data-title="Item" data-type="modal" data-url="' . base_url() . 'product_prices/view/$1" data-fullscreenmodal="1" data-id="$1"><i class="bi bi-eye-fill fs-4 me-2"></i>View</button>';
        $this->datatables->add_column('action', $buttonRelease, 'id');

        $fieldSearch = [
            "a.id",
            "a.batch_name",
            "a.batch_description",
            "a.admins_ms_sources_id",
            "a.start_date",
            "a.end_date"
        ];
        $this->_searchDefaultDatatables($fieldSearch);

        $filters = !empty($this->input->post('filters')) ? $this->input->post('filters') : false;
        if ($filters !== false && is_array($filters)) {
            $searchX = [];
            foreach ($filters as $ky => $val) {
                $value = $val['value'];
                if (!empty($value)) {
                    switch ($val['name']) {
                        case 'batch_location':
                            $field = $value;
                            $searchX['value'] = isset($field) ? $field : "";
                            break;
                        case 'search_by':
                            $field = $value;
                            $searchX['field'] = isset($field) ? $field : "";
                            break;
                        case 'search_by1':
                            $field = $value;
                            $searchX['value'] = isset($field) ? $field : "";
                            break;
                        case 'searchDate':
                            $field = $value;
                            $searchX['value'] = isset($field) ? $field : "";
                            break;
                    }
                }
            }
            if (!empty($searchX)) {
                if (isset($searchX['value'])) {
                    switch ($searchX['field']) {
                        case 'id':
                            $this->datatables->where("a.{$searchX['field']} = '{$searchX['value']}'");
                            break;
                        case 'batch_location':
                            $this->datatables->where("a.{$searchX['field']} = '{$searchX['value']}'");
                            break;
                        case 'start_date':
                            $dateValue = explode("to", $searchX['value']);
                            $fromDate = $dateValue[0];
                            $toDate = $dateValue[1];
                            $this->datatables->where("a.{$searchX['field']} BETWEEN '{$fromDate}' AND '{$toDate}'");
                            break;
                        case 'end_date':
                            $dateValue = explode("to", $searchX['value']);
                            $fromDate = $dateValue[0];
                            $toDate = $dateValue[1];
                            $this->datatables->where("a.{$searchX['field']} BETWEEN '{$fromDate}' AND '{$toDate}'");
                            break;

                        default:
                            if (!empty($searchX['field'])) {
                                $this->datatables->where("a.{$searchX['field']} LIKE '%{$searchX['value']}%'");
                            }
                            break;
                    }
                }
            }
        }

        //untuk export

        if (isset($_GET['searchby'])) {
            $search_by = $_GET['searchby'];
            if (isset($_GET['searchby1'])) {
                $search_by1 = $_GET['searchby1'];
                if ($search_by != "" && $search_by1 != "") {
                    $this->datatables->where("a.{$search_by}= '{$search_by1}'");
                }
            }
            if (isset($_GET['batch_location'])) {
                $batchLocation = $_GET['batch_location'];
                if ($search_by != "" && $batchLocation != "") {
                    $this->datatables->where("a.{$search_by}= '{$batchLocation}'");
                }
            }
            if (isset($_GET['from']) && isset($_GET['to'])) {
                $from = $_GET['from'];
                $to = $_GET['to'];
                if ($_GET['searchby'] == "start_date" && $from != "" && $to != "") {
                    $this->datatables->where("a.start_date BETWEEN '{$from}' AND '{$to}'");
                }
                if ($_GET['searchby'] == "end_date" && $from != "" && $to != "") {
                    $this->datatables->where("a.end_date BETWEEN '{$from}' AND '{$to}'");
                }
            }
        }

        return $this->datatables->generate();
    }

    private function _validate()
    {
        $response = ['success' => false, 'validate' => true, 'messages' => []];
        $response['type'] = !empty($this->input->post('id')) ? 'update' : 'insert';

        $rules = ['trim', 'required', 'xss_clean'];

        $this->form_validation->set_rules('batch_name', 'Batch Name', $rules);
        $this->form_validation->set_rules('batch_description', 'Batch Description', $rules);
        $this->form_validation->set_rules('batch_location', 'Batch Location', $rules);
        $this->form_validation->set_rules('start_date', 'Start Date', $rules);
        $checkEndDate = $this->input->post('endDate');
        if ($checkEndDate == 1) {
            $this->form_validation->set_rules('end_date', 'End Date', $rules);
        }

        $this->form_validation->set_error_delimiters('<div class="' . VALIDATION_MESSAGE_FORM . '">', '</div>');

        if ($this->form_validation->run() === false) {
            $response['validate'] = false;
            foreach ($this->input->post() as $key => $value) {
                $response['messages'][$key] = form_error($key);
            }
        }

        return $response;
    }

    public function save()
    {
        $this->db->trans_begin();

        try {

            $response = self::_validate();

            if (!$response['validate']) {
                throw new Exception("Error Processing Request", 1);
            }

            $id = $this->input->post('id');
            $batch_name = clearInput($this->input->post('batch_name'));
            $batch_description = clearInput($this->input->post('batch_description'));
            $batch_location = clearInput($this->input->post('batch_location'));
            $start_date = clearInput($this->input->post('start_date'));
            $end_date = clearInput($this->input->post('end_date'));
            $endDateStatus = isset($_POST['endDate']) ? 1 : 0;

            $data_array = [
                'batch_name' => $batch_name,
                'batch_description' => $batch_description,
                'admins_ms_sources_id' => $batch_location,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'end_date_status' => $endDateStatus,
            ];

            if (empty($id)) {

                $process = $this->insert($data_array);

                for ($i = 0; $i < count($this->input->post('product_id_1')); $i++) {

                    $cek_product_id = $this->getDataProductImage()->get(['users_ms_products_id' => $this->input->post('product_id_1')[$i]]);
                    if (!empty($cek_product_id)) {
                        $data_update = [
                            'status' => 3,
                        ];
                        $this->db->where('id', $cek_product_id->users_ms_products_id);
                        $this->db->update($this->_table_products, $data_update);
                    }

                    $data_productPrice = [
                        'users_ms_batchs_id' => $process,
                        'users_ms_products_id' => $this->input->post('product_id_1')[$i],
                        'price' => $this->input->post('price_1')[$i],
                        'sale_price' => $this->input->post('sale_price_1')[$i],
                        'offline_price' => $this->input->post('offline_price_1')[$i],
                    ];

                    $this->insertCustom($data_productPrice, $this->_table_batchs_detail);
                }

                if (!$process) {
                    $response['messages'] = 'Data Insert Invalid';
                    throw new Exception;
                }

                $response['messages'] = 'Insert Data Batch Success';
            } else {

                $this->db->where('id', $id);
                $process2 = $this->db->update($this->_table_batchs, $data_array);
                $response['messages'] = 'Update Data Batch Success';

                if ($this->input->post('product_id_1') !== null) {

                    $this->db->delete($this->_table_batchs_detail, array('users_ms_batchs_id' => $id));
                    for ($i = 0; $i < count($this->input->post('product_id_1')); $i++) {

                        $cek_product_id = $this->getDataProductImage()->get(['users_ms_products_id' => $this->input->post('product_id_1')[$i]]);
                        if (!empty($cek_product_id)) {
                            $data_update = [
                                'status' => 3,
                            ];
                            $this->db->where('id', $cek_product_id->id);
                            $this->db->update($this->_table_products, $data_update);
                        }

                        $data_productPrice = [
                            'users_ms_batchs_id' => $id,
                            'users_ms_products_id' => $this->input->post('product_id_1')[$i],
                            'price' => $this->input->post('price_1')[$i],
                            'sale_price' => $this->input->post('sale_price_1')[$i],
                            'offline_price' => $this->input->post('offline_price_1')[$i],
                        ];

                        $this->insertCustom($data_productPrice, $this->_table_batchs_detail);
                    }

                    if (!$process2) {
                        $response['messages'] = 'Failed Insert data Batch';
                        throw new Exception;
                    } else {
                        $response['messages'] = 'Update Data Batch Success';
                    }
                }
            }

            $this->db->trans_commit();
            $response['success'] = true;
            return $response;
        } catch (Exception $e) {
            $this->db->trans_rollback();
            return $response;
        }
    }

    public function getItems($id)
    {
        try {
            $get = $this->db->get_where('users_ms_batchs', ['id' => $id])->row();

            if (!$get) {
                throw new Exception('Data not Register', 1);
            }

            $table = [
                'id' => $get->id,
                'batch_name' => $get->batch_name,
                'batch_description' => $get->batch_description,
                'batch_location' => $get->admins_ms_sources_id,
                'start_date' => $get->start_date,
                'end_date' => $get->end_date,
                'end_date_status' => $get->end_date_status,
                'status' => $get->status
            ];

            return $table;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function deleteData($id)
    {
        $this->db->trans_begin();
        try {
            $softDelete = $this->softDeleteCustom($this->_table_products_images, 'id', $id);

            if (!$softDelete) {
                throw new Exception('Failed delete item', 1);
            }

            $this->db->trans_commit();
            return true;
        } catch (Exception $e) {
            $this->db->trans_rollback();
            return $e->getMessage();
        }
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

    public function productValidation($id)
    {
        try {
            $this->db->select('*');
            $this->db->from("{$this->_table_products}");
            $this->datatables->where(["{$this->_table_users_ms_companys}_id" => $this->_users_ms_companys_id]);
            $this->db->where('deleted_at IS NULL');
            $this->db->where('id', $id);
            return $this->db->get()->row_array();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function process_data($getData)
    {
        $rData = [];
        $product_id = [];

        foreach ($getData as $res) {

            if (isset($res['Product_Id']) && isset($res['Price']) && isset($res['Sale_Price'])) {
                $checkProduct = $this->productValidation($res['Product_Id']);
                if (!empty($checkProduct)) {

                    foreach ($getData as $row) {
                        if (isset($row['Product_Id']) && isset($row['Price']) && isset($row['Sale_Price'])) {
                            if ($row['Product_Id'] == $res['Product_Id']) {
                                $product_id[] = $row['Product_Id'];
                            }
                        }
                    }

                    if (count($product_id) > 1) {
                        $productID = $res['Product_Id'] . "<span class='ms-2 badge badge-light-danger fw-bold'>Duplicated Product ID</span>";
                        $validate_check = 2;
                    } else {
                        $productID = $res['Product_Id'];
                        $validate_check = 1;
                    }

                    for ($i = 0; $i <= count($product_id) + 1; $i++) {
                        unset($product_id[$i]);
                    }
                    $product_id = array();
                } else {
                    $productID = $res['Product_Id'] . "<span class='ms-2 badge badge-light-danger fw-bold'>Product ID Not Found</span>";
                    $validate_check = 2;
                }

                if (!empty($res['Price'])) {
                    if (is_numeric($res['Price'])) {
                        $price = $res['Price'];
                    } else {
                        $price = $res['Price'] . "<span class='ms-2 badge badge-light-danger fw-bold'>Price is Not Number</span>";
                        $validate_check = 2;
                    }
                } else {
                    $price = "<span class='ms-2 badge badge-light-danger fw-bold'>Price is Required</span>";
                    $validate_check = 2;
                }

                if (is_numeric($res['Sale_Price'])) {
                    $salePrice = $res['Sale_Price'];
                } else {
                    $salePrice = $res['Sale_Price'] . "<span class='ms-2 badge badge-light-danger fw-bold'>Sale Price  is Not Number</span>";
                }
                if (is_numeric($res['Offline_Price'])) {
                    $offlinePrice = $res['Offline_Price'];
                } else {
                    $offlinePrice = $res['Offline_Price'] . "<span class='ms-2 badge badge-light-danger fw-bold'>Offline Price  is Not Number</span>";
                }

                $row =
                    [
                        'Product_Id' => $productID,
                        'Price' => $price,
                        'Sale_Price' => $salePrice,
                        'Offline_Price' => $offlinePrice,
                        'validate' => $validate_check,
                    ];
                array_push($rData, $row);
            }
        }

        $output =
            [
                "draw" => 10,
                "recordsTotal" => 100,
                "recordsFiltered" => 10,
                "data" => $rData,
            ];

        return $output;
    }

    public function process_update_batch()
    {
        $this->db->trans_begin();
        try {
            $id = $_POST['batch_id'];
            $status = $_POST['batch_status'];
            $this->db->where('id', $id);
            $process2 = $this->db->update($this->_table_batchs, array('status' => $status));

            $this->db->trans_commit();
            $output =
                [
                    "error" => false,
                ];

            return $output;
        } catch (Exception $e) {
            $this->db->trans_rollback();
            return $e->getMessage();
        }
    }

    public function showCsv($button = '')
    {
        $this->datatables->select(
            "   a.id as id,
                a.batch_name,
                a.batch_description,
                if(a.admins_ms_sources_id=99,'Main',c.source_name) as batch_location,
                DATE_FORMAT(a.start_date,'%d %M %Y %H:%i') as start_date,
                d.product_name,
                b.price,
                b.sale_price,
                b.offline_price,
                DATE_FORMAT(a.end_date,'%d %M %Y %H:%i') as end_date",
            false
        );

        $this->datatables->from("{$this->_table_batchs} a");
        $this->datatables->join("{$this->_table_batchs_detail} b", "b.users_ms_batchs_id = a.id", "inner");
        $this->datatables->join("{$this->_table_admins_ms_sources} c", "c.id = a.admins_ms_sources_id", "left");
        $this->datatables->join("{$this->_table_products} d", "d.id = b.users_ms_products_id", "inner");
        $this->datatables->where(["a.{$this->_table_users_ms_companys}_id" => $this->_users_ms_companys_id]);
        $this->datatables->where('a.deleted_at IS NULL');
        $this->datatables->where('b.deleted_at IS NULL');
        $this->datatables->order_by('a.updated_at desc');
        $this->datatables->add_column('action', '<div class="d-flex justify-content-center"><button class="btn btn-outline btn-outline-dashed btn-outline-success btn-sm btnEdit" data-type="modal" data-url="' . base_url('product_prices/update/$1') . '" data-fullscreenmodal="1" data-id="$1">Edit</button><button class="btn btn-outline btn-outline-dashed btn-outline-info btn-sm ms-2 btnView" data-type="modal" data-url="' . base_url('product_prices/view/$1') . '" data-fullscreenmodal="1" data-id="$1">View</button></div>', 'id');

        $fieldSearch = [
            "a.id as id",
            "a.batch_name",
            "a.batch_description",
            "a.admins_ms_sources_id",
            "a.start_date",
            "a.end_date"
        ];
        $this->_searchDefaultDatatables($fieldSearch);

        $filters = !empty($this->input->post('filters')) ? $this->input->post('filters') : false;
        if ($filters !== false && is_array($filters)) {
            $searchX = [];
            foreach ($filters as $ky => $val) {
                $value = $val['value'];
                if (!empty($value)) {
                    switch ($val['name']) {
                        case 'batch_location':
                            $field = $value;
                            $searchX['value'] = isset($field) ? $field : "";
                            break;
                        case 'search_by':
                            $field = $value;
                            $searchX['field'] = isset($field) ? $field : "";
                            break;
                        case 'search_by1':
                            $field = $value;
                            $searchX['value'] = isset($field) ? $field : "";
                            break;
                        case 'searchDate':
                            $field = $value;
                            $searchX['value'] = isset($field) ? $field : "";
                            break;
                    }
                }
            }
            if (!empty($searchX)) {
                if (isset($searchX['value'])) {
                    switch ($searchX['field']) {
                        case 'id':
                            $this->datatables->where("a.{$searchX['field']} = '{$searchX['value']}'");
                            break;
                        case 'batch_location':
                            $this->datatables->where("a.{$searchX['field']} = '{$searchX['value']}'");
                            break;
                        case 'start_date':
                            $dateValue = explode(" ", $searchX['value']);
                            $fromDate = $dateValue[0] . " " . $dateValue[1];
                            $toDate = $dateValue[3] . " " . $dateValue[4];
                            $this->datatables->where("a.{$searchX['field']} BETWEEN '{$fromDate}' AND '{$toDate}'");
                            break;
                        case 'end_date':
                            $dateValue = explode(" ", $searchX['value']);
                            $fromDate = $dateValue[0] . " " . $dateValue[1];
                            $toDate = $dateValue[3] . " " . $dateValue[4];
                            $this->datatables->where("a.{$searchX['field']} BETWEEN '{$fromDate}' AND '{$toDate}'");
                            break;

                        default:
                            $this->datatables->where("a.{$searchX['field']} LIKE '%{$searchX['value']}%'");
                            break;
                    }
                }
            }
        }

        //untuk export

        if (isset($_GET['searchby'])) {
            $search_by = $_GET['searchby'];
            if (isset($_GET['searchby1'])) {
                $search_by1 = $_GET['searchby1'];
                if ($search_by != "" && $search_by1 != "") {
                    $this->datatables->where("a.{$search_by}= '{$search_by1}'");
                }
            }
            if (isset($_GET['batch_location'])) {
                $batchLocation = $_GET['batch_location'];
                if ($search_by != "" && $batchLocation != "") {
                    $this->datatables->where("a.{$search_by}= '{$batchLocation}'");
                }
            }
            if (isset($_GET['from']) && isset($_GET['to'])) {
                $from = $_GET['from'];
                $to = $_GET['to'];
                if ($_GET['searchby'] == "start_date" && $from != "" && $to != "") {
                    $this->datatables->where("a.start_date BETWEEN '{$from}' AND '{$to}'");
                }
                if ($_GET['searchby'] == "end_date" && $from != "" && $to != "") {
                    $this->datatables->where("a.end_date BETWEEN '{$from}' AND '{$to}'");
                }
            }
        }

        return $this->datatables->generate();
    }
}
