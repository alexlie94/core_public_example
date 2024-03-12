<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<style type="text/css">
    fieldset {
        margin-bottom: 1em;
        border: 1px solid #888;
        border-right: 1px solid #666;
        border-bottom: 1px solid #666;
    }
</style>

<div class="d-flex flex-column flex-column-fluid">

    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-fluid">

            <div class="row mt-3">
                <div class="card shadow-sm">
                    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6" style="margin-bottom: -30px;">
                        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
                            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                                <h1
                                    class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                                    <?= $titlePage ?>
                                </h1>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <form id="formSearch" class="form formSearch" autocomplete="off">
                            <div class="row mb-10 mt-5">

                                <!--begin::Wrapper-->
                                <div class="d-flex align-items-center">
                                    <!--begin::Label-->
                                    <div class="fw-semibold me-5">
                                        <label class="fs-3">Warehouse Location</label>
                                    </div>
                                    <!--end::Label-->
                                    <!--begin::Checkboxes-->
                                    <div class="d-flex">
                                        <!--begin::Checkbox-->
                                        <select class="form-select" id="warehouse" name="warehouse"
                                            data-control="select2" data-placeholder="Select an option"
                                            data-type="select">
                                            <option value="0">Combined</option>
                                            <?php foreach ($warehouse_id as $res) { ?>
                                                <option value="<?= $res['id'] ?>">
                                                    <?= $res['warehouse_name'] ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                        <!--end::Checkbox-->
                                    </div>
                                    <!--end::Checkboxes-->
                                </div>
                                <!--end::Wrapper-->
                            </div>
                        </form>

                        <div class="row mb-5">
                            <div class="d-flex flex-end gap-2 gap-lg-3">
                                <button type="button" id="btnSearchReset"
                                    class="btn btn-outline btn-outline-dashed btn-outline-danger btn-active-light-danger hover-scale btn-sm fw-bold">
                                    <i class="fa-solid fa-refresh fs-4 me-2"></i>Reset</button>
                                <button type="button" id="search" name="search"
                                    data-url="<?= base_url('inventory_warehouse/summary'); ?>"
                                    class="btn btn-outline btn-outline-dashed btn-outline-primary btn-active-light-primary hover-scale btn-sm fw-bold">
                                    <i class="fa-solid fa-search fs-4 me-2"></i>Search</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <!--begin::Row-->
                        <div class="row gy-5 g-xl-10">

                            <!--begin::Col-->
                            <div class="col-xl-4">
                                <!--begin::List widget 11-->
                                <div class="card card-flush h-xl-100 bg-success">
                                    <!--begin::Header-->
                                    <div class="card-header pt-7 mb-3">
                                        <!--begin::Title-->
                                        <h3 class="card-title align-items-start flex-column">
                                            <span class="card-label fw-bold text-white">Receiving</span>
                                            <span class="text-white mt-1 fw-semibold fs-6">Last Update
                                                <?= isset($lastUpdatedReceiving['updated_at']) ? $lastUpdatedReceiving['updated_at'] : "dd-mmm-yyyy
                                                hh-mm-ss" ?>
                                            </span>
                                        </h3>
                                        <!--end::Title-->
                                        <!--begin::Toolbar-->
                                        <div class="card-toolbar">
                                            <button type="button" id="btnReceiving" class="btn btn-sm btn-light"
                                                data-type="modal"
                                                data-url="<?= base_url('inventory_warehouse/receiving') ?>"
                                                data-fullscreenmodal=1>
                                                Preview
                                            </button>
                                            <button type="button" id="btnReceivingLog" class="btn btn-sm btn-light ms-2"
                                                data-type="modal"
                                                data-url="<?= base_url('inventory_warehouse/receiving_log') ?>"
                                                data-fullscreenmodal=1>
                                                Receiving Log
                                            </button>
                                        </div>
                                        <!--end::Toolbar-->
                                    </div>
                                    <!--end::Header-->
                                    <!--begin::Body-->
                                    <div class="card-body pt-4">
                                        <!--begin::Item-->
                                        <div class="d-flex flex-stack">
                                            <!--begin::Section-->
                                            <div class="d-flex align-items-center me-5">
                                                <!--begin::Symbol-->
                                                <div class="symbol symbol-40px me-4">
                                                    <span class="symbol-label">
                                                        <i class="bi bi-clock fs-1 p-0 text-black"></i>
                                                    </span>
                                                </div>
                                                <!--end::Symbol-->
                                                <!--begin::Content-->
                                                <div class="me-5">
                                                    <!--begin::Title-->
                                                    <span class="text-white fw-bold fs-6">In
                                                        Progress</span>
                                                    <!--end::Title-->
                                                </div>
                                                <!--end::Content-->
                                            </div>
                                            <!--end::Section-->
                                            <!--begin::Wrapper-->
                                            <div class="text-white fw-bold fs-7 text-end">
                                                <!--begin::Number-->
                                                <span class="text-white fw-bold fs-6 d-block" id="receive_inpro"></span>
                                                <!--end::Number-->Pieces
                                            </div>
                                            <!--end::Wrapper-->
                                        </div>
                                        <!--end::Item-->
                                        <!--begin::Separator-->
                                        <div class="separator separator-dashed my-5"></div>
                                        <!--end::Separator-->
                                        <!--begin::Item-->
                                        <div class="d-flex flex-stack">
                                            <!--begin::Section-->
                                            <div class="d-flex align-items-center me-5">
                                                <!--begin::Symbol-->
                                                <div class="symbol symbol-40px me-4">
                                                    <span class="symbol-label">
                                                        <i class="bi bi-x-circle fs-1 p-0 text-black"></i>
                                                    </span>
                                                </div>
                                                <!--end::Symbol-->
                                                <!--begin::Content-->
                                                <div class="me-5">
                                                    <!--begin::Title-->
                                                    <span class="text-white fw-bold fs-6">Closed</span>
                                                    <!--end::Title-->
                                                </div>
                                                <!--end::Content-->
                                            </div>
                                            <!--end::Section-->
                                            <!--begin::Wrapper-->
                                            <div class="text-white fw-bold fs-7 text-end">
                                                <!--begin::Number-->
                                                <span class="text-white fw-bold fs-6 d-block" id="receive_close"></span>
                                                <!--end::Number-->Pieces
                                            </div>
                                            <!--end::Wrapper-->
                                        </div>
                                        <!--end::Item-->
                                        <!--begin::Separator-->
                                        <div class="separator separator-dashed my-5"></div>
                                        <!--end::Separator-->
                                        <!--begin::Item-->
                                        <div class="d-flex flex-stack">
                                            <!--begin::Section-->
                                            <div class="d-flex align-items-center me-5">
                                                <!--begin::Content-->
                                                <div class="me-5">
                                                    <!--begin::Title-->
                                                    <span href="#" class="text-white fw-bold fs-6"></span>
                                                    <!--end::Title-->
                                                </div>
                                                <!--end::Content-->
                                            </div>
                                            <!--end::Section-->
                                            <!--begin::Wrapper-->
                                            <div class="text-white fw-bold fs-7 text-end">
                                                <!--begin::Number-->
                                                <span class="text-white fw-bold fs-6 d-block"></span>
                                                <!--end::Number-->
                                            </div>
                                            <!--end::Wrapper-->
                                        </div>
                                        <!--end::Item-->
                                    </div>
                                    <!--end::Body-->
                                </div>
                                <!--end::List widget 11-->
                            </div>
                            <!--end::Col-->

                            <!--begin::Col-->
                            <div class="col-xl-4">
                                <!--begin::List widget 11-->
                                <div class="card card-flush h-xl-100 bg-warning">
                                    <!--begin::Header-->
                                    <div class="card-header pt-7 mb-3">
                                        <!--begin::Title-->
                                        <h3 class="card-title align-items-start flex-column">
                                            <span class="card-label fw-bold text-white">Put Away</span>
                                            <span class="text-white mt-1 fw-semibold fs-6">Last Update
                                                <?= isset($lastUpdatedPutaway['updated_at']) ? $lastUpdatedPutaway['updated_at'] : "dd-mmm-yyyy
                                                hh-mm-ss" ?>
                                            </span>
                                        </h3>
                                        <!--end::Title-->
                                        <!--begin::Toolbar-->
                                        <div class="card-toolbar">
                                            <button type="button" id="btnPutaway" class="btn btn-sm btn-light"
                                                data-type="modal"
                                                data-url="<?= base_url('inventory_warehouse/putaway') ?>"
                                                data-fullscreenmodal=1>
                                                Preview
                                            </button>
                                            <button type="button" id="btnPutawayLog" class="btn btn-sm btn-light ms-2"
                                                data-type="modal"
                                                data-url="<?= base_url('inventory_warehouse/putaway_log') ?>"
                                                data-fullscreenmodal=1>
                                                Put Away Log
                                            </button>
                                        </div>
                                        <!--end::Toolbar-->
                                    </div>
                                    <!--end::Header-->
                                    <!--begin::Body-->
                                    <div class="card-body pt-4">
                                        <!--begin::Item-->
                                        <div class="d-flex flex-stack">
                                            <!--begin::Section-->
                                            <div class="d-flex align-items-center me-5">
                                                <!--begin::Symbol-->
                                                <div class="symbol symbol-40px me-4">
                                                    <span class="symbol-label">
                                                        <i class="bi bi-clock fs-1 p-0 text-black"></i>
                                                    </span>
                                                </div>
                                                <!--end::Symbol-->
                                                <!--begin::Content-->
                                                <div class="me-5">
                                                    <!--begin::Title-->
                                                    <span class="text-white fw-bold fs-6">In
                                                        Progress</span>
                                                    <!--end::Title-->
                                                </div>
                                                <!--end::Content-->
                                            </div>
                                            <!--end::Section-->
                                            <!--begin::Wrapper-->
                                            <div class="text-white fw-bold fs-7 text-end">
                                                <!--begin::Number-->
                                                <span class="text-white fw-bold fs-6 d-block" id="put_prog"></span>
                                                <!--end::Number-->Pieces
                                            </div>
                                            <!--end::Wrapper-->
                                        </div>
                                        <!--end::Item-->
                                        <!--begin::Separator-->
                                        <div class="separator separator-dashed my-5"></div>
                                        <!--end::Separator-->
                                        <!--begin::Item-->
                                        <div class="d-flex flex-stack">
                                            <!--begin::Section-->
                                            <div class="d-flex align-items-center me-5">
                                                <!--begin::Symbol-->
                                                <div class="symbol symbol-40px me-4">
                                                    <span class="symbol-label">
                                                        <i class="bi bi-x-circle fs-1 p-0 text-black"></i>
                                                    </span>
                                                </div>
                                                <!--end::Symbol-->
                                                <!--begin::Content-->
                                                <div class="me-5">
                                                    <!--begin::Title-->
                                                    <span class="text-white fw-bold fs-6">Closed</span>
                                                    <!--end::Title-->
                                                </div>
                                                <!--end::Content-->
                                            </div>
                                            <!--end::Section-->
                                            <!--begin::Wrapper-->
                                            <div class="text-white fw-bold fs-7 text-end">
                                                <!--begin::Number-->
                                                <span class="text-white fw-bold fs-6 d-block" id="put_clos"></span>
                                                <!--end::Number-->Pieces
                                            </div>
                                            <!--end::Wrapper-->
                                        </div>
                                        <!--end::Item-->
                                        <!--begin::Separator-->
                                        <div class="separator separator-dashed my-5"></div>
                                        <!--end::Separator-->
                                        <!--begin::Item-->
                                        <div class="d-flex flex-stack">
                                            <!--begin::Section-->
                                            <div class="d-flex align-items-center me-5">
                                                <!--begin::Content-->
                                                <div class="me-5">
                                                    <!--begin::Title-->
                                                    <span href="#" class="text-white fw-bold fs-6"></span>
                                                    <!--end::Title-->
                                                </div>
                                                <!--end::Content-->
                                            </div>
                                            <!--end::Section-->
                                            <!--begin::Wrapper-->
                                            <div class="text-white fw-bold fs-7 text-end">
                                                <!--begin::Number-->
                                                <span class="text-white fw-bold fs-6 d-block"></span>
                                                <!--end::Number-->
                                            </div>
                                            <!--end::Wrapper-->
                                        </div>
                                        <!--end::Item-->
                                    </div>
                                    <!--end::Body-->
                                </div>
                                <!--end::List widget 11-->
                            </div>
                            <!--end::Col-->

                            <!--begin::Col-->
                            <div class="col-xl-4">
                                <!--begin::List widget 11-->
                                <div class="card card-flush h-xl-100 bg-info">
                                    <!--begin::Header-->
                                    <div class="card-header pt-7 mb-3">
                                        <!--begin::Title-->
                                        <h3 class="card-title align-items-start flex-column">
                                            <span class="card-label fw-bold text-white">Storage</span>
                                            <span class="text-white mt-1 fw-semibold fs-6">Last Update
                                                <?= isset($lastUpdatedStorage['updated_at']) ? $lastUpdatedStorage['updated_at'] : "dd-mmm-yyyy
                                                hh-mm-ss" ?>
                                            </span>
                                        </h3>
                                        <!--end::Title-->
                                        <!--begin::Toolbar-->
                                        <div class="card-toolbar">
                                            <button type="button" id="btnStorage" class="btn btn-sm btn-light"
                                                data-type="modal"
                                                data-url="<?= base_url('inventory_warehouse/storage') ?>"
                                                data-fullscreenmodal=1>
                                                Preview
                                            </button>
                                            <button type="button" id="btnStorageLog" class="btn btn-sm btn-light ms-2"
                                                data-type="modal"
                                                data-url="<?= base_url('inventory_warehouse/storage_log') ?>"
                                                data-fullscreenmodal=1>
                                                Storage Log
                                            </button>
                                        </div>
                                        <!--end::Toolbar-->
                                    </div>
                                    <!--end::Header-->
                                    <!--begin::Body-->
                                    <div class="card-body pt-4">
                                        <!--begin::Item-->
                                        <div class="d-flex flex-stack">
                                            <!--begin::Section-->
                                            <div class="d-flex align-items-center me-5">
                                                <!--begin::Symbol-->
                                                <div class="symbol symbol-40px me-4">
                                                    <span class="symbol-label">
                                                        <i class="bi bi-boxes fs-1 p-0 text-black"></i>
                                                    </span>
                                                </div>
                                                <!--end::Symbol-->
                                                <!--begin::Content-->
                                                <div class="me-5">
                                                    <!--begin::Title-->
                                                    <span href="#" class="text-white fw-bold fs-6">Total</span>
                                                    <!--end::Title-->
                                                </div>
                                                <!--end::Content-->
                                            </div>
                                            <!--end::Section-->
                                            <!--begin::Wrapper-->
                                            <div class="text-white fw-bold fs-7 text-end">
                                                <!--begin::Number-->
                                                <span class="text-white fw-bold fs-6 d-block" id="stora_tot"></span>
                                                <!--end::Number-->Pieces
                                            </div>
                                            <!--end::Wrapper-->
                                        </div>
                                        <!--end::Item-->
                                        <!--begin::Separator-->
                                        <div class="separator separator-dashed my-5"></div>
                                        <!--end::Separator-->
                                    </div>
                                    <!--end::Body-->
                                </div>
                                <!--end::List widget 11-->
                            </div>
                            <!--end::Col-->

                            <!--begin::Col-->
                            <div class="col-xl-4">
                                <!--begin::List widget 11-->
                                <div class="card card-flush h-xl-100" style="background-color: #a020f0;">
                                    <!--begin::Header-->
                                    <div class="card-header pt-7 mb-3">
                                        <!--begin::Title-->
                                        <h3 class="card-title align-items-start flex-column">
                                            <span class="card-label fw-bold text-white">Picking</span>
                                            <span class="text-white mt-1 fw-semibold fs-6">Last Update
                                                <?= isset($lastUpdatedPicking['updated_at']) ? $lastUpdatedPicking['updated_at'] : "dd-mmm-yyyy
                                                hh-mm-ss" ?>
                                            </span>
                                        </h3>
                                        <!--end::Title-->
                                        <!--begin::Toolbar-->
                                        <div class="card-toolbar">
                                            <button type="button" id="btnPicking" class="btn btn-sm btn-light"
                                                data-type="modal"
                                                data-url="<?= base_url('inventory_warehouse/picking') ?>"
                                                data-fullscreenmodal=1>
                                                Preview
                                            </button>
                                            <button type="button" id="btnPickingLog" class="btn btn-sm btn-light ms-2"
                                                data-type="modal"
                                                data-url="<?= base_url('inventory_warehouse/picking_log') ?>"
                                                data-fullscreenmodal=1>
                                                Picking Log
                                            </button>
                                        </div>
                                        <!--end::Toolbar-->
                                    </div>
                                    <!--end::Header-->
                                    <!--begin::Body-->
                                    <div class="card-body pt-4">
                                        <!--begin::Item-->
                                        <div class="d-flex flex-stack">
                                            <!--begin::Section-->
                                            <div class="d-flex align-items-center me-5">
                                                <!--begin::Symbol-->
                                                <div class="symbol symbol-40px me-4">
                                                    <span class="symbol-label">
                                                        <i class="bi bi-clock fs-1 p-0 text-black"></i>
                                                    </span>
                                                </div>
                                                <!--end::Symbol-->
                                                <!--begin::Content-->
                                                <div class="me-5">
                                                    <!--begin::Title-->
                                                    <span class="text-white fw-bold fs-6">In
                                                        Progress</span>
                                                    <!--end::Title-->
                                                </div>
                                                <!--end::Content-->
                                            </div>
                                            <!--end::Section-->
                                            <!--begin::Wrapper-->
                                            <div class="text-white fw-bold fs-7 text-end">
                                                <!--begin::Number-->
                                                <span class="text-white fw-bold fs-6 d-block" id="pick_prog"></span>
                                                <!--end::Number-->Pieces
                                            </div>
                                            <!--end::Wrapper-->
                                        </div>
                                        <!--end::Item-->
                                        <!--begin::Separator-->
                                        <div class="separator separator-dashed my-5"></div>
                                        <!--end::Separator-->
                                        <!--begin::Item-->
                                        <div class="d-flex flex-stack">
                                            <!--begin::Section-->
                                            <div class="d-flex align-items-center me-5">
                                                <!--begin::Symbol-->
                                                <div class="symbol symbol-40px me-4">
                                                    <span class="symbol-label">
                                                        <i class="bi bi-x-circle fs-1 p-0 text-black"></i>
                                                    </span>
                                                </div>
                                                <!--end::Symbol-->
                                                <!--begin::Content-->
                                                <div class="me-5">
                                                    <!--begin::Title-->
                                                    <span class="text-white fw-bold fs-6">Closed</span>
                                                    <!--end::Title-->
                                                </div>
                                                <!--end::Content-->
                                            </div>
                                            <!--end::Section-->
                                            <!--begin::Wrapper-->
                                            <div class="text-white fw-bold fs-7 text-end">
                                                <!--begin::Number-->
                                                <span class="text-white fw-bold fs-6 d-block" id="pick_clos"></span>
                                                <!--end::Number-->Pieces
                                            </div>
                                            <!--end::Wrapper-->
                                        </div>
                                        <!--end::Item-->
                                        <!--begin::Separator-->
                                        <div class="separator separator-dashed my-5"></div>
                                        <!--end::Separator-->
                                        <!--begin::Item-->
                                        <div class="d-flex flex-stack">
                                            <!--begin::Section-->
                                            <div class="d-flex align-items-center me-5">
                                                <!--begin::Content-->
                                                <div class="me-5">
                                                    <!--begin::Title-->
                                                    <span href="#" class="text-white fw-bold fs-6"></span>
                                                    <!--end::Title-->
                                                </div>
                                                <!--end::Content-->
                                            </div>
                                            <!--end::Section-->
                                            <!--begin::Wrapper-->
                                            <div class="text-white fw-bold fs-7 text-end">
                                                <!--begin::Number-->
                                                <span class="text-white fw-bold fs-6 d-block"></span>
                                                <!--end::Number-->
                                            </div>
                                            <!--end::Wrapper-->
                                        </div>
                                        <!--end::Item-->
                                    </div>
                                    <!--end::Body-->
                                </div>
                                <!--end::List widget 11-->
                            </div>
                            <!--end::Col-->

                            <!--begin::Col-->
                            <div class="col-xl-4">
                                <!--begin::List widget 11-->
                                <div class="card card-flush h-xl-100" style="background-color: #E75480;">
                                    <!--begin::Header-->
                                    <div class="card-header pt-7 mb-3">
                                        <!--begin::Title-->
                                        <h3 class="card-title align-items-start flex-column">
                                            <span class="card-label fw-bold text-white">Packing</span>
                                            <span class="text-white mt-1 fw-semibold fs-6">Last Update
                                                <?= isset($lastUpdatedPacking['updated_at']) ? $lastUpdatedPacking['updated_at'] : "dd-mmm-yyyy
                                                hh-mm-ss" ?>
                                            </span>
                                        </h3>
                                        <!--end::Title-->
                                        <!--begin::Toolbar-->
                                        <div class="card-toolbar">
                                            <button type="button" id="btnPacking" class="btn btn-sm btn-light"
                                                data-type="modal"
                                                data-url="<?= base_url('inventory_warehouse/packing') ?>"
                                                data-fullscreenmodal=1>
                                                Preview
                                            </button>
                                            <button type="button" id="btnPackingLog" class="btn btn-sm btn-light ms-2"
                                                data-type="modal"
                                                data-url="<?= base_url('inventory_warehouse/packing_log') ?>"
                                                data-fullscreenmodal=1>
                                                Packing Log
                                            </button>
                                        </div>
                                        <!--end::Toolbar-->
                                    </div>
                                    <!--end::Header-->
                                    <!--begin::Body-->
                                    <div class="card-body pt-4">
                                        <!--begin::Item-->
                                        <div class="d-flex flex-stack">
                                            <!--begin::Section-->
                                            <div class="d-flex align-items-center me-5">
                                                <!--begin::Symbol-->
                                                <div class="symbol symbol-40px me-4">
                                                    <span class="symbol-label">
                                                        <i class="bi bi-clock fs-1 p-0 text-black"></i>
                                                    </span>
                                                </div>
                                                <!--end::Symbol-->
                                                <!--begin::Content-->
                                                <div class="me-5">
                                                    <!--begin::Title-->
                                                    <span class="text-white fw-bold fs-6">In
                                                        Progress</span>
                                                    <!--end::Title-->
                                                </div>
                                                <!--end::Content-->
                                            </div>
                                            <!--end::Section-->
                                            <!--begin::Wrapper-->
                                            <div class="text-white fw-bold fs-7 text-end">
                                                <!--begin::Number-->
                                                <span class="text-white fw-bold fs-6 d-block" id="pack_prog"></span>
                                                <!--end::Number-->Pieces
                                            </div>
                                            <!--end::Wrapper-->
                                        </div>
                                        <!--end::Item-->
                                        <!--begin::Separator-->
                                        <div class="separator separator-dashed my-5"></div>
                                        <!--end::Separator-->
                                        <!--begin::Item-->
                                        <div class="d-flex flex-stack">
                                            <!--begin::Section-->
                                            <div class="d-flex align-items-center me-5">
                                                <!--begin::Symbol-->
                                                <div class="symbol symbol-40px me-4">
                                                    <span class="symbol-label">
                                                        <i class="bi bi-x-circle fs-1 p-0 text-black"></i>
                                                    </span>
                                                </div>
                                                <!--end::Symbol-->
                                                <!--begin::Content-->
                                                <div class="me-5">
                                                    <!--begin::Title-->
                                                    <span class="text-white fw-bold fs-6">Closed</span>
                                                    <!--end::Title-->
                                                </div>
                                                <!--end::Content-->
                                            </div>
                                            <!--end::Section-->
                                            <!--begin::Wrapper-->
                                            <div class="text-white fw-bold fs-7 text-end">
                                                <!--begin::Number-->
                                                <span class="text-white fw-bold fs-6 d-block" id="pack_clos"></span>
                                                <!--end::Number-->Pieces
                                            </div>
                                            <!--end::Wrapper-->
                                        </div>
                                        <!--end::Item-->
                                        <!--begin::Separator-->
                                        <div class="separator separator-dashed my-5"></div>
                                        <!--end::Separator-->
                                        <!--begin::Item-->
                                        <div class="d-flex flex-stack">
                                            <!--begin::Section-->
                                            <div class="d-flex align-items-center me-5">
                                                <!--begin::Content-->
                                                <div class="me-5">
                                                    <!--begin::Title-->
                                                    <span href="#" class="text-white fw-bold fs-6"></span>
                                                    <!--end::Title-->
                                                </div>
                                                <!--end::Content-->
                                            </div>
                                            <!--end::Section-->
                                            <!--begin::Wrapper-->
                                            <div class="text-white fw-bold fs-7 text-end">
                                                <!--begin::Number-->
                                                <span class="text-white fw-bold fs-6 d-block"></span>
                                                <!--end::Number-->
                                            </div>
                                            <!--end::Wrapper-->
                                        </div>
                                        <!--end::Item-->
                                    </div>
                                    <!--end::Body-->
                                </div>
                                <!--end::List widget 11-->
                            </div>
                            <!--end::Col-->

                            <!--begin::Col-->
                            <div class="col-xl-4">
                                <!--begin::List widget 11-->
                                <div class="card card-flush h-xl-100" style="background-color: #29465B;">
                                    <!--begin::Header-->
                                    <div class="card-header pt-7 mb-3">
                                        <!--begin::Title-->
                                        <h3 class="card-title align-items-start flex-column">
                                            <span class="card-label fw-bold text-white">Shipping</span>
                                            <span class="text-white mt-1 fw-semibold fs-6">Last Update
                                                <?= isset($lastUpdatedShipping['updated_at']) ? $lastUpdatedShipping['updated_at'] : "dd-mmm-yyyy
                                                hh-mm-ss" ?>
                                            </span>
                                        </h3>
                                        <!--end::Title-->
                                        <!--begin::Toolbar-->
                                        <div class="card-toolbar">
                                            <button type="button" id="btnShipping" class="btn btn-sm btn-light"
                                                data-type="modal"
                                                data-url="<?= base_url('inventory_warehouse/shipping') ?>"
                                                data-fullscreenmodal=1>
                                                Preview
                                            </button>
                                            <button type="button" id="btnShippingLog" class="btn btn-sm btn-light ms-2"
                                                data-type="modal"
                                                data-url="<?= base_url('inventory_warehouse/shipping_log') ?>"
                                                data-fullscreenmodal=1>
                                                Shipping Log
                                            </button>
                                        </div>
                                        <!--end::Toolbar-->
                                    </div>
                                    <!--end::Header-->
                                    <!--begin::Body-->
                                    <div class="card-body pt-4">
                                        <!--begin::Item-->
                                        <div class="d-flex flex-stack">
                                            <!--begin::Section-->
                                            <div class="d-flex align-items-center me-5">
                                                <!--begin::Symbol-->
                                                <div class="symbol symbol-40px me-4">
                                                    <span class="symbol-label">
                                                        <i class="bi bi-clock fs-1 p-0 text-black"></i>
                                                    </span>
                                                </div>
                                                <!--end::Symbol-->
                                                <!--begin::Content-->
                                                <div class="me-5">
                                                    <!--begin::Title-->
                                                    <span class="text-white fw-bold fs-6">In
                                                        Progress</span>
                                                    <!--end::Title-->
                                                </div>
                                                <!--end::Content-->
                                            </div>
                                            <!--end::Section-->
                                            <!--begin::Wrapper-->
                                            <div class="text-white fw-bold fs-7 text-end">
                                                <!--begin::Number-->
                                                <span class="text-white fw-bold fs-6 d-block" id="ship_prog"></span>
                                                <!--end::Number-->Pieces
                                            </div>
                                            <!--end::Wrapper-->
                                        </div>
                                        <!--end::Item-->
                                        <!--begin::Separator-->
                                        <div class="separator separator-dashed my-5"></div>
                                        <!--end::Separator-->
                                        <!--begin::Item-->
                                        <div class="d-flex flex-stack">
                                            <!--begin::Section-->
                                            <div class="d-flex align-items-center me-5">
                                                <!--begin::Symbol-->
                                                <div class="symbol symbol-40px me-4">
                                                    <span class="symbol-label">
                                                        <i class="bi bi-x-circle fs-1 p-0 text-black"></i>
                                                    </span>
                                                </div>
                                                <!--end::Symbol-->
                                                <!--begin::Content-->
                                                <div class="me-5">
                                                    <!--begin::Title-->
                                                    <span class="text-white fw-bold fs-6">Closed</span>
                                                    <!--end::Title-->
                                                </div>
                                                <!--end::Content-->
                                            </div>
                                            <!--end::Section-->
                                            <!--begin::Wrapper-->
                                            <div class="text-white fw-bold fs-7 text-end">
                                                <!--begin::Number-->
                                                <span class="text-white fw-bold fs-6 d-block" id="ship_clos"></span>
                                                <!--end::Number-->Pieces
                                            </div>
                                            <!--end::Wrapper-->
                                        </div>
                                        <!--end::Item-->
                                        <!--begin::Separator-->
                                        <div class="separator separator-dashed my-5"></div>
                                        <!--end::Separator-->
                                        <!--begin::Item-->
                                        <div class="d-flex flex-stack">
                                            <!--begin::Section-->
                                            <div class="d-flex align-items-center me-5">
                                                <!--begin::Content-->
                                                <div class="me-5">
                                                    <!--begin::Title-->
                                                    <span href="#" class="text-white fw-bold fs-6"></span>
                                                    <!--end::Title-->
                                                </div>
                                                <!--end::Content-->
                                            </div>
                                            <!--end::Section-->
                                            <!--begin::Wrapper-->
                                            <div class="text-white fw-bold fs-7 text-end">
                                                <!--begin::Number-->
                                                <span class="text-white fw-bold fs-6 d-block"></span>
                                                <!--end::Number-->
                                            </div>
                                            <!--end::Wrapper-->
                                        </div>
                                        <!--end::Item-->
                                    </div>
                                    <!--end::Body-->
                                </div>
                                <!--end::List widget 11-->
                            </div>
                            <!--end::Col-->

                        </div>
                        <!--end::Row-->
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>