<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<div class="d-flex flex-column scroll-y me-n7 pe-7">
    <div class="fv-row mb-7">
        <label class="required fw-semibold fs-6 mb-4">Launch Date</label>
        <input type="text" class="form-control singleDateRange" name="launch_date" id="launch_date" data-library="singleDateRangeStartTomorrow">
    </div>
    <div class="fv-row mb-7">
        <label class="fw-semibold fs-6 mb-4">Status</label>
        <input type="text" class="form-control" name="launch_status" id="launch_status" readonly>
        <div class="help-block mt-2 fs-6">
            * Default Image will be selected when status Image Not Selected
        </div>
    </div>
</div>

<script>
var lookupDisplay = '<?=$lookupDisplay?>';
lookupDisplay = JSON.parse(lookupDisplay);
var lookupLaunchStatus = '<?=$lookupLaunchStatus?>';
lookupLaunchStatus = JSON.parse(lookupLaunchStatus);
var lookupDisplayColour = '<?=$lookupDisplayColour?>';
lookupDisplayColour = JSON.parse(lookupDisplayColour);

</script>