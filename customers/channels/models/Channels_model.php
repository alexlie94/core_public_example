<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Channels_model extends MY_ModelCustomer
{
	use MY_Tables;

	public function __construct()
	{
		$this->_tabel = $this->_table_users_ms_channels;
		parent::__construct();
	}

	public function show($button = '')
	{
		$this->datatables->select(
			"a.id,
            b.source_name,
            a.channel_name,
            b.status as status_source,
            a.status as status_channel",
			false
		);
		$this->datatables->from("{$this->_table_users_ms_channels} a");
		$this->datatables->join("{$this->_table_admins_ms_sources} b", "b.id = a.admins_ms_sources_id", "inner");
		$this->datatables->where(["a.{$this->_table_users_ms_companys}_id" => $this->_users_ms_companys_id]);
		$this->datatables->where("a.deleted_at is null", null, false);
		$this->datatables->where("b.deleted_at is null", null, false);
		$this->datatables->order_by('a.updated_at desc');

		$button = "<button class=\"btn btn-outline btn-outline-dashed btn-outline-success btn-active-light-success hover-scale btn-sm fw-bold me-2 mb-2 btnEdit\" data-type=\"modal\" data-fullscreenmodal=\"0\" data-url=\"" . base_url("channels/update/$1") . "\" data-id =\"$1\"><i class=\"bi bi-pencil-square fs-4 me-2\"></i>Edit</button>";
		$button .= "<button class=\"btn btn-outline btn-outline-dashed btn-outline-danger btn-active-light-danger hover-scale btn-sm fw-bold mb-2\" data-url=\"" . base_url("channels/delete/$1") . "\" data-type=\"confirm\" data-textconfirm=\"Are you sure you want to delete this item ?\" data-title=\"Item\" data-id =\"$1\"><i class=\"bi bi-trash fs-4 me-2\"></i>Delete</button>";

		$this->datatables->add_column('action', $button, 'id');

		$filters = !empty($this->input->post('filters')) ? $this->input->post('filters') : false;

		if ($filters !== false && is_array($filters)) {

			$getSearchBy = '';
			$setValue = '';

			foreach ($filters as $val) {

				if ($val['name'] == 'searchBy') {
					$getSearchBy .= $val['value'];
				}

				if ($val['name'] == 'searchValue') {
					$setValue .= $val['value'];
				}
			}

			if (!empty($getSearchBy)) {
				switch ($getSearchBy) {
					case 'source_name':
						$this->datatables->like('b.source_name', $setValue);
						break;
					case 'channel_name':
						$this->datatables->like('a.channel_name', $setValue);
						break;
					default:
						break;
				}
			}
		}

		$fieldSearch = [
			"a.id",
			"b.source_name",
			"a.channel_name",
			"a.status"
		];
		$this->_searchDefaultDatatables($fieldSearch);
		return $this->datatables->generate();
	}

	private function _validate()
	{
		$response = array('success' => false, 'validate' => true, 'messages' => []);
		$response['type'] = !empty($this->input->post('id')) ? 'update' : 'insert';

		$role = array('trim', 'required', 'xss_clean');

		$this->form_validation->set_rules('channel_name', 'Channel Name', $role);
		$this->form_validation->set_rules('source_id', 'Source Name', $role);
		$this->form_validation->set_rules('status', 'Status', $role);

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
				throw new Exception("Error Processing Request", 1);
			}

			$id = clearInput($this->input->post('id'));
			$channel = clearInput($this->input->post('channel_name'));
			$source = clearInput($this->input->post('source_id'));
			$status = clearInput($this->input->post('status'));

			$data_array = array(
				'admins_ms_sources_id' => $source,
				'channel_name' => $channel,
				'status' => $status
			);

			if (empty($id)) {

				$process = $this->insert($data_array);

				if (!$process) {
					$response['messages'] = 'Failed Insert data channel';
					throw new Exception;
				}

				$response['messages'] = "Successfully Insert Data Channel";
			} else {
				$data = $this->get(array('id' => $id));

				if (!$data) {
					$response['messages'] = 'Data update invalid';
					throw new Exception;
				}

				$data_update = array(
					'admins_ms_sources_id' => $source,
					'channel_name' => $channel,
					'status' => $status
				);

				$process = $this->update(array('id' => $id), $data_update);

				if (!$process) {
					$response['messages'] = 'Failed update data user';
					throw new Exception;
				};

				$response['messages'] = 'Successfully Update Data Channel';
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
			$get = $this->get(['id' => $id]);

			$table = [
				'id' => $get->id,
				'admins_ms_sources_id' => $get->admins_ms_sources_id,
				'channel_name' => $get->channel_name,
				'status' => $get->status,
			];

			return $table;
		} catch (Exception $e) {
			return $e->getMessage();
		}
	}

	public function getSource()
	{
		try {
			$this->db->select('t0.*');
			$this->db->from("{$this->_table_admins_ms_sources} t0");
			$this->db->join("{$this->_table_users_ms_authenticate_channels} t1", "t1.sources_id = t0.id");
			$this->db->where(["t1.{$this->_table_users_ms_companys}_id" => $this->_users_ms_companys_id]);
			$this->db->where('t0.deleted_at IS NULL');
			$this->db->where('t0.status', 1);
			$this->db->group_by("t0.id");
			return $this->db->get()->result_array();
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
}
