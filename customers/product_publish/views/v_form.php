<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<div class="d-flex flex-column flex-column-fluid">

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
		<div id="kt_app_content_container" class="app-container ">

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
						</ul>

					</div>

				</div>

			</div>

			<div class="tab-content" id="myTabContent">

				<div class="tab-pane fade show active" id="all" role="tabpanel">

					<div class="card shadow-lg mt-6 p-10">

						<div class="d-flex flex-wrap flex-stack ">
							<div class="d-flex flex-wrap align-items-center my-1">
								<div class="d-flex align-items-center position-relative my-3">

									<select name="status" data-control="select2" data-hide-search="true" data-placeholder="Filter" class="form-select form-select-sm form-select-solid w-180px ">
										<option value="1">Recently Updated</option>
										<option value="2">Last Month</option>
										<option value="3">Last Quarter</option>
										<option value="4">Last Year</option>
									</select>

									<div class="position-relative ms-3">
										<input type="text" id="kt_filter_search" class="form-control form-control-sm form-control-solid w-200px" placeholder="Search" />

										<span class="svg-icon svg-icon-3 position-absolute top-50 end-0 translate-middle-y me-3">
											<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
												<rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
												<path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="currentColor" />
											</svg>
										</span>

									</div>
								</div>

							</div>

							<div class="d-flex flex-wrap my-1">

								<div class="d-flex my-0">

									<div class="mb-0">
										<div class="input-group">
											<span class="input-group-text">

												<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar" viewBox="0 0 16 16">
													<path d="M0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2V13a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zM1 0a1 1 0 0 1 1 1v11a1 1 0 0 1-1 1h14a1 1 0 0 1-1-1V1a1 1 0 0 1-1-1H2a1 1 0 0 1-1 1zm4 12h1V7H5v5zm2 0h1V7H7v5zm2 0h1V7H9v5zm2 0h1V7h-1v5z" />
												</svg>
											</span>
											<input class="form-control form-control-sm form-control-solid" placeholder="Pick date range" id="kt_daterangepicker_1" />

										</div>
									</div>

								</div>

							</div>
						</div>

						<!-- <div class="d-flex flex-wrap flex-stack pb-7">
							<div class="d-flex flex-wrap align-items-center my-1">
								<div class="form-check form-check-custom form-check-solid form-check-sm">
									<input class="form-check-input" type="checkbox" value="" id="bulkSelect" />
									<label class="form-check-label text-gray-700 fw-bold" for="bulkSelect">
										Bulk Select
									</label>
								</div>

								<div class="d-flex align-items-center position-relative  ms-15">
									<span class="text-gray-700 fw-bold w-100">Sort by : </span><select name="status" data-control="select2" data-hide-search="true" data-placeholder="Filter" class="form-select form-select-sm  w-150px border-0 ms-2 ">
										<option selected>Choose...</option>
										<option value="option1">Option 1</option>
										<option value="option2">Option 2</option>
										<option value="option3">Option 3</option>
									</select>
								</div>
							</div>
						</div> -->

						<div class="col-xl-12 h-xl-100  p-3">

							<!-- <div class="d-flex flex-row flex-stack flex-wrap pb-1">
								<div class="d-flex flex-column py-2">
									<div class="d-flex align-items-center">
										<div class="form-check form-check-custom form-check-solid form-check-sm">
											<input class="form-check-input" type="checkbox" value="" id="selectOrder" />
											<label class="form-check-label text-gray-700 fw-bold" for="selectOrder">
											</label>
										</div>

										<img src="<?= MEDIA . '/marketplace/shopee-icon.png' ?>" alt="" class="me-4" width="40" />

										<div>
											<div class="fs-5 fw-bold">Visa **** | IDR. 50.000</div>
											<div class="fs-6 fw-semibold text-gray-700">Shopee | Order ID :
												<a href="">SO-10421441</a> / 41241241214F
											</div>
										</div>

									</div>
								</div>

								<div class="d-flex align-items-right">
									<div class=" text-end">
										<div class="fs-7 fw-semibold text-gray-700">Order Date</div>
										<div class="fs-6 fw-bold ">30 Sep 2023, 02:41 AM</div>
									</div>
									<button class="btn btn-sm btn-primary mx-3">Create Shipment</button>
									<a href="#" class="btn btn-icon btn-secondary">
										<i class="bi bi-three-dots-vertical fs-1"></i>
									</a>
								</div>
								<img src="<?= MEDIA . '/marketplace/shopee-icon.png' ?>" alt="" class="me-4" width="30" />
							</div> -->

							<table id="datatable_display_publish" class="table align-top table-row-dashed table-row-bordered dataTable no-footer">
								<thead>
									<tr class="fw-semibold fs-6 text-gray-800">
										<th class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
											<div class="form-check form-check-sm form-check-custom form-check-solid me-3">
												<input class="form-check-input checked_bulk" type="checkbox" />
											</div>
										</th>
										<th>Product Name</th>
										<th class="min-w-100px">Master SKU</th>
										<th class="min-w-100px">Source & Channel</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>

						</div>
					</div>
				</div>

				<div class="tab-pane fade" id="unpaid" role="tabpanel">
					tes2
				</div>
			</div>
		</div>
	</div>


	<div class="toolbar" id="floatingToolbar">
		<div class="row">
			<div class="col-md-12">
				<div class="d-flex justify-content-end">
					<!-- Add toolbar buttons or content here -->
					<a href="#" class="btn btn-light-danger mx-3">Danger</a>
					<a href="#" class="btn btn-light-primary mx-3">Danger</a>
				</div>
			</div>
		</div>
	</div>


	<style>
		#floatingToolbar {
			display: none;
			position: fixed;
			bottom: 0;
			left: 0;
			width: 100%;
			background: rgb(255, 121, 121);
			background: linear-gradient(277deg, rgba(255, 121, 121, 1) 0%, rgba(254, 197, 167, 1) 100%);
			padding: 10px;
			border: 0;
			box-shadow: 0px 0px 2px rgba(0, 0, 0, 0.2);
		}
	</style>
