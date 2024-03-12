<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu_model extends MY_Model
{
    use MY_Tables;

    public function __construct()
    {
        $this->_tabel = $this->_table_admins_ms_menus;
        parent::__construct();
    }

    public function _getAccesscontrols()
    {
        $this->_ci->load->model("accesscontrols/Accesscontrols_model","accesscontrols_model");
        return $this->_ci->accesscontrols_model;
    }

    public function getMenu()
    {
        $this->db->select('a.id,
            a.menu_name,
            a.parent,
            a.order,
            b.view,
            b.insert,
            b.update,
            b.delete,
            b.import,
            b.export',FALSE);
        $this->db->join("{$this->_table_admins_ms_accesscontrols} b","b.{$this->_tabel}_id = a.id","inner");
        $this->db->where(array("a.status" => 1));
        $this->db->order_by("a.id asc");
        return $this->db->get("{$this->_tabel} a")->result_array();
    }

    public function menu()
	{
		try {
            $menu = $this->getMenu();
            if(!$menu || (is_array($menu) && count($menu) == 0)){
                throw new Exception("Error Processing Request", 1);
                
            }       
            $data = [];
            foreach($menu as $ky => $val){
                $cari = array_search($val['parent'],array_column($data,'id'));
                if($cari === false){
                    $data[] = array(
                        'id' => $val['id'],
                        'menu_name' => $val['menu_name'],
                        'parent' => 0,
                        'view' => $val['view'],
                        'insert' => $val['insert'],
                        'update' => $val['update'],
                        'delete' => $val['delete'],
                        'import' => $val['import'],
                        'export' => $val['export'],
                    );
                }else{
                    $data[$cari]['child'][] = array(
                        'id' => $val['id'],
                        'menu_name' => $val['menu_name'],
                        'parent' => $val['parent'],
                        'view' => $val['view'],
                        'insert' => $val['insert'],
                        'update' => $val['update'],
                        'delete' => $val['delete'],
                        'import' => $val['import'],
                        'export' => $val['export'],
                    );
                }
            }

            return $data;
        } catch (Exception $e) {
            return false;
        }
	}

}