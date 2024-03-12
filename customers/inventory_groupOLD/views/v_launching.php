<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>


<div class="row">
    <div class="col-xl-6">
        <table class="table table-striped table-row-bordered gy-5 gs-7" style=" border: 1px solid #000000;padding: 8px;text-align: left;">
            <thead>
                <tr class="fw-semibold fs-6 text-gray-800">
                    <th class="min-w-50px">Product GID</th>
                    <th class="min-w-100px">Product Group</th>
                    <th class="min-w-100px">Brand</th>
                    <th class="min-w-100px">Source</th>
                    <th class="min-w-100px">Channel</th>
                </tr>
            </thead>
            <tbody>
                <tr class="odd">
                    <td><?= $dataItems->group_code ?></td>
                    <td><?= $dataItems->group_name ?></td>
                    <td><?= $dataItems->group_name ?></td>
                    <td>Default</td>
                    <td>Default</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<div class="row mt-8">
    <div class="table-responsive">
        <table class="table table-striped table-row-bordered gy-5 gs-7" style=" border: 1px solid #000000;padding: 8px;text-align: left;">
            <thead>
                <tr class="fw-semibold fs-6 text-gray-800 border-bottom border-gray-200">
                    <th>Source</th>
                    <th>Channel</th>
                    <th>Action</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style='vertical-align: middle;'>Default</td>
                    <td style='vertical-align: middle;'>Default</td>
                    <td style='vertical-align: middle;'>
                        <button type="button" class="btn btn-outline btn-outline-dashed btn-outline-success btn-sm me-2 btnDefaultImage" data-title="Item" data-type="modal" data-url="<?= base_url('inventory_group/selectDefaultImage/' . $dataItems->id) ?>" data-id="<?= $dataItems->id ?>" data-fullscreenmodal="0">Select Default Image</button>
                    </td>
                    <td style='vertical-align: middle;'>Image Not Selected</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<div class="row mt-8">
    <div class="table-responsive">
        <table id="kt_datatable_sources" class="table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer" aria-describedby="table-data_info" style=" border: 1px solid #000000;padding: 8px;text-align: left;">
            <thead>
                <tr class="fw-semibold fs-6 text-gray-800 border-bottom border-gray-200">
                    <th>Source</th>
                    <th>Channel</th>
                    <th>Launch Date</th>
                    <th>Action</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

<div class="row mb-10 mt-5">
    <div class="col-md-6">
    </div>

    <div class="col-md-6">
        <div class="d-flex flex-end gap-2 gap-lg-3 mt-9">
            <button type="button" class="btn btn-outline btn-outline-dashed btn-outline-success btn-active-light-success btn-sm fw-bold addSource" data-type="modal" data-url="<?= base_url('inventory_group/add_source/') . $dataItems->id  ?> " data-id="<?= $dataItems->id ?>" data-fullscreenmodal="0">
                <i class="bi bi-plus"></i>
                Add Source</button>
        </div>
    </div>
</div>
