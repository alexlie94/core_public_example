<?php

use PhpParser\Node\Expr\Isset_;

defined('BASEPATH') or exit('No direct script access allowed');

class Master_warehouse_model extends MY_ModelCustomer
{
    use MY_Tables;

    public function __construct()
    {
        $this->_tabel = $this->_table_ms_master_warehouse;
        parent::__construct();
    }

    public function show($button = '')
    {
        $this->datatables->select(
            "a.id as id,
            a.warehouse_code,
            a.warehouse_name,
            a.email,
            a.address,
            a.phone,",
            false,
        );
        $this->datatables->from("{$this->_table_ms_master_warehouse} a");
        $this->datatables->where('a.deleted_at is null', null, false);
        $this->datatables->where(["a.{$this->_table_users_ms_companys}_id" => $this->_users_ms_companys_id]);
        $this->datatables->order_by('a.updated_at desc');

        $button = "<button class=\"btn btn-outline btn-outline-dashed btn-outline-success btn-active-light-success hover-scale btn-sm fw-bold me-2 mb-2 btnEdit\" data-type=\"modal\" data-fullscreenmodal=\"0\" data-url=\"" . base_url("master_warehouse/update/$1") . "\" data-id =\"$1\"><i class=\"bi bi-pencil-square fs-4 me-2\"></i>Edit</button>";
        $button .= "<button class=\"btn btn-outline btn-outline-dashed btn-outline-danger btn-active-light-danger hover-scale btn-sm fw-bold mb-2\" data-url=\"" . base_url("master_warehouse/delete/$1") . "\" data-type=\"confirm\" data-textconfirm=\"Are you sure you want to delete this item ?\" data-title=\"Item\" data-id =\"$1\"><i class=\"bi bi-trash fs-4 me-2\"></i>Delete</button>";

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
                    case 'warehouse_code':
                        $this->datatables->like('a.warehouse_code', $setValue);
                        break;
                    case 'warehouse_name':
                        $this->datatables->like('a.warehouse_name', $setValue);
                        break;
                    default:
                        break;
                }
            }
        }

        $get_value_master_requisition = $this->input->post('master_reqisition');
        if (!empty($get_value_master_requisition)) {
            $this->datatables->like('a.warehouse_code', $get_value_master_requisition);
            $this->datatables->or_like('a.warehouse_name', $get_value_master_requisition);
            $this->datatables->or_like('a.email', $get_value_master_requisition);
            $this->datatables->or_like('a.address', $get_value_master_requisition);
            $this->datatables->or_like('a.phone', $get_value_master_requisition);
        }

        $fieldSearch = [
            "a.warehouse_code",
            "a.warehouse_name",
            "a.email",
            "a.address",
            "a.phone"
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
                'warehouse_code' => $get->warehouse_code,
                'warehouse_name' => $get->warehouse_name,
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
        $role_validate_email = ['trim', 'required', 'xss_clean', 'valid_email'];
        $role_validate_phone = ['trim', 'required', 'xss_clean', 'numeric'];

        $this->form_validation->set_rules('warehouse_name', 'Warehouse Name', $role_validate);
        $this->form_validation->set_rules('email', 'Email', $role_validate_email);
        $this->form_validation->set_rules('address', 'Address', $role_validate);
        $this->form_validation->set_rules('phone', 'Phone', $role_validate_phone);

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
            if (isset($_POST['warehouse_name_1'])) {
                for ($y = 0; $y < count($_POST['warehouse_name_1']); $y++) {
                    $data_array = [
                        'warehouse_code' => mkautono($this->_tabel, 'warehouse_code', 'WC'),
                        'warehouse_name' => $this->input->post('warehouse_name_1')[$y],
                        'email' => $this->input->post('email_1')[$y],
                        'address' => $this->input->post('address_1')[$y],
                        'phone' => $this->input->post('phone_1')[$y],
                        'users_ms_companys_id' => $this->_users_ms_companys_id,
                    ];

                    $this->insert($data_array);

                    $response['type'] = 'insert';
                    $response['validate'] = true;
                    $response['messages'] = 'Successfully Insert Data Warehouse';
                }
            } else {

                $response = self::_validate();

                if (!$response['validate']) {
                    throw new Exception('Error Processing Request', 1);
                }

                $id = clearInput($this->input->post('id'));
                $warehouse_name = clearInput($this->input->post('warehouse_name'));
                $email = clearInput($this->input->post('email'));
                $address = clearInput($this->input->post('address'));
                $phone = clearInput($this->input->post('phone'));

                if (empty($id)) {

                    $data_array = [
                        'warehouse_code' => mkautono($this->_tabel, 'warehouse_code', 'WC'),
                        'warehouse_name' => $warehouse_name,
                        'email' => strtolower($email),
                        'address' => $address,
                        'phone' => $phone,
                    ];

                    $process = $this->insert($data_array);

                    $data_access['users_id'] = $process;

                    $response['messages'] = 'Successfully Insert Data Warehouse';
                } else {

                    $data = $this->get(['id' => $id]);

                    $data_array = [
                        'warehouse_name' => $warehouse_name,
                        'email' => strtolower($email),
                        'address' => $address,
                        'phone' => $phone,
                    ];

                    if (!$data) {
                        $response['messages'] = 'Data update invalid';
                        throw new Exception();
                    }

                    $process = $this->update(['id' => $id], $data_array);

                    if (!$process) {
                        $response['messages'] = 'Failed Update Data Warehouse';
                        throw new Exception();
                    }

                    $response['messages'] = 'Successfully Update Data Warehouse';
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
                throw new Exception("Sorry, you don't have permission to change status this Item", 1);
            }

            if ($get->email == $this->_session_email) {
                throw new Exception("Sorry,you don't have permission to change status this Item", 1);
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

            $softDelete = $this->softDelete($id);

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

    public function process_data($getData)
    {
        $rData = [];

        foreach ($getData as $res) {

            if (isset($res['WAREHOUSE_NAME']) && isset($res['EMAIL'])) {

                $warehouse_name = $res['WAREHOUSE_NAME'];
                if (filter_var($res['EMAIL'], FILTER_VALIDATE_EMAIL)) {
                    $email = $res['EMAIL'];
                    if (is_numeric($res['PHONE'])) {
                        $phone = $res['PHONE'];
                        $validate_check = 1;
                    } else {
                        $phone = $res['PHONE'] . "<span class='ms-2 badge badge-light-danger fw-bold'>Invalid Email</span>";
                        $validate_check = 2;
                    }
                } else {
                    $email = $res['EMAIL'] . "<span class='ms-2 badge badge-light-danger fw-bold'>Invalid Email</span>";
                    $validate_check = 2;
                }

                $row =
                    [
                        'warehouse_name' => $warehouse_name,
                        'email' => $email,
                        'address' => $res['ADDRESS'],
                        'phone' => $phone,
                        'validate' => !empty($validate_check) ? $validate_check : '',
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
}
