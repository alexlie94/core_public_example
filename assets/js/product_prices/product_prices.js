$(document).ready(function () {
  var baseurl = base_url() + "product_prices/show";
  var column = [
    { data: "id" },
    { data: "batch_name" },
    { data: "batch_description" },
    { data: "batch_location" },
    { data: "start_date" },
    { data: "end_date" },
    { data: "action", width: "17%" },
  ];
  ajax_crud_table_without_number(baseurl, column);
  sweetAlertConfirm();
  libraryInput();

  $("#search_by_value").hide();
  $("#select_batch_location").hide();
  $("#batch_date").hide();
  $(document).on("change", "#search_by", function () {
    var getSelect = $("#search_by").val();
    if (getSelect == "admins_ms_sources_id") {
      $("#select_batch_location").show();
      $("#batch_location").select2({
        minimumResultsForSearch: Infinity,
      });
      $("#search_by_value").hide();
      $("#batch_date").hide();
    } else if (getSelect == "start_date") {
      $("#batch_date").show();
      $("#search_by_value").hide();
      $("#select_batch_location").hide();
    } else if (getSelect == "end_date") {
      $("#batch_date").show();
      $("#search_by_value").hide();
      $("#select_batch_location").hide();
    } else if (getSelect == "id") {
      $("#search_by_value").show();
      $("#select_batch_location").hide();
      $("#batch_date").hide();
    } else if (getSelect == "batch_name") {
      $("#search_by_value").show();
      $("#select_batch_location").hide();
      $("#batch_date").hide();
    } else if (getSelect == "batch_description") {
      $("#search_by_value").show();
      $("#select_batch_location").hide();
      $("#batch_date").hide();
    } else {
      $("#search_by_value").hide();
      $("#select_batch_location").hide();
      $("#batch_date").hide();
    }
  });
  $("#kt_datepicker_3").flatpickr({
    enableTime: true,
    dateFormat: "Y-m-d H:i:s",
    mode: "range",
  });
  $("#batch_location").select2({
    minimumResultsForSearch: Infinity,
  });

  $(document).on("click", "#buttonDeleted", function () {
    $(this).parent().parent().addClass("deleted");

    $("#kt_datatable_vertical_scroll")
      .DataTable()
      .rows(".deleted")
      .remove()
      .draw();
  });

  $(document).on("click", "#btnAdd", function () {
    buttonAction($(this), "#modalLarge2");

    $("select").select2({
      minimumResultsForSearch: Infinity,
    });

    checkBox = document
      .getElementById("endDate")
      .addEventListener("click", (event) => {
        if (event.target.checked) {
          $("input:checkbox").val("1");
          $("#end_date_status").val("1");
        } else {
          $("input:checkbox").val("0");
          $("#end_date_status").val("0");
        }
      });

    const currentDate = new Date();
    // Mengambil informasi dari objek tanggal
    const year = currentDate.getFullYear();
    const month = currentDate.getMonth() + 1; // Ingat bahwa indeks bulan dimulai dari 0
    const day = currentDate.getDate() - 1;

    $("#start_date").flatpickr({
      disable: [
        {
          from: "1550-01-01",
          to: year + "-" + month + "-" + day,
        },
      ],
      enableTime: true,
      dateFormat: "Y-m-d H:i:s",
    });
    $("#end_date").flatpickr({
      disable: [
        {
          from: "1550-01-01",
          to: year + "-" + month + "-" + day,
        },
      ],
      enableTime: true,
      dateFormat: "Y-m-d H:i:s",
    });

    $("#formatError").hide();
  });

  $(document).on("change", "#upload_data", function () {
    var file = this.files[0];
    $("#btnProcessModal").hide();
    $("#kt_datatable_vertical_scroll tbody").empty();

    if (typeof file != "undefined") {
      var reader = new FileReader();
      reader.readAsBinaryString(file);
      reader.onload = function (dataAll) {
        var rows = dataAll.target.result.split("\n");
        var jsonData = [];
        var headers = [];
        for (var i = 0; i < rows.length; i++) {
          var cells = rows[i].split(",");
          var rowData = {};
          for (var j = 0; j < cells.length; j++) {
            if (i == 0) {
              var headerName = cells[j].trim();
              headers.push(headerName);
            } else {
              var key = headers[j];
              if (key) {
                rowData[key] = cells[j].trim();
              }
            }
          }

          if (i != 0) {
            jsonData.push(rowData);
          }
        }

        var headersFormatCsv = [
          "Product_Id",
          "Price",
          "Sale_Price",
          "Offline_Price",
        ];

        var no = 1;
        var no2 = 1;

        var dataUpload = [];

        dataUpload.push({ name: "_token", value: getCookie() });
        dataUpload.push({
          name: "dataUpload",
          value: JSON.stringify(jsonData),
        });
        $.ajax({
          url: base_url() + "product_prices/upload_data",
          method: "POST",
          dataType: "JSON",
          async: false,
          data: dataUpload,
          success: function (result) {
            var getJsonData = result.data;

            if (headersFormatCsv.toString() == headers.toString()) {
              $("#formatError").hide();

              var check_validate = [];

              for (let i = 0; i < getJsonData.length; i++) {
                check_validate.push(getJsonData[i].validate);
              }

              for (let i = 0; i < getJsonData.length; i++) {
                var tr_table = `<tr>`;
                tr_table +=
                  `
                  <td>` +
                  no +
                  `</td>
                  <td> <input type="hidden" name="product_id_1[]" value="` +
                  getJsonData[i].Product_Id +
                  `">` +
                  getJsonData[i].Product_Id +
                  `</td>
                  <td> <input type="hidden" name="price_1[]" value="` +
                  getJsonData[i].Price +
                  `">` +
                  getJsonData[i].Price +
                  `</td>
                  <td> <input type="hidden" name="sale_price_1[]" value="` +
                  getJsonData[i].Sale_Price +
                  `">` +
                  getJsonData[i].Sale_Price +
                  `</td>
                  <td> <input type="hidden" name="offline_price_1[]" value="` +
                  getJsonData[i].Offline_Price +
                  `">` +
                  getJsonData[i].Offline_Price +
                  `</td>
                </tr>`;

                var tr_table2 = `<tr>`;
                tr_table2 +=
                  `
                  <td>` +
                  no2 +
                  `</td>
                  <td>` +
                  getJsonData[i].Product_Id +
                  `</td>
                  <td>` +
                  getJsonData[i].Price +
                  `</td>
                  <td>` +
                  getJsonData[i].Sale_Price +
                  `</td>
                  <td>` +
                  getJsonData[i].Offline_Price +
                  `</td>
                </tr>`;

                if (check_validate.includes(2)) {
                  $("#kt_datatable_vertical_scroll").append(tr_table2);
                  $("#btnProcessModal").hide();
                } else {
                  $("#kt_datatable_vertical_scroll").append(tr_table);
                  $("#btnProcessModal").show();
                }

                no++;
                no2++;
              }
            } else {
              $("#formatError").show();
            }
          },
        });
      };
    } else {
      $("#show_data_preview").html("");
    }
  });

  $(document).on("click", ".btnEdit", function () {
    buttonAction($(this), "#modalLarge2");

    $("select").select2({
      minimumResultsForSearch: Infinity,
    });

    $("#btnStatus").on("click", function () {
      var status = $("#batch_status").val();
      var text = "disabled";
      if (status == "1") {
        text = "enable";
      }
      Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, " + text + " it!",
      }).then((result) => {
        if (result.isConfirmed) {
          var dataUpload = [];

          dataUpload.push({ name: "_token", value: getCookie() });
          dataUpload.push({ name: "batch_id", value: $("#id").val() });
          dataUpload.push({
            name: "batch_status",
            value: $("#batch_status").val(),
          });
          $.ajax({
            url: base_url() + "product_prices/update_status_batch",
            method: "POST",
            dataType: "JSON",
            async: false,
            data: dataUpload,
            success: function (result) {
              if (result.error == false) {
                $("#modalLarge2").modal("hide");
                Swal.fire(
                  "" + text + "!",
                  "Your file has been " + text + ".",
                  "success"
                );
              }
            },
          });
        }
      });
    });

    checkBox = document
      .getElementById("endDate")
      .addEventListener("click", (event) => {
        if (event.target.checked) {
          $("input:checkbox").val("1");
        } else {
          $("input:checkbox").val("0");
        }
      });

    const currentDate = new Date();
    // Mengambil informasi dari objek tanggal
    const year = currentDate.getFullYear();
    const month = currentDate.getMonth() + 1; // Ingat bahwa indeks bulan dimulai dari 0
    const day = currentDate.getDate() - 1;

    $("#start_date").flatpickr({
      disable: [
        {
          from: "1550-01-01",
          to: year + "-" + month + "-" + day,
        },
      ],
      enableTime: true,
      dateFormat: "Y-m-d H:i:s",
    });
    $("#end_date").flatpickr({
      disable: [
        {
          from: "1550-01-01",
          to: year + "-" + month + "-" + day,
        },
      ],
      enableTime: true,
      dateFormat: "Y-m-d H:i:s",
    });

    $("#formatError").hide();

    var inputDateTime = $("#start_date").val();
    var inputDate = new Date(inputDateTime.replace(/-/g, "/"));

    if (isValidDateTime(inputDate)) {
      var currentDateTime = new Date();

      if (inputDate > currentDateTime) {
        $("#btnProcessModal").show();
      } else {
        $("#btnProcessModal").hide();
      }
    } else {
      alert("Invalid date format. Please use Y-m-d H:i:s format.");
    }

    function isValidDateTime(date) {
      return date instanceof Date && !isNaN(date);
    }
  });

  $(document).on("click", ".btnView", function () {
    buttonAction($(this), "#modalLarge2");
    $("#btnProcessModal").hide();
    $("#batch_name").attr("disabled", true);
    $("#batch_description").attr("disabled", true);
    $(".batch_location_target").attr("disabled", true);
    $("#start_date").attr("disabled", true);
    $("#end_date").attr("disabled", true);
    $("#endDate").attr("disabled", true);
    $("#btnStatus").hide();
    $("#upload_data").attr("disabled", true);
    $("#formatError").hide();
  });

  $(document).on("click", "#btnDownloadView", function () {
    if ($("select[name=search_by]").val() != null) {
      var searchby = $("select[name=search_by]").val();
    } else {
      var searchby = "";
    }

    if ($("select[name=batch_location]").val() != null) {
      var batchLocation = $("select[name=batch_location]").val();
    } else {
      var batchLocation = "";
    }

    if ($("input[name=searchDate]").val() != "") {
      var serachDate = $("input[name=searchDate]").val().split("to");
      var searchFrom = serachDate[0];
      var searchTo = serachDate[1];
    } else {
      var searchFrom = "";
      var searchTo = "";
    }

    var searchby1 = $("input[name=search_by1]").val();

    window.location.href =
      base_url() +
      "product_prices/download_view" +
      "?batch_location=" +
      batchLocation +
      "&searchby=" +
      searchby +
      "&searchby1=" +
      searchby1 +
      "&from=" +
      searchFrom +
      "&to=" +
      searchTo;
  });

  $(document).on("click", "#btnCloseModalFullscreen", function () {
    $("#modalLarge2").modal("hide");
  });

  $(document).on("click", "#btnProcessModal", function () {
    var items = $("#kt_datatable_vertical_scroll tbody tr").length;
    if (items < 1) {
      sweetAlertMessageWithConfirmNotShowCancelButton(
        "Batch item not found !!!",
        ""
      );
      return false;
    }
    var btnCloseModal = "#btnCloseModal";
    var textButton = $(this).text();
    var btn = $(this);
    var url = $("#form").data("url");
    var data = $("#form").serializeArray(); // convert form to array
    data.push({ name: "_token", value: getCookie() });
    $.ajax({
      url: url,
      method: "POST",
      dataType: "JSON",
      async: false,
      data: $.param(data),
      beforeSend: function () {
        loadingButton(btn);
        disabledButton($(btnCloseModal));
      },
      success: function (response) {
        if (!response.success) {
          if (!response.validate) {
            $.each(response.messages, function (key, value) {
              addErrorValidation(key, value);
            });
          }
        } else {
          if (response.type == "insert") {
            if (typeof response.data != "undefined") {
              addDataOption(response.data);
            }
            reset_input();
            modalAutoClose(closeModal);
          }

          var draw = $(".draw_datatables").val();
          draw++;
          $(".draw_datatables").val(draw);
          tbl.ajax.reload();
        }
        loadingButtonOff(btn, textButton);
        enabledButton($(btnCloseModal));
        if (response.type == "update") {
          if (response.success) {
            var closeModal =
              btnCloseModal != "#btnCloseModal" ? btnCloseModal : "#modalLarge";
            modalAutoClose(closeModal);
          }
        }

        if (response.validate) {
          message(response.success, response.messages);
          $("#modalLarge2").modal("hide");
        }
      },
      error: function (jqXHR, textStatus, errorThrown) {
        switch (jqXHR.status) {
          case 401:
            sweetAlertMessageWithConfirmNotShowCancelButton(
              "Your session has expired or invalid. Please relogin",
              function () {
                window.location.href = base_url();
              }
            );
            break;

          default:
            sweetAlertMessageWithConfirmNotShowCancelButton(
              "We are sorry, but you do not have access to this service",
              function () {
                location.reload();
              }
            );
            break;
        }
      },
    });
  });
});
