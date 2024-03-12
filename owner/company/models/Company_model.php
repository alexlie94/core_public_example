<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Company_model extends MY_Model
{
    use MY_Tables;

    public function __construct()
    {
        $this->_tabel = $this->_table_users_ms_companys;
        parent::__construct();
    }

    public function _getRole()
    {
        $this->_ci->load->model('rolepermissions/Rolepermissions_model', 'rolepermissions_model');
        return $this->_ci->rolepermissions_model;
    }

    public function _getAccess()
    {
        $this->_ci->load->model('access/Access_model', 'access_model');
        return $this->_ci->access_model;
    }

    public function _getUsers()
    {
        $this->_ci->load->model('userscompany/Userscompany_model','userscompany_model');
        return $this->_ci->userscompany_model;
    }

    public function _getRoleCompany()
    {
        $this->_ci->load->model('rolescompany/Rolescompany_model','rolescompany_model');
        return $this->_ci->rolescompany_model;
    }

    public function _getAccessCompany()
    {
        $this->_ci->load->model('accesscompany/Accesscompany_model','accesscompany_model');
        return $this->_ci->accesscompany_model;
    }

    public function _getAccessControlCompany()
    {
        $this->_ci->load->model('accesscontrolcompany/Accesscontrolcompany_model','accesscontrolcompany_model');
        return $this->_ci->accesscontrolcompany_model;
    }

    public function _getRoleAccessCompany()
    {
        $this->_ci->load->model('roleaccesscompany/Roleaccesscompany_model','roleaccesscompany_model');
        return $this->_ci->roleaccesscompany_model;
    }

    public function show($button = '')
    {

        $this->datatables->select(
            "id,company_code,company_name, IF(status = 1, 'enable','disable') as status, created_at",false 
        );

        $this->datatables->where("a.deleted_at is null",null,false);

        $filters = !empty($this->input->post('filters')) ? $this->input->post('filters'): false;
        
        $status = [];
        if($filters !== false && is_array($filters)){
            foreach($filters as $ky => $val){
                $value = $val['value'];
                if(!empty($value)){
                    switch ($val['name']) {
                        case 'company_name_filter':
                                $this->datatables->like('company_name',$value);
                            break;
                        case 'register_filter':
                                $this->datatables->where('DATE(created_at) =',$value);
                            break;
                        case 'status_filter[]':
                                $status[] = (string)$value;
                            break;
                    }
                }
            }
        }

        if(count($status) > 0){
            $this->datatables->where_in('status',$status);
        }

        $fieldSearch = [
            'company_name',
            'company_code',
        ];

        $this->_searchDefaultDatatables($fieldSearch);

        $this->datatables->order_by('updated_at desc');
        $this->datatables->add_column('action',$button,'id');
        $this->datatables->from("{$this->_tabel} a");
        return $this->datatables->generate();
    }

    private function _validate()
    {
        $response = array('success' => false, 'validate' => true, 'messages' => []);
        $response['type'] = !empty($this->input->post('id')) ? 'update' : 'insert';

        $role = array('trim', 'required', 'xss_clean');
        $role_email = array('trim','required','valid_email','xss_clean');

        $name_company = $role;

        $id = $this->input->post('id');

        $company_name_check = array(
            'company_name_check',function($value) use ($id){
                if(!empty($value) || $value != ""){
                    try {
                        $get = $this->get(array('company_name' => $value));
                        if(is_object($get)){
                            if(!empty($id) && $id != $get->id){
                                throw new Exception("Error Processing Request", 1);
                                
                            }

                            if(empty($id)){
                                throw new Exception("Error Processing Request", 1);
                                
                            }

                        }
                        return true;
                    } catch (Exception $e) {
                        $this->form_validation->set_message('company_name_check', 'The {field} already used');
                        return false;
                    }
                }
            }
        );

        array_push($name_company,$company_name_check);


        $company_code = $role;

        $company_code_check = array(
            'company_code_check',function($value) use ($id){
                if(!empty($value) || $value != ""){
                    try {
                        $get = $this->getWithoutDeleteNull(array('company_code' => $value));
                        if(is_object($get)){
                            if(!empty($id) && $id != $get->id){
                                throw new Exception("Error Processing Request", 1);
                                
                            }

                            if(empty($id)){
                                throw new Exception("Error Processing Request", 1);
                                
                            }

                        }
                        return true;
                    } catch (Exception $e) {
                        $this->form_validation->set_message('company_code_check', 'The {field} already used');
                        return false;
                    }
                }
            }
        );

        array_push($company_code,$company_code_check);

        $email_check = array(
            'email_check',function($value) use ($id){
                if(!empty($value) || $value != ''){

                    try {
                        
                        $cek = $this->_getUsers()->get(array('email' => $value));
                        
                        if(is_object($cek)){
                            
                            if(!empty($id) && $id != $cek->users_ms_companys_id){
                                throw new Exception("Error Processing Request", 1);
                                
                            }

                            if(!empty($id) && $cek->user_api != 1){
                                throw new Exception("Error Processing Request", 1);
                                
                            }

                            if(empty($id)){
                                throw new Exception("Error Processing Request", 1);
                                
                            }
                            
                        }
                        return true;
                    } catch (Exception $e) {
                        
                        $this->form_validation->set_message('email_check','The {field} must be use another email');
                        return false;
                    }
                }
            }
        );      

        array_push($role_email,$email_check);

        $this->form_validation->set_rules('company_name', 'Company Name', $name_company);
        $this->form_validation->set_rules('company_code', 'Company Code', $company_code);
        $this->form_validation->set_rules('fullname', 'Fullname', $role);
        $this->form_validation->set_rules('email',"Email Login",$role_email);

        if(empty($id)){
            $this->form_validation->set_rules('password','Password Login',$role);
        }

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

        $response = self::_validate();

        if (!$response['validate']) {
            return $response;
        }

        $this->db->trans_begin();
        try {

            $id = clearInput($this->input->post('id'));
            $type = is_null($id) ? 'Update' : 'Insert';
            $company_name = clearInput($this->input->post('company_name'));
            $company_code = clearInput($this->input->post('company_code'));
            $status = empty($this->input->post('status')) ? 2 : clearInput($this->input->post('status'));

            $data_array = array(
                'company_name' => $company_name,
                'company_code' => strtoupper($company_code),
                'status' => $status == 'enabled' ? 1 : 2,
            );

            $process = empty($id) ? $this->insert($data_array) : $this->update(array('id' => $id),$data_array);
            if(!$process){
                throw new Exception("Failed {$type} Data Company", 1);
                
            }

            $users_ms_companys_id = empty($id) ? $process : $id;

            $fullname = clearInput($this->input->post('fullname'));
            $email = clearInput($this->input->post('email'));
            $password = clearInput($this->input->post('password'));

            $data_users = array(
                'users_ms_companys_id' => $users_ms_companys_id,
                'fullname' => $fullname,
                'email' => $email,
                'password' => password_hash($password, PASSWORD_DEFAULT), 
                'user_api' => 1,
                'status' => 1,
            );

            if(!empty($id) && (is_null($password) || empty($password))){
                unset($data_users['password']);
            }

            $process = empty($id) ? $this->_getUsers()->insert($data_users) : $this->_getUsers()->update(array('users_ms_companys_id' => $users_ms_companys_id,'user_api' => 1),$data_users);
            if(!$process){
                throw new Exception("Failed {$type} data User Company", 1);
                
            }

            if(empty($id)){
                
                $users_id = $process;
                $data_role = [
                    'users_ms_companys_id' => $users_ms_companys_id,
                    'role_name' => 'Administrator',
                    'status' => 1,
                ];

                $process = $this->_getRoleCompany()->insert($data_role);
                if(!$process){
                    throw new Exception("Failed {$type} Role Company", 1);
                    
                }

                $users_ms_roles_id = $process;

                $data_access = [
                    'users_ms_roles_id' => $users_ms_roles_id,
                    'users_id' => $users_id,
                ];

                $process = $this->_getAccessCompany()->insert($data_access);
                if(!$process){
                    throw new Exception("Failed {$type} Access User Company", 1);
                    
                }


                $get = $this->_getAccessControlCompany()->get_all();
                if(!$get){
                    throw new Exception("Failed {$type} Access Menus", 1);
                    
                }

                $dataRoleAccess = [];
                foreach($get as $ky => $val){
                    $users_ms_menus_id = $val->users_ms_menus_id;
                    $view = $val->view;
                    $insert = $val->insert;
                    $update = $val->update;
                    $delete = $val->delete;
                    $import = $val->import;
                    $export = $val->export;

                    $dataRoleAccess[] = [
                        'users_ms_menus_id' => $users_ms_menus_id,
                        'users_ms_roles_id' => $users_ms_roles_id,
                        'view' => $view,
                        'insert' => $insert,
                        'update' => $update,
                        'delete' => $delete,
                        'import' => $import,
                        'export' => $export,
                    ];

                }

                if(count($dataRoleAccess) == 0){
                    throw new Exception("Failed {$type} Role Access Menus", 1);
                    
                }

                $process = $this->_getRoleAccessCompany()->insert_batch($dataRoleAccess);
                if(!$process){
                    throw new Exception("Failed {$type} Role Access Menus", 1);
                    
                }

            }

            $this->db->trans_commit();
            $response['messages'] = "Successfully {$type} Data Company";
            $response['success'] = true;
            return $response;
        } catch (Exception $e) {
            $this->db->trans_rollback();
            $response['success'] = false;
            $response['messages'] = $e->getMessage();
            return $response;
        }
    }

    public function getItems($id)
    {
        try {

            $get = $this->get(array('id' => $id));

            if (!$get) {
                throw new Exception("Data Not Register", 1);
            }

            $getUsers = $this->_getUsers()->get(array('users_ms_companys_id' => $get->id,'user_api' => 1));

            $table = array(
                'id' => $get->id,
                'company_name' => $get->company_name,
                'status' => $get->status == 1 ? 'enabled' : 'disabled',
                'company_code' => $get->company_code,
                'email' => $getUsers->email,
                'fullname' => $getUsers->fullname,
            );

            return $table;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function deleteData($id)
    {
        $this->db->trans_begin();
        try {
            if ($id == null) {
                throw new Exception("Failed Delete Data Company", 1);
            }

            $get = $this->get(array('id' => $id));

            if (!$get) {
                throw new Exception("Failed Delete Data Company", 1);
            }

            $softDelete = $this->softDelete($id);

            if (!$softDelete) {
                throw new Exception("Failed Delete Data Company", 1);
            }

            $this->db->trans_commit();
            return true;
        } catch (Exception $e) {
            $this->db->trans_rollback();
            return $e->getMessage();
        }
    }

    public function status($id)
    {
        $response = array('text' => 'Successfully change status item', 'success' => true);
		try {
			if ($id == null) {
				throw new Exception;
			}

			$get = $this->company_model->get(array('id' => $id));
			if (!$get) {
				throw new Exception;
			}
			$status = $get->status == 1 ? 2 : 1;
			$update = $this->company_model->update(array('id' => $id), array('status' => $status));
			if (!$update) {
				throw new Exception;
			}

			echo json_encode($response);
			exit();
		} catch (Exception $e) {
			$response['text'] = 'Failed change status';
			$response['success'] = false;
			return $response;
		}
    }
}
