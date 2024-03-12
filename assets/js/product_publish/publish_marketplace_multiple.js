$(function () {
  "use strict";
  var initialActiveTabIndex = $(".tab-pane.active");
  var SourceActive = $(initialActiveTabIndex).attr("id");
  form_marketplace(SourceActive);
  $("li.nav-item").on("click", function (i, x) {
    var dataSource = $(this).find("a");
    $("a.popovers").popover("hide");
    SourceActive = $(dataSource).attr("data-source");
    form_marketplace(SourceActive);
  });
});

function form_marketplace(SourceActive) {
  var urlParams = new URLSearchParams(window.location.search);
  var params = urlParams.get("params");
  let data = [];
  $("#form_publish_" + SourceActive).html("");
  data.push({ name: "_token", value: getCookie() });
  data.push({ name: "params", value: params });
  data.push({ name: "source", value: SourceActive });
  $.ajax({
    url: base_url() + "product_publish/get_view_form",
    method: "POST",
    dataType: "JSON",
    data: $.param(data),
    async: false,
    success: function (response) {
      $("#form_publish_" + SourceActive).html(response.v_marketplace);
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
