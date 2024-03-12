<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<div class="row">
    <div class="col-xl-6">
        <table class="table table-striped table-row-bordered gy-5 gs-7" style=" border: 1px solid #000000;padding: 8px;text-align: left;">
            <thead>
                <tr class="fw-semibold fs-6 text-gray-800">
                    <th class="min-w-50px">Product GID</th>
                    <th class="min-w-200px">Product Group</th>
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
        <table id="kt_datatable_vertical_scroll" class="table table-striped table-row-bordered gy-5 gs-7" style=" border: 1px solid #000000;padding: 8px;text-align: left;">
            <thead>
                <tr class="fw-semibold fs-6 text-gray-800">
                    <th class="min-w-50px">Image</th>
                    <th class="min-w-200px">Media Name</th>
                    <th class="min-w-200px">Action</th>
                    <th class="min-w-100px">Status</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>