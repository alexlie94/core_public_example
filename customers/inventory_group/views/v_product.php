<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<div class="row col-md-12">

    <div class="col-md-4">
        <div class="card card-flush py-4 border-primary">
            <div class="card-header ">
                <p class=" fw-bold fs-4 mt-3">Detail</p>
            </div>
            <div class="card-body pt-0">

                <div class="row ">
                    <div class="row col-md-12 ">

                        <div class="col-md-12  mb-5">
                            <div class="flex-row-fluid">
                                <label class=" form-label">Product</label>
                                <input class="form-control" disabled name="product" id="product" placeholder="Product Name" value="<?= isset($all_data->product_name) ? $all_data->product_name : '' ?>" />
                            </div>
                        </div>
                        <div class="col-md-12  mb-5">

                            <div class="flex-row-fluid">
                                <label class=" form-label">Supplier</label>
                                <input class="form-control" disabled name="product" id="product" placeholder="Product Name" value="<?= isset($all_data->supplier_name) ? $all_data->supplier_name : '' ?>" />
                            </div>
                        </div>
                        <div class="col-md-12 mb-5">
                            <div class="flex-row-fluid">
                                <label class=" form-label">Brand</label>
                                <input class="form-control" disabled name="product" id="product" placeholder="Product Name" value="<?= isset($all_data->brand_name) ? $all_data->brand_name : '' ?>" />
                            </div>
                        </div>
                        <div class="col-md-12 mb-5">
                            <div class="flex-row-fluid">
                                <label class=" form-label">Gender</label>
                                <input class="form-control" disabled name="product" id="product" placeholder="Product Name" value="<?= isset($all_data->gender) ? $all_data->gender : '' ?>" />
                            </div>
                        </div>
                        <div class="col-md-12  mb-5">
                            <div class="flex-row-fluid">
                                <label class=" form-label">Ownership Type</label>
                                <h4 id="ownership_type">Private Label Buying</h4>
                            </div>
                        </div>


                        <div class="col-md-12  mb-5">
                            <div class="fv-row flex-row-fluid">
                                <label class=" form-label">Price</label>
                                <input class="form-control" disabled name="product_price" id="product_price" placeholder="Price" value="<?= isset($all_data->product_price) ? format_number_to_idr($all_data->product_price) : '' ?>" />
                            </div>
                        </div>
                        <div class="col-md-12 mb-5">
                            <div class="fv-row flex-row-fluid">
                                <label class=" form-label">Sale Price</label>
                                <input class="form-control" disabled name="product_sale_price" id="product_sale_price" placeholder="Sale Price" value="<?= isset($all_data->product_sale_price) ? format_number_to_idr($all_data->product_sale_price) : '' ?>" />
                            </div>
                        </div>
                        <div class="col-md-12 mb-5">
                            <div class="fv-row flex-row-fluid">
                                <label class=" form-label">Offline Price</label>
                                <input class="form-control" disabled name="product_sale_price" id="product_sale_price" placeholder="Sale Price" value="<?= isset($all_data->product_offline_price) ? format_number_to_idr($all_data->product_offline_price) : '' ?>" />
                            </div>
                        </div>
                        <div class="col-md-12  mb-5">
                            <div class="flex-row-fluid">
                                <label class=" form-label">Status</label>
                                <h4>Enabled</h4>
                            </div>
                        </div>
                        <div class="col-md-12  mb-5">
                            <div class="fv-row flex-row-fluid">
                                <label class=" form-label">Size</label>
                                <input class="form-control" name="size" id="size" value="<?= isset($all_data->product_size) ? $all_data->product_size : '' ?>" disabled />
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <div class="col-md-4">
        <div class="">
            <div class="card card-flush py-4 border-primary">
                <div class="card-header ">
                    <p class=" fw-bold fs-4 mt-3">Type</p>
                </div>
                <div class="card-body pt-0">
                    <div class="row col-md-12">
                        <div class=" col-md-12">
                            <div class="flex-row-fluid mb-5">
                                <label class=" form-label">Category</label>
                                <input class="form-control" disabled name="product_sale_price" id="product_sale_price" placeholder="Sale Price" value="<?= isset($all_data->category_name) ? $all_data->category_name : '' ?>" />
                            </div>

                            <div class="flex-row-fluid mb-5">
                                <label class=" form-label">Sub Category</label>
                                <input class="form-control" disabled name="product_sale_price" id="product_sale_price" placeholder="Sale Price" value="<?= isset($all_data->category_name_1) ? $all_data->category_name_1 : '' ?>" />
                            </div>

                            <div class="flex-row-fluid mb-5">
                                <label class=" form-label">Sub Sub Category</label>
                                <input class="form-control" disabled name="product_sale_price" id="product_sale_price" placeholder="Sale Price" value="<?= isset($all_data->category_name_2) ? $all_data->category_name_2 : '' ?>" />
                            </div>

                            <div class="flex-row-fluid mb-5">
                                <label class=" form-label">Management Type 1</label>
                                <input class="form-control" disabled name="product_sale_price" id="product_sale_price" placeholder="Sale Price" value="<?= isset($all_data->category_name) ? $all_data->category_name : '' ?>" />
                            </div>

                            <div class="flex-row-fluid mb-5">
                                <label class=" form-label">Management Type 2</label>
                                <input class="form-control" disabled name="product_sale_price" id="product_sale_price" placeholder="Sale Price" value="<?= isset($all_data->category_name_1) ? $all_data->category_name_1 : '' ?>" />
                            </div>

                            <div class="flex-row-fluid mb-5">
                                <label class=" form-label">Management Type 3</label>
                                <input class="form-control" disabled name="product_sale_price" id="product_sale_price" placeholder="Sale Price" value="<?= isset($all_data->category_name_2) ? $all_data->category_name_2 : '' ?>" />
                            </div>
                            <div class="flex-row-fluid mb-5">
                                <label class=" form-label">Matrix</label>
                                <input class="form-control" disabled name="product_sale_price" id="product_sale_price" placeholder="Sale Price" value="<?= isset($all_data->category_name_2) ? $all_data->category_name_2 : '' ?>" />
                            </div>

                            <div class="flex-row-fluid mb-5">
                                <label class=" form-label">Matrix Type</label>
                                <h4>Seasonal</h4>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card card-flush py-4 border-primary">
            <div class="card-header ">
                <p class=" fw-bold fs-4 mt-3">Description</p>
            </div>
            <div class="card-body pt-0">
                <div class="row">

                    <div class="row col-md-12">

                        <div class="col-md-12  mb-5">
                            <div class="flex-row-fluid">
                                <label class=" form-label">Description</label>
                                <textarea rows="3" class="form-control" disabled name="product_description"><?= isset($all_data->product_description) ? $all_data->product_description : '' ?></textarea>
                            </div>
                        </div>


                        <div class="col-md-12  mb-5">
                            <div class="flex-row-fluid">
                                <label class=" form-label">Short Description</label>
                                <textarea rows="3" class="form-control" disabled name="product_short_description"><?= isset($all_data->product_short_description) ? $all_data->product_short_description : '' ?></textarea>
                            </div>
                        </div>


                        <div class="col-md-12  mb-5">
                            <div class="flex-row-fluid">
                                <label class=" form-label">Info</label>
                                <textarea rows="3" class="form-control" disabled name="product_info"><?= isset($all_data->product_info) ? $all_data->product_info : '' ?></textarea>
                            </div>
                        </div>

                        <div class="col-md-12  mb-5">
                            <div class="flex-row-fluid">
                                <label class=" form-label">Size Guideline</label>
                                <textarea rows="3" name="size_guide_line" class="form-control" disabled><?= isset($all_data->size_guide_line) ? $all_data->size_guide_line : '' ?></textarea>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>


</div>