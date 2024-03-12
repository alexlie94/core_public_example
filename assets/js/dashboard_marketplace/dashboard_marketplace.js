$(document).ready(function () {
  const currentDate = new Date();
  var start = moment().subtract(6, "days");
  var end = moment();

  function cb(start, end) {
    $("#kt_daterangepicker_4").html(
      start.format("MM/DD/YYYY") + " - " + end.format("MM/DD/YYYY")
    );
    $("#kt_daterangepicker_5").html(
      start.format("MM/DD/YYYY") + " - " + end.format("MM/DD/YYYY")
    );
  }

  $("#kt_daterangepicker_4").daterangepicker(
    {
      maxDate: currentDate,
      startDate: start,
      endDate: end,
      ranges: {
        Today: [moment(), moment()],
        Yesterday: [moment().subtract(1, "days"), moment()],
        "Last 7 Days": [moment().subtract(6, "days"), moment()],
        "Last 30 Days": [moment().subtract(29, "days"), moment()],
        "This Month": [moment().startOf("month"), moment().endOf("month")],
        "Last Month": [
          moment().subtract(1, "month").startOf("month"),
          moment().subtract(1, "month").endOf("month"),
        ],
      },
    },
    cb
  );

  $("#kt_daterangepicker_5").daterangepicker(
    {
      maxDate: currentDate,
      startDate: start,
      endDate: end,
      ranges: {
        Today: [moment(), moment()],
        Yesterday: [moment().subtract(1, "days"), moment()],
        "Last 7 Days": [moment().subtract(6, "days"), moment()],
        "Last 30 Days": [moment().subtract(29, "days"), moment()],
        "This Month": [moment().startOf("month"), moment().endOf("month")],
        "Last Month": [
          moment().subtract(1, "month").startOf("month"),
          moment().subtract(1, "month").endOf("month"),
        ],
      },
    },
    cb
  );

  cb(start, end);

  var date2Begin = $("#kt_daterangepicker_4").val().split("/");
  var mdateBegin = date2Begin[2].split(" ");
  var dateBegin =
    mdateBegin[0] +
    "-" +
    date2Begin[0] +
    "-" +
    date2Begin[1] +
    "to" +
    date2Begin[4] +
    "-" +
    mdateBegin[2] +
    "-" +
    date2Begin[3];

  ajax_order(dateBegin);

  $(document).on("change", "#kt_daterangepicker_4", function () {
    $("#show_order tbody tr").remove();
    $("#show_order tfoot tr").remove();
    var date2 = $(this).val().split("/");
    var mdate = date2[2].split(" ");
    var date =
      mdate[0] +
      "-" +
      date2[0] +
      "-" +
      date2[1] +
      "to" +
      date2[4] +
      "-" +
      mdate[2] +
      "-" +
      date2[3];
    ajax_order(date);
  });

  $(document).on("click", "#btnExportSO", function () {
    buttonAction($(this), "#modalLarge2");
  });

  $(document).on("click", "#btnCloseModal", function () {
    $("#modalLarge2").modal("hide");
  });

  $(document).on("click", "#btnExportSalesOrder", function () {
    if ($("select[name=warehouse_id]").val() != null) {
      var warehouseid = $("select[name=warehouse_id]").val();
    } else {
      var warehouseid = "";
    }

    if ($("select[name=date_range_export]").val() != null) {
      var date_range_export = $("select[name=date_range_export]").val();
    } else {
      var date_range_export = "";
    }

    if ($("select[name=source_id]").val() != null) {
      var source_id = $("select[name=source_id]").val();
    } else {
      var source_id = "";
    }

    if ($("select[name=so_status]").val() != null) {
      var so_status = $("select[name=so_status]").val();
    } else {
      var so_status = "";
    }

    window.location.href =
      base_url() +
      "dashboard_marketplace/export_sales_order" +
      "?warehouseid=" +
      warehouseid +
      "&date_range_export=" +
      date_range_export +
      "&source_id=" +
      source_id +
      "&so_status=" +
      so_status;
  });

  var param_date_so = $("#kt_daterangepicker_5").val().split("/");
  var mparam_date_so = param_date_so[2].split(" ");
  var date_param =
    mparam_date_so[0] +
    "-" +
    param_date_so[0] +
    "-" +
    param_date_so[1] +
    "to" +
    param_date_so[4] +
    "-" +
    mparam_date_so[2] +
    "-" +
    param_date_so[3];
  var source_param = $("#source_id").val();

  var date1 = $("#kt_daterangepicker_5").val().split(" ");
  var start_date = new Date(date1[0]);
  var end_date = new Date(date1[2]);
  var Difference_In_Time = end_date.getTime() - start_date.getTime();
  var Difference_In_Days = Difference_In_Time / (1000 * 3600 * 24);
  $("#gross_sale_day").html("vs previous " + Difference_In_Days + " day(s)");
  $("#orders_days").html("vs previous " + Difference_In_Days + " day(s)");
  $("#items_sold").html("vs previous " + Difference_In_Days + " day(s)");
  $("#avg_order_value_days").html(
    "vs previous " + Difference_In_Days + " day(s)"
  );
  $("#avg_order_per_days").html(
    "vs previous " + Difference_In_Days + " day(s)"
  );
  var previousDay = getPreviousDay(end_date);
  var param_value_so = date_param + "to" + source_param + "to" + previousDay;

  ajax_sales_order(param_value_so, Difference_In_Days);

  $("select").select2({
    minimumResultsForSearch: Infinity,
  });

  $(document).on("change", "#kt_daterangepicker_5", function () {
    $("#show_sales_order tbody tr").remove();
    $("#show_sales_order tfoot tr").remove();
    var param_date_so = $(this).val().split("/");
    var mparam_date_so = param_date_so[2].split(" ");
    var date_param =
      mparam_date_so[0] +
      "-" +
      param_date_so[0] +
      "-" +
      param_date_so[1] +
      "to" +
      param_date_so[4] +
      "-" +
      mparam_date_so[2] +
      "-" +
      param_date_so[3];
    var source_param = $("#source_id").val();

    var date1 = $(this).val().split(" ");
    var start_date = new Date(date1[0]);
    var end_date = new Date(date1[2]);
    var Difference_In_Time = end_date.getTime() - start_date.getTime();
    var Difference_In_Days = Difference_In_Time / (1000 * 3600 * 24);
    if (Difference_In_Days == 0) {
      Difference_In_Days = Difference_In_Days + 1;
    }
    $("#gross_sale_day").html("vs previous " + Difference_In_Days + " day(s)");
    $("#orders_days").html("vs previous " + Difference_In_Days + " day(s)");
    $("#items_sold").html("vs previous " + Difference_In_Days + " day(s)");
    $("#avg_order_value_days").html(
      "vs previous " + Difference_In_Days + " day(s)"
    );
    $("#avg_order_per_days").html(
      "vs previous " + Difference_In_Days + " day(s)"
    );
    var previousDay = getPreviousDay(end_date);
    var param_value_so = date_param + "to" + source_param + "to" + previousDay;

    ajax_sales_order(param_value_so, Difference_In_Days);
  });

  $(document).on("change", "#source_id", function () {
    $("#show_sales_order tbody tr").remove();
    $("#show_sales_order tfoot tr").remove();
    var param_date_so = $("#kt_daterangepicker_5").val().split("/");
    var mparam_date_so = param_date_so[2].split(" ");
    var date_param =
      mparam_date_so[0] +
      "-" +
      param_date_so[0] +
      "-" +
      param_date_so[1] +
      "to" +
      param_date_so[4] +
      "-" +
      mparam_date_so[2] +
      "-" +
      param_date_so[3];
    var source_param = $(this).val();

    var date1 = $("#kt_daterangepicker_5").val().split(" ");
    var start_date = new Date(date1[0]);
    var end_date = new Date(date1[2]);
    var Difference_In_Time = end_date.getTime() - start_date.getTime();
    var Difference_In_Days = Difference_In_Time / (1000 * 3600 * 24);
    if (Difference_In_Days == 0) {
      Difference_In_Days = Difference_In_Days + 1;
    }
    $("#gross_sale_day").html("vs previous " + Difference_In_Days + " day(s)");
    $("#orders_days").html("vs previous " + Difference_In_Days + " day(s)");
    $("#items_sold").html("vs previous " + Difference_In_Days + " day(s)");
    $("#avg_order_value_days").html(
      "vs previous " + Difference_In_Days + " day(s)"
    );
    $("#avg_order_per_days").html(
      "vs previous " + Difference_In_Days + " day(s)"
    );
    var previousDay = getPreviousDay(end_date);
    var param_value_so = date_param + "to" + source_param + "to" + previousDay;

    ajax_sales_order(param_value_so, Difference_In_Days);
  });

  ajax_show_data_inventory_display();
  ajax_show_data_inventory_shadow();
  ajax_show_data_inventory_group();
});

