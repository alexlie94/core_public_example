<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Endpoints_model extends MY_Model
{
    use MY_Tables;

    public function __construct()
    {
        $this->_tabel = $this->_table_admins_ms_endpoints;
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

    public function getSource()
    {
        $this->db->select('*');
        $this->db->from($this->_table_admins_ms_sources);
        $this->db->where('deleted_at IS NULL', null, false);
        $this->db->where('status', 1);
        return $this->db->get()->result_array();
    }

    public function show($button = '')
    {
        $this->datatables->select(
            "a.id,
            a.title,
            b.source_name,
            a.endpoint_url,
            a.status",
            false
        );
        $this->datatables->from("{$this->_tabel} a");
        $this->datatables->join("{$this->_table_admins_ms_sources} b", "b.id = a.admins_ms_sources_id", "inner");
        $this->datatables->where("a.deleted_at is null", null, false);
        $this->datatables->where("b.deleted_at is null", null, false);
        $this->datatables->order_by('a.updated_at desc');
        $this->datatables->add_column('action', $button, 'id');
        $fieldSearch = [
            "a.id",
            "a.title",
            "b.source_name",
            "a.endpoint_url",
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

        $this->form_validation->set_rules('title', 'Title', $role);
        $this->form_validation->set_rules('admins_ms_sources_id', 'Source Name', $role);
        $this->form_validation->set_rules('endpoint_url', 'Endpoint URL', $role);
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
            $title = clearInput($this->input->post('title'));
            $endpoint_url = clearInput($this->input->post('endpoint_url'));
            $admins_ms_sources_id = clearInput($this->input->post('admins_ms_sources_id'));
            $status = clearInput($this->input->post('status'));

            $data_array = array(
                'title' => $title,
                'endpoint_url' => $endpoint_url,
                'status' => $status,
                'admins_ms_sources_id' => $admins_ms_sources_id,
            );

            if (empty($id)) {

                $process = $this->insert($data_array);

                if (!$process) {
                    $response['messages'] = 'Failed Insert Data Source Access';
                    throw new Exception;
                }

                $response['messages'] = "Insert Data Endpoints Success";
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

                $response['messages'] = 'Update Data Endpoints Success';
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
                'title' => $get->title,
                'endpoint_url' => $get->endpoint_url,
                'status' => $get->status,
                'admins_ms_sources_id' => $get->admins_ms_sources_id
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
                throw new Exception('Failed delete item', 1);
            }

            $this->db->trans_commit();
            return true;
        } catch (Exception $e) {
            $this->db->trans_rollback();
            return $e->getMessage();
        }
    }
}
