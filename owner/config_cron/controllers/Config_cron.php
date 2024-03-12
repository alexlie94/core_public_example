<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Config_cron extends MY_Owner
{

	public function __construct()
	{
		$this->_function_except = ['account', 'settings', 'show', 'process', 'status', 'paging', 'showImage', 'showIcon'];
		parent::__construct();
	}

	public function index()
	{
		$this->template->title('Config Cron');
		$this->setTitlePage('Config Cron');
		$this->assetsBuild(['datatables']);
		$this->_custom_button_header = array(
			array(
				'button' => 'insert',
				'label' => 'Add New Config Cron',
				'type' => 'modal',
				'url' => base_url() . "config_cron/insert",
			)
		);

		$header_table = array(
			'no',
			'cron controller',
			'cron description',
			'status',
			""
		);

		$this->setTable($header_table, true);
		$this->setJs('config_cron');
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
				'url' => base_url() . "config_cron/update/$1",
			),
			array(
				'button' => 'delete',
				'type' => 'confirm',
				'title' => 'Item',
				'confirm' => 'Are you sure you want to delete this item ?',
				'url' => base_url() . "config_cron/delete/$1",
			)
		);

		$button = $this->setButtonOnTable();

		echo $this->config_cron_model->show($button);
	}

	public function insert()
	{
		isAjaxRequestWithPost();
		$data = array(
			'title_modal' => 'Add New Config Cron',
			'url_form' => base_url() . "config_cron/process",
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

		$response = $this->config_cron_model->save();
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

			$dataItems = $this->config_cron_model->getItems($id);

			if (!is_array($dataItems)) {
				throw new Exception($dataItems, 1);
			}

			$data = array(
				'title_modal' => 'Edit Config Cron',
				'url_form' => base_url() . "config_cron/process",
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
			$process = $this->config_cron_model->deleteData($id);

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
