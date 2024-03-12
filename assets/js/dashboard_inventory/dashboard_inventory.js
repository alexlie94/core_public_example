$(document).ready(function () {
  var baseurl = base_url() + "dashboard_inventory/show";
  var tableYudha = "dashboard_inventory";
  var column = [
    { data: "id" },
    { data: "product_name" },
    { data: "supplier_name" },
    { data: "brand_name" },
    { data: "category_name" },
    { data: "sku" },
    { data: "quantity"},
  ];
  ajax_crud_table(baseurl, column, tableYudha);

  // $("#kt_datatable_footer_callback").DataTable({
  //   footerCallback: function (row, data, start, end, display) {
  //     var api = this.api(),
  //       data;

  //     // Remove the formatting to get integer data for summation
  //     var intVal = function (i) {
  //       return typeof i === "string"
  //         ? i.replace(/[\$,]/g, "") * 1
  //         : typeof i === "number"
  //         ? i
  //         : 0;
  //     };

  //     // Total over all pages
  //     var total = api
  //       .column(4)
  //       .data()
  //       .reduce(function (a, b) {
  //         return intVal(a) + intVal(b);
  //       }, 0);

  //     // Total over this page
  //     var pageTotal = api
  //       .column(4, {
  //         page: "current",
  //       })
  //       .data()
  //       .reduce(function (a, b) {
  //         return intVal(a) + intVal(b);
  //       }, 0);

  //     // Update footer
  //     $(api.column(4).footer()).html(
  //       "$" + pageTotal + " ( $" + total + " total)"
  //     );
  //   },
  // });

  var elements = [].slice.call(
    document.querySelectorAll('[data-kt-daterangepicker="true"]')
  );
  var start = moment().subtract(29, "days");
  var end = moment();

  elements.map(function (element) {
    if (element.getAttribute("data-kt-initialized") === "1") {
      return;
    }

    var display = element.querySelector("div");
    var attrOpens = element.hasAttribute("data-kt-daterangepicker-opens")
      ? element.getAttribute("data-kt-daterangepicker-opens")
      : "left";
    var range = element.getAttribute("data-kt-daterangepicker-range");

    var cb = function (start, end) {
      var current = moment();

      if (display) {
        if (current.isSame(start, "day") && current.isSame(end, "day")) {
          display.innerHTML = start.format("D MMM YYYY");
        } else {
          display.innerHTML =
            start.format("D MMM YYYY") + " - " + end.format("D MMM YYYY");
        }
      }
    };

    if (range === "today") {
      start = moment();
      end = moment();
    }

    $(element).daterangepicker(
      {
        startDate: start,
        endDate: end,
        opens: attrOpens,
        ranges: {
          Today: [moment(), moment()],
          Yesterday: [
            moment().subtract(1, "days"),
            moment().subtract(1, "days"),
          ],
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

    element.setAttribute("data-kt-initialized", "1");
  });

  // // Check if jQuery included
  // if (typeof jQuery == 'undefined') {
  //     return;
  // }

  // // Check if daterangepicker included
  // if (typeof $.fn.daterangepicker === 'undefined') {
  //     return;
  // }
});