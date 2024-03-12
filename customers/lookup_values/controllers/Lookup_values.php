<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Lookup_values extends MY_Owner
{

	public function __construct()
	{

		$this->_function_except = ['account', 'settings', 'show', 'process', 'status', 'paging', 'upload_data'];
		parent::__construct();
	}

	public function index()
	{
		$this->template->title('Lookup Values');
		$this->setTitlePage('Lookup Values');
		$this->assetsBuild(['datatables']);
		$this->_custom_button_header = array(
			array(
				'button' => 'insert',
				'label' => 'Add New Lookup Values',
				'type' => 'modal',
				'url' => base_url() . "lookup_values/insert",
			)
		);

		$header_table = array('no', 'lookup code', 'lookup name', 'lookup config', "");

		$this->setTable($header_table, true);
		$this->setJs('lookup_values');
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
				'url' => base_url() . "lookup_values/update/$1",
			),
			array(
				'button' => 'delete',
				'type' => 'confirm',
				'title' => 'Item',
				'confirm' => 'Are you sure you want to delete this item ?',
				'url' => base_url() . "lookup_values/delete/$1",
			)
		);

		$button = $this->setButtonOnTable();

		echo $this->lookup_values_model->show($button);
	}

	public function insert()
	{
		isAjaxRequestWithPost();
		$data = array(
			'title_modal' => 'Add New Channel',
			'url_form' => base_url() . "lookup_values/process",
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

		$response = $this->lookup_values_model->save();
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

			$get = $this->lookup_values_model->getItems($id);

			if (!is_array($get)) {
				throw new Exception($get, 1);
			}

			$data = array(
				'title_modal' => 'Edit Channels',
				'url_form' => base_url() . "lookup_values/process",
				'form' => $this->load->view('v_form2', $get, true),
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
			$process = $this->lookup_values_model->deleteData($id);

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

	public function upload_data()
	{
		$getData = json_decode($_POST['dataUpload'], true);

		$processing_data = $this->lookup_values_model->process_data($getData);

		echo json_encode($processing_data);
	}
}
