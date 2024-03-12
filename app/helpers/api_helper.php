<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once APPPATH . '/libraries/REST_Controller.php';

if (!function_exists('api_response')) {
    function api_response($error = false, $http_code = 200, $message = null, $data = [], $token = null)
    {
        $ci = &get_instance();
        $res = [];

        $res['error'] = $error;
        $res['status_code'] = $http_code;
        if ($message != null) {
            $res['message'] = $message;
        }

        if (count($data) !== 0) {
            $res['data'] = $data;
        }
        if ($token != null) {
            $res['access_token'] = $token;
        }

        return $res;
    }
}

if (!function_exists('check_header')) {
    function check_header($param)
    {
        $ci = &get_instance();

        try {
            $authorization = $param->get_request_header('Authorization');
            $content_type = $param->get_request_header('Content-Type');

            if ($content_type == false || $content_type != 'application/json' || $authorization == false) {
                throw new Exception('Incomplete or invalid header provided ', 401);
            } else {
                $split = explode(' ', $authorization);
                if ($split[0] == 'Bearer') {
                    $user_check = user_check($split[1]);

                    if ($user_check === true) {
                        return api_response(false, 200, 'OK', [], $split[1]);
                    } else {
                        throw new Exception('Invalid access token', 401);
                    }
                } else {
                    throw new Exception('Error authorization', 401);
                }
            }
        } catch (Exception $e) {
            $error_message = $e->getMessage();
            $error_code = $e->getCode();

            $response = api_response(true, $error_code, $error_message);
            return $response;
        }
    }
}

