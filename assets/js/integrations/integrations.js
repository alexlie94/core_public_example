$(document).ready(function () {
  $(document).on("click", "#btnCloseModal", function () {
    modalAutoClose($(this));
  });

  $(document).on("click", "#btnManage", function () {
    buttonAction($(this), "#modalLarge2");
  });

  $(document).on("click", "#connect", function () {
    var attr_data = $(this).attr("data-id");
    var parse_data = JSON.parse(attr_data);
    console.log(JSON.parse(attr_data));
    var formData = new FormData();
    formData.append("_token", getCookie());
    for (var key in parse_data) {
      if (parse_data.hasOwnProperty(key)) {
        formData.append(key, parse_data[key]);
      }
    }

    $.ajax({
      url: base_url() + "integrations/connect",
      type: "POST",
      data: formData,
      dataType: "JSON",
      contentType: false,
      processData: false,
      cache: false,
      success: function (data) {
        if (data.success === true) {
          message(true, data.message);
          window.open(data.url, "_blank");
        } else {
          message(false, data.message);
        }
      },
    });
  });

  $(document).on("click", ".checkedStatus", function () {
    var value = $(this).val();
    var id = $(this).attr("data-id");

    var data = [];
    data.push({ name: "_token", value: getCookie() });
    data.push({ name: "value", value: value });
    data.push({ name: "id", value: id });
    $.ajax({
      url: base_url() + "integrations/change_status",
      type: "POST",
      async: false,
      data: $.param(data),
      dataType: "JSON",
      success: function (data) {
        if (data.success === true) {
          message(true, "Success change status API");
        } else {
          message(false, "Please contact support");
        }
      },
      error: function (xhr, status, error) {
        message(false, error);
      },
    });
  });
});
