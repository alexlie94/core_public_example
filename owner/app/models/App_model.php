<?php
defined('BASEPATH') or exit('No direct script access allowed');

class App_model extends MY_Model
{
    use MY_Tables;

    public function __construct()
    {
        $this->_tabel = $this->_table_admins;
        parent::__construct();
    }

    public function _check_data_user($email,$remember_token = null)
    {
        try {
            $this->db->select("a.email,
                c.status,
                (SELECT 
                        COUNT(bb.menu_name)
                    FROM
                        {$this->_table_admins_ms_role_accesscontrols} aa
                            INNER JOIN
                        {$this->_table_admins_ms_menus} bb ON bb.id = aa.{$this->_table_admins_ms_menus}_id
                    WHERE
                        bb.status = 1
                            AND aa.{$this->_table_admins_ms_roles}_id = c.id
                            AND aa.view = 1
                            AND bb.deleted_at IS NULL) AS total_menu_active", false);

            $this->db->join("{$this->_table_admins_ms_access} b","b.{$this->_table_admins}_id = a.id","inner");
            $this->db->join("{$this->_table_admins_ms_roles} c","c.id = b.{$this->_table_admins_ms_roles}_id","inner");
            $this->db->join("{$this->_table_admins_ms_role_accesscontrols} d","d.{$this->_table_admins_ms_roles}_id = c.id","inner");
            $this->db->group_by(array("a.email" , "c.status", "total_menu_active"));

            $this->db->where(array("a.email" => $email,"a.status" => 1, "c.status" => 1));
            if($remember_token != null){
                $this->db->where(array('a.remember_token' => $remember_token));
            }

            $check = $this->db->get("{$this->_table_admins} a")->row();
            if(!$check){
                throw new Exception("Error Processing Request", 1);
                
            }

            return true;
        } catch (Exception $e) {
            return false;
        }
        
    }

    public function get_menu($email,$remember_token,$method = 'all')
    {   
        $this->db->select("e.id AS menu_id,
            e.controller,
            e.menu_name,
            e.icon,
            e.parent,
            e.order,
            d.view,
            d.insert,
            d.update,
            d.delete,
            d.import,
            d.export",FALSE);

        $this->db->join("{$this->_table_admins_ms_access} b","b.{$this->_table_admins}_id = a.id","inner");
        $this->db->join("{$this->_table_admins_ms_roles} c","c.id = b.{$this->_table_admins_ms_roles}_id","inner");
        $this->db->join("{$this->_table_admins_ms_role_accesscontrols} d","d.{$this->_table_admins_ms_roles}_id = c.id","inner");
        $this->db->join("{$this->_table_admins_ms_menus} e","e.id = d.{$this->_table_admins_ms_menus}_id","inner");
        $this->db->where(array(
            'a.email' => $email,
            'a.remember_token' => $remember_token,
        ));
        $this->db->where("a.status !=",0);
        $this->db->where("a.deleted_at IS NULL");
        $this->db->where("b.deleted_at IS NULL");
        $this->db->where("c.status !=",0);
        $this->db->where("c.deleted_at IS NULL");
        $this->db->where("d.deleted_at IS NULL");
        $this->db->where("e.status !=",0);
        $this->db->where("e.deleted_at IS NULL");
        $this->db->where(array("d.view" => 1));

        if($method == 'all'){
            $this->db->order_by("e.parent asc,e.order asc,e.id asc");
            $result = $this->db->get("{$this->_table_admins} a")->result();
        }else{
            $this->db->limit(1);
            $result = $this->db->get("{$this->_table_admins} a")->row();
        }

        return $result;
    }

    public function validation_menu($email,$remember_token,$method)
    {
        $get = $this->get_menu($email,$remember_token,$method);
        if(is_object($get)){
            $menu_id = $get->menu_id;
            $this->db->where(array('parent' => $menu_id));
            $this->db->limit(1);
            $check =$this->db->get("{$this->_table_admins_ms_menus}")->row();
            if(is_object($check)){
                $this->db->select("d.controller,c.{$this->_table_admins_ms_menus}_id,c.view",false);
                $this->db->join("{$this->_table_users_ms_access} b","a.id = b.{$this->_table_admins}_id","inner");
                $this->db->join("{$this->_table_users_ms_role_accesscontrols} c","c.{$this->_table_admins_ms_roles}_id = b.{$this->_table_admins_ms_roles}_id","inner");
                $this->db->join("{$this->_table_users_ms_menus} d","d.id = c.{$this->_table_admins_ms_menus}_id","inner");
                $this->db->where(array("a.email" => $email,"a.remember_token" => $remember_token));
                $this->db->where(array("c.view" => 1,"d.parent" => $menu_id));
                $this->db->limit(1);
                $result = $this->db->get("{$this->_table_admins} a")->row();
                return $result->controller;
            }

            return $get->controller;
        }

        return '';
    }

    public function createSession($email)
    {
        $response = array('success' => false, 'validate' => true);
        $check    = $this->get(array('email' => $email));

        try {

            if(!$check){
                $response['messages'] = 'Invalid Email or Password';
                throw new Exception("Error Processing Request", 1);
                
            }

            //create session_id
            $remember_token = generateCode();

            //process update
            $data = array(
                'remember_token' => $remember_token,
            );
            $where = array(
                'email' => $email,
            );
            $update = $this->update($where,$data);
            if (!$update) {
                $response['messages'] = 'Invalid Email or Password';
                throw new Exception("Error Processing Request", 1);
            }

            //create session
            $session = array(
                'email_admin'      => $email,
                'session_id_admin' => $remember_token,
                'x-id-user' => $check->id,
            );
            $this->session->set_userdata($session);

            //get menu first 
            $menu = $this->validation_menu($email,$remember_token,'get');
            if($menu){
                $response['success'] = true;
                $response['menu_first'] = $menu; 
                return $response; 
            }

        } catch (Exception $e) {
            return $response;
        }

    }
}