function formatRupiah(angka, prefix) {
  var number_string = angka.replace(/[^,\d]/g, "").toString(),
    split = number_string.split(","),
    sisa = split[0].length % 3,
    rupiah = split[0].substr(0, sisa),
    ribuan = split[0].substr(sisa).match(/\d{3}/gi);

  // tambahkan titik jika yang di input sudah menjadi angka ribuan
  if (ribuan) {
    separator = sisa ? "." : "";
    rupiah += separator + ribuan.join(".");
  }

  rupiah = split[1] != undefined ? rupiah + "," + split[1] : rupiah;
  return prefix == undefined ? rupiah : rupiah ? "Rp. " + rupiah : "";
}

function formatDateToMMDDYYYY(date) {
  const month = String(date.getMonth() + 1).padStart(2, "0"); // Penambahan 1 karena bulan dimulai dari 0
  const day = String(date.getDate()).padStart(2, "0");
  const year = date.getFullYear();

  return `${year}-${month}-${day}`;
}

function getPreviousDay(date = new Date()) {
  const previous = new Date(date.getTime());
  previous.setDate(date.getDate() - 1);
  return formatDateToMMDDYYYY(previous);
}

function ambilDuaAngkaDiBelakangKoma(angka) {
  if (typeof angka !== "number") {
    return null;
  }
  var angkaBulat = angka.toFixed(2);
  var angkaFloat = parseFloat(angkaBulat);
  return angkaFloat;
}

