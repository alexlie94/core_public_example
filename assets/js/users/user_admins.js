$(document).ready(function(){
    var baseurl = base_url() + 'users/show';
    var column = [
        { "data": "id" },
        { "data": "fullname" },
        { "data": "email" },
        { "data": "status",render : function (data, type, row){ 
            var span = data == 'enable' ? "<span class=\"badge badge-light-success\">" : "<span class=\"badge badge-light-warning\">";
            return span+data+"</span>";
        } },
        { "data": "role_name" },
        { "data": "action" ,"width" : "17%"},
    ];
    
    ajax_crud_table(baseurl,column);

    sweetAlertConfirm();
    libraryInput();
    addData();
    modalClose();
    process();
    editData();

    $(document).on("change","input[name='rolename']",function(){
	    $("#rolename").next(".invalid-feedback").remove();
    });
    
});