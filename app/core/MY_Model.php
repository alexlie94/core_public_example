<?php
defined('BASEPATH') or exit('No direct script access allowed');
class MY_Model extends CI_Model
{
	// Nama tabel database yang akan digunakan.
	protected $_tabel = '';
	protected $_ci;
	protected $_updated_by;
	protected $_created_by;
	protected $_user_id;
	protected $_session_email;
	protected $_session_id;

	public function __construct()
	{
		$this->_ci = &get_instance();
		$user_id = !empty($this->session->userdata('x-id-user')) ? $this->session->userdata('x-id-user') : null;
		$this->_created_by = $this->_updated_by = ($user_id != null ? $user_id : 'N');
		$this->_user_id = $user_id;
		$this->_session_email = $this->session->userdata('email_admin');
		$this->_session_id    = $this->session->userdata('session_id_admin');
		parent::__construct();
	}

	public function _searchDefaultDatatables($field = [])
	{
		$search = !empty($this->input->post('search')) ? $this->input->post('search') : [];
		if (isset($search['value']) && strlen(trim($search['value'])) > 0  && is_array($field) && count($field) > 0) {
			$this->datatables->like($field[0], $search['value']);
			for ($i = 1; $i < count($field); $i++) {
				$this->datatables->or_like($field[$i], $search['value']);
			}
		}
	}

	// Ambil 1 record, menerima beberapa pola "where"
	public function get()
	{
		// Mendapatkan argumen yang dilewatkan ke fungsi ini.
		$args = func_get_args();

		// $this->db->where('name', $name);
		// $this->db->where('name !=', $name);
		if (count($args) > 1) {
			$this->db->where($args[0], $args[1]);
		}

		// $this->db->where(3);
		elseif (count($args) == 1 && is_numeric($args[0])) {
			$this->db->where('id', $args[0]);
		}

		// $this->db->where(array('id' => $id, 'nama' => $nama))
		// $this->db->where("name='Joe' AND status='boss' OR status='active'")
		else {
			$this->db->where($args[0]);
		}

		// Memastikan hanya mengembalikan 1 record.
		$this->db->limit(1);

		$this->db->where('deleted_at IS NULL', null, false);

		// Mengembalikan hasil query.
		return $this->db->get($this->_tabel)->row();
	}

	// Ambil semua record, menerima beberapa pola "where".
	public function get_all()
	{
		// Mendapatkan argumen yang dilewatkan ke fungsi ini.
		$args = func_get_args();

		// Dipanggil tanpa prameter.
		if (!count($args) > 0) {
			$this->db->where('deleted_at IS NULL', null, false);
			return $this->db->get($this->_tabel)->result();
		}

		// $this->db->where('name', $name);
		// $this->db->where('name !=', $name);
		elseif (count($args) > 1) {
			$this->db->where($args[0], $args[1]);
		}

		// $this->db->where(3);
		elseif (count($args) == 1 && is_numeric($args[0])) {
			$this->db->where('id', $args[0]);
		}

		// $this->db->where(array('id' => $id, 'nama' => $nama))
		// $this->db->where("name='Joe' AND status='boss' OR status='active'")
		elseif ((count($args) == 1) && (is_array($args[0]) || is_string($args[0]))) {
			$this->db->where($args[0]);
		}

		$this->db->where('deleted_at IS NULL', null, false);

		// Mengembalikan semua record hasil query.
		return $this->db->get($this->_tabel)->result();
	}

	// Mengurutkan data berdasarkan kolom....dengan urutan....
	public function sort($field = '', $order = '')
	{
		if ($field && $order) {
			$this->db->order_by($field, $order);
		} else {
			$this->db->order_by('id', 'asc');
		}
		return $this;
	}

	// Ambil jumlah seluruh record get_all.
	public function get_all_num_rows()
	{
		$this->db->where('deleted_at IS NULL', null, false);
		return $this->db->get($this->_tabel)->num_rows();
	}

