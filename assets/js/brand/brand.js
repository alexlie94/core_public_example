$(document).ready(function () {
  var baseurl = base_url() + "brand/show";
  var column = [
    { data: "id" },
    { data: "brand_code" },
    { data: "brand_name" },
    { data: "description" },
    { data: "action", width: "17%" },
  ];

  ajax_crud_table(baseurl, column);

  sweetAlertConfirm();
  libraryInput();

  $("select").select2({
    minimumResultsForSearch: Infinity,
  });

  $(document).on("click", "#buttonDeleted", function () {
    $(this).parent().parent().remove();
  });

  $(document).on("click", "#btnAdd", function () {
    buttonAction($(this));
    $("#show_mass_upload").hide();

    $("#button_mass_upload").click(function () {
      $("#show_mass_upload").show();
      $("#form_brand").hide();
      $("#btnProcessModal").hide();
      $("#button_mass_upload").hide();
      $("#formatError").hide();
    });
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

        var headersFormatCsv = ["BRAND_NAME_(*)", "DESCRIPTION"];

        var dataUpload = [];

        dataUpload.push({ name: "_token", value: getCookie() });
        dataUpload.push({
          name: "dataUpload",
          value: JSON.stringify(jsonData),
        });
        $.ajax({
          url: base_url() + "brand/upload_data",
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
                  getJsonData[i].brand_name +
                  `</td>
                                                    <td>` +
                  getJsonData[i].description +
                  `</td>
                                                    <input type="hidden" name="brand_name_bulk[]" value="` +
                  getJsonData[i].brand_name +
                  `" />
                                                    <input type="hidden" name="description_bulk[]" value="` +
                  getJsonData[i].description +
                  `" />
                                                </tr>`;

                var tr_table2 = `<tr>`;
                tr_table2 +=
                  `   <td></td>
                                                    <td>` +
                  getJsonData[i].brand_name +
                  `</td>
                                                    <td>` +
                  getJsonData[i].description +
                  `</td>
                                                </tr>`;

                if (check_validate.includes(2)) {
                  $("#kt_datatable_vertical_scroll").append(tr_table2);
                  $("#btnProcessModal").hide();
                } else {
                  $("#kt_datatable_vertical_scroll").append(tr_table);
                  $("#btnProcessModal").show();
                }
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

  modalClose();
  process();

  $(document).on("click", ".btnEdit", function () {
    buttonAction($(this));
    $("#show_mass_upload").hide();
    $("#button_mass_upload").hide();
  });
});
