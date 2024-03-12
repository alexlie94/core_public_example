<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

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
                        <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x mb-5 fs-6">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#online">Online</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link offline" data-bs-toggle="tab" href="#offline">Offline</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="online" role="tabpanel">
                    <div class="row mt-3">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <form id="formSearch" class="form formSearch" autocomplete="off">
                                    <div class="row mb-10 mt-5">
                                        <div class="col-md-2">
                                            <label class="fs-5 fw-semibold mb-2">Search By</label>
                                            <select class="form-select" name="searchBy" id="searchBy" aria-label="Please Select">
                                                <option value="">Please Select</option>
                                                <?php
                                                foreach ($searchBy as $key => $value) {
                                                    $selected = $key == "productid" ? "selected" : "";
                                                    echo "<option value='{$key}' {$selected}>{$value}</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-md-5" id="placeSearch">
                                            <input type="text" class="form-control mt-9" id="searchValue" name="searchValue"
                                                placeholder="Product ID" autocomplete="off">
                                        </div>
                                        <div class="col-md-5 mt-9 flex-end d-flex align-items-center">
                                            <div class="d-flex flex-end gap-2 gap-lg-3">
                                                <button type="button" class="btn btn-outline btn-outline-dashed btn-outline-info btn-active-light-info hover-scale btn-sm"
                                                id="btnMassUpload" data-type="modal" data-url="<?= base_url('inventory_allocation/upload') ?>"><i class="bi bi-file-earmark-arrow-up-fill fs-4 me-2"></i>Mass Upload</button>
                                                <button type="button" class="btn btn-outline btn-outline-dashed btn-outline-warning btn-active-light-warning hover-scale btn-sm" id="btnExport" data-type="redirect" data-url="<?= base_url('inventory_allocation/download'); ?>"><i class="fa-solid fa-cloud-download fs-4 me-2"></i>Download View</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-10 mt-5">
                                        <div class="col-md-2">
                                            <label class="fs-5 fw-semibold mb-2">Source</label>
                                            <select class="form-select" name="source" id="source" data-url="<?= $sourceUrl ?>"
                                                aria-label="Please Select">
                                                <option value="">Please Select</option>
                                                <?php
                                                foreach ($source as $key => $value) {
                                                    echo "<option value='{$value->id}'>{$value->source_name}</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="fs-5 fw-semibold mb-2">Channel</label>
                                            <select class="form-select" name="channel" id="channel" aria-label="Please Select">
                                                <option value="">Please Select</option>
                                            </select>
                                        </div>
                                    </div>
                                </form>

                                <div class="row mb-5">
                                    <div class="d-flex flex-end gap-2 gap-lg-3">
                                        <button class="btn btn-outline btn-outline-dashed btn-outline-primary btn-active-light-primary hover-scale btn-sm fw-bold" type="button"
                                            onclick="reloadDatatablesTabs('table-data')"><i class="fa-solid fa-search fs-4 me-2"></i>Search</button>
                                        <button id="btnSearchResetInventory" class="btn btn-outline btn-outline-dashed btn-outline-danger btn-active-light-danger hover-scale btn-sm fw-bold"
                                            type="button"><i class="fa-solid fa-refresh fs-4 me-2"></i>Reset</button>
                                        
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="row mt-6">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <?= $table ?>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="tab-pane fade" id="offline" role="tabpanel">
                    <div class="row mt-3">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <form id="formSearchOffline" class="form formSearchOffline" autocomplete="off">
                                    <div class="row mb-10 mt-5">
                                        <div class="col-md-2">
                                            <label class="fs-5 fw-semibold mb-2">Search By</label>
                                            <select class="form-select" name="searchByOffline" id="searchByOffline"
                                                aria-label="Please Select">
                                                <option value="">Please Select</option>
                                                <?php
                                                foreach ($searchBy as $key => $value) {
                                                    $selected = $key == "productid" ? "selected" : "";
                                                    echo "<option value='{$key}' {$selected}>{$value}</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-md-5" id="placeSearchOffline">
                                            <input type="text" class="form-control mt-9" id="searchValueOffline"
                                                name="searchValueOffline" placeholder="Product ID" autocomplete="off">
                                        </div>

                                        <div class="col-md-5 mt-9 flex-end d-flex align-items-center">
                                            <div class="d-flex flex-end gap-2 gap-lg-3">
                                                <button
                                                    class="btn btn-outline btn-outline-dashed btn-outline-info btn-active-light-info hover-scale btn-sm"
                                                    id="btnMassUploadOffline" data-type="modal"
                                                    data-url="<?= base_url('inventory_allocation/uploadoffline') ?>"><i class="bi bi-file-earmark-arrow-up-fill fs-4 me-2"></i>Mass Upload</button>
                                                <button class="btn btn-outline btn-outline-dashed btn-outline-warning btn-active-light-warning hover-scale btn-sm" id="btnExportOffline"
                                                    data-type="redirect"
                                                    data-url="<?= base_url('inventory_allocation/downloadoffline'); ?>"><i class="fa-solid fa-cloud-download fs-4 me-2"></i>Download
                                                    View</button>
                                            </div>
                                        </div>
                                        
                                    </div>
                                    <div class="row mb-10 mt-5">
                                        <div class="col-md-4">
                                            <label class="fs-5 fw-semibold mb-2">Display</label>
                                            <select class="form-select" name="offlineStores" id="offlineStores"
                                                aria-label="Please Select">
                                                <option value="">Please Select</option>
                                                <?php
                                                foreach ($getOfflineStores as $key => $value) {
                                                    echo "<option value='{$value->id}'>{$value->offline_store_name}</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </form>

                                <div class="row mb-5">
                                    <div class="d-flex flex-end gap-2 gap-lg-3">
                                        <button class="btn btn-outline btn-outline-dashed btn-outline-primary btn-active-light-primary hover-scale btn-sm fw-bold" type="button"
                                            onclick="reloadDatatablesTabs('table-data-offline')"><i class="fa-solid fa-search fs-4 me-2"></i>Search</button>
                                        <button id="btnSearchResetInventoryOffline"
                                            class="btn btn-outline btn-outline-dashed btn-outline-danger btn-active-light-danger hover-scale btn-sm fw-bold" type="button"><i class="fa-solid fa-refresh fs-4 me-2"></i>Reset</button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="row mt-6">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <?= $tableOffline ?>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>

</div>

<style>
    #table-data tbody tr,
    #table-data-offline tbody tr {
        cursor: pointer;
    }
</style>

<script>
    var permission = <?= $getPermission ?>;
    var vlookup = '<?= $getLookupArray ?>';
    vlookup = JSON.parse(vlookup);
    var searchInput = '<?= $searchInput ?>';
    searchInput = JSON.parse(searchInput);
</script>