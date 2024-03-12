<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Inventory_allocation extends MY_Customers
{

    private $_searchBy;
    private $_downloadTemplateCsv;
    private $_downloadTemplateCsvOffline;

    public function __construct()
    {
        $this->_function_except = ['getchannel','show', 'checkingdataoffline' ,'processuploadoffline','documentoffline' ,'downloadtemplatecsvoffline','uploadoffline','processupload','checkingdata','downloadtemplatecsv' ,'document','allocation','process','showoffline','allocationoffline','processoffline','download','downloadoffline','upload'];
        parent::__construct();
        $this->_searchBy = [
            'productid' => 'ProductID','sku' => 'SKU','productname' => 'Product Name',
            'brandname' => 'Brand Name','status' => 'Status','datecreated' => 'Date Created',
        ];
        $this->_downloadTemplateCsv = 'assets/excel/template_upload_inventory_allocation.csv';
        $this->_downloadTemplateCsvOffline = 'assets/excel/template_upload_inventory_allocation_offline.csv';
    }

    public function index()
    {
        $this->template->title('Inventory Allocation');
        $this->setTitlePage('Inventory Allocation');
        $this->assetsBuild(['datatables']);
        $this->setJs('inventory_allocation');

        $searchArray = [
            'productid' => '<input type=\"text\" class=\"form-control mt-9\" id=\"searchValue\" name=\"searchValue\" placeholder=\"Product ID\" autocomplete=\"off\">',
            'productname' => '<input type=\"text\" class=\"form-control mt-9\" id=\"searchValue\" name=\"searchValue\" placeholder=\"Product Name\" autocomplete=\"off\">',
            'brandname' => '<input type=\"text\" class=\"form-control mt-9\" id=\"searchValue\" name=\"searchValue\" placeholder=\"Brand Name\" autocomplete=\"off\">',
            'datecreated' => '<input type=\"text\" class=\"form-control mt-9 dateRange\" id=\"searchValue\" name=\"searchValue\" placeholder=\"Date Created\">',
            'sku' => '<input type=\"text\" class=\"form-control mt-9\" id=\"searchValue\" name=\"searchValue\" placeholder=\"SKU\" autocomplete=\"off\">',
        ];

        $getLookup = $this->inventory_allocation_model->_getLookupValues()->get_all(array('lookup_config' => 'products_status'));
        $getSource = $this->inventory_allocation_model->_getSources()->get_all_without_delete();
        $getOfflineStores = $this->inventory_allocation_model->_getOfflineStores()->get_all_without_delete();
        
        $getLookupArray = [];
        if($getLookup){
            $htmlStatus = '<div class=\"mt-12 d-flex\">';
            $i = 0;
            foreach ($getLookup as $key => $value) {
                $lookupCode = $value->lookup_code;
                $lookupName = $value->lookup_name;

                $htmlStatus .= '<div class=\"form-check form-check-custom form-check-primary  me-5\"><input class=\"form-check-input\" name=\"status[]\" type=\"checkbox\" value=\"'.$lookupCode.'\" id=\"flexCheckbox'.$i.'\"><label class=\"form-check-label\" for=\"flexCheckbox'.$i.'\">'.$lookupName.'</label></div>';
                $i++;

                switch ($lookupCode) {
                    case '1':
                        $getLookupArray[$lookupName] = "badge badge-light-dark";
                        break;

                    case '2':
                        $getLookupArray[$lookupName] = "badge badge-light-success";
                        break;

                    case '3':
                        $getLookupArray[$lookupName] = "badge badge-light-primary";
                        break;

                    case '4':
                        $getLookupArray[$lookupName] = "badge badge-light-warning";
                        break;

                    case '5':
                        $getLookupArray[$lookupName] = "badge badge-light-info";
                        break;

                    case '6':
                        $getLookupArray[$lookupName] = "badge badge-light-success";
                        break;
            
                }

            }

            $htmlStatus .= '</div>';
            $searchArray['status'] = $htmlStatus;

            $searchArray['status'] .= '</div>';
        }

        $data = [
            'searchBy' => $this->_searchBy,
            'searchInput' => json_encode($searchArray),
            'source' => $getSource,
            'sourceUrl' => base_url()."inventory_allocation/getchannel",
            'getLookupArray' => json_encode($getLookupArray),
            'getOfflineStores' => $getOfflineStores,
        ];

        $header_table = [
            'No','ProductID','Product','Brand','Size','Status'
        ];

        $this->setTemplateTable($header_table);

        $header_table_offline = [
            'No','ProductID','Product','Brand','Size','Status'
        ];

        $data['tableOffline'] = generateTable($header_table_offline,"table-data-offline");

        $this->template->build('v_show',$data);

    }

    public function getchannel()
	{
		isAjaxRequestWithPost();

        $this->function_access('view',true);

		$id = clearInput($this->input->post('source_id'));
		$response = $this->inventory_allocation_model->getChannel($id);
		echo json_encode($response);
	}

    public function show()
    {
        isAjaxRequestWithPost();
		$this->function_access('view');
		echo $this->inventory_allocation_model->show();

    }

    public function showoffline()
    {
        isAjaxRequestWithPost();
		$this->function_access('view');
		echo $this->inventory_allocation_model->showOffline();

    }

    public function allocation()
    {
        isAjaxRequestWithPost();
        $this->function_access('update');

        try {
            $productID = clearInput($this->input->post('productID'));

            //check productID 
            $check = $this->inventory_allocation_model->_getProducts()->get(array('id' => $productID));
            if(!$check){
                throw new Exception("Failed Processing Request", 1);
                
            }

            $table = $this->inventory_allocation_model->tabelAllocaton($productID);
            if($table === false){
                throw new Exception("Failed Processing Request", 1);
                
            }

            $showData = [
                'productid' => $productID,
                'productName' => $check->product_name,
                'table' => $table,
                'form' => base_url("inventory_allocation/process"),
            ];

            $data = [
                'title_modal' => "Allocation",
                'content' => $this->load->view('v_form',$showData,true),
            ];

            $html = $this->load->view($this->_v_modal,$data,true);
            $response['html'] = $html;
            echo json_encode($response);
        } catch (Exception $e) {
            $response['failed'] = true;
            $response['message'] = $e->getMessage();
            echo json_encode($response);
        }
    
    }

    public function process()
    {
        isAjaxRequestWithPost();
        $this->function_access('update');

        $response = $this->inventory_allocation_model->save();
		echo json_encode($response);
		exit();
    }

    public function allocationoffline()
    {
        isAjaxRequestWithPost();
        $this->function_access('update');

        try {
            $productID = clearInput($this->input->post('productID'));

            //check productID 
            $check = $this->inventory_allocation_model->_getProducts()->get(array('id' => $productID));
            if(!$check){
                throw new Exception("Failed Processing Request", 1);
                
            }
            
            $msgError = '';
            $table = $this->inventory_allocation_model->tabelAllocationOffline($productID,$msgError);
            if($table === false){
                throw new Exception($msgError, 1);
                
            }
            
            $showData = [
                'productid' => $productID,
                'productName' => $check->product_name,
                'table' => $table,
                'form' => base_url("inventory_allocation/processoffline"),
            ];

            $data = [
                'title_modal' => "Allocation Offline",
                'content' => $this->load->view('v_form',$showData,true),
                'buttonCloseID' => 'btnCloseModalOffline',
                'buttonID' => 'btnProcessModalOffline'
            ];

            $html = $this->load->view($this->_v_modal,$data,true);
            $response['html'] = $html;
            echo json_encode($response);
        } catch (Exception $e) {
            $response['failed'] = true;
            $response['message'] = $e->getMessage();
            echo json_encode($response);
        }
    
    }

    public function processoffline()
    {
        isAjaxRequestWithPost();
        $this->function_access('update');

        $response = $this->inventory_allocation_model->saveoffline();
		echo json_encode($response);
		exit();
    }

    public function download()
    {
        $this->function_access('view');
        $this->inventory_allocation_model->export();
    }

    public function downloadoffline()
    {
        $this->function_access('view');
        $this->inventory_allocation_model->exportOffline();
    }

    public function upload()
    {
        isAjaxRequestWithPost();
        $this->function_access('insert');
        $dataShow = [
            'url_form' => base_url() . 'inventory_allocation/document',
        ];

        $urlDownloadTemplate = base_url()."inventory_allocation/downloadtemplatecsv";
        $data = [
            'title_modal' => 'Mass Upload',
            'content' => $this->load->view('v_upload', $dataShow, true),
            'buttonCloseID' => 'btnCloseModalUpload',
            'buttonID' => 'btnDownloadTemplate',
            'buttonTypeSave' => 'redirect',
            'buttonName' => 'Download Template CSV',
            'dataInput' => "data-url = '{$urlDownloadTemplate}'",
        ];

        $html = $this->load->view($this->_v_modal, $data, true);

        echo json_encode(['html' => $html]);
    }

    public function downloadtemplatecsv()
    {
        $this->load->helper('download');
        force_download($this->_downloadTemplateCsv, NULL);
    }

    public function document()
    {
        isAjaxRequestWithPost();
        $statusInsert = $this->function_access('insert',true);
        if(!$statusInsert){
            echo json_encode(array('success' => false,'messages' => 'failed processing request'));
            exit();
        }

        $response = $this->inventory_allocation_model->import();

        try {

            if(!$response['success']){
                throw new Exception("Error Processing Request", 1);
                
            }

            $html = $this->preview();
            
            if(is_array($html)){
                $response = $html;
                throw new Exception("Error Processing Request", 1);
                
            }

            $response['html'] = $html;
            echo json_encode($response);
        } catch (Exception $e) {
            echo json_encode($response);
        }
    }

    private function preview()
    {
        
        try {

            $preview = $this->inventory_allocation_model->preview();
            if($preview['success'] === false){
                throw new Exception("Error Processing Request", 1);
                
            }

            $data = ['data' => $preview['data']];
            $statusDataSaving = $preview['data']['statusDataSaving'];

            if($statusDataSaving){
                $buttonName = 'Save Change';
                $buttonUrl  = base_url()."inventory_allocation/processupload";
            }else{
                $buttonName = 'Check Data';
                $buttonUrl = base_url()."inventory_allocation/checkingdata";
            }

            $template = array(
                'title_modal' => 'Mass Upload',
                'buttonCloseID' => 'btnCloseModalPreview',
                'buttonName' => $buttonName,
                'buttonID' => 'btnProcessUploadModal',
                'dataInput' => "data-url = \"{$buttonUrl}\" ", //processcheckingdata
                'content' => $this->load->view("v_review",$data,true),
            );

            $html = $this->load->view($this->_v_modal, $template, true);
            return $html;
        } catch (Exception $e) {
            return $preview;
        }


    }

    public function checkingdata()
    {
        isAjaxRequestWithPost();
        $statusInsert = $this->function_access('insert',true);
        if(!$statusInsert){
            echo json_encode(array('success' => false,'messages' => 'failed processing request'));
            exit();
        }

        $response = $this->inventory_allocation_model->checkingData();
        if($response['success']){
            $response['showModal'] = true;
        }

        echo json_encode($response);

    }

    public function processupload()
    {
        isAjaxRequestWithPost();
        $statusInsert = $this->function_access('insert',true);
        if(!$statusInsert){
            echo json_encode(array('success' => false,'messages' => 'failed processing request'));
            exit();
        }

        $response = $this->inventory_allocation_model->checkingData();
        if($response['success']){
            $response = $this->inventory_allocation_model->saveUpload();
        }

        echo json_encode($response);

    }

    public function uploadoffline()
    {
        isAjaxRequestWithPost();
        $this->function_access('insert');
        $dataShow = [
            'url_form' => base_url() . 'inventory_allocation/documentoffline',
        ];

        $urlDownloadTemplate = base_url()."inventory_allocation/downloadtemplatecsvoffline";
        $data = [
            'title_modal' => 'Mass Upload Offline Store',
            'content' => $this->load->view('v_uploadoffline', $dataShow, true),
            'buttonCloseID' => 'btnCloseModalUpload',
            'buttonID' => 'btnDownloadTemplate',
            'buttonTypeSave' => 'redirect',
            'buttonName' => 'Download Template CSV',
            'dataInput' => "data-url = '{$urlDownloadTemplate}'",
        ];

        $html = $this->load->view($this->_v_modal, $data, true);

        echo json_encode(['html' => $html]);
    }

    public function downloadtemplatecsvoffline()
    {
        $this->load->helper('download');
        force_download($this->_downloadTemplateCsvOffline, NULL);
    }

    public function documentoffline()
    {
        isAjaxRequestWithPost();
        $statusInsert = $this->function_access('insert',true);
        if(!$statusInsert){
            echo json_encode(array('success' => false,'messages' => 'failed processing request'));
            exit();
        }

        $response = $this->inventory_allocation_model->importoffline();

        try {

            if(!$response['success']){
                throw new Exception("Error Processing Request", 1);
                
            }

            $html = $this->previewoffline();
            
            if(is_array($html)){
                $response = $html;
                throw new Exception("Error Processing Request", 1);
                
            }

            $response['html'] = $html;
            echo json_encode($response);
        } catch (Exception $e) {
            echo json_encode($response);
        }
    }

    private function previewoffline()
    {
        
        try {

            $preview = $this->inventory_allocation_model->previewoffline();
            if($preview['success'] === false){
                throw new Exception("Error Processing Request", 1);
                
            }

            $data = ['data' => $preview['data']];
            $statusDataSaving = $preview['data']['statusDataSaving'];

            if($statusDataSaving){
                $buttonName = 'Save Change';
                $buttonUrl  = base_url()."inventory_allocation/processuploadoffline";
            }else{
                $buttonName = 'Check Data';
                $buttonUrl = base_url()."inventory_allocation/checkingdataoffline";
            }

            $template = array(
                'title_modal' => 'Mass Upload Offline Store',
                'buttonCloseID' => 'btnCloseModalPreview',
                'buttonName' => $buttonName,
                'buttonID' => 'btnProcessUploadModal',
                'dataInput' => "data-url = \"{$buttonUrl}\" ", //processcheckingdata
                'content' => $this->load->view("v_reviewoffline",$data,true),
            );

            $html = $this->load->view($this->_v_modal, $template, true);
            return $html;
        } catch (Exception $e) {
            return $preview;
        }


    }

    public function checkingdataoffline()
    {
        isAjaxRequestWithPost();
        $statusInsert = $this->function_access('insert',true);
        if(!$statusInsert){
            echo json_encode(array('success' => false,'messages' => 'failed processing request'));
            exit();
        }

        $response = $this->inventory_allocation_model->checkingDataOffline();
        if($response['success']){
            $response['showModal'] = true;
        }

        echo json_encode($response);

    }

    public function processuploadoffline()
    {
        isAjaxRequestWithPost();
        $statusInsert = $this->function_access('insert',true);
        if(!$statusInsert){
            echo json_encode(array('success' => false,'messages' => 'failed processing request'));
            exit();
        }

        $response = $this->inventory_allocation_model->checkingDataOffline();
        if($response['success']){
            $response = $this->inventory_allocation_model->saveUploadOffline();
        }

        echo json_encode($response);

    }
}
