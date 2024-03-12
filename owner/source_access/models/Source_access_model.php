<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Source_access_model extends MY_Model
{
    use MY_Tables;

    public function __construct()
    {
        $this->_tabel = $this->_table_users_ms_company_sources;
        parent::__construct();
    }

    public function _getRole()
    {
        $this->_ci->load->model('rolepermissions/Rolepermissions_model', 'rolepermissions_model');
        return $this->_ci->rolepermissions_model;
    }

    public function _getAccess()
    {
        $this->_ci->load->model('access/Access_model', 'access_model');
        return $this->_ci->access_model;
    }

    public function getCompany()
    {
        $this->db->select('*');
        $this->db->from($this->_table_users_ms_companys);
        $this->db->where('deleted_at IS NULL', null, false);
        $this->db->where('status', 1);
        return $this->db->get()->result_array();
    }

    public function getSource($id)
    {
        $this->db->select('b.id, b.source_name');
        $this->db->from("{$this->_tabel} a");
        $this->db->join("{$this->_table_admins_ms_sources} b", "b.id = a.admins_ms_sources_id", "inner");
        $this->db->where("a.deleted_at is null", null, false);
        $this->db->where("b.deleted_at is null", null, false);
        $this->db->where('b.status', 1);
        $this->db->where('a.id', $id);
        return $this->db->get()->row_array();
    }

    public function getSourceInEdit($id)
    {
        $data = $this->get(array('id' => $id));

        $this->db->select('*');
        $this->db->from($this->_table_admins_ms_sources);
        $this->db->where("id not in(select admins_ms_sources_id from {$this->_table_users_ms_company_sources} where users_ms_companys_id = {$data->users_ms_companys_id} )");
        $this->db->where('deleted_at IS NULL', null, false);
        $this->db->where('status', 1);
        return $this->db->get()->result_array();
    }

    public function getCompanySource($company_id)
    {
        $this->db->select('*');
        $this->db->from($this->_table_admins_ms_sources);
        $this->db->where("id not in(select admins_ms_sources_id from {$this->_table_users_ms_company_sources} where users_ms_companys_id = {$company_id} )");
        $this->db->where('deleted_at IS NULL', null, false);
        $this->db->where('status', 1);
        return $this->db->get()->result();
    }

    public function getEndpoints($id)
    {
        $this->db->select('*');
        $this->db->from($this->_table_admins_ms_endpoints);
        $this->db->where('deleted_at IS NULL', null, false);
        $this->db->where('status', 1);
        $this->db->where('admins_ms_sources_id', $id);
        $result =  $this->db->get()->result();

        $html = '';
        $x = 1;
        foreach ($result as $row) {
            $html .= '<div class="col-md-3 mb-4">
            <div class="form-check d-flex align-items-center">
                <input class="form-check-input" type="checkbox" value="' . $row->id . '" name="endpoints_id[]" id="endpoints_id_' . $x . '" />
                <label class="form-check-label ms-3" for="endpoints_id_' . $x . '">
                    ' . $row->title . '
                </label>
            </div>
        </div>';
            $x++;
        }

        return $html;
    }

    public function show($button = '')
    {
        $this->datatables->select(
            "a.id,
            b.company_name,
            c.source_name,
            a.status",
            false
        );
        $this->datatables->from("{$this->_tabel} a");
        $this->datatables->join("{$this->_table_users_ms_companys} b", "b.id = a.users_ms_companys_id", "inner");
        $this->datatables->join("{$this->_table_admins_ms_sources} c", "c.id = a.admins_ms_sources_id", "inner");
        $this->datatables->where("a.deleted_at is null", null, false);
        $this->datatables->where("b.deleted_at is null", null, false);
        $this->datatables->where("c.deleted_at is null", null, false);
        $this->datatables->order_by('a.updated_at desc');
        $this->datatables->add_column('action', $button, 'id');
        $fieldSearch = [
            "a.id",
            "b.company_name",
            "c.source_name",
            "a.status"
        ];
        $this->_searchDefaultDatatables($fieldSearch);
        return $this->datatables->generate();
    }

    private function _validate()
    {
        $response = array('success' => false, 'validate' => true, 'messages' => []);
        $response['type'] = !empty($this->input->post('id')) ? 'update' : 'insert';

        $role = array('trim', 'required', 'xss_clean');

        $this->form_validation->set_rules('users_ms_companys_id', 'Company Name', $role);
        $this->form_validation->set_rules('admins_ms_sources_id', 'Source Name', $role);
        $this->form_validation->set_rules('status', 'Status', $role);

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

            $id = clearInput($this->input->post('id'));
            $users_ms_companys_id = clearInput($this->input->post('users_ms_companys_id'));
            $admins_ms_sources_id = clearInput($this->input->post('admins_ms_sources_id'));
            $status = clearInput($this->input->post('status'));

            $data_array = array(
                'users_ms_companys_id' => $users_ms_companys_id,
                'admins_ms_sources_id' => $admins_ms_sources_id,
                'status' => $status,
            );

            $this->db->select("*");
            $this->db->from("{$this->_table_users_ms_authenticate_channels}");
            $this->db->where('sources_id', $admins_ms_sources_id);
            $this->db->where('users_ms_companys_id', $users_ms_companys_id);
            $this->db->where('deleted_at IS NULL', null, false);
            $data_check = $this->db->get()->result_array();

            if (empty($id)) {
                $process = $this->insert($data_array);

                if (!$process) {
                    $response['messages'] = 'Failed Insert Data Source Access';
                    throw new Exception;
                }

                if ($this->input->post('endpoints_id') != null) {
                    for ($y = 0; $y < count($this->input->post('endpoints_id')); $y++) {
                        $endpoint_id = $this->input->post('endpoints_id')[$y];
                        $data_endpoints = array(
                            'users_ms_companys_id' => $users_ms_companys_id,
                            'status' => 1,
                            'admins_ms_endpoints_id' => $endpoint_id
                        );
                        $this->insertCustom($data_endpoints, $this->_table_admins_ms_company_endpoints);
                    }
                }

                if ($status == 1) {

                    if (!empty($data_check)) {
                        $array_auth =
                            [
                                'status' => 1
                            ];
                        $this->db->where('sources_id',  $admins_ms_sources_id);
                        $this->db->where('users_ms_companys_id', $users_ms_companys_id);
                        $this->db->update($this->_table_users_ms_authenticate_channels, $array_auth);
                    }
                } else {

                    if (!empty($data_check)) {
                        $array_auth =
                            [
                                'status' => 0
                            ];
                        $this->db->where('sources_id',  $admins_ms_sources_id);
                        $this->db->where('users_ms_companys_id', $users_ms_companys_id);
                        $this->db->update($this->_table_users_ms_authenticate_channels, $array_auth);
                    }
                }

                $response['messages'] = "Insert Data Source Access Success";
            } else {
                $data = $this->get(array('id' => $id));

                if (!$data) {
                    $response['messages'] = 'Data update invalid';
                    throw new Exception;
                }

                $process = $this->update(array('id' => $id), $data_array);

                if (!$process) {
                    $response['messages'] = 'Failed update data';
                    throw new Exception;
                }

                $this->db->select("t2.id, t2.admins_ms_endpoints_id, t2.status, t2.enabled_by_admin");
                $this->db->from("{$this->_table_users_ms_company_sources} t0");
                $this->db->join("{$this->_table_admins_ms_endpoints} t1", "t1.admins_ms_sources_id = t0.admins_ms_sources_id", "left");
                $this->db->join("{$this->_table_admins_ms_company_endpoints} t2", "t2.admins_ms_endpoints_id = t1.id AND t2.users_ms_companys_id = t0.users_ms_companys_id", "left");
                $this->db->where("t0.id", $id);
                $this->db->where('t2.deleted_at IS NULL', null, false);
                $data_endpoints = $this->db->get()->result_array();

                for ($x = 0; $x < count($data_endpoints); $x++) {
                    if (empty($data_endpoints[$x]['id'])) {
                        if (isset($this->input->post('endpoints_id')[$x])) {
                            $array_endpoints =
                                [
                                    'users_ms_companys_id' => $users_ms_companys_id,
                                    'status' => $status,
                                    'admins_ms_endpoints_id' => $this->input->post('endpoints_id')[$x]
                                ];
                            $this->insertCustom($array_endpoints, $this->_table_admins_ms_company_endpoints);
                        }
                    } else {
                        if ($this->input->post('endpoints_id') == null) {
                            $array_endpoints_company =
                                [
                                    'status' => 0,
                                    'enabled_by_admin' => 0,
                                ];
                            $this->db->where('id', $data_endpoints[$x]['id']);
                            $this->db->update($this->_table_admins_ms_company_endpoints, $array_endpoints_company);
                        } else {
                            $cari = array_search($data_endpoints[$x]['admins_ms_endpoints_id'], $this->input->post('endpoints_id'));
                            if ($cari === false) {
                                if ($data_endpoints[$x]['enabled_by_admin'] == 1) {
                                    $array_endpoints_company =
                                        [
                                            'status' => 0,
                                            'enabled_by_admin' => 0,
                                        ];
                                    $this->db->where('id', $data_endpoints[$x]['id']);
                                    $this->db->update($this->_table_admins_ms_company_endpoints, $array_endpoints_company);
                                }
                            } else {
                                if ($data_endpoints[$x]['enabled_by_admin'] == 0) {
                                    $array_endpoints_company =
                                        [
                                            'status' => 0,
                                            'enabled_by_admin' => 1,
                                        ];
                                    $this->db->where('id', $data_endpoints[$x]['id']);
                                    $this->db->update($this->_table_admins_ms_company_endpoints, $array_endpoints_company);
                                }
                            }
                        }
                    }
                }

                if ($status == 1) {

                    if (!empty($data_check)) {
                        $array_auth =
                            [
                                'status' => 1
                            ];
                        $this->db->where('sources_id',  $admins_ms_sources_id);
                        $this->db->where('users_ms_companys_id', $users_ms_companys_id);
                        $this->db->update($this->_table_users_ms_authenticate_channels, $array_auth);
                    }
                } else {

                    if (!empty($data_check)) {
                        $array_auth =
                            [
                                'status' => 0
                            ];
                        $this->db->where('sources_id',  $admins_ms_sources_id);
                        $this->db->where('users_ms_companys_id', $users_ms_companys_id);
                        $this->db->update($this->_table_users_ms_authenticate_channels, $array_auth);
                    }
                }

                $response['messages'] = 'Update Data Source Access Success';
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

            $get = $this->get(array('id' => $id));

            if (!$get) {
                throw new Exception("Data not Register", 1);
            }

            $table = array(
                'id' => $get->id,
                'users_ms_companys_id' => $get->users_ms_companys_id,
                'admins_ms_sources_id' => $get->admins_ms_sources_id,
                'status' => $get->status
            );

            return $table;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function deleteData($id)
    {
        $this->db->trans_begin();
        try {
            if ($id == null) {
                throw new Exception("Failed delete item", 1);
            }

            $get = $this->get(array('id' => $id));

            if (!$get) {
                throw new Exception("Failed delete item", 1);
            }

            $softDelete = $this->softDelete($id);
            $this->db->select('t2.id');
            $this->db->from("{$this->_table_users_ms_company_sources} t0");
            $this->db->join("{$this->_table_admins_ms_endpoints} t1", "t1.admins_ms_sources_id = t0.admins_ms_sources_id", "left");
            $this->db->join("{$this->_table_admins_ms_company_endpoints} t2", "t2.admins_ms_endpoints_id = t1.id AND t2.users_ms_companys_id = t0.users_ms_companys_id", "left");
            $this->db->where('t0.id', $id);
            $this->db->where('t2.deleted_at IS NULL', null, false);
            $data_endpoints = $this->db->get()->result_array();

            for ($x = 0; $x < count($data_endpoints); $x++) {
                if (isset($data_endpoints[$x]['id'])) {
                    $id = $data_endpoints[$x]['id'];
                    $array_endpoints =
                        [
                            'deleted_at' => date('Y-m-d H:i:s')
                        ];
                    $this->db->where('id', $id);
                    $this->db->update($this->_table_admins_ms_company_endpoints, $array_endpoints);
                }
            }

            $array_auth =
                [
                    'deleted_at' => date('Y-m-d H:i:s')
                ];
            $this->db->where('sources_id', $get->admins_ms_sources_id);
            $this->db->where('users_ms_companys_id', $get->users_ms_companys_id);
            $this->db->update($this->_table_users_ms_authenticate_channels, $array_auth);

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

    public function getDataDetail($id)
    {
        $this->db->select(' t1.id
                           ,t1.title
                           ,IFNULL(t2.enabled_by_admin,0) AS check_access
                          ');
        $this->db->from("{$this->_table_users_ms_company_sources} t0");
        $this->db->join("{$this->_table_admins_ms_endpoints} t1", "t1.admins_ms_sources_id = t0.admins_ms_sources_id", "left");
        $this->db->join("{$this->_table_admins_ms_company_endpoints} t2", "t2.admins_ms_endpoints_id = t1.id AND t2.users_ms_companys_id = t0.users_ms_companys_id", "left");
        $this->db->where('t0.id', $id);
        $this->db->where('t2.deleted_at IS NULL', null, false);
        $result =  $this->db->get()->result();
        $html = '';
        $x = 1;
        foreach ($result as $row) {
            if ($row->id != null) {
                $checked = $row->check_access ? 'checked' : '';
                $html .= '<div class="col-md-3 mb-4">
                <div class="form-check d-flex align-items-center">
                    <input class="form-check-input" type="checkbox" value="' . $row->id . '" name="endpoints_id[]" id="endpoints_id_' . $x . '" ' . $checked . '/>
                    <label class="form-check-label ms-3" for="endpoints_id_' . $x . '">
                        ' . $row->title . '
                    </label>
                </div>
            </div>';
            }
            $x++;
        }

        return $html;
    }
}
