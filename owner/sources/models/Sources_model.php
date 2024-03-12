<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sources_model extends MY_Model
{
    use MY_Tables;

    public function __construct()
    {
        $this->_tabel = $this->_table_admins_ms_sources;
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
            source_image,
            source_name,
            source_icon,
            source_url,
            app_keys,
            secret_keys,
            status",
            false
        );
        $this->datatables->from("{$this->_tabel}");
        $this->datatables->where("deleted_at is null", null, false);
        $this->datatables->order_by('updated_at desc');
        $this->datatables->add_column('action', $button, 'id');
        $fieldSearch = [
            "source_image",
            "source_name",
            "source_icon",
            "source_url",
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

        $this->form_validation->set_rules('source_name', 'Source Name', $role);
        $this->form_validation->set_rules('source_url', 'Source URL', $role);
        $this->form_validation->set_rules('status', 'Status', $role);
        $this->form_validation->set_rules('source_image1', 'Image', $role);
        $this->form_validation->set_rules('source_icon1', 'Icon', $role);

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
            $source_name = clearInput($this->input->post('source_name'));
            $source_image = clearInput($this->input->post('source_image'));
            $source_icon = clearInput($this->input->post('source_icon'));
            $source_url = clearInput($this->input->post('source_url'));
            $app_keys = clearInput($this->input->post('app_keys'));
            $secret_keys = clearInput($this->input->post('secret_keys'));
            $status = clearInput($this->input->post('status'));

            $data_array = array(
                'source_name' => $source_name,
                'source_url' => $source_url,
                'app_keys' => $app_keys,
                'secret_keys' => $secret_keys,
                'status' => $status,
            );

            //Image Upload
            $path = '../assets/uploads/channels_image/';
            $string_pieces = explode(";base64,", $source_image);
            $image_type_pieces = explode("image/", $string_pieces[0]);
            $image_type = !empty($image_type_pieces[1]) ? $image_type_pieces[1] : '';

            //Icon Upload
            $path_icon = '../assets/uploads/channels_image/';
            $string_pieces_icon = explode(";base64,", $source_icon);
            $image_type_pieces_icon = explode("image/", $string_pieces_icon[0]);
            $image_type_icon = !empty($image_type_pieces_icon[1]) ? $image_type_pieces_icon[1] : '';

            if (empty($id)) {

                $process = $this->insert($data_array);

                if (!$process) {
                    $response['messages'] = 'Failed Insert Data Source';
                    throw new Exception;
                }

                $nameImage = $process . '_' . date('mdY') . '_img_' . date('His') . '.' . $image_type;
                $nameIcon = $process . '_' . date('mdY') . '_icon_' . date('His') . '.' . $image_type_icon;

                if (!empty($this->input->post('source_image'))) {
                    $data_img = [
                        'source_image' => $nameImage,
                    ];
                    $this->update(['id' => $process], $data_img);

                    tf_convert_base64_to_image($source_image, $path, $nameImage);
                }

                if (!empty($this->input->post('source_icon'))) {
                    $data_img = [
                        'source_icon' => $nameIcon,
                    ];
                    $this->update(['id' => $process], $data_img);

                    tf_convert_base64_to_image($source_icon, $path_icon, $nameIcon);
                }

                $response['messages'] = "Successfully Insert Data Source";
            } else {
                $data = $this->get(array('id' => $id));

                if (!$data) {
                    $response['messages'] = 'Data update invalid';
                    throw new Exception;
                }

                $nameImage = $id . '_' . date('mdY') . '_' . $id . '_img_' . date('His') . '.' . $image_type;
                $nameIcon = $id . '_' . date('mdY') . '_' . $id . '_icon_' . date('His') . '.' . $image_type_icon;

                $data_update = array(
                    'source_name' => $source_name,
                    'source_url' => $source_url,
                    'app_keys' => $app_keys,
                    'secret_keys' => $secret_keys,
                    'status' => $status,
                );

                $process = $this->update(array('id' => $id), $data_update);

                if (!$process) {
                    $response['messages'] = 'Failed update data user';
                    throw new Exception;
                }

                if (!empty($this->input->post('source_image'))) {
                    $old_image = $this->input->post('old_source_image');
                    if (file_exists($path . $old_image)) {
                        unlink($path . $old_image);
                    }
                    $data_img = [
                        'source_image' => $nameImage,
                    ];
                    $this->update(['id' => $id], $data_img);

                    tf_convert_base64_to_image($source_image, $path, $nameImage);
                }

                if (!empty($this->input->post('source_icon'))) {
                    $old_icon = $this->input->post('old_source_icon');
                    if (file_exists($path_icon . $old_icon)) {
                        unlink($path_icon . $old_icon);
                    }
                    $data_img = [
                        'source_icon' => $nameIcon,
                    ];
                    $this->update(['id' => $id], $data_img);

                    tf_convert_base64_to_image($source_icon, $path_icon, $nameIcon);
                }

                $response['messages'] = 'Successfully Update Data Source';
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
                'source_name' => $get->source_name,
                'source_image' => $get->source_image,
                'source_icon' => $get->source_icon,
                'source_url' => $get->source_url,
                'app_keys' => $get->app_keys,
                'secret_keys' => $get->secret_keys,
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
            $path = '../assets/uploads/channels_image/';
            if ($id == null) {
                throw new Exception("Failed delete item", 1);
            }

            $get = $this->get(array('id' => $id));

            if (!$get) {
                throw new Exception("Failed delete item", 1);
            }

            $softDelete = $this->softDelete($id);
            if (file_exists($path . $get->source_image)) {
                unlink($path . $get->source_image);
            }
            if (file_exists($path . $get->source_icon)) {
                unlink($path . $get->source_icon);
            }

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

    public function getImage($imgName)
    {
        try {
            $this->db->select("*");
            $this->db->from("{$this->_tabel}");
            $this->db->where("source_image", $imgName);
            $this->db->where("deleted_at IS NULL");
            return $this->db->get()->row_array();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function getIcon($imgName)
    {
        try {
            $this->db->select("*");
            $this->db->from("{$this->_tabel}");
            $this->db->where("source_icon", $imgName);
            $this->db->where("deleted_at IS NULL");
            return $this->db->get()->row_array();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
