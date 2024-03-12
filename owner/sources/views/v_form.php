<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<div class="d-flex flex-column scroll-y me-n7 pe-7">

	<input type="hidden" class="form-control" id="id" name="id" value="<?= isset($id) ? $id : '' ?>" />

	<div class="row">
		<div class="col-md-6">
			<div class="fv-row mb-7">
				<label class="required fw-semibold fs-6 mb-4">Source Name</label>
				<input type="text" class="form-control form-control-solid mb-3 mb-lg-0" id="source_name" value="<?= isset($source_name) ? $source_name : '' ?>" name="source_name" value="" data-type="input" autocomplete="off" />
			</div>
		</div>

		<div class="col-md-6">
			<div class="fv-row mb-7">
				<label class="required fw-semibold fs-6 mb-4">Status</label>
				<select class="form-select form-select-solid" data-control="select" data-placeholder="Select an option" id="status" name="status">
					<option value="1" <?= isset($status) ? $status == "1" ? "selected" : "" : "" ?>>Enable</option>
					<option value="2" <?= isset($status) ? $status == "2" ? "selected" : "" : "" ?>>Disable</option>
				</select>
			</div>
		</div>

		<div class="col-md-6" id="img_domain">
			<label class="required fw-semibold fs-6 mb-4">Source Image</label>

			<input type="hidden" id="old_source_image" name="old_source_image" value="<?= isset($source_image) ? $source_image : "" ?>" />
			<input type="hidden" id="old_source_icon" name="old_source_icon" value="<?= isset($source_icon) ? $source_icon : "" ?>" />

			<div class="fv-row mb-7 text-center">
				<div class="image-input image-input-empty" data-kt-image-input="true">
					<div id="SourceImage" class="image-input-wrapper w-125px h-125px" style="background-image: url(../assets/<?= isset($source_image) ? 'uploads/channels_image/' . $source_image : 'metronic/media/svg/avatars/blank.svg' ?>)">
					</div>

					<label class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" id="change_image" data-bs-toggle="tooltip" data-bs-dismiss="click" title="Change avatar">
						<i class="bi bi-pencil-fill fs-7"></i>
						<input type="file" name="avatar" accept=".png, .jpg, .jpeg" />
					</label>

					<span class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" id="remove_image" data-bs-toggle="tooltip" data-bs-dismiss="click" title="Cancel avatar">
						<i class="bi bi-x fs-2"></i>
					</span>
				</div>
			</div>

			<input type="hidden" id="source_image" name="source_image" />
			<input type="hidden" id="source_image1" name="source_image1" value="<?= isset($source_image) ? $source_image : "" ?>" />

		</div>
		<div class="col-md-6" id="icon_domain">
			<label class="required fw-semibold fs-6 mb-4">Source Icon</label>

			<div class="fv-row mb-7 text-center">
				<div class="image-input image-input-empty" data-kt-image-input2="true">
					<div id="SourceIcon" class="image-input-wrapper w-125px h-125px" style="background-image: url(../assets/<?= isset($source_icon) ? 'uploads/channels_image/' . $source_icon : 'metronic/media/svg/avatars/blank.svg' ?>)">
					</div>

					<label class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" id="change_icon" data-bs-toggle="tooltip" data-bs-dismiss="click" title="Change avatar">
						<i class="bi bi-pencil-fill fs-7"></i>
						<input type="file" name="avatar" accept=".png, .jpg, .jpeg" />
					</label>

					<span class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" id="remove_icon" data-bs-toggle="tooltip" data-bs-dismiss="click" title="Cancel avatar">
						<i class="bi bi-x fs-2"></i>
					</span>
				</div>
			</div>

			<input type="hidden" id="source_icon" name="source_icon" />
			<input type="hidden" id="source_icon1" name="source_icon1" value="<?= isset($source_icon) ? $source_icon : "" ?>" />

		</div>

		<div class="col-md-12">
			<div class="fv-row mb-7">
				<label class="required fw-semibold fs-6 mb-4">Source URL</label>
				<input type="text" class="form-control form-control-solid mb-3 mb-lg-0" id="source_url" value="<?= isset($source_url) ? $source_url : '' ?>" name="source_url" data-type="input" autocomplete="off" />
			</div>
		</div>


	</div>


	<div class="fv-row mb-7">
		<label class="required fw-semibold fs-6 mb-4">App Keys</label>
		<input type="text" class="form-control form-control-solid mb-3 mb-lg-0" id="app_keys" value="<?= isset($app_keys) ? $app_keys : '' ?>" name="app_keys" data-type="input" autocomplete="off" />
	</div>

	<div class="fv-row mb-7">
		<label class="required fw-semibold fs-6 mb-4">Secret Keys</label>
		<input type="text" class="form-control form-control-solid mb-3 mb-lg-0" id="secret_keys" value="<?= isset($secret_keys) ? $secret_keys : '' ?>" name="secret_keys" data-type="input" autocomplete="off" />
	</div>


</div>

<script>
	$(document).on("click", "#remove_image", function() {
		$("#source_image").val('');
	});
	$(document).on("click", "#remove_icon", function() {
		$("#source_icon").val('');
	});
	var imageInputElement = document.querySelector("[data-kt-image-input='true']");
	var imageInput = new KTImageInput(imageInputElement);
	imageInput.on("kt.imageinput.change", function(e) {
		var xx = e.wrapperElement;
		setTimeout(() => {
			var bg = $(xx).css("background-image")
			bg = bg.replace(/.\s?url\([\'\"]?/, '').replace(/[\'\"]?\)./, '')
			let convert = bg.replace('url("', '');
			let convert2 = convert.replace('");', '');
			$("#source_image").val(convert2);
			$("#source_image1").val(convert2);
		}, 100);
		$('#img_domain').find('.invalid-feedback').hide()

	});


	var imageInputElement2 = document.querySelector("[data-kt-image-input2='true']");
	var imageInput2 = new KTImageInput(imageInputElement2);
	imageInput2.on("kt.imageinput.change", function(ex) {
		var xx2 = ex.wrapperElement;
		setTimeout(() => {
			var bg2 = $(xx2).css("background-image")
			bg2 = bg2.replace(/.\s?url\([\'\"]?/, '').replace(/[\'\"]?\)./, '')
			let convertx = bg2.replace('url("', '');
			let convertx2 = convertx.replace('");', '');
			$("#source_icon").val(convertx2);
			$("#source_icon1").val(convertx2);
		}, 100);
		$('#icon_domain').find('.invalid-feedback').hide()

	});

	$('#status').select2({
		minimumResultsForSearch: Infinity,
	});
</script>