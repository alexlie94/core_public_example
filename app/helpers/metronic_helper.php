<?php
defined('BASEPATH') or exit('No direct script access allowed');

if (!function_exists('clearInput')) {
	function clearInput($data)
	{
		$filter = trim(stripslashes(strip_tags(htmlspecialchars($data, ENT_QUOTES))));
		return $filter;
	}
}

if (!function_exists('generateCode')) {
	function generateCode($length = 150)
	{
		return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
	}
}

if (!function_exists('getInputCsrf')) {
	function getInputCsrf()
	{
		$ci = &get_instance();
		return "<input type=\"hidden\" name=\"{$ci->security->get_csrf_token_name()}\" value=\"{$ci->security->get_csrf_hash()}\" class=\"token_csrf\" data-name=\"{$ci->security->get_csrf_token_name()}\" style=\"display: none\">";
	}
}

if (!function_exists('generateTable')) {
	function generateTable($array = [], $tableID = 'table-data')
	{
		if (!is_array($array) || (is_array($array) && count($array) == 0)) {
			return '';
		}

		$header = "<input type=\"hidden\" name=\"paging_datatables\" value=\"0\" class=\"halaman\" style=\"display: none\">";
		$header .= "<input type=\"hidden\" name=\"draw\" value=\"1\" class=\"draw_datatables\" style=\"display: none\">";
		$header .= "<table id=\"{$tableID}\" class=\"table align-middle table-row-dashed table-striped table-hover table-row-bordered dataTable\">";
		$header .= "<thead>";
		$header .= "<tr class=\"text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0\">";
		for ($i = 0; $i < count($array); $i++) {
			$header .= "<th>" . ($array[$i] != '' ? ucwords($array[$i]) : "") . "</th>";
		}
		$header .= "</tr>";
		$header .= "</thead>";
		$header .= "<tbody class=\"fw-semibold text-gray-600\"></tbody>";
		$header .= "</table>";
		return $header;
	}
}

if (!function_exists('generateButtonHeader')) {
	function generateButtonHeader($obj, $custom = [])
	{
		$button = "";
		try {
			if (!is_object($obj)) {
				throw new Exception;
			}
			if ((int) $obj->view === 0) {
				throw new Exception;
			}

			if ((int) $obj->insert === 0 && (int) $obj->export === 0 && (int) $obj->import === 0) {
				throw new Exception;
			}

			$insert_type = '';
			$insert_url = '';
			$insert_label = 'Add Data';
			$insert_fullscreen = '';

			$import_type = '';
			$import_url = '';
			$import_label = 'Import Data';

			$export_type = '';
			$export_url = '';
			$export_label = 'Export Data';

			if (count($custom) > 0) {
				foreach ($custom as $ky => $val) {
					if (isset($val['button'])) {
						switch ($val['button']) {
							case 'insert':
								$insert_type = (($val['type'] == 'redirect' || $val['type'] == 'modal' || $val['type'] == 'onload') && isset($val['type'])) ? "data-type = \"{$val['type']}\"" : '';
								$insert_url = (isset($val['url'])) ? "data-url = \"{$val['url']}\"" : '';
								$insert_label = (isset($val['label'])) ? $val['label'] : $insert_label;
								$insert_fullscreen = (isset($val['fullscreen']) && $val['fullscreen'] === true) ? "data-fullscreenmodal = 1" : "data-fullscreenmodal = 0";
								break;

							case 'import':
								$import_type = (($val['type'] == 'redirect' || $val['type'] == 'modal' || $val['type'] == 'onload') && isset($val['type'])) ? "data-type = \"{$val['type']}\"" : '';
								$import_url = (isset($val['url'])) ? "data-url = \"{$val['url']}\"" : '';
								$import_label = (isset($val['label'])) ? $val['label'] : $import_label;
								break;

							case 'export':
								$export_type = (($val['type'] == 'redirect' || $val['type'] == 'modal' || $val['type'] == 'onload') && isset($val['type'])) ? "data-type = \"{$val['type']}\"" : '';
								$export_url = (isset($val['url'])) ? "data-url = \"{$val['url']}\"" : '';
								$export_label = (isset($val['label'])) ? $val['label'] : $export_label;
								break;
						}
					}
				}
			}

			$button .= ((int) $obj->import === 1) ? "<button class=\"btn btn-flex btn-primary h-40px fs-7 fw-bold " . ((int) $obj->export === 1 || (int) $obj->insert === 1 ? "me-3" : "") . "\" type=\"button\" id=\"btnImport\" {$import_type} {$import_url}>{$import_label}</button>" : "";
			$button .= ((int) $obj->export === 1) ? "<button class=\"btn btn-flex btn-warning h-40px fs-7 fw-bold " . (((int) $obj->insert === 1) ? "me-3" : "") . "\" type=\"button\" id=\"btnExport\" {$export_type} {$export_url}>{$export_label}</button>" : "";
			$button .= ((int) $obj->insert === 1) ? "<button class=\"btn btn-flex btn-success h-40px fs-7 fw-bold\" type=\"button\" id=\"btnAdd\" {$insert_type} {$insert_url} {$insert_fullscreen}>{$insert_label}</button>" : "";

			return $button;
		} catch (\Throwable $th) {
			return $button;
		}
	}
}

if (!function_exists('pageError')) {
	function pageError()
	{
		redirect(BASE_URL . 'page/error', 'location');
	}
}

