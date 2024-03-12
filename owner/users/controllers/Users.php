<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Users extends MY_Owner
{

	public function __construct()
	{
		$this->_function_except = ['account', 'settings', 'show', 'process', 'status', 'paging'];
		parent::__construct();
	}

	public function index()
	{
		$this->template->title('User Admins');
		$this->setTitlePage('User Admins');
		$this->assetsBuild(['datatables']);
		$this->_custom_button_header = array(
			array(
				'button' => 'insert',
				'label' => 'Add New User Admins',
				'type' => 'modal',
				'url' => base_url() . "users/insert",
			),
			array(
				'button' => 'import',
				'label' => 'Import Data User Admins',
				'type' => 'modal',
				'url' => base_url() . "users/import",
			),
			array(
				'button' => 'export',
				'label' => 'Export Data User Admins',
				'type' => 'modal',
				'url' => base_url() . "users/export",
			)
		);

		$dataRoleAdmin = $this->users_model->_getRole()->get_all();
		$roleAdmins = [];
		foreach ($dataRoleAdmin as $ky => $val) {
			$roleAdmins[$val->id] = $val->role_name;
		}

		$cardSearch = array(
			array(
				'label' => 'Fullname',
				'type' => 'input',
				'name' => 'fullname_filter',
			),
			array(
				'label' => 'Email',
				'type' => 'input',
				'name' => 'email_filter',
			),
			array(
				'label' => 'Status',
				'type' => 'checkbox', //input, checkbox,dateRange,date
				'name' => 'inputStatus_filter',
				'value' => array(
					'enable' => 'Enabled',
					'disable' => 'Disable'
				)

			),
			array(
				'label' => 'Role Name',
				'type' => 'select-multiple',
				'library' => 'select2',
				'name' => 'rolename_filter',
				'value' => $roleAdmins,
			),
		);

		$this->cardSearch($cardSearch);

		$header_table = array('no', 'fullname', 'email', 'status', 'role name', "");

		$this->setTable($header_table, true);
		$this->setJs('user_admins');
		$this->template->build($this->_v_show);
	}

    public function show()
	{
		isAjaxRequestWithPost();
		$this->function_access('view');
		$this->_custom_button_on_table = array(
			array(
				'button' => 'update',
				'type' => 'modal',
				'url' => base_url() . "users/update/$1",
			),
			array(
				'button' => 'delete',
				'type' => 'confirm',
				'title' => 'Item',
				'confirm' => 'Are you sure you want to delete this item ?',
				'url' => base_url() . "users/delete/$1",
			),
			array(
				'button' => 'status',
				'type' => 'confirm',
				'title' => 'Status',
				'confirm' => 'Are you sure you want to change status this item ?',
				'url' => base_url() . "users/status/$1",
			)
		);

		$button = $this->setButtonOnTable();

		echo $this->users_model->show($button);
	}

	public function insert()
	{
		isAjaxRequestWithPost();
		$checked['checked'] = 'enabled';
		$checked['role'] = $this->users_model->_getRole()->get_all(array('status' => 1));
		$data = array(
			'title_modal' => 'Add New User Admin',
			'url_form' => base_url() . "users/process",
			'form' => $this->load->view('v_form', $checked, true),
		);
		$html = $this->load->view($this->_v_form_modal, $data, true);

		echo json_encode(array('html' => $html));
		exit();
	}

	public function process()
	{
		isAjaxRequestWithPost();
		if (!empty($this->input->post('id'))) {
			$this->function_access('update');
		} else {
			$this->function_access('insert');
		}

		$response = $this->users_model->save();
		echo json_encode($response);
		exit();
	}

	public function update($id)
	{
		isAjaxRequestWithPost();
		try {
			if ($id == null) {
				throw new Exception("Failed to request Edit", 1);
			}

			$dataItems = $this->users_model->getItems($id,$this->_session_email);
			if(!is_array($dataItems)){
				throw new Exception($dataItems, 1);
				
			}

			$data = array(
				'title_modal' => 'Edit User Admin',
				'url_form' => base_url() . "users/process",
				'form' => $this->load->view('v_form', $dataItems, true),
			);
			$html = $this->load->view($this->_v_form_modal, $data, true);
			$response['html'] = $html;
			echo json_encode($response);
			exit();
		} catch (Exception $e) {
			$response['failed'] = true;
			$response['message'] = $e->getMessage();
			echo json_encode($response);
			exit();
		}
	}

	public function status($id = null)
	{
		isAjaxRequestWithPost();
		$this->function_access('update');
		$response = array('text' => 'Successfully change status item', 'success' => true);
		try {
			$process = $this->users_model->changeStatus($id);
			if($process !== true){
				throw new Exception($process, 1);
				
			}
			echo json_encode($response);
			exit();
		} catch (Exception $e) {
			$response['text'] = $e->getMessage();
			$response['success'] = false;
			echo json_encode($response);
			exit();
		}
	}

	public function delete($id = null)
	{
		isAjaxRequestWithPost();
		$response = array('text' => 'Successfully delete item', 'success' => true);
		try {
			$process = $this->users_model->deleteData($id);
			if($process !== true){
				throw new Exception($process, 1);
				
			}
			echo json_encode($response);
			exit();
		} catch (Exception $e) {
			$response['text'] = $e->getMessage();
			$response['success'] = false;
			echo json_encode($response);
			exit();
		}
	}
}
