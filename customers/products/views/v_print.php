<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<input type="hidden" id="get_param" />

<div id="form_brand">
	<div class="d-flex flex-column scroll-y me-n7 pe-7">
		<div class="row">
			<input type="hidden" class="form-control" id="id" name="id" value="<?= isset($id) ? $id : '' ?>" />
			<div class="col-md-12">
				<div class="fv-row mb-7">
					<label class="fw-semibold fs-6 mb-4">Print QTY</label>
					<input type="number" id="print_qty" class="form-control form-control-solid mb-3 mb-lg-0" data-type="input" autocomplete="off" />
				</div>
			</div>
		</div>
	</div>
</div>