if (!function_exists('generateButtonOnTable')) {
	function generateButtonOnTable($obj, $custom = [])
	{
		$button = "";
		try {
			if (!is_object($obj)) {
				throw new Exception;
			}
			if ((int) $obj->view === 0) {
				throw new Exception;
			}

			// if((int)$obj->update === 0 && (int)$obj->delete === 0){
			//     throw new Exception;

			// }

			$update_type = '';
			$update_url = '';
			$update_confirm = '';
			$update_title = '';
			$update_fullscreen = '';

			$delete_type = '';
			$delete_url = '';
			$delete_confirm = '';
			$delete_title = '';

			$detail_type = '';
			$detail_url = '';
			$detail_confirm = '';
			$detail_title = '';
			$detail_fullscreen = '';

			$status_type = '';
			$status_url = '';
			$status_confirm = '';
			$status_title = '';

			if (count($custom) > 0) {
				foreach ($custom as $ky => $val) {
					if (isset($val['button'])) {
						switch ($val['button']) {
							case 'update':
								$update_type = (($val['type'] == 'redirect' || $val['type'] == 'modal' || $val['type'] == 'confirm' || $val['type'] == 'onload') && isset($val['type'])) ? "data-type = \"{$val['type']}\"" : '';
								$update_url = (isset($val['url'])) ? "data-url = \"{$val['url']}\"" : '';
								$update_confirm = (isset($val['confirm']) && (isset($val['type']) && $val['type'] == 'confirm')) ? "data-textconfirm = \"{$val['confirm']}\"" : "";
								$update_title = (isset($val['confirm']) && isset($val['title']) && (isset($val['type']) && $val['type'] == 'confirm')) ? "data-title = \"{$val['title']}\"" : "";
								$update_fullscreen = (isset($val['fullscreen'])) ? "data-fullscreenmodal = 1" : "data-fullscreenmodal = 0";
								break;

							case 'delete':
								$delete_type = (($val['type'] == 'redirect' || $val['type'] == 'modal' || $val['type'] == 'confirm' || $val['type'] == 'onload') && isset($val['type'])) ? "data-type = \"{$val['type']}\"" : '';
								$delete_url = (isset($val['url'])) ? "data-url = \"{$val['url']}\"" : '';
								$delete_confirm = (isset($val['confirm']) && (isset($val['type']) && $val['type'] == 'confirm')) ? "data-textconfirm = \"{$val['confirm']}\"" : "";
								$delete_title = (isset($val['confirm']) && isset($val['title']) && (isset($val['type']) && $val['type'] == 'confirm')) ? "data-title = \"{$val['title']}\"" : "";
								break;

							case 'detail':
								$detail_type = (($val['type'] == 'redirect' || $val['type'] == 'modal' || $val['type'] == 'confirm' || $val['type'] == 'onload') && isset($val['type'])) ? "data-type = \"{$val['type']}\"" : '';
								$detail_url = (isset($val['url'])) ? "data-url = \"{$val['url']}\"" : '';
								$detail_confirm = (isset($val['confirm']) && (isset($val['type']) && $val['type'] == 'confirm')) ? "data-textconfirm = \"{$val['confirm']}\"" : "";
								$detail_title = (isset($val['confirm']) && isset($val['title']) && (isset($val['type']) && $val['type'] == 'confirm')) ? "data-title = \"{$val['title']}\"" : "";
								$detail_fullscreen = (isset($val['fullscreen'])) ? "data-fullscreenmodal = 1" : "data-fullscreenmodal = 0";
								break;

							case 'status':
								$status_type = (($val['type'] == 'redirect' || $val['type'] == 'modal' || $val['type'] == 'confirm' || $val['type'] == 'onload') && isset($val['type'])) ? "data-type = \"{$val['type']}\"" : '';
								$status_url = (isset($val['url'])) ? "data-url = \"{$val['url']}\"" : '';
								$status_confirm = (isset($val['confirm']) && (isset($val['type']) && $val['type'] == 'confirm')) ? "data-textconfirm = \"{$val['confirm']}\"" : "";
								$status_title = (isset($val['confirm']) && isset($val['title']) && (isset($val['type']) && $val['type'] == 'confirm')) ? "data-title = \"{$val['title']}\"" : "";
								break;
						}
					}
				}
			}

			// $button .= $status_url != '' && (int) $obj->update === 1 ? "<button class=\"btn btn-outline btn-outline-dashed btn-outline-info btn-sm " . ((int) $obj->delete === 1 || (int) $obj->update === 1 ? "me-2" : "") . " btnStatus\" {$status_type} {$status_url} {$status_confirm} {$status_title} data-id =\"$1\">Disabled</button>" : "";
			// $button .= $detail_url != '' && (int) $obj->view === 1 ? "<button class=\"btn btn-outline btn-outline-dashed btn-outline-primary btn-sm " . ((int) $obj->delete === 1 || (int) $obj->update === 1 ? "me-2" : "") . " btnDetail\" {$detail_type} {$detail_url} {$detail_confirm} {$detail_title}  {$detail_fullscreen} data-id =\"$1\">View detail</button>" : "";
			// $button .= ((int) $obj->update === 1) ? "<button class=\"btn btn-outline btn-outline-dashed btn-outline-success btn-sm " . ((int) $obj->delete === 1 ? "me-2" : "") . " btnEdit\" {$update_type} {$update_url} {$update_confirm} {$update_title} {$update_fullscreen} data-id =\"$1\">Edit</button>" : "";
			// $button .= ((int) $obj->delete === 1) ? "<button class=\"btn btn-outline btn-outline-dashed btn-outline-danger  btn-sm btnDelete\"   {$delete_type} {$delete_url} {$delete_confirm} {$delete_title} data-id =\"$1\">Delete</button>" : "";

			// $button .= $status_url != '' && (int) $obj->update === 1 ? "<button class=\"btn btn-outline btn-outline-dashed btn-outline-warning btn-active-light-warning hover-scale btn-sm fw-bold " . ((int) $obj->delete === 1 || (int) $obj->update === 1 ? "me-2 mb-2" : "") . " btnStatus\" {$status_type} {$status_url} {$status_confirm} {$status_title} data-id =\"$1\"><i class=\"bi bi-slash-circle fs-4 me-2\"></i>Disabled</button>" : "";
			// $button .= $detail_url != '' && (int) $obj->view === 1 ? "<button class=\"btn btn-outline btn-outline-dashed btn-outline-primary btn-sm " . ((int) $obj->delete === 1 || (int) $obj->update === 1 ? "me-2" : "") . " btnDetail\" {$detail_type} {$detail_url} {$detail_confirm} {$detail_title}  {$detail_fullscreen} data-id =\"$1\">View detail</button>" : "";
			// $button .= ((int) $obj->update === 1) ? "<button class=\"btn btn-outline btn-outline-dashed btn-outline-success btn-active-light-success hover-scale btn-sm fw-bold " . ((int) $obj->delete === 1 ? "me-2 mb-2" : "") . " btnEdit\" {$update_type} {$update_url} {$update_confirm} {$update_title} {$update_fullscreen} data-id =\"$1\"><i class=\"bi bi-pencil-square fs-4 me-2\"></i></button>" : "";
			// $button .= ((int) $obj->delete === 1) ? "<button class=\"btn btn-outline btn-outline-dashed btn-outline-danger btn-active-light-danger hover-scale btn-sm fw-bold mb-2 btnDelete\"   {$delete_type} {$delete_url} {$delete_confirm} {$delete_title} data-id =\"$1\"><i class=\"bi bi-trash fs-4 me-2\"></i>Delete</button>" : "";

			$button .= $status_url != '' && (int) $obj->update === 1 ? "<button class=\"btn btn-outline btn-outline-dashed btn-outline-warning btn-active-light-warning hover-scale btn-sm fw-bold " . ((int) $obj->delete === 1 || (int) $obj->update === 1 ? "me-2 mb-2" : "") . " btnStatus\" {$status_type} {$status_url} {$status_confirm} {$status_title} data-id =\"$1\"><i class=\"bi bi-slash-circle fs-4 me-2\"></i>Disabled</button>" : "";
			$button .= $detail_url != '' && (int) $obj->view === 1 ? "<button class=\"btn btn-outline btn-outline-dashed btn-outline-primary btn-sm " . ((int) $obj->delete === 1 || (int) $obj->update === 1 ? "me-2" : "") . " btnDetail\" {$detail_type} {$detail_url} {$detail_confirm} {$detail_title}  {$detail_fullscreen} data-id =\"$1\">View detail</button>" : "";
			$button .= ((int) $obj->update === 1) ? "<button class=\"btn btn-icon btn-outline  btn-outline-success btn-outline-dashed hover-scale btn-sm fw-bold " . ((int) $obj->delete === 1 ? "me-1" : "") . " btnEdit\" {$update_type} {$update_url} {$update_confirm} {$update_title} {$update_fullscreen} data-id =\"$1\"><i class=\"bi bi-pencil-square\"></i></button>" : "";
			$button .= ((int) $obj->delete === 1) ? "<button class=\"btn btn-icon  btn-outline  btn-outline-danger btn-outline-dashed hover-scale btn-sm fw-bold  btnDelete\"   {$delete_type} {$delete_url} {$delete_confirm} {$delete_title} data-id =\"$1\"><i class=\"bi bi-trash\"></i></button>" : "";
			return $button;
		} catch (Exception $e) {
			return $button;
		}
	}
}

