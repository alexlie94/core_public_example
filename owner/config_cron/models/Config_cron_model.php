<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Config_cron_model extends MY_Model
{
    use MY_Tables;

    public function __construct()
    {
        $this->_tabel = $this->_table_admins_config_cron;
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
        $this->datatables->select(
            "id,
            cron_controller,
            cron_desc,
            status",
            false
        );
        $this->datatables->from("{$this->_tabel}");
        $this->datatables->where("deleted_at is null", null, false);
        $this->datatables->order_by('updated_at desc');
        $this->datatables->add_column('action', $button, 'id');
        $fieldSearch = [
            "cron_controller",
            "cron_desc",
            "status"
        ];
        $this->_searchDefaultDatatables($fieldSearch);
        return $this->datatables->generate();
    }

    private function _validate()
    {
        $response = array('success' => false, 'validate' => true, 'messages' => []);
        $response['type'] = !empty($this->input->post('id')) ? 'update' : 'insert';

        $role = array('trim', 'required', 'xss_clean');
        $role_cron = array('trim', 'required', 'xss_clean');
        $validation_cron = array(
            'validation_cron', function ($value) {
                $cron_controller = preg_replace('/\s+/', '', $_POST['cron_controller']);
                if (!empty($value) || $value != '') {
                    try {
                        $cek = $this->db->where(array('cron_controller' => $cron_controller))->get('admins_config_cron')->num_rows();
                        if ($cek) {
                            throw new Exception();
                        }
                        return true;
                    } catch (Exception $e) {
                        $this->form_validation->set_message('validation_cron', 'The {field} already used');
                        return false;
                    }
                }
            }
        );
        array_push($role_cron, $validation_cron);

        $this->form_validation->set_rules('cron_controller', 'Cron Controller', $role_cron);
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
            $cron_controller = clearInput($this->input->post('cron_controller'));
            $cron_desc = clearInput($this->input->post('cron_desc'));
            $status = clearInput($this->input->post('status'));

            $data_array = array(
                'cron_controller' => preg_replace('/\s+/', '', $cron_controller),
                'cron_desc' => $cron_desc,
                'status' => $status,
                'updated_by' => $this->_created_by,
            );

            if (empty($id)) {

                $process = $this->db->insert($this->_tabel, $data_array);

                if (!$process) {
                    $response['messages'] = 'Failed Insert Data Config Cron';
                    throw new Exception;
                }

                $response['messages'] = "Successfully Insert Data Config Cron";
            } else {
                $data = $this->get(array('id' => $id));

                if (!$data) {
                    $response['messages'] = 'Data update invalid';
                    throw new Exception;
                }

                $process = $this->update(array('id' => $id), $data_array);

                if (!$process) {
                    $response['messages'] = 'Failed update data user';
                    throw new Exception;
                }

                $response['messages'] = 'Successfully Update Data Config Cron';
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
                'cron_controller' => $get->cron_controller,
                'cron_desc' => $get->cron_desc,
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
}
