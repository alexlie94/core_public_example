<div class="card shadow-sm p-5">
	<div class="notice d-flex bg-light-warning rounded border-warning border border-dashed p-6">
		<!--begin::Icon-->
		<!--begin::Svg Icon | path: icons/duotune/general/gen044.svg-->
		<span class="svg-icon svg-icon-2tx svg-icon-warning me-4">
			<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
				<rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="currentColor" />
				<rect x="11" y="14" width="7" height="2" rx="1" transform="rotate(-90 11 14)" fill="currentColor" />
				<rect x="11" y="17" width="2" height="2" rx="1" transform="rotate(-90 11 17)" fill="currentColor" />
			</svg>
		</span>
		<div class="d-flex flex-stack flex-grow-1">
			<!--begin::Content-->
			<div class="fw-semibold">
				<h4 class="text-gray-900 fw-bold">We need your attention!</h4>
				<div class="fs-6 text-gray-700">Your product is currently not registered on the marketplace. To start using tools, please
					<a class="fw-bold popovers" href="javascript:void(0)" data-bs-toggle="popover" data-bs-html="true" data-bs-dismiss="true" title="<span><b>Please choose your channel</b></span>" data-bs-content=' '>Choose channel</a>.
				</div>
			</div>
			<!--end::Content-->
		</div>
	</div>
</div>

