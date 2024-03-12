$(document).ready(function () {
  $.fn.modal.Constructor.prototype._initializeFocusTrap = () => ({
    activate: () => {},
    deactivate: () => {},
  });

  $(document).on("click", ".downloadView", function () {
    let selectField = $(this).parent().parent().parent().find("select");
    let inputField = $(this).parent().parent().parent().find("input");
    let statusVal = [];
    let status = $('input[id="lookup_status"]').filter(":checked");

    for (let i = 0; i < status.length; i++) {
      statusVal.push($(status[i]).siblings("label").text().trim());
      statusVal.push($(status[i]).val());
    }

    window.location.href =
      base_url() +
      "product_image/downloadView" +
      "?searchName=" +
      selectField.val() +
      "&valueSearch=" +
      inputField.val() +
      "&valueStatus=" +
      statusVal;
  });

  $("#start_date").flatpickr({
    enableTime: false,
    dateFormat: "Y-m-d",
  });

  $("#end_date").flatpickr({
    enableTime: false,
    dateFormat: "Y-m-d",
  });

  $(document).on("change", "select", function (e) {
    $(this)
      .next()
      .next(".fv-plugins-message-container.invalid-feedback")
      .html("");
  });
  $(document).on("change", ":input", function () {
    $(this).next(".fv-plugins-message-container.invalid-feedback").html("");
  });

  var baseurl = base_url() + "product_image/show";
  var column = [
    { data: "id" },
    { data: "id" },
    { data: "product_name" },
    {
      data: "product_price",
      render: function (data) {
        return format_number_to_idr(data);
      },
    },
    {
      data: "product_sale_price",
      render: function (data) {
        return format_number_to_idr(data);
      },
    },
    {
      data: "product_size",
      render: function (data) {
        if (data === null || data === "") {
          return '<div class="badge badge-light-danger">no size</div>';
        }
        var sizes = data.split(",");
        var badges = sizes.map(function (size) {
          return '<div class="badge badge-light-info">' + size + "</div>";
        });

        return badges.join("");
      },
    },
    { data: "brand_name" },
    {
      data: "status",
      render: function (data) {
        switch (data) {
          case "1":
            var span = "badge badge-light-dark";
            var status = "New";
            break;

          case "2":
            var span = "badge badge-light-success";
            var status = "Launching";
            break;

          case "3":
            var span = "badge badge-light-primary";
            var status = "Incoming";
            break;

          case "4":
            var span = "badge badge-light-warning";
            var status = "Pending";
            break;

          case "5":
            var span = "badge badge-light-info";
            var status = "Actived";
            break;

          case "6":
            var span = "badge badge-light-success";
            var status = "Launched";
            break;
        }
        return '<div class="' + span + '">' + status + "</div>";
      },
    },
    { data: "action", width: "20%" },
  ];

  ajax_crud_table(baseurl, column);
  // sweetAlertConfirm();
  libraryInput();

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
            // reloadDatatables();
            Swal.fire(
              "",
              response.text,
              response.success ? "success" : "error"
            );
            tableImageList();
            table_img.ajax.reload();
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

  // MODAL EDIT
  $(document).on("click", ".btnEdit", function () {
    buttonAction($(this), "#modalLarge2");
    setLocalStorageProduct($(this).attr("data-id"));
  });

  $(document).on("click", "#btnEditProduct", function () {
    editProduct();
  });
  // END

  // MODAL VARIANT
  $(document).on("click", ".btnList", function () {
    buttonAction($(this), "#modalLarge2");
    tableVariantList($(this).attr("data-id"));
    setLocalStorageProduct($(this).attr("data-id"));
  });

  $(document).on("click", "#btnCloseModal", function () {
    modalAutoClose($(this));
  });
  // END MODAL VARIANT

  // MODAL VIEW PRODUCT
  $(document).on("click", ".btnView", function () {
    buttonAction($(this), "#modalLarge2");
  });
  // END

  // MODAL IMAGE
  $(document).on("click", ".addImage", function () {
    buttonAction($(this), "#modalLarge3");
    setLocalStorageVariantGroup($(this).attr("data-id"));
    showFormListImage();
  });

  $(document).on("click", ".editVariant", function () {
    buttonAction($(this), "#modalLarge3");
    setLocalStorageVariantGroup($(this).attr("data-id"));
    // showFormListImage();
  });

  // MODAL SIZE
  $(document).on("click", ".changeSize", function () {
    buttonAction($(this), "#modalLarge3");
    setLocalStorageVariantGroup($(this).attr("data-id"));
    showFormListSize();
  });

  $(document).on("click", ".btnEditSize", function () {
    var data = $(this).attr("data-url");
    var val = $(this).attr("data-val");
    editSize(data, val);
  });

  $(document).on("click", "#btnCloseModalAddImage", function () {
    // modalAutoClose($(this));
    showFormListImageManual();
  });

  $(document).on("click", "#btnCloseModal", function () {
    modalAutoClose($(this));
  });

  // END MODAL IMAGE

  // MODAL VIEW IMAGE
  $(document).on("click", ".btnViewImage", function () {
    var image = $(this).attr("data-id");

    // Create a new Image object
    const img = new Image();

    // Set the source URL for the image
    img.src = image;

    // Handle the image load event
    $(img).on("load", function () {
      // Image is loaded, you can now access its width and height
      const imageWidth = this.width;
      const imageHeight = this.height;

      Swal.fire({
        imageUrl: image,
        imageWidth: "auto",
        imageHeight: null,
        imageAlt: "images",
        confirmButtonText: "Close",
        allowOutsideClick: false,
      });
    });
  });

  // ADD VARIANT
  $(document).on("click", "#btnAddVariant", function () {
    buttonAction($(this), "#modalLarge3");
    var data = getLocalStorageProduct();

    var tbody = $("#kt_datatable_add_variant tbody");
    var row = $("<tr>");

    var productIdCell = $("<td>").text(data.product_id);
    var productNameCell = $("<td>").text(data.product_name);

    row.append(productIdCell);
    row.append(productNameCell);

    tbody.append(row);
  });

  $(document).on("click", "#btnCloseAddVariant", function () {
    modalAutoClose($(this));
  });

  $(document).on("click", "#btnAddNewVariant", function () {
    insertData();
  });
  // END ADD VARIANT

  // EDIT VARIANT
  $(document).on("click", "#btnProcessEditVariant", function () {
    editDataVariant();
  });
  // END EDIT VARIANT

  // select general color
  $(document).on("change", "#general_color", function () {
    var parentId = $(this).val();
    getVariantColor(parentId);
  });
  // end

  // category
  $(document).on("change", "#category", function () {
    var parentId = $(this).val();

    $.ajax({
      url: base_url() + "product_image/get_category_by_parent/" + parentId,
      type: "GET",
      dataType: "json",
      success: function (data) {
        var options = '<option value="">Select Option</option>';

        $("#category_2").html(options);

        $.each(data, function (index, val) {
          options +=
            '<option value="' +
            val.id +
            '">' +
            val.categories_name +
            "</option>";
        });
        $("#category_1").html(options);
      },
    });
  });

  $(document).on("change", "#category_1", function () {
    var parentId = $(this).val();
    $.ajax({
      url: base_url() + "product_image/get_category_by_parent/" + parentId,
      type: "GET",
      dataType: "json",
      success: function (data) {
        var options = '<option value="">Select Option</option>';
        $.each(data, function (index, val) {
          options +=
            '<option value="' +
            val.id +
            '">' +
            val.categories_name +
            "</option>";
        });
        $("#category_2").html(options);
      },
    });
  });

  // end

  // management type
  $(document).on("change", "#management_type_1", function () {
    var parentId = $(this).val();

    $.ajax({
      url:
        base_url() + "product_image/get_management_type_by_parent/" + parentId,
      type: "GET",
      dataType: "json",
      success: function (data) {
        var options = '<option value="">Select Option</option>';

        $("#management_type_3").html(options);

        $.each(data, function (index, val) {
          options +=
            '<option value="' +
            val.id +
            '">' +
            val.management_type_name +
            "</option>";
        });
        $("#management_type_2").html(options);
      },
    });
  });

  $(document).on("change", "#management_type_2", function () {
    var parentId = $(this).val();
    $.ajax({
      url:
        base_url() + "product_image/get_management_type_by_parent/" + parentId,
      type: "GET",
      dataType: "json",
      success: function (data) {
        var options = '<option value="">Select Option</option>';
        $.each(data, function (index, val) {
          options +=
            '<option value="' +
            val.id +
            '">' +
            val.management_type_name +
            "</option>";
        });
        $("#management_type_3").html(options);
      },
    });
  });

  // end

  // variant size
  $(document).on("click", ".variantSize", function () {
    var parentId = $(this).attr("data-id");
    console.log(parentId);
  });

  //checkbox checked
  $(document).on("change", "#custom_variant_color", function () {
    if ($("#custom_variant_color").is(":checked")) {
      $("#custom_variant_color_name").removeAttr("disabled");
      $("#custom_variant_color_code").removeAttr("disabled");
    } else {
      $("#custom_variant_color_name").attr("disabled", true);
      $("#custom_variant_color_code").attr("disabled", true);
    }
  });

  $(document).on("click", "#btnAddNewVariant", function () {});

  processNestedFields("#btnCloseModalFullscreen");

  var imageData = [];
  function dropZoneArea(name, imageName) {
    var funcDropZone = new Dropzone(name, {
      url: base_url() + "product_image/upload",
      paramName: "fileImage",
      maxFiles: 10,
      maxFilesize: 10,
      addRemoveLinks: true,
      acceptedFiles: "image/jpeg,image/png,image/jpg",
      sending: function (file, xhr, formData) {
        formData.append("_token", getCookie());
      },
      init: function () {
        var dropzone = this;
        this.on("success", function (file, response) {
          $(imageName).val("");
          imageData.push(file.dataURL);

          var getChange = JSON.stringify(imageData);
          $(imageName).val(getChange);
        });
      },
    });

    return funcDropZone;
  }

  $(document).on("click", "#btnAddImage", function () {
    buttonAction($(this), "#modalLarge3");

    dropZoneArea("#kt_ecommerce_add_product_media_1", "#imageChild_1");
  });

  $(document).on("click", "#btnUploadImage", function () {
    var textButton = $(this).text();
    var btn = $(this);
    var url = $("#form").data("url");
    var arType = [];
    var dataProduct = getLocalStorageVariantGroup();
    // $("img.upload-img").each(function (i, x) {
    //   arType.push(x.src);
    // });

    var data = $("#form").serializeArray(); // convert form to array
    data.push({ name: "_token", value: getCookie() });
    data.push({ name: "img", value: JSON.stringify(imageData) });
    data.push({ name: "product_id", value: dataProduct.product_id });
    data.push({ name: "product_name", value: dataProduct.product_name });
    data.push({
      name: "variant_color_id",
      value: dataProduct.variant_color_id,
    });
    data.push({
      name: "general_color_id",
      value: dataProduct.general_color_id,
    });
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
        imageData = [];
        if (!response.success) {
          loadingButtonOff(btn, textButton);
          enabledButton($(btnCloseModal));
          message(false, response.message);
        } else {
          // $("#modalLarge3").modal("hide");
          showFormListImageManual();

          // tbody.append(row);
          loadingButtonOff(btn, textButton);
          enabledButton($(btnCloseModal));
          message(true, response.message);
        }
      },
      error: function (jqXHR, textStatus, errorThrown) {
        switch (jqXHR.status) {
          case 401:
            sweetAlertMessageWithConfirmNotShowCancelButton(
              "Your session has expired or invalid. Please relogin",
              function () {
                // window.location.href = base_url();
              }
            );
            break;

          default:
            sweetAlertMessageWithConfirmNotShowCancelButton(
              "We are sorry, but you do not have access to this service",
              function () {
                // location.reload();
              }
            );
            break;
        }
      },
    });
  });

  $(document).on("click", "#btnCloseModalFullscreen", function () {
    $("#modalLarge3").modal("hide");
  });
});

