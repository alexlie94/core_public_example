let default_channel = $(".tabChannel").attr("data-id");
let channel = default_channel;
let channel_status = $("#channelStatus");

$(document).ready(function () {
  channel = $(".tabChannel").attr("data-id");
  channel_status = $("#channelStatus");
  // load first order
  getChannel($(".tabChannel"));

  //start CreateShipment
  $(document).on("click", ".btnCreateShipment", function () {
    buttonAction($(this), "#modalLarge3");
    populateTimeslots();
  });
  $(document).on("click", "#btnCloseCreateShipment", function () {
    modalAutoClose($(this));
  });

  $(document).on("click", "#address_id", function () {
    populateTimeslots();
  });

  $(document).on("click", "#btnAddCreateShipment", function () {
    createShipment();
  });

  // end CreateShipment

  // filter
  var enableFilter = true; // Variabel yang mengontrol apakah event akan dijalankan atau tidak

  $(document).on("change", "#filter_select", function () {
    if (enableFilter) {
      filter();
    }
  });

  var typingTimer;
  var doneTypingInterval = 500;
  $(document).on("keyup", "#filter_input", function () {
    if (enableFilter) {
      clearTimeout(typingTimer);
      typingTimer = setTimeout(filter, doneTypingInterval);
    }
  });

  $(document).on("change", "#filter_order_by", function () {
    if (enableFilter) {
      filter();
    }
  });

  $(document).on("change", "#filter_date", function () {
    if (enableFilter) {
      filter();
    }
  });

  $(document).on("change", "#filter_limit", function () {
    if (enableFilter) {
      default_limit = $(this).val();
      filter();
    }
  });
  // filter
});

function getChannel(data) {
  var channel_id = $(data).attr("data-id");
  channel = channel_id;
  var url = base_url() + "sales_orders/get_data_status_order/" + channel_id;
  $.ajax({
    url: url,
    type: "GET",
    dataType: "html",
    success: function (data) {
      $(".content").html(data);
      // filter();
    },
    error: function (xhr, status, error) {
      console.error(error);
    },
  });
}

function switch_status(order_status_id, channel_id) {
  var order_status = $(order_status_id).attr("data-id");
  channel = channel_id;
  channel_status.val(order_status);

  filter();
}

let default_limit = 5;
let default_offset = 0;
let is_paging = false;
let limit_order = default_limit;
let offset_order = default_offset;

// filter
function filter() {
  var filter_data = {
    selected: $("#filter_select").val(),
    input: $("#filter_input").val(),
    order_by: $("#filter_order_by").val(),
    date_range: $("#filter_date").val(),
  };
  var filter_arr = JSON.stringify(filter_data);

  var channel_id = channel;
  var order_status = channel_status.val();

  if (order_status === "") {
    order_status = 88;
  }

  if (!is_paging) {
    limit_order = default_limit;
    offset_order = default_offset;
  }

  getOrder(channel_id, order_status, limit_order, offset_order, filter_arr);
}

function reset() {
  enableFilter = false;
  default_date_filter();
  $("#filter_input").val("");
  $("#filter_order_by").val("last_updated");
  $("#filter_select").val("local_order_id");
  $("#filter_limit").val(5);
  default_limit = 5;
  $(".contentOrder").html(loading());
  setTimeout(function () {
    filter();
  }, 500);

  enableFilter = true;
}
// end filter
function getOrder(channel_id, order_status, limit, offset, filter) {
  is_paging = false;
  $(".contentOrder").html(loading());

  var url = base_url() + "sales_orders/get_data_order";

  var formData = new FormData();
  formData.append("_token", getCookie());
  formData.append("status", order_status);
  formData.append("channel_id", channel_id);
  formData.append("limit", limit);
  formData.append("offset", offset);
  formData.append("filter", filter);
  $.ajax({
    url: url,
    type: "POST",
    data: formData,
    dataType: "html",
    contentType: false,
    processData: false,
    cache: false,
    success: function (data) {
      if (data.length !== 0) {
        var notFound = "";
        $(".pagination").removeAttr("style");
      } else {
        $(".pagination").attr("style", "display:none");
        var notFound =
          '<div class="card-body pt-0 pb-0 d-flex align-items-center justify-content-center" style="min-height: 80px;"><span class="text-muted">The list does not contain any orders at the moment.</span></div>';
      }

      $(".contentOrder").html(data);
      $(".orderNotFound").html(notFound);
      $(".seeMore").each(function () {
        var dataValue = $(this).data("value");

        if (dataValue <= 2) {
          $(this).hide();
        }

        addPaginationControls(limit, offset);
      });
    },
    error: function (xhr, status, error) {
      message(false, error);
    },
  });
}

function floatMenu(data) {
  if ($("body").attr("data-kt-app-sidebar-secondary-collapse") === "on") {
    $("#floatingToolbar").attr("style", "widht:50%");
  } else {
    $("#floatingToolbar").attr("style", "widht:10%");
  }
  if ($(data).is(":checked")) {
    $("#floatingToolbar").slideDown();
  } else {
    $("#floatingToolbar").slideUp();
  }
}

function toggleSeeMore(id) {
  var $elements = $(".detailOrder" + id);

  if ($elements.length > 2) {
    var $icon = $("#see-more i");

    if ($elements.slice(2).is(":visible")) {
      $elements.slice(2).slideUp(400, function () {
        $icon.removeClass("bi-arrow-up-short").addClass("bi-arrow-down-short");
      });
    } else {
      $elements.slice(2).slideDown(400, function () {
        $icon.removeClass("bi-arrow-down-short").addClass("bi-arrow-up-short");
      });
    }
  }
}

