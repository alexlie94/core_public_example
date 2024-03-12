<?php
defined('BASEPATH') or exit('No direct script access allowed');
require FCPATH . 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Inventory_warehouse extends MY_Customers
{

    public function __construct()
    {
        $this->_function_except = ['account', 'settings', 'show_receiving', 'process', 'status', 'paging', 'summary', 'receiving', 'putaway', 'show_putaway', 'storage', 'show_storage', 'picking', 'show_picking', 'packing', 'show_packing', 'shipping', 'show_shipping', 'exports_receiving', 'exports_putaway', 'exports_storage', 'exports_picking', 'exports_packing', 'exports_shipping', 'storage_log', 'show_storage_log', 'receiving_log', 'putaway_log', 'picking_log', 'packing_log', 'shipping_log', 'show_receiving_log', 'show_putaway_log', 'show_picking_log', 'show_packing_log', 'show_shipping_log'];
        parent::__construct();
    }

    public function index()
    {
        $this->template->title('Inventory Warehouse');
        $this->assetsBuild(['datatables']);
        $this->setTitlePage('Inventory Warehouse');
        $this->setJs('inventory_warehouse');
        $get['warehouse_id'] = $this->inventory_warehouse_model->getDataWarehouse();
        $get['lastUpdatedStorage'] = $this->inventory_warehouse_model->lastUpdateStorage();
        $get['lastUpdatedReceiving'] = $this->inventory_warehouse_model->lastUpdateReceiving();
        $get['lastUpdatedPutaway'] = $this->inventory_warehouse_model->lastUpdatePutaway();
        $get['lastUpdatedPacking'] = $this->inventory_warehouse_model->lastUpdatePacking();
        $get['lastUpdatedPicking'] = $this->inventory_warehouse_model->lastUpdatePicking();
        $get['lastUpdatedShipping'] = $this->inventory_warehouse_model->lastUpdateShipping();
        $this->template->build('v_form', $get);
    }

    public function summary()
    {
        isAjaxRequestWithPost();
        $this->function_access('view');
        $get = $this->inventory_warehouse_model->getDataWarehouse();
        $id = $this->input->post('id');
        $count = count($get) == 0 ? 1 : count($get);

        for ($xy = 0; $xy < $count; $xy++) {
            switch ($id) {
                case 0:
                    $sumStorage = $this->inventory_warehouse_model->sumStorageDefault();
                    $sumReceivingInpro = $this->inventory_warehouse_model->sumReceivingInproDefault();
                    $sumReceivingClosed = $this->inventory_warehouse_model->sumReceivingClosedDefault();
                    $sumPutawayInpro = $this->inventory_warehouse_model->sumPutawayInproDefault();
                    $sumPutawayClosed = $this->inventory_warehouse_model->sumPutawayClosedDefault();
                    $sumPackingInpro = $this->inventory_warehouse_model->sumPackingInproDefault();
                    $sumPackingClosed = $this->inventory_warehouse_model->sumPackingClosedDefault();
                    $sumPickingInpro = $this->inventory_warehouse_model->sumPickingInproDefault();
                    $sumPickingClosed = $this->inventory_warehouse_model->sumPickingClosedDefault();
                    $sumShippingInpro = $this->inventory_warehouse_model->sumShippingInproDefault();
                    $sumShippingClosed = $this->inventory_warehouse_model->sumShippingClosedDefault();
                    $array = [
                        'receiving' => array(
                            'inProgress' => isset($sumReceivingInpro['total']) ? $sumReceivingInpro['total'] : '0',
                            'closed' => isset($sumReceivingClosed['total']) ? $sumReceivingClosed['total'] : '0',
                        ),
                        'putAway' => array(
                            'inProgress' => isset($sumPutawayInpro['total']) ? $sumPutawayInpro['total'] : '0',
                            'closed' => isset($sumPutawayClosed['total']) ? $sumPutawayClosed['total'] : '0',
                        ),
                        'storage' => array(
                            'total' => isset($sumStorage['total']) ? $sumStorage['total'] : '0',
                        ),
                        'picking' => array(
                            'inProgress' => isset($sumPickingInpro['total']) ? $sumPickingInpro['total'] : '0',
                            'closed' => isset($sumPickingClosed['total']) ? $sumPickingClosed['total'] : '0',
                        ),
                        'packing' => array(
                            'inProgress' => isset($sumPackingInpro['total']) ? $sumPackingInpro['total'] : '0',
                            'closed' => isset($sumPackingClosed['total']) ? $sumPackingClosed['total'] : '0',
                        ),
                        'shipping' => array(
                            'inProgress' => isset($sumShippingInpro['total']) ? $sumShippingInpro['total'] : '0',
                            'closed' => isset($sumShippingClosed['total']) ? $sumShippingClosed['total'] : '0',
                        )
                    ];
                    break;
                case $id:
                    $sumStorage = $this->inventory_warehouse_model->sumStorage($id);
                    $sumReceivingInpro = $this->inventory_warehouse_model->sumReceivingInpro($id);
                    $sumReceivingClosed = $this->inventory_warehouse_model->sumReceivingClosed($id);
                    $sumPutawayInpro = $this->inventory_warehouse_model->sumPutawayInpro($id);
                    $sumPutawayClosed = $this->inventory_warehouse_model->sumPutawayClosed($id);
                    $sumPackingInpro = $this->inventory_warehouse_model->sumPackingInpro($id);
                    $sumPackingClosed = $this->inventory_warehouse_model->sumPackingClosed($id);
                    $sumPickingInpro = $this->inventory_warehouse_model->sumPickingInpro($id);
                    $sumPickingClosed = $this->inventory_warehouse_model->sumPickingClosed($id);
                    $sumShippingInpro = $this->inventory_warehouse_model->sumShippingInpro($id);
                    $sumShippingClosed = $this->inventory_warehouse_model->sumShippingClosed($id);
                    $array = [
                        'receiving' => array(
                            'inProgress' => isset($sumReceivingInpro['total']) ? $sumReceivingInpro['total'] : '0',
                            'closed' => isset($sumReceivingClosed['total']) ? $sumReceivingClosed['total'] : '0',
                        ),
                        'putAway' => array(
                            'inProgress' => isset($sumPutawayInpro['total']) ? $sumPutawayInpro['total'] : '0',
                            'closed' => isset($sumPutawayClosed['total']) ? $sumPutawayClosed['total'] : '0',
                        ),
                        'storage' => array(
                            'total' => isset($sumStorage['total']) ? $sumStorage['total'] : '0',
                        ),
                        'picking' => array(
                            'inProgress' => isset($sumPickingInpro['total']) ? $sumPickingInpro['total'] : '0',
                            'closed' => isset($sumPickingClosed['total']) ? $sumPickingClosed['total'] : '0',
                        ),
                        'packing' => array(
                            'inProgress' => isset($sumPackingInpro['total']) ? $sumPackingInpro['total'] : '0',
                            'closed' => isset($sumPackingClosed['total']) ? $sumPackingClosed['total'] : '0',
                        ),
                        'shipping' => array(
                            'inProgress' => isset($sumShippingInpro['total']) ? $sumShippingInpro['total'] : '0',
                            'closed' => isset($sumShippingClosed['total']) ? $sumShippingClosed['total'] : '0',
                        )
                    ];
                    break;
            }
        }
        echo json_encode($array);
        exit();
    }

    public function receiving()
    {
        isAjaxRequestWithPost();
        $get['warehouse'] = $this->inventory_warehouse_model->getDataWarehouse();
        $get['lastUpdated'] = $this->inventory_warehouse_model->lastUpdateReceiving();

        $data = [
            'title_modal' => 'Preview Receiving',
            'url_form' => base_url() . 'inventory_warehouse/exports_receiving',
            'content' => $this->load->view('v_form2', $get, true),
            'buttonCloseID' => 'btnCloseModalFullscreen',
            'buttonSave' => true,
            'buttonID' => 'btnExportReceiving',
            'buttonName' => 'Download View',
            'buttonTypeSave' => 'redirect',
        ];

        $html = $this->load->view($this->_v_modal, $data, true);

        echo json_encode(['html' => $html]);
        exit();
    }

    public function putaway()
    {
        isAjaxRequestWithPost();
        $get['warehouse'] = $this->inventory_warehouse_model->getDataWarehouse();
        $get['lastUpdated'] = $this->inventory_warehouse_model->lastUpdatePutaway();

        $data = [
            'title_modal' => 'Preview Put Away',
            'url_form' => base_url() . 'inventory_warehouse/exports_putaway',
            'content' => $this->load->view('v_form3', $get, true),
            'buttonCloseID' => 'btnCloseModalFullscreen',
            'buttonSave' => true,
            'buttonID' => 'btnExportPutaway',
            'buttonName' => 'Download View',
            'buttonTypeSave' => 'redirect',
        ];

        $html = $this->load->view($this->_v_modal, $data, true);

        echo json_encode(['html' => $html]);
        exit();
    }

    public function storage()
    {
        isAjaxRequestWithPost();
        $get['warehouse'] = $this->inventory_warehouse_model->getDataWarehouse();
        $get['lastUpdated'] = $this->inventory_warehouse_model->lastUpdateStorage();

        $data = [
            'title_modal' => 'Preview Storage',
            'url_form' => base_url() . 'inventory_warehouse/exports_storage',
            'content' => $this->load->view('v_form4', $get, true),
            'buttonCloseID' => 'btnCloseModalFullscreen',
            'buttonSave' => true,
            'buttonID' => 'btnExportStorage',
            'buttonName' => 'Download View',
            'buttonTypeSave' => 'redirect',
        ];

        $html = $this->load->view($this->_v_modal, $data, true);

        echo json_encode(['html' => $html]);
        exit();
    }

    public function packing()
    {
        isAjaxRequestWithPost();
        $get['warehouse'] = $this->inventory_warehouse_model->getDataWarehouse();
        $get['lastUpdated'] = $this->inventory_warehouse_model->lastUpdatePacking();

        $data = [
            'title_modal' => 'Preview Packing',
            'url_form' => base_url() . 'inventory_warehouse/exports_packing',
            'content' => $this->load->view('v_form6', $get, true),
            'buttonCloseID' => 'btnCloseModalFullscreen',
            'buttonSave' => true,
            'buttonID' => 'btnExportPacking',
            'buttonName' => 'Download View',
            'buttonTypeSave' => 'redirect',
        ];

        $html = $this->load->view($this->_v_modal, $data, true);

        echo json_encode(['html' => $html]);
        exit();
    }

    public function picking()
    {
        isAjaxRequestWithPost();
        $get['warehouse'] = $this->inventory_warehouse_model->getDataWarehouse();
        $get['lastUpdated'] = $this->inventory_warehouse_model->lastUpdatePacking();

        $data = [
            'title_modal' => 'Preview Picking',
            'url_form' => base_url() . 'inventory_warehouse/exports_picking',
            'content' => $this->load->view('v_form5', $get, true),
            'buttonCloseID' => 'btnCloseModalFullscreen',
            'buttonSave' => true,
            'buttonID' => 'btnExportPicking',
            'buttonName' => 'Download View',
            'buttonTypeSave' => 'redirect',
        ];

        $html = $this->load->view($this->_v_modal, $data, true);

        echo json_encode(['html' => $html]);
        exit();
    }

    public function shipping()
    {
        isAjaxRequestWithPost();
        $get['warehouse'] = $this->inventory_warehouse_model->getDataWarehouse();
        $get['lastUpdated'] = $this->inventory_warehouse_model->lastUpdateShipping();

        $data = [
            'title_modal' => 'Preview Shipping',
            'url_form' => base_url() . 'inventory_warehouse/exports_shipping',
            'content' => $this->load->view('v_form7', $get, true),
            'buttonCloseID' => 'btnCloseModalFullscreen',
            'buttonSave' => true,
            'buttonID' => 'btnExportShipping',
            'buttonName' => 'Download View',
            'buttonTypeSave' => 'redirect',
        ];

        $html = $this->load->view($this->_v_modal, $data, true);

        echo json_encode(['html' => $html]);
        exit();
    }

    public function show_receiving()
    {
        echo $this->inventory_warehouse_model->show_receiving();
    }

    public function show_putaway()
    {
        echo $this->inventory_warehouse_model->show_putaway();
    }

    public function show_storage()
    {
        echo $this->inventory_warehouse_model->show_storage();
    }

    public function show_packing()
    {
        echo $this->inventory_warehouse_model->show_packing();
    }

    public function show_picking()
    {
        echo $this->inventory_warehouse_model->show_picking();
    }

    public function show_shipping()
    {
        echo $this->inventory_warehouse_model->show_shipping();
    }

    public function exports_receiving()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        foreach (range('A', 'H') as $coulumID) {
            $spreadsheet->getActiveSheet()->getColumnDimension($coulumID)->setAutosize(true);
        }
        $sheet->setCellValue('A1', 'PO Number');
        $sheet->setCellValue('B1', 'Brand');
        $sheet->setCellValue('C1', 'Supplier');
        $sheet->setCellValue('D1', 'Publisher');
        $sheet->setCellValue('E1', 'Date Created');
        $sheet->setCellValue('F1', 'Quantity');
        $sheet->setCellValue('G1', 'Quantity Receiving');
        $sheet->setCellValue('H1', 'Status');

        $users = $this->inventory_warehouse_model->show_receiving();
        $users_data = json_decode($users);

        $x = 2; //start from row 2
        foreach ($users_data->data as $row) {
            $sheet->setCellValue('A' . $x, $row->po_number);
            $sheet->setCellValue('B' . $x, $row->brand_name);
            $sheet->setCellValue('C' . $x, $row->supplier_name);
            $sheet->setCellValue('D' . $x, $row->publisher_name);
            $sheet->setCellValue('E' . $x, $row->created_at);
            $sheet->setCellValue('F' . $x, $row->qty);
            $sheet->setCellValue('G' . $x, $row->qty_receiving);
            $sheet->setCellValue('H' . $x, $row->lookup_name);
            $x++;
        }


        $writer = new Xlsx($spreadsheet);
        $fileName = 'Inventory_Warehouse_Receiving_Report.xlsx';

        /* for force download */
        header('Content-Type: appliction/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        $writer->save('php://output');
        /* force download end */
    }

    public function exports_putaway()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        foreach (range('A', 'I') as $coulumID) {
            $spreadsheet->getActiveSheet()->getColumnDimension($coulumID)->setAutosize(true);
        }
        $sheet->setCellValue('A1', 'PO Number');
        $sheet->setCellValue('B1', 'Brand');
        $sheet->setCellValue('C1', 'Supplier');
        $sheet->setCellValue('D1', 'Publisher');
        $sheet->setCellValue('E1', 'Date Created');
        $sheet->setCellValue('F1', 'Quantity');
        $sheet->setCellValue('G1', 'Quantity Receiving');
        $sheet->setCellValue('H1', 'Quantity Put Away');
        $sheet->setCellValue('I1', 'Status');

        $users = $this->inventory_warehouse_model->show_putaway();
        $users_data = json_decode($users);

        $x = 2; //start from row 2
        foreach ($users_data->data as $row) {
            $sheet->setCellValue('A' . $x, $row->po_number);
            $sheet->setCellValue('B' . $x, $row->brand_name);
            $sheet->setCellValue('C' . $x, $row->supplier_name);
            $sheet->setCellValue('D' . $x, $row->publisher_name);
            $sheet->setCellValue('E' . $x, $row->created_at);
            $sheet->setCellValue('F' . $x, $row->qty);
            $sheet->setCellValue('G' . $x, $row->qty_receiving);
            $sheet->setCellValue('H' . $x, $row->qty_putaway);
            $sheet->setCellValue('I' . $x, $row->lookup_name);
            $x++;
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = 'Inventory_Warehouse_Put_Away_Report.xlsx';
        //$writer->save($fileName);  //this is for save in folder

        /* for force download */
        header('Content-Type: appliction/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        $writer->save('php://output');
        /* force download end */
    }

    public function exports_storage()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        foreach (range('A', 'F') as $coulumID) {
            $spreadsheet->getActiveSheet()->getColumnDimension($coulumID)->setAutosize(true);
        }
        $sheet->setCellValue('A1', 'SKU');
        $sheet->setCellValue('B1', 'Product');
        $sheet->setCellValue('C1', 'Brand');
        $sheet->setCellValue('D1', 'Category');
        $sheet->setCellValue('E1', 'Size');
        $sheet->setCellValue('F1', 'Quantity');

        $users = $this->inventory_warehouse_model->show_storage();
        $users_data = json_decode($users);

        $x = 2; //start from row 2
        foreach ($users_data->data as $row) {
            $sheet->setCellValue('A' . $x, $row->sku);
            $sheet->setCellValue('B' . $x, $row->product_name);
            $sheet->setCellValue('C' . $x, $row->brand_name);
            $sheet->setCellValue('D' . $x, $row->category_name);
            $sheet->setCellValue('E' . $x, $row->product_size);
            $sheet->setCellValue('F' . $x, $row->qty);
            $x++;
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = 'Inventory_Warehouse_Storage_Report.xlsx';
        //$writer->save($fileName);  //this is for save in folder

        /* for force download */
        header('Content-Type: appliction/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        $writer->save('php://output');
        /* force download end */
    }

    public function exports_picking()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        foreach (range('A', 'H') as $coulumID) {
            $spreadsheet->getActiveSheet()->getColumnDimension($coulumID)->setAutosize(true);
        }
        $sheet->setCellValue('A1', 'Purchase Code');
        $sheet->setCellValue('B1', 'Customer Name');
        $sheet->setCellValue('C1', 'Customer Email');
        $sheet->setCellValue('D1', 'Date Created');
        $sheet->setCellValue('E1', 'Quantity');
        $sheet->setCellValue('F1', 'Quantity Picking');
        $sheet->setCellValue('G1', 'Assignee');
        $sheet->setCellValue('H1', 'Status');

        $users = $this->inventory_warehouse_model->show_picking();
        $users_data = json_decode($users);

        $x = 2; //start from row 2
        foreach ($users_data->data as $row) {
            $sheet->setCellValue('A' . $x, $row->purchase_code);
            $sheet->setCellValue('B' . $x, $row->customer_name);
            $sheet->setCellValue('C' . $x, $row->customer_email);
            $sheet->setCellValue('D' . $x, $row->created_at);
            $sheet->setCellValue('E' . $x, $row->qty);
            $sheet->setCellValue('F' . $x, $row->qty_picking);
            $sheet->setCellValue('G' . $x, $row->assignee);
            $sheet->setCellValue('H' . $x, $row->lookup_name);
            $x++;
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = 'Inventory_Warehouse_Picking_Report.xlsx';
        //$writer->save($fileName);  //this is for save in folder

        /* for force download */
        header('Content-Type: appliction/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        $writer->save('php://output');
        /* force download end */
    }

    public function exports_packing()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        foreach (range('A', 'H') as $coulumID) {
            $spreadsheet->getActiveSheet()->getColumnDimension($coulumID)->setAutosize(true);
        }
        $sheet->setCellValue('A1', 'Purchase Code');
        $sheet->setCellValue('B1', 'Customer Name');
        $sheet->setCellValue('C1', 'Customer Email');
        $sheet->setCellValue('D1', 'Date Created');
        $sheet->setCellValue('E1', 'Quantity');
        $sheet->setCellValue('F1', 'Quantity Packing');
        $sheet->setCellValue('G1', 'Assignee');
        $sheet->setCellValue('H1', 'Status');

        $users = $this->inventory_warehouse_model->show_packing();
        $users_data = json_decode($users);

        $x = 2; //start from row 2
        foreach ($users_data->data as $row) {
            $sheet->setCellValue('A' . $x, $row->purchase_code);
            $sheet->setCellValue('B' . $x, $row->customer_name);
            $sheet->setCellValue('C' . $x, $row->customer_email);
            $sheet->setCellValue('D' . $x, $row->created_at);
            $sheet->setCellValue('E' . $x, $row->qty);
            $sheet->setCellValue('F' . $x, $row->qty_packing);
            $sheet->setCellValue('G' . $x, $row->assignee);
            $sheet->setCellValue('H' . $x, $row->lookup_name);
            $x++;
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = 'Inventory_Warehouse_Packing_Report.xlsx';
        //$writer->save($fileName);  //this is for save in folder

        /* for force download */
        header('Content-Type: appliction/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        $writer->save('php://output');
        /* force download end */
    }

    public function exports_shipping()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        foreach (range('A', 'H') as $coulumID) {
            $spreadsheet->getActiveSheet()->getColumnDimension($coulumID)->setAutosize(true);
        }
        $sheet->setCellValue('A1', 'Purchase Code');
        $sheet->setCellValue('B1', 'Customer Name');
        $sheet->setCellValue('C1', 'Customer Email');
        $sheet->setCellValue('D1', 'Date Created');
        $sheet->setCellValue('E1', 'Quantity');
        $sheet->setCellValue('F1', 'Quantity Shipping');
        $sheet->setCellValue('G1', 'Assignee');
        $sheet->setCellValue('H1', 'Status');

        $users = $this->inventory_warehouse_model->show_shipping();
        $users_data = json_decode($users);

        $x = 2; //start from row 2
        foreach ($users_data->data as $row) {
            $sheet->setCellValue('A' . $x, $row->purchase_code);
            $sheet->setCellValue('B' . $x, $row->customer_name);
            $sheet->setCellValue('C' . $x, $row->customer_email);
            $sheet->setCellValue('D' . $x, $row->created_at);
            $sheet->setCellValue('E' . $x, $row->qty);
            $sheet->setCellValue('F' . $x, $row->qty_shipping);
            $sheet->setCellValue('G' . $x, $row->assignee);
            $sheet->setCellValue('H' . $x, $row->lookup_name);
            $x++;
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = 'Inventory_Warehouse_Shipping_Report.xlsx';
        //$writer->save($fileName);  //this is for save in folder

        /* for force download */
        header('Content-Type: appliction/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        $writer->save('php://output');
        /* force download end */
    }

    public function receiving_log()
    {
        isAjaxRequestWithPost();

        $data = [
            'title_modal' => 'Receiving Log',
            'url_form' => base_url() . 'inventory_warehouse/show_receiving_log',
            'content' => $this->load->view('receiving_log', '', true),
            'buttonCloseID' => 'btnCloseModalFullscreen',
            'buttonSave' => false,
        ];

        $html = $this->load->view($this->_v_modal, $data, true);

        echo json_encode(['html' => $html]);
        exit();
    }

    public function putaway_log()
    {
        isAjaxRequestWithPost();

        $data = [
            'title_modal' => 'Putaway Log',
            'url_form' => base_url() . 'inventory_warehouse/show_putaway_log',
            'content' => $this->load->view('putaway_log', '', true),
            'buttonCloseID' => 'btnCloseModalFullscreen',
            'buttonSave' => false,
        ];

        $html = $this->load->view($this->_v_modal, $data, true);

        echo json_encode(['html' => $html]);
        exit();
    }

    public function storage_log()
    {
        isAjaxRequestWithPost();

        $data = [
            'title_modal' => 'Storage Log',
            'url_form' => base_url() . 'inventory_warehouse/show_storage_log',
            'content' => $this->load->view('storage_log', '', true),
            'buttonCloseID' => 'btnCloseModalFullscreen',
            'buttonSave' => false,
        ];

        $html = $this->load->view($this->_v_modal, $data, true);

        echo json_encode(['html' => $html]);
        exit();
    }

    public function picking_log()
    {
        isAjaxRequestWithPost();

        $data = [
            'title_modal' => 'Picking Log',
            'url_form' => base_url() . 'inventory_warehouse/picking_log',
            'content' => $this->load->view('picking_log', '', true),
            'buttonCloseID' => 'btnCloseModalFullscreen',
            'buttonSave' => false,
        ];

        $html = $this->load->view($this->_v_modal, $data, true);

        echo json_encode(['html' => $html]);
        exit();
    }

    public function packing_log()
    {
        isAjaxRequestWithPost();

        $data = [
            'title_modal' => 'Packing Log',
            'url_form' => base_url() . 'inventory_warehouse/packing_log',
            'content' => $this->load->view('packing_log', '', true),
            'buttonCloseID' => 'btnCloseModalFullscreen',
            'buttonSave' => false,
        ];

        $html = $this->load->view($this->_v_modal, $data, true);

        echo json_encode(['html' => $html]);
        exit();
    }

    public function shipping_log()
    {
        isAjaxRequestWithPost();

        $data = [
            'title_modal' => 'Shipping Log',
            'url_form' => base_url() . 'inventory_warehouse/shipping_log',
            'content' => $this->load->view('shipping_log', '', true),
            'buttonCloseID' => 'btnCloseModalFullscreen',
            'buttonSave' => false,
        ];

        $html = $this->load->view($this->_v_modal, $data, true);

        echo json_encode(['html' => $html]);
        exit();
    }

    public function show_storage_log()
    {
        echo $this->inventory_warehouse_model->show_storage_log();
    }

    public function show_receiving_log()
    {
        echo $this->inventory_warehouse_model->show_receiving_log();
    }

    public function show_putaway_log()
    {
        echo $this->inventory_warehouse_model->show_putaway_log();
    }

    public function show_picking_log()
    {
        echo $this->inventory_warehouse_model->show_picking_log();
    }

    public function show_packing_log()
    {
        echo $this->inventory_warehouse_model->show_packing_log();
    }

    public function show_shipping_log()
    {
        echo $this->inventory_warehouse_model->show_shipping_log();
    }
}
