let action = "";

let iconSuccess = `<span class="svg-icon svg-icon-2x">
                                <svg xmlns="http://www.w3.org/2000/svg" style="margin-top: 9px;" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <polygon points="0 0 24 0 24 24 0 24"/>
                                        <path d="M9.26193932,16.6476484 C8.90425297,17.0684559 8.27315905,17.1196257 7.85235158,16.7619393 C7.43154411,16.404253 7.38037434,15.773159 7.73806068,15.3523516 L16.2380607,5.35235158 C16.6013618,4.92493855 17.2451015,4.87991302 17.6643638,5.25259068 L22.1643638,9.25259068 C22.5771466,9.6195087 22.6143273,10.2515811 22.2474093,10.6643638 C21.8804913,11.0771466 21.2484189,11.1143273 20.8356362,10.7474093 L17.0997854,7.42665306 L9.26193932,16.6476484 Z" fill="#008000" fill-rule="nonzero" opacity="0.3" transform="translate(14.999995, 11.000002) rotate(-180.000000) translate(-14.999995, -11.000002) "/>
                                        <path d="M4.26193932,17.6476484 C3.90425297,18.0684559 3.27315905,18.1196257 2.85235158,17.7619393 C2.43154411,17.404253 2.38037434,16.773159 2.73806068,16.3523516 L11.2380607,6.35235158 C11.6013618,5.92493855 12.2451015,5.87991302 12.6643638,6.25259068 L17.1643638,10.2525907 C17.5771466,10.6195087 17.6143273,11.2515811 17.2474093,11.6643638 C16.8804913,12.0771466 16.2484189,12.1143273 15.8356362,11.7474093 L12.0997854,8.42665306 L4.26193932,17.6476484 Z" fill="#008000" fill-rule="nonzero" transform="translate(9.999995, 12.000002) rotate(-180.000000) translate(-9.999995, -12.000002) "/>
                                    </g>
                                </svg>
                              </span>`;

let iconError = `<span class="svg-icon svg-icon-2x">
                                  <svg width="24" height="24" viewBox="0 0 24 24" fill="none" style="margin-top: 9px;"
                                      xmlns="http://www.w3.org/2000/svg">
                                      <rect opacity="0.5" x="7.05025" y="15.5356" width="12" height="2"
                                          rx="1" transform="rotate(-45 7.05025 15.5356)" fill="#ff0000" />
                                      <rect x="8.46447" y="7.05029" width="12" height="2" rx="1"
                                          transform="rotate(45 8.46447 7.05029)" fill="#ff0000" />
                                  </svg>
                              </span>`;

