<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<!--begin::Form-->
<form id="formoffline" class="dropzoneExcel" data-url="<?=$url_form?>">
    <!--begin::Input group-->
    <div class="fv-row">
        <!--begin::Dropzone-->
        <div class="dropzone" id="dropZoneModal">
            <!--begin::Message-->
            <div class="dz-message needsclick">
                <i class="bi bi-file-earmark-arrow-up text-primary fs-3x"></i>

                <!--begin::Info-->
                <div class="ms-4">
                    <h3 class="fs-5 fw-bold text-gray-900 mb-1">Drop files here or click to upload.</h3>
                    <span class="fs-7 fw-semibold text-gray-400">Upload max 1 files</span>
                </div>
                <!--end::Info-->
            </div>
        </div>
        <!--end::Dropzone-->
    </div>
    <!--end::Input group-->
</form>
<!--end::Form-->