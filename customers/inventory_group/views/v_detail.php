<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<input type="hidden" id="group_id" name="group_id" value="<?= $dataItems->id ?>">
<div id="kt_app_content" class="app-content flex-column-fluid modal-upload">
	<div id="kt_app_content_container" class="app-container container-fluid ">
		<div class="row mb-10">
			<div class="col-md-6">
				<table class="table table-striped table-row-bordered gy-5 gs-7" style="border: 1px solid #000000;padding: 8px;text-align: left;">
					<thead>
						<tr class="fw-semibold fs-6 text-gray-800">
							<th class="min-w-100px">Product GID</th>
							<th class="min-w-200px">Product Group</th>
							<th class="min-w-200px">Brand</th>
						</tr>
					</thead>
					<tbody>
						<tr class="odd">
							<td><?= $dataItems->group_code ?></td>
							<td><?= $dataItems->group_name ?></td>
							<td><?= empty($dataItems->brand_name) ? '-' : $dataItems->brand_name ?></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>

		<div class="row mb-10">
			<div class="col-md-12">
				<div class="form-group">
					<label class="required form-label" for="group_name">Group Name</label>
					<input type="text" id="group_name" data-type="input" name="group_name" value="<?= $dataItems->group_name ?>" class="form-control mb-2">
				</div>
			</div>
		</div>

		<div class="row mb-10">
			<div class="col-md-12">
				<div class="form-group">
					<label class="form-label">Group Description</label>
					<textarea type="text" style="height: 170px;" id="group_description" data-type="input" name="group_description" class="form-control mb-2"><?= $dataItems->group_description ?> </textarea>
				</div>
			</div>
		</div>

		<div class="row mt-8 mb-4" align="right">
			<div class="col-lg-12">
				<button class="btn btn-outline btn-outline-dashed btn-outline-success btn-active-light-success btn-sm fw-bold" type="button" id="btnAddSKU" data-type="modal" data-url="<?= base_url('/inventory_group/addSKU') ?>" data-fullscreenmodal="0">
					<i class="bi bi-plus"></i>
					Add New
				</button>
			</div>
		</div>

		<div class="row">
			<div class="col-xl-12">
				<table id="kt_datatable_vertical_scroll" class="table table-striped table-row-bordered gy-5 gs-7" style="border-collapse: collapse;width: 100%;border: 1px solid #000;">
					<thead>
						<tr class="fw-semibold fs-6 text-gray-800">
							<th class="min-w-150px">Product ID</th>
							<th class="min-w-300px">Product</th>
							<th class="min-w-100px">Size</th>
							<th class="min-w-200px">Brand</th>
							<th class="min-w-150px">Action</th>
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