function apex_chart(params) {
  var element = document.getElementById("kt_apexcharts_3");
  let sub_total_array = [];
  let date_array = [];
  for (let yud = 0; yud < params["data2"]["date_per_day"].length; yud++) {
    var data_value =
      params["data2"]["date_per_day"][yud]["sub_total"].sum_sub_total != null
        ? params["data2"]["date_per_day"][yud]["sub_total"].sum_sub_total.split(
            "."
          )
        : "0";
    var date_value = params["data2"]["display_date"][yud].display_tanggal;
    var result = data_value[0];
    sub_total_array.push(result);
    date_array.push(date_value);
  }

  var height = parseInt(KTUtil.css(element, "height"));
  var labelColor = KTUtil.getCssVariableValue("--kt-gray-500");
  var borderColor = KTUtil.getCssVariableValue("--kt-gray-200");
  var baseColor = KTUtil.getCssVariableValue("--kt-info");
  var lightColor = KTUtil.getCssVariableValue("--kt-info-light");

  if (!element) {
    return;
  }

  var options = {
    series: [
      {
        name: "Net Profit",
        data: sub_total_array,
      },
    ],
    chart: {
      fontFamily: "inherit",
      type: "area",
      height: height,
      toolbar: {
        show: false,
      },
    },
    plotOptions: {},
    legend: {
      show: false,
    },
    dataLabels: {
      enabled: false,
    },
    fill: {
      type: "solid",
      opacity: 1,
    },
    stroke: {
      curve: "smooth",
      show: true,
      width: 3,
      colors: [baseColor],
    },
    xaxis: {
      categories: date_array,
      axisBorder: {
        show: false,
      },
      axisTicks: {
        show: false,
      },
      labels: {
        style: {
          colors: labelColor,
          fontSize: "12px",
        },
      },
      crosshairs: {
        position: "front",
        stroke: {
          color: baseColor,
          width: 1,
          dashArray: 3,
        },
      },
      tooltip: {
        enabled: true,
        formatter: undefined,
        offsetY: 0,
        style: {
          fontSize: "12px",
        },
      },
    },
    yaxis: {
      labels: {
        style: {
          colors: labelColor,
          fontSize: "12px",
        },
      },
    },
    states: {
      normal: {
        filter: {
          type: "none",
          value: 0,
        },
      },
      hover: {
        filter: {
          type: "none",
          value: 0,
        },
      },
      active: {
        allowMultipleDataPointsSelection: false,
        filter: {
          type: "none",
          value: 0,
        },
      },
    },
    tooltip: {
      style: {
        fontSize: "12px",
      },
      y: {
        formatter: function (val) {
          return "Rp. " + val;
        },
      },
    },
    colors: [lightColor],
    grid: {
      borderColor: borderColor,
      strokeDashArray: 4,
      yaxis: {
        lines: {
          show: true,
        },
      },
    },
    markers: {
      strokeColor: baseColor,
      strokeWidth: 3,
    },
  };

  var chart = new ApexCharts(element, options);
  chart.render();
}

