$(document).ready(function () {
  var baseurl = base_url() + "category/show";
  var column = [
    { data: "id" },
    { data: "categories_code" },
    { data: "catgry_name" },
    { data: "formatted_catgry_name" },
    { data: "action", width: "17%" },
  ];

  ajax_crud_table(baseurl, column);

  sweetAlertConfirm();
  libraryInput();
  process();

  const reloadJS = (className) => {
    let hideSearch = $(className);
    // Hide Search Box
    hideSearch.each(function () {
      let $this = $(this);
      $this.select2({
        placeholder: "--Choose Options--",
        // minimumResultsForSearch: Infinity,
        dropdownParent: $this.parent(),
        allowClear: true,
      });
    });
  };

  $(document).on("click", "#buttonDeleted", function () {
    $(this).parent().parent().remove();
  });

  $(document).on("click", "#btnAdd", function () {
    buttonAction($(this));

    reloadJS(".parentSelect");
  });

  $(document).on("change", "#upload_data", function () {
    var file = this.files[0];

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
              var headerName = cells[j].trim().replace(/\s+/g, "_");
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

        var headersFormatCsv = ["CATEGORY_NAME_(*)", "PARENT_CATEGORY_NAME"];

        var dataUpload = [];

        if (headersFormatCsv.toString() == headers.toString()) {
          $("#formatError").hide();

          dataUpload.push({ name: "_token", value: getCookie() });
          dataUpload.push({
            name: "dataUpload",
            value: JSON.stringify(jsonData),
          });
          $.ajax({
            url: base_url() + "category/upload_data",
            method: "POST",
            dataType: "JSON",
            async: false,
            data: dataUpload,
            success: function (result) {
              var getJsonData = result.data;
              var getJsonDataParent = result.category_data;

              var check_validate = [];

              for (let i = 0; i < getJsonDataParent.length; i++) {
                if (getJsonDataParent[i].category_name !== "") {
                  $("#set_id").append(
                    `<input type="hidden" name="parent_name_cat[]" value="` +
                      getJsonDataParent[i].category_name +
                      `"></input>`
                  );
                }
              }

              for (let i = 0; i < getJsonData.length; i++) {
                check_validate.push(getJsonData[i].validate);
              }

              for (let i = 0; i < getJsonData.length; i++) {
                var tr_table = `<tr>`;
                tr_table +=
                  `    <td>
                                    <button type="button" data-repeater-delete="" class="btn btn-sm btn-icon btn-light-danger" id="buttonDeleted">
                                                <span class="svg-icon svg-icon-2">
                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <rect opacity="0.5" x="7.05025" y="15.5356" width="12" height="2" rx="1" transform="rotate(-45 7.05025 15.5356)" fill="currentColor" />
                                                        <rect x="8.46447" y="7.05029" width="12" height="2" rx="1" transform="rotate(45 8.46447 7.05029)" fill="currentColor" />
                                                    </svg>
                                                </span>
                                    </button>
                                    </td>
                                    <td>` +
                  getJsonData[i].category_name +
                  `</td>
                                    <td>` +
                  getJsonData[i].parent_category +
                  `</td>
                                    <input type="hidden" name="category_name_bulk[]" value="` +
                  getJsonData[i].category_name +
                  `" />
                                    <input type="hidden" name="parent_category_bulk[]" value="` +
                  getJsonData[i].parent_category +
                  `" />
                                </tr>`;

                var tr_table2 = `<tr>`;
                tr_table2 +=
                  `   <td></td>
                                    <td>` +
                  getJsonData[i].category_name +
                  `</td>
                                    <td>` +
                  getJsonData[i].parent_category +
                  `</td>
                                </tr>`;

                if (check_validate.includes(2)) {
                  $("#kt_datatable_vertical_scroll tbody").append(tr_table2);
                  $("#saveMassUpload").hide();
                } else {
                  $("#kt_datatable_vertical_scroll tbody").append(tr_table);
                  $("#saveMassUpload").show();
                }
              }
            },
          });
        } else {
          $("#formatError").show();
        }
      };
    }
  });

  modalClose();

  $(document).on("click", ".btnEdit", function () {
    buttonAction($(this));
    $("#btn_show_mass_upload").hide();
    reloadJS(".parentSelect");
  });

  $(document).on("click", "#btn_show_mass_upload", function () {
    buttonAction($(this));
    $("#formatError").hide();
    $("#saveMassUpload").hide();
  });

  $(document).on("click", "#saveMassUpload", function () {
    Swal.fire({
      title: "Save Mass Upload Category",
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
        let btnCloseModal = "btnCloseModalMassUpload";
        let textButton = $(this).text();
        let btn = $(this);
        let url = $("#form").data("url");
        let data = $("#form").serializeArray();
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
  });

  $(document).on("click", "#btnCloseModalMassUpload", function () {
    $("#modalLarge").modal("hide");
  });
});
