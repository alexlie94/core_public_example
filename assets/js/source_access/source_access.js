$(document).ready(function(){
    var baseurl = base_url() + 'source_access/show';
    var column = [
        { "data": "id" },
        { "data": "company_name" },
        { "data": "source_name" },
        { "data": "status",render : function (data, type, row){ 
            var span = data == 1 ? "<span class=\"badge badge-light-success\"> Enable" : "<span class=\"badge badge-light-danger\"> Disable";
            return span+"</span>";
            } 
        },
        { "data": "action" ,"width" : "17%"},
    ];
    
    ajax_crud_table(baseurl,column);

    sweetAlertConfirm();
    libraryInput();
    
    $(document).on("click", "#btnAdd", function () {
        buttonAction($(this),"#modalLarge3");
        $('#users_ms_companys_id').select2({
            minimumResultsForSearch: Infinity,
        });
        $('#admins_ms_sources_id').select2({
            minimumResultsForSearch: Infinity,
        });
        $('#status').select2({
            minimumResultsForSearch: Infinity,
        });

        $(document).on("change", "#admins_ms_sources_id", function () {
            var endpointsId = $(this).val();
            if (endpointsId) {
                $.ajax({
                    url: base_url() + "source_access/listEndpoints/" + endpointsId,
                    type: "GET",
                    success: function (data) {
                      $('#table1').html(data)
                    },
                  });
            }

        });

    });

    $(document).on("click", ".btnEdit", function () {
        buttonAction($(this),"#modalLarge3");
        $('#users_ms_companys_id').select2({
            minimumResultsForSearch: Infinity,
        });
        $('#admins_ms_sources_id').select2({
            minimumResultsForSearch: Infinity,
        });
        $('#status').select2({
            minimumResultsForSearch: Infinity,
        });

        getDataDetail($(this).data("id"));
        $("#admins_ms_sources_id").on("change", function () {
            var endpointsId = $(this).val();
            if (endpointsId) {
                $.ajax({
                    url: base_url() + "source_access/listEndpoints/" + endpointsId,
                    type: "GET",
                    success: function (data) {
                      $('#table1').html(data)
                    },
                });
            }

            
        });

    });

    function getDataDetail(id) {
        $.ajax({
            url: base_url() + "source_access/getDataDetail/" + id,
            type: "GET",
            success: function (data) {
              $('#table1').html(data)
            },
          });
    }

    $(document).on('click','#btnCloseModal',function(){
        $("#modalLarge3").modal("hide");
    });

    $(document).on("change", "#users_ms_companys_id", function () {
        var id = $(this).val();
        $.ajax({
            url: base_url() + "source_access/company_sources/" + id,
          type: "GET",
          dataType: "json",
          success: function (data) {
            var options = '<option value="">Select Option</option>';
    
            $.each(data, function (index, val) {
              options +=
                '<option value="' +
                val.id +
                '">' +
                val.source_name +
                "</option>";
            });
            $("#admins_ms_sources_id").html(options);
          },
        });
      });
    
    processSourceAccess();
    
});