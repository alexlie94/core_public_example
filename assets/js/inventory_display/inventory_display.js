$(document).ready(function () {
  localStorage.removeItem("category");
  localStorage.removeItem("shipping_list");

  var baseurl = base_url() + "inventory_display/show";

  var column = [
    { data: "id" },
    { data: "product_id" },
    { data: "product_name" },
    { data: "product_price" },
    { data: "product_sale_price" },
    { data: "product_size" },
    { data: "brand_name" },
    {
      data: "status_name",
      render: function (data, type, row) {
        return "<span class='" + vlookup[data] + "'>" + data + "</span>";
      },
    },
    { data: "action", width: "17%" },
  ];

  ajax_crud_table(baseurl, column, "table-data", "inventory_display");

  exportData();

  $(document).on("change", "#searchBy", function () {
    var search = $(this).val();
    switchSearch(search);
  });

  function switchSearch(search) {
    var html = search != "" ? searchInput[search] : "";
    $("#placeSearch").html(html);
    $(".dateRange").daterangepicker({
      timePicker: true,
      timePicker24Hour: true,
      timePickerSeconds: true,
      locale: {
        format: "YYYY-MM-DD HH:mm:ss",
      },
    });
  }

  $(document).on("change", "#source", function () {
    var source = $(this).val();
    var data = [];
    data.push({ name: "source_id", value: source });
    loadingPage();
    requestUrlNotLoadingButton(data, $(this).data("url"), function (response) {
      CloseLoadingPage();
      $("#channel").find("option").remove();
      if (typeof response.data != "undefined") {
        for (let i = 0; i < response.data.length; i++) {
          $("#channel").append(
            "<option value='" +
              response.data[i].id +
              "'>" +
              response.data[i].channel_name +
              "</option>"
          );
        }
      }
    });
  });

  $(document).on("click", ".btnLaunched", function () {
    var url = $(this).data("url");
    var status = $(this).data("status");
    if (status != 3) {
      sweetAlertMessage("Status Product must be Incoming");
    } else {
      window.location.href = url;
    }
  });

  $(document).on("click", "#btnSearchResetInventory", function () {
    $("#searchBy").val("productid").trigger("change");
    $("#source").val("").trigger("change");
    reloadDatatables();
  });

  $(document).on("click", "#btnMassUpload", function () {
    buttonAction($(this));
  });

  $(document).on("click", "#btnCloseModalUpload", function () {
    modalAutoClose($(this));
  });

  $(document).on("click", "#btnCloseModalPreview", function () {
    modalAutoClose($(this));
  });

  $(document).on("click", "#btnDownloadTemplate", function () {
    var url = $(this).data("url");
    window.location.assign(url);
  });

  sweetAlertConfirmDeleteHTML();

  function errorValidation(validation, validationIcon) {
    $.each(validation, function (key, value) {
      var type = value.type;
      var name = value.name;
      var sequence = value.sequence;
      var message = value.message;
      //var icon = value.icon;

      let element = $(type + '[name="' + name + '[]"]')[sequence];

      switch (type) {
        case "input":
          $(element).next(".invalid-feedback").remove();
          $(element).after(message);
          $(element)
            .parent()
            .parent()
            .find(".icontd")
            .html(validationIcon[sequence].icon);
          if (typeof value.productName != "undefined") {
            $(element)
              .parent()
              .parent()
              .find(".productName")
              .attr("value", value.productName);
          }
          break;

        case "select":
          $(element).next().next(".invalid-feedback").remove();
          $(element).next().after(message);
          $(element)
            .parent()
            .parent()
            .find(".icontd")
            .html(validationIcon[sequence].icon);
          break;
      }
    });
  }

  $(document).on("click", "#btnProcessUploadModal", function () {
    var btn = $(this);
    var url = btn.attr("data-url");
    var data = $("#formUpload").serializeArray();
    var btnCloseModal = $("#btnCloseModalPreview");
    var textButton = btn.text();

    disabledButton(btnCloseModal);

    requestUrl(data, btn, url, function (response) {
      enabledButton(btnCloseModal);
      loadingButtonOff(btn, textButton);

      var buttonName = response.buttonName;
      var buttonUrl = response.buttonUrl;

      $(btn).text(buttonName);
      $(btn).attr("data-url", buttonUrl);

      if (typeof response.validation == "object") {
        errorValidation(response.validation, response.validationIcon);
        if (response.success) {
          if (typeof response.showModal != "undefined") {
            sweetAlertMessageWithConfirmShowCancelButton(
              "Data is valid",
              function (result) {
                if (result.isConfirmed) {
                  var button = $("#btnProcessUploadModal");
                  var url = button.attr("data-url");
                  var data = $("#formUpload").serializeArray();
                  var textButton = button.text();

                  disabledButton(btnCloseModal);
                  requestUrl(data, btn, url, function (responsechecking) {
                    enabledButton(btnCloseModal);
                    loadingButtonOff(button, textButton);

                    console.log(responsechecking);

                    if (responsechecking.success === false) {
                      sweetAlertMessage(responsechecking.message);
                    } else {
                      reloadDatatables();
                      modalAutoClose();
                      modalAutoClose("#btnCloseModalPreview");
                      message(
                        responsechecking.success,
                        responsechecking.messages
                      );
                    }
                  });
                } else {
                  return false;
                }
              }
            );
          } else {
            //disini
            if (response.success === false) {
              sweetAlertMessage(response.message);
            } else {
              reloadDatatables();
              modalAutoClose();
              modalAutoClose("#btnCloseModalPreview");
              message(response.success, response.messages);
            }
          }
        }
      } else {
        if (response.success === false) {
          sweetAlertMessageWithConfirmNotShowCancelButton(
            response.messages,
            function () {
              location.reload();
            }
          );
        } else {
          if (response.success === false) {
            sweetAlertMessage(response.message);
          } else {
            reloadDatatables();
            modalAutoClose();
            modalAutoClose("#btnCloseModalPreview");
            message(response.success, response.messages);
          }
        }
      }
    });
  });

  /* SHADOW */
  $(document).on("click", ".btnLaunched,.btnShadow", function () {
    var url = $(this).data("url");
    var status = $(this).data("status");
    if (status < 3) {
      sweetAlertMessage("Status Product must be Incoming");
    } else {
      window.location.href = url;
    }
  });

  /* END SHADOW */
});
