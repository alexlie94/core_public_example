<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Rolepermissions extends MY_Customers
{

	public function __construct()
	{
		$this->_function_except = ['show', 'status', 'process', 'paging'];
		parent::__construct();
	}

	public function index()
	{
		$this->template->title('Role & Permissions');
		$this->setTitlePage('Role & Permissions');
		$this->assetsBuild(['datatables']);
		$data = [
			'searchBy' => ['role_name' => 'Role Name'],
		];

		$header_table = array('no', 'role name', 'users', 'status', "");

		$this->setTable($header_table, true);
		$this->setJs('rolepermissions');
		$this->template->build('v_show', $data);
	}

	public function show()
	{
		isAjaxRequestWithPost();
		$this->function_access('view');
		$this->_custom_button_on_table = array(
			array(
				'button' => 'update',
				'type' => 'modal',
				'fullscreen' => true,
				'url' => base_url() . "rolepermissions/update/$1",
			),
			array(
				'button' => 'delete',
				'type' => 'confirm',
				'title' => 'Item',
				'confirm' => 'Are you sure you want to delete this item ?',
				'url' => base_url() . "rolepermissions/delete/$1",
			),
			array(
				'button' => 'status',
				'type' => 'confirm',
				'title' => 'Status',
				'confirm' => 'Are you sure you want to change status this item ?',
				'url' => base_url() . "rolepermissions/status/$1",
			)
		);

		$button = $this->setButtonOnTable();

		echo $this->rolepermissions_model->show($button);
	}

	public function status($id = null)
	{
		isAjaxRequestWithPost();
		$this->function_access('update');
		$response = array('text' => 'Successfully change status item', 'success' => true);
		try {
			$process = $this->rolepermissions_model->changeStatus($id);
			if ($process !== true) {
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
			$process = $this->rolepermissions_model->deleteData($id);
			if ($process !== true) {
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

	public function insert()
	{
		isAjaxRequestWithPost();
		$content = array(
			'url_form' => base_url() . "rolepermissions/process",
			'data' => $this->rolepermissions_model->_getMenu()->menu(),
		);

		$data = array(
			'title_modal' => 'Add New Role',
			'content' => $this->load->view($this->_v_form_permission, $content, true),
			'buttonCloseID' => 'btnCloseModalFullscreen',
		);
		$html = $this->load->view($this->_v_modal, $data, true);

		echo json_encode(array('html' => $html));
		exit();
	}

	public function update($id)
	{
		isAjaxRequestWithPost();
		try {
			if ($id == null) {
				throw new Exception("Failed to request Edit", 1);
			}

			$get = $this->rolepermissions_model->get(array('id' => $id));
			if (!$get) {
				throw new Exception("Error Processing Request", 1);
			}

			$content = array(
				'url_form' => base_url() . "rolepermissions/process",
				'data' => $this->rolepermissions_model->getMenuUpdate($get->id),
				'id' => $get->id,
				'role_name' => $get->role_name,
				'buttonCloseID' => 'btnCloseModalFullscreen',
			);

			$data = array(
				'title_modal' => 'Edit Role',
				'content' => $this->load->view($this->_v_form_permission, $content, true),
			);

			$html = $this->load->view($this->_v_modal, $data, true);

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

	public function process()
	{
		isAjaxRequestWithPost();
		if (!empty($this->input->post('id'))) {
			$this->function_access('update');
		} else {
			$this->function_access('insert');
		}

		$className = $this->router->class;
		$response = $this->rolepermissions_model->save($className);
		echo json_encode($response);
		exit();
	}

}