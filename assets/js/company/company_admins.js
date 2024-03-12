$(document).ready(function(){
    var baseurl = base_url() + 'company/show';

    var column = [
        { "data": "id" },
        {"data" : "company_code"},
        { "data": "company_name" },
        { "data": "status",render : function (data, type, row){ 
            var span = data == 'enable' ? "<span class=\"badge badge-light-success\">" : "<span class=\"badge badge-light-warning\">";
            return span+data+"</span>";
        } },
        { "data": "created_at" },
        { "data": "action" ,"width" : "17%"},
    ];

    ajax_crud_table(baseurl,column);

    sweetAlertConfirm();
    libraryInput();
    addData();
    modalClose();
    process();
    editData();
    
});