<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<div class="form-group row mb-8" id="show_mass_upload">
    <div class="col-md-7">
        <input type="hidden" id="wares" name="wares">
        <label class="fw-semibold fs-6">Mass Upload</label>
        <input type="file" id="upload_data" class="form-control mb-3 mb-lg-0 mt-19" accept=".csv" data-type="input"
            autocomplete="off" />
        <div id='formatError' class="fv-plugins-message-container invalid-feedback mb-6">Format Data Csv Error,Download
            CSV Format!.</div>
    </div>

    <div class="col-md-5 mt-3">
        <a href="<?= base_url('/assets/excel/List_Data_Transfer_Out.csv') ?>" download="List_Data_Transfer_Out.csv"
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
                            <th style='text-align: center;vertical-align: middle;'>#</th>
                            <th class="min-w-100px" align="center">SKU</th>
                            <th class="min-w-200px" align="center">PRODUCT NAME</th>
                            <th class="min-w-100px" align="center">BRAND</th>
                            <th class="min-w-100px" align="center">LOCATION</th>
                            <th class="min-w-80px" align="center">QTY</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>