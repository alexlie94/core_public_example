$(document).ready(function(){
    var baseurl = base_url() + 'sources/show';
    var column = [
        { "data": "id" },
        { "data": "source_image",
        render : function(data){
            var show = data != "default.png" ? `<div class="d-flex align-items-center">
                        <a style="cursor: pointer;" class="symbol symbol-50px" data-type="modal" data-url="`+base_url()+`/sources/showImage/`+data+`" data-fullscreenmodal="0" data-id="`+data+`" id="btnShowImg">
                            <span class="symbol-label" style="background-image:url(../assets/uploads/channels_image/`+data+`);"></span>
                        </a>
                    </div>` : `<div class="d-flex align-items-center">
                        <a class="symbol symbol-50px">
                            <span class="symbol-label" style="background-image:url(../assets/uploads/default.png);"></span>
                        </a>
                    </div>`;
            return show;
        } 
        },
        { "data": "source_icon",
        render : function(data){
            var show = data != "default.png" ? `<div class="d-flex align-items-center">
                        <a style="cursor: pointer;" class="symbol symbol-50px" data-type="modal" data-url="`+base_url()+`/sources/showIcon/`+data+`" data-fullscreenmodal="0" data-id="`+data+`" id="btnShowIcon">
                            <span class="symbol-label" style="background-image:url(../assets/uploads/channels_image/`+data+`);"></span>
                        </a>
                    </div>` : `<div class="d-flex align-items-center">
                        <a class="symbol symbol-50px">
                            <span class="symbol-label" style="background-image:url(../assets/uploads/default.png);"></span>
                        </a>
                    </div>`;
            return show;
        } 
        },
        { "data": "source_name" },
        { "data": "source_url" },
        { "data": "app_keys" },
        { "data": "secret_keys" },
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

    $(document).on("click", "#btnShowImg", function () {
        buttonAction($(this));
        $('#btnProcessModal').hide();
        var name = $('#source_name').val();
        $('.modal-header h2').prepend(name+" ");
    });

    $(document).on("click", "#btnShowIcon", function () {
        buttonAction($(this));
        $('#btnProcessModal').hide();
        var name = $('#source_name').val();
        $('.modal-header h2').prepend(name+" ");
    });

    modalClose();
    process();
});