<!--begin::Modal - Create Campaign-->
<div class="modal fade" id="kt_modal_create_campaign" tabindex="-1" aria-hidden="true" role="dialog" aria-labelledby="myModalLabel160">
	<!--begin::Modal dialog-->
	<div class="modal-dialog modal-fullscreen p-9">
		<!--begin::Modal content-->
		<div class="modal-content modal-rounded">
			<!--begin::Modal header-->
			<div class="modal-header py-7 d-flex justify-content-between">
				<!--begin::Modal title-->
				<span>
					<h2>Create Campaign</h2>
				</span>
				<!--end::Modal title-->
				<!--begin::Close-->
				<div class="d-flex align-items-center">
					<button class="btn btn-outline btn-outline-dashed btn-outline-primary btn-active-light-primary hover-scale btn-sm me-8"> <i class="fa-solid fa-floppy-disk fs-4 me-2"></i> Save</button>
					<!-- "Publish" button -->
					<button class="btn btn-outline btn-outline-dashed btn-outline-success btn-active-light-success hover-scale btn-sm me-4"> <i class="fa-solid fa-cloud-upload fs-4 me-2"></i> Save & Publish</button>
					<div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
						<!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
						<span class="svg-icon svg-icon-1">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
								<rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor" />
								<rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="currentColor" />
							</svg>
						</span>
						<!--end::Svg Icon-->
					</div>
				</div>
				<!--end::Close-->
			</div>
			<!--begin::Modal header-->
			<!--begin::Modal body-->
			<div class="modal-body scroll-y">
				<div class="card mb-1">
					<div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse" data-bs-target="#kt_account_profile_details" aria-expanded="true" aria-controls="kt_account_profile_details">
						<!--begin::Card title-->
						<div class="card-title m-0">
							<h3 class="fw-bold m-0">Product display</h3>
						</div>
					</div>
					<!--end::Card title-->
					<div id="kt_account_settings_profile_details" class="collapse show">
						<form id="form" data-url="<?= $url_form ?>" class="form">
							<input type="hidden" id="product_id" name="product_id" value="<?= $channel[0]['users_ms_products_id'] ?>">
							<input type="hidden" id="sources_id" name="sources_id" value="<?= $channel[0]['admins_ms_sources_id'] ?>">
							<!--begin::Card body-->
							<div class="card-body border-top p-4">
								<!--begin::Input group-->
								<div class="row mb-6">
									<div class="col-lg-4">
										<div class="border border-dashed border-gray-300 rounded px-7 py-3 mb-6">
											<div class="d-flex flex-stack">
												<!--begin::Wrapper-->
												<div class="ms-9 list-img">

												</div>
											</div>
										</div>
									</div>

									<div class="col-lg-8">
										<div class="border border-dashed border-gray-300 rounded px-7 py-3">
											<div class="d-flex flex-stack">
												<!--begin::Wrapper-->
												<div class="ms-0">
													<h3 class="card-title align-items-start flex-column">
														<span class="card-label fw-bold text-dark">Stock Report</span>
														<span class="text-gray-400 mt-1 fw-semibold fs-6">Total 2,356 Items in the Stock</span>
													</h3>
													<!--begin::Card body-->
													<div class="card-body p-0">
														<!--begin::Table-->
														<table class="table align-middle table-row-dashed gy-3" id="kt_table_widget_5_table">
															<!--begin::Table head-->
															<thead>
																<!--begin::Table row-->
																<tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
																	<th class="text-end pe-3 min-w-100px">Product ID</th>
																	<th class="min-w-100px">Product Name</th>
																	<th class="min-w-100px">SKU</th>
																	<th class="text-end pe-3 min-w-150px">Date Added</th>
																	<th class="text-end pe-3 min-w-100px">Price</th>
																	<th class="text-end pe-3 min-w-50px">Status</th>
																	<th class="text-end pe-0 min-w-25px">Qty</th>
																</tr>
																<!--end::Table row-->
															</thead>
															<!--end::Table head-->
															<!--begin::Table body-->
															<tbody class="fw-bold text-gray-600">
																<?php if (isset($product)) {
																	foreach ($product as $rows) {
																?>
																		<tr>
																			<td class="text-center">#<?= $rows['id'] ?></td>
																			<td>
																				<a href="javascript:void(0)" class="text-dark text-hover-primary"><?= $rows['product_name'] ?></a>
																			</td>
																			<td>
																				<a href="javascript:void(0)" class="text-dark text-hover-primary"><?= $rows['sku'] ?></a>
																			</td>
																			<td class="text-end"><?= $rows['created_at'] ?></td>
																			<td class="text-end">$1,230</td>
																			<td class="text-end">
																				<?php if ($rows['qty'] > 0) { ?>
																					<span class="badge py-3 px-4 fs-7 badge-light-success">In Stock</span>
																				<?php } else { ?>
																					<span class="badge py-3 px-4 fs-7 badge-light-danger">Out of Stock</span>
																				<?php } ?>
																			</td>
																			<td class="text-end" data-order="<?= $rows['qty'] ?>">
																				<span class="text-dark fw-bold"><?= $rows['qty'] ?> PCS</span>
																			</td>
																		</tr>
																<?php }
																} ?>
															</tbody>
															<!--end::Table body-->
														</table>
														<!--end::Table-->
													</div>
													<!--end::Card body-->
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>


				<div class="card card-flush py-4 mb-1">
					<div class="card-header">
						<div class="card-title">
							<h3>Product Details</h3>
						</div>
					</div>
					<div class="card-body pt-0">
						<!--begin::Input group-->
						<div class="mb-10 row">
							<div class="col-lg-6">
								<!--begin::Label-->
								<label class="required form-label">Product Name</label>
								<!--end::Label-->
								<!--begin::Input-->
								<input type="text" name="product_name" class="form-control mb-2" placeholder="Product name" value="" />
								<!--end::Input-->
								<!--begin::Description-->
								<div class="text-muted fs-7">A product name is required and recommended to be unique.</div>
								<!--end::Description-->
							</div>
							<div class="col-lg-6">
								<!--begin::Label-->
								<label class="required form-label">Category</label>
								<!--end::Label-->
								<!--begin::Input-->
								<input type="text" name="product_name" class="form-control mb-2" placeholder="Category" value="" />
								<!--end::Input-->
							</div>
						</div>
						<div class="mb-10 row">
							<div class="col-lg-6">
								<!--begin::Label-->
								<label class="required form-label">Brand</label>
								<!--end::Label-->
								<!--begin::Input-->
								<input type="text" name="product_name" class="form-control mb-2" placeholder="Product name" value="" />
								<!--end::Input-->
								<!--begin::Description-->
								<div class="text-muted fs-7">A product name is required and recommended to be unique.</div>
								<!--end::Description-->
							</div>
							<div class="col-lg-6">
								<!--begin::Label-->
								<label class="form-label">Condition</label>
								<!--end::Label-->
								<select class="form-select mb-2" data-hide-search="true" data-placeholder="Select an option" id="condition" name="condition">
									<option value="new" selected="selected">New</option>
									<option value="used">Used</option>
								</select>
							</div>
						</div>
						<!--end::Input group-->
						<!--begin::Input group-->
						<div>
							<!--begin::Label-->
							<label class="required form-label">Description</label>
							<!--end::Label-->
							<!--begin::Editor-->
							<textarea id="product_description" name="product_description" rows="4" class="form-control mb-2" data-bv-stringlength="true" data-bv-stringlength-min="20" data-bv-stringlength-message="Minimum 20 characters required"></textarea>
							<!--end::Editor-->
							<!--begin::Description-->
							<div class="text-muted fs-7">Please enter a minimum of 20 characters.</div>
							<!--end::Description-->
						</div>
						<!--end::Input group-->
					</div>
				</div>

				<!--begin::Shipping-->
				<div class="card card-flush py-4 mb-10">
					<!--begin::Card header-->
					<div class="card-header">
						<div class="card-title">
							<h3>Shipping</h3>
						</div>
					</div>
					<!--end::Card header-->
					<!--begin::Card body-->
					<div class="card-body pt-0">
						<!--begin::Shipping form-->
						<div id="kt_ecommerce_add_product_shipping" class="mt-3">
							<!--begin::Input group-->
							<div class="mb-5 fv-row col-lg-4">
								<!--begin::Label-->
								<label class="required form-label">Weight</label>
								<!--end::Label-->
								<!--begin::Editor-->
								<input type="text" name="weight" class="form-control" placeholder="Product weight" value="" />
								<!--end::Editor-->
								<!--begin::Description-->
								<div class="text-muted fs-7">Set a product weight in grams (gr).</div>
								<!--end::Description-->
							</div>
							<!--end::Input group-->
							<!--begin::Input group-->
							<div class="mb-5 fv-row">
								<!--begin::Label-->
								<label class="form-label">Dimension</label>
								<!--end::Label-->
								<!--begin::Input-->
								<div class="d-flex flex-wrap flex-sm-nowrap gap-3">
									<input type="number" name="width" class="form-control" placeholder="Width (w)" value="" />
									<input type="number" name="height" class="form-control" placeholder="Height (h)" value="" />
									<input type="number" name="length" class="form-control" placeholder="Lengtn (l)" value="" />
								</div>
								<!--end::Input-->
								<!--begin::Description-->
								<div class="text-muted fs-7">Enter the product dimensions in centimeters (cm).</div>
								<!--end::Description-->
							</div>
							<!--end::Input group-->
							<div class="fv-row">
								<!--begin::Label-->
								<label class="required form-label">Shipping List</label>
								<!--end::Label-->

								<select class="form-select" data-placeholder="Select an option" multiple="multiple" id="shipping_list" name="shipping_list">
									<option value="1">Option 1</option>
									<option value="2">Option 2</option>
								</select>

								<!--begin::Description-->
								<div class="text-muted fs-7">Make sure you have filled in the weight before selecting a shipping list.</div>
								<!--end::Description-->
							</div>
						</div>
						<!--end::Shipping form-->
					</div>
					<!--end::Card header-->
				</div>
				<!--end::Shipping-->

			</div>
			<!--begin::Modal body-->
		</div>
	</div>
