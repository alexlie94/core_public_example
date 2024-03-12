<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<div class="d-flex flex-column flex-column-fluid">

	<div id="kt_app_content" class="app-content flex-column-fluid">
		<div id="kt_app_content_container" class="app-container container-fluid">

			<div class="row mt-3">
				<div class="card shadow-sm">

					<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6" style="margin-bottom: -30px;">
						<div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
							<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
								<h1
									class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
									<?= $titlePage ?>
								</h1>
							</div>
						</div>
					</div>

					<div class="card-body">
						<form id="formSearch" class="form formSearch" autocomplete="off">
							<div class="row mb-10 mt-5">

								<div class="col-md-2">
									<label class="fs-5 fw-semibold mb-2">Search By</label>
									<select class="form-select" name="searchBy" id="searchBy"
										aria-label="Please Select">
										<option value="">Please Select</option>
										<?php
										foreach ($searchBy as $key => $value) {
											$selected = $key == "productid" ? "selected" : "";
											echo "<option value='{$key}' {$selected}>{$value}</option>";
										}
										?>
									</select>
								</div>

								<div class="col-md-3">
									<input type="text" class="form-control mt-9" id="searchValue" name="searchValue"
										placeholder="" autocomplete="off">
								</div>

								<div class="col-md-7">
									<div>
										<label class="fs-5 fw-semibold mb-2">Display</label>
										<select class="form-select" name="searchBy" id="searchBy"
											aria-label="Please Select">
											<option value="">Please Select</option>
											<?php
											foreach ($source as $value) {
												$selected = $key == "productid" ? "selected" : "";
												echo "<option>{$value}</option>";
											}
											?>
										</select>
									</div>
									<div class="d-flex flex-end gap-2 gap-lg-3 mt-9">
										<button type="button"
											class="btn btn-outline btn-outline-dashed btn-outline-info btn-active-light-info hover-scale btn-sm massUpload"
											data-type="modal" data-url="<?= base_url('inventory_group/mass_upload') ?> "
											data-fullscreenmodal="1">
											<i class="bi bi-file-earmark-arrow-up-fill fs-4 me-2"></i>Mass
											Upload</button>
										<button type="button"
											class="btn btn-outline btn-outline-dashed btn-outline-warning btn-active-light-warning hover-scale btn-sm downloadView">
											<i class="fa-solid fa-cloud-download fs-4 me-2"></i>Download
											View</button>
										<button
											class="btn btn-outline btn-outline-dashed btn-outline-success btn-active-light-success hover-scale btn-sm fw-bold"
											data-fullscreenmodal="1" type="button" id="btnAdd" data-type="modal"
											data-url="<?= base_url('inventory_group/insert') ?> ">
											<i class="fa-solid fa-plus fs-4 me-2"></i>
											Create Product</button>
									</div>
								</div>
							</div>

							<div class="row mb-10">
								<div class="col-md-2">
								</div>

								<div class="col-md-3">
									<div class="d-flex gap-4 gap-lg-3 ml-4">
										<?php
										$no = 1;
										foreach ($lookupValue as $result) {
											echo "	<div class='form-check form-check-custom form-check-success form-check-solid'>
										<input class='form-check' type='checkbox' id='lookup_status' name='lookup_status_{$no}' value='{$result->lookup_code}' />
										<label class='form-check-label' for=''>
											{$result->lookup_name}
										</label>
									</div>";

											$no++;
										}
										?>
									</div>
								</div>
							</div>

							<div class="row mb-10">
								<div class="col-md-2">
								</div>

								<div class="col-md-6">
									<div class="row">
										<div class="col d-flex flex-column align-items-center justify-content-center">
											<div class="input-group input-group-solid">
												<span class="input-group-text" id="basic-addon1">
													<i class="bi bi-calendar-plus"></i>
												</span>
												<input class="form-control form-control-solid" placeholder="dd mm yyyy"
													name="start_date" id="start_date" type="text" data-type="input">
											</div>
										</div>

										<div class="col d-flex flex-column align-items-center justify-content-center">
											<span class="input-group-text"><strong>To</strong></span>
										</div>

										<div class=" col d-flex flex-column align-items-center justify-content-center">
											<div class="input-group input-group-solid">
												<input class="form-control form-control-solid" placeholder="dd mm yyyy"
													name="end_date" id="end_date" type="text" data-type="input">
												<span class="input-group-text" id="basic-addon3">
													<i class="bi bi-calendar-plus"></i>
												</span>
											</div>
										</div>
									</div>
								</div>

							</div>
						</form>

						<div class="row mb-5">
							<div class="d-flex flex-end gap-2 gap-lg-3">
								<button type="button" id="btnResetSearch"
									class="btn btn-outline btn-outline-dashed btn-outline-danger btn-active-light-danger hover-scale btn-sm fw-bold">
									<i class="fa-solid fa-refresh fs-4 me-2"></i>Reset</button>
								<button type="button" id="btnSearch" onclick="reloadDatatables()"
									class="btn btn-outline btn-outline-dashed btn-outline-primary btn-active-light-primary hover-scale btn-sm fw-bold">
									<i class="fa-solid fa-search fs-4 me-2"></i>Search</button>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="row mt-6">
				<div class="card shadow-sm">
					<div class="card-body">
						<?= $table ?>
					</div>
				</div>
			</div>

		</div>
	</div>

</div>