<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<div id="form_lookup_values">
    <div class="d-flex flex-column scroll-y me-n7 pe-7">
        <div class="fv-row mb-7">
            <input type="hidden" class="form-control" id="id" name="id" value="<?= isset($id) ? $id : '' ?>" />

            <label class="required fw-semibold fs-6 mb-4">Lookup Code</label>
            <input name="lookup_code" id="lookup_code" type="text" class="form-control form-control-solid mb-3 mb-lg-0"
                placeholder="Enter Lookup Code" data-type="input" autocomplete="off"
                value="<?= isset($lookup_code) ? $lookup_code : '' ?>" />
        </div>

        <div class="fv-row mb-7">
            <label class="required fw-semibold fs-6 mb-4">Lookup Name</label>
            <input type="text" id="lookup_name" name="lookup_name" class="form-control form-control-solid mb-3 mb-lg-0"
                placeholder="Enter Lookup Name" data-type="input" autocomplete="off"
                value="<?= isset($lookup_name) ? $lookup_name : '' ?>" />
        </div>

        <div class="fv-row mb-7">
            <label class="required fw-semibold fs-6 mb-4">Lookup Config</label>
            <input type="text" id="lookup_config" name="lookup_config"
                class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Enter Lookup Config" data-type="input"
                autocomplete="off" value="<?= isset($lookup_config) ? $lookup_config : '' ?>" />
        </div>
    </div>
</div>