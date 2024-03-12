<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Application_update_model extends MY_Model
{
    use MY_Tables;

    public function __construct()
    {
        $this->_tabel = $this->_table_admins_ms_application_update;
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
            "a.id,
            a.title,
            a.launch_date,
            b.lookup_name",
            false
        );
        $this->datatables->from("{$this->_tabel} a");
        $this->datatables->join("{$this->_table_ms_lookup_values} b", "b.lookup_code = a.status", "inner");
        $this->datatables->where("b.lookup_config", "status");
        $this->datatables->where("a.deleted_at is null", null, false);
        $this->datatables->order_by('a.updated_at desc');
        $this->datatables->add_column('action', $button, 'id');
        $fieldSearch = [
            "a.title",
            "a.launch_date",
            "b.lookup_name",
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
        $this->form_validation->set_rules('launch_date', 'Launch Date', $role);
        $this->form_validation->set_rules('status', 'Status', $role);
        $this->form_validation->set_rules('v_content', 'Content', $role);

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
            $launch_date = clearInput($this->input->post('launch_date'));
            $status = clearInput($this->input->post('status'));
            $content = $this->input->post('kt_docs_ckeditor_classic');

            $data_array = array(
                'title' => $title,
                'content' => $content,
                'launch_date' => $launch_date,
                'status' => $status,
            );

            if (empty($id)) {

                $process = $this->insert($data_array);

                if (!$process) {
                    $response['messages'] = 'Failed Insert Data Application Update';
                    throw new Exception;
                }

                $response['messages'] = "Successfully Insert Data Application Update";
            } else {
                $data = $this->get(array('id' => $id));

                if (!$data) {
                    $response['messages'] = 'Data update invalid';
                    throw new Exception;
                }

                $process = $this->update(array('id' => $id), $data_array);

                if (!$process) {
                    $response['messages'] = 'Failed update data Application Update';
                    throw new Exception;
                }

                $response['messages'] = 'Successfully Update Data Application Update';
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
                'content' => $get->content,
                'launch_date' => $get->launch_date,
                'status' => $get->status,
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