function ajax_sales_order(params, Difference_In_Days) {
  $.ajax({
    url: base_url() + "dashboard_marketplace/show_sales_order/" + params,
    method: "GET",
    dataType: "JSON",
    success: function (data) {
      apex_chart(data);
      if (data["data1"].length - 2 == 0) {
        var tr_table = `<tr>`;
        tr_table += `<td colspan="5" align="center" >No data available in table</td>`;
        tr_table += `</tr>`;
        $("#show_sales_order").append(tr_table);
        $("#gross_sales_value").html(0);
        $("#orders_value").html(0);
        $("#items_sold_value").html(0);
        $("#avg_order_value").html(0);
        $("#avg_order_per_day").html(0);
        $("#percentage_gross_sale").html(
          '<div class="d-flex mb-2"><span class="badge badge-light fs-base"><span class="svg-icon svg-icon-5 svg-icon-danger ms-n1 me-1"></span><span>0%</span></span></div>'
        );
        $("#percentage_orders").html(
          '<div class="d-flex mb-2"><span class="badge badge-light fs-base"><span class="svg-icon svg-icon-5 svg-icon-danger ms-n1 me-1"></span><span>0%</span></span></div>'
        );
        $("#percentage_items_sold").html(
          '<div class="d-flex mb-2"><span class="badge badge-light fs-base"><span class="svg-icon svg-icon-5 svg-icon-danger ms-n1 me-1"></span><span>0%</span></span></div>'
        );
        $("#percentage_avg_order_value").html(
          '<div class="d-flex mb-2"><span class="badge badge-light fs-base"><span class="svg-icon svg-icon-5 svg-icon-danger ms-n1 me-1"></span><span>0%</span></span></div>'
        );
        $("#percentage_avg_order_per_days").html(
          '<div class="d-flex mb-2"><span class="badge badge-light fs-base"><span class="svg-icon svg-icon-5 svg-icon-danger ms-n1 me-1"></span><span>0%</span></span></div>'
        );
      } else {
        for (let i = 0; i < data["data1"].length - 2; i++) {
          var price = data["data1"][i].sub_total.split(".");
          var tr_table = `<tr>`;
          tr_table +=
            `
                        <td>
                            <div class="d-flex align-items-center ms-6">
                                <a class="symbol symbol-50px">
                                    <span class="symbol-label" style="background-image:url(assets/uploads/channels_image/` +
            data["data1"][i].source_icon +
            `);"></span>
                                </a>
                            </div>
                        </td>
                        <td>
                            <span class="text-gray-400 fs-3 fw-semibold">` +
            data["data1"][i].channel_name +
            `</span>
                        </td>`;
          tr_table +=
            `<td><span class="fs-3">` +
            data["data1"][i].orders +
            `</span></td>`;
          tr_table +=
            `<td><span class="fs-3">` +
            data["data1"][i].items_sold +
            `</span></td>`;
          tr_table +=
            `<td><span class="fs-3">` +
            formatRupiah(price[0], "Rp. ") +
            `</span></td>`;
          tr_table += `</tr>`;
          $("#show_sales_order").append(tr_table);
        }
        var new_gross_value =
          data["data1"][data["data1"].length - 1].sum_sub_total != null
            ? data["data1"][data["data1"].length - 1].sum_sub_total.split(".")
            : [0];
        var tr_table = `<tr>`;
        tr_table += `
                <td></td>
                <td><span class="text-gray-400 fs-3 fw-semibold">TOTAL</span></td>`;
        tr_table +=
          `<td><span class="fs-3 fw-bold">` +
          data["data1"][data["data1"].length - 1].sum_orders +
          `</span></td>`;
        tr_table +=
          `<td><span class="fs-3 fw-bold">` +
          data["data1"][data["data1"].length - 1].sum_items_sold +
          `</span></td>`;
        tr_table +=
          `<td><span class="fs-3 fw-bold">` +
          formatRupiah(new_gross_value[0], "Rp. ") +
          `</span></td>`;
        tr_table += `</tr>`;
        $("#show_sales_order tfoot").append(tr_table);
        $("#gross_sales_value").html(
          data["data1"][data["data1"].length - 1].sum_sub_total != null
            ? formatRupiah(new_gross_value[0])
            : 0
        );
        $("#orders_value").html(
          data["data1"][data["data1"].length - 1].sum_orders != null
            ? data["data1"][data["data1"].length - 1].sum_orders
            : 0
        );
        $("#items_sold_value").html(
          data["data1"][data["data1"].length - 1].sum_items_sold != null
            ? data["data1"][data["data1"].length - 1].sum_items_sold
            : 0
        );

        var previous_gross_sale =
          data["data1"][data["data1"].length - 2].p_sum_sub_total != null
            ? data["data1"][data["data1"].length - 2].p_sum_sub_total.split(".")
            : [0];

        if (previous_gross_sale[0] != 0) {
          var result_percentage_gross_sale =
            ((new_gross_value[0] - previous_gross_sale[0]) /
              previous_gross_sale[0]) *
            100;
        } else {
          var result_percentage_gross_sale = 0;
        }

        if (result_percentage_gross_sale > 0) {
          $("#percentage_gross_sale").html(
            '<span class="badge badge-light-success fs-base"><span class="svg-icon svg-icon-7 svg-icon-success ms-n1"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path opacity="0.5" d="M13 9.59998V21C13 21.6 12.6 22 12 22C11.4 22 11 21.6 11 21V9.59998H13Z" fill="currentColor" /><path d="M5.7071 7.89291C5.07714 8.52288 5.52331 9.60002 6.41421 9.60002H17.5858C18.4767 9.60002 18.9229 8.52288 18.2929 7.89291L12.7 2.3C12.3 1.9 11.7 1.9 11.3 2.3L5.7071 7.89291Z" fill="currentColor" /></svg></span><span>' +
              ambilDuaAngkaDiBelakangKoma(result_percentage_gross_sale) +
              "%</span></span>"
          );
        } else if (result_percentage_gross_sale < 0) {
          $("#percentage_gross_sale").html(
            '<span class="badge badge-light-danger fs-base"><span class="svg-icon svg-icon-5 svg-icon-danger ms-n1"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><rect opacity="0.5" x="11" y="18" width="13" height="2" rx="1" transform="rotate(-90 11 18)" fill="currentColor"></rect><path d="M11.4343 15.4343L7.25 11.25C6.83579 10.8358 6.16421 10.8358 5.75 11.25C5.33579 11.6642 5.33579 12.3358 5.75 12.75L11.2929 18.2929C11.6834 18.6834 12.3166 18.6834 12.7071 18.2929L18.25 12.75C18.6642 12.3358 18.6642 11.6642 18.25 11.25C17.8358 10.8358 17.1642 10.8358 16.75 11.25L12.5657 15.4343C12.2533 15.7467 11.7467 15.7467 11.4343 15.4343Z" fill="currentColor"></path></svg></span><span>' +
              ambilDuaAngkaDiBelakangKoma(
                Math.abs(result_percentage_gross_sale)
              ) +
              "%</span></span>"
          );
        } else {
          $("#percentage_gross_sale").html(
            '<div class="d-flex mb-2"><span class="badge badge-light fs-base"><span class="svg-icon svg-icon-5 svg-icon-danger ms-n1 me-1"></span><span>0%</span></span></div>'
          );
        }

        var new_sum_orders =
          data["data1"][data["data1"].length - 1].sum_orders != null
            ? data["data1"][data["data1"].length - 1].sum_orders.split(".")
            : [0];
        var previous_sum_orders =
          data["data1"][data["data1"].length - 2].p_sum_orders != null
            ? data["data1"][data["data1"].length - 2].p_sum_orders.split(".")
            : [0];

        if (previous_sum_orders[0] != 0) {
          var result_percentage_sum_orders =
            ((new_sum_orders[0] - previous_sum_orders[0]) /
              previous_sum_orders[0]) *
            100;
        } else {
          var result_percentage_sum_orders = 0;
        }

        if (result_percentage_sum_orders > 0) {
          $("#percentage_orders").html(
            '<span class="badge badge-light-success fs-base"><span class="svg-icon svg-icon-7 svg-icon-success ms-n1"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path opacity="0.5" d="M13 9.59998V21C13 21.6 12.6 22 12 22C11.4 22 11 21.6 11 21V9.59998H13Z" fill="currentColor" /><path d="M5.7071 7.89291C5.07714 8.52288 5.52331 9.60002 6.41421 9.60002H17.5858C18.4767 9.60002 18.9229 8.52288 18.2929 7.89291L12.7 2.3C12.3 1.9 11.7 1.9 11.3 2.3L5.7071 7.89291Z" fill="currentColor" /></svg></span><span>' +
              ambilDuaAngkaDiBelakangKoma(result_percentage_sum_orders) +
              "%</span></span>"
          );
        } else if (result_percentage_sum_orders < 0) {
          $("#percentage_orders").html(
            '<span class="badge badge-light-danger fs-base"><span class="svg-icon svg-icon-5 svg-icon-danger ms-n1"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><rect opacity="0.5" x="11" y="18" width="13" height="2" rx="1" transform="rotate(-90 11 18)" fill="currentColor"></rect><path d="M11.4343 15.4343L7.25 11.25C6.83579 10.8358 6.16421 10.8358 5.75 11.25C5.33579 11.6642 5.33579 12.3358 5.75 12.75L11.2929 18.2929C11.6834 18.6834 12.3166 18.6834 12.7071 18.2929L18.25 12.75C18.6642 12.3358 18.6642 11.6642 18.25 11.25C17.8358 10.8358 17.1642 10.8358 16.75 11.25L12.5657 15.4343C12.2533 15.7467 11.7467 15.7467 11.4343 15.4343Z" fill="currentColor"></path></svg></span><span>' +
              ambilDuaAngkaDiBelakangKoma(
                Math.abs(result_percentage_sum_orders)
              ) +
              "%</span></span>"
          );
        } else {
          $("#percentage_orders").html(
            '<div class="d-flex mb-2"><span class="badge badge-light fs-base"><span class="svg-icon svg-icon-5 svg-icon-danger ms-n1 me-1"></span><span>0%</span></span></div>'
          );
        }

        var new_sum_items_sold =
          data["data1"][data["data1"].length - 1].sum_items_sold.split(".");
        var previous_sum_items_sold =
          data["data1"][data["data1"].length - 2].p_sum_items_sold != null
            ? data["data1"][data["data1"].length - 2].p_sum_items_sold.split(
                "."
              )
            : [0];

        if (previous_sum_items_sold[0] != 0) {
          var result_percentage_sum_items_sold =
            ((new_sum_items_sold[0] - previous_sum_items_sold[0]) /
              previous_sum_items_sold[0]) *
            100;
        } else {
          var result_percentage_sum_items_sold = 0;
        }

        if (result_percentage_sum_items_sold > 0) {
          $("#percentage_items_sold").html(
            '<span class="badge badge-light-success fs-base"><span class="svg-icon svg-icon-7 svg-icon-success ms-n1"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path opacity="0.5" d="M13 9.59998V21C13 21.6 12.6 22 12 22C11.4 22 11 21.6 11 21V9.59998H13Z" fill="currentColor" /><path d="M5.7071 7.89291C5.07714 8.52288 5.52331 9.60002 6.41421 9.60002H17.5858C18.4767 9.60002 18.9229 8.52288 18.2929 7.89291L12.7 2.3C12.3 1.9 11.7 1.9 11.3 2.3L5.7071 7.89291Z" fill="currentColor" /></svg></span><span>' +
              ambilDuaAngkaDiBelakangKoma(result_percentage_sum_items_sold) +
              "%</span></span>"
          );
        } else if (result_percentage_sum_items_sold < 0) {
          $("#percentage_items_sold").html(
            '<span class="badge badge-light-danger fs-base"><span class="svg-icon svg-icon-5 svg-icon-danger ms-n1"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><rect opacity="0.5" x="11" y="18" width="13" height="2" rx="1" transform="rotate(-90 11 18)" fill="currentColor"></rect><path d="M11.4343 15.4343L7.25 11.25C6.83579 10.8358 6.16421 10.8358 5.75 11.25C5.33579 11.6642 5.33579 12.3358 5.75 12.75L11.2929 18.2929C11.6834 18.6834 12.3166 18.6834 12.7071 18.2929L18.25 12.75C18.6642 12.3358 18.6642 11.6642 18.25 11.25C17.8358 10.8358 17.1642 10.8358 16.75 11.25L12.5657 15.4343C12.2533 15.7467 11.7467 15.7467 11.4343 15.4343Z" fill="currentColor"></path></svg></span><span>' +
              ambilDuaAngkaDiBelakangKoma(
                Math.abs(result_percentage_sum_items_sold)
              ) +
              "%</span></span>"
          );
        } else {
          $("#percentage_items_sold").html(
            '<div class="d-flex mb-2"><span class="badge badge-light fs-base"><span class="svg-icon svg-icon-5 svg-icon-danger ms-n1 me-1"></span><span>0%</span></span></div>'
          );
        }

        var new_avg_order_value = new_gross_value[0] / new_sum_orders[0];
        var result_new_avg_order_value = Math.floor(new_avg_order_value);
        $("#avg_order_value").html(
          formatRupiah(result_new_avg_order_value.toString())
        );
        var previous_avg_order_value =
          previous_gross_sale[0] / previous_sum_orders[0];
        var result_percentage_avg_order_value =
          ((new_avg_order_value - previous_avg_order_value) /
            previous_avg_order_value) *
          100;

        if (result_percentage_avg_order_value > 0) {
          $("#percentage_avg_order_value").html(
            '<span class="badge badge-light-success fs-base"><span class="svg-icon svg-icon-7 svg-icon-success ms-n1"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path opacity="0.5" d="M13 9.59998V21C13 21.6 12.6 22 12 22C11.4 22 11 21.6 11 21V9.59998H13Z" fill="currentColor" /><path d="M5.7071 7.89291C5.07714 8.52288 5.52331 9.60002 6.41421 9.60002H17.5858C18.4767 9.60002 18.9229 8.52288 18.2929 7.89291L12.7 2.3C12.3 1.9 11.7 1.9 11.3 2.3L5.7071 7.89291Z" fill="currentColor" /></svg></span><span>' +
              ambilDuaAngkaDiBelakangKoma(result_percentage_avg_order_value) +
              "%</span></span>"
          );
        } else if (result_percentage_avg_order_value < 0) {
          $("#percentage_avg_order_value").html(
            '<span class="badge badge-light-danger fs-base"><span class="svg-icon svg-icon-5 svg-icon-danger ms-n1"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><rect opacity="0.5" x="11" y="18" width="13" height="2" rx="1" transform="rotate(-90 11 18)" fill="currentColor"></rect><path d="M11.4343 15.4343L7.25 11.25C6.83579 10.8358 6.16421 10.8358 5.75 11.25C5.33579 11.6642 5.33579 12.3358 5.75 12.75L11.2929 18.2929C11.6834 18.6834 12.3166 18.6834 12.7071 18.2929L18.25 12.75C18.6642 12.3358 18.6642 11.6642 18.25 11.25C17.8358 10.8358 17.1642 10.8358 16.75 11.25L12.5657 15.4343C12.2533 15.7467 11.7467 15.7467 11.4343 15.4343Z" fill="currentColor"></path></svg></span><span>' +
              ambilDuaAngkaDiBelakangKoma(
                Math.abs(result_percentage_avg_order_value)
              ) +
              "%</span></span>"
          );
        } else {
          $("#percentage_avg_order_value").html(
            '<div class="d-flex mb-2"><span class="badge badge-light fs-base"><span class="svg-icon svg-icon-5 svg-icon-danger ms-n1 me-1"></span><span>0%</span></span></div>'
          );
        }

        var result_days = Difference_In_Days + 1;
        var new_avg_order_per_days = new_sum_orders[0] / result_days;
        $("#avg_order_per_day").html(Math.floor(new_avg_order_per_days));
        var previous_avg_order_per_days = previous_sum_orders / result_days;

        if (previous_avg_order_per_days == 0) {
          var result_percentage_avg_order_per_days = 0;
        } else {
          var result_percentage_avg_order_per_days =
            ((new_avg_order_per_days - previous_avg_order_per_days) /
              previous_avg_order_per_days) *
            100;
        }

        if (result_percentage_avg_order_per_days > 0) {
          $("#percentage_avg_order_per_days").html(
            '<span class="badge badge-light-success fs-base"><span class="svg-icon svg-icon-7 svg-icon-success ms-n1"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path opacity="0.5" d="M13 9.59998V21C13 21.6 12.6 22 12 22C11.4 22 11 21.6 11 21V9.59998H13Z" fill="currentColor" /><path d="M5.7071 7.89291C5.07714 8.52288 5.52331 9.60002 6.41421 9.60002H17.5858C18.4767 9.60002 18.9229 8.52288 18.2929 7.89291L12.7 2.3C12.3 1.9 11.7 1.9 11.3 2.3L5.7071 7.89291Z" fill="currentColor" /></svg></span><span>' +
              ambilDuaAngkaDiBelakangKoma(
                result_percentage_avg_order_per_days
              ) +
              "%</span></span>"
          );
        } else if (result_percentage_avg_order_per_days < 0) {
          $("#percentage_avg_order_per_days").html(
            '<span class="badge badge-light-danger fs-base"><span class="svg-icon svg-icon-5 svg-icon-danger ms-n1"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><rect opacity="0.5" x="11" y="18" width="13" height="2" rx="1" transform="rotate(-90 11 18)" fill="currentColor"></rect><path d="M11.4343 15.4343L7.25 11.25C6.83579 10.8358 6.16421 10.8358 5.75 11.25C5.33579 11.6642 5.33579 12.3358 5.75 12.75L11.2929 18.2929C11.6834 18.6834 12.3166 18.6834 12.7071 18.2929L18.25 12.75C18.6642 12.3358 18.6642 11.6642 18.25 11.25C17.8358 10.8358 17.1642 10.8358 16.75 11.25L12.5657 15.4343C12.2533 15.7467 11.7467 15.7467 11.4343 15.4343Z" fill="currentColor"></path></svg></span><span>' +
              ambilDuaAngkaDiBelakangKoma(
                result_percentage_avg_order_per_days
              ) +
              "%</span></span>"
          );
        } else {
          $("#percentage_avg_order_per_days").html(
            '<div class="d-flex mb-2"><span class="badge badge-light fs-base"><span class="svg-icon svg-icon-5 svg-icon-danger ms-n1 me-1"></span><span>0%</span></span></div>'
          );
        }
      }
    },
  });
}

