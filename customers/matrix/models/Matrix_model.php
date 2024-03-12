<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Matrix_model extends MY_ModelCustomer
{
    use MY_Tables;
    public function __construct()
    {
        $this->_tabel = $this->_table_users_ms_matrix;
        parent::__construct();
    }

    public function show($button = '')
    {
        $this->datatables->select(" a.id as id,
                                    a.categories_code,
                                    a.categories_name as catgry_name ", false);

        $this->datatables->from("{$this->_table_category} a");

        $this->datatables->where("a.deleted_at is null", null, false);

        $filters = !empty($this->input->post('filters')) ? $this->input->post('filters') : false;

        if ($filters !== false && is_array($filters)) {
            foreach ($filters as $ky => $val) {
                $value = $val['value'];
                if (!empty($value)) {
                    switch ($val['name']) {
                        case 'filter_category_code':
                            $this->datatables->like('a.categories_code', $value);
                            break;
                        case 'filter_category_name':
                            $this->datatables->like('a.categories_name', $value);
                            break;
                    }
                }
            }
        }

        $fieldSearch = [
            'a.categories_code',
            'a.categories_name',
        ];

        $this->_searchDefaultDatatables($fieldSearch);

        $this->datatables->order_by('a.updated_at desc');

        $this->datatables->add_column('action', $button, 'id');

        return $this->datatables->generate();
    }

    private function _validate()
    {
        $response = array('success' => false, 'validate' => true, 'messages' => []);
        $response['type'] = !empty($this->input->post('id')) ? 'update' : 'insert';

        $role_cat_name = array('trim', 'required', 'xss_clean');

        $this->form_validation->set_rules('category_name', 'Category Name', $role_cat_name);

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

            if ($this->input->post('category_code_bulk')) {

                if (!empty($this->input->post('parent_name_cat'))) {
                    foreach ($this->input->post('parent_name_cat') as $list) {

                        $datas = [
                            'categories_name' => $list,
                            'parent_categories_id' => 0
                        ];

                        $this->insert($datas);
                    }
                }

                for ($i = 0; $i < count($this->input->post('category_code_bulk')); $i++) {

                    $get_category_id = $this->get(['categories_name' => $this->input->post('parent_category_bulk')[$i]]);

                    $data_array = [
                        'categories_code' => $this->input->post('category_code_bulk')[$i],
                        'categories_name' => $this->input->post('category_name_bulk')[$i],
                        'parent_categories_id' => !empty($get_category_id->id) ? $get_category_id->id : 0
                    ];

                    $this->insert($data_array);
                }

                $response['type'] = 'insert';
                $response['validate'] = true;
                $response['messages'] = 'Successfully Insert Data Brand';
            } else {

                $response = self::_validate();

                if (!$response['validate']) {
                    throw new Exception("Error Processing Request", 1);
                }

                $id = clearInput($this->input->post('id'));
                $cat_code = !empty($this->input->post('category_code')) ? clearInput($this->input->post('category_code')) : '';
                $cat_name = clearInput($this->input->post('category_name'));
                $parent_id = clearInput($this->input->post('parent_id'));

                $array_category_data = array(
                    'categories_code' => $cat_code,
                    'categories_name' => $cat_name,
                    'parent_categories_id' => $parent_id
                );

                if (empty($id)) {
                    $cek_category_name = $this->get(['categories_name' => $cat_name]);

                    if (!empty($cek_category_name)) {
                        $response['messages'] = 'Data Already Exists';
                        throw new Exception;
                    }

                    $execute = $this->insert($array_category_data);

                    if (!$execute) {
                        $response['messages'] = 'Failed Insert Data Category';
                        throw new Exception;
                    }

                    $response['data']['id'] = $execute;
                    $response['data']['name'] = $cat_name;
                    $response['messages'] = "Successfully Insert Data Category";
                } else {
                    $data = $this->get(array('id' => $id));

                    if (!$data) {
                        $response['messages'] = 'Data Update Invalid';
                        throw new Exception();
                    }

                    $cek_category_name = $this->get(['id !=' => $id, 'categories_name' => $cat_name]);

                    if (!empty($cek_category_name)) {
                        $response['messages'] = 'Data Already Exists';
                        throw new Exception;
                    }

                    $process = $this->update(array('id' => $id), $array_category_data);

                    if (!$process) {
                        $response['messages'] = 'Failed Update Data Category';
                        throw new Exception;
                    }

                    $response['messages'] = 'Successfully Update Data Category';
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
                'categories_code' => $get->categories_code,
                'categories_name' => $get->categories_name,
                'parent_categories_id' => $get->parent_categories_id
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
            $cek_use_id = $this->get(['parent_categories_id' => $id]);

            if ($cek_use_id) {
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

    public function proccess_data($getData)
    {
        $rData = [];

        $category_data = [];
        $data_parent_name = [];

        // Push Category Name
        foreach ($getData as $res) {

            if (!empty($res['CATEGORY_CODE'])) {
                array_push($data_parent_name, $res['PARENT_CATEGORY_NAME']);
            }
        }

        //Push Category Id
        foreach (array_unique($data_parent_name) as $list) {
            $get_category_id = $this->get(['categories_name' => $list]);

            if (empty($get_category_id)) {
                $row2 =
                    [
                        'category_name' => $list,
                    ];

                array_push($category_data, $row2);
            }
        }

        foreach ($getData as $res) {

            if (!empty($res['CATEGORY_CODE'])) {

                $cek_category = $this->get(['categories_name' => $res['CATEGORY_NAME']]);

                if (!empty($cek_category)) {
                    $data_check =  $res['CATEGORY_NAME'] . '<span class="ms-2 badge badge-light-danger fw-bold">Already Exist</span>';
                    $validate_check = 2;
                } else {
                    if ($res['CATEGORY_NAME'] == '') {
                        $data_check = '<span class="ms-2 badge badge-light-danger fw-bold">Data Empty</span>';
                        $validate_check = 2;
                    } else {
                        $data_check = $res['CATEGORY_NAME'];
                    }
                }

                $row =
                    [
                        'category_code'   => $res['CATEGORY_CODE'],
                        'category_name'   => $data_check,
                        'parent_category'  => $res['PARENT_CATEGORY_NAME'],
                        'validate'     => empty($validate_check) ? '' : $validate_check,
                    ];

                $rData[] = $row;
            }
        }



        $output =
            [
                "data" => $rData,
                "category_data" => $category_data
            ];

        echo json_encode($output);
    }

    public function manage_add_parent()
    {

        $get_result = $this->get_all();

        $rData = [];

        foreach ($get_result as $res) {

            $row =
                [
                    'category_id' => $res->id,
                    'category_name'   => $res->categories_name,
                ];

            $rData[] = $row;
        }

        $output =
            [
                "data" => $rData
            ];

        echo json_encode($output);
    }
}