	public function get_num_rows()
	{
		// Mendapatkan argumen yang dilewatkan ke fungsi ini.
		$args = func_get_args();

		// $this->db->where('name', $name);
		// $this->db->where('name !=', $name);
		if (count($args) > 1) {
			$this->db->where($args[0], $args[1]);
		}

		// $this->db->where(3);
		elseif (count($args) == 1 && is_numeric($args[0])) {
			$this->db->where('id', $args[0]);
		}

		// $this->db->where(array('id' => $id, 'nama' => $nama))
		// $this->db->where("name='Joe' AND status='boss' OR status='active'")
		else {
			$this->db->where($args[0]);
		}

		$this->db->where('deleted_at IS NULL', null, false);

		return $this->db->get($this->_tabel)->num_rows();
	}

	// Insert.
	public function insert($data)
	{
		if (!is_object($data)) {
			$data = (object)$data;
		}
		$data->created_by = $data->updated_by = $this->_created_by;
		$this->db->set($data);
		$this->db->insert($this->_tabel);
		return $this->db->insert_id();
	}

	public function insert_custom($data)
	{
		if (!is_object($data)) {
			$data = (object)$data;
		}
		$data->updated_by = $this->_created_by;
		$this->db->set($data);
		$this->db->insert($this->_tabel);
		return $this->db->insert_id();
	}

	public function insert_batch($data)
	{
		for ($i = 0; $i < count($data); $i++) {
			$data[$i]['created_by'] = $data[$i]['updated_by'] =  $this->_created_by;
		}
		return $this->db->insert_batch($this->_tabel, $data);
	}

	public function insertCustom($data, $tableName = '')
	{
		if (!is_object($data)) {
			$data = (object)$data;
		}

		$data->created_by = $data->updated_by = $this->_created_by;
		$this->db->set($data);
		$this->db->insert($tableName);

		return $this->db->insert_id();
	}

	// Update, menerima beberapa pola "where"
	public function update()
	{
		// Mendapatkan argumen yang dilewatkan ke fungsi ini.
		$args = func_get_args();

		// update('name', $name, $data);
		// update('name !=', $name, $data);
		if (count($args) > 2) {
			$this->db->where($args[0], $args[1]);
			if (!is_object($args[2])) {
				$args[2] = (object)$args[2];
			}
			$data = $args[2];
		}

		// update(3, $data);
		elseif (count($args) == 2 && is_numeric($args[0])) {
			$this->db->where('id', $args[0]);
			if (!is_object($args[1])) {
				$args[1] = (object)$args[1];
			}
			$data = $args[1];
		}

		// update(array('id' => $id, 'nama' => $nama), $data)
		// update("name='Joe' AND status='boss' OR status='active'", $data)
		else {
			$this->db->where($args[0]);
			if (!is_object($args[1])) {
				$args[1] = (object)$args[1];
			}
			$data = $args[1];
		}

		$data->updated_by = $this->_updated_by;

		// Pastikan hanya 1 record yang diupdate.
		$this->db->limit(1);

		// Update
		return $this->db->update($this->_tabel, $data);
	}

	// Hapus data berdasarkan id yang diberikan.
	public function delete($id)
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
		if (!is_array($id)) {
			$this->db->limit(1);
		}
		$this->db->delete($this->_tabel);