if (!function_exists('generateCsrf')) {
	function generateCsrf()
	{
		$ci = &get_instance();
		return $ci->security->get_csrf_hash();
	}
}

if (!function_exists('cardSearch')) {
	function cardSearch($arr)
	{
		$html = "<div class=\"card mb-10\">";
		$html .= "<div class=\"card-header collapsible cursor-pointer rotate\" data-bs-toggle=\"collapse\" data-bs-target=\"#kt_docs_card_collapsible\">";
		$html .= "<h3 class=\"card-title text-primary\">
					<span class=\"svg-icon svg-icon-2 text-primary\">
						<svg width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">
							<path d=\"M19.0759 3H4.72777C3.95892 3 3.47768 3.83148 3.86067 4.49814L8.56967 12.6949C9.17923 13.7559 9.5 14.9582 9.5 16.1819V19.5072C9.5 20.2189 10.2223 20.7028 10.8805 20.432L13.8805 19.1977C14.2553 19.0435 14.5 18.6783 14.5 18.273V13.8372C14.5 12.8089 14.8171 11.8056 15.408 10.964L19.8943 4.57465C20.3596 3.912 19.8856 3 19.0759 3Z\" fill=\"currentColor\"></path>
						</svg>
					</span> Filter</h3>";
		$html .= "</div>";
		try {
			$html .= "<div id=\"kt_docs_card_collapsible\" class=\"collapse\">";
			$html .= "<div class=\"card-body\">";
			$html .= "<form id=\"formSearch\" class=\"form formSearch\" autocomplete=\"off\">";
			foreach ($arr as $ky => $val) {
				switch ($val['type']) {
					case 'dateRange':
						$html .= "<div class=\"mb-5\">
                                <label class=\"fw-semibold fs-6 mb-2\" for=\"{$val['name'][0]}\">{$val['label']}</label>
                                <div class=\"col-2\">
                                    <input class=\"form-control form-control-solid mb-3 mb-lg-0\" name=\"{$val['name'][0]}\" id=\"{$val['name'][0]}\" type=\"text\"  data-type=\"{$val['type']}\" />
                                </div>
                                <label class=\"col-1 fw-semibold fs-6 mb-2 text-center\" for=\"{$val['name'][1]}\" style=\"margin-left:-3%;margin-right:-3%;\">To</label>
                                <div class=\"col-2\">
                                    <input class=\"form-control form-control-solid mb-3 mb-lg-0\" name=\"{$val['name'][1]}\" id=\"{$val['name'][1]}\" type=\"text\"  data-type=\"{$val['type']}\" />
                                </div>
                            </div>";
						break;

					case 'checkbox':

						$html .= "<div class=\"mb-5\">
									<label class=\"fw-semibold fs-6 mb-2\" for=\"{$val['name']}\">{$val['label']}</label>";
						$html .= "<div class=\"d-flex flex-row fv-row\">";
						$i = 1;
						foreach ($val['value'] as $kyCheckbox => $checkbox) {
							$html .= "<div class=\"form-check form-check-custom mb-5 " . (count($val['value']) == $i ? 'ms-5' : '') . "\">";

							$html .= "<input type=\"checkbox\" class=\"form-check-input me-3\" name=\"{$val['name']}[]\" value=\"{$kyCheckbox}\" data-type=\"{$val['type']}\" />";

							$html .= "<label class=\"form-check-label\" for=\"{$val['name']}}\">
										{$checkbox}
									</label>";

							$html .= "</div>";
							$i++;
						}
						$html .= "</div>";
						$html .= "</div>";
						break;

					case 'radio':
						$html .= "<div class=\"mb-5\">
									<label class=\"fw-semibold fs-6 mb-2\" for=\"{$val['name']}\">{$val['label']}</label>";
						$html .= "<div class=\"d-flex flex-row fv-row\">";
						$i = 1;
						foreach ($val['value'] as $kyRadio => $radio) {
							$html .= "<div class=\"form-check form-check-custom " . (count($val['value']) == $i ? 'ms-5' : '') . "\">";
							$html .= "<input class=\"form-check-input\" type=\"radio\" name=\"{$val['name']}\" value=\"{$kyRadio}\" data-type=\"{$val['type']}\" />";
							$html .= "<label class=\"form-check-label\" for=\"{$val['name']}\">";
							$html .= "{$radio}</label>";
							$html .= "</div>";
							$i++;
						}
						$html .= "</div>";
						$html .= "</div>";

						break;

					case 'select-multiple':

						$html .= "<div class=\"mb-5\">";
						$html .= "<label for=\"{$val['name']}\" class=\"form-label fw-semibold fs-6 mb-2\">{$val['label']}</label>";
						$html .= "<div class=\"col-5\">";
						$html .= "<select class=\"form-select\" name=\"{$val['name']}[]\" data-control=\"select2\" data-close-on-select=\"false\" data-placeholder=\"Select an option\" data-allow-clear=\"true\" multiple=\"multiple\" data-type=\"{$val['type']}\">";
						foreach ($val['value'] as $kySelect => $select) {
							$html .= "<option value=\"{$kySelect}\">{$select}</option>";
						}
						$html .= "</select>";
						$html .= "</div>";
						$html .= "</div>";

						break;

					default:
						$html .= "<div class=\"mb-5\">
									<label for=\"{$val['name']}\" class=\"form-label fw-semibold fs-6 mb-2\">{$val['label']}</label>
									<div class=\"col-5\">
										<input class=\"form-control\" " . ($val['type'] == 'date' ? "data-inputmask-alias=\"datetime\"  data-inputmask-inputformat=\"yyyy-mm-dd\"" : "") . " name=\"{$val['name']}\" id=\"{$val['name']}\" type=\"text\"  data-type=\"{$val['type']}\" data-library=\"" . (isset($val['library']) ? "{$val['library']}" : "") . "\" />
									</div>
								</div>";
						break;
				}
			}

			$html .= "</form>";

			$html .= "<div>
                    <div class=\"col-5\">
						<div class=\"d-flex flex-end\">
							<button id=\"btnSearchHidden\" class=\"btn btn-warning  m-2\" type=\"button\">Close Filter &amp; Without Reset</button>
							<button id=\"btnSearchResetUncollapse\" class=\"btn btn-danger m-2\" type=\"button\">Close Filter &amp; Reset</button>
                        	<button id=\"btnSearchReset\" class=\"btn btn-secondary m-2\" type=\"button\">Reset</button>
							<button id=\"btnSearch\" class=\"btn btn-primary ml-2 mt-2 mb-2 ms-2 ml-1\" type=\"button\" onclick=\"reloadDatatables()\">Search</button>
						</div>
					</div>
                </div>";

			$html .= "</div>";
			$html .= "</div>";
			$html .= "</div>";
			return $html;
		} catch (Exception $e) {
			$html = '';
			return $html;
		}
	}
}

