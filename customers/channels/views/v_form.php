<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<div class="d-flex flex-column scroll-y me-n7 pe-7">

    <input type="hidden" class="form-control" id="id" name="id" value="<?= isset($id) ? $id : '' ?>" />

    <div class="fv-row mb-7">
        <label class="required fw-semibold fs-6 mb-4">Channel Name</label>
        <input type="text" class="form-control mb-3 mb-lg-0" id="channel_name"
            value="<?= isset($channel_name) ? $channel_name : '' ?>" name="channel_name" value="" data-type="input"
            autocomplete="off" placeholder="Enter Channel Name" />
    </div>

    <div class="fv-row mb-7">
        <label class="required fw-semibold fs-6 mb-4">Source Name</label>
        <select class="form-select" data-control="select2" data-placeholder="Select Source" id="source_id"
            name="source_id">
            <option></option>
            <?php foreach ($sources as $value) { ?>
                <option value="<?= $value['id'] ?>" <?= isset($admins_ms_sources_id) ? $admins_ms_sources_id == $value['id'] ? 'selected' : "" : "" ?>>
                    <?= $value['source_name'] ?>
                </option>
            <?php } ?>
        </select>
    </div>

    <div class="fv-row mb-7">
        <label class="required fw-semibold fs-6 mb-4">Status</label>
        <select class="form-select" data-control="select2" data-placeholder="Select Status" id="status" name="status">
            <option></option>
            <option value="1" <?= isset($status) ? $status == "1" ? 'selected' : "" : "" ?>>Enable</option>
            <option value="2" <?= isset($status) ? $status == "2" ? 'selected' : "" : "" ?>>Disable</option>
        </select>
    </div>
</div>
<script>
    $('select').select2({
        minimumResultsForSearch: Infinity,
    });
</script>