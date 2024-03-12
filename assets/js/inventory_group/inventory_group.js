var pushToTagify = [];
let arrayProduct = [];
$(document).ready(function () {
  var baseurl = base_url() + "inventory_group/show";

  var column = [
    { data: "group_code" },
    { data: "group_name" },
    {
      data: "brand_group",
      render: function (data) {
        let badge = '<div class="badge badge-light-info">' + data + "</div>";

        return badge;
      },
    },
    {
      data: "status_name",
      render: function (data) {
        let badge = "";

        if (data === "New Group") {
          badge = '<div class="badge badge-light-primary">' + data + "</div>";
        } else if (data === "Incoming") {
          badge = '<div class="badge badge-light-warning">' + data + "</div>";
        }

        return badge;
      },
    },
    { data: "action", width: "25%" },
  ];

  libraryInput();

  ajax_crud_group(baseurl, column);

  $(document).on("click", "#saveProcess", function () {
    Swal.fire({
      title: "Save Group Product",
      text: "Are you sure save this data?",
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
        let btnCloseModal = "#btnCloseModalFullscreen";

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
                modalAutoClose(btnCloseModal);
              }
              addDraw();
              $("#table-data").DataTable().ajax.reload();
            }

            loadingButtonOff(btn, textButton);
            enabledButton($(btnCloseModal));

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

  $(document).on("click", "#saveSources", function () {
    let btnCloseModal = "#btnCloseModalImage";

    var textButton = $(this).text();
    var btn = $(this);
    var url = base_url() + "inventory_group/process_sources";
    var data = $("#formCustom").serializeArray();
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

  $(document).on("click", "#buttonDeleted", function () {
    let getBrand = $(this).data("brand");

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
        let row = $(this).parent().parent();

        row.remove();

        var index = $.inArray(getBrand, pushToTagify);
        if (index !== -1) {
          pushToTagify.splice(index, 1);
        }

        var input1 = document.querySelector("#kt_tagify_1");
        var tagify_class = new Tagify(input1);
        tagify_class.removeAllTags();

        tagify_class.addTags(pushToTagify);
      } else {
        return false;
      }
    });
  });

  $(document).on("click", "#btnAdd,.btnEdit", function () {
    $("#modalLarge3 .modal-content").attr("style", "border: 3px solid #888;");
    buttonAction($(this), "#modalLarge2");
    $("#kt_datatable_vertical_scroll tbody").html("");

    ClassicEditor.create(document.querySelector("#kt_docs_ckeditor_classic"))
      .then((editor) => {
        console.log(editor);
      })
      .catch((error) => {
        console.error(error);
      });
  });

  $(document).on("click", ".checkList", function () {
    let pushData = $(this).data("value");
    let checked = $(this)[0].checked;

    if (checked) {
      const foundElement = arrayProduct.find((item) => item === pushData);

      if (typeof foundElement === "undefined") {
        arrayProduct.push(pushData);
      }
    } else {
      arrayProduct = [];
    }
  });

  $(document).on("click", "#btnAddSKU", function () {
    buttonAction($(this), "#modalLarge3");

    var dataUpload = [];
    var tableRows = $("#kt_datatable_product_list").DataTable({
      paging: true,
    });

    //Search Datatables
    $("#myInputTextField").keyup(function () {
      tableRows.search($(this).val()).draw();
    });

    $(document).on("click", "#checkedBox", function () {
      $(this).addClass("selected_row");
    });

    dataUpload.push({ name: "_token", value: getCookie() });
    $.ajax({
      url: base_url() + "inventory_group/productList",
      method: "POST",
      dataType: "JSON",
      async: false,
      data: dataUpload,
      success: function (result) {
        var getJsonData = result.data;

        tableRows.clear().draw();
        var checkedData = [];

        no = 1;
        for (let i = 0; i < getJsonData.length; i++) {
          var datas = getJsonData[i];

          var rowChecked = {
            product_id: datas.product_id,
            product_code: datas.product_code,
            product_name: datas.product_name,
            brand_name: datas.brand_name,
            product_size: datas.product_size,
          };

          checkedData = rowChecked;

          var dataToRow = [
            no++,
            datas.product_code,
            datas.product_name,
            `<div class="badge badge-light-info">` +
              datas.product_size +
              `</div>`,
            datas.brand_name,
            `<div class="form-check form-check-sm form-check-custom form-check-solid">
                                <input data-value="` +
              btoa(JSON.stringify(checkedData)) +
              `" class="form-check-input checkList" type="checkbox" id="checkedBox" />
                            </div>`,
          ];

          tableRows.row.add(dataToRow).draw();
        }
      },
    });
  });

  $(document).on("click", "#btnView", function () {
    var button = $(this);
    var postData = $(this).data("id");

    var url = button.data("url");
    var type = button.data("type");
    var fullscreen = button.data("fullscreenmodal");
    var modalID = "#modalLarge3";

    if (type == "modal") {
      var data = [];
      data.push({ name: "data_id", value: postData });
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

    $("#btnProcessModal3").hide();
  });
});

let dataArr = [];
$(document).on("click", "#selectProductList", function (e) {
  var target = $(".modal-upload").parent().parent().parent(".modal-content")[0];
  var blockUI = KTBlockUI.getInstance(target);
  e.preventDefault();
  blockUI.block();

  for (let i = 0; i < arrayProduct.length; i++) {
    let getJsonData = JSON.parse(atob(arrayProduct[i]));

    pushToTagify.push(getJsonData.brand_name);

    let tr_table = ` <tr>`;

    tr_table +=
      `<td style='vertical-align: middle;'>` +
      getJsonData.product_code +
      `</td>
      <td style='vertical-align: middle;'>` +
      getJsonData.product_name +
      `</td>
      <td style='vertical-align: middle;'><div class="badge badge-light-info">` +
      getJsonData.product_size +
      `
      </td>
      <td style='vertical-align: middle;'>` +
      getJsonData.brand_name +
      `</td>
      <td>
      <button type="button" data-id="` +
      getJsonData.product_id +
      `" class="btn btn-success hover-scale btn-sm mr-5" id="btnView" data-type="modal" data-url="` +
      base_url() +
      `inventory_group/viewSKU" data-fullscreenmodal="0">View</button>
        <button type="button" data-brand="` +
      getJsonData.brand_name +
      `" class="btn btn-danger hover-scale btn-sm" data-textconfirm="Are you sure you want to delete this item ?" data-title="Item" id="buttonDeleted">Delete</button>
      </td>
      <input type="hidden" name="product_id[]" value="` +
      getJsonData.product_id +
      `" />
      <input type="hidden" name="detail_id[]" value="0" />`;

    tr_table += `</tr>`;

    $("#kt_datatable_vertical_scroll tbody").append(tr_table);
  }

  // if (!isDuplicateSKU(getJsonData.sku)) {
  //   $("#kt_datatable_vertical_scroll tbody").append(tr_table);
  // }

  var input1 = document.querySelector("#kt_tagify_1");
  var tagify_class = new Tagify(input1);
  tagify_class.addTags(pushToTagify);

  setTimeout(function () {
    blockUI.release();
  }, 700);

  $("#modalLarge3").modal("hide");
  arrayProduct = [];
});

$(document).on("click", "#btnCloseModalFullscreen", function () {
  $("#modalLarge2").modal("hide");
});

$(document).on("click", "#btnCloseModalFullscreen2", function () {
  $("#modalLarge3").modal("hide");
});

$(document).on("click", ".btnLaunching", function () {
  var url = $(this).data("url");

  window.location.href = url;

  // var status = $(this).data("status");
  // if (status != 3) {
  //   sweetAlertMessage("Status Product must be Incoming");
  // } else {
  //   window.location.href = url;
  // }

  // buttonAction($(this), "#modalLarge2");

  // let getUrl = $(this).data("url");
  // let parts = getUrl.split("/");
  // let lastParam = parts[parts.length - 1];

  // $("#kt_datatable_sources").DataTable({
  //   ajax: {
  //     url: base_url() + "inventory_group/dataSourceChannels",
  //     type: "POST",
  //     data: function () {
  //       let data = [
  //         { name: "_token", value: getCookie() },
  //         {
  //           name: "group_id",
  //           value: lastParam,
  //         },
  //       ];

  //       return data;
  //     },
  //   },
  //   processing: true,
  //   serverSide: true,
  //   paging: false,
  //   ordering: false,
  //   searching: true,
  //   info: false,
  //   bDestroy: true,
  //   aLengthMenu: [
  //     [10, 25, 50, -1],
  //     [10, 25, 50, "All"],
  //   ],
  //   columns: [
  //     { data: "source_name" },
  //     { data: "channel_name" },
  //     { data: "channel_name" },
  //     {
  //       data: "",
  //       render: function () {
  //         return (
  //           '<button type="button" class="btn btn-outline btn-outline-dashed btn-outline-info btn-sm me-2 btnDefaultImage" data-title="Item" data-type="modal" data-url="" data-fullscreenmodal="0">Image</button>' +
  //           '<button type="button" class="btn btn-outline btn-outline-dashed btn-outline-primary btn-sm me-2 btnDefaultImage" data-title="Item" data-type="modal" data-url="http://localhost/ims_project/inventory_group/selectDefaultImage/<?= $dataItems->id ?>" data-fullscreenmodal="0">Launch</button>'
  //         );
  //       },
  //     },
  //     {
  //       data: "",
  //       render: function () {
  //         return "Image Not Selected,Pending";
  //       },
  //     },
  //   ],
  //   columnDefs: [
  //     {
  //       width: "20px",
  //       orderable: false,
  //       responsivePriority: 2,
  //       targets: 0,
  //     },
  //   ],
  // });
});

$(document).on("click", ".btnDefaultImage", function () {
  let getGid = $(this).data("id");

  $("#modalLarge3 .modal-content").attr("style", "border: 3px solid #888;");

  buttonAction($(this), "#modalLarge3");

  let data = [
    { name: "_token", value: getCookie() },
    {
      name: "group_id",
      value: getGid,
    },
  ];
  $.ajax({
    url: base_url() + "inventory_group/image_default",
    method: "POST",
    dataType: "JSON",
    async: false,
    data: data,
    success: function (result) {
      let status = "";
      let status_image = "";
      let getJsonData = result.data;
      let arrayCheck = [];

      for (let i = 0; i < getJsonData.length; i++) {
        arrayCheck.push(getJsonData[i].image_status);

        if (getJsonData[i].image_status === "1") {
          status = "Not Selected";
          status_image = "disabled";
        } else if (data === "2") {
          status = "Selected";
        } else {
          status = "Main";
        }

        let tr_table = ` <tr>`;

        tr_table +=
          `<td style='text-align: center;vertical-align: middle;'>
            <div class="symbol symbol-50px">
            <span class="symbol-label" style="background-image:url(` +
          base_url() +
          "assets/uploads/products_image/" +
          getJsonData[i].image_name +
          `);border: 1px solid #000;"></span>
            </div>
        </td>
        <td style='vertical-align: middle;'>` +
          getJsonData[i].image_name +
          `</td>
        <td style='vertical-align: middle;'>
        <button type="button" class="btn btn-outline btn-outline-dashed btn-outline-success btn-sm me-2 btnSelectDefaultImage" data-title="Item" data-type="modal" data-url="` +
          base_url() +
          "inventory_group/selected_image_default/" +
          getJsonData[i].image_name +
          `" data-fullscreenmodal="0" data-id="` +
          getJsonData[i].id +
          `" data-gid="` +
          getGid +
          `">Select</button> 
          <button type="button" class="btn btn-outline btn-outline-dashed btn-outline-info btn-sm me-2 btnViewDefaultImage" data-title="Item" data-type="modal" data-url="` +
          base_url() +
          "inventory_group/view_image_default/" +
          getJsonData[i].image_name +
          `" data-fullscreenmodal="0">View</button>
          <button type="button" class="btn btn-outline btn-outline-dashed btn-outline-warning btn-sm me-2 btnCancelSelected" data-id="` +
          getJsonData[i].id +
          `" ` +
          status_image +
          `>Cancel</button>
        </td>
        <td style='vertical-align: middle;'>
        <span class="statusName" data-imageid="` +
          getJsonData[i].id +
          `">` +
          status +
          `</span>
          </td>

        <input type="hidden" name="detail_id[]" value="` +
          status +
          `">`;

        tr_table += `</tr>`;

        $("#kt_datatable_vertical_scroll tbody").append(tr_table);
      }

      if (arrayCheck.includes(["3", "2"])) {
        setTimeout(() => {
          $("#saveProcessDefaultImage").prop("disabled", false);
        }, 100);
      }
    },
  });
});

$(document).on("click", "#btnCloseModalImage", function () {
  $("#modalLarge3").modal("hide");
});

$(document).on("click", ".addSource", function () {
  let button = $(this);
  let url = button.data("url");
  let type = button.data("type");
  let fullscreen = button.data("fullscreenmodal");
  let modalID = "#modalLarge3";
  if (type == "modal") {
    $("#modalLarge3 .modal-content").attr(
      "style",
      "border: 3px solid #888;px;width:500px;position: absolute;left: 50%;top: 50%;transform: translate(-50%, -50%);"
    );

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

  let data_sources = [{ name: "_token", value: getCookie() }];
  $.ajax({
    url: base_url() + "inventory_group/source_list",
    method: "POST",
    dataType: "JSON",
    data: data_sources,
    async: false,
    success: function (response) {
      let getJsonData = response.data;

      addSource(getJsonData);
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

$(document).on("change", "#source", function () {
  var value = $(this).val();

  $(this).next(".fv-plugins-message-container.invalid-feedback").html("");

  let data_channel = [
    { name: "_token", value: getCookie() },
    { name: "sources_id", value: value },
  ];

  $.ajax({
    url: base_url() + "inventory_group/channels_list",
    method: "POST",
    dataType: "JSON",
    data: data_channel,
    async: false,
    success: function (response) {
      let getJsonData = response.data;

      addChannel(getJsonData);
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

function addSource(data) {
  $("#source").empty();
  $("#source").append(
    $("<option>", {
      value: "",
      text: "Select Source",
    })
  );
  $.each(data, function (i, item) {
    $("#source").append(
      $("<option>", {
        value: item.source_id,
        text: item.source_name,
      })
    );
  });
}

function addChannel(data) {
  $("#channel").empty();

  $.each(data, function (i, item) {
    $("#channel").append(
      $("<option>", {
        value: item.channels_id,
        text: item.channels_name,
      })
    );
  });
}

$(document).on("click", ".btnSelectDefaultImage", function () {
  let modal = $("#modalLarge4 .modal-content");
  let button = $(this);
  let setId = button.data("id");
  let setGid = button.data("gid");

  setTimeout(() => {
    let buttonClose = modal.find("button")[0];
    let buttonSave = modal.find("button")[1];
    $(buttonSave).remove();

    $(buttonClose).attr(
      "class",
      "btn btn-outline btn-outline-dashed btn-outline-warning btn-active-light-success fw-bold"
    );

    $(buttonClose).after(
      '<button type="button" class="btn btn-outline btn-outline-dashed btn-outline-info btn-active-light-success fw-bold" data-id="' +
        setId +
        '"  id="updateSelect" data-lookup="2">Select</button>'
    );

    $(buttonClose).after(
      '<button type="button" class="btn btn-outline btn-outline-dashed btn-outline-primary btn-active-light-success fw-bold" data-id="' +
        setId +
        '" data-gid="' +
        setGid +
        '" id="updateMain" data-lookup="3">Select Main</button>'
    );
  }, 100);

  modal.attr(
    "style",
    "border: 3px solid #888;px;width:500px;position: absolute;left: 50%;top: 50%;transform: translate(-50%, -50%);"
  );

  var url = button.data("url");
  var type = button.data("type");
  var fullscreen = button.data("fullscreenmodal");
  var modalID = "#modalLarge4";
  if (type == "modal") {
    var data = [
      { name: "_token", value: getCookie() },
      { name: "type", value: type },
      { name: "default_id", value: setId },
    ];

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
});

$(document).on("click", ".btnViewDefaultImage", function () {
  let modal = $("#modalLarge4 .modal-content");
  // modal.attr(
  //   "style",
  //   "border: 3px solid #888;px;width:500px;position: absolute;left: 50%;top: 50%;transform: translate(-50%, -50%);"
  // );

  setTimeout(() => {
    let buttonSave = modal.find("button")[1];
    $(buttonSave).remove();
  }, 100);

  buttonAction($(this), "#modalLarge4");
});

$(document).on("click", "#btnCloseSelect", function () {
  $("#modalLarge4").modal("hide");
});

function changeKeyIfValueIs3(obj) {
  if (obj.key1 === 3) {
    obj.newKey = obj.key1;
    delete obj.key1;
  }
  return obj;
}

let dataAll = [];
$(document).on("click", "#updateSelect,#updateMain", function () {
  let imageID = $(this).data("id");
  let lookup = $(this).data("lookup");

  if (lookup === 3) {
    $(".statusName[data-imageid='" + imageID + "']").text("Main");
    dataAll.push({ key: 3, value: imageID });
  } else {
    $(".statusName[data-imageid='" + imageID + "']").text("Selected");
    dataAll.push({ key: 2, value: imageID });
  }

  var statusNames = $(".statusName")
    .map(function () {
      return $(this).text();
    })
    .get();

  console.log(statusNames);

  $('.btnCancelSelected[data-id="' + imageID + '"]').prop("disabled", false);

  $("#modalLarge4").modal("hide");
});

$(document).on("click", ".btnCancelSelected", function () {
  let imageID = $(this).data("id");

  $(".statusName[data-imageid='" + imageID + "']").text("Not Selected");
  $('.btnCancelSelected[data-id="' + imageID + '"]').prop("disabled", true);
});

function ajax_crud_group(base_url, column, tableID = "table-data") {
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
        targets: -1,
        title: "Action",
        width: "300px",
        className: "dt-center",
      },
    ],
    rowCallback: function (row, data) {
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

$(document).on("click", ".btnDetail", function () {
  buttonAction($(this), "#modalLarge2");

  let get_data = $(this).data("id");

  data = [
    { name: "_token", value: getCookie() },
    { name: "data_id", value: get_data },
  ];

  $.ajax({
    url: base_url() + "inventory_group/productListResult",
    method: "POST",
    dataType: "JSON",
    async: false,
    data: data,
    success: function (result) {
      var getJsonData = result.data;

      for (let i = 0; i < getJsonData.length; i++) {
        var datas = getJsonData[i];

        let tr_table = ` <tr>`;

        tr_table +=
          `<td style='vertical-align: middle;'>` +
          datas.product_code +
          `</td>
          <td style='vertical-align: middle;'>` +
          datas.product_name +
          `</td>
          <td style='vertical-align: middle;'><div class="badge badge-light-info">` +
          datas.product_size +
          `
          </td>
          <td style='vertical-align: middle;'>` +
          datas.brand_name +
          `</td>
          <td>
          <button type="button" data-id="` +
          datas.product_id +
          `" class="btn btn-success hover-scale btn-sm mr-5" id="btnView" data-type="modal" data-url="` +
          base_url() +
          `inventory_group/viewSKU" data-fullscreenmodal="0">View</button>
            <button type="button" data-brand="` +
          datas.brand_name +
          `" class="btn btn-danger hover-scale btn-sm" data-textconfirm="Are you sure you want to delete this item ?" data-title="Item" id="buttonDeleted">Delete</button>
          </td>
          <input type="hidden" name="product_id[]" value="` +
          datas.product_id +
          `" />
          <input type="hidden" name="detail_id[]" value="` +
          datas.detail_id +
          `" />`;

        tr_table += `</tr>`;

        $("#kt_datatable_vertical_scroll tbody").append(tr_table);
      }
    },
  });
});

function checkImageExists(imageUrl, successCallback, errorCallback) {
  $.ajax({
    url: imageUrl,
    type: "HEAD",
    success: function () {
      successCallback();
    },
    error: function (xhr) {
      if (xhr.status === 404) {
        errorCallback();
      }
    },
  });
}

$(document).on("click", ".btnMedia", function () {
  buttonAction($(this), "#modalLarge2");
  $("#form").attr("id", "parentForm");

  let urlMedia = base_url() + "inventory_group/media_image";
  let get_data = $(this).data("id");
  let pathImage = base_url() + "assets/uploads/";

  var column = [
    {
      data: "image_name",
      render: function (data, type, row, meta) {
        let imageName = row.image_name;

        // checkImageExists(
        //   pathImage + "products_image/" + imageName,
        //   function () {
        //     $(".symbol-label").css({
        //       "background-image":
        //         "url(" + pathImage + "products_image/" + imageName + ")",
        //       border: "1px solid black",
        //     });
        //   },
        //   function () {
        //     $(".symbol-label").css({
        //       "background-image": "url(" + pathImage + "default.png)",
        //       border: "1px solid black",
        //     });
        //   }
        // );

        let div =
          ` <div class="symbol symbol-50px">
              <span class="symbol-label" style="background-image: url(` +
          pathImage +
          `products_image/` +
          imageName +
          `); border: 1px solid black;">
              </span>
            </div>`;

        return div;
      },
    },
    { data: "image_name" },
    {
      data: "id",
      render: function (data, type, row, meta) {
        let badge =
          `<button
          type="button"
          class="btn btn-outline btn-outline-dashed btn-outline-danger btn-sm btnDeleteMedia"
          data-type="confirm"
          data-url=" ` +
          base_url() +
          `inventory_group/delete/` +
          row.id +
          `"
          data-textconfirm="Are you sure you want to delete this item ?"
          data-title="Item"
          data-id="` +
          row.id +
          `">
          Delete
          </button>

          <button type="button" data-id="` +
          row.id +
          `" class="btn btn-outline btn-outline-dashed btn-outline-primary btn-sm btnViewMedia" data-type="modal" data-url="` +
          base_url() +
          `inventory_group/image_media_view/` +
          row.image_name +
          `" data-fullscreenmodal="0">View
          </button>`;

        return badge;
      },
    },
  ];

  tbl = $("#datatable_media").DataTable({
    processing: true,
    oLanguage: {
      sProcessing: "loading...",
    },
    serverSide: true,
    responsive: true,
    orderable: false,
    paging: false,
    searching: false,
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
      url: urlMedia,
      type: "POST",
      data: function () {
        let data = [
          { name: "_token", value: getCookie() },
          { name: "data_id", value: get_data },
        ];

        return data;
      },
    },
    columns: column,
    columnDefs: [
      {
        targets: 0, // Targets the second column (columns are 0-based)
        className: "text-center", // Set the width of the second column header
      },
      // Add more columnDefs as needed for other columns
    ],
    rowCallback: function (row, data, index) {
      $(row).css("vertical-align", "middle");
    },
  });
});

$(document).on("click", "#btnProcessDetail", function () {
  let btnCloseModal = "#btnCloseModalFullscreen";

  var textButton = $(this).text();
  var btn = $(this);
  var url = base_url() + "inventory_group/process_detail";
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
        addDraw();
        $("#table-data").DataTable().ajax.reload();
      }
      loadingButtonOff(btn, textButton);
      enabledButton($(btnCloseModal));

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

$(document).on("click", ".downloadView", function () {
  // let selectField = $(this).parent().parent().parent().find("select");
  // let inputField = $(this).parent().parent().parent().find("input");
  // let statusVal = [];
  // let status = $('input[id="lookup_status"]').filter(":checked");
  // let startDate = $('input[id="start_date"]');
  // let endDate = $('input[id="end_date"]');

  // for (let i = 0; i < status.length; i++) {
  //   statusVal.push($(status[i]).siblings("label").text().trim());
  // }

  window.location.href = base_url() + "inventory_group/download";
  // "?searchName=" +
  // selectField.val() +
  // "&valueSearch=" +
  // inputField.val() +
  // "&valueStatus=" +
  // statusVal +
  // "&startDate=" +
  // startDate.val() +
  // "&endDate=" +
  // endDate.val();
});

$(document).on("click", ".btnViewMedia", function () {
  $("#modalLarge3 .modal-content").attr(
    "style",
    "border: 3px solid #888;px;width:500px;position: absolute;left: 50%;top: 50%;transform: translate(-50%, -50%);"
  );
  buttonAction($(this), "#modalLarge3");
  $("#btnProcessModal").remove();
});

$(document).on("click", "#btnCloseViewImageMedia", function () {
  $("#modalLarge3").modal("hide");
});

$(document).on("click", "#btnAddImage", function () {
  $("#modalLarge3 .modal-content").attr(
    "style",
    "border: 3px solid #888;px;width:500px;position: absolute;left: 50%;top: 50%;transform: translate(-50%, -50%);"
  );
  buttonAction($(this), "#modalLarge3");
});

$(document).on("click", ".massUpload", function () {
  buttonAction($(this), "#modalLarge2");
});

$(document).on("click", ".btnDeleteMedia", function () {
  var url = $(this).data("url");

  Swal.fire({
    title: "Delete Group Media Image",
    text: "Are you sure you want to delete this item ?",
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
      var data = [{ name: "_token", value: getCookie() }];

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
          $("#datatable_media").DataTable().ajax.reload();
          Swal.fire("", response.text, response.success ? "success" : "error");
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

$(document).on("click", "#btnProcessMedia", function () {
  let btnCloseModal = "#btnCloseViewImageMedia";
  let btn = $(this);
  let urlMedia = $("#form").data("url");
  let formData = document.getElementById("form");
  let formDataObject = new FormData(formData);
  formDataObject.append("_token", getCookie());
  $.ajax({
    url: urlMedia,
    method: "POST",
    mimeType: "multipart/form-data",
    dataType: "JSON",
    contentType: false,
    processData: false,
    cache: false,
    async: false,
    data: formDataObject,
    // beforeSend: function () {
    //   loadingButton(btn);
    //   disabledButton($(btnCloseModal));
    // },
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
        $("#datatable_media").DataTable().ajax.reload();
      }
      // loadingButtonOff(btn, textButton);
      // enabledButton($(btnCloseModal));

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