if (!function_exists('generatePaging')) {
	function generatePaging()
	{
		$ci = &get_instance();
		$total = $ci->input->post('total');
		$limit = $ci->input->post('limit');
		$page = $ci->input->post('page');
		$pagination_html = '<div class="btn-group col-sm-12 pl-sm-3 mb-4 justify-content-end"">
        <nav aria-label="Page navigation example">
            <ul class="pagination">';

		$total_links = ceil($total / $limit);

		$previous_link = '';

		$next_link = '';

		$page_link = '';

		if ($total_links > 4) {
			if ($page < 5) {
				for ($count = 1; $count <= 5; $count++) {
					$page_array[] = $count;
				}
				$page_array[] = '...';
				$page_array[] = $total_links;
			} else {
				$end_limit = $total_links - 5;

				if ($page > $end_limit) {
					$page_array[] = 1;

					$page_array[] = '...';

					for ($count = $end_limit; $count <= $total_links; $count++) {
						$page_array[] = $count;
					}
				} else {
					$page_array[] = 1;

					$page_array[] = '...';

					for ($count = $page - 1; $count <= $page + 1; $count++) {
						$page_array[] = $count;
					}

					$page_array[] = '...';

					$page_array[] = $total_links;
				}
			}
		} else {
			for ($count = 1; $count <= $total_links; $count++) {
				$page_array[] = $count;
			}
		}

		for ($count = 0; $count < count($page_array); $count++) {
			if (($page == 0 ? 1 : ($page + 1)) == $page_array[$count]) {
				$page_link .= '
                <li class="page-item active">
                    <a class="page-link" data-halaman = "' . $count . '">' . $page_array[$count] . ' <span class="sr-only">(current)</span></a>
                </li>
                ';

				$previous_id = $page_array[$count] - 1 - 1;

				if ($previous_id > 0) {
					$previous_link = '<li class="page-item"><a class="page-link" data-halaman = "' . $previous_id . '"><i class="previous"></i></a></li>';
				} else {
					$previous_link = '
                    <li class="page-item disabled">
                        <a class="page-link" href="#"><i class="previous"></i></a>
                    </li>
                    ';
				}

				$next_id = $page_array[$count] + 1 + 1;

				if ($next_id >= $total_links) {
					$next_link = '
                    <li class="page-item disabled">
                        <a class="page-link" href="#"><i class="next"></i></a>
                    </li>
                    ';
				} else {
					$next_link = '
                    <li class="page-item"><a class="page-link" data-halaman = "' . $next_id . '"><i class="next"></i></a></li>
                    ';
				}
			} else {
				if ($page_array[$count] == '...') {
					$page_link .= '
                    <li class="page-item disabled">
                        <a class="page-link" href="#">...</a>
                    </li>
                    ';
				} else {
					$page_link .= '
                    <li class="page-item">
                        <a class="page-link" data-halaman="' . $count . '">' . $page_array[$count] . '</a>
                    </li>
                    ';
				}
			}
		}

		$pagination_html .= $previous_link . $page_link . $next_link;

		$pagination_html .= ' </ul>
                </nav>
            </div>';

		return $pagination_html;
	}
}

