$(document).ready(function(){
    var errorMessage = '<div class="fv-plugins-message-container invalid-feedback">{{message}}</div>';
    var modal = "#modalSource";
    var productID = "";
    var dataRow = [];
    var modalLarge2 = "#modalLarge2";
    var modalLarge4 = "#modalLarge4";
    var btnSaving = "";

    var channelBody = '<tr id="id_{{sourceID}}_{{channelID}}" data-source="{{sourceID}}" data-channel="{{channelID}}">'+
			'<td>{{no}}</td>'+
			'<td><span class="sourceid{{sourceID}}">{{sourceName}}</span></td>'+
            '<td><span class="channelid{{channelID}}">{{channelName}}</span></td>'+
            '<td><span class="launchDate{{sourceID}}_{{channelID}}">{{launchDate}}</span></td>'+
            '<td><button class="btn btn-outline btn-outline-dashed btn-outline-success btn-sm me-2 btnImageNotDefault" data-source="{{sourceID}}" data-channel="{{channelID}}" data-fullscreenmodal="1" data-type="modal" data-url="'+base_url()+"inventory_display/notdefaultshadow/{{sourceID}}/{{channelID}}/{{productID}}"+'" {{disabledButton}}>Image</button><button class="btn btn-outline btn-outline-dashed btn-outline-{{colorStatus}} btn-sm me-2 btnActionNotDefault btnActionNotDefault{{sourceID}}_{{channelID}}" data-source="{{sourceID}}" data-channel="{{channelID}}" data-status="{{status}}" data-color="btn-outline-{{colorStatus}}" data-type="modal" data-url="'+base_url()+"inventory_display/launchshadow/{{sourceID}}/{{channelID}}/{{productID}}"+'" {{disabledButton}}>{{textButtonAction}}</button></td>'+
            '<td><span class="textStatus{{sourceID}}_{{channelID}}">{{textStatus}}</span></td>'+
        '</tr>';

    initRow();

    $(document).on('click','#addSource',function(){
        var btn = $(this);
        var btnText = btn.text();
        var data = [];
        productID = btn.data('id');
        request(data,btn,function(response){
            loadingButtonOff(btn,"<i class=\"bi bi-plus-lg fs-4 me-2\"></i>"+btnText);
            if(response.success === false){
                sweetAlertMessage(response.messages);
            }else{
                addSource(response.data);
                $("#source,#channel").val("").trigger("change");
                $(modal).modal("show");
            }
        });

    });

    function initRow()
    {
        
        dataLaunching.forEach((element) => {
            var sourceNotDefault = element.admins_ms_sources_id;
            var textSource = element.source_name;
            var channelNotDefault = element.users_ms_channels_id;
            var textChannel = element.channel_name;
            var launchDate = element.launch_date;
            var colorStatus = dataLookupColourLaunching[element.display_status];
            var status = element.display_status;
            var textButtonAction = dataLookupDisplayLaunching[element.display_status];
            var textStatus = dataLookupLaunchStatus[element.display_status];
            var product = element.users_ms_products_id;

            addRow(sourceNotDefault,textSource,channelNotDefault,textChannel,product,launchDate,colorStatus,status,textButtonAction,textStatus);
        });        
    }

    function addSource(data)
    {
        $("#source").empty();
        $('#source').append($('<option>', { 
            value: "",
            text : "Select Source",
        }));
        $.each(data, function (i, item) {
            $('#source').append($('<option>', { 
                value: item.id,
                text : item.source_name 
            }));
        });
    }

    function addChannel(data)
    {
        $("#channel").empty();
        $.each(data, function (i, item) {
            $('#channel').append($('<option>', { 
                value: item.id,
                text : item.channel_name
            }));
        });
    }

    $(document).on('change','#source',function(){
        var value = $(this).val();
        if(value != ""){
            var url = $(this).data('url');
            var data = [];
            data.push({name:"sourceID",value:value});
            loadingPage();
            requestUrlNotLoadingButton(data,url,function(response){
                CloseLoadingPage();
                if(response.success === false){
                    $("#channel").empty();
                    sweetAlertMessage(response.messages);
                }else{
                    addChannel(response.data);
                    $("#channel").next(".fv-plugins-message-container.invalid-feedback").html("");
                }
                
            });
        }else{
            $("#channel").empty();
        }
    });

    function validate()
    {
        var message = [];

        $(".validateSource").each(function(){
            var id = $(this).attr("id");
            var value = $(this).val();
            if(value == "" || value == null){
                var messageData = errorMessage.replaceAll("{{message}}","This field must be required");
				message.push({[id] : messageData});
            }
        });

        message.forEach((value) => {
			var key = Object.keys(value);
			var value = value[key];
			addErrorValidation(key,value);
		});

        return message.length;
    }

    $(document).on("change", "#source,#channel", function () {
        $(this)
          .next(".fv-plugins-message-container.invalid-feedback")
          .html("");
    });

    $(document).on('click','#sendSource',function(){
        var validates = validate();
        if(validates == 0){
            
            var sourceNotDefault = $("#source").val();
            var channelNotDefault = $("#channel").val();
            var textSource = $("#source option:selected").text();
            var textChannel = $("#channel option:selected").text();

            var check = checkRow(sourceNotDefault,textSource,channelNotDefault,textChannel);
            if(check == 0){
                addRow(sourceNotDefault,textSource,channelNotDefault,textChannel,productID);
            }
        }
    });

    function addRow(sourceNotDefault,textSource,channelNotDefault,textChannel,productID,launchDate = "-",colorStatus = "primary",status = 4,textButtonAction = "Launch",textStatus = "Image Not Selected, Pending")
    {
        var no = 1;

        Date.prototype.yyyymmdd = function() {
            var yyyy = this.getFullYear().toString();
            var mm = (this.getMonth()+1).toString(); // getMonth() is zero-based
            var dd  = this.getDate().toString();
            return yyyy + "-" + (mm[1]?mm:"0"+mm[0]) + "-" + (dd[1]?dd:"0"+dd[0]); // padding
        };
          
        var disabledButton = "";
        if(launchDate != "-"){
            var date = new Date();
            var curDate = date.yyyymmdd();
            var start = new Date(launchDate);
            var nowDate = new Date(curDate);
            if(start > nowDate){
                disabledButton = "";
            }else{
                disabledButton = "disabled";
            }
        }

        var checkNo = $("#tableSource tbody tr:last-child td:first").text();
        if(checkNo != "" && checkNo != null && typeof checkNo != "undefined"){
            no = no + parseInt(checkNo);
        }
        
        var channelBodyTemp = channelBody;
        var templateBody = channelBodyTemp.replaceAll("{{no}}",no);
        templateBody = templateBody.replaceAll("{{sourceID}}",sourceNotDefault);
        templateBody = templateBody.replaceAll("{{sourceName}}",textSource);
        templateBody = templateBody.replaceAll("{{channelID}}",channelNotDefault);
        templateBody = templateBody.replaceAll("{{channelName}}",textChannel);
        templateBody = templateBody.replaceAll("{{launchDate}}",launchDate); // -
        templateBody = templateBody.replaceAll("{{disabledButton}}",disabledButton);
        templateBody = templateBody.replaceAll("{{colorStatus}}",colorStatus); //primary
        templateBody = templateBody.replaceAll("{{status}}",status); //4
        templateBody = templateBody.replaceAll("{{textButtonAction}}",textButtonAction); // Launch
        templateBody = templateBody.replaceAll("{{textStatus}}",textStatus);
        templateBody = templateBody.replaceAll("{{productID}}",productID);

        $("#tableSource tbody").append(templateBody);
    }

    function checkRow(sourceNotDefault,textSource,channelNotDefault,textChannel)
    {
        var ketemu = 0;
        $("#tableSource tbody tr").each(function(){
            var source = $(this).attr("data-source");
            var channel = $(this).attr("data-channel");

            if(sourceNotDefault == source && channelNotDefault == channel){
                ketemu += 1;    
                sweetAlertMessage("Source "+textSource+" and Channel "+textChannel+" already exist on table");
            }

        });

        return ketemu;
    }

    // IMAGE DEFAULT 
    $(document).on('click','#btnDefaultImage',function(){
        var btn = $(this);
        var source = btn.data("source");
        var channel = btn.data("channel");
        btnSaving = "#btnProcessModalDefault";

        buttonAction(btn,modalLarge2);
                
    });

    $(document).on('click','#btnProcessModalDefault',function(){
        if(dataArray.length < 1){
            sweetAlertMessage("Failed data processing");
        }

        var result = sendDataConverted();
        var btn = $(this);
        var textBtn = btn.text();
        var source = btn.data("source");
        var channel = btn.data("channel");
        var productid = btn.data("productid");
        
        result = JSON.stringify(result);
        var sendData = [];
        sendData.push({name:"images",value:result});
        sendData.push({name:"source", value:source});
        sendData.push({name:"channel",value:channel});
        sendData.push({name:"productid", value:productid});
        request(sendData,btn,function(response){
            loadingButtonOff(btn,textBtn);
            message(response.success,response.messages);
            if(response.success){
                $(modalLarge2).modal("hide");
                $("#statusDefaultImage").text(checkStatusSelectedImage());
            }
        });
    });

    function checkStatusSelectedImage()
    {
        var status = 'Image Not Selected';
        for (let i = 0; i < dataArray.length; i++) {
            var lookup = dataArray[i][1];
            if(lookup == 3){
                status = 'Image Selected';
            }
        }

        return status;
    }

    // END IMAGE DEFAULT

    // IMAGE NOT DEFAULT

    $(document).on('click','#btnCloseModal',function(){
        modalAutoClose($(this));
    })

    $(document).on('click','.btnImageNotDefault',function(){
        var btn = $(this);
        var source = btn.data("source");
        var channel = btn.data("channel");
        btnSaving = "#btnProcessModalNotDefault";

        buttonAction(btn,modalLarge2);
                
    });

    $(document).on('click','.btnSelect, .btnView',function(){
        var btn = $(this);
        var imageName = btn.data('imagename');
        var imageID = btn.data('imageid');
        var data = [];
        data.push({name:'imageName',value:imageName});
        data.push({name: 'imageID', value:imageID});
        buttonActionData(btn,data,modalLarge4);
    });

    $(document).on('click','.btnSelectImage,.btnSelectMainImage',function(){
        var btn = $(this);
        var imageID = btn.data('imageid');
        var lookupData = btn.data('lookup');
        var textStatus = lookupValue[lookupData];
        $(modalLarge4).modal('hide');

        saveData(imageID,lookupData);
        statusName(imageID,textStatus);
        buttonCancel(imageID,false);

        if(lookupData == 3){
            $(btnSaving).prop("disabled",false);
        }

        $("#tableDetail tbody tr").each(function(key,value){
            var status = $(value).find("td:last").text();
            if(status == 'Main'){
                $(btnSaving).prop("disabled",false);
            }
        });
        
    });

    function saveData(imageID,lookupData)
    {
 
        imageID = String(imageID);

        for (let i = 0; i < dataArray.length; i++) {
            var search = dataArray[i][0];
            var searchMain = dataArray[i][1];
            lookupData = parseInt(lookupData);
            searchMain = parseInt(searchMain);
            search = String(search);


            if(lookupData == 3 && searchMain == lookupData){
                removeData(search);
                $(btnSaving).prop("disabled",true);
                if(imageID != search){
                    var textStatus = lookupValue[2];
                    statusName(search,textStatus);
                    dataArray.push([search,2]);
                }
            }

            if(search == imageID){
                if(lookupData != 3 && searchMain == 3){
                    $(btnSaving).prop("disabled",true);
                }
            }

        }

        removeData(imageID);
        dataArray.push([imageID,lookupData]);
        if(lookupData == 3){
            $(btnSaving).prop("disabled",false);
        }
    }

    function statusName(imageID,textStatus)
    {
        $(".statusName[data-imageid='"+imageID+"']").text(textStatus);
    }

    function buttonCancel(imageID,status)
    {
        $(".btnCancel[data-imageid='"+imageID+"']").prop("disabled",status);
    }

    function removeData(imageID)
    {
        for (let i = 0; i < dataArray.length; i++) {
            dataArray = dataArray.filter(item => item[0] != imageID);
        }
    }

    $(document).on('click',".btnCancel",function(){
        var btn = $(this);
        var imageID = btn.data('imageid');
        var lookupData = 1;
        var textStatus = lookupValue[lookupData];
        statusName(imageID,textStatus);
        buttonCancel(imageID,true);
        saveData(imageID,lookupData);
    });

    function sendDataConverted()
    {
        var converted = [];
        for (let i = 0; i < dataArray.length; i++) {
            var imageID = dataArray[i][0];
            var lookup = dataArray[i][1];
            var image = imageID+"|"+lookup;
            converted.push({name:"image",value: image});

        }

        return converted;
    }

    $(document).on('click','#btnProcessModalNotDefault',function(){
        if(dataArray.length < 1){
            sweetAlertMessage("Failed data processing");
        }

        var result = sendDataConverted();
        var btn = $(this);
        var textBtn = btn.text();
        var source = btn.data("source");
        var channel = btn.data("channel");
        var productid = btn.data("productid");
        
        result = JSON.stringify(result);
        var sendData = [];
        sendData.push({name:"images",value:result});
        sendData.push({name:"source", value:source});
        sendData.push({name:"channel",value:channel});
        sendData.push({name:"productid", value:productid});
        request(sendData,btn,function(response){
            loadingButtonOff(btn,textBtn);
            message(response.success,response.messages);
            if(response.success){
                if(typeof response.launch != "undefined"){
                    $(".textStatus"+source+"_"+channel).text(lookupLaunchStatus[4]);
                    $(".btnActionNotDefault"+source+"_"+channel).text(lookupDisplay[4]);
                    $(".btnActionNotDefault"+source+"_"+channel).attr("data-status",4);
                    var colour = $(".btnActionNotDefault"+source+"_"+channel).attr("data-color");
                    $(".btnActionNotDefault"+source+"_"+channel).removeClass(colour);
                    $(".btnActionNotDefault"+source+"_"+channel).addClass("btn-outline-"+lookupDisplayColour[4]);                    
                    $(".btnActionNotDefault"+source+"_"+channel).attr("data-color","btn-outline-"+lookupDisplayColour[4]);
                    $(".launchDate"+source+"_"+channel).text("-");
                }
                
                $(modalLarge2).modal("hide");
            }
        });
    });

    //END IMAGE DEFAULT

    $(document).on('click','#btnNotDefaultToDefaultImage',function(){
        var btn = $(this);
        var textBtn = btn.text();
        var source = btn.data('source');
        var channel = btn.data('channel');
        var productid = btn.data('product');

        var sendData = [];
        sendData.push({name:"source", value:source});
        sendData.push({name:"channel",value:channel});
        sendData.push({name:"productid", value:productid});
        request(sendData,btn,function(response){
            loadingButtonOff(btn,textBtn);
            if(response.success){
                message(response.success,response.messages);
                if(typeof response.launch != "undefined"){
                    $(".textStatus"+source+"_"+channel).text(lookupLaunchStatus[4]);
                    $(".btnActionNotDefault"+source+"_"+channel).text(lookupDisplay[4]);
                    $(".btnActionNotDefault"+source+"_"+channel).attr("data-status",4);
                    var colour = $(".btnActionNotDefault"+source+"_"+channel).attr("data-color");
                    $(".btnActionNotDefault"+source+"_"+channel).removeClass(colour);
                    $(".btnActionNotDefault"+source+"_"+channel).addClass("btn-outline-"+lookupDisplayColour[4]);                    
                    $(".btnActionNotDefault"+source+"_"+channel).attr("data-color","btn-outline-"+lookupDisplayColour[4]);
                    $(".launchDate"+source+"_"+channel).text("-");
                }
                $(modalLarge2).modal("hide");
            }else{
                sweetAlertMessage(response.messages);
            }
            
        });
    });

    $(document).on('click','.btnActionNotDefault',function(){
        var btn = $(this);
        var status = btn.attr('data-status');
        var source = btn.data('source');
        var channel = btn.data('channel');
        var data = [];
        data.push({name:"status",value:status});
        buttonActionData(btn,data,modalLarge4);
        var statusName = $('.textStatus'+source+"_"+channel).text();
        statusName = statusName.split(",");
        $("#launch_status").val(statusName[0]);
    });

    $(document).on('click','.btnLaunchProductSource',function(){
        var btn = $(this);
        var textBtn = btn.text();
        var source = btn.data('source');
        var channel = btn.data('channel');
        var productid = btn.data('productid');
        var launchDate = $("#launch_date").val();
        var data = [];
        data.push({name:"source",value:source});
        data.push({name:"channel",value:channel});
        data.push({name:"productid",value:productid});
        data.push({name:"launchdate",value:launchDate});

        request(data,btn,function(response){
            loadingButtonOff(btn,textBtn);
            $(modalLarge4).modal("hide");
            message(response.success,response.messages);
            if(response.success){
                $(".textStatus"+source+"_"+channel).text(lookupLaunchStatus[5]);
                $(".btnActionNotDefault"+source+"_"+channel).text(lookupDisplay[5]);
                $(".btnActionNotDefault"+source+"_"+channel).attr("data-status",5);
                var colour = $(".btnActionNotDefault"+source+"_"+channel).attr("data-color");
                $(".btnActionNotDefault"+source+"_"+channel).removeClass(colour);
                $(".btnActionNotDefault"+source+"_"+channel).addClass("btn-outline-"+lookupDisplayColour[5]);                    
                $(".btnActionNotDefault"+source+"_"+channel).attr("data-color","btn-outline-"+lookupDisplayColour[5]);
                $(".launchDate"+source+"_"+channel).text(launchDate);
            }
        });
    });

});