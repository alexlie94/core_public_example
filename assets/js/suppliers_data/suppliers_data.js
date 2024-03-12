$(document).ready(function () {
  var baseurl = base_url() + "suppliers_data/show";
  var column = [
    { data: "id" },
    { data: "supplier_code" },
    { data: "supplier_name" },
    { data: "email" },
    { data: "address" },
    { data: "phone" },
    { data: "action", width: "17%" },
  ];

  ajax_crud_table(baseurl, column);

  sweetAlertConfirm();
  libraryInput();
  $(document).on("click", ".btnEdit", function () {
    buttonAction($(this));

    var phone_key = $('#phone_key').val();
    $('#phone').val(phone_key.split('/')[0]);
    $('#phone2').val(phone_key.split('/')[1]);

    $("#show_mass_upload").hide();
    $("#button_mass_upload").hide();
    $(".card-toolbar").hide();

    $("#kt_docs_repeater_advanced").repeater({
      isFirstItemUndeletable: true,
      initEmpty: false,
      show: function () {
        $(this).slideDown();
        $(this).find(".select2-container").remove();
        var select_brand = $(this).find("select")[0];
        var select_type = $(this).find("select")[1];
        var $select = $("#" + select_brand.id);
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
        var $select = $("#select_brand_id_0");
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

        var $select2 = $("#select_type_ownership_0");
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

  $(document).on("click", "#btnAdd", function () {
    buttonAction($(this));

    $("#show_mass_upload").hide();

    $("#button_mass_upload").click(function () {
      $("#show_mass_upload").show();
      $("#form_supplier").hide();
      $("#btnProcessModal").hide();
      $("#button_mass_upload").hide();
      $("#formatError").hide();
    });

    $("#kt_docs_repeater_advanced").repeater({
      isFirstItemUndeletable: true,
      initEmpty: false,
      show: function () {
        $(this).slideDown();
        $(this).find(".select2-container").remove();
        var select_brand = $(this).find("select")[0];
        var select_type = $(this).find("select")[1];
        var $select = $("#" + select_brand.id);
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
        var $select = $("#select_brand_id_0");
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

        var $select2 = $("#select_type_ownership_0");
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

  $(document).on("change", "#upload_data", function () {
    $("#btnProcessModal").hide();

    $("#kt_datatable_vertical_scroll tbody").remove();

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
  
          const headersFormatCsv = [
            "SUPPLIER_NAME(*)",
            "EMAIL(*)",
            "ADDRESS",
            "PHONE_1(*)",
            "PHONE_2",
            "BRAND_NAME(*)",
            "TYPE_OWNERSHIP_NAME",
          ];

          var no =1;
          var no2 =1;
  
          let dataUpload = [];
  
          if (headersFormatCsv.toString() == headerSplit.toString()) {
              dataUpload.push({ name: "_token", value: getCookie() });
              dataUpload.push({
                  name: "dataUpload",
                  value: JSON.stringify(output),
              });
              $.ajax({
                  url: base_url() + "suppliers_data/upload_data",
                  method: "POST",
                  dataType: "JSON",
                  async: false,
                  data: dataUpload,
                  success: function (result) {
                      let getJsonData = result.data;
      
                      $("#formatError").hide();
                      let check_validate = [];
                      for (let i = 0; i < getJsonData.length; i++) {
                          check_validate.push(getJsonData[i].validate);
                      }
                      for (let i = 0; i < getJsonData.length; i++) {
                        var tr_table =`<tr>`;
                        tr_table +=`
                          <td>`+no+`</td>
                          <td> <input type="hidden" name="supplier_name_1[]" value="`+getJsonData[i].supplier_name+`">`+getJsonData[i].supplier_name+`</td>
                          <td> <input type="hidden" name="email_1[]" value="`+getJsonData[i].email+`">`+getJsonData[i].email+`</td>
                          <td> <input type="hidden" name="address_1[]" value="`+getJsonData[i].address+`">`+getJsonData[i].address+`</td>
                          <td> <input type="hidden" name="phone_1_1[]" value="`+getJsonData[i].phone_1+`">`+getJsonData[i].phone_1+`</td>
                          <td> <input type="hidden" name="phone_2_1[]" value="`+getJsonData[i].phone_2+`">`+getJsonData[i].phone_2+`</td>
                          <td> <input type="hidden" name="brand_name_1[]" value="`+getJsonData[i].brand_name+`">`+getJsonData[i].brand_name+`</td>
                          <td> <input type="hidden" name="type_ownership_name_1[]" value="`+getJsonData[i].type_ownership_name+`">`+getJsonData[i].type_ownership_name+`</td>
                        </tr>`;
        
                        var tr_table2 =`<tr>`;
                        tr_table2 +=`
                          <td>`+no2+`</td>
                          <td>` +getJsonData[i].supplier_name+`</td>
                          <td>` +getJsonData[i].email+`</td>
                          <td>` +getJsonData[i].address+`</td>
                          <td>` +getJsonData[i].phone_1+`</td>
                          <td>` +getJsonData[i].phone_2+`</td>
                          <td>` +getJsonData[i].brand_name+`</td>
                          <td>` +getJsonData[i].type_ownership_name+`</td>
                        </tr>`;
        
                        if(check_validate.includes(2)){
                          $("#kt_datatable_vertical_scroll").append(tr_table2);
                          $('#btnProcessModal').hide();
                        }else{
                          $("#kt_datatable_vertical_scroll").append(tr_table);
                          $('#btnProcessModal').show();
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

  modalClose();
  processNestedFieldsCustom();
});
