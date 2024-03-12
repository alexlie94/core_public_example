<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<div class="form-group row mb-8 modal-upload">

	<div class="row">
		<div class="col-md-7 mt-14">
			<label class="required fw-semibold fs-6">MASS UPLOAD </label>
			<div class="input-group">
				<input type="file" id="data_upload" style="padding: 17px;" class="form-control"  data-type="input" autocomplete="off" />
				<span class="input-group-text" id="basic-addon2">
					<button id="upload_button" class="btn btn-primary btn-sm" type="button">Upload</button>
				</span>
			</div>
			<div id='formatError' class="fv-plugins-message-container invalid-feedback mb-6">Format Data Csv Error, Download CSV Format!</div>
		</div>
		<div class="col-md-5 text-gray-800 text-hover-primary mb-3 text-center">
			<div class="symbol symbol-60px mb-5">
				<img src="<?= base_url('/assets/excel/icons8-microsoft-excel-2019-100.png') ?>" class="theme-light-show" alt="" />
				<img src="<?= base_url('/assets/excel/icons8-microsoft-excel-2019-100.png') ?>" class="theme-dark-show" alt="" />
			</div>

			<a type="button" href="javascript:void(0)" id="download_product" class=" d-flex flex-column">
				<button type="button" data-repeater-create="" class="fs-5 fw-bold mb-2 btn btn-md btn-light-success">
					<span class="svg-icon svg-icon-2">
						<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
							<rect opacity="0.5" x="11" y="18" width="12" height="2" rx="1" transform="rotate(-90 11 18)" fill="currentColor" />
							<rect x="6" y="11" width="12" height="2" rx="1" fill="currentColor" />
						</svg>
					</span>
					Download CSV Format
				</button>
			</a>
		</div>
	</div>

	<div class="container mt-5">
		<div class="row flex-nowrap overflow-auto">
			<div class="col-md-12">
				<table id="kt_datatable_vertical_scroll" class="table table-striped border rounded gy-5 gs-7">
					<thead>
						<tr class="fw-semibold fs-6 text-gray-800">
							<th class="min-w-80px"></th>
							<th class="min-w-20px">No</th>
							<th class="required min-w-200px">Brand Name</th>
							<th class="required min-w-250px">Supplier Name</th>
							<th class="required min-w-200px">Category Name</th>
							<th class="required min-w-200px">Product Name</th>
							<th class="required min-w-200px">Gender</th>
							<th class="min-w-200px">Sub Category</th>
							<th class="min-w-200px">Sub Sub Category</th>
							<th class="min-w-150px">Price</th>
							<th class="required min-w-200px">General Color</th>
							<th class="min-w-200px">Variant Color</th>
							<th class="required min-w-150px">Size</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<script>
	setTimeout(() => {
		var target = $(".modal-upload").parent().parent().parent('.modal-content')[0];
		var blockUI = new KTBlockUI(target);
	}, 300);
</script>
