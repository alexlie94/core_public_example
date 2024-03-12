$(document).ready(function () {
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

  let baseurl = base_url() + "products/show";
  let column = [
    { data: "id", width: "5%" },
    { data: "sku", width: "17%" },
    { data: "product_name", width: "35%" },
    { data: "brand_name", width: "17%" },
    { data: "category_name", width: "17%" },
    {
      data: "variant_color_name",
      render: function (data) {
        return (
          `<a href="javascript:void(0)" class="symbol symbol-35px"><span class="symbol-label"
        style="background-color:#` +
          data +
          `;border-radius: 5px 5px 5px;border: 2px solid black;"></span></a>`
        );
      },
    },
    {
      data: "product_size",
      render: function (data) {
        return '<div class="badge badge-light-info">' + data + "</div>";
      },
    },
    { data: "created_at", width: "17%" },
    {
      data: "status_name",
      render: function (data) {
        switch (data) {
          case "New":
            return '<div class="badge badge-light-success">' + data + "</div>";
          case "Launching":
            return '<div class="badge badge-light-primary">' + data + "</div>";
          case "Incoming":
            return '<div class="badge badge-light-warning">' + data + "</div>";
          case "Pending":
            return '<div class="badge badge-light-danger">' + data + "</div>";
          default:
            return '<div class="badge badge-light-info">' + data + "</div>";
        }
      },
    },
    { data: "action", width: "17%" },
  ];

  ajax_crud_po(baseurl, column);

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

  processNestedFields("#btnCloseModalFullscreen");

  let getNameSubCategory =
    "select[name='kt_docs_repeater_nested_outer[0][select_sub_category]']";

  let getNameSub2Category =
    "select[name='kt_docs_repeater_nested_outer[0][select_sub2_category]']";

  let getSelectedGeneralColor =
    "select[name='kt_docs_repeater_nested_outer[0][kt_docs_repeater_nested_inner][0][generate_color]']";
  let setSelectedVariantColor =
    "select[name='kt_docs_repeater_nested_outer[0][kt_docs_repeater_nested_inner][0][variant_color]']";
  let setSelectedVariantColorName =
    "input[name='kt_docs_repeater_nested_outer[0][kt_docs_repeater_nested_inner][0][variant_color_name]']";

  $(document).on("change", "#select_category_id", function () {
    let select_sub_category = $(
      "select[name='kt_docs_repeater_nested_outer[0][select_sub_category]']"
    );

    let select_sub2_category = $(getNameSub2Category);
    let getId = $(this).val();

    select_sub_category.empty();
    select_sub2_category.empty();

    ajaxSelect2SubCategory(getId, select_sub_category);
  });

  $(document).on("change", getNameSubCategory, function () {
    let select_sub2_category = $(getNameSub2Category);
    let getId = $(this).val();
    select_sub2_category.empty();

    ajaxSelect2SubCategory(getId, select_sub2_category);
  });

  $(document).on("change", getSelectedGeneralColor, function () {
    let setAttrVariantColor = $(this)
      .parent()
      .parent()
      .parent()
      .find(setSelectedVariantColor);

    let getId = $(this).val();

    setAttrVariantColor.empty();
    $(setSelectedVariantColorName).val("");

    ajaxSelect2Color(getId, setAttrVariantColor);
  });

  $(document).on("change", setSelectedVariantColor, function () {
    var setAttributColorName = $(setSelectedVariantColorName);

    var getId = $(this).val();

    ajaxSelect2ColorHexaName(getId, setAttributColorName);
  });

  $(document).on("click", "#btnAdd ,.btnEdit", function () {
    buttonAction($(this), "#modalLarge2");

    $("#addProduct_1").on("click", function () {
      $("#add_product_parent").trigger("click");
    });

    $("#add_product_variant_1").on("click", function () {
      $("#target_variant_click_1").trigger("click");
    });

    reloadJS(".parentSelect");

    let i = 1;
    let j = 0;

    $("#kt_docs_repeater_nested").repeater({
      isFirstItemUndeletable: true,
      initEmpty: false,

      repeaters: [
        {
          selector: ".inner-repeater",
          isFirstItemUndeletable: true,
          initEmpty: false,
          show: function () {
            j++;
            $(this).slideDown();

            reloadJS(".childSubSelect");

            let getFieldSelect = $(this).find("select");
            let getFieldInput = $(this).find("input");

            let getSelectedGeneralColor = $(getFieldSelect[0]).attr("name");
            let getSelectedVariantColor = $(getFieldSelect[1]).attr("name");
            let getSelectedVariantColorName = $(getFieldInput[0]).attr("name");

            let getSelectedParent = $(this).parent().parent().find("select");
            let getInputParent = $(this).parent().parent().find("input")[0];

            let getNameGeneralColor = $(getSelectedParent[0]).attr("name");
            let getNameVariantColor = $(getSelectedParent[1]).attr("name");
            let getNameVariantColorName = $(getInputParent).attr("name");

            let setValueGeneralColor = $(
              "select[name='" + getNameGeneralColor + "']"
            );
            let setValueVariantColor = $(
              "select[name='" + getNameVariantColor + "']"
            );
            let setValueVariantColorName = $(
              "input[name='" + getNameVariantColorName + "']"
            );

            let setSelectedGeneralColor = $(
              "select[name='" + getSelectedGeneralColor + "']"
            );

            let setSelectedVariantColor = $(
              "select[name='" + getSelectedVariantColor + "']"
            );

            let setSelectedVariantColorName = $(
              "input[name='" + getSelectedVariantColorName + "']"
            );

            let setAttrVariantColor2 = $(this)
              .parent()
              .parent()
              .parent()
              .find(setSelectedVariantColor);

            ajaxSelect2Color(setValueGeneralColor.val(), setAttrVariantColor2);

            //Set Value Select Copy Parent
            // setSelectedGeneralColor.select2("val", setValueGeneralColor.val());

            setSelectedGeneralColor.val(setValueGeneralColor.val()).change();

            setSelectedVariantColor.val(setValueVariantColor.val()).change();

            $("input[name='" + getSelectedVariantColorName + "']").val(
              setValueVariantColorName.val()
            );

            //end

            setSelectedGeneralColor.on("change", function () {
              let setAttrVariantColor = $(this)
                .parent()
                .parent()
                .parent()
                .find(setSelectedVariantColor);

              let getId = $(this).val();

              setAttrVariantColor.empty();
              $(setSelectedVariantColorName).val("");

              ajaxSelect2Color(getId, setAttrVariantColor);
            });

            setSelectedVariantColor.on("change", function () {
              var setAttributColorName = $(setSelectedVariantColorName);

              var getId = $(this).val();

              ajaxSelect2ColorHexaName(getId, setAttributColorName);
            });
          },

          hide: function (deleteElement) {
            $(this).slideUp(deleteElement);
          },

          ready: function () {
            reloadJS(".childSubSelect");
          },
        },
      ],

      show: function () {
        i++;
        j++;
        $(this).slideDown();

        var id_btn_variant = "add_product_variant_" + i + "";
        var btn_variant = $(this).find("#add_product_variant_1")[0];
        btn_variant.setAttribute("id", id_btn_variant);

        var id_btn_variant2 = "target_variant_click_" + i + "";
        var btn_variant2 = $(this).find("#target_variant_click_1")[0];
        btn_variant2.setAttribute("id", id_btn_variant2);

        var change_addProduct = "addProduct_" + i + "";
        var chgId = $(this).find("#addProduct_1")[0];
        chgId.setAttribute("id", change_addProduct);

        //Adding Button Product Variant
        var get_parent_variant = $(this).find("button")[1];
        var set_parent_variant = $("#" + get_parent_variant.id);

        var get_parent_variant2 = $(this).find("button")[3];
        var set_parent_variant2 = $("#" + get_parent_variant2.id);

        $(set_parent_variant).on("click", function () {
          $(set_parent_variant2).trigger("click");
        });
        //End

        $("#addProduct_" + i).on("click", function () {
          $("#add_product_parent").trigger("click");
        });

        reloadJS(".childSelect");

        let getFieldSelectSubCategory = $(this).find("select");
        let getNameSelectSubCategory = $(getFieldSelectSubCategory[1]).attr(
          "name"
        );
        let getNameSelectSub2Category = $(getFieldSelectSubCategory[2]).attr(
          "name"
        );

        let select_sub_category = $(
          "select[name='" + getNameSelectSubCategory + "']"
        );

        let select_sub2_category = $(
          "select[name='" + getNameSelectSub2Category + "']"
        );

        select_sub_category.empty();

        ajaxSelect2SubCategory(
          $("#select_category_id").val(),
          select_sub_category
        );

        $(document).on("change", "#select_category_id", function () {
          let getId = $(this).val();

          select_sub_category.empty();
          select_sub2_category.empty();

          ajaxSelect2SubCategory(getId, select_sub_category);
        });

        // Sub Category

        select_sub2_category.empty();

        ajaxSelect2SubCategory(select_sub_category.val(), select_sub2_category);

        select_sub_category.on("change", function () {
          let getId = $(this).val();

          select_sub2_category.empty();

          ajaxSelect2SubCategory(getId, select_sub2_category);
        });
        // end sub category

        let getFieldSelect = $(this).find("select");
        let getFieldInput = $(this).find("input");

        let getSelectedGeneralColor = $(getFieldSelect[3]).attr("name");
        let getSelectedVariantColor = $(getFieldSelect[4]).attr("name");
        let getSelectedVariantColorName = $(getFieldInput[2]).attr("name");

        let setSelectedGeneralColor = $(
          "select[name='" + getSelectedGeneralColor + "']"
        );

        let setSelectedVariantColor = $(
          "select[name='" + getSelectedVariantColor + "']"
        );

        let setSelectedVariantColorName = $(
          "input[name='" + getSelectedVariantColorName + "']"
        );

        setSelectedGeneralColor.on("change", function () {
          let setAttrVariantColor = $(this)
            .parent()
            .parent()
            .parent()
            .find(setSelectedVariantColor);

          let getId = $(this).val();

          setAttrVariantColor.empty();
          $(setSelectedVariantColorName).val("");

          ajaxSelect2Color(getId, setAttrVariantColor);
        });

        setSelectedVariantColor.on("change", function () {
          var setAttributColorName = $(setSelectedVariantColorName);

          var getId = $(this).val();

          ajaxSelect2ColorHexaName(getId, setAttributColorName);
        });
      },

      hide: function (deleteElement) {
        $(this).slideUp(deleteElement);
      },

      ready: function () {
        reloadJS(".childSelect");
      },
    });
  });

  $(document).on("click", "#btn_show_mass_upload", function () {
    buttonAction($(this), "#modalLarge2");
    $("#formatError").hide();

    $("#btnCloseModalMassUpload").after(
      '<button class="btn btn-info btn-rounded ml-2" type="button" id="btnNext">Next</button>'
    );

    $("#saveMassUpload").remove();

    let btnCloseModal = "btnCloseModalMassUpload";
    $(document).on("click", "#saveMassUpload", function (e) {
      let cektable = $("#kt_datatable_vertical_scroll tbody tr").length;

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
            var target = $(".modal-upload")
              .parent()
              .parent()
              .parent(".modal-content")[0];
            console.log(target);
            var blockUI = KTBlockUI.getInstance(target);
            e.preventDefault();
            blockUI.block();
            var textButton = $(this).text();
            var btn = $(this);
            var url = base_url() + "products/process";
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
                    $("#saveMassUpload").remove();
                    $("#btnCloseModalMassUpload").after(
                      '<button class="btn btn-info btn-rounded ml-2" type="button" id="btnNext">Next</button>'
                    );
                    $(
                      "td div.fv-plugins-message-container.invalid-feedback"
                    ).remove();

                    $("#kt_datatable_vertical_scroll tbody tr").each(
                      function () {
                        $(this).find("td:last").removeClass("td-success");
                        $(this).find("td:last").removeClass("td-error");
                      }
                    );
                    var duplicate = [];
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

                      var explode = value.split("|");
                      if (explode.length == 2) {
                        $(element).after(explode[0]);
                        duplicate.push(
                          explode[1]
                            .replace(/\s+/g, "_")
                            .replace("-", "")
                            .replace("-", "")
                            .replace("</div>", "")
                        );
                        let lastTd = $(element)
                          .parent()
                          .parent()
                          .find("td:last");
                        $(lastTd).addClass("td-error");
                      }

                      if (explode.length == 1) {
                        $(element).after(value);

                        if (value) {
                          let lastTd = $(element)
                            .parent()
                            .parent()
                            .find("td:last");
                          $(lastTd).addClass("td-error");
                        }

                        if (!value) {
                          let lastTd = $(element)
                            .parent()
                            .parent()
                            .find("td:last");
                          $(lastTd).addClass("td-success");
                        }
                      }
                    });

                    $(".td-success").html(iconSuccess);

                    $(".td-error").html(iconError);

                    let duplicateConvert = duplicate.filter(onlyUnique);

                    for (var ix = 0; ix < duplicateConvert.length; ix++) {
                      var itemx = duplicateConvert[ix];
                      var checkInput = $('input[data-check="' + itemx + '"]');
                      if (checkInput.length > 1) {
                        $(checkInput).addClass("check-error");
                      }
                    }

                    if (duplicateConvert.length > 0) {
                      $(".check-error").next().remove();
                      setTimeout(() => {
                        addError();
                      }, 100);
                    }
                  }
                } else {
                  if (response.type == "insert") {
                    if (typeof response.data != "undefined") {
                      addDataOption(response.data);
                    }
                    reset_input();
                    $("#modalLarge2").modal("hide");
                  }
                  addDraw();
                  $("#table-data").DataTable().ajax.reload();
                }
                loadingButtonOff(btn, textButton);
                enabledButton($(btnCloseModal));

                setTimeout(function () {
                  blockUI.release();
                }, 1000);
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
  });

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
    var fileName = input_file_id.toLowerCase();

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

    const btnProcessModal = $("#btnProcessModal");
    const dataTable = $("#kt_datatable_vertical_scroll tbody");
    const formatError = $("#formatError");
    const url = base_url() + "products/upload_data";

    btnProcessModal.remove();
    dataTable.html("");

    let file = $("#data_upload")[0].files[0];

    const headersFormatCsv = [
      "BRAND_NAME_(*)",
      "SUPPLIER_NAME_(*)",
      "CATEGORY_NAME_(*)",
      "PRODUCT_NAME_(*)",
      "GENDER_(*)",
      "SUB_CATEGORY",
      "SUB_SUB_CATEGORY",
      "PRICE",
      "GENERAL_COLOR_(*)",
      "VARIANT_COLOR",
      "SIZE_(*)",
    ];

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
              var checkExit = "";
              var checkExitTr = "";
              for (let i = 0; i < getJsonData.length; i++) {
                let makeClass =
                  getJsonData[i].product_name +
                  getJsonData[i].general_color +
                  getJsonData[i].size;
                let convert = makeClass.replace(/\s+/g, "_");

                if (getJsonData[i].validate[10] == 4) {
                  checkExit = "item-exist";
                  checkExitTr = "table_detail item-exist";
                } else {
                  checkExit = "";
                  checkExitTr = "table_detail";
                }
                let tr_table = ` <tr class="` + checkExitTr + `">`;

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
                    <td style='text-align: center;vertical-align: middle;'> ` +
                  no++ +
                  `</td>
                    <td>
                    <input type="text" class="form-control mb-3 mb-lg-0" data-type="input" name="get_brand_name[]"
                            value="` +
                  getJsonData[i].brand_name +
                  `" />` +
                  setMessage("Brand", getJsonData[i].validate[0]) +
                  `</td>
                    <td>
                      <input type="text" class="form-control mb-3 mb-lg-0" data-type="input" name="get_supplier_name[]"
                          value="` +
                  getJsonData[i].supplier_name +
                  `" />` +
                  setMessage("Supplier", getJsonData[i].validate[1]) +
                  ` </td>
                    <td>
                        <input type="text" class="form-control mb-3 mb-lg-0" data-type="input" name="get_category_name[]"
                            value="` +
                  getJsonData[i].category_name +
                  `" />` +
                  setMessage("Category", getJsonData[i].validate[2]) +
                  ` </td>
                    <td> <input type="text" class="form-control mb-3 mb-lg-0" data-type="input" name="get_product_name[]"
                            value="` +
                  getJsonData[i].product_name +
                  `" onkeyup="checkDuplicate(this);" />` +
                  setMessage("Product", getJsonData[i].validate[3]) +
                  `  </td>
                    <td> <input type="text" class="form-control mb-3 mb-lg-0" data-type="input" name="get_gender[]"
                            value="` +
                  getJsonData[i].gender +
                  `" /> ` +
                  setMessage("Gender", getJsonData[i].validate[4]) +
                  ` </td>
                    <td> <input type="text" class="form-control mb-3 mb-lg-0" data-type="input" name="get_sub_category_name[]"
                            value="` +
                  getJsonData[i].sub_category_name +
                  `" /> ` +
                  setMessage("Sub Category", getJsonData[i].validate[5]) +
                  ` </td>
                    <td> <input type="text" class="form-control mb-3 mb-lg-0" data-type="input" name="get_sub_sub_category_name[]"
                            value="` +
                  getJsonData[i].sub_sub_category_name +
                  `" /> ` +
                  setMessage("Sub Sub Category", getJsonData[i].validate[6]) +
                  ` </td>
                    <td> <input type="text" onkeyup="formatCurrency(this)" class="form-control mb-lg-0" data-type="input"
                            name="get_price[]"
                            value="` +
                  format_number_no_idr(getJsonData[i].price) +
                  `" />` +
                  setMessage("Price", getJsonData[i].validate[7]) +
                  ` </td>
                    <td> <input type="text" class="form-control mb-3 mb-lg-0" data-type="input" name="get_general_color[]"
                            value="` +
                  getJsonData[i].general_color +
                  `" onkeyup="checkDuplicate(this);"/>` +
                  setMessage("General Color", getJsonData[i].validate[8]) +
                  `  </td>
                    <td> <input type="text" class="form-control mb-3 mb-lg-0" data-type="input" name="get_variant_color[]"
                            value="` +
                  getJsonData[i].variant_color +
                  `" /> ` +
                  setMessage("Variant Color", getJsonData[i].validate[9]) +
                  ` </td>
                    <td>
                    <input type="text" class="form-control mb-3 mb-lg-0 ` +
                  checkExit +
                  `" data-type="input" id="tes_` +
                  no +
                  `" data-check="` +
                  convert +
                  `" onkeyup="checkDuplicate(this);" name="get_size[]"
                            value="` +
                  getJsonData[i].size +
                  `" /> ` +
                  setMessage("Size", getJsonData[i].validate[10]) +
                  `</td>`;

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
                $("#btnProcessModal").show();
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
        }
      };

      reader.readAsBinaryString(file);
    }
  });

  $(document).on("click", "#btnNext", function (e) {
    let cektable = $("#kt_datatable_vertical_scroll tbody tr").length;

    if (cektable < 1) {
      toastr.warning("File Mass Upload Empty", "", {
        progressBar: !0,
        timeOut: 2000,
      });
    } else {
      var target = $(".modal-upload")
        .parent()
        .parent()
        .parent(".modal-content")[0];

      var blockUI = KTBlockUI.getInstance(target);
      e.preventDefault();
      blockUI.block();

      var url = $("#form").data("url");
      var data = $("#form").serializeArray(); // convert form to array
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

              var duplicate = [];
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

                var explode = value.split("|");
                if (explode.length == 2) {
                  $(element).after(explode[0]);
                  duplicate.push(
                    explode[1]
                      .replace(/\s+/g, "_")
                      .replace("-", "")
                      .replace("-", "")
                      .replace("</div>", "")
                  );
                  let lastTd = $(element).parent().parent().find("td:last");
                  $(lastTd).addClass("td-error");
                }

                if (explode.length == 1) {
                  $(element).after(value);

                  if (value) {
                    let lastTd = $(element).parent().parent().find("td:last");
                    $(lastTd).addClass("td-error");
                  }

                  if (!value) {
                    let lastTd = $(element).parent().parent().find("td:last");
                    $(lastTd).addClass("td-success");
                  }
                }
              });

              $(".td-success").html(iconSuccess);

              $(".td-error").html(iconError);

              let duplicateConvert = duplicate.filter(onlyUnique);

              for (var ix = 0; ix < duplicateConvert.length; ix++) {
                var itemx = duplicateConvert[ix];
                var checkInput = $('input[data-check="' + itemx + '"]');
                if (checkInput.length > 1) {
                  $(checkInput).addClass("check-error");
                }
              }

              if (duplicateConvert.length > 0) {
                $(".check-error").next().remove();
                setTimeout(() => {
                  addError();
                }, 100);
              }
            }
          } else {
            $("#kt_datatable_vertical_scroll tbody tr").each(function () {
              $(this).find("td:last").removeClass("td-error");
              $(this).find("td:last").addClass("td-success");
            });

            $(".invalid-feedback").remove();

            $("#btnNext").remove();

            if ($(".saveMassUpload").length < 1) {
              $("#btnCloseModalMassUpload").after(
                '<button class="btn btn-primary btn-rounded ml-2 saveMassUpload" type="button" id="saveMassUpload">Save Changes</button>'
              );
            }
            $(".td-success").html(iconSuccess);
          }
          setTimeout(function () {
            blockUI.release();
          }, 1000);
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

  function setMessage(name, index) {
    switch (index) {
      case 6:
        return (
          '<div style="margin-top: -2px;margin-bottom: -29px;" class="fv-plugins-message-container invalid-feedback">' +
          name +
          "Double Entry</div>"
        );
        break;
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

  $(document).on("click", "#btnCloseModalFullscreen", function () {
    $("#modalLarge2").modal("hide");
  });

  $(document).on("click", ".btnPrint", function () {
    buttonAction($(this), "#modalLarge2");

    let param = $(this).attr("param");
    $("#get_param").val(param);
    $("#print_qty").val(1);

    $(document).on("click", "#print", function () {
      let getQTY = $("#print_qty").val();
      let getParam = $("#get_param").val();
      var newparam = getParam + "|" + getQTY;

      window.open(
        base_url() +
          "barcode/products/printlabel?action=sku&param=" +
          newparam +
          ""
      );
    });
  });

  $(document).on("click", "#btnCloseModal", function () {
    $("#modalLarge2").modal("hide");
  });

  $(document).on("click", "#buttonDeleted", function () {
    $(this).parent().parent().remove();
  });

  function ajax_crud_po(base_url, column, tableID = "table-data") {
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
      searching: false,
      processing: true,
      oLanguage: {
        sProcessing: "loading...",
      },
      serverSide: true,
      responsive: false,
      scrollX: true,
      orderable: false,
      lengthChange: false,
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
          var data =
            $("#formSearch").length > 0
              ? $("#formSearch").serializeArray()
              : [];

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
        var info = this.fnPagingInfo();
        var page = info.iPage;
        var length = info.iLength;
        var index = page * length + (iDisplayIndex + 1);
        $("td:eq(0)", row).html(index);

        if (typeof data.status != "undefined" && data.status !== null) {
          var text = "";
          var color = "";
          switch (data.status) {
            case "enable":
              text = "Disabled";
              color = "btn-outline-warning";
              break;
            case "disable":
              text = "Enabled";
              color = "btn-outline-info";
              break;
            case "print":
              $("td:last", row).html(data.print);
              break;
          }

          if (data.status == "enable" || data.status == "disable") {
            var newAction = data.action.replace("Disabled", text);
            var newColorAction = newAction.replace("btn-outline-info", color);

            $("td:last", row).html(newColorAction);
          }
        }

        if (data.action == "") {
          $("td:last", row).remove();
        }
      },
    });
  }

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

  const ajaxSelect2SubCategory = (value, getAttributName) => {
    let sendData = [];
    sendData.push({ name: "parent_category_id", value: value });
    sendData.push({ name: "_token", value: getCookie() });

    $.ajax({
      url: base_url() + "products/sub_category",
      method: "POST",
      dataType: "JSON",
      async: false,
      data: sendData,
      success: function (result) {
        let getDataJSon = result.data;

        getAttributName.append("<option value=''></option>");

        for (let i = 0; i < getDataJSon.length; i++) {
          getAttributName.append(
            "<option value=" +
              getDataJSon[i].id +
              ">" +
              getDataJSon[i].categories_name +
              "</option>"
          );
        }
      },
    });
  };
  const ajaxSelect2Color = (value, getAttributName) => {
    let sendData = [];
    sendData.push({ name: "general_color_id", value: value });
    sendData.push({ name: "_token", value: getCookie() });

    $.ajax({
      url: base_url() + "products/variant_color",
      method: "POST",
      dataType: "JSON",
      async: false,
      data: sendData,
      success: function (result) {
        let getDataJSon = result.data;

        getAttributName.append("<option value=''></option>");

        for (let i = 0; i < getDataJSon.length; i++) {
          getAttributName.append(
            "<option value=" +
              getDataJSon[i].id +
              ">" +
              getDataJSon[i].color_name +
              "</option>"
          );
        }
      },
    });
  };
  const ajaxSelect2ColorHexaName = (value, getAttributName) => {
    let sendData = [];
    sendData.push({ name: "variant_color_id", value: value });
    sendData.push({ name: "_token", value: getCookie() });

    $.ajax({
      url: base_url() + "products/variant_color_name",
      method: "POST",
      dataType: "JSON",
      async: false,
      data: sendData,
      success: function (result) {
        var getDataJSon = result.data;

        for (let i = 0; i < getDataJSon.length; i++) {
          let dataGet = getDataJSon[i].color_hexa;
          getAttributName.val(
            dataGet.replace(/\s/g, "") === "" ? "FFFFFF" : dataGet
          );
        }
      },
    });
  };

  $(document).on("click", "#btnCloseModalMassUpload", function () {
    $("#modalLarge2").modal("hide");
  });

  $(document).on("click", ".downloadView", function () {
    let selectField = $(this).parent().parent().parent().find("select");
    let inputField = $(this).parent().parent().parent().find("input");
    let statusVal = [];
    let status = $('input[id="lookup_status"]').filter(":checked");
    let startDate = $('input[id="start_date"]');
    let endDate = $('input[id="end_date"]');

    for (let i = 0; i < status.length; i++) {
      //
      statusVal.push($(status[i]).siblings("label").text().trim());
      statusVal.push($(status[i]).val());
    }
    window.location.href =
      base_url() +
      "products/downloadView" +
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
});

