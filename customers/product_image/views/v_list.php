<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<div class="m-10">
    <div style="display: flex; justify-content: space-between;" class="mb-5">
        <div></div>
        <div>
            <button type="button" id="btnAddVariant" data-type="modal" data-type="modal"
                data-url="<?= base_url().'product_image/form_add_variant' ?>" class="btn btn-primary float-end">Create
                Variant</button>
        </div>
    </div>
    <table id="kt_datatable_variant_list" class="table table-rounded table-striped border gy-7 gs-7">
        <thead>
            <tr class="fw-semibold fs-6 text-gray-800 border-bottom border-gray-200">
                <th>No</th>
                <th>Product ID</th>
                <th>Product Name</th>
                <th>General Color</th>
                <th>Variant Color</th>
                <th>Color</th>
                <th>Action</th>
            </tr>
        </thead>
    </table>
</div>

</div>