if (!function_exists('isAjaxRequestWithPost')) {
	function isAjaxRequestWithPost()
	{
		$ci = &get_instance();
		if (!$ci->input->is_ajax_request() && empty($ci->input->post())) {
			pageError();
			exit();
		}

		return $ci->output->set_content_type('application/json');
	}
}

if (!function_exists('isAjaxRequest')) {
	function isAjaxRequest()
	{
		$ci = &get_instance();
		if (!$ci->input->is_ajax_request()) {
			pageError();
			exit();
		}

		return $ci->output->set_content_type('application/json');
	}
}

if (!function_exists('controllerExist')) {
	function isControllerExist()
	{
		$ci = &get_instance();
		return $ci->router->class;
	}
}

if (!function_exists('debug')) {
	function debug($value)
	{
		echo "<pre>";
		var_dump($value);
		echo "</pre>";
		exit();
	}
}

if (!function_exists('pre')) {
	function pre($value)
	{
		echo "<pre>";
		print_r($value);
		echo "</pre>";
		exit();
	}
}

if (!function_exists('tf_convert_base64_to_image')) {
	function tf_convert_base64_to_image($base64_code, $path, $image_name = null)
	{
		if (!empty($base64_code) && !empty($path)) :

			$string_pieces = explode(";base64,", $base64_code);
			$image_type_pieces = explode("image/", $string_pieces[0]);

			$image_type = $image_type_pieces[1];

			/*@ Create full path with image name and extension */
			$store_at = $path . md5(uniqid());
			// $store_at = $path . md5(uniqid()) . '.' . $image_type;

			/*@ If image name available then use that  */
			if (!empty($image_name)) :
				$store_at = $path . $image_name;
			// $store_at = $path . $image_name . '.' . $image_type;
			endif;

			$decoded_string = base64_decode($string_pieces[1]);

			file_put_contents($store_at, $decoded_string);

		endif;
	}
}


if (!function_exists('mkautono')) {
	function mkautono($table, $field, $prefix)
	{
		$ci = &get_instance();
		$month_trans = date("ym");
		$_trans = date("ymd");
		$com_code = $ci->session->userdata('x-com-code');
		$companys_id = $ci->session->userdata('x-com');

		$ci->db->select("RIGHT($field,5) AS TransNo");
		$ci->db->from("$table");
		$ci->db->where("MID(RIGHT($field,12),1,4)", $month_trans);
		$ci->db->where("users_ms_companys_id", $companys_id);
		$ci->db->order_by("$field DESC");
		$ci->db->limit(1);

		$data = $ci->db->get()->row();
		$trans_no = isset($data->TransNo) ? $data->TransNo : 0;
		$get_seq_number = (int) $trans_no + 1;
		$seq_number = str_pad($get_seq_number, 5, "0", STR_PAD_LEFT);
		$vno = $com_code . '/' . $prefix . '/' . $_trans . '/' . $seq_number;
		return $vno;
	}
}

if (!function_exists('mkautono_no_auth')) {
	function mkautono_no_auth($table, $field, $prefix, $comp_id)
	{
		$ci = &get_instance();
		$month_trans = date("ym");
		$_trans = date("ymd");

		$ci->db->select("RIGHT($field,5) AS TransNo");
		$ci->db->from("$table");
		$ci->db->where("MID(RIGHT($field,12),1,4)", $month_trans);
		$ci->db->where("users_ms_companys_id", $comp_id);
		$ci->db->order_by("$field DESC");
		$ci->db->limit(1);

		$data = $ci->db->get()->row();

		$ci->db->select("t1.company_code");
		$ci->db->from("users_ms_companys t1");
		$ci->db->where("t1.id", $comp_id);
		$ci->db->limit(1);

		$comp_code = $ci->db->get()->row()->company_code;


		$trans_no = isset($data->TransNo) ? $data->TransNo : 0;
		$get_seq_number = (int) $trans_no + 1;
		$seq_number = str_pad($get_seq_number, 5, "0", STR_PAD_LEFT);
		$vno = $comp_code . '/' . $prefix . '/' . $_trans . '/' . $seq_number;
		return $vno;
	}
}