</div>
<!--end::Modal - Create Campaign-->
<!-- Bootstrap Validator CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-validator/0.5.3/css/bootstrapValidator.min.css">

<!-- Bootstrap Validator JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-validator/0.5.3/js/bootstrapValidator.min.js"></script>

<script>
	var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
	var popoverList = popoverTriggerList.map(function(popoverTriggerEl) {
		return new bootstrap.Popover(popoverTriggerEl);
	});
	var data_channel = <?php echo json_encode($channel); ?>;
	var data_images = <?php echo json_encode($images); ?>;

	// // Fungsi untuk menambahkan tombol ke popover saat popover ditampilkan
	document.addEventListener('shown.bs.popover', function(event) {
		var popover = bootstrap.Popover.getInstance(event.target);
		var content = popover.tip.querySelector('.popover-body');
		var html = "";

		for (let index = 0; index < data_channel.length; index++) {
			html += `<input type="radio" class="btn-check" name="radio_buttons_2" data-channel_name="` + data_channel[index].channel_name + `" value="` + data_channel[index].users_ms_channels_id + `" id="kt_radio_buttons_2_option_` + index + `" onclick="buttonClick(this)"/>
					<label class="btn btn-outline btn-outline-dashed btn-active-light-primary p-4 d-flex align-items-center mb-5" for="kt_radio_buttons_2_option_` + index + `">
						<span class="d-block fw-semibold text-start">
							<span class="text-dark fw-bold d-block fs-3">` + data_channel[index].channel_name + `</span>
						</span>
					</label>`;
		}
		$(content).html(html);
	});

	// Fungsi yang akan dijalankan saat tombol di klik
	function buttonClick(event) {
		$("a.popovers").popover("hide");
		$("#kt_modal_create_campaign").modal('show');
		$("#kt_modal_create_campaign h2").html('<img alt="" src="<?= check_image_source('shopee') ?>" width="30" style="margin-top: -10px;"> <span>' + $(event).attr('data-channel_name') + '</span>');
		get_data_marketplace($(event).val());
	}

	function get_data_marketplace(channel) {
		const filteredData = data_images.filter(
			(item) => item.users_ms_channels_id === channel
		);

		var img_html = ""
		for (let x = 0; x < filteredData.length; x++) {
			img_html += `<img src="` + url_asset_metronic + `media/misc/spinner.gif" data-src="` + url_asset + `products_image/` + filteredData[x].image_name + `" class="lozad rounded w-100px ms-n1 me-6 mb-6" alt="" />`;
		}
		$(".list-img").html(img_html);

		setTimeout(() => {
			const observer = lozad(); // Initialize Lozad
			observer.observe();
		}, 300);
	}


	$('#condition').each(function() {
		var $this = $(this);
		$this.select2({
			minimumResultsForSearch: Infinity,
			dropdownParent: $this.parent()
		});
	});

	$('#shipping_list').each(function() {
		var $this = $(this);
		$this.select2({
			placeholder: "--Choose Options--",
			minimumResultsForSearch: Infinity,
			dropdownParent: $this.parent(),
			allowClear: true,
		});
	});
</script>