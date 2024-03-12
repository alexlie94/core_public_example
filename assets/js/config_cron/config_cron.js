$(document).ready(function(){
    var baseurl = base_url() + 'config_cron/show';
    var column = [
        { "data": "id" },
        { "data": "cron_controller" },
        { "data": "cron_desc" },
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
        buttonAction($(this));
    });

    modalClose();
    process();
});