if (!function_exists('get_random_string')) {
	function get_random_string($valid_chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789", $length = 2)
	{
		// start with an empty random string
		$random_string = "";

		// count the number of chars in the valid chars string so we know how many choices we have
		$num_valid_chars = strlen($valid_chars);

		// repeat the steps until we've created a string of the right length
		for ($i = 0; $i < $length; $i++) {
			// pick a random number from 1 up to the number of valid chars
			$random_pick = mt_rand(1, $num_valid_chars);

			// take the random character out of the string of valid chars
			// subtract 1 from $random_pick because strings are indexed starting at 0, and we started picking at 1
			$random_char = $valid_chars[$random_pick - 1];

			// add the randomly-chosen char onto the end of our string so far
			$random_string .= $random_char;
		}

		// return our finished random string
		return $random_string;
	}
}

if (!function_exists('generate_random_string')) {
	function generate_random_string($length = 4)
	{
		$characters = '123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$randomString = '';

		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, strlen($characters) - 1)];
		}

		return $randomString;
	}
}

if (!function_exists('sku_is_exists')) {
	function sku_is_exists($sku = null)
	{
		$ci = &get_instance();
		$data = $ci->db->where('sku', $sku)->get('users_ms_product_variants')->result();
		return ($data ? TRUE : FALSE);
	}
}

if (!function_exists('create_sku')) {
	function create_sku($parameter = array())
	{
		// Initilize parameter
		$SKU = array();
		$SKU_RAND = get_random_string(); // get random string
		$SKU[] = substr(str_pad($parameter['brand_name'], 2, "0", STR_PAD_RIGHT), 0, 2);
		$SKU[] = substr(str_pad($parameter['product_name'], 2, "0", STR_PAD_RIGHT), 0, 2);
		$SKU[] = substr(str_pad($parameter['type_name'], 2, "0", STR_PAD_RIGHT), 0, 2);
		$SKU[] = substr(str_pad($parameter['color_name'], 2, "0", STR_PAD_RIGHT), 0, 2);
		$SKU[] = substr(str_pad($parameter['product_size'], 2, "0", STR_PAD_RIGHT), 0, 2);
		$SKU_INITIALIZE = implode("", $SKU);
		$SKU_CODE = strtoupper($SKU_INITIALIZE . "-" . $SKU_RAND);

		// CEK SKU is existing
		while (sku_is_exists($SKU_CODE)) : // if existing, the random character make changes
			$SKU_RAND = get_random_string(); // get random string
			$SKU_CODE = $SKU_INITIALIZE . "-" . $SKU_RAND;
		endwhile;

		return $SKU_CODE;
	}
}

if (!function_exists('getBrand')) {
	function getBrand($brand)
	{
		$ci = &get_instance();

		$ci->db->where(
			array('brand_code' => $brand)
		);
		$ci->db->limit(1);
		return $ci->db->get('users_ms_brands')->row();
	}
}

if (!function_exists('cron_log')) {
	function cron_log($LogType = '', $LogRequest = '', $LogResponse = '', $channel = '')
	{
		$ci = &get_instance();
		$array = array(
			'users_ms_companys_id' => 1,
			'channel' => "Berrybenka",
			'log_type' => $LogType,
			'log_module' => $_SERVER['REQUEST_URI'],
			'log_request' => $LogRequest,
			'log_response' => $LogResponse,
			'log_ip' => $ci->input->ip_address(),
			'created_by' => "System",
		);
		return $ci->db->insert('users_cron_log', $array);
	}
}

if (!function_exists('format_number_to_idr')) {
	function format_number_to_idr($number = '', $showIDR = 1)
	{
		if ($showIDR) {
			$formattedValue = 'IDR ' . number_format($number, 0, '.', '.');
		} else {
			$formattedValue = number_format($number, 0, '.', '.');
		}
		return $formattedValue;
	}
}

if (!function_exists('timestamp_to_date')) {
	function timestamp_to_date($timestamp = '')
	{
		date_default_timezone_set('Asia/Jakarta');
		$format_tanggal = date("d M Y, h:i A", $timestamp);
		return $format_tanggal;
	}
}

function generateTableHtml($array = [], $tbody = [], $id = 'table-data', $theadClass = "")
{
	if (!is_array($array) || (is_array($array) && count($array) == 0)) {
		return '';
	}

	$idTable = "id=\"{$id}\"";

	$header = "<div class=\"table-responsive\">";
	$header .= "<table class=\"table table-rounded table-striped border gy-4 gs-4\" {$idTable}>";
	$header .= "<thead " . (!empty($theadClass) ? "class ='{$theadClass}'" : "") . ">";
	$header .= "<tr class=\"fw-bold fs-6 text-gray-800 border-bottom border-gray-200\">";
	for ($i = 0; $i < count($array); $i++) {
		$header .= "<th>" . ($array[$i] != '' ? ucwords($array[$i]) : "") . "</th>";
	}
	$header .= "</tr>";
	$header .= "</thead>";
	$header .= "<tbody class=\"fs-6\">";
	$valTbody = "";
	foreach ($tbody as $ky => $val) {
		$valTbody .= "<tr>";
		for ($i = 0; $i < count($array); $i++) {
			$valTbody .= "<td class=\"align-middle\">{$val[$array[$i]]}</td>";
		}
		$valTbody .= "</tr>";
	}
	$header .= $valTbody;
	$header .= "</tbody>";
	$header .= "</table>";
	$header .= "</div>";
	return $header;
}


