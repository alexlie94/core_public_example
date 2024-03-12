$(document).ready(function () {
  let url_display = base_url() + "product_publish/show_display";

  localStorage.removeItem("category");
  localStorage.removeItem("shipping_list");

  // var data = [{ name: "_token", value: getCookie() }];

  // $.ajax({
  //   url: url_display,
  //   method: "POST",
  //   dataType: "JSON",
  //   async: false,
  //   data: data,
  //   success: function (response) {
  //     let parseData = response.data;

  //     let previousProductName = null;

  //     for (let i = 0; i < parseData.length; i++) {
  //       let sku = parseData[i].sku.split(",");

  //       for (let j = 0; j < sku.length; j++) {
  //         let currentProductName = parseData[i].product_name;

  //         if (currentProductName !== previousProductName) {
  //           let tr_table = `<tr>`;

  //           tr_table +=
  //             `
  //           <td style='text-align: center;vertical-align: middle;'>
  //               <div class="form-check form-check-custom form-check-solid form-check-sm">
  //                   <input class="form-check-input" type="checkbox" value="" id="selectOrder" />
  //               </div>
  //           </td>
  //           <td style='text-align: center;vertical-align: middle;'>
  //               <div class="symbol" style="display: flex; align-items: center;">
  //                   <span class="symbol-label" style="background-image: url(products_image/); border: 1px solid black; width: 38px; height: 38px;">
  //                   </span>
  //                   <span style="margin: 0 10px;">${currentProductName}</span>
  //               </div>
  //           </td>
  //           <td>${sku[j]}</td>
  //           <td>
  //           <img src="./assets/metronic/media/marketplace/shopee-icon.png" alt="" class="me-4" width="30" /> ` +
  //             parseData[i].source_per_channel +
  //             `</td>
  //           <td>
  //           <a style="display: flex; align-items: center; href="#" class="btn btn-icon btn-secondary btn-sm">
  // 									<i class="bi bi-three-dots-vertical fs-1"></i>
  // 							</a>
  //           </td>
  //           `;

  //           tr_table += `</tr>`;

  //           $("#datatable_display_publish tbody").append(tr_table);

  //           previousProductName = currentProductName;
  //         } else {
  //           let tr_table = `<tr>`;

  //           tr_table +=
  //             `
  //           <td></td>
  //           <td></td>
  //           <td>${sku[j]}</td>
  //           <td>
  //           <img src="./assets/metronic/media/marketplace/shopee-icon.png" alt="" class="me-4" width="30" /> ` +
  //             parseData[i].source_per_channel +
  //             `</td>
  //           <td>
  //           <a style="display: flex; align-items: center; href="#" class="btn btn-icon btn-secondary btn-sm">
  // 									<i class="bi bi-three-dots-vertical fs-1"></i>
  // 							</a>
  //           </td>
  //           `;

  //           tr_table += `</tr>`;

  //           $("#datatable_display_publish tbody").append(tr_table);
  //         }
  //       }
  //     }
  //   },
  //   error: function (jqXHR, textStatus, errorThrown) {
  //     switch (jqXHR.status) {
  //       case 401:
  //         sweetAlertMessageWithConfirmNotShowCancelButton(
  //           "Your session has expired or invalid. Please relogin",
  //           function () {
  //             window.location.href = base_url();
  //           }
  //         );
  //         break;
  //       default:
  //         sweetAlertMessageWithConfirmNotShowCancelButton(
  //           "We are sorry, but you do not have access to this service",
  //           function () {
  //             location.reload();
  //           }
  //         );
  //         break;
  //     }
  //   },
  // });

  var column = [
    {
      data: "id",
      render: function (data) {
        let checkAtr = `
        <div class="form-check form-check-sm form-check-custom form-check-solid">
          <input class="form-check-input" type="checkbox" value="1">
        </div>`;

        return checkAtr;
      },
    },
    {
      data: "product_name",
      render: function (data, type, row, meta) {
        let status = row.status_product;
        let image_name = row.image_name;

        let color = "";

        switch (status) {
          case "New":
            color = "success";
            break;
          case "Launching":
            color = "primary";
            break;
          case "Incoming":
            color = "warning";
            break;
          case "Pending":
            color = "danger";
            break;
          default:
            color = "info";
        }

        let image =
          `<div class="symbol symbol-50px" style="display: flex; align-items: center;">
        <span class="symbol-label" style="background-image: url(./assets/uploads/products_image/` +
          image_name +
          `); border: 1px solid black; width: 38px; height: 38px;">
        </span>
          <div class="d-flex flex-column mb-2" style="margin: 0 10px;">
            ` +
          row.product_name +
          `
            <span><div class="badge badge-light-` +
          color +
          `">` +
          status +
          `</div></span>
          </div>
      </div>`;

        return image;
      },
      width: "25%",
    },
    {
      data: "sku",
      render: function (data) {
        let sku = data.split(",");

        var html = "";
        for (let xx = 0; xx < sku.length; xx++) {
          var element = sku[xx];

          let rSku = "";
          let rSize = "";
          let rHexa = "";
          let parts = element.split("||");
          if (parts.length > 0) {
            rSku = parts[0];
            rSize = parts[1];
            rHexa = parts[2];
          }

          html +=
            `<div class="d-flex flex-wrap mb-2" style="border-bottom: 1px dashed #c8c8c8;">
                    <div class="d-flex flex-column me-7 me-lg-16">

                        <div class="d-flex align-items-center">

                            <span class="bullet bullet-dot me-2 h-10px w-10px" style="background-color: #` +
            rHexa +
            `;border: 1px solid black;" ></span>

                            <span class="fw-bold text-gray-600 fs-7">` +
            rSku +
            ` - <div class="badge badge-light-info">` +
            rSize +
            `</div></span>

                        </div>

                    </div>
                  </div>`;
        }

        return html;
      },
      width: "25%",
    },
    {
      data: "source_per_channel",
      render: function (data, type, full, meta) {
        let source_data = full["source_per_channel"].split(",");

        var html = "";
        for (let xx = 0; xx < source_data.length; xx++) {
          var element = source_data[xx];

          let channel = "";
          let parts = element.split("||");

          if (parts.length > 0) {
            let image_source = "";
            rSource = parts[0];

            switch (rSource) {
              case "Shopee":
                image_source = "shopee-icon.png";
                break;
              case "Tiktok":
                image_source = "tiktok-icon.png";
                break;
              case "Zalora":
                image_source = "zalora-icon.png";
                break;
              case "Tokopedia":
                image_source = "tokopedia-icon.png";
                break;
              case "Berrybenka Offline":
                image_source = "bb.webp";
                break;
              default:
                image_source = "shopee-icon.png";
            }

            channel =
              `<img src="./assets/metronic/media/marketplace/` +
              image_source +
              `" class="me-4" width="25" /> ` +
              parts[1];
          }

          html +=
            `<div class="d-flex flex-wrap mb-2" style="border-bottom: 1px dashed #c8c8c8;">
                    <div class="d-flex flex-column me-7 me-lg-16 justify-content-center align-items-center">                   
                            <span class="fw-bold text-gray-600 fs-6">
                             ` +
            channel +
            `</span>        
                    </div>
                  </div>`;
        }

        return html;
      },
      width: "25%",
    },
    {
      data: "id",
      render: function (data, a, x, c) {
        let btn =
          `
      <div class="ms-2">
          <button type="button" class="btn btn-sm btn-icon btn-light btn-active-light-primary" data-kt-menu-trigger="click"
              data-kt-menu-placement="bottom-end">
      
              <span class="svg-icon svg-icon-5 m-0">
                  <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <rect x="10" y="10" width="4" height="4" rx="2"
                          fill="currentColor" />
                      <rect x="17" y="10" width="4" height="4" rx="2"
                          fill="currentColor" />
                      <rect x="3" y="10" width="4" height="4" rx="2"
                          fill="currentColor" />
                  </svg>
              </span>
              
          </button>
          
          <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-200px py-4"
              data-kt-menu="true">
                
              <div class="menu-item px-3">
                  <a href="` +
          base_url() +
          `product_publish/publish_marketplace?params=` +
          x.params +
          `" class="menu-link px-3 text-success" data-kt-filemanager-table="rename">
                  <i class="fa-solid fa-plus text-success fs-4 me-2"></i>
                  Add Product Publish
                  </a>
              </div>
              
              <div class="menu-item px-3">
                  <a href="#" class="menu-link px-3 text-warning" data-kt-filemanager-table-filter="move_row"
                      data-bs-toggle="modal" data-bs-target="#kt_modal_move_to_folder">
                      <i class="fa-solid text-warning fa-edit fs-4 me-2"></i>
                      Edit
                  </a>
              </div>
              
              <div class="menu-item px-3">
                  <a href="#" class="menu-link px-3 text-info" data-kt-filemanager-table-filter="move_row"
                      data-bs-toggle="modal" data-bs-target="#kt_modal_move_to_folder">
                      <i class="fa-solid text-info fa-eye fs-4 me-2"></i>
                      View
                  </a>
              </div>
          </div>
          
      </div>`;

        return btn;
      },
      width: "18%",
      className: "text-center",
    },
  ];

  tbl = $("#datatable_display_publish").DataTable({
    processing: false,
    oLanguage: {
      sProcessing: "loading...",
    },
    serverSide: true,
    responsive: true,
    orderable: true,
    paging: true,
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
      url: url_display,
      type: "POST",
      data: function () {
        let data = [{ name: "_token", value: getCookie() }];
        return data;
      },
    },
    columns: column,
    columnDefs: [
      {
        targets: 0,
        className: "text-center",
      },
    ],
    rowCallback: function (row, data, index) {
      // $(row).css("vertical-align", "middle");
    },
  });
  tbl.on("draw", function () {
    // Tindakan yang ingin Anda lakukan setelah setiap "draw" (penggambaran ulang) DataTable
    KTMenu.createInstances();
    // Lakukan tindakan lain di sini sesuai kebutuhan
  });
});

$(document).on("click", ".checked_bulk", function () {
  var table = $("#datatable_display_publish").DataTable();

  var checkboxes = table.column(0).nodes().to$().find(":checkbox");
  checkboxes.prop("checked", $(this).prop("checked"));
});

$(document).on("change", "#cheked_channel", function () {
  if (this.checked) {
    $(this).closest("label").addClass("active");
  } else {
    $(this).closest("label").removeClass("active");
  }
});
