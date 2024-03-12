<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Application_update extends MY_Owner
{

	public function __construct()
	{
		$this->_function_except = ['account', 'settings', 'show', 'process', 'status', 'paging'];
		parent::__construct();
	}

	public function index()
	{
		$this->template->title('Application Update');
		$this->setTitlePage('Application Update');
		$this->assetsBuild(['datatables', 'ckeditor']);
		$this->_custom_button_header = array(
			array(
				'button' => 'insert',
				'label' => 'Add New Application Update',
				'type' => 'modal',
				'fullscreen' => TRUE,
				'url' => base_url() . "application_update/insert",
			)
		);

		$header_table = array(
			'no',
			'title',
			'launch date',
			'status',
			""
		);

		$this->setTable($header_table, true);
		$this->setJs('application_update');
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
				'fullscreen' => TRUE,
				'url' => base_url() . "application_update/update/$1",
			),
			array(
				'button' => 'delete',
				'type' => 'confirm',
				'title' => 'Item',
				'confirm' => 'Are you sure you want to delete this item ?',
				'url' => base_url() . "application_update/delete/$1",
			)
		);

		$button = $this->setButtonOnTable();

		echo $this->application_update_model->show($button);
	}

	public function insert()
	{
		isAjaxRequestWithPost();
		$data = array(
			'title_modal' => 'Add New Application Update',
			'url_form' => base_url() . "application_update/process",
			'form' => $this->load->view('v_form', '', true),
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

		$response = $this->application_update_model->save();
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

			$dataItems = $this->application_update_model->getItems($id);

			if (!is_array($dataItems)) {
				throw new Exception($dataItems, 1);
			}

			$data = array(
				'title_modal' => 'Edit Application Update',
				'url_form' => base_url() . "application_update/process",
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

	public function delete($id = null)
	{
		isAjaxRequestWithPost();
		$response = array('text' => 'Successfully delete item', 'success' => true);

		try {
			$process = $this->application_update_model->deleteData($id);

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
}