<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<div class="card card-bordered mb-5 groupCard border border-dark" style="padding: 20px;">
    <form id="formSearch" class="form formSearch" autocomplete="off"
        data-url="<?= base_url() . 'inventory_warehouse/exports_storage' ?>">
        <div class="row mb-4 mt-5">

            <!--begin::Col-->
            <div class="col-md-6 fv-row">
                <label class="fs-6 fw-semibold mb-2">Destination Warehouse</label>
                <select name="warehouse_id" id="warehouse_id" class="form-select form-select-solid"
                    data-control="select2" data-hide-search="true" data-type='select'
                    data-placeholder="Select Warehouse">
                    <option value="0" selected>Combined</option>
                    <?php foreach ($warehouse as $res) { ?>
                    <option value="<?= $res['id'] ?>"><?= $res['warehouse_name'] ?></option>
                    <?php } ?>
                </select>
            </div>
            <!--end::Col-->
        </div>

        <!--begin::Input group-->
        <div class="row mb-4">
            <!--begin::Col-->
            <div class="col-md-6 fv-row">
                <label class="fs-6 fw-bold mb-2">Search By</label>
                <select class="form-select form-select-solid" data-control="select2" data-hide-search="true"
                    data-placeholder="Select Search By" name="search_by" id="search_by" data-type="select">
                    <option value="" disabled selected hidden>Please Select</option>
                    <option value="sku">SKU</option>
                    <option value="product_name">Product Name</option>
                    <option value="brand_name">Brand Name</option>
                </select>
            </div>
            <!--end::Col-->
            <!--begin::Col-->
            <div class="col-md-6 fv-row">
                <label class="fs-6 fw-semibold mb-2 text-white">*</label>
                <input type="text" class="form-control form-control-solid" placeholder="Enter Search Target"
                    name="search_by1" id="search_by1" data-type='input' />
            </div>
            <!--end::Col-->
        </div>
        <!--end::Input group-->
    </form>

    <div class="row mb-5">
        <div class="d-flex flex-end gap-2 gap-lg-3">
            <button type="button" id="btnSearchReset"
                class="btn btn-outline btn-outline-dashed btn-outline-danger btn-active-light-danger btn-sm fw-bold">Reset</button>
            <button type="button" id="btnSearch" onclick="reloadDatatables()"
                class="btn btn-outline btn-outline-dashed btn-outline-primary btn-active-light-primary btn-sm fw-bold">Search</button>
        </div>
    </div>
</div>

<!--begin::Datatable-->
<input type="hidden" name="paging_datatables" value="0" class="halaman" style="display: none"><input type="hidden"
    name="draw" value="1" class="draw_datatables" style="display: none">
<div class="table-responsive">
    <table id="show_storage" class="table table-hover table-rounded">
        <thead>
            <tr>
                <th class="min-w-100px">SKU</th>
                <th class="min-w-200px">Product</th>
                <th class="min-w-150px">Brand</th>
                <th class="min-w-200px">Category</th>
                <th class="min-w-150px">Size</th>
                <th class="min-w-100px">Quantity</th>
            </tr>
        </thead>
        <tbody>

        </tbody>
    </table>
</div>
<div class="paginationDatatables"></div>
<!--end::Datatable-->

<div class="mt-4">
    <!--begin::Statistics-->
    <div class="d-flex align-items-center mb-2">
        <!--begin::Value-->
        <span class="fs-1 fw-bold text-gray-800 me-2 lh-1 ls-n2">Last Update</span>
        <!--end::Value-->

        <!--begin::Label-->

        <!--end::Label-->
    </div>
    <!--end::Statistics-->

    <!--begin::Description-->
    <span
        class="fs-6 fw-semibold text-gray-400"><?= isset($lastUpdated['updated_at']) ? $lastUpdated['updated_at'] : 'dd-mmm-yyyy hh-mm-ss' ?></span>
    <!--end::Description-->
</div>