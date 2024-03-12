$(document).ready(function(){
    var baseurl = base_url() + 'rolepermissions/show';
    var column = [
        { "data": "id" },
        { "data": "role_name" },
        { "data": "jumlah_user" },
        { "data": "status",render : function (data, type, row){ 
            var span = data == 'enable' ? "<span class=\"badge badge-light-success\">" : "<span class=\"badge badge-light-warning\">";
            return span+data+"</span>";
        } },
        { "data": "action" },
    ];

    ajax_crud_table(baseurl,column);

    $(document).on("click", "#btnProcessModal", function () {
        var textButton = $(this).text();
		var btn = $(this);
        var btnCloseModal = '#btnCloseModalFullscreen';
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
						reset_input();
					}
					if(!response.menu_first){
						reloadDatatables();
					}
					
				}

				loadingButtonOff(btn, textButton);
				enabledButton($(btnCloseModal));

				if (response.type == "update") {
					if (response.success) {

						if(response.menu_first){
							
							sweetAlertMessageWithConfirmNotShowCancelButton(
								response.messages + ".<br>You don't have access to this page.<br><b>Will be redirected to another page.</b>",
								function () {
									window.location.href = base_url() + response.menu_first;
								}
							);

						}

						var closeModal = btnCloseModal != "#btnCloseModal" ? btnCloseModal : "#modalLarge";
						modalAutoClose(closeModal);
					}
				}

				if (response.validate) {
                    if(response.success){
                        sweetAlertMessageWithConfirmNotShowCancelButton(response.messages,function(){
                            location.reload();
                        });
                    }else{
                        message(response.success, response.messages);
                    }
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

    sweetAlertConfirm();
    $(document).on("click", "#btnAdd", function () {
		buttonAction($(this),"#modalLarge2");
	});
    $(document).on('click','#btnCloseModalFullscreen',function(){
        $("#modalLarge2").modal("hide");
    });

    $(document).on("click", ".btnEdit", function () {
		buttonAction($(this),"#modalLarge2");
	});


    function checkboxRole(count,status)
    {

        $(".view[data-count='"+count+"']").each(function(index){
            var checkView = $(this).prop("disabled");
            if(!checkView){
                $(this).prop("checked",status);
            }
        });

        $(".insert[data-count='"+count+"']").each(function(index){
            var checkInsert = $(this).prop("disabled");
            if(!checkInsert){
                $(this).prop("checked",status);
            }
        });

        $(".update[data-count='"+count+"']").each(function(index){
            var checkUpdate = $(this).prop("disabled");
            if(!checkUpdate){
                $(this).prop("checked",status);
            }
        });

        $(".delete[data-count='"+count+"']").each(function(index){
            var checkDelete = $(this).prop("disabled");
            if(!checkDelete){
               $(this).prop("checked",status);
            }
        });


        $(".import[data-count='"+count+"']").each(function(index){
            var checkImport = $(this).prop("disabled");
            if(!checkImport){
                $(this).prop("checked",status);
            }
        });

        $(".export[data-count='"+count+"']").each(function(index){
            var checkExport = $(this).prop("disabled");
            if(!checkExport){
                $(this).prop("checked",status);
            }
        });

    }

    $(document).on('change','.all',function(){
        var all = $(this).is( ":checked" );
        $(".header").prop("checked",all);
        $(".header").each(function(index){
            var count = $(this).data('count');
            checkboxRole(count,all);
            
        });
    });

    $(document).on('change','.header',function(){
        var checkbox = $(this);
        var status = checkbox.is(":checked");
        var count = checkbox.data('count');

        checkboxRole(count,status);

    });

    $(document).on('change','.insert,.update,.delete,.export,.import',function(){
        var checkbox = $(this);
        var status = checkbox.is(":checked");
        var disable = checkbox.prop("disabled");
        if(!disable && status){
            var id = checkbox.data('id');
            $(".view[data-id='"+id+"']").prop("checked",status);
        }

        if(!disable && !status){
            var count = checkbox.data('count');
            $(".header[data-count='"+count+"']").prop("checked",status);
        }
    });

    $(document).on('change','.view',function(){
        var checkbox = $(this);
        var status = checkbox.is(":checked");
        var disable = checkbox.prop("disabled");
        if(!disable && !status){
            var count = checkbox.data('count');
            $(".header[data-count='"+count+"']").prop("checked",status);

            var id = checkbox.data('id');
            $("[data-id='"+id+"']").prop("checked",status);
        }
    });
});