if (!function_exists('create_url')) {
	function create_url($url, $path, $param)
	{
		$parameter = implode(
			'&',
			array_map(
				function ($v, $k) {
					return sprintf("%s=%s", $k, $v);
				},
				$param,
				array_keys($param)
			)
		);
		$url = $url . $path . "?" . $parameter;

		return $url;
	}
}

if (!function_exists('get_request_curl')) {
	function get_request_curl($url)
	{
		// pre($url);
		$curl = curl_init();

		curl_setopt_array(
			$curl,
			array(
				CURLOPT_URL => $url,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => '',
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => 'GET',
				CURLOPT_HTTPHEADER => array(
					'Content-Type: application/json'
				),
			)
		);

		$response = curl_exec($curl);

		curl_close($curl);
		return json_decode($response);
	}
}

if (!function_exists('get_request_with_header_curl')) {
	function get_request_with_header_curl($url, $header, $decode_response = 1)
	{
		// pre($url);
		$curl = curl_init();

		curl_setopt_array(
			$curl,
			array(
				CURLOPT_URL => $url,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => '',
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => 'GET',
				CURLOPT_HTTPHEADER => $header
			)
		);

		$response = curl_exec($curl);

		curl_close($curl);

		if ($decode_response) {

			return json_decode($response);
		} else {

			return $response;
		}
	}
}


if (!function_exists('post_request_curl')) {
	function post_request_curl($url, $data)
	{
		$curl = curl_init();

		curl_setopt_array(
			$curl,
			array(
				CURLOPT_URL => $url,
				CURLOPT_RETURNTRANSFER => true,
				CURLINFO_HEADER_OUT => true,
				CURLOPT_ENCODING => '',
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => 'POST',
				CURLOPT_POSTFIELDS => $data,
				CURLOPT_HTTPHEADER => array(
					'Content-Type: application/json',
				),
			)
		);
		$response = curl_exec($curl);

		curl_close($curl);
		return json_decode($response);
	}
}
if (!function_exists('post_request_with_header_curl')) {
	function post_request_with_header_curl($url, $data, $header)
	{
		$curl = curl_init();

		curl_setopt_array(
			$curl,
			array(
				CURLOPT_URL => $url,
				CURLOPT_RETURNTRANSFER => true,
				CURLINFO_HEADER_OUT => true,
				CURLOPT_ENCODING => '',
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => 'POST',
				CURLOPT_POSTFIELDS => $data,
				CURLOPT_HTTPHEADER => $header,
			)
		);
		$response = curl_exec($curl);

		curl_close($curl);
		return json_decode($response);
	}
}

if (!function_exists('post_request_curl_file')) {
	function post_request_curl_file($url, $data)
	{
		$curl = curl_init();

		curl_setopt_array(
			$curl,
			array(
				CURLOPT_URL => $url,
				CURLOPT_RETURNTRANSFER => true,
				CURLINFO_HEADER_OUT => true,
				CURLOPT_ENCODING => '',
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => 'POST',
				CURLOPT_POSTFIELDS => $data,
				CURLOPT_HTTPHEADER => array(
					'Content-Type: application/json',
				),
			)
		);
		$response = curl_exec($curl);

		curl_close($curl);
		return $response;
	}
}

if (!function_exists('generate_signature_tiktok')) {
	function generate_signature_tiktok($uri, &$params, $secret)
	{
		$paramsToBeSigned = $params;
		$stringToBeSigned = '';


		ksort($paramsToBeSigned);


		foreach ($paramsToBeSigned as $k => $v) {
			if (!is_array($v)) {
				$stringToBeSigned .= "$k$v";
			}
		}

		$stringToBeSigned = $uri . $stringToBeSigned;


		$stringToBeSigned = $secret . $stringToBeSigned . $secret;

		return hash_hmac('sha256', $stringToBeSigned, $secret);
	}
}

if (!function_exists('checkHeaderDocument')) {
	function checkHeaderDocument($data = [], $formatHeader = [])
	{
		$headerExists = [];
		$keyHeader = false;
		foreach ($data as $ky => $val) {
			$chr = 65;
			$headerExists = [];
			for ($i = 0; $i < count($formatHeader); $i++) {
				$check = isset($val[chr($chr + $i)]) ? $val[chr($chr + $i)] : false;
				if ($check === false) {
					continue 2;
				}

				if (strtolower($check) != strtolower($formatHeader[$i])) {
					continue 2;
				} else {
					$headerExists[$i] = true;
				}
			}

			if (count($headerExists) > 0) {
				$cari = array_search(false, $headerExists);
				if ($cari === false) {
					$keyHeader = $ky;
					break;
				}
			}
		}

		return $keyHeader;
	}
}


