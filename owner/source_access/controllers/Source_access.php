<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Source_access extends MY_Owner
{

	public function __construct()
	{
		$this->_function_except = ['account', 'settings', 'show', 'process', 'status', 'paging', 'listEndpoints', 'getDataDetail', 'company_sources'];
		parent::__construct();
	}

	public function index()
	{
		$this->template->title('Source Access');
		$this->setTitlePage('Source Access');
		$this->assetsBuild(['datatables', 'repeater']);
		$this->_custom_button_header = array(
			array(
				'button' => 'insert',
				'label' => 'Add New Source Access',
				'type' => 'modal',
				'url' => base_url() . "source_access/insert",
			)
		);

		$header_table = array(
			'no',
			'company',
			'source',
			'status',
			""
		);

		$this->setTable($header_table, true);
		$this->setJs('source_access');

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
				'url' => base_url() . "source_access/update/$1",
			),
			array(
				'button' => 'delete',
				'type' => 'confirm',
				'title' => 'Item',
				'confirm' => 'Are you sure you want to delete this item ?',
				'url' => base_url() . "source_access/delete/$1",
			)
		);

		$button = $this->setButtonOnTable();

		echo $this->source_access_model->show($button);
	}

	public function insert()
	{
		isAjaxRequestWithPost();
		$get['company'] = $this->source_access_model->getCompany();
		// $get['source'] = $this->source_access_model->getSource();
		$data = array(
			'title_modal' => 'Add New Source Access',
			'url_form' => base_url() . "source_access/process",
			'form' => $this->load->view('v_form', $get, true),
		);
		$html = $this->load->view($this->_v_form_modal, $data, true);

		echo json_encode(array('html' => $html));
		exit();
	}

	public function listEndpoints($id)
	{
		$data = $this->source_access_model->getEndpoints($id);
		echo $data;
	}

	public function getDataDetail($id)
	{
		$data = $this->source_access_model->getDataDetail($id);
		echo $data;
	}

	public function process()
	{
		isAjaxRequestWithPost();
		if (!empty($this->input->post('id'))) {
			$this->function_access('update');
		} else {
			$this->function_access('insert');
		}

		$response = $this->source_access_model->save();
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

			$dataItems = $this->source_access_model->getItems($id);
			$dataItems['company'] = $this->source_access_model->getCompany();
			$dataItems['source'] = $this->source_access_model->getSourceInEdit($id);
			$dataItems['source2'] = $this->source_access_model->getSource($id);

			if (!is_array($dataItems)) {
				throw new Exception($dataItems, 1);
			}

			$data = array(
				'title_modal' => 'Edit Sources Access',
				'url_form' => base_url() . "source_access/process",
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
			$process = $this->source_access_model->deleteData($id);

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

	public function company_sources($company_id)
	{
		$data = $this->source_access_model->getCompanySource($company_id);
		echo json_encode($data);
	}
}
