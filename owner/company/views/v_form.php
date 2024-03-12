<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<div class="d-flex flex-column scroll-y me-n7 pe-7">
	<div class="row">
		<div class="col-md-6">
			<div class="fv-row mb-7">
				<input class="form-control" id="id" name="id" type="hidden" value="<?= isset($id) ? $id : "" ?>" />
				<label class="required fw-semibold fs-6 mb-4">Company Code</label>
				<input type="text" id="company_code" name="company_code" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Company Code" value="<?= isset($company_code) ? $company_code : "" ?>" data-type="input" autocomplete="off" />
			</div>
		</div>
		<div class="col-md-6">
			<div class="fv-row mb-7">
				<label class="required fw-semibold fs-6 mb-4">Status</label>
				<label class="form-check form-switch form-check-custom form-check-solid">
					<input class="form-check-input" type="checkbox" value="enabled" <?= (isset($status) ? ($status === "enabled" ? "checked=checked" : "") : "checked=checked") ?> name="status" id="status">
					<span class="form-check-label fw-semibold text-muted">Active</span>
				</label>
			</div>
		</div>
	</div>


	<div class="fv-row mb-7">
		<label class="required fw-semibold fs-6 mb-4">Company Name</label>
		<input type="text" id="company_name" name="company_name" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Company Code" value="<?= isset($company_name) ? $company_name : "" ?>" data-type="input" autocomplete="off" />
	</div>



	<div class="fv-row mb-7">
		<label class="required fw-semibold fs-6 mb-4">Fullname</label>
		<input type="text" id="fullname" name="fullname" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Fullname" value="<?= isset($fullname) ? $fullname : "" ?>" data-type="input" autocomplete="off" />
	</div>
	<div class="row">
		<div class="col-md-6">
			<div class="fv-row mb-7">
				<label class="required fw-semibold fs-6 mb-4">Email Login</label>
				<input type="text" id="email" name="email" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Email" value="<?= isset($email) ? $email : "" ?>" data-type="input" autocomplete="off" />
			</div>
		</div>
		<div class="col-md-6">
			<div class="fv-row mb-7">
				<label class="<?= isset($update) ? "" : "required" ?> fw-semibold fs-6 mb-4">Password Login</label>
				<input type="text" id="password" name="password" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Password" value="" data-type="input" autocomplete="off" />
			</div>
		</div>
	</div>
</div>
<!--end::Scroll-->