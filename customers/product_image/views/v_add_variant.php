<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<!--begin::Repeater-->
<div class="col-md-6">
    <table id="kt_datatable_add_variant" class="table table-rounded table-striped border gy-7 gs-7">
        <thead>
            <tr class="fw-semibold fs-6 text-gray-800 border-bottom border-gray-200">
                <th width="20%">Product ID</th>
                <th width="70%">Product Name</th>
            </tr>
        </thead>
        <tbody>

        </tbody>
    </table>
</div>



<div class="d-flex flex-column flex-lg-row-fluid gap-7 gap-lg-10">
    <!--begin::Order details-->
    <div class="card card-flush py-4">
        <!--begin::Card body-->
        <div class="card-body pt-0">
            <!--begin::Billing address-->
            <div class="row">

                <!--begin::Input group-->
                <div class="row col-md-6">
                    <div class="col-md-12 mb-4">
                        <div class="flex-row-fluid">
                            <!--begin::Label-->
                            <label class="required form-label">General Color</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <select class="form-select " id="general_color" name="general_color">
                                <option value="" disabled selected>Select Option</option>
                                <?php 
								foreach($general_color as $row){
									echo '<option value="'.$row->id.'">'.$row->color_name.' </option>';
								} ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="flex-row-fluid">
                            <!--begin::Label-->
                            <label class="required form-label">Variant Color</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <select class="form-select" id="variant_color" name="variant_color">
                                <option value="" disabled selected>Select Option</option>
                            </select>
                            <!--end::Input-->
                        </div>
                    </div>
                    <div class="col-md-12 mt-10">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="custom_variant_color"
                                id="custom_variant_color">
                            <label class="form-check-label" for="custom_variant_color">
                                Custom Variant Color
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12 mt-5">
                        <div class="flex-row-fluid">
                            <!--begin::Label-->
                            <label class="required form-label">Size</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input class="form-control" name="size" id="size" placeholder="Custom Variant Color Name"
                                value="" />
                            <!--end::Input-->
                            <span>Separate by comma. ex: S,M,L</span>
                        </div>
                    </div>
                </div>
                <!--end::Input group-->
                <!--begin::Input group-->
                <div class="row col-md-6">
                    <div class="col-md-12 ">
                        <div class="fv-row flex-row-fluid">
                            <!--begin::Label-->
                            <label class=" form-label">Custom Variant Color Name</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input class="form-control" name="custom_variant_color_name" id="custom_variant_color_name"
                                placeholder="Custom Variant Color Name" value="" disabled />
                            <!--end::Input-->
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="fv-row flex-row-fluid">
                            <!--begin::Label-->
                            <label class=" form-label">Custom Variant Color Code</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input class="form-control" name="custom_variant_color_code" id="custom_variant_color_code"
                                placeholder="Custom Variant Color Code" value="" disabled />
                            <!--end::Input-->
                        </div>
                    </div>
                </div>
                <!--end::Input group-->

            </div>
            <!--end::Billing address-->
        </div>
        <!--end::Card body-->
    </div>
</div>


<script>
$('select').select2({
    placeholder: "--Choose Options--",
    dropdownParent: $('#modalLarge3'),
    allowClear: true,
});
</script>