function checkDuplicate(e) {
  validate_upload(e);
  // 	var target = $(".modal-upload")
  // 	.parent()
  // 	.parent()
  // 	.parent(".modal-content")[0];
  // console.log(target);
  // var blockUI = KTBlockUI.getInstance(target);
  // e.preventDefault();
  // blockUI.block();
  // var size = $(e).parent().parent().find("input")[10];
  // var product = $(e).parent().parent().find("input")[3];
  // var General = $(e).parent().parent().find("input")[8];

  // var newtd = $(e).parent().parent().find("input")[10];

  // let makeClass = $(product).val() + $(General).val() + $(size).val();
  // let convert = makeClass.replace(/\s+/g, "_");
  // $(newtd).attr("data-check", convert);
  // $(newtd).removeClass("item-exist");
  // let lastTd = $(size).parent().next();

  // $(lastTd).removeClass("td-error");
  // $(lastTd).addClass("td-success");

  // $(".td-success").html(`<span class="svg-icon svg-icon-2x">
  //                               <svg xmlns="http://www.w3.org/2000/svg" style="margin-top: 9px;" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
  //                                   <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
  //                                       <polygon points="0 0 24 0 24 24 0 24"/>
  //                                       <path d="M9.26193932,16.6476484 C8.90425297,17.0684559 8.27315905,17.1196257 7.85235158,16.7619393 C7.43154411,16.404253 7.38037434,15.773159 7.73806068,15.3523516 L16.2380607,5.35235158 C16.6013618,4.92493855 17.2451015,4.87991302 17.6643638,5.25259068 L22.1643638,9.25259068 C22.5771466,9.6195087 22.6143273,10.2515811 22.2474093,10.6643638 C21.8804913,11.0771466 21.2484189,11.1143273 20.8356362,10.7474093 L17.0997854,7.42665306 L9.26193932,16.6476484 Z" fill="#008000" fill-rule="nonzero" opacity="0.3" transform="translate(14.999995, 11.000002) rotate(-180.000000) translate(-14.999995, -11.000002) "/>
  //                                       <path d="M4.26193932,17.6476484 C3.90425297,18.0684559 3.27315905,18.1196257 2.85235158,17.7619393 C2.43154411,17.404253 2.38037434,16.773159 2.73806068,16.3523516 L11.2380607,6.35235158 C11.6013618,5.92493855 12.2451015,5.87991302 12.6643638,6.25259068 L17.1643638,10.2525907 C17.5771466,10.6195087 17.6143273,11.2515811 17.2474093,11.6643638 C16.8804913,12.0771466 16.2484189,12.1143273 15.8356362,11.7474093 L12.0997854,8.42665306 L4.26193932,17.6476484 Z" fill="#008000" fill-rule="nonzero" transform="translate(9.999995, 12.000002) rotate(-180.000000) translate(-9.999995, -12.000002) "/>
  //                                   </g>
  //                               </svg>
  //                             </span>`);

  // setTimeout(() => {
  //   checkTable();
  // }, 100);
}

