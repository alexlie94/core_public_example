<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<div id="form_ownership_types">
    <div class="d-flex flex-column scroll-y me-n7 pe-7">
        <div class="fv-row mb-7">
            <input type="hidden" class="form-control" id="id" name="id"
                value="<?= isset($items['id']) ? $items['id'] : '' ?>" />

            <label class="fw-semibold fs-6 mb-4">Types Ownership Code</label>
            <input name="ownership_type_code" id="ownership_type_code" type="text"
                class="form-control form-control mb-3 mb-lg-0" placeholder="Enter Types Ownership Code"
                value="<?= isset($items['ownership_type_code']) ? $items['ownership_type_code'] : 'Auto' ?>"
                data-type="input" autocomplete="off" disabled />
        </div>

        <div class="fv-row mb-7">
            <label class="required fw-semibold fs-6 mb-4">Types Ownership Name</label>
            <input type="text" id="ownership_type_name" name="ownership_type_name"
                class="form-control form-control mb-3 mb-lg-0" placeholder="Enter Types Ownership Name"
                value="<?= isset($items['ownership_type_name']) ? $items['ownership_type_name'] : '' ?>"
                data-type="input" autocomplete="off" />
        </div>

        <div class="fv-row mb-7">
            <label class="required fw-semibold fs-6 mb-4">Status</label>
            <select name="status" id="status" class="form-select" data-control="select2"
                data-placeholder="Select Status">
                <option></option>
                <option value="1" <?= isset($items['status']) ? $items['status'] == '1' ? 'selected' : '' : '' ?>>Enable
                </option>
                <option value="2" <?= isset($items['status']) ? $items['status'] == '2' ? 'selected' : '' : '' ?>>Disable
                </option>
            </select>
        </div>

    </div>
</div>
<script>
    $('select').select2({
        minimumResultsForSearch: Infinity,
    });
</script>