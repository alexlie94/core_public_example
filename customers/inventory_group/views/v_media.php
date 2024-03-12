<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<input type="hidden" id="group_id" name="group_id" value="<?= $dataItems->id ?>">
<div id="kt_app_content" class="app-content flex-column-fluid modal-upload">
	<div id="kt_app_content_container" class="app-container container-fluid ">

		<div class="col-md-6 mb-10">
			<div class="card shadow-lg">
				<div class="card-body">
					<table class="table table-striped table-row-bordered gy-5 gs-7">
						<thead>
							<tr class="fw-semibold fs-6 text-gray-800">
								<th class="min-w-100px">Product GID</th>
								<th class="min-w-200px">Product Group</th>
								<th class="min-w-200px">Brand</th>
							</tr>
						</thead>
						<tbody>
							<tr class="odd">
								<td><?= isset($dataItems->group_code) ? $dataItems->group_code : '' ?></td>
								<td><?= isset($dataItems->group_name) ? $dataItems->group_name : '' ?></td>
								<td><?= empty($dataItems->brand_name) ? '-' : $dataItems->brand_name ?></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>

		<div class="col-md-12">
			<div class="card shadow-lg">
				<div class="card-header">
					<h3 class="card-title"></h3>

					<div class="row mt-8 mb-4" align="right">
						<div class="col-lg-12">
							<button class="btn btn-success hover-scale" type="button" id="btnAddImage" data-type="modal" data-url="<?= base_url('/inventory_group/add_image/'. $dataItems->id) ?>" data-fullscreenmodal="0">
								<i class="bi bi-plus"></i>
								Create New
							</button>
						</div>
					</div>
				</div>
				<div class="card-body">
					<table id="datatable_media" class="table table-striped table-row-bordered gy-5 gs-7">
						<thead>
							<tr class="fw-semibold fs-6 text-gray-800">
								<th>Image</th>
								<th>Media Name</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
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