function getVariantColor(parentId) {
  $.ajax({
    url: base_url() + "product_image/get_variant_color_by_parent/" + parentId,
    type: "GET",
    dataType: "json",
    success: function (data) {
      var options = '<option value="">Select Option</option>';
      $.each(data, function (index, val) {
        options +=
          '<option value="' + val.id + '">' + val.color_name + "</option>";
      });
      $("#variant_color").html(options);
    },
  });
}

function tableVariantList(id) {
  $("#kt_datatable_variant_list").DataTable({
    ajax: {
      url: base_url() + "product_image/list_variant",
      type: "POST",
      data: function () {
        var data = [];
        data.push({ name: "_token", value: getCookie() });
        data.push({
          name: "id",
          value: id,
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
      { data: "no" },
      { data: "product_id" },
      { data: "product_name" },
      { data: "general_color" },
      { data: "variant_color" },
      {
        data: "variant_color_hexa",
        render: function (data) {
          return (
            `<div class="symbol symbol-35px"><span class="symbol-label" style="background-color:#` +
            data +
            `;"></span></div>`
          );
        },
      },
      {
        data: null,
        render: function (data, type, row) {
          // console.log(row);
          var html = "";
          html +=
            "<button type='button'  class='btn btn-sm btn-light-primary font-weight-bold mr-2 editVariant' data-title='Item' data-type='modal' data-url='" +
            base_url() +
            "product_image/edit_variant/" +
            row.product_id +
            "/" +
            row.general_color_id +
            "/" +
            row.variant_color_id +
            "' >Edit</button>";
          html += "  ";
          html +=
            "<button type='button'  class='btn btn-sm btn-light-success font-weight-bold mr-2 addImage' data-id='" +
            JSON.stringify(data) +
            "' data-title='Item' data-type='modal' data-url='" +
            base_url() +
            "product_image/list_image' >Image</button>";
          html += "  ";
          html +=
            "<button type='button'  class='btn btn-sm btn-light-dark font-weight-bold mr-2 changeSize' data-id='" +
            JSON.stringify(data) +
            "' data-title='Item' data-type='modal' data-url='" +
            base_url() +
            "product_image/list_size' >Size</button>";

          return html;
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
}

function tableImageList() {
  var variantGroup = getLocalStorageVariantGroup();
  var table_img = $("#kt_datatable_image_list").DataTable({
    ajax: {
      url: base_url() + "product_image/image_list_table",
      type: "POST",
      data: function () {
        var data = [];
        data.push({ name: "_token", value: getCookie() });
        data.push(
          {
            name: "product_id",
            value: variantGroup.product_id,
          },
          {
            name: "general_color_id",
            value: variantGroup.general_color_id,
          },
          {
            name: "variant_color_id",
            value: variantGroup.variant_color_id,
          }
        );
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
      {
        data: null,
        render: function (data, type, row) {
          // console.log(row);
          var html = "";
          html += '<img src="' + row.image_file + '" width="70">';

          return html;
        },
      },
      { data: "image_name" },
      {
        data: null,
        render: function (data, type, row) {
          // console.log(row);
          var html = "";
          html +=
            '<button type="button"  class="btn btn-sm btn-light-danger font-weight-bold mr-2 btnDelete" data-id="' +
            row.id +
            '" data-type="confirm" data-url="' +
            base_url() +
            "/product_image/delete/" +
            row.id +
            '" data-textconfirm="Are you sure you want to delete this item ?" data-title="' +
            row.image_name +
            '">Delete</button>';
          html += "  ";
          html +=
            '<button type="button"  class="btn btn-sm btn-light-info font-weight-bold mr-2 btnViewImage" data-id="' +
            row.image_file +
            '">View</button>';

          return html;
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
}

function tableSizeList() {
  var variantGroup = getLocalStorageVariantGroup();
  var table_img = $("#kt_datatable_size_list").DataTable({
    ajax: {
      url: base_url() + "product_image/size_list_table",
      type: "POST",
      data: function () {
        var data = [];
        data.push({ name: "_token", value: getCookie() });
        data.push(
          {
            name: "product_id",
            value: variantGroup.product_id,
          },
          {
            name: "general_color_id",
            value: variantGroup.general_color_id,
          },
          {
            name: "variant_color_id",
            value: variantGroup.variant_color_id,
          }
        );
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
      { data: "no" },
      { data: "sku" },
      { data: "product_size" },
      {
        data: null,
        render: function (data, type, row) {
          // console.log(row);
          var html = "";
          html +=
            '<button type="button"  class="btn btn-sm btn-light-dark font-weight-bold mr-2 btnEditSize" data-url="' +
            base_url() +
            "/product_image/edit_size/" +
            row.id +
            '" data-val="' +
            row.product_size +
            '">Edit</button>';

          return html;
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
}

function setLocalStorageProduct(productID) {
  $.ajax({
    url: base_url() + "product_image/get_local_storage_product/" + productID,
    method: "GET",
    dataType: "json",
    data: function () {
      var data = [];
      data.push({ name: "_token", value: getCookie() });
      return data;
    },
    success: function (data) {
      localStorage.setItem("product", JSON.stringify(data));
    },
    error: function (xhr, status, error) {
      console.error("Terjadi kesalahan: " + error);
    },
  });
}

function getLocalStorageProduct() {
  return JSON.parse(localStorage.getItem("product"));
}

function setLocalStorageVariantGroup(data) {
  localStorage.setItem("variantGroup", data);
}

function getLocalStorageVariantGroup() {
  return JSON.parse(localStorage.getItem("variantGroup"));
}

function insertData() {
  var formData = new FormData();
  var formArray = {
    general_color:
      $("#general_color").val() == null ? "" : $("#general_color").val(),
    variant_color:
      $("#variant_color").val() == null ? "" : $("#variant_color").val(),
    size: $("#size").val(),
    custom_variant_color: $("#custom_variant_color").prop("checked"),
    custom_variant_color_name: $("#custom_variant_color_name").val(),
    custom_variant_color_code: $("#custom_variant_color_code").val(),
  };
  // var serializedArray = $("#form").serializeArray();
  // console.log(serializedArray);
  for (var key in formArray) {
    if (formArray.hasOwnProperty(key)) {
      formData.append(key, formArray[key]);
    }
  }

  // formData.append("data", JSON.stringify(formArray));
  formData.append("_token", getCookie());
  formData.append("product_id", getLocalStorageProduct().product_id);
  $.ajax({
    url: base_url() + "product_image/insert_data",
    type: "POST",
    data: formData,
    dataType: "JSON",
    contentType: false,
    processData: false,
    cache: false,
    success: function (response) {
      if (response.success === false) {
        if (response.sku_error === true) {
          message(false, response.message);
          return;
        }
        $(".fv-plugins-message-container").remove();

        $.each(response.message, function (fieldName, errorMsg) {
          var errorText = $(errorMsg).text();
          var errorDiv = $(
            '<div class="fv-plugins-message-container invalid-feedback">'
          );
          errorDiv.text(errorText);
          var fieldElement = $("#" + fieldName);
          if (fieldElement.hasClass("form-select")) {
            fieldElement.next().after(errorDiv);
          } else {
            fieldElement.after(errorDiv);
          }
        });
      } else {
        reloadDatatables();
        message(true, response.message);
        $("#modalLarge3").modal("hide");
        tableVariantList(getLocalStorageProduct().product_id);
      }
    },
    error: function (xhr, status, error) {
      message(false, error);
    },
  });
}

function editProduct() {
  var formData = new FormData();
  var serializedArray = $("#formEditProduct").serializeArray();

  serializedArray.forEach(function (input) {
    formData.append(input.name, input.value);
  });

  formData.append("_token", getCookie());
  formData.append("product_id", getLocalStorageProduct().product_id);
  $.ajax({
    url: base_url() + "product_image/edit_product",
    type: "POST",
    data: formData,
    dataType: "JSON",
    contentType: false,
    processData: false,
    cache: false,
    success: function (response) {
      if (response.success === false) {
        $(".fv-plugins-message-container").remove();
        message(false, "There is input that must be filled");
        $.each(response.message, function (fieldName, errorMsg) {
          var errorText = $(errorMsg).text();
          var errorDiv = $(
            '<div class="fv-plugins-message-container invalid-feedback">'
          );
          errorDiv.text(errorText);
          var fieldElement = $("#" + fieldName);
          if (fieldElement.hasClass("form-select")) {
            fieldElement.next().after(errorDiv);
          } else {
            fieldElement.after(errorDiv);
          }
        });
      } else {
        reloadDatatables();
        $("#modalLarge2").modal("hide");
        message(true, response.message);
      }
    },
    error: function (xhr, status, error) {
      message(false, error);
    },
  });
}

function showFormListImage() {
  tableImageList();

  var data = getLocalStorageVariantGroup();

  var tbody = $("#kt_datatable_list_product_image tbody");
  var row = $("<tr>");

  var a = $("<td>").text(data.product_id);
  var b = $("<td>").text(data.product_name);
  var c = $("<td>").text(data.general_color);
  var d = $("<td>").text(data.variant_color);

  row.append(a);
  row.append(b);
  row.append(c);
  row.append(d);

  tbody.append(row);
}

function showFormListSize() {
  tableSizeList();

  var data = getLocalStorageVariantGroup();

  var tbody = $("#kt_datatable_list_product_image tbody");
  var row = $("<tr>");

  var a = $("<td>").text(data.product_id);
  var b = $("<td>").text(data.product_name);
  var c = $("<td>").text(data.general_color);
  var d = $("<td>").text(data.variant_color);

  row.append(a);
  row.append(b);
  row.append(c);
  row.append(d);

  tbody.append(row);
}

function showFormListImageManual() {
  var myButton = $("<button>");
  myButton.data("url", base_url() + "product_image/list_image");
  myButton.data("type", "modal");
  myButton.data("fullscreenmodal", false);

  buttonAction(myButton, "#modalLarge3");
  showFormListImage();
}

function showFormListSizeManual() {
  var myButton = $("<button>");
  myButton.data("url", base_url() + "product_image/list_size");
  myButton.data("type", "modal");
  myButton.data("fullscreenmodal", false);

  buttonAction(myButton, "#modalLarge3");
  showFormListSize();
}

function editSize(url, val) {
  var dataProd = getLocalStorageVariantGroup();
  Swal.fire({
    title: "Edit Size",
    input: "text",
    inputPlaceholder: "Enter something...",
    inputValue: val,
    inputAttributes: {
      required: "true",
    },
    showCancelButton: true,
    confirmButtonColor: "#0CC27E",
    cancelButtonColor: "#FF586B",
    confirmButtonText: "Edit",
    cancelButtonText: "No, cancel!",
    confirmButtonClass: "btn btn-success mr-5",
    cancelButtonClass: "btn btn-danger",
    buttonsStyling: false,
    allowOutsideClick: false,
    preConfirm: (resultValue) => {
      if (!resultValue) {
        return false;
      }
      return resultValue;
    },
    inputValidator: (value) => {
      if (!value) {
        return "Please enter a size";
      }
    },
  }).then((result) => {
    if (result.isConfirmed) {
      const value = result.value;
      var data = [];
      data.push({ name: "_token", value: getCookie() });
      data.push({ name: "value", value: value });
      data.push({ name: "product_id", value: dataProd.product_id });
      data.push({ name: "variant_color_id", value: dataProd.variant_color_id });
      data.push({ name: "general_color_id", value: dataProd.general_color_id });
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
          Swal.fire(
            "",
            response.message,
            response.success ? "success" : "error"
          );

          showFormListSizeManual();
          reloadDatatables();
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

// FUNCTION EDIT VARIANT
function editDataVariant() {
  var formData = new FormData();
  var serializedArray = $("#formEditProduct").serializeArray();

  serializedArray.forEach(function (input) {
    formData.append(input.name, input.value);
  });

  formData.append("_token", getCookie());
  $.ajax({
    url: base_url() + "product_image/process_edit_variant",
    type: "POST",
    data: formData,
    dataType: "JSON",
    contentType: false,
    processData: false,
    cache: false,
    success: function (response) {
      if (response.success === false) {
        if (response.sku_error === true) {
          message(false, response.message);
          return;
        }
        $(".fv-plugins-message-container").remove();

        $.each(response.message, function (fieldName, errorMsg) {
          var errorText = $(errorMsg).text();
          var errorDiv = $(
            '<div class="fv-plugins-message-container invalid-feedback">'
          );
          errorDiv.text(errorText);
          var fieldElement = $("#" + fieldName);
          if (fieldElement.hasClass("form-select")) {
            fieldElement.next().after(errorDiv);
          } else {
            fieldElement.after(errorDiv);
          }
        });
      } else {
        reloadDatatables();
        message(true, response.message);
        $("#modalLarge3").modal("hide");
        tableVariantList(getLocalStorageProduct().product_id);
      }
    },
    error: function (xhr, status, error) {
      message(false, error);
    },
  });
}

// END
