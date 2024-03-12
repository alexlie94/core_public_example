$(document).ready(function(){
    var baseurl = base_url() + 'application_update/show';
    var column = [
        { "data": "id" },
        { "data": "title" },
        { "data": "launch_date" },
        { "data": "lookup_name",render : function (data, type, row){ 
            var span = data == "Enabled" ? "<span class=\"badge badge-light-success\"> Enable" : "<span class=\"badge badge-light-danger\"> Disabled";
            return span+"</span>";
            } 
        },
        { "data": "action" ,"width" : "17%"},
    ];
    ajax_crud_table(baseurl,column);

    sweetAlertConfirm();
    libraryInput();
    
    $(document).on("click", "#btnAdd,.btnEdit", function () {
        buttonAction($(this),"#modalLarge2");
        const currentDate = new Date();
        // Mengambil informasi dari objek tanggal
        const year = currentDate.getFullYear();
        const month = currentDate.getMonth() + 1; // Ingat bahwa indeks bulan dimulai dari 0
        const day = currentDate.getDate() - 1;

        $("#launch_date").flatpickr({
        disable: [
            {
            from: "1550-01-01",
            to: year + "-" + month + "-" + day,
            },
        ],
        enableTime: true,
        dateFormat: "Y-m-d H:i:s",
        });

        $("#status").select2({
            minimumResultsForSearch: Infinity,
        });

        $(document).on("change", "#launch_date", function () {
            $('#launch_date').parent().find('.invalid-feedback').hide();
        });
    });

    $(document).on("click", "#btnCloseModal", function () {
        $("#modalLarge2").modal("hide");
    });
    
    $(document).on("click", "#btnProcessModal", function () {
        document.querySelector("[name=kt_docs_ckeditor_classic").value = instance.getData()
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
                $("#modalLarge2").modal("hide");
              }
              reloadDatatables();
            }
            loadingButtonOff(btn, textButton);
            enabledButton($(btnCloseModal));
            if (response.type == "update") {
              if (response.success) {
                $("#modalLarge2").modal("hide");
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
});