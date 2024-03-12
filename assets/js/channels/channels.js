$(document).ready(function(){
    var baseurl = base_url() + 'channels/show';
    var column = [
        { "data": "id" },
        { "data": "source_name" },
        { "data": "channel_name" },
        { "data": "status_channel",render : function (data, type, row){ 
            var span = data == '1' ? "<span class=\"badge badge-light-success\"> Enable" : "<span class=\"badge badge-light-danger\"> Disable";
            return span+"</span>";
            } 
        },
        { "data": "action" ,"width" : "17%"},
    ];
    
    ajax_crud_table(baseurl,column, "table-data", "Channels");
    sweetAlertConfirm();
    libraryInput();
    
    $(document).on("click", "#btnAdd,.btnEdit", function () {
        buttonAction($(this));
    });

    modalClose();
    processNestedFieldsCustom();
});