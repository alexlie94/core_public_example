<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Master_warehouse extends MY_Customers
{

    public function __construct()
    {
        $this->_function_except = ['account', 'settings', 'show', 'process', 'status', 'paging', 'upload_data'];
        parent::__construct();
    }

    public function index()
    {
        $this->template->title('Data Warehouse');
        $this->setTitlePage('Data Warehouse');
        $this->assetsBuild(['datatables']);
        $data = [
            'searchBy' => ['warehouse_code' => 'Warehouse Code', 'warehouse_name' => 'Warehouse Name'],
        ];
        $header_table = ['no', 'warehouse code', 'warehouse name', 'email', 'address', 'phone', 'action'];
        $this->setTable($header_table, true);
        $this->setJs('master_warehouse');
        $this->template->build('v_show', $data);
    }

    public function show()
    {
        // isAjaxRequestWithPost();
        $this->function_access('view');
        $this->_custom_button_on_table = [
            [
                'button' => 'update',
                'type' => 'modal',
                'url' => base_url() . "master_warehouse/update/$1",
            ],
            [
                'button' => 'delete',
                'type' => 'confirm',
                'title' => 'Warehouse',
                'confirm' => 'Are you sure you want to delete this item ?',
                'url' => base_url() . "master_warehouse/delete/$1",
            ]
        ];
        $button = $this->setButtonOnTable();
        echo $this->master_warehouse_model->show($button);
    }

    public function insert()
    {
        isAjaxRequestWithPost();

        $data = array(
            'title_modal' => 'Add New Warehouse',
            'url_form' => base_url() . "master_warehouse/process",
            'form' => $this->load->view('v_form', '', true),
        );
        $html = $this->load->view($this->_v_form_modal, $data, true);

        echo json_encode(array('html' => $html));
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

        $response = $this->master_warehouse_model->save();
        echo json_encode($response);
        exit();
    }

    public function update($id)
    {
        isAjaxRequestWithPost();
        try {
            if ($id == null) {
                throw new Exception("Failed to request Edit", 1);
            }

            $dataItems = $this->master_warehouse_model->getItems($id);

            if (!is_array($dataItems)) {
                throw new Exception($dataItems, 1);
            }

            $data = array(
                'title_modal' => 'Edit Warehouse',
                'url_form' => base_url() . "master_warehouse/process",
                'form' => $this->load->view('v_form', $dataItems, true),
            );

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

    public function status($id = null)
    {
        isAjaxRequestWithPost();
        $this->function_access('update');
        $response = array('text' => 'Successfully change status item', 'success' => true);
        try {
            $process = $this->users_model->changeStatus($id);
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

    public function delete($id = null)
    {
        isAjaxRequestWithPost();
        $response = array('text' => 'Successfully delete item', 'success' => true);
        try {
            $process = $this->master_warehouse_model->deleteData($id);
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

        $processing_data = $this->master_warehouse_model->process_data($getData);

        echo json_encode($processing_data);
    }
}
