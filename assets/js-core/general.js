function base_url() {
  var dropzoneexcel;
  var pathparts = window.location.pathname.split("/");
  if (
    location.host == "localhost:8090" ||
    location.host == "localhost" ||
    location.host == "172.17.1.25"
  ) {
    var folder = pathparts[2].trim("/");
    if (folder == "backend") {
      return (
        window.location.origin +
        "/" +
        pathparts[1].trim("/") +
        "/" +
        pathparts[2].trim("/") +
        "/"
      );
    }
    return window.location.origin + "/" + pathparts[1].trim("/") + "/"; // http://localhost/myproject/controller or folder
  } else {
    var folder = pathparts[1].trim("/");
    if (folder == "backend") {
      return window.location.origin + "/" + pathparts[1].trim("/") + "/";
    }
    return window.location.origin + "/"; // http://stackoverflow.com/
  }
}

var url_asset = base_url() + "assets/uploads/";
var url_asset_metronic = base_url() + "assets/metronic/";

function disabledButton(selector) {
  selector.prop("disabled", true);
}

function loadingButton(selector) {
  disabledButton(selector);
  selector.html(
    '<span class="indicator-label">Please wait...<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>'
  );
}

function loadingButtonOff(selector, text) {
  enabledButton(selector);
  selector.html('<span class="indicator-label">' + text + "</span>");
}

function enabledButton(selector) {
  selector.prop("disabled", false);
}

$(document).on("keyup", ":input", function () {
  $(this).removeClass("fv-plugins-bootstrap5-row-invalid");
  $(this).next(".invalid-feedback").remove();
});

$(document).on("change", "select", function () {
  $(this)
    .next()
    .next(".fv-plugins-message-container.invalid-feedback")
    .html("");
});

function update_csrf(token) {
  $(":input.token_csrf").val(token);
}

function get_csrf() {
  return $(":input.token_csrf").val();
}

function get_csrf_name() {
  return $(":input.token_csrf").data("name");
}

