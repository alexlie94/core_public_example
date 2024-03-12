<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Rolepermissions_model extends MY_Model
{
    use MY_Tables;

	public function __construct()
	{
		$this->_tabel = $this->_table_admins_ms_roles;
		parent::__construct();
	}

	public function _getUsers()
	{
		$this->_ci->load->model('users/Users_model','users_model');
		return $this->_ci->users_model;
	}

	public function _getAccess()
    {
        $this->_ci->load->model('access/Access_model','access_model');
        return $this->_ci->access_model;
    }

	public function _getMenu()
	{
		$this->_ci->load->model('menu/Menu_model','menu_model');
		return $this->_ci->menu_model;
	}

	public function _getRoleAccess()
	{
		$this->_ci->load->model('roleaccess/Roleaccess_model','roleaccess_model');
		return $this->_ci->roleaccess_model;
	}

    public function _getApp()
	{
		$this->_ci->load->model('app/App_model','app_model');
		return $this->_ci->app_model;
	}

	public function show($button)
    {
        $this->datatables->select("a.id,
            a.role_name,
            (select count(bb.id) from {$this->_table_admins_ms_access} aa inner join {$this->_table_admins} bb ON bb.id = aa.{$this->_table_admins}_id where aa.{$this->_tabel}_id = a.id and bb.deleted_at is null) as jumlah_user,
            IF(a.status = 1, 'enable','disable') as status,
            ",false);
        

        $fieldSearch = [
            'a.role_name'
        ];

        $this->_searchDefaultDatatables($fieldSearch);
        
        $this->datatables->order_by('a.updated_at desc');
        $this->datatables->add_column('action',$button,'id');
        $this->datatables->from("{$this->_tabel} a");
        return $this->datatables->generate();
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

            $users = $this->_getUsers()->get(array('remember_token' => $this->_session_id));
            if($users){
                $cek = $this->_getAccess()->get(array("{$this->_table_admins}_id" => $users->id,"{$this->_table_admins_ms_roles}_id" => $id));
                if ($cek) {
                    throw new Exception("Sorry, you don't have permission to access", 1);
                }
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
            if($id == null){
                throw new Exception("Failed delete item", 1);
                
            }
            $validate = $this->validationDelete($id);
			
            if(!$validate['status']){
                throw new Exception($validate['messages'], 1);
                
            }

            $deleteRoleAccess = $this->_getRoleAccess()->delete(array("{$this->_table_admins_ms_roles}_id" => $id));
			if(!$deleteRoleAccess){
				throw new Exception("Failed delete Role Access item", 1);
				
			}

            $softDelete = $this->delete($id);
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

	public function validationDelete($id)
	{
		$response = array('status' => false,'messages' => '');
		try {
			if ($id == null) {
				throw new Exception("Failed delete item", 1);
			}

			$get = $this->get(array('id' => $id));
			if (!$get) {
				throw new Exception("Failed delete item", 1);
			}

			$users = $this->_getUsers()->get(array('remember_token' => $this->_session_id));
			if($users){
				$cek = $this->_getAccess()->get(array("{$this->_table_admins_ms_roles}_id" => $id, "{$this->_table_admins}_id" => $users->id));
				if ($cek) {
					throw new Exception("Sorry, you don't have permission to access", 1);
				}
			}

			$cekRelasi = $this->_getAccess()->get(array("{$this->_table_admins_ms_roles}_id" => $id));
			if ($cekRelasi) {
				throw new Exception("Role name has active users", 1);
			}

			$response['status'] = true;
			return $response;

		} catch (Exception $e) {
			$response['messages'] = $e->getMessage();
			return $response;
		}
	}

	public function _validate()
	{
		$response = array('success' => false, 'validate' => true);
        $role_name = array('trim','required','xss_clean');
		$id = clearInput($this->input->post('id'));
        $role_check = empty($id) ? array(
            'role_check',function($value){
                if(!empty($value) || $value != ''){
                    try {
                        $cek = $this->get(array('role_name' => clearInput($value)));
                        if(is_object($cek)){
                            throw new Exception;                       
                        }
                        return true;
                    } catch (Exception $e) {
                        $this->form_validation->set_message('role_check','The {field} already used');
                        return false;
                    }
                }
            }
        ) : array(
            'role_check',function($value) use ($id){
                if(!empty($value) || $value != ''){
                    try {
                        $cek = $this->get(array('role_name' => clearInput($value)));
                        if(is_object($cek)){
                            if($cek->id != $id){
                                throw new Exception; 
                            }                      
                        }
                        return true;
                    } catch (Exception $e) {
                        $this->form_validation->set_message('role_check','The {field} already used');
                        return false;
                    }
                }
            }
        );

        array_push($role_name,$role_check);

        $this->form_validation->set_rules('role_name', 'Role Name', $role_name);
        $this->form_validation->set_error_delimiters('<div class="'.VALIDATION_MESSAGE_FORM.'">', '</div>');

        if ($this->form_validation->run() === false) {
            $response['validate'] = false;
            foreach ($this->input->post() as $key => $value) {
                $response['messages'][$key] = form_error($key);
            }
        }

        return $response;
	}

	private function _validateRoleAccess()
    {
        $response = array('success' => false,'validate' => true);
		$state = false;
        $roleAccess = $this->input->post('rolepermissions');
        foreach ($roleAccess as $key => $value) {
            if(count($value) > 1){
                $state = true;
            }
        }

        if(!$state){
            $response['messages'] = "Role Permission must be required.";
        }

        return $response;
    }

	public function save($class = "")
	{
		$id = clearInput($this->input->post('id'));
		$this->db->trans_begin();
		try {
			$response = $this->_validate();
			if($response['validate'] === false){
				throw new Exception("", 1);
				
			}

			$response = $this->_validateRoleAccess();
			if(isset($response['messages'])){
				throw new Exception("Error Processing Request", 1);
				
			}

			$data_role = array(
                'role_name' => clearInput($this->input->post('role_name')), 
            );

			$process_role = empty($id) ? $this->insert($data_role) : $this->update(array('id' => $id),$data_role);
            if(!$process_role){
                $response['messages'] = 'Failed '.(empty($id) ? "insert data ":"update data ")."Role Permission";
                throw new Exception("Error Processing Request", 1);
                
            }

			$roleAccess = $this->input->post('rolepermissions');
            $childMenu = [];

            //check permissions 
            $menu_id_permission = '';
            $permissionOff = false;

            if($class != ''){
                $checkPermission = $this->_getMenu()->get(array('controller' => $class));
                if(is_object($checkPermission)){
                    $menu_id_permission = $checkPermission->id;
                }
                
            }   
            

            foreach($roleAccess as $ky => $val){
                
                $view = isset($val['view']) ? 1: 0;
                $insert = isset($val['insert']) ? 1: 0;
                $update = isset($val['update']) ? 1 : 0;
                $delete = isset($val['delete']) ? 1 : 0;
                $import = isset($val['import']) ? 1 : 0;
                $export = isset($val['export']) ? 1 : 0;

                $data_permission = array(
                    'view' => $view,
                    'insert' => $insert,
                    'update' => $update,
                    'delete' => $delete,
                    'import' => $import,
                    'export' => $export,
                );

                $menu_id = $val['menu_id'];
                $childMenu[] = array(
                    'menu_id' => $menu_id,
                    'view' => $view,
                );

                if($menu_id == $menu_id_permission && $view == 0){
                    $permissionOff = true;
                }

                if(empty($id)){
                    $data_permission["{$this->_table_admins_ms_menus}_id"] = $menu_id;
                    $data_permission["{$this->_table_admins_ms_roles}_id"] = $process_role;
                    $process = $this->_getRoleAccess()->insert($data_permission);

                }else{
                    $array = array("{$this->_table_admins_ms_roles}_id" => $id, "{$this->_table_admins_ms_menus}_id" => $menu_id);
                    $check = $this->_getRoleAccess()->get($array);
                    if($check){
                        $process = $this->_getRoleAccess()->update($array, $data_permission);
                    }else{
                        $data_permission["{$this->_table_admins_ms_menus}_id"] = $menu_id;
                        $data_permission["{$this->_table_admins_ms_roles}_id"] = $id;
                        $process = $this->_getRoleAccess()->insert($data_permission);
                    }
                    
                }

                if(!$process){
                    $response['messages'] = 'Failed '.(empty($id) ? "insert data ":"update data ")."Role Permission Access";
                    throw new Exception("Error Processing Request", 1);
                    
                }
            }

			if(count($childMenu) > 0){
                $headerMenu = [];
                foreach($childMenu as $kyMenu => $valMenu){
                    $menu_id = $valMenu['menu_id'];
                    $view = $valMenu['view'];
                    $count_string = strlen($menu_id);
                    switch ($count_string) {
                        case 3:
                            $menu = substr($menu_id, 0, 1);
                            $cari    = array_search($menu, array_column($childMenu,'menu_id'));
                            $cariVal = array_search($menu,array_column($headerMenu,'menu_id'));
                            if ($cari === false && $cariVal === false) {
                                $headerMenu[] = array(
                                    'menu_id' => $menu,
                                    'view' => $view,
                                );
                            }

                            if($cariVal !== false){
                                $headerMenu[$cariVal]['view'] = $headerMenu[$cariVal]['view'] == 1 ? 1 : $view; 
                            }

                            break;
                        case 5:
                            $menu =  substr($menu_id,0,1);
                            $cari    = array_search($menu, array_column($childMenu,'menu_id'));
                            $cariVal = array_search($menu,array_column($headerMenu,'menu_id'));
                            if ($cari === false && $cariVal === false) {
                                $headerMenu[] = array(
                                    'menu_id' => $menu,
                                    'view' => $view,
                                );
                            }

                            if($cariVal !== false){
                                $headerMenu[$cariVal]['view'] = $headerMenu[$cariVal]['view'] == 1 ? 1 : $view; 
                            }

                            $menu = substr($menu_id,0,3);
                            $cari    = array_search($menu, array_column($childMenu,'menu_id'));
                            $cariVal = array_search($menu,array_column($headerMenu,'menu_id'));
                            if ($cari === false && $cariVal === false) {
                                $headerMenu[] = array(
                                    'menu_id' => $menu,
                                    'view' => $view,
                                );
                            }

                            if($cariVal !== false){
                                $headerMenu[$cariVal]['view'] = $headerMenu[$cariVal]['view'] == 1 ? 1 : $view; 
                            }

                            break;

                    }
                }

                if(count($headerMenu) > 0){
					$data_permission = [];
                    foreach($headerMenu as $kyHeader => $valHeader){
                        $data_permission = array(
                            'view' => $valHeader['view'],
                        );
                        $menu_id = $valHeader['menu_id'];
                        if(empty($id)){
                            $data_permission["{$this->_table_admins_ms_menus}_id"] = $menu_id;
                            $data_permission["{$this->_table_admins_ms_roles}_id"] = $process_role;
                            $process = $this->_getRoleAccess()->insert($data_permission);
                        }else{
                            $array = array("{$this->_table_admins_ms_roles}_id" => $id, "{$this->_table_admins_ms_menus}_id" => $menu_id);
                            $check = $this->_getRoleAccess()->get($array);
                            if($check){
                                $process = $this->_getRoleAccess()->update($array, $data_permission);
                            }else{
                                $data_permission["{$this->_table_admins_ms_menus}_id"] = $menu_id;
                                $data_permission["{$this->_table_admins_ms_roles}_id"] = $id;
                                $process = $this->_getRoleAccess()->insert($data_permission); 
                            }
                        }

                        if(!$process){
                            $response['messages'] = 'Failed '.(empty($id) ? "insert data ":"update data ")."Role Permission Access";
                            throw new Exception("Error Processing Request", 1);
                        }
                    }
                }
            }

        
            $this->db->trans_commit();
			$response['success'] = true;
			$response['messages'] = 'Successfully '.(empty($id) ? 'Insert Data' : 'Update Data').' Role & Permission';
            $response['type'] = empty($id) ? 'insert' : 'update';
            if($permissionOff){
                $menu_first = $this->_getApp()->validation_menu($this->_session_email,$this->_session_id,'get');
                $response['menu_first'] = $menu_first;
            }
            return $response;

		} catch (Exception $e) {
			$this->db->trans_rollback();
			return $response;

		}
	}

	public function getMenuUpdate($id)
	{
		$menu = $this->_getMenu()->menu();
        $role = $this->_getRoleAccess()->get_all(array("{$this->_table_admins_ms_roles}_id" => $id));

        foreach($role as $ky => $val){
            $menu_id = $val->{$this->_table_admins_ms_menus."_id"};
            $view = $val->view;
            $insert = $val->insert;
            $update = $val->update;
            $delete = $val->delete;
            $import = $val->import;
            $export = $val->export;

            switch (strlen($menu_id)) {
                case 1:
                    $cari = array_search($menu_id,array_column($menu,'id'));
                    if($cari !== false){
                        $menu[$cari]['viewValue'] = $view;
                        $menu[$cari]['insertValue'] = $insert;
                        $menu[$cari]['updateValue'] = $update;
                        $menu[$cari]['deleteValue'] = $delete;
                        $menu[$cari]['importValue'] = $import;
                        $menu[$cari]['exportValue'] = $export;
                    }
                    break;

                case 3:
                    $menu_1 = substr($menu_id,0,1);
                    $cari = array_search($menu_1,array_column($menu,'id'));
                    if($cari !== false){
                        $child = $menu[$cari]['child'];
                        $cariChild = array_search($menu_id,array_column($child,'id'));
                        if($cariChild !== false){
                            $menu[$cari]['child'][$cariChild]['viewValue'] = $view;
                            $menu[$cari]['child'][$cariChild]['insertValue'] = $insert;
                            $menu[$cari]['child'][$cariChild]['updateValue'] = $update;
                            $menu[$cari]['child'][$cariChild]['deleteValue'] = $delete;
                            $menu[$cari]['child'][$cariChild]['importValue'] = $import;
                            $menu[$cari]['child'][$cariChild]['exportValue'] = $export;
                        }
                    }
                    break;
                
            }

        }

        return $menu;
	}
}