function addError() {
  $(".check-error").after(
    `<div style="margin-top: -2px;margin-bottom: -29px;" class="fv-plugins-message-container invalid-feedback">Size Duplicate</div>`
  );

  let lastTd = $(".check-error").parent().next();

  $(lastTd).removeClass("td-success");
  $(lastTd).addClass("td-error");

  $(".td-error").html(`<span class="svg-icon svg-icon-2x">
                                  <svg width="24" height="24" viewBox="0 0 24 24" fill="none" style="margin-top: 9px;"
                                      xmlns="http://www.w3.org/2000/svg">
                                      <rect opacity="0.5" x="7.05025" y="15.5356" width="12" height="2"
                                          rx="1" transform="rotate(-45 7.05025 15.5356)" fill="#ff0000" />
                                      <rect x="8.46447" y="7.05029" width="12" height="2" rx="1"
                                          transform="rotate(45 8.46447 7.05029)" fill="#ff0000" />
                                  </svg>
                              </span>`);
}

function checkTable() {
  var className = [];

  $("table tr.table_detail").each(function () {
    var inputCheck = $(this).find("input")[10];
    $(".check-error").next().remove();
    $(inputCheck).removeClass("check-error");
    className.push($(inputCheck).attr("data-check"));

    let lastTd = $(inputCheck).parent().next();

    if ($(inputCheck).hasClass("item-exist")) {
      $(lastTd).removeClass("td-success");
      $(lastTd).addClass("td-error");
      $(".td-error").html(`<span class="svg-icon svg-icon-2x">
															<svg width="24" height="24" viewBox="0 0 24 24" fill="none" style="margin-top: 9px;"
																	xmlns="http://www.w3.org/2000/svg">
																	<rect opacity="0.5" x="7.05025" y="15.5356" width="12" height="2"
																			rx="1" transform="rotate(-45 7.05025 15.5356)" fill="#ff0000" />
																	<rect x="8.46447" y="7.05029" width="12" height="2" rx="1"
																			transform="rotate(45 8.46447 7.05029)" fill="#ff0000" />
															</svg>
												</span>`);
    } else {
      $(lastTd).removeClass("td-error");
      $(lastTd).addClass("td-success");

      $(".td-success").html(`<span class="svg-icon svg-icon-2x">
                                <svg xmlns="http://www.w3.org/2000/svg" style="margin-top: 9px;" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <polygon points="0 0 24 0 24 24 0 24"/>
                                        <path d="M9.26193932,16.6476484 C8.90425297,17.0684559 8.27315905,17.1196257 7.85235158,16.7619393 C7.43154411,16.404253 7.38037434,15.773159 7.73806068,15.3523516 L16.2380607,5.35235158 C16.6013618,4.92493855 17.2451015,4.87991302 17.6643638,5.25259068 L22.1643638,9.25259068 C22.5771466,9.6195087 22.6143273,10.2515811 22.2474093,10.6643638 C21.8804913,11.0771466 21.2484189,11.1143273 20.8356362,10.7474093 L17.0997854,7.42665306 L9.26193932,16.6476484 Z" fill="#008000" fill-rule="nonzero" opacity="0.3" transform="translate(14.999995, 11.000002) rotate(-180.000000) translate(-14.999995, -11.000002) "/>
                                        <path d="M4.26193932,17.6476484 C3.90425297,18.0684559 3.27315905,18.1196257 2.85235158,17.7619393 C2.43154411,17.404253 2.38037434,16.773159 2.73806068,16.3523516 L11.2380607,6.35235158 C11.6013618,5.92493855 12.2451015,5.87991302 12.6643638,6.25259068 L17.1643638,10.2525907 C17.5771466,10.6195087 17.6143273,11.2515811 17.2474093,11.6643638 C16.8804913,12.0771466 16.2484189,12.1143273 15.8356362,11.7474093 L12.0997854,8.42665306 L4.26193932,17.6476484 Z" fill="#008000" fill-rule="nonzero" transform="translate(9.999995, 12.000002) rotate(-180.000000) translate(-9.999995, -12.000002) "/>
                                    </g>
                                </svg>
                              </span>`);
    }
  });

  var seen = {};

  var duplicates = [];

  for (var i = 0; i < className.length; i++) {
    var item = className[i];

    if (seen[item]) {
      duplicates.push(item);
    } else {
      seen[item] = true;
    }
  }

  let duplicateConvert = duplicates.filter(onlyUnique);

  for (var ix = 0; ix < duplicateConvert.length; ix++) {
    var itemx = duplicateConvert[ix];
    var checkInput = $('input[data-check="' + itemx + '"]');
    // if ($(inputCheck).hasClass("item-exist")) {
    // console.log();
    if (!$(checkInput).hasClass("item-exist")) {
      if (checkInput.length > 1) {
        $(checkInput).addClass("check-error");
      }
    }
  }

  setTimeout(() => {
    addError();
  }, 100);
}

