<?php defined('BASEPATH') or exit('No direct script access allowed');

abstract class MY_Template extends MX_Controller
{

	protected $_css;
	protected $_js;
	protected $_breadcrumb = '';
	protected $_custom_button_header = [];
	protected $_custom_button_on_table = [];
	protected $_logout_url = '';

	protected $_v_show = THEME . '/layouts/v_show';
	protected $_v_form_modal = THEME . '/layouts/v_form_modal';
	protected $_v_form_modal_custom = THEME . '/layouts/v_form_modal_custom';
	protected $_v_modal = THEME . '/layouts/v_modal';
	protected $_v_integration = THEME . '/layouts/v_integration';
	protected $_v_form_permission = THEME . '/layouts/v_form_permission';
	protected $_grid_menu = '';
	protected $_fullname_users = '';
	protected $_account_url = '';
	protected $_v_modal_notButton = THEME . '/layouts/v_modal_notbutton';


	public function __construct()
	{
		parent::__construct();
		$this->config->load('assets');
		$this->template->set_layout(LAYOUT, THEME);
		$this->template->set_partial(HEADER, THEME . '/' . HEADER);
		$this->template->set_partial(SIDEBAR, THEME . '/' . SIDEBAR);
		$this->template->set_partial(BREADCRUMB, THEME . '/' . BREADCRUMB);
		$this->template->set_partial(FOOTER, THEME . '/' . FOOTER);
		//$this->template->set_partial(SEARCH_UI,THEME.'/'.SEARCH_UI);
		if ($this->_logout_url != '') {
			$this->template->set('logout_url', $this->_logout_url);
		}

		if ($this->_grid_menu) {
			$this->template->set('_grid_menu', true);
		}

		$this->_fullname_users = $this->getProfil();

		if ($this->_fullname_users != '' || $this->_fullname_users != null) {
			$this->template->set('profil_users', $this->_fullname_users);
		}

		if ($this->_account_url != '') {
			$this->template->set('account_setting', $this->_account_url);
		}
	}

	protected function assetsBuild($asset = [])
	{
		foreach ($asset as $assets) {
			if ($assets == 'toastr') {
				continue;
			}
			foreach ($this->config->item('assets_' . $assets) as $key => $value) {
				switch ($key) {
					case 'css':
						foreach ($value as $data) {
							$this->_css[] = $data;
						}
						break;
					case 'js':
						foreach ($value as $data) {
							$this->_js[] = $data;
						}
						break;
				}
			}
		}
		$this->assetsRequest();
	}

	protected function assetsRequest()
	{
		$this->template->set('pageCSS', $this->_css);
		$this->template->set('pageJS', $this->_js);
	}

	protected function setJs($name = '')
	{
		if ($name == '') {
			$name = strtolower($this->router->class . '_' . $this->router->method);
		}

		$this->template->set('js', JS_CORE . '/' . strtolower(get_class($this)) . '/' . $name . '.js');
	}

	protected function setTitlePage($title = '')
	{
		$default = $this->_breadcrumb == '' ? ucwords(get_class($this)) : ucwords($this->_breadcrumb);
		$set = $title == '' ? $default : $title;
		$this->template->set('titlePage', $set);
	}

	protected function setBreadcrumb($arr_breadcrumb = [])
	{
		if (count($arr_breadcrumb) == 0) {
			$class = $this->router->class;
			$url = base_url() . $class;
			$arr_breadcrumb[] = array(
				'name' => $class,
				'url' => $url,
			);
			$method = $this->router->method;
			$method = $method == 'index' ? 'view' : $method;
			$arr_breadcrumb[] = array(
				'name' => $method,
				'url' => '',
			);
		}

		$this->template->set('breadcrumb', $arr_breadcrumb);
	}

	protected function setTitleCard($title = '')
	{
		$default = $this->_breadcrumb == '' ? ucwords(get_class($this)) : ucwords($this->_breadcrumb);
		$set = $title == '' ? $default : $title;
		if (strpos($set, 'Data') === false) {
			$set = 'Data ' . $set;
		}
		$this->template->set('titleCard', $set);
	}

	protected function setMenu($menu)
	{
		$this->template->set('menu', $menu);
	}
	protected function setJsType($url = null)
	{
		if ($url != null) {
			$this->template->set('jsType', $url);
		}
	}

	protected function setTemplateTable($array = [])
	{
		$string = "";
		if (is_array($array) && count($array)  > 0) {
			$string = generateTable($array);
		}
		$this->template->set('table', $string);
	}

	protected function buttonHeader($obj = "", $custom = [])
	{
		$string = '';
		if (is_object($obj)) {
			$string = generateButtonHeader($obj, $custom);
		}

		$this->template->set('buttonHeader', $string);
	}

	protected function buttonOnTable($obj = "")
	{
		$string = '';

		if (is_object($obj)) {
			$custom = count($this->_custom_button_on_table) > 0 ? $this->_custom_button_on_table : [];
			$string = generateButtonOnTable($obj, $custom);
		}

		return $string;
	}

	protected function cardSearch($array = [])
	{
		try {
			if (!is_array($array) || (is_array($array) && count($array) == 0)) {
				throw new Exception;
			}

			$_form_search = cardSearch($array);
			$this->template->set('formSearch', $_form_search);
		} catch (Exception $e) {
			return;
		}
	}

	protected function cardSearchHTML($array = [])
	{
		try {
			if (!is_array($array) || (is_array($array) && count($array) == 0)) {
				throw new Exception;
			}

			$_form_search = cardSearch($array);
			return $_form_search;
		} catch (Exception $e) {
			return;
		}
	}

	protected function isAjaxRequestWithPost()
	{
		if (!$this->input->is_ajax_request() && empty($this->input->post())) {
			pageError();
			exit();
		}

		return $this->output->set_content_type('application/json');
	}

	protected function isAjaxRequest()
	{
		if (!$this->input->is_ajax_request()) {
			pageError();
			exit();
		}

		return $this->output->set_content_type('application/json');
	}

	protected function getProfil()
	{
		$this->load->model('users/Users_model', 'users_model');
		$get = $this->users_model->getProfil();
		if ($get) {
			$data['fullname'] = $get->fullname;
			$data['email']   = $get->email;
			return $data;
		}
		return null;
	}

	protected function getPermission($status)
	{
		$this->template->set('getPermission', $status);
	}

	protected function setTemplateTableHTML($header = [], $body = [], $tableID = null, $theadClass = null)
	{
		$string = "";
		if (is_array($header) && count($header)  > 0) {
			$string = generateTableHtml($header, $body, $tableID, $theadClass);
		}
		$this->template->set('table', $string);
	}
}
