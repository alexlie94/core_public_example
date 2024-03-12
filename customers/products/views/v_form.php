<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<style>
	.select2-basic+span+span>span.select2-dropdown.select2-dropdown--below {
		top: -6px;
	}

	button.select2-selection__clear {
		margin-top: -4px;
	}
</style>


<div class="row mb-4">

	<div class="col-md-12" style="padding: 20px;">

		<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
			<h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
				Product Information
			</h1>
		</div>

		<div class="row mt-4">

			<div class="col-md-2" id="brand_parent">
				<label class="required fw-semibold fs-6 mb-4">Brand</label>
				<select class="form-select form-select-lg parentSelect" data-dropdown-parent="#modalLarge2" data-kt-repeater="select2" data-control="select2" data-placeholder="Select an option" id="select_brand_id" name="select_brand_id">
					<option value=""></option>
					<?php
					foreach ($brands as $res) {
					?>
						<option value='<?= $res->id ?>'>
							<?= $res->brand_name ?>
						</option>
					<?php } ?>
				</select>
			</div>

			<div class="col-md-5" id="supplier_parent">
				<label class="required fw-semibold fs-6 mb-4">Suppliers</label>

				<select class="form-select form-select-lg parentSelect" data-dropdown-parent="#modalLarge2" data-kt-repeater="select2" data-control="select2" data-placeholder="Select an option" id="select_supplier_id" name="select_supplier_id">
					<option value=""></option>
					<?php
					foreach ($suppliers as $res) {
					?>
						<option value='<?= $res->id ?>'>
							<?= $res->supplier_name ?>
						</option>
					<?php } ?>
				</select>
			</div>

			<div class="col-md-3" id="category_parent">
				<label class="required fw-semibold fs-6 mb-4">Category</label>

				<select class="form-select form-select-lg parentSelect" data-dropdown-parent="#modalLarge2" data-kt-repeater="select2" data-control="select2" data-placeholder="Select an option" id="select_category_id" name="select_category_id">
					<option value=""></option>
					<?php
					foreach ($category as $res) {
					?>
						<option value='<?= $res->id ?>'>
							<?= $res->categories_name ?>
						</option>
					<?php } ?>
				</select>
			</div>

			<div class="col-md-2">

				<div class="card-toolbar" align="right">
					<button type="button" data-fullscreenmodal="0" data-type="modal" data-url="<?= base_url('products/massUpload') ?>" class="btn btn-outline btn-outline-dashed btn-outline-warning btn-active-light-warning btn-sm" id="btn_show_mass_upload" style="margin-top: 37px;">
						<span class="svg-icon svg-icon-primary svg-icon-2x">
							<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
								<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
									<polygon points="0 0 24 0 24 24 0 24" />
									<path d="M5.85714286,2 L13.7364114,2 C14.0910962,2 14.4343066,2.12568431 14.7051108,2.35473959 L19.4686994,6.3839416 C19.8056532,6.66894833 20,7.08787823 20,7.52920201 L20,20.0833333 C20,21.8738751 19.9795521,22 18.1428571,22 L5.85714286,22 C4.02044787,22 4,21.8738751 4,20.0833333 L4,3.91666667 C4,2.12612489 4.02044787,2 5.85714286,2 Z" fill="currentColor" fill-rule="nonzero" opacity="0.3" />
									<path d="M8.95128003,13.8153448 L10.9077535,13.8153448 L10.9077535,15.8230161 C10.9077535,16.0991584 11.1316112,16.3230161 11.4077535,16.3230161 L12.4310522,16.3230161 C12.7071946,16.3230161 12.9310522,16.0991584 12.9310522,15.8230161 L12.9310522,13.8153448 L14.8875257,13.8153448 C15.1636681,13.8153448 15.3875257,13.5914871 15.3875257,13.3153448 C15.3875257,13.1970331 15.345572,13.0825545 15.2691225,12.9922598 L12.3009997,9.48659872 C12.1225648,9.27584861 11.8070681,9.24965194 11.596318,9.42808682 C11.5752308,9.44594059 11.5556598,9.46551156 11.5378061,9.48659872 L8.56968321,12.9922598 C8.39124833,13.2030099 8.417445,13.5185067 8.62819511,13.6969416 C8.71848979,13.773391 8.8329684,13.8153448 8.95128003,13.8153448 Z" fill="currentColor" />
								</g>
							</svg>
						</span>
						Mass Upload Product
					</button>
				</div>

			</div>
		</div>
	</div>
</div>

