<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<div class="d-flex flex-column scroll-y me-n7 pe-7">

    <input type="hidden" class="form-control" id="id" name="id" value="<?= isset($id) ? $id : '' ?>" />

    <div class="row">
        <div class="col-md-6">
            <div class="fv-row mb-7">
                <label class="required fw-semibold fs-6 mb-4">Title</label>
                <input type="text" class="form-control form-control-solid" name="title" id="title" placeholder="Title"
                    data-type="input" value="<?= isset($title) ? $title : '' ?>" />
            </div>
        </div>

        <div class="col-md-6">
            <div class="fv-row mb-7">
                <label class="required fw-semibold fs-6 mb-4">Source Name</label>
                <select class="form-select form-select-solid" data-control="select2"
                    data-placeholder="Select Source Name" id="admins_ms_sources_id" name="admins_ms_sources_id">
                    <option></option>
                    <?php foreach ($source as $value) { ?>
                    <option value="<?= $value['id'] ?>"
                        <?= isset($admins_ms_sources_id) ? $admins_ms_sources_id == $value['id'] ? "selected" : ""  : "" ?>>
                        <?= $value['source_name'] ?>
                    </option>
                    <?php } ?>
                </select>
            </div>
        </div>

        <div class="col-md-6">
            <div class="fv-row mb-7">
                <label class="required fw-semibold fs-6 mb-4">Endpoint URL</label>
                <input type="text" class="form-control form-control-solid" name="endpoint_url" id="endpoint_url"
                    placeholder="Endpoint URL" data-type="input"
                    value="<?= isset($endpoint_url) ? $endpoint_url : '' ?>" />
            </div>
        </div>

        <div class="col-md-6">
            <div class="fv-row mb-7">
                <label class="required fw-semibold fs-6 mb-4">Status</label>
                <select class="form-select form-select-solid" data-control="select" data-placeholder="Select Status"
                    id="status" name="status">
                    <option></option>
                    <option value="1" <?= isset($status) ? $status == "1" ? "selected" : "" : "" ?>>Enable</option>
                    <option value="2" <?= isset($status) ? $status == "2" ? "selected" : "" : "" ?>>Disable</option>
                </select>
            </div>
        </div>
    </div>

</div>