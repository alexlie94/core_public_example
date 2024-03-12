<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<form id="formEditProduct">
    <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bold">
        <!--begin::Nav item-->
        <li class="nav-item mt-2">
            <a class="nav-link text-active-primary ms-0 me-10 py-5 active" data-bs-toggle="tab"
                href="#kt_tab_pane_1">Detail</a>
        </li>
        <!--end::Nav item-->
        <!--begin::Nav item-->
        <li class="nav-item mt-2">
            <a class="nav-link text-active-primary ms-0 me-10 py-5" data-bs-toggle="tab" href="#kt_tab_pane_2">Type</a>
        </li>
        <!--end::Nav item-->
        <!--begin::Nav item-->
        <li class="nav-item mt-2">
            <a class="nav-link text-active-primary ms-0 me-10 py-5" data-bs-toggle="tab"
                href="#kt_tab_pane_3">Description</a>
        </li>
        <!--end::Nav item-->
    </ul>
    <!--begin::Navs-->

    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="kt_tab_pane_1" role="tabpanel">
            <div class="">
                <!--begin::Order details-->
                <div class="card card-flush py-4">
                    <!--begin::Card body-->
                    <div class="card-body pt-0">
                        <!--begin::Billing address-->
                        <div class="row">

                            <!--begin::Input group-->
                            <div class="row col-md-6">

                                <div class="col-md-12  mb-5">
                                    <div class="flex-row-fluid">
                                        <!--begin::Label-->
                                        <label class=" form-label">Product</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <input class="form-control" name="product" id="product"
                                            placeholder="Product Name"
                                            value="<?= isset($all_data->product_name) ? $all_data->product_name : '' ?>" />
                                        <!--end::Input-->
                                    </div>
                                </div>
                                <div class="col-md-12  mb-5">
                                    <div class="flex-row-fluid">
                                        <!--begin::Label-->
                                        <label class=" form-label">Supplier</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <select class="form-select " id="supplier" name="supplier">
                                            <option
                                                value="<?= isset($all_data->users_ms_suppliers_id) ? $all_data->users_ms_suppliers_id : '' ?>	"
                                                selected>
                                                <?= isset($all_data->supplier_name) ? $all_data->supplier_name : 'Select Option' ?>
                                            </option>
                                            <?php 
										foreach($supplier as $row){
											echo '<option value="'.$row->id.'">'.$row->supplier_name.' </option>';
										} ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12 mb-5">
                                    <div class="flex-row-fluid">
                                        <!--begin::Label-->
                                        <label class=" form-label">Brand</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <select class="form-select" id="brand" name="brand">
                                            <option
                                                value="<?= isset($all_data->users_ms_brands_id) ? $all_data->users_ms_brands_id : '' ?>	"
                                                selected>
                                                <?= isset($all_data->brand_name) ? $all_data->brand_name : 'Select Option' ?>
                                            </option>

                                            <?php 
										foreach($brand as $row){
											echo '<option value="'.$row->id.'">'.$row->brand_name.' </option>';
										} ?>
                                        </select>
                                        <!--end::Input-->
                                    </div>
                                </div>
                                <div class="col-md-12 mb-5">
                                    <div class="flex-row-fluid">
                                        <!--begin::Label-->
                                        <label class=" form-label">Gender</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <select class="form-select form-select mb-2" data-type='select'
                                            data-control="select2" data-hide-search="true"
                                            data-placeholder="Select an option" id="gender" name="gender">
                                            <option value="<?= isset($all_data->gender) ? $all_data->gender : '' ?>	"
                                                selected>
                                                <?= isset($all_data->gender) ? ucfirst($all_data->gender) : 'Select Option' ?>
                                            </option>
                                            <option value="man">
                                                Man
                                            </option>
                                            <option value="woman">
                                                Woman
                                            </option>
                                        </select>
                                        <!--end::Input-->
                                    </div>
                                </div>
                                <div class="col-md-12  mb-5">
                                    <div class="flex-row-fluid">
                                        <!--begin::Label-->
                                        <label class=" form-label">Ownership Type</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <h4 id="ownership_type">Private Label Buying</h4>
                                        <!--end::Input-->
                                    </div>
                                </div>
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="row col-md-6">
                                <div class="col-md-12  mb-5">
                                    <div class="fv-row flex-row-fluid">
                                        <!--begin::Label-->
                                        <label class=" form-label">Price</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <input class="form-control" name="product_price" id="product_price"
                                            onkeyup="formatCurrency(this)" placeholder="Price"
                                            value="<?= isset($all_data->product_price) ? format_number_to_idr($all_data->product_price,0) : '' ?>" />
                                        <!--end::Input-->
                                    </div>
                                </div>
                                <div class="col-md-12  mb-5">
                                    <div class="fv-row flex-row-fluid">
                                        <!--begin::Label-->
                                        <label class=" form-label">Offline Price</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <input class="form-control" name="product_offline_price"
                                            id="product_offline_price" onkeyup="formatCurrency(this)"
                                            placeholder="Offline Price"
                                            value="<?= isset($all_data->product_offline_price) ? format_number_to_idr($all_data->product_offline_price,0) : '' ?>" />
                                        <!--end::Input-->
                                    </div>
                                </div>
                                <div class="col-md-12 mb-5">
                                    <div class="fv-row flex-row-fluid">
                                        <!--begin::Label-->
                                        <label class=" form-label">Sale Price</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <input class="form-control" name="product_sale_price" id="product_sale_price"
                                            placeholder="Sale Price" onkeyup="formatCurrency(this)"
                                            value="<?= isset($all_data->product_sale_price) ? format_number_to_idr($all_data->product_sale_price,0) : '' ?>" />
                                        <!--end::Input-->
                                    </div>
                                </div>
                                <div class="col-md-12  mb-5">
                                    <div class="flex-row-fluid">
                                        <!--begin::Label-->
                                        <label class=" form-label">Status</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <h4>Enabled</h4>
                                        <!--end::Input-->
                                    </div>
                                </div>
                                <div class="col-md-12  mb-5">
                                    <div class="fv-row flex-row-fluid">
                                        <!--begin::Label-->
                                        <label class=" form-label">Size</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <input class="form-control" name="size" id="size"
                                            value="<?= isset($size->size) ? $size->size : '' ?>" disabled />
                                        <!--end::Input-->
                                    </div>
                                </div>
                                <!-- <div class="col-md-12  mb-5">
                                <div class="flex-row-fluid">
                                    <label class=" form-label">Size</label>
                                    <input class="form-control" name="size" id="size" placeholder="Size" value=""
                                        disabled />
                                </div>
                            </div> -->
                            </div>
                            <!--end::Input group-->

                        </div>
                        <!--end::Billing address-->
                    </div>
                    <!--end::Card body-->
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="kt_tab_pane_2" role="tabpanel">
            <div class="">
                <!--begin::Order details-->
                <div class="card card-flush py-4">
                    <!--begin::Card body-->
                    <div class="card-body pt-0">
                        <!--begin::Billing address-->
                        <div class="row col-md-12">
                            <div class=" col-md-6">
                                <!--begin::Input group-->
                                <div class="flex-row-fluid mb-5">
                                    <!--begin::Label-->
                                    <label class=" form-label">Category</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <select class="form-select select_category" id="category" name="category">
                                        <option
                                            value="<?= isset($all_data->users_ms_categories_id) ? $all_data->users_ms_categories_id : '' ?>"
                                            selected>
                                            <?= isset($all_data->category_name) ? $all_data->category_name : 'Select Option' ?>
                                        </option>
                                        <?php 
								foreach($category as $row){
									echo '<option value="'.$row->id.'">'.$row->categories_name.' </option>';
								} ?>
                                    </select>
                                </div>

                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="flex-row-fluid mb-5">
                                    <!--begin::Label-->
                                    <label class=" form-label">Sub Category</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <select class="form-select select_category" id="category_1" name="category_1">
                                        <option
                                            value="<?= $all_data->users_ms_categories_id_1 ? $all_data->users_ms_categories_id_1 : '' ?>	"
                                            selected>
                                            <?= $all_data->category_name_1 ? $all_data->category_name_1 : 'Select Option' ?>
                                        </option>
                                    </select>
                                </div>

                                <!--end::Input group-->
                                <!--begin::Input group-->

                                <!--begin::Input group-->
                                <div class="flex-row-fluid mb-5">
                                    <!--begin::Label-->
                                    <label class=" form-label">Sub Sub Category</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->

                                    <select class="form-select select_category" id="category_2" name="category_2">
                                        <option
                                            value="<?= $all_data->users_ms_categories_id_2 ? $all_data->users_ms_categories_id_2 : '' ?>	"
                                            selected>
                                            <?= $all_data->category_name_2 ? $all_data->category_name_2 : 'Select Option' ?>
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class=" col-md-6">
                                <!--begin::Input group-->
                                <div class="flex-row-fluid mb-5">
                                    <!--begin::Label-->
                                    <label class=" form-label">Management Type 1</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <select class="form-select " id="management_type_1" name="management_type_1">
                                        <option
                                            value="<?= $all_data->users_ms_management_type_id_1 ? $all_data->users_ms_management_type_id_1 : '' ?>	"
                                            selected>
                                            <?= $all_data->management_type_name_1 ? $all_data->management_type_name_1 : 'Select Option' ?>
                                        </option>
                                        <?php 
								foreach($management_type as $row){
									echo '<option value="'.$row->id.'">'.$row->management_type_name.' </option>';
								} ?>
                                    </select>
                                </div>

                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="flex-row-fluid mb-5">
                                    <!--begin::Label-->
                                    <label class=" form-label">Management Type 2</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <select class="form-select " id="management_type_2" name="management_type_2">
                                        <option
                                            value="<?= $all_data->users_ms_management_type_id_2 ? $all_data->users_ms_management_type_id_2 : '' ?>	"
                                            selected>
                                            <?= $all_data->management_type_name_2 ? $all_data->management_type_name_2 : 'Select Option' ?>
                                        </option>
                                    </select>
                                </div>
                                <!--begin::Input group-->

                                <!--begin::Input group-->
                                <div class="flex-row-fluid mb-5">
                                    <!--begin::Label-->
                                    <label class=" form-label">Management Type 3</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <select class="form-select " id="management_type_3" name="management_type_3">
                                        <option
                                            value="<?= $all_data->users_ms_management_type_id_3 ? $all_data->users_ms_management_type_id_3 : '' ?>	"
                                            selected>
                                            <?= $all_data->management_type_name_3 ? $all_data->management_type_name_3 : 'Select Option' ?>
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!--end::Input group-->

                        <div class="row col-md-6">
                            <!--begin::Input group-->
                            <div class="flex-row-fluid mb-5">
                                <!--begin::Label-->
                                <label class=" form-label">Matrix</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <select class="form-select " id="matrix" name="matrix">
                                    <option
                                        value="<?= isset($all_data->users_ms_matrix_id) ? $all_data->users_ms_matrix_id : '' ?>	"
                                        selected>
                                        <?= isset($all_data->matrix) ? $all_data->matrix : 'Select Option' ?>
                                    </option>
                                    <?php 
								foreach($matrix as $row){
									echo '<option value="'.$row->id.'">'.$row->matrix.' </option>';
								} ?>
                                </select>
                            </div>

                            <div class="flex-row-fluid mb-5">
                                <!--begin::Label-->
                                <label class=" form-label">Matrix Type</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <h4>Seasonal</h4>
                            </div>
                        </div>
                    </div>
                    <!--end::Billing address-->
                </div>
                <!--end::Card body-->
            </div>
        </div>
        <div class="tab-pane fade" id="kt_tab_pane_3" role="tabpanel">
            <div class="">
                <!--begin::Order details-->
                <div class="card card-flush py-4">
                    <!--begin::Card body-->
                    <div class="card-body pt-0">
                        <!--begin::Billing address-->
                        <div class="row">

                            <!--begin::Input group-->
                            <div class="row col-md-6">

                                <div class="col-md-12  mb-5">
                                    <div class="flex-row-fluid">
                                        <!--begin::Label-->
                                        <label class=" form-label">Description</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <textarea rows="6" class="form-control"
                                            name="product_description"><?= isset($all_data->product_description) ? $all_data->product_description : '' ?></textarea>
                                        <!--end::Input-->
                                    </div>
                                </div>
                            </div>
                            <!--end::Input group-->

                            <!--begin::Input group-->
                            <div class="row col-md-6">

                                <div class="col-md-12  mb-5">
                                    <div class="flex-row-fluid">
                                        <!--begin::Label-->
                                        <label class=" form-label">Short Description</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <textarea rows="6" class="form-control"
                                            name="product_short_description"><?= isset($all_data->product_short_description) ? $all_data->product_short_description : '' ?></textarea>
                                        <!--end::Input-->
                                    </div>
                                </div>
                            </div>
                            <!--end::Input group-->

                            <!--begin::Input group-->
                            <div class="row col-md-6">

                                <div class="col-md-12  mb-5">
                                    <div class="flex-row-fluid">
                                        <!--begin::Label-->
                                        <label class=" form-label">Info</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <textarea rows="6" class="form-control"
                                            name="product_info"><?= isset($all_data->product_info) ? $all_data->product_info : '' ?></textarea>
                                        <!--end::Input-->
                                    </div>
                                </div>
                            </div>
                            <!--end::Input group-->

                            <!--begin::Input group-->
                            <div class="row col-md-6">

                                <div class="col-md-12  mb-5">
                                    <div class="flex-row-fluid">
                                        <!--begin::Label-->
                                        <label class=" form-label">Size Guideline</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <textarea rows="6" name="size_guide_line"
                                            class="form-control"><?= isset($all_data->size_guide_line) ? $all_data->size_guide_line : '' ?></textarea>
                                        <!--end::Input-->
                                    </div>
                                </div>
                            </div>
                            <!--end::Input group-->

                        </div>
                        <!--end::Billing address-->
                    </div>
                    <!--end::Card body-->
                </div>
            </div>
        </div>
    </div>
</form>


<script>
$('select').select2({
    placeholder: "--Choose Options--",
    dropdownParent: $('#modalLarge2'),
    allowClear: true,
});
</script>