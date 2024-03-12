<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<input type="hidden" name="get_gid" value="<?= $getGid ?>" />

<div class="row">
    <div class="d-flex flex-column scroll-y me-n7 pe-7">
        <div class="fv-row mb-7">
            <label class="required fw-semibold fs-6 mb-4">Source</label>
            <select data-type='select' data-control="select2" name="source" id="source" class="form-select validateSource">
            </select>
        </div>

        <div class="fv-row mb-7">
            <label class="fw-semibold fs-6 mb-4">Channel</label>
            <select data-type='select' name="channel" id="channel" class="form-select validateSource">
            </select>
        </div>
    </div>
</div>