<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<div class="card-toolbar mb-6" align="right">
	<button type="button" data-fullscreenmodal="0" data-type="modal" data-url="<?= base_url('category/massUpload') ?>"
		class="btn btn-outline btn-outline-dashed btn-outline-warning btn-active-light-warning btn-sm"
		id="btn_show_mass_upload" style="margin-top: 37px;">
		<span class="svg-icon svg-icon-primary svg-icon-2x">
			<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
				height="24px" viewBox="0 0 24 24" version="1.1">
				<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
					<polygon points="0 0 24 0 24 24 0 24" />
					<path
						d="M5.85714286,2 L13.7364114,2 C14.0910962,2 14.4343066,2.12568431 14.7051108,2.35473959 L19.4686994,6.3839416 C19.8056532,6.66894833 20,7.08787823 20,7.52920201 L20,20.0833333 C20,21.8738751 19.9795521,22 18.1428571,22 L5.85714286,22 C4.02044787,22 4,21.8738751 4,20.0833333 L4,3.91666667 C4,2.12612489 4.02044787,2 5.85714286,2 Z"
						fill="currentColor" fill-rule="nonzero" opacity="0.3" />
					<path
						d="M8.95128003,13.8153448 L10.9077535,13.8153448 L10.9077535,15.8230161 C10.9077535,16.0991584 11.1316112,16.3230161 11.4077535,16.3230161 L12.4310522,16.3230161 C12.7071946,16.3230161 12.9310522,16.0991584 12.9310522,15.8230161 L12.9310522,13.8153448 L14.8875257,13.8153448 C15.1636681,13.8153448 15.3875257,13.5914871 15.3875257,13.3153448 C15.3875257,13.1970331 15.345572,13.0825545 15.2691225,12.9922598 L12.3009997,9.48659872 C12.1225648,9.27584861 11.8070681,9.24965194 11.596318,9.42808682 C11.5752308,9.44594059 11.5556598,9.46551156 11.5378061,9.48659872 L8.56968321,12.9922598 C8.39124833,13.2030099 8.417445,13.5185067 8.62819511,13.6969416 C8.71848979,13.773391 8.8329684,13.8153448 8.95128003,13.8153448 Z"
						fill="currentColor" />
				</g>
			</svg>
		</span>
		Mass Upload Category
	</button>
</div>

<div>
	<div class="d-flex flex-column scroll-y me-n7 pe-7">
		<div class="row">
			<input type="hidden" class="form-control" id="id" name="id"
				value="<?= isset($dataItems['id']) ? $dataItems['id'] : '' ?>" />
			<div class="col-md-6">
				<div class="fv-row mb-7">
					<label class="fw-semibold fs-6 mb-4">Category Code</label>
					<input type="text" id="category_code" name="category_code"
						class="form-control form-control mb-3 mb-lg-0" placeholder="Category Code"
						value="<?= isset($dataItems['categories_code']) ? $dataItems['categories_code'] : 'Auto' ?>"
						data-type="input" autocomplete="off" disabled />
				</div>
			</div>
			<div class="col-md-6">
				<div class="fv-row mb-7">
					<label class="required fw-semibold fs-6 mb-4">Category Name</label>

					<input type="text" id="category_name" name="category_name"
						class="form-control form-control mb-3 mb-lg-0" placeholder="Category Name"
						value="<?= isset($dataItems['categories_name']) ? $dataItems['categories_name'] : '' ?>"
						data-type="input" autocomplete="off" />
				</div>
			</div>

			<div class="col-md-12">
				<div class="fv-row mb-7">
					<label class="fw-semibold fs-6 mb-4">Category Parent</label>
					<select class="form-select form-select-lg parentSelect" data-dropdown-parent="#modalLarge"
						data-control="select2" data-type='select' data-placeholder="Please Select" id="parent_id"
						name="parent_id">
						<option></option>
						<?php
						foreach ($dataCategory as $res) {

							if (isset($dataItems['id'])) {
								$val = ($res->id == $dataItems['parent_categories_id']) ? 'selected' : '';
							} else {
								$val = '';
							}
							?>
							<option value='<?= $res->id ?>' <?= $val ?>>
								<?= $res->categories_name ?>
							</option>
						<?php } ?>
					</select>
				</div>
			</div>
		</div>
	</div>
</div>