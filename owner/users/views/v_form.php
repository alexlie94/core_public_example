<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!--begin::Scroll-->
<div class="d-flex flex-column scroll-y me-n7 pe-7">
    <!--begin::Input group-->
    <div class="fv-row mb-7">
        <input class="form-control" id="id" name="id" type="hidden" value="<?=isset($id) ? $id : ""?>"/>
        <!--begin::Label-->
        <label class="required fw-semibold fs-6 mb-4">Full Name</label>
        <!--end::Label-->
        <!--begin::Input-->
        <input type="text" id="fullname" name="fullname" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Full name" value="<?=isset($fullname) ? $fullname : ""?>" data-type="input" autocomplete="off"/>
        <!--end::Input-->
    </div>
    <!--end::Input group-->
    <!--begin::Input group-->
    <div class="fv-row mb-7">
        <!--begin::Label-->
        <label class="required fw-semibold fs-6 mb-4">Email</label>
        <!--end::Label-->
        <!--begin::Input-->
        <input type="text" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="example@domain.com"  id="email" name="email" value="<?=isset($email) ? $email : ''?>" data-type="input" autocomplete="off"/>
        <!--end::Input-->
    </div>
    <!--end::Input group-->
    <!--begin::Input group-->
    <div class="fv-row mb-7">
        <!--begin::Label-->
        <label class="required fw-semibold fs-6 mb-4">Password</label>
        <!--end::Label-->
        <!--begin::Input-->
        <input type="text" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="password"  id="password" name="password" data-type="input" autocomplete="off"/>
        <!--end::Input-->
    </div>
    <!--end::Input group-->
    <div class="fv-row mb-7">
        <!--begin::Label-->
        <label class="required fw-semibold fs-6 mb-4">Status</label>
        <!--end::Label-->
        <!--begin::Input-->
        <label class="form-check form-switch form-check-custom form-check-solid">
            <input class="form-check-input" type="checkbox" value="enabled" <?=(isset($checked) ? ($checked == 'enabled' ? "checked=\"checked\"" : "") : "checked=\"checked\"")?> name="status" id="status">
            <span class="form-check-label fw-semibold text-muted">Enabled</span>
        </label>
        <!--end::Input-->
    </div>
    <!--begin::Input group-->
    <div class="mb-7">
        <!--begin::Label-->
        <label class="required fw-semibold fs-6 mb-4" id="rolename">Role</label>
        <!--end::Label-->
        <!--begin::Roles-->
        <?php 
        foreach($role as $ky => $val){
            $output = '<div class="d-flex fv-row">';
            $output .= '<div class="form-check form-check-custom form-check-solid">';
            
            $output .= '<input class="form-check-input me-3" name="rolename" type="radio" value="'.$val->id.'"  '.(isset($role_id) && $role_id == $val->id ? "checked='checked'" : "").'" />';
            $output .= '<label class="form-check-label" for="">
                            <div class="fw-bold text-gray-800">'.ucwords($val->role_name).'</div>
                        </label>';
            $output .= '</div>';
            $output .= '</div>';
            $output .= '<div class="separator separator-dashed my-5"></div>';

            echo $output;
        }
        ?>
        <!--end::Roles-->
    </div>
    <!--end::Input group-->
</div>
<!--end::Scroll-->