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
								<img src="<?= MEDIA . '/marketplace/shopee-icon.png' ?>" alt="" class="me-4" width="50" />
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
																<!--begin::Subtotal-->
																<tr>
																	<td colspan="3" class="fs-6 text-end text-dark fw-bold ">
																		Sub Total Orders
																	</td>
																	<td class="fs-6 text-end text-dark">
																		<?= format_number_to_idr($detail['subtotal']) ?>
																	</td>
																</tr>
																<!--end::Subtotal-->
																<!--begin::Shipping-->
																<tr class="h-1px">
																	<td colspan="3" class="text-end">
																		<p class="fs-6 text-dark fw-bold"> Shipping Fee
																		</p>
																		<p class="fs-7">Actual Shipping Fee </p>
																		<span class="fs-7">Discount Shipping Fee </span>

																	</td>
																	<td class="text-end ">
																		<p class="fs-6 text-dark fw-bold">
																			<?= format_number_to_idr($detail['shipping_price']) ?>
																		</p>
																		<p class="fs-7">
																			<?= format_number_to_idr($detail['original_shipping_price']) ?>
																		</p>
																		<span class="fs-7">
																			-<?= format_number_to_idr($detail['shipping_discount_amount']) ?>
																		</span>
																	</td>
																</tr>
																<!--end::Shipping-->
																<!--begin::TOtal Order-->
																<tr class="">

																	<td></td>
																	<td></td>
																	<td class="fs-6 text-end text-dark fw-bold bg-success">
																		Total Orders
																	</td>
																	<td class="fs-6 text-end text-dark bg-success px-2">
																		<?= format_number_to_idr($detail['channel_total_price']) ?>
																	</td>
																</tr>

																<!--end::TOtal Order-->
																<tr>
																	<td colspan="3" class="fs-6 text-end text-dark fw-bold ">
																		Discount Voucher
																	</td>
																	<td class="fs-6 text-end text-dark fw-bold">
																		-<?= format_number_to_idr($detail['voucher_from_seller']) ?>
																	</td>
																</tr>
																<!--begin::Tax-->
																<tr>
																	<td colspan="3" class="text-end">
																		<p class="fs-6 text-dark fw-bold">Shopee
																			Administrative Fee
																		</p>
																		<p class="fs-7">Commision Fee</p>
																		<span class="fs-7">Service Fee (including 11%
																			VAT)
																		</span>

																	</td>
																	<td class="text-end ">
																		<p class="fs-6 text-dark fw-bold">
																			-<?php
																				$tax_total = $detail['commission_fee'] + $detail['tax_price'];
																				echo format_number_to_idr($tax_total) ?>
																		</p>
																		<p class="fs-7">
																			-<?= format_number_to_idr($detail['commission_fee']) ?>
																		</p>
																		<span class="fs-7">
																			-<?= format_number_to_idr($detail['tax_price']) ?>
																		</span>
																	</td>
																</tr>
																<!--end::Tax-->
																<!--begin::Grand total-->
																<tr>
																	<td colspan="3" class="fs-4 text-dark fw-bold text-end">
																		Total Sales</td>
																	<td class="text-dark fs-4 fw-bolder text-end">

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
								if ($detail['tracking_info'] === null) {
									echo '
										<div class="card-body pt-0 pb-0 d-flex align-items-center justify-content-center"
										style="min-height: 80px;">
										<span class="text-muted">There is no Tracking Info</span>
									</div>
										';
								} else {

									$tracking = json_decode($detail['tracking_info'], true);

									if (empty($tracking['tracking_info'])) {
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
											$tracking = json_decode($detail['tracking_info'], true);
											foreach ($tracking['tracking_info'] as $row_tracking) {
												$logistics_status = ucfirst(str_replace("_", " ", $row_tracking['logistics_status']));
												$date = timestamp_to_date($row_tracking['update_time']);
												switch ($row_tracking['logistics_status']) {
													case 'PICKED_UP':
														$color = 'success';
														$icon = '<span class="svg-icon svg-icon-2 svg-icon-success">
													<svg width="24" height="24" viewBox="0 0 24 24" fill="none"
														xmlns="http://www.w3.org/2000/svg">
														<path opacity="0.3"
															d="M22 12C22 17.5 17.5 22 12 22C6.5 22 2 17.5 2 12C2 6.5 6.5 2 12 2C17.5 2 22 6.5 22 12ZM12 10C10.9 10 10 10.9 10 12C10 13.1 10.9 14 12 14C13.1 14 14 13.1 14 12C14 10.9 13.1 10 12 10ZM6.39999 9.89999C6.99999 8.19999 8.40001 6.9 10.1 6.4C10.6 6.2 10.9 5.7 10.7 5.1C10.5 4.6 9.99999 4.3 9.39999 4.5C7.09999 5.3 5.29999 7 4.39999 9.2C4.19999 9.7 4.5 10.3 5 10.5C5.1 10.5 5.19999 10.6 5.39999 10.6C5.89999 10.5 6.19999 10.2 6.39999 9.89999ZM14.8 19.5C17 18.7 18.8 16.9 19.6 14.7C19.8 14.2 19.5 13.6 19 13.4C18.5 13.2 17.9 13.5 17.7 14C17.1 15.7 15.8 17 14.1 17.6C13.6 17.8 13.3 18.4 13.5 18.9C13.6 19.3 14 19.6 14.4 19.6C14.5 19.6 14.6 19.6 14.8 19.5Z"
															fill="currentColor" />
														<path
															d="M16 12C16 14.2 14.2 16 12 16C9.8 16 8 14.2 8 12C8 9.8 9.8 8 12 8C14.2 8 16 9.8 16 12ZM12 10C10.9 10 10 10.9 10 12C10 13.1 10.9 14 12 14C13.1 14 14 13.1 14 12C14 10.9 13.1 10 12 10Z"
															fill="currentColor" />
													</svg>
												</span>';
														break;
													case 'ORDER_CREATED':
														$color = 'primary';
														$icon = '<span class="svg-icon svg-icon-2 svg-icon-primary">
													<svg width="24" height="24" viewBox="0 0 24 24" fill="none"
														xmlns="http://www.w3.org/2000/svg">
														<path opacity="0.3"
															d="M22 12C22 17.5 17.5 22 12 22C6.5 22 2 17.5 2 12C2 6.5 6.5 2 12 2C17.5 2 22 6.5 22 12ZM12 10C10.9 10 10 10.9 10 12C10 13.1 10.9 14 12 14C13.1 14 14 13.1 14 12C14 10.9 13.1 10 12 10ZM6.39999 9.89999C6.99999 8.19999 8.40001 6.9 10.1 6.4C10.6 6.2 10.9 5.7 10.7 5.1C10.5 4.6 9.99999 4.3 9.39999 4.5C7.09999 5.3 5.29999 7 4.39999 9.2C4.19999 9.7 4.5 10.3 5 10.5C5.1 10.5 5.19999 10.6 5.39999 10.6C5.89999 10.5 6.19999 10.2 6.39999 9.89999ZM14.8 19.5C17 18.7 18.8 16.9 19.6 14.7C19.8 14.2 19.5 13.6 19 13.4C18.5 13.2 17.9 13.5 17.7 14C17.1 15.7 15.8 17 14.1 17.6C13.6 17.8 13.3 18.4 13.5 18.9C13.6 19.3 14 19.6 14.4 19.6C14.5 19.6 14.6 19.6 14.8 19.5Z"
															fill="currentColor" />
														<path
															d="M16 12C16 14.2 14.2 16 12 16C9.8 16 8 14.2 8 12C8 9.8 9.8 8 12 8C14.2 8 16 9.8 16 12ZM12 10C10.9 10 10 10.9 10 12C10 13.1 10.9 14 12 14C13.1 14 14 13.1 14 12C14 10.9 13.1 10 12 10Z"
															fill="currentColor" />
													</svg>
												</span>';
														break;
													case 'DELIVERED':
														$color = 'info';
														$icon = ' <span class="svg-icon svg-icon-2 svg-icon-info">
													<svg width="24" height="24" viewBox="0 0 24 24" fill="none"
														xmlns="http://www.w3.org/2000/svg">
														<path opacity="0.3"
															d="M18.0624 15.3453L13.1624 20.7453C12.5624 21.4453 11.5624 21.4453 10.9624 20.7453L6.06242 15.3453C4.56242 13.6453 3.76242 11.4453 4.06242 8.94534C4.56242 5.34534 7.46242 2.44534 11.0624 2.04534C15.8624 1.54534 19.9624 5.24534 19.9624 9.94534C20.0624 12.0453 19.2624 13.9453 18.0624 15.3453Z"
															fill="currentColor" />
														<path
															d="M12.0624 13.0453C13.7193 13.0453 15.0624 11.7022 15.0624 10.0453C15.0624 8.38849 13.7193 7.04535 12.0624 7.04535C10.4056 7.04535 9.06241 8.38849 9.06241 10.0453C9.06241 11.7022 10.4056 13.0453 12.0624 13.0453Z"
															fill="currentColor" />
													</svg>
												</span>';
														break;
													case 'RETURN_INITIATED':
														$color = 'danger';
														$icon = ' <span class="svg-icon svg-icon-2 svg-icon-danger">
													<svg width="24" height="24" viewBox="0 0 24 24" fill="none"
														xmlns="http://www.w3.org/2000/svg">
														<path opacity="0.3"
															d="M18.0624 15.3453L13.1624 20.7453C12.5624 21.4453 11.5624 21.4453 10.9624 20.7453L6.06242 15.3453C4.56242 13.6453 3.76242 11.4453 4.06242 8.94534C4.56242 5.34534 7.46242 2.44534 11.0624 2.04534C15.8624 1.54534 19.9624 5.24534 19.9624 9.94534C20.0624 12.0453 19.2624 13.9453 18.0624 15.3453Z"
															fill="currentColor" />
														<path
															d="M12.0624 13.0453C13.7193 13.0453 15.0624 11.7022 15.0624 10.0453C15.0624 8.38849 13.7193 7.04535 12.0624 7.04535C10.4056 7.04535 9.06241 8.38849 9.06241 10.0453C9.06241 11.7022 10.4056 13.0453 12.0624 13.0453Z"
															fill="currentColor" />
													</svg>
												</span>';
														break;
													case 'RETURN_STARTED':
														$color = 'danger';
														$icon = ' <span class="svg-icon svg-icon-2 svg-icon-danger">
													<svg width="24" height="24" viewBox="0 0 24 24" fill="none"
														xmlns="http://www.w3.org/2000/svg">
														<path opacity="0.3"
															d="M18.0624 15.3453L13.1624 20.7453C12.5624 21.4453 11.5624 21.4453 10.9624 20.7453L6.06242 15.3453C4.56242 13.6453 3.76242 11.4453 4.06242 8.94534C4.56242 5.34534 7.46242 2.44534 11.0624 2.04534C15.8624 1.54534 19.9624 5.24534 19.9624 9.94534C20.0624 12.0453 19.2624 13.9453 18.0624 15.3453Z"
															fill="currentColor" />
														<path
															d="M12.0624 13.0453C13.7193 13.0453 15.0624 11.7022 15.0624 10.0453C15.0624 8.38849 13.7193 7.04535 12.0624 7.04535C10.4056 7.04535 9.06241 8.38849 9.06241 10.0453C9.06241 11.7022 10.4056 13.0453 12.0624 13.0453Z"
															fill="currentColor" />
													</svg>
												</span>';
														break;
													case 'LOST':
														$color = 'danger';
														$icon = ' <span class="svg-icon svg-icon-2 svg-icon-danger">
													<svg width="24" height="24" viewBox="0 0 24 24" fill="none"
														xmlns="http://www.w3.org/2000/svg">
														<path opacity="0.3"
															d="M18.0624 15.3453L13.1624 20.7453C12.5624 21.4453 11.5624 21.4453 10.9624 20.7453L6.06242 15.3453C4.56242 13.6453 3.76242 11.4453 4.06242 8.94534C4.56242 5.34534 7.46242 2.44534 11.0624 2.04534C15.8624 1.54534 19.9624 5.24534 19.9624 9.94534C20.0624 12.0453 19.2624 13.9453 18.0624 15.3453Z"
															fill="currentColor" />
														<path
															d="M12.0624 13.0453C13.7193 13.0453 15.0624 11.7022 15.0624 10.0453C15.0624 8.38849 13.7193 7.04535 12.0624 7.04535C10.4056 7.04535 9.06241 8.38849 9.06241 10.0453C9.06241 11.7022 10.4056 13.0453 12.0624 13.0453Z"
															fill="currentColor" />
													</svg>
												</span>';
														break;

													default:
														$color = 'danger';
														$icon = ' <span class="svg-icon svg-icon-2 svg-icon-danger">
													<svg width="24" height="24" viewBox="0 0 24 24" fill="none"
														xmlns="http://www.w3.org/2000/svg">
														<path opacity="0.3"
															d="M18.0624 15.3453L13.1624 20.7453C12.5624 21.4453 11.5624 21.4453 10.9624 20.7453L6.06242 15.3453C4.56242 13.6453 3.76242 11.4453 4.06242 8.94534C4.56242 5.34534 7.46242 2.44534 11.0624 2.04534C15.8624 1.54534 19.9624 5.24534 19.9624 9.94534C20.0624 12.0453 19.2624 13.9453 18.0624 15.3453Z"
															fill="currentColor" />
														<path
															d="M12.0624 13.0453C13.7193 13.0453 15.0624 11.7022 15.0624 10.0453C15.0624 8.38849 13.7193 7.04535 12.0624 7.04535C10.4056 7.04535 9.06241 8.38849 9.06241 10.0453C9.06241 11.7022 10.4056 13.0453 12.0624 13.0453Z"
															fill="currentColor" />
													</svg>
												</span>';
														break;
												}

											?>
												<!--begin::Timeline item-->
												<div class="timeline-item align-items-center mb-4">
													<!--begin::Timeline line-->
													<div class="timeline-line w-20px mt-12 mb-n14"></div>
													<!--end::Timeline line-->
													<!--begin::Timeline icon-->
													<div class="timeline-icon pt-1" style="margin-left: 0.7px">
														<!--begin::Svg Icon | path: icons/duotune/general/gen015.svg-->
														<?= $icon ?>
														<!--end::Svg Icon-->
													</div>
													<!--end::Timeline icon-->
													<!--begin::Timeline content-->
													<div class="timeline-content m-0">
														<!--begin::Label-->
														<span class="fs-8 fw-bolder text-<?= $color ?> text-capitalize"><?= $logistics_status ?></span>
														<!--begin::Label-->
														<!--begin::Title-->
														<span class="fs-6 text-gray-800 fw-bold d-block text-hover-<?= $color ?> text-capitalize"><?= $row_tracking['description'] ?></span>
														<!--end::Title-->
														<!--begin::Title-->
														<span class="fw-semibold text-gray-400"><?= $date ?></span>
														<!--end::Title-->
													</div>
													<!--end::Timeline content-->
												</div>
											<?php } ?>
											<!--end::Timeline item-->

										</div>
										<!--end::Timeline-->
								<?php }
								}
								?>
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
		top:
			0;
		bottom: 0;
		background-color: var(--kt-gray-200);
	}
</style>