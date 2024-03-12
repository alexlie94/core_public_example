<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<div class="modal-upload"></div>
<!--begin::Stepper-->
<div class="stepper stepper-pills" id="kt_stepper_example_basic">
	<div class="stepper-nav flex-center flex-wrap mb-10">
		<div class="d-flex flex-stack flex-top">
			<!--begin::Wrapper-->
			<div class="me-2">
				<button type="button" class="btn btn-light btn-active-light-danger" data-kt-stepper-action="previous" id="previous_stepper">
					<i class="bi bi-arrow-left fs-2x"></i>
				</button>
			</div>
			<!--end::Wrapper-->

			<!--begin::Nav-->
			<div class="stepper-nav flex-center flex-wrap mb-10">

				<!--begin::Step 1-->
				<div class="stepper-item mx-8 my-4 current" data-kt-stepper-element="nav">
					<!--begin::Wrapper-->
					<div class="stepper-wrapper d-flex align-items-center mt-10">
						<!--begin::Icon-->
						<div class="stepper-icon w-40px h-40px">
							<i class="stepper-check fas fa-check"></i>
							<span class="stepper-number">1</span>
						</div>
						<!--end::Icon-->

						<!--begin::Label-->
						<div class="stepper-label">
							<h3 class="stepper-title">
								Step 1
							</h3>

							<div class="stepper-desc">
								Fill Transfer Out Form
							</div>
						</div>
						<!--end::Label-->
					</div>
					<!--end::Wrapper-->

					<!--begin::Line-->
					<div class="stepper-line h-40px"></div>
					<!--end::Line-->
				</div>
				<!--end::Step 1-->

				<!--begin::Step 2-->
				<div class="stepper-item mx-8 my-4" data-kt-stepper-element="nav">
					<!--begin::Wrapper-->
					<div class="stepper-wrapper d-flex align-items-center mt-10">
						<!--begin::Icon-->
						<div class="stepper-icon w-40px h-40px">
							<i class="stepper-check fas fa-check"></i>
							<span class="stepper-number">2</span>
						</div>
						<!--begin::Icon-->

						<!--begin::Label-->
						<div class="stepper-label">
							<h3 class="stepper-title">
								Step 2
							</h3>

							<div class="stepper-desc">
								Select Product SKU
							</div>
						</div>
						<!--end::Label-->
					</div>
					<!--end::Wrapper-->

					<!--begin::Line-->
					<div class="stepper-line h-40px"></div>
					<!--end::Line-->
				</div>
				<!--end::Step 2-->
			</div>
			<!--end::Nav-->

			<!--begin::Wrapper-->
			<div>
				<button type="button" class="btn btn-light btn-active-light-primary" data-kt-stepper-action="submit" id="submit_stepper" data-type="modal" data-url="<?= base_url('tito2/return_back') ?>" data-fullscreenmodal="0">
					<i class="bi bi-check2-all fs-2x"></i>
				</button>

				<button type="button" class="btn btn-light btn-active-light-primary" data-kt-stepper-action="next" id="next_stepper">
					<i class="bi bi-arrow-right fs-2x"></i>
				</button>
			</div>
			<!--end::Wrapper-->
		</div>
	</div>

	<!--begin::Form-->
	<form class="form w-lg-500px mx-auto" novalidate="novalidate" id="kt_stepper_example_basic_form">
		<!--begin::Group-->
		<div class="mb-5">
			<!--begin::Step 1-->
			<div class="flex-column current" data-kt-stepper-element="content">
				<div class="row">

					<input type="hidden" id="id" name="id" value="<?= isset($id) ? $id : '' ?>">

					<div class="col-md-4 mb-4">
						<label class="required fw-semibold fs-6 mb-4">From</label>
						<select class="form-select form-select" data-control="select2" data-placeholder="Select Status" id="from_warehouse" name="from_warehouse" disabled>
							<option></option>
							<?php foreach ($warehouse_id as $value) { ?>
								<option value="<?= $value['id'] ?>" <?= isset($from_users_ms_warehouses_id) ? $from_users_ms_warehouses_id == $value['warehouse_name'] ? 'selected' : '' : '' ?>>
									<?= $value['warehouse_name'] ?>
								</option>
							<?php } ?>
						</select>
					</div>

					<div class="col-md-4 mb-4">
						<label class="required fw-semibold fs-6 mb-4">To</label>
						<select class="form-select form-select" data-control="select2" data-placeholder="Select Status" id="to_warehouse" name="to_warehouse" disabled>
							<option></option>
							<?php if (!empty($id)) { ?>
								<?php foreach ($warehouse_id_custom as $value) { ?>
									<option value="<?= $value['id'] ?>" <?= isset($to_users_ms_warehouses_id) ? $to_users_ms_warehouses_id == $value['warehouse_name'] ? 'selected' : '' : '' ?>>
										<?= $value['warehouse_name'] ?>
									</option>
								<?php } ?>
							<?php } ?>

						</select>
					</div>

					<div class="col-md-4 mb-4">
						<label class="required fw-semibold fs-6 mb-4">PIC</label>
						<input type="text" class="form-control form-control mb-3 mb-lg-0" id="assignee" value="<?= isset($assignee) ? $assignee : '' ?>" name="assignee" value="" data-type="input" autocomplete="off" placeholder="Enter Assignee" disabled />
					</div>

				</div>
				<div class="row">
					<div class="col-md-12 mb-4">
						<label class="fw-semibold fs-6 mb-4">Description</label>
						<textarea type="text" id="desc" name="desc" class="form-control form-control mb-3 mb-lg-0" rows="4" placeholder="Description" data-type="input" autocomplete="off" disabled><?= isset($description) ? $description : '' ?></textarea>
					</div>
				</div>
			</div>
			<!--begin::Step 1-->

			<!--begin::Step 1-->
			<div class="flex-column" data-kt-stepper-element="content">

				<div class="card shadow-sm border-1" data-bs-theme="light" style="height: auto;">
					<!--begin::Body-->
					<div class="card-body">
						<!--begin::Row-->
						<div class="row align-items-center" style="height: auto;">
							<!--begin::Col-->
							<div class="col-md-12">
								<!--begin::Title-->
								<div class="text-gray-800 mb-6 pt-6">
									<span class="fs-2qx fw-bold">
										<?= isset($to_number) ? $to_number : 'AUTO' ?>
									</span>
								</div>
								<!--end::Title-->

								<div class="d-flex flex-wrap">
									<div class="col-md-3">
										<!--begin::Stat-->
										<div class="rounded min-w-125px py-3 px-4 my-1 me-6 mb-4" style="border: 1px dashed rgba(0, 0, 0, 0.5)">

											<!--begin::Label-->
											<div class="fw-semibold fs-6 text-gray-800 opacity-50">From Warehouse</div>
											<!--end::Label-->

											<!--begin::Number-->
											<div class="d-flex align-items-center">
												<i class="bi bi-house-door-fill fs-2x text-gray-800 me-2"></i>
												<div class="text-gray-800 fs-2 fw-bold counted" data-kt-countup="true" data-kt-countup-value="4368" data-kt-initialized="1" id="from_warehouse_2">
													<?= isset($from_users_ms_warehouses_id) ? $from_users_ms_warehouses_id : '' ?>
												</div>
											</div>
											<!--end::Number-->

										</div>
										<!--end::Stat-->
									</div>
									<div class="col-md-3">
										<!--begin::Stat-->
										<div class="rounded min-w-125px py-3 px-4 my-1 me-6 mb-4" style="border: 1px dashed rgba(0, 0, 0, 0.5)">

											<!--begin::Label-->
											<div class="fw-semibold fs-6 text-gray-800 opacity-50">To Warehouse</div>
											<!--end::Label-->

											<!--begin::Number-->
											<div class="d-flex align-items-center">
												<i class="bi bi-house-door-fill fs-2x text-gray-800 me-2"></i>
												<div class="text-gray-800 fs-2 fw-bold counted" data-kt-countup="true" data-kt-initialized="1" id="to_warehouse_2">
													<?= isset($to_users_ms_warehouses_id) ? $to_users_ms_warehouses_id : '' ?>
												</div>
											</div>
											<!--end::Number-->
										</div>
										<!--end::Stat-->
									</div>
									<div class="col-md-3">
										<!--begin::Stat-->
										<div class="rounded min-w-125px py-3 px-4 my-1 me-6 mb-4" style="border: 1px dashed rgba(0, 0, 0, 0.5)">

											<!--begin::Label-->
											<div class="fw-semibold fs-6 text-gray-800 opacity-50">Assignee</div>
											<!--end::Label-->

											<!--begin::Number-->
											<div class="d-flex align-items-center">
												<i class="bi bi-person-fill fs-2x text-gray-800 me-2"></i>
												<div class="text-gray-800 fs-2 fw-bold counted" data-kt-countup="true" data-kt-initialized="1" id="assignee_2">
													<?= isset($assignee) ? $assignee : '' ?>
												</div>
											</div>
											<!--end::Number-->
										</div>
										<!--end::Stat-->
									</div>
									<div class="col-md-3">
										<!--begin::Stat-->
										<div class="rounded min-w-125px py-3 px-4 my-1 mb-4" style="border: 1px dashed rgba(0, 0, 0, 0.5)">

											<!--begin::Label-->
											<div class="fw-semibold fs-6 text-gray-800 opacity-50">Status</div>
											<!--end::Label-->

											<!--begin::Number-->
											<div class="d-flex align-items-center">
												<?php
												if ($status == 'Close') {
													echo '<i class="bi bi-x-circle fs-2x text-gray-800 me-2"></i>';
												} else if ($status == 'In Progress') {
													echo '<i class="bi bi-hourglass-split fs-2x text-gray-800 me-2"></i>';
												} else {
													echo '<i class="bi bi-check-circle fs-2x text-gray-800 me-2"></i>';
												}
												?>
												<div class="text-gray-800 fs-2 fw-bold counted" data-kt-countup="true" data-kt-initialized="1">
													<?= isset($status) ? $status : 'Open' ?>
												</div>
											</div>
											<!--end::Number-->
										</div>
										<!--end::Stat-->
									</div>
									<div class="col-md-12">
										<!--begin::Stat-->
										<div class="rounded min-w-125px py-3 px-4 my-1 mb-4" style="border: 1px dashed rgba(0, 0, 0, 0.5)">

											<!--begin::Label-->
											<div class="fw-semibold fs-6 text-gray-800 opacity-50">Description</div>
											<!--end::Label-->

											<!--begin::Number-->
											<div class="d-flex align-items-center">
												<div class="text-gray-800 fs-2 fw-bold counted" data-kt-countup="true" data-kt-initialized="1" id="desc_2">
													<?= isset($description) ? $description : '' ?>
												</div>
											</div>
											<!--end::Number-->
										</div>
										<!--end::Stat-->
									</div>
								</div>
							</div>
							<!--end::Col-->
						</div>
						<!--end::Row-->
					</div>
					<!--end::Body-->
				</div>
				<div class="row mt-10" id="display_show_tito">
					<!--begin::Datatable-->
					<input type="hidden" name="paging_datatables" value="0" class="halaman" style="display: none"><input type="hidden" name="draw" value="1" class="draw_datatables" style="display: none">
					<div class="table-responsive">
						<table id="show_tito" class="table table-hover table-rounded align-middle gs-0 gy-4">
							<thead>
								<tr class="fw-bold text-muted bg-light">
									<th class="ps-4 rounded-start text-center">#</th>
									<th>SKU</th>
									<th>PRODUCT NAME</th>
									<th>BRAND</th>
									<th>LOCATION</th>
									<th>QTY</th>
									<th class="rounded-end">QTY Received</th>
								</tr>
							</thead>
							<tbody>
								<?php if (!empty($tito_details_data)) { ?>
									<?php $no = 0;
									$no1 = 1;
									foreach ($tito_details_data as $value) { ?>
										<tr data-sku="<?= $value['sku'] ?>">
											<td style='text-align: center;vertical-align: middle;'>
												<?= $no1 ?>
												<input type="hidden" id="tito_detail_id_<?= $no ?>" name="detail_id[]" value="<?= $value['id'] ?>">
												<input type="hidden" id="" name="product_id[]" value="<?= $value['users_ms_inventory_storages_id'] ?>">
											</td>
											<td style='vertical-align: middle;'>
												<?= $value['sku'] ?>
												<input type="hidden" id="product_sku_<?= $no ?>" name="product_sku[]" value="<?= $value['sku'] ?>">
											</td>
											<td style='vertical-align: middle;'>
												<?= $value['product_name'] ?>
												<input type="hidden" id="product_name_<?= $no ?>" name="product_name[]" value="<?= $value['product_name'] ?>">
											</td>
											<td style='vertical-align: middle;'>
												<?= $value['brand_name'] ?>
												<input type="hidden" id="brand_name_<?= $no ?>" name="brand_name[]" value="<?= $value['brand_name'] ?>">
											</td>
											<td style='vertical-align: middle;'>
												<?= $value['warehouse_name'] ?>
												<input type="hidden" id="warehouse_name_<?= $no ?>" name="warehouse_name[]" value="<?= $value['warehouse_name'] ?>">
											</td>
											<td style='vertical-align: middle;'>
												<?= $value['qty'] ?>
												<input type="hidden" id="qty_sku_<?= $no ?>" name="qty_sku[]" value="<?= $value['qty'] ?>">
											</td>
											<td style='vertical-align: middle;'>
												<div class="input-group input-group-sm"><input type="number" class="form-control qty_received" id="qty_received_<?= $no ?>" data-sku="<?= $value['sku'] ?>" name="qty_received[]" value="0" min="0" onkeyup="restrictInput(event)" onchange="validateAndSetToZero(this)">
												</div>
											</td>
										</tr>
									<?php $no++;
										$no1++;
									} ?>
								<?php } ?>
							</tbody>
							<tfoot>

							</tfoot>
						</table>
					</div>
					<div class="paginationDatatables"></div>
					<div class="border-top border-2 border-gray-300 mb-10"></div>
					<!--end::Datatable-->
				</div>
				<div class="row mt-10" id="new_display_show_tito">
					<div class="table-responsive">
						<table id="show_new_tito" class="table table-hover table-rounded align-middle gs-0 gy-4">
							<thead>
								<tr class="fw-semibold fs-6 text-gray-800">
									<th class="ps-4 rounded-start text-center">NO</th>
									<th class="min-w-100px">SKU</th>
									<th class="min-w-200px">PRODUCT NAME</th>
									<th class="min-w-50px">BRAND</th>
									<th class="min-w-50px">QTY</th>
									<th class="min-w-50px">QTY RECEIVED</th>
									<th class="min-w-50px">QTY LOST</th>
									<th class="rounded-end">DESCRIPTION</th>
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<!--begin::Step 1-->

		</div>
		<!--end::Group-->

		<!--begin::Actions-->

		<!--end::Actions-->
	</form>
	<!--end::Form-->
</div>
<!--end::Stepper-->
</div>
<script>
	$("select").select2({
		minimumResultsForSearch: Infinity,
	});

	setTimeout(() => {
		var target = $(".modal-upload").parent().parent().parent('.modal-content')[0];
		var blockUI = new KTBlockUI(target);
	}, 300);
</script>