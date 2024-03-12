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
    <button class="btn btn-secondary btn-rounded" type="button" <?= (isset($buttonCloseID) ? "id=\"" . $buttonCloseID . "\"" : "id=\"btnCloseModal\"") ?>>Close</button>
    <?= isset($buttonSave) && !$buttonSave ? "" : "<button class=\"btn btn-primary btn-rounded ml-2\" type=\"button\" id=\"" . (isset($buttonID) ? $buttonID : "btnProcessModal") . "\" " . (isset($buttonTypeSave) ? "data-type=\"{$buttonTypeSave}\"" : "") ." ".(isset($dataInput) ? $dataInput : "")." ".(isset($buttonDisabled) && $buttonDisabled === true ? 'disabled' : "" ). ">" . (isset($buttonName) ? $buttonName : 'Save changes') . "</button>" ?>
</div>