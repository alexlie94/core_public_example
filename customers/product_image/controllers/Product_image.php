<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Product_image extends MY_Customers
{

    public function __construct()
    {
        $this->_function_except = ['account', 'settings', 'show', 'process', 
		'status', 'paging', 'upload','list','list_variant',
		'get_local_storage_product','form_add_variant', 'get_variant_color_by_parent',
		'insert_data','edit','edit_product','view','list_image','image_list_table',
		'list_size','size_list_table','edit_size','edit_variant',"process_edit_variant",
		'get_category_by_parent','get_management_type_by_parent','downloadView'];
       
		parent::__construct();
        $this->_searchBy = [
            'id' => 'Product ID', 'product_name' => 'Product Name', 'brand_name' => 'Brand Name'
        ];

		
    }

    public function Index()
    {
        $this->template->title('Product Media');
        $this->setTitlePage('Data Product Media');
        $this->assetsBuild(['datatables', 'repeater']);

		
		// $data_brand = $this->product_image_model->get_brand()->get_all();
		// $brand = [];
		// foreach ($data_brand as $ky => $val) {
		// 	$brand[$val->id] = $val->brand_name;
		// }

		// $data_status = $this->product_image_model->get_product_status();
		// $product_status = [];
		// foreach ($data_status as $ky => $val) {
		// 	$product_status[$val->lookup_code] = $val->lookup_name;
		// }

        // $cardSearch = [
		// 	[
        //         'label' => 'Product ID',
        //         'type' => 'input',
        //         'name' => 'product_id_filter',
        //     ],
		// 	[
        //         'label' => 'Product Name',
        //         'type' => 'input',
        //         'name' => 'product_name_filter',
        //     ],
        //     [
        //         'label' => 'Brand',
        //         'type' => 'select-multiple',
        //         'name' => 'brand_filter',
		// 		'library' => 'select2',
		// 		'value' => $brand
        //     ],
		// 	[
		// 		'label' => 'Status',
		// 		'type' => 'checkbox',
		// 		'name' => 'product_status_filter',
		// 		'value' => $product_status

		// 	],
        // ];

        // $this->cardSearch($cardSearch);
		$data = [
            'searchBy' => $this->_searchBy,
            'lookupValue' => $this->db
                            ->get_where('admins_ms_lookup_values', ['lookup_config' => 'products_status'])->result(),
        ];

        $header_table = ['no', 'product_id','product name', 'price', 'sale price','size', 'brand', 'status', 'action'];

        $this->setTable($header_table, false);
        $this->setJs('product_image');

        $this->template->build('v_show',$data);
    }

    public function show()
    {
        isAjaxRequestWithPost();
        $this->function_access('view');
        $this->_custom_button_on_table = [
            [
                'button' => 'detail',
                'type' => 'modal',
                'fullscreen' => TRUE,
                'url' => base_url() . "product_image/update/$2",
            ],
            [
                'button' => 'delete',
                'type' => 'confirm',
                'title' => 'Item',
                'confirm' => 'Are you sure you want to delete this item ?',
                'url' => base_url() . "product_image/delete/$2",
            ],
        ];

        $button = $this->setButtonOnTable();

        echo $this->product_image_model->show($button);
    }

	public function insert_data()
	{
		isAjaxRequestWithPost();

		$response = $this->product_image_model->save();
		echo json_encode($response);
		exit();
	}

	public function process_edit_variant()
	{
		isAjaxRequestWithPost();

		$response = $this->product_image_model->edit_variant();
		echo json_encode($response);
		exit();
	}
	

    public function insert()
    {
        isAjaxRequestWithPost();
        // $get['getProducts'] = $this->product_image_model->getProduct();

        $data = [
            'title_modal' => 'Add New Product Image',
            'url_form' => base_url() . 'product_image/process',
            'form' => $this->load->view('v_form', '', true),
            'buttonCloseID' => 'btnCloseModalAddImage',
            'buttonID' => 'btnUploadImage',
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

        $response = $this->product_image_model->upload_image();

        echo json_encode($response);
        exit();
    }

    public function update($id)
    {
        isAjaxRequestWithPost();
        try {
            $get['dataProduct'] = $this->product_image_model->getItems($id);
            $get['getProducts'] = $this->product_image_model->getProduct();

            $data = [
                'title_modal' => 'Edit Products',
                'url_form' => base_url() . 'product_image/process',
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
            $process = $this->product_image_model->deleteData($id);
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
    public function upload()
    {
    }

	public function list()
	{
		isAjaxRequestWithPost();

		// $get = array(
		// 	'all_data' => $this->product_image_model->get_variant($id)
		// );
		
		$data = array(
			'title_modal' => 'List Variant',
			'url_form' => base_url() . "product_image/process",
			'form' => $this->load->view('product_image/v_list', '', true),
			'buttonSave' => false
		);
		$html = $this->load->view($this->_v_form_modal, $data, true);

		echo json_encode(array('html' => $html));
		exit();
	}

	public function list_variant()
    {
		
       $list = $this->product_image_model->get_variant($_POST['id']);

	   $rdata = [];
	   $number = 1;
	   if(is_array($list)){
        	foreach ($list as $vdata) {
				$row =
					[
						'no'					=> $number++,
						'product_id'       		=> $vdata->product_id,
						'variant_id'       		=> $vdata->variant_id,
						'product_name'       	=> $vdata->product_name,
						'general_color'       	=> $vdata->general_color,
						'variant_color'       	=> $vdata->variant_color,
						'variant_color_hexa'    => $vdata->variant_color_hexa,
						'general_color_id'    => $vdata->general_color_id,
						'variant_color_id'    => $vdata->variant_color_id,
					];
				$rdata[] = $row;
			}
		}

        $output = array(
            "draw" => 10,
            "recordsTotal" => 100,
            "recordsFiltered" => 10,
            "data" => $rdata,
        );


        //output to json format
        echo json_encode($output);
    }

	public function edit_variant($product_id,$general_color_id,$variant_color_id)
	{
		isAjaxRequestWithPost();

		$get = array(
			'all_data' => $this->product_image_model->get_data_edit_variant($product_id,$general_color_id,$variant_color_id),
			'general_color' => $this->product_image_model->get_general_color()
		);
		
		$data = array(
			'title_modal' => 'List Edit Variant',
			// 'url_form' => base_url() . "product_image/process",
			'content' => $this->load->view('product_image/v_edit_variant', $get, true),
			'buttonID' => 'btnProcessEditVariant',
			'buttonName' => 'Save'
			
		);
		$html = $this->load->view($this->_v_modal, $data, true);

		echo json_encode(array('html' => $html));
		exit();
	}

	public function list_image()
	{
		isAjaxRequestWithPost();

		// $get = array(
		// 	'all_data' => $this->product_image_model->get_variant($id)
		// );
		
		$data = array(
			'title_modal' => 'List Image',
			'url_form' => base_url() . "product_image/process",
			'form' => $this->load->view('product_image/v_list_image', '', true),
			'buttonSave' => false
		);
		$html = $this->load->view($this->_v_form_modal, $data, true);

		echo json_encode(array('html' => $html));
		exit();
	}

	public function list_size()
	{
		isAjaxRequestWithPost();

		// $get = array(
		// 	'all_data' => $this->product_image_model->get_variant($id)
		// );
		
		$data = array(
			'title_modal' => 'List Variant Size',
			'url_form' => base_url() . "product_image/process",
			'form' => $this->load->view('product_image/v_list_size', '', true),
			'buttonSave' => false
		);
		$html = $this->load->view($this->_v_form_modal, $data, true);

		echo json_encode(array('html' => $html));
		exit();
	}

	public function image_list_table()
    {
		
       $list = $this->product_image_model->get_list_image();

	   $rdata = [];
	   $number = 1;
        foreach ($list as $vdata) {
            $row =
                [
					'id'       				=> $vdata->id,
					'product_id'       		=> $vdata->users_ms_products_id,
					'image_name'       		=> $vdata->image_name,
                    'image_file'       		=> $vdata->image_file
                ];
            $rdata[] = $row;
        }

        $output = array(
            "draw" => 10,
            "recordsTotal" => 100,
            "recordsFiltered" => 10,
            "data" => $rdata,
        );


        //output to json format
        echo json_encode($output);
    }

	public function size_list_table()
    {
		
       $list = $this->product_image_model->get_list_size();

	   $rdata = [];
	   $number = 1;
        foreach ($list as $vdata) {
            $row =
                [
					'no'       				=> $number++,
					'id'       				=> $vdata->id,
					'product_size'       				=> $vdata->product_size,
					'sku'       			=> $vdata->sku
                ];
            $rdata[] = $row;
        }

        $output = array(
            "draw" => 10,
            "recordsTotal" => 100,
            "recordsFiltered" => 10,
            "data" => $rdata,
        );


        //output to json format
        echo json_encode($output);
    }
	
	public function form_add_variant()
    {
        isAjaxRequestWithPost();
        $get['general_color'] = $this->product_image_model->get_general_color();

        $data = [
            'title_modal' => 'Form Create Variant',
            'url_form' => base_url() . 'product_image/process',
			'buttonCloseID' => 'btnCloseAddVariant',
            'buttonID' => 'btnAddNewVariant',
            'buttonName' => 'Create Variant',
            'form' => $this->load->view('product_image/v_add_variant',$get, true)
        ];

        $html = $this->load->view($this->_v_form_modal, $data, true);

        echo json_encode(['html' => $html]);
        exit();
    }

	public function get_local_storage_product($product_id){
		$data = $this->product_image_model->get_product_by_id()->get(array('id' => $product_id));

		$arr = array('product_id' => $data->id,'product_name' => $data->product_name);
		
		echo json_encode($arr);
	}

	public function get_variant_color_by_parent($parent_id){
		$data = $this->product_image_model->get_variant_color_by_parent($parent_id);

        echo json_encode($data);
	}

	public function get_category_by_parent($parent_id){
		$data = $this->product_image_model->get_category_by_parent($parent_id);

        echo json_encode($data);
	}

	
	public function get_management_type_by_parent($parent_id){
		$data = $this->product_image_model->get_management_type_by_parent($parent_id);

        echo json_encode($data);
	}
	

	public function edit($id)
	{
		isAjaxRequestWithPost();

		$get = array(
			'all_data' => $this->product_image_model->get_product_by_id()->get(array('id' => $id)),
			'size' => $this->product_image_model->get_size_by_id_product($id),
			'supplier' => $this->product_image_model->get_supplier()->get_all(),
			'category' => $this->product_image_model->get_category()->get_all(array('parent_categories_id' => 0)),
			'management_type' => $this->product_image_model->get_management_type()->get_all(array('parent_management_type_id' => 0)),
			'matrix' => $this->product_image_model->get_matrix()->get_all(),
			'brand' => $this->product_image_model->get_brand()->get_all(),
		);
		

		$data = array(
			'title_modal' => 'Edit Products',
			// 'url_form' => base_url() . "product_image/process",
			'content' => $this->load->view('product_image/v_edit', $get, true),
			// 'buttonSave' => false,
			'buttonID' => 'btnEditProduct',
			'buttonName' => 'Save'
		);
		$html = $this->load->view($this->_v_modal, $data, true);

		echo json_encode(array('html' => $html));
		exit();
	}


	public function edit_product(){
		$response = $this->product_image_model->edit_product();
		echo json_encode($response);
		exit();
	}

	public function edit_size($id){
		$response = $this->product_image_model->edit_size($id);
		echo json_encode($response);
		exit();
	}

	public function view($id)
	{
		isAjaxRequestWithPost();

		$get = array(
			'all_data' => $this->product_image_model->get_product_by_id()->get(array('id' => $id)),
			'size' => $this->product_image_model->get_size_by_id_product($id),
		);
		
		$data = array(
			'title_modal' => 'View Product',
			'url_form' => base_url() . "product_image/process",
			'form' => $this->load->view('product_image/v_product', $get, true),
			'buttonSave' => false
		);
		$html = $this->load->view($this->_v_form_modal, $data, true);

		echo json_encode(array('html' => $html));
		exit();
	}


	public function downloadView(){

        $dataGet =[
            'searchBy' => isset($_GET['searchName'])? $_GET['searchName']:'',
            'searchValue' => isset($_GET['valueSearch'])? $_GET['valueSearch']:'',
            'valueStatus' => isset($_GET['valueStatus']) ? $_GET['valueStatus'] : ''
        ];

        $this->product_image_model->generateSpreadsheet($dataGet);
    }


}