		if ($this->db->affected_rows() > 0) {
			return true;
		}
		return false;
	}

	public function softDelete($id)
	{
		$check = $this->checkForeignKey($id);
		if (!$check) {
			return $check;
		}

		if (is_numeric($id)) {
			$this->db->where('id', $id);
		} else {
			if (is_array($id)) {
				$this->db->where($id);
			} else {
				return false;
			}
		}
		$data = array(
			'deleted_at' => date('Y-m-d H:i:s')
		);

		$data['updated_by'] = $this->_updated_by;

		if (!is_array($id)) {
			$this->db->limit(1);
		}
		return $this->db->update($this->_tabel, $data);
	}

	public function softDeleteCustom($tableName, $field, $id)
	{

		// $check = $this->checkForeignKey($id, $tableName);
		// if (!$check) {
		// 	return $check;
		// }

		if (is_numeric($id)) {
			$this->db->where($field, $id);
		} else {
			if (is_array($id)) {
				$this->db->where($id);
			} else {
				return false;
			}
		}

		$data = array(
			'deleted_at' => date('Y-m-d H:i:s')
		);

		$data['updated_by'] = $this->_updated_by;

		// if (!is_array($id)) {
		// 	$this->db->limit(1);
		// }
		return $this->db->update($tableName, $data);
	}

	protected function generate_csrf()
	{
		return $this->security->get_csrf_hash();
	}

	public function get_all_not_in()
	{
		// Mendapatkan argumen yang dilewatkan ke fungsi ini.
		$args = func_get_args();

		// $this->db->where('name', $name);
		// $this->db->where('name !=', $name);
		if (count($args) > 1) {
			$this->db->where_not_in($args[0], $args[1]);
		}

		$this->db->where('deleted_at IS NULL', null, false);

		// Mengembalikan semua record hasil query.
		return $this->db->get($this->_tabel)->result();
	}

	public function _getApp()
	{
		$this->_ci->load->model('app/App_model', 'app_model');
		return $this->_ci->app_model;
	}

	public function checkForeignKey($id, $tableName = "")
	{
		$db_name = $this->db->database;
		if ($tableName == "") {
			$tableName = $this->_tabel;
		}

		$query = "SELECT 
				TABLE_NAME,COLUMN_NAME,CONSTRAINT_NAME, REFERENCED_TABLE_NAME,REFERENCED_COLUMN_NAME
			FROM
				INFORMATION_SCHEMA.KEY_COLUMN_USAGE
			WHERE
				REFERENCED_TABLE_SCHEMA = '{$db_name}' AND
				REFERENCED_TABLE_NAME = '{$tableName}'";

		$data = $this->db->query($query)->result_array();
		foreach ($data as $ky => $val) {
			$table_name = $val['TABLE_NAME'];
			$column = $val['COLUMN_NAME'];

			//search 
			$this->db->where(array($column => $id));
			$this->db->where('deleted_at IS NULL', null, false);
			$get = $this->db->get("{$table_name}")->num_rows();
			if ($get && $get > 0) {
				return false;
			}
		}
		return true;
	}

	public function getWithoutDeleteNull()
	{
		// Mendapatkan argumen yang dilewatkan ke fungsi ini.
		$args = func_get_args();

		// $this->db->where('name', $name);
		// $this->db->where('name !=', $name);
		if (count($args) > 1) {
			$this->db->where($args[0], $args[1]);
		}

		// $this->db->where(3);
		elseif (count($args) == 1 && is_numeric($args[0])) {
			$this->db->where('id', $args[0]);
		}

		// $this->db->where(array('id' => $id, 'nama' => $nama))
		// $this->db->where("name='Joe' AND status='boss' OR status='active'")
		else {
			$this->db->where($args[0]);
		}

		// Memastikan hanya mengembalikan 1 record.
		$this->db->limit(1);

		// Mengembalikan hasil query.
		return $this->db->get($this->_tabel)->row();
	}

	public function softDeleteWithoutForeign($id)
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
		$data = array(
			'deleted_at' => date('Y-m-d H:i:s')
		);

		$data['updated_by'] = $this->_updated_by;

		if (!is_array($id)) {
			$this->db->limit(1);
		}
		return $this->db->update($this->_tabel, $data);
	}

	public function get_all_without_delete()
	{
		// Mendapatkan argumen yang dilewatkan ke fungsi ini.
		$args = func_get_args();

		// Dipanggil tanpa prameter.
		if (!count($args) > 0) {
			return $this->db->get($this->_tabel)->result();
		}

		// $this->db->where('name', $name);
		// $this->db->where('name !=', $name);
		elseif (count($args) > 1) {
			$this->db->where($args[0], $args[1]);
		}

		// $this->db->where(3);
		elseif (count($args) == 1 && is_numeric($args[0])) {
			$this->db->where('id', $args[0]);
		}

		// $this->db->where(array('id' => $id, 'nama' => $nama))
		// $this->db->where("name='Joe' AND status='boss' OR status='active'")
		elseif ((count($args) == 1) && (is_array($args[0]) || is_string($args[0]))) {
			$this->db->where($args[0]);
		}

		// Mengembalikan semua record hasil query.
		return $this->db->get($this->_tabel)->result();
	}

	protected function activityLogs($data = [])
	{
		$table = 'users_ms_activity_logs';
		$this->db->insert($table, $data);
		return;
	}

	protected function errorLogs($data = [])
	{
		$table = 'users_ms_error_logs';
		$this->db->insert($table, $data);
		return;
	}
}
