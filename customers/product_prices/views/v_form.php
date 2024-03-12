<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<div class="row g-9 mb-8">
    <div class="card card-flush shadow-sm mt-4">
        <div class="card-header">
            <h3 class="card-title">Batch Edit</h3>
        </div>
        <div class="card-body py-5">
            <div class="row g-9 mb-8">
                <div class="col-md-6 fv-row">
                    <label class="fs-6 fw-semibold mb-2">Batch ID</label>
                    <input type="hidden" class="form-control form-control" placeholder="Enter Batch ID" name="id"
                        id="id" data-type="input" value="<?= isset($dataItems['id']) ? $dataItems['id'] : '' ?>" />
                    <input type="text" class="form-control form-control" placeholder="Enter Batch ID" name="show_id"
                        id="show_id" data-type="input"
                        value="<?= isset($dataItems['id']) ? $dataItems['id'] : 'Auto' ?>" disabled />
                </div>
                <!--begin::Col-->
                <div class="col-md-6 fv-row">
                    <label class="required fs-6 fw-semibold mb-2">Batch Name</label>
                    <input type="text" class="form-control form-control" placeholder="Enter Batch Name"
                        name="batch_name" id="batch_name" data-type="input"
                        value="<?= isset($dataItems['batch_name']) ? $dataItems['batch_name'] : '' ?>" />
                </div>
                <!--end::Col-->
            </div>
            <!--begin::Input group-->
            <div class="row g-9 mb-8">
                <!--begin::Col-->
                <div class="col-md-6 fv-row">
                    <label class="required fs-6 fw-semibold mb-2">Batch Description</label>
                    <input type="text" class="form-control form-control" placeholder="Enter Batch Description"
                        name="batch_description" id="batch_description" data-type="input"
                        value="<?= isset($dataItems['batch_description']) ? $dataItems['batch_description'] : '' ?>" />
                </div>
                <!--end::Col-->
                <!--begin::Col-->
                <div class="col-md-6 fv-row">
                    <label class="fs-6 fw-semibold mb-2">Batch Location</label>
                    <select name="batch_location" id="batch_location" class="form-select batch_location_target"
                        data-control="select2" data-hide-search="true" data-type='select'
                        data-placeholder="Select Batch Location" data-type="select">
                        <option value="99" <?= isset($dataItems['batch_location']) ? $dataItems['batch_location'] == 'main' ? 'selected' : '' : '' ?>>
                            Main</option>
                        <?php foreach ($sources as $value) { ?>
                            <option value="<?= $value['id'] ?>" <?= isset($dataItems['batch_location']) ? $dataItems['batch_location'] == $value['id'] ? 'selected' : '' : '' ?>>
                                <?= $value['source_name'] ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <!--end::Col-->
            </div>
            <!--end::Input group-->
            <div class="row g-9 mb-5">
                <div class="col-md-6 fv-row">
                </div>
                <div class="col-md-6 fv-row">
                    <!--begin::Checkboxes-->
                    <div class="d-flex align-items-center">
                        <!--begin::Checkbox-->
                        <input type="hidden" id="end_date_status" name="end_date_status" value="0">
                        <label class="form-check form-check-custom form-check">
                            <span class="form-check-label fw-semibold" for="endDate" style="margin: auto;">End
                                Date&nbsp;&nbsp;</span>
                            <input class="form-check-input h-20px w-20px" type="checkbox" name="endDate" id="endDate"
                                value="<?= isset($dataItems['end_date_status']) ? $dataItems['end_date_status'] == 1 ? '1' : '0' : '' ?>"
                                <?= isset($dataItems['end_date_status']) ? $dataItems['end_date_status'] == 1 ? 'checked' : '' : '' ?>>
                        </label>
                        <!--end::Checkbox-->
                    </div>
                    <!--end::Checkboxes-->
                </div>
            </div>
            <!--begin::Input group-->
            <div class="row g-9 mb-8">
                <!--begin::Col-->
                <div class="col-md-6 fv-row">
                    <!--begin::Input-->
                    <label class="required fs-6 fw-semibold mb-2">Start Date</label>
                    <div class="input-group input-group">
                        <span class="input-group-text" id="basic-addon1"><i class="bi bi-calendar-plus"></i></span>
                        <input class="form-control" placeholder="Select Start Date" name="start_date" id="start_date"
                            type="text" readonly="readonly" data-type="input" data-type="input"
                            value="<?= isset($dataItems['start_date']) ? $dataItems['start_date'] : '' ?>">
                    </div>
                    <!--end::Input-->
                </div>
                <!--end::Col-->
                <!--begin::Col-->
                <div class="col-md-6 fv-row">
                    <!--begin::Input-->
                    <label class="fs-6 fw-semibold mb-2">End Date</label>
                    <div class="input-group input-group">
                        <span class="input-group-text" id="basic-addon1"><i class="bi bi-calendar-plus"></i></span>
                        <input class="form-control" placeholder="Select End Date" name="end_date" id="end_date"
                            type="text" readonly="readonly" data-type="input"
                            value="<?= isset($dataItems['end_date']) ? $dataItems['end_date'] : '' ?>">
                    </div>
                    <!--end::Input-->
                </div>
                <!--end::Col-->
            </div>
            <!--end::Input group-->
            <div class="row g-9 mb-8">
                <div class="col-md-6 fv-row mb-5" id="status_field">
                    <input type="hidden" id="batch_status" name="batch_status"
                        value="<?= isset($dataItems['status']) ? $dataItems['status'] == 1 ? 2 : 1 : '' ?>">
                    <?php
                    $startDate = isset($dataItems['start_date']) ? $dataItems['start_date'] : '';
                    $currentDateTime = date('Y-m-d H:i:s');
                    if ($startDate != '') {
                        if ($startDate > $currentDateTime && isset($dataItems['end_date'])) {
                            // Start date and time is greater than today
                            if ($dataItems['status'] == 1) {
                                # code...
                                echo '
                                <!--begin::Input-->
                                <input type="hidden" id="status" name="status" value="">
                                <button class="btn btn-light-danger h-40px fs-7 fw-bold" type="button"
                                    id="btnStatus">Disable</button>
                                <!--End::Input-->';
                            } else {
                                echo '
                                <!--begin::Input-->
                                <input type="hidden" id="status" name="status" value="">
                                <button class="btn btn-light-success h-40px fs-7 fw-bold" type="button"
                                    id="btnStatus">Enable</button>
                                <!--End::Input-->';
                            }
                        }
                    }
                    ?>
                </div>
            </div>
            <!--End::Input group-->
            <div class="row g-9 mb-8">
                <div class="col-md-6 mt-3">
                    <label class="required fw-semibold fs-6 mb-4">Re-Upload Data</label>
                    <input type="file" id="upload_data" class="form-control mb-3 mb-lg-0 mt-19" accept=".csv"
                        data-type="input" autocomplete="off" <?php
                        if ($startDate != '') {
                            if ($startDate > $currentDateTime && isset($dataItems['end_date'])) {
                                echo '';
                            } else {
                                echo 'disabled';
                            }
                        }
                        ?> />
                    <div id='formatError' class="fv-plugins-message-container invalid-feedback">Format Data Csv
                        Error,Download CSV Format!.</div>
                </div>

                <div class="col-md-6 mt-3 text-gray-800 text-hover-primary d-flex flex-column mb-3 text-center">
                    <div class="symbol symbol-60px mb-5">
                        <img src="<?= base_url('/assets/excel/icons8-microsoft-excel-2019-100.png') ?>"
                            class="theme-light-show" alt="" />
                        <img src="<?= base_url('/assets/excel/icons8-microsoft-excel-2019-100.png') ?>"
                            class="theme-dark-show" alt="" />
                    </div>
                    <a href="<?= base_url('/assets/excel/Product_List_Price.csv') ?>" download="Product_List_Price.csv"
                        target="_blank" class="text-gray-800 text-hover-primary d-flex flex-column mb-3 text-center">
                        <button type="button" data-repeater-create=""
                            class="fs-5 fw-bold mb-2 btn btn-md btn-light-success mt-6">
                            <span class="svg-icon svg-icon-2">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <rect opacity="0.5" x="11" y="18" width="12" height="2" rx="1"
                                        transform="rotate(-90 11 18)" fill="currentColor" />
                                    <rect x="6" y="11" width="12" height="2" rx="1" fill="currentColor" />
                                </svg>
                            </span>
                            Download CSV Format
                        </button>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-9 mb-8">
    <div class="card card-flush shadow-sm mt-4">
        <div class="card-header">
            <h3 class="card-title">Batch Preview</h3>
        </div>
        <div class="card-body py-5">
            <div class="row flex-nowrap overflow-auto">
                <div class="table-responsive">
                    <table id="kt_datatable_vertical_scroll" class="table table-striped border rounded gy-5 gs-7">
                        <thead>
                            <tr class="fw-semibold fs-6 text-gray-800">
                                <th class="min-w-40px">No</th>
                                <th class="min-w-100px">Product ID</th>
                                <th>Price</th>
                                <th>Sale Price</th>
                                <th>Offline Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (isset($showDataTable)) {
                                $no = 1;
                                foreach ($showDataTable as $result) { ?>
                                    <tr>
                                        <td>
                                            <?= $no++ ?>
                                        </td>
                                        <td>
                                            <?= $result['users_ms_products_id'] ?>
                                        </td>
                                        <td>
                                            <?= $result['price'] ?>
                                        </td>
                                        <td>
                                            <?= $result['sale_price'] ?>
                                        </td>
                                        <td>
                                            <?= $result['offline_price'] ?>
                                        </td>
                                    </tr>
                                <?php }
                            } ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>