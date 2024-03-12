<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sources extends MY_Owner
{

	public function __construct()
	{
		$this->_function_except = ['account', 'settings', 'show', 'process', 'status', 'paging', 'showImage', 'showIcon'];
		parent::__construct();
	}

	public function index()
	{
		$this->template->title('Sources');
		$this->setTitlePage('Sources');
		$this->assetsBuild(['datatables']);
		$this->_custom_button_header = array(
			array(
				'button' => 'insert',
				'label' => 'Add New Sources',
				'type' => 'modal',
				'url' => base_url() . "sources/insert",
			)
		);

		$header_table = array(
			'no',
			'image',
			'source icon',
			'source',
			'source url',
			'app keys',
			'secret keys',
			'status',
			""
		);

		$this->setTable($header_table, true);
		$this->setJs('sources');
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
				'url' => base_url() . "sources/update/$1",
			),
			array(
				'button' => 'delete',
				'type' => 'confirm',
				'title' => 'Item',
				'confirm' => 'Are you sure you want to delete this item ?',
				'url' => base_url() . "sources/delete/$1",
			)
		);

		$button = $this->setButtonOnTable();

		echo $this->sources_model->show($button);
	}

	public function insert()
	{
		isAjaxRequestWithPost();
		$data = array(
			'title_modal' => 'Add New Sources',
			'url_form' => base_url() . "sources/process",
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

		$response = $this->sources_model->save();
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

			$dataItems = $this->sources_model->getItems($id);

			if (!is_array($dataItems)) {
				throw new Exception($dataItems, 1);
			}

			$data = array(
				'title_modal' => 'Edit Sources',
				'url_form' => base_url() . "sources/process",
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
			$process = $this->sources_model->deleteData($id);

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

	public function showImage($imgName)
	{
		isAjaxRequestWithPost();
		try {
			if ($imgName == null) {
				throw new Exception("Failed to request Edit", 1);
			}

			$dataItems = $this->sources_model->getImage($imgName);

			if (!is_array($dataItems)) {
				throw new Exception($dataItems, 1);
			}

			$data = array(
				'title_modal' => 'Image',
				'url_form' => base_url() . "sources/process",
				'content' => $this->load->view('v_show_img', $dataItems, true),
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

	public function showIcon($imgName)
	{
		isAjaxRequestWithPost();
		try {
			if ($imgName == null) {
				throw new Exception("Failed to request Edit", 1);
			}

			$dataItems = $this->sources_model->getIcon($imgName);

			if (!is_array($dataItems)) {
				throw new Exception($dataItems, 1);
			}

			$data = array(
				'title_modal' => 'Icon',
				'url_form' => base_url() . "sources/process",
				'content' => $this->load->view('v_show_icon', $dataItems, true),
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
}
