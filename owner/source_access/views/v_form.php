<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<div class="d-flex flex-column scroll-y me-n7 pe-7">

    <input type="hidden" class="form-control" id="id" name="id" value="<?= isset($id) ? $id : '' ?>" />

    <div class="row">
        <div class="col-md-6">
            <div class="fv-row mb-7">
                <label class="required fw-semibold fs-6 mb-4">Company Name</label>
                <select class="form-select form-select-solid" data-control="select2" data-placeholder="Select Company Name" id="users_ms_companys_id" name="users_ms_companys_id">
                    <option></option>
                    <?php foreach ($company as $value) { ?>
                        <option value="<?= $value['id'] ?>" <?= isset($users_ms_companys_id) ?  $users_ms_companys_id == $value['id'] ? "selected" : ""  : "" ?>>
                            <?= $value['company_name'] ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
        </div>

        <div class="col-md-6">
            <div class="fv-row mb-7">
                <label class="required fw-semibold fs-6 mb-4">Source Name</label>
                <select class="form-select form-select-solid" data-control="select2" data-placeholder="Select Source Name" id="admins_ms_sources_id" name="admins_ms_sources_id">
                    <option></option>
                    <?php if (!empty($id)) { ?>
                        <option value="<?= $source2['id'] ?>" selected><?= $source2['source_name'] ?></option>
                        <?php foreach ($source as $value) { ?>
                            <option value="<?= $value['id'] ?>">
                                <?= $value['source_name'] ?>
                            </option>
                        <?php } ?>
                    <?php } ?>
                </select>
            </div>
        </div>

        <div class="col-md-6">
            <div class="fv-row mb-7">
                <label class="required fw-semibold fs-6 mb-4">Status</label>
                <select class="form-select form-select-solid" data-control="select" data-placeholder="Select Status" id="status" name="status">
                    <option></option>
                    <option value="1" <?= isset($status) ? $status == "1" ? "selected" : "" : "" ?>>Enable</option>
                    <option value="0" <?= isset($status) ? $status == "0" ? "selected" : "" : "" ?>>Disable</option>
                </select>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Source Access Detail</h3>
        </div>
        <div class="card-body card-scroll h-200px">
            <div class="row" id="table1">

            </div>
        </div>
    </div>

</div>