$(document).ready(function () {
  let baseurl = base_url() + "inventory_requisition/show";

  let column = [
    { data: "po_number" },
    { data: "brand_name", width: "15%" },
    { data: "supplier_name", width: "15%" },
    { data: "username", width: "10%" },
    { data: "created_at", width: "15%" },
    { data: "total_qty" },
    {
      data: "status_name",
      render: function (data) {
        let badge = "";

        if (data === "Open") {
          badge = '<div class="badge badge-light-success">' + data + "</div>";
        } else if (data === "Release") {
          badge = '<div class="badge badge-light-danger">' + data + "</div>";
        } else {
          badge = '<div class="badge badge-light-warning">' + data + "</div>";
        }

        return badge;
      },
    },
    { data: "action", width: "25%" },
  ];

  ajax_crud_po(baseurl, column, "table-data");

  $("#start_date").flatpickr({
    enableTime: false,
    dateFormat: "Y-m-d",
  });

  $("#end_date").flatpickr({
    enableTime: false,
    dateFormat: "Y-m-d",
  });

  sweetAlertConfirm();
  libraryInput();

  let btnCloseModal = "#btnCloseModalFullscreen";

  $(document).on("click", "#btnProcessModal", function (e) {
    let cektable = $("#kt_datatable_vertical_scroll tbody tr").length;

    if (cektable < 1) {
      toastr.warning("File Mass Upload Empty", "", {
        progressBar: !0,
        timeOut: 2000,
      });
    } else {
      Swal.fire({
        title: "Save Product SKU",
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
          var target = $(".modal-upload")
            .parent()
            .parent()
            .parent(".modal-content")[0];

          var blockUI = KTBlockUI.getInstance(target);
          e.preventDefault();
          blockUI.block();

          let textButton = $(this).text();
          let btn = $(this);
          let url = $("#form").data("url");
          let data = $("#form").serializeArray(); // convert form to array
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
                  $(
                    "td div.fv-plugins-message-container.invalid-feedback"
                  ).remove();
                  $.each(response.messages, function (key, value) {
                    let keyName = key.split("[")[0];
                    let inputString = key;
                    let startIndex = inputString.indexOf("[");
                    let endIndex = inputString.indexOf("]");

                    let valueInsideBrackets = inputString.substring(
                      startIndex + 1,
                      endIndex
                    );

                    let element = $('input[name="' + keyName + '[]"]')[
                      valueInsideBrackets
                    ];

                    $(element).after(value);
                  });

                  blockUI.release();
                }
              } else {
                if (response.type == "insert") {
                  if (typeof response.data != "undefined") {
                    addDataOption(response.data);
                  }
                  reset_input();
                  let closeModal = "#modalLarge2";
                  if (closeModal != "#modalLarge2") {
                    let idModal = $(closeModal)
                      .parent()
                      .parent()
                      .parent()
                      .parent()
                      .attr("id");
                    $("#" + idModal).modal("hide");
                  } else {
                    $(closeModal).modal("hide");
                  }
                }
                addDraw();
                $("#table-data").DataTable().ajax.reload();
              }
              loadingButtonOff(btn, textButton);
              enabledButton($(btnCloseModal));
              if (response.type == "update") {
                if (response.success) {
                  let closeModal =
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
        } else {
          return false;
        }
      });
    }
  });

  $(document).on("click", "#buttonDeleted", function () {
    $(this).parent().parent().remove();
  });

  function setMessage(name, index) {
    switch (index) {
      case 5:
        return (
          '<div style="margin-top: -2px;margin-bottom: -29px;" class="fv-plugins-message-container invalid-feedback">' +
          name +
          " Not Number</div>"
        );
        break;
      case 4:
        return (
          '<div style="margin-top: -2px;margin-bottom: -29px;" class="fv-plugins-message-container invalid-feedback">' +
          name +
          " Already Exist</div>"
        );
        break;
      case 3:
        return (
          '<div style="margin-top: -2px;margin-bottom: -29px;" class="fv-plugins-message-container invalid-feedback">' +
          name +
          " field is required</div>"
        );
        break;
      case 2:
        return (
          '<div style="margin-top: -2px;margin-bottom: -29px;" class="fv-plugins-message-container invalid-feedback">' +
          name +
          " Not Exist</div>"
        );
        break;
      default:
        return "";
        break;
    }
  }

  function areArraysEqual(arr1, arr2) {
    // Check if the arrays have the same length
    if (arr1.length !== arr2.length) {
      return false;
    }

    // Sort both arrays
    arr1.sort();
    arr2.sort();

    // Compare each element
    for (let i = 0; i < arr1.length; i++) {
      if (arr1[i] !== arr2[i]) {
        return false; // If any element is different, return false.
      }
    }

    return true; // Both arrays have the same values.
  }

  $(document).on("click", "#upload_button", function (e) {
    let input_file_id = $("#data_upload").val();
    let fileName = input_file_id.toLowerCase();
    let btnProcessModal = $("#btnProcessModal");
    const dataTable = $("#kt_datatable_vertical_scroll tbody");
    const formatError = $("#formatError");
    const url = base_url() + "inventory_requisition/uploadDataProduct";

    const headersFormatCsv = [
      "SKU_(*)",
      "QUANTITY_(*)",
      "TYPE",
      "PRICE",
      "MATERIAL_COST",
      "SERVICE_COST",
      "OVERHEAD_COST",
      "DESCRIPTION",
    ];

    if (!input_file_id) {
      toastr.warning("File Mass Upload Empty", "", {
        progressBar: !0,
        timeOut: 2000,
      });
      return false;
    }

    if (!fileName.endsWith(".xlsx")) {
      toastr.warning("File Not Supported", "", {
        progressBar: !0,
        timeOut: 2000,
      });
      return false;
    }

    var target = $(".modal-upload")
      .parent()
      .parent()
      .parent(".modal-content")[0];

    var blockUI = KTBlockUI.getInstance(target);
    e.preventDefault();
    blockUI.block();

    btnProcessModal.remove();
    dataTable.html("");

    let file = $("#data_upload")[0].files[0];

    if (file) {
      var reader = new FileReader();

      reader.onload = function (e) {
        var data = e.target.result;
        var workbook = XLSX.read(data, { type: "binary" });
        var sheetName = workbook.SheetNames[0]; // Get the name of the first sheet
        var sheet = workbook.Sheets[sheetName];
        var jsonData = XLSX.utils.sheet_to_json(sheet);

        var jsonData = XLSX.utils.sheet_to_json(sheet, { header: 1 });

        let getJsonData = jsonData[0];

        let headerSplit = [];
        for (var i = 0; i < getJsonData.length; i++) {
          getJsonData[i] = getJsonData[i].replace(/ /g, "_");
          headerSplit.push(getJsonData[i]);
        }

        var dataRows = jsonData.slice(1);

        var dataArray = dataRows.map(function (row) {
          var obj = {};
          getJsonData.forEach(function (header, index) {
            obj[header] = row[index];
          });
          return obj;
        });

        if (areArraysEqual(headersFormatCsv, headerSplit)) {
          const dataPush = [
            { name: "supplier_id", value: $("#url_supplier_id").val() },
            { name: "brand_id", value: $("#url_brand_id").val() },
            { name: "_token", value: getCookie() },
            { name: "dataUpload", value: JSON.stringify(dataArray) },
          ];

          $.ajax({
            url: url,
            method: "POST",
            dataType: "JSON",
            async: false,
            data: dataPush,
            success: function (result) {
              let getJsonData = result.data;
              $("#formatError").hide();
              let no = 1;

              for (let i = 0; i < getJsonData.length; i++) {
                let tr_table = ` <tr>`;

                tr_table +=
                  ` <td style='text-align: center;vertical-align: middle;'>
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
                    </td>
                  <td style='text-align: center;vertical-align: middle;'>` +
                  no +
                  `</td>
                  <td style='vertical-align: middle;'>` +
                  getJsonData[i].product_name +
                  `</td>
                  <td>
                  <input type="text" class="form-control mb-3 mb-lg-0 data-input" data-type="input" name="sku[]"
                          value="` +
                  getJsonData[i].sku +
                  `" />` +
                  setMessage("SKU", getJsonData[i].validate[0]) +
                  `</td>
                  <td style='vertical-align: middle;'>` +
                  getJsonData[i].brand_name +
                  `</td>
                  <td style='vertical-align: middle;'>` +
                  getJsonData[i].categories_name +
                  `</td>
                  <td style='vertical-align: middle;'>` +
                  getJsonData[i].product_size +
                  `</td>
                  <td style='vertical-align: middle;'>` +
                  getJsonData[i].color +
                  `</td>
                  <td> 
                  <input type="text" onkeyup="formatCurrency(this)" class="form-control mb-3 mb-lg-0" data-type="input" name="quantity[]" value="` +
                  format_number_no_idr(getJsonData[i].qty) +
                  `" />
                  </td>
                  <td> 
                  <input type="text" class="form-control mb-lg-0" data-type="input" name="type[]" value="` +
                  getJsonData[i].type +
                  `" />` +
                  setMessage("Type", getJsonData[i].validate[1]) +
                  `</td>
                  <td> 
                  <input type="text" onkeyup="formatCurrency(this)" class="form-control mb-3 mb-lg-0" data-type="input" name="price[]" value="` +
                  format_number_no_idr(getJsonData[i].price) +
                  `" />
                  </td>
                  <td> 
                  <input onkeyup="formatCurrency(this)" type="text" class="form-control mb-3 mb-lg-0" data-type="input" name="material_cost[]" value="` +
                  format_number_no_idr(getJsonData[i].material_cost) +
                  `" /> 
                  </td>
                  <td>
                  <input type="text" onkeyup="formatCurrency(this)" class="form-control mb-3 mb-lg-0" data-type="input" name="service_cost[]" value="` +
                  format_number_no_idr(getJsonData[i].service_cost) +
                  `" />
                  </td>
                  <td>
                  <input type="text onkeyup="formatCurrency(this)" class="form-control mb-3 mb-lg-0" data-type="input" name="overhead_cost[]" value="` +
                  format_number_no_idr(getJsonData[i].overhead_cost) +
                  `" /> 
                  </td>
                  <td>
                  <input type="text" class="form-control mb-3 mb-lg-0" data-type="input" name="description[]"
                            value="` +
                  getJsonData[i].description +
                  `" />
                  </td>
                  <td>
                    <input type="file" id="image_file" name="image_file[]" class="form-control mb-3 mb-lg-0 imageRow" multiple data-type="input" accept=".jpg, .jpeg, .png"/>
                  </td>

                  <input type="hidden" name="product_id[]" value="` +
                  getJsonData[i].product_id +
                  `" />
                  <input type="hidden" name="product_name[]" value="` +
                  getJsonData[i].product_name +
                  `" />
                  <input type="hidden" name="brand_name[]" value="` +
                  getJsonData[i].brand_name +
                  `" />
                  <input type="hidden" name="categories_name[]" value="` +
                  getJsonData[i].categories_name +
                  `" />
                  <input type="hidden" name="product_size[]" value="` +
                  getJsonData[i].product_size +
                  `" />
                  <input type="hidden" name="color[]" value="` +
                  getJsonData[i].color +
                  `" />`;

                if (
                  getJsonData[i].validate.every(function (element) {
                    return element === 1;
                  })
                ) {
                  tr_table +=
                    `<td class="td-success" style='text-align: center;vertical-align: middle;'>` +
                    iconSuccess +
                    `</td>`;
                } else {
                  tr_table +=
                    `<td class="td-error" style='text-align: center;vertical-align: middle;'>` +
                    iconError +
                    ` </td>`;
                }

                tr_table += `</tr>`;

                $("#kt_datatable_vertical_scroll tbody").append(tr_table);
                no++;
              }

              blockUI.release();
            },
            error: function (xhr, status, error) {
              switch (xhr.status) {
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
          formatError.show();
          dataTable.html("");
          blockUI.release();
        }
      };

      reader.readAsBinaryString(file);
    }
  });

  $(document).on("click", "#saveMassUpload", function (e) {
    let cektable = $("#kt_datatable_vertical_scroll tbody tr").length;
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
          let textButton = $(this).text();
          let btn = $(this);
          let url = base_url() + "inventory_requisition/process";
          let formData = document.getElementById("form");
          let formDataObject = new FormData(formData);
          formDataObject.append("_token", getCookie());
          $.ajax({
            url: url,
            method: "POST",
            mimeType: "multipart/form-data",
            dataType: "JSON",
            contentType: false,
            processData: false,
            cache: false,
            async: false,
            data: formDataObject,
            beforeSend: function () {
              loadingButton(btn);
              disabledButton($(btnCloseModal));
            },
            success: function (response) {
              if (!response.success) {
                if (!response.validate) {
                  $(
                    "td div.fv-plugins-message-container.invalid-feedback"
                  ).remove();

                  $("#saveMassUpload").remove();
                  $("#btnCloseModalFullscreen").after(
                    '<button class="btn btn-info btn-rounded ml-2" type="button" id="btnNext">Next</button>'
                  );

                  $("#kt_datatable_vertical_scroll tbody tr").each(function () {
                    $(this).find("td:last").removeClass("td-success");
                    $(this).find("td:last").removeClass("td-error");
                  });

                  $.each(response.messages, function (key, value) {
                    let keyName = key.split("[")[0];
                    let inputString = key;
                    let startIndex = inputString.indexOf("[");
                    let endIndex = inputString.indexOf("]");

                    let valueInsideBrackets = inputString.substring(
                      startIndex + 1,
                      endIndex
                    );

                    let element = $('input[name="' + keyName + '[]"]')[
                      valueInsideBrackets
                    ];

                    $(element).after(value);

                    if (value) {
                      let lastTd = $(element).parent().parent().find("td:last");
                      $(lastTd).addClass("td-error");
                    }

                    if (!value) {
                      let lastTd = $(element).parent().parent().find("td:last");
                      $(lastTd).addClass("td-success");
                    }
                  });

                  $(".td-success").html(iconSuccess);
                  $(".td-error").html(iconError);
                }

                blockUI.release();
              } else {
                if (response.type == "insert") {
                  if (typeof response.data != "undefined") {
                    addDataOption(response.data);
                  }
                  reset_input();
                }
                addDraw();
                $("#table-data").DataTable().ajax.reload();
                $("#modalLarge2").modal("hide");
              }

              loadingButtonOff(btn, textButton);
              enabledButton($(btnCloseModal));

              if (response.validate) {
                message(response.success, response.messages);
                blockUI.release();
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

  $(document).on("click", "#btnNext", function () {
    let cektable = $("#kt_datatable_vertical_scroll tbody tr").length;

    if (cektable < 1) {
      toastr.warning("File Mass Upload Empty", "", {
        progressBar: !0,
        timeOut: 2000,
      });
    } else {
      let url = base_url() + "inventory_requisition/processMassUpload";
      let data = $("#form").serializeArray(); // convert form to array
      data.push({ name: "_token", value: getCookie() });
      $.ajax({
        url: url,
        method: "POST",
        dataType: "JSON",
        async: false,
        data: $.param(data),
        success: function (response) {
          if (!response.success) {
            if (!response.validate) {
              $(
                "td div.fv-plugins-message-container.invalid-feedback"
              ).remove();

              $("#kt_datatable_vertical_scroll tbody tr").each(function () {
                $(this).find("td:last").removeClass("td-success");
                $(this).find("td:last").removeClass("td-error");
              });

              $.each(response.messages, function (key, value) {
                let keyName = key.split("[")[0];
                let inputString = key;
                let startIndex = inputString.indexOf("[");
                let endIndex = inputString.indexOf("]");
                let valueInsideBrackets = inputString.substring(
                  startIndex + 1,
                  endIndex
                );
                let element = $('input[name="' + keyName + '[]"]')[
                  valueInsideBrackets
                ];

                $(element).after(value);
                if (value) {
                  let lastTd = $(element).parent().parent().find("td:last");
                  $(lastTd).addClass("td-error");
                }

                if (!value) {
                  let lastTd = $(element).parent().parent().find("td:last");
                  $(lastTd).addClass("td-success");
                }
              });
              $(".td-success").html(iconSuccess);
              $(".td-error").html(iconError);
            }
          } else {
            $("#kt_datatable_vertical_scroll tbody tr").each(function () {
              $(this).find("td:last").removeClass("td-error");
              $(this).find("td:last").addClass("td-success");
            });
            $(".invalid-feedback").remove();
            $("#btnNext").remove();

            $("#btnCloseModalFullscreen").after(
              '<button class="btn btn-primary btn-rounded ml-2" type="button" id="saveMassUpload">Save Changes</button>'
            );
            $(".td-success").html(iconSuccess);
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
    }
  });

  $(document).on("click", "#btnAdd", function () {
    action = "add";

    buttonAction($(this), "#modalLarge2");

    $("#btnProcessModal").hide();
    $("#formatError").hide();

    let elements = Array.prototype.slice.call(
      document.querySelectorAll("[data-bs-stacked-modal]")
    );

    if (elements && elements.length > 0) {
      elements.forEach((element) => {
        if (element.getAttribute("data-kt-initialized") === "1") {
          return;
        }

        element.setAttribute("data-kt-initialized", "1");

        element.addEventListener("click", function (e) {
          e.preventDefault();

          const modalEl = document.querySelector(
            this.getAttribute("data-bs-stacked-modal")
          );

          if (modalEl) {
            const modal = new bootstrap.Modal(modalEl);
            modal.show();
          }
        });
      });
    }

    $("#kt_datatable_fixed_columns").DataTable({
      scrollY: 350,
      scrollX: true,
    });

    listSuppliers();
    listWarehouse();

    let element = document.querySelector("#kt_stepper_example_basic");
    let stepper = new KTStepper(element);

    stepper.on("kt.stepper.next", function (stepper) {
      stepper.goNext();

      $(".last").length == 1 ? $("#btnProcessModal").show() : "";
    });

    stepper.on("kt.stepper.previous", function (stepper) {
      stepper.goPrevious();
    });
  });

  $(document).on("click", "#search_suppliers", function () {
    listSuppliers();
  });

  $(document).on("click", "#search_brand", function () {
    $("#kt_datatable_brand").DataTable({
      ajax: {
        url: base_url() + "inventory_requisition/brands",
        type: "POST",
        data: function () {
          let data = [];
          data.push({ name: "supp_id", value: $("#url_supplier_id").val() });
          data.push({ name: "_token", value: getCookie() });
          data.push({
            name: "dataBrands",
            value: $("#brand").val(),
          });
          return data;
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
        { data: "brand_code" },
        { data: "brand_name" },
        { data: "description" },
        { data: "" },
      ],
      columnDefs: [
        {
          // For Responsive
          width: "20px",
          orderable: false,
          responsivePriority: 2,
          targets: 0,
        },
        {
          // Actions
          targets: -1,
          width: "20px",
          title: "Action",
          orderable: false,
          className: "text-left",
          render: function (data, type, full, meta) {
            let ActionButton = "";

            if (action === "add") {
              ActionButton =
                `<a href="javascript:void(0)" onClick="changeURLBrand(this);" data-brand_id="` +
                full["brand_id"] +
                `" data-brand_name="` +
                full["brand_name"] +
                `" class="btn btn-bg-success btn-sm">Choose</a>`;
            } else {
              ActionButton =
                `<a href="javascript:void(0)" onClick="changeBrand(this);" data-brand_id="` +
                full["brand_id"] +
                `" data-brand_name="` +
                full["brand_name"] +
                `" class="btn btn-bg-success btn-sm">Choose</a>`;
            }

            return ActionButton;
          },
        },
      ],
    });
  });

  $(document).on("click", "#search_warehouse", function () {
    listWarehouse();
  });

  $(document).on("click", "#modal_master_data", function () {
    buttonAction($(this), "#modalLarge2");
    $("#btnProcessModal").remove();

    $("#form").attr("id", "parentForm");

    var baseUrlSupplier = base_url() + "suppliers_data/show";
    var columnSupplier = [
      { data: "id" },
      { data: "supplier_code" },
      { data: "supplier_name" },
      { data: "email" },
      { data: "address" },
      { data: "phone" },
      { data: "action", width: "17%" },
    ];

    ajax_crud_table_custom(
      baseUrlSupplier,
      columnSupplier,
      "kt_datatable_suppliers"
    );

    var baseUrlBrand = base_url() + "brand/show";
    var columnBrand = [
      { data: "id" },
      { data: "brand_code" },
      { data: "brand_name" },
      { data: "description" },
      { data: "action", width: "17%" },
    ];

    ajax_crud_table_custom1(baseUrlBrand, columnBrand, "kt_datatable_brand");

    var baseUrlWarehouse = base_url() + "master_warehouse/show";
    var columnWarehouse = [
      { data: "id" },
      { data: "warehouse_code" },
      { data: "warehouse_name" },
      { data: "email" },
      { data: "address" },
      { data: "phone" },
      { data: "action", width: "17%" },
    ];

    ajax_crud_table_custom2(
      baseUrlWarehouse,
      columnWarehouse,
      "kt_datatable_warehouse"
    );
  });

  $(document).on("click", "#btnAddSupplier,.btnEditSupplier", function () {
    buttonAction($(this), "#modalLarge5");

    var phone_key = $("#phone_key").val();
    $("#phone").val(phone_key.split("/")[0]);
    $("#phone2").val(phone_key.split("/")[1]);

    $("#button_mass_upload").remove();
    $("#show_mass_upload").remove();
    $("#btnProcessModal").attr("id", "btnProcessSupplierRequisition");

    $("#kt_docs_repeater_advanced").repeater({
      isFirstItemUndeletable: true,
      initEmpty: false,
      show: function () {
        $(this).slideDown();
        $(this).find(".select2-container").remove();
        var select_brand = $(this).find("select")[0];
        var select_type = $(this).find("select")[1];
        var $select = $("#select_brand_id");
        $select.each(function () {
          var $this = $(this);
          $this.select2({
            placeholder: "--Choose Options--",
            // minimumResultsForSearch: Infinity,
            dropdownParent: $this
              .parent()
              .parent()
              .parent()
              .parent()
              .parent()
              .parent()
              .parent()
              .parent()
              .parent()
              .parent()
              .parent()
              .parent()
              .parent(),
            allowClear: true,
          });
        });

        var $select2 = $("#" + select_type.id);
        $select2.each(function () {
          var $this = $(this);
          $this.select2({
            placeholder: "--Choose Options--",
            // minimumResultsForSearch: Infinity,
            dropdownParent: $this
              .parent()
              .parent()
              .parent()
              .parent()
              .parent()
              .parent()
              .parent()
              .parent()
              .parent()
              .parent()
              .parent()
              .parent()
              .parent(),
            allowClear: true,
          });
        });

        $(this).find(".select2-container").css("width", "100%");
      },

      hide: function (deleteElement) {
        $(this).slideUp(deleteElement);
      },

      ready: function () {
        var $select = $("#select_brand_id");
        $select.each(function () {
          var $this = $(this);
          $this.select2({
            placeholder: "--Choose Options--",
            // minimumResultsForSearch: Infinity,
            dropdownParent: $this
              .parent()
              .parent()
              .parent()
              .parent()
              .parent()
              .parent()
              .parent()
              .parent()
              .parent()
              .parent()
              .parent()
              .parent()
              .parent(),
            allowClear: true,
          });
        });

        var $select2 = $("#select_type_ownership");
        $select2.each(function () {
          var $this = $(this);
          $this.select2({
            placeholder: "--Choose Options--",
            // minimumResultsForSearch: Infinity,
            dropdownParent: $this
              .parent()
              .parent()
              .parent()
              .parent()
              .parent()
              .parent()
              .parent()
              .parent()
              .parent()
              .parent()
              .parent()
              .parent()
              .parent(),
            allowClear: true,
          });
        });
      },
    });
  });

  modalMasterData("#btnAddBrand,.btnEditBrand", "btnProcessBrandRequisition");
  modalMasterData(
    "#btnAddWarehouse,.btnEditWarehouse",
    "btnProcessWarehouseRequisition"
  );

  saveButton(
    "#btnProcessSupplierRequisition",
    base_url() + "suppliers_data/process"
  );
  saveButton("#btnProcessBrandRequisition", base_url() + "brand/process");
  saveButton(
    "#btnProcessWarehouseRequisition",
    base_url() + "master_warehouse/process"
  );

  modalCloseCustom("#btnCloseSuppliers", "#modalLarge3");
  modalCloseCustom("#btnCloseModal", "#modalLarge5");
  modalCloseCustom("#btnCloseModalFullscreen", "#modalLarge2");
  modalCloseCustom("#btnCloseModalFullscreen2", "#modalLarge3");
});

function listWarehouse() {
  $("#kt_datatable_warehouse").DataTable({
    ajax: {
      url: base_url() + "inventory_requisition/warehouse",
      type: "POST",
      data: function () {
        let data = [];
        data.push({ name: "_token", value: getCookie() });
        data.push({
          name: "dataWarehouse",
          value: $("#warehouse").val(),
        });
        return data;
      },
    },
    processing: true,
    serverSide: true,
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
      { data: "warehouse_name" },
      { data: "email" },
      { data: "address" },
      { data: "phone" },
      { data: "" },
    ],
    columnDefs: [
      {
        // For Responsive
        width: "20px",
        orderable: false,
        responsivePriority: 2,
        targets: 0,
      },
      {
        // Actions
        targets: -1,
        width: "20px",
        title: "Action",
        orderable: false,
        className: "text-left",
        render: function (data, type, full, meta) {
          let ActionButton = "";

          if (action === "add") {
            ActionButton =
              `<a href="javascript:void(0)" id="warehouseID" onClick="changeURLWarehouse(this);" data-warehouse_id="` +
              full["warehouse_id"] +
              `" data-warehouse_name="` +
              full["warehouse_name"] +
              `" class="btn btn-bg-success btn-sm">Choose</a>`;
          } else {
            ActionButton =
              `<a href="javascript:void(0)" id="warehouseID" onClick="changeWarehouse(this);" data-warehouse_id="` +
              full["warehouse_id"] +
              `" data-warehouse_name="` +
              full["warehouse_name"] +
              `" class="btn btn-bg-success btn-sm">Choose</a>`;
          }

          return ActionButton;
        },
      },
    ],
  });
}

function listSuppliers() {
  $("#kt_datatable_suppliers").DataTable({
    ajax: {
      url: base_url() + "inventory_requisition/suppliers",
      type: "POST",
      data: function () {
        let data = [];
        data.push({ name: "_token", value: getCookie() });
        data.push({
          name: "dataSuppliers",
          value: $("#suppliers").val(),
        });
        return data;
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
      { data: "supplier_name" },
      { data: "email" },
      { data: "address" },
      { data: "phone" },
      { data: "" },
    ],
    columnDefs: [
      {
        // For Responsive
        width: "20px",
        orderable: false,
        responsivePriority: 2,
        targets: 0,
      },
      {
        // Actions
        targets: -1,
        width: "20px",
        title: "Action",
        orderable: false,
        className: "text-left",
        render: function (data, type, full, meta) {
          let ActionButton = "";
          if (action === "add") {
            ActionButton =
              `<a href="javascript:void(0)" onClick="getURL(this);" data-supp_id="` +
              full["supplier_id"] +
              `" data-supp_name="` +
              full["supplier_name"] +
              `" data-supp_email="` +
              full["email"] +
              `" class="btn btn-bg-success btn-sm">Choose</a>`;
          } else {
            ActionButton =
              `<a href="javascript:void(0)" onClick="changeSupplier(this);" data-supp_id="` +
              full["supplier_id"] +
              `" data-supp_name="` +
              full["supplier_name"] +
              `" data-supp_email="` +
              full["email"] +
              `" class="btn btn-bg-success btn-sm">Choose</a>`;
          }

          return ActionButton;
        },
      },
    ],
  });
}

//Get URL Params
const urlParams = new URLSearchParams(window.location.search);

function changeURLWarehouse(e) {
  let warehouse_id = $(e).data("warehouse_id");
  let warehouse_name = $(e).data("warehouse_name");

  $("#supp_name3").text($("#supp_name").text());
  $("#brand_name2").text($("#brand_name").text());
  $("#supp_email").text($("#supplier_email").val());

  $("#warehouse_name").text(warehouse_name);
  $("#url_warehouse_id").val(warehouse_id);

  $("#continueButton").click();

  let cekBtn = $("#btnNext").length;

  if (cekBtn === 0) {
    $("#btnCloseModalFullscreen").after(
      '<button class="btn btn-info btn-rounded ml-2" type="button" id="btnNext">Next</button>'
    );
  }
}

function changeURLBrand(e) {
  let brand_id = $(e).data("brand_id");
  let brand_name = $(e).data("brand_name");

  $("#supp_name1").text($("#supp_name").text());
  $("#brand_name").text(brand_name);
  $("#url_brand_id").val(brand_id);

  $("#continueButton").click();
}

function getURL(e) {
  let supp_id = $(e).data("supp_id");
  let supp_name = $(e).data("supp_name");
  let supp_email = $(e).data("supp_email");

  $("#supp_name").text(supp_name);
  $("#url_supplier_id").val(supp_id);
  $("#supplier_email").val(supp_email);

  $("#kt_datatable_brand").DataTable({
    ajax: {
      url: base_url() + "inventory_requisition/brands",
      type: "POST",
      data: function () {
        let data = [];
        data.push({ name: "supp_id", value: supp_id });
        data.push({ name: "_token", value: getCookie() });
        data.push({
          name: "dataBrands",
          value: $("#brand").val(),
        });
        return data;
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
      { data: "brand_code" },
      { data: "brand_name" },
      { data: "description" },
      { data: "" },
    ],
    columnDefs: [
      {
        // For Responsive
        width: "20px",
        orderable: false,
        responsivePriority: 2,
        targets: 0,
      },
      {
        // Actions
        targets: -1,
        width: "20px",
        title: "Action",
        orderable: false,
        className: "text-left",
        render: function (data, type, full, meta) {
          let ActionButton = "";

          if (action === "add") {
            ActionButton =
              `<a href="javascript:void(0)" onClick="changeURLBrand(this);" data-brand_id="` +
              full["brand_id"] +
              `" data-brand_name="` +
              full["brand_name"] +
              `" class="btn btn-bg-success btn-sm">Choose</a>`;
          } else {
            ActionButton =
              `<a href="javascript:void(0)" onClick="changeBrand(this);" data-brand_id="` +
              full["brand_id"] +
              `" data-brand_name="` +
              full["brand_name"] +
              `" class="btn btn-bg-success btn-sm">Choose</a>`;
          }

          return ActionButton;
        },
      },
    ],
  });

  $("#continueButton").click();
}

function changeSupplier(e) {
  let supp_id = $(e).data("supp_id");
  let supp_name = $(e).data("supp_name");
  let supp_email = $(e).data("email");

  $("#val_supp").val(supp_name);
  $("#set_supplier_id").val(supp_id);
  $("#supp_email").val(supp_email);

  $("#val_brand").val("");

  $("#kt_datatable_brand").DataTable({
    ajax: {
      url: base_url() + "inventory_requisition/brands",
      type: "POST",
      data: function () {
        let data = [];
        data.push({ name: "supp_id", value: supp_id });
        data.push({ name: "_token", value: getCookie() });
        data.push({
          name: "dataBrands",
          value: $("#brand").val(),
        });
        return data;
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
      { data: "brand_code" },
      { data: "brand_name" },
      { data: "description" },
      { data: "" },
    ],
    columnDefs: [
      {
        // For Responsive
        width: "20px",
        orderable: false,
        responsivePriority: 2,
        targets: 0,
      },
      {
        // Actions
        targets: -1,
        width: "20px",
        title: "Action",
        orderable: false,
        className: "text-left",
        render: function (data, type, full, meta) {
          let ActionButton = "";

          if (action === "add") {
            ActionButton =
              `<a href="javascript:void(0)" onClick="changeURLBrand(this);" data-brand_id="` +
              full["brand_id"] +
              `" data-brand_name="` +
              full["brand_name"] +
              `" class="btn btn-bg-success btn-sm">Choose</a>`;
          } else {
            ActionButton =
              `<a href="javascript:void(0)" onClick="changeBrand(this);" data-brand_id="` +
              full["brand_id"] +
              `" data-brand_name="` +
              full["brand_name"] +
              `" class="btn btn-bg-success btn-sm">Choose</a>`;
          }

          return ActionButton;
        },
      },
    ],
  });

  $("#modalLarge3").modal("hide");
}

function changeBrand(e) {
  let brand_id = $(e).data("brand_id");
  let brand_name = $(e).data("brand_name");

  $("#val_brand").val(brand_name);
  $("#set_brand_id").val(brand_id);

  $("#modalLarge3").modal("hide");
  $("#dialog").html("");
}

function changeWarehouse(e) {
  let warehouse_id = $(e).data("warehouse_id");
  let warehouse_name = $(e).data("warehouse_name");

  $("#val_warehouse").val(warehouse_name);
  $("#set_warehouse_id").val(warehouse_id);

  $("#modalLarge3").modal("hide");
}

$(document).on("click", "#btnAddSKU", function () {
  let url = base_url() + "inventory_requisition/listProductSku";

  let get_supp_id =
    typeof $("#set_supplier_id").val() != "undefined"
      ? $("#set_supplier_id").val()
      : $("#url_supplier_id").val();
  let get_brand_id =
    typeof $("#set_brand_id").val() != "undefined"
      ? $("#set_brand_id").val()
      : $("#url_brand_id").val();

  buttonAction($(this), "#modalLarge3");

  let data = [
    { name: "_token", value: getCookie() },
    { name: "set_id_supplier", value: get_supp_id },
    { name: "set_brand_id", value: get_brand_id },
  ];

  let dataChecked = [];

  $.ajax({
    url: url,
    method: "POST",
    dataType: "JSON",
    data: $.param(data),
    async: false,
    success: function (response) {
      let getJsonData = response.data;

      for (let i = 0; i < getJsonData.length; i++) {
        let arrayData = [
          { sku: getJsonData[i].sku },
          { product_id: getJsonData[i].product_id },
          { product_name: getJsonData[i].product_name },
          { brand_name: getJsonData[i].brand_name },
          { category_name: getJsonData[i].category_name },
          { product_size: getJsonData[i].product_size },
          { color_name: getJsonData[i].color_name },
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
          getJsonData[i].sku +
          `</td>     
          <td style='vertical-align: middle;'>` +
          getJsonData[i].product_name +
          `</td>
          <td style='vertical-align: middle;'>` +
          getJsonData[i].brand_name +
          `</td>
          <td style='vertical-align: middle;'>` +
          getJsonData[i].category_name +
          `</td>
          <td style='vertical-align: middle;'>` +
          getJsonData[i].product_size +
          `</td>
          <td style='vertical-align: middle;'>
            <a href="javascript:void(0)" class="symbol symbol-35px">
                <span class="symbol-label" style="background-color:#` +
          getJsonData[i].color +
          `;">
                </span>
            </a>
          </td>`;

        tr_table += `</tr>`;

        $("#kt_datatable_sku tbody").append(tr_table);
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
      { name: "set_id_supplier", value: get_supp_id },
      { name: "set_brand_id", value: get_brand_id },
      { name: "value_input", value: getSKU },
    ];

    $("#kt_datatable_sku").DataTable({
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
        { data: "sku" },
        { data: "product_name" },
        { data: "brand_name" },
        { data: "category_name" },
        { data: "product_size" },
        {
          data: "color",
          render: function (data) {
            var color =
              `<a href="javascript:void(0)" class="symbol symbol-35px">
                <span class="symbol-label" style="background-color:#` +
              data +
              `;">
                </span>
            </a>`;

            return color;
          },
        },
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

$(document).on("click", "#btnAddSKUEdit", function () {
  let url = base_url() + "inventory_requisition/listProductSku";

  let get_supp_id =
    typeof $("#set_supplier_id").val() != "undefined"
      ? $("#set_supplier_id").val()
      : $("#url_supplier_id").val();
  let get_brand_id =
    typeof $("#set_brand_id").val() != "undefined"
      ? $("#set_brand_id").val()
      : $("#url_brand_id").val();

  buttonAction($(this), "#modalLarge3");

  let data = [
    { name: "_token", value: getCookie() },
    { name: "set_id_supplier", value: get_supp_id },
    { name: "set_brand_id", value: get_brand_id },
  ];

  let dataChecked = [];

  $.ajax({
    url: url,
    method: "POST",
    dataType: "JSON",
    data: $.param(data),
    async: false,
    success: function (response) {
      let getJsonData = response.data;

      for (let i = 0; i < getJsonData.length; i++) {
        let arrayData = [
          { sku: getJsonData[i].sku },
          { product_id: getJsonData[i].product_id },
          { product_name: getJsonData[i].product_name },
          { brand_name: getJsonData[i].brand_name },
          { category_name: getJsonData[i].category_name },
          { product_size: getJsonData[i].product_size },
          { color_name: getJsonData[i].color_name },
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
          getJsonData[i].sku +
          `</td>     
          <td style='vertical-align: middle;'>` +
          getJsonData[i].product_name +
          `</td>
          <td style='vertical-align: middle;'>` +
          getJsonData[i].brand_name +
          `</td>
          <td style='vertical-align: middle;'>` +
          getJsonData[i].category_name +
          `</td>
          <td style='vertical-align: middle;'>` +
          getJsonData[i].product_size +
          `</td>
          <td style='vertical-align: middle;'>
            <a href="javascript:void(0)" class="symbol symbol-35px">
                <span class="symbol-label" style="background-color:#` +
          getJsonData[i].color +
          `;">
                </span>
            </a>
          </td>`;

        tr_table += `</tr>`;

        $("#kt_datatable_sku tbody").append(tr_table);
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
      { name: "set_id_supplier", value: get_supp_id },
      { name: "set_brand_id", value: get_brand_id },
      { name: "value_input", value: getSKU },
    ];

    $("#kt_datatable_sku").DataTable({
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
        { data: "sku" },
        { data: "product_name" },
        { data: "brand_name" },
        { data: "category_name" },
        { data: "product_size" },
        {
          data: "color",
          render: function (data) {
            var color =
              `<a href="javascript:void(0)" class="symbol symbol-35px">
                <span class="symbol-label" style="background-color:#` +
              data +
              `;">
                </span>
            </a>`;

            return color;
          },
        },
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

function isDuplicateSKU(sku) {
  let isDuplicate = false;
  $("#kt_datatable_vertical_scroll tbody tr").each(function () {
    let existingSKU = $(this).find("td:eq(3)").text(); // Assuming the SKU is in the 4th column
    if (existingSKU === sku) {
      isDuplicate = true;
      return false; // Exit the loop early since we found a duplicate
    }
  });
  return isDuplicate;
}

$(document).on("click", "#btnProcessModal2", function (e) {
  var target = $(".modal-upload").parent().parent().parent(".modal-content")[0];
  var blockUI = KTBlockUI.getInstance(target);
  e.preventDefault();
  blockUI.block();

  let getLastNumber = $(
    $("#kt_datatable_sku tbody tr:last").find("td")[1]
  ).text();

  let no = 1;
  $("input[id=checkedBox]").each(function (i, n) {
    if (this.checked) {
      let getJsonData = JSON.parse(atob($(this).data("value")))[i];

      let tr_table = ` <tr>`;
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
          </td>
          <td style='text-align: center;vertical-align: middle;'> ` +
        no++ +
        `</td>
          <td style='vertical-align: middle;'>` +
        getJsonData[2].product_name +
        `</td>
          <td style='vertical-align: middle;'>` +
        getJsonData[0].sku +
        `</td>
          <td style='vertical-align: middle;'>` +
        getJsonData[3].brand_name +
        `</td>
          <td style='vertical-align: middle;'>` +
        getJsonData[4].category_name +
        `</td>
          <td style='vertical-align: middle;'>` +
        getJsonData[5].product_size +
        `</td>
          <td style='vertical-align: middle;'>` +
        getJsonData[6].color_name +
        `</td>
          <td>
          <input type="text" onkeyup="formatCurrency(this)" class="form-control mb-3 mb-lg-0" data-type="input" name="quantity[]"/>
          </td>
          <td style='text-align: center;vertical-align: middle;'>
            <select name="type[]" data-control="select2" data-placeholder="Filter" style="border: 1px solid black;" class="form-select form-select-sm form-select-solid w-150px me-5" tabindex="-1" aria-hidden="true" data-kt-initialized="1">
                  <option value="New Product">New Product</option>
                  <option value="New Variant">New Variant</option>
                  <option value="Re Stock">Re Stock</option>
                  <option value="Replenishment">Replenishment</option>
              </select>
          </td>
          <td>
            <input type="text" onkeyup="formatCurrency(this)" class="form-control mb-3 mb-lg-0" data-type="input" name="price[]"/>
          </td>
          <td>
            <input onkeyup="formatCurrency(this)" type="text" class="form-control mb-3 mb-lg-0" data-type="input" name="material_cost[]"/>
          </td>
          <td>
            <input type="text" onkeyup="formatCurrency(this)" class="form-control mb-3 mb-lg-0" data-type="input" name="service_cost[]"/>
          </td>
          <td>
            <input type="text" onkeyup="formatCurrency(this)" class="form-control mb-3 mb-lg-0" data-type="input" name="overhead_cost[]"/>
          </td>
          <td>
            <input type="text" class="form-control mb-3 mb-lg-0" data-type="input" name="description[]"/>
          </td>
          <td>
            <input type="file" id="image_file" name="image_file[]" class="form-control mb-3 mb-lg-0 imageRow" multiple data-type="input" accept=".jpg, .jpeg, .png"/>
          </td>
          <td class="td-success" style='text-align: center;vertical-align: middle;'>` +
        iconSuccess +
        `</td>
          <input type="hidden" name="product_id[]" value="` +
        getJsonData[1].product_id +
        `" />
          <input type="hidden" name="product_name[]" value="` +
        getJsonData[2].product_name +
        `" />
          <input type="hidden" name="sku[]" value="` +
        getJsonData[0].sku +
        `" />
          <input type="hidden" name="brand_name[]" value="` +
        getJsonData[3].brand_name +
        `" />
          <input type="hidden" name="categories_name[]" value="` +
        getJsonData[4].category_name +
        `" />
          <input type="hidden" name="product_size[]" value="` +
        getJsonData[5].product_size +
        `" />
          <input type="hidden" name="color[]" value="` +
        getJsonData[6].color_name +
        `" />
          <input type="hidden" name="detail_id[]" value="0">`;
      tr_table += `</tr>`;

      $("#kt_datatable_vertical_scroll tbody").append(tr_table);

      // if (!isDuplicateSKU(getJsonData.sku)) {
      //   $("#kt_datatable_vertical_scroll tbody").append(tr_table);
      // }
    }
  });

  $("#modalLarge3").modal("hide");

  setTimeout(function () {
    blockUI.release();
  }, 700);
});

$(document).on("click", "#btnPutSkuEdit", function (e) {
  var target = $(".modal-upload").parent().parent().parent(".modal-content")[0];
  var blockUI = KTBlockUI.getInstance(target);
  e.preventDefault();
  blockUI.block();

  let getLastNumber = $(
    $("#kt_datatable_sku tbody tr:last").find("td")[1]
  ).text();

  let no = 1;
  $("input[id=checkedBox]").each(function (i, n) {
    if (this.checked) {
      let getJsonData = JSON.parse(atob($(this).data("value")))[i];

      let tr_table = ` <tr>`;
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
          </td>
          <td style='text-align: center;vertical-align: middle;'> ` +
        no++ +
        `</td>
          <td style='vertical-align: middle;'>` +
        getJsonData[2].product_name +
        `</td>
          <td style='vertical-align: middle;'>` +
        getJsonData[0].sku +
        `</td>
          <td style='vertical-align: middle;'>` +
        getJsonData[3].brand_name +
        `</td>
          <td style='vertical-align: middle;'>` +
        getJsonData[4].category_name +
        `</td>
          <td style='vertical-align: middle;'>` +
        getJsonData[5].product_size +
        `</td>
          <td style='vertical-align: middle;'>` +
        getJsonData[6].color_name +
        `</td>
          <td>
          <input type="text" onkeyup="formatCurrency(this)" class="form-control mb-3 mb-lg-0" data-type="input" name="quantity[]"/>
          </td>
          <td style='text-align: center;vertical-align: middle;'>
            <select name="type[]" data-control="select2" data-placeholder="Filter" style="border: 1px solid black;" class="form-select form-select-sm form-select-solid w-150px me-5" tabindex="-1" aria-hidden="true" data-kt-initialized="1">
                  <option value="New Product">New Product</option>
                  <option value="New Variant">New Variant</option>
                  <option value="Re Stock">Re Stock</option>
                  <option value="Replenishment">Replenishment</option>
              </select>
          </td>
          <td>
            <input type="text" onkeyup="formatCurrency(this)" class="form-control mb-3 mb-lg-0" data-type="input" name="price[]"/>
          </td>
          <td>
            <input onkeyup="formatCurrency(this)" type="text" class="form-control mb-3 mb-lg-0" data-type="input" name="material_cost[]"/>
          </td>
          <td>
            <input type="text" onkeyup="formatCurrency(this)" class="form-control mb-3 mb-lg-0" data-type="input" name="service_cost[]"/>
          </td>
          <td>
            <input type="text" onkeyup="formatCurrency(this)" class="form-control mb-3 mb-lg-0" data-type="input" name="overhead_cost[]"/>
          </td>
          <td>
            <input type="text" class="form-control mb-3 mb-lg-0" data-type="input" name="description[]"/>
          </td>
          <td style='text-align: center;vertical-align: middle;'>
          <div class="d-flex align-items-center">
              <a style="cursor: pointer;" class="symbol symbol-50px" data-type="modal" data-url="assets/uploads/requisition_image/2023_09_14_0.png" data-fullscreenmodal="0"">
                  <span class="symbol-label" style="background-image:url(assets/uploads/default.png);border: 1px solid #000;"></span>
              </a>
            </div>
          </td>
          <td>
            <input type="file" id="image_file" name="image_file[]" class="form-control mb-3 mb-lg-0 imageRow" multiple data-type="input" accept=".jpg, .jpeg, .png"/>
          </td>
          <td class="td-success" style='text-align: center;vertical-align: middle;'>` +
        iconSuccess +
        `</td>
          <input type="hidden" name="product_id[]" value="` +
        getJsonData[1].product_id +
        `" />
          <input type="hidden" name="product_name[]" value="` +
        getJsonData[2].product_name +
        `" />
          <input type="hidden" name="sku[]" value="` +
        getJsonData[0].sku +
        `" />
          <input type="hidden" name="brand_name[]" value="` +
        getJsonData[3].brand_name +
        `" />
          <input type="hidden" name="categories_name[]" value="` +
        getJsonData[4].category_name +
        `" />
          <input type="hidden" name="product_size[]" value="` +
        getJsonData[5].product_size +
        `" />
          <input type="hidden" name="color[]" value="` +
        getJsonData[6].color_name +
        `" />
          <input type="hidden" name="detail_id[]" value="0">`;
      tr_table += `</tr>`;

      $("#kt_datatable_vertical_scroll tbody").append(tr_table);

      // if (!isDuplicateSKU(getJsonData.sku)) {
      //   $("#kt_datatable_vertical_scroll tbody").append(tr_table);
      // }
    }
  });

  $("#modalLarge3").modal("hide");

  setTimeout(function () {
    blockUI.release();
  }, 700);
});

$(document).on("click", "#buttonDeletedDetail", function () {
  $(this).parent().parent().addClass("deleted");

  $("#kt_datatable_fixed_columns").DataTable().rows(".deleted").remove().draw();

  let getId = $(this).data("id");

  let dataUpload = [];

  dataUpload.push({ name: "_token", value: getCookie() });
  dataUpload.push({ name: "set_id_detail", value: JSON.stringify(getId) });
  $.ajax({
    url: base_url() + "inventory_requisition/delete",
    method: "POST",
    dataType: "JSON",
    async: false,
    data: dataUpload,
    success: function (result) {},
  });
});

$(document).on("click", ".btnEditPO", function (e) {
  action = "edit";

  let button = $(this);
  let modal = "#modalLarge2";

  let url = button.data("url");
  let type = button.data("type");
  let fullscreen = button.data("fullscreenmodal");
  let modalID = modal == null ? "#modalLarge" : modal;
  if (type == "modal") {
    let data = [];
    data.push({ name: "_token", value: getCookie() });
    data.push({ name: "type", value: type });
    data.push({
      name: "set_id_supplier",
      value: setTimeout(() => {
        console.log($("#set_supplier_id").val());
      }, 50),
    });
    data.push({
      name: "set_brand_id",
      value: setTimeout(() => {
        console.log($("#set_brand_id").val());
      }, 50),
    });
    $.ajax({
      url: url,
      method: "POST",
      dataType: "JSON",
      data: $.param(data),
      async: false,
      success: function (response) {
        $(modalID + ".modal-dialog").removeClass("modal-fullscreen");
        if (typeof response.failed == "undefined") {
          if (fullscreen == 1) {
            $(modalID + " .modal-dialog").addClass("modal-fullscreen");
          }
          $(modalID + " .modal-content").html(response.html);
          checkLibraryOnModal();
          $(modalID).modal("show");
        } else {
          sweetAlertMessage(response.message);
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
  }
  if (type == "redirect") {
    window.location.href = url;
  }

  let getId = $(this).data("id");

  let dataUpload = [];

  dataUpload.push({ name: "_token", value: getCookie() });
  dataUpload.push({ name: "set_id_detail", value: JSON.stringify(getId) });
  $.ajax({
    url: base_url() + "inventory_requisition/listDetailPO",
    method: "POST",
    dataType: "JSON",
    async: false,
    data: dataUpload,
    success: function (result) {
      let getJsonData = result.data;

      let no = 1;
      for (let i = 0; i < getJsonData.length; i++) {
        let prdct = getJsonData[i].type === "New Product" ? "selected" : "";
        let vriant = getJsonData[i].type === "New Variant" ? "selected" : "";
        let restck = getJsonData[i].type === "Re Stock" ? "selected" : "";
        let reples = getJsonData[i].type === "Replenishment" ? "selected" : "";
        let image_file = "";

        if ($.trim(getJsonData[i].image_name) === "") {
          image_file = "assets/uploads/default.png";
        } else {
          image_file =
            "assets/uploads/requisition_image/" + getJsonData[i].image_name;
        }

        let tr_table = ` <tr>`;

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
        </td>
        <td style='text-align: center;vertical-align: middle;'> ` +
          no +
          `</td>
        <td style='vertical-align: middle;'>` +
          getJsonData[i].product_name +
          `</td>
        <td style='vertical-align: middle;'>` +
          getJsonData[i].sku +
          `</td>
        <td style='vertical-align: middle;'>` +
          getJsonData[i].brand_name +
          `</td>
        <td style='vertical-align: middle;'>` +
          getJsonData[i].category_name +
          `</td>
        <td style='vertical-align: middle;'>` +
          getJsonData[i].product_size +
          `</td>
        <td style='vertical-align: middle;'>` +
          getJsonData[i].color +
          `</td>
        <td> 
        <input type="text" onkeyup="formatCurrency(this)" class="form-control mb-3 mb-lg-0" data-type="input" name="quantity[]" value="` +
          format_number_no_idr(getJsonData[i].quantity) +
          `"/>
        </td>
        <td style='text-align: center;vertical-align: middle;'> 
          <select name="type[]" data-control="select2" data-placeholder="Filter" style="border: 1px solid black;" class="form-select form-select-sm form-select-solid w-150px me-5" tabindex="-1" aria-hidden="true" data-kt-initialized="1">
            <option value="New Product" ` +
          prdct +
          `>New Product
          </option>
                <option value="New Variant" ` +
          vriant +
          `>New Variant</option>
                <option value="Re Stock" ` +
          restck +
          `>Re Stock</option>
                <option value="Replenishment" ` +
          reples +
          `>Replenishment</option>
            </select>
        </td>
        <td> 
          <input type="text" onkeyup="formatCurrency(this)" class="form-control mb-3 mb-lg-0" data-type="input" name="price[]" value="` +
          format_number_no_idr(getJsonData[i].price) +
          `" />
        </td>
        <td> 
          <input onkeyup="formatCurrency(this)" type="text" class="form-control mb-3 mb-lg-0" data-type="input" name="material_cost[]" value="` +
          format_number_no_idr(getJsonData[i].material_cost) +
          `"/> 
        </td>
        <td>
          <input type="text" onkeyup="formatCurrency(this)" class="form-control mb-3 mb-lg-0" data-type="input" name="service_cost[]" value="` +
          format_number_no_idr(getJsonData[i].service_cost) +
          `"/>
        </td>
        <td>
          <input type="text" onkeyup="formatCurrency(this)" class="form-control mb-3 mb-lg-0" data-type="input" name="overhead_cost[]" value="` +
          format_number_no_idr(getJsonData[i].overhead_cost) +
          `"/> 
        </td>
        <td>
          <input type="text" class="form-control mb-3 mb-lg-0" data-type="input" name="description[]" value="` +
          getJsonData[i].description +
          `"/>
        </td>
        <td style='text-align: center;vertical-align: middle;'>
          <div class="d-flex align-items-center">
              <a style="cursor: pointer;" class="symbol symbol-50px" data-type="modal" data-url="assets/uploads/requisition_image/2023_09_14_0.png" data-fullscreenmodal="0"">
                  <span class="symbol-label" style="background-image:url(` +
          image_file +
          `);border: 1px solid #000;"></span>
              </a>
            </div>
        </td>
        <td>
        <input type="file" id="image_file" name="image_file[]" value="` +
          getJsonData[i].image_name +
          `" class="form-control mb-3 mb-lg-0 imageRow" data-type="input" accept=".jpg, .jpeg, .png"/>
        </td>
        <td class="td-success" style='text-align: center;vertical-align: middle;'>` +
          iconSuccess +
          `</td>

        <input type="hidden" name="product_id[]" value="` +
          getJsonData[i].product_id +
          `" />
        <input type="hidden" name="product_name[]" value="` +
          getJsonData[i].product_name +
          `" />
        <input type="hidden" name="sku[]" value="` +
          getJsonData[i].sku +
          `" />
        <input type="hidden" name="brand_name[]" value="` +
          getJsonData[i].brand_name +
          `" />
        <input type="hidden" name="categories_name[]" value="` +
          getJsonData[i].category_name +
          `" />
        <input type="hidden" name="product_size[]" value="` +
          getJsonData[i].product_size +
          `" />
        <input type="hidden" name="color[]" value="` +
          getJsonData[i].color +
          `" />
        <input type="hidden" name="detail_id[]" value="` +
          getJsonData[i].id_po +
          `">`;

        tr_table += `</tr>`;

        $("#kt_datatable_vertical_scroll tbody").append(tr_table);

        no++;

        // if (!isDuplicateSKU(getJsonData.sku)) {
        //   $("#kt_datatable_vertical_scroll tbody").append(tr_table);
        // }
      }
    },
  });
});

$(document).on("click", "#searchSuppliers", function () {
  buttonAction($(this), "#modalLarge3");

  listSuppliers();
  $("#btnProcessModal2").hide();
});

$(document).on("click", "#searchBrand", function () {
  buttonAction($(this), "#modalLarge3");

  $("#kt_datatable_brand").DataTable({
    ajax: {
      url: base_url() + "inventory_requisition/brands",
      type: "POST",
      data: function () {
        let data = [];
        data.push({ name: "supp_id", value: $("#set_supplier_id").val() });
        data.push({ name: "_token", value: getCookie() });
        data.push({
          name: "dataBrands",
          value: $("#brand").val(),
        });
        return data;
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
      { data: "brand_code" },
      { data: "brand_name" },
      { data: "description" },
      { data: "" },
    ],
    columnDefs: [
      {
        // For Responsive
        width: "20px",
        orderable: false,
        responsivePriority: 2,
        targets: 0,
      },
      {
        // Actions
        targets: -1,
        width: "20px",
        title: "Action",
        orderable: false,
        className: "text-left",
        render: function (data, type, full, meta) {
          let ActionButton = "";

          if (action === "add") {
            ActionButton =
              `<a href="javascript:void(0)" onClick="changeURLBrand(this);" data-brand_id="` +
              full["brand_id"] +
              `" data-brand_name="` +
              full["brand_name"] +
              `" class="btn btn-bg-success btn-sm">Choose</a>`;
          } else {
            ActionButton =
              `<a href="javascript:void(0)" onClick="changeBrand(this);" data-brand_id="` +
              full["brand_id"] +
              `" data-brand_name="` +
              full["brand_name"] +
              `" class="btn btn-bg-success btn-sm">Choose</a>`;
          }

          return ActionButton;
        },
      },
    ],
  });
  $("#btnProcessModal2").hide();
});

$(document).on("click", "#searchWarehouse", function () {
  buttonAction($(this), "#modalLarge3");

  listWarehouse();
  $("#btnProcessModal2").hide();
});

$(document).on("click", ".btnDetail", function () {
  buttonAction($(this), "#modalLarge2");

  let getId = $(this).data("id");

  let dataUpload = [];

  let tableRows = $("#kt_datatable_fixed_columns").DataTable();

  dataUpload.push({ name: "_token", value: getCookie() });
  dataUpload.push({ name: "set_id_detail", value: JSON.stringify(getId) });
  $.ajax({
    url: base_url() + "inventory_requisition/listDetailPO",
    method: "POST",
    dataType: "JSON",
    async: false,
    data: dataUpload,
    success: function (result) {
      let getJsonData = result.data;

      for (let i = 0; i < getJsonData.length; i++) {
        let dataToRow = [
          "",
          getJsonData[i].sku,
          getJsonData[i].category_name,
          getJsonData[i].product_name,
          getJsonData[i].brand_name,
          getJsonData[i].product_size,
          getJsonData[i].color,
          getJsonData[i].quantity,
          getJsonData[i].type,
          getJsonData[i].price,
          getJsonData[i].material_cost,
          getJsonData[i].service_cost,
          getJsonData[i].overhead_cost,
          getJsonData[i].description,
        ];

        tableRows.row.add(dataToRow).draw();
      }
    },
  });
});

$(document).on("click", "button[data-type='release']", function () {
  let url = $(this).data("url");
  let confirm = $(this).data("textconfirm");
  let title = $(this).data("title");
  let text = $(this).text();

  Swal.fire({
    title: text + " " + title,
    text: confirm,
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#0CC27E",
    cancelButtonColor: "#FF586B",
    confirmButtonText: "Yes",
    cancelButtonText: "No, cancel!",
    confirmButtonClass: "btn btn-success mr-5",
    cancelButtonClass: "btn btn-danger",
    buttonsStyling: false,
    allowOutsideClick: false,
  }).then((result) => {
    if (result.isConfirmed) {
      let data = [];
      data.push({ name: "_token", value: getCookie() });

      $.ajax({
        url: url,
        method: "POST",
        dataType: "JSON",
        async: false,
        data: $.param(data),
        beforeSend: function () {
          loadingPage();
        },
        success: function (response) {
          reloadDatatables();
          Swal.fire(
            "",
            response.text,
            response.success ? "success" : "error",
            $("#modalLarge2").modal("hide")
          );
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
    } else {
      return false;
    }
  });
});

function ajax_crud_po(base_url, column, tableID) {
  $.fn.dataTableExt.oApi.fnPagingInfo = function (oSettings) {
    return {
      iStart: oSettings._iDisplayStart,
      iEnd: oSettings.fnDisplayEnd(),
      iLength: oSettings._iDisplayLength,
      iTotal: oSettings.fnRecordsTotal(),
      iFilteredTotal: oSettings.fnRecordsDisplay(),
      iPage: Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
      iTotalPages: Math.ceil(
        oSettings.fnRecordsDisplay() / oSettings._iDisplayLength
      ),
    };
  };

  tbl = $("#" + tableID).DataTable({
    initComplete: function () {
      let api = this.api();
      $("#" + tableID + "_filter input")
        .off(".DT")
        .on("keyup.DT", function (e) {
          if (e.keyCode == 13) {
            api.search(this.value).draw();
          }
        });
    },
    processing: true,
    oLanguage: {
      sProcessing: "loading...",
    },
    serverSide: true,
    responsive: false,
    scrollX: true,
    orderable: false,
    dom:
      "<'row'" +
      "<'col-sm-6 d-flex align-items-center justify-conten-start'l>" +
      "<'col-sm-6 d-flex align-items-center justify-content-end'f>" +
      ">" +
      "<'table-responsive'tr>" +
      "<'row'" +
      "<'col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start'i>" +
      "<'col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end'p>" +
      ">",
    ajax: {
      url: base_url,
      type: "POST",
      data: function (d) {
        let data =
          $("#formSearch").length > 0 ? $("#formSearch").serializeArray() : [];
        return $.extend({}, d, {
          _token: getCookie(),
          filters: data,
        });
      },
    },
    columns: column,
    columnDefs: [
      {
        // Actions
        targets: -1,
        title: "Action",
        width: "300px",
        className: "dt-center",
      },
    ],
    rowCallback: function (row, data, iDisplayIndex) {
      if (typeof data.status != "undefined" && data.status !== null) {
        let text = "";
        let color = "";
        switch (data.status) {
          case "enable":
            text = "Disabled";
            color = "btn-outline-warning";
            break;
          case "disable":
            text = "Enabled";
            color = "btn-outline-info";
            break;
          case "1":
            $("td:last", row).html(
              data.release + data.edit_cstom + data.dlete_cstom
            );
            break;
          case "2":
            $("td:last", row).html(data.detail);
            break;
        }

        if (data.status == "enable" || data.status == "disable") {
          let newAction = data.action.replace("Disabled", text);
          let newColorAction = newAction.replace("btn-outline-info", color);

          $("td:last", row).html(newColorAction);
        }
      }

      if (data.action == "") {
        $("td:last", row).remove();
      }
    },
  });
}

let warehouse_tbl = "";
let brand_tbl = "";
let supplier_tbl = "";
function ajax_crud_table_custom(base_url, column, tableID, selected = false) {
  $.fn.dataTableExt.oApi.fnPagingInfo = function (oSettings) {
    return {
      iStart: oSettings._iDisplayStart,
      iEnd: oSettings.fnDisplayEnd(),
      iLength: oSettings._iDisplayLength,
      iTotal: oSettings.fnRecordsTotal(),
      iFilteredTotal: oSettings.fnRecordsDisplay(),
      iPage: Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
      iTotalPages: Math.ceil(
        oSettings.fnRecordsDisplay() / oSettings._iDisplayLength
      ),
    };
  };

  supplier_tbl = $("#" + tableID).DataTable({
    initComplete: function () {
      var api = this.api();
      $("#" + tableID + "_filter input")
        .off(".DT")
        .on("keyup.DT", function (e) {
          if (e.keyCode == 13) {
            api.search(this.value).draw();
          }
        });
    },
    processing: true,
    searching: false,
    oLanguage: {
      sProcessing: "loading...",
    },
    serverSide: true,
    responsive: false,
    select: selected,
    scrollX: false,
    orderable: false,
    dom:
      "<'row'" +
      "<'col-sm-6 d-flex align-items-center justify-conten-start'l>" +
      "<'col-sm-6 d-flex align-items-center justify-content-end'f>" +
      ">" +
      "<'table-responsive'tr>" +
      "<'row'" +
      "<'col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start'i>" +
      "<'col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end'p>" +
      ">",
    ajax: {
      url: base_url,
      type: "POST",
      data: function (d) {
        var data = $("#supplier_master").val();
        return $.extend({}, d, {
          _token: getCookie(),
          master_reqisition: data,
        });
      },
    },
    columns: column,
    columnDefs: [
      {
        // Actions
        targets: -1,
        title: "Action",
        width: "300px",
        className: "dt-center",
      },
    ],
    rowCallback: function (row, data, iDisplayIndex) {
      let info = this.fnPagingInfo();
      let page = info.iPage;
      let length = info.iLength;
      let index = page * length + (iDisplayIndex + 1);

      $("td:eq(0)", row).html(index);

      if (typeof data.status != "undefined" && data.status !== null) {
        let text = "";
        let color = "";

        switch (data.status) {
          case "enable":
            text = "Disabled";
            color = "btn-outline-warning";
            break;
          case "disable":
            text = "Enabled";
            color = "btn-outline-info";
            break;
          case "release":
            $("td:last", row).html(data.detail);
            break;
        }

        if (data.status == "enable" || data.status == "disable") {
          let newAction = data.action.replace("Disabled", text);
          let newColorAction = newAction.replace("btn-outline-info", color);

          $("td:last", row).html(newColorAction);
        }
      }

      if (tableID == "kt_datatable_suppliers") {
        let newAction = data.action
          .replace("btnEdit", "btnEditSupplier")
          .replace('data-type = "modal"', 'data-type = "modal" type="button"')
          .replace("btnDelete", "btnDeleteSupplier")
          .replace(
            'data-type = "confirm"',
            'data-type = "confirm" type="button"'
          );
        $("td:last", row).html(newAction);
      }
      if (tableID == "kt_datatable_brand") {
        let newAction = data.action
          .replace("btnEdit", "btnEditBrand")
          .replace('data-type = "modal"', 'data-type = "modal" type="button"')
          .replace("btnDelete", "btnDeleteBrand")
          .replace(
            'data-type = "confirm"',
            'data-type = "confirm" type="button"'
          );
        $("td:last", row).html(newAction);
      }
      if (tableID == "kt_datatable_warehouse") {
        let newAction = data.action
          .replace("btnEdit", "btnEditWarehouse")
          .replace('data-type = "modal"', 'data-type = "modal" type="button"')
          .replace("btnDelete", "btnDeleteWarehouse")
          .replace(
            'data-type = "confirm"',
            'data-type = "confirm" type="button"'
          );
        $("td:last", row).html(newAction);
      }

      if (data.action == "") {
        $("td:last", row).remove();
      }
    },
  });
}

function ajax_crud_table_custom1(base_url, column, tableID, selected = false) {
  $.fn.dataTableExt.oApi.fnPagingInfo = function (oSettings) {
    return {
      iStart: oSettings._iDisplayStart,
      iEnd: oSettings.fnDisplayEnd(),
      iLength: oSettings._iDisplayLength,
      iTotal: oSettings.fnRecordsTotal(),
      iFilteredTotal: oSettings.fnRecordsDisplay(),
      iPage: Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
      iTotalPages: Math.ceil(
        oSettings.fnRecordsDisplay() / oSettings._iDisplayLength
      ),
    };
  };

  brand_tbl = $("#" + tableID).DataTable({
    initComplete: function () {
      var api = this.api();
      $("#" + tableID + "_filter input")
        .off(".DT")
        .on("keyup.DT", function (e) {
          if (e.keyCode == 13) {
            api.search(this.value).draw();
          }
        });
    },
    processing: true,
    searching: false,
    oLanguage: {
      sProcessing: "loading...",
    },
    serverSide: true,
    responsive: false,
    select: selected,
    scrollX: false,
    orderable: false,
    dom:
      "<'row'" +
      "<'col-sm-6 d-flex align-items-center justify-conten-start'l>" +
      "<'col-sm-6 d-flex align-items-center justify-content-end'f>" +
      ">" +
      "<'table-responsive'tr>" +
      "<'row'" +
      "<'col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start'i>" +
      "<'col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end'p>" +
      ">",
    ajax: {
      url: base_url,
      type: "POST",
      data: function (d) {
        var data = $("#brand_master").val();
        return $.extend({}, d, {
          _token: getCookie(),
          master_reqisition: data,
        });
      },
    },
    columns: column,
    columnDefs: [
      {
        // Actions
        targets: -1,
        title: "Action",
        width: "300px",
        className: "dt-center",
      },
    ],
    rowCallback: function (row, data, iDisplayIndex) {
      let info = this.fnPagingInfo();
      let page = info.iPage;
      let length = info.iLength;
      let index = page * length + (iDisplayIndex + 1);

      $("td:eq(0)", row).html(index);

      if (typeof data.status != "undefined" && data.status !== null) {
        let text = "";
        let color = "";

        switch (data.status) {
          case "enable":
            text = "Disabled";
            color = "btn-outline-warning";
            break;
          case "disable":
            text = "Enabled";
            color = "btn-outline-info";
            break;
          case "release":
            $("td:last", row).html(data.detail);
            break;
        }

        if (data.status == "enable" || data.status == "disable") {
          let newAction = data.action.replace("Disabled", text);
          let newColorAction = newAction.replace("btn-outline-info", color);

          $("td:last", row).html(newColorAction);
        }
      }

      if (tableID == "kt_datatable_suppliers") {
        let newAction = data.action
          .replace("btnEdit", "btnEditSupplier")
          .replace('data-type = "modal"', 'data-type = "modal" type="button"')
          .replace("btnDelete", "btnDeleteSupplier")
          .replace(
            'data-type = "confirm"',
            'data-type = "confirm" type="button"'
          );
        $("td:last", row).html(newAction);
      }
      if (tableID == "kt_datatable_brand") {
        let newAction = data.action
          .replace("btnEdit", "btnEditBrand")
          .replace('data-type = "modal"', 'data-type = "modal" type="button"')
          .replace("btnDelete", "btnDeleteBrand")
          .replace(
            'data-type = "confirm"',
            'data-type = "confirm" type="button"'
          );
        $("td:last", row).html(newAction);
      }
      if (tableID == "kt_datatable_warehouse") {
        let newAction = data.action
          .replace("btnEdit", "btnEditWarehouse")
          .replace('data-type = "modal"', 'data-type = "modal" type="button"')
          .replace("btnDelete", "btnDeleteWarehouse")
          .replace(
            'data-type = "confirm"',
            'data-type = "confirm" type="button"'
          );
        $("td:last", row).html(newAction);
      }

      if (data.action == "") {
        $("td:last", row).remove();
      }
    },
  });
}

function ajax_crud_table_custom2(base_url, column, tableID, selected = false) {
  $.fn.dataTableExt.oApi.fnPagingInfo = function (oSettings) {
    return {
      iStart: oSettings._iDisplayStart,
      iEnd: oSettings.fnDisplayEnd(),
      iLength: oSettings._iDisplayLength,
      iTotal: oSettings.fnRecordsTotal(),
      iFilteredTotal: oSettings.fnRecordsDisplay(),
      iPage: Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
      iTotalPages: Math.ceil(
        oSettings.fnRecordsDisplay() / oSettings._iDisplayLength
      ),
    };
  };

  warehouse_tbl = $("#" + tableID).DataTable({
    initComplete: function () {
      var api = this.api();
      $("#" + tableID + "_filter input")
        .off(".DT")
        .on("keyup.DT", function (e) {
          if (e.keyCode == 13) {
            api.search(this.value).draw();
          }
        });
    },
    processing: true,
    searching: false,
    oLanguage: {
      sProcessing: "loading...",
    },
    serverSide: true,
    responsive: false,
    select: selected,
    scrollX: false,
    orderable: false,
    dom:
      "<'row'" +
      "<'col-sm-6 d-flex align-items-center justify-conten-start'l>" +
      "<'col-sm-6 d-flex align-items-center justify-content-end'f>" +
      ">" +
      "<'table-responsive'tr>" +
      "<'row'" +
      "<'col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start'i>" +
      "<'col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end'p>" +
      ">",
    ajax: {
      url: base_url,
      type: "POST",
      data: function (d) {
        var data = $("#warehouse_master").val();
        return $.extend({}, d, {
          _token: getCookie(),
          master_reqisition: data,
        });
      },
    },
    columns: column,
    columnDefs: [
      {
        // Actions
        targets: -1,
        title: "Action",
        width: "300px",
        className: "dt-center",
      },
    ],
    rowCallback: function (row, data, iDisplayIndex) {
      let info = this.fnPagingInfo();
      let page = info.iPage;
      let length = info.iLength;
      let index = page * length + (iDisplayIndex + 1);

      $("td:eq(0)", row).html(index);

      if (typeof data.status != "undefined" && data.status !== null) {
        let text = "";
        let color = "";

        switch (data.status) {
          case "enable":
            text = "Disabled";
            color = "btn-outline-warning";
            break;
          case "disable":
            text = "Enabled";
            color = "btn-outline-info";
            break;
          case "release":
            $("td:last", row).html(data.detail);
            break;
        }

        if (data.status == "enable" || data.status == "disable") {
          let newAction = data.action.replace("Disabled", text);
          let newColorAction = newAction.replace("btn-outline-info", color);

          $("td:last", row).html(newColorAction);
        }
      }

      if (tableID == "kt_datatable_suppliers") {
        let newAction = data.action
          .replace("btnEdit", "btnEditSupplier")
          .replace('data-type = "modal"', 'data-type = "modal" type="button"')
          .replace("btnDelete", "btnDeleteSupplier")
          .replace(
            'data-type = "confirm"',
            'data-type = "confirm" type="button"'
          );
        $("td:last", row).html(newAction);
      }
      if (tableID == "kt_datatable_brand") {
        let newAction = data.action
          .replace("btnEdit", "btnEditBrand")
          .replace('data-type = "modal"', 'data-type = "modal" type="button"')
          .replace("btnDelete", "btnDeleteBrand")
          .replace(
            'data-type = "confirm"',
            'data-type = "confirm" type="button"'
          );
        $("td:last", row).html(newAction);
      }
      if (tableID == "kt_datatable_warehouse") {
        let newAction = data.action
          .replace("btnEdit", "btnEditWarehouse")
          .replace('data-type = "modal"', 'data-type = "modal" type="button"')
          .replace("btnDelete", "btnDeleteWarehouse")
          .replace(
            'data-type = "confirm"',
            'data-type = "confirm" type="button"'
          );
        $("td:last", row).html(newAction);
      }

      if (data.action == "") {
        $("td:last", row).remove();
      }
    },
  });
}

function saveButton(buttonName, url) {
  btnCloseModal = "#btnCloseModal";
  $(document).on("click", buttonName, function () {
    var textButton = $(this).text();
    var btn = $(this);
    var data = $("#form").serializeArray();

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
            modalAutoClose(btnCloseModal);
          }
          reloadDatatables();
        }
        loadingButtonOff(btn, textButton);
        enabledButton($(btnCloseModal));
        if (response.type == "update") {
          if (response.success) {
            modalAutoClose(btnCloseModal);
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
}

function modalMasterData(btnName, idName) {
  $(document).on("click", btnName, function () {
    buttonAction($(this), "#modalLarge5");
    $("#button_mass_upload").remove();
    $("#show_mass_upload").remove();

    $("#btnProcessModal").attr("id", idName);
  });
}

$(document).on("click", "#search_warehouse_master", function () {
  addDraw();
  warehouse_tbl.ajax.reload();
});

$(document).on("click", "#search_brand_master", function () {
  addDraw();
  brand_tbl.ajax.reload();
});

$(document).on("click", "#search_supplier_master", function () {
  addDraw();
  supplier_tbl.ajax.reload();
});

$(document).on("change", "input[name='sku[]']", function () {
  let sku = $(this).closest("tr").find("td input")[0];
  let set_product_name = $(this).closest("tr").find("td")[2];
  let set_brand_name = $(this).closest("tr").find("td")[4];
  let set_category_name = $(this).closest("tr").find("td")[5];
  let set_size = $(this).closest("tr").find("td")[6];
  let set_color = $(this).closest("tr").find("td")[7];
  let icon = $(this).closest("tr").find("td")[15];

  $(this)
    .closest("tr")
    .each(function name() {
      console.log($(this).find(".invalid-feedback"));
    });

  const url = base_url() + "inventory_requisition/cekSKU";

  const dataPush = [
    { name: "sku_input", value: $(sku).val() },
    { name: "supplier_id", value: $("#url_supplier_id").val() },
    { name: "brand_id", value: $("#url_brand_id").val() },
    { name: "_token", value: getCookie() },
  ];

  $.ajax({
    url: url,
    method: "POST",
    dataType: "JSON",
    async: false,
    data: dataPush,
    success: function (result) {
      let getJsonData = result.data[0];

      $("#kt_datatable_vertical_scroll tbody tr").each(function () {
        $(this).find("td:last").removeClass("td-success");
        $(this).find("td:last").removeClass("td-error");
      });

      if (getJsonData != false) {
        $(set_product_name).text(getJsonData.product_name);
        $(set_brand_name).text(getJsonData.brand_name);
        $(set_category_name).text(getJsonData.categories_name);
        $(set_size).text(getJsonData.product_size);
        $(set_color).text(getJsonData.color);

        $(icon).addClass("td-success");

        $(".td-success").html(iconSuccess);
      } else {
        $(sku).after(
          `<div style="margin-top: -2px;margin-bottom: -29px;" class="fv-plugins-message-container invalid-feedback">SKU Not Exist</div>`
        );
        $(set_product_name).text("");
        $(set_brand_name).text("");
        $(set_category_name).text("");
        $(set_size).text("");
        $(set_color).text("");
        $(icon).addClass("td-error");

        $(".td-error").html(iconError);
      }
    },
    error: function (xhr, status, error) {
      switch (xhr.status) {
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

$(document).on("click", "#download_product", function () {
  let getSupp = $("#url_supplier_id").val();
  let getBrand = $("#url_brand_id").val();

  window.location.href =
    base_url() +
    "inventory_requisition/downloadXlxs?supp_id=" +
    getSupp +
    "&brand_id=" +
    getBrand;
});

$(document).on("click", "#previous", function () {
  $("#kt_datatable_vertical_scroll tbody").html("");
});

$(document).on("click", ".downloadView", function () {
  let selectField = $(this).parent().parent().parent().find("select");
  let inputField = $(this).parent().parent().parent().find("input");
  let statusVal = [];
  let status = $('input[id="lookup_status"]').filter(":checked");
  let startDate = $('input[id="start_date"]');
  let endDate = $('input[id="end_date"]');

  for (let i = 0; i < status.length; i++) {
    statusVal.push($(status[i]).siblings("label").text().trim());
  }

  window.location.href =
    base_url() +
    "inventory_requisition/downloadView" +
    "?searchName=" +
    selectField.val() +
    "&valueSearch=" +
    inputField.val() +
    "&valueStatus=" +
    statusVal +
    "&startDate=" +
    startDate.val() +
    "&endDate=" +
    endDate.val();
});
