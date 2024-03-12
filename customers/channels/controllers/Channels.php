<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Channels extends MY_Customers
{

	public function __construct()
	{
		$this->_function_except = ['account', 'settings', 'show', 'process', 'status', 'paging'];
		parent::__construct();
	}

	public function index()
	{
		$this->template->title('Data Channels');
		$this->setTitlePage('Data Channels');
		$this->assetsBuild(['datatables']);
		$data = [
			'searchBy' => ['source_name' => 'Source Name', 'channel_name' => 'Channel Name'],
		];

		$header_table = array('no', 'source', 'channel', 'status', "");

		$this->setTable($header_table, true);
		$this->setJs('channels');
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
				'url' => base_url() . "channels/update/$1",
			),
			array(
				'button' => 'delete',
				'type' => 'confirm',
				'title' => 'Item',
				'confirm' => 'Are you sure you want to delete this item ?',
				'url' => base_url() . "channels/delete/$1",
			)
		);

		$button = $this->setButtonOnTable();

		echo $this->channels_model->show($button);
	}

	public function insert()
	{
		isAjaxRequestWithPost();
		$get['sources'] = $this->channels_model->getSource();
		$data = array(
			'title_modal' => 'Add New Channel',
			'url_form' => base_url() . "channels/process",
			'form' => $this->load->view('v_form', $get, true),
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

		$response = $this->channels_model->save();
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

			$dataItems = $this->channels_model->getItems($id);
			$dataItems['sources'] = $this->channels_model->getSource();

			if (!is_array($dataItems)) {
				throw new Exception($dataItems, 1);
			}

			$data = array(
				'title_modal' => 'Edit Channel',
				'url_form' => base_url() . "channels/process",
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
			$process = $this->channels_model->deleteData($id);

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
