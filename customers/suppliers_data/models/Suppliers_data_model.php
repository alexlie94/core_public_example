<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Suppliers_data_model extends MY_ModelCustomer
{
    use MY_Tables;
    public function __construct()
    {
        $this->_tabel = $this->_table_ms_suppliers;
        $this->_tabel_brand = $this->_table_ms_suppliers_brands;
        parent::__construct();
    }

    public function getDataBrands2()
    {
        $this->_ci->load->model('brand/Brand_model', 'brand_model');
        return $this->_ci->brand_model;
    }

    public function getDataProductsOwnershipTypes()
    {
        $this->_ci->load->model('ownership_types/Ownership_types_model', 'ownership_types_model');
        return $this->_ci->ownership_types_model;
    }

    public function getDataBrands()
    {
        $this->db->select('*');
        $this->db->from($this->_table_ms_brands);
        $this->db->where('deleted_at IS NULL');
        $this->db->where(["{$this->_table_users_ms_companys}_id" => $this->_users_ms_companys_id]);
        return $this->db->get()->result_array();
    }

    public function validateBrand($code)
    {
        $this->db->select('*');
        $this->db->from($this->_table_ms_brands);
        $this->db->where('deleted_at IS NULL');
        $this->db->where('brand_code', $code);
        // $this->db->where('brand_name', $name);
        // $this->db->where("brand_code = '{$code}' AND brand_name = '{$name}'");
        $this->db->where(["{$this->_table_users_ms_companys}_id" => $this->_users_ms_companys_id]);
        return $this->db->get()->result_array();
    }

    public function getDataOwnershipTypes()
    {
        $this->db->select('*');
        $this->db->from($this->_table_users_ms_ownership_types);
        $this->db->where('deleted_at IS NULL');
        $this->db->where(["{$this->_table_users_ms_companys}_id" => $this->_users_ms_companys_id]);
        return $this->db->get()->result_array();
    }

    public function validateOwnershipTypes($code)
    {
        $this->db->select('*');
        $this->db->from($this->_table_users_ms_ownership_types);
        $this->db->where('deleted_at IS NULL');
        $this->db->where('ownership_type_code', $code);
        // $this->db->where('ownership_type_name', $name);
        // $this->db->where("ownership_type_code = '{$code}' AND ownership_type_name = '{$name}'");
        $this->db->where(["{$this->_table_users_ms_companys}_id" => $this->_users_ms_companys_id]);
        return $this->db->get()->result_array();
    }

    public function getDataSuppliersBrands($idSuppliers)
    {
        $this->db->select('*');
        $this->db->from($this->_table_ms_suppliers_brands);
        $this->db->where('deleted_at IS NULL');
        $this->db->where('users_ms_suppliers_id', $idSuppliers);
        $this->db->where(["{$this->_table_users_ms_companys}_id" => $this->_users_ms_companys_id]);
        return $this->db->get()->result_array();
    }

    public function supplierValidate($supplier_name)
    {
        $this->db->select('*');
        $this->db->from($this->_table_ms_suppliers);
        $this->db->where('deleted_at IS NULL');
        // $this->db->where("supplier_code = '{$supplier_code}' AND supplier_name = '{$supplier_name}' AND email = '{$supplier_email}'");
        // $this->db->where('supplier_code', $supplier_code);
        $this->db->where('supplier_name', $supplier_name);
        // $this->db->where('email', $supplier_email);
        // $this->db->where("supplier_name LIKE '%{$supplier_name}%'");
        $this->db->where(["{$this->_table_users_ms_companys}_id" => $this->_users_ms_companys_id]);
        return $this->db->get()->result_array();
    }

    public function show($button = '')
    {
        $brand = $this->_table_ms_suppliers_brands;
        $this->datatables->select(
            "id,
            supplier_code,
            supplier_name,
            email,
            address,
            phone,",
            false,
        );
        $this->datatables->from("{$this->_table_ms_suppliers}");
        $this->datatables->where('deleted_at is null', null, false);
        $this->datatables->where(["{$this->_table_users_ms_companys}_id" => $this->_users_ms_companys_id]);
        $this->datatables->order_by('updated_at desc');

        $button = "<button class=\"btn btn-outline btn-outline-dashed btn-outline-success btn-active-light-success hover-scale btn-sm fw-bold me-2 mb-2 btnEdit\" data-type=\"modal\" data-fullscreenmodal=\"0\" data-url=\"" . base_url("suppliers_data/update/$1") . "\" data-id =\"$1\"><i class=\"bi bi-pencil-square fs-4 me-2\"></i>Edit</button>";
        $button .= "<button class=\"btn btn-outline btn-outline-dashed btn-outline-danger btn-active-light-danger hover-scale btn-sm fw-bold mb-2\" data-url=\"" . base_url("suppliers_data/delete/$1") . "\" data-type=\"confirm\" data-textconfirm=\"Are you sure you want to delete this item ?\" data-title=\"Item\" data-id =\"$1\"><i class=\"bi bi-trash fs-4 me-2\"></i>Delete</button>";

        $this->datatables->add_column('action', $button, 'id');

        $filters = !empty($this->input->post('filters')) ? $this->input->post('filters') : false;

        if ($filters !== false && is_array($filters)) {

            $getSearchBy = '';
            $setValue = '';

            foreach ($filters as $val) {

                if ($val['name'] == 'searchBy') {
                    $getSearchBy .= $val['value'];
                }

                if ($val['name'] == 'searchValue') {
                    $setValue .= $val['value'];
                }
            }

            if (!empty($getSearchBy)) {
                switch ($getSearchBy) {
                    case 'supplier_code':
                        $this->datatables->like('supplier_code', $setValue);
                        break;
                    case 'supplier_name':
                        $this->datatables->like('supplier_name', $setValue);
                        break;
                    default:
                        break;
                }
            }
        }

        $get_value_master_requisition = $this->input->post('master_reqisition');
        if (!empty($get_value_master_requisition)) {
            $this->datatables->like('supplier_code', $get_value_master_requisition);
            $this->datatables->or_like('supplier_name', $get_value_master_requisition);
            $this->datatables->or_like('email', $get_value_master_requisition);
            $this->datatables->or_like('address', $get_value_master_requisition);
            $this->datatables->or_like('phone', $get_value_master_requisition);
        }

        $fieldSearch = [
            "supplier_code",
            "supplier_name",
            "email",
            "address",
            "phone"
        ];
        $this->_searchDefaultDatatables($fieldSearch);
        return $this->datatables->generate();
    }

    public function getItems($id)
    {
        try {
            $get = $this->get(['id' => $id]);

            $table = [
                'id' => $get->id,
                'supplier_code' => $get->supplier_code,
                'supplier_name' => $get->supplier_name,
                'email' => $get->email,
                'address' => $get->address,
                'phone' => $get->phone,
            ];

            return $table;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    private function _validate()
    {
        $response = ['success' => false, 'validate' => true, 'messages' => []];

        $response['type'] = !empty($this->input->post('id')) ? 'update' : 'insert';

        $role_validate = ['trim', 'required', 'xss_clean'];
        $role_validate_name = ['trim', 'required', 'xss_clean'];
        $role_validate_email = ['trim', 'required', 'xss_clean', 'valid_email'];
        $role_validate_phone = ['trim', 'required', 'xss_clean', 'numeric'];

        $id = !empty($this->input->post('id')) ? clearInput($this->input->post('id')) : "";
        $name_check = $response['type'] == 'insert' ? array(
            'name_check',
            function ($value) {
                if (!empty($value) || $value != '') {
                    try {
                        $cek = $this->get(array('supplier_name' => clearInput($value)));
                        if (is_object($cek)) {
                            throw new Exception;
                        }
                        return true;
                    } catch (Exception $e) {
                        $this->form_validation->set_message('name_check', 'The {field} already used');
                        return false;
                    }
                }
            }
        ) : array(
            'name_check',
            function ($value) use ($id) {
                if (!empty($value) || $value != '') {
                    try {
                        $cek = $this->get(array('supplier_name' => clearInput($value)));
                        if (is_object($cek)) {
                            if ($cek->id != $id) {
                                throw new Exception;
                            }
                        }
                        return true;
                    } catch (Exception $e) {
                        $this->form_validation->set_message('name_check', 'The {field} already used');
                        return false;
                    }
                }
            }
        );
        array_push($role_validate_name, $name_check);

        // $this->form_validation->set_rules('supplier_code', 'Supplier Code', $role_validate);
        $this->form_validation->set_rules('supplier_name', 'Supplier Name', $role_validate_name);
        $this->form_validation->set_rules('email', 'Email', $role_validate_email);
        $this->form_validation->set_rules('address', 'Address', $role_validate);
        $this->form_validation->set_rules('phone', 'Phone', $role_validate_phone);
        for ($i = 0; $i < count($this->input->post('kt_docs_repeater_advanced')); $i++) {
            $this->form_validation->set_rules('kt_docs_repeater_advanced[' . $i . '][select_brand_id]', 'Brand', $role_validate);

            $this->form_validation->set_error_delimiters('<div class="' . VALIDATION_MESSAGE_FORM . '">', '</div>');

            if ($this->form_validation->run() === false) {
                $response['validate'] = false;

                $response['messages']['kt_docs_repeater_advanced[' . $i . '][select_brand_id]'] = form_error('kt_docs_repeater_advanced[' . $i . '][select_brand_id]');
            }
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
            if (isset($_POST['supplier_name_1'])) {

                for ($y = 0; $y < count($_POST['supplier_name_1']); $y++) {

                    $cek_brand = $this->getDataBrands2()->get(['brand_name' => $this->input->post('brand_name_1')[$y]]);
                    $cek_type_ownership = $this->getDataProductsOwnershipTypes()->get(['ownership_type_name' => $this->input->post('type_ownership_name_1')[$y]]);

                    $supplier_email = strtolower($this->input->post('email_1')[$y]);
                    $supplier_name = $this->input->post('supplier_name_1')[$y];
                    $supplier_code = mkautono($this->_tabel, 'supplier_code', 'SC');
                    $cek_supplier = $this->supplierValidate($supplier_name);
                    $address = $this->input->post('address_1')[$y];
                    $phone_1 = "0" . $this->input->post('phone_1_1')[$y];
                    $phone_2 = !empty($this->input->post('phone_2_1')[$y]) ? "/0" . $this->input->post('phone_2_1')[$y] : "";
                    $phone = $phone_1 . $phone_2;

                    if (!empty($cek_supplier)) {
                        $supplier_id = $cek_supplier[0]['id'];
                        $massupload_array_2 = [
                            'users_ms_suppliers_id' => $supplier_id,
                            'users_ms_brands_id' => $cek_brand->id,
                            'users_ms_ownership_types_id' => !empty($cek_type_ownership) ? $cek_type_ownership->id : '0',
                        ];

                        $this->insertCustom($massupload_array_2, $this->_table_ms_suppliers_brands);
                    } else {
                        $massupload_array_1 = [
                            'supplier_code' => $supplier_code,
                            'supplier_name' => $supplier_name,
                            'email' => $supplier_email,
                            'address' => $address,
                            'phone' => $phone,
                        ];
                        $process = $this->insert($massupload_array_1);
                        $massupload_array_2 = [
                            'users_ms_suppliers_id' => $process,
                            'users_ms_brands_id' => $cek_brand->id,
                            'users_ms_ownership_types_id' => !empty($cek_type_ownership) ? $cek_type_ownership->id : '0',
                        ];

                        $this->insertCustom($massupload_array_2, $this->_table_ms_suppliers_brands);
                    }
                }

                $response['messages'] = 'Successfully Insert Data Supplier';
                $response['type'] = 'insert';
                $response['validate'] = true;
            } else {

                $response = self::_validate();

                if (!$response['validate']) {
                    throw new Exception('Error Processing Request', 1);
                }

                $id = clearInput($this->input->post('id'));
                $supplier_name = clearInput($this->input->post('supplier_name'));
                $email = clearInput($this->input->post('email'));
                $address = clearInput($this->input->post('address'));
                $phone = clearInput($this->input->post('phone'));
                $phone2 = !empty($this->input->post('phone2')) ? "/" . $this->input->post('phone2') : "";

                if (empty($id)) {

                    $data_array = [
                        'supplier_code' => mkautono($this->_tabel, 'supplier_code', 'SC'),
                        'supplier_name' => $supplier_name,
                        'email' => strtolower($email),
                        'address' => $address,
                        'phone' => $phone . $phone2,
                    ];
                    $process = $this->insert($data_array);
                    $data_access['users_id'] = $process;
                    $response['messages'] = 'Successfully Insert Data Supplier';

                    foreach ($this->input->post('kt_docs_repeater_advanced') as $res) {
                        $select_brand_id = $res['select_brand_id'];
                        $select_type_ownership = $res['select_type_ownership'];
                        $data_insert =
                            [
                                'users_ms_suppliers_id' => $process,
                                'users_ms_brands_id' => $select_brand_id,
                                'users_ms_ownership_types_id' => $select_type_ownership,
                            ];
                        $process2 = $this->insertCustom($data_insert, $this->_table_ms_suppliers_brands);
                    }
                } else {

                    $data = $this->get(['id' => $id]);
                    if (!$data) {
                        $response['messages'] = 'Data update invalid';
                        throw new Exception();
                    }

                    $data_array = [
                        'supplier_name' => $supplier_name,
                        'email' => strtolower($email),
                        'address' => $address,
                        'phone' => $phone . $phone2,
                    ];
                    $process = $this->update(['id' => $id], $data_array);
                    $data_access['users_id'] = $process;
                    $response['messages'] = 'Successfully Update Data Supplier';
                    if (!$process) {
                        $response['messages'] = 'Failed Update Data Supplier';
                        throw new Exception();
                    }

                    $this->db->select('*');
                    $this->db->from($this->_table_ms_suppliers_brands);
                    $this->db->where('deleted_at IS NULL');
                    $this->db->where('users_ms_suppliers_id', $id);

                    $data_brand = $this->db->get()->result_array();
                    $array_supplier_brand_id = [];

                    foreach ($this->input->post('kt_docs_repeater_advanced') as $res) {

                        $brand = $res['select_brand_id'];
                        $type_ownership = $res['select_type_ownership'];
                        if (empty($res['idBrands'])) {
                            $data_insert = [
                                'users_ms_suppliers_id' => $id,
                                'users_ms_brands_id' => $brand,
                                'users_ms_ownership_types_id' => $type_ownership,
                            ];
                            $process2 = $this->insertCustom($data_insert, $this->_table_ms_suppliers_brands);
                        } else {
                            $array_supplier_brand_id[] = $res['idBrands'];
                            $data = $this->get(array('id' => $id));
                            if (!$data) {
                                $response['messages'] = 'Data update invalid';
                                throw new Exception;
                            }
                            $data_validation = [
                                'users_ms_brands_id' => $brand,
                                'users_ms_ownership_types_id' => $type_ownership,
                            ];
                            $this->db->where('id', $res['idBrands']);
                            $process2 = $this->db->update($this->_table_ms_suppliers_brands, $data_validation);
                        }
                    }

                    if (count($data_brand) > 0) {
                        foreach ($data_brand as $data => $nilai) {
                            $cari = array_search($nilai['id'], $array_supplier_brand_id);
                            if ($cari === false) {
                                $array_where = array(
                                    'users_ms_suppliers_id' => $id,
                                    'id' => $nilai['id'],
                                );
                                $softDelete2 = $this->softDeleteCustom($this->_table_ms_suppliers_brands, null, $array_where);
                            }
                        }
                    }

                    if (!$process || !$process2) {
                        $response['messages'] = 'Failed Update Data Supplier';
                        throw new Exception;
                    }
                    $response['messages'] = "Successfully Update Data Supplier";
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

    public function changeStatus($id)
    {
        try {
            if ($id == null) {
                throw new Exception('Failed change status', 1);
            }

            $get = $this->get(['id' => $id]);
            if (!$get) {
                throw new Exception('Failed change status', 1);
            }

            if ($get->user_api == 1) {
                throw new Exception("Sorry, you don't have permission to change status this item", 1);
            }

            if ($get->email == $this->_session_email) {
                throw new Exception("Sorry,you don't have permission to change status this item", 1);
            }

            $status = $get->status == 1 ? 0 : 1;
            $update = $this->update(['id' => $id], ['status' => $status]);
            if (!$update) {
                throw new Exception('Failed change status', 1);
            }

            return true;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function deleteData($id)
    {
        $this->db->trans_begin();
        try {
            if ($id == null) {
                throw new Exception('Failed delete item', 1);
            }

            $get = $this->get(['id' => $id]);
            if (!$get) {
                throw new Exception('Failed delete item', 1);
            }

            $softDelete2 = $this->softDeleteCustom($this->_table_ms_suppliers_brands, 'users_ms_suppliers_id', $id);
            $softDelete = $this->softDeleteCustom($this->_table_ms_suppliers, 'id', $id);

            if (!$softDelete || !$softDelete2) {
                throw new Exception('Failed delete item', 1);
            }

            $this->db->trans_commit();
            return true;
        } catch (Exception $e) {
            $this->db->trans_rollback();
            return $e->getMessage();
        }
    }

    public function process_data($getData)
    {
        $rData = [];

        foreach ($getData as $res) {
            if (isset($res['SUPPLIER_NAME(*)']) && !empty($res['SUPPLIER_NAME(*)']) && $res['SUPPLIER_NAME(*)'] != "") {

                if (!empty($res['SUPPLIER_NAME(*)'])) {
                    $supplier_name = $res['SUPPLIER_NAME(*)'];
                    $validate_check = 1;
                } else {
                    $supplier_name = $res['SUPPLIER_NAME(*)'] . "<span class='badge badge-light-danger fw-bold'>Supplier Name is Required</span>";
                    $validate_check = 2;
                }

                if (!empty($res['EMAIL(*)'])) {
                    if (filter_var($res['EMAIL(*)'], FILTER_VALIDATE_EMAIL)) {
                        $email = $res['EMAIL(*)'];
                    } else {
                        $email = $res['EMAIL(*)'] . "<span class='badge badge-light-danger fw-bold'>Is Not Email Format</span>";
                        $validate_check = 2;
                    }
                } else {
                    $email = $res['EMAIL(*)'] . "<span class='badge badge-light-danger fw-bold'>Email is Required</span>";
                    $validate_check = 2;
                }

                if (!empty($res['PHONE_1(*)'])) {
                    if (is_numeric($res['PHONE_1(*)'])) {
                        $phone_1 = $res['PHONE_1(*)'];
                    } else {
                        $phone_1 = $res['PHONE_1(*)'] . "<span class='badge badge-light-danger fw-bold'>Phone is Not Number</span>";
                        $validate_check = 2;
                    }
                } else {
                    $phone_1 = $res['PHONE_1(*)'] . "<span class='badge badge-light-danger fw-bold'>Phone is Required</span>";
                    $validate_check = 2;
                }

                if (!empty($res['PHONE_2'])) {
                    if (is_numeric($res['PHONE_2'])) {
                        $phone_2 = $res['PHONE_2'];
                    } else {
                        $phone_2 = $res['PHONE_2'] . "<span class='badge badge-light-danger fw-bold'>Phone is Not Number</span>";
                        $validate_check = 2;
                    }
                } else {
                    $phone_2 = $res['PHONE_2'];
                }

                if (!empty($res['ADDRESS'])) {
                    $address = $res['ADDRESS'];
                } else {
                    $address = $res['ADDRESS'] . "<span class='badge badge-light-danger fw-bold'>Address is Required</span>";
                    $validate_check = 2;
                }

                if (!empty($res['BRAND_NAME(*)'])) {
                    $cek_brand = $this->getDataBrands2()->get(['brand_name' => $res['BRAND_NAME(*)']]);
                    if (!empty($cek_brand)) {
                        $brand_name = $res['BRAND_NAME(*)'];
                    } else {
                        $brand_name = $res['BRAND_NAME(*)'] . "<span class='badge badge-light-danger fw-bold'>Brand is Not Exist</span>";
                        $validate_check = 2;
                    }
                } else {
                    $brand_name = $res['BRAND_NAME(*)'] . "<span class='badge badge-light-danger fw-bold'>Brand Name is Required</span>";
                    $validate_check = 2;
                }

                if (!empty($res['TYPE_OWNERSHIP_NAME'])) {
                    $cek_type_ownership = $this->getDataProductsOwnershipTypes()->get(['ownership_type_name' => $res['TYPE_OWNERSHIP_NAME']]);
                    if (!empty($cek_type_ownership)) {
                        $type_ownership_name = $res['TYPE_OWNERSHIP_NAME'];
                    } else {
                        $type_ownership_name = $res['TYPE_OWNERSHIP_NAME'] . "<span class='badge badge-light-danger fw-bold'>Ownership is Not Exist</span>";
                        $validate_check = 2;
                    }
                } else {
                    $type_ownership_name = $res['TYPE_OWNERSHIP_NAME'];
                }

                $row =
                    [
                        'supplier_name' => $supplier_name,
                        'email' => $email,
                        'address' => $address,
                        'phone_1' => $phone_1,
                        'phone_2' => $phone_2,
                        'brand_name' => $brand_name,
                        'type_ownership_name' => $type_ownership_name,
                        'validate' => $validate_check,
                    ];
                array_push($rData, $row);
            }
        }

        $output =
            [
                "data" => $rData,
            ];

        return $output;
    }
}
