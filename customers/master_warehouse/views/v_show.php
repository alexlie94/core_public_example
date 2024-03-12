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
                        <form id="formSearch" class="form formSearch" autocomplete="off">
                            <div class="row mb-10 mt-5">

                                <div class="col-md-2">
                                    <label class="fs-5 fw-semibold mb-2">Search By</label>
                                    <select class="form-select" name="searchBy" id="searchBy" data-control="select2"
                                        data-hide-search="true" data-type='select' data-placeholder="Please Select">
                                        <option value="" disabled selected hidden>Please Select</option>
                                        <?php
                                        foreach ($searchBy as $key => $value) {
                                            echo "<option value='{$key}'>{$value}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <input type="text" class="form-control mt-9" id="searchValue" name="searchValue"
                                        placeholder="" autocomplete="off" data-type='input'>
                                </div>

                                <div class="col-md-7">
                                    <div class="d-flex flex-end gap-2 gap-lg-3 mt-9">
                                        <button
                                            class="btn btn-outline btn-outline-dashed btn-outline-success btn-active-light-success hover-scale btn-sm fw-bold"
                                            type="button" id="btnAdd" data-type="modal"
                                            data-url="<?= base_url('master_warehouse/insert') ?>"
                                            data-fullscreenmodal="0"><i class="fa-solid fa-plus fs-4 me-2"></i>Create
                                            Warehouse</button>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <div class="row mb-5">
                            <div class="d-flex flex-end gap-2 gap-lg-3">
                                <button type="button" id="btnSearchReset"
                                    class="btn btn-outline btn-outline-dashed btn-outline-danger btn-active-light-danger hover-scale btn-sm fw-bold">
                                    <i class="fa-solid fa-refresh fs-4 me-2"></i>Reset</button>
                                <button type="button" id="btnSearch" onclick="reloadDatatables()"
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
                        <?= $table ?>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>