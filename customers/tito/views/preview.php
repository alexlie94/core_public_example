<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

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
								<input type="hidden" id="from_warehouse_3" name="from_warehouse_3" value="<?= isset($from_users_ms_warehouses_id) ? $from_users_ms_warehouses_id : '' ?>">
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

<div class="row mt-10" id="new_display_show_tito">
	<div class="table-responsive">
		<table id="show_new_tito" class="table table-hover table-rounded align-middle gs-0 gy-4">
			<thead>
				<tr class="fw-semibold fs-6 text-gray-800">
					<th class="ps-4 rounded-start text-center">NO</th>
					<th class="min-w-100px">SKU</th>
					<th class="min-w-200px">PRODUCT NAME</th>
					<th class="min-w-50px">BRAND</th>
					<th class="min-w-50px">WAREHOUSE NAME</th>
					<th class="min-w-50px">QTY</th>
					<th class="min-w-50px">QTY RECEIVED</th>
					<th class="min-w-50px">QTY LOST</th>
					<th class="rounded-end">DESCRIPTION</th>
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
							</td>
							<td style='vertical-align: middle;'>
								<?= $value['sku'] ?>
							</td>
							<td style='vertical-align: middle;'>
								<?= $value['product_name'] ?>
							</td>
							<td style='vertical-align: middle;'>
								<?= $value['brand_name'] ?>
							</td>
							<td style='vertical-align: middle;'>
								<?= $value['warehouse_name'] ?>
							</td>
							<td style='vertical-align: middle;'>
								<?= $value['qty'] ?>
							</td>
							<td style='vertical-align: middle;'>
								<?= $value['qty_received'] ?>
							</td>
							<td style='vertical-align: middle;'>
								<?= $value['qty_lost'] ?>
							</td>
							<td style='vertical-align: middle;'>
								<?= $value['description'] != "" ? $value['description'] : "No Description" ?>
							</td>
						</tr>
					<?php $no++;
						$no1++;
					} ?>
				<?php } ?>
			</tbody>
		</table>
	</div>
</div>