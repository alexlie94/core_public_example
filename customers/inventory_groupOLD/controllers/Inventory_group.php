<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Inventory_group extends MY_Customers
{

    public function __construct()
    {
        $this->_function_except = ['show', 'process', 'status', 'paging', 'productList', 'addSKU', 'viewSKU', 'launching', 'selectDefaultImage', 'add_source', 'source_list', 'channels_list', 'process_sources', 
        'dataSourceChannels', 'image_default', 'selected_image_default', 'view_image_default', 'update_default_image'];
        parent::__construct();

        $this->_searchBy = [
            'product_gid' => 'Product GID',
            'product_group_name' => 'Product Group Name',
            'brand_name' => 'Brand Name',
            'status' => 'status',
            'date_created' => 'Date Created',
            'date_modified' => 'Date Modified'
        ];
    }

    public function index()
    {
        $this->template->title('Inventory Group');
        $this->setTitlePage('Inventory Group');
        $this->assetsBuild(['datatables']);
        $this->setJs('inventory_group');

        $header_table = ['no', 'product gid', 'product group', 'brand','status', ''];

        $select_source= ['Shopify Berrybenka','BBS'];

        $data = [
            'searchBy' => $this->_searchBy,
            'source' => $select_source,
            'lookupValue' => $this->db
                ->get_where('admins_ms_lookup_values', ['lookup_config' => 'po_status'])->result(),
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
                'fullscreen' => TRUE,
                'url' => base_url() . "inventory_group/update/$1",
            ]
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


    public function productList()
    {
        $get_product_list = $this->inventory_group_model->show_products_data();

        $output =
            [
                "draw" => 10,
                "recordsTotal" => 100,
                "recordsFiltered" => 10,
                "data" => $get_product_list
            ];

        echo json_encode($output);
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
            'buttonName' => 'Select'
        ];

        $html = $this->load->view($this->_v_form_modal, $data, true);

        echo json_encode(['html' => $html]);
        exit();
    }

    public function viewSKU()
    {
        $get_data_id = $_POST['data_id'];

        $get['product_data'] = $this->inventory_group_model->_getProduct()->get(['id' => $get_data_id]);

        isAjaxRequestWithPost();
        $data = [
            'title_modal' => 'Product View Detail',
            'url_form' => '',
            'form' => $this->load->view('v_form3', $get, true),
            'buttonCloseID' => 'btnCloseModalFullscreen2',
            'buttonID' => 'btnProcessModal3'
        ];

        $html = $this->load->view($this->_v_form_modal, $data, true);

        echo json_encode(['html' => $html]);
        exit();
    }

    public function launching($id)
    {
        isAjaxRequestWithPost();
        $get['dataItems'] = $this->inventory_group_model->get($id);
       
        $data = [
            'title_modal' => 'Product Group Launching',
            'url_form' => base_url() . 'inventory_group/process',
            'form' => $this->load->view('v_launching', $get, true),
            'buttonCloseID' => 'btnCloseModalFullscreen',
            'buttonID' => 'saveProcess',
        ];

        $html = $this->load->view($this->_v_form_modal, $data, true);

        echo json_encode(['html' => $html]);
        exit();
    }

    public function dataSourceChannels()
    {
        isAjaxRequestWithPost();

        $getGid = isset($_POST['group_id'])? $_POST['group_id']:'';

        $this->inventory_group_model->manageDataSourceChannels($getGid);
    }


    public function selectDefaultImage($id)
    {
        isAjaxRequestWithPost();
        $get['dataItems'] = $this->inventory_group_model->get($id);

        $data = [
            'title_modal' => 'Product Group Image',
            'url_form' => base_url() . 'inventory_group/process',
            'form' => $this->load->view('v_select_image',$get, true),
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

        $get_sources_id = isset($_POST['sources_id'])? $_POST['sources_id']:'';

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
        $get['default_id'] = isset($_POST['default_id'])? $_POST['default_id']:'';

        $data = [
            'title_modal' => 'Confirm Select',
            'url_form' => base_url() . 'inventory_group/process_sources',
            'form' => $this->load->view('v_select_image_default',$get, true),
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
    
}
