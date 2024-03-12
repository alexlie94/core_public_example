<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Inventory_requisition extends MY_Customers
{

    public function __construct()
    {
        $this->_function_except = 
        [ 
            'show','process','status','paging','suppliers','brands',
            'warehouse','addSKU', 'addSKUEdit','addSuppliers',
            'addBrands','addWarehouse','release','detail','listDetailPO', 'masterDataModal',
            'masterDataModalSupplier', 'uploadDataProduct', 'processMassUpload', 'cekSKU', 'downloadXlxs', 'listProductSku', 'downloadView'
        ];

        parent::__construct();
        $this->_searchBy = [
            'po_number'=>'PO Number', 
            'brand_name'=>'Brand Name', 
            'supplier_name' => 'Supplier Name',
             'publisher' => 'Publisher'
        ];
    }

    public function index()
    {
        $this->template->title('Inventory Requisition ( Create PO )');
        $this->setTitlePage('Inventory Requisition ( Create PO )');
        $this->assetsBuild(['datatables', 'repeater','xlsx']);
        $this->setJs('inventory_requisition');

        $data = [
            'searchBy' => $this->_searchBy,
            'lookupValue' => $this->db
                            ->get_where('admins_ms_lookup_values', ['lookup_config' => 'po_status'])->result(),
        ];

        $this->setTable(['po number','brand','supplier','publisher','date created','quantity','status',''], true);

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
                'url' => base_url() . "inventory_requisition/update/$1",
            ],
            [
                'button' => 'delete',
                'type' => 'confirm',
                'title' => 'Item',
                'confirm' => 'Are you sure you want to delete this item ?',
                'url' => base_url() . "inventory_requisition/delete/$1",
            ]

        ];

        $button = $this->setButtonOnTable();

        echo $this->inventory_requisition_model->show($button);
    }

    public function insert()
    {
        isAjaxRequestWithPost();
        $get['publisher'] = $this->db->get_where('users', ['id' => $this->session->userdata('x-id-user')])->row();

        $data = [
            'title_modal' => 'Add New PO',
            'url_form' => base_url() . 'inventory_requisition/process',
            'form' => $this->load->view('v_form',$get, true),
            'buttonCloseID' => 'btnCloseModalFullscreen',
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

        $response = $this->inventory_requisition_model->save();

        echo json_encode($response);

        exit();
    }

    public function processMassUpload()
    {
        isAjaxRequestWithPost();

        $response = $this->inventory_requisition_model->saveMassUpload();

        echo json_encode($response);
        exit();
    }

    public function listDetailPO()
    {
        $this->inventory_requisition_model->manageListDetailPO($_POST['set_id_detail']);
    }

    public function update($id)
    {
        isAjaxRequestWithPost();
        try {
            $get['getItems'] = $this->inventory_requisition_model->get($id);
            $get['supp'] = $this->inventory_requisition_model->getDataSuppliers()->get($get['getItems']->users_ms_suppliers_id);
            $get['brands'] = $this->inventory_requisition_model->getDataBrands()->get($get['getItems']->users_ms_brands_id);
            $get['whs'] = $this->inventory_requisition_model->getDataWarehouse()->get($get['getItems']->users_ms_warehouses_id);
            $get['publisher'] = $this->db->get_where('users', ['id' => $get['getItems']->created_by])->row();
            $get['isDetail'] = true;

            $data = [
                'title_modal' => 'Edit Inventory Requisition',
                'url_form' => base_url() . 'inventory_requisition/process',
                'form' => $this->load->view('v_form_edit', $get, true),
                'buttonCloseID' => 'btnCloseModalFullscreen',
                'buttonID' => 'saveMassUpload',
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
        $response = ['text' => 'Successfully Delete Item', 'success' => true];

        try {

            $idSet = isset($_POST['set_id_detail']) ? $_POST['set_id_detail'] : '';

            $process = $this->inventory_requisition_model->deleteData($idSet, $id);

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

    public function suppliers()
    {
        $this->inventory_requisition_model->manageSuppliersData($_POST['dataSuppliers']);
    }

    public function brands()
    {
        $this->inventory_requisition_model
            ->manageBrandData(isset($_POST['supp_id']) ? $_POST['supp_id'] : '', $_POST['dataBrands']);
    }

    public function warehouse()
    {
        $this->inventory_requisition_model->manageWarehouseData($_POST['dataWarehouse']);
    }

    public function uploadDataProduct()
    {
        $getPushData =[
            'data_upload' => $_POST['dataUpload'],
            'supplier_id' => $_POST['supplier_id'],
            'brand_id' => $_POST['brand_id']
        ];

        $this->inventory_requisition_model->dataUpload($getPushData);
    }

    public function cekSKU()
    {
        $getPushData = [
            'sku_input' => $_POST['sku_input'],
            'supplier_id' => $_POST['supplier_id'],
            'brand_id' => $_POST['brand_id']
        ];

        $this->inventory_requisition_model->getProductBySku($getPushData);
    }

    public function addSKU()
    {
        isAjaxRequestWithPost();

        $data = [
            'title_modal' => 'SKU List',
            'url_form' => base_url() . 'inventory_requisition/process',
            'form' => $this->load->view('v_form2', '', true),
            'buttonCloseID' => 'btnCloseModalFullscreen2',
            'buttonID' => 'btnProcessModal2',
            'buttonName' => 'Select'
        ];

        $html = $this->load->view($this->_v_form_modal, $data, true);

        echo json_encode(['html' => $html]);
        exit();
    }

    public function addSKUEdit()
    {
        isAjaxRequestWithPost();

        $data = [
            'title_modal' => 'SKU List',
            'url_form' => base_url() . 'inventory_requisition/process',
            'form' => $this->load->view('v_form2', '', true),
            'buttonCloseID' => 'btnCloseModalFullscreen2',
            'buttonID' => 'btnPutSkuEdit',
            'buttonName' => 'Select'
        ];

        $html = $this->load->view($this->_v_form_modal, $data, true);

        echo json_encode(['html' => $html]);
        exit();
    }

    public function listProductSku(){
        isAjaxRequestWithPost();

        $getData =[
            'brand_id' => isset($_POST['set_brand_id']) ? $_POST['set_brand_id'] : '',
            'supplier_id' => isset($_POST['set_id_supplier']) ? $_POST['set_id_supplier'] : '',
            'value_input' => isset($_POST['value_input']) ? $_POST['value_input'] : ''
        ];

        $this->inventory_requisition_model->manageListSku($getData);
    }

    public function addSuppliers()
    {
        isAjaxRequestWithPost();

        $data = [
            'title_modal' => 'Suppliers List',
            'url_form' => base_url() . 'inventory_requisition/process',
            'form' => $this->load->view('v_suppliers', '', true),
            'buttonCloseID' => 'btnCloseSuppliers',
            'buttonID' => 'btnProcessModal2',
            'buttonName' => 'Add'
        ];

        $html = $this->load->view($this->_v_form_modal, $data, true);

        echo json_encode(['html' => $html]);
        exit();
    }

    public function addBrands()
    {
        isAjaxRequestWithPost();

        $data = [
            'title_modal' => 'Brands List',
            'url_form' => base_url() . 'inventory_requisition/process',
            'form' => $this->load->view('v_brands', '', true),
            'buttonCloseID' => 'btnCloseSuppliers',
            'buttonID' => 'btnProcessModal2',
            'buttonName' => 'Add'
        ];

        $html = $this->load->view($this->_v_form_modal, $data, true);

        echo json_encode(['html' => $html]);
        exit();
    }

    public function addWarehouse()
    {
        isAjaxRequestWithPost();

        $data = [
            'title_modal' => 'Warehouse List',
            'url_form' => base_url() . 'inventory_requisition/process',
            'form' => $this->load->view('v_warehouse', '', true),
            'buttonCloseID' => 'btnCloseSuppliers',
            'buttonID' => 'btnProcessModal2',
            'buttonName' => 'Add'
        ];

        $html = $this->load->view($this->_v_form_modal, $data, true);

        echo json_encode(['html' => $html]);
        exit();
    }

    public function release($id)
    {
        isAjaxRequestWithPost();

        try {
            $response = $this->inventory_requisition_model->proccess_release($id);

            echo json_encode($response);
            exit();
        } catch (Exception $e) {
            $response['text'] = $e->getMessage();
            $response['success'] = false;
            echo json_encode($response);
            exit();
        }
    }

    public function detail($id)
    {
        isAjaxRequestWithPost();
        try {
            $get['getItems'] = $this->inventory_requisition_model->get($id);
            $get['supp'] = $this->inventory_requisition_model->getDataSuppliers()
                                ->get($get['getItems']->users_ms_suppliers_id);
            $get['brands'] = $this->inventory_requisition_model->getDataBrands()
                                    ->get($get['getItems']->users_ms_brands_id);
            $get['whs'] = $this->inventory_requisition_model->getDataWarehouse()
                                ->get($get['getItems']->users_ms_warehouses_id);
            $get['detailPO'] = $this->inventory_requisition_model->getDataPurchaseOrderDetail($id)->result_array();
            $get['publisher'] = $this->db->get_where('users', ['id' => $get['getItems']->created_by])->row();

            $data = [
                'title_modal' => 'Edit Inventory Requisition',
                'url_form' => '',
                'form' => $this->load->view('v_views', $get, true),
                'buttonCloseID' => 'btnCloseModalFullscreen',
                'buttonSave' => true
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

    public function masterDataModal()
    {
        isAjaxRequestWithPost();
        // $data['data_supplier'] = $this->inventory_requisition_model->getDataSuppliers()->get_all();
        // $data['data_brand'] = $this->inventory_requisition_model->getDataBrands()->get_all();
        // $data['data_warehouse'] = $this->inventory_requisition_model->getDataWarehouse()->get_all();

        $data = [
            'title_modal' => 'Master Data',
            'url_form' => base_url() . 'inventory_requisition/process',
            'form' => $this->load->view('v_master_data', '', true),
            'buttonCloseID' => 'btnCloseModalFullscreen',
        ];

        $this->setTable(['po number', 'brand', 'supplier', 'publisher', 'date created', 'quantity', 'status', ''], true);
        $html = $this->load->view($this->_v_form_modal, $data, true);

        echo json_encode(['html' => $html]);
        exit();
    }

    public function downloadXlxs()
    {

        $getData = [
            'supplier_id' => isset($_GET['supp_id'])? $_GET['supp_id']:'',
            'brand_id' => isset($_GET['brand_id'])? $_GET['brand_id']:''
        ];

        $this->inventory_requisition_model->downloadXlxs($getData);
    }

    public function downloadView(){
        $this->inventory_requisition_model->export();
    }

}