function ajax_order(params) {
  $.ajax({
    url: base_url() + "dashboard_marketplace/show/" + params,
    method: "GET",
    dataType: "JSON",
    success: function (data) {
      if (data.length - 1 == 0) {
        var tr_table = `<tr>`;
        tr_table += `<td colspan="6" align="center" >No data available in table</td>`;
        tr_table += `</tr>`;
        $("#show_order").append(tr_table);
        $("#total_order").html("0");
      } else {
        for (let i = 0; i < data.length - 1; i++) {
          var tr_table = `<tr>`;
          tr_table +=
            `
                        <td>
                            <div class="d-flex align-items-center ms-6">
                                <a class="symbol symbol-50px">
                                    <span class="symbol-label" style="background-image:url(assets/uploads/channels_image/` +
            data[i].source_icon +
            `);"></span>
                                </a>
                            </div>
                        </td>
                        <td>
                            <span class="text-gray-400 fs-3 fw-semibold">` +
            data[i].channel_name +
            `</span>
                        </td>`;
          if (data[i].pending_payment > 0) {
            tr_table +=
              `<td><span class="text-success fs-3">` +
              data[i].pending_payment +
              `</span></td>`;
          } else {
            tr_table +=
              `<td><span class="fs-3">` +
              data[i].pending_payment +
              `</span></td>`;
          }
          if (data[i].open_orders > 0) {
            tr_table +=
              `<td><span class="text-success fs-3">` +
              data[i].open_orders +
              `</span></td>`;
          } else {
            tr_table +=
              `<td><span class="fs-3">` + data[i].open_orders + `</span></td>`;
          }
          if (data[i].not_shipped > 0) {
            tr_table +=
              `<td><span class="text-success fs-3">` +
              data[i].not_shipped +
              `</span></td>`;
          } else {
            tr_table +=
              `<td><span class="fs-3">` + data[i].not_shipped + `</span></td>`;
          }
          if (data[i].ready_to_ship > 0) {
            tr_table +=
              `<td><span class="text-success fs-3">` +
              data[i].ready_to_ship +
              `</span></td>`;
          } else {
            tr_table +=
              `<td><span class="fs-3">` +
              data[i].ready_to_ship +
              `</span></td>`;
          }
          tr_table += `</tr>`;
          $("#show_order").append(tr_table);
        }
        var tr_table = `<tr>`;
        tr_table += `
                <td></td>
                <td><span class="text-gray-400 fs-3 fw-semibold">TOTAL</span></td>`;
        if (data[data.length - 1].sum_pending_payment > 0) {
          tr_table +=
            `<td><span class="text-success fs-3">` +
            data[data.length - 1].sum_pending_payment +
            `</span></td>`;
        } else {
          tr_table +=
            `<td><span class="fs-3">` +
            data[0].sum_pending_payment +
            `</span></td>`;
        }
        if (data[data.length - 1].sum_open_orders > 0) {
          tr_table +=
            `<td><span class="text-success fs-3">` +
            data[data.length - 1].sum_open_orders +
            `</span></td>`;
        } else {
          tr_table +=
            `<td><span class="fs-3">` +
            data[data.length - 1].sum_open_orders +
            `</span></td>`;
        }
        if (data[data.length - 1].sum_not_shipped > 0) {
          tr_table +=
            `<td><span class="text-success fs-3">` +
            data[data.length - 1].sum_not_shipped +
            `</span></td>`;
        } else {
          tr_table +=
            `<td><span class="fs-3">` +
            data[data.length - 1].sum_not_shipped +
            `</span></td>`;
        }
        if (data[data.length - 1].sum_ready_to_ship > 0) {
          tr_table +=
            `<td><span class="text-success fs-3">` +
            data[data.length - 1].sum_ready_to_ship +
            `</span></td>`;
        } else {
          tr_table +=
            `<td><span class="fs-3">` +
            data[data.length - 1].sum_ready_to_ship +
            `</span></td>`;
        }
        tr_table += `</tr>`;
        $("#show_order tfoot").append(tr_table);
        $("#total_order").html(data[data.length - 1].total_order);
      }
    },
  });
}

