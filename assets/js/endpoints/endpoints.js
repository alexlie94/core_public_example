$(document).ready(function(){
    var baseurl = base_url() + 'endpoints/show';
    var column = [
        { "data": "id" },
        { "data": "title" },
        { "data": "source_name" },
        { "data": "endpoint_url" },
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
    
    $(document).on("click", "#btnAdd,.btnEdit", function () {
        buttonAction($(this),"#modalLarge");
        $('#admins_ms_sources_id').select2({
            minimumResultsForSearch: Infinity,
        });
        $('#status').select2({
            minimumResultsForSearch: Infinity,
        });

    });

    modalClose();
    processNestedFieldsCustom();
});