function onlyUnique(value, index, array) {
  return array.indexOf(value) === index;
}

function validate_upload(e) {
  console.log($(e).attr("id"));
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

  let cektable = $("#kt_datatable_vertical_scroll tbody tr").length;

  if (cektable < 1) {
    toastr.warning("File Mass Upload Empty", "", {
      progressBar: !0,
      timeOut: 2000,
    });
  } else {
    // var target = $(".modal-upload")
    //   .parent()
    //   .parent()
    //   .parent(".modal-content")[0];
    // var blockUI = KTBlockUI.getInstance(target);
    // blockUI.block();

    var url = $("#form").data("url");
    var data = $("#form").serializeArray(); // convert form to array
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
            $("td div.fv-plugins-message-container.invalid-feedback").remove();

            $("#kt_datatable_vertical_scroll tbody tr").each(function () {
              $(this).find("td:last").removeClass("td-success");
              $(this).find("td:last").removeClass("td-error");
            });

            var duplicate = [];
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

              var explode = value.split("|");
              if (explode.length == 2) {
                $(element).after(explode[0]);
                duplicate.push(
                  explode[1]
                    .replace(/\s+/g, "_")
                    .replace("-", "")
                    .replace("-", "")
                    .replace("</div>", "")
                );
                let lastTd = $(element).parent().parent().find("td:last");
                $(lastTd).addClass("td-error");
              }
              // console.log(element);
              $(element).next().remove();
              $(element).after(value);
              if (explode.length == 1) {
                if (value) {
                  let lastTd = $(element).parent().parent().find("td:last");
                  $(lastTd).addClass("td-error");
                }

                if (!value) {
                  let lastTd = $(element).parent().parent().find("td:last");
                  $(lastTd).addClass("td-success");
                }
              }
            });

            $(".td-success").html(iconSuccess);

            $(".td-error").html(iconError);

            let duplicateConvert = duplicate.filter(onlyUnique);

            for (var ix = 0; ix < duplicateConvert.length; ix++) {
              var itemx = duplicateConvert[ix];
              var checkInput = $('input[data-check="' + itemx + '"]');
              if (checkInput.length > 1) {
                $(checkInput).addClass("check-error");
              }
            }

            if (duplicateConvert.length > 0) {
              $(".check-error").next().remove();
              setTimeout(() => {
                addError();
              }, 100);
            }
          }
        } else {
          $("#kt_datatable_vertical_scroll tbody tr").each(function () {
            $(this).find("td:last").removeClass("td-error");
            $(this).find("td:last").addClass("td-success");
          });

          $(".invalid-feedback").remove();

          $("#btnNext").remove();

          if ($(".saveMassUpload").length < 1) {
            $("#btnCloseModalMassUpload").after(
              '<button class="btn btn-primary btn-rounded ml-2 saveMassUpload" type="button" id="saveMassUpload">Save Changes</button>'
            );
          }
          $(".td-success").html(iconSuccess);
        }
        // setTimeout(function () {
        //   blockUI.release();
        // }, 1000);
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
}

$(document).on("click", "#download_product", function () {
  window.location.href = base_url() + "products/downloadCsv";
});
