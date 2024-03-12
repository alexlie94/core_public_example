<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
    <!--begin::Content wrapper-->
    <div class="d-flex flex-column flex-column-fluid">
        <!--begin::Toolbar-->
        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
            <!--begin::Toolbar container-->
            <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
                <!--begin::Page title-->
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <!--begin::Title-->
                    <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                        <?= $titlePage ?>
                    </h1>
                    <!--end::Title-->
                </div>
                <!--end::Page title-->
            </div>
            <!--end::Toolbar container-->
        </div>
        <!--end::Toolbar-->
        <!--begin::Toolbar-->
        <div id="kt_app_toolbar" class="app-toolbar">
            <!--begin::Toolbar container-->
            <div id="kt_app_toolbar_container" class="app-container d-flex flex-stack flex-wrap">
                <!--begin::Toolbar wrapper-->
                <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
                    <!--begin::Page title-->
                    <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
                        <!--begin::Title-->
                        <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bold fs-2x m-0">
                            Good Morning, Administrator</h1>
                        <h1 class="page-heading d-flex flex-column justify-content-center text-gray-400 fw-bold fs-1x m-0">
                            Sunday, May 21, 2023</h1>
                        <!--end::Title-->
                    </div>
                    <!--end::Page title-->
                </div>
                <!--end::Toolbar wrapper-->
            </div>
            <!--end::Toolbar container-->
        </div>
        <!--end::Toolbar-->
        <!--begin::Content-->
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <!--begin::Content container-->
            <div id="kt_app_content_container" class="app-container">
                <!--begin::Row-->
                <div class="row g-5  mb-5 mb-xl-10">
                    <!--begin::Col-->
                    <div class="col-xl-12">
                        <!--begin::Row-->
                        <div class="row g-5 g-xl-12">
                            <!--begin::Col-->
                            <div class="col-xl-9">
                                <!--begin::Notice-->
                                <div class="notice d-flex bg-light-warning rounded border-warning border border-dashed p-6 mb-4">
                                    <!--begin::Icon-->
                                    <!--begin::Svg Icon | path: icons/duotune/general/gen044.svg-->
                                    <span class="svg-icon svg-icon-2tx svg-icon-warning me-4">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="currentColor" />
                                            <rect x="11" y="14" width="7" height="2" rx="1" transform="rotate(-90 11 14)" fill="currentColor" />
                                            <rect x="11" y="17" width="2" height="2" rx="1" transform="rotate(-90 11 17)" fill="currentColor" />
                                        </svg>
                                    </span>
                                    <!--end::Svg Icon-->
                                    <!--end::Icon-->
                                    <!--begin::Wrapper-->
                                    <div class="d-flex flex-stack flex-grow-1">
                                        <!--begin::Content-->
                                        <div class="fw-semibold">
                                            <h4 class="text-gray-900 fw-bold">Anouncement!</h4>
                                            <p class="text-gray-400 fs-6 fw-semibold">21 May 2023 :</p>
                                            <div class="fs-6 text-gray-700">
                                                <ul>
                                                    <li>New Release : Bulk Listing via Interface: Multi products, Multi
                                                        Stores, dan Multi categories (Available: Shopee, Tokopedia,
                                                        Bukalapak, Blibli). <a href="#" class="text-gray-800 fw-bold text-hover-primary">Learn
                                                            more</a> </li>
                                                    <li>New Release : <a href="#" class="text-gray-800 fw-bold text-hover-primary">Marketplace
                                                            Chats</a> (Ready Channel: Shopee,
                                                        Tokopedia). <a href="#" class="text-gray-800 fw-bold text-hover-primary">Learn
                                                            more</a></li>
                                                    <li>New Release : <a href="#" class="text-gray-800 fw-bold text-hover-primary">Reporting
                                                            v2</a> (Available: Financial Transactions log/Ledger based
                                                        on Invoice) Please active Invoice first. <a href="#" class="text-gray-800 fw-bold text-hover-primary">Learn
                                                            more</a></li>
                                                </ul>
                                            </div>
                                            <p class="text-gray-400 fs-6 fw-semibold">22 May 2023 :</p>
                                            <div class="fs-6 text-gray-700">
                                                <ul>
                                                    <li>Harga coret untuk Tiktok sudah dapat dilihat di Forstok. <a href="#" class="text-gray-800 fw-bold text-hover-primary">Learn
                                                            more</a></li>
                                                </ul>
                                            </div>
                                            <p class="text-gray-400 fs-6 fw-semibold" align="right"><i>Updated At 23 May
                                                    2023</i>
                                            </p>
                                        </div>
                                        <!--end::Content-->
                                    </div>
                                    <!--end::Wrapper-->
                                </div>
                                <!--end::Notice-->
                            </div>
                            <!--end::Col-->
                            <!--begin::Col-->
                            <div class="col-xl-3">
                                <!--begin::More channels-->
                                <div class="card-rounded bg-primary bg-opacity-5 p-10">
                                    <!--begin::Title-->
                                    <h2 class="text-dark text-center fw-bold mb-11">Help & Support</h2>
                                    <!--end::Title-->
                                    <!--begin::Item-->
                                    <div class="d-flex align-items-center mb-7">
                                        <!--begin::Icon-->
                                        <i class="bi bi-journal-text text-primary fs-1 me-5"></i>
                                        <!--end::SymIconbol-->
                                        <!--begin::Info-->
                                        <div class="d-flex flex-column">
                                            <a href="#" class="link-primary">
                                                <h5 class="text-gray-800 fw-bold text-hover-primary">Help Documentation
                                                </h5>
                                            </a>
                                        </div>
                                        <!--end::Info-->
                                    </div>
                                    <!--end::Item-->
                                    <!--begin::Item-->
                                    <div class="d-flex align-items-center mb-7">
                                        <!--begin::Icon-->
                                        <i class="bi bi-chat-square-text-fill text-primary fs-1 me-5"></i>
                                        <!--end::SymIconbol-->
                                        <!--begin::Info-->
                                        <div class="d-flex flex-column">
                                            <a href="#" class="link-primary">
                                                <h5 class="text-gray-800 fw-bold text-hover-primary">Frequently Asked
                                                    Question
                                                </h5>
                                            </a>
                                        </div>
                                        <!--end::Info-->
                                    </div>
                                    <!--end::Item-->
                                    <!--begin::Item-->
                                    <div class="d-flex align-items-center mb-7">
                                        <!--begin::Icon-->
                                        <i class="bi bi-journal-code text-primary fs-1 me-5"></i>
                                        <!--end::SymIconbol-->
                                        <!--begin::Info-->
                                        <div class="d-flex flex-column">
                                            <a href="#" class="link-primary">
                                                <h5 class="text-gray-800 fw-bold text-hover-primary">API Documentation
                                                </h5>
                                            </a>
                                        </div>
                                        <!--end::Info-->
                                    </div>
                                    <!--end::Item-->
                                    <!--begin::Item-->
                                    <div class="d-flex align-items-center mb-5">
                                        <!--begin::Icon-->
                                        <i class="bi bi-gift text-primary fs-1 me-5"></i>
                                        <!--end::SymIconbol-->
                                        <!--begin::Info-->
                                        <div class="d-flex flex-column">
                                            <a href="#" class="link-primary">
                                                <h5 class="text-gray-800 fw-bold text-hover-primary">What's New
                                                </h5>
                                            </a>
                                        </div>
                                        <!--end::Info-->
                                    </div>
                                    <!--end::Item-->
                                    <hr>
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="d-flex flex-column">
                                            <a href="#" class="text-gray-800 fw-bold text-hover-primary fs-6">My Open
                                                Tikets</a>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="d-flex flex-column">
                                            <a href="#" class="text-gray-800 fw-bold text-hover-primary fs-6">Create New
                                                Ticked</a>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="d-flex flex-column">
                                            <a href="#" class="text-gray-800 fw-bold text-hover-primary fs-6">Register
                                                For Webinar</a>
                                        </div>
                                    </div>
                                </div>
                                <!--end::More channels-->
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Row-->
                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Row-->
                <!--begin::Row-->
                <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
                    <!--begin::Col-->
                    <div class="col-xxl-12">
                        <!--begin::Tables Widget 11-->
                        <div class="card shadow-sm mb-5 mb-xl-1">
                            <!--begin::Header-->
                            <div class="card-header border-0 pt-5">
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bold fs-3 mb-1">Pending Actions</span>
                                    <span class="text-danger mt-1 fw-semibold fs-2x">42</span>
                                </h3>
                            </div>
                            <!--end::Header-->
                            <!--begin::Body-->
                            <div class="card-body py-3">
                                <!--begin::Table container-->
                                <div class="table-responsive">
                                    <!--begin::Table-->
                                    <table class="table align-middle gs-0 gy-4">
                                        <!--begin::Table head-->
                                        <thead>
                                            <tr class="fw-bold text-muted bg-light">
                                                <th class="ps-4 rounded-start">TYPE</th>
                                                <th class="p-4 text-end rounded-end">SKU</th>
                                            </tr>
                                        </thead>
                                        <!--end::Table head-->
                                        <!--begin::Table body-->
                                        <tbody>
                                            <tr class="fs-3">
                                                <td class="ps-4">Import items - Not Match Sku | <button type="button" class="btn btn-danger btn-sm p-1">Download</button></td>
                                                <td class="p-4 text-success text-end">42</td>
                                            </tr>
                                        </tbody>
                                        <!--end::Table body-->
                                    </table>
                                    <div class="separator mb-8"></div>
                                    <!--end::Table-->
                                </div>
                                <!--end::Table container-->
                            </div>
                            <!--begin::Body-->
                        </div>
                        <!--end::Tables Widget 11-->
                    </div>
                    <!--end::Col-->
                    <!--begin::Col-->
                    <div class="col-xxl-12">
                        <!--begin::Tables Widget 11-->
                        <div class="card shadow-sm mb-5 mb-xl-1">
                            <!--begin::Header-->
                            <div class="card-header border-0 pt-5">
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bold fs-3 mb-1">Order to Fulfill</span>
                                    <span class="text-danger mt-1 fw-semibold fs-2x">13</span>
                                </h3>
                                <div class="card-toolbar">
                                    <!--begin::Daterangepicker(defined in src/js/layout/app.js)-->
                                    <div data-kt-daterangepicker="true" data-kt-daterangepicker-opens="left" data-kt-daterangepicker-range="today" class="btn btn-sm btn-light d-flex align-items-center px-4">
                                        <!--begin::Display range-->
                                        <div class="text-gray-600 fw-bold">Loading date range...</div>
                                        <!--end::Display range-->
                                        <!--begin::Svg Icon | path: icons/duotune/general/gen014.svg-->
                                        <span class="svg-icon svg-icon-1 ms-2 me-0">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path opacity="0.3" d="M21 22H3C2.4 22 2 21.6 2 21V5C2 4.4 2.4 4 3 4H21C21.6 4 22 4.4 22 5V21C22 21.6 21.6 22 21 22Z" fill="currentColor" />
                                                <path d="M6 6C5.4 6 5 5.6 5 5V3C5 2.4 5.4 2 6 2C6.6 2 7 2.4 7 3V5C7 5.6 6.6 6 6 6ZM11 5V3C11 2.4 10.6 2 10 2C9.4 2 9 2.4 9 3V5C9 5.6 9.4 6 10 6C10.6 6 11 5.6 11 5ZM15 5V3C15 2.4 14.6 2 14 2C13.4 2 13 2.4 13 3V5C13 5.6 13.4 6 14 6C14.6 6 15 5.6 15 5ZM19 5V3C19 2.4 18.6 2 18 2C17.4 2 17 2.4 17 3V5C17 5.6 17.4 6 18 6C18.6 6 19 5.6 19 5Z" fill="currentColor" />
                                                <path d="M8.8 13.1C9.2 13.1 9.5 13 9.7 12.8C9.9 12.6 10.1 12.3 10.1 11.9C10.1 11.6 10 11.3 9.8 11.1C9.6 10.9 9.3 10.8 9 10.8C8.8 10.8 8.59999 10.8 8.39999 10.9C8.19999 11 8.1 11.1 8 11.2C7.9 11.3 7.8 11.4 7.7 11.6C7.6 11.8 7.5 11.9 7.5 12.1C7.5 12.2 7.4 12.2 7.3 12.3C7.2 12.4 7.09999 12.4 6.89999 12.4C6.69999 12.4 6.6 12.3 6.5 12.2C6.4 12.1 6.3 11.9 6.3 11.7C6.3 11.5 6.4 11.3 6.5 11.1C6.6 10.9 6.8 10.7 7 10.5C7.2 10.3 7.49999 10.1 7.89999 10C8.29999 9.90003 8.60001 9.80003 9.10001 9.80003C9.50001 9.80003 9.80001 9.90003 10.1 10C10.4 10.1 10.7 10.3 10.9 10.4C11.1 10.5 11.3 10.8 11.4 11.1C11.5 11.4 11.6 11.6 11.6 11.9C11.6 12.3 11.5 12.6 11.3 12.9C11.1 13.2 10.9 13.5 10.6 13.7C10.9 13.9 11.2 14.1 11.4 14.3C11.6 14.5 11.8 14.7 11.9 15C12 15.3 12.1 15.5 12.1 15.8C12.1 16.2 12 16.5 11.9 16.8C11.8 17.1 11.5 17.4 11.3 17.7C11.1 18 10.7 18.2 10.3 18.3C9.9 18.4 9.5 18.5 9 18.5C8.5 18.5 8.1 18.4 7.7 18.2C7.3 18 7 17.8 6.8 17.6C6.6 17.4 6.4 17.1 6.3 16.8C6.2 16.5 6.10001 16.3 6.10001 16.1C6.10001 15.9 6.2 15.7 6.3 15.6C6.4 15.5 6.6 15.4 6.8 15.4C6.9 15.4 7.00001 15.4 7.10001 15.5C7.20001 15.6 7.3 15.6 7.3 15.7C7.5 16.2 7.7 16.6 8 16.9C8.3 17.2 8.6 17.3 9 17.3C9.2 17.3 9.5 17.2 9.7 17.1C9.9 17 10.1 16.8 10.3 16.6C10.5 16.4 10.5 16.1 10.5 15.8C10.5 15.3 10.4 15 10.1 14.7C9.80001 14.4 9.50001 14.3 9.10001 14.3C9.00001 14.3 8.9 14.3 8.7 14.3C8.5 14.3 8.39999 14.3 8.39999 14.3C8.19999 14.3 7.99999 14.2 7.89999 14.1C7.79999 14 7.7 13.8 7.7 13.7C7.7 13.5 7.79999 13.4 7.89999 13.2C7.99999 13 8.2 13 8.5 13H8.8V13.1ZM15.3 17.5V12.2C14.3 13 13.6 13.3 13.3 13.3C13.1 13.3 13 13.2 12.9 13.1C12.8 13 12.7 12.8 12.7 12.6C12.7 12.4 12.8 12.3 12.9 12.2C13 12.1 13.2 12 13.6 11.8C14.1 11.6 14.5 11.3 14.7 11.1C14.9 10.9 15.2 10.6 15.5 10.3C15.8 10 15.9 9.80003 15.9 9.70003C15.9 9.60003 16.1 9.60004 16.3 9.60004C16.5 9.60004 16.7 9.70003 16.8 9.80003C16.9 9.90003 17 10.2 17 10.5V17.2C17 18 16.7 18.4 16.2 18.4C16 18.4 15.8 18.3 15.6 18.2C15.4 18.1 15.3 17.8 15.3 17.5Z" fill="currentColor" />
                                            </svg>
                                        </span>
                                        <!--end::Svg Icon-->
                                    </div>
                                    <!--end::Daterangepicker-->
                                </div>
                            </div>
                            <!--end::Header-->
                            <!--begin::Body-->
                            <div class="card-body py-3">
                                <!--begin::Table container-->
                                <div class="table-responsive">
                                    <!--begin::Table-->
                                    <table class="table align-middle gs-0 gy-4">
                                        <!--begin::Table head-->
                                        <thead>
                                            <tr class="fw-bold text-muted bg-light">
                                                <th class="ps-4 rounded-start"></th>
                                                <th>PENDING PAYMENT</th>
                                                <th>OPEN ORDERS</th>
                                                <th>NOT SHIPPED</th>
                                                <th class="rounded-end">READY TO SHIP</th>
                                            </tr>
                                        </thead>
                                        <!--end::Table head-->
                                        <!--begin::Table body-->
                                        <tbody>
                                            <tr class="fs-3">
                                                <td class="ps-4">
                                                    <div class="d-flex align-items-center">
                                                        <div class="symbol symbol-50px me-5">
                                                            <span class="symbol-label bg-light">
                                                                <img src="http://localhost/ims_project/assets/metronic/media/marketplace/tokopedia-icon.png" class="h-75 align-self-end" alt="">
                                                            </span>
                                                        </div>
                                                        <div class="d-flex justify-content-start flex-column">
                                                            <span class="text-muted fw-bold d-block fs-6">TOKOPEDIA</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>0</td>
                                                <td>0</td>
                                                <td>0</td>
                                                <td class="text-success">13</td>
                                            </tr>
                                            <tr class="py-5 fw-bolder border-bottom border-gray-500 fs-3"></tr>
                                            <tr class="py-5 fw-bolder border-bottom border-gray-500 fs-3">
                                                <td class="ps-4">TOTAL</td>
                                                <td>0</td>
                                                <td>0</td>
                                                <td>0</td>
                                                <td class="text-success">13</td>
                                            </tr>
                                        </tbody>
                                        <!--end::Table body-->
                                    </table>
                                    <!--end::Table-->
                                </div>
                                <!--end::Table container-->
                            </div>
                            <!--begin::Body-->
                        </div>
                        <!--end::Tables Widget 11-->
                    </div>
                    <!--end::Col-->
                    <!--begin::Col-->
                    <div class="col-xxl-12">
                        <!--begin::Chart widget 20-->
                        <div class="card shadow-sm card-flush mb-5 mb-xl-1">
                            <!--begin::Header-->
                            <div class="card-header py-5 mb-10">
                                <!--begin::Title-->
                                <div class="d-flex align-items-center position-relative my-1">
                                    <div class="align-items-start flex-column">
                                        <!--begin::Solid input group style-->
                                        <select class="form-select form-select-solid" data-control="select2" data-placeholder="Select an option">
                                            <option></option>
                                            <option value="1">Option 1</option>
                                            <option value="2">Option 2</option>
                                            <option value="3">Option 3</option>
                                            <option value="4">Option 4</option>
                                            <option value="5">Option 5</option>
                                        </select>
                                        <!--end::Solid input group style-->
                                    </div>
                                    <div class="ps-4 align-items-start flex-column">
                                        <!--begin::Daterangepicker(defined in src/js/layout/app.js)-->
                                        <div data-kt-daterangepicker="true" data-kt-daterangepicker-opens="left" data-kt-daterangepicker-range="today" class="btn btn-sm btn-light d-flex align-items-center px-4">
                                            <!--begin::Display range-->
                                            <div class="text-gray-600 fw-bold">Loading date range...</div>
                                            <!--end::Display range-->
                                            <!--begin::Svg Icon | path: icons/duotune/general/gen014.svg-->
                                            <span class="svg-icon svg-icon-1 ms-2 me-0">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path opacity="0.3" d="M21 22H3C2.4 22 2 21.6 2 21V5C2 4.4 2.4 4 3 4H21C21.6 4 22 4.4 22 5V21C22 21.6 21.6 22 21 22Z" fill="currentColor" />
                                                    <path d="M6 6C5.4 6 5 5.6 5 5V3C5 2.4 5.4 2 6 2C6.6 2 7 2.4 7 3V5C7 5.6 6.6 6 6 6ZM11 5V3C11 2.4 10.6 2 10 2C9.4 2 9 2.4 9 3V5C9 5.6 9.4 6 10 6C10.6 6 11 5.6 11 5ZM15 5V3C15 2.4 14.6 2 14 2C13.4 2 13 2.4 13 3V5C13 5.6 13.4 6 14 6C14.6 6 15 5.6 15 5ZM19 5V3C19 2.4 18.6 2 18 2C17.4 2 17 2.4 17 3V5C17 5.6 17.4 6 18 6C18.6 6 19 5.6 19 5Z" fill="currentColor" />
                                                    <path d="M8.8 13.1C9.2 13.1 9.5 13 9.7 12.8C9.9 12.6 10.1 12.3 10.1 11.9C10.1 11.6 10 11.3 9.8 11.1C9.6 10.9 9.3 10.8 9 10.8C8.8 10.8 8.59999 10.8 8.39999 10.9C8.19999 11 8.1 11.1 8 11.2C7.9 11.3 7.8 11.4 7.7 11.6C7.6 11.8 7.5 11.9 7.5 12.1C7.5 12.2 7.4 12.2 7.3 12.3C7.2 12.4 7.09999 12.4 6.89999 12.4C6.69999 12.4 6.6 12.3 6.5 12.2C6.4 12.1 6.3 11.9 6.3 11.7C6.3 11.5 6.4 11.3 6.5 11.1C6.6 10.9 6.8 10.7 7 10.5C7.2 10.3 7.49999 10.1 7.89999 10C8.29999 9.90003 8.60001 9.80003 9.10001 9.80003C9.50001 9.80003 9.80001 9.90003 10.1 10C10.4 10.1 10.7 10.3 10.9 10.4C11.1 10.5 11.3 10.8 11.4 11.1C11.5 11.4 11.6 11.6 11.6 11.9C11.6 12.3 11.5 12.6 11.3 12.9C11.1 13.2 10.9 13.5 10.6 13.7C10.9 13.9 11.2 14.1 11.4 14.3C11.6 14.5 11.8 14.7 11.9 15C12 15.3 12.1 15.5 12.1 15.8C12.1 16.2 12 16.5 11.9 16.8C11.8 17.1 11.5 17.4 11.3 17.7C11.1 18 10.7 18.2 10.3 18.3C9.9 18.4 9.5 18.5 9 18.5C8.5 18.5 8.1 18.4 7.7 18.2C7.3 18 7 17.8 6.8 17.6C6.6 17.4 6.4 17.1 6.3 16.8C6.2 16.5 6.10001 16.3 6.10001 16.1C6.10001 15.9 6.2 15.7 6.3 15.6C6.4 15.5 6.6 15.4 6.8 15.4C6.9 15.4 7.00001 15.4 7.10001 15.5C7.20001 15.6 7.3 15.6 7.3 15.7C7.5 16.2 7.7 16.6 8 16.9C8.3 17.2 8.6 17.3 9 17.3C9.2 17.3 9.5 17.2 9.7 17.1C9.9 17 10.1 16.8 10.3 16.6C10.5 16.4 10.5 16.1 10.5 15.8C10.5 15.3 10.4 15 10.1 14.7C9.80001 14.4 9.50001 14.3 9.10001 14.3C9.00001 14.3 8.9 14.3 8.7 14.3C8.5 14.3 8.39999 14.3 8.39999 14.3C8.19999 14.3 7.99999 14.2 7.89999 14.1C7.79999 14 7.7 13.8 7.7 13.7C7.7 13.5 7.79999 13.4 7.89999 13.2C7.99999 13 8.2 13 8.5 13H8.8V13.1ZM15.3 17.5V12.2C14.3 13 13.6 13.3 13.3 13.3C13.1 13.3 13 13.2 12.9 13.1C12.8 13 12.7 12.8 12.7 12.6C12.7 12.4 12.8 12.3 12.9 12.2C13 12.1 13.2 12 13.6 11.8C14.1 11.6 14.5 11.3 14.7 11.1C14.9 10.9 15.2 10.6 15.5 10.3C15.8 10 15.9 9.80003 15.9 9.70003C15.9 9.60003 16.1 9.60004 16.3 9.60004C16.5 9.60004 16.7 9.70003 16.8 9.80003C16.9 9.90003 17 10.2 17 10.5V17.2C17 18 16.7 18.4 16.2 18.4C16 18.4 15.8 18.3 15.6 18.2C15.4 18.1 15.3 17.8 15.3 17.5Z" fill="currentColor" />
                                                </svg>
                                            </span>
                                            <!--end::Svg Icon-->
                                        </div>
                                        <!--end::Daterangepicker-->
                                    </div>

                                </div>
                                <!--end::Title-->
                                <!--begin::Toolbar-->
                                <div class="card-toolbar">
                                    <!--begin::Daterangepicker(defined in src/js/layout/app.js)-->
                                    <select class="form-select form-select-solid" data-control="select2" data-placeholder="Select an option">
                                        <option></option>
                                        <option value="export">Export</option>
                                        <option value="import">Import</option>
                                    </select>
                                    <!--end::Daterangepicker-->
                                </div>
                                <!--end::Toolbar-->
                            </div>
                            <!--end::Header-->
                            <!--begin::Card body-->
                            <div class="card-body d-flex justify-content-between flex-column pb-0 px-0 pt-1">
                                <!--begin::Items-->
                                <div class="d-flex flex-wrap d-grid gap-5 px-9 mb-5">
                                    <!--begin::Item-->
                                    <div class="me-md-2">
                                        <!--begin::Title-->
                                        <span class="fs-3 fw-semibold text-gray-600">Gross Sales</span>
                                        <!--end::Title-->
                                        <!--begin::Statistics-->
                                        <div class="d-flex mb-2">
                                            <span class="fs-4 fw-semibold text-gray-400 me-1">Rp.</span>
                                            <span class="fs-2hx fw-bold text-gray-800 me-2 lh-1 ls-n2">9.330.900</span>
                                        </div>
                                        <!--end::Statistics-->
                                        <div class="d-flex mb-2">
                                            <!--begin::Label-->
                                            <span class="badge badge-light-success fs-base">
                                                <!--begin::Svg Icon | path: icons/duotune/arrows/arr067.svg-->
                                                <span class="svg-icon svg-icon-7 svg-icon-success ms-n1">
                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path opacity="0.5" d="M13 9.59998V21C13 21.6 12.6 22 12 22C11.4 22 11 21.6 11 21V9.59998H13Z" fill="currentColor" />
                                                        <path d="M5.7071 7.89291C5.07714 8.52288 5.52331 9.60002 6.41421 9.60002H17.5858C18.4767 9.60002 18.9229 8.52288 18.2929 7.89291L12.7 2.3C12.3 1.9 11.7 1.9 11.3 2.3L5.7071 7.89291Z" fill="currentColor" />
                                                    </svg>
                                                </span>
                                                <!--end::Svg Icon-->10.5%
                                            </span>
                                            <!--end::Label-->
                                        </div>
                                        <!--begin::Description-->
                                        <span class="fs-6 fw-semibold text-gray-400">vs previous 13 day(s)</span>
                                        <!--end::Description-->
                                    </div>
                                    <!--end::Item-->
                                    <!--begin::Item-->
                                    <div class="px-5 ps-md-10 pe-md-7 me-md-5">
                                        <!--begin::Title-->
                                        <span class="fs-3 fw-semibold text-gray-600">Orders</span>
                                        <!--end::Title-->
                                        <!--begin::Statistics-->
                                        <div class="d-flex mb-2">
                                            <span class="fs-4 fw-semibold text-gray-400 me-1"></span>
                                            <span class="fs-2hx fw-bold text-gray-800 me-2 lh-1 ls-n2">70</span>
                                        </div>
                                        <!--end::Statistics-->
                                        <div class="d-flex mb-2">
                                            <!--begin::Label-->
                                            <span class="badge badge-light-danger fs-base">
                                                <!--begin::Svg Icon | path: icons/duotune/arrows/arr065.svg-->
                                                <span class="svg-icon svg-icon-5 svg-icon-danger ms-n1">
                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <rect opacity="0.5" x="11" y="18" width="13" height="2" rx="1" transform="rotate(-90 11 18)" fill="currentColor"></rect>
                                                        <path d="M11.4343 15.4343L7.25 11.25C6.83579 10.8358 6.16421 10.8358 5.75 11.25C5.33579 11.6642 5.33579 12.3358 5.75 12.75L11.2929 18.2929C11.6834 18.6834 12.3166 18.6834 12.7071 18.2929L18.25 12.75C18.6642 12.3358 18.6642 11.6642 18.25 11.25C17.8358 10.8358 17.1642 10.8358 16.75 11.25L12.5657 15.4343C12.2533 15.7467 11.7467 15.7467 11.4343 15.4343Z" fill="currentColor"></path>
                                                    </svg>
                                                </span>
                                                <!--end::Svg Icon-->9.4%
                                            </span>
                                            <!--end::Label-->
                                        </div>
                                        <!--begin::Description-->
                                        <span class="fs-6 fw-semibold text-gray-400">vs previous 13 day(s)</span>
                                        <!--end::Description-->
                                    </div>
                                    <!--end::Item-->
                                    <!--begin::Item-->
                                    <div class="m-0">
                                        <!--begin::Title-->
                                        <span class="fs-3 fw-semibold text-gray-600">Items Sold</span>
                                        <!--end::Title-->
                                        <!--begin::Statistics-->
                                        <div class="d-flex mb-2">
                                            <!--begin::Currency-->
                                            <span class="fs-4 fw-semibold text-gray-400 align-self-start me-1"></span>
                                            <!--end::Currency-->
                                            <!--begin::Value-->
                                            <span class="fs-2hx fw-bold text-gray-800 me-2 lh-1 ls-n2">74</span>
                                            <!--end::Value-->
                                        </div>
                                        <div class="d-flex mb-2">
                                            <!--begin::Label-->
                                            <span class="badge badge-light-success fs-base">
                                                <!--begin::Svg Icon | path: icons/duotune/arrows/arr067.svg-->
                                                <span class="svg-icon svg-icon-7 svg-icon-success ms-n1">
                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path opacity="0.5" d="M13 9.59998V21C13 21.6 12.6 22 12 22C11.4 22 11 21.6 11 21V9.59998H13Z" fill="currentColor" />
                                                        <path d="M5.7071 7.89291C5.07714 8.52288 5.52331 9.60002 6.41421 9.60002H17.5858C18.4767 9.60002 18.9229 8.52288 18.2929 7.89291L12.7 2.3C12.3 1.9 11.7 1.9 11.3 2.3L5.7071 7.89291Z" fill="currentColor" />
                                                    </svg>
                                                </span>
                                                <!--end::Svg Icon-->4.5%
                                            </span>
                                            <!--end::Label-->
                                        </div>
                                        <!--end::Statistics-->
                                        <!--begin::Description-->
                                        <span class="fs-6 fw-semibold text-gray-400">vs previous 13 day(s)</span>
                                        <!--end::Description-->
                                    </div>
                                    <!--end::Item-->
                                </div>
                                <!--end::Items-->
                                <!--begin::Items-->
                                <div class="d-flex flex-wrap d-grid gap-5 px-9 mb-5">
                                    <!--begin::Item-->
                                    <div class="me-md-2">
                                        <!--begin::Title-->
                                        <span class="fs-3 fw-semibold text-gray-600">Avg. Order Value</span>
                                        <!--end::Title-->
                                        <!--begin::Statistics-->
                                        <div class="d-flex mb-2">
                                            <span class="fs-4 fw-semibold text-gray-400 me-1">Rp.</span>
                                            <span class="fs-2hx fw-bold text-gray-800 me-2 lh-1 ls-n2">133.298</span>
                                        </div>
                                        <!--end::Statistics-->
                                        <div class="d-flex mb-2">
                                            <!--begin::Label-->
                                            <span class="badge badge-light-success fs-base">
                                                <!--begin::Svg Icon | path: icons/duotune/arrows/arr067.svg-->
                                                <span class="svg-icon svg-icon-7 svg-icon-success ms-n1">
                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path opacity="0.5" d="M13 9.59998V21C13 21.6 12.6 22 12 22C11.4 22 11 21.6 11 21V9.59998H13Z" fill="currentColor" />
                                                        <path d="M5.7071 7.89291C5.07714 8.52288 5.52331 9.60002 6.41421 9.60002H17.5858C18.4767 9.60002 18.9229 8.52288 18.2929 7.89291L12.7 2.3C12.3 1.9 11.7 1.9 11.3 2.3L5.7071 7.89291Z" fill="currentColor" />
                                                    </svg>
                                                </span>
                                                <!--end::Svg Icon-->10.5%
                                            </span>
                                            <!--end::Label-->
                                        </div>
                                        <!--begin::Description-->
                                        <span class="fs-6 fw-semibold text-gray-400">vs previous 13 day(s)</span>
                                        <!--end::Description-->
                                    </div>
                                    <!--end::Item-->
                                    <!--begin::Item-->
                                    <div class="px-5 ps-md-10 pe-md-7 me-md-5">
                                        <!--begin::Title-->
                                        <span class="fs-3 fw-semibold text-gray-600">Avg. Order/day</span>
                                        <!--end::Title-->
                                        <!--begin::Statistics-->
                                        <div class="d-flex mb-2">
                                            <span class="fs-4 fw-semibold text-gray-400 me-1"></span>
                                            <span class="fs-2hx fw-bold text-gray-800 me-2 lh-1 ls-n2">5</span>
                                        </div>
                                        <!--end::Statistics-->
                                        <div class="d-flex mb-2">
                                            <!--begin::Label-->
                                            <span class="badge badge-light-danger fs-base">
                                                <!--begin::Svg Icon | path: icons/duotune/arrows/arr065.svg-->
                                                <span class="svg-icon svg-icon-5 svg-icon-danger ms-n1">
                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <rect opacity="0.5" x="11" y="18" width="13" height="2" rx="1" transform="rotate(-90 11 18)" fill="currentColor"></rect>
                                                        <path d="M11.4343 15.4343L7.25 11.25C6.83579 10.8358 6.16421 10.8358 5.75 11.25C5.33579 11.6642 5.33579 12.3358 5.75 12.75L11.2929 18.2929C11.6834 18.6834 12.3166 18.6834 12.7071 18.2929L18.25 12.75C18.6642 12.3358 18.6642 11.6642 18.25 11.25C17.8358 10.8358 17.1642 10.8358 16.75 11.25L12.5657 15.4343C12.2533 15.7467 11.7467 15.7467 11.4343 15.4343Z" fill="currentColor"></path>
                                                    </svg>
                                                </span>
                                                <!--end::Svg Icon-->9.4%
                                            </span>
                                            <!--end::Label-->
                                        </div>
                                        <!--begin::Description-->
                                        <span class="fs-6 fw-semibold text-gray-400">vs previous 13 day(s)</span>
                                        <!--end::Description-->
                                    </div>
                                    <!--end::Item-->
                                    <!--begin::Item-->
                                    <div class="m-0">
                                        <!--begin::Title-->
                                        <span class="fs-3 fw-semibold text-gray-600">Avg. Item Discount</span>
                                        <!--end::Title-->
                                        <!--begin::Statistics-->
                                        <div class="d-flex mb-2">
                                            <!--begin::Currency-->
                                            <span class="fs-4 fw-semibold text-gray-400 align-self-start me-1"></span>
                                            <!--end::Currency-->
                                            <!--begin::Value-->
                                            <span class="fs-2hx fw-bold text-gray-800 me-2 lh-1 ls-n2">0%</span>
                                            <!--end::Value-->
                                        </div>
                                        <div class="d-flex mb-2">
                                            <!--begin::Label-->
                                            <span class="badge badge-light-success fs-base">
                                                <!--begin::Svg Icon | path: icons/duotune/arrows/arr067.svg-->
                                                <span class="svg-icon svg-icon-7 svg-icon-success ms-n1">
                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path opacity="0.5" d="M13 9.59998V21C13 21.6 12.6 22 12 22C11.4 22 11 21.6 11 21V9.59998H13Z" fill="currentColor" />
                                                        <path d="M5.7071 7.89291C5.07714 8.52288 5.52331 9.60002 6.41421 9.60002H17.5858C18.4767 9.60002 18.9229 8.52288 18.2929 7.89291L12.7 2.3C12.3 1.9 11.7 1.9 11.3 2.3L5.7071 7.89291Z" fill="currentColor" />
                                                    </svg>
                                                </span>
                                                <!--end::Svg Icon-->4.5%
                                            </span>
                                            <!--end::Label-->
                                        </div>
                                        <!--end::Statistics-->
                                        <!--begin::Description-->
                                        <span class="fs-6 fw-semibold text-gray-400">vs previous 13 day(s)</span>
                                        <!--end::Description-->
                                    </div>
                                    <!--end::Item-->
                                </div>
                                <!--end::Items-->
                                <!--begin::Chart-->
                                <div id="kt_apexcharts_3" class="min-h-auto ps-4 pe-6 mb-5" data-kt-chart-info="Revenue" style="height: 300px"></div>
                                <!--end::Chart-->

                                <div class="card-body py-3">
                                    <!--begin::Table container-->
                                    <div class="table-responsive">
                                        <!--begin::Table-->
                                        <table class="table align-middle gs-0 gy-4">
                                            <!--begin::Table head-->
                                            <thead>
                                                <tr class="fw-bold text-muted bg-light">
                                                    <th class="ps-4 rounded-start"></th>
                                                    <th>ORDERS</th>
                                                    <th>ITEMS SOLD</th>
                                                    <th>SUB TOTAL</th>
                                                    <th>SHIPPING</th>
                                                    <th>SELLER VOUCHER</th>
                                                    <th>CHANNEL REBATE</th>
                                                    <th class="rounded-end">GROSS SALES</th>
                                                </tr>
                                            </thead>
                                            <!--end::Table head-->
                                            <!--begin::Table body-->
                                            <tbody>
                                                <tr class="fs-3">
                                                    <td class="ps-4">
                                                        <div class="d-flex align-items-center">
                                                            <div class="symbol symbol-50px me-5">
                                                                <span class="symbol-label bg-light">
                                                                    <img src="http://localhost/ims_project/assets/metronic/media/marketplace/tokopedia-icon.png" class="h-75 align-self-end" alt="">
                                                                </span>
                                                            </div>
                                                            <div class="d-flex justify-content-start flex-column">
                                                                <span class="text-muted fw-bold d-block">TOKOPEDIA</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>70</td>
                                                    <td>74</td>
                                                    <td>Rp. 9.330.900</td>
                                                    <td>Rp. 0</td>
                                                    <td>Rp. 0</td>
                                                    <td>Rp. 9.330.900</td>
                                                    <td>Rp. 9.330.900</td>
                                                </tr>
                                                <tr class="py-5 fw-bolder border-bottom border-gray-500 fs-3"></tr>
                                                <tr class="py-5 fw-bolder border-bottom border-gray-500 fs-3">
                                                    <td class="ps-4">TOTAL</td>
                                                    <td>70</td>
                                                    <td>74</td>
                                                    <td>Rp. 9.330.900</td>
                                                    <td>Rp. 0</td>
                                                    <td>Rp. 0</td>
                                                    <td>Rp. 9.330.900</td>
                                                    <td>Rp. 9.330.900</td>
                                                </tr>
                                            </tbody>
                                            <!--end::Table body-->
                                        </table>
                                        <!--end::Table-->
                                    </div>
                                </div>
                                <!--end::Table container-->
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Chart widget 20-->
                    </div>
                    <!--end::Col-->
                    <!--begin::Col-->
                    <div class="col-xxl-12">
                        <div class="card shadow-sm card-flush mb-5 mb-xl-1">
                            <!--begin::Header-->
                            <div class="card-header border-0 pt-5">
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bold fs-3 mb-1">Total SKUs</span>
                                </h3>
                            </div>
                            <!--end::Header-->
                            <!--begin::Card body-->
                            <div class="card-body d-flex justify-content-between flex-column pb-0 px-0 pt-1">
                                <!--begin::Items-->
                                <div class="d-flex flex-wrap d-grid gap-5 px-9 mb-5">
                                    <!--begin::Item-->
                                    <div class="me-md-2">
                                        <!--begin::Statistics-->
                                        <div class="d-flex mb-2">
                                            <span class="fs-2hx fw-bold text-gray-800 me-2 lh-1 ls-n2">1223</span>
                                            <span class="fs-4 fw-semibold text-gray-400 me-1">All</span>
                                        </div>
                                        <!--end::Statistics-->
                                    </div>
                                    <!--end::Item-->
                                    <!--begin::Item-->
                                    <div class="px-5 ps-md-10 pe-md-7 me-md-5">
                                        <!--begin::Statistics-->
                                        <div class="d-flex mb-2">
                                            <span class="fs-2hx fw-bold text-gray-800 me-2 lh-1 ls-n2">224</span>
                                            <span class="fs-4 fw-semibold text-success me-1">In Stok</span>
                                        </div>
                                        <!--end::Statistics-->
                                    </div>
                                    <!--end::Item-->
                                    <!--begin::Item-->
                                    <div class="m-0">
                                        <!--begin::Statistics-->
                                        <div class="d-flex mb-2">
                                            <!--begin::Value-->
                                            <span class="fs-2hx fw-bold text-gray-800 me-2 lh-1 ls-n2">999</span>
                                            <!--end::Value-->
                                            <!--begin::Currency-->
                                            <span class="fs-4 fw-semibold text-gray-400 align-self-start me-1">Out of
                                                Stock</span>
                                            <!--end::Currency-->
                                        </div>
                                        <!--end::Statistics-->
                                    </div>
                                    <!--end::Item-->
                                </div>
                                <!--end::Items-->
                                <div class="card-body py-3">
                                    <!--begin::Table container-->
                                    <div class="table-responsive">
                                        <!--begin::Table-->
                                        <table class="table align-middle gs-0 gy-4">
                                            <!--begin::Table head-->
                                            <thead>
                                                <tr class="fw-bold text-muted bg-light">
                                                    <th class="ps-4 rounded-start"></th>
                                                    <th>ALL</th>
                                                    <th>LIVE</th>
                                                    <th>QC PENDING</th>
                                                    <th>QC REJECTED</th>
                                                    <th>INACTIVE</th>
                                                    <th>PENDING ACTION</th>
                                                    <th>MISSING IMAGE</th>
                                                    <th class="rounded-end">OUT OF STOCK</th>
                                                </tr>
                                            </thead>
                                            <!--end::Table head-->
                                            <!--begin::Table body-->
                                            <tbody>
                                                <tr class="fs-3">
                                                    <td class="ps-4">
                                                        <div class="d-flex align-items-center">
                                                            <div class="symbol symbol-50px me-5">
                                                                <span class="symbol-label bg-light">
                                                                    <img src="http://localhost/ims_project/assets/metronic/media/marketplace/tokopedia-icon.png" class="h-75 align-self-end" alt="">
                                                                </span>
                                                            </div>
                                                            <div class="d-flex justify-content-start flex-column">
                                                                <span class="text-muted fw-bold d-block">TOKOPEDIA</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="text-success">1223</td>
                                                    <td class="text-success">120</td>
                                                    <td>0</td>
                                                    <td>0</td>
                                                    <td class="text-success">289</td>
                                                    <td class="text-success">42</td>
                                                    <td class="text-success">875</td>
                                                    <td class="text-success">999</td>
                                                </tr>
                                            </tbody>
                                            <!--end::Table body-->
                                        </table>
                                        <!--end::Table-->
                                    </div>
                                    <div class="separator mb-10"></div>
                                </div>
                            </div>
                            <!--end::Card body-->
                        </div>
                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Row-->
            </div>
            <!--end::Content container-->
        </div>
        <!--end::Content-->
    </div>
    <!--end::Content wrapper-->
</div>