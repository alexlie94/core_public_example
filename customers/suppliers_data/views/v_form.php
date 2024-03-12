<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<style>
    .modal-open .select2-container--bootstrap5 .select2-dropdown {
        z-index: 45056 !important;
    }
</style>
<div class="card-toolbar mb-6" align="right">
    <button type="button" class="btn btn-sm btn-light-success" id="button_mass_upload">
        <span class="svg-icon svg-icon-primary svg-icon-2x">
            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                height="24px" viewBox="0 0 24 24" version="1.1">
                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                    <polygon points="0 0 24 0 24 24 0 24" />
                    <path
                        d="M5.85714286,2 L13.7364114,2 C14.0910962,2 14.4343066,2.12568431 14.7051108,2.35473959 L19.4686994,6.3839416 C19.8056532,6.66894833 20,7.08787823 20,7.52920201 L20,20.0833333 C20,21.8738751 19.9795521,22 18.1428571,22 L5.85714286,22 C4.02044787,22 4,21.8738751 4,20.0833333 L4,3.91666667 C4,2.12612489 4.02044787,2 5.85714286,2 Z"
                        fill="currentColor" fill-rule="nonzero" opacity="0.3" />
                    <path
                        d="M8.95128003,13.8153448 L10.9077535,13.8153448 L10.9077535,15.8230161 C10.9077535,16.0991584 11.1316112,16.3230161 11.4077535,16.3230161 L12.4310522,16.3230161 C12.7071946,16.3230161 12.9310522,16.0991584 12.9310522,15.8230161 L12.9310522,13.8153448 L14.8875257,13.8153448 C15.1636681,13.8153448 15.3875257,13.5914871 15.3875257,13.3153448 C15.3875257,13.1970331 15.345572,13.0825545 15.2691225,12.9922598 L12.3009997,9.48659872 C12.1225648,9.27584861 11.8070681,9.24965194 11.596318,9.42808682 C11.5752308,9.44594059 11.5556598,9.46551156 11.5378061,9.48659872 L8.56968321,12.9922598 C8.39124833,13.2030099 8.417445,13.5185067 8.62819511,13.6969416 C8.71848979,13.773391 8.8329684,13.8153448 8.95128003,13.8153448 Z"
                        fill="currentColor" />
                </g>
            </svg>
        </span>
        Mass Upload Suppliers
    </button>
</div>

