<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>


<input type="hidden" id="id" name="id" value="<?= $getItems->id  ?>" data-type="input" />
<input type="hidden" id="set_supplier_id" name="set_supplier_id" value="<?= isset($supp->id) ? $supp->id : '' ?>">
<input type="hidden" id="set_brand_id" name="set_brand_id" value="<?= isset($brands->id) ? $brands->id : '' ?>">
<input type="hidden" id="set_warehouse_id" name="set_warehouse_id" value="<?= isset($whs->id) ? $whs->id : '' ?>">

<!-- Stepper Form -->
<div class="mb-5 modal-upload">

    <!-- FORM CREATE PO -->
    <div class="flex-column" data-kt-stepper-element="content">

        <div class="fv-row mb-8" style="max-width: 100%; white-space: nowrap; overflow-x: auto;">

            <div class="row g-9" data-kt-buttons="true" data-kt-buttons-target="[data-kt-button='true']">

                <div class="d-flex flex-nowrap gap-5 px-9 mb-5 overflow-auto">
                    <label class="btn btn-outline btn-outline-dashed btn-active-light-primary d-flex text-start p-6" data-kt-button="true" style="width: 30%;">
                        <span class="ms-4">
                            <table class="table" style="line-height: 0;">
                                <tbody>
                                    <tr>
                                        <td>PO Number</td>
                                        <td>:</td>
                                        <td>
                                            <div style="position: relative;top: -9px;" class="badge badge-light-success text-bold"><?= $getItems->po_number ?></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Brand Name</td>
                                        <td>:</td>
                                        <td><?= $supp->supplier_name ?></td>
                                    </tr>
                                    <tr>
                                        <td>Supplier Name</td>
                                        <td>:</td>
                                        <td><?= $brands->brand_name ?></td>
                                    </tr>
                                    <tr>
                                        <td>Publisher</td>
                                        <td>:</td>
                                        <td><?= $publisher->fullname ?></td>
                                    </tr>
                                    <tr>
                                        <td>Date Created</td>
                                        <td>:</td>
                                        <td><?= date('Y M d', strtotime($getItems->created_at)) ?></td>
                                    </tr>
                                    <tr>
                                        <td>Ownership Type</td>
                                        <td>:</td>
                                        <td>Private Label</td>
                                    </tr>
                                </tbody>
                            </table>
                        </span>
                    </label>

                    <label class="btn btn-outline btn-outline-dashed btn-active-light-primary d-flex text-start p-6" data-kt-button="true" style="width:100%;display:flex;flex-direction: column;">
                        <span class="ms-4">
                            <span class="fs-4 fw-bold text-gray-800 mb-2 d-block">Notes</span>
                            <span class="fw-semibold fs-7 text-gray-600">
                                <textarea class="form-control" style="flex: 1;resize: none;width: 100%;height: 100%;border: 1px solid #000000;border-radius: 5px;padding: 5px;" name="description_parent" data-type="input" data-kt-autosize="true"><?= $getItems->description ?></textarea></span>
                        </span>
                    </label>

                    <label class="btn btn-outline btn-outline-dashed btn-active-light-primary d-flex text-start p-6" data-kt-button="true" style="background-color: #B0E0E6;">
                        <span class="ms-4">
                            <span class="fs-4 fw-bold text-gray-800 mb-2 d-block">Destination Warehouse</span>
                            <span class="fw-semibold fs-7 text-gray-600">
                                <div class="fs-2hx fw-bold"><?= $whs->warehouse_name ?></div>
                            </span>
                        </span>
                    </label>

                </div>

            </div>
        </div>

        <div class="row">
            <div class="col-xl-12">

                <div class="row">
                    <div class="col-md-4">
                    </div>

                    <div class="col-md-8" align="right">
                        <div class="form-group">
                            <button class="btn btn-outline btn-outline-dashed btn-outline-success btn-active-light-danger btn-sm fw-bold" type="button" id="btnAddSKUEdit" align="right" data-type="modal" data-url="<?= base_url('/inventory_requisition/addSKUEdit') ?>" data-fullscreenmodal="0">
                                Add SKU
                            </button>
                        </div>
                    </div>

                </div>

                <div class="table-responsive">
                    <table id="kt_datatable_vertical_scroll" class="table table-striped table-row-bordered gy-5 gs-7">
                        <thead>
                            <tr class="fw-semibold fs-6 text-gray-800">
                                <th>#</th>
                                <th class="min-w-50px">No</th>
                                <th class="min-w-300px">Product Name</th>
                                <th class="min-w-200px">SKU</th>
                                <th class="min-w-200px">Brand</th>
                                <th class="min-w-200px">Category</th>
                                <th class="min-w-150px">Size</th>
                                <th class="min-w-150px">Color</th>
                                <th class="min-w-150px">Qty</th>
                                <th class="min-w-150px">Type</th>
                                <th class="min-w-150px">Price</th>
                                <th class="min-w-150px">Material Cost</th>
                                <th class="min-w-150px">Service Cost</th>
                                <th class="min-w-150px">Overhead Cost</th>
                                <th class="min-w-200px">Description</th>
                                <th class="min-w-70px">Image</th>
                                <th class="min-w-250px">File Image</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    setTimeout(() => {
        var target = $(".modal-upload").parent().parent().parent('.modal-content')[0];
        var blockUI = new KTBlockUI(target);
    }, 300);
</script>