<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<input type="hidden" class="form-control" id="id" name="id" value="<?= isset($id) ? $id : '' ?>" />

<div class="modal-upload">
    <div class="card shadow-lg">
        <div class="card-body">
            <div class="card-body pt-0 modal-upload">
                <div class="mb-5 fv-row">
                    <label class="required form-label">Group Name</label>
                    <input type="text" id="group_name" data-type='input' name="group_name" class="form-control mb-2" />
                </div>

                <div class="mb-5 fv-row">
                    <label class="form-label">Brand</label>
                    <input class="form-control mb-2" id="kt_tagify_1" readonly />
                </div>

                <div>
                    <label class="form-label">Group Description</label>
                    <textarea type="text" name="group_description" id="kt_docs_ckeditor_classic"></textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-lg mt-6">
        <div class="card-body">

            <div class="row mt-8 mb-4" align="right">
                <div class="col-lg-12">
                    <button class="btn btn-outline btn-outline-dashed btn-outline-success btn-active-light-success btn-sm fw-bold" type="button" id="btnAddSKU" data-type="modal" data-url="<?= base_url('/inventory_group/addSKU') ?>" data-fullscreenmodal="0">
                        <i class="bi bi-plus"></i>
                        Add New
                    </button>
                </div>
            </div>

            <div class="col-xl-12">
                <table id="kt_datatable_vertical_scroll" class="table table-striped table-row-bordered gy-5 gs-7">
                    <thead>
                        <tr class="fw-semibold fs-6 text-gray-800">
                            <th class="min-w-50px">Product ID</th>
                            <th class="min-w-200px">Product</th>
                            <th class="min-w-100px">Size</th>
                            <th class="min-w-100px">Brand</th>
                            <th class="min-w-100px">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    setTimeout(() => {
        var target = $(".modal-upload").parent().parent().parent('.modal-content')[0];
        var blockUI = new KTBlockUI(target);
    }, 300);
</script>