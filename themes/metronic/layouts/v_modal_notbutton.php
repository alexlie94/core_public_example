<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!--begin::Modal header-->
<div class="modal-header">
    <!--begin::Modal title-->
    <h2 class="fw-bold"><?= $title_modal ?></h2>
    <!--end::Modal title-->
</div>
<!--end::Modal header-->
<div class="modal-body scroll-y m-5">
    <?= $content ?>
</div>
<div class="modal-footer">
    <?=$buttonFooter?>
</div>