if (!function_exists('formValidationSelf')) {
	function formValidationSelf($title, $field, $param = "")
	{
		$lang['form_validation_required'] = "The {$field} field is required.";
		$lang["form_validation_isset"] = "The {$field} field must have a value.";
		$lang["form_validation_valid_email"] = "The {$field} field must contain a valid email address.";
		$lang["form_validation_valid_emails"] = "The {$field} field must contain all valid email addresses.";
		$lang["form_validation_valid_url"] = "The {$field} field must contain a valid URL.";
		$lang["form_validation_valid_ip"] = "The {$field} field must contain a valid IP.";
		$lang["form_validation_valid_base64"] = "The {$field} field must contain a valid Base64 string.";
		$lang["form_validation_min_length"] = "The {$field} field must be at least {$param} characters in length.";
		$lang["form_validation_max_length"] = "The {$field} field cannot exceed {$param} characters in length.";
		$lang["form_validation_exact_length"] = "The {$field} field must be exactly {$param} characters in length.";
		$lang["form_validation_alpha"] = "The {$field} field may only contain alphabetical characters.";
		$lang["form_validation_alpha_numeric"] = "The {$field} field may only contain alpha-numeric characters.";
		$lang["form_validation_alpha_numeric_spaces"] = "The {$field} field may only contain alpha-numeric characters and spaces.";
		$lang["form_validation_alpha_dash"] = "The {$field} field may only contain alpha-numeric characters, underscores, and dashes.";
		$lang["form_validation_numeric"] = "The {$field} field must contain only numbers.";
		$lang["form_validation_is_numeric"] = "The {$field} field must contain only numeric characters.";
		$lang["form_validation_integer"] = "The {$field} field must contain an integer.";
		$lang["form_validation_regex_match"] = "The {$field} field is not in the correct format.";
		$lang["form_validation_matches"] = "The {$field} field does not match the {$param} field.";
		$lang["form_validation_differs"] = "The {$field} field must differ from the {$param} field.";
		$lang["form_validation_is_unique"] = "The {$field} field must contain a unique value.";
		$lang["form_validation_is_natural"] = "The {$field} field must only contain digits.";
		$lang["form_validation_is_natural_no_zero"] = "The {$field} field must only contain digits and must be greater than zero.";
		$lang["form_validation_decimal"] = "The {$field} field must contain a decimal number.";
		$lang["form_validation_less_than"] = "The {$field} field must contain a number less than {$param}.";
		$lang["form_validation_less_than_equal_to"] = "The {$field} field must contain a number less than or equal to {$param}.";
		$lang["form_validation_greater_than"] = "The {$field} field must contain a number greater than {$param}.";
		$lang["form_validation_greater_than_equal_to"] = "The {$field} field must contain a number greater than or equal to {$param}.";
		$lang["form_validation_error_message_not_set"] = "Unable to access an error message corresponding to your field name {$field}.";
		$lang["form_validation_in_list"] = "The {$field} field must be one of: {$param}.";
		$lang['form_validation_found'] = "The {$field} field is not found.";
		$lang['form_validation_existexcel'] = "The {$field} field is already exist on table with different sequence.";
		$lang["form_validation_matchesexcel"] = "The {$field} field does not match the previous {$field} field in the same {$param}.";
		$lang['form_validation_foundparam'] = "The {$field} field is not found in {$param}.";
		$lang['form_validation_existdata'] = "The {$field} field is already exist.";
		$lang['form_validation_existdataexcel'] = "The {$field} field is already exist on the same sequence.";
		$lang['form_validation_existdatabase'] = "The {$field} field is already exist on data.";
		$lang['form_validation_existexceltable'] = "The {$field} field is already exist on table.";


		return showMessageErrorForm($lang[$title]);
	}
}

if (!function_exists('showMessageErrorForm')) {
	function showMessageErrorForm($text)
	{
		return "<div class=\"fv-plugins-message-container invalid-feedback\">{$text}</div>";
	}
}

if (!function_exists('activityLogs')) {
	function activityLogs($data = [])
	{
		$ci = &get_instance();
		$table = 'users_ms_activity_logs';
		$ci->db->insert($table, $data);
		return;
	}
}

if (!function_exists('errorLogs')) {
	function errorLogs($data = [])
	{
		$ci = &get_instance();
		$table = 'users_ms_error_logs';
		$ci->db->insert($table, $data);
		return;
	}
}


if (!function_exists('create_log_order')) {
	function create_log_order($company_id, $order_id, $code, $updated_at, $created_by)
	{
		$ci = &get_instance();
		$table = 'users_tr_order_logs';
		switch ($code) {
			case 1:
				$log_desc = 'Order is created in system';
				break;
			case 2:
				$log_desc = 'Order is paid';
				break;
			case 3:
				$log_desc = 'Order has been arranged to ship';
				break;
			case 4:
				$log_desc = 'Order has been shipped';
				break;
			case 5:
				$log_desc = 'Order has been successfully delivered';
				break;
			default:
				$log_desc = 'Log stopped';
				break;
		}
		$data = array(
			'users_ms_companys_id' => $company_id,
			'users_tr_orders_id' => $order_id,
			'log_code' => $code,
			'log_desc' => $log_desc,
			'local_updated_at' => $updated_at,
			'created_by' => $created_by,
		);
		$ci->db->insert($table, $data);
		return;
	}
}

if (!function_exists('check_image_source')) {
	function check_image_source($source_name)
	{
		$path_image = "../assets/metronic/media/marketplace/";

		$toLowerConvert = strtolower($source_name);
		$replaceWhiteSpace = str_replace(' ', '_', $toLowerConvert);

		switch ($replaceWhiteSpace) {
			case "shopee":
				$image_source_name = "shopee-icon.png";
				break;
			case "tiktok":
				$image_source_name = "tiktok-icon.png";
				break;
			case "zalora":
				$image_source_name = "zalora-icon.png";
				break;
			case "tokopedia":
				$image_source_name = "tokopedia-icon.png";
				break;
			case "berrybenka_offline":
				$image_source_name = "bb.webp";
				break;
			case "blibli":
				$image_source_name = "blibli-icon.png";
				break;
			case "bukalapak":
				$image_source_name = "bukalapak-icon.png";
				break;
			case "lazada":
				$image_source_name = "lazada-icon.png";
				break;
			default:
				$image_source_name = "";
		}

		return $path_image . $image_source_name;
	}
}

if (!function_exists('check_image_file')) {
	function check_image_file($image_path, $image_name)
	{
		if (!file_exists($image_path . $image_name)) {
			$image_file = './assets/uploads/default.png';
		} else {
			$image_file = $image_path . $image_name;
		}

		return '.' . $image_file;
	}
}