if (!function_exists('user_check')) {
    function user_check($param)
    {
        $ci = &get_instance();

        try {
            $query = $ci->db->query("SELECT api_token
         FROM users
         WHERE api_token = '{$param}'
        ");
            $res = $query->num_rows();

            if ($res < 1) {
                throw new Exception(false);
            }

            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}

if (!function_exists('generate_jwt')) {
    function generate_jwt($data)
    {
        $ci = &get_instance();

        $secret_key = $ci->config->item('jwt_secret_key');

        $ci->load->library('jwt');
        $token = $ci->jwt->encode($data, $secret_key, 'HS256');

        return $token;
    }
}

if (!function_exists('get_jwt_data')) {
    function get_jwt_data($data)
    {
        $ci = &get_instance();

        $secret_key = $ci->config->item('jwt_secret_key');

        $ci->load->library('jwt');
        $data = $ci->jwt->decode($data, $secret_key);

        return $data;
    }
}

if (!function_exists('form_validation')) {
    function form_validation($data, $company_id = null, $unique_field = [])
    {
        $ci = &get_instance();
        $ci->load->library('form_validation');

        foreach ($data as $field => $value) {
            $_POST[$field] = $value;
            $rules = 'required';

            if ($field == isset($unique_field['unique_field'])) {
                $check_unique = check_unique($unique_field['from_table'], $company_id, $unique_field['unique_field'], $value);

                if (!$check_unique) {
                    return ['error' => true, 'message' => 'The ' . $unique_field['unique_field'] . ' with value ' . $value . ' is exist'];
                }
            }

            $ci->form_validation->set_rules($field, $field, $rules);
        }

        $ci->form_validation->set_message('required', 'The {field} field is required');
        $ci->form_validation->set_error_delimiters('', ',');

        if ($ci->form_validation->run() == false) {
            $error_messages = implode(',', $ci->form_validation->error_array());
            $error_messages = str_replace("\n", '', $error_messages);
            return ['error' => true, 'message' => $error_messages];
        } else {
            return ['error' => false];
        }
    }
}

if (!function_exists('check_unique')) {
    function check_unique($table, $company_id = null, $unique_field = null, $val = null)
    {
        $ci = &get_instance();
        $ci->db->where($unique_field, $val);
        $ci->db->where('users_ms_companys_id', $company_id);
        $query = $ci->db->get($table);

        if ($query->num_rows() > 0) {
            return false;
        } else {
            return true;
        }
    }
}

if (!function_exists('query_api_check')) {
    function query_api_check($sources_id = null, $channel_id = null)
    {
        $ci = &get_instance();

        $query = $ci->db->query(
            "SELECT
				t1.id as source_id,
				t1.source_url,
				t1.source_auth_url,
				t1.app_keys,
				t1.secret_keys,
				t2.id as auth_id,
				t2.users_ms_companys_id as company_id,
				t2.channels_id as channel_id,
				t2.shop_id,
				t2.access_token
			FROM admins_ms_sources t1
			INNER JOIN users_ms_authenticate_channels t2
					ON t1.id = t2.sources_id
					AND t2.status = 1
					AND t2.deleted_at IS NULL
			INNER JOIN admins_ms_endpoints t3
					ON t1.id = t3.admins_ms_sources_id
					AND t3.endpoint_url  = 'get_master'
					AND t3.status = 1
					AND t3.deleted_at IS NULL
			INNER JOIN admins_ms_company_endpoints t4
					ON t2.users_ms_companys_id = t4.users_ms_companys_id
					AND t3.id = t4.admins_ms_endpoints_id
					AND t4.status = 1
					AND t4.deleted_at IS NULL
            WHERE t2.sources_id = {$sources_id}
			AND t2.channels_id = {$channel_id}
			AND t1.status = 1
			AND t1.deleted_at IS NULL"
        );

        $result = $query->row();

        return $result;
    }
}

if (!function_exists('get_master_marketplace')) {
    function get_master_marketplace($type = '', $sources_id = '', $channel_id = null)
    {
        $result = query_api_check($sources_id, $channel_id);

        if ($result) {
            if ($result->shop_id == '' || $result->shop_id == null) {
                return false;
            } else {

                $config =
                    [
                        'partner_id' => intVal($result->app_keys),
                        'secret_key' => $result->secret_keys,
                        'shop_id' => $result->shop_id,
                        'access_token' => $result->access_token,
                        'host' => $result->source_url,
                        'timestamp' => time()
                    ];

                switch ($type) {
                    case 'category':

                        $config['path'] = '/api/v2/product/get_category';
                        $string     = $config['partner_id'] . $config['path'] . $config['timestamp'] . $config['access_token'] .  $config['shop_id'];
                        $sign       = hash_hmac('sha256', $string, $config['secret_key']);

                        $param = array(
                            "timestamp"                 => $config['timestamp'],
                            "access_token"              => $config['access_token'],
                            "partner_id"                => $config['partner_id'],
                            "shop_id"                   => $config['shop_id'],
                            "sign"                      => $sign
                        );

                        $url     = create_url($config['host'], $config['path'], $param);
                        $data    = get_request_curl($url);

                        if ($data->error === 'error_auth') {
                            return false;
                        } else {
                            return $data->response;
                        }
                        break;
                    case 'shipping':

                        $config['path'] = '/api/v2/logistics/get_channel_list';
                        $string     = $config['partner_id'] . $config['path'] . $config['timestamp'] . $config['access_token'] .  $config['shop_id'];
                        $sign       = hash_hmac('sha256', $string, $config['secret_key']);

                        $param = array(
                            "timestamp"                 => $config['timestamp'],
                            "access_token"              => $config['access_token'],
                            "partner_id"                => $config['partner_id'],
                            "shop_id"                   => $config['shop_id'],
                            "sign"                      => $sign
                        );

                        $url     = create_url($config['host'], $config['path'], $param);
                        $data    = get_request_curl($url);

                        if ($data->error === 'error_auth') {
                            return false;
                        } else {
                            return $data->response;
                        }

                        break;
                    default:
                        return false;
                }
            }
        } else {
            return false;
        }
    }
}

if (!function_exists('get_brand_list')) {
    function get_brand_list()
    {
        $sources_id = isset($_GET['sources_id']) ? $_GET['sources_id'] : '';
        $channel_id = isset($_GET['channels_id']) ? $_GET['channels_id'] : '';

        $check_api_connect = query_api_check($sources_id, $channel_id);

        if ($check_api_connect) {
            $get_ctg_id = isset($_GET['category_id']) ? $_GET['category_id'] : '';

            if ($check_api_connect->shop_id == '' || $check_api_connect->shop_id == null) {
                return false;
            } else {

                $config =
                    [
                        'partner_id' => intVal($check_api_connect->app_keys),
                        'secret_key' => $check_api_connect->secret_keys,
                        'shop_id' => $check_api_connect->shop_id,
                        'access_token' => $check_api_connect->access_token,
                        'host' => $check_api_connect->source_url,
                        'timestamp' => time()
                    ];

                $config['path'] = '/api/v2/product/get_brand_list';
                $string     = $config['partner_id'] . $config['path'] . $config['timestamp'] . $config['access_token'] .  $config['shop_id'];
                $sign       = hash_hmac('sha256', $string, $config['secret_key']);

                $param = array(
                    "timestamp"                 => $config['timestamp'],
                    "access_token"              => $config['access_token'],
                    "category_id"               => $get_ctg_id,
                    "partner_id"                => $config['partner_id'],
                    "shop_id"                   => $config['shop_id'],
                    "sign"                      => $sign,
                    "offset"                    => 0,
                    "page_size"                 => 10,
                    "status"                    => 1,
                );

                $url     = create_url($config['host'], $config['path'], $param);
                $data    = get_request_curl($url);

                if ($data->error === 'error_auth') {
                    return $data->error;
                } elseif ($data->error === 'product.error_invalid_category') {
                    return 'empty';
                } else {
                    return $data->response;
                }
            }
        } else {
            return false;
        }
    }

    if (!function_exists('post_image_request_url')) {
        function post_image_request_url($boundary, $url = '', $data_json = '')
        {
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLINFO_HEADER_OUT => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $data_json,
                CURLOPT_HTTPHEADER => array(
                    "Content-Type: multipart/form-data; boundary=$boundary",
                    'cache-control: no-cache',
                ),
            ));
            $response = curl_exec($curl);

            curl_close($curl);
            return json_decode($response);
        }
    }
}
