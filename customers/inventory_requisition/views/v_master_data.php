<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<form id="formEditProduct">
    <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bold">
        <li class="nav-item mt-2">
            <a class="nav-link text-active-primary ms-0 me-10 py-5 active" data-bs-toggle="tab" href="#tab_supplier">Supplier</a>
        </li>

        <li class="nav-item mt-2">
            <a class="nav-link text-active-primary ms-0 me-10 py-5" data-bs-toggle="tab" id='click_tab_brand' href="#tab_brand">Brand</a>
        </li>

        <li class="nav-item mt-2">
            <a class="nav-link text-active-primary ms-0 me-10 py-5" data-bs-toggle="tab" href="#tab_warehouse">Warehouse</a>
        </li>
    </ul>

    <div class="tab-content" id="myTabContent">

        <div class="tab-pane fade show active" id="tab_supplier" role="tabpanel">
            <div class="card card-flush py-4">
                <div class="card-body pt-0">

                    <div class="flex-column current" data-kt-stepper-element="content">

                        <div class="row">
                            <div class="col-md-10">
                                <div class="d-flex align-items-center mb-5">
                                    <div class="position-relative w-md-400px me-md-2">

                                        <span class="svg-icon svg-icon-3 svg-icon-gray-500 position-absolute top-50 translate-middle ms-6">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
                                                <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="currentColor" />
                                            </svg>
                                        </span>

                                        <input type="text" class="form-control form-control-solid ps-10" id="supplier_master" placeholder="Search Suppliers" style="border: 2px solid #ccc;  border-radius: 10px;" />
                                    </div>

                                    <div class="d-flex align-items-center" style="padding-left: 10px;">
                                        <button type="button" class="btn btn-primary me-5" id="search_supplier_master">Search</button>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <button class="btn btn-outline btn-outline-dashed btn-outline-success btn-active-light-success fw-bold" data-fullscreenmodal="0" type="button" id="btnAddSupplier" data-type="modal" data-url="<?= base_url('suppliers_data/insert') ?>">
                                    <i class="bi bi-plus"></i>
                                    Add New Supplier
                                </button>
                            </div>

                        </div>

                        <div class="fv-row mb-10">

                            <table id="kt_datatable_suppliers" class="table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer" aria-describedby="table-data_info" style="width: 1202px;">
                                <thead>
                                    <tr class="text-start text-gray-600 fw-bold fs-7 text-uppercase gs-0">
                                        <th class="min-w-50px">No</th>
                                        <th class="min-w-200px">Suppliers Code</th>
                                        <th class="min-w-200px">Suppliers Name</th>
                                        <th class="min-w-200px">Email</th>
                                        <th class="min-w-200px">Address</th>
                                        <th class="min-w-200px">Phone</th>
                                        <th class="min-w-200px">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="fw-semibold text-gray-600">
                                </tbody>

                            </table>
                        </div>

                    </div>

                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="tab_brand" role="tabpanel">
            <div class="card card-flush py-4">
                <div class="card-body pt-0">
                    <div class="flex-column current" data-kt-stepper-element="content">

                        <div class="row">
                            <div class="col-md-10">
                                <div class="d-flex align-items-center mb-5">
                                    <div class="position-relative w-md-400px me-md-2">

                                        <span class="svg-icon svg-icon-3 svg-icon-gray-500 position-absolute top-50 translate-middle ms-6">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
                                                <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="currentColor" />
                                            </svg>
                                        </span>

                                        <input type="text" class="form-control form-control-solid ps-10" id="brand_master" placeholder="Search Brand" style="border: 2px solid #ccc;  border-radius: 10px;" />
                                    </div>

                                    <div class="d-flex align-items-center" style="padding-left: 10px;">
                                        <button type="button" class="btn btn-primary me-5" id="search_brand_master">Search</button>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <button class="btn btn-outline btn-outline-dashed btn-outline-success btn-active-light-success fw-bold" data-fullscreenmodal="0" type="button" id="btnAddBrand" data-type="modal" data-url="<?= base_url('brand/insert') ?>">
                                    <i class="bi bi-plus"></i>
                                    Add New Brand
                                </button>
                            </div>

                        </div>

                        <div class="fv-row mb-10">

                            <table id="kt_datatable_brand" class="table table-striped table-row-bordered gy-5 gs-7">
                                <thead>
                                    <tr class="fw-semibold fs-6 text-gray-800">
                                        <th class="min-w-50px">No</th>
                                        <th class="min-w-200px">Brand Code</th>
                                        <th class="min-w-200px">Brand Name</th>
                                        <th class="min-w-200px">Description</th>
                                        <th class="min-w-200px">Action</th>
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

        <div class="tab-pane fade" id="tab_warehouse" role="tabpanel">
            <div class="card card-flush py-4">

                <div class="card-body pt-0">
                    <div class="flex-column current" data-kt-stepper-element="content">

                        <div class="row">
                            <div class="col-md-10">
                                <div class="d-flex align-items-center mb-5">
                                    <div class="position-relative w-md-400px me-md-2">

                                        <span class="svg-icon svg-icon-3 svg-icon-gray-500 position-absolute top-50 translate-middle ms-6">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
                                                <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="currentColor" />
                                            </svg>
                                        </span>

                                        <input type="text" class="form-control form-control-solid ps-10" id="warehouse_master" placeholder="Search Warehouse" style="border: 2px solid #ccc;  border-radius: 10px;" />
                                    </div>

                                    <div class="d-flex align-items-center" style="padding-left: 10px;">
                                        <button type="button" class="btn btn-primary me-5" id="search_warehouse_master">Search</button>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <button class="btn btn-outline btn-outline-dashed btn-outline-success btn-active-light-success fw-bold" data-fullscreenmodal="0" type="button" id="btnAddWarehouse" data-type="modal" data-url="<?= base_url('master_warehouse/insert') ?>">
                                    <i class="bi bi-plus"></i>
                                    Add New Warehouse
                                </button>
                            </div>

                        </div>

                        <div class="fv-row mb-10">

                            <table id="kt_datatable_warehouse" class="table table-striped table-row-bordered gy-5 gs-7">
                                <thead>
                                    <tr class="fw-semibold fs-6 text-gray-800">
                                        <th class="min-w-50px">No</th>
                                        <th class="min-w-200px">Warehouse Code</th>
                                        <th class="min-w-200px">Warehouse Name</th>
                                        <th class="min-w-200px">Email</th>
                                        <th class="min-w-200px">Address</th>
                                        <th class="min-w-200px">Phone</th>
                                        <th class="min-w-200px">Action</th>
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
    </div>
</form>