function addPaginationControls(limit, offset) {
  const currentPage = offset / limit + 1;

  const totalOrders = $("#countOrder").val();
  const totalPages = Math.ceil(totalOrders / limit);

  const paginationHtml = `
	<div class="container py-5">
  <nav aria-label="Page navigation">
    <ul class="pagination">
      <li class="page-item">
        <button class="page-link prevPage" onclick="goToPage(${
          currentPage - 1
        }, ${totalPages})" aria-label="Previous">
          <span aria-hidden="true">&laquo;</span>
          <span class="sr-only">Previous</span>
        </button>
      </li>
      <li class="page-item"><span class="page-link">Page ${currentPage} of ${totalPages}</span></li>
      <li class="page-item">
        <button class="page-link nextPage" onclick="goToPage(${
          currentPage + 1
        }, ${totalPages})" aria-label="Next">
          <span aria-hidden="true">&raquo;</span>
          <span class="sr-only">Next</span>
        </button>
      </li>
    </ul>
  </nav>
</div>
  `;

  $(".pagination").html(paginationHtml);

  if (currentPage === 1) {
    $(".prevPage").prop("disabled", true);
  }

  if (currentPage === totalPages) {
    $(".nextPage").prop("disabled", true);
  }
}

function goToPage(pageNumber, totalPages) {
  if (pageNumber < 1 || pageNumber > totalPages) {
    return;
  }

  offset_order = (pageNumber - 1) * limit_order;
  is_paging = true;
  filter();
}

// CreateShipment Function
function createShipment() {
  // var channel_id = $("#channel_id").val();
  var url = base_url() + "sales_orders/add_create_shipment";

  var formData = new FormData();
  formData.append("_token", getCookie());
  formData.append("address_id", $("#address_id").val());
  formData.append("time_id", $("#time_id").val());
  formData.append("local_order_id", $("#local_order_id").val());
  $.ajax({
    url: url,
    type: "POST",
    data: formData,
    dataType: "JSON",
    contentType: false,
    processData: false,
    cache: false,
    success: function (data) {
      if (data.status == true) {
        $("#modalLarge3").modal("hide");
        filter();
        message(true, data.msg);
      } else {
        message(false, data.msg);
      }
    },
    error: function (xhr, status, error) {
      message(false, error);
    },
  });
}

function populateTimeslots() {
  var address_id = $("#address_id").val();
  var time_id = $("#time_id");
  time_id.empty();

  var pickupInfo = $("#json_pickup").val();
  var pickupList = JSON.parse(pickupInfo);
  var address_id = $("#address_id").val();

  var selectedAddress = pickupList.address_list.find(
    (address) => address.address_id == address_id
  );

  if (selectedAddress) {
    var timeSlots = selectedAddress.time_slot_list;
    timeSlots.forEach(function (timeSlot) {
      time_id.append(
        $("<option>", {
          value: timeSlot.pickup_time_id,
          text:
            moment.unix(timeSlot.date).format("DD MMMM YYYY") +
            " - " +
            timeSlot.time_text,
        })
      );
    });
  }
}
// End CreateShipment Function

function default_date_filter() {
  $("#filter_date").daterangepicker({
    startDate: moment().startOf("hour").add(-7, "days"),
    endDate: moment().startOf("hour"),
    locale: {
      format: "DD MMM, YYYY",
    },
  });
}

// function printLabel(e) {
//   var url = base_url() + "sales_orders/print_label";
//   var local_order_id = $(e).attr("data-id");
//   var formData = new FormData();
//   formData.append("_token", getCookie());
//   formData.append("local_order_id", local_order_id);

//   toastr.info("Shipping label data is being processed", "Please wait", {
//     progressBar: !0,
//     timeOut: 2000,
//   });

//   $.ajax({
//     url: url,
//     type: "POST",
//     data: formData,
//     contentType: false,
//     processData: false,
//     cache: false,
//     success: function () {
//       message(true, "Shipping label has been processed");
//       // message(false, data.msg);
//     },
//     error: function (xhr, status, error) {
//       message(false, error);
//     },
//   });
// }

function printLabel() {
  toastr.info("Shipping label data is being processed", "Please wait", {
    progressBar: !0,
    timeOut: 2000,
  });
}

function sync_order(e) {
  Swal.fire({
    title: "Sync Order Confirmation",
    text: "Are you sure you want to sync this order?",
    icon: "question",
    showCancelButton: true,
    confirmButtonText: "Sync order",
    cancelButtonText: "Cancel",
  }).then((result) => {
    if (result.isConfirmed) {
      loading_toast("Syncing order, please wait...");
      var url = base_url() + "sales_orders/sync_order";

      var formData = new FormData();
      formData.append("_token", getCookie());
      formData.append("channel_id", channel);
      formData.append("local_order_id", $(e).attr("data-order-id"));
      $.ajax({
        url: url,
        type: "POST",
        data: formData,
        dataType: "JSON",
        contentType: false,
        processData: false,
        cache: false,
        success: function (data) {
          toastr.clear();
          if (data.status) {
            // 2311071B5PETX5
            message(true, data.msg);
          } else {
            message(false, data.msg);
          }
        },
        error: function (xhr, status, error) {
          toastr.clear();
          message(false, error);
        },
      });
    }
  });
}

function loading_toast(msg) {
  toastr.options = {
    closeButton: false,
    progressBar: false,
    preventDuplicates: true,
    showMethod: "fadeIn",
    hideMethod: "fadeOut",
    timeOut: 0,
    showIcon: false,
  };

  toastr.info(
    '<div class="spinner-border w-20px h-20px" role="status"><span class="sr-only">Loading...</span></div><span class="ms-5">' +
      msg +
      "</span> "
  );
}