let tbl;
let length = 10;
let searchCustom = [];
function ajax_crud_table(
  base_url,
  column,
  tableID = "table-data",
  controller = null,
  selected = false
) {
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
    oLanguage: {
      sProcessing: "loading...",
    },
    serverSide: true,
    searching: false,
    responsive: false,
    select: selected,
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
        var data =
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
        width: "1000px",
        className: "dt-center",
      },
    ],
    fixedColumns: {
      // left: 1,
      right: 1,
    },
    rowCallback: function (row, data, iDisplayIndex) {
      var info = this.fnPagingInfo();
      var page = info.iPage;
      var length = info.iLength;
      var index = page * length + (iDisplayIndex + 1);
      $("td:eq(0)", row).html(index);
      if (typeof data.status != "undefined" && data.status !== null) {
        var text = "";
        var color = "";
        var color2 = "";
        var iconButton = "";
        switch (data.status) {
          case "enable":
            text = "Disabled";
            color = "btn-outline-warning";
            color2 = "btn-active-light-warning";
            iconButton = "bi bi-slash-circle";
            break;
          case "disable":
            text = "Enabled";
            color = "btn-outline-info";
            color2 = "btn-active-light-info";
            iconButton = "bi bi-check-circle";
            break;
          case "release":
            $("td:last", row).html(data.detail);
            break;
        }

        if (data.status == "enable" || data.status == "disable") {
          var newAction = data.action.replace("Disabled", text);
          var newColorAction = newAction.replace("btn-outline-warning", color);
          var newColor2Action = newColorAction.replace(
            "btn-active-light-warning",
            color2
          );
          var newIconButton = newColor2Action.replace(
            "bi bi-slash-circle",
            iconButton
          );

          $("td:last", row).html(newIconButton);
        }
      }

      switch (controller) {
        case "Channels":
          if (data.status_source == 1 && data.status_channel == 1) {
            $("td:eq(3)", row).html(
              '<span class="badge badge-light-success"> Enable </span>'
            );
          } else if (data.status_source != 1 && data.status_channel != 1) {
            $("td:eq(3)", row).html(
              '<span class="badge badge-light-danger"> Disable </span>'
            );
          } else if (data.status_source != 1) {
            $("td:eq(3)", row).html(
              '<span class="badge badge-light-danger">The Source ' +
                data.source_name +
                " is Disable</span>"
            );
          } else if (data.status_channel != 1) {
            $("td:eq(3)", row).html(
              '<span class="badge badge-light-danger">The Channel ' +
                data.channel_name +
                " is Disable</span>"
            );
          }
          break;
        case "inventory_display":
          if (data.status != 3) {
            var newAction = data.action.replace("{{disabled}}", "disabled");
          } else {
            var newAction = data.action.replace("{{disabled}}", "");
          }

          if (data.status < 3) {
            var newAction = newAction.replace("{{disabledShadow}}", "disabled");
          } else {
            var newAction = newAction.replace("{{disabledShadow}}", "");
          }

          $("td:eq(8)", row).html(newAction);

          break;

        case "inventory_allocations":
          var nRow = $(row);
          nRow.attr("data-productid", data.product_id);
          break;

        case "tito":
          if (data.status > 1) {
            var newAction = data.action.replace("{{disabled}}", "disabled");
          } else {
            var newAction = data.action.replace("{{disabled}}", "");
          }

          if (data.status > 1) {
            var newAction = newAction.replace("{{disabledSend}}", "disabled");
          } else {
            var newAction = newAction.replace("{{disabledSend}}", "");
          }

          if (data.status < 2) {
            var newAction = newAction.replace("{{hiddenView}}", "hidden");
          } else {
            var newAction = newAction.replace("{{hiddenView}}", "");
          }

          $("td:eq(8)", row).html(newAction);

          break;

        case "tito2":
          if (data.status == 1 || data.status == 3) {
            var newAction = data.action.replace("{{disabled}}", "disabled");
          } else {
            var newAction = data.action.replace("{{disabled}}", "");
          }

          if (data.status != 2) {
            var newAction = newAction.replace("{{hiddenView}}", "");
          } else {
            var newAction = newAction.replace("{{hiddenView}}", "hidden");
          }

          $("td:eq(8)", row).html(newAction);

          break;

        default:
          break;
      }

      if (data.action == "") {
        $("td:last", row).remove();
      }
    },
  });
}

