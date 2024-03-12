var pushToTagify = [];
let arrayProduct = [];
let qty_sku = [];
let sku_value = [];
$(document).ready(function () {
  var baseurl = base_url() + "tito2/show";
  var column = [
    { data: "id" },
    { data: "to_number" },
    { data: "ti_number" },
    { data: "created_at" },
    { data: "qty" },
    { data: "qty_received" },
    { data: "assignee" },
    {
      data: "status",
      render: function (data, type, row) {
        if (data == "1") {
          var span = '<span class="badge badge-light-success">Open';
        } else if (data == "2") {
          var span = '<span class="badge badge-light-warning">In Progress';
        } else {
          var span = '<span class="badge badge-light-danger">Close';
        }
        return span + "</span>";
      },
    },
    { data: "action", width: "17%" },
  ];

  ajax_crud_table(baseurl, column, "table-data", "tito2");
  sweetAlertConfirm();
  libraryInput();

  $("#search_by_value").hide();

  $("#batch_date").hide();
  $(document).on("change", "#search_by", function () {
    var getSelect = $("#search_by").val();
    if (getSelect == "ti_number") {
      $("#search_by_value").show();
      $("#batch_date").hide();
    } else if (getSelect == "created_at") {
      $("#batch_date").show();
      $("#search_by_value").hide();
    } else if (getSelect == "assignee") {
      $("#search_by_value").show();
      $("#batch_date").hide();
    } else {
      $("#search_by_value").hide();
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

  $(document).on("change", "#from_warehouse", function () {
    var id = $(this).val();
    $.ajax({
      url: base_url() + "tito2/getWarehouse/" + id,
      type: "GET",
      dataType: "json",
      success: function (data) {
        var options = '<option value=""></option>';

        $.each(data, function (index, val) {
          options +=
            '<option value="' +
            val.id +
            '">' +
            val.warehouse_name +
            "</option>";
        });
        $("#to_warehouse").html(options);
      },
    });
  });

  $(document).on("click", "#btnAdd,.btnEdit", function () {
    buttonAction($(this), "#modalLarge2");

    $("#new_display_show_tito").hide();

    $("#btnProcessModal").hide();

    $(document).on("click", "#next_stepper", function () {
      var from_warehouse_val = $("#from_warehouse").val();
      $("#btnMassUpload").attr("data-warehouse", from_warehouse_val);
    });

    // Stepper lement
    var element = document.querySelector("#kt_stepper_example_basic");
    var stepper = new KTStepper(element);
    stepper.on("kt.stepper.next", function (stepper) {
      var from_warehouse = $("#from_warehouse").val();
      var to_warehouse = $("#to_warehouse").val();
      var assignee = $("#assignee").val();
      var desc = $("#desc").val();
      if (from_warehouse == "") {
        toastr.warning("From Warehouse is Required", "", {
          progressBar: !0,
          timeOut: 2000,
        });
      } else if (to_warehouse == "") {
        toastr.warning("To Warehouse is Required", "", {
          progressBar: !0,
          timeOut: 2000,
        });
      } else if (assignee == "") {
        toastr.warning("Assignee is Required", "", {
          progressBar: !0,
          timeOut: 2000,
        });
      } else {
        stepper.goNext(); // go next step

        $.ajax({
          url: base_url() + "tito2/getWarehouseById/" + from_warehouse,
          type: "GET",
          dataType: "json",
          success: function (data) {
            $("#from_warehouse_2").html(data.warehouse_name);
          },
        });

        $.ajax({
          url: base_url() + "tito2/getWarehouseById/" + to_warehouse,
          type: "GET",
          dataType: "json",
          success: function (data) {
            $("#to_warehouse_2").html(data.warehouse_name);
          },
        });

        $("#assignee_2").html(assignee);
        $("#desc_2").html(desc);
      }

      $(document).on("click", "#submit_stepper", function () {
        let validasi_qty = [];
        let return_qty = [];
        for (let yb = 0; yb < $(".qty_received").length; yb++) {
          var qty_input = $("#qty_received_" + yb).val();
          var qty_data = $("#qty_sku_" + yb).val();
          var sku = $("#qty_received_" + yb).attr("data-sku");
          if (parseInt(qty_input) < parseInt(qty_data)) {
            data_array_return = { sku: sku, validate: 1 };
            return_qty.push(data_array_return);
          } else {
            data_array_return = { sku: sku, validate: 0 };
            return_qty.push(data_array_return);
          }

          if (parseInt(qty_input) > parseInt(qty_data)) {
            data_array = { sku: sku, validate: 0 };
            validasi_qty.push(data_array);
          } else {
            data_array = { sku: sku, validate: 1 };
            validasi_qty.push(data_array);
          }
        }

        const hasZeroValidate = validasi_qty.some(
          (item) => item.validate === 0
        );
        const hasOneValidate = return_qty.some((item) => item.validate === 1);

        if (hasZeroValidate) {
          const filteredData = validasi_qty.filter(
            (item) => item.validate === 0
          );
          const skusWithZeroValidate = filteredData.map((item) => item.sku);
          for (let val = 0; val < skusWithZeroValidate.length; val++) {
            toastr.warning(
              "Quantity SKU " + skusWithZeroValidate[val] + " Not Enough",
              "",
              {
                progressBar: !0,
                timeOut: 2000,
              }
            );
          }
        } else if (hasOneValidate) {
          buttonAction($(this), "#modalLarge3");
          let nox = 1;
          let noz = 0;
          for (let ix = 0; ix < $(".qty_received").length; ix++) {
            var qty_input = $("#qty_received_" + ix).val();
            var qty_data = $("#qty_sku_" + ix).val();
            var summation = parseInt(qty_data) - parseInt(qty_input);
            let tr_table = ` <tr>`;
            if (parseInt(qty_input) < parseInt(qty_data)) {
              tr_table +=
                `<td style='text-align: center;vertical-align: middle;'>` +
                nox +
                `<input class="tito_detail_id" type="hidden" id="tito_detail_id_` +
                ix +
                `" name="tito_detail_id[]" value="` +
                $("#tito_detail_id_" + ix).val() +
                `"></td><td style='vertical-align: middle;'>` +
                $("#product_sku_" + ix).val() +
                `<input class="product_sku_return" type="hidden" id="product_sku_return_` +
                ix +
                `" name="product_sku_return[]" value="` +
                $("#product_sku_" + ix).val() +
                `"><input type="hidden" id="product_sku_return_validate_` +
                noz +
                `" name="product_sku_return_validate[]" value="` +
                $("#product_sku_" + ix).val() +
                `">
                </td><td style='vertical-align: middle;'><input type="hidden" id="product_name_return_` +
                ix +
                `" name="product_name_return[]" value="` +
                $("#product_name_" + ix).val() +
                `">` +
                $("#product_name_" + ix).val() +
                `</td><td style='vertical-align: middle;'><input type="hidden" id="brand_name_return_` +
                ix +
                `" name="brand_name_return[]" value="` +
                $("#brand_name_" + ix).val() +
                `">` +
                $("#brand_name_" + ix).val() +
                `</td><td style='vertical-align: middle;'><input type="hidden" id="qty_sku_return_` +
                ix +
                `" name="qty_sku_return[]" value="` +
                $("#qty_sku_" + ix).val() +
                `">` +
                $("#qty_sku_" + ix).val() +
                `</td>` +
                `<td style='vertical-align: middle;'><input type="hidden" id="qty_received_return_` +
                ix +
                `" name="qty_received_return[]" value="` +
                $("#qty_received_" + ix).val() +
                `">` +
                $("#qty_received_" + ix).val() +
                `</td><td style='vertical-align: middle;'><input type="hidden" id="qty_lost_return_` +
                ix +
                `" name="qty_lost_return[]" value="` +
                summation +
                `">` +
                summation +
                `</td><td style='vertical-align: middle;'><textarea type="text" id="desc_return_` +
                noz +
                `" name="desc_return[]" class="form-control mb-3 mb-lg-0 desc_return" rows="1" placeholder="Description" data-type="input" autocomplete="off"></textarea></td><input type="hidden" id="warehouse_name_return_` +
                ix +
                `" name="warehouse_name_return[]" value="` +
                $("#warehouse_name_" + ix).val() +
                `">`;
              noz++;
              nox++;
            } else {
              tr_table +=
                `<input class="tito_detail_id" type="hidden" id="tito_detail_id_` +
                ix +
                `" name="tito_detail_id[]" value="` +
                $("#tito_detail_id_" + ix).val() +
                `"><input class="product_sku_return" type="hidden" id="product_sku_return_` +
                ix +
                `" name="product_sku_return[]" value="` +
                $("#product_sku_" + ix).val() +
                `"><input type="hidden" id="product_name_return_` +
                ix +
                `" name="product_name_return[]" value="` +
                $("#product_name_" + ix).val() +
                `"><input type="hidden" id="brand_name_return_` +
                ix +
                `" name="brand_name_return[]" value="` +
                $("#brand_name_" + ix).val() +
                `"><input type="hidden" id="warehouse_name_return_` +
                ix +
                `" name="warehouse_name_return[]" value="` +
                $("#warehouse_name_" + ix).val() +
                `"><input type="hidden" id="qty_sku_return_` +
                ix +
                `" name="qty_sku_return[]" value="` +
                $("#qty_sku_" + ix).val() +
                `"><input type="hidden" id="qty_received_return_` +
                ix +
                `" name="qty_received_return[]" value="` +
                $("#qty_received_" + ix).val() +
                `">`;
            }
            tr_table += `</tr>`;
            $("#show_qty_not_match tbody").append(tr_table);
          }
        } else {
          $("#submit_stepper").hide();
          $("#btnProcessModal").show();
          $("#display_show_tito").hide();
          $("#new_display_show_tito").show();
          let nox = 1;
          let noz = 0;
          for (let ix = 0; ix < $(".qty_received").length; ix++) {
            var qty_input = $("#qty_received_" + ix).val();
            var qty_data = $("#qty_sku_" + ix).val();
            var summation = parseInt(qty_data) - parseInt(qty_input);
            let tr_table = ` <tr>`;
            tr_table +=
              `<td style='text-align: center;vertical-align: middle;'>` +
              nox +
              `<input class="tito_detail_id" type="hidden" id="tito_detail_id_` +
              ix +
              `" name="tito_detail_id[]" value="` +
              $("#tito_detail_id_" + ix).val() +
              `"></td><td style='vertical-align: middle;'>` +
              $("#product_sku_" + ix).val() +
              `<input class="product_sku_return" type="hidden" id="product_sku_return_` +
              ix +
              `" name="product_sku_return[]" value="` +
              $("#product_sku_" + ix).val() +
              `"><input type="hidden" id="product_sku_return_validate_` +
              noz +
              `" name="product_sku_return_validate[]" value="` +
              $("#product_sku_" + ix).val() +
              `">
                </td><td style='vertical-align: middle;'><input type="hidden" id="product_name_return_` +
              ix +
              `" name="product_name_return[]" value="` +
              $("#product_name_" + ix).val() +
              `">` +
              $("#product_name_" + ix).val() +
              `</td><td style='vertical-align: middle;'><input type="hidden" id="brand_name_return_` +
              ix +
              `" name="brand_name_return[]" value="` +
              $("#brand_name_" + ix).val() +
              `">` +
              $("#brand_name_" + ix).val() +
              `</td><td style='vertical-align: middle;'><input type="hidden" id="qty_sku_return_` +
              ix +
              `" name="qty_sku_return[]" value="` +
              $("#qty_sku_" + ix).val() +
              `">` +
              $("#qty_sku_" + ix).val() +
              `</td>` +
              `<td style='vertical-align: middle;'><input type="hidden" id="qty_received_return_` +
              ix +
              `" name="qty_received_return[]" value="` +
              $("#qty_received_" + ix).val() +
              `">` +
              $("#qty_received_" + ix).val() +
              `</td><td style='vertical-align: middle;'><input type="hidden" id="qty_lost_return_` +
              ix +
              `" name="qty_lost_return[]" value="0">0</td><td style='vertical-align: middle;'><textarea type="text" id="desc_return_` +
              ix +
              `" name="desc_return[]" class="form-control form-control-solid mb-3 mb-lg-0 desc_return" rows="1" placeholder="No Description" data-type="input" autocomplete="off" readonly></textarea></td><input type="hidden" id="warehouse_name_return_` +
              ix +
              `" name="warehouse_name_return[]" value="` +
              $("#warehouse_name_" + ix).val() +
              `">`;
            noz++;
            nox++;
            tr_table += `</tr>`;
            $("#new_display_show_tito tbody").append(tr_table);
          }
        }
      });
    });
    stepper.on("kt.stepper.previous", function (stepper) {
      stepper.goPrevious(); // go previous step
    });
  });

  $(document).on("click", "#btn_add_sku", function () {
    var warehouse_id = $("#from_warehouse").val();
    let url = base_url() + "tito2/productList/" + warehouse_id;
    buttonAction($(this), "#modalLarge3");

    let data = [{ name: "_token", value: getCookie() }];

    let dataChecked = [];

    $.ajax({
      url: url,
      method: "POST",
      dataType: "JSON",
      data: $.param(data),
      async: false,
      success: function (response) {
        let getJsonData = response.data;
        let skuExits = [];

        $("#show_tito tbody tr").each(function (i, x) {
          skuExits.push($(x).attr("data-sku"));
        });

        var filteredData = getJsonData.filter(function (item) {
          return !skuExits.includes(item.product_sku);
        });

        for (let i = 0; i < filteredData.length; i++) {
          let arrayData = [
            { product_id: filteredData[i].product_id },
            { product_sku: filteredData[i].product_sku },
            { product_name: filteredData[i].product_name },
            { brand_name: filteredData[i].brand_name },
            { warehouse_name: filteredData[i].warehouse_name },
            { qty: filteredData[i].qty },
          ];

          dataChecked.push(arrayData);

          let tr_table = ` <tr>`;

          tr_table +=
            ` <td style='text-align: center;vertical-align: middle;'>
                  <div class="form-check form-check-custom form-check-solid">
                          <input data-value="${btoa(
                            JSON.stringify(dataChecked)
                          )}" class=" form-check-input" type="checkbox" value="1" id="checkedBox" />
                  </div>
              </td>
          
            <td style='vertical-align: middle;'>` +
            filteredData[i].product_sku +
            `</td>     
            <td style='vertical-align: middle;'>` +
            filteredData[i].product_name +
            `</td>
            <td style='vertical-align: middle;'>` +
            filteredData[i].brand_name +
            `</td><td style='vertical-align: middle;'>` +
            filteredData[i].warehouse_name +
            `</td>` +
            `<td><div class="input-group input-group-sm"><input type="number" class="form-control select_qty_sku" id="qty_sku_` +
            i +
            `" name="qty_sku" data-qty="` +
            filteredData[i].qty +
            `" data-sku="` +
            filteredData[i].product_sku +
            `" autocomplete="off" value="0" min="0" onkeyup="restrictInput(event)" onchange="validateAndSetToZero(this)"></div></td>`;

          tr_table += `</tr>`;

          $("#kt_datatable_product_list tbody").append(tr_table);
        }
      },
      error: function (jqXHR, textStatus, errorThrown) {
        if (jqXHR.status === 401) {
          sweetAlertMessageWithConfirmNotShowCancelButton(
            "Your session has expired or invalid. Please relogin",
            function () {
              window.location.href = base_url();
            }
          );
        } else {
          sweetAlertMessageWithConfirmNotShowCancelButton(
            "We are sorry, but you do not have access to this service",
            function () {
              location.reload();
            }
          );
        }
      },
    });

    $(document).on("click", "#search_sku", function () {
      let getSKU = $("#sku").val();

      let dataList = [
        { name: "_token", value: getCookie() },
        { name: "value_input", value: getSKU },
      ];

      $("#kt_datatable_product_list").DataTable({
        ajax: {
          url: url,
          type: "POST",
          data: function () {
            return dataList;
          },
        },
        processing: true,
        serverSide: false,
        paging: true,
        ordering: false,
        searching: true,
        info: false,
        bDestroy: true,
        aLengthMenu: [
          [10, 25, 50, -1],
          [10, 25, 50, "All"],
        ],
        columns: [
          {
            data: "id",
            render: function (data2) {
              var data2 = `<div class="form-check form-check-custom form-check-solid">
                          <input data-value="${btoa(
                            JSON.stringify(dataChecked)
                          )}" class=" form-check-input" type="checkbox" value="1" id="checkedBox" />
                  </div>`;

              return data2;
            },
          },
          { data: "product_sku" },
          { data: "product_name" },
          { data: "brand_name" },
        ],
        columnDefs: [
          {
            // For Responsive
            width: "20px",
            orderable: false,
            responsivePriority: 2,
            targets: 0,
          },
        ],
      });
    });
  });

  $(document).on("click", "#selectProductList", function (e) {
    let validasi_qty = [];
    for (let yb = 0; yb < $(".select_qty_sku").length; yb++) {
      var qty_input = $("#qty_sku_" + yb).val();
      var qty_data = $("#qty_sku_" + yb).attr("data-qty");
      var qty_sku = $("#qty_sku_" + yb).attr("data-sku");
      if (parseInt(qty_input) > parseInt(qty_data)) {
        data_array = { sku: qty_sku, validate: 0 };
        validasi_qty.push(data_array);
      } else {
        data_array = { sku: qty_sku, validate: 1 };
        validasi_qty.push(data_array);
      }
    }

    const hasZeroValidate = validasi_qty.some((item) => item.validate === 0);
    if (hasZeroValidate) {
      const filteredData = validasi_qty.filter((item) => item.validate === 0);
      const skusWithZeroValidate = filteredData.map((item) => item.sku);
      for (let val = 0; val < skusWithZeroValidate.length; val++) {
        toastr.warning(
          "Quantity SKU " + skusWithZeroValidate[val] + " Not Enough",
          "",
          {
            progressBar: !0,
            timeOut: 2000,
          }
        );
      }
    } else {
      var target = $(".modal-upload")
        .parent()
        .parent()
        .parent(".modal-content")[0];
      var blockUI = KTBlockUI.getInstance(target);
      e.preventDefault();
      blockUI.block();

      let getLastNumber = $(
        $("#kt_datatable_product_list tbody tr:last").find("td")[1]
      ).text();

      let no = 1;
      $("input[id=checkedBox]").each(function (i, n) {
        if (this.checked) {
          let getJsonData = JSON.parse(atob($(this).data("value")))[i];
          let tr_table = ` <tr data-sku="` + getJsonData[1].product_sku + `">`;

          tr_table +=
            `<td style='text-align: center;vertical-align: middle;'>
            <button type="button" data-repeater-delete="" class="btn btn-sm btn-icon btn-light-danger" id="buttonDeleted">
              <span class="svg-icon svg-icon-2">
                  <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                      xmlns="http://www.w3.org/2000/svg">
                      <rect opacity="0.5" x="7.05025" y="15.5356" width="12" height="2" rx="1"
                          transform="rotate(-45 7.05025 15.5356)" fill="currentColor" />
                      <rect x="8.46447" y="7.05029" width="12" height="2" rx="1"
                          transform="rotate(45 8.46447 7.05029)" fill="currentColor" />
                  </svg>
              </span>
            </button>
            <input type="hidden" id="" name="detail_id[]">
          <input type="hidden" id="" name="product_id[]" value="` +
            getJsonData[0].product_id +
            `">
        </td>  
        <td style='vertical-align: middle;'>` +
            getJsonData[1].product_sku +
            `<input type="hidden" id="" name="product_sku[]" value="` +
            getJsonData[1].product_sku +
            `">
        </td>
        <td style='vertical-align: middle;'>` +
            getJsonData[2].product_name +
            `<input type="hidden" id="" name="product_name[]" value="` +
            getJsonData[2].product_name +
            `">
          </td>
        <td style='vertical-align: middle;'>` +
            getJsonData[3].brand_name +
            `<input type="hidden" id="" name="brand_name[]" value="` +
            getJsonData[3].brand_name +
            `">
          </td>
          <td style='vertical-align: middle;'>` +
            getJsonData[4].warehouse_name +
            `<input type="hidden" id="" name="warehouse_name[]" value="` +
            getJsonData[4].warehouse_name +
            `">
          </td>
        <td style='vertical-align: middle;'><div class="input-group input-group-sm"><input type="number" class="form-control qty_sku" id="qty_sku_` +
            i +
            `" name="qty_sku[]" data-qty="` +
            getJsonData[5].qty +
            `" data-sku="` +
            getJsonData[1].product_sku +
            `" value="` +
            $("#qty_sku_" + i).val() +
            `" min="0" onkeyup="restrictInput(event)" onchange="validateAndSetToZero(this)"></div>
          </td>`;

          tr_table += `</tr>`;

          $("#show_tito tbody").append(tr_table);
          no++;
        }
      });

      $("#modalLarge3").modal("hide");

      setTimeout(function () {
        blockUI.release();
      }, 700);
    }
  });

  $(document).on("click", "#buttonDeleted", function () {
    $(this).parent().parent().remove();
  });

  $(document).on("click", "#btn_deleted", function () {
    Swal.fire({
      title: "Are you sure?",
      text: "You won't be able to revert this!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Yes",
    }).then((result) => {
      if (result.isConfirmed) {
        var dataUpload = [];
        dataUpload.push({ name: "_token", value: getCookie() });
        dataUpload.push({ name: "id", value: $("#id").val() });
        $.ajax({
          url: base_url() + "tito2/process_delete_id",
          method: "POST",
          dataType: "JSON",
          async: false,
          data: dataUpload,
          success: function (result) {
            if (result.error == false) {
              $("#modalLarge2").modal("hide");
              Swal.fire("Successfully delete item", "", "success");
              reloadDatatables();
            }
          },
        });
      }
    });
  });

  $(document).on("click", "#btnCloseModalFullscreen2", function () {
    $("#modalLarge3").modal("hide");
  });

  $(document).on("click", "#btnCloseModal", function () {
    $("#modalLarge").modal("hide");
    $("#modalLarge2").modal("hide");
  });

  $(document).on("click", "#saveMassUpload", function (e) {
    let cektable = $("#show_tito tbody tr").length;
    var target = $(".modal-upload")
      .parent()
      .parent()
      .parent(".modal-content")[0];
    var blockUI = KTBlockUI.getInstance(target);
    e.preventDefault();

    if (cektable < 1) {
      toastr.warning("File Mass Upload Empty", "", {
        progressBar: !0,
        timeOut: 2000,
      });
    } else {
      Swal.fire({
        title: "Save Mass Upload Product",
        text: "Are you sure save this data?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#0CC27E",
        cancelButtonColor: "#FF586B",
        confirmButtonText: "Yes",
        cancelButtonText: "No, cancel!",
        customClass: {
          confirmButton: "btn btn-success mr-5",
          cancelButton: "btn btn-danger",
        },
        buttonsStyling: false,
        allowOutsideClick: false,
      }).then((result) => {
        if (result.isConfirmed) {
          var textButton = $(this).text();
          var btn = $(this);
          var url = $("#form").data("url");
          var arType = [];
          $("img.upload-img").each(function (i, x) {
            arType.push(x.src);
          });
          var data = $("#form").serializeArray(); // convert form to array
          data.push({ name: "_token", value: getCookie() });
          data.push({ name: "img", value: JSON.stringify(arType) });
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
                    addErrorValidationNestedFields(key, value);
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
                reloadDatatables();
              }
              loadingButtonOff(btn, textButton);
              enabledButton($(btnCloseModal));
              if (response.type == "update") {
                if (response.success) {
                  var closeModal =
                    btnCloseModal != "#btnCloseModal"
                      ? btnCloseModal
                      : "#modalLarge";
                  modalAutoClose(closeModal);
                }
              }

              if (response.validate) {
                message(response.success, response.messages);
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
        } else {
          return false;
        }
      });
    }
  });

  $(document).on("click", "#btnMassUpload", function () {
    buttonAction($(this), "#modalLarge3");
    $("#btnProcessMassUpload").hide();
    var wareh_val = $(this).attr("data-warehouse");
    $("#upload_data").attr("data-warehouse", wareh_val);
    $("#wares").val(wareh_val);
    $("#formatError").hide();

    $("#show_tito tbody tr").each(function (i, x) {
      sku_value.push($(x).attr("data-sku"));
    });
  });

  $(document).on("change", "#upload_data", function () {
    $("#btnProcessMassUpload").hide();

    $("#kt_datatable_vertical_scroll tbody tr").remove();

    let file = this.files[0];
    $("#kt_datatable_vertical_scroll").find("tr:gt(0)").remove();

    if (typeof file != "undefined") {
      const reader = new FileReader();
      reader.readAsBinaryString(file);
      reader.onload = function (event) {
        const csvData = event.target.result;
        const lines = csvData.split("\n");

        const headers = lines[0].split(",");
        const output = [];
        const headerSplit = [];

        for (let i = 0; i < headers.length; i++) {
          const header = headers[i].replace(/\s+/g, "_");
          headerSplit.push(header);
        }

        for (let i = 0; i < headerSplit.length; i++) {
          headerSplit[i] = headerSplit[i].replace(/_+$/, "");
        }

        for (let i = 1; i < lines.length; i++) {
          const data = lines[i];
          const row = {};

          for (let j = 0; j < headerSplit.length; j++) {
            row[headerSplit[j]] = customSplit(data)[j];
          }
          output.push(row);
        }

        const headersFormatCsv = ["SKU(*)", "QTY(*)"];

        var no = 1;
        var no2 = 1;

        let dataUpload = [];

        dataUpload.push({
          name: "from_warehouse",
          value: $("#wares").val(),
        });
        if (headersFormatCsv.toString() == headerSplit.toString()) {
          dataUpload.push({ name: "_token", value: getCookie() });
          dataUpload.push({
            name: "dataUpload",
            value: JSON.stringify(output),
          });
          $.ajax({
            url: base_url() + "tito2/upload_data",
            method: "POST",
            dataType: "JSON",
            async: false,
            data: dataUpload,
            success: function (result) {
              let getJsonData = result.data;

              let check_validate = [];
              for (let i = 0; i < getJsonData.length; i++) {
                check_validate.push(getJsonData[i].validate);
              }

              var filteredData = getJsonData.filter(function (item) {
                return !sku_value.includes(item.product_sku);
              });

              for (let i = 0; i < filteredData.length; i++) {
                var tr_table = `<tr>`;
                tr_table +=
                  ` <td style='text-align: center;vertical-align: middle;'>
                    <button type="button" data-repeater-delete="" class="btn btn-sm btn-icon btn-light-danger" id="buttonDeleted">
                      <span class="svg-icon svg-icon-2">
                          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <rect opacity="0.5" x="7.05025" y="15.5356" width="12" height="2" rx="1" transform="rotate(-45 7.05025 15.5356)" fill="currentColor" />
                              <rect x="8.46447" y="7.05029" width="12" height="2" rx="1" transform="rotate(45 8.46447 7.05029)" fill="currentColor" />
                          </svg>
                      </span>
                    </button>
                    <input class="product_id" id="product_id_` +
                  i +
                  `" type="hidden" name="product_id[]" value="` +
                  filteredData[i].product_id +
                  `">
                    </td>
                            <td style='vertical-align: middle;'> <input id="sku_` +
                  i +
                  `" type="hidden" name="product_sku[]" value="` +
                  filteredData[i].product_sku +
                  `">` +
                  filteredData[i].product_sku +
                  `</td>
                            <td  style='vertical-align: middle;'> <input id="product_name_` +
                  i +
                  `" type="hidden" name="product_name[]" value="` +
                  filteredData[i].product_name +
                  `">` +
                  filteredData[i].product_name +
                  `</td>
                            <td style='vertical-align: middle;'> <input id="brand_name_` +
                  i +
                  `" type="hidden" name="brand_name[]" value="` +
                  filteredData[i].brand_name +
                  `">` +
                  filteredData[i].brand_name +
                  `</td>
                            <td  style='vertical-align: middle;'> <input id="warehouse_name_` +
                  i +
                  `" type="hidden" name="warehouse_name[]" value="` +
                  filteredData[i].warehouse_name +
                  `">` +
                  filteredData[i].warehouse_name +
                  `</td>
                            <td  style='vertical-align: middle;'> <input id="qty_sku_` +
                  i +
                  `" type="hidden" name="qty_sku[]" value="` +
                  filteredData[i].qty +
                  `"><input id="data_qty_sku_` +
                  i +
                  `" type="hidden" name="data_qty_sku[]" value="` +
                  filteredData[i].data_qty +
                  `">` +
                  filteredData[i].qty +
                  `</td>
                          </tr>`;
                var tr_table2 = `<tr>`;
                tr_table2 +=
                  `<td></td>
                  <td style='vertical-align: middle;'>` +
                  filteredData[i].product_sku +
                  `</td style='vertical-align: middle;'>
                            <td>` +
                  filteredData[i].product_name +
                  `</td style='vertical-align: middle;'>
                            <td>` +
                  filteredData[i].brand_name +
                  `</td style='vertical-align: middle;'>
                            <td>` +
                  filteredData[i].warehouse_name +
                  `</td style='vertical-align: middle;'>
                            <td>` +
                  filteredData[i].qty +
                  `</td style='vertical-align: middle;'>
                            </tr>`;
                if (check_validate.includes(2)) {
                  $("#kt_datatable_vertical_scroll").append(tr_table2);
                  $("#btnProcessMassUpload").hide();
                } else {
                  $("#kt_datatable_vertical_scroll").append(tr_table);
                  $("#btnProcessMassUpload").show();
                }

                no++;
                no2++;
              }
            },
          });
        } else {
          $("#formatError").show();
          $("#kt_datatable_vertical_scroll").find("tr:gt(0)").remove();
        }
      };
    } else {
      $("#show_data_preview").html("");
    }
  });

  $(document).on("click", "#btnProcessMassUpload", function (e) {
    var target = $(".modal-upload")
      .parent()
      .parent()
      .parent(".modal-content")[0];
    var blockUI = KTBlockUI.getInstance(target);
    e.preventDefault();
    blockUI.block();

    let getLastNumber = $(
      $("#kt_datatable_product_list tbody tr:last").find("td")[1]
    ).text();

    var inv_storage_id = $(".product_id").length;

    for (let xy = 0; xy < inv_storage_id; xy++) {
      let tr_table = ` <tr data-sku="` + $("#sku_" + xy).val() + `">`;

      tr_table +=
        `<td style='text-align: center;vertical-align: middle;'>
            <button type="button" data-repeater-delete="" class="btn btn-sm btn-icon btn-light-danger" id="buttonDeleted">
              <span class="svg-icon svg-icon-2">
                  <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                      xmlns="http://www.w3.org/2000/svg">
                      <rect opacity="0.5" x="7.05025" y="15.5356" width="12" height="2" rx="1"
                          transform="rotate(-45 7.05025 15.5356)" fill="currentColor" />
                      <rect x="8.46447" y="7.05029" width="12" height="2" rx="1"
                          transform="rotate(45 8.46447 7.05029)" fill="currentColor" />
                  </svg>
              </span>
            </button>
            <input type="hidden" id="" name="detail_id[]">
            <input type="hidden" id="" name="product_id[]" value="` +
        $("#product_id_" + xy).val() +
        `">
        </td>  
        <td style='vertical-align: middle;'>` +
        $("#sku_" + xy).val() +
        `<input type="hidden" id="" name="product_sku[]" value="` +
        $("#sku_" + xy).val() +
        `">
        </td>
        <td style='vertical-align: middle;'>` +
        $("#product_name_" + xy).val() +
        `<input type="hidden" id="" name="product_name[]" value="` +
        $("#product_name_" + xy).val() +
        `">
          </td>
        <td style='vertical-align: middle;'>` +
        $("#brand_name_" + xy).val() +
        `<input type="hidden" id="" name="brand_name[]" value="` +
        $("#brand_name_" + xy).val() +
        `">
          </td>
          <td style='vertical-align: middle;'>` +
        $("#warehouse_name_" + xy).val() +
        `<input type="hidden" id="" name="warehouse_name[]" value="` +
        $("#warehouse_name_" + xy).val() +
        `">
          </td>
        <td style='vertical-align: middle;'><div class="input-group input-group-sm"><input type="number" class="form-control qty_sku" id="qty_sku_` +
        xy +
        `" name="qty_sku[]" data-qty="` +
        $("#data_qty_sku_" + xy).val() +
        `" data-sku="` +
        $("#sku_" + xy).val() +
        `" value="` +
        $("#qty_sku_" + xy).val() +
        `" min="0" onkeyup="restrictInput(event)" onchange="validateAndSetToZero(this)"></div>
          </td>`;

      tr_table += `</tr>`;

      $("#show_tito tbody").append(tr_table);
    }

    $("#modalLarge3").modal("hide");

    setTimeout(function () {
      blockUI.release();
    }, 700);
  });

  $(document).on("click", "#btnProcessModal", function () {
    var textButton = $(this).text();
    var btn = $(this);
    var url = $("#form").data("url");
    var arType = [];
    $("img.upload-img").each(function (i, x) {
      arType.push(x.src);
    });
    var data = $("#form").serializeArray(); // convert form to array
    data.push({ name: "_token", value: getCookie() });
    data.push({ name: "img", value: JSON.stringify(arType) });
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
              addErrorValidationNestedFields(key, value);
            });
          }
        } else {
          if (response.type == "insert") {
            if (typeof response.data != "undefined") {
              addDataOption(response.data);
            }
            reset_input();
            $("#modalLarge2").modal("hide");
          }
          reloadDatatables();
        }
        loadingButtonOff(btn, textButton);
        enabledButton($(btnCloseModal));
        if (response.type == "update") {
          if (response.success) {
            $("#modalLarge2").modal("hide");
          }
        }
        if (response.validate) {
          message(response.success, response.messages);
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

  $(document).on("click", "#btnSend", function () {
    Swal.fire({
      title: "Are you sure?",
      text: "You won't be able to revert this!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Yes",
    }).then((result) => {
      if (result.isConfirmed) {
        var dataUpload = [];
        dataUpload.push({ name: "_token", value: getCookie() });
        dataUpload.push({ name: "id", value: $(this).attr("data-id") });
        $.ajax({
          url: base_url() + "tito2/change_status",
          method: "POST",
          dataType: "JSON",
          async: false,
          data: dataUpload,
          success: function (result) {
            if (result.error == false) {
              Swal.fire("Successfully update item", "", "success");
              reloadDatatables();
            }
          },
        });
      }
    });
  });

  $(document).on("click", "#btnReturnQtySku", function (e) {
    let validasi_desc = [];
    for (let yb = 0; yb < $(".desc_return").length; yb++) {
      var desc_value = $("#desc_return_" + yb).val();
      var sku_value = $("#product_sku_return_validate_" + yb).val();
      if (desc_value == "" || desc_value == undefined) {
        data_array = { sku: sku_value, validate: 0 };
        validasi_desc.push(data_array);
      } else {
        data_array = { sku: sku_value, validate: 1 };
        validasi_desc.push(data_array);
      }
    }

    const hasZeroValidate = validasi_desc.some((item) => item.validate === 0);
    if (hasZeroValidate) {
      const filteredData = validasi_desc.filter((item) => item.validate === 0);
      const skusWithZeroValidate = filteredData.map((item) => item.sku);
      for (let val = 0; val < skusWithZeroValidate.length; val++) {
        toastr.warning(
          "Description SKU " + skusWithZeroValidate[val] + " is Required",
          "",
          {
            progressBar: !0,
            timeOut: 2000,
          }
        );
      }
    } else {
      var target = $(".modal-upload")
        .parent()
        .parent()
        .parent(".modal-content")[0];
      var blockUI = KTBlockUI.getInstance(target);
      e.preventDefault();
      blockUI.block();
      $("#modalLarge3").hide();
      $("#submit_stepper").hide();
      $("#btnProcessModal").show();
      $("#display_show_tito").hide();
      $("#new_display_show_tito").show();
      var nox = 1;
      var nozz = 0;
      for (let ix = 0; ix < $(".product_sku_return").length; ix++) {
        var qty_input = $("#qty_received_return_" + ix).val();
        var qty_data = $("#qty_sku_return_" + ix).val();
        var summation = parseInt(qty_data) - parseInt(qty_input);
        let tr_table = ` <tr>`;
        if (parseInt(qty_input) < parseInt(qty_data)) {
          tr_table +=
            `<td style='text-align: center;vertical-align: middle;'>` +
            nox++ +
            `<input class="tito_detail_id" type="hidden" id="tito_detail_id_` +
            ix +
            `" name="tito_detail_id[]" value="` +
            $("#tito_detail_id_" + ix).val() +
            `"></td><td style='vertical-align: middle;'>` +
            $("#product_sku_return_" + ix).val() +
            `<input type="hidden" id="product_sku_return_` +
            ix +
            `" name="product_sku_return[]" value="` +
            $("#product_sku_" + ix).val() +
            `">
                </td><td style='vertical-align: middle;'><input type="hidden" id="product_name_return_` +
            ix +
            `" name="product_name_return[]" value="` +
            $("#product_name_" + ix).val() +
            `">` +
            $("#product_name_" + ix).val() +
            `</td><td style='vertical-align: middle;'><input type="hidden" id="brand_name_return_` +
            ix +
            `" name="brand_name_return[]" value="` +
            $("#brand_name_" + ix).val() +
            `">` +
            $("#brand_name_" + ix).val() +
            `</td><td style='vertical-align: middle;'><input type="hidden" id="qty_sku_return_` +
            ix +
            `" name="qty_sku_return[]" value="` +
            $("#qty_sku_" + ix).val() +
            `">` +
            $("#qty_sku_" + ix).val() +
            `</td>` +
            `<td style='vertical-align: middle;'><input type="hidden" id="qty_received_return_` +
            ix +
            `" name="qty_received_return[]" value="` +
            $("#qty_received_" + ix).val() +
            `">` +
            $("#qty_received_" + ix).val() +
            `</td><td style='vertical-align: middle;'><input type="hidden" id="qty_lost_return_` +
            ix +
            `" name="qty_lost_return[]" value="` +
            summation +
            `">` +
            summation +
            `</td><td style='vertical-align: middle;'><textarea type="text" id="desc_return_result_` +
            ix +
            `" name="desc_return[]" class="form-control form-control-solid mb-3 mb-lg-0 desc_return" rows="1" placeholder="Description" data-type="input" autocomplete="off" readonly>` +
            $("#desc_return_" + nozz).val() +
            `</textarea></td><input type="hidden" id="warehouse_name_return_` +
            ix +
            `" name="warehouse_name_return[]" value="` +
            $("#warehouse_name_" + ix).val() +
            `">`;
          nozz++;
        } else {
          tr_table +=
            `<input class="tito_detail_id" type="hidden" id="tito_detail_id_` +
            ix +
            `" name="tito_detail_id[]" value="` +
            $("#tito_detail_id_" + ix).val() +
            `"><input type="hidden" id="product_sku_return_` +
            ix +
            `" name="product_sku_return[]" value="` +
            $("#product_sku_" + ix).val() +
            `"><input type="hidden" id="product_name_return_` +
            ix +
            `" name="product_name_return[]" value="` +
            $("#product_name_" + ix).val() +
            `"><input type="hidden" id="brand_name_return_` +
            ix +
            `" name="brand_name_return[]" value="` +
            $("#brand_name_" + ix).val() +
            `"><input type="hidden" id="warehouse_name_return_` +
            ix +
            `" name="warehouse_name_return[]" value="` +
            $("#warehouse_name_" + ix).val() +
            `"><input type="hidden" id="qty_sku_return_` +
            ix +
            `" name="qty_sku_return[]" value="` +
            $("#qty_sku_" + ix).val() +
            `"><input type="hidden" id="qty_received_return_` +
            ix +
            `" name="qty_received_return[]" value="` +
            $("#qty_received_" + ix).val() +
            `"><input type="hidden" id="qty_lost_return_` +
            ix +
            `" name="qty_lost_return[]" value="0"><input type="hidden" id="desc_return_result_` +
            ix +
            `" name="desc_return[]" value="">`;
        }
        tr_table += `</tr>`;
        $("#new_display_show_tito tbody").append(tr_table);
      }
      $("#modalLarge3").modal("hide");

      setTimeout(function () {
        blockUI.release();
      }, 700);
    }
  });

  $(document).on("click", "#btnPreview", function () {
    buttonAction($(this), "#modalLarge2");
  });
});

function customSplit(text) {
  const parts = [];
  let currentPart = "";
  let withinQuotes = false;

  for (let i = 0; i < text.length; i++) {
    const char = text[i];

    if (char === '"') {
      withinQuotes = !withinQuotes;
      currentPart += char;
    } else if (char === "," && !withinQuotes) {
      parts.push(currentPart.trim().replace(/^"(.*)"$/, "$1"));
      currentPart = "";
    } else {
      currentPart += char;
    }
  }

  if (currentPart !== "") {
    parts.push(currentPart.trim().replace(/^"(.*)"$/, "$1"));
  }

  return parts;
}

function restrictInput(event) {
  const inputValue = event.target.value;
  const numericValue = inputValue.replace(/[^0-9]/g, "");
  event.target.value = numericValue;
}

function validateAndSetToZero(inputElement) {
  var newValue = inputElement.value;
  if (newValue === "") {
    inputElement.value = "0";
  }
}
