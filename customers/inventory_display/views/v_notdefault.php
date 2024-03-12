<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<div class="row mb-10">
    <div class="col-md-6">
        <?= $headerTable ?>
    </div>
</div>

<div class="row mb-10">
    <div class="col-md-12">
        <div class="d-flex flex-end gap-2 gap-lg-3">
            <button class="btn btn-flex btn-success h-40px fs-7 fw-bold" id="btnNotDefaultToDefaultImage" data-source="<?=$dataSource?>" data-channel="<?=$dataChannel?>" data-product="<?=$dataProductID?>" data-url="<?=base_url("inventory_display/setdefaultimage")?>">Default Image</button>
        </div>
    </div>
</div>

<div class="row mb-10">
    <div class="col-md-12">
        <?= $detailTable ?>
    </div>
</div>

<script>
    var lookupValue = '<?=$lookup?>';
    lookupValue = JSON.parse(lookupValue);
    var dataArray = '<?=$dataNotDefaultArray?>'; 
    dataArray = JSON.parse(dataArray);
    var lookupDisplay = '<?=$lookupDisplay?>';
    lookupDisplay = JSON.parse(lookupDisplay);
    var lookupLaunchStatus = '<?=$lookupLaunchStatus?>';
    lookupLaunchStatus = JSON.parse(lookupLaunchStatus);
    var lookupDisplayColour = '<?=$lookupDisplayColour?>';
    lookupDisplayColour = JSON.parse(lookupDisplayColour);

</script>