function ajax_show_data_inventory_display() {
  $.ajax({
    url: base_url() + "dashboard_marketplace/data_display",
    method: "GET",
    dataType: "JSON",
    success: function (data) {
      if (data.length - 1 == 0) {
        var tr_table = `<tr>`;
        tr_table += `<td colspan="7" align="center" >No data available in table</td>`;
        tr_table += `</tr>`;
        $("#show_display").append(tr_table);
        $("#all_display_data").html("0");
        $("#display_in_stock").html("0");
        $("#display_out_of_stock").html("0");
      } else {
        for (let i = 0; i < data.length - 1; i++) {
          var tr_table = `<tr>`;
          tr_table +=
            `
                        <td>
                            <div class="d-flex align-items-center ms-6">
                                <a class="symbol symbol-50px">
                                    <span class="symbol-label" style="background-image:url(assets/uploads/channels_image/` +
            data[i].source_icon +
            `);"></span>
                                </a>
                            </div>
                        </td>
                        <td>
                            <span class="text-gray-400 fs-3 fw-semibold">` +
            data[i].channel_name +
            `</span>
                        </td>`;
          tr_table +=
            `<td><span class="fs-3">` +
            data[i].all_inv_display +
            `</span></td>`;
          tr_table += `<td><span class="fs-3">` + data[i].live + `</span></td>`;
          tr_table +=
            `<td><span class="fs-3">` + data[i].inactive + `</span></td>`;
          tr_table +=
            `<td><span class="fs-3">` + data[i].pending_action + `</span></td>`;
          tr_table +=
            `<td><span class="fs-3">` + data[i].out_of_stock + `</span></td>`;
          tr_table += `</tr>`;
          $("#show_display").append(tr_table);
        }
        $("#all_display_data").html(data[data.length - 1].all_sku);
        $("#display_in_stock").html(data[data.length - 1].count_in_stock);
        $("#display_out_of_stock").html(data[data.length - 1].count_of_stock);
      }
    },
  });
}

