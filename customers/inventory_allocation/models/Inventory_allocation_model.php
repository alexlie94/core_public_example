<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Inventory_allocation_model extends MY_ModelCustomer
{
    use MY_Tables;

    public function __construct()
    {
        $this->_tabel = $this->_table_products;
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

    public function _getProductVariants()
    {
        $this->_ci->load->model('products/Products_variants_model','products_variants_model');
        return $this->_ci->products_variants_model;
    }

    public function _getInventoryAllocationDatatables()
    {
        $this->_ci->load->model('Inventory_allocation_datatables','inventory_allocation_datatables');
        return $this->_ci->inventory_allocation_datatables;
    }

    public function _getInventoryAllocations()
    {
        $this->_ci->load->model('inventory_allocations/Inventory_allocations_model','inventory_allocations_model');
        return $this->_ci->inventory_allocations_model;
    }

    public function _getInventoryAllocationDetails()
    {
        $this->_ci->load->model('inventory_allocation_details/Inventory_allocation_details_model','inventory_allocation_details_model');
        return $this->_ci->inventory_allocation_details_model;
    }

    public function _getInventoryAllocationOfflines()
    {
        $this->_ci->load->model('inventory_allocation_offlines/Inventory_allocation_offlines_model','inventory_allocation_offlines_model');
        return $this->_ci->inventory_allocation_offlines_model;
    }

    public function _getInventoryAllocationOfflineDetails()
    {
        $this->_ci->load->model('inventory_allocation_offline_details/Inventory_allocation_offline_details_model','inventory_allocation_offline_details_model');
        return $this->_ci->inventory_allocation_offline_details_model;
    }

    public function _getOfflineStores()
    {
        $this->_ci->load->model('offline_stores/Offline_stores_model','offline_stores_model');
        return $this->_ci->offline_stores_model;
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

    public function _getProducts()
    {
        $this->_ci->load->model('products/Products_model','products_model');
        return $this->_ci->products_model;
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
            $this->datatables->join("{$this->_table_ms_inventory_allocation} d","d.{$this->_table_products}_id = a.id and d.{$this->_table_users_ms_companys}_id = {$this->_users_ms_companys_id}","inner");
            $this->datatables->join("{$this->_table_ms_inventory_allocation_detail} e","e.{$this->_table_ms_inventory_allocation}_id = d.id","inner");
            $this->datatables->where(array("d.{$this->_table_admins_ms_sources}_id" => $sourceSearch,"d.{$this->_table_users_ms_channels}_id" => $channelSearch));
        }

        $this->db->where("a.deleted_at is null", null, false);
        $this->datatables->where(array("a.{$this->_table_users_ms_companys}_id" => $this->_users_ms_companys_id));

        $this->datatables->group_by(array('a.id','a.product_name','c.brand_name','a.status'));
        $this->datatables->order_by('a.id desc'); 
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
                    case 'sku':
                        $this->datatables->like("b.sku",$searchValue);
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

    public function showOffline()
    {

        $filters = !empty($this->input->post('filters')) ? $this->input->post('filters') : false;
        
        $offlineStoresSearch = "";

        if ($filters !== false && is_array($filters)) {
            $typeSearch = "";
            $searchValue = "";
            $status = [];
            foreach ($filters as $ky => $val) {
                $value = $val['value'];
                if (!empty($value)) {
                    switch ($val['name']) {
                        case 'searchByOffline':
                            $typeSearch = $value;
                            break;
                        case 'searchValueOffline':
                            $searchValue = $value;
                            break;
                        case 'offlineStores':
                            $offlineStoresSearch = $value;
                            break;
                    }
                }
            }
        }

        $this->datatables->select("
            a.id as id,
            a.id as product_id,
            a.product_name,
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

        if($offlineStoresSearch != ""){
            $this->datatables->join("{$this->_table_users_ms_inventory_allocation_offlines} d","d.{$this->_table_products}_id = a.id and d.{$this->_table_users_ms_companys}_id = {$this->_users_ms_companys_id}","inner");
            $this->datatables->join("{$this->_table_users_ms_inventory_allocation_offline_details} e","e.{$this->_table_users_ms_inventory_allocation_offlines}_id = d.id","inner");
            $this->datatables->where(array("d.{$this->_table_users_ms_offline_stores}_id" => $offlineStoresSearch));
        }

        $this->datatables->where(array("a.{$this->_table_users_ms_companys}_id" => $this->_users_ms_companys_id));

        $this->datatables->group_by(array('a.id','a.product_name','c.brand_name','a.status'));
        $this->datatables->order_by('a.id desc'); 
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
                    case 'sku':
                        $this->datatables->like("b.sku",$searchValue);
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

    private function getStorage()
    {
        $this->db->select("
            
        ",false);
    }

    public function tabelAllocaton($productID)
    {
        $thead = [
            'Display',
            'Size',
            'Storage',
        ];

        try {

            $channel = $this->_getInventoryAllocationDatatables()->getChannelSource();
            if(!$channel){
                throw new Exception("Error Processing Request", 1);
                
            }

            $tbody = $this->_getInventoryAllocationDatatables()->query($productID);
            if(!$tbody){
                throw new Exception("Error Processing Request", 1);
                
            }

            $arrayFieldAvailable = [];
            $arrayFieldReserved = [];

            foreach($channel as $ky => $val){
                $source = $val->source_name;
                $channel = $val->channel_name;

                $field = $source."<br>".$channel;
                $thead[] = "Available"."<br>".$field;
                $thead[] = "Reserved"."<br>".$field;

                $arrayFieldAvailable[] = [
                    'name' => "{$val->admins_ms_sources_id}_{$val->users_ms_channels_id}_available",
                    'source' => $val->admins_ms_sources_id,
                    'channel' => $val->users_ms_channels_id,
                ];
                $arrayFieldReserved[] = [
                    'name' => "{$val->admins_ms_sources_id}_{$val->users_ms_channels_id}_reserved",
                    'source' => $val->admins_ms_sources_id,
                    'channel' => $val->users_ms_channels_id,
                ];
            }           

            $table = "<div class=\"table-responsive\">";
            $table .= "<table class=\"table table-row-dashed table-row-gray-300\" id=\"tableVariantAllocation\">";
            $table .= "<thead>";
            $table .= "<tr class=\"fw-bold fs-6 text-gray-800\">";
            $noThead = 0;
            foreach($thead as $ky => $val){
                $table .= "<th class=\"text-center\" ".($noThead == 0 ? "width=\"15%\"" : "").($noThead == 1 ? "width=\"5%\"" : "").($noThead == 2 ? "width=\"5%\"" : "").">".($val != "" ? ucwords($val) : "")."</th>";
                $noThead++;
            }
            $table .= "</tr>";
            $table .="</thead>";
            $table .= "<tbody class=\"fs-6\">";
            foreach($tbody as $ky => $val){
                $table .= "<tr>";
                $table .= "<td><input type=\"text\" class=\"form-control form-control-solid\" readonly value=\"{$val->sku}\"></td>";
                $table .= "<td><input type=\"text\" class=\"form-control form-control-solid\" readonly value=\"{$val->product_size}\"></td>";
                $table .= "<td><input type=\"text\" class=\"form-control form-control-solid\" readonly value=\"{$val->quantity}\"></td>";
                for($i = 0; $i < count($arrayFieldAvailable);$i++){
                    $table .= "<td class=\"available-input\"  data-id=\"{$val->users_ms_product_variants_id}\" data-source=\"{$arrayFieldAvailable[$i]['source']}\" data-channel=\"{$arrayFieldAvailable[$i]['channel']}\"><input type=\"text\" class=\"form-control form-control-solid\" readonly value=\"{$val->{$arrayFieldAvailable[$i]['name']}}\"></td>";
                    $table .= "<td class=\"reserved-input\" data-id=\"{$val->users_ms_product_variants_id}\" data-source=\"{$arrayFieldReserved[$i]['source']}\" data-channel=\"{$arrayFieldReserved[$i]['channel']}\"><input type=\"text\" class=\"form-control\"  value=\"{$val->{$arrayFieldReserved[$i]['name']}}\"></td>";

                }
                $table .= "</tr>";
            }
            $table .= "</tbody>";
            $table .= "</table>";
            $table .= "</div>";


            return $table;

        } catch (Exception $e) {
            return false;
        }

    }

    public function tabelAllocationOffline($productID,&$msgError)
    {
        $thead = [
            'Display',
            'Size',
            'Storage',
        ];

        try {

            $offlineStores = $this->_getInventoryAllocationDatatables()->getOfflineStore();
            if(!$offlineStores){
                throw new Exception("Offline Store not Exists", 1);
                
            }

            $tbody = $this->_getInventoryAllocationDatatables()->queryOffline($productID);
            if(!$tbody){
                throw new Exception("Offline Store not Exists", 1);
                
            }

            foreach($offlineStores as $ky => $val){
                $offlineName = $val->offline_store_name;
                $thead[] = $offlineName;

            }

            $table = "<div class=\"table-responsive\">";
            $table .= "<table class=\"table table-row-dashed table-row-gray-300\" id=\"tableVariantAllocationOffline\">";
            $table .= "<thead>";
            $table .= "<tr class=\"fw-bold fs-6 text-gray-800\">";
            $noThead = 0;
            foreach($thead as $ky => $val){
                $table .= "<th class=\"text-center\" ".($noThead == 0 ? "width=\"15%\"" : "").($noThead == 1 ? "width=\"5%\"" : "").($noThead == 2 ? "width=\"5%\"" : "").">".($val != "" ? ucwords($val) : "")."</th>";
                $noThead++;
            }
            $table .= "</tr>";
            $table .="</thead>";
            $table .= "<tbody class=\"fs-6\">";
            foreach($tbody as $ky => $val){
                $table .= "<tr>";
                $table .= "<td><input type=\"text\" class=\"form-control form-control-solid\" readonly value=\"{$val->sku}\"></td>";
                $table .= "<td><input type=\"text\" class=\"form-control form-control-solid\" readonly value=\"{$val->product_size}\"></td>";
                $table .= "<td><input type=\"text\" class=\"form-control form-control-solid\" readonly value=\"{$val->quantity}\"></td>";
                foreach($offlineStores as $key => $store){
                    $storeName = $store->users_ms_offline_stores_id."_"."reserved";
                    $table .= "<td class=\"reserved-input\" data-id=\"{$val->users_ms_product_variants_id}\" data-offline=\"{$store->users_ms_offline_stores_id}\" ><input type=\"text\" class=\"form-control\"  value=\"{$val->{$storeName}}\"></td>";

                }
                $table .= "</tr>";
            }
            $table .= "</tbody>";
            $table .= "</table>";
            $table .= "</div>";

            return $table;
            
        } catch (Exception $e) {
            $msgError = $e->getMessage();
            return false;
        }
    }

    public function save()
    {
        $this->db->trans_begin();
        $response = ['success' => true,'messages' => ''];
        try {

            $productID = clearInput($this->input->post('productID'));

            $data = $this->input->post('data');
            $data = json_decode($data);

            $header = [];
            foreach($data as $ky => $val){
                $variantsID = $val->variantsID;
                $sourceID = $val->sourceID;
                $channel = $val->channel;
                $reserved = $val->reserved;

                $check = $this->_getProductVariants()->get(array('id' => $variantsID));
                if(!$check){
                    throw new Exception("SKU not found", 1);
                    
                }

                if($check->users_ms_products_id != $productID){
                    throw new Exception("Failed Processing Request", 1);
                    
                }

                $headerKey = $sourceID."_".$channel;
                $search = array_search($headerKey,array_column($header,'key'));
                if($search === false){
                    $header[] = [
                        'key' => $headerKey,
                        'sourceID' => $sourceID,
                        'channel' => $channel,
                        'productID' => $productID,
                        'details' => [
                            0 => [
                                'variantsID' => $variantsID,
                                'reserved' => $reserved,
                            ]
                        ]
                    ];

                }else{
                    $header[$search]['details'][] = [
                        'variantsID' => $variantsID,
                        'reserved' => $reserved,
                    ];
                }
            }

            foreach($header as $ky => $val){
                
                $dataHeader = [
                    'admins_ms_sources_id' => $val['sourceID'],
                    'users_ms_channels_id' => $val['channel'],
                    'users_ms_products_id' => $val['productID'],
                ];

                $check = $this->_getInventoryAllocations()->get($dataHeader);
                $users_ms_inventory_allocations_id = '';
                if(!$check){
                    $insert = $this->_getInventoryAllocations()->insert($dataHeader);
                    if(!$insert){
                        throw new Exception("Failed Insert Data", 1);
                        
                    }

                    $users_ms_inventory_allocations_id = $insert;
                }else{
                    $users_ms_inventory_allocations_id = $check->id;
                }

                foreach($val['details'] as $key => $value){
                    $variantsID = $value['variantsID'];
                    $reserved = $value['reserved'];

                    $dataDetails = [
                        'users_ms_product_variants_id' => $variantsID,
                        'reserved' => $reserved
                    ];

                    //check 
                    $check = $this->_getInventoryAllocationDetails()->get(array('users_ms_inventory_allocations_id' => $users_ms_inventory_allocations_id,'users_ms_product_variants_id' => $variantsID));
                    if(!$check){
                        $dataDetails['users_ms_inventory_allocations_id'] = $users_ms_inventory_allocations_id;

                        $insert = $this->_getInventoryAllocationDetails()->insert($dataDetails);    
                        if(!$insert){
                            throw new Exception("Failed insert Data", 1);
                            
                        }
                    }else{
                        $update = $this->_getInventoryAllocationDetails()->update(array('id' => $check->id),$dataDetails);
                        if(!$update){
                            throw new Exception("Failed update Data", 1);
                            
                        }
                    }
                }

            }
            
            $this->db->trans_commit();
            $response['messages'] = "Successfully Update Data Inventory Allocation";
            return $response;
        } catch (Exception $e) {
            $this->db->trans_rollback();
            $response['success'] = false;
            $response['messages'] = $e->getMessage();
            return $response;
        }
    }

    public function saveoffline()
    {
        $this->db->trans_begin();
        $response = ['success' => true, 'messages' => ''];
        try {
            $productID = clearInput($this->input->post('productID'));
            $data = $this->input->post('data');
            $data = json_decode($data);

            $header = [];
            foreach($data as $ky => $val){
                $variantsID = $val->variantsID;
                $offlineStoreID = $val->offlineStore;
                $reserved = $val->reserved;

                $check = $this->_getProductVariants()->get(array('id' => $variantsID));
                if(!$check){
                    throw new Exception("SKU not found", 1);
                    
                }

                if($check->users_ms_products_id != $productID){
                    throw new Exception("Failed Processing Request", 1);
                    
                }

                $search = array_search($offlineStoreID,array_column($header,'users_ms_offline_stores_id'));
                if($search === false){
                    $header[] = [
                        'users_ms_offline_stores_id' => $offlineStoreID,
                        'users_ms_products_id' => $productID,
                        'details' => [
                            0 => [
                                'users_ms_product_variants_id' => $variantsID,
                                'reserved' => $reserved,
                            ]
                        ]
                    ];
                }else{
                    $header[$search]['details'][] = [
                        'users_ms_product_variants_id' => $variantsID,
                        'reserved' => $reserved,
                    ];
                }

            }

            foreach($header as $ky => $val){
                $dataDetails = $val['details']; 
                unset($val['details']);
                $dataHeaders = $val;

                $check = $this->_getInventoryAllocationOfflines()->get($dataHeaders);
                $users_ms_inventory_allocation_offlines_id = "";
                if(!$check){
                    $insert = $this->_getInventoryAllocationOfflines()->insert($dataHeaders);
                    if(!$insert){
                        throw new Exception("Failed Insert Data", 1);
                        
                    }
                    $users_ms_inventory_allocation_offlines_id = $insert;
                }else{
                    $users_ms_inventory_allocation_offlines_id = $check->id;
                }

                foreach ($dataDetails as $key => $value) {
                    $dataDetails[$key]['users_ms_inventory_allocation_offlines_id'] = $users_ms_inventory_allocation_offlines_id;

                    $check = $this->_getInventoryAllocationOfflineDetails()->get(array('users_ms_inventory_allocation_offlines_id' => $users_ms_inventory_allocation_offlines_id, 'users_ms_product_variants_id' => $value['users_ms_product_variants_id']));
                    if(!$check){
                        $insert = $this->_getInventoryAllocationOfflineDetails()->insert($dataDetails[$key]);
                        if(!$insert){
                            throw new Exception("Failed insert Data", 1);
                            
                        }
                    }else{
                        $update = $this->_getInventoryAllocationOfflineDetails()->update(array('id' => $check->id),$dataDetails[$key]);
                        if(!$update){
                            throw new Exception("Failed update Data", 1);
                            
                        }
                    }
                }
            }

            $this->db->trans_commit();
            $response['messages'] = "Successfully update Data Inventory Allocation Offline";
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

        $this->db->select("
            a.id as product_id,
            a.product_name,
            b.product_size,
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
            IFNULL(h.quantity,0) as available,
            IFNULL(e.reserved,0)",false);

        $this->db->join("{$this->_table_products_variants} b","b.{$this->_table_products}_id = a.id","inner");
        $this->db->join("{$this->_table_ms_brands} c","c.id = a.{$this->_table_ms_brands}_id","inner");

        if($source != "" && $channel != ""){
            $this->db->join("{$this->_table_ms_inventory_allocation} d","d.{$this->_table_products}_id = a.id and d.{$this->_table_users_ms_companys}_id = {$this->_users_ms_companys_id}","inner");
        }else{
            $this->db->join("{$this->_table_ms_inventory_allocation} d","d.{$this->_table_products}_id = a.id and d.{$this->_table_users_ms_companys}_id = {$this->_users_ms_companys_id}","left");
        }

        $this->db->join("{$this->_table_ms_inventory_allocation_detail} e","e.{$this->_table_ms_inventory_allocation}_id = d.id AND e.users_ms_product_variants_id = b.id","left");
        $this->db->join("{$this->_table_admins_ms_sources} f","f.id = d.{$this->_table_admins_ms_sources}_id","left");
        $this->db->join("{$this->_table_users_ms_channels} g","g.id = d.{$this->_table_users_ms_channels}_id","left");
        $this->db->join("{$this->_table_users_ms_product_bb_inventories} h","h.products_id = a.id and h.product_variants_id = b.id","left");


        if($source != "" && $channel != ""){
            $this->db->where(array("d.{$this->_table_admins_ms_sources}_id" => $source,"d.{$this->_table_users_ms_channels}_id" => $channel));
        }

        if($searchBy != "status" && $searchValue != ""){
            switch ($searchBy) {
                case 'productid':
                    $this->db->where(array('a.id' => $searchValue));
                    break;
                case 'sku':
                    $this->db->like("b.sku",$searchValue);
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

        $this->db->where("a.deleted_at is null", null, false);
        $this->db->where(array("a.{$this->_table_users_ms_companys}_id" => $this->_users_ms_companys_id));
        $this->db->group_by(array('a.id','a.product_name','c.brand_name', 'f.source_name' , 'g.channel_name', 'e.reserved','b.product_size','h.quantity'));
        $this->db->order_by('a.updated_at desc, b.product_size desc'); 
        $this->db->from("{$this->_table_products} a");
        
        $query = $this->db->get()->result_array();

        //prosess converting to xlsx
		$data = array(
			'title' => 'Data Inventory Allocation',
			'filename' => 'inventory_allocation',
			'query' => $query,
		);

		$this->excel->process($data);
    }

    public function exportOffline()
    {
        $status = [];
        $searchValue = "";
        $searchBy = $this->input->get('searchByOffline');
        if($searchBy != "status"){
            $searchValue = $this->input->get('searchValueOffline');
        }else{
            $status = $this->input->get('status');
        }

        $offlineStore = $this->input->get('offlineStores');
        $searchDatatables = $this->input->get('search');

        $this->db->select("
            a.id as product_id,
            a.product_name,
            b.product_size,
            c.brand_name,
            (SELECT 
                    lookup_name
                FROM
                    admins_ms_lookup_values
                WHERE
                    lookup_config = 'products_status'
                        AND lookup_code = a.status) as status_name,
            f.offline_store_name,
            IFNULL(g.quantity,0) as available,
            IFNULL(e.reserved,0) as reserved
        ",false);

        $this->db->join("{$this->_table_products_variants} b","b.{$this->_table_products}_id = a.id","inner");
        $this->db->join("{$this->_table_ms_brands} c","c.id = a.{$this->_table_ms_brands}_id","inner");

        if($offlineStore != ""){
            $this->db->join("{$this->_table_users_ms_inventory_allocation_offlines} d","d.{$this->_table_products}_id = a.id and d.{$this->_table_users_ms_companys}_id = {$this->_users_ms_companys_id}","inner");
        }else{
            $this->db->join("{$this->_table_users_ms_inventory_allocation_offlines} d","d.{$this->_table_products}_id = a.id and d.{$this->_table_users_ms_companys}_id = {$this->_users_ms_companys_id}","left");
        }

        $this->db->join("{$this->_table_users_ms_inventory_allocation_offline_details} e","e.{$this->_table_users_ms_inventory_allocation_offlines}_id = d.id AND e.users_ms_product_variants_id = b.id","left");
        $this->db->join("{$this->_table_users_ms_offline_stores} f","f.id = d.{$this->_table_users_ms_offline_stores}_id","left");
        $this->db->join("{$this->_table_users_ms_product_bb_inventories} g","g.products_id = a.id and g.product_variants_id = b.id","left");

        if($offlineStore != ""){
            $this->db->where(array("d.{$this->_table_users_ms_offline_stores}_id" => $offlineStore));
        }

        if($searchBy != "status" && $searchValue != ""){
            switch ($searchBy) {
                case 'productid':
                    $this->db->where(array('a.id' => $searchValue));
                    break;
                case 'sku':
                    $this->db->like("b.sku",$searchValue);
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
            }
        }else{
            if(count($status) > 0){
                $this->db->where_in('a.status',$status);
            }
        }

        if($searchValue == "" && $searchDatatables != "" && $offlineStore == ""){
            $this->db->group_start();
            $this->db->like("a.id",$searchDatatables);
            $this->db->or_like("a.product_name",$searchDatatables);
            $this->db->or_like("c.brand_name",$searchDatatables);
            $this->db->group_end();
        }

        $this->db->where("a.deleted_at is null", null, false);
        $this->db->where(array("a.{$this->_table_users_ms_companys}_id" => $this->_users_ms_companys_id));
        $this->db->group_by(array('a.id','a.product_name','c.brand_name', 'b.product_size','f.offline_store_name','g.quantity','e.reserved'));
        $this->db->order_by('a.updated_at desc, b.product_size desc'); 
        $this->db->from("{$this->_table_products} a");
        
        $query = $this->db->get()->result_array();

        //prosess converting to xlsx
		$data = array(
			'title' => 'Data Inventory Allocation Offline',
			'filename' => 'inventory_allocation_offline',
			'query' => $query,
		);

		$this->excel->process($data);
    }


    public function import()
    {
        $response = array('success' => false, 'messages' => 'Failed import file');
		$document = $this->_document_excel;
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

			$response['success'] = true;
			$response['messages'] = 'successfully import file';
			return $response;
		} catch (Exception $e) {
			$response['messages'] = $e->getMessage();
			return $response;
		}
    }

    public function preview()
	{
		$response = ['success' => false, 'messages' => '', 'data' => ''];
		$document = $this->_document_excel;
		$data = $this->excel->previewCsv($document);
        
		if (file_exists($document)) {
			@unlink($document);
		}

        try {

            if (!is_array($data) || count($data) == 0) {
				throw new Exception("Failed file Excel", 1);
			}

			if (count($data) < 2) {
				throw new Exception("File Excel is empty", 1);
			}

            $headerExcel = ['sku', 'source name', 'channel name', 'reserved qty'];

			$check = checkHeaderDocument($data, $headerExcel);
			if ($check === false) {
				throw new Exception("Failed Header Column on File", 1);
			}

            $statusDataSaving = true;

			$header = ['SKU', 'SIZE', 'STORAGE','SOURCE NAME','CHANNEL NAME','AVAILABLE QTY','RESERVED QTY'];

			$trueData = [];


			$iconTrue = '<span class="svg-icon svg-icon-2x">
                    <svg xmlns="http://www.w3.org/2000/svg" style="margin-top: 9px;" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <polygon points="0 0 24 0 24 24 0 24"/>
                            <path d="M9.26193932,16.6476484 C8.90425297,17.0684559 8.27315905,17.1196257 7.85235158,16.7619393 C7.43154411,16.404253 7.38037434,15.773159 7.73806068,15.3523516 L16.2380607,5.35235158 C16.6013618,4.92493855 17.2451015,4.87991302 17.6643638,5.25259068 L22.1643638,9.25259068 C22.5771466,9.6195087 22.6143273,10.2515811 22.2474093,10.6643638 C21.8804913,11.0771466 21.2484189,11.1143273 20.8356362,10.7474093 L17.0997854,7.42665306 L9.26193932,16.6476484 Z" fill="#008000" fill-rule="nonzero" opacity="0.3" transform="translate(14.999995, 11.000002) rotate(-180.000000) translate(-14.999995, -11.000002) "/>
                            <path d="M4.26193932,17.6476484 C3.90425297,18.0684559 3.27315905,18.1196257 2.85235158,17.7619393 C2.43154411,17.404253 2.38037434,16.773159 2.73806068,16.3523516 L11.2380607,6.35235158 C11.6013618,5.92493855 12.2451015,5.87991302 12.6643638,6.25259068 L17.1643638,10.2525907 C17.5771466,10.6195087 17.6143273,11.2515811 17.2474093,11.6643638 C16.8804913,12.0771466 16.2484189,12.1143273 15.8356362,11.7474093 L12.0997854,8.42665306 L4.26193932,17.6476484 Z" fill="#008000" fill-rule="nonzero" transform="translate(9.999995, 12.000002) rotate(-180.000000) translate(-9.999995, -12.000002) "/>
                        </g>
                    </svg>
                </span>';

            $iconFalse = '<span class="svg-icon svg-icon-2x">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" style="margin-top: 9px;"
                        xmlns="http://www.w3.org/2000/svg">
                        <rect opacity="0.5" x="7.05025" y="15.5356" width="12" height="2"
                            rx="1" transform="rotate(-45 7.05025 15.5356)" fill="#ff0000" />
                        <rect x="8.46447" y="7.05029" width="12" height="2" rx="1"
                            transform="rotate(45 8.46447 7.05029)" fill="#ff0000" />
                    </svg>
                </span>';

            foreach($data as $key => $value){
                if($key <= $check){
                    continue;
                }

                $sku = $value['A'];
                $sourceName = $value['B'];
                $channelName = $value['C'];
                $reserved = $value['D'];

                $errorSku = "";
                $productName = "";
                $productSize = "";
                $storage = "";
                $availableQty = "";
                $users_ms_product_variants_id = "";
                $users_ms_products_id = "";

                if(is_null($sku)){
                    $errorSku = formValidationSelf('form_validation_required',"SKU");
                }else{
                    $get = $this->_getProductVariants()->get(array('sku' => $sku));
                    if(!$get){
                        $errorSku = formValidationSelf('form_validation_found','SKU');
                    }else{
                        $product = $this->_getProducts()->get(array('id' => $get->users_ms_products_id ));
                        if(!$product){
                            $errorSku = formValidationSelf('form_validation_found','SKU');
                        }else{
                            $productName = $product->product_name;
                            $productSize = $get->product_size;
                            $this->db->where(array('product_variants_id' => $get->id));
                            $this->db->where(array('users_ms_companys_id' => $this->_users_ms_companys_id));
                            $getData = $this->db->get("users_ms_product_bb_inventories")->row();
                            $storage = !$getData ? 0 : $getData->quantity;
                            $availableQty = $storage;
                            $users_ms_product_variants_id = $get->id;
                            $users_ms_products_id = $get->users_ms_products_id;
                        }
                    }
                }

                $errorSourceName = "";
                if(is_null($sourceName)){
                    $errorSourceName = formValidationSelf("form_validation_required","SOURCE NAME");
                }else{
                    $get = $this->_getSources()->get(array('source_name' => $sourceName));
                    if(!$get){
                        $errorSourceName = formValidationSelf('form_validation_found',"SOURCE NAME");
                    }else{
                        $sourceName = $get->id;
                    }
                }

                $errorChannelName = "";
                if(is_null($channelName)){
                    $errorChannelName = formValidationSelf('form_validation_required',"CHANNEL NAME");
                }else{

                    $arraySearch = $errorSourceName == "" ? ['admins_ms_sources_id' => $sourceName,'channel_name' => $channelName] : ['channel_name' => $channelName];

                    $get = $this->_getChannels()->get($arraySearch);
                    if(!$get){
                        $errorChannelName = formValidationSelf('form_validation_found','CHANNEL NAME');
                    }else{
                        $channelName = $get->id;
                        if($errorSourceName == "" && $sourceName != $get->admins_ms_sources_id){
                            $errorChannelName = formValidationSelf('form_validation_foundparam','CHANNEL NAME','SOURCE NAME');
                        }
                    }
                }

                $kunci = "";
                if($errorSku == "" && $errorSourceName == "" && $errorChannelName == ""){
                    $kunci = $sku."|".$sourceName."|".$channelName;
                    $cari = array_search($kunci,array_column($trueData,'kunci'));
                    if($cari !== false){
                        $errorSku = $errorSourceName = $errorChannelName = formValidationSelf('form_validation_existexceltable',"SKU with SOURCE NAME and CHANNEL NAME");
                    }else{
                        $getAllocation = $this->_getInventoryAllocations()->get(array('admins_ms_sources_id' => $sourceName,'users_ms_channels_id' => $channelName,'users_ms_products_id' => $users_ms_products_id));
                        if($getAllocation){
                            $users_ms_inventory_allocations_id = $getAllocation->id;
                            $getDetail = $this->_getInventoryAllocationDetails()->get(array('users_ms_inventory_allocations_id' => $users_ms_inventory_allocations_id,'users_ms_product_variants_id' => $users_ms_product_variants_id));
                            if($getDetail){
                                $errorSku = $errorSourceName = $errorChannelName = formValidationSelf('form_validation_existdatabase',"SKU with SOURCE NAME and CHANNEL NAME");
                            }
                        }
                    }
                }

                $errorReserved = "";
                if($reserved == ""){
                    $errorReserved = formValidationSelf("form_validation_required","RESERVED QTY");
                }else if(!is_numeric($reserved)){
                    $errorReserved = formValidationSelf("form_validation_numeric","RESERVED QTY");
                }     
                
                if(empty($errorSku) && empty($errorSourceName) && empty($errorChannelName) && empty($errorReserved)){
                    $statusRow = $iconTrue;
                }else{
                    $statusRow = $iconFalse;
                    $statusDataSaving = false;
                }

                $trueData[] = [
                    'kunci' => $kunci,
                    'sku' => $sku,
                    'errorSku' => $errorSku,
                    'productName' => $productName,
                    'productSize' => $productSize,
                    'storage' => $storage,
                    'availableQty' => $availableQty,
                    'sourceName' => $sourceName,
                    'errorSourceName' => $errorSourceName,
                    'channelName' => $channelName,
                    'errorChannelName' => $errorChannelName,
                    'reserved' => $reserved,
                    'errorReserved' => $errorReserved,
					'action' => '<button type="button" class="btn btn-icon btn-danger btnRemoveRow" {{dataInput}}><i class="bi bi-trash3"></i></button>',
                    'statusRow' => $statusRow,
                ];
            }

            $getSource = $this->_getSources()->get_all(array('status' => 1));
            $getChannel = $this->_getChannels()->get_all(array('status' => 1));

            $header[] = 'ACTION';
            $header[] = "";
 
            $response['data'] = [
				'thead' => $header,
				'tbody' => $trueData,
				'statusDataSaving' => $statusDataSaving,
                'getSource' => $getSource,
                'getChannel' => $getChannel,
			];

            $response['success'] = true;
            return $response;

        } catch (Exception $e) {
            $response['messages'] = $e->getMessage();
			return $response;
        }

	}

    public function checkingData()
	{
		$response = $this->validationDataUpload();
		try {
			if ($response['success'] === false) {
				throw new Exception("Error Processing Request", 1);
			}
			return $response;
		} catch (Exception $e) {
			return $response;
		}
	}

    private function validationDataUpload()
	{
		$response = ['success' => false, 'messages' => '', 'buttonName' => '', 'buttonUrl' => ''];

		try {

			$data = $this->input->post();

			if (!is_array($data) || count($data) == 0) {
				throw new Exception("Error Processing Request", 1);
			}

			$statusDataSaving = true;

			$iconTrue = '<span class="svg-icon svg-icon-2x">
                    <svg xmlns="http://www.w3.org/2000/svg" style="margin-top: 9px;" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <polygon points="0 0 24 0 24 24 0 24"/>
                            <path d="M9.26193932,16.6476484 C8.90425297,17.0684559 8.27315905,17.1196257 7.85235158,16.7619393 C7.43154411,16.404253 7.38037434,15.773159 7.73806068,15.3523516 L16.2380607,5.35235158 C16.6013618,4.92493855 17.2451015,4.87991302 17.6643638,5.25259068 L22.1643638,9.25259068 C22.5771466,9.6195087 22.6143273,10.2515811 22.2474093,10.6643638 C21.8804913,11.0771466 21.2484189,11.1143273 20.8356362,10.7474093 L17.0997854,7.42665306 L9.26193932,16.6476484 Z" fill="#008000" fill-rule="nonzero" opacity="0.3" transform="translate(14.999995, 11.000002) rotate(-180.000000) translate(-14.999995, -11.000002) "/>
                            <path d="M4.26193932,17.6476484 C3.90425297,18.0684559 3.27315905,18.1196257 2.85235158,17.7619393 C2.43154411,17.404253 2.38037434,16.773159 2.73806068,16.3523516 L11.2380607,6.35235158 C11.6013618,5.92493855 12.2451015,5.87991302 12.6643638,6.25259068 L17.1643638,10.2525907 C17.5771466,10.6195087 17.6143273,11.2515811 17.2474093,11.6643638 C16.8804913,12.0771466 16.2484189,12.1143273 15.8356362,11.7474093 L12.0997854,8.42665306 L4.26193932,17.6476484 Z" fill="#008000" fill-rule="nonzero" transform="translate(9.999995, 12.000002) rotate(-180.000000) translate(-9.999995, -12.000002) "/>
                        </g>
                    </svg>
                </span>';

			$iconFalse = '<span class="svg-icon svg-icon-2x">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" style="margin-top: 9px;"
                        xmlns="http://www.w3.org/2000/svg">
                        <rect opacity="0.5" x="7.05025" y="15.5356" width="12" height="2"
                            rx="1" transform="rotate(-45 7.05025 15.5356)" fill="#ff0000" />
                        <rect x="8.46447" y="7.05029" width="12" height="2" rx="1"
                            transform="rotate(45 8.46447 7.05029)" fill="#ff0000" />
                    </svg>
                </span>';

			$validation = [];
			$validationIcon = [];

			$trueData = [];

			$listSku = $data['sku'];
            $calculationError = [];

			foreach ($listSku as $ky => $val) {
				$i = $ky;

                $sku = $data['sku'][$i];
                $sourceName = $data['sourceName'][$i];
                $channelName = $data['channelName'][$i];
                $reserved = $data['reserved'][$i];

                $errorSku = "";
                $productName = "";
                $productSize = "";
                $storage = "";
                $availableQty = "";
                $users_ms_product_variants_id = "";
                $users_ms_products_id = "";

                if(is_null($sku)){
                    $errorSku = formValidationSelf('form_validation_required',"SKU");
                }else{
                    $get = $this->_getProductVariants()->get(array('sku' => $sku));
                    if(!$get){
                        $errorSku = formValidationSelf('form_validation_found','SKU');
                    }else{
                        $product = $this->_getProducts()->get(array('id' => $get->users_ms_products_id ));
                        if(!$product){
                            $errorSku = formValidationSelf('form_validation_found','SKU');
                        }else{
                            $productName = $product->product_name;
                            $productSize = $get->product_size;
                            $this->db->where(array('product_variants_id' => $get->id));
                            $this->db->where(array('users_ms_companys_id' => $this->_users_ms_companys_id));
                            $getData = $this->db->get("users_ms_product_bb_inventories")->row();
                            $storage = !$getData ? 0 : $getData->quantity;
                            $availableQty = $storage;
                            $users_ms_product_variants_id = $get->id;
                            $users_ms_products_id = $get->users_ms_products_id;
                        }
                    }
                }

                $validation[] = [
					'type' => "input",
					'name' => "sku",
					'sequence' => $i,
					'message' => $errorSku,
                    'productSize' => $productSize,
                    'storage' => $storage,
                    'availableQty' => $availableQty,
				];

                $errorSourceName = "";
                if(is_null($sourceName)){
                    $errorSourceName = formValidationSelf("form_validation_required","SOURCE NAME");
                }else{
                    $get = $this->_getSources()->get(array('id' => $sourceName));
                    if(!$get){
                        $errorSourceName = formValidationSelf('form_validation_found',"SOURCE NAME");
                    }else{
                        $sourceName = $get->id;
                    }
                }

                $validation[] = [
					'type' => "select",
					'name' => "sourceName",
					'sequence' => $i,
					'message' => $errorSourceName,
				];

                $errorChannelName = "";
                if(is_null($channelName)){
                    $errorChannelName = formValidationSelf('form_validation_required',"CHANNEL NAME");
                }else{

                    $arraySearch = $errorSourceName == "" ? ['admins_ms_sources_id' => $sourceName,'id' => $channelName] : ['id' => $channelName];

                    $get = $this->_getChannels()->get($arraySearch);
                    if(!$get){
                        $textError = $errorSourceName == "" ? formValidationSelf('form_validation_foundparam','CHANNEL NAME','SOURCE NAME') : formValidationSelf('form_validation_found','CHANNEL NAME');
                        $errorChannelName = $textError;
                    }else{
                        $channelName = $get->id;
                        if($errorSourceName == "" && $sourceName != $get->admins_ms_sources_id){
                            $errorChannelName = formValidationSelf('form_validation_foundparam','CHANNEL NAME','SOURCE NAME');
                        }
                    }
                }

                $validation[] = [
					'type' => "select",
					'name' => "channelName",
					'sequence' => $i,
					'message' => $errorChannelName,
				];

                $kunci = "";
                if($errorSku == "" && $errorSourceName == "" && $errorChannelName == ""){
                    $kunci = $sku."|".$sourceName."|".$channelName;
                    $cari = array_search($kunci,array_column($trueData,'kunci'));
                    if($cari !== false){
                        $errorSku = $errorSourceName = $errorChannelName = formValidationSelf('form_validation_existexceltable',"SKU with SOURCE NAME and CHANNEL NAME");
                        
                        foreach($validation as $keyVal => $valVal){
                            if($valVal['name'] == "sku" && $valVal['sequence'] == $i){
                                $validation[$keyVal]['message'] = $errorSku;
                            }
                            if($valVal['name'] == "sourceName" && $valVal['sequence'] == $i){
                                $validation[$keyVal]['message'] = $errorSourceName;
                            }
                            if($valVal['name'] == "channelName" && $valVal['sequence'] == $i){
                                $validation[$keyVal]['message'] = $errorChannelName;
                            }
                        }
                    
                    }else{
                        $getAllocation = $this->_getInventoryAllocations()->get(array('admins_ms_sources_id' => $sourceName,'users_ms_channels_id' => $channelName,'users_ms_products_id' => $users_ms_products_id));
                        if($getAllocation){
                            $users_ms_inventory_allocations_id = $getAllocation->id;
                            $getDetail = $this->_getInventoryAllocationDetails()->get(array('users_ms_inventory_allocations_id' => $users_ms_inventory_allocations_id,'users_ms_product_variants_id' => $users_ms_product_variants_id));
                            if($getDetail){
                                $errorSku = $errorSourceName = $errorChannelName = formValidationSelf('form_validation_existdatabase',"SKU with SOURCE NAME and CHANNEL NAME");
                                foreach($validation as $keyVal => $valVal){
                                    if($valVal['name'] == "sku" && $valVal['sequence'] == $i){
                                        $validation[$keyVal]['message'] = $errorSku;
                                    }
                                    if($valVal['name'] == "sourceName" && $valVal['sequence'] == $i){
                                        $validation[$keyVal]['message'] = $errorSourceName;
                                    }
                                    if($valVal['name'] == "channelName" && $valVal['sequence'] == $i){
                                        $validation[$keyVal]['message'] = $errorChannelName;
                                    }
                                }
                            }
                        }
                    }
                }

                $errorReserved = "";
                if($reserved == ""){
                    $errorReserved = formValidationSelf("form_validation_required","RESERVED QTY");
                }else if(!is_numeric($reserved)){
                    $errorReserved = formValidationSelf("form_validation_numeric","RESERVED QTY");
                }

                $validation[] = [
					'type' => "input",
					'name' => "reserved",
					'sequence' => $i,
					'message' => $errorReserved,
				];


                $trueData[] = [
                    'kunci' => $kunci,
                    'sku' => $sku,
                    'errorSku' => $errorSku,
                    'productName' => $productName,
                    'productSize' => $productSize,
                    'storage' => $storage,
                    'availableQty' => $availableQty,
                    'sourceName' => $sourceName,
                    'errorSourceName' => $errorSourceName,
                    'channelName' => $channelName,
                    'errorChannelName' => $errorChannelName,
                    'reserved' => $reserved,
                    'errorReserved' => $errorReserved,
                ];


                if (count($validationIcon) == 0) {
					$validationIcon[] = [
						'sequence' => $i,
						'icon' => $iconTrue,
					];
				} else {
					$cari = array_search($i, array_column($validationIcon, 'sequence'));
					if ($cari === false) {
						$validationIcon[] = [
							'sequence' => $i,
							'icon' => $iconTrue,
						];
					}
				}

            }

            foreach ($validation as $ky => $val) {
				$sequence = $val['sequence'];
				$message = $val['message'];
				$error = 0;
				if ($message != "") {
					$error = 1;
				}

				if (count($calculationError) == 0 && $error == 1) {
					$calculationError[] = [
						'sequence' => $sequence,
						'error' => true,
					];
				} else if ($error == 1) {
					$cari = array_search($sequence, array_column($calculationError, 'sequence'));
					if ($cari === false) {
						$calculationError[] = [
							'sequence' => $sequence,
							'error' => true,
						];
					}
				}
			}

            if (count($calculationError) > 0) {
				$statusDataSaving = false;
			}

            foreach ($calculationError as $ky => $val) {
				$sequence = $val['sequence'];
				$cari = array_search($sequence, array_column($validationIcon, 'sequence'));
				if ($cari !== false) {
					$validationIcon[$cari]['icon'] = $iconFalse;
				}
			}


			$response['messages'] = "";
			$response['buttonUrl'] = $statusDataSaving ? base_url() . "inventory_allocation/processupload" : base_url() . "inventory_allocation/checkingdata";
			$response['buttonName'] = $statusDataSaving  ? "Save Change" : "Check Data";
			$response['validation'] = $validation;
			$response['validationIcon'] = $validationIcon;
			$response['success'] = $statusDataSaving;

			return $response;
		} catch (Exception $e) {
			$response['messages'] = $e->getMessage();
			$response['buttonUrl'] = base_url() . "inventory_allocation/checkingdata";
			$response['buttonName'] = 'Check Data';
			$response['validation'] = "";
			$response['validationIcon'] = "";
			$response['success'] = false;

			return $response;
		}
	}

    public function saveUpload()
	{
		$response = ['success' => false, 'messages' => '', 'buttonName' => '', 'buttonUrl' => ''];

		$this->db->trans_begin();
		try {

			$data = $this->input->post();
			if (!is_array($data) || count($data) == 0) {
				throw new Exception("Error Processing Request1", 1);
			}

			$listSku = $data['sku'];

            foreach($listSku as $ky => $val){
                
                $sku = $data['sku'][$ky];
                $sourceID = $data['sourceName'][$ky];
                $channelID = $data['channelName'][$ky];
                $available = $data['available'][$ky];
                $reserved = $data['reserved'][$ky];

                if(!is_numeric($available)){
                    throw new Exception("Failed value Available qty", 1);
                    
                }

                if(!is_numeric($reserved)){
                    throw new Exception("Failed value reserved qty", 1);
                    
                }

                $getVariant = $this->_getProductVariants()->get(array('sku' => $sku));
                if(!$getVariant){
                    throw new Exception("SKU : {$sku} not found", 1);
                    
                }

                $users_ms_products_id = $getVariant->users_ms_products_id;
                $users_ms_product_variants_id = $getVariant->id;

                $getProducts = $this->_getProducts()->get(array('id' => $getVariant->users_ms_products_id ));
                if(!$getProducts){
                    throw new Exception("Product SKU {$sku} not found", 1);
                    
                }

                $getChannel = $this->_getChannels()->get(array('admins_ms_sources_id' => $sourceID,'id' =>$channelID));
                if(!$getChannel){
                    throw new Exception("Channel not found in Source", 1);
                    
                }

                $header = [
                    'admins_ms_sources_id' => $sourceID,
                    'users_ms_channels_id' => $channelID,
                    'users_ms_products_id' => $users_ms_products_id,
                ];

                //cek 
                $cek = $this->_getInventoryAllocations()->get($header);
                if($cek){
                    $users_ms_inventory_allocations_id = $cek->id;
                }else{
                    //insert header 
                    $insert = $this->_getInventoryAllocations()->insert($header);
                    if(!$insert){
                        throw new Exception("Failed insert data allocation", 1);
                        
                    }
                    $users_ms_inventory_allocations_id = $insert;
                }

                $detail = [
                    'users_ms_inventory_allocations_id' => $users_ms_inventory_allocations_id,
                    'users_ms_product_variants_id' => $users_ms_product_variants_id,
                    'available' => $available,
                    'reserved' => $reserved,
                ];

                $insert = $this->_getInventoryAllocationDetails()->insert($detail);
                if(!$insert){
                    throw new Exception("Failed insert data allocation", 1);
                    
                }
                      

            }

			$response['success'] = true;
			$response['messages'] = "successfully upload data";
			$this->db->trans_commit();
			return $response;
		} catch (Exception $e) {
			$this->db->trans_rollback();
			$response['messages'] = $e->getMessage();
			return $response;
		}
	}

    public function importoffline()
    {
        $response = array('success' => false, 'messages' => 'Failed import file');
		$document = $this->_document_excel_offline;
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

			$response['success'] = true;
			$response['messages'] = 'successfully import file';
			return $response;
		} catch (Exception $e) {
			$response['messages'] = $e->getMessage();
			return $response;
		}
    }

    public function previewoffline()
	{
		$response = ['success' => false, 'messages' => '', 'data' => ''];
		$document = $this->_document_excel_offline;
		$data = $this->excel->previewCsv($document);
        
		if (file_exists($document)) {
			@unlink($document);
		}
        

        try {

            if (!is_array($data) || count($data) == 0) {
				throw new Exception("Failed file Excel", 1);
			}

			if (count($data) < 2) {
				throw new Exception("File Excel is empty", 1);
			}

            $headerExcel = ['sku', 'offline store name', 'reserved qty'];

			$check = checkHeaderDocument($data, $headerExcel);
			if ($check === false) {
				throw new Exception("Failed Header Column on File", 1);
			}

            $statusDataSaving = true;

			$header = ['SKU', 'SIZE', 'STORAGE','OFFLINE STORE NAME','AVAILABLE QTY','RESERVED QTY'];

			$trueData = [];


			$iconTrue = '<span class="svg-icon svg-icon-2x">
                    <svg xmlns="http://www.w3.org/2000/svg" style="margin-top: 9px;" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <polygon points="0 0 24 0 24 24 0 24"/>
                            <path d="M9.26193932,16.6476484 C8.90425297,17.0684559 8.27315905,17.1196257 7.85235158,16.7619393 C7.43154411,16.404253 7.38037434,15.773159 7.73806068,15.3523516 L16.2380607,5.35235158 C16.6013618,4.92493855 17.2451015,4.87991302 17.6643638,5.25259068 L22.1643638,9.25259068 C22.5771466,9.6195087 22.6143273,10.2515811 22.2474093,10.6643638 C21.8804913,11.0771466 21.2484189,11.1143273 20.8356362,10.7474093 L17.0997854,7.42665306 L9.26193932,16.6476484 Z" fill="#008000" fill-rule="nonzero" opacity="0.3" transform="translate(14.999995, 11.000002) rotate(-180.000000) translate(-14.999995, -11.000002) "/>
                            <path d="M4.26193932,17.6476484 C3.90425297,18.0684559 3.27315905,18.1196257 2.85235158,17.7619393 C2.43154411,17.404253 2.38037434,16.773159 2.73806068,16.3523516 L11.2380607,6.35235158 C11.6013618,5.92493855 12.2451015,5.87991302 12.6643638,6.25259068 L17.1643638,10.2525907 C17.5771466,10.6195087 17.6143273,11.2515811 17.2474093,11.6643638 C16.8804913,12.0771466 16.2484189,12.1143273 15.8356362,11.7474093 L12.0997854,8.42665306 L4.26193932,17.6476484 Z" fill="#008000" fill-rule="nonzero" transform="translate(9.999995, 12.000002) rotate(-180.000000) translate(-9.999995, -12.000002) "/>
                        </g>
                    </svg>
                </span>';

            $iconFalse = '<span class="svg-icon svg-icon-2x">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" style="margin-top: 9px;"
                        xmlns="http://www.w3.org/2000/svg">
                        <rect opacity="0.5" x="7.05025" y="15.5356" width="12" height="2"
                            rx="1" transform="rotate(-45 7.05025 15.5356)" fill="#ff0000" />
                        <rect x="8.46447" y="7.05029" width="12" height="2" rx="1"
                            transform="rotate(45 8.46447 7.05029)" fill="#ff0000" />
                    </svg>
                </span>';

            foreach($data as $key => $value){
                if($key <= $check){
                    continue;
                }

                $sku = $value['A'];
                $offlineStoreName = $value['B'];
                $reserved = $value['C'];

                $errorSku = "";
                $productName = "";
                $productSize = "";
                $storage = "";
                $availableQty = "";
                $users_ms_product_variants_id = "";
                $users_ms_products_id = "";

                if(is_null($sku)){
                    $errorSku = formValidationSelf('form_validation_required',"SKU");
                }else{
                    $get = $this->_getProductVariants()->get(array('sku' => $sku));
                    if(!$get){
                        $errorSku = formValidationSelf('form_validation_found','SKU');
                    }else{
                        $product = $this->_getProducts()->get(array('id' => $get->users_ms_products_id ));
                        if(!$product){
                            $errorSku = formValidationSelf('form_validation_found','SKU');
                        }else{
                            $productName = $product->product_name;
                            $productSize = $get->product_size;
                            $this->db->where(array('product_variants_id' => $get->id));
                            $this->db->where(array('users_ms_companys_id' => $this->_users_ms_companys_id));
                            $getData = $this->db->get("users_ms_product_bb_inventories")->row();
                            $storage = !$getData ? 0 : $getData->quantity;
                            $availableQty = $storage;
                            $users_ms_product_variants_id = $get->id;
                            $users_ms_products_id = $get->users_ms_products_id;
                        }
                    }
                }

                $errorOfflineStoreName = "";
                if(is_null($offlineStoreName)){
                    $errorOfflineStoreName = formValidationSelf("form_validation_required","OFFLINE STORE NAME");
                }else{
                    $get = $this->_getOfflineStores()->get(array('offline_store_name' => $offlineStoreName));
                    if(!$get){
                        $errorOfflineStoreName = formValidationSelf('form_validation_found',"OFFLINE STORE NAME");
                    }else{
                        $offlineStoreName = $get->id;
                    }
                }

                $kunci = "";
                if($errorSku == "" && $errorOfflineStoreName == ""){
                    $kunci = $sku."|".$offlineStoreName;
                    $cari = array_search($kunci,array_column($trueData,'kunci'));
                    if($cari !== false){
                        $errorSku = $errorOfflineStoreName = formValidationSelf('form_validation_existexceltable',"SKU with OFFLINE STORE NAME");
                    }else{
                        $getAllocation = $this->_getInventoryAllocationOfflines()->get(array('users_ms_offline_stores_id' => $offlineStoreName, 'users_ms_products_id' => $users_ms_products_id));
                        if($getAllocation){
                            $users_ms_inventory_allocation_offlines_id = $getAllocation->id;
                            $getDetail = $this->_getInventoryAllocationOfflineDetails()->get(array('users_ms_inventory_allocation_offlines_id' => $users_ms_inventory_allocation_offlines_id,'users_ms_product_variants_id' => $users_ms_product_variants_id));
                            if($getDetail){
                                $errorSku = $errorOfflineStoreName = formValidationSelf('form_validation_existdatabase',"SKU with OFFLINE STORE NAME");
                            }
                        }
                    }
                }

                $errorReserved = "";
                if($reserved == ""){
                    $errorReserved = formValidationSelf("form_validation_required","RESERVED QTY");
                }else if(!is_numeric($reserved)){
                    $errorReserved = formValidationSelf("form_validation_numeric","RESERVED QTY");
                }     
                
                if(empty($errorSku) && empty($errorOfflineStoreName) && empty($errorReserved)){
                    $statusRow = $iconTrue;
                }else{
                    $statusRow = $iconFalse;
                    $statusDataSaving = false;
                }

                $trueData[] = [
                    'kunci' => $kunci,
                    'sku' => $sku,
                    'errorSku' => $errorSku,
                    'productName' => $productName,
                    'productSize' => $productSize,
                    'storage' => $storage,
                    'availableQty' => $availableQty,
                    'offlineStoreName' => $offlineStoreName,
                    'errorOfflineStoreName' => $errorOfflineStoreName,
                    'reserved' => $reserved,
                    'errorReserved' => $errorReserved,
					'action' => '<button type="button" class="btn btn-icon btn-danger btnRemoveRow" {{dataInput}}><i class="bi bi-trash3"></i></button>',
                    'statusRow' => $statusRow,
                ];
            }

            $getOfflineStore = $this->_getOfflineStores()->get_all(array('status' => 1));

            $header[] = 'ACTION';
            $header[] = "";
 
            $response['data'] = [
				'thead' => $header,
				'tbody' => $trueData,
				'statusDataSaving' => $statusDataSaving,
                'getOfflineStore' => $getOfflineStore,
			];

            $response['success'] = true;
            return $response;

        } catch (Exception $e) {
            $response['messages'] = $e->getMessage();
			return $response;
        }

	}

    public function checkingDataOffline()
	{
		$response = $this->validationDataUploadOffline();
		try {
			if ($response['success'] === false) {
				throw new Exception("Error Processing Request", 1);
			}
			return $response;
		} catch (Exception $e) {
			return $response;
		}
	}

    private function validationDataUploadOffline()
	{
		$response = ['success' => false, 'messages' => '', 'buttonName' => '', 'buttonUrl' => ''];

		try {

			$data = $this->input->post();

			if (!is_array($data) || count($data) == 0) {
				throw new Exception("Error Processing Request", 1);
			}

			$statusDataSaving = true;

			$iconTrue = '<span class="svg-icon svg-icon-2x">
                    <svg xmlns="http://www.w3.org/2000/svg" style="margin-top: 9px;" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <polygon points="0 0 24 0 24 24 0 24"/>
                            <path d="M9.26193932,16.6476484 C8.90425297,17.0684559 8.27315905,17.1196257 7.85235158,16.7619393 C7.43154411,16.404253 7.38037434,15.773159 7.73806068,15.3523516 L16.2380607,5.35235158 C16.6013618,4.92493855 17.2451015,4.87991302 17.6643638,5.25259068 L22.1643638,9.25259068 C22.5771466,9.6195087 22.6143273,10.2515811 22.2474093,10.6643638 C21.8804913,11.0771466 21.2484189,11.1143273 20.8356362,10.7474093 L17.0997854,7.42665306 L9.26193932,16.6476484 Z" fill="#008000" fill-rule="nonzero" opacity="0.3" transform="translate(14.999995, 11.000002) rotate(-180.000000) translate(-14.999995, -11.000002) "/>
                            <path d="M4.26193932,17.6476484 C3.90425297,18.0684559 3.27315905,18.1196257 2.85235158,17.7619393 C2.43154411,17.404253 2.38037434,16.773159 2.73806068,16.3523516 L11.2380607,6.35235158 C11.6013618,5.92493855 12.2451015,5.87991302 12.6643638,6.25259068 L17.1643638,10.2525907 C17.5771466,10.6195087 17.6143273,11.2515811 17.2474093,11.6643638 C16.8804913,12.0771466 16.2484189,12.1143273 15.8356362,11.7474093 L12.0997854,8.42665306 L4.26193932,17.6476484 Z" fill="#008000" fill-rule="nonzero" transform="translate(9.999995, 12.000002) rotate(-180.000000) translate(-9.999995, -12.000002) "/>
                        </g>
                    </svg>
                </span>';

			$iconFalse = '<span class="svg-icon svg-icon-2x">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" style="margin-top: 9px;"
                        xmlns="http://www.w3.org/2000/svg">
                        <rect opacity="0.5" x="7.05025" y="15.5356" width="12" height="2"
                            rx="1" transform="rotate(-45 7.05025 15.5356)" fill="#ff0000" />
                        <rect x="8.46447" y="7.05029" width="12" height="2" rx="1"
                            transform="rotate(45 8.46447 7.05029)" fill="#ff0000" />
                    </svg>
                </span>';

			$validation = [];
			$validationIcon = [];

			$trueData = [];

			$listSku = $data['sku'];
            $calculationError = [];

			foreach ($listSku as $ky => $val) {
				$i = $ky;

                $sku = $data['sku'][$i];
                $offlineStoreName = $data['offlineStoreName'][$i];
                $reserved = $data['reserved'][$i];

                $errorSku = "";
                $productName = "";
                $productSize = "";
                $storage = "";
                $availableQty = "";
                $users_ms_product_variants_id = "";
                $users_ms_products_id = "";

                if(is_null($sku)){
                    $errorSku = formValidationSelf('form_validation_required',"SKU");
                }else{
                    $get = $this->_getProductVariants()->get(array('sku' => $sku));
                    if(!$get){
                        $errorSku = formValidationSelf('form_validation_found','SKU');
                    }else{
                        $product = $this->_getProducts()->get(array('id' => $get->users_ms_products_id ));
                        if(!$product){
                            $errorSku = formValidationSelf('form_validation_found','SKU');
                        }else{
                            $productName = $product->product_name;
                            $productSize = $get->product_size;
                            $this->db->where(array('product_variants_id' => $get->id));
                            $this->db->where(array('users_ms_companys_id' => $this->_users_ms_companys_id));
                            $getData = $this->db->get("users_ms_product_bb_inventories")->row();
                            $storage = !$getData ? 0 : $getData->quantity;
                            $availableQty = $storage;
                            $users_ms_product_variants_id = $get->id;
                            $users_ms_products_id = $get->users_ms_products_id;
                        }
                    }
                }

                $validation[] = [
					'type' => "input",
					'name' => "sku",
					'sequence' => $i,
					'message' => $errorSku,
                    'productSize' => $productSize,
                    'storage' => $storage,
                    'availableQty' => $availableQty,
				];

                $errorOfflineStoreName = "";
                if(is_null($offlineStoreName)){
                    $errorOfflineStoreName = formValidationSelf("form_validation_required","OFFLINE STORE NAME");
                }else{
                    $get = $this->_getOfflineStores()->get(array('id' => $offlineStoreName));
                    if(!$get){
                        $errorOfflineStoreName = formValidationSelf('form_validation_found',"OFFLINE STORE NAME");
                    }else{
                        $offlineStoreName = $get->id;
                    }
                }

                $validation[] = [
					'type' => "select",
					'name' => "offlineStoreName",
					'sequence' => $i,
					'message' => $errorOfflineStoreName,
				];


                $kunci = "";
                if($errorSku == "" && $errorOfflineStoreName == ""){
                    $kunci = $sku."|".$offlineStoreName;
                    $cari = array_search($kunci,array_column($trueData,'kunci'));
                    if($cari !== false){
                        $errorSku = $errorOfflineStoreName = formValidationSelf('form_validation_existexceltable',"SKU with OFFLINE STORE NAME");
                        
                        foreach($validation as $keyVal => $valVal){
                            if($valVal['name'] == "sku" && $valVal['sequence'] == $i){
                                $validation[$keyVal]['message'] = $errorSku;
                            }
                            if($valVal['name'] == "offlineStoreName" && $valVal['sequence'] == $i){
                                $validation[$keyVal]['message'] = $errorOfflineStoreName;
                            }
                        }
                    
                    }else{
                        $getAllocation = $this->_getInventoryAllocationOfflines()->get(array('users_ms_offline_stores_id' => $offlineStoreName,'users_ms_products_id' => $users_ms_products_id));
                        if($getAllocation){
                            $users_ms_inventory_allocation_offlines_id = $getAllocation->id;
                            $getDetail = $this->_getInventoryAllocationOfflineDetails()->get(array('users_ms_inventory_allocation_offlines_id' => $users_ms_inventory_allocation_offlines_id,'users_ms_product_variants_id' => $users_ms_product_variants_id));
                            if($getDetail){
                                $errorSku = $errorOfflineStoreName  = formValidationSelf('form_validation_existdatabase',"SKU with OFFLINE STORE NAME");
                                foreach($validation as $keyVal => $valVal){
                                    if($valVal['name'] == "sku" && $valVal['sequence'] == $i){
                                        $validation[$keyVal]['message'] = $errorSku;
                                    }
                                    if($valVal['name'] == "offlineStoreName" && $valVal['sequence'] == $i){
                                        $validation[$keyVal]['message'] = $errorOfflineStoreName;
                                    }
                                }
                            }
                        }
                    }
                }

                $errorReserved = "";
                if($reserved == ""){
                    $errorReserved = formValidationSelf("form_validation_required","RESERVED QTY");
                }else if(!is_numeric($reserved)){
                    $errorReserved = formValidationSelf("form_validation_numeric","RESERVED QTY");
                }

                $validation[] = [
					'type' => "input",
					'name' => "reserved",
					'sequence' => $i,
					'message' => $errorReserved,
				];


                $trueData[] = [
                    'kunci' => $kunci,
                    'sku' => $sku,
                    'errorSku' => $errorSku,
                    'productName' => $productName,
                    'productSize' => $productSize,
                    'storage' => $storage,
                    'availableQty' => $availableQty,
                    'offlineStoreName' => $offlineStoreName,
                    'errorOfflineStoreName' => $errorOfflineStoreName,
                    'reserved' => $reserved,
                    'errorReserved' => $errorReserved,
                ];


                if (count($validationIcon) == 0) {
					$validationIcon[] = [
						'sequence' => $i,
						'icon' => $iconTrue,
					];
				} else {
					$cari = array_search($i, array_column($validationIcon, 'sequence'));
					if ($cari === false) {
						$validationIcon[] = [
							'sequence' => $i,
							'icon' => $iconTrue,
						];
					}
				}

            }

            foreach ($validation as $ky => $val) {
				$sequence = $val['sequence'];
				$message = $val['message'];
				$error = 0;
				if ($message != "") {
					$error = 1;
				}

				if (count($calculationError) == 0 && $error == 1) {
					$calculationError[] = [
						'sequence' => $sequence,
						'error' => true,
					];
				} else if ($error == 1) {
					$cari = array_search($sequence, array_column($calculationError, 'sequence'));
					if ($cari === false) {
						$calculationError[] = [
							'sequence' => $sequence,
							'error' => true,
						];
					}
				}
			}

            if (count($calculationError) > 0) {
				$statusDataSaving = false;
			}

            foreach ($calculationError as $ky => $val) {
				$sequence = $val['sequence'];
				$cari = array_search($sequence, array_column($validationIcon, 'sequence'));
				if ($cari !== false) {
					$validationIcon[$cari]['icon'] = $iconFalse;
				}
			}


			$response['messages'] = "";
			$response['buttonUrl'] = $statusDataSaving ? base_url() . "inventory_allocation/processuploadoffline" : base_url() . "inventory_allocation/checkingdataoffline";
			$response['buttonName'] = $statusDataSaving  ? "Save Change" : "Check Data";
			$response['validation'] = $validation;
			$response['validationIcon'] = $validationIcon;
			$response['success'] = $statusDataSaving;

			return $response;
		} catch (Exception $e) {
			$response['messages'] = $e->getMessage();
			$response['buttonUrl'] = base_url() . "inventory_allocation/checkingdataoffline";
			$response['buttonName'] = 'Check Data';
			$response['validation'] = "";
			$response['validationIcon'] = "";
			$response['success'] = false;

			return $response;
		}
	}

    public function saveUploadOffline()
	{
		$response = ['success' => false, 'messages' => '', 'buttonName' => '', 'buttonUrl' => ''];

		$this->db->trans_begin();
		try {

			$data = $this->input->post();
			if (!is_array($data) || count($data) == 0) {
				throw new Exception("Error Processing Request1", 1);
			}

			$listSku = $data['sku'];

            foreach($listSku as $ky => $val){
                
                $sku = $data['sku'][$ky];
                $offlineStoreID = $data['offlineStoreName'][$ky];
                $available = $data['available'][$ky];
                $reserved = $data['reserved'][$ky];

                if(!is_numeric($available)){
                    throw new Exception("Failed value Available qty", 1);
                    
                }

                if(!is_numeric($reserved)){
                    throw new Exception("Failed value reserved qty", 1);
                    
                }

                $getVariant = $this->_getProductVariants()->get(array('sku' => $sku));
                if(!$getVariant){
                    throw new Exception("SKU : {$sku} not found", 1);
                    
                }

                $users_ms_products_id = $getVariant->users_ms_products_id;
                $users_ms_product_variants_id = $getVariant->id;

                $getProducts = $this->_getProducts()->get(array('id' => $getVariant->users_ms_products_id ));
                if(!$getProducts){
                    throw new Exception("Product SKU {$sku} not found", 1);
                    
                }


                $header = [
                    'users_ms_offline_stores_id' => $offlineStoreID,
                    'users_ms_products_id' => $users_ms_products_id,
                ];

                //cek 
                $cek = $this->_getInventoryAllocationOfflines()->get($header);
                if($cek){
                    $users_ms_inventory_allocation_offlines_id = $cek->id;
                }else{
                    //insert header 
                    $insert = $this->_getInventoryAllocationOfflines()->insert($header);
                    if(!$insert){
                        throw new Exception("Failed insert data allocation", 1);
                        
                    }
                    $users_ms_inventory_allocation_offlines_id = $insert;
                }

                $detail = [
                    'users_ms_inventory_allocation_offlines_id' => $users_ms_inventory_allocation_offlines_id,
                    'users_ms_product_variants_id' => $users_ms_product_variants_id,
                    'available' => $available,
                    'reserved' => $reserved,
                ];

                $insert = $this->_getInventoryAllocationOfflineDetails()->insert($detail);
                if(!$insert){
                    throw new Exception("Failed insert data allocation", 1);
                    
                }
                      

            }

			$response['success'] = true;
			$response['messages'] = "successfully upload data";
			$this->db->trans_commit();
			return $response;
		} catch (Exception $e) {
			$this->db->trans_rollback();
			$response['messages'] = $e->getMessage();
			return $response;
		}
	}
}