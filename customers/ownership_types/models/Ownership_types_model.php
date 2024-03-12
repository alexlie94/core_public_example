<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ownership_types_model extends MY_ModelCustomer
{
    use MY_Tables;

    public function __construct()
    {
        $this->_tabel = $this->_table_users_ms_ownership_types;
        parent::__construct();
    }

    public function validateOwnershipTypesCode($value)
    {
        try {
            $this->db->select('*');
            $this->db->from("{$this->_table_users_ms_ownership_types}");
            $this->db->where("ownership_type_code", $value);
            $this->db->where('deleted_at IS NULL');
            return $this->db->get()->row_array();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function getItems($id)
    {
        try {
            $this->db->select('*');
            $this->db->from("{$this->_table_users_ms_ownership_types}");
            $this->db->where("id", $id);
            $this->db->where('deleted_at IS NULL');
            return $this->db->get()->row_array();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function show($button = '')
    {
        $this->datatables->select(
            "a.id,
            a.ownership_type_code,
            a.ownership_type_name,
            a.status",
            false,
        );
        $this->datatables->from("{$this->_table_users_ms_ownership_types} a");
        $this->datatables->where('a.deleted_at is null', null, false);
        $this->datatables->where(["a.{$this->_table_users_ms_companys}_id" => $this->_users_ms_companys_id]);
        $this->datatables->order_by('a.updated_at desc');

        $button = "<button class=\"btn btn-outline btn-outline-dashed btn-outline-success btn-active-light-success hover-scale btn-sm fw-bold me-2 mb-2 btnEdit\" data-type=\"modal\" data-fullscreenmodal=\"0\" data-url=\"" . base_url("ownership_types/update/$1") . "\" data-id =\"$1\"><i class=\"bi bi-pencil-square fs-4 me-2\"></i>Edit</button>";
        $button .= "<button class=\"btn btn-outline btn-outline-dashed btn-outline-danger btn-active-light-danger hover-scale btn-sm fw-bold mb-2\" data-url=\"" . base_url("ownership_types/delete/$1") . "\" data-type=\"confirm\" data-textconfirm=\"Are you sure you want to delete this item ?\" data-title=\"Item\" data-id =\"$1\"><i class=\"bi bi-trash fs-4 me-2\"></i>Delete</button>";

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
                    case 'ownership_type_code':
                        $this->datatables->like('a.ownership_type_code', $setValue);
                        break;
                    case 'ownership_type_name':
                        $this->datatables->like('a.ownership_type_name', $setValue);
                        break;
                    default:
                        break;
                }
            }
        }

        $fieldSearch = [
            "a.ownership_type_code",
            "a.ownership_type_name",
            "a.status"
        ];

        $this->_searchDefaultDatatables($fieldSearch);

        return $this->datatables->generate();
    }

    private function _validate()
    {
        $response = ['success' => false, 'validate' => true, 'messages' => []];

        $response['type'] = !empty($this->input->post('id')) ? 'update' : 'insert';

        $role_validate = ['trim', 'required', 'xss_clean'];

        $this->form_validation->set_rules('ownership_type_name', 'Ownership Type Name', $role_validate);
        $this->form_validation->set_rules('status', 'Status', $role_validate);

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
            if (isset($_POST['ownership_type_name_1'])) {
                for ($y = 0; $y < count($_POST['ownership_type_name_1']); $y++) {
                    $data_array = [
                        'ownership_type_code' => mkautono($this->_tabel, 'ownership_type_code', 'OTC'),
                        'ownership_type_name' => $this->input->post('ownership_type_name_1')[$y],
                    ];

                    $this->insert($data_array);

                    $response['type'] = 'insert';
                    $response['validate'] = true;
                    $response['messages'] = 'Successfully Insert Data Ownership Types';
                }
            } else {

                $response = self::_validate();

                if (!$response['validate']) {
                    throw new Exception('Error Processing Request', 1);
                }

                $id = clearInput($this->input->post('id'));
                $ownership_type_name = clearInput($this->input->post('ownership_type_name'));
                $status = clearInput($this->input->post('status'));

                if (empty($id)) {

                    $data_array = [
                        'ownership_type_code' => mkautono($this->_tabel, 'ownership_type_code', 'OTC'),
                        'ownership_type_name' => $ownership_type_name,
                        'status' => $status,
                    ];

                    $process = $this->insert($data_array);

                    $data_access['users_id'] = $process;

                    $response['messages'] = 'Successfully Insert Data Types Ownership';
                } else {

                    $data = $this->get(['id' => $id]);

                    $data_array = [
                        'ownership_type_name' => $ownership_type_name,
                        'status' => $status,
                    ];

                    if (!$data) {
                        $response['messages'] = 'Data update invalid';
                        throw new Exception();
                    }

                    $process = $this->update(['id' => $id], $data_array);

                    if (!$process) {
                        $response['messages'] = 'Failed Update Data Types Ownership';
                        throw new Exception();
                    }

                    $response['messages'] = 'Successfully Update Data Types Ownership';
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

            if (isset($res['OWNERSHIP_TYPE_NAME(*)'])) {

                $validate_check = $this->validateOwnershipTypesCode($res['OWNERSHIP_TYPE_NAME(*)']);
                if (empty($validate_check)) {
                    $code = $res['OWNERSHIP_TYPE_NAME(*)'];
                    $validate_check = 1;
                } else {
                    $code = $res['OWNERSHIP_TYPE_NAME(*)'] . "<span class='ms-2 badge badge-light-danger fw-bold'>The Name Already Exist</span>";
                    $validate_check = 2;
                }

                $row =
                    [
                        'ownership_type_name' => $res['OWNERSHIP_TYPE_NAME(*)'],
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
}
