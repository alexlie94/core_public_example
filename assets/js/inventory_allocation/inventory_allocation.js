$(document).ready(function(){

    var baseurl = base_url() + 'inventory_allocation/show';
    var baseurloffline = base_url() + 'inventory_allocation/showoffline';

    var column = [
        { "data": "id" },
        { "data": "product_id" },
        { "data": "product_name" },
        { "data": "brand_name" },
        { "data": "product_size" },
        { "data": "status_name",render : function (data, type, row){ 
            return "<span class='"+vlookup[data]+"'>"+data+"</span>";
        } },
    ];

    ajax_crud_table_tabs(baseurl,column,"table-data","inventory_allocations",true);
    exportData();

    $(document).on('change','#searchBy',function(){
        var search = $(this).val();  
        switchSearch(search);
    });

    function switchSearch(search)
    {
        var html = search != "" ? searchInput[search] : "";
        $("#placeSearch").html(html);
        $(".dateRange").daterangepicker({
            timePicker: true,
            timePicker24Hour: true,
            timePickerSeconds: true,
            locale: {
                format: "YYYY-MM-DD HH:mm:ss"
            }
        });
    }

    $(document).on('change','#source',function(){
        var source = $(this).val();
        var data = [];
        data.push({name:"source_id",value:source});
        loadingPage();
        requestUrlNotLoadingButton(data,$(this).data('url'),function(response){
            CloseLoadingPage();
            $("#channel").find('option').remove();
            if(typeof response.data != "undefined"){
                for(let i = 0; i < response.data.length; i++){
                    $("#channel").append("<option value='"+response.data[i].id+"'>"+response.data[i].channel_name+"</option>");
                }
            }

        });
    });

    $(document).on('click',"#table-data tbody tr",function(){
        var check = $(this).hasClass("selected");
        if(check){
            var modalID = "#modalLarge2";
            var productid = $(this).data('productid');
            var url = base_url()+"inventory_allocation/allocation";
            var data = [];
            data.push({name:"productID",value:productid});
            requestUrlNotLoadingButton(data,url,function(response){
                if (typeof response.failed == "undefined") {
                    $(modalID + " .modal-dialog").addClass("modal-fullscreen");
                    $(modalID + " .modal-content").html(response.html);
                    //checkLibraryOnModal();
                    $(modalID).modal("show");
                }else{
                    sweetAlertMessage(response.message);
                }
            });

        }
        
    });

    function removeSelectedTr()
    {
        $("#table-data tbody tr.odd").removeClass('selected');
        $("#table-data tbody tr.even").removeClass('selected');
    }

    $(document).on('click','#btnCloseModal',function(){
        modalAutoClose($(this));
        removeSelectedTr();
    });

    $(document).on("click", "#btnProcessModal", function () {
        var btnCloseModal = "#btnCloseModal"
        var textButton = $(this).text();
        var btn = $(this);
        var url = $("#form").data("url");
        var data = $("#form").serializeArray(); // convert form to array
        data.push({ name: "_token", value: getCookie() });

        var dataReserved = [];
        $("#tableVariantAllocation .reserved-input").each(function(){
            var input = $(this);
            var reserved = input.find("input[type=text]").val();
            var dataInput = {
                "variantsID" : input.data('id'),
                "sourceID" : input.data('source'),
                "channel" : input.data('channel'),
                "reserved" : reserved,
            }
            dataReserved.push(dataInput);
        });

        dataReserved = JSON.stringify(dataReserved);
        data.push({name:"data",value:dataReserved});
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
                message(response.success, response.messages);
                modalAutoClose(btnCloseModal);
                removeSelectedTr();
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

    $(document).on('click','#btnSearchResetInventory',function(){
        $("#searchBy").val('productid').trigger('change');
        $("#source").val("").trigger('change');
        reloadDatatablesTabs('table-data');
    });

    $(document).on('change','#searchByOffline',function(){
        var search = $(this).val();
        switchSearchOffline(search);
    });

    function switchSearchOffline(search)
    {
        var html = search != "" ? searchInput[search] : "";
        $("#placeSearchOffline").html(html);
        $(".dateRange").daterangepicker({
            timePicker: true,
            timePicker24Hour: true,
            timePickerSeconds: true,
            locale: {
                format: "YYYY-MM-DD HH:mm:ss"
            }
        });
    }

    $(document).on('click','#btnSearchResetInventoryOffline',function(){
        $("#searchByOffline").val('productid').trigger('change');
        $("#offlineStores").val("").trigger('change');
        reloadDatatablesTabs('table-data-offline');
    });

    $(document).on('click','.offline',function(){
        var check = $("#table-data-offline_wrapper").hasClass('dataTables_wrapper');
        if(!check){
            ajax_crud_table_tabs(baseurloffline,column,"table-data-offline","inventory_allocations",true,'formSearchOffline');
        }
    });

    function removeSelectedTrOffline()
    {
        $("#table-data-offline tbody tr.odd").removeClass('selected');
        $("#table-data-offline tbody tr.even").removeClass('selected');
    }

    $(document).on('click',"#table-data-offline tbody tr",function(){
        var check = $(this).hasClass("selected");
        if(check){
            var modalID = "#modalLarge2";
            var productid = $(this).data('productid');
            var url = base_url()+"inventory_allocation/allocationoffline";
            var data = [];
            data.push({name:"productID",value:productid});
            requestUrlNotLoadingButton(data,url,function(response){
                if (typeof response.failed == "undefined") {
                    $(modalID + " .modal-dialog").addClass("modal-fullscreen");
                    $(modalID + " .modal-content").html(response.html);
                    //checkLibraryOnModal();
                    $(modalID).modal("show");
                }else{
                    sweetAlertMessage(response.message);
                }
            });

        }
        
    });

    $(document).on('click','#btnCloseModalOffline',function(){
        modalAutoClose($(this));
        removeSelectedTrOffline();
    });

    $(document).on('click','#btnProcessModalOffline',function(){
        var btnCloseModal = "#btnCloseModalOffline"
        var textButton = $(this).text();
        var btn = $(this);
        var url = $("#form").data("url");
        var data = $("#form").serializeArray(); // convert form to array
        data.push({ name: "_token", value: getCookie() });

        var dataReserved = [];
        $("#tableVariantAllocationOffline .reserved-input").each(function(){
            var input = $(this);
            var reserved = input.find("input[type=text]").val();
            var dataInput = {
                "variantsID" : input.data('id'),
                "offlineStore" : input.data('offline'),
                "reserved" : reserved,
            }
            dataReserved.push(dataInput);

        });

        dataReserved = JSON.stringify(dataReserved);
        data.push({name:"data",value:dataReserved});
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
                message(response.success, response.messages);
                modalAutoClose(btnCloseModal);
                removeSelectedTrOffline();
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

    $(document).on("click", "#btnExportOffline", function () {
        var baseUrl = $(this).data("url");
        var data =
          $("#formSearchOffline").length > 0 ? $("#formSearchOffline").serializeArray() : [];
        data.push({
          name: "search",
          value: $("#table-data-offline_filter input").val(),
        });
        data.push({ name: "_token", value: getCookie() });
        var uri = baseUrl + "?" + $.param(data);
        window.open(uri);
    });

    /* ONLINE */

    $(document).on('click','#btnMassUpload',function(){
        buttonAction($(this));
    });

    $(document).on('click','#btnCloseModalUpload',function(){
        modalAutoClose($(this));
    });

    $(document).on('click','#btnCloseModalPreview',function(){
        modalAutoClose($(this));
    });

    $(document).on('click','#btnDownloadTemplate',function(){
        var url = $(this).data('url');
        window.location.assign(url);
    });

    sweetAlertConfirmDeleteHTML();

    function errorValidation(validation,validationIcon)
    {
        $.each(validation, function (key, value) {
           var type = value.type;
           var name = value.name;
           var sequence = value.sequence;
           var message = value.message;
           //var icon = value.icon;

           let element = $(type+'[name="'+name+'[]"]')[sequence];

           switch (type) {
                case "input":
                    $(element).next(".invalid-feedback").remove();
                    $(element).after(message);
                    $(element).parent().parent().find('.icontd').html(validationIcon[sequence].icon);
                    if(typeof value.productSize != "undefined"){
                        $(element).parent().parent().find('.productSize').attr("value",value.productSize);
                    }
                    if(typeof value.storage != "undefined"){
                        $(element).parent().parent().find('.productStorage').attr("value",value.storage);
                    }
                    if(typeof value.availableQty != "undefined"){
                        $(element).parent().parent().find('.productAvailable').attr("value",value.availableQty);
                    }
                    break;
            
                case "select":
                    $(element).next().next(".invalid-feedback").remove();
                    $(element).next().after(message);
                    $(element).parent().parent().find('.icontd').html(validationIcon[sequence].icon);
                    break;
           }

          

        });
    }

    $(document).on("click", "#btnProcessUploadModal", function () {
		var btn = $(this);
		var url = btn.attr("data-url");
		var data = $("#formUpload").serializeArray();
		var btnCloseModal = $("#btnCloseModalPreview");
		var textButton = btn.text();
		
        disabledButton(btnCloseModal);
       
		requestUrl(data, btn, url, function (response) {
			enabledButton(btnCloseModal);
			loadingButtonOff(btn, textButton);

            var buttonName = response.buttonName;
            var buttonUrl = response.buttonUrl;

            $(btn).text(buttonName);
            $(btn).attr('data-url',buttonUrl);
            
            if(typeof response.validation == "object"){
                errorValidation(response.validation,response.validationIcon);
                if(response.success){
                    if(typeof response.showModal != "undefined"){
                        sweetAlertMessageWithConfirmShowCancelButton("Data is valid",function(result){
                            if (result.isConfirmed) {
                                
                                var button = $("#btnProcessUploadModal");
                                var url = button.attr('data-url');
                                var data = $("#formUpload").serializeArray();
                                var textButton = button.text();

                                disabledButton(btnCloseModal);
                                requestUrl(data,btn,url,function(responsechecking){
                                    enabledButton(btnCloseModal);
			                        loadingButtonOff(button, textButton);

                                    if (responsechecking.success === false) {
                                    	sweetAlertMessage(responsechecking.message);
                                    } else {
                                    	modalAutoClose();
                                    	modalAutoClose("#btnCloseModalPreview");
                                    	message(responsechecking.success, responsechecking.messages);
                                    }
                                   
                                    
                                });


                            } else {
                                return false;
                            }
                        });
                    }else{
                        //disini
                        if (response.success === false) {
                            sweetAlertMessage(response.message);
                        } else {
                            modalAutoClose();
                            modalAutoClose("#btnCloseModalPreview");
                            message(response.success, response.messages);
                        }
                    }
                }
            }else{
                if(response.success === false){
                    sweetAlertMessageWithConfirmNotShowCancelButton(
                      response.messages,
                      function () {
                        location.reload();
                      }
                    );
                    
                }else{
                    if (response.success === false) {
                        sweetAlertMessage(response.message);
                    } else {
                        modalAutoClose();
                        modalAutoClose("#btnCloseModalPreview");
                        message(response.success, response.messages);
                    }
                }
            }
            
		});
	});

    /* ONLINE */

    /* OFFLINE */

    $(document).on('click','#btnMassUploadOffline',function(){
        buttonAction($(this));
    });


    $(document).on('click','#btnDownloadTemplateOffline',function(){
        var url = $(this).data('url');
        window.location.assign(url);
    });

    /* END OFFLINE */

});