<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Access_model extends MY_ModelCustomer
{
    use MY_Tables;

    public function __construct()
    {
        $this->_tabel = $this->_table_users_ms_access;
        parent::__construct();
    }

    public function getData()
    {
        $args = func_get_args();
        $this->db->where($args[0]);
        $this->db->limit(1);
		$this->db->where('deleted_at IS NULL', null, false);

		// Mengembalikan hasil query.
		return $this->db->get($this->_tabel)->row();
    }

    public function insertData($data)
    {
        if (!is_object($data)) {
			$data = (object)$data;
		}
		$data->created_by = $data->updated_by = $this->_created_by;
		$this->db->set($data);
		$this->db->insert($this->_tabel);
		return $this->db->insert_id();
    }

    public function updateData()
    {
        $args = func_get_args();
        $this->db->where($args[0]);
        if (!is_object($args[1])) {
            $args[1] = (object)$args[1];
        }
        $data = $args[1];

        $data->updated_by = $this->_updated_by;

		// Pastikan hanya 1 record yang diupdate.
		$this->db->limit(1);

		// Update
		return $this->db->update($this->_tabel, $data);
    }

    public function deleteData($id)
    {
        if (is_numeric($id)) {
			$this->db->where('id', $id);
		} else {
			if (is_array($id)) {
				$this->db->where($id);
			} else {
				return false;
			}
		}

		if(!is_array($id)){
			$this->db->limit(1);
		}
		$this->db->delete($this->_tabel);

		if ($this->db->affected_rows() > 0) {
			return true;
		}
		return false;
    }

}