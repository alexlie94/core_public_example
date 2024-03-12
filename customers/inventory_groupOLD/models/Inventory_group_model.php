<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Inventory_group_model extends MY_ModelCustomer
{
    use MY_Tables;
    public function __construct()
    {
        $this->_tabel = $this->_table_ms_inventory_group;
        parent::__construct();
        $this->load->helper('metronic');
    }

    public function _getSources()
    {
        $this->_ci->load->model('sources/Sources_model', 'sources_model');
        return $this->_ci->load->sources_model;
    }

    public function _getChannels()
    {
        $this->_ci->load->model('channels/Channels_model', 'channels_model');
        return $this->_ci->load->channels_model;
    }

    public function _getProductImages()
    {
        $this->_ci->load->model('product_image/Product_image_model', 'product_image_model');
        return $this->_ci->product_image_model;
    }

    protected function _getProduct()
    {
        $this->db->select(' a.id,
                            a.product_code,
                            a.product_name,
                            a.brand_name,
                            (SELECT GROUP_CONCAT(DISTINCT product_size ORDER BY product_size ASC) 
                            FROM users_ms_product_variants   
                            WHERE users_ms_products_id = a.id AND deleted_at IS NULL) as product_size');
        $this->db->from("{$this->_table_products} a");
        $this->db->where('a.deleted_at is null', null, false);
        $this->db->where('a.users_ms_companys_id', $this->_users_ms_companys_id);
        $this->db->order_by('a.product_code', "DESC");

        $query = $this->db->get();

        return $query;
    }

    public function _dataSourceChannels($gid){

        $this->db->select(' b.source_name,
                            c.channel_name');
        $this->db->from("users_ms_launching_groups a");
        $this->db->join("admins_ms_sources b", 'b.id = a.admins_ms_sources_id', 'left');
        $this->db->join("users_ms_channels c", 'c.id = a.users_ms_channels_id', 'left');
        $this->db->where('a.deleted_at is null', null, false);
        $this->db->where('a.users_ms_companys_id', $this->_users_ms_companys_id);
        $this->db->where('a.users_ms_products_gid', $gid);

        $query = $this->db->get();

        return $query;
    }

    public function _dataImageDefault($gid)
    {
        $this->db->select(' a.id,
                            a.image_status,
                            b.image_name,
                            b.image_file');
        $this->db->from("users_ms_inventory_groups_defaults a");
        $this->db->join("users_ms_product_images b", 'b.id = a.users_ms_product_images_id', 'left');
        $this->db->where('a.users_ms_inventory_groups_id', $gid);
        $this->db->where('a.users_ms_companys_id', $this->_users_ms_companys_id);
        $this->db->where('a.deleted_at is null', null, false);
        // $this->db->where('b.deleted_at is null', null, false);
        // $this->db->where('c.deleted_at is null', null, false);

        $query = $this->db->get();

        return $query;
    }

    public function _getInventoryDisplayGroups($sourceId,$channelId,$gid)
    {
        $this->db->select('id');
        $this->db->from("users_ms_inventory_display_groups ");
        $this->db->where('admins_ms_sources_id', $sourceId);
        $this->db->where('users_ms_channels_id', $channelId);
        $this->db->where('users_ms_inventory_groups_id', $gid);
        $this->db->where('deleted_at is null', null, false);
        $this->db->where('users_ms_companys_id', $this->_users_ms_companys_id);

        $query = $this->db->get();

        return $query;
    }

    public function show($button = '')
    {
        $this->datatables->select(
            "id,group_code,group_name,group_description",
            false
        );
        $this->datatables->from("{$this->_tabel}");
        $this->datatables->where('deleted_at is null', null, false);
        $this->datatables->order_by('updated_at desc');

        $btn_launching = '<button class="btn btn-outline btn-outline-dashed btn-outline-success btn-sm me-2 btnLaunching" data-title="Item" data-type="modal" data-url="' . base_url() . 'inventory_group/launching/$1" data-fullscreenmodal="1" data-id="$1">Launching</button>';
        $btn_media = '<button class="btn btn-outline btn-outline-dashed btn-outline-info btn-sm me-2 btnRelease" data-title="Item" data-type="modal" data-url="' . base_url() . 'inventory_group/launching/$1" data-fullscreenmodal="1" data-id="$1">Media</button>';
        $this->datatables->add_column('action', $btn_launching, 'id');
        $this->datatables->add_column('media', $btn_media, 'id');

        return $this->datatables->generate();
    }

    private function _validate()
    {
        $response = ['success' => false, 'validate' => true, 'messages' => []];

        $response['type'] = !empty($this->input->post('id')) ? 'update' : 'insert';

        $role_validate = ['trim', 'required', 'xss_clean'];

        $this->form_validation->set_rules('group_name', 'Group Name', $role_validate);

        $this->form_validation->set_error_delimiters('<div class="' . VALIDATION_MESSAGE_FORM . '">', '</div>');

        if ($this->form_validation->run() === false) {
            $response['validate'] = false;
            foreach ($this->input->post() as $key => $value) {
                $response['messages'][$key] = form_error($key);
            }
        }

        return $response;
    }

    private function _validate_sources()
    {
        $response = ['success' => false, 'validate' => true, 'messages' => []];

        $response['type'] = 'insert';

        $role_validate = ['trim', 'required', 'xss_clean'];

        $this->form_validation->set_rules('source', 'Source', $role_validate);

        $this->form_validation->set_error_delimiters('<div class="' . VALIDATION_MESSAGE_FORM . '">', '</div>');

        if ($this->form_validation->run() === false) {
            $response['validate'] = false;
            foreach ($this->input->post() as $key => $value) {
                $response['messages'][$key] = form_error($key);
            }
        }

        return $response;
    }

    public function save()
    {
        $this->db->trans_begin();

        try {

            $response = self::_validate();

            if (!$response['validate']) {
                throw new Exception('Error Processing Request', 1);
            }

            $id = clearInput($this->input->post('id'));
            $group_name = clearInput($this->input->post('group_name'));
            $group_description = clearInput($this->input->post('group_description'));
            $inputDetail = $this->input->post('product_id');

            if (empty($id)) {

            $insert_data =
                [
                    'group_code' => mkautono($this->_table_ms_inventory_group, 'group_code', 'G'),
                    'group_name' => $group_name,
                    'group_description' => $group_description
                ];

                $execute = $this->insert($insert_data);

                for ($i = 0; $i < count($inputDetail); $i++) {

                    $get_productId = $inputDetail[$i];

                    $cekExistData = $this->db->get_where('users_ms_product_images',['users_ms_products_id' => $get_productId, 'deleted_at = ' => NULL])->result();

                    foreach ($cekExistData as $resList) {

                        $insert_image_default =
                            [
                                'users_ms_inventory_groups_id' => $execute,
                                'users_ms_products_id' => $get_productId,
                                'users_ms_product_images_id' => $resList->id,
                                'image_status' => '1'
                            ];

                        $execute_image_defaults =  $this->insertCustom($insert_image_default, 'users_ms_inventory_groups_defaults');
                    }

                    $insert_data_detail =
                        [
                            'users_ms_inventory_groups_id' => $execute,
                            'users_ms_products_id' => $get_productId
                        ];

                    $execute_detail =  $this->insertCustom($insert_data_detail, $this->_table_ms_inventory_group_detail);
                }

                if (!$execute || !$execute_detail || !$execute_image_defaults) {
                    $response['messages'] = 'Data Insert Invalid';
                    throw new Exception();
                }


                $response['messages'] = 'Success Insert Data Inventory Group';
            } 

            $this->db->trans_commit();
            $response['success'] = true;
            return $response;
        } catch (Exception $e) {
            $this->db->trans_rollback();
            return $response;
        }
    }

    public function save_sources(){
    $this->db->trans_begin();

    try {

        $getSources= $this->input->post('source');
        $getChannels = $this->input->post('channel');
        $getGid = $this->input->post('get_gid');

        $response = self::_validate_sources();

        if (!$response['validate']) {
            throw new Exception('Error Processing Request', 1);
        }

        $checkIfExist = $this->_getInventoryDisplayGroups($getSources, $getChannels, $getGid)->num_rows();

        if($checkIfExist > 0){
            $response['messages'] = 'Data Source Already Exist';
            throw new Exception();
        }

        $this->db->trans_commit();
        $response['success'] = true;
        return $response;
    } catch (Exception $e) {
        $this->db->trans_rollback();
        return $response;
    }
    }

    public function update_image_status()
    {
        $this->db->trans_begin();

        try {

            $getId = $this->input->post('data_id');
            $type = $this->input->post('type');

            switch ($type) {
            case 'select':
                $this->db->where('id', $getId);
                $this->db->update('users_ms_inventory_groups_defaults', ['image_status' => 2]);
                break;
            case 'main':

                $getGid = $this->input->post('data_gid');

                $checkMain = $this->db->get_where('users_ms_inventory_groups_defaults',[
                    'users_ms_inventory_groups_id' => $getGid,
                    'image_status' => 3
                    ])
                    ->row();

                if(!empty($checkMain)){
                    $this->db->where('users_ms_companys_id', $this->_users_ms_companys_id);
                    $this->db->where('id', $checkMain->id);
                    $this->db->update('users_ms_inventory_groups_defaults', ['image_status' => 2]);
                }
                
                $this->db->where('id', $getId);
                $this->db->update('users_ms_inventory_groups_defaults', ['image_status' => 3]);
                
                
                break;
            case 'cancel':
                $this->db->where('id', $getId);
                $this->db->update('users_ms_inventory_groups_defaults', ['image_status' => 1]);
                break;
            }
            

            $this->db->trans_commit();
            $response['success'] = true;
            return $response;
        } catch (Exception $e) {
            $this->db->trans_rollback();
            return $response;
        }
    }

    public function getItems($id)
    {
        try {
            $get = $this->db->get_where('ms_product_prices', ['id' => $id])->row();

            if (!$get) {
                throw new Exception('Data not Register', 1);
            }

            $table = [
                'id' => $get->id,
                'batch_name' => $get->batch_name,
                'batch_description' => $get->batch_description,
                'batch_location' => $get->batch_location,
                'start_date' => $get->start_date,
                'end_date' => $get->end_date
            ];

            return $table;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function deleteData($id)
    {
        $this->db->trans_begin();
        try {
            $softDelete = $this->softDeleteCustom($this->_table_products_images, 'id', $id);

            if (!$softDelete) {
                throw new Exception('Failed delete item', 1);
            }

            $this->db->trans_commit();
            return true;
        } catch (Exception $e) {
            $this->db->trans_rollback();
            return $e->getMessage();
        }
    }

    public function show_products_data()
    {
        $rProduct = $this->_getProduct()->result();

        $datas = [];

        foreach ($rProduct as $res) {
            $row = [
                'product_id' => $res->id,
                'product_code' => $res->product_code,
                'product_name' => $res->product_name,
                'brand_name' => $res->brand_name,
                'product_size' => $res->product_size
            ];

            $datas[] = $row;
        }

        return $datas;
    }

    public function manageDataSource()
    {
        $rSources = $this->_getSources()->get_all(['status' => '1']);

        $datas = [];

        foreach ($rSources as $res) {
            $row = [
                'source_id' => $res->id,
                'source_name' => $res->source_name,
            ];

            $datas[] = $row;
        }

        $output = [
            'data' => $datas,
        ];

        echo json_encode($output);
    }

    public function manageDataChannels($id)
    {
        $rChannels = $this->_getChannels()->get_all(['admins_ms_sources_id' => $id, 'status' => '1']);

        $datas = [];

        foreach ($rChannels as $res) {
            $row = [
                'channels_id' => $res->id,
                'channels_name' => $res->channel_name,
            ];

            $datas[] = $row;
        }

        $output = [
            'data' => $datas,
        ];

        echo json_encode($output);
    }

    public function manageDataSourceChannels($id)
    {
        $rData = $this->_dataSourceChannels($id)->result();

        $datas = [];

        foreach ($rData as $res) {
            $row = [
                'source_name' => $res->source_name,
                'channel_name' => $res->channel_name,
            ];

            $datas[] = $row;
        }

        $output = [
            'data' => $datas,
        ];

        echo json_encode($output);
    }

    public function manageDataImageDefault($id)
    {
        $rData = $this->_dataImageDefault($id)->result();

        $datas = [];

        foreach ($rData as $res) {

            if(!empty($res->image_name) || !empty($res->image_file) || !empty($res->image_status)){
                $row = [
                    'id' => $res->id,
                    'image_name' => $res->image_name,
                    'image_file' => $res->image_file,
                    'image_status' => $res->image_status
                ];

                $datas[] = $row;
            }
        }

        $output = [
            'data' => $datas,
        ];

        echo json_encode($output);
    }
}
