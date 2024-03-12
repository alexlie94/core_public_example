<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>


<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6" style="margin-bottom: -30px;">
	<div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
		<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
			<h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
				<?= $titlePage ?>
			</h1>
		</div>
	</div>
</div>

<div id="kt_app_content" class="app-content flex-column-fluid">
	<!--begin::Content container-->
	<div id="kt_app_content_container" class="app-container ">
		<button class="btn btn-active-light-primary rounded-4 btn-sm mb-4" onclick="history.back()">
			<i class="bi bi-arrow-left"></i> Back
		</button>
		<!--begin::Row-->
		<div class="row gx-9 gy-6">
			<!--begin::Col-->
			<div class="card col-xl-12" data-kt-billing-element="card">
				<div class="col-xl-12 h-xl-100  p-3">

					<!--begin::Card-->
					<div class="d-flex flex-row flex-stack flex-wrap pb-1">
						<!--begin::Info-->
						<div class="d-flex flex-column py-2">

							<!--begin::Wrapper-->
							<div class="d-flex align-items-center">
								<!--begin::Icon-->
								<img src="<?= MEDIA . '/marketplace/tokopedia-icon.png' ?>" alt="" class="me-4" width="50" />
								<!--end::Icon-->
								<!--begin::Details-->
								<div>
									<div class="fs-3 fw-bold">
										<?= $detail['recipient_name'] == '' ? '-' : $detail['recipient_name'] ?> |
										<?= format_number_to_idr($detail['total_price']) ?></div>
									<div class="fs-6 fw-semibold text-gray-700"><?= $detail['source_name'] ?> | Order ID
										: <?= $detail['local_order_id'] ?>
									</div>
								</div>
								<!--end::Details-->
							</div>
							<!--end::Wrapper-->
						</div>
						<!--end::Info-->
						<!--begin::Actions-->
						<div class="d-flex align-items-right">
							<div class=" text-end">
								<div class="fs-7 fw-semibold text-gray-700">Order Date</div>
								<div class="fs-6 fw-bold "><?= timestamp_to_date($detail['local_updated_at']) ?></div>
							</div>
							<!-- <button class="btn btn-sm btn-primary mx-3">Create Shipment</button>
							<a href="#" class="btn btn-icon btn-secondary">
								<i class="bi bi-three-dots-vertical fs-1"></i>
							</a> -->
						</div>
						<!--end::Actions-->

					</div>

					<!--begin::Details-->
					<div class="d-flex flex-wrap flex-sm-nowrap my-4">
						<!--begin::Navs-->
						<ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-6 fw-bold">
							<!--begin::Nav item-->
							<li class="nav-item ">
								<a class="nav-link text-active-primary ms-0 me-10 py-2 active" data-bs-toggle="tab" href="#detail">Order Detail</a>
							</li>
							<!--end::Nav item-->
							<!--begin::Nav item-->
							<li class="nav-item ">
								<a class="nav-link text-active-primary ms-0 me-10 py-2" data-bs-toggle="tab" href="#tracking">Tracking Info</a>
							</li>
							<!--end::Nav item-->
							<!--begin::Nav item-->
							<li class="nav-item ">
								<a class="nav-link text-active-primary ms-0 me-10 py-2" data-bs-toggle="tab" href="#activities">Activities</a>
							</li>
							<!--end::Nav item-->

						</ul>
						<!--begin::Navs-->
					</div>

					<div class="tab-content" id="myTabContent">
						<div class="tab-pane fade show active mb-5  shadow-sm rounded-2" id="detail" role="tabpanel">

							<div class="card ">
								<div class="card-body py-6">
									<div class=" mx-auto w-100">
										<!-- <div class="d-flex justify-content-between flex-column flex-sm-row mb-1">
											<h4 class="fw-bold text-gray-800 fs-6 pe-5 pb-7">Order Detail</h4>
											<div class="text-sm-end">
												<div class="text-sm-end fw-semibold fs-7 text-muted ">
													<div>Cecilia Chapman, 711-2880 Nulla St, Mankato</div>
													<div>Mississippi 96522</div>
												</div>
											</div>
										</div> -->
										<div class="pt-3">
											<div class="d-flex flex-column gap-7 gap-md-10">
												<!-- <div class="separator"></div> -->
												<div class="card-body pt-0 d-flex border-right-4">
													<div class="flex-column gap-0" style="margin-right:200px">

														<h4 class="fw-bold text-gray-800 fs-6  pb-4">Order Detail
														</h4>
														<div class="separator "></div>
														<!--begin::Input group-->
														<div class="fv-row">
															<!--begin::Label-->
															<label class=" form-label text-muted mb-0">Order
																ID</label>
															<!--end::Label-->
															<!--begin::Select2-->
															<p class="fw-bold fs-7"><?= $detail['local_order_id'] ?></p>
															<!--end::Select2-->
														</div>
														<!--end::Input group-->
														<!--begin::Input group-->
														<div class="fv-row">
															<!--begin::Label-->
															<label class=" form-label text-muted mb-0">Source</label>
															<!--end::Label-->
															<!--begin::Select2-->
															<p class="fw-bold fs-7"><?= $detail['source_name'] ?></p>
															<!--end::Select2-->
														</div>
														<!--end::Input group-->
														<!--begin::Input group-->
														<div class="fv-row">
															<!--begin::Label-->
															<label class=" form-label text-muted mb-0">Channel</label>
															<!--end::Label-->
															<!--begin::Select2-->
															<p class="fw-bold fs-7"><?= $detail['channel_name'] ?></p>
															<!--end::Select2-->
														</div>
														<!--end::Input group-->
														<!--begin::Input group-->
														<div class="fv-row">
															<!--begin::Label-->
															<label class=" form-label text-muted mb-0">Payment
																Method</label>
															<!--end::Label-->
															<!--begin::Select2-->

															<p class="fw-bold fs-7"><?= $detail['payment_method'] ?>
															</p>
															<!--end::Select2-->
														</div>
														<!--end::Input group-->
														<!--begin::Input group-->
														<div class="fv-row">
															<!--begin::Label-->
															<label class=" form-label text-muted mb-0">COD</label>
															<!--end::Label-->
															<!--begin::Editor-->
															<p class="fw-bold fs-7">
																<?= $detail['is_cod'] ? 'Yes' : 'No' ?>
															</p>
															<!--end::Editor-->
														</div>
														<!--end::Input group-->
													</div>
													<div class="flex-column gap-0">

														<h4 class="fw-bold text-gray-800 fs-6  pb-4">Ship to
														</h4>
														<div class="separator "></div>
														<!--begin::Input group-->
														<div class="fv-row pt-5">
															<label class="form-label text-muted mb-0">Name</label>
															<p class="fw-bold fs-7">
																<?= $detail['recipient_name'] ?></p>
														</div>
														<!--end::Input group-->
														<!--begin::Input group-->
														<div class="fv-row">
															<!--begin::Label-->
															<label class=" form-label text-muted mb-0">Phone</label>
															<!--end::Label-->
															<!--begin::Select2-->
															<p class="fw-bold fs-7"><?= $detail['recipient_phone'] ?>
															</p>
															<!--end::Select2-->
														</div>
														<!--end::Input group-->
														<!--begin::Input group-->
														<div class="fv-row">
															<!--begin::Label-->
															<label class=" form-label text-muted mb-0">Email</label>
															<!--end::Label-->
															<!--begin::Select2-->
															<p class="fw-bold fs-7">
																<?= $detail['recipient_email'] != null ? $detail['recipient_email'] : '-' ?>
															</p>
															<!--end::Select2-->
														</div>
														<!--end::Input group-->
														<!--begin::Input group-->
														<div class="fv-row" style="max-width:250px">
															<!--begin::Label-->
															<label class=" form-label text-muted mb-0">Address</label>
															<!--end::Label-->
															<!--begin::Select2-->
															<p class="fw-bold fs-7">
																<?= $detail['recipient_full_address'] ?>
															</p>
															<!--end::Select2-->
														</div>
														<!--end::Input group-->
													</div>
												</div>


												<!--begin:Order summary-->
												<div class="d-flex justify-content-between flex-column">
													<!--begin::Table-->
													<div class="table-responsive border-bottom mb-9">
														<table class="table align-middle table-row-dashed fs-6 gy-5 mb-0">
															<thead>
																<tr class="border-bottom fs-6 fw-bold text-muted">
																	<th class="min-w-175px pb-2">Products</th>
																	<th class="min-w-80px text-end pb-2">Qty</th>
																	<th class="min-w-100px text-end pb-2">Price</th>
																	<th class="min-w-100px text-end pb-2">Sub Total</th>
																</tr>
															</thead>
															<tbody class="fw-semibold text-gray-600">
																<?php foreach ($detail['detail'] as $row) { ?>
																	<!--begin::Products-->
																	<tr>
																		<!--begin::Product-->
																		<td>
																			<div class="d-flex align-items-center">
																				<!--begin::Thumbnail-->
																				<img src="<?= $row->local_image ?>" alt="" class="me-4" width="50" />
																				<!--end::Thumbnail-->
																				<!--begin::Title-->
																				<div class="ms-5">
																					<div class="fw-bold">
																						<?= $row->local_item_name ?>
																					</div>
																					<div class="fs-7 text-muted">SKU :
																						<?= $row->local_item_sku ?></div>
																				</div>
																				<!--end::Title-->
																			</div>
																		</td>
																		<!--end::Product-->
																		<!--begin::SKU-->
																		<td class="text-end">
																			x<?= $row->quantity_purchased ?>
																		</td>
																		<!--end::SKU-->
																		<!--begin::Quantity-->
																		<td class="text-end">
																			<?php
																			if ($row->product_discount_price > 0) {
																				echo '
																				<span class="text-decoration-line-through text-muted">' . format_number_to_idr($row->product_original_price) . '</span><br>
																				<span class="">' . format_number_to_idr($row->product_discount_price) . '</span>';
																			} else {
																				echo '<span class="">' . format_number_to_idr($row->product_original_price) . '</span>';
																			}
																			?>
																		</td>
																		<!--end::Quantity-->
																		<!--begin::Total-->
																		<td class="text-end">
																			<?= format_number_to_idr($row->product_discount_price * $row->quantity_purchased) ?>
																		</td>
																		<!--end::Total-->
																	</tr>
																<?php } ?>
																<!--end::Products-->
																<!--begin::Shipping-->
																<tr>
																	<td colspan="3" class="text-end">Shipping Fee</td>
																	<td class="text-end">
																		<?= format_number_to_idr($detail['shipping_price']) ?>
																	</td>
																</tr>
																<!--end::Shipping-->
																<!--begin::Insurance Fee-->
																<tr>
																	<td colspan="3" class="text-end">Insurance Fee</td>
																	<td class="text-end">
																		<?= format_number_to_idr($detail['insurance_fee']) ?>
																	</td>
																</tr>
																<!--end::Insurance Fee-->
																<!--begin::Grand total-->
																<tr>
																	<td colspan="3" class="fs-3 text-dark fw-bold text-end">
																		Total Sales</td>
																	<td class="text-dark fs-3 fw-bolder text-end">

																		<?= format_number_to_idr($detail['total_price']) ?>
																	</td>
																</tr>
																<!--end::Grand total-->
															</tbody>
														</table>
													</div>
													<!--end::Table-->
												</div>
												<!--end:Order summary-->
											</div>
											<!--end::Wrapper-->
										</div>
										<!--end::Body-->
									</div>
									<!-- end::Wrapper-->
								</div>
								<!-- end::Body-->
							</div>

						</div>
						<div class="tab-pane fade show mb-5 shadow-sm rounded-2" id="tracking" role="tabpanel">
							<div class="card">
								<?php
								if (empty($detail['tracking_info'])) {
									echo '
                <div class="card-body pt-0 pb-0 d-flex align-items-center justify-content-center"
                style="min-height: 80px;">
                <span class="text-muted">There is no Tracking Info</span>
            </div>
            ';
								} else {
								?>
									<!--begin::Timeline-->
									<div class="timeline ms-n1 py-10 ps-20">
										<?php
										$tracking = json_decode($detail['tracking_info']);
										date_default_timezone_set('Asia/Jakarta');
										foreach ($tracking as $row_tracking) {
											$date = date("d M Y H:i", strtotime($row_tracking->timestamp));
										?>
											<!--begin::Timeline item-->
											<div class="timeline-item align-items-center mb-4">
												<!--begin::Timeline line-->
												<div class="timeline-line w-20px mt-12 mb-n14"></div>
												<!--end::Timeline line-->
												<!--begin::Timeline icon-->
												<div class="timeline-icon pt-1" style="margin-left: 0.7px">
													<!--begin::Svg Icon | path: icons/duotune/general/gen015.svg-->
													<span class="svg-icon svg-icon-2 svg-icon-info">
														<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
															<path opacity="0.3" d="M18.0624 15.3453L13.1624 20.7453C12.5624 21.4453 11.5624 21.4453 10.9624 20.7453L6.06242 15.3453C4.56242 13.6453 3.76242 11.4453 4.06242 8.94534C4.56242 5.34534 7.46242 2.44534 11.0624 2.04534C15.8624 1.54534 19.9624 5.24534 19.9624 9.94534C20.0624 12.0453 19.2624 13.9453 18.0624 15.3453Z" fill="currentColor" />
															<path d="M12.0624 13.0453C13.7193 13.0453 15.0624 11.7022 15.0624 10.0453C15.0624 8.38849 13.7193 7.04535 12.0624 7.04535C10.4056 7.04535 9.06241 8.38849 9.06241 10.0453C9.06241 11.7022 10.4056 13.0453 12.0624 13.0453Z" fill="currentColor" />
														</svg>
													</span>
													<!--end::Svg Icon-->
												</div>
												<!--end::Timeline icon-->
												<!--begin::Timeline content-->
												<div class="timeline-content m-0">
													<!--begin::Label-->
													<span class="fs-8 fw-bolder text-info text-capitalize"><?= $row_tracking->action_by ?></span>
													<!--begin::Label-->
													<!--begin::Title-->
													<span class="fs-6 text-gray-800 fw-bold d-block text-hover-info text-capitalize"><?= $row_tracking->message ?></span>
													<!--end::Title-->
													<!--begin::Title-->
													<span class="fw-semibold text-gray-400"><?= $date ?></span>
													<!--end::Title-->
												</div>
												<!--end::Timeline content-->
											</div>
											<!--end::Timeline item-->
										<?php } ?>
									</div>
									<!--end::Timeline-->
								<?php } ?>
							</div>
						</div>

						<div class="tab-pane fade show mb-5 shadow-sm rounded-2" id="activities" role="tabpanel">
							<?php
							if (empty($detail['log'])) { ?>
								<div class="card ">
									<div class="card-body pt-0 pb-0 d-flex align-items-center justify-content-center" style="min-height: 80px;">
										<span class="text-muted">There is no Activities</span>
									</div>
								</div>
							<?php } else { ?>
								<div class="card py-10 ps-20">
									<!--begin::Timeline-->
									<div class="timeline-label">

										<?php
										foreach ($detail['log'] as $row_log) {
											$date = date("d M Y ", $row_log->local_updated_at);
											$time = date("H:i", $row_log->local_updated_at);
										?>
											<!--begin::Item-->
											<div class="timeline-item">
												<!--begin::Label-->
												<div class="timeline-label  fs-6 pe-4 w-100px " style="text-align: right;">
													<span class="fw-bold text-gray-800"> <?= $date ?> </span>
													<br>
													<span class="fw-normal text-gray-800 "> <?= $time ?> </span>
												</div>
												<!--end::Label-->
												<!--begin::Badge-->
												<div class="timeline-badge">
													<i class="fa fa-genderless text-primary fs-1"></i>
												</div>
												<!--end::Badge-->
												<!--begin::Text-->
												<div class="timeline-content text-gray-800 fw-bold ps-5 fs-5">
													<?= $row_log->log_desc ?></div>
												<!--end::Text-->
											</div>
											<!--end::Item-->
										<?php
										}
										?>

									</div>
									<!--end::Timeline-->
								</div>
								<!--end::Content-->
							<?php } ?>
						</div>
						<!--end::Tab panel-->
					</div>
					<!--end::Tab Content-->
				</div>
				<!--end::Card body-->
			</div>
			<!--end::Timeline-->
		</div>
	</div>
</div>

<style>
	.timeline-label:before {
		content: "";
		position: absolute;
		left: 100px;
		width: 3px;
		top: 0;
		bott om: 0;
		background-color: var(--kt-gray-200);
	}
</style>