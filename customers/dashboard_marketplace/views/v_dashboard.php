<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
	<!--begin::Content wrapper-->
	<div class="d-flex flex-column flex-column-fluid">
		<!--begin::Toolbar-->
		<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
			<!--begin::Toolbar container-->
			<div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
				<!--begin::Page title-->
				<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
					<!--begin::Title-->
					<h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
						<?= $titlePage ?>
					</h1>
					<!--end::Title-->
				</div>
				<!--end::Page title-->
			</div>
			<!--end::Toolbar container-->
		</div>
		<!--end::Toolbar-->
		<!--begin::Toolbar-->
		<div id="kt_app_toolbar" class="app-toolbar">
			<!--begin::Toolbar container-->
			<div id="kt_app_toolbar_container" class="app-container d-flex flex-stack flex-wrap">
				<!--begin::Toolbar wrapper-->
				<div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
					<!--begin::Page title-->
					<div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
						<!--begin::Title-->
						<h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bold fs-2x m-0">
							<?= $greeting ?>, Administrator</h1>
						<h1 class="page-heading d-flex flex-column justify-content-center text-gray-400 fw-bold fs-1x m-0">
							<?= $today = date("F j, Y, g:i a"); ?></h1>
						<!--end::Title-->
					</div>
					<!--end::Page title-->
				</div>
				<!--end::Toolbar wrapper-->
			</div>
			<!--end::Toolbar container-->
		</div>
		<!--end::Toolbar-->
		<!--begin::Content-->
		<div id="kt_app_content" class="app-content flex-column-fluid">
			<!--begin::Content container-->
			<div id="kt_app_content_container" class="app-container">
				<!--begin::Row-->
				<div class="row g-5  mb-5 mb-xl-10">
					<!--begin::Col-->
					<div class="col-xl-12">
						<!--begin::Row-->
						<div class="row g-5 g-xl-12">
							<!--begin::Col-->
							<div class="col-xl-12">
								<!--begin::Notice-->
								<div class="notice d-flex bg-light-warning rounded border-warning border border-dashed p-6 mb-4">
									<!--begin::Icon-->
									<!--begin::Svg Icon | path: icons/duotune/general/gen044.svg-->
									<span class="svg-icon svg-icon-2tx svg-icon-warning me-4">
										<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
											<rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="currentColor" />
											<rect x="11" y="14" width="7" height="2" rx="1" transform="rotate(-90 11 14)" fill="currentColor" />
											<rect x="11" y="17" width="2" height="2" rx="1" transform="rotate(-90 11 17)" fill="currentColor" />
										</svg>
									</span>
									<!--end::Svg Icon-->
									<!--end::Icon-->
									<!--begin::Wrapper-->
									<div class="d-flex flex-stack flex-grow-1">
										<!--begin::Content-->
										<div class="fw-semibold col-md-12">
											<h4 class="text-gray-900 fw-bold">
												<?= isset($UpdateInfo['title']) ? $UpdateInfo['title'] : 'Anouncement!' ?>
											</h4>
											<p class="text-gray-400 fs-6 fw-semibold">
												<?= isset($UpdateInfo['created_at']) ? $UpdateInfo['created_at'] : 'DD-MM-YYYY' ?>
												:</p>
											<div class="fs-6 text-gray-700">
												<?= isset($UpdateInfo['content']) ? $UpdateInfo['content'] : 'There are no updates available at this time!' ?>
											</div>
											<p class="text-gray-400 fs-6 fw-semibold me-4" align="right"><i>Updated
													At
													<?= isset($UpdateInfo['updated_at']) ? $UpdateInfo['updated_at'] : 'DD-MM-YYYY' ?></i>
											</p>
										</div>

										<!--end::Content-->
									</div>
									<!--end::Wrapper-->
								</div>
								<!--end::Notice-->
							</div>
							<!--end::Col-->
						</div>
						<!--end::Row-->
					</div>
					<!--end::Col-->
				</div>
				<!--end::Row-->
				<!--begin::Row-->
				<div class="row g-5 g-xl-10 mb-5 mb-xl-10">
					<!--begin::Col-->
					<div class="col-xxl-12">
						<!--begin::Tables Widget 11-->
						<div class="card shadow-sm mb-5 mb-xl-1">
							<!--begin::Header-->
							<div class="card-header border-0 pt-5">
								<h3 class="card-title align-items-start flex-column">
									<span class="card-label fw-bold fs-3 mb-1">Pending Actions</span>
									<span class="text-danger mt-1 fw-semibold fs-2x" id="all_pending_action_data"></span>
								</h3>
							</div>
							<!--end::Header-->
							<!--begin::Body-->
							<div class="card-body py-3">
								<!--begin::Datatable-->
								<input type="hidden" name="paging_datatables" value="0" class="halaman" style="display: none"><input type="hidden" name="draw" value="1" class="draw_datatables" style="display: none">
								<div class="table-responsive">
									<table id="show_pending_actions" class="table table-hover table-rounded align-middle gs-0 gy-4">
										<thead>
											<tr class="fw-bold text-muted bg-light">
												<th class="ps-4 rounded-start">TYPE</th>
												<th class="p-4 text-end rounded-end">SKU</th>
											</tr>
										</thead>
										<tbody>

										</tbody>
										<tfoot>

										</tfoot>
									</table>
								</div>
								<div class="paginationDatatables"></div>
								<!--end::Datatable-->
							</div>
							<!--begin::Body-->
						</div>
						<!--end::Tables Widget 11-->
					</div>
					<!--end::Col-->
					<!--begin::Col-->
					<div class="col-xxl-12">
						<!--begin::Tables Widget 11-->
						<div class="card shadow-sm mb-5 mb-xl-1">
							<!--begin::Header-->
							<div class="card-header border-0 pt-5">
								<h3 class="card-title align-items-start flex-column">
									<span class="card-label fw-bold fs-3 mb-1">Order to Fulfill</span>
									<span class="text-danger mt-1 fw-semibold fs-2x" id="total_order"></span>
								</h3>
								<div class="card-toolbar">
									<!--begin::Daterangepicker(defined in src/js/layout/app.js)-->
									<input style="cursor: pointer;" class="form-control form-control-solid" placeholder="Pick date range" id="kt_daterangepicker_4" readonly />
									<!--end::Daterangepicker-->
								</div>
							</div>
							<!--end::Header-->
							<!--begin::Body-->
							<div class="card-body py-3">
								<!--begin::Datatable-->
								<input type="hidden" name="paging_datatables" value="0" class="halaman" style="display: none"><input type="hidden" name="draw" value="1" class="draw_datatables" style="display: none">
								<div class="table-responsive">
									<table id="show_order" class="table table-hover table-rounded align-middle gs-0 gy-4">
										<thead>
											<tr class="fw-bold text-muted bg-light">
												<th class="ps-4 rounded-start"></th>
												<th></th>
												<th>PENDING PAYMENT</th>
												<th>OPEN ORDERS</th>
												<th>NOT SHIPPED</th>
												<th class="rounded-end">READY TO SHIP</th>
											</tr>
										</thead>
										<tbody>

										</tbody>
										<tfoot>

										</tfoot>
									</table>
								</div>
								<div class="paginationDatatables"></div>
								<!--end::Datatable-->

							</div>
							<!--begin::Body-->
						</div>
						<!--end::Tables Widget 11-->
					</div>
					<!--end::Col-->
					<!--begin::Col-->
					<div class="col-xxl-12">
						<!--begin::Chart widget 20-->
						<div class="card shadow-sm card-flush mb-5 mb-xl-1">
							<!--begin::Header-->
							<div class="card-header py-5 mb-10">
								<!--begin::Title-->
								<div class="d-flex align-items-center position-relative my-1">
									<div class="align-items-start flex-column">
										<!--begin::Solid input group style-->
										<select class="form-select form-select-solid" data-control="select2" data-placeholder="Select an option" id="source_id" name="source_id">
											<option value="0" selected>All Store</option>
											<?php foreach ($sources as $value) { ?>
												<option value="<?= $value['id'] ?>">
													<?= $value['source_name'] ?>
												</option>
											<?php } ?>
										</select>
										<!--end::Solid input group style-->
									</div>
									<div class="ps-4 align-items-start flex-column">
										<!--begin::Daterangepicker(defined in src/js/layout/app.js)-->
										<input style="cursor: pointer;" class="form-control form-control-solid" placeholder="Pick date range" id="kt_daterangepicker_5" readonly />
										<!--end::Daterangepicker-->
									</div>

								</div>
								<!--end::Title-->
								<!--begin::Toolbar-->
								<div class="card-toolbar">
									<!--begin::Daterangepicker(defined in src/js/layout/app.js)-->
									<button class="btn btn-sm btn-secondary" id="btnExportSO" type="button" data-type="modal" data-url="<?= base_url('dashboard_marketplace/export_so') ?>" data-fullscreenmodal="0">Export</button>
									<!--end::Daterangepicker-->
								</div>
								<!--end::Toolbar-->
							</div>
							<!--end::Header-->
							<!--begin::Card body-->
							<div class="card-body d-flex justify-content-between flex-column pb-0 px-0 pt-1">
								<!--begin::Items-->
								<div class="d-flex flex-wrap d-grid gap-5 px-9 mb-5">
									<!--begin::Item-->
									<div class="me-md-2">
										<!--begin::Title-->
										<span class="fs-3 fw-semibold text-gray-600">Gross Sales</span>
										<!--end::Title-->
										<!--begin::Statistics-->
										<div class="d-flex mb-2">
											<span class="fs-4 fw-semibold text-gray-400 me-1">Rp.</span>
											<span class="fs-2hx fw-bold text-gray-800 me-2 lh-1 ls-n2" id="gross_sales_value"></span>
										</div>
										<!--end::Statistics-->
										<div class="d-flex mb-2" id="percentage_gross_sale"></div>
										<!--begin::Description-->
										<span class="fs-6 fw-semibold text-gray-400" id="gross_sale_day"></span>
										<!--end::Description-->
									</div>
									<!--end::Item-->
									<!--begin::Item-->
									<div class="px-5 ps-md-10 pe-md-7 me-md-5">
										<!--begin::Title-->
										<span class="fs-3 fw-semibold text-gray-600">Orders</span>
										<!--end::Title-->
										<!--begin::Statistics-->
										<div class="d-flex mb-2">
											<span class="fs-4 fw-semibold text-gray-400 me-1"></span>
											<span class="fs-2hx fw-bold text-gray-800 me-2 lh-1 ls-n2" id="orders_value"></span>
										</div>
										<!--end::Statistics-->
										<div class="d-flex mb-2" id="percentage_orders">
										</div>
										<!--begin::Description-->
										<span class="fs-6 fw-semibold text-gray-400" id="orders_days"></span>
										<!--end::Description-->
									</div>
									<!--end::Item-->
									<!--begin::Item-->
									<div class="m-0">
										<!--begin::Title-->
										<span class="fs-3 fw-semibold text-gray-600">Items Sold</span>
										<!--end::Title-->
										<!--begin::Statistics-->
										<div class="d-flex mb-2">
											<!--begin::Currency-->
											<span class="fs-4 fw-semibold text-gray-400 align-self-start me-1"></span>
											<!--end::Currency-->
											<!--begin::Value-->
											<span class="fs-2hx fw-bold text-gray-800 me-2 lh-1 ls-n2" id="items_sold_value"></span>
											<!--end::Value-->
										</div>
										<div class="d-flex mb-2" id="percentage_items_sold">
										</div>
										<!--end::Statistics-->
										<!--begin::Description-->
										<span class="fs-6 fw-semibold text-gray-400" id="items_sold"></span>
										<!--end::Description-->
									</div>
									<!--end::Item-->
								</div>
								<!--end::Items-->
								<!--begin::Items-->
								<div class="d-flex flex-wrap d-grid gap-5 px-9 mb-5">
									<!--begin::Item-->
									<div class="me-md-2">
										<!--begin::Title-->
										<span class="fs-3 fw-semibold text-gray-600">Avg. Order Value</span>
										<!--end::Title-->
										<!--begin::Statistics-->
										<div class="d-flex mb-2">
											<span class="fs-4 fw-semibold text-gray-400 me-1">Rp.</span>
											<span class="fs-2hx fw-bold text-gray-800 me-2 lh-1 ls-n2" id="avg_order_value"></span>
										</div>
										<!--end::Statistics-->
										<div class="d-flex mb-2" id="percentage_avg_order_value">

										</div>
										<!--begin::Description-->
										<span class="fs-6 fw-semibold text-gray-400" id="avg_order_value_days"></span>
										<!--end::Description-->
									</div>
									<!--end::Item-->
									<!--begin::Item-->
									<div class="px-5 ps-md-10 pe-md-7 me-md-5">
										<!--begin::Title-->
										<span class="fs-3 fw-semibold text-gray-600">Avg. Order/day</span>
										<!--end::Title-->
										<!--begin::Statistics-->
										<div class="d-flex mb-2">
											<span class="fs-4 fw-semibold text-gray-400 me-1"></span>
											<span class="fs-2hx fw-bold text-gray-800 me-2 lh-1 ls-n2" id="avg_order_per_day"></span>
										</div>
										<!--end::Statistics-->
										<div class="d-flex mb-2" id="percentage_avg_order_per_days">

										</div>
										<!--begin::Description-->
										<span class="fs-6 fw-semibold text-gray-400" id="avg_order_per_days"></span>
										<!--end::Description-->
									</div>
									<!--end::Item-->
									<!--begin::Item-->
									<div class="m-0" hidden>
										<!--begin::Title-->
										<span class="fs-3 fw-semibold text-gray-600">Avg. Item Discount</span>
										<!--end::Title-->
										<!--begin::Statistics-->
										<div class="d-flex mb-2">
											<!--begin::Currency-->
											<span class="fs-4 fw-semibold text-gray-400 align-self-start me-1"></span>
											<!--end::Currency-->
											<!--begin::Value-->
											<span class="fs-2hx fw-bold text-gray-800 me-2 lh-1 ls-n2">0%</span>
											<!--end::Value-->
										</div>
										<div class="d-flex mb-2">
											<!--begin::Label-->
											<span class="badge badge-light-success fs-base">
												<!--begin::Svg Icon | path: icons/duotune/arrows/arr067.svg-->
												<span class="svg-icon svg-icon-7 svg-icon-success ms-n1">
													<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
														<path opacity="0.5" d="M13 9.59998V21C13 21.6 12.6 22 12 22C11.4 22 11 21.6 11 21V9.59998H13Z" fill="currentColor" />
														<path d="M5.7071 7.89291C5.07714 8.52288 5.52331 9.60002 6.41421 9.60002H17.5858C18.4767 9.60002 18.9229 8.52288 18.2929 7.89291L12.7 2.3C12.3 1.9 11.7 1.9 11.3 2.3L5.7071 7.89291Z" fill="currentColor" />
													</svg>
												</span>
												<!--end::Svg Icon-->4.5%
											</span>
											<!--end::Label-->
										</div>
										<!--end::Statistics-->
										<!--begin::Description-->
										<span class="fs-6 fw-semibold text-gray-400" id="avg_discount_days">vs previous
											13 day(s)</span>
										<!--end::Description-->
									</div>
									<!--end::Item-->
								</div>
								<!--end::Items-->
								<!--begin::Chart-->
								<div id="kt_apexcharts_3" class="min-h-auto ps-4 pe-6 mb-5" data-kt-chart-info="Revenue" style="height: 300px"></div>
								<!--end::Chart-->

								<div class="card-body py-3">
									<!--begin::Datatable-->
									<input type="hidden" name="paging_datatables" value="0" class="halaman" style="display: none"><input type="hidden" name="draw" value="1" class="draw_datatables" style="display: none">
									<div class="table-responsive">
										<table id="show_sales_order" class="table table-hover table-rounded align-middle gs-0 gy-4">
											<thead>
												<tr class="fw-bold text-muted bg-light">
													<th class="ps-4 rounded-start"></th>
													<th>CHANNELS</th>
													<th>ORDERS</th>
													<th>ITEMS SOLD</th>
													<th class="rounded-end">SUB TOTAL</th>
												</tr>
											</thead>
											<tbody>

											</tbody>
											<tfoot>

											</tfoot>
										</table>
									</div>
									<div class="paginationDatatables"></div>
									<!--end::Datatable-->
								</div>
								<!--end::Table container-->
							</div>
							<!--end::Card body-->
						</div>
						<!--end::Chart widget 20-->
					</div>
					<!--end::Col-->
					<div class="col-xxl-12">
						<div class="card shadow-lg">
							<div class="card-body pt-0 pb-0">

								<div class="d-flex flex-wrap flex-sm-nowrap mb-3">

									<ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bold">
										<li class="nav-item ">
											<a class="nav-link fw-bold text-active-primary ms-0 me-10 py-5 active" data-bs-toggle="tab" href="#all">Inventory Display</a>
										</li>

										<li class="nav-item ">
											<a class="nav-link text-active-primary ms-0 me-10 py-5" data-bs-toggle="tab" href="#unpaid">Shadow</a>
										</li>

										<!-- <li class="nav-item ">
                                            <a class="nav-link text-active-primary ms-0 me-10 py-5" data-bs-toggle="tab"
                                                href="#paid">Inventory group</a>
                                        </li> -->
									</ul>

								</div>

							</div>

						</div>
					</div>
					<!--begin::Col-->
					<div class="col-xxl-12">
						<div class="tab-content" id="myTabContent">
							<div class="tab-pane fade show active" id="all" role="tabpanel">
								<div class="card shadow-sm card-flush mb-5 mb-xl-1">
									<!--begin::Header-->
									<div class="card-header border-0 pt-5">
										<h3 class="card-title align-items-start flex-column">
											<span class="card-label fw-bold fs-3 mb-1">Total SKU</span>
										</h3>
									</div>
									<!--end::Header-->
									<!--begin::Card body-->
									<div class="card-body d-flex justify-content-between flex-column pb-0 px-0 pt-1">
										<!--begin::Items-->
										<div class="d-flex flex-wrap d-grid gap-5 px-9 mb-5">
											<!--begin::Item-->
											<div class="me-md-2">
												<!--begin::Statistics-->
												<div class="d-flex mb-2">
													<span class="fs-2hx fw-bold text-gray-800 me-2 lh-1 ls-n2" id="all_display_data"></span>
													<span class="fs-4 fw-semibold text-gray-400 me-1">All</span>
												</div>
												<!--end::Statistics-->
											</div>
											<!--end::Item-->
											<!--begin::Item-->
											<div class="px-5 ps-md-10 pe-md-7 me-md-5">
												<!--begin::Statistics-->
												<div class="d-flex mb-2">
													<span class="fs-2hx fw-bold text-gray-800 me-2 lh-1 ls-n2" id="display_in_stock"></span>
													<span class="fs-4 fw-semibold text-success me-1">In Stok</span>
												</div>
												<!--end::Statistics-->
											</div>
											<!--end::Item-->
											<!--begin::Item-->
											<div class="m-0">
												<!--begin::Statistics-->
												<div class="d-flex mb-2">
													<!--begin::Value-->
													<span class="fs-2hx fw-bold text-gray-800 me-2 lh-1 ls-n2" id="display_out_of_stock"></span>
													<!--end::Value-->
													<!--begin::Currency-->
													<span class="fs-4 fw-semibold text-gray-400 align-self-start me-1">Out
														of
														Stock</span>
													<!--end::Currency-->
												</div>
												<!--end::Statistics-->
											</div>
											<!--end::Item-->
										</div>
										<!--end::Items-->
										<div class="card-body py-3">
											<!--begin::Datatable-->
											<input type="hidden" name="paging_datatables" value="0" class="halaman" style="display: none"><input type="hidden" name="draw" value="1" class="draw_datatables" style="display: none">
											<div class="table-responsive">
												<table id="show_display" class="table table-hover table-rounded align-middle gs-0 gy-4">
													<thead>
														<tr class="fw-bold text-muted bg-light">
															<th class="ps-4 rounded-start"></th>
															<th></th>
															<th>ALL</th>
															<th>LIVE</th>
															<th>INACTIVE</th>
															<th>PENDING ACTION</th>
															<th class="rounded-end">OUT OF STOCK</th>
														</tr>
													</thead>
													<tbody>

													</tbody>
													<tfoot>

													</tfoot>
												</table>
											</div>
											<div class="paginationDatatables"></div>
											<div class="border-top border-2 border-gray-300 mb-10"></div>
											<!--end::Datatable-->
										</div>
									</div>
									<!--end::Card body-->
								</div>
							</div>
							<div class="tab-pane fade" id="unpaid" role="tabpanel">
								<div class="card shadow-sm card-flush mb-5 mb-xl-1">
									<!--begin::Header-->
									<div class="card-header border-0 pt-5">
										<h3 class="card-title align-items-start flex-column">
											<span class="card-label fw-bold fs-3 mb-1">Total SKU</span>
										</h3>
									</div>
									<!--end::Header-->
									<!--begin::Card body-->
									<div class="card-body d-flex justify-content-between flex-column pb-0 px-0 pt-1">
										<!--begin::Items-->
										<div class="d-flex flex-wrap d-grid gap-5 px-9 mb-5">
											<!--begin::Item-->
											<div class="me-md-2">
												<!--begin::Statistics-->
												<div class="d-flex mb-2">
													<span class="fs-2hx fw-bold text-gray-800 me-2 lh-1 ls-n2" id="all_shadow_data"></span>
													<span class="fs-4 fw-semibold text-gray-400 me-1">All</span>
												</div>
												<!--end::Statistics-->
											</div>
											<!--end::Item-->
											<!--begin::Item-->
											<div class="px-5 ps-md-10 pe-md-7 me-md-5">
												<!--begin::Statistics-->
												<div class="d-flex mb-2">
													<span class="fs-2hx fw-bold text-gray-800 me-2 lh-1 ls-n2" id="in_stock_shadow_data"></span>
													<span class="fs-4 fw-semibold text-success me-1">In Stok</span>
												</div>
												<!--end::Statistics-->
											</div>
											<!--end::Item-->
											<!--begin::Item-->
											<div class="m-0">
												<!--begin::Statistics-->
												<div class="d-flex mb-2">
													<!--begin::Value-->
													<span class="fs-2hx fw-bold text-gray-800 me-2 lh-1 ls-n2" id="out_of_stock_shadow_data"></span>
													<!--end::Value-->
													<!--begin::Currency-->
													<span class="fs-4 fw-semibold text-gray-400 align-self-start me-1">Out
														of
														Stock</span>
													<!--end::Currency-->
												</div>
												<!--end::Statistics-->
											</div>
											<!--end::Item-->
										</div>
										<!--end::Items-->
										<div class="card-body py-3">
											<!--begin::Datatable-->
											<input type="hidden" name="paging_datatables" value="0" class="halaman" style="display: none"><input type="hidden" name="draw" value="1" class="draw_datatables" style="display: none">
											<div class="table-responsive">
												<table id="show_shadow" class="table table-hover table-rounded align-middle gs-0 gy-4">
													<thead>
														<tr class="fw-bold text-muted bg-light">
															<th class="ps-4 rounded-start"></th>
															<th></th>
															<th>ALL</th>
															<th>LIVE</th>
															<th>INACTIVE</th>
															<th>PENDING ACTION</th>
															<th class="rounded-end">OUT OF STOCK</th>
														</tr>
													</thead>
													<tbody>

													</tbody>
													<tfoot>

													</tfoot>
												</table>
											</div>
											<div class="paginationDatatables"></div>
											<div class="border-top border-2 border-gray-300 mb-10"></div>
											<!--end::Datatable-->
										</div>
									</div>
									<!--end::Card body-->
								</div>
							</div>
							<div class="tab-pane fade" id="paid" role="tabpanel">
								<div class="card shadow-sm card-flush mb-5 mb-xl-1">
									<!--begin::Header-->
									<div class="card-header border-0 pt-5">
										<h3 class="card-title align-items-start flex-column">
											<span class="card-label fw-bold fs-3 mb-1">Total SKUs</span>
										</h3>
									</div>
									<!--end::Header-->
									<!--begin::Card body-->
									<div class="card-body d-flex justify-content-between flex-column pb-0 px-0 pt-1">
										<!--begin::Items-->
										<div class="d-flex flex-wrap d-grid gap-5 px-9 mb-5">
											<!--begin::Item-->
											<div class="me-md-2">
												<!--begin::Statistics-->
												<div class="d-flex mb-2">
													<span class="fs-2hx fw-bold text-gray-800 me-2 lh-1 ls-n2" id="all_group_data"></span>
													<span class="fs-4 fw-semibold text-gray-400 me-1">All</span>
												</div>
												<!--end::Statistics-->
											</div>
											<!--end::Item-->
											<!--begin::Item-->
											<div class="px-5 ps-md-10 pe-md-7 me-md-5">
												<!--begin::Statistics-->
												<div class="d-flex mb-2">
													<span class="fs-2hx fw-bold text-gray-800 me-2 lh-1 ls-n2" id="show_in_stock"></span>
													<span class="fs-4 fw-semibold text-success me-1">In Stock</span>
												</div>
												<!--end::Statistics-->
											</div>
											<!--end::Item-->
											<!--begin::Item-->
											<div class="m-0">
												<!--begin::Statistics-->
												<div class="d-flex mb-2">
													<!--begin::Value-->
													<span class="fs-2hx fw-bold text-gray-800 me-2 lh-1 ls-n2" id="show_out_of_stock_group"></span>
													<!--end::Value-->
													<!--begin::Currency-->

													<span class="fs-4 fw-semibold text-gray-400 align-self-start me-1">Out
														of
														Stock</span>
													<!--end::Currency-->
												</div>
												<!--end::Statistics-->
											</div>
											<!--end::Item-->
										</div>
										<!--end::Items-->
										<div class="card-body py-3">
											<!--begin::Datatable-->
											<input type="hidden" name="paging_datatables" value="0" class="halaman" style="display: none"><input type="hidden" name="draw" value="1" class="draw_datatables" style="display: none">
											<div class="table-responsive">
												<table id="show_group" class="table table-hover table-rounded align-middle gs-0 gy-4">
													<thead>
														<tr class="fw-bold text-muted bg-light">
															<th class="ps-4 rounded-start"></th>
															<th></th>
															<th>ALL</th>
															<th>LIVE</th>
															<th>INACTIVE</th>
															<th>PENDING ACTION</th>
															<th class="rounded-end">OUT OF STOCK</th>
														</tr>
													</thead>
													<tbody>

													</tbody>
													<tfoot>

													</tfoot>
												</table>
											</div>
											<div class="paginationDatatables"></div>
											<div class="border-top border-2 border-gray-300 mb-10"></div>
											<!--end::Datatable-->
										</div>
									</div>
									<!--end::Card body-->
								</div>
							</div>
						</div>
					</div>
					<!--end::Col-->
				</div>
				<!--end::Row-->
			</div>
			<!--end::Content container-->
		</div>
		<!--end::Content-->
	</div>
	<!--end::Content wrapper-->
</div>