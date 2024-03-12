$(document).ready(function () {
  const toggleMenuInput = document.getElementById("category");
  const menu = document.getElementById("menu");

  get_listBrand();
  shipping_list();

  toggleMenuInput.addEventListener("click", () => {
    menu.style.display =
      menu.style.display === "none" || menu.style.display === ""
        ? "block"
        : "none";

    if (menu.style.display === "block") {
      let data_push = [
        {
          name: "_token",
          value: getCookie(),
        },
        {
          name: "sources_id",
          value: $("#sources_id").val(),
        },
        {
          name: "channels_id",
          value: $("#channels_id").val(),
        },
      ];

      if (localStorage.getItem("category") !== "undefined") {
        var json_menu = JSON.parse(localStorage.getItem("category"));
        var e_ctg_id = $("#ctg_id").val();

        if (json_menu === null) {
          $.ajax({
            url: base_url() + "product_publish/get_category_api_single",
            method: "POST",
            dataType: "JSON",
            async: false,
            data: data_push,
            success: function (data) {
              let ctgry = data.category.category_list;
              let shipping = data.shipping.logistics_channel_list;

              create_wlocal(ctgry);
              localStorage.setItem("category", JSON.stringify(ctgry));
              localStorage.setItem("shipping_list", JSON.stringify(shipping));
              append_list_logistic();
            },
            error: function (jqXHR, textStatus, errorThrown) {
              error_message(jqXHR.status);
            },
          });
        } else {
          if (e_ctg_id === "") {
            create_wlocal(json_menu);
          }
        }
      } else {
        sweetAlertMessageWithConfirmNotShowCancelButton(
          "Your token authorization has expired. Please reconnect again",
          function () {
            return false;
          }
        );
      }
    }
  });

  function create_wlocal(json) {
    if (typeof json !== "undefined") {
      const filteredData = json.filter((item) => item.parent_category_id === 0);

      let ul_header = '<ul class="rc-cascader-menu">';
      for (let i = 0; i < filteredData.length; i++) {
        let ctgry_name = filteredData[i].display_category_name;
        let child = filteredData[i].has_children;
        let ctg_id = filteredData[i].category_id;

        if (child) {
          ul_header +=
            `	<li class = "rc-cascader-menu-item rc-cascader-menu-item-expand" data-ctg_id ="` +
            ctg_id +
            `" title = "` +
            ctgry_name +
            `" onClick="get_children(this,` +
            ctg_id +
            `);cek_active(this,` +
            ctg_id +
            `)">
									<div class = "rc-cascader-menu-item-content" > ` +
            ctgry_name +
            `</div>
									<div class ="rc-cascader-menu-item-expand-icon" > 
										<i> 
											<svg viewBox = "64 64 896 896" width = "1em" height = "1em" fill = "currentColor" focusable = "false"
													data-icon = "right" aria-hidden = "true" style = "vertical-align: -0.125em; margin: auto;">
											<path d = "M765.7 486.8L314.9 134.7A7.97 7.97 0 00302 141v77.3c0 4.9 2.3 9.6 6.1 12.6l360 281.1-360 281.1c-3.9 3-6.1 7.7-6.1 12.6V883c0 6.7 7.7 10.4 12.9 6.3l450.8-352.1a31.96 31.96 0 000-50.4z" >
											</path> 
											</svg>
										</i>
									</div>
								</li>`;
        } else {
          ul_header +=
            `	
					<li class = "rc-cascader-menu-item rc-cascader-menu-item-expand" data-ctg_id ="` +
            ctg_id +
            `" title = "` +
            ctgry_name +
            `" onClick="cek_active(this,` +
            ctg_id +
            `,'single')">
						<div class = "rc-cascader-menu-item-content" > ` +
            ctgry_name +
            `</div>
					</li>
				`;

          setTimeout(() => {
            $("div.rc-cascader-menus").empty();
          }, 100);
        }
      }
      ul_header += "</ul>";

      $("div.rc-cascader-menus").html(ul_header);
    } else {
      sweetAlertMessageWithConfirmNotShowCancelButton(
        "Your token authorization has expired. Please reconnect again",
        function () {
          return false;
        }
      );
    }
  }

  menu.addEventListener("click", (event) => {
    const target = event.target;
    const parentUl = target.closest("ul");
    const menuItems = parentUl.querySelectorAll(".rc-cascader-menu-item");
  });

  setTimeout(() => {
    $(document).on("click", function (event) {
      const menu = $("#menu");
      const target = $(event.target);

      let ctgIdValue = parseInt($("#ctg_id").val());
      let get_parent;

      if (isNaN(ctgIdValue)) {
        get_parent = false;
      } else {
        get_parent = ctgIdValue;
      }

      if (get_parent !== false) {
        let data = JSON.parse(localStorage.getItem("category"));

        const filteredData = data.filter(
          (item) => item.category_id === get_parent
        );
        let has_children = filteredData[0].has_children;
        if (
          !target.closest("#menu").length &&
          !target.is("#category") &&
          has_children === false
        ) {
          menu.hide();
        }
      } else {
        if (!target.closest("#menu").length && !target.is("#category")) {
          menu.hide();
        }
      }
    });
  }, 100);

  $(document).on("click", "#proccess_publish", function () {
    Swal.fire({
      title: "Save Item",
      text: "Are you sure you want to save this item ?",
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
        var textButton = $(this).text();
        var btn = $(this);
        var url = $("#form").data("url");
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
          },
          success: function (response) {
            if (!response.success) {
              if (!response.validate) {
                $.each(response.messages, function (key, value) {
                  error_validation_msg(key, value);

                  let keyName = key.split("[")[0];
                  let inputString = key;
                  let startIndex = inputString.indexOf("[");
                  let endIndex = inputString.indexOf("]");

                  let valueInsideBrackets = inputString.substring(
                    startIndex + 1,
                    endIndex
                  );

                  let element = $(
                    $('input[name="' + keyName + '[]"]')[valueInsideBrackets]
                  );

                  element
                    .removeClass("fv-plugins-bootstrap5-row-invalid")
                    .addClass(
                      value.length < 1
                        ? "fv-plugins-bootstrap5-row-invalid"
                        : ""
                    )
                    .next(".invalid-feedback")
                    .remove();
                  element.after(value);
                });
              }
            } else {
              if (response.type == "insert") {
                if (typeof response.data != "undefined") {
                  addDataOption(response.data);
                }
                reset_input();
              }
              window.location = base_url() + "inventory_display";
            }
            loadingButtonOff(btn, textButton);

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
});

function get_listBrand() {
  let select_list = $('select[name="brand"]');

  select_list.each(function () {
    var $this = $(this);
    $this.select2({
      dropdownCssClass: "select2-dropdown-below",
      dropdownParent: $this.parent(),
      allowClear: true,
      minimumResultsForSearch: Infinity,
      minimumInputLength: 1,
      ajax: {
        url: base_url() + "product_publish/get_brand_api",
        dataType: "json",
        delay: 250,
        data: function (params) {
          return {
            q: params.term,
            page: params.page,
            startLimit: params.startLimit,
            category_id: $("#ctg_id").val(),
            sources_id: $("#sources_id").val(),
            channels_id: $("#channels_id").val(),
          };
        },
        cache: true,
        processResults: function (data, params) {
          if (data.messages) {
            sweetAlertMessageWithConfirmNotShowCancelButton(
              data.messages,
              function () {
                return false;
              }
            );
          }

          return {
            results: data.items.map(function (item) {
              return {
                id: item.id + ":" + item.name,
                text: item.name,
              };
            }),
          };
        },
        error: function (jqXHR, textStatus, errorThrown) {
          error_message(jqXHR.status);
        },
        placeholder: "--Choose Options--",
        templateResult: formatRepos,
      },
    });
  });
}

function append_list_logistic() {
  var select_list = $('select[name="shipping[]"]');

  let get_shipping_list_data = JSON.parse(
    localStorage.getItem("shipping_list")
  );

  const filteredData = get_shipping_list_data.filter(
    (item) => item.mask_channel_id === 0 && item.enabled === true
  );

  let list_append = [];
  list_append.push("<option value=''></option>");
  for (let i = 0; i < filteredData.length; i++) {
    list_append.push(
      "<option value=" +
        filteredData[i].logistics_channel_id +
        ":" +
        filteredData[i].logistics_channel_name +
        ">" +
        filteredData[i].logistics_channel_name +
        "</option>"
    );
  }

  select_list.html(list_append);
}

function shipping_list() {
  if (
    localStorage.getItem("shipping_list") !== "undefined" &&
    localStorage.getItem("shipping_list") !== null
  ) {
    append_list_logistic();
  } else {
    return false;
  }
}

function formatRepos(repo) {
  return repo.name;
}

function get_children(e, id) {
  var remove_Ul = $(e).closest("ul");
  remove_Ul.nextAll("ul").remove();

  let get_parent = id;
  let data = JSON.parse(localStorage.getItem("category"));

  const filteredData = data.filter(
    (item) => item.parent_category_id === get_parent
  );

  let ul_header = '<ul class="rc-cascader-menu">';
  for (let i = 0; i < filteredData.length; i++) {
    let ctgry_name = filteredData[i].display_category_name;
    let child = filteredData[i].has_children;
    let ctg_id = filteredData[i].category_id;

    if (child) {
      ul_header +=
        `<li class = "rc-cascader-menu-item rc-cascader-menu-item-expand" data-ctg_id ="` +
        ctg_id +
        `" title = "` +
        ctgry_name +
        `" onClick="get_children(this,` +
        ctg_id +
        `);cek_active(this,` +
        ctg_id +
        `)">
								<div class = "rc-cascader-menu-item-content" data-ctg_id ="` +
        ctg_id +
        `" > ` +
        ctgry_name +
        `</div>
								 <div class ="rc-cascader-menu-item-expand-icon" > <i> 
								 	<svg viewBox = "64 64 896 896" width = "1em" height = "1em" fill = "currentColor" focusable = "false"
											data-icon = "right" aria-hidden = "true" style = "vertical-align: -0.125em; margin: auto;">
									<path d = "M765.7 486.8L314.9 134.7A7.97 7.97 0 00302 141v77.3c0 4.9 2.3 9.6 6.1 12.6l360 281.1-360 281.1c-3.9 3-6.1 7.7-6.1 12.6V883c0 6.7 7.7 10.4 12.9 6.3l450.8-352.1a31.96 31.96 0 000-50.4z" >
									</path> 
									</svg>
								</i>
							</div>
							</li>`;
    } else {
      ul_header +=
        `	
						<li class = "rc-cascader-menu-item rc-cascader-menu-item-expand" title = "` +
        ctgry_name +
        `" onClick="cek_active(this,` +
        ctg_id +
        `,'single')">
							<div class = "rc-cascader-menu-item-content" > ` +
        ctgry_name +
        `</div>
						</li>
					`;
    }
  }
  ul_header += "</ul>";

  $("div.rc-cascader-menus").append(ul_header);
}

function cek_active(e, b, c = null) {
  $("#ctg_id").val(b);

  if (c == "single") {
    removeLI(e);
  }

  $(e)
    .closest("ul")
    .find("li")
    .each(function (l, s) {
      $(s).removeClass("rc-cascader-menu-item-active");
    });

  setTimeout(() => {
    $(e).addClass("rc-cascader-menu-item-active");
  }, 100);

  var concatenatedCategories = "";
  setTimeout(() => {
    $("li.rc-cascader-menu-item-active").each(function (m, n) {
      let get_pic_ctgry = $(n)[0].outerText;

      concatenatedCategories += get_pic_ctgry + " > ";
    });

    let categories = concatenatedCategories.split(" > ");

    if (categories.length > 0) {
      categories.pop();
      let updatedString = categories.join(" > ");
      $("#category").val(updatedString);
    }
  }, 200);
}

function removeLI(e) {
  var remove_Ul = $(e).closest("ul");
  remove_Ul.nextAll("ul").remove();
}

function error_message(status) {
  switch (status) {
    case 401:
      sweetAlertMessageWithConfirmNotShowCancelButton(
        "Your session has expired or invalid. Please relogin",
        function () {
          window.location.href = base_url();
        }
      );
      break;
    case 500:
      sweetAlertMessageWithConfirmNotShowCancelButton(
        "Your token authorization has expired. Please reconnect again",
        function () {
          return false;
        }
      );
      break;
    case "pick":
      sweetAlertMessageWithConfirmNotShowCancelButton(
        "You must pick channel first,please pick!",
        function () {
          return false;
        }
      );
      break;
    default:
      sweetAlertMessageWithConfirmNotShowCancelButton(
        "We are sorry, but you do not have access to this service",
        function () {
          return false;
        }
      );
      break;
  }
}

function error_validation_msg(key, value) {
  if (
    key === "weight" ||
    key === "width" ||
    key === "length" ||
    key === "height"
  ) {
    var element = $('input[name="' + key + '"]');

    element
      .removeClass("fv-plugins-bootstrap5-row-invalid")
      .addClass(value.length < 1 ? "fv-plugins-bootstrap5-row-invalid" : "")
      .next(".invalid-feedback")
      .remove();
    element.next().after(value);
  } else {
    $("input, select, textarea").each(function () {
      var typeTagName = $(this).prop("tagName").toLowerCase();

      switch (typeTagName) {
        case "input":
          var element = $('input[name="' + key + '"]');

          element
            .removeClass("fv-plugins-bootstrap5-row-invalid")
            .addClass(
              value.length < 1 ? "fv-plugins-bootstrap5-row-invalid" : ""
            )
            .next(".invalid-feedback")
            .remove();
          element.after(value);

          break;
        case "textarea":
          var element = $('textarea[name="' + key + '"]');
          element
            .removeClass("fv-plugins-bootstrap5-row-invalid")
            .addClass(
              value.length < 1 ? "fv-plugins-bootstrap5-row-invalid" : ""
            )
            .next(".invalid-feedback")
            .remove();
          element.after(value);
          break;
        case "select":
          var element = $('select[name="' + key + '"]');

          //Build Element With Span
          element
            .removeClass("fv-plugins-bootstrap5-row-invalid")
            .addClass(
              value.length < 1 ? "fv-plugins-bootstrap5-row-invalid" : ""
            )
            .next()
            .next(".invalid-feedback")
            .remove();

          element.next().after(value);
          break;
        default:
          break;
      }
    });
  }
}

$(document).on("keyup", ":input", function () {
  $(this).removeClass("fv-plugins-bootstrap5-row-invalid");
  $(this).next().next(".invalid-feedback").remove();
});
