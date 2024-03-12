<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<!--begin::Repeater-->
<div class="col-md-9">
    <table id="kt_datatable_list_product_image" class="table table-rounded table-striped border gy-7 gs-7">
        <thead>
            <tr class="fw-semibold fs-6 text-gray-800 border-bottom border-gray-200">
                <th width="15%">Product ID</th>
                <th width="40%">Product Name</th>
                <th width="25%">General Color</th>
                <th width="25%">Variant Color</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

<div style="display: flex; justify-content: space-between;" class="mb-5">
    <div></div>
    <div>
        <button type="button" id="btnAddImage" data-type="modal" data-type="modal"
            data-url="<?= base_url().'product_image/insert' ?>" class="btn btn-primary">Create New</button>
    </div>
</div>

<div class="d-flex flex-column flex-lg-row-fluid gap-7 gap-lg-10">
    <!--begin::Order details-->
    <div class="card card-flush py-4">
        <!--begin::Card body-->
        <div class="card-body pt-0">
            <!--begin::Billing address-->
            <div class="row">
                <table id="kt_datatable_image_list" class="table table-row-bordered gy-5">
                    <thead>
                        <tr class="fw-semibold fs-6 text-muted">
                            <th width="10%">Image</th>
                            <th width="70%">Image Name</th>
                            <th width="20%">Action</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>

            </div>
            <!--end::Billing address-->
        </div>
        <!--end::Card body-->
    </div>
</div>