function ajax_show_data_inventory_shadow() {
  $.ajax({
    url: base_url() + "dashboard_marketplace/data_shadow",
    method: "GET",
    dataType: "JSON",
    success: function (data) {
      if (data.length - 1 == 0) {
        var tr_table = `<tr>`;
        tr_table += `<td colspan="7" align="center" >No data available in table</td>`;
        tr_table += `</tr>`;
        $("#show_shadow").append(tr_table);
        $("#all_shadow_data").html("0");
        $("#in_stock_shadow_data").html("0");
        $("#out_of_stock_shadow_data").html("0");
      } else {
        for (let i = 0; i < data.length - 1; i++) {
          var tr_table = `<tr>`;
          tr_table +=
            `
                        <td>
                            <div class="d-flex align-items-center ms-6">
                                <a class="symbol symbol-50px">
                                    <span class="symbol-label" style="background-image:url(assets/uploads/channels_image/` +
            data[i].source_icon +
            `);"></span>
                                </a>
                            </div>
                        </td>
                        <td>
                            <span class="text-gray-400 fs-3 fw-semibold">` +
            data[i].channel_name +
            `</span>
                        </td>`;
          tr_table +=
            `<td><span class="fs-3">` +
            data[i].all_inv_display +
            `</span></td>`;
          tr_table += `<td><span class="fs-3">` + data[i].live + `</span></td>`;
          tr_table +=
            `<td><span class="fs-3">` + data[i].inactive + `</span></td>`;
          tr_table +=
            `<td><span class="fs-3">` + data[i].pending_action + `</span></td>`;
          tr_table +=
            `<td><span class="fs-3">` + data[i].out_of_stock + `</span></td>`;
          tr_table += `</tr>`;
          $("#show_shadow").append(tr_table);
        }
        $("#all_shadow_data").html(data[data.length - 1].all_sku);
        $("#in_stock_shadow_data").html(data[data.length - 1].count_in_stock);
        $("#out_of_stock_shadow_data").html(
          data[data.length - 1].count_of_stock
        );
      }
    },
  });
}

function ajax_show_data_inventory_group() {
  $.ajax({
    url: base_url() + "dashboard_marketplace/data_inventory_group",
    method: "GET",
    dataType: "JSON",
    success: function (data) {
      console.log(data);
    },
  });
}

function ajax_show_data_pending_actions() {
  $.ajax({
    url: base_url() + "dashboard_marketplace/data_pending_action",
    method: "GET",
    dataType: "JSON",
    success: function (data) {
      console.log(data);
    },
  });
}
