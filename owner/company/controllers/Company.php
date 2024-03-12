<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Company extends MY_Owner
{

	public function __construct()
	{
		$this->_function_except = ['account', 'settings', 'show', 'process', 'status', 'paging'];
		parent::__construct();
	}

	public function index()
    {
		$this->template->title("Company");
		$this->setTitlePage("Company");
		$this->assetsBuild(['datatables']);
		$this->_custom_button_header = array(
			array(
				'button' => 'insert',
				'label' => 'Add New Company',
				'type' => 'modal',
				'url' => base_url() . "company/insert",
			),
			array(
				'button' => 'imfort',
				'label' => 'Import Data Company',
				'type' => 'modal',
				'url' => base_url() . "company/import",
			),
			array(
				'button' => 'export',
				'label' => 'Export Data Company',
				'type' => 'modal',
				'url' => base_url() . "company/export",
			)
		);

		$cardSearch = array(
			array(
				'label' => "Company Name",
				'type' => "input",
				"name" => "company_name_filter"
			),
			array(
				'label' => "Register Date",
				"type" => "date",
				"name" => "register_filter"
			),
			array(
				'label' => "Status",
				"type" => "checkbox",
				'name' => "status_filter",
				"value" => array(
					1 => "Enabled",
					2 => "Disabled",
 				)
			)

		);

		$this->cardSearch($cardSearch); 
		$header_table = array('no','Company Code','Company Name', 'status','registration date',"");
		$this->setTable($header_table,true);
		$this->setJs('company_admins');
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
				'url' => base_url() . "company/update/$1",
			),
			array(
				'button' => 'delete',
				'type' => 'confirm',
				'title' => 'Item',
				'confirm' => 'Are you sure you want to delete this item ?',
				'url' => base_url() . "company/delete/$1",
			),
			array(
				'button' => 'status',
				'type' => 'confirm',
				'title' => 'Status',
				'confirm' => 'Are you sure you want to change status this item ?',
				'url' => base_url() . "company/status/$1",
			)
		);

		$button = $this->setButtonOnTable();

		echo $this->company_model->show($button);
	}

	public function insert()
	{
		isAjaxRequestWithPost();
		$checked['checked'] = 'enabled';

		$data = array(
			'title_modal' => 'Add New Company',
			'url_form' => base_url() . "company/process",
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

		$response = $this->company_model->save();
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

			$dataItems = $this->company_model->getItems($id);

			if (!is_array($dataItems)) {
				throw new Exception($dataItems, 1);
			}

			$dataItems['update'] = true;

			$data = array(
				'title_modal' => 'Edit Company',
				'url_form' => base_url() . "company/process",
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
			$process = $this->company_model->softDeleteWithoutForeign($id);

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

	public function status($id = null)
	{
		isAjaxRequestWithPost();
		$this->function_access('update');

		$response = $this->company_model->status($id);
		echo json_encode($response);
	}
}
