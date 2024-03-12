<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<!--begin:Form-->
<form id="AccountSetting" class="form" data-url="<?= base_url('account/process') ?>" method="POST">
    <input type="text" name="id" id="id" value="<?= $Account->id ?>" hidden>
    <!--begin::Input group-->
    <div class="d-flex flex-column mb-8 fv-row">
        <!--begin::Label-->
        <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
            <span>Full Name</span>
        </label>
        <!--end::Label-->
        <input type="text" class="form-control form-control-solid" placeholder="Enter Full Name" name="fullname"
            id="fullname" value="<?= $Account->fullname ?>" />
    </div>
    <!--end::Input group-->
    <!--begin::Input group-->
    <div class="d-flex flex-column mb-8 fv-row">
        <!--begin::Label-->
        <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
            <span>Email</span>
        </label>
        <!--end::Label-->
        <input type="text" class="form-control form-control-solid" placeholder="Enter Email" name="email" id="email"
            value="<?= $Account->email ?>" />
    </div>
    <!--end::Input group-->
    <!--begin::Input group-->
    <div class="d-flex flex-column mb-8 fv-row">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="myCheck" name="myCheck" value="" />
            <label class="form-check-label" for="myCheck">
                Change Password
            </label>
            <!--end::Input group-->
        </div>
    </div>
    <!--begin::Input group-->
    <div class="d-flex flex-column mb-8 fv-row">
        <!--end::Label-->
        <input type="password" class="form-control form-control-solid mb-4" placeholder="Enter Old Password"
            name="oldPass" id="oldPass" />
        <input type="password" class="form-control form-control-solid mb-4" placeholder="Enter New Password"
            name="newPass" id="newPass" />
        <input type="password" class="form-control form-control-solid mb-4" placeholder="Enter Confirmation Password"
            name="conPass" id="conPass" />
    </div>
    <!--end::Input group-->
</form>
<!--end:Form-->
<script>
$(document).ready(function() {

    $('#oldPass').hide();
    $('#newPass').hide();
    $('#conPass').hide();
    checkBox = document.getElementById('myCheck').addEventListener('click', event => {
        if (event.target.checked) {
            $("input:checkbox").val("1");
            $('#oldPass').show();
            $('#newPass').show();
            $('#conPass').show();
        } else {
            $("input:checkbox").val("0");
            $('#oldPass').hide();
            $('#newPass').hide();
            $('#conPass').hide();
        }
    });
});
</script>