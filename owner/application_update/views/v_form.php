<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<div class="d-flex flex-column scroll-y me-n7 pe-7">

	<input type="hidden" class="form-control" id="id" name="id" value="<?= isset($id) ? $id : '' ?>" />

	<div class="row">
		<div class="col-md-4">
			<div class="fv-row mb-7">
				<label class="required fw-semibold fs-6 mb-4">Title</label>
				<input type="text" class="form-control form-control-solid mb-3 mb-lg-0" id="title" value="<?= isset($title) ? $title : '' ?>" name="title" value="" data-type="input" autocomplete="off" />
			</div>
		</div>

		<!--begin::Col-->
		<div class="col-md-4 fv-row mb-7">
			<!--begin::Input-->
			<label class="required fs-6 fw-semibold mb-4">Launch Date</label>
			<div class="input-group input-group-solid">
				<span class="input-group-text" id="basic-addon1"><i class="bi bi-calendar-plus"></i></span>
				<input class="form-control form-control-solid" placeholder="Select Launch Date" name="launch_date" id="launch_date" readonly type="text" data-type="input" data-type="input" autocomplete="off" value="<?= isset($launch_date) ? $launch_date : '' ?>">
			</div>
			<!--end::Input-->
		</div>
		<!--end::Col-->

		<div class="col-md-4">
			<div class="fv-row mb-7">
				<label class="required fw-semibold fs-6 mb-4">Status</label>
				<select class="form-select form-select-solid" data-control="select" data-placeholder="Select an option" id="status" name="status">
					<option value="1" <?= isset($status) ? $status == "1" ? "selected" : "" : "" ?>>Enable</option>
					<option value="2" <?= isset($status) ? $status == "2" ? "selected" : "" : "" ?>>Disable</option>
				</select>
			</div>
		</div>

	</div>

	<div class="row">
		<div class="col-md-12">
			<label class="required fw-semibold fs-6 mb-4">Content</label>
			<textarea name="kt_docs_ckeditor_classic" id="kt_docs_ckeditor_classic"><?= isset($content) ? $content : '' ?></textarea>
			<input type="hidden" name="v_content" id="v_content" value="">
		</div>
	</div>

</div>

<script>
	var instance;
	ClassicEditor.create(document.querySelector("#kt_docs_ckeditor_classic")).then(editor => instance = editor);

	$("body").on('DOMSubtreeModified', ".ck.ck-content", function() {
		var x = $('.ck.ck-content').html();
		if (x == '<p><br data-cke-filler="true"></p>' || x == '<h2><br data-cke-filler="true"></h2>' || x ==
			'<h3><br data-cke-filler="true"></h3>' || x == '<h4><br data-cke-filler="true"></h4>') {
			$('#v_content').val("");
		} else {
			$('#v_content').val(1);
		}
		if ($('#v_content').val() == 1) {
			$('#v_content').parent().find('.invalid-feedback').hide();
		}
	});
</script>