function buttonAction(button, modal = null) {
  var url = button.data("url");
  var type = button.data("type");
  var fullscreen = button.data("fullscreenmodal");
  var modalID = modal == null ? "#modalLarge" : modal;
  if (type == "modal") {
    var data = [];
    data.push({ name: "_token", value: getCookie() });
    data.push({ name: "type", value: type });
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
        // $.getScript(base_url() + "assets/metronic/js/scripts.bundle.js");
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
  if (type == "redirect") {
    window.location.href = url;
  }
}

function addData() {
  $(document).on("click", "#btnAdd", function () {
    buttonAction($(this));
  });
}

function editData() {
  $(document).on("click", ".btnEdit", function () {
    buttonAction($(this));
  });
}

function reloadDatatables() {
  addDraw();
  tbl.ajax.reload();
}

function reloadDatatablesCustom() {
  $("#kt_datatable_vertical_scroll").DataTable().ajax.reload();
  $("#kt_datatable_suppliers").DataTable().ajax.reload();
  $("#kt_datatable_brand").DataTable().ajax.reload();
  $("#kt_datatable_warehouse").DataTable().ajax.reload();
  $("#kt_datatable_sources").DataTable().ajax.reload();
}

function addDataOption(getResponse) {
  var option = document.createElement("option");
  option.text = getResponse.name;
  option.value = getResponse.id;

  var select = document.getElementById("parent_id");
  select.appendChild(option);
}

function process(btnCloseModal = "#btnCloseModal") {
  $(document).on("click", "#btnProcessModal", function () {
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
          reloadDatatables();
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

function processNestedFields(btnCloseModal = "#btnCloseModal") {
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
            modalAutoClose1(closeModal);
          }
          reloadDatatables();
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

function processNestedFieldsCustom(btnCloseModal = "#btnCloseModal") {
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
            modalAutoClose(closeModal);
          }
          reloadDatatables();
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

function processSourceAccess(btnCloseModal = "#btnCloseModal") {
  $(document).on("click", "#btnProcessModal", function () {
    Swal.fire({
      title: "Are you sure?",
      text: "You won't be able to revert this!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Yes, save it!",
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
      }
    });
  });
}

function processAccount() {
  $(document).on("click", "#btnProcessModalAccount", function () {
    var textButton = $(this).text();
    var btn = $(this);
    var url = $("#AccountSetting").data("url");
    var data = $("#AccountSetting").serializeArray(); // convert form to array
    data.push({ name: "_token", value: getCookie() });
    $.ajax({
      url: url,
      method: "POST",
      dataType: "JSON",
      async: false,
      data: $.param(data),
      beforeSend: function () {
        loadingButton(btn);
        disabledButton($("#btnCloseModal"));
      },
      success: function (response) {
        if (!response.success) {
          if (!response.validate) {
            $.each(response.messages, function (key, value) {
              addErrorValidation(key, value);
            });
          }
        } else {
          var check = $("#table-data").hasClass("table");
          if (check) {
            reloadDatatables();
          }

          modalAutoClose();
          message(response.success, response.messages);
          $(".fullname").html(data[1].value);
          $(".email_account").html(data[2].value);
        }

        loadingButtonOff(btn, textButton);
        enabledButton($("#btnCloseModal"));
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

function message(success, message) {
  if (success) {
    toastr.success(message, "", {
      progressBar: !0,
      timeOut: 2000,
    });
  } else {
    toastr.warning(message, "", {
      progressBar: !0,
      timeOut: 2000,
    });
  }
}

function sweetAlertMessage(message) {
  Swal.fire("", message);
}

function modalClose() {
  $(document).on("click", "#btnCloseModal", function () {
    $("#modalLarge").modal("hide");
  });
}

function modalCloseCustom(btnName, modalName) {
  $(document).on("click", btnName, function () {
    $(modalName).modal("hide");
  });
}

function modalAutoClose(closeModal = "#modalLarge") {
  if (closeModal != "#modalLarge") {
    var idModal = $(closeModal).parent().parent().parent().parent().attr("id");
    $("#" + idModal).modal("hide");
  } else {
    $("#modalLarge3").modal("hide");
    $(closeModal).modal("hide");
  }
}

function reset_input() {
  $("input[data-type='input']").val("");
  $("input[data-type='date']").val("");
  $("textarea[data-type='input']").val("");
  $("input[data-type='checkbox']").prop("checked", false);
  $("select[data-type='select-multiple']").val("").trigger("change");
  $("select[data-type='select']").val("").trigger("change");

  if (typeof $("[data-repeater-item]") != "undefined") {
    $("[data-repeater-item]").slice(2).remove();
  }

  if (typeof $(".dropzone")[0] != "undefined") {
    $(".dropzone")[0].dropzone.files.forEach(function (file) {
      file.previewTemplate.remove();
    });

    $(".dropzone").removeClass("dz-started");
  }

  if ($("#kt_datatable_vertical_scroll")) {
    $("#kt_datatable_vertical_scroll").DataTable().clear().draw();
  }

  $("#remove_image").click();

  if ($("#kt_datatable_fixed_columns")) {
    $("#kt_datatable_fixed_columns").DataTable().clear().draw();
  }
}

function requestFromForm(btn, callback) {
  var url = $("#form").data("url");
  var type = btn.data("type");
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
      disabledButton($("#btnCloseModal"));
    },
    success: callback,
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

function request(data, btn, callback) {
  var url = btn.data("url");
  data.push({ name: "_token", value: getCookie() });

  $.ajax({
    url: url,
    method: "POST",
    dataType: "JSON",
    async: false,
    data: $.param(data),
    beforeSend: function () {
      //loadingButton(btn);
    },
    success: callback,
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
          // sweetAlertMessageWithConfirmNotShowCancelButton(
          //   "We are sorry, but you do not have access to this service",
          //   function () {
          //     location.reload();
          //   }
          // );
          break;
      }
    },
  });
}

function requestUrl(data, btn, url, callback) {
  data.push({ name: "_token", value: getCookie() });

  $.ajax({
    url: url,
    method: "POST",
    dataType: "JSON",
    async: false,
    data: $.param(data),
    beforeSend: function () {
      //loadingButton(btn);
    },
    success: callback,
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
          // sweetAlertMessageWithConfirmNotShowCancelButton(
          //   "We are sorry, but you do not have access to this service",
          //   function () {
          //     location.reload();
          //   }
          // );
          break;
      }
    },
  });
}

function requestUrlNotLoadingButton(data, url, callback) {
  data.push({ name: "_token", value: getCookie() });

  $.ajax({
    url: url,
    method: "POST",
    dataType: "JSON",
    async: false,
    data: $.param(data),
    success: callback,
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

$(document).on("click", "#btnCollapse", function () {
  var result = $(this).hasClass("i-Add");
  if (result) {
    $(this).removeClass("i-Add").addClass("i-Remove");
  } else {
    $(this).removeClass("i-Remove").addClass("i-Add");
  }
});

$(document).on("click", "#btnSearchReset", function () {
  reset_input();
  reloadDatatables();
});

function libraryInput() {
  var checkInputMask = $("input[data-library='inputmask']").data("library");
  if (typeof checkInputMask != "undefined" && checkInputMask == "inputmask") {
    $("input[data-library='inputmask']").inputmask();
  }
  var checkSelect = $("select").hasClass("select2");
  if (checkSelect) {
    $("select[data-library='select2']").select2({
      theme: "bootstrap4",
    });
    $("select[data-library='select2-single']").select2({
      theme: "bootstrap4",
    });
  }
}

function loadingPage() {
  Swal.fire({
    html: '<span class="spinner-border text-primary" role="status"></span><br><br><span class="text-muted fs-6 fw-semibold mt-5">Loading...</span>',
    allowOutsideClick: false,
    showCancelButton: false,
    showConfirmButton: false,
  });
}

function sweetAlertConfirm() {
  $(document).on("click", "button[data-type='confirm']", function () {
    var url = $(this).data("url");
    var confirm = $(this).data("textconfirm");
    var title = $(this).data("title");
    var text = $(this).text();

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
        var data = [];
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
              response.success ? "success" : "error"
            );
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
}

function sweetAlertMessageWithConfirmNotShowCancelButton(message, callback) {
  Swal.fire({
    html: message,
    allowOutsideClick: false,
    showCancelButton: false,
    showConfirmButton: true,
    type: "warning",
    confirmButtonColor: "#0CC27E",
    confirmButtonText: "Ok",
  }).then(callback);
}

function checkLibraryOnModal() {
  var result = $(".modal-body .form-control").hasClass("select2");
  if (result) {
    $("select[data-library='select2-single']").select2({
      theme: "bootstrap4",
    });
    $("select[data-library='select2']").select2({
      theme: "bootstrap4",
    });
  }

  var result = $(".modal-body .form-control").hasClass("singleDateRange");
  if (result) {
    $("input[data-library='singleDateRangeStartTomorrow']").daterangepicker({
      singleDatePicker: true,
      timePicker: false,
      showDropdowns: true,
      startDate: moment().add(1, "days"),
      minDate: moment().add(1, "days"),
      locale: {
        format: "YYYY-MM-DD",
      },
    });
  }

  var result = $(".modal-body #form").hasClass("dropzoneExcel");
  if (result) {
    dropZoneExcel($("#form").data("url"));
  }

  var result = $(".modal-body #formoffline").hasClass("dropzoneExcel");
  if (result) {
    dropZoneExcel($("#formoffline").data("url"));
  }
}

function addErrorValidation(key, value) {
  var check = $("#" + key).data("library");
  var element = $("#" + key);

  if (typeof check == "undefined") {
    element
      .removeClass("fv-plugins-bootstrap5-row-invalid")
      .addClass(value.length < 1 ? "fv-plugins-bootstrap5-row-invalid" : "")
      .next(".invalid-feedback")
      .remove();
    element.after(value);
  } else {
    switch (check) {
      case "select2-single":
        element
          .removeClass("fv-plugins-bootstrap5-row-invalid")
          .addClass(value.length > 0 ? "fv-plugins-bootstrap5-row-invalid" : "")
          .next()
          .next(".invalid-feedback")
          .remove();

        element.next().after(value);
        break;
      case "select2":
        element
          .removeClass("fv-plugins-bootstrap5-row-invalid")
          .addClass(value.length > 0 ? "fv-plugins-bootstrap5-row-invalid" : "")
          .next()
          .next(".invalid-feedback")
          .remove();

        element.next().after(value);
        break;
    }
  }
}

function addErrorValidationNestedFields(key, value) {
  $("input, select, textarea").each(function () {
    var typeTagName = $(this).prop("tagName").toLowerCase();

    switch (typeTagName) {
      case "input":
        var element = $('input[name="' + key + '"]');
        element
          .removeClass("fv-plugins-bootstrap5-row-invalid")
          .addClass(value.length < 1 ? "fv-plugins-bootstrap5-row-invalid" : "")
          .next(".invalid-feedback")
          .remove();
        element.after(value);
        break;
      case "textarea":
        var element = $('textarea[name="' + key + '"]');
        element
          .removeClass("fv-plugins-bootstrap5-row-invalid")
          .addClass(value.length < 1 ? "fv-plugins-bootstrap5-row-invalid" : "")
          .next(".invalid-feedback")
          .remove();
        element.after(value);
        break;
      case "select":
        var element = $('select[name="' + key + '"]');

        //Build Element With Span
        element
          .removeClass("fv-plugins-bootstrap5-row-invalid")
          .addClass(value.length < 1 ? "fv-plugins-bootstrap5-row-invalid" : "")
          .next()
          .next(".invalid-feedback")
          .remove();

        element.next().after(value);

        // element
        //   .removeClass("fv-plugins-bootstrap5-row-invalid")
        //   .addClass(value.length < 1 ? "fv-plugins-bootstrap5-row-invalid" : "")
        //   .next(".invalid-feedback")
        //   .remove();

        // element.after(value);
        break;
      default:
        break;
    }
  });
}

function setCookie(cname) {
  let name = cname + "=";
  let decodedCookie = decodeURIComponent(document.cookie);
  let ca = decodedCookie.split(";");
  for (let i = 0; i < ca.length; i++) {
    let c = ca[i];
    while (c.charAt(0) == " ") {
      c = c.substring(1);
    }
    if (c.indexOf(name) == 0) {
      return c.substring(name.length, c.length);
    }
  }
  return "";
}

function getCookie() {
  return setCookie("csrf_cookie_name");
}

$("#modalLarge").on("hidden.bs.modal", function () {
  $("#modalLarge .modal-dialog").removeClass("modal-fullscreen");
  $("#modalLarge .modal-dialog").removeClass("p-9");
});

// $("#modalLarge2").on("hidden.bs.modal", function () {
//   $("#modalLarge2 .modal-dialog").removeClass("modal-fullscreen");
//   $("#modalLarge2 .modal-dialog").removeClass("p-9");
// });

$("#modalLarge3").on("hidden.bs.modal", function () {
  $("#modalLarge3 .modal-dialog").removeClass("modal-fullscreen");
  $("#modalLarge3 .modal-dialog").removeClass("p-9");
});

function loadPaginationDatatables(url, total, page) {
  var data = [];
  data.push({
    name: "total",
    value: total,
  });
  data.push({
    name: "limit",
    value: length,
  });
  data.push({
    name: "page",
    value: page,
  });

  if (typeof total != "undefined") {
    requestUrlNotLoadingButton(data, url, function (response) {
      $(".paginationDatatables").html(response.paging);
    });
  }
}

function addDraw() {
  var draw = $(".draw_datatables").val();
  draw++;
  $(".draw_datatables").val(draw);
}

$(document).on("click", ".paginationDatatables .page-link", function () {
  var halaman = $(this).data("halaman");
  $(".halaman").val(halaman);
  reloadDatatables();
});

let tbl1;
let length1 = 10;
function ajax_crud_table1(
  base_url,
  column,
  tableID = "table-data",
  controller = ""
) {
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

  tbl1 = $("#" + tableID).DataTable({
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
        var data =
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
      },
    ],
    rowCallback: function (row, data, iDisplayIndex) {
      $("td:eq(0)", row)["0"].setAttribute("data-id", data["id"]);
      var info = this.fnPagingInfo();
      var page = info.iPage;
      var length1 = info.iLength;
      var index = page * length1 + (iDisplayIndex + 1);
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
        }

        var newAction = data.action.replace("Disabled", text);
        var newColorAction = newAction.replace("btn-outline-info", color);
        $("td:last", row).html(newColorAction);
      }

      if (controller != "") {
        switch (controller) {
          case "inventory_display_default":
            var replaceText = data.status_id == 1 ? "disabled" : "";
            var newAction = data.action_default.replace(
              "{{notSelected}}",
              replaceText
            );
            $("td:eq(3)", row).html(newAction);
            break;

          default:
            break;
        }
      }

      if (data.action == "") {
        $("td:last", row).remove();
      }
    },
  });
}

function reloadDatatables1() {
  addDraw();
  tbl1.ajax.reload();
}

$(document).on("click", "#btnReset", function () {
  reset_input();
  reloadDatatables1();
});

function processAllocation() {
  $(document).on("click", "#btnProcessModal", function () {
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
        disabledButton($("#btnCloseModalFullscreen"));
      },
      success: function (response) {
        if (!response.success) {
          if (!response.validate) {
            $.each(response.messages, function (key, value) {
              addErrorValidation(key, value);
            });
          }
        } else {
          var check = $("#table-data").hasClass("table");
          if (check) {
            reloadDatatables1();
          }

          modalAutoClose1();
          message(response.success, response.messages);
        }

        loadingButtonOff(btn, textButton);
        enabledButton($("#btnCloseModalFullscreen"));
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

function modalAutoClose1(closeModal = "#modalLarge2") {
  if (closeModal != "#modalLarge2") {
    var idModal = $(closeModal).parent().parent().parent().parent().attr("id");
    $("#" + idModal).modal("hide");
  } else {
    $(closeModal).modal("hide");
  }
}

function filterHidden() {
  $("#kt_docs_card_collapsible").collapse("hide");
}

$(document).on("click", "#btnSearchHidden", function () {
  filterHidden();
});

$(document).on("click", "#btnSearchResetUncollapse", function () {
  reset_input();
  filterHidden();
  reloadDatatables();
});

function CloseLoadingPage() {
  Swal.close();
}

function ajax_crud_table_without_number(
  base_url,
  column,
  tableID = "table-data",
  controller = null
) {
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

  if (typeof permission != "undefined") {
    var permissionBool = Boolean(permission);
    if (!permissionBool) {
      column.pop();
    }
  }

  tbl = $("#" + tableID).DataTable({
    initComplete: function () {
      var api = this.api();
      $("#" + tableID + "_filter input")
        .off(".DT")
        .on("keyup.DT", function (e) {
          if (e.keyCode == 13) {
            api.search(this.value).draw();
          }
        });
      tbl.columns.adjust().draw(false);
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
        var data =
          $("#formSearch").length > 0 ? $("#formSearch").serializeArray() : [];
        return $.extend({}, d, {
          _token: getCookie(),
          filters: data,
        });
      },
    },
    ajax: {
      url: base_url,
      type: "POST",
      data: function (d) {
        var data =
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
      var info = this.fnPagingInfo();
      var page = info.iPage;
      var length = info.iLength;
      var index = page * length + (iDisplayIndex + 1);
    },
  });
}

function ajax_crud_table_without_number_Custom(
  base_url,
  column,
  tableID = "table-data",
  controller = null
) {
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

  if (typeof permission != "undefined") {
    var permissionBool = Boolean(permission);
    if (!permissionBool) {
      column.pop();
    }
  }

  tbl = $("#" + tableID).DataTable({
    initComplete: function () {
      var api = this.api();
      $("#" + tableID + "_filter input")
        .off(".DT")
        .on("keyup.DT", function (e) {
          if (e.keyCode == 13) {
            api.search(this.value).draw();
          }
        });
      tbl.columns.adjust().draw(false);
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
        var data =
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
        // targets: -1,
        // title: "Action",
        // width: "300px",
        // className: "dt-center",
      },
    ],
    rowCallback: function (row, data, iDisplayIndex) {
      var info = this.fnPagingInfo();
      var page = info.iPage;
      var length = info.iLength;
      var index = page * length + (iDisplayIndex + 1);
    },
  });
}

function generateNumber() {
  var value = Math.floor(Math.random() * 1000000);
  return "a" + value.toString();
}

function buttonActionData(button, data, modal = null) {
  var url = button.data("url");
  var type = button.data("type");
  var fullscreen = button.data("fullscreenmodal");
  var modalID = modal == null ? "#modalLarge" : modal;
  if (type == "modal") {
    data.push({ name: "_token", value: getCookie() });
    data.push({ name: "type", value: type });
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
        // $.getScript(base_url() + "assets/metronic/js/scripts.bundle.js");
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
  if (type == "redirect") {
    window.location.href = url;
  }
}

function format_number_to_idr(amount) {
  return "IDR " + amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

const format_number_no_idr = (amount) => {
  return amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
};

function formatCurrency(input) {
  var value = input.value.replace(/\D/g, "");
  input.value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

var datatablesTabs = [];
function ajax_crud_table_tabs(
  base_url,
  column,
  tableID = "table-data",
  controller = null,
  selected = false,
  formName = "formSearch"
) {
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

  datatablesTabs[tableID] = $("#" + tableID).DataTable({
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
    oLanguage: {
      sProcessing: "loading...",
    },
    serverSide: true,
    responsive: false,
    select: selected,
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
        var data =
          $("#" + formName).length > 0
            ? $("#" + formName).serializeArray()
            : [];
        return $.extend({}, d, {
          _token: getCookie(),
          filters: data,
        });
      },
    },
    columns: column,
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
          case "release":
            $("td:last", row).html(data.detail);
            break;
        }

        if (data.status == "enable" || data.status == "disable") {
          var newAction = data.action.replace("Disabled", text);
          var newColorAction = newAction.replace("btn-outline-info", color);

          $("td:last", row).html(newColorAction);
        }
      }

      switch (controller) {
        case "Channels":
          if (data.status_source == 1 && data.status_channel == 1) {
            $("td:eq(3)", row).html(
              '<span class="badge badge-light-success"> Enable </span>'
            );
          } else if (data.status_source != 1 && data.status_channel != 1) {
            $("td:eq(3)", row).html(
              '<span class="badge badge-light-danger"> Disable </span>'
            );
          } else if (data.status_source != 1) {
            $("td:eq(3)", row).html(
              '<span class="badge badge-light-danger">The Source ' +
                data.source_name +
                " is Disable</span>"
            );
          } else if (data.status_channel != 1) {
            $("td:eq(3)", row).html(
              '<span class="badge badge-light-danger">The Channel ' +
                data.channel_name +
                " is Disable</span>"
            );
          }
          break;
        case "inventory_display":
          if (data.status != 3) {
            var newAction = data.action.replace("{{disabled}}", "disabled");
          } else {
            var newAction = data.action.replace("{{disabled}}", "");
          }

          $("td:eq(8)", row).html(newAction);

          break;

        case "inventory_allocations":
          var nRow = $(row);
          nRow.attr("data-productid", data.product_id);
          break;

        default:
          break;
      }

      if (data.action == "") {
        $("td:last", row).remove();
      }
    },
  });
}

function reloadDatatablesTabs(id) {
  datatablesTabs[id].ajax.reload();
}

$(document).on("click", "#btnResetSearch", function () {
  let selectField = $(this).parent().parent().parent().find("select")[0];
  let inputField = $(this).parent().parent().parent().find("input");
  let startDate = $('input[id="start_date" ]');
  let endDate = $('input[id="end_date" ]');
  $(selectField).val(null).trigger("change");
  $(inputField[0]).val("");
  $('input[id="lookup_status" ]:checked').prop("checked", false);
  $(startDate).val("");
  $(endDate).val("");
});

function exportData() {
  $(document).on("click", "#btnExport", function () {
    var baseUrl = $(this).data("url");
    var data =
      $("#formSearch").length > 0 ? $("#formSearch").serializeArray() : [];
    data.push({
      name: "search",
      value: $("#table-data_filter input").val(),
    });
    data.push({ name: "_token", value: getCookie() });
    var uri = baseUrl + "?" + $.param(data);
    window.open(uri);
  });
}

function dropZoneExcel(base_url) {
  dropzoneexcel = new Dropzone("#dropZoneModal", {
    url: base_url, // Set the url for your upload script location
    paramName: "file", // The name that will be used to transfer the file
    maxFiles: 1,
    maxFilesize: 2, // MB
    addRemoveLinks: true,
    acceptedFiles: "text/csv",
    init: function () {
      this.on("maxfilesexceeded", function (file) {
        this.removeAllFiles();
        this.addFile(file);
      });

      this.on("sending", function (file, xhr, formData) {
        formData.append("_token", getCookie());
      });
      this.on("error", function (file, errorMessage) {
        var errorDisplay = document.querySelectorAll("[data-dz-errormessage]");
        errorDisplay[errorDisplay.length - 1].innerHTML =
          "Error 404: The upload page was not found on the server";
      });
      this.on("success", function (file, response) {
        if (typeof response == "string") {
          var response = JSON.parse(response);
        }

        if (!response.success) {
          if (typeof response.success == "undefined") {
            sweetAlertMessageWithStatus(
              false,
              "Error processing request upload"
            );
          } else {
            sweetAlertMessageWithStatus(response.success, response.messages);
          }
        } else {
          //show preview
          if (response.html) {
            var modalID = "#modalLarge2";
            $(modalID + " .modal-dialog").addClass("modal-fullscreen");
            $(modalID + " .modal-content").html(response.html);
            $(modalID).modal("show");
            this.removeFile(file);
            checkLibraryOnModalAfterDropZone(modalID);
          }
        }
      });
    },
  });
}

function sweetAlertMessageWithStatus(success, message) {
  Swal.fire("", message, success ? "success" : "warning");
}

function checkLibraryOnModalAfterDropZone(modalID) {
  var result = $(".modal-body .form-select").hasClass("selectIn");
  if (result) {
    $("select[data-library='select2-single']").select2({
      placeholder: "--Options--",
      dropdownParent: $(modalID),
      allowClear: true,
    });
  }
}

function sweetAlertConfirmDeleteHTML() {
  $(document).on("click", "button[data-type='confirm']", function () {
    var confirm = $(this).data("textconfirm");
    var title = $(this).data("title");
    var deleteID = $(this).data("id");

    Swal.fire({
      title: title,
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
        $("#" + deleteID).remove();
      } else {
        return false;
      }
    });
  });
}

function sweetAlertMessageWithConfirmShowCancelButton(message, callback) {
  Swal.fire({
    html: message,
    allowOutsideClick: false,
    showCancelButton: true,
    showConfirmButton: true,
    icon: "success",
    confirmButtonColor: "#0CC27E",
    confirmButtonText: "Continue to Save",
  }).then(callback);
}

function loading(width = 84, height = 84) {
  const svg = `
    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
      style="margin: auto; background: none; display: block; shape-rendering: auto;" width="${width}px" height="${height}px"
      viewBox="0 0 100 100" preserveAspectRatio="xMidYMid">
      <path d="M10 50A40 40 0 0 0 90 50A40 42 0 0 1 10 50" fill="#93dbe9" stroke="none">
          <animateTransform attributeName="transform" type="rotate" dur="1s" repeatCount="indefinite" keyTimes="0;1"
              values="0 50 51;360 50 51"></animateTransform>
      </path>
    </svg>
  `;
  return svg;
}

$isMobile = function () {
  let check = false;
  (function (a) {
    if (
      /(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test(
        a
      ) ||
      /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(
        a.substr(0, 4)
      ) ||
      $(window).innerWidth() <= 420
    )
      check = true;
  })(navigator.userAgent || navigator.vendor);
  return check;
};

var mobileModal1 = document.querySelector("ion-modal");

mobileModal1.initialBreakpoint = 1;
mobileModal1.breakpoints = [0, 1];
