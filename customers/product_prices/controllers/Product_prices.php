<?php
defined('BASEPATH') or exit('No direct script access allowed');
require FCPATH . 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Product_prices extends MY_Customers
{

    public function __construct()
    {
        date_default_timezone_set('Asia/Jakarta');
        $this->_function_except = ['account', 'settings', 'show', 'process', 'status', 'paging', 'upload_data', 'download_view', 'view', 'update_status_batch'];
        parent::__construct();
        $this->_searchBy = [
            'id' => 'Batch ID',
            'batch_name' => 'Batch Name',
            'batch_description' => 'Batch Description',
            'admins_ms_sources_id' => 'Batch Location',
            'start_date' => 'Start Date',
            'end_date' => 'End Date'
        ];
    }

    public function Index()
    {
        $this->template->title('Product Prices');
        $this->setTitlePage('Product Prices');
        $this->assetsBuild(['datatables', 'repeater']);
        $data = [
            'searchBy' => $this->_searchBy,
            'sources' => $this->product_prices_model->getSource(),
        ];

        $header_table = ['Batch ID', 'Batch Name', 'Batch Description', 'Batch Location', 'Start Date', 'End Date', 'Action'];
        $this->setTable($header_table, false);
        $this->setJs('product_prices');
        $this->template->build('v_show', $data);
    }

    public function show()
    {
        isAjaxRequestWithPost();
        $this->function_access('view');
        $this->_custom_button_on_table = [
            [
                'button' => 'update',
                'type' => 'modal',
                'fullscreen' => true,
                'url' => base_url() . "product_prices/update/$1",
            ],
            [
                'button' => 'delete',
                'type' => 'confirm',
                'title' => 'Item',
                'confirm' => 'Are you sure you want to delete this item ?',
                'url' => base_url() . "product_prices/delete/$1",
            ],
        ];

        $button = $this->setButtonOnTable();

        echo $this->product_prices_model->show($button);
    }

    public function insert()
    {
        isAjaxRequestWithPost();
        $get['sources'] = $this->product_prices_model->getSource();
        $data = [
            'title_modal' => 'Batch Form',
            'url_form' => base_url() . 'product_prices/process',
            'form' => $this->load->view('v_form', $get, true),
            'buttonCloseID' => 'btnCloseModalFullscreen',
            'buttonName' => 'Create Batch'
        ];

        $html = $this->load->view($this->_v_form_modal, $data, true);

        echo json_encode(['html' => $html]);
        exit();
    }

    public function view($id)
    {
        isAjaxRequestWithPost();
        $get['dataItems'] = $this->product_prices_model->getItems($id);
        $get['sources'] = $this->product_prices_model->getSource();
        $get['showDataTable'] = $this->db->get_where('users_ms_batchs_detail', ['users_ms_batchs_id' => $get['dataItems']['id']])->result_array();
        $data = [
            'title_modal' => 'Download View',
            'url_form' => base_url() . 'product_prices/process',
            'form' => $this->load->view('v_form', $get, true),
            'buttonCloseID' => 'btnCloseModalFullscreen',
        ];

        $html = $this->load->view($this->_v_form_modal, $data, true);

        echo json_encode(['html' => $html]);
        exit();
    }

    public function download_view()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        foreach (range('A', 'J') as $coulumID) {
            $spreadsheet->getActiveSheet()->getColumnDimension($coulumID)->setAutosize(true);
        }
        $sheet->setCellValue('A1', 'BATCH ID');
        $sheet->setCellValue('B1', 'BATCH NAME');
        $sheet->setCellValue('C1', 'BATCH DESCRIPTION');
        $sheet->setCellValue('D1', 'BATCH LOCATION');
        $sheet->setCellValue('E1', 'START DATE');
        $sheet->setCellValue('F1', 'PRODUCT NAME');
        $sheet->setCellValue('G1', 'PRICE');
        $sheet->setCellValue('H1', 'SALE PRICE');
        $sheet->setCellValue('I1', 'OFFLINE PRICE');
        $sheet->setCellValue('J1', 'END DATE');

        $users = $this->product_prices_model->showCsv();
        $users_data = json_decode($users);

        $x = 2; //start from row 2
        foreach ($users_data->data as $row) {
            $sheet->setCellValue('A' . $x, $row->id);
            $sheet->setCellValue('B' . $x, $row->batch_name);
            $sheet->setCellValue('C' . $x, $row->batch_description);
            $sheet->setCellValue('D' . $x, $row->batch_location);
            $sheet->setCellValue('E' . $x, $row->start_date);
            $sheet->setCellValue('F' . $x, $row->product_name);
            $sheet->setCellValue('G' . $x, $row->price);
            $sheet->setCellValue('H' . $x, $row->sale_price);
            $sheet->setCellValue('I' . $x, $row->offline_price);
            $sheet->setCellValue('J' . $x, $row->end_date);
            $x++;
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = 'Product_Price_Report.xlsx';
        //$writer->save($fileName);  //this is for save in folder

        /* for force download */
        header('Content-Type: appliction/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        $writer->save('php://output');
        /* force download end */
    }

    public function process()
    {
        isAjaxRequestWithPost();
        if (!empty($this->input->post('id'))) {
            $this->function_access('update');
        } else {
            $this->function_access('insert');
        }

        $response = $this->product_prices_model->save();

        echo json_encode($response);

        exit();
    }

    public function update($id)
    {
        isAjaxRequestWithPost();
        try {
            $get['dataItems'] = $this->product_prices_model->getItems($id);
            $get['sources'] = $this->product_prices_model->getSource();
            $get['showDataTable'] = $this->db->get_where('users_ms_batchs_detail', ['users_ms_batchs_id' => $get['dataItems']['id']])->result_array();

            $data = [
                'title_modal' => 'Edit Product Prices',
                'url_form' => base_url() . 'product_prices/process',
                'form' => $this->load->view('v_form', $get, true),
                'buttonCloseID' => 'btnCloseModalFullscreen',
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
            $process = $this->product_prices_model->deleteData($id);
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

        $processing_data = $this->product_prices_model->process_data($getData);

        echo json_encode($processing_data);
    }

    public function update_status_batch()
    {
        $processing_data = $this->product_prices_model->process_update_batch();

        echo json_encode($processing_data);
    }
}
