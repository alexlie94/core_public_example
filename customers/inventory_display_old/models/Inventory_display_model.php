<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Inventory_display_model extends MY_ModelCustomer
{
    use MY_Tables;

    public function __construct()
    {
        $this->_tabel = $this->_table_products;
        $this->_tabel_variant = $this->_table_products_variants;
        parent::__construct();
    }

    public function _getLookupValues()
    {
        $this->_ci->load->model('lookup_values/Lookup_values_model','lookup_values_model');
        return $this->_ci->lookup_values_model;
    }

    public function _getSources()
    {
        $this->_ci->load->model('sources/Sources_model','sources_model');
        return $this->_ci->load->sources_model;
    }

    public function _getChannels()
    {
        $this->_ci->load->model('channels/Channels_model','channels_model');
        return $this->_ci->load->channels_model;
    }

    public function _getProducts()
    {
        $this->_ci->load->model('products/Products_model','products_model');
        return $this->_ci->products_model;
    }

    public function _getProductVariants()
    {
        $this->_ci->load->model('products/Products_variants_model','products_variants_model');
        return $this->_ci->products_variants_model;
    }

    public function _getColorNameHexa()
    {
        $this->_ci->load->model('color_name_hexa/Color_name_hexa_model','color_name_hexa_model');
        return $this->_ci->color_name_hexa_model;
    }

    public function _getInventoryDisplayDefault()
    {
        $this->_ci->load->model('inventory_display_defaults/Inventory_display_defaults_model','inventory_display_defaults_model');
        return $this->_ci->inventory_display_defaults_model;
    }

    public function _getInventoryDisplayNotDefault()
    {
        $this->_ci->load->model('inventory_display_not_defaults/Inventory_display_not_defaults_model','inventory_display_not_defaults_model');
        return $this->_ci->inventory_display_not_defaults_model;
    }

    public function _getInvetoryDisplayDetails()
    {
        $this->_ci->load->model('inventory_display_details/Inventory_display_details_model','inventory_display_details_model');
        return $this->_ci->inventory_display_details_model;
    }

    public function show()
    {

        $filters = !empty($this->input->post('filters')) ? $this->input->post('filters') : false;
        
        $sourceSearch = "";
        $channelSearch = "";

        if ($filters !== false && is_array($filters)) {
            $typeSearch = "";
            $searchValue = "";
            $status = [];
            foreach ($filters as $ky => $val) {
                $value = $val['value'];
                if (!empty($value)) {
                    switch ($val['name']) {
                        case 'searchBy':
                            $typeSearch = $value;
                            break;
                        case 'searchValue':
                            $searchValue = $value;
                            break;
                        case 'status[]' :
                            $status[] = $value;
                            break;
                        case 'source':
                            $sourceSearch = $value;
                            break;
                        case 'channel':
                            $channelSearch = $value;
                            break;
                    }
                }
            }
        }

        $this->datatables->select("
            a.id as id,
            a.id as product_id,
            a.product_name,
            a.product_price,
            a.product_sale_price,
            GROUP_CONCAT(DISTINCT b.product_size) as product_size,
            c.brand_name,
            a.status,
            (SELECT 
                    lookup_name
                FROM
                    admins_ms_lookup_values
                WHERE
                    lookup_config = 'products_status'
                        AND lookup_code = a.status) as status_name",false);
        
        $this->datatables->join("{$this->_table_products_variants} b","b.{$this->_table_products}_id = a.id","inner");
        $this->datatables->join("{$this->_table_ms_brands} c","c.id = a.{$this->_table_ms_brands}_id","inner");
        
        if($sourceSearch != "" && $channelSearch != ""){
            $this->datatables->join("{$this->_table_users_ms_inventory_displays} d","d.{$this->_table_products}_id = a.id and d.{$this->_table_users_ms_companys}_id = {$this->_users_ms_companys_id}","inner");
            $this->datatables->where(array("d.{$this->_table_admins_ms_sources}_id" => $sourceSearch,"d.{$this->_table_users_ms_channels}_id" => $channelSearch));
        }
        
        $this->datatables->where("a.deleted_at is null", null, false);
        $this->datatables->where(array("a.{$this->_table_users_ms_companys}_id" => $this->_users_ms_companys_id));
        $this->datatables->group_by(array('a.id','a.product_code','a.product_name','a.product_price','a.product_sale_price','c.brand_name'));

        $this->datatables->order_by('a.updated_at desc'); 
        $button = "<button class=\"btn btn-outline btn-outline-dashed btn-outline-success btn-sm btnLaunched\" data-status=\"$2\" data-url=\"".base_url("inventory_display/launching/$1")."\" data-id =\"$1\" {{disabled}}>Launching</button>";
        $this->datatables->add_column('action',$button,"id,status");
        $this->datatables->from("{$this->_table_products} a");

        if ($filters !== false && is_array($filters)) {
            
            if(count($status) > 0){
                $this->datatables->where_in('a.status',$status);
            }

            if($typeSearch != "" && $searchValue != ""){
                switch ($typeSearch) {
                    case 'productid':
                        $this->datatables->where(array('a.id' => $searchValue));
                        break;
                    case 'productname':
                        $this->datatables->like("a.product_name",$searchValue);
                        break;
                    case 'brandname':
                        $this->datatables->like("c.brand_name",$searchValue);
                        break;
                    case 'datecreated':
                        $split = explode(" - ",$searchValue); 
                        $this->datatables->where(array('a.created_at >=' => $split[0]));
                        $this->datatables->where(array('a.created_at <=' => $split[1]));
                        break;
                    case 'gender' :
                        $this->datatables->where(array('a.gender' => $searchValue));
                        break;

                    case 'datemodified':
                        $split = explode(" - ",$searchValue); 
                        $this->datatables->where(array('a.updated_at >=' => $split[0]));
                        $this->datatables->where(array('a.updated_at <=' => $split[1]));
                        break;
                }
            }
        }

        $fieldSearch = [
            'a.id',
            'a.product_name',
            'c.brand_name'
        ];

        $this->_searchDefaultDatatables($fieldSearch);

        return $this->datatables->generate();
    }

    public function getChannel($id)
    {
        $response = array('success' => false,'messages'=> '');
        try {

            if(is_null($id)){
                throw new Exception("Failed processing get request", 1);
                
            }

            $get = $this->_getChannels()->get_all_without_delete(array('admins_ms_sources_id' => $id));
            if(!$get){
                throw new Exception("Failed get Channel", 1);
                
            }

            $data = [];
            foreach($get as $ky => $val){
                $data[] = [
                    'id' => $val->id,
                    'channel_name' => $val->channel_name,
                ];
            }

            $response['success'] = true;
            $response['data'] = $data;
            return $response;
        } catch (Exception $e) {
            $response['messages'] = $e->getMessage();
            return $response;
        }
    }

    private function showImageDefault($id)
    {
        $this->db->select("
            a.id,
            a.image_name as image,
            a.image_name as image_name,
            IFNULL(b.image_status, 1) as status_id,
            (SELECT 
                    lookup_name
                FROM
                    admins_ms_lookup_values
                WHERE
                    lookup_code = IFNULL(b.image_status, 1)
                        AND lookup_config = 'inventory_display_images') AS status_name
        ",FALSE);

        $this->db->from("{$this->_table_products_images} a");
        $this->db->where("a.deleted_at is null",null,false);
        $this->db->where(array("a.users_ms_companys_id" => $this->_users_ms_companys_id));
        $this->db->where(array("c.users_ms_companys_id" => $this->_users_ms_companys_id));
        $this->db->where(array("a.users_ms_products_id" => $id));
        $this->db->join("{$this->_table_users_ms_inventory_display_defaults} b", "b.users_ms_product_images_id = a.id and b.users_ms_companys_id = a.users_ms_companys_id","left");
        $this->db->join("{$this->_table_products} c","c.id = a.users_ms_products_id","inner");
        
        $this->db->order_by("a.id desc");

        $query = $this->db->get()->result();
        return $query;
    }

    private function showImageNotDefault($source,$channel,$productID)
    {
        $this->db->select("
            a.id,
            a.image_name as image,
            a.image_name as image_name,
            IFNULL(b.image_status, 1) as status_id,
            (SELECT 
                    lookup_name
                FROM
                    admins_ms_lookup_values
                WHERE
                    lookup_code = IFNULL(b.image_status, 1)
                        AND lookup_config = 'inventory_display_images') AS status_name
        ",FALSE);

        $this->db->from("{$this->_table_products_images} a");
        $this->db->where("a.deleted_at is null",null,false);
        $this->db->where(array("a.users_ms_companys_id" => $this->_users_ms_companys_id));
        $this->db->where(array("c.users_ms_companys_id" => $this->_users_ms_companys_id));
        $this->db->where(array("a.users_ms_products_id" => $productID));
        $this->db->join("{$this->_table_users_ms_inventory_display_details} b", "b.users_ms_product_images_id = a.id and b.users_ms_companys_id = a.users_ms_companys_id and b.admins_ms_sources_id = {$source} and b.users_ms_channels_id = {$channel}","left");
        $this->db->join("{$this->_table_products} c","c.id = a.users_ms_products_id","inner");
        
        $this->db->order_by("a.id desc");

        $query = $this->db->get()->result();
        return $query;
    }

    public function launching($id)
    {
        try {

            if($id == null){
                throw new Exception("Failed process launching", 1);
                
            }
    
            $getProduct = $this->_getProducts()->get(array('id' => $id,'status' => 3));
            if(!$getProduct){
                throw new Exception("Failed process launching get product", 1);
                
            }
    
            $getVariant = $this->_getProductVariants()->get_all(array('users_ms_products_id' => $id));
            if(!$getVariant){
                throw new Exception("Failed process launching get product variant", 1);
                
            }
    
            $variant = $this->headerVariantTable($id,$getProduct->product_name,$getVariant);

            return [
                'variant' => $variant,
                'backUrl' => base_url()."inventory_display",
            ];

            
        } catch (Exception $e) {
            pageError();
        }

        
    }

    public function headerVariantTable($id,$productName,$data)
    {
        $no = 1;
        $variant = [];
        foreach($data as $ky => $val){
            $generalColor = $val->general_color_id;
            $variantColor = $val->variant_color_id;

            $generalColorData = $this->_getColorNameHexa()->get(array('id' => $generalColor));
            $variantColorData = $this->_getColorNameHexa()->get(array('id' => $variantColor));

            $generalColorName = "";
            if(is_object($generalColorData)){
                $generalColorName = $generalColorData->color_name;
            }

            $variantColorName = "";
            if(is_object($variantColorData)){
                $variantColorName = $variantColorData->color_name;
            }

            $variant[] = [
                'No' => $no,
                'ProductID' => $id,
                'Product' => $productName,
                'SKU' => $val->sku,
                'General Color' => $generalColorName,
                'Variant Color' => $variantColorName
            ];

            $no++;
        }

        return $variant;
    }

    public function default($id)
    {
        try {

            if($id == null){
                throw new Exception("Failed process launching", 1);
                
            }
    
            $getProduct = $this->_getProducts()->get(array('id' => $id,'status !=' => 1));
            if(!$getProduct){
                throw new Exception("Failed process launching get product", 1);
                
            }
    
            $getVariant = $this->_getProductVariants()->get_all(array('users_ms_products_id' => $id));
            if(!$getVariant){
                throw new Exception("Failed process launching get product variant", 1);
                
            }
    
            $variant = $this->headerVariantTable($id,$getProduct->product_name,$getVariant);

            $detail = $this->showImageDefault($id);
            if(!$detail){
                throw new Exception("No Image on product", 1);
                
            }

            $no = 1;

            $button = "<button class=\"btn btn-outline btn-outline-dashed btn-outline-success btn-sm btnSelect me-5\" data-imagename=\"$2\"  data-imageid =\"$1\" data-url=\"".base_url("inventory_display/confirmimage")."\" data-type=\"modal\">Select</button>";
            $button .= "<button class=\"btn btn-outline btn-outline-dashed btn-outline-primary btn-sm btnView me-5\" data-imagename=\"$2\"  data-imageid =\"$1\" data-url=\"".base_url("inventory_display/viewimage")."\" data-type=\"modal\">View</button>";
            $button .= "<button class=\"btn btn-outline btn-outline-dashed btn-outline-warning btn-sm btnCancel\" data-imagename=\"$2\"  data-imageid =\"$1\" {{notSelected}} >Cancel</button>";

            $urlImage = base_url("assets/uploads/products_image/");

            $dataArrayDefault = [];

            foreach($detail as $ky => $val){
                $image_id = $val->id;
                $image = $val->image;
                $imageName = $val->image_name;
                $statusID = $val->status_id;
                $status = "<span class=\"statusName\" data-imageid='{$image_id}'>{$val->status_name}</span>";

                $buttonAction = $button;
                $buttonAction = str_replace("$1",$image_id,$buttonAction);
                $buttonAction = str_replace("$2",$imageName,$buttonAction);
                $conditionStatus = $statusID == 1 ? "disabled" : "";
                $buttonAction = str_replace("{{notSelected}}",$conditionStatus,$buttonAction);

                $htmlImage = "<div class=\"symbol symbol-50px\"><span class=\"symbol-label\" style=\"background-image:url(".$urlImage.$imageName.");\"></span></div>";

                $detailImage[] = [
                    'No' => $no,
                    'Image' => $htmlImage,
                    'Image Name' => $imageName,
                    'Action' => $buttonAction,
                    'Status' => $status,
                ];
                $no++;

                $dataArrayDefault[] = [$image_id, (int)$statusID];
            }

            return [
                'variant' => $variant,
                'detail' => $detailImage,
                'dataArrayDefault' => $dataArrayDefault,
            ];

            
        } catch (Exception $e) {
            return [
                'error' => $e->getMessage(),
            ];
        }

        
    }

    public function _validateProduct($imageID)
    {
        $this->db->select("a.users_ms_products_id");
        $this->db->from("{$this->_table_products_images} a");
        $this->db->join("{$this->_table_products} b","b.id = a.users_ms_products_id
            AND b.users_ms_companys_id = a.users_ms_companys_id","inner");
        $this->db->where(array("a.id" => $imageID,"a.users_ms_companys_id" => $this->_users_ms_companys_id));

        return $this->db->get()->row();
    }

    public function defaultProcess()
    {
        $this->db->trans_begin();
        $response = array('success' => false, 'messages' => "Successfully setting image default product");
        try {

            $source = clearInput($this->input->post('source'));
            $channel = clearInput($this->input->post('channel'));
            $productID = clearInput($this->input->post('productid'));

            //check source , check channel
            if($source != "default" || $channel != "default"){
                throw new Exception("failed request data", 1);
                
            }

            //check productID 
            $get = $this->_getProducts()->get(array("id" => $productID));
            if(!$get){
                throw new Exception("Failed request data", 1);
                
            }

            $images = $this->input->post('images');
            $images = json_decode($images);
            if(!is_array($images) || count($images) < 1){
                throw new Exception("Failed Processing Requests", 1);
                
            }

            $users_ms_products_id = $productID;
            $searchMain = false;

            foreach($images as $ky => $val){
                
                $code = $val->value;
                $checkDif = strpos($code,"|");
                if($checkDif === false){
                    throw new Exception("Failed Processing Requests", 1);
                    
                }

                $data = explode("|",$code);
                if(!is_array($data) || count($data) != 2){
                    throw new Exception("Failed Processing Requests", 1);
                    
                }

                $imageID = $data[0];
                $lookup = $data[1];

                if((int)$lookup == 3){
                    $searchMain = true;
                }

                if((int)$lookup > 3){
                    throw new Exception("Failed Processing Request", 1);
                    
                }

                $get = $this->_validateProduct($imageID);
                if(!$get){
                    throw new Exception("Failed Processing Requests", 1);
                    
                }

                if($users_ms_products_id != $get->users_ms_products_id){
                    throw new Exception("Failed Processing Requests", 1);
                    
                }

                $insertOrUpdate = [
                    'users_ms_products_id' => $users_ms_products_id,
                    'users_ms_product_images_id' => $imageID,
                ];

                $search = $this->_getInventoryDisplayDefault()->get($insertOrUpdate);

                $insertOrUpdate['image_status'] = $lookup;

                if(!$search){
                    //insert
                    $insert = $this->_getInventoryDisplayDefault()->insert($insertOrUpdate);
                    if(!$insert){
                        throw new Exception("Failed Processing Data", 1);
                        
                    }
                }else{
                    //update
                    $id = $search->id;
                    $sync_status = $search->sync_status;
                    if($sync_status == 2){
                        $insertOrUpdate['sync_status'] = 1;
                    }
                    $update = $this->_getInventoryDisplayDefault()->update(array('id' => $id),$insertOrUpdate);
                    if(!$update){
                        throw new Exception("Failed Processing Data", 1);

                    }
                }

            }

            if($searchMain === false){
                throw new Exception("Failed request data", 1);
                
            }

            $this->db->trans_commit();
            $response['success'] = true;
            return $response;
        } catch (Exception $e) {
            $this->db->trans_rollback();
            $response['messages'] = $e->getMessage();
            return $response;
        } 
    }

    public function getSources()
    {
        $response = ['success' => true, 'messages' => '','data' => []];
        try {
            $get = $this->_getSources()->get_all(array('status' => 1));
            if(!$get){
                throw new Exception("Data Source not found", 1);
                
            }

            $data = [];
            foreach($get as $ky => $val){
                $data[] = [
                    'id' => $val->id,
                    'source_name' => $val->source_name,
                ];
            }

            if(count($data) <  1){
                throw new Exception("Data Source not found", 1);
                
            }

            $response['data'] = $data;
            return $response;
        } catch (Exception $e) {
            $response['messages'] = $e->getMessage();
            $response['success'] = false;
            return $response;
        }
    }

    public function getChannels()
    {
        $response = ["success" => true, "messages" => "", 'data' => []];
        try {
            $id = clearInput($this->input->post('sourceID'));
            if(empty($id)){
                throw new Exception("Failed Processing Request", 1);
                
            }

            $get = $this->_getChannels()->get_all(array('admins_ms_sources_id' => $id,'status' => 1));
            if(!$get){
                throw new Exception("Data Channel not found", 1);
                
            }

            $data = [];
            foreach($get as $ky => $val){
                $data[] = [
                    'id' => $val->id,
                    'channel_name' => $val->channel_name,
                ];
            }

            if(count($data) <  1){
                throw new Exception("Data Source not found", 1);
                
            }

            $response['data'] = $data;
            return $response;

        } catch (Exception $e) {
            $response['success'] = false;
            $response['messages'] = $e->getMessage();
            return $response;
         }
    }

    public function notDefault($source,$channel,$productID)
    {
        try {

            $getProduct = $this->_getProducts()->get(array('id' => $productID,'status !=' => 1));
            if(!$getProduct){
                throw new Exception("Failed process launching get product", 1);
                
            }
    
            $getVariant = $this->_getProductVariants()->get_all(array('users_ms_products_id' => $productID));
            if(!$getVariant){
                throw new Exception("Failed process launching get product variant", 1);
                
            }
    
            $variant = $this->headerVariantTable($productID,$getProduct->product_name,$getVariant);
            
            $detail = $this->showImageNotDefault($source,$channel,$productID);
            if(!$detail){
                throw new Exception("Error Processing Request", 1);
                
            }

            $no = 1;

            $button = "<button class=\"btn btn-outline btn-outline-dashed btn-outline-success btn-sm btnSelect me-5\" data-imagename=\"$2\"  data-imageid =\"$1\" data-url=\"".base_url("inventory_display/confirmimage")."\" data-type=\"modal\">Select</button>";
            $button .= "<button class=\"btn btn-outline btn-outline-dashed btn-outline-primary btn-sm btnView me-5\" data-imagename=\"$2\"  data-imageid =\"$1\" data-url=\"".base_url("inventory_display/viewimage")."\" data-type=\"modal\">View</button>";
            $button .= "<button class=\"btn btn-outline btn-outline-dashed btn-outline-warning btn-sm btnCancel\" data-imagename=\"$2\"  data-imageid =\"$1\" {{notSelected}} >Cancel</button>";

            $urlImage = base_url("assets/uploads/products_image/");

            $dataArrayDefault = [];

            foreach($detail as $ky => $val){
                $image_id = $val->id;
                $image = $val->image;
                $imageName = $val->image_name;
                $statusID = $val->status_id;
                $status = "<span class=\"statusName\" data-imageid='{$image_id}'>{$val->status_name}</span>";

                $buttonAction = $button;
                $buttonAction = str_replace("$1",$image_id,$buttonAction);
                $buttonAction = str_replace("$2",$imageName,$buttonAction);
                $conditionStatus = $statusID == 1 ? "disabled" : "";
                $buttonAction = str_replace("{{notSelected}}",$conditionStatus,$buttonAction);

                $htmlImage = "<div class=\"symbol symbol-50px\"><span class=\"symbol-label\" style=\"background-image:url(".$urlImage.$imageName.");\"></span></div>";

                $detailImage[] = [
                    'No' => $no,
                    'Image' => $htmlImage,
                    'Image Name' => $imageName,
                    'Action' => $buttonAction,
                    'Status' => $status,
                ];
                $no++;

                $dataArrayDefault[] = [$image_id, (int)$statusID];
            }

            return [
                'variant' => $variant,
                'detail' => $detailImage,
                'dataArrayDefault' => $dataArrayDefault,
            ];
            
        } catch (Exception $e) {
            return [];
        }
    }


    public function notDefaultProcess()
    {
        $this->db->trans_begin();
        $response = array('success' => false, 'messages' => "Successfully setting image product");
        try {
            $source = clearInput($this->input->post('source'));
            $channel = clearInput($this->input->post('channel'));
            $productID = clearInput($this->input->post('productid'));

            //check source 
            $get = $this->_getSources()->get(array('id' => $source));
            if(!$get){
                throw new Exception("failed request data", 1);
                
            }

            //check channel
            $get = $this->_getChannels()->get(array("id" => $channel));
            if(!$get){
                throw new Exception("Failed request data", 1);
                
            }

            //check productID
            $get = $this->_getProducts()->get(array("id" => $productID));
            if(!$get){
                throw new Exception("Failed request data", 1);
                
            }

            $images = $this->input->post('images');
            $images = json_decode($images);
            if(!is_array($images) || count($images) < 1){
                throw new Exception("Failed Processing Requests", 1);
                
            }

            $users_ms_products_id = $productID;
            $searchMain = false;

            $dataHeader = [
                'admins_ms_sources_id' => $source,
                'users_ms_channels_id' => $channel,
                'users_ms_products_id' => $users_ms_products_id,
            ];

            //check header 

            $check = $this->_getInventoryDisplayNotDefault()->get($dataHeader);
            $users_ms_inventory_displays_id = "";
            if(!is_object($check)){
                $dataHeader['display_status_by'] = $this->_user_id;
                $dataHeader['display_status'] = 4;
                $insertHeader = $this->_getInventoryDisplayNotDefault()->insert($dataHeader);
                if(!$insertHeader){
                    throw new Exception("Failed insert data setting product image", 1);
                    
                }
                $users_ms_inventory_displays_id = $insertHeader;
                $response['launch'] = true;
            }else{
                $users_ms_inventory_displays_id = $check->id;

                $updateHeader = $this->_getInventoryDisplayNotDefault()->update(array('id' => $users_ms_inventory_displays_id),$dataHeader);
                if(!$updateHeader){
                    throw new Exception("Failed Processing Request", 1);
                    
                }
            }

            $updateHeaderToPending = false;

            foreach($images as $ky => $val){
                
                $code = $val->value;
                $checkDif = strpos($code,"|");
                if($checkDif === false){
                    throw new Exception("Failed Processing Requests", 1);
                    
                }

                $data = explode("|",$code);
                if(!is_array($data) || count($data) != 2){
                    throw new Exception("Failed Processing Requests", 1);
                    
                }

                $imageID = $data[0];
                $lookup = $data[1];

                if((int)$lookup === 3){
                    $searchMain = true;
                }

                if((int)$lookup > 3){
                    throw new Exception("Failed Processing Request", 1);
                    
                }

                $get = $this->_validateProduct($imageID);
                if(!$get){
                    throw new Exception("Failed Processing Requests", 1);
                    
                }

                if($users_ms_products_id != $get->users_ms_products_id){
                    throw new Exception("Failed Processing Requests", 1);
                    
                }

                $insertOrUpdate = [
                    'users_ms_inventory_displays_id' => $users_ms_inventory_displays_id,
                    'admins_ms_sources_id' => $source,
                    'users_ms_channels_id' => $channel,
                    'users_ms_products_id' => $users_ms_products_id,
                    'users_ms_product_images_id' => $imageID,
                ];

                $search = $this->_getInvetoryDisplayDetails()->get($insertOrUpdate);

                $insertOrUpdate['image_status'] = $lookup;

                if(!$search){
                    //insert
                    $insertOrUpdate['image_status_by'] = $this->_user_id;
                    $insertOrUpdate['sync_status_by'] = $this->_user_id;
                    $insert = $this->_getInvetoryDisplayDetails()->insert($insertOrUpdate);
                    if(!$insert){
                        throw new Exception("Failed Processing Data", 1);
                        
                    }
                }else{
                    //update
                    $id = $search->id;
                    if($search->image_status != $lookup){
                        $insertOrUpdate['image_status_by'] = $this->_user_id;
                        $sync_status = $search->sync_status;
                        if($sync_status == 2){
                            $insertOrUpdate['sync_status'] = 1;
                            $insertOrUpdate['sync_status_by'] = $this->_user_id;
                        }

                        //update header menjadi pending 
                        $updateHeaderToPending = true;
                    }
                    
                    $update = $this->_getInvetoryDisplayDetails()->update(array('id' => $id),$insertOrUpdate);
                    if(!$update){
                        throw new Exception("Failed Processing Data", 1);

                    }
                }

            }

            if($searchMain === false){
                throw new Exception("Failed request data", 1);
                
            }

            if($updateHeaderToPending){
                $updateHeader = $this->_getInventoryDisplayNotDefault()->update(array('id' => $users_ms_inventory_displays_id),['display_status' => 4,'display_status_by' => $this->_user_id,'launch_date' => null]);
                if(!$updateHeader){
                    throw new Exception("Error Processing Request", 1);
                    
                }
                $response['launch'] = true;

                //check status product di inventory 
                //$check = $this->_getInventoryDisplayNotDefault()->get(array('users_ms_products_id' => $productID,'display_status >' => 4));
                //update product to incoming 
                // if(!is_object($check)){
                //     $updateStatusProduct = [
                //         'status' => 3,
                //     ];
                    
                //     $updateProduct = $this->_getProducts()->update(array('id' => $productID),$updateStatusProduct);
                //     if(!$updateProduct){
                //         throw new Exception("Failed Processing Request", 1);
                        
                //     }
                // }
            }

            $this->db->trans_commit();
            $response['success'] = true;
            return $response;
        } catch (Exception $e) {
            $this->db->trans_rollback();
            $response['messages'] = $e->getMessage();
            return $response;
        } 
    }

    public function setDefaultImage()
    {
        $this->db->trans_begin();
        $response = array('success' => false, 'messages' => "Successfully setting image product");
        try {
            $source = clearInput($this->input->post('source'));
            $channel = clearInput($this->input->post('channel'));
            $productID = clearInput($this->input->post('productid'));

            if(is_null($source) || is_null($channel) || is_null($productID)){
                throw new Exception("Error Processing Request", 1);
                
            }

            //check source 
            $get = $this->_getSources()->get(array('id' => $source));
            if(!$get){
                throw new Exception("failed request data", 1);
                
            }

            //check channel
            $get = $this->_getChannels()->get(array("id" => $channel));
            if(!$get){
                throw new Exception("Failed request data", 1);
                
            }

            //check productID
            $get = $this->_getProducts()->get(array("id" => $productID));
            if(!$get){
                throw new Exception("Failed request data", 1);
                
            }

            $checkDataDefault = $this->_getInventoryDisplayDefault()->get(array('users_ms_products_id' => $productID,'image_status' => 3));
            if(!is_object($checkDataDefault)){
                throw new Exception("Default Image Status  is <i><b>Image Not Selected</b></i>", 1);
            }

            $detail = $this->showImageDefault($productID);
            if(!$detail){
                throw new Exception("Failed request data", 1);
                
            }

            $dataHeader = [
                'admins_ms_sources_id' => $source,
                'users_ms_channels_id' => $channel,
                'users_ms_products_id' => $productID,
            ];

            //check header 

            $check = $this->_getInventoryDisplayNotDefault()->get($dataHeader);
            $users_ms_inventory_displays_id = "";
            if(!is_object($check)){
                $dataHeader['display_status'] = 4;
                $dataHeader['display_status_by'] = $this->_user_id;
                $insertHeader = $this->_getInventoryDisplayNotDefault()->insert($dataHeader);
                if(!$insertHeader){
                    throw new Exception("Failed insert data setting product image", 1);
                    
                }
                $users_ms_inventory_displays_id = $insertHeader;
                $response['launch'] = true;
            }else{
                $users_ms_inventory_displays_id = $check->id;

                $updateHeader = $this->_getInventoryDisplayNotDefault()->update(array('id' => $users_ms_inventory_displays_id),$dataHeader);
                if(!$updateHeader){
                    throw new Exception("Failed Processing Request", 1);
                    
                }
            }

            $insertOrUpdate = [
                'users_ms_inventory_displays_id' => $users_ms_inventory_displays_id,
                'admins_ms_sources_id' => $source,
                'users_ms_channels_id' => $channel,
                'users_ms_products_id' => $productID,
            ];

            $searchMain = false;

            $updateHeaderToPending = false;

            foreach($detail as $ky => $val){
                $users_ms_product_images_id = $val->id;
                $image_status = $val->status_id;
                $sync_status = 1;

                if($image_status == 3){
                    $searchMain = true;
                }

                $insertOrUpdate['users_ms_product_images_id'] = $users_ms_product_images_id;

                $search = $this->_getInvetoryDisplayDetails()->get($insertOrUpdate);

                $insertOrUpdate['image_status'] = $image_status;
                $insertOrUpdate['sync_status'] = $sync_status;

                if(!$search){
                    //insert
                    $insertOrUpdate['image_status_by'] = $this->_user_id;
                    $insertOrUpdate['sync_status_by'] = $this->_user_id;
                    $insert = $this->_getInvetoryDisplayDetails()->insert($insertOrUpdate);
                    if(!$insert){
                        throw new Exception("Failed Processing Data", 1);
                        
                    }
                }else{
                    //update
                    $id = $search->id;
                    if($search->image_status != $image_status){
                        $insertOrUpdate['image_status_by'] = $this->_user_id;
                        $sync_status = $search->sync_status;
                        if($sync_status == 2){
                            $insertOrUpdate['sync_status'] = 1;
                            $insertOrUpdate['sync_status_by'] = $this->_user_id;
                        }

                        //update header menjadi pending 
                        $updateHeaderToPending = true;
                    }
                    $update = $this->_getInvetoryDisplayDetails()->update(array('id' => $id),$insertOrUpdate);
                    if(!$update){
                        throw new Exception("Failed Processing Data", 1);

                    }
                }

            }

            if($searchMain === false){
                throw new Exception("Failed request data", 1);
                
            }

            if($updateHeaderToPending){
                $updateHeader = $this->_getInventoryDisplayNotDefault()->update(array('id' => $users_ms_inventory_displays_id),['display_status' => 4,'display_status_by' => $this->_user_id,'launch_date' => null]);
                if(!$updateHeader){
                    throw new Exception("Error Processing Request", 1);
                    
                }

                //check status product di inventory 
                //$check = $this->_getInventoryDisplayNotDefault()->get(array('users_ms_products_id' => $productID,'display_status >' => 4));
                //update product to incoming 
                // if(!is_object($check)){
                //     $updateStatusProduct = [
                //         'status' => 3,
                //     ];
                    
                //     $updateProduct = $this->_getProducts()->update(array('id' => $productID),$updateStatusProduct);
                //     if(!$updateProduct){
                //         throw new Exception("Failed Processing Request", 1);
                        
                //     }
                // }


                $response['launch'] = true;
            }

            $this->db->trans_commit();
            $response['success'] = true;
            return $response;

        } catch (Exception $e) {
            $this->db->trans_rollback();
            $response['messages'] = $e->getMessage();
            return $response;
        }
    }

    public function showLaunching($productID)
    {
        $this->db->select("
            a.users_ms_products_id,
            a.admins_ms_sources_id,
            b.source_name,
            a.users_ms_channels_id,
            c.channel_name,
            IFNULL(a.launch_date,'-') as launch_date,
            a.display_status,
            (select lookup_name from admins_ms_lookup_values where lookup_config = 'inventory_displays' and lookup_code = a.display_status) as status_name
        ",false);

        $this->db->from("{$this->_table_users_ms_inventory_displays} a");
        $this->db->join("{$this->_table_admins_ms_sources} b","b.id = a.admins_ms_sources_id","inner");
        $this->db->join("{$this->_table_users_ms_channels} c","c.id = a.users_ms_channels_id and c.users_ms_companys_id = a.users_ms_companys_id","inner");
        $this->db->where("a.deleted_at is null",null,false);
        $this->db->where(array("a.users_ms_products_id" => $productID));
        $this->db->where(array("a.users_ms_companys_id" => $this->_users_ms_companys_id));

        $query = $this->db->get()->result();
        if(!$query){
            $query = [];
        }
        return $query;

    }

    public function launchProductSource()
    {
        $response = ['success' => true, 'messages' => 'Successfully launch product source'];
        $this->db->trans_begin();
        try {
            $source = clearInput($this->input->post('source'));
            $channel = clearInput($this->input->post('channel'));
            $productID = clearInput($this->input->post('productid'));
            $launchDate = $this->input->post('launchdate');

            if(is_null($launchDate) || $launchDate == ""){
                throw new Exception("Failed Processing Request", 1);
                
            }

            if(is_null($source) || is_null($channel) || is_null($productID)){
                throw new Exception("Failed Processing Request", 1);
                
            }

            //check source 
            $get = $this->_getSources()->get(array('id' => $source));
            if(!$get){
                throw new Exception("failed request data", 1);
                
            }

            //check channel
            $get = $this->_getChannels()->get(array("id" => $channel));
            if(!$get){
                throw new Exception("Failed request data", 1);
                
            }

            //check productID
            $get = $this->_getProducts()->get(array("id" => $productID));
            if(!$get){
                throw new Exception("Failed request data", 1);
                
            }


            $arrCheck = [
                'admins_ms_sources_id' => $source,
                'users_ms_channels_id' => $channel,
                'users_ms_products_id' => $productID,
            ];

            $check = $this->_getInventoryDisplayNotDefault()->get($arrCheck);
            $users_ms_inventory_displays_id = "";

            if(!is_object($check)){
                //saving data from default display
                $get = $this->_getInventoryDisplayDefault()->get_all(array('users_ms_products_id' => $productID));
                if(!$get){
                    throw new Exception("Failed Processing Request", 1);
                    
                }

                $dataHeader = [
                    'admins_ms_sources_id' => $source,
                    'users_ms_channels_id' => $channel,
                    'users_ms_products_id' => $productID,
                    'display_status_by' => $this->_user_id,
                    'display_status' => 4,
                ];

                //save header 
                $saveHeader = $this->_getInventoryDisplayNotDefault()->insert($dataHeader);
                if(!$saveHeader){
                    throw new Exception("Failed Processing Request", 1);
                    
                }

                $users_ms_inventory_displays_id = $saveHeader;

                foreach($get as $ky => $val){
                    $dataDetail = [
                        'users_ms_inventory_displays_id' => $users_ms_inventory_displays_id,
                        'admins_ms_sources_id' => $source,
                        'users_ms_channels_id' => $channel,
                        'users_ms_products_id' => $productID,
                        'users_ms_product_images_id' => $val->users_ms_product_images_id,
                        'image_status_by' => $this->_user_id,
                        'image_status' => $val->image_status,
                        'sync_status_by' => $this->_user_id,
                    ];

                    $save = $this->_getInvetoryDisplayDetails()->insert($dataDetail);
                    if(!$save){
                        throw new Exception("Failed Processing Request", 1);
                        
                    }
                }
            }else{
                $users_ms_inventory_displays_id = $check->id;
            }

            //update header inventory display 
            $updateHeader = [
                'display_status_by' => $this->_user_id,
                'display_status' => 5, //pending
                'launch_by' => $this->_user_id,
                'launch_date' => $launchDate,
            ];

            $updateHeader = $this->_getInventoryDisplayNotDefault()->update(array('id' => $users_ms_inventory_displays_id),$updateHeader);
            if(!$updateHeader){
                throw new Exception("Failed Processing Request", 1);
                
            }

            $this->db->trans_commit();
            return $response;
        } catch (Exception $e) {
            $this->db->trans_rollback();
            $response['success'] = false;
            $response['messages'] = $e->getMessage();
            return $response;
        }
    }

    public function export()
    {
        $status = [];
        $searchValue = "";
        $searchBy = $this->input->get('searchBy');
        if($searchBy != "status"){
            $searchValue = $this->input->get('searchValue');
        }else{
            $status = $this->input->get('status');
        }

        $source = $this->input->get('source');
        $channel = $this->input->get('channel');
        $searchDatatables = $this->input->get('search');

        $this->db->select("a.id as product_id,
            a.product_name,
            a.product_price,
            a.product_sale_price,
            GROUP_CONCAT(DISTINCT b.product_size) as product_size,
            c.brand_name,
            (SELECT 
                    lookup_name
                FROM
                    admins_ms_lookup_values
                WHERE
                    lookup_config = 'products_status'
                        AND lookup_code = a.status) as status_name,
            f.source_name,
            g.channel_name,
            h.image_name,
            (SELECT 
                    lookup_name
                FROM
                    admins_ms_lookup_values
                WHERE
                    lookup_config = 'inventory_displays'
                        AND lookup_code = d.display_status) AS display_status_name,
            d.launch_date",false);

        $this->db->join("{$this->_table_products_variants} b","b.{$this->_table_products}_id = a.id","inner");
        $this->db->join("{$this->_table_ms_brands} c","c.id = a.{$this->_table_ms_brands}_id","inner");
        if($source != "" && $channel != ""){
            $this->db->join("{$this->_table_users_ms_inventory_displays} d","d.{$this->_table_products}_id = a.id and d.{$this->_table_users_ms_companys}_id = {$this->_users_ms_companys_id}","inner");
        }else{
            $this->db->join("{$this->_table_users_ms_inventory_displays} d","d.{$this->_table_products}_id = a.id and d.{$this->_table_users_ms_companys}_id = {$this->_users_ms_companys_id}","left");
        }

        $this->db->join("{$this->_table_users_ms_inventory_display_details} e","e.{$this->_table_users_ms_inventory_displays}_id = d.id","left");
        $this->db->join("{$this->_table_admins_ms_sources} f","f.id = e.{$this->_table_admins_ms_sources}_id","left");
        $this->db->join("{$this->_table_users_ms_channels} g","g.id = e.{$this->_table_users_ms_channels}_id","left");
        $this->db->join("{$this->_table_products_images} h","h.id = e.{$this->_table_products_images}_id","left");

        if($source != "" && $channel != ""){
            $this->db->where(array("d.{$this->_table_admins_ms_sources}_id" => $source,"d.{$this->_table_users_ms_channels}_id" => $channel));
        }

        $this->db->where("a.deleted_at is null", null, false);
        $this->db->where(array("a.{$this->_table_users_ms_companys}_id" => $this->_users_ms_companys_id));
        
        if($searchBy != "status" && $searchValue != ""){
            switch ($searchBy) {
                case 'productid':
                    $this->db->where(array('a.id' => $searchValue));
                    break;
                case 'productname':
                    $this->db->like("a.product_name",$searchValue);
                    break;
                case 'brandname':
                    $this->db->like("c.brand_name",$searchValue);
                    break;
                case 'datecreated':
                    $split = explode(" - ",$searchValue); 
                    $this->db->where(array('a.created_at >=' => $split[0]));
                    $this->db->where(array('a.created_at <=' => $split[1]));
                    break;
                case 'gender' :
                    $this->db->where(array('a.gender' => $searchValue));
                    break;

                case 'datemodified':
                    $split = explode(" - ",$searchValue); 
                    $this->db->where(array('a.updated_at >=' => $split[0]));
                    $this->db->where(array('a.updated_at <=' => $split[1]));
                    break;
            }
        }else{
            if(count($status) > 0){
                $this->db->where_in('a.status',$status);
            }
        }

        if($searchValue == "" && $searchDatatables != "" && $source == "" && $channel == ""){
            $this->db->group_start();
            $this->db->like("a.id",$searchDatatables);
            $this->db->or_like("a.product_name",$searchDatatables);
            $this->db->or_like("c.brand_name",$searchDatatables);
            $this->db->group_end();
        }
        
        $this->db->group_by(array('a.id','a.product_code','a.product_name','a.product_price','a.product_sale_price','c.brand_name',"f.source_name" , "g.channel_name","h.image_name","d.display_status","d.launch_date"));
        $this->db->order_by('a.updated_at desc'); 
        $this->db->from("{$this->_table_products} a");

        $query = $this->db->get()->result_array();

        //prosess converting to xlsx
		$data = array(
			'title' => 'Data Inventory Display',
			'filename' => 'inventory_display',
			'query' => $query,
		);

		$this->excel->process($data);
    }

    public function import($document)
    {
        $response = array('success' => false, 'messages' => 'Failed import file');

		try {

			if (empty($_FILES["file"]["tmp_name"])) {
				throw new Exception("File not found", 1);
			}

            if (file_exists($document)) {
				@unlink($document);
			}

			if (!move_uploaded_file($_FILES["file"]["tmp_name"], $document)) {
				throw new Exception("Failed import file", 1);
			}

            $data = $this->excel->previewCsv($document);
            if (file_exists($document)) {
				@unlink($document);
			}
            
            if(!is_array($data) || count($data) == 0){
                throw new Exception("Failed file Csv", 1);
                
            }

            $totalHeader = 7; 
            $firstKey = array_key_first($data);
            if(count($data[$firstKey]) != $totalHeader){
                throw new Exception("Failed Format file Csv", 1);
                
            }

            $dataSend = [];
            $productIDArray = [];
            $dataProduct = [];

            foreach($data as $ky => $val){
                
                if($ky == $firstKey){
                    continue;
                }

                $sequence = $val['A'];
                $productID = $val['B'];
                $sourceName = $val['C'];
                $channelName = $val['D'];
                $defaultImage = $val['E'];
                $imageName = $val['F'];
                $statusImage = $val['G'];

                $dataSend[] = [
                    'sequence' => $sequence,
                    'productID' => $productID,
                    'productName' => "",
                    'sourceName' => $sourceName,
                    'channelName' => $channelName,
                    'defaultImage' => $defaultImage,
                    'imageName' => $imageName,
                    'statusImage' => $statusImage,
                ];

                $search = array_search($productID,$productIDArray);
                if($search === false){
                    $productIDArray[] = $productID;
                }
                
            }

            if(count($productIDArray) > 0){
                $dataProduct = $this->_getProducts()->getProductList($productIDArray);
            }

            foreach($dataSend  as $ky => $val){
                $productID = $val['productID'];
                $cari = array_search($productID,array_column($dataProduct,'id'));
                if($cari !== false){
                    $dataSend[$ky]['productName'] = $dataProduct[$cari]['product_name'];
                }
            }

            if(count($dataSend) == 0){
                throw new Exception("Data must be exists", 1);
                
            }
            
            $response['data'] = $dataSend;

			$response['success'] = true;
			$response['messages'] = 'successfully import file';
			return $response;
		} catch (Exception $e) {
            $response['messages'] = $e->getMessage();
			return $response;
		}
    }
}
