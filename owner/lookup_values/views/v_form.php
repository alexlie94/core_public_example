<?php
defined('BASEPATH') or exit('No direct script access allowed');

$customBaseURL = explode("/", base_url());
// echo '<pre>';
// print_r($customBaseURL);
// die;
?>

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
        Mass Upload Lookup Values
    </button>
</div>

<div class="form-group row mb-8" id="show_mass_upload">
    <div class="col-md-7">
        <label class="required fw-semibold fs-6">Mass Upload</label>
        <input type="file" id="upload_data" class="form-control mb-3 mb-lg-0 mt-19" accept=".csv" data-type="input"
            autocomplete="off" />
        <div id='formatError' class="fv-plugins-message-container invalid-feedback mb-6">Format Data Csv Error,Download
            CSV Format!.</div>
    </div>

    <div class="col-md-5 mt-3">
        <a href="<?= base_url('../assets/excel/List_Lookup_Values.csv') ?>" download="List_Lookup_Values.csv"
            target="_blank" class="text-gray-800 text-hover-primary d-flex flex-column mb-3 text-center">
            <div class="symbol symbol-60px mb-5">
                <img src="<?= base_url('../assets/excel/icons8-microsoft-excel-2019-100.png') ?>"
                    class="theme-light-show" alt="" />
                <img src="<?= base_url('../assets/excel/icons8-microsoft-excel-2019-100.png') ?>"
                    class="theme-dark-show" alt="" />
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
                            <th class="min-w-200px">Lookup Code</th>
                            <th class="min-w-200px">Lookup Name</th>
                            <th class="min-w-200px">Lookup Config</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<div id="form_lookup_values">
    <div class="d-flex flex-column scroll-y me-n7 pe-7">
        <div class="fv-row mb-7">
            <input type="hidden" class="form-control" id="id" name="id" />

            <label class="required fw-semibold fs-6 mb-4">Lookup Code</label>
            <input name="lookup_code" id="lookup_code" type="text" class="form-control form-control-solid mb-3 mb-lg-0"
                placeholder="Enter Lookup Code" data-type="input" autocomplete="off" />
        </div>

        <div class="fv-row mb-7">
            <label class="required fw-semibold fs-6 mb-4">Lookup Name</label>
            <input type="text" id="lookup_name" name="lookup_name" class="form-control form-control-solid mb-3 mb-lg-0"
                placeholder="Enter Lookup Name" data-type="input" autocomplete="off" />
        </div>

        <div class="fv-row mb-7">
            <label class="required fw-semibold fs-6 mb-4">Lookup Config</label>
            <input type="text" id="lookup_config" name="lookup_config"
                class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Enter Lookup Config" data-type="input"
                autocomplete="off" />
        </div>
    </div>
</div>