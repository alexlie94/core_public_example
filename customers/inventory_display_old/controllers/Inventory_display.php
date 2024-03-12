<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Inventory_display extends MY_Customers
{

    private $_searchBy;
    private $_downloadTemplateCsv;

    public function __construct()
    {
        $this->_function_except = ['getchannel','show','launching','default','defaultprocess','addsource','addchannel','notdefault','confirmimage','viewimage','notdefaultprocess','setdefaultimage','launch','launchproductsource','download','upload','processupload','downloadtemplatecsv'];
        parent::__construct();
        $this->_searchBy = [
            'productid' => 'ProductID','productname' => 'Product Name','brandname' => 'Brand Name','status' => 'Status', 'gender' => 'Gender', 'datecreated' => 'Date Created', 'datemodified' => 'Date Modified'
        ];
        $this->_downloadTemplateCsv = 'assets/excel/template_upload_inventory_display.csv';
    }

    public function index()
    {
        $this->template->title('Inventory Display');
        $this->setTitlePage('Inventory Display');
        $this->assetsBuild(['datatables']);
        $this->setJs('inventory_display');
        $searchArray = [
            'productid' => '<input type=\"text\" class=\"form-control mt-9\" id=\"searchValue\" name=\"searchValue\" placeholder=\"Product ID\" autocomplete=\"off\">',
            'productname' => '<input type=\"text\" class=\"form-control mt-9\" id=\"searchValue\" name=\"searchValue\" placeholder=\"Product Name\" autocomplete=\"off\">',
            'brandname' => '<input type=\"text\" class=\"form-control mt-9\" id=\"searchValue\" name=\"searchValue\" placeholder=\"Brand Name\" autocomplete=\"off\">',
            'gender' => '<select class=\"form-select form-select mt-9\" name=\"searchValue\" id=\"searchValue\" aria-label=\"Search By\"><option value=\"man\">Man</option><option value=\"woman\">Woman</option></select>',
            'datecreated' => '<input type=\"text\" class=\"form-control mt-9 dateRange\" id=\"searchValue\" name=\"searchValue\" placeholder=\"Date Created\">',
            'datemodified' => '<input type=\"text\" class=\"form-control mt-9 dateRange\" id=\"searchValue\" name=\"searchValue\" placeholder=\"Date Modified\">',
        ];

        $getLookup = $this->inventory_display_model->_getLookupValues()->get_all(array('lookup_config' => 'products_status'));
        $getSource = $this->inventory_display_model->_getSources()->get_all_without_delete();
        
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
            'sourceUrl' => base_url()."inventory_display/getchannel",
            'getLookupArray' => json_encode($getLookupArray),
        ];

        $header_table = [
            'No','ProductID','Product','Price','Sale Price','Size','Brand','Status','Action'
        ];

        $this->setTable($header_table);

        $this->template->build('v_show',$data);

    }

    public function getchannel()
	{
		isAjaxRequestWithPost();

        $this->function_access('view',true);

		$id = clearInput($this->input->post('source_id'));
		$response = $this->inventory_display_model->getChannel($id);
		echo json_encode($response);
	}

    public function show()
    {

        isAjaxRequestWithPost();
		$this->function_access('view');
		echo $this->inventory_display_model->show();

    }

    public function launching($id)
    {
        $this->function_access('update');
        $this->template->title('Product Launching');
        $this->setTitlePage('Product Launching');
        $this->assetsBuild(['datatables']);

        $data = $this->inventory_display_model->launching($id);

        $headerTable = ["No","ProductID","Product","SKU","General Color","Variant Color"];
        
        $sourceDefault = ['No','Source', 'Channel', 'Action', 'Status'];
        $defaultImage = $this->inventory_display_model->_getInventoryDisplayDefault()->get(array('users_ms_products_id' => $id,'image_status' => 3));
        $statusdefaultImage = 'Image Not Selected';
        if(is_object($defaultImage)){
            $statusdefaultImage = 'Image Selected';
        }
        $statusDefaultImageHtml = "<span id=\"statusDefaultImage\">{$statusdefaultImage}</span>";
        $urlDefaultImage = base_url("inventory_display/default/default/default/{$id}");
        $buttonDefault = "<button class=\"btn btn-outline btn-outline-dashed btn-outline-success btn-sm\" id=\"btnDefaultImage\" data-source=\"default\" data-channel=\"default\" data-fullscreenmodal=\"1\" data-type=\"modal\"  data-url=\"{$urlDefaultImage}\">Select Default Image</button>";
        $sourceDefaultData[0] = ['No' => '1','Source'=>'Default','Channel' => 'Default','Action' => $buttonDefault, "Status" => $statusDefaultImageHtml];


        $sourceTable = ["No","Source","Channel","Launch Date","Action","Status"];

        $dataLaunching = $this->inventory_display_model->showLaunching($id);

        $lookupValueDisplay = $this->lookupValuesDisplay();

        $showData = [
            'headerTable' => generateTableHtml($headerTable,$data['variant'],"tableVariant"),
            'backUrl' => $data['backUrl'],
            'headerDefault' => generateTableHtml($sourceDefault,$sourceDefaultData),
            'headerSource' => generateTableHtml($sourceTable,[],'tableSource'),
            'addSourceUrl' => base_url()."inventory_display/addsource",
            'showChannelSourceUrl' => base_url()."inventory_display/addchannel",
            'productID' => $id,
            'dataLaunching' => json_encode($dataLaunching),
            'lookupDisplayLaunching' => json_encode($lookupValueDisplay['display']),
            'lookupLaunchStatusLaunching' => json_encode($lookupValueDisplay['launchStatus']),
            'lookupDisplayColourLaunching' => json_encode($lookupValueDisplay['colourDisplay']),
        ];

        $this->setJs('inventory_display_launching');
        $this->template->build('v_launching', $showData);
    }

    private function lookupValues()
    {
        $get = $this->inventory_display_model->_getLookupValues()->get_all(array('lookup_config' => "inventory_display_images"));
        if(!$get){
            pageError();
        }

        $data = [];
        foreach($get as $ky => $val){
            $data[$val->lookup_code] = $val->lookup_name; 
        }

        return $data;
                
    }

    private function lookupValuesDisplay()
    {
        $get = $this->inventory_display_model->_getLookupValues()->get_all(array('lookup_config' => "inventory_displays"));
        if(!$get){
            pageError();
        }

        $data = [];
        $dataStatus2 = [];
        $dataColour = [];

        foreach($get as $ky => $val){
            $data[$val->lookup_code] = $val->lookup_name; 
            switch ($val->lookup_code) {
                case 4:
                    $dataStatus2[$val->lookup_code] = 'Image Selected, Pending';
                    $dataColour[$val->lookup_code] = 'primary';
                    break;

                case 5:
                    $dataStatus2[$val->lookup_code] = 'Image Selected, Scheduled';
                    $dataColour[$val->lookup_code] = 'danger';

                    break;
                case 6:
                    $dataStatus2[$val->lookup_code] = 'Image Selected, Launched';
                    $dataColour[$val->lookup_code] = 'success';
                    break;
                
            }
        }
        $dataArray = [
            'display' => $data,
            'launchStatus' => $dataStatus2,
            'colourDisplay' => $dataColour,
        ];

        return $dataArray;
                
    }

    public function defaultprocess()
    {
        isAjaxRequestWithPost();
        $this->function_access('update');

        $response = $this->inventory_display_model->defaultProcess();
        echo json_encode($response);
    }

    public function addsource()
    {
        isAjaxRequestWithPost();
        $this->function_access('update');
        $response = $this->inventory_display_model->getSources();
        echo json_encode($response);
    }

    public function addchannel()
    {
        isAjaxRequestWithPost();
        $this->function_access('update');
        $response = $this->inventory_display_model->getChannels();
        echo json_encode($response);
    }

    public function notdefault($source,$channel,$productID)
    {
        isAjaxRequestWithPost();
        $this->function_access('view');
        try {

            if ($source == null || $channel == null || $productID == null) {
				throw new Exception("Failed to request Edit", 1);
			}

            $get = $this->inventory_display_model->_getSources()->get(array('id' => $source));
            if(!$get){
                throw new Exception("failed request data", 1);
                
            }

            $sourceData = $get;

            //check channel 
            $get = $this->inventory_display_model->_getChannels()->get(array("id" => $channel));
            if(!$get){
                throw new Exception("Failed request data", 1);
                
            }

            $channelData = $get;

            //check productID
            $get = $this->inventory_display_model->_getProducts()->get(array("id" => $productID));
            if(!$get){
                throw new Exception("Failed request data", 1);
                
            }

            $headerTable = ["No","ProductID","Product","SKU","General Color","Variant Color","Source","Channel"];
            $imageTable = [
                'No','Image','Image Name','Action','Status',
            ];

            $dataShow = $this->inventory_display_model->notDefault($source,$channel,$productID);

            foreach($dataShow['variant'] as $ky => $val){
                $dataShow['variant'][$ky]['Source'] = $sourceData->source_name;
                $dataShow['variant'][$ky]['Channel'] = $channelData->channel_name;
            }

            $lookupValue = $this->lookupValues();
            $lookupValueDisplay = $this->lookupValuesDisplay();

            $getDefaultImageData = $this->inventory_display_model->_getInvetoryDisplayDetails()->get(array('users_ms_products_id' => $productID,'image_status' => 3,'admins_ms_sources_id' => $source,'users_ms_channels_id' => $channel));
            $statusButtonSave = true;
            if(is_object($getDefaultImageData)){
                $statusButtonSave = false;
            }

            $showHtml = [
                'headerTable' => generateTableHtml($headerTable,$dataShow['variant'],'tableVariant'),
                'detailTable' => generateTableHtml($imageTable,$dataShow['detail'],'tableDetail'),
                'lookup' => json_encode($lookupValue),
                'dataNotDefaultArray' => json_encode($dataShow['dataArrayDefault']),
                'dataSource' => $source,
                'dataChannel' => $channel,
                'dataProductID' => $productID,
                'lookupDisplay' => json_encode($lookupValueDisplay['display']),
                'lookupLaunchStatus' => json_encode($lookupValueDisplay['launchStatus']),
                'lookupDisplayColour' => json_encode($lookupValueDisplay['colourDisplay']),
            ];

            $urlSaving = base_url("inventory_display/notdefaultprocess");
            $dataInput = "data-source='{$source}' data-channel='{$channel}' data-productid='{$productID}' data-url='{$urlSaving}'";
            $data = array(
                'title_modal' => "Product Image",
                'content' => $this->load->view('v_notdefault',$showHtml,true), 
                'dataInput' => $dataInput,
                'buttonID' => 'btnProcessModalNotDefault',
                'buttonDisabled' => $statusButtonSave,
            );

            $html = $this->load->view($this->_v_modal,$data,true);
            $response['html'] = $html;
            echo json_encode($response);

        } catch (Exception $e) {
            $response['failed'] = true;
            $response['message'] = $e->getMessage();
            echo json_encode($response);
        }
    }

    public function confirmimage()
    {
        isAjaxRequestWithPost();
        $this->function_access('update');
        try {
            $imageName = $this->input->post('imageName');
            $imageID = clearInput($this->input->post('imageID'));

            $button = "<button type=\"button\" class=\"btn btn-light me-2\" data-bs-dismiss=\"modal\">Close</button>";
            $button .= "<button type=\"button\" class=\"btn btn-success btnSelectMainImage me-2\" data-imageid = \"{$imageID}\" data-lookup = \"3\">Select Main</button>";
            $button .= "<button type=\"button\" class=\"btn btn-primary btnSelectImage me-2\" data-imageid = \"{$imageID}\" data-lookup = \"2\">Select</button>";
            $data = [
                'title_modal' => "Confirm Select",
                'content' => $imageName,
                'buttonFooter' => $button,
            ];

            $html = $this->load->view($this->_v_modal_notButton,$data,true);
            $response['html'] = $html;
            echo json_encode($response);
        } catch (Exception $e) {
            $response['failed'] = true;
            $response['message'] = $e->getMessage();
            echo json_encode($response);
        }
    }

    public function viewimage()
    {
        isAjaxRequestWithPost();
        $this->function_access('update');
        try {
            $imageName = $this->input->post('imageName');
            $imageID = clearInput($this->input->post('imageID'));
            $urlImage = base_url("assets/uploads/products_image/{$imageName}");

            $button = "<button type=\"button\" class=\"btn btn-light\" data-bs-dismiss=\"modal\">Close</button>";
            
            $data = [
                'title_modal' => $imageName,
                'content' => "<div class=\"overlay-wrapper bgi-no-repeat bgi-position-center bgi-size-cover card-rounded\" style=\"height: 266px;background-image:url('{$urlImage}')\"></div>",
                'buttonFooter' => $button,
            ];

            $html = $this->load->view($this->_v_modal_notButton,$data,true);
            $response['html'] = $html;
            echo json_encode($response);
        } catch (Exception $e) {
            $response['failed'] = true;
            $response['message'] = $e->getMessage();
            echo json_encode($response);
        }
    }

    public function notdefaultprocess()
    {
        isAjaxRequestWithPost();
        $this->function_access('update');

        $response = $this->inventory_display_model->notDefaultProcess();
        echo json_encode($response);
    }

    public function default($source,$channel,$productID)
    {
        isAjaxRequestWithPost();
        $this->function_access('view');
        try {


            //check source && channel
            if($source != "default" && $channel != "default"){
                throw new Exception("Failed to request data", 1);
                
            }

            //check productID 
            $get = $this->inventory_display_model->_getProducts()->get(array("id" => $productID));
            if(!$get){
                throw new Exception("Failed request data", 1);
                
            }

            $headerTable = ["No","ProductID","Product","SKU","General Color","Variant Color","Source","Channel"];
            $imageTable = [
                'No','Image','Image Name','Action','Status',
            ];

            $dataShow = $this->inventory_display_model->default($productID);
            if(isset($dataShow['error'])){
                throw new Exception($dataShow['error'], 1);
                
            }

            $getDefaultImageData = $this->inventory_display_model->_getInventoryDisplayDefault()->get(array('users_ms_products_id' => $productID,'image_status' => 3));
            $statusButtonSave = true;
            if(is_object($getDefaultImageData)){
                $statusButtonSave = false;
            }

            foreach($dataShow['variant'] as $ky => $val){
                $dataShow['variant'][$ky]['Source'] = "Default";
                $dataShow['variant'][$ky]['Channel'] = "Default";
            }

            $lookupValue = $this->lookupValues();

            $showHtml = [
                'headerTable' => generateTableHtml($headerTable,$dataShow['variant'],'tableVariant'),
                'detailTable' => generateTableHtml($imageTable,$dataShow['detail'],'tableDetail'),
                'lookup' => json_encode($lookupValue),
                'dataDefaultArray' => json_encode($dataShow['dataArrayDefault']),
            ];

            $urlSaving = base_url("inventory_display/defaultprocess");
            $dataInput = "data-source='{$source}' data-channel='{$channel}' data-productid='{$productID}' data-url='{$urlSaving}'";
            $data = array(
                'title_modal' => "Product Default Image",
                'content' => $this->load->view('v_default',$showHtml,true), 
                'dataInput' => $dataInput,
                'buttonID' => 'btnProcessModalDefault',
                'buttonDisabled' => $statusButtonSave,
            );

            $html = $this->load->view($this->_v_modal,$data,true);
            $response['html'] = $html;
            echo json_encode($response);

        } catch (Exception $e) {
            $response['failed'] = true;
            $response['message'] = $e->getMessage();
            echo json_encode($response);
        }
    }

    public function setdefaultimage()
    {
        isAjaxRequestWithPost();
        $this->function_access('update');
        $response = $this->inventory_display_model->setDefaultImage();
        echo json_encode($response);
    }

    public function launch($source,$channel,$productID)
    {
        isAjaxRequestWithPost();
        $this->function_access('update');
        try {
            $status = clearInput($this->input->post('status'));
            if($status != 4){
                throw new Exception("Status must be Launch", 1);
                
            }

            $button = "<button type=\"button\" class=\"btn btn-light me-2\" data-bs-dismiss=\"modal\">Close</button>";
            $buttonUrl = base_url("inventory_display/launchproductsource");
            $button .= "<button type=\"button\" class=\"btn btn-primary btnLaunchProductSource\" data-source = \"{$source}\" data-channel = \"{$channel}\" data-productid= \"{$productID}\" data-url=\"{$buttonUrl}\">Launch</button>";
            
            $lookupValueDisplay = $this->lookupValuesDisplay();

            $showHtml = [
                'lookupDisplay' => json_encode($lookupValueDisplay['display']),
                'lookupLaunchStatus' => json_encode($lookupValueDisplay['launchStatus']),
                'lookupDisplayColour' => json_encode($lookupValueDisplay['colourDisplay']),
            ];
            
            $data = [
                'title_modal' => "Launch Date",
                'content' => $this->load->view('v_launchdate',$showHtml,true),
                'buttonFooter' => $button 
            ];

            $html = $this->load->view($this->_v_modal_notButton,$data,true);
            $response['html'] = $html;

            echo json_encode($response);

        } catch (Exception $e) {
            $response['failed'] = true;
            $response['message'] = $e->getMessage();
            echo json_encode($response);
        }
    }

    public function launchproductsource()
    {
        isAjaxRequestWithPost();
        $this->function_access('update');
        $response = $this->inventory_display_model->launchProductSource();
        echo json_encode($response);
    }

    public function download()
    {
        $this->function_access('view');
        $this->inventory_display_model->export();
    }

    public function downloadtemplatecsv()
    {
        $this->load->helper('download');
        force_download($this->_downloadTemplateCsv, NULL);
    }

    public function upload()
    {
        isAjaxRequestWithPost();
        $dataShow = [
            'url_form' => base_url() . 'inventory_display/processupload',
        ];

        $urlDownloadTemplate = base_url()."inventory_display/downloadtemplatecsv";
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

    public function processupload()
    {
        isAjaxRequestWithPost();
        $statusInsert = $this->function_access('insert',true);
        if(!$statusInsert){
            echo json_encode(array('success' => false,'messages' => 'failed processing request'));
            exit();
        }

		$response = $this->inventory_display_model->import($this->_document_excel);
        if(!empty($response['data'])){
            $data['data'] = $response['data'];
            $template = array(
                'title_modal' => 'Preview File',
                'buttonCloseID' => 'btnCloseModalPreview',
                'buttonName' => 'Next',
                'buttonID' => 'btnProcessUploadModal',
                'buttonUrl' => base_url()."inventory_display/checkingupload",
                'content' => $this->load->view('v_review',$data,true),
            );

            $response['html'] = $this->load->view($this->_v_modal,$template,true);
        }

		echo json_encode($response);   
    }
}
