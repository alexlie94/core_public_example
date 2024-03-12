<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Users_model extends MY_Model
{
    use MY_Tables;
	
    public function __construct()
	{
		$this->_tabel = $this->_table_admins;
		parent::__construct();
	}

    public function _getRole()
	{
		$this->_ci->load->model('rolepermissions/Rolepermissions_model','rolepermissions_model');
		return $this->_ci->rolepermissions_model;
	}

    public function _getAccess()
    {
        $this->_ci->load->model('access/Access_model','access_model');
        return $this->_ci->access_model;
    }

    public function show($button = '')
    {

        $this->datatables->select("a.id as id,
            a.fullname as fullname,
            a.email as email,
            IF(a.status = 1, 'enable','disable') as status,
            c.role_name as role_name",false);
        $this->datatables->where("a.deleted_at is null",null,false);
        $this->datatables->join("{$this->_table_admins_ms_access} b","b.{$this->_table_admins}_id = a.id","inner");
        $this->datatables->join("{$this->_table_admins_ms_roles} c","c.id = b.{$this->_table_admins_ms_roles}_id","inner");

        $filters = !empty($this->input->post('filters')) ? $this->input->post('filters'): false;
        
        $status = [];
        $roleName = [];
        if($filters !== false && is_array($filters)){
            foreach($filters as $ky => $val){
                $value = $val['value'];
                if(!empty($value)){
                    switch ($val['name']) {
                        case 'fullname_filter':
                                $this->datatables->like('a.fullname',$value);
                            break;
                        case 'email_filter':
                                $this->datatables->like('a.email',$value);
                            break;
                        case 'rolename_filter[]':
                                $roleName[] = $value;
                            break;
                        case 'inputStatus_filter[]':
                               $status[] = $value;
                            break;
                    }
                }
            }
        }

        if(count($status) > 0){
            $state = [];
            for($i= 0; $i < count($status);$i++){
                switch ($status[$i]) {
                    case 'enable':
                        $state[] = 1;
                        break;
                    
                    case 'disable':
                        $state[] = 0;
                        break;
                }
            }
            $this->datatables->where_in('a.status',$state);
        }

        if(count($roleName) > 0){
            $this->datatables->where_in('c.id',$roleName);
        }

        $fieldSearch = [
            'a.fullname',
            'a.email'
        ];

        $this->_searchDefaultDatatables($fieldSearch);

        $this->datatables->order_by('a.updated_at desc');
        $this->datatables->add_column('action',$button,'id');
        $this->datatables->from("{$this->_table_admins} a");
        return $this->datatables->generate();
    }

    private function _validate()
    {
        $response = array('success' => false, 'validate' => true, 'messages' => []);
        $response['type'] = !empty($this->input->post('id')) ? 'update' : 'insert';

        $role_fullname = array('trim','required','xss_clean');
        $role_email = array('trim','required','valid_email','xss_clean');
        $role_password = $response['type'] == 'insert' ? array('trim','required','xss_clean','min_length[8]','max_length[15]') : [];
        $role_access = array('trim','required','xss_clean');

        $this->form_validation->set_rules('fullname', 'Fullname', $role_fullname);

        $id = !empty($this->input->post('id')) ? clearInput($this->input->post('id')) : "";
        $email_check = $response['type'] == 'insert' ? array(
            'email_check',function($value){
                if(!empty($value) || $value != ''){
                    try {
                        $cek = $this->get(array('email' => clearInput($value)));
                        if(is_object($cek)){
                            throw new Exception;                            
                        }
                        return true;
                    } catch (Exception $e) {
                        $this->form_validation->set_message('email_check','The {field} already used');
                        return false;
                    }
                }
            }
        ) : array(
            'email_check',function($value) use ($id){
                if(!empty($value) || $value != ''){
                    try {
                        $cek = $this->get(array('email' => clearInput($value)));
                        if(is_object($cek)){
                            if($cek->id != $id){
                                throw new Exception;
                                
                            }                            
                        }
                        return true;
                    } catch (Exception $e) {
                        $this->form_validation->set_message('email_check','The {field} already used');
                        return false;
                    }
                }
            }
        );;
        array_push($role_email,$email_check);

        $this->form_validation->set_rules('email', 'Email', $role_email);

        if($response['type'] == 'update'){
            $password_check = array(
                'password_check',function($value) use ($id){
                    if(!empty($value) || $value != ''){
                        try {
                            $length = strlen($value);
                            if($length < 8 || ($length > 8  && $length < 15)){
                                throw new Exception;
                                
                            }
                            return true;
                        } catch (Exception $e) {
                            $this->form_validation->set_message('password_check','The {field} min length 8 character & max length 15 character');
                            return false;
                        }
                    }
                }
            );
            array_push($role_password,$password_check);
        }

        $this->form_validation->set_rules('password', 'Password', $role_password);

        $this->form_validation->set_rules('rolename', 'Role Name', $role_access);

        $this->form_validation->set_error_delimiters('<div class="'.VALIDATION_MESSAGE_FORM.'">', '</div>');

        if ($this->form_validation->run() === false) {
            $response['validate'] = false;
            foreach ($this->input->post() as $key => $value) {
                $response['messages'][$key] = form_error($key);
            }
        }

        if(empty($this->input->post('rolename'))){
            $response['validate'] = false;
            $response['messages']['rolename'] = "<div class=\"".VALIDATION_MESSAGE_FORM."\">The Role field is required.</div>";
        }

        return $response;
    }

    public function save()
    {
        $this->db->trans_begin();
        try {

            $response = self::_validate();

            if(!$response['validate']){
                throw new Exception("Error Processing Request", 1);
                
            }

            $id = clearInput($this->input->post('id'));
            $fullname = clearInput($this->input->post('fullname'));
            $password = clearInput($this->input->post('password'));
            $status = empty($this->input->post('status')) ? 0 : clearInput($this->input->post('status'));
            $email = clearInput($this->input->post('email'));
            $role_id = clearInput($this->input->post('rolename'));

            $data_array = array(
                'fullname' => $fullname,
                'password' => $password,
                'email' => $email,
                'status' => $status == 'enabled' ? 1 : 0,
            );

            $data_access = array(
                "{$this->_table_admins_ms_roles}_id" => $role_id,
            );

            if(empty($id)){
                $data_array['remember_token'] = generateCode();
                $data_array['password'] = password_hash($password, PASSWORD_DEFAULT);
                $process = $this->insert($data_array);
                if(!$process){
                    $response['messages'] = 'Failed insert data user admin';
                    throw new Exception;
                    
                }

                $data_access["{$this->_table_admins}_id"] = $process;
                $insert_access = $this->_getAccess()->insert($data_access);
                if(!$insert_access){
                    $response['messages'] = 'Failed insert Role user admin';
                    throw new Exception;
                }

                $response['messages'] = "Successfully Insert data user admin";

            }else{
                $data = $this->get(array('id' => $id));
                if(!$data){
                    $response['messages'] = 'Data update invalid';
                    throw new Exception;
                    
                }

                if(strlen($password) < 8){
                    unset($data_array['password']);
                }else{
                    $data_array['password'] = password_hash($password, PASSWORD_DEFAULT);
                }

                $process = $this->update(array('id' => $id),$data_array);
                if(!$process){
                    $response['messages'] = 'Failed update data user';
                    throw new Exception;
                    
                }
               
                $update_access = $this->_getAccess()->update(array("{$this->_table_admins}_id" => $id),$data_access);
                if(!$update_access){
                    $response['messages'] = 'Failed update Role user admin';
                    throw new Exception;
                    
                }

                $response['messages'] = 'Successfully update data User admin';
            }

            $this->db->trans_commit();
            $response['success'] = true;
            return $response;

        } catch (Exception $e) {
            $this->db->trans_rollback();
            return $response;
        }
    }

    public function getItems($id,$email)
    {
        try {
           
			$get = $this->get(array('id' => $id));
			if (!$get) {
				throw new Exception("Data not Register", 1);
			}

			if ($get->email == $email) {
				throw new Exception("Sorry,you don't have permission to access", 1);
			}

			$role = $this->_getAccess()->get(array("{$this->_table_admins}_id" => $get->id));
			if (!$role) {
				throw new Exception("Role User not found", 1);
			}

			$select = $this->_getRole()->get_all(array('status' => 1));

			$table = array(
				'id' => $get->id,
				'role_id' => $role->{$this->_table_admins_ms_roles."_id"},
				'fullname' => $get->fullname,
				'email' => $get->email,
				'checked' => $get->status == 1 ? 'enabled' : 'disabled',
				'role' => $select,
			);

            return $table;
        } catch (Exception $e) {
            return $e->getMessage();           
        }
    } 

    public function changeStatus($id)
    {
        try {
            if ($id == null) {
				throw new Exception("Failed change status", 1);
			}

			$get = $this->get(array('id' => $id));
			if (!$get) {
				throw new Exception("Failed change status", 1);
			}

			if ($get->email == $this->_session_email) {
				throw new Exception("Sorry,you don't have permission to change status this item", 1);
			}

			$status = $get->status == 1 ? 0 : 1;
			$update = $this->update(array('id' => $id), array('status' => $status));
			if (!$update) {
				throw new Exception("Failed change status", 1);
			}

            return true;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function deleteData($id)
    {
        $this->db->trans_begin();
        try {
            if ($id == null) {
				throw new Exception("Failed delete item", 1);
			}

			$get = $this->get(array('id' => $id));
			if (!$get) {
				throw new Exception("Failed delete item", 1);
			}

			if ($get->email == $this->_session_email) {
				throw new Exception("Sorry,you don't have permission to delete this item", 1);
			}

			$access = $this->_getAccess()->delete(array("{$this->_tabel}_id" => $id));
			if(!$access){
				throw new Exception("Failed delete Access Role Users", 1);
				
			}

			$softDelete = $this->softDelete($id);
			if (!$softDelete) {
				throw new Exception("Failed delete item", 1);
			}

            $this->db->trans_commit();
            return true;
        } catch (Exception $e) {
            $this->db->trans_rollback();
            return $e->getMessage();
        }
    }

    public function getProfil()
    {
        $get = $this->get(array('email' => $this->_session_email,'remember_token' => $this->_session_id));
        return $get;
    }

}
