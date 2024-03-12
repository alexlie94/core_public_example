<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Matrix extends MY_Customers
{

    public function __construct()
    {
        $this->_function_except = ['account', 'settings', 'show', 'process', 'status', 'paging', 'upload_data', 'add_parent'];
        parent::__construct();
    }

    public function Index()
    {
        $this->template->title('Category');
        $this->setTitlePage('Data Category');
        $this->assetsBuild(['datatables']);
        $this->_custom_button_header = [
            [
                'button' => 'insert',
                'label' => 'Create New Category',
                'type' => 'modal',
                'url' => base_url() . 'category/insert',
            ],
        ];

        $cardSearch = [
            [
                'label' => 'Category Code',
                'type' => 'input',
                'name' => 'filter_category_code'
            ],
            [
                'label' => 'Category Name',
                'type' => 'input',
                'name' => 'filter_category_name'
            ]
        ];

        $this->cardSearch($cardSearch);

        $header_table = ['no', 'category code', 'category name', 'action'];

        $this->setTable($header_table, true);
        $this->setJs('category');

        $this->template->build($this->_v_show);
    }

    public function show()
    {
        // isAjaxRequestWithPost();
        $this->function_access('view');
        $this->_custom_button_on_table = [
            [
                'button' => 'update',
                'type' => 'modal',
                'url' => base_url() . "category/update/$1",
            ],
            [
                'button' => 'delete',
                'type' => 'confirm',
                'title' => 'Item',
                'confirm' => 'Are you sure you want to delete this item ?',
                'url' => base_url() . "category/delete/$1",
            ],
        ];

        $button = $this->setButtonOnTable();

        echo $this->category_model->show($button);
    }

    public function insert()
    {
        isAjaxRequestWithPost();

        $get['dataCategory'] = $this->category_model->get_all();

        $data = [
            'title_modal' => 'Add New Category',
            'url_form' => base_url() . 'category/process',
            'form' => $this->load->view('v_form', $get, true),
        ];

        $html = $this->load->view($this->_v_form_modal, $data, true);

        echo json_encode(['html' => $html]);
        exit();
    }

    public function process()
    {
        isAjaxRequestWithPost();
        if (!empty($this->input->post('id'))) {
            $this->function_access('update');
        } else {
            $this->function_access('insert');
        }

        $response = $this->category_model->save();

        echo json_encode($response);
        exit();
    }

    public function update($id)
    {
        isAjaxRequestWithPost();
        try {
            $get['dataCategory'] = $this->category_model->get_all();
            $get['dataItems'] = $this->category_model->getItems($id);

            $data = [
                'title_modal' => 'Edit Category',
                'url_form' => base_url() . 'category/process',
                'form' => $this->load->view('v_form', $get, true),
            ];

            $html = $this->load->view($this->_v_form_modal, $data, true);
            $response['html'] = $html;
            echo json_encode($response);
            exit();
        } catch (Exception $e) {
            $response['failed'] = true;
            $response['message'] = $e->getMessage();
            echo json_encode($response);
            exit();
        }
    }

    public function delete($id = null)
    {
        isAjaxRequestWithPost();
        $response = ['text' => 'Successfully delete item', 'success' => true];
        try {
            $process = $this->category_model->deleteData($id);
            if ($process !== true) {
                throw new Exception($process, 1);
            }
            echo json_encode($response);
            exit();
        } catch (Exception $e) {
            $response['text'] = $e->getMessage();
            $response['success'] = false;
            echo json_encode($response);
            exit();
        }
    }

    public function upload_data()
    {
        $getData = json_decode($_POST['dataUpload'], true);

        $this->category_model->proccess_data($getData);
    }

    public function add_parent()
    {
        $this->category_model->manage_add_parent();
    }
}