<div class="form-group row mb-8" id="show_mass_upload">
    <div class="col-md-7">
        <label class="fw-semibold fs-6">Mass Upload</label>
        <input type="file" id="upload_data" class="form-control mb-3 mb-lg-0 mt-19" accept=".csv" data-type="input"
            autocomplete="off" />
        <div id='formatError' class="fv-plugins-message-container invalid-feedback mb-6">Format Data Csv Error,Download
            CSV Format!.</div>
    </div>

    <div class="col-md-5 mt-3">
        <a href="<?= base_url('/assets/excel/List_Data_Supplier.csv') ?>" download="List_Data_Supplier.csv"
            target="_blank" class="text-gray-800 text-hover-primary d-flex flex-column mb-3 text-center">
            <div class="symbol symbol-60px mb-5">
                <img src="<?= base_url('/assets/excel/icons8-microsoft-excel-2019-100.png') ?>" class="theme-light-show"
                    alt="" />
                <img src="<?= base_url('/assets/excel/icons8-microsoft-excel-2019-100.png') ?>" class="theme-dark-show"
                    alt="" />
            </div>
            <button type="button" data-repeater-create="" class="fs-5 fw-bold mb-2 btn btn-sm btn-light-success">
                <span class="svg-icon svg-icon-3">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path opacity="0.3"
                            d="M19 15C20.7 15 22 13.7 22 12C22 10.3 20.7 9 19 9C18.9 9 18.9 9 18.8 9C18.9 8.7 19 8.3 19 8C19 6.3 17.7 5 16 5C15.4 5 14.8 5.2 14.3 5.5C13.4 4 11.8 3 10 3C7.2 3 5 5.2 5 8C5 8.3 5 8.7 5.1 9H5C3.3 9 2 10.3 2 12C2 13.7 3.3 15 5 15H19Z"
                            fill="currentColor" />
                        <path d="M13 17.4V12C13 11.4 12.6 11 12 11C11.4 11 11 11.4 11 12V17.4H13Z"
                            fill="currentColor" />
                        <path opacity="0.3" d="M8 17.4H16L12.7 20.7C12.3 21.1 11.7 21.1 11.3 20.7L8 17.4Z"
                            fill="currentColor" />
                    </svg>
                </span>
                Download CSV
            </button>
        </a>
    </div>

    <div class="container mt-4">
        <div class="row flex-nowrap overflow-auto">
            <div class="table-responsive">
                <table id="kt_datatable_vertical_scroll" class="table table-striped border rounded gy-5 gs-7">
                    <thead>
                        <tr class="fw-semibold fs-6 text-gray-800">
                            <th class="min-w-50px">No</th>
                            <th class="min-w-150px">Supplier Name</th>
                            <th class="min-w-150px">Email</th>
                            <th class="min-w-250px">Address</th>
                            <th class="min-w-100px">Phone 1</th>
                            <th class="min-w-100px">Phone 2</th>
                            <th class="min-w-100px">Brand Name</th>
                            <th class="min-w-100px">Type Ownership Name</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<div id="form_supplier">
    <div class="d-flex flex-column scroll-y me-n7 pe-7">
        <div class="row">
            <div class="col-md-6">
                <div class="fv-row mb-7">
                    <input type="hidden" class="form-control" id="id" name="id"
                        value="<?= isset($dataItems['id']) ? $dataItems['id'] : '' ?>" />

                    <label class="fw-semibold fs-6 mb-4">Supplier Code</label>
                    <input type="text" id="supplier_code" name="supplier_code"
                        class="form-control form-control mb-3 mb-lg-0" placeholder="Supplier Code"
                        value="<?= isset($dataItems['supplier_code']) ? $dataItems['supplier_code'] : 'Auto' ?>"
                        data-type="input" autocomplete="off" disabled />

                </div>
            </div>
            <div class="col-md-6">
                <div class="fv-row mb-7">
                    <label class="required fw-semibold fs-6 mb-4">Supplier Name</label>
                    <input type="text" id="supplier_name" name="supplier_name"
                        class="form-control form-control mb-3 mb-lg-0" placeholder="Supplier Name"
                        value="<?= isset($dataItems['supplier_name']) ? $dataItems['supplier_name'] : '' ?>"
                        data-type="input" autocomplete="off" />
                </div>
            </div>
            <div class="col-md-6">
                <div class="fv-row mb-7">
                    <label class="required fw-semibold fs-6 mb-4">Email</label>
                    <input type="email" id="email" name="email" class="form-control form-control mb-3 mb-lg-0"
                        placeholder="Email" value="<?= isset($dataItems['email']) ? $dataItems['email'] : '' ?>"
                        data-type="input" autocomplete="off" />
                </div>
            </div>
            <input type="hidden" id="phone_key" name="phone_key"
                value="<?= isset($dataItems['phone']) ? $dataItems['phone'] : '' ?>" onkeyup="multiplePhone(this)">
            <div class="col-md-6">
                <div class="fv-row mb-7">
                    <label class="required fw-semibold fs-6 mb-4">Phone 1</label>
                    <input type="text" id="phone" name="phone" class="form-control form-control mb-3 mb-lg-0"
                        placeholder="Example : 08123456789" value="" data-type="input" autocomplete="off" />
                </div>
            </div>
            <div class="col-md-6">
                <div class="fv-row mb-7">
                    <label class="fw-semibold fs-6 mb-4">Phone 2</label>
                    <input type="text" id="phone2" name="phone2" class="form-control form-control mb-3 mb-lg-0"
                        placeholder="Example : 08123456789" value="" data-type="input" autocomplete="off" />
                </div>
            </div>
            <div class="col-md-12">
                <div class="fv-row mb-7">
                    <label class="fw-semibold fs-6 mb-4">Address</label>
                    <textarea type="text" id="address" name="address" rows="4"
                        class="form-control form-control mb-3 mb-lg-0" placeholder="Address" data-type="input"
                        autocomplete="off"><?= isset($dataItems['address']) ? $dataItems['address'] : '' ?></textarea>
                </div>
            </div>

        </div>

        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-wrap fw-semibold fs-5 text-gray-400">Supplier Details</div>
                <!--begin::Repeater-->
                <div id="kt_docs_repeater_advanced" class="mb-12">
                    <!--begin::Form group-->
                    <div class="row mt-n6 mb-6">
                        <div class="col-md-12" align="right">
                            <button class="btn btn-sm btn-flex btn-light-primary addProduct" data-repeater-create
                                type="button">
                                <i class="fa-solid fa-plus fs-4 me-2"></i>
                                Add Detail Supplier
                            </button>
                        </div>
                    </div>
                    <!-- <div class="form-group mb-4">
                        <a href="javascript:;" data-repeater-create class="btn btn-flex btn-light-primary">
                            <i class="ki-duotone ki-plus fs-3"></i>
                            Add
                        </a>
                    </div> -->
                    <!--end::Form group-->
                    <!--begin::Form group-->
                    <div class="form-group">
                        <div data-repeater-list="kt_docs_repeater_advanced">
                            <?php if (isset($suppliers_brands)) { ?>
                                <?php $x = 0;
                                foreach ($suppliers_brands as $key) { ?>

                                    <div data-repeater-item>
                                        <input type="hidden" id="idBrands" name="idBrands" class="form-control mb-3 mb-lg-0"
                                            value="<?= isset($suppliers_brands[$x]['id']) ? $suppliers_brands[$x]['id'] : '' ?>"
                                            data-type="input" />
                                        <div class="form-group row mb-5">
                                            <div class="col-md-5">
                                                <label class="form-label required">Brand</label>
                                                <select class="form-select" data-kt-repeater="select2"
                                                    data-placeholder="Select Brand" name="select_brand_id" id="select_brand_id"
                                                    required>
                                                    <option></option>
                                                    <?php foreach ($brands as $res) { ?>
                                                        <option value='<?= $res['id'] ?>'
                                                            <?= ($suppliers_brands[$x]["users_ms_brands_id"] == $res['id']) ? 'selected' : ""; ?>>
                                                            <?= $res['brand_name'] ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="col-md-5">
                                                <label class="form-label">Type Ownership</label>
                                                <select class="form-select" data-kt-repeater="select2"
                                                    data-placeholder="Select Type Ownership" name="select_type_ownership"
                                                    id="select_type_ownership" required>
                                                    <option></option>
                                                    <?php foreach ($TypeOwnership as $res) { ?>
                                                        <option value='<?= $res['id'] ?>'
                                                            <?= ($suppliers_brands[$x]["users_ms_ownership_types_id"] == $res['id']) ? 'selected' : ""; ?>>
                                                            <?= $res['ownership_type_name'] ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <a href="javascript:;" data-repeater-delete
                                                    class="btn btn-flex btn-sm btn-light-danger mt-3 mt-md-9">
                                                    <i class="ki-duotone ki-trash fs-3"><span class="path1"></span><span
                                                            class="path2"></span><span class="path3"></span><span
                                                            class="path4"></span><span class="path5"></span></i>
                                                    Delete
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                    <?php $x++;
                                } ?>
                            <?php } else { ?>

                                <div data-repeater-item>
                                    <input type="hidden" id="idBrands" name="idBrands" class="form-control mb-3 mb-lg-0"
                                        data-type="input" />
                                    <div class="form-group row mb-5">
                                        <div class="col-md-5">
                                            <label class="form-label required">Brand</label>
                                            <select class="form-select" data-kt-repeater="select2"
                                                data-placeholder="Select Brand" name="select_brand_id" id="select_brand_id"
                                                required>
                                                <option></option>
                                                <?php foreach ($brands as $res) { ?>
                                                    <option value='<?= $res['id'] ?>'>
                                                        <?= $res['brand_name'] ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="col-md-5">
                                            <label class="form-label">Type Ownership</label>
                                            <select class="form-select" data-kt-repeater="select2"
                                                data-placeholder="Select Type Ownership" name="select_type_ownership"
                                                id="select_type_ownership" required>
                                                <option></option>
                                                <?php foreach ($TypeOwnership as $res) { ?>
                                                    <option value='<?= $res['id'] ?>'>
                                                        <?= $res['ownership_type_name'] ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <a href="javascript:;" data-repeater-delete
                                                class="btn btn-flex btn-sm btn-light-danger mt-3 mt-md-9">
                                                <i class="ki-duotone ki-trash fs-3"><span class="path1"></span><span
                                                        class="path2"></span><span class="path3"></span><span
                                                        class="path4"></span><span class="path5"></span></i>
                                                Delete
                                            </a>
                                        </div>
                                    </div>
                                </div>

                            <?php } ?>
                        </div>
                    </div>
                    <!--end::Form group-->
                </div>
                <!--end::Repeater-->
            </div>
        </div>
    </div>
</div>

<script>
    // Repeating
    Inputmask({
        "mask": "9",
        "repeat": 15,
        "greedy": false
    }).mask("#phone");

    Inputmask({
        "mask": "9",
        "repeat": 15,
        "greedy": false
    }).mask("#phone2");
</script>