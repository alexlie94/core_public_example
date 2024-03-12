<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<div class="stepper stepper-pills modal-upload" id="kt_stepper_example_basic">

    <input type="hidden" id="url_supplier_id" name="url_supplier_id">
    <input type="hidden" id="url_brand_id" name="url_brand_id">
    <input type="hidden" id="url_warehouse_id" name="url_warehouse_id">
    <input type="hidden" id="supplier_email">

    <div class="row">
        <div class="col-2">
            <div class="stepper-item mx-8 my-4 current" data-kt-stepper-element="nav">
                <div class="stepper-wrapper d-flex align-items-center">
                    <div class="me-2">
                        <button id="previous" type="button" class="btn btn-danger text-right btn-sm" data-kt-stepper-action="previous">
                            <span class="svg-icon svg-icon-2x">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <polygon points="0 0 24 0 24 24 0 24" />
                                        <rect fill="currentColor" opacity="0.3" transform="translate(12.000000, 12.000000) scale(-1, 1) rotate(-90.000000) translate(-12.000000, -12.000000) " x="11" y="5" width="2" height="14" rx="1" />
                                        <path d="M3.7071045,15.7071045 C3.3165802,16.0976288 2.68341522,16.0976288 2.29289093,15.7071045 C1.90236664,15.3165802 1.90236664,14.6834152 2.29289093,14.2928909 L8.29289093,8.29289093 C8.67146987,7.914312 9.28105631,7.90106637 9.67572234,8.26284357 L15.6757223,13.7628436 C16.0828413,14.136036 16.1103443,14.7686034 15.7371519,15.1757223 C15.3639594,15.5828413 14.7313921,15.6103443 14.3242731,15.2371519 L9.03007346,10.3841355 L3.7071045,15.7071045 Z" fill="currentColor" fill-rule="nonzero" transform="translate(9.000001, 11.999997) scale(-1, -1) rotate(90.000000) translate(-9.000001, -11.999997) " />
                                    </g>
                                </svg>
                            </span>
                            Back
                        </button>
                    </div>
                </div>
                <div class="stepper-line h-40px"></div>
            </div>
        </div>

        <div class="col-10">
            <div class="stepper-nav flex-left flex-wrap mb-10">
                <div class="stepper-item mx-8 my-4 current" data-kt-stepper-element="nav">
                    <div class="stepper-wrapper d-flex align-items-center">
                        <div class="stepper-icon w-40px h-40px">
                            <i class="stepper-check fas fa-check"></i>
                            <span class="stepper-number">1</span>
                        </div>

                        <div class="stepper-label">
                            <h3 class="stepper-title">
                                Select Suppliers
                            </h3>
                        </div>

                    </div>
                    <div class="stepper-line h-40px"></div>
                </div>

                <div class="stepper-item mx-8 my-4" data-kt-stepper-element="nav">
                    <div class="stepper-wrapper d-flex align-items-center">
                        <div class="stepper-icon w-40px h-40px">
                            <i class="stepper-check fas fa-check"></i>
                            <span class="stepper-number">2</span>
                        </div>

                        <div class="stepper-label">
                            <h3 class="stepper-title">
                                Select Brand
                            </h3>
                        </div>
                    </div>
                    <div class="stepper-line h-40px"></div>
                </div>

                <div class="stepper-item mx-8 my-4" data-kt-stepper-element="nav">
                    <div class="stepper-wrapper d-flex align-items-center">
                        <div class="stepper-icon w-40px h-40px">
                            <i class="stepper-check fas fa-check"></i>
                            <span class="stepper-number">3</span>
                        </div>

                        <div class="stepper-label">
                            <h3 class="stepper-title">
                                Select Warehouse
                            </h3>
                        </div>
                    </div>
                    <div class="stepper-line h-40px"></div>
                </div>

                <div class="stepper-item mx-8 my-4" data-kt-stepper-element="nav">
                    <div class="stepper-wrapper d-flex align-items-center">
                        <div class="stepper-icon w-40px h-40px">
                            <i class="stepper-check fas fa-check"></i>
                            <span class="stepper-number">4</span>
                        </div>

                        <div class="stepper-label">
                            <h3 class="stepper-title">
                                Create PO
                            </h3>
                        </div>
                    </div>
                    <div class="stepper-line h-40px"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stepper Form -->
    <div class="mb-5">

        <div class="flex-column current" data-kt-stepper-element="content">

            <div class="row">
                <div class="col-md-6">
                    <div class="d-flex align-items-center mb-5">
                        <div class="position-relative w-md-400px me-md-2">

                            <span class="svg-icon svg-icon-3 svg-icon-gray-500 position-absolute top-50 translate-middle ms-6">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
                                    <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="currentColor" />
                                </svg>
                            </span>

                            <input type="text" class="form-control form-control-solid ps-10" id="suppliers" placeholder="Search Suppliers" style="border: 2px solid #ccc;  border-radius: 10px;" />
                        </div>

                        <div class="d-flex align-items-center" style="padding-left: 10px;">
                            <button type="button" class="btn btn-primary me-5" id="search_suppliers">Search</button>
                        </div>
                    </div>
                </div>

            </div>

            <div class="fv-row mb-10">

                <table id="kt_datatable_suppliers" class="table table-striped table-row-bordered gy-5 gs-7">
                    <thead>
                        <tr class="fw-semibold fs-6 text-gray-800">
                            <th class="min-w-200px">Suppliers Name</th>
                            <th class="min-w-200px">Email</th>
                            <th class="min-w-200px">Address</th>
                            <th class="min-w-200px">Phone</th>
                            <th class="min-w-200px"></th>
                        </tr>
                    </thead>

                    <tbody>
                    </tbody>

                </table>
            </div>

        </div>


        <div class="flex-column" data-kt-stepper-element="content">

            <div class="col-md-4 col-xxl-6 mb-4">

                <div style="height: 80px; width: 450px;" class="notice d-flex bg-light-warning rounded border-warning border border-dashed mb-9 p-6">
                    <div class="d-flex flex-stack flex-grow-1">
                        <div class="fw-semibold">
                            <div class="table-responsive">
                                <table class="table" style="margin-top: 12px">
                                    <tbody>
                                        <tr>
                                            <td>Supplier Name</td>
                                            <td>:</td>
                                            <td id="supp_name"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="d-flex align-items-center mb-5">
                        <div class="position-relative w-md-400px me-md-2">

                            <span class="svg-icon svg-icon-3 svg-icon-gray-500 position-absolute top-50 translate-middle ms-6">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
                                    <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="currentColor" />
                                </svg>
                            </span>

                            <input type="text" class="form-control form-control-solid ps-10" id="brand" placeholder="Search Brand" style="border: 2px solid #ccc;  border-radius: 10px;" />
                        </div>

                        <div class="d-flex align-items-center" style="padding-left: 10px;">
                            <button type="button" class="btn btn-primary me-5" id="search_brand">Search</button>
                        </div>
                    </div>
                </div>

            </div>


            <div class="fv-row mb-10">
                <table id="kt_datatable_brand" class="table table-striped table-row-bordered gy-5 gs-7">
                    <thead>
                        <tr class="fw-semibold fs-6 text-gray-800">
                            <th class="min-w-200px">Brand Code</th>
                            <th class="min-w-200px">Brand Name</th>
                            <th class="min-w-200px">Description</th>
                            <th class="min-w-200px"></th>
                        </tr>
                    </thead>

                    <tbody>
                    </tbody>

                </table>
            </div>

        </div>


        <div class="flex-column" data-kt-stepper-element="content">

            <div class="col-md-4 col-xxl-6 mb-4">
                <div style="height: 80px; width: 450px;" class="notice d-flex bg-light-warning rounded border-warning border border-dashed mb-9 p-6">
                    <div class="d-flex flex-stack flex-grow-1">
                        <div class="fw-semibold">
                            <div class="table-responsive">
                                <table class="table" style="margin-top: 12px">
                                    <tbody>
                                        <tr>
                                            <td>Supplier Name</td>
                                            <td>:</td>
                                            <td id="supp_name1"></td>
                                        </tr>

                                        <tr>
                                            <td>Brand Name</td>
                                            <td>:</td>
                                            <td id="brand_name"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="d-flex align-items-center mb-5">
                        <div class="position-relative w-md-400px me-md-2">

                            <span class="svg-icon svg-icon-3 svg-icon-gray-500 position-absolute top-50 translate-middle ms-6">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
                                    <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="currentColor" />
                                </svg>
                            </span>

                            <input type="text" class="form-control form-control-solid ps-10" id="warehouse" placeholder="Search Warehouse" style="border: 2px solid #ccc;  border-radius: 10px;" />
                        </div>

                        <div class="d-flex align-items-center" style="padding-left: 10px;">
                            <button type="button" class="btn btn-primary me-5" id="search_warehouse">Search</button>
                        </div>
                    </div>
                </div>

            </div>

            <div class="fv-row mb-10">
                <table id="kt_datatable_warehouse" class="table table-striped table-row-bordered gy-5 gs-7">
                    <thead>
                        <tr class="fw-semibold fs-6 text-gray-800">
                            <th class="min-w-200px">Warehouse Name</th>
                            <th class="min-w-200px">Email</th>
                            <th class="min-w-200px">Address</th>
                            <th class="min-w-200px">Phone</th>
                            <th class="min-w-200px"></th>
                        </tr>
                    </thead>

                    <tbody>
                    </tbody>

                </table>
            </div>


        </div>

        <!-- FORM CREATE PO -->
        <div class="flex-column" data-kt-stepper-element="content">

            <div class="fv-row mb-8" style="max-width: 100%; white-space: nowrap; overflow-x: auto;">
                <div class="row g-9" data-kt-buttons="true" data-kt-buttons-target="[data-kt-button='true']">
                    <div class="d-flex flex-nowrap gap-5 px-9 mb-5 overflow-auto">
                        <label class="btn btn-outline btn-outline-dashed btn-active-light-primary d-flex text-start p-6" data-kt-button="true">
                            <span class="ms-6">
                                <table class="table" style="line-height: 0;">
                                    <tbody>
                                        <tr>
                                            <td>PO Number</td>
                                            <td>:</td>
                                            <td>
                                                <div style="position: relative;top: -9px;" class="badge badge-light-danger text-bold">Waiting</div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Brand Name</td>
                                            <td>:</td>
                                            <td id="brand_name2"></td>
                                        </tr>
                                        <tr>
                                            <td>Supplier Name</td>
                                            <td>:</td>
                                            <td id="supp_name3"></td>
                                        </tr>
                                        <tr>
                                            <td>Supplier Email</td>
                                            <td>:</td>
                                            <td id="supp_email"></td>
                                        </tr>
                                        <tr>
                                            <td>Publisher</td>
                                            <td>:</td>
                                            <td><?= $publisher->fullname ?></td>
                                        </tr>
                                        <tr>
                                            <td>Date Created</td>
                                            <td>:</td>
                                            <td><?= date('Y M d') ?></td>
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

                        <label class="btn btn-outline btn-outline-dashed btn-active-light-primary d-flex text-start p-6" data-kt-button="true" style="display:flex;flex-direction: column;">
                            <span class="ms-6">
                                <span class="fs-4 fw-bold text-gray-800 mb-2 d-block">Notes</span>
                                <span class="fw-semibold fs-7 text-gray-600">
                                    <textarea style="flex: 1;resize: none;width: 100%;height: 100%;border: 1px solid #000000;border-radius: 5px;padding: 5px;" class="form-control" name="description_parent" data-type="input" data-kt-autosize="true"></textarea></span>
                            </span>
                        </label>

                        <label class="btn btn-outline btn-outline-dashed btn-active-light-primary d-flex text-start p-6" data-kt-button="true" style="width: 400px;">
                            <span class="ms-6">
                                <label class="required fw-semibold fs-6">Mass Upload</label>

                                <div class="form-group mt-4">
                                    <div class="input-group" style="margin-top: 5.8%;">
                                        <input type="file" id="data_upload" class="form-control" data-type="input" autocomplete="off" />
                                        <div class="input-group-append">
                                            <button id="upload_button" class="btn btn-primary" type="button">Upload</button>
                                        </div>
                                    </div>
                                </div>

                                <div id='formatError' class="fv-plugins-message-container invalid-feedback mb-6">Format Data Csv Error,Download CSV Format!.</div>
                            </span>
                        </label>

                        <label class="btn btn-outline btn-outline-dashed btn-active-light-primary d-flex text-start p-6" data-kt-button="true" style="background-color: #B0E0E6;">
                            <span class="ms-6">
                                <span class="fs-4 fw-bold text-gray-800 mb-2 d-block">Destination Warehouse</span>
                                <span class="fw-semibold fs-7 text-gray-600">
                                    <div class="fs-2hx fw-bold" id="warehouse_name"></div>
                                </span>
                            </span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="row mb-6">
                <div class="col-xl-12">

                    <div class="row">
                        <div class="col-md-4">
                            <a type="button" href="javascript:void(0)" id="download_product" class="text-gray-800 text-hover-primary d-flex flex-column mt-3 text-center">
                                <button type="button" data-repeater-create="" class="fs-5 fw-bold mb-2 btn btn-sm btn-light-warning">
                                    <span class="svg-icon svg-icon-3">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path opacity="0.3" d="M19 15C20.7 15 22 13.7 22 12C22 10.3 20.7 9 19 9C18.9 9 18.9 9 18.8 9C18.9 8.7 19 8.3 19 8C19 6.3 17.7 5 16 5C15.4 5 14.8 5.2 14.3 5.5C13.4 4 11.8 3 10 3C7.2 3 5 5.2 5 8C5 8.3 5 8.7 5.1 9H5C3.3 9 2 10.3 2 12C2 13.7 3.3 15 5 15H19Z" fill="currentColor" />
                                            <path d="M13 17.4V12C13 11.4 12.6 11 12 11C11.4 11 11 11.4 11 12V17.4H13Z" fill="currentColor" />
                                            <path opacity="0.3" d="M8 17.4H16L12.7 20.7C12.3 21.1 11.7 21.1 11.3 20.7L8 17.4Z" fill="currentColor" />
                                        </svg>
                                    </span>
                                    Download CSV
                                </button>
                            </a>
                        </div>

                        <div class="col-md-8" align="right">
                            <div class="form-group">
                                <button type="button" class="btn btn-outline btn-outline-dashed btn-outline-success btn-active-light-danger btn-sm fw-bold" id="btnAddSKU" data-type="modal" data-url="<?= base_url('/inventory_requisition/addSKU') ?>" data-fullscreenmodal="0">
                                    <i class="bi bi-plus"></i>
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
                                    <th class="min-w-250px">Image</th>
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

        <!-- Button Action Stepper -->
        <div class="d-flex flex-stack">

            <div>
                <button type="button" id="continueButton" style="display:none;" class="btn btn-primary" data-kt-stepper-action="next">
                    Continue
                </button>
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