<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<div class="row">
    <div class="col-md-12">
        <div class="d-flex align-items-center flex-center">
            <img src="<?= isset($source_image) ? '../assets/uploads/channels_image/' . $source_image : 'metronic/media/svg/avatars/blank.svg' ?>" alt="" width="50%" height="50%">
            <input type="hidden" id="source_name" name="source_name" value="<?= $source_name ?>">
        </div>
    </div>
</div>