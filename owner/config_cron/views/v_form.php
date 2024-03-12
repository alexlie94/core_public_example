<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<div class="d-flex flex-column scroll-y me-n7 pe-7">

	<input type="hidden" class="form-control" id="id" name="id" value="<?= isset($id) ? $id : '' ?>" />

	<div class="row">
		<div class="col-md-6">
			<div class="fv-row mb-7">
				<label class="required fw-semibold fs-6 mb-4">Cron Controller</label>
				<input type="text" class="form-control form-control-solid mb-3 mb-lg-0" id="cron_controller" value="<?= isset($cron_controller) ? $cron_controller : '' ?>" name="cron_controller" value="" data-type="input" autocomplete="off" />
			</div>
		</div>

		<div class="col-md-6">
			<div class="fv-row mb-7">
				<label class="required fw-semibold fs-6 mb-4">Cron Description</label>
				<input type="text" class="form-control form-control-solid mb-3 mb-lg-0" id="cron_desc" value="<?= isset($cron_desc) ? $cron_desc : '' ?>" name="cron_desc" value="" data-type="input" autocomplete="off" />
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

	</div>

</div>

<script>
	$('#status').select2({
		minimumResultsForSearch: Infinity,
	});
</script>