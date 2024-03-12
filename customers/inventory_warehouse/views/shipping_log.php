<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<div class="row">
    <!--begin::Col-->
    <div class="col-xl-12">
        <!--begin::Datatable-->
        <input type="hidden" name="paging_datatables" value="0" class="halaman" style="display: none"><input type="hidden" name="draw" value="1" class="draw_datatables" style="display: none">
        <div class="table-responsive">
            <table id="shipping_log" class="table table-hover table-rounded">
                <thead>
                    <tr>
                        <th class="min-w-100px">Purchase Code</th>
                        <th class="min-w-100px">Created Date</th>
                        <th class="min-w-100px">SKU</th>
                        <th class="min-w-100px">Quantity</th>
                        <th class="min-w-100px">Quantity Shipping</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
        <div class="paginationDatatables"></div>
        <!--end::Datatable-->
    </div>
</div>