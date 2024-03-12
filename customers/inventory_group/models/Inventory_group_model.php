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

    public function _getProductVariants()
    {
        $this->_ci->load->model('products/Products_variants_model', 'products_variants_model');
        return $this->_ci->products_variants_model;
    }

    public function _getInventoryGroupDefault()
    {
        $this->_ci->load->model('inventory_group_defaults/Inventory_group_defaults_model', 'inventory_group_defaults_model');
        return $this->_ci->inventory_group_defaults_model;
    }

    public function _getProducts()
    {
        $this->_ci->load->model('products/Products_model', 'products_model');
        return $this->_ci->products_model;
    }

    public function _getChannels()
    {
        $this->_ci->load->model('channels/Channels_model', 'channels_model');
        return $this->_ci->load->channels_model;
    }

    public function _getLaunchingGroups()
    {
        $this->_ci->load->model('launching_groups/Launching_groups_model', 'launching_groups_model');
        return $this->_ci->launching_groups_model;
    }

    public function _getLaunchingGroupDetails()
    {
        $this->_ci->load->model('launching_group_details/Launching_group_details_model', 'launching_group_details_model');
        return $this->_ci->launching_group_details_model;
    }

    public function _getLookupValues()
    {
        $this->_ci->load->model('lookup_values/Lookup_values_model', 'lookup_values_model');
        return $this->_ci->lookup_values_model;
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
        $this->db->order_by('a.product_code', 'DESC');

        $query = $this->db->get();

        return $query;
    }

    protected function _getGroupMedia($gid)
    {
        $this->db->select(' id,
                            image_name', false);
        $this->db->from("users_ms_inventory_group_media");
        $this->db->where('deleted_at is null', null, false);
        $this->db->where('users_ms_companys_id', $this->_users_ms_companys_id);
        $this->db->where('users_ms_inventory_groups_id', $gid);
        $this->db->order_by('id', 'DESC');

        $query = $this->db->get();

        return $query;
    }

    public function _viewProduct($id)
    {
        $this->db->select(
            ' a.product_name ,
                            a.brand_name ,
                            a.supplier_name,
                            a.gender,
                            a.product_price ,
                            a.product_sale_price ,
                            a.product_offline_price,
                            a.category_name,
                            a.category_name_1,
                            a.category_name_2,
                            (   SELECT
                                    GROUP_CONCAT(DISTINCT product_size ORDER BY product_size ASC)
                                FROM ' .
            $this->_table_products_variants .
            '
                                WHERE users_ms_products_id = a.id AND deleted_at IS NULL) as product_size',
        );
        $this->db->from("{$this->_table_products} a");
        $this->db->join("{$this->_table_products_variants} b", 'b.users_ms_products_id = a.id', 'left');
        $this->db->where('a.id', $id);
        $this->db->where('a.deleted_at is null', null, false);
        $this->db->where('a.users_ms_companys_id', $this->_users_ms_companys_id);

        $query = $this->db->get();

        return $query;
    }

    public function _getProductGroup($id)
    {
        $this->db->select(
            ' a.id,
                            a.group_code,
                            a.group_name,
                            b.id as detail_id,
                            c.id as product_id,
                            c.product_code,
                            c.product_name,
                            c.brand_name,
                            (   SELECT
                                    GROUP_CONCAT(DISTINCT product_size ORDER BY product_size ASC)
                                FROM ' . $this->_table_products_variants . '
                                WHERE id = c.id AND deleted_at IS NULL) as product_size,
                            a.group_description',
        );
        $this->db->from("{$this->_tabel} a");
        $this->db->join('users_ms_inventory_group_product_details b', 'b.users_ms_inventory_groups_id = a.id', 'left');
        $this->db->join('users_ms_products c', 'c.id = b.users_ms_products_id', 'left');
        $this->db->where('a.id', $id);
        $this->db->where('a.deleted_at is null', null, false);
        $this->db->where('a.users_ms_companys_id', $this->_users_ms_companys_id);

        $query = $this->db->get();

        return $query;
    }

    public function _getProductGroupDetail($id)
    {
        $this->db->select(
            ' a.id,
                            a.group_code,
                            a.group_name,
                            b.id as detail_id,
                            c.id as product_id,
                            c.product_code,
                            c.product_name,
                            c.brand_name,
                            (   SELECT
                                    GROUP_CONCAT(DISTINCT product_size ORDER BY product_size ASC)
                                FROM ' .
            $this->_table_products_variants .
            '
                                WHERE id = c.id AND deleted_at IS NULL) as product_size,
                            a.group_description',
        );
        $this->db->from("{$this->_tabel} a");
        $this->db->join('users_ms_inventory_group_product_details b', 'b.users_ms_inventory_groups_id = a.id', 'left');
        $this->db->join('users_ms_products c', 'c.id = b.users_ms_products_id', 'left');
        $this->db->where('a.id', $id);
        $this->db->where('a.deleted_at is null', null, false);
        $this->db->where('b.deleted_at is null', null, false);
        $this->db->where('a.users_ms_companys_id', $this->_users_ms_companys_id);

        $query = $this->db->get();

        return $query;
    }

    public function _dataSourceChannels($gid)
    {
        $this->db->select(' b.source_name,
                            c.channel_name');
        $this->db->from('users_ms_launching_groups a');
        $this->db->join('admins_ms_sources b', 'b.id = a.admins_ms_sources_id', 'left');
        $this->db->join('users_ms_channels c', 'c.id = a.users_ms_channels_id', 'left');
        $this->db->where('a.deleted_at is null', null, false);
        $this->db->where('a.users_ms_companys_id', $this->_users_ms_companys_id);
        $this->db->where('a.users_ms_products_gid', $gid);

        $query = $this->db->get();

        return $query;
    }

    public function showLaunching($gid)
    {
        $this->db->select(
            "
            a.users_ms_inventory_groups_id,
            a.admins_ms_sources_id,
            b.source_name,
            a.users_ms_channels_id,
            c.channel_name,
            IFNULL(a.launch_date,'-') as launch_date,
            a.display_status,
            (select lookup_name from admins_ms_lookup_values where lookup_config = 'inventory_displays' and lookup_code = a.display_status) as status_name
        ",
            false,
        );

        $this->db->from('users_ms_launching_groups a');
        $this->db->join("{$this->_table_admins_ms_sources} b", 'b.id = a.admins_ms_sources_id', 'inner');
        $this->db->join("{$this->_table_users_ms_channels} c", 'c.id = a.users_ms_channels_id and c.users_ms_companys_id = a.users_ms_companys_id', 'inner');
        $this->db->where('a.deleted_at is null', null, false);
        $this->db->where(['a.users_ms_inventory_groups_id' => $gid]);
        $this->db->where(['a.users_ms_companys_id' => $this->_users_ms_companys_id]);

        $query = $this->db->get()->result();
        if (!$query) {
            $query = [];
        }
        return $query;
    }

    public function _dataImageDefault($gid)
    {
        $this->db->select(' a.id,
                            a.image_status,
                            b.image_name,
                            b.image_file');
        $this->db->from('users_ms_inventory_groups_defaults a');
        $this->db->join('users_ms_product_images b', 'b.id = a.users_ms_product_images_id', 'left');
        $this->db->where('a.users_ms_inventory_groups_id', $gid);
        $this->db->where('a.users_ms_companys_id', $this->_users_ms_companys_id);
        $this->db->where('a.deleted_at is null', null, false);
        // $this->db->where('b.deleted_at is null', null, false);
        // $this->db->where('c.deleted_at is null', null, false);

        $query = $this->db->get();

        return $query;
    }

    public function _getInventoryDisplayGroups($sourceId, $channelId, $gid)
    {
        $this->db->select('id');
        $this->db->from('users_ms_inventory_display_groups ');
        $this->db->where('admins_ms_sources_id', $sourceId);
        $this->db->where('users_ms_channels_id', $channelId);
        $this->db->where('users_ms_inventory_groups_id', $gid);
        $this->db->where('deleted_at is null', null, false);
        $this->db->where('users_ms_companys_id', $this->_users_ms_companys_id);

        $query = $this->db->get();

        return $query;
    }

    private function showImageDefault($gpid)
    {
        $this->db->select(
            "
            a.id,
            b.users_ms_products_id as product_id,
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
        ",
            false,
        );
        $this->db->from("{$this->_table_products_images} a");
        $this->db->join('users_ms_inventory_groups_defaults b', 'b.users_ms_product_images_id = a.id and b.users_ms_companys_id = a.users_ms_companys_id', 'left');
        $this->db->where('a.deleted_at is null', null, false);
        $this->db->where(['a.users_ms_companys_id' => $this->_users_ms_companys_id]);
        $this->db->where(['b.users_ms_inventory_groups_id' => $gpid]);

        $this->db->order_by('a.id desc');

        $query = $this->db->get();
        return $query;
    }

    public function showImageMedia($gpid)
    {
        $this->db->select(
            "
            a.id,
            a.image_name as image_name
        ",
            false,
        );
        $this->db->from("{$this->_table_products_images} a");
        $this->db->join('users_ms_inventory_group_product_details b', 'b.users_ms_products_id = a.users_ms_products_id AND b.users_ms_companys_id = a.users_ms_companys_id', 'left');
        $this->db->where('a.deleted_at is null', null, false);
        $this->db->where(['a.users_ms_companys_id' => $this->_users_ms_companys_id]);
        $this->db->where(['b.users_ms_inventory_groups_id' => $gpid]);

        $this->db->order_by('a.id asc');

        $query = $this->db->get();
        return $query;
    }

    private function showImageDefaultToNotDefault($gpid)
    {
        $this->db->select(
            "
            a.id,
            b.users_ms_products_id as product_id,
            a.image_name as image,
            a.image_name as image_name,
            coalesce(1) as status_id,
            (SELECT
                    lookup_name
                FROM
                    admins_ms_lookup_values
                WHERE
                    lookup_code = '1'
                        AND lookup_config = 'inventory_display_images') AS status_name
        ",
            false,
        );
        $this->db->from("{$this->_table_products_images} a");
        $this->db->join('users_ms_inventory_groups_defaults b', 'b.users_ms_product_images_id = a.id and b.users_ms_companys_id = a.users_ms_companys_id', 'left');
        $this->db->where('a.deleted_at is null', null, false);
        $this->db->where(['a.users_ms_companys_id' => $this->_users_ms_companys_id]);
        $this->db->where(['b.users_ms_inventory_groups_id' => $gpid]);

        $this->db->order_by('a.id desc');

        $query = $this->db->get();
        return $query;
    }

    public function showImageNotDefault($source, $channel, $gid)
    {
        $this->db->select(
            "
            a.id,
            b.id as launch_id,
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
        ",
            false,
        );
        $this->db->from("{$this->_table_products_images} a");
        $this->db->join('users_ms_launching_group_details b', "b.users_ms_product_images_id = a.id AND b.admins_ms_sources_id = {$source} AND b.users_ms_channels_id = {$channel}", 'left');
        $this->db->join('users_ms_launching_groups c', 'c.id = b.users_ms_launching_groups_id', 'left');
        $this->db->where('a.deleted_at is null', null, false);
        $this->db->where('c.deleted_at is null', null, false);
        $this->db->where('a.users_ms_companys_id', $this->_users_ms_companys_id);
        $this->db->where('c.users_ms_inventory_groups_id', $gid);
        $this->db->where('c.admins_ms_sources_id', $source);
        $this->db->where('c.users_ms_channels_id', $channel);
        $this->db->order_by('a.id desc');
        // $this->db->group_by("a.id");

        $query = $this->db->get();
        return $query;
    }

    public function getDataLaunchingStatus()
    {
        $this->db->select('a.id');
        $this->db->from('users_ms_launching_groups a');
        $this->db->join('users_ms_launching_group_details b', 'b.users_ms_launching_groups_id = a.id', 'left');
        $this->db->where('b.image_status', 3);
        $this->db->where('a.users_ms_companys_id', $this->_users_ms_companys_id);
        $this->db->where('a.deleted_at is null', null, false);

        $query = $this->db->get();

        return $query;
    }

    public function show($button = '')
    {
        $this->datatables->select(
            "   a.id,
                a.group_code,
                a.group_name,
                (SELECT
                    GROUP_CONCAT(DISTINCT brand_name ORDER BY brand_name ASC)
                FROM users_ms_products
                WHERE id in(SELECT users_ms_products_id FROM users_ms_inventory_group_product_details where users_ms_inventory_groups_id = a.id) AND deleted_at IS NULL) as brand_group,
                c.brand_name,
                (SELECT
                    lookup_name
                FROM
                    admins_ms_lookup_values
                WHERE
                    lookup_code = a.status
                        AND lookup_config = 'inventory_group_status') AS status_name",
            false,
        );
        $this->datatables->from("{$this->_tabel} a");
        $this->datatables->join('users_ms_inventory_group_product_details b', 'b.users_ms_inventory_groups_id = a.id', 'left');
        $this->datatables->join("{$this->_table_products} c", 'c.id = b.users_ms_products_id', 'left');
        $this->datatables->where('a.users_ms_companys_id', $this->_users_ms_companys_id);
        $this->datatables->where('a.deleted_at is null', null, false);

        $filters = !empty($this->input->post('filters')) ? $this->input->post('filters') : false;

        if ($filters !== false && is_array($filters)) {
            $getSearchBy = '';
            $set_value = '';
            $set_start_date = '';
            $set_end_date = '';

            $set_lookup_status = [];

            foreach ($filters as $ky => $val) {
                if ($val['name'] == 'searchBy') {
                    $getSearchBy .= $val['value'];
                }

                if ($val['name'] == 'searchValue') {
                    $set_value .= $val['value'];
                }

                if ($val['name'] == 'start_date') {
                    $set_start_date .= $val['value'];
                }

                if ($val['name'] == 'end_date') {
                    $set_end_date .= $val['value'];
                }

                if ($val['name'] == 'lookup_status_1') {
                    $set_lookup_status[] = $val['value'];
                }

                if ($val['name'] == 'lookup_status_2') {
                    $set_lookup_status[] = $val['value'];
                }

                if ($val['name'] == 'lookup_status_3') {
                    $set_lookup_status[] = $val['value'];
                }
            }

            if (!empty($getSearchBy)) {
                switch ($getSearchBy) {
                    case 'product_gid':
                        $this->datatables->like('a.group_code', $set_value);
                        break;
                    case 'product_group_name':
                        $this->datatables->like('a.group_name', $set_value);
                        break;
                    case 'brand_name':
                        $this->datatables->like('c.brand_name', $set_value);
                        break;
                }
            }

            if (!empty($set_lookup_status)) {
                $this->datatables->where_in('a.status', $set_lookup_status);
            }

            if (!empty($set_start_date)) {
                $this->datatables->where('a.created_at >=', $set_start_date);
            }

            if (!empty($set_start_date) && !empty($set_end_date)) {
                $this->datatables->where('DATE(a.created_at) >=', $set_start_date);
                $this->datatables->where('DATE(a.created_at) <=', $set_end_date);
            }

            if (empty($set_start_date) && !empty($set_end_date)) {
                $getDateNow = date('Y-m-d');

                $this->datatables->where('DATE(a.created_at) >=', $getDateNow);
                $this->datatables->where('DATE(a.created_at) <=', $set_end_date);
            }
        }

        $this->datatables->order_by('a.updated_at desc');
        $this->datatables->group_by('a.group_code');

        $btn_launching =
            '<button type="button" class="btn btn-outline btn-outline-dashed btn-outline-success btn-active-light-success hover-scale btn-sm fw-bold me-2 mb-2 btnLaunching" data-title="Item" data-type="modal" data-url="' .
            base_url() .
            'inventory_group/launching/$1" data-fullscreenmodal="1" data-id="$1"><i class="bi bi-rocket-takeoff fs-4 me-2"></i>Launching</button>
        <button class="btn btn-outline btn-outline-dashed btn-outline-info btn-active-light-info hover-scale btn-sm fw-bold me-2 mb-2 btnMedia" data-title="Item" data-type="modal" data-url="' .
            base_url() .
            'inventory_group/media_view/$1" data-fullscreenmodal="1" data-id="$1"><i class="bi bi-cast fs-4 me-2"></i>Media</button>
        <button class="btn btn-outline btn-outline-dashed btn-outline-dark btn-active-light-dark hover-scale btn-sm fw-bold me-2 mb-2 btnDetail" data-title="Item" data-type="modal" data-url="' .
            base_url() .
            'inventory_group/detail_view/$1" data-fullscreenmodal="1" data-id="$1"><i class="bi bi-eye-fill fs-4 me-2"></i>Detail</button>';

        $this->datatables->add_column('action', $btn_launching, 'id');

        return $this->datatables->generate();
    }

    public function notDefaultProcess()
    {
        $this->db->trans_begin();
        $response = ['success' => false, 'messages' => 'Successfully setting image product'];
        try {
            $source = clearInput($this->input->post('source'));
            $channel = clearInput($this->input->post('channel'));
            $gid = clearInput($this->input->post('gid'));

            // check source
            $get = $this->_getSources()->get(['id' => $source]);
            if (!$get) {
                throw new Exception('failed request data', 1);
            }

            //check channel
            $get = $this->_getChannels()->get(['id' => $channel]);
            if (!$get) {
                throw new Exception('Failed request data', 1);
            }

            //check productID
            $get = $this->get(['id' => $gid]);
            if (!$get) {
                throw new Exception('Failed request data', 1);
            }

            $images = $this->input->post('images');
            $images = json_decode($images);
            if (!is_array($images) || count($images) < 1) {
                throw new Exception('Failed Processing Requests', 1);
            }

            $searchMain = false;

            $dataHeader = [
                'users_ms_inventory_groups_id' => $gid,
                'admins_ms_sources_id' => $source,
                'users_ms_channels_id' => $channel,
            ];

            $check = $this->_getLaunchingGroups()->get($dataHeader);

            $users_ms_launching_groups_id = '';
            if (empty($check)) {
                $dataHeader['display_status_by'] = $this->_user_id;
                $dataHeader['display_status'] = 4;
                $insertHeader = $this->_getLaunchingGroups()->insert($dataHeader);

                if (!$insertHeader) {
                    throw new Exception('Failed insert data setting product image', 1);
                }

                $users_ms_launching_groups_id = $insertHeader;
                $response['launch'] = true;
            } else {
                $users_ms_launching_groups_id = $check->id;

                $updateHeader = $this->_getLaunchingGroups()->update(['id' => $users_ms_launching_groups_id], $dataHeader);
                if (!$updateHeader) {
                    throw new Exception('Failed Processing Request', 1);
                }
            }

            // $updateHeaderToPending = false;

            foreach ($images as $ky => $val) {
                $code = $val->value;
                $checkDif = strpos($code, '|');
                if ($checkDif === false) {
                    throw new Exception('Failed Processing Requests', 1);
                }

                $data = explode('|', $code);
                if (!is_array($data) || count($data) != 2) {
                    throw new Exception('Failed Processing Requests', 1);
                }

                $imageID = $data[0];
                $lookup = $data[1];

                if ((int) $lookup === 3) {
                    $searchMain = true;
                }

                if ((int) $lookup > 3) {
                    throw new Exception('Failed Processing Request', 1);
                }

                $getproduct = $this->_validateProduct($imageID);
                if (!$getproduct) {
                    throw new Exception('Failed Processing Requests', 1);
                }

                $insertOrUpdate = [
                    'users_ms_launching_groups_id' => $users_ms_launching_groups_id,
                    'admins_ms_sources_id' => $source,
                    'users_ms_channels_id' => $channel,
                    'users_ms_products_id' => $getproduct->users_ms_products_id,
                    'users_ms_product_images_id' => $imageID,
                ];

                $search = $this->_getLaunchingGroupDetails()->get($insertOrUpdate);

                $insertOrUpdate['image_status'] = $lookup;

                if (!$search) {
                    //insert
                    $insertOrUpdate['image_status_by'] = $this->_user_id;
                    $insertOrUpdate['sync_status_by'] = $this->_user_id;
                    $insert = $this->_getLaunchingGroupDetails()->insert($insertOrUpdate);
                    if (!$insert) {
                        throw new Exception('Failed Processing Data', 1);
                    }
                } else {
                    //update
                    $id = $search->id;
                    if ($search->image_status != $lookup) {
                        $insertOrUpdate['image_status_by'] = $this->_user_id;
                        $sync_status = $search->sync_status;
                        if ($sync_status == 2) {
                            $insertOrUpdate['sync_status'] = 1;
                            $insertOrUpdate['sync_status_by'] = $this->_user_id;
                        }

                        //update header menjadi pending
                        $updateHeaderToPending = true;
                    }

                    $update = $this->_getLaunchingGroupDetails()->update(['id' => $id], $insertOrUpdate);
                    if (!$update) {
                        throw new Exception('Failed Processing Data', 1);
                    }
                }
            }

            if ($searchMain === false) {
                throw new Exception('Failed request data', 1);
            }

            // if ($updateHeaderToPending) {
            //     $updateHeader = $this->_getLaunchingGroups()->update(array('id' => $users_ms_inventory_displays_id), ['display_status' => 4, 'display_status_by' => $this->_user_id, 'launch_date' => null]);
            //     if (!$updateHeader) {
            //         throw new Exception("Error Processing Request", 1);
            //     }
            //     $response['launch'] = true;
            // }

            $this->db->trans_commit();
            $response['success'] = true;
            return $response;
        } catch (Exception $e) {
            $this->db->trans_rollback();
            $response['messages'] = $e->getMessage();
            return $response;
        }
    }

    public function _validateProduct($imageID)
    {
        $this->db->select('a.users_ms_products_id');
        $this->db->from("{$this->_table_products_images} a");
        $this->db->join(
            "{$this->_table_products} b",
            "b.id = a.users_ms_products_id
            AND b.users_ms_companys_id = a.users_ms_companys_id",
            'inner',
        );
        $this->db->where(['a.id' => $imageID, 'a.users_ms_companys_id' => $this->_users_ms_companys_id]);

        return $this->db->get()->row();
    }

    public function notDefault($source, $channel, $gid)
    {
        if ($gid == null) {
            throw new Exception('Failed process launching', 1);
        }

        $dataNotDefaultImage = $this->showImageNotDefault($source, $channel, $gid)->result();

        if (count($dataNotDefaultImage) > 0) {
            $dataImage = $dataNotDefaultImage;
        } else {
            $dataImage = $this->showImageDefaultToNotDefault($gid)->result();
        }

        $dataArrayDefault = [];
        $urlImage = base_url('assets/uploads/products_image/');

        $button = "<button class=\"btn btn-outline btn-outline-dashed btn-outline-success btn-sm btnSelect me-5\" data-imagename=\"$2\"  data-imageid =\"$1\" data-url=\"" . base_url('inventory_group/confirmimage') . "\" data-type=\"modal\">Select</button>";
        $button .= "<button class=\"btn btn-outline btn-outline-dashed btn-outline-primary btn-sm btnView me-5\" data-imagename=\"$2\"  data-imageid =\"$1\" data-url=\"" . base_url('inventory_group/viewimage') . "\" data-type=\"modal\">View</button>";
        $button .= "<button class=\"btn btn-outline btn-outline-dashed btn-outline-warning btn-sm btnCancel\" data-imagename=\"$2\"  data-imageid =\"$1\" {{ notSelected }} >Cancel</button>";

        foreach ($dataImage as $ky => $val) {
            $image_id = $val->id;
            $imageName = $val->image_name;
            $statusID = $val->status_id;
            $status = "<span class=\"statusName\" data-imageid='{$image_id}'>{$val->status_name}</span>";

            $buttonAction = $button;
            $buttonAction = str_replace("$1", $image_id, $buttonAction);
            $buttonAction = str_replace("$2", empty($imageName) ? '' : $imageName, $buttonAction);
            $conditionStatus = $statusID == 1 ? 'disabled' : '';
            $buttonAction = str_replace('{{ notSelected }}', $conditionStatus, $buttonAction);

            $htmlImage = "<div class=\"symbol symbol-50px\"><span class=\"symbol-label\" style=\"background-image:url(" . $urlImage . $imageName . ");\"></span></div>";

            $detailImage[] = [
                'Image' => $htmlImage,
                'Media Name' => $imageName,
                'Action' => $buttonAction,
                'Status' => $status,
            ];

            $dataArrayDefault[] = [$image_id, (int) $statusID];
        }

        $setData = [
            'detail' => $detailImage,
            'dataArrayDefault' => $dataArrayDefault,
        ];

        return $setData;
    }

    public function defaultProcess()
    {
        $this->db->trans_begin();
        $response = ['success' => false, 'messages' => 'Successfully setting image default product'];
        try {
            $source = clearInput($this->input->post('source'));
            $channel = clearInput($this->input->post('channel'));
            $productID = clearInput($this->input->post('productgid'));

            //check source , check channel
            if ($source != 'default' || $channel != 'default') {
                throw new Exception('failed request data', 1);
            }

            $images = $this->input->post('images');
            $images = json_decode($images);

            if (!is_array($images) || count($images) < 1) {
                throw new Exception('Failed Processing Requests', 1);
            }

            $searchMain = false;

            foreach ($images as $ky => $val) {
                $code = $val->value;

                $checkDif = strpos($code, '|');
                if ($checkDif === false) {
                    throw new Exception('Failed Processing Requests', 1);
                }

                $data = explode('|', $code);
                if (!is_array($data) || count($data) != 2) {
                    throw new Exception('Failed Processing Requests', 1);
                }

                $imageID = $data[0];
                $lookup = $data[1];

                if ($lookup === '3') {
                    $searchMain = true;
                }

                if ($lookup > '3') {
                    throw new Exception('Failed Processing Request', 1);
                }

                $get = $this->_validateProduct($imageID);
                if (!$get) {
                    throw new Exception('Failed Processing Requests', 1);
                }

                $insertOrUpdate = [
                    'users_ms_inventory_groups_id' => $productID,
                    'users_ms_product_images_id' => $imageID,
                ];

                $search = $this->_getInventoryGroupDefault()->get($insertOrUpdate);

                $insertOrUpdate['image_status'] = $lookup;

                if (!$search) {
                    //insert
                    $insert = $this->_getInventoryGroupDefault()->insert($insertOrUpdate);
                    if (!$insert) {
                        throw new Exception('Failed Processing Data', 1);
                    }
                } else {
                    //update
                    $id = $search->id;
                    $sync_status = $search->sync_status;
                    if ($sync_status == 2) {
                        $insertOrUpdate['sync_status'] = 1;
                    }
                    $update = $this->_getInventoryGroupDefault()->update(['id' => $id], $insertOrUpdate);
                    if (!$update) {
                        throw new Exception('Failed Processing Data', 1);
                    }
                }
            }

            if ($searchMain === false) {
                throw new Exception('Failed request data', 1);
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
        $response = ['success' => true, 'messages' => '', 'data' => []];
        try {
            $get = $this->_getSources()->get_all(['status' => 1]);
            if (!$get) {
                throw new Exception('Data Source not found', 1);
            }

            $data = [];
            foreach ($get as $ky => $val) {
                $data[] = [
                    'id' => $val->id,
                    'source_name' => $val->source_name,
                ];
            }

            if (count($data) < 1) {
                throw new Exception('Data Source not found', 1);
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
        $response = ['success' => true, 'messages' => '', 'data' => []];
        try {
            $id = clearInput($this->input->post('sourceID'));
            if (empty($id)) {
                throw new Exception('Failed Processing Request', 1);
            }

            $get = $this->_getChannels()->get_all(['admins_ms_sources_id' => $id, 'status' => 1]);
            if (!$get) {
                throw new Exception('Data Channel not found', 1);
            }

            $data = [];
            foreach ($get as $ky => $val) {
                $data[] = [
                    'id' => $val->id,
                    'channel_name' => $val->channel_name,
                ];
            }

            if (count($data) < 1) {
                throw new Exception('Data Source not found', 1);
            }

            $response['data'] = $data;
            return $response;
        } catch (Exception $e) {
            $response['success'] = false;
            $response['messages'] = $e->getMessage();
            return $response;
        }
    }

    public function getGroupDetails($gid)
    {
        $variant = [];

        $data = $this->_getProductGroup($gid)->row();

        $variant[] = [
            'No' => 1,
            'Product GID' => $data->group_code,
            'Product Group' => $data->group_name,
            'Brand' => $data->brand_name,
            'Source' => 'Default',
            'Channel' => 'Default',
        ];

        return $variant;
    }

    public function detailShowImageDefault($gid)
    {
        if ($gid == null) {
            throw new Exception('Failed process launching', 1);
        }

        $dataImage = $this->showImageDefault($gid)->result();

        if (!$dataImage) {
            throw new Exception('No Image on product', 1);
        }

        $dataArrayDefault = [];
        $urlImage = base_url('assets/uploads/products_image/');

        $button = "<button class=\"btn btn-outline btn-outline-dashed btn-outline-success btn-sm btnSelect me-5\" data-imagename=\"$2\"  data-imageid =\"$1\" data-url=\"" . base_url('inventory_group/confirmimage') . "\" data-type=\"modal\">Select</button>";
        $button .= "<button class=\"btn btn-outline btn-outline-dashed btn-outline-primary btn-sm btnView me-5\" data-imagename=\"$2\"  data-imageid =\"$1\" data-url=\"" . base_url('inventory_group/viewimage') . "\" data-type=\"modal\">View</button>";
        $button .= "<button class=\"btn btn-outline btn-outline-dashed btn-outline-warning btn-sm btnCancel\" data-imagename=\"$2\"  data-imageid =\"$1\" {{ notSelected }} >Cancel</button>";

        foreach ($dataImage as $ky => $val) {
            $image_id = $val->id;
            $imageName = $val->image_name;
            $statusID = $val->status_id;
            $status = "<span class=\"statusName\" data-imageid='{$image_id}'>{$val->status_name}</span>";

            $buttonAction = $button;
            $buttonAction = str_replace("$1", $image_id, $buttonAction);
            $buttonAction = str_replace("$2", empty($imageName) ? '' : $imageName, $buttonAction);
            $conditionStatus = $statusID == 1 ? 'disabled' : '';
            $buttonAction = str_replace('{{ notSelected }}', $conditionStatus, $buttonAction);

            $htmlImage = "<div class=\"symbol symbol-50px\"><span class=\"symbol-label\" style=\"background-image:url(" . $urlImage . $imageName . ");\"></span></div>";

            $detailImage[] = [
                'Image' => $htmlImage,
                'Media Name' => $imageName,
                'Action' => $buttonAction,
                'Status' => $status,
            ];

            $dataArrayDefault[] = [$image_id, (int) $statusID];
        }

        $setData = [
            'detail' => $detailImage,
            'dataArrayDefault' => $dataArrayDefault,
        ];

        return $setData;
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
            $group_description = $this->input->post('group_description');
            $inputDetail = $this->input->post('product_id');

            if (!isset($inputDetail)) {
                $response['messages'] = 'Data Product Empty';
                throw new Exception();
            }

            if (empty($id)) {
                $insert_data = [
                    'group_code' => mkautono($this->_table_ms_inventory_group, 'group_code', 'G'),
                    'group_name' => $group_name,
                    'group_description' => $group_description,
                    'status' => 1,
                ];

                $execute = $this->insert($insert_data);

                for ($i = 0; $i < count($inputDetail); $i++) {
                    $get_productId = $inputDetail[$i];

                    $cekExistData = $this->db->get_where('users_ms_product_images', ['users_ms_products_id' => $get_productId, 'deleted_at = ' => null])->result();

                    foreach ($cekExistData as $resList) {
                        $insert_image_default = [
                            'users_ms_inventory_groups_id' => $execute,
                            'users_ms_products_id' => $get_productId,
                            'users_ms_product_images_id' => $resList->id,
                            'image_status' => '1',
                        ];

                        $insert_image_media = [
                            'users_ms_inventory_groups_id' => $execute,
                            'image_name' => $resList->image_name
                        ];

                        $execute_image_defaults = $this->insertCustom($insert_image_default, 'users_ms_inventory_groups_defaults');
                        $execute_image_media = $this->insertCustom($insert_image_media, 'users_ms_inventory_group_media');
                    }

                    $insert_data_detail = [
                        'users_ms_inventory_groups_id' => $execute,
                        'users_ms_products_id' => $get_productId,
                    ];

                    $execute_detail = $this->insertCustom($insert_data_detail, $this->_table_ms_inventory_group_detail);
                }

                if (!$execute || !$execute_detail || !$execute_image_defaults || !$execute_image_media) {
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

    public function save_detail()
    {
        $this->db->trans_begin();

        try {
            $response = self::_validate();

            if (!$response['validate']) {
                throw new Exception('Error Processing Request', 1);
            }

            $id = clearInput($this->input->post('group_id'));
            $group_name = clearInput($this->input->post('group_name'));
            $group_description = $this->input->post('group_description');
            $product_id = $this->input->post('product_id');

            if (!isset($product_id)) {
                $response['messages'] = 'Data Product Empty';
                throw new Exception();
            }

            $update_data = [
                'group_name' => $group_name,
                'group_description' => $group_description,
            ];

            $this->db->where('id', $id);
            $execute = $this->db->update($this->_table_ms_inventory_group, $update_data);

            $dataProductArr = [];
            $dataBind = [];

            for ($i = 0; $i < count($product_id); $i++) {
                $insert_product_detail = [
                    'users_ms_inventory_groups_id' => $id,
                    'users_ms_products_id' => $product_id[$i],
                ];

                if ($this->input->post('detail_id')[$i] == 0) {
                    $execute_detail = $this->insertCustom($insert_product_detail, $this->_table_ms_inventory_group_detail);
                } else {
                    $detailId = $this->input->post('detail_id')[$i];
                    array_push($dataBind, $detailId);

                    $this->db->where('users_ms_inventory_groups_id', $id);
                    $this->db->where('users_ms_companys_id', $this->_users_ms_companys_id);
                    $this->db->where('deleted_at IS NULL');
                    $cekproduct = $this->db->get('users_ms_inventory_group_product_details')->result();

                    foreach ($cekproduct as $val) {
                        array_push($dataProductArr, $val->id);
                    }
                }
            }

            $differences1 = array_diff($dataBind, $dataProductArr);
            $differences2 = array_diff($dataProductArr, $dataBind);

            $notSameValues = array_merge($differences1, $differences2);
            $notSameValues = array_unique($notSameValues);

            foreach ($notSameValues as $key) {
                $delete = $this->softDeleteCustom($this->_table_ms_inventory_group_detail, 'id', $key);
            }

            // if (!$execute || !$execute_detail || !$execute_image_defaults) {
            //     $response['messages'] = 'Data Insert Invalid';
            //     throw new Exception();
            // }

            $response['messages'] = 'Success Update Data Inventory Group';

            $this->db->trans_commit();
            $response['success'] = true;
            return $response;
        } catch (Exception $e) {
            $this->db->trans_rollback();
            return $response;
        }
    }

    public function save_media()
    {
        $this->db->trans_begin();

        try {
            // $response = self::_validate();

            // if (!$response['validate']) {
            //     throw new Exception('Error Processing Request', 1);
            // }

            $path_image = './assets/uploads/products_image/';
            $gid = clearInput($this->input->post('gid'));
            $original_file_name = $_FILES['image_upload']['name'];
            $cleaned_file_name = preg_replace("/[^A-Za-z0-9.]/", "", $original_file_name);
            $convertImageName = str_replace(' ', '_', $cleaned_file_name);

            $tmp_file = $_FILES['image_upload']['tmp_name'];
            $full_path = $path_image . $convertImageName;

            if (!file_exists($path_image)) {
                mkdir('./assets/uploads/products_image', 0777, true);
            }

            move_uploaded_file($tmp_file, $full_path);

            $data_insert = [
                'users_ms_inventory_groups_id' => $gid,
                'image_name' => $convertImageName
            ];

            $execute = $this->insertCustom($data_insert, 'users_ms_inventory_group_media');

            if (!$execute) {
                $response['messages'] = 'Data Insert Invalid';
                throw new Exception();
            }

            $response['messages'] = 'Success Insert Data Group Media';

            $this->db->trans_commit();

            $response['type'] = 'insert';
            $response['success'] = true;
            return $response;
        } catch (Exception $e) {
            $this->db->trans_rollback();
            return $response;
        }
    }

    public function save_sources()
    {
        $this->db->trans_begin();

        try {
            $getSources = $this->input->post('source');
            $getChannels = $this->input->post('channel');
            $getGid = $this->input->post('get_gid');

            $response = self::_validate_sources();

            if (!$response['validate']) {
                throw new Exception('Error Processing Request', 1);
            }

            $checkIfExist = $this->_getInventoryDisplayGroups($getSources, $getChannels, $getGid)->num_rows();

            if ($checkIfExist > 0) {
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

                    $checkMain = $this->db
                        ->get_where('users_ms_inventory_groups_defaults', [
                            'users_ms_inventory_groups_id' => $getGid,
                            'image_status' => 3,
                        ])
                        ->row();

                    if (!empty($checkMain)) {
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
                'end_date' => $get->end_date,
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
            $softDelete = $this->softDeleteCustom('users_ms_inventory_group_media', 'id', $id);

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
                'product_size' => $res->product_size,
            ];

            $datas[] = $row;
        }

        return $datas;
    }

    public function manageProductData($id)
    {
        $rProduct = $this->_getProductGroupDetail($id)->result();

        $datas = [];

        foreach ($rProduct as $res) {
            $row = [
                'group_id' => $res->id,
                'detail_id' => $res->detail_id,
                'product_id' => $res->product_id,
                'product_code' => $res->product_code,
                'product_name' => $res->product_name,
                'brand_name' => $res->brand_name,
                'product_size' => $res->product_size,
            ];

            $datas[] = $row;
        }

        $output = [
            'data' => $datas,
        ];

        echo json_encode($output);
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
            if (!empty($res->image_name) || !empty($res->image_file) || !empty($res->image_status)) {
                $row = [
                    'id' => $res->id,
                    'image_name' => $res->image_name,
                    'image_file' => $res->image_file,
                    'image_status' => $res->image_status,
                ];

                $datas[] = $row;
            }
        }

        $output = [
            'data' => $datas,
        ];

        echo json_encode($output);
    }

    public function manageDataImageMedia($gid)
    {
        $rData = $this->_getGroupMedia($gid)->result();

        $datas = [];

        foreach ($rData as $res) {
            $row = [
                'id' => $res->id,
                'image_name' => $res->image_name,
            ];

            $datas[] = $row;
        }

        $output = [
            'data' => $datas,
        ];

        echo json_encode($output);
    }

    public function launchProductSource()
    {
        $response = ['success' => true, 'messages' => 'Successfully launch product source'];

        $this->db->trans_begin();

        try {
            $source = clearInput($this->input->post('source'));
            $channel = clearInput($this->input->post('channel'));
            $gid = clearInput($this->input->post('gid'));
            $launchDate = $this->input->post('launchdate');

            if (is_null($launchDate) || $launchDate == '') {
                throw new Exception('Failed Processing Request', 1);
            }

            if (is_null($source) || is_null($channel) || is_null($gid)) {
                throw new Exception('Failed Processing Request', 1);
            }

            //check source
            $get = $this->_getSources()->get(['id' => $source]);
            if (!$get) {
                throw new Exception('failed request data', 1);
            }

            //check channel
            $get = $this->_getChannels()->get(['id' => $channel]);
            if (!$get) {
                throw new Exception('Failed request data', 1);
            }

            //check productID
            $get = $this->get(['id' => $gid]);
            if (!$get) {
                throw new Exception('Failed request data', 1);
            }

            $arrCheck = [
                'admins_ms_sources_id' => $source,
                'users_ms_channels_id' => $channel,
                'users_ms_inventory_groups_id' => $gid,
            ];

            $check = $this->_getLaunchingGroups()->get($arrCheck);
            $users_ms_inventory_displays_id = '';

            if (!is_object($check)) {
                $get = $this->_getInventoryGroupDefault()->get_all(['users_ms_inventory_groups_id' => $gid]);
                if (!$get) {
                    throw new Exception('Failed Processing Request', 1);
                }

                $dataHeader = [
                    'admins_ms_sources_id' => $source,
                    'users_ms_channels_id' => $channel,
                    'users_ms_inventory_groups_id' => $gid,
                    'display_status_by' => $this->_user_id,
                    'display_status' => 4,
                ];

                //save header
                $saveHeader = $this->_getLaunchingGroups()->insert($dataHeader);
                if (!$saveHeader) {
                    throw new Exception('Failed Processing Request', 1);
                }

                $users_ms_inventory_displays_id = $saveHeader;

                foreach ($get as $ky => $val) {
                    $dataDetail = [
                        'users_ms_inventory_displays_id' => $users_ms_inventory_displays_id,
                        'admins_ms_sources_id' => $source,
                        'users_ms_channels_id' => $channel,
                        'users_ms_inventory_groups_id' => $gid,
                        'users_ms_product_images_id' => $val->users_ms_product_images_id,
                        'image_status_by' => $this->_user_id,
                        'image_status' => $val->image_status,
                        'sync_status_by' => $this->_user_id,
                    ];

                    $save = $this->_getLaunchingGroupDetails()->insert($dataDetail);
                    if (!$save) {
                        throw new Exception('Failed Processing Request', 1);
                    }
                }
            } else {
                $users_ms_inventory_displays_id = $check->id;
            }

            //update header inventory display
            $updateHeader = [
                'display_status_by' => $this->_user_id,
                'display_status' => 5,
                //pending
                'launch_by' => $this->_user_id,
                'launch_date' => $launchDate,
            ];

            $updateHeader = $this->_getLaunchingGroups()->update(['id' => $users_ms_inventory_displays_id], $updateHeader);
            if (!$updateHeader) {
                throw new Exception('Failed Processing Request', 1);
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

    public function setDefaultImage()
    {
        $this->db->trans_begin();
        $response = ['success' => false, 'messages' => 'Successfully setting image product'];
        try {
            $source = clearInput($this->input->post('source'));
            $channel = clearInput($this->input->post('channel'));
            $gid = clearInput($this->input->post('gid'));

            if (is_null($source) || is_null($channel) || is_null($gid)) {
                throw new Exception('Error Processing Request', 1);
            }

            //check source
            $get = $this->_getSources()->get(['id' => $source]);
            if (!$get) {
                throw new Exception('failed request data', 1);
            }

            //check channel
            $get = $this->_getChannels()->get(['id' => $channel]);
            if (!$get) {
                throw new Exception('Failed request data', 1);
            }

            //check gid
            $get = $this->get(['id' => $gid]);
            if (!$get) {
                throw new Exception('Failed request data', 1);
            }

            $checkDataDefault = $this->_getInventoryGroupDefault()->get([
                'users_ms_inventory_groups_id' => $gid,
                'image_status' => 3,
            ]);

            if (empty($checkDataDefault)) {
                throw new Exception('Default Image Status is <i><b>Image Not Selected</b></i>', 1);
            }

            $detail = $this->showImageDefault($gid)->result();

            if (!$detail) {
                throw new Exception('Failed request data', 1);
            }

            $insertOrUpdate = [
                'admins_ms_sources_id' => $source,
                'users_ms_channels_id' => $channel,
            ];

            $cekHeaderParams = [
                'admins_ms_sources_id' => $source,
                'users_ms_channels_id' => $channel,
                'users_ms_inventory_groups_id' => $gid,
            ];

            $dataHeader = [
                'admins_ms_sources_id' => $source,
                'users_ms_channels_id' => $channel,
                'users_ms_inventory_groups_id' => $gid,
                'display_status_by' => $this->_user_id,
                'display_status' => 4,
            ];

            $cekLaunch = $this->showImageNotDefault($source, $channel, $gid)->result();

            if (empty($cekLaunch)) {
                $insertLaunch = $this->_getLaunchingGroups()->insert($dataHeader);

                foreach ($detail as $res) {
                    $insertOrUpdate['users_ms_launching_groups_id'] = $insertLaunch;
                    $insertOrUpdate['users_ms_product_images_id'] = $res->id;
                    $insertOrUpdate['users_ms_products_id'] = $res->product_id;
                    $insertOrUpdate['image_status'] = $res->status_id;
                    $insertOrUpdate['sync_status'] = 1;
                    $insertOrUpdate['image_status_by'] = $this->_user_id;
                    $insertOrUpdate['sync_status_by'] = $this->_user_id;

                    $insert = $this->_getLaunchingGroupDetails()->insert($insertOrUpdate);

                    if (!$insert) {
                        throw new Exception('Failed Processing Data', 1);
                    }
                }
            } else {
                $cekLaunchheader = $this->_getLaunchingGroups()->get($cekHeaderParams);
                $cekLaunchDetail = $this->_getLaunchingGroupDetails()->get(['users_ms_launching_groups_id' => $cekLaunchheader->id]);

                if (empty($cekLaunchDetail)) {
                    foreach ($detail as $res) {
                        $insertOrUpdate['users_ms_launching_groups_id'] = $cekLaunchheader->id;
                        $insertOrUpdate['users_ms_product_images_id'] = $res->id;
                        $insertOrUpdate['users_ms_products_id'] = $res->product_id;
                        $insertOrUpdate['image_status'] = $res->status_id;
                        $insertOrUpdate['sync_status'] = 1;
                        $insertOrUpdate['image_status_by'] = $this->_user_id;
                        $insertOrUpdate['sync_status_by'] = $this->_user_id;

                        $insert = $this->_getLaunchingGroupDetails()->insert($insertOrUpdate);

                        if (!$insert) {
                            throw new Exception('Failed Processing Data', 1);
                        }
                    }
                } else {
                    foreach ($cekLaunch as $val) {
                        foreach ($detail as $res) {
                            $insertOrUpdate['image_status_by'] = $this->_user_id;
                            $insertOrUpdate['image_status'] = $res->status_id;
                            $insertOrUpdate['sync_status'] = 1;
                            $insertOrUpdate['sync_status_by'] = $this->_user_id;

                            $this->db->where('users_ms_product_images_id', $res->id);
                            $update = $this->_getLaunchingGroupDetails()->update(['id' => $val->launch_id], $insertOrUpdate);

                            if (!$update) {
                                throw new Exception('Failed Processing Data', 1);
                            }
                        }
                    }
                }
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

    public function export()
    {
        $status = [];
        $searchValue = '';
        $searchBy = $this->input->get('searchBy');
        if ($searchBy != 'status') {
            $searchValue = $this->input->get('searchValue');
        } else {
            $status = $this->input->get('status');
        }

        $source = $this->input->get('source');
        $channel = $this->input->get('channel');
        $searchDatatables = $this->input->get('search');

        $this->datatables->select(
            "   a.id,
                a.group_code,
                a.group_name,
                (SELECT
                    GROUP_CONCAT(DISTINCT brand_name ORDER BY brand_name ASC)
                FROM users_ms_products
                WHERE id in(SELECT users_ms_products_id FROM users_ms_inventory_group_product_details where users_ms_inventory_groups_id = a.id) AND deleted_at IS NULL) as brand_group,
                (SELECT
                    lookup_name
                FROM
                    admins_ms_lookup_values
                WHERE
                    lookup_code = a.status
                        AND lookup_config = 'inventory_group_status') AS status_name",
            false,
        );
        $this->db->from("{$this->_tabel} a");
        $this->db->join('users_ms_inventory_group_product_details b', 'b.users_ms_inventory_groups_id = a.id', 'left');
        $this->db->join("{$this->_table_products} c", 'c.id = b.users_ms_products_id', 'left');
        $this->db->where('a.users_ms_companys_id', $this->_users_ms_companys_id);
        $this->db->where('a.deleted_at is null', null, false);

        $query = $this->db->get()->result_array();

        //prosess converting to xlsx
        $data = [
            'title' => 'Data Inventory Display',
            'filename' => 'inventory_display',
            'query' => $query,
        ];

        $this->excel->process($data);
    }
}
