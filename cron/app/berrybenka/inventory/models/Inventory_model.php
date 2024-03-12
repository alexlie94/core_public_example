<?php defined('BASEPATH') or exit('No direct script access allowed');

class Inventory_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function get($where, $table)
	{
		$this->db->where($where);

		// Memastikan hanya mengembalikan 1 record.
		$this->db->limit(1);

		// Mengembalikan hasil query.
		return $this->db->get($table)->row();
	}

	public function getLastModified($table, $brand_id = null)
	{
		if ($brand_id == null) {
			return false;
		}

		$where = array(
			'users_ms_brands_id' => $brand_id
		);

		$this->db->select('product_last_modified AS last_modified');
		$this->db->where($where);
		$this->db->limit(1);
		$this->db->order_by('product_last_modified DESC');

		return $this->db->get($table)->row();
	}

	public function processDataProduct($data, &$msg)
	{
		$this->db->trans_begin();
		try {
			$this->db->trans_commit();
			$msg = 'Berhasil di proses';
			return true;
		} catch (Exception $e) {
			$this->db->trans_rollback();
			$msg = $e->getMessage();
			return false;
		}
	}
}