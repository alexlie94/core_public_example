<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<div id="form_master_warehouse">
	<input type="hidden" class="form-control" id="id" name="id" value="<?= isset($id) ? $id : '' ?>" />
	<div class="d-flex flex-column scroll-y me-n7 pe-7">
		<div class="row">
			<div class="col-md-6">
				<div class="fv-row mb-7">
					<label class="fw-semibold fs-6 mb-4">Warehouse Code</label>
					<input type="text" id="warehouse_code" name="warehouse_code"
						class="form-control form-control mb-3 mb-lg-0" placeholder="Enter Warehouse Code"
						data-type="input" autocomplete="off"
						value="<?= isset($warehouse_code) ? $warehouse_code : 'Auto' ?>" disabled />
				</div>
			</div>
			<div class="col-md-6">
				<div class="fv-row mb-7">
					<input type="hidden" class="form-control" id="id" name="id" value="<?= isset($id) ? $id : '' ?>" />

					<label class="required fw-semibold fs-6 mb-4">Warehouse Name</label>
					<input name="warehouse_name" id="warehouse_name" type="text"
						class="form-control form-control mb-3 mb-lg-0" placeholder="Enter Warehouse Name"
						value="<?= isset($warehouse_name) ? $warehouse_name : '' ?>" data-type="input"
						autocomplete="off" />
				</div>
			</div>
			<div class="col-md-6">
				<div class="fv-row mb-7">
					<label class="required fw-semibold fs-6 mb-4">Email</label>
					<input type="email" id="email" name="email" class="form-control form-control mb-3 mb-lg-0"
						placeholder="Enter Email" value="<?= isset($email) ? $email : '' ?>" data-type="input"
						autocomplete="off" />
				</div>
			</div>
			<div class="col-md-6">
				<div class="fv-row mb-7">
					<label class="required fw-semibold fs-6 mb-4">Phone</label>
					<input type="text" id="phone" name="phone" class="form-control form-control mb-3 mb-lg-0"
						placeholder="Enter Phone" value="<?= isset($phone) ? $phone : '' ?>" data-type="input"
						autocomplete="off" />
				</div>
			</div>
			<div class="col-md-12">
				<div class="fv-row mb-7">
					<label class="required fw-semibold fs-6 mb-4">Address</label>
					<textarea type="text" id="address" name="address" rows="4"
						class="form-control form-control mb-3 mb-lg-0" placeholder="Enter Address" data-type="input"
						autocomplete="off"><?= isset($address) ? $address : '' ?></textarea>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	// Repeating
	Inputmask({
		"mask": "9",
		"repeat": 12,
		"greedy": false
	}).mask("#phone");
</script>