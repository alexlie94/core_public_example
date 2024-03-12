<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<div class="mb-5">

    <!-- FORM CREATE PO -->
    <div class="flex-column" data-kt-stepper-element="content">

        <div class="fv-row mb-8">
            <div class="row g-9" data-kt-buttons="true" data-kt-buttons-target="[data-kt-button='true']">

                <div class="d-flex flex-wrap d-grid gap-5 px-12 mb-5">
                    <label class="btn btn-outline btn-outline-dashed btn-active-light-primary d-flex text-start p-6" data-kt-button="true" style="width: 30%;">
                        <span class="ms-4">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <td>PO Number</td>
                                        <td>:</td>
                                        <td>
                                            <div class="badge badge-light-success text-bold"><?= $getItems->po_number ?></div>
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

                    <label class="btn btn-outline btn-outline-dashed btn-active-light-primary d-flex text-start p-6" data-kt-button="true" style="width: 30%;">
                        <span class="ms-4">
                            <span class="fs-4 fw-bold text-gray-800 mb-2 d-block">Notes</span>
                            <span class="fw-semibold fs-7 text-gray-600"><textarea class="form-control" style="width: 165%; height: 80%;" name="description_parent" data-type="input" data-kt-autosize="true"></textarea></span>
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

                <table id="kt_datatable_fixed_columns" class="table table-striped table-row-bordered gy-5 gs-7">
                    <thead>
                        <tr class="fw-semibold fs-6 text-gray-800">
                            <th>#</th>
                            <th class="min-w-150px">SKU</th>
                            <th class="min-w-150px">Category</th>
                            <th class="min-w-300px">Product</th>
                            <th class="min-w-150px">Brand</th>
                            <th class="min-w-100px">Size</th>
                            <th class="min-w-100px">Color</th>
                            <th class="min-w-100px">Qty</th>
                            <th class="min-w-100px">Type</th>
                            <th class="min-w-150px">Price</th>
                            <th class="min-w-150px">Material Cost</th>
                            <th class="min-w-150px">Service Cost</th>
                            <th class="min-w-150px">Overhead Cost</th>
                            <th class="min-w-200px">Description</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>