<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<form id="formSearch" class="form formSearch" autocomplete="off"
    data-url="<?= base_url() . 'dashboard_marketplace/export_sales_order' ?>">
    <div class="row mb-4">
        <div class="col-md-6">
            <label class="fw-semibold fs-6 mb-4" for="date_range_export">Date</label>
            <input style="cursor: pointer;" class="form-control form-control-solid" placeholder="Pick date range"
                id="date_range_export" name="date_range_export" readonly />
        </div>
        <div class="col-md-6">
            <label class="fw-semibold fs-6 mb-4" for="source_id">Source</label>
            <select class="form-select form-select-solid" data-control="select2" data-placeholder="Select an option"
                id="source_id" name="source_id">
                <option value="0" selected>All</option>
                <?php foreach ($sources as $value) { ?>
                <option value="<?= $value['id'] ?>">
                    <?= $value['source_name'] ?>
                </option>
                <?php } ?>
            </select>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <label class="fw-semibold fs-6 mb-4" for="warehouse_id">Warehouse</label>
            <select name="warehouse_id" id="warehouse_id" class="form-select form-select-solid" data-control="select2"
                data-hide-search="true" data-type='select' data-placeholder="Select Warehouse">
                <option value="0" selected>All</option>
                <?php foreach ($warehouse as $res) { ?>
                <option value="<?= $res['id'] ?>"><?= $res['warehouse_name'] ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="col-md-6">
            <label class="fw-semibold fs-6 mb-4" for="so_status">Sales Order Status</label>
            <select name="so_status" id="so_status" class="form-select form-select-solid" data-control="select2"
                data-hide-search="true" data-type='select' data-placeholder="Select Status">
                <option value="0" selected>All</option>
                <?php foreach ($status_so as $res) { ?>
                <option value="<?= $res['lookup_code'] ?>"><?= $res['lookup_name'] ?></option>
                <?php } ?>
            </select>
        </div>
    </div>
</form>

<script>
const currentDate = new Date();
$('select').select2({
    minimumResultsForSearch: Infinity,
});

var start = moment().subtract(29, "days");
var end = moment();

function cb(start, end) {
    $("#date_range_export").html(start.format("MMMM D, YYYY") + " - " + end.format("MMMM D, YYYY"));
}

$("#date_range_export").daterangepicker({
    "maxDate": currentDate,
    startDate: start,
    endDate: end,
    ranges: {
        "Today": [moment(), moment()],
        "Yesterday": [moment().subtract(1, "days"), moment().subtract(1, "days")],
        "Last 7 Days": [moment().subtract(6, "days"), moment()],
        "Last 30 Days": [moment().subtract(29, "days"), moment()],
        "This Month": [moment().startOf("month"), moment().endOf("month")],
        "Last Month": [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf(
            "month")]
    }
}, cb);

cb(start, end);
</script>