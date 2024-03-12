<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<div class="row">
    <div class="col-md-8">
    </div>

    <div class="col-md-4">
        <div class="d-flex align-items-center mb-5">
            <div class="position-relative w-md-400px me-md-2">

                <span class="svg-icon svg-icon-3 svg-icon-gray-500 position-absolute top-50 translate-middle ms-6">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1"
                            transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
                        <path
                            d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                            fill="currentColor" />
                    </svg>
                </span>

                <input type="text" class="form-control form-control ps-10" id="sku"
                    placeholder="Find Product Name Or SKU" style="border: 2px solid #ccc;  border-radius: 10px;" />
            </div>

            <div class="d-flex align-items-center" style="padding-left: 10px;">
                <button type="button" class="btn btn-primary me-5" id="search_sku">Search</button>
            </div>
        </div>
    </div>
</div>

<table id="kt_datatable_product_list" class="table table-striped table-row-bordered gy-5 gs-7">
    <thead>
        <tr class="fw-semibold fs-6 text-gray-800">
            <th>#</th>
            <th class="min-w-100px">SKU</th>
            <th class="min-w-200px">PRODUCT NAME</th>
            <th class="min-w-50px">BRAND</th>
            <th class="min-w-50px">LOCATION</th>
            <th>QTY</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>