<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<div class="row g-5">

    <div class="col-lg-12">
        <div class="card card-stretch card-bordered mb-5">
            <div class="card-header">
                <h3 class="card-title">Detail</h3>
            </div>
            <div class="card-body" style="height: 750px;">
                <div class="fv-row mb-7">
                    <label class="required fw-semibold fs-6 mb-4">Product Code</label>
                    <input type="text" id="brand_name" name="brand_name" class="form-control form-control-solid mb-3 mb-lg-0" value="<?= $product_data->product_code ?>" data-type="input" autocomplete="off" />
                </div>

                <div class="fv-row mb-7">
                    <label class="required fw-semibold fs-6 mb-4">Product Name</label>
                    <input type="text" id="brand_name" name="brand_name" class="form-control form-control-solid mb-3 mb-lg-0" value="<?= $product_data->product_name ?>" data-type="input" autocomplete="off" />
                </div>

                <div class="fv-row mb-7">
                    <label class="required fw-semibold fs-6 mb-4">Brand Name</label>
                    <input type="text" id="brand_name" name="brand_name" class="form-control form-control-solid mb-3 mb-lg-0" value="<?= $product_data->brand_name ?>" data-type="input" autocomplete="off" />
                </div>

                <div class="fv-row mb-7">
                    <label class="required fw-semibold fs-6 mb-4">Category Name</label>
                    <input type="text" id="brand_name" name="brand_name" class="form-control form-control-solid mb-3 mb-lg-0" value="<?= $product_data->category_name ?>" data-type="input" autocomplete="off" />
                </div>

                <div class="fv-row mb-7">
                    <label class="required fw-semibold fs-6 mb-4">Product Ownership Name</label>
                    <input type="text" id="brand_name" name="brand_name" class="form-control form-control-solid mb-3 mb-lg-0" value="<?= $product_data->product_ownership_name ?>" data-type="input" autocomplete="off" />
                </div>

                <div class="fv-row mb-7">
                    <label class="required fw-semibold fs-6 mb-4">Product Description</label>
                    <input type="text" id="brand_name" name="brand_name" class="form-control form-control-solid mb-3 mb-lg-0" value="<?= $product_data->product_description ?>" data-type="input" autocomplete="off" />
                </div>

                <div class="fv-row mb-7">
                    <label class="required fw-semibold fs-6 mb-4">Product Info</label>
                    <input type="text" id="brand_name" name="brand_name" class="form-control form-control-solid mb-3 mb-lg-0" value="<?= $product_data->product_info ?>" data-type="input" autocomplete="off" />
                </div>
            </div>
        </div>
    </div>

</div>