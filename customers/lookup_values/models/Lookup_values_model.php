<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Lookup_values_model extends MY_Model
{
    use MY_Tables;

    public function __construct()
    {
        $this->_tabel = $this->_table_ms_lookup_values;
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

    public function show($button = '')
    {
        $this->datatables->select("id,lookup_code,lookup_name,lookup_config", false);
        $this->datatables->from("{$this->_table_ms_lookup_values}");
        $this->datatables->where("deleted_at is null", null, false);
        $this->datatables->order_by('updated_at desc');
        $this->datatables->add_column('action', $button, 'id');
        $fieldSearch = [
            "lookup_code",
            "lookup_name",
            "lookup_config"
        ];
        $this->_searchDefaultDatatables($fieldSearch);
        return $this->datatables->generate();
    }

    private function _validate()
    {
        $response = array('success' => false, 'validate' => true, 'messages' => []);
        $response['type'] = !empty($this->input->post('id')) ? 'update' : 'insert';

        $role = array('trim', 'required', 'xss_clean');
        $role_code = array('trim', 'required', 'xss_clean', 'numeric');

        $this->form_validation->set_rules('lookup_code', 'Lookup Code', $role_code);
        $this->form_validation->set_rules('lookup_name', 'Lookup Name', $role);
        $this->form_validation->set_rules('lookup_config', 'Lookup Config', $role);

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
            if (isset($_POST['lookup_code_1'])) {
                for ($y = 0; $y < count($_POST['lookup_code_1']); $y++) {
                    $lookup_config = $this->input->post('lookup_config_1')[$y];
                    $lookup_config_v = strtolower(preg_replace('/\s+/', '_', $lookup_config));
                    $data_array = [
                        'lookup_code' => $this->input->post('lookup_code_1')[$y],
                        'lookup_name' => $this->input->post('lookup_name_1')[$y],
                        'lookup_config' => $lookup_config_v,
                    ];

                    $this->insert($data_array);

                    $response['type'] = 'insert';
                    $response['validate'] = true;
                    $response['messages'] = 'Successfully Insert Data Lookup Values';
                }
            } else {

                $response = self::_validate();

                if (!$response['validate']) {
                    throw new Exception('Error Processing Request', 1);
                }

                $id = clearInput($this->input->post('id'));
                $lookup_code = clearInput($this->input->post('lookup_code'));
                $lookup_name = clearInput($this->input->post('lookup_name'));
                $lookup_config = clearInput($this->input->post('lookup_config'));
                $lookup_config_v = strtolower(preg_replace('/\s+/', '_', $lookup_config));

                if (empty($id)) {

                    $data_array = [
                        'lookup_code' => $lookup_code,
                        'lookup_name' => $lookup_name,
                        'lookup_config' => $lookup_config_v,
                    ];

                    $process = $this->insert($data_array);

                    $data_access['users_id'] = $process;

                    $response['messages'] = 'Successfully Insert Data Lookup Values';
                } else {

                    $data = $this->get(['id' => $id]);

                    $data_array = [
                        'lookup_code' => $lookup_code,
                        'lookup_name' => $lookup_name,
                        'lookup_config' => $lookup_config_v,
                    ];

                    if (!$data) {
                        $response['messages'] = 'Data update invalid';
                        throw new Exception();
                    }

                    $process = $this->update(['id' => $id], $data_array);

                    if (!$process) {
                        $response['messages'] = 'Failed Update Data Lookup Values';
                        throw new Exception();
                    }

                    $response['messages'] = 'Successfully Update Data Lookup Values';
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

            $get = $this->get(array('id' => $id));

            if (!$get) {
                throw new Exception("Data not Register", 1);
            }

            $table = array(
                'id' => $get->id,
                'lookup_code' => $get->lookup_code,
                'lookup_name' => $get->lookup_name,
                'lookup_config' => $get->lookup_config,
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

            if (!$softDelete) {
                throw new Exception("Failed delete item", 1);
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

            if (isset($res['LOOKUP CODE']) && isset($res['LOOKUP NAME']) && isset($res['LOOKUP CONFIG'])) {

                $row =
                    [
                        'lookup_code' => $res['LOOKUP CODE'],
                        'lookup_name' => $res['LOOKUP NAME'],
                        'lookup_config' => $res['LOOKUP CONFIG'],
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
