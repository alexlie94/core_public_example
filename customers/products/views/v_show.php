<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<div class="d-flex flex-column flex-column-fluid">
	<div id="kt_app_content" class="app-content flex-column-fluid">
		<div id="kt_app_content_container" class="app-container container-fluid">

			<div class="card shadow-sm">
				<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6" style="margin-bottom: -30px;">
					<div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
						<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
							<h1 class="page-heading d-flex text-dark fw-bold fs-1 flex-column justify-content-center my-0">
								<?= $titlePage ?>
							</h1>
						</div>
					</div>
				</div>

				<div class="d-flex flex-end align-items-center p-5">
					<div class="d-flex flex-end gap-2 gap-lg-3">
						<button type="button" class="btn btn-outline btn-outline-dashed btn-outline-warning btn-active-light-warning hover-scale btn-sm downloadView">
							<i class="fa-solid fa-cloud-download fs-4 me-2"></i>
							Download View
						</button>
						<button class="btn btn-outline btn-outline-dashed btn-outline-success btn-active-light-success hover-scale btn-sm fw-bold" data-fullscreenmodal="1" type="button" id="btnAdd" data-type="modal" data-url="<?= base_url('products/insert') ?> " data-fullscreenmodal="0">
							<i class="fa-solid fa-plus fs-4 me-2"></i>
							Create Product
						</button>
					</div>
				</div>

				<div class="row p-8" style="margin-top: -17px;">

					<div class="col-12">
						<label for="searchBy" class="fs-5 fw-semibold mb-3">Search By:</label>
					</div>

					<div class="col-md-2 d-flex align-items-center">
						<select class="form-select form-select-sm w-100" name="searchBy" id="searchBy" aria-label="Please Select" data-type="select">
							<?php
							foreach ($searchBy as $key => $value) {
								$selected = $key == "productid" ? "selected" : "";
								echo "<option value='{$key}' {$selected}>{$value}</option>";
							}
							?>
						</select>
					</div>

					<div style="margin-left: -20px;" class="col-md-3 d-flex align-items-center">
						<div class="input-group">
							<input type="text" class="form-control form-control-sm " id="searchValue" name="searchValue" placeholder="" autocomplete="off" data-type="input">
							<span class="input-group-text">
								<i class="fas fa-search"></i>
							</span>
						</div>
					</div>

					<div class="col-md-1 d-flex align-items-center" style="width: 12%;">
						<select class="form-select form-select-sm w-100" name="searchBy" id="searchBy" aria-label="Please Select" data-type="select">
							<option value="">Status</option>
							<?php
							foreach ($lookupValue as $result) {
								echo "<option value='{$result->lookup_name}'>{$result->lookup_name}</option>";
							}
							?>
						</select>
					</div>

					<div class="col-md-1 d-flex align-items-center" style="width: 14%;">
						<div class="input-group">
							<span class="input-group-text" id="basic-addon1">
								<i class="bi bi-calendar-plus"></i>
							</span>
							<input class="form-control form-control-sm" placeholder="dd mm yyyy" name="start_date" id="start_date" type="text" readonly="readonly" data-type="input">
						</div>
					</div>

					<div class="col-md-1 d-flex flex-column align-items-center justify-content-center">
						<label class="text-bold fs-2">-</label>
					</div>

					<div class="col-md-1 d-flex align-items-center" style="width: 14%;">
						<div class="input-group">
							<input class="form-control form-control-sm" placeholder="dd mm yyyy" name="end_date" id="end_date" type="text" readonly="readonly" data-type="input">
							<span class="input-group-text" id="basic-addon1">
								<i class="bi bi-calendar-plus"></i>
							</span>
						</div>
					</div>

					<div class="col-md-1 d-flex align-items-center">
						<a href="#" class="btn btn-icon btn-outline-dashed btn-danger hover-scale btn-sm" style="background-color: #E74C3C">
							<i class="fa-solid fa-refresh fs-4"></i>
						</a>
					</div>
				</div>

				<div class="card-body">
					<?= $table ?>
				</div>
			</div>

		</div>
	</div>
</div>