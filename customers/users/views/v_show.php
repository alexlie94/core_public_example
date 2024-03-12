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

								<div class="col-md-3">
									<label class="fs-5 fw-semibold mb-2">Fullname</label>
									<input class="form-control" name="fullname_filter" id="fullname_filter" type="text"
										data-type="input" data-library="">
								</div>

								<div class="col-md-3">
									<label class="fs-5 fw-semibold mb-2">Email</label>
									<input class="form-control" name="email_filter" id="email_filter" type="text"
										data-type="input" data-library="">
								</div>

								<div class="col-md-6">
									<div class="d-flex flex-end gap-2 gap-lg-3 mt-10">
										<button
											class="btn btn-outline btn-outline-dashed btn-outline-success btn-active-light-success hover-scale btn-sm fw-bold"
											type="button" id="btnAdd" data-type="modal"
											data-url="<?= base_url('users/insert') ?>" data-fullscreenmodal="0">
											<i class="fa-solid fa-plus fs-4 me-2"></i>Add New Users</button>
									</div>
								</div>
							</div>
							<div class="row mb-5 mt-5">
								<div class="col-md-3">
								</div>

								<div class="col-md-3">
									<div class="d-flex flex-row fv-row">
										<div class="form-check form-check-custom mb-5 "><input type="checkbox"
												class="form-check-input me-3" name="inputStatus_filter[]" value="enable"
												data-type="checkbox"><label class="form-check-label"
												for="inputStatus_filter">
												Enabled
											</label></div>
										<div class="form-check form-check-custom mb-5 ms-5"><input type="checkbox"
												class="form-check-input me-3" name="inputStatus_filter[]"
												value="disable" data-type="checkbox"><label class="form-check-label"
												for="inputStatus_filter">
												Disable
											</label></div>
									</div>
								</div>
							</div>
							<div class="row mb-10 mt-5">
								<div class="col-md-6">
									<label for="rolename_filter" class="form-label fw-semibold fs-6 mb-2">Role
										Name</label>
									<select class="form-select" name="rolename_filter[]" data-control="select2"
										data-close-on-select="false" data-placeholder="Please Select"
										data-allow-clear="true" multiple="multiple" data-type="select-multiple">
										<option value="" disabled hidden>Please Select</option>
										<?php
										foreach ($dataRoleAdmin as $key => $val) {
											echo "<option value='{$val->id}'>{$val->role_name}</option>";
										}
										?>
									</select>
								</div>
							</div>
						</form>

						<div class="row mb-5">
							<div class="d-flex flex-end gap-2 gap-lg-3">
								<button type="button" id="btnSearchReset"
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