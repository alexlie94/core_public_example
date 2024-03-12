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
        <?= $detailTable ?>
    </div>
</div>

<script>
    var lookupValue = '<?=$lookup?>';
    lookupValue = JSON.parse(lookupValue);
    var dataArray = '<?=$dataDefaultArray?>'; 
    dataArray = JSON.parse(dataArray);
</script>