<!--begin::Repeater-->
<div id="kt_docs_repeater_nested">

	<div class="card">
		<div class="card-body">
			<!--begin::Form group-->
			<div class="form-group" id="adding_field">
				<div data-repeater-list="kt_docs_repeater_nested_outer">

					<a href="javascript:;" style="display: none;" id="add_product_parent" data-repeater-create class="btn btn-flex btn-light-primary"></a>

					<div data-repeater-item>
						<div class="card card-bordered mb-5 groupCard border border-dark" style="padding: 20px;">

							<div class="row">
								<div class="col-md-12" style="padding: 20px;">
									<div class="row">

										<div class="col-md-2" align="right">

										</div>

										<div class="col-md-10" align="right">
											<div class="form-group">

												<button id="addProduct_1" class="btn btn-outline btn-outline-dashed btn-outline-primary btn-active-light-primary btn-sm addProduct" type="button">
													<i class="fa-solid fa-plus fs-4 me-2"></i>
													Add Products
												</button>

												<button id="add_product_variant_1" class="btn btn-outline btn-outline-dashed btn-outline-success btn-active-light-success btn-sm" type="button" style="margin-left: 6px;margin-right: 6px;">
													<i class="fa-solid fa-plus fs-4 me-2"></i>
													Add Variant Products
												</button>

												<button type="button" class="btn btn-outline btn-outline-dashed btn-outline-danger btn-active-light-danger mb-3 btn-sm remove_prod" style="margin-top: 8px;" data-repeater-delete>
													<i class="fa-solid fa-trash-can fs-4 me-2"></i>
													Remove Product
												</button>
											</div>
										</div>

									</div>

								</div>
							</div>

							<div class="row">
								<div class="col-md-12">
									<div class="row" id="listed_input">

										<div class="col-md-4">
											<label class="required fw-semibold fs-6 mb-4">Product</label>
											<input type="text" id="products_name" name="products_name" class="form-control mb-3 mb-lg-0" placeholder="Product Name" value="<?= isset($dataItems->product_name) ? $dataItems->product_name : '' ?>" data-type="input" autocomplete="off" />
										</div>

										<div class="col-md-2">
											<label class="required fw-semibold fs-6 mb-4">Gender</label>
											<select class="form-select childSelect" data-type='select' data-control="select2" data-kt-repeater="select2" data-hide-search="true" data-placeholder="Select an option" name="select_gender">
												<option value=""></option>
												<option value="man">
													Man
												</option>
												<option value="woman">
													Woman
												</option>
											</select>
										</div>

										<div class="col-md-2">
											<label class="fw-semibold fs-6 mb-4">Sub Category</label>
											<select class="form-select childSelect" data-type='select' data-control="select2" data-kt-repeater="select2" data-hide-search="true" data-placeholder="Select an option" name="select_sub_category">
											</select>
										</div>

										<div class="col-md-2">
											<label class="fw-semibold fs-6 mb-4">Sub Sub Category</label>
											<select class="form-select childSelect" data-type='select' data-control="select2" data-kt-repeater="select2" data-hide-search="true" data-placeholder="Select an option" name="select_sub2_category">
											</select>
										</div>

										<div class="col-md-2">
											<label class="fw-semibold fs-6 mb-4">Price</label>
											<div class="input-group mb-5">
												<span class="input-group-text" id="basic-addon1">IDR</span>
												<input type="number" class="form-control mw-100" data-type="input" onkeyup="formatCurrency(this)" id="price" name="price" placeholder="Price" />
											</div>
										</div>
									</div>
								</div>
							</div>

							<div class="form-group row flex-nowrap overflow-auto">
								<div class="col-md-12" style="padding: 20px;">
									<div class="inner-repeater">
										<div data-repeater-list="kt_docs_repeater_nested_inner">

											<button style="display: none;" id="target_variant_click_1" data-repeater-create type="button">sss</button>

											<div data-repeater-item>
												<div class="row">
													<div class="col-md-12">
														<div class="row" id="listed_input">

															<div class="col-md-1">
															</div>

															<div class="col-md-3">
																<label class="required form-label">General Color</label>
																<div class="input-group pb-3">
																	<select class="form-select childSubSelect" data-dropdown-parent="#modalLarge2" data-control="select2" data-placeholder="Select an option" name="generate_color">
																		<option value=""></option>
																		<?php
																		foreach ($general_color as $res) {
																		?>
																			<option value='<?= $res->id ?>'>
																				<?= $res->color_name ?>
																			</option>
																		<?php } ?>
																	</select>
																</div>
															</div>

															<div class="col-md-2">
																<label class="required form-label">Variant Color</label>
																<div class="input-group pb-3">
																	<select class="form-select childSubSelect" data-dropdown-parent="#modalLarge2" data-control="select2" data-placeholder="Select an option" name="variant_color">
																	</select>
																</div>
															</div>

															<div class="col-md-2">
																<label class="required form-label">Variant Color Name</label>
																<div class="input-group pb-3">
																	<input type="text" class="form-control mw-100 " data-type="input" id="variant_color_name" name="variant_color_name" placeholder="Variant Color Name" />
																</div>
															</div>

															<div class="col-md-2">
																<label class="required form-label">Size</label>
																<div class="input-group pb-3">
																	<input type="text" class="form-control mw-100 " data-type="input" id="size" name="size" placeholder="Size" />
																</div>
															</div>


															<div class="col-md-2" style="text-align: right;">
																<button type="button" class="btn btn-outline btn-outline-dashed btn-outline-danger btn-active-light-danger mb-3 btn-sm" style="margin-top: 30px;" data-repeater-delete>
																	<i class="fa-solid fa-trash-can fs-4 me-2"></i>
																	Remove Variant
																</button>
															</div>

														</div>
													</div>
												</div>
											</div>

										</div>
									</div>
								</div>
							</div>

						</div>
					</div>


				</div>
			</div>
		</div>
	</div>
</div>