<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Inventory_group extends MY_Customers
{
    public function __construct()
    {
        $this->_function_except = ['show', 'process', 'status', 'paging', 'productList', 'addSKU', 'viewSKU', 'launching', 'selectDefaultImage', 'add_source', 'source_list', 'channels_list', 'process_sources', 'dataSourceChannels', 'image_default', 'launchproductsource', 'selected_image_default', 'view_image_default', 'update_default_image', 'default', 'confirmimage', 'defaultprocess', 'notdefault', 'addsource', 'addchannel', 'notdefaultprocess', 'launch', 'viewimage', 'setdefaultimage', 'download', 'detail_view', 'productListResult', 'process_detail', 'media_view', 'media_image', 'image_media_view', 'add_image', 'mass_upload', 'process_media_image'];
        parent::__construct();

        $this->_searchBy = [
            'product_gid' => 'Product GID',
            'product_group_name' => 'Product Group Name',
            'brand_name' => 'Brand Name',
        ];
    }

    public function index()
    {
        $this->template->title('Inventory Group');
        $this->setTitlePage('Inventory Group');
        $this->assetsBuild(['datatables', 'ckeditor']);
        $this->setJs('inventory_group');

        $header_table = ['product gid', 'product group', 'brand', 'status', ''];

        $select_source = ['Shopify Berrybenka', 'BBS'];

        $data = [
            'searchBy' => $this->_searchBy,
            'source' => $select_source,
            'lookupValue' => $this->db->get_where('admins_ms_lookup_values', ['lookup_config' => 'po_status'])->result(),
        ];

        $this->setTable($header_table, true);

        $this->setTable($header_table, true);

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
                'url' => base_url() . "inventory_group/update/$1",
            ],
        ];

        $button = $this->setButtonOnTable();

        echo $this->inventory_group_model->show($button);
    }

    public function insert()
    {
        isAjaxRequestWithPost();
        $data = [
            'title_modal' => 'Product Group Detail',
            'url_form' => base_url() . 'inventory_group/process',
            'form' => $this->load->view('v_form', '', true),
            'buttonCloseID' => 'btnCloseModalFullscreen',
            'buttonID' => 'saveProcess',
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

        $response = $this->inventory_group_model->save();

        echo json_encode($response);

        exit();
    }

    public function process_sources()
    {
        isAjaxRequestWithPost();

        $response = $this->inventory_group_model->save_sources();

        echo json_encode($response);

        exit();
    }

    public function process_detail()
    {
        isAjaxRequestWithPost();

        $response = $this->inventory_group_model->save_detail();

        echo json_encode($response);

        exit();
    }

    public function update_default_image()
    {
        isAjaxRequestWithPost();

        $response = $this->inventory_group_model->update_image_status();

        echo json_encode($response);

        exit();
    }

    public function update($id)
    {
        isAjaxRequestWithPost();
        try {
            $get['dataItems'] = $this->inventory_group_model->get($id);

            $data = [
                'title_modal' => 'Product Group Launching',
                'url_form' => base_url() . 'product_prices/process',
                'form' => $this->load->view('v_launching', $get, true),
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
            $process = $this->inventory_group_model->deleteData($id);
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

    public function productList()
    {
        $get_product_list = $this->inventory_group_model->show_products_data();

        $output = [
            'draw' => 10,
            'recordsTotal' => 100,
            'recordsFiltered' => 10,
            'data' => $get_product_list,
        ];

        echo json_encode($output);
    }

    public function productListResult()
    {
        $get_data = isset($_POST['data_id']) ? $_POST['data_id'] : '';

        $this->inventory_group_model->manageProductData($get_data);
    }

    public function addSKU()
    {
        isAjaxRequestWithPost();
        $data = [
            'title_modal' => 'Product List',
            'url_form' => '',
            'form' => $this->load->view('v_form2', '', true),
            'buttonCloseID' => 'btnCloseModalFullscreen2',
            'buttonID' => 'selectProductList',
            'buttonName' => 'Select',
        ];

        $html = $this->load->view($this->_v_form_modal, $data, true);

        echo json_encode(['html' => $html]);
        exit();
    }

    public function viewSKU()
    {
        $get_data_id = $_POST['data_id'];

        $get['all_data'] = $this->inventory_group_model->_viewProduct($get_data_id)->row();

        isAjaxRequestWithPost();
        $data = [
            'title_modal' => 'Product View Detail',
            'url_form' => '',
            'form' => $this->load->view('v_product', $get, true),
            'buttonCloseID' => 'btnCloseModalFullscreen2',
            'buttonID' => 'btnProcessModal3',
        ];

        $html = $this->load->view($this->_v_form_modal, $data, true);

        echo json_encode(['html' => $html]);
        exit();
    }

    public function launching($id)
    {
        $this->function_access('update');
        $this->template->title('Product Group Launching');
        $this->setTitlePage('Product Group Launching');
        $this->assetsBuild(['datatables']);
        $this->setJs('inventory_group_launching');

        $headerData = json_encode($this->inventory_group_model->_getProductGroup($id)->row());
        $data = json_decode($headerData, true);

        $sourceDefault = ['No', 'Source', 'Channel', 'Action', 'Status'];
        $defaultImage = $this->inventory_group_model->_getInventoryGroupDefault()->get(['users_ms_inventory_groups_id' => $id, 'image_status' => 3]);
        $statusdefaultImage = 'Image Not Selected';
        if (is_object($defaultImage)) {
            $statusdefaultImage = 'Image Selected';
        }
        $statusDefaultImageHtml = "<span id=\"statusDefaultImage\">{$statusdefaultImage}</span>";
        $urlDefaultImage = base_url("inventory_group/default/default/default/{$id}");
        $buttonDefault = "<button class=\"btn btn-outline btn-outline-dashed btn-outline-success btn-sm\" id=\"btnDefaultImage\" data-source=\"default\" data-channel=\"default\" data-fullscreenmodal=\"1\" data-type=\"modal\"  data-url=\"{$urlDefaultImage}\">Select Default Image</button>";
        $sourceDefaultData[0] = ['No' => '1', 'Source' => 'Default', 'Channel' => 'Default', 'Action' => $buttonDefault, 'Status' => $statusDefaultImageHtml];

        $sourceTable = ['No', 'Source', 'Channel', 'Launch Date', 'Action', 'Status'];

        $dataLaunching = $this->inventory_group_model->showLaunching($id);

        $lookupValueDisplay = $this->lookupValuesDisplay();

        $showData = [
            'dataItems' => $data,
            'headerDefault' => generateTableHtml($sourceDefault, $sourceDefaultData),
            'backUrl' => base_url('inventory_group'),
            'buttonDefault' => $buttonDefault,
            'headerSource' => generateTableHtml($sourceTable, [], 'tableSource'),
            'addSourceUrl' => base_url() . 'inventory_group/addsource',
            'productID' => $id,
            'showChannelSourceUrl' => base_url() . 'inventory_group/addchannel',
            'dataLaunching' => json_encode($dataLaunching),
            'lookupDisplayLaunching' => json_encode($lookupValueDisplay['display']),
            'lookupLaunchStatusLaunching' => json_encode($lookupValueDisplay['launchStatus']),
            'lookupDisplayColourLaunching' => json_encode($lookupValueDisplay['colourDisplay']),
        ];

        $this->template->build('v_launching', $showData);
    }

    public function addsource()
    {
        isAjaxRequestWithPost();
        $this->function_access('update');
        $response = $this->inventory_group_model->getSources();
        echo json_encode($response);
    }

    public function addchannel()
    {
        isAjaxRequestWithPost();
        $this->function_access('update');
        $response = $this->inventory_group_model->getChannels();
        echo json_encode($response);
    }

    public function defaultprocess()
    {
        isAjaxRequestWithPost();
        $this->function_access('update');

        $response = $this->inventory_group_model->defaultProcess();
        echo json_encode($response);
    }

    private function lookupValues()
    {
        $get = $this->inventory_group_model->_getLookupValues()->get_all(['lookup_config' => 'inventory_display_images']);
        if (!$get) {
            pageError();
        }

        $data = [];
        foreach ($get as $ky => $val) {
            $data[$val->lookup_code] = $val->lookup_name;
        }

        return $data;
    }

    public function default($source, $channel, $gid)
    {
        isAjaxRequestWithPost();
        $this->function_access('view');
        try {
            //check source && channel
            if ($source != 'default' && $channel != 'default' && empty($gid)) {
                throw new Exception('Failed to request data', 1);
            }

            $headerTable = ['No', 'Product GID', 'Product Group', 'Brand', 'Source', 'Channel'];
            $headerData = $this->inventory_group_model->getGroupDetails($gid);
            if (!$headerData) {
                throw new Exception('Failed request data', 1);
            }

            $imageTable = ['Image', 'Media Name', 'Action', 'Status'];
            $imageData = $this->inventory_group_model->detailShowImageDefault($gid);
            if (!$imageData) {
                throw new Exception('Failed request data', 1);
            }

            $lookupValue = $this->lookupValues();

            $cekButton = $this->db
                ->get_where('users_ms_inventory_groups_defaults', [
                    'users_ms_companys_id' => $this->_users_ms_companys_id,
                    'users_ms_inventory_groups_id' => $gid,
                    'image_status' => 3,
                ])
                ->num_rows();

            $showHtml = [
                'headerTable' => generateTableHtml($headerTable, $headerData),
                'detailTable' => generateTableHtml($imageTable, $imageData['detail']),
                'lookup' => json_encode($lookupValue),
                'dataDefaultArray' => json_encode($imageData['dataArrayDefault']),
            ];

            $urlSaving = base_url('inventory_group/defaultprocess');
            $dataInput = "data-source='{$source}' data-channel='{$channel}' data-productgid='{$gid}' data-url='{$urlSaving}'";
            $data = [
                'title_modal' => 'Product Group Image',
                'content' => $this->load->view('v_default', $showHtml, true),
                'dataInput' => $dataInput,
                'buttonID' => 'btnProcessModalDefault',
                'buttonDisabled' => $cekButton > 0 ? false : true,
            ];

            $html = $this->load->view($this->_v_modal, $data, true);
            $response['html'] = $html;
            echo json_encode($response);
        } catch (Exception $e) {
            $response['failed'] = true;
            $response['message'] = $e->getMessage();
            echo json_encode($response);
        }
    }

    private function lookupValuesDisplay()
    {
        $get = $this->inventory_group_model->_getLookupValues()->get_all(['lookup_config' => 'inventory_displays']);
        if (!$get) {
            pageError();
        }

        $data = [];
        $dataStatus2 = [];
        $dataColour = [];

        foreach ($get as $ky => $val) {
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

    public function notdefaultprocess()
    {
        isAjaxRequestWithPost();
        $this->function_access('update');

        $response = $this->inventory_group_model->notDefaultProcess();
        echo json_encode($response);
    }

    public function notdefault($source, $channel, $gid)
    {
        isAjaxRequestWithPost();
        $this->function_access('view');
        try {
            if ($source == null || $channel == null || $gid == null) {
                throw new Exception('Failed to request Edit', 1);
            }

            $getSource = $this->inventory_group_model->_getSources()->get(['id' => $source]);
            if (!$getSource) {
                throw new Exception('failed request data', 1);
            }

            //check channel
            $getChannel = $this->inventory_group_model->_getChannels()->get(['id' => $channel]);
            if (!$getChannel) {
                throw new Exception('Failed request data', 1);
            }

            $headerTable = ['No', 'Product GID', 'Product Group', 'Brand', 'Source', 'Channel'];
            $headerData = $this->inventory_group_model->getGroupDetails($gid);
            if (!$headerData) {
                throw new Exception('Failed request data', 1);
            }

            $imageTable = ['Image', 'Media Name', 'Action', 'Status'];
            $imageData = $this->inventory_group_model->notDefault($source, $channel, $gid);
            if (!$imageData) {
                throw new Exception('Failed request data', 1);
            }

            $lookupValue = $this->lookupValues();
            $lookupValueDisplay = $this->lookupValuesDisplay();

            $showHtml = [
                'headerTable' => generateTableHtml($headerTable, $headerData),
                'detailTable' => generateTableHtml($imageTable, $imageData['detail']),
                'lookup' => json_encode($lookupValue),
                'dataNotDefaultArray' => json_encode($imageData['dataArrayDefault']),
                'dataSource' => $source,
                'dataChannel' => $channel,
                'dataProductID' => $gid,
                'lookupDisplay' => json_encode($lookupValueDisplay['display']),
                'lookupLaunchStatus' => json_encode($lookupValueDisplay['launchStatus']),
                'lookupDisplayColour' => json_encode($lookupValueDisplay['colourDisplay']),
            ];

            $cekButton = $this->inventory_group_model->showImageNotDefault($source, $channel, $gid)->result();

            $urlSaving = base_url('inventory_group/notdefaultprocess');
            $dataInput = "data-source='{$source}' data-channel='{$channel}' data-productid='{$gid}' data-url='{$urlSaving}'";
            $data = [
                'title_modal' => 'Product Image',
                'content' => $this->load->view('v_notdefault', $showHtml, true),
                'dataInput' => $dataInput,
                'buttonID' => 'btnProcessModalNotDefault',
                'buttonDisabled' => count($cekButton) > 0 ? false : true,
            ];

            $html = $this->load->view($this->_v_modal, $data, true);
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
                'title_modal' => 'Confirm Select',
                'content' => $imageName,
                'buttonFooter' => $button,
            ];

            $html = $this->load->view($this->_v_modal_notButton, $data, true);
            $response['html'] = $html;
            echo json_encode($response);
        } catch (Exception $e) {
            $response['failed'] = true;
            $response['message'] = $e->getMessage();
            echo json_encode($response);
        }
    }

    public function dataSourceChannels()
    {
        isAjaxRequestWithPost();

        $getGid = isset($_POST['group_id']) ? $_POST['group_id'] : '';

        $this->inventory_group_model->manageDataSourceChannels($getGid);
    }

    public function selectDefaultImage($id)
    {
        isAjaxRequestWithPost();
        $get['dataItems'] = $this->inventory_group_model->get($id);

        $data = [
            'title_modal' => 'Product Group Image',
            'url_form' => base_url() . 'inventory_group/process',
            'form' => $this->load->view('v_select_image', $get, true),
            'buttonCloseID' => 'btnCloseModalImage',
            'buttonID' => 'saveProcessDefaultImage',
        ];

        $html = $this->load->view($this->_v_form_modal, $data, true);

        echo json_encode(['html' => $html]);
        exit();
    }

    public function add_source($id)
    {
        isAjaxRequestWithPost();
        $get['getGid'] = $id;

        $data = [
            'title_modal' => 'Add Source & Channel',
            'url_form' => base_url() . 'inventory_group/process_sources',
            'form' => $this->load->view('v_add_source', $get, true),
            'buttonCloseID' => 'btnCloseModalImage',
            'buttonID' => 'saveSources',
            'buttonName' => 'Add',
        ];

        $html = $this->load->view($this->_v_form_modal_custom, $data, true);

        echo json_encode(['html' => $html]);
        exit();
    }

    public function source_list()
    {
        isAjaxRequestWithPost();

        $this->inventory_group_model->manageDataSource();
    }

    public function channels_list()
    {
        isAjaxRequestWithPost();

        $get_sources_id = isset($_POST['sources_id']) ? $_POST['sources_id'] : '';

        $this->inventory_group_model->manageDataChannels($get_sources_id);
    }

    public function image_default()
    {
        isAjaxRequestWithPost();

        $get_group_id = isset($_POST['group_id']) ? $_POST['group_id'] : '';

        $this->inventory_group_model->manageDataImageDefault($get_group_id);
    }

    public function selected_image_default($image)
    {
        isAjaxRequestWithPost();
        $get['image_name'] = $image;
        $get['default_id'] = isset($_POST['default_id']) ? $_POST['default_id'] : '';

        $data = [
            'title_modal' => 'Confirm Select',
            'url_form' => base_url() . 'inventory_group/process_sources',
            'form' => $this->load->view('v_select_image_default', $get, true),
            'buttonCloseID' => 'btnCloseSelect',
            'buttonID' => 'saveSources',
        ];

        $html = $this->load->view($this->_v_form_modal, $data, true);

        echo json_encode(['html' => $html]);
        exit();
    }

    public function view_image_default($image)
    {
        isAjaxRequestWithPost();
        $get['image_name'] = $image;

        $data = [
            'title_modal' => $image,
            'url_form' => base_url() . 'inventory_group/process_sources',
            'form' => $this->load->view('v_view_image_default', $get, true),
            'buttonCloseID' => 'btnCloseSelect',
            'buttonID' => 'saveSources',
        ];

        $html = $this->load->view($this->_v_form_modal, $data, true);

        echo json_encode(['html' => $html]);
        exit();
    }

    public function launch($source, $channel, $productID)
    {
        isAjaxRequestWithPost();
        $this->function_access('update');
        try {
            $status = clearInput($this->input->post('status'));
            if ($status != 4) {
                throw new Exception('Status must be Launch', 1);
            }

            $button = "<button type=\"button\" class=\"btn btn-light me-2\" data-bs-dismiss=\"modal\">Close</button>";
            $buttonUrl = base_url('inventory_group/launchproductsource');
            $button .= "<button type=\"button\" class=\"btn btn-primary btnLaunchProductSource\" data-source = \"{$source}\" data-channel = \"{$channel}\" data-gid= \"{$productID}\" data-url=\"{$buttonUrl}\">Launch</button>";

            $lookupValueDisplay = $this->lookupValuesDisplay();

            $showHtml = [
                'lookupDisplay' => json_encode($lookupValueDisplay['display']),
                'lookupLaunchStatus' => json_encode($lookupValueDisplay['launchStatus']),
                'lookupDisplayColour' => json_encode($lookupValueDisplay['colourDisplay']),
            ];

            $data = [
                'title_modal' => 'Launch Date',
                'content' => $this->load->view('v_launchdate', $showHtml, true),
                'buttonFooter' => $button,
            ];

            $html = $this->load->view($this->_v_modal_notButton, $data, true);
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
        $response = $this->inventory_group_model->launchProductSource();
        echo json_encode($response);
    }

    public function setdefaultimage()
    {
        isAjaxRequestWithPost();
        $this->function_access('update');
        $response = $this->inventory_group_model->setDefaultImage();
        echo json_encode($response);
    }

    public function download()
    {
        isAjaxRequestWithPost();
        $this->function_access('view');
        $this->inventory_group_model->export();
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

            $html = $this->load->view($this->_v_modal_notButton, $data, true);
            $response['html'] = $html;
            echo json_encode($response);
        } catch (Exception $e) {
            $response['failed'] = true;
            $response['message'] = $e->getMessage();
            echo json_encode($response);
        }
    }

    public function detail_view($id)
    {
        isAjaxRequestWithPost();
        $get['dataItems'] = $this->inventory_group_model->_getProductGroup($id)->row();

        $data = [
            'title_modal' => 'Product Group Detail',
            'url_form' => '',
            'form' => $this->load->view('v_detail', $get, true),
            'buttonCloseID' => 'btnCloseModalFullscreen',
            'buttonID' => 'btnProcessDetail',
        ];

        $html = $this->load->view($this->_v_form_modal, $data, true);

        echo json_encode(['html' => $html]);
        exit();
    }

    public function media_view($id)
    {
        isAjaxRequestWithPost();
        $get['dataItems'] = $this->inventory_group_model->_getProductGroup($id)->row();

        $data = [
            'title_modal' => 'Product Group Media',
            'url_form' => '',
            'form' => $this->load->view('v_media', $get, true),
            'buttonCloseID' => 'btnCloseModalFullscreen',
            'buttonID' => 'btnProcessDetail',
        ];

        $html = $this->load->view($this->_v_form_modal, $data, true);

        echo json_encode(['html' => $html]);
        exit();
    }

    public function image_media_view($image_name)
    {
        isAjaxRequestWithPost();
        $url ="./assets/uploads/products_image/" . $image_name;
        $imageUrl = base_url('assets/uploads/products_image/' . $image_name);
        $defaultImage = base_url('assets/uploads/default.png');

        if (!file_exists($url)) {
            $get['image_url'] = $defaultImage;
        }else{
            $get['image_url'] = $imageUrl;
        }

        $data = [
            'title_modal' => $image_name,
            'url_form' => base_url() . 'inventory_group/process_sources',
            'form' => $this->load->view('v_view_image_default', $get, true),
            'buttonCloseID' => 'btnCloseViewImageMedia'
        ];

        $html = $this->load->view($this->_v_form_modal, $data, true);

        echo json_encode(['html' => $html]);
        exit();
    }

    public function media_image()
    {
        isAjaxRequestWithPost();

        $data = isset($_POST['data_id']) ? $_POST['data_id'] : '';

        $this->inventory_group_model->manageDataImageMedia($data);
    }

    public function add_image($gid)
    {
        isAjaxRequestWithPost();
        $get['gid'] = $gid;

        $data = [
            'title_modal' => 'Upload Image',
            'url_form' => base_url() . 'inventory_group/process_media_image',
            'form' => $this->load->view('v_add_image', $get, true),
            'buttonCloseID' => 'btnCloseViewImageMedia',
            'buttonID' => 'btnProcessMedia',
            'buttonName' => 'Add',
        ];

        $html = $this->load->view($this->_v_form_modal, $data, true);

        echo json_encode(['html' => $html]);
        exit();
    }

    public function mass_upload()
    {
        isAjaxRequestWithPost();

        $data = [
            'title_modal' => 'Mass Upload Group',
            'url_form' => '',
            'form' => $this->load->view('v_mass_upload', '', true),
            'buttonCloseID' => 'btnCloseModalFullscreen',
            // 'buttonID' => 'btnProcessDetail',
            // 'buttonName' => 'Add',
        ];

        $html = $this->load->view($this->_v_form_modal, $data, true);

        echo json_encode(['html' => $html]);
        exit();
    }

    public function process_media_image()
    {
        isAjaxRequestWithPost();
     
        $response = $this->inventory_group_model->save_media();

        echo json_encode($response);

        exit();
    }
    
}
