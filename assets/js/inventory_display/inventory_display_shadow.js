$(document).ready(function(){

    var headerBody = '<tr>'+
			'<td>{{no}}</td>'+
			'<td>{{productID}}</td>'+
            '<td>{{productName}}</td>'+
            '<td>{{status}}</td>'+
			'<td>{{shadowLaunchDate}}</td>'+
            '<td>{{button}}</td>'+
        '</tr>';

    var id = $("#addShadow").attr("data-id");
    var baseurl = base_url() + "inventory_display/sku/"+id;

    var column = [
        { "data": "id" },
        { "data": "users_ms_products_id" },
        { "data": "sku" },
        { "data": "genaral_color" },
        { "data": "variant_color" },
        { "data": "product_size" },
        { "data": "status_variant"},
    ];

    ajax_crud_table(baseurl,column,"table-data");

    $(document).on('click','#addShadow',function(){
        var button = $(this);
        var productid = button.attr("data-id");
        var textButton = button.text();
        buttonAction(button);
    });

    function addRowHeader(no,productID,productName,productStatus,shadowLaunchDate,button)
    {
        var templateBody = headerBody;
        var tbody = templateBody.replaceAll("{{no}}",no);
        tbody = tbody.replaceAll("{{productID}}",productID);
        tbody = tbody.replaceAll("{{productName}}",productName);
        tbody = tbody.replaceAll("{{status}}",productStatus);
        tbody = tbody.replaceAll("{{shadowLaunchDate}}","-");
        tbody = tbody.replaceAll("{{button}}",button);

        $("#tableVariant tbody").append(tbody);
    }

    $(document).on('click',"#btnProcessAddShadow",function(){
        var button = $(this);
        var textButton = button.text();
        var closeModal = "#btnCloseModal";
        var url = $("#form").attr("data-url");
        disabledButton($(closeModal));
        var data = [];
        data.push({name:"qty", value: $("#qty").val()});
        requestUrl(data,button,url,function(response){
            enabledButton($(closeModal));
            loadingButtonOff(button,textButton);
            if(!response.success && !response.validate){
                $.each(response.messages, function (key, value) {
                    addErrorValidation(key, value);
                });
            }else{
                if(response.success){
                    modalAutoClose();
                    reset_input();
                    reloadDatatables();

                    no = $("#tableVariant tbody tr:last td:first").text();
                    $.each(response.header, function (key, value) {
                        no++;
                        addRowHeader(no,value.users_ms_product_shadows_id,value.productName,value.statusName,"-",value.button);
                    });
                }
                
                message(response.success,response.messages);
            }
        });
    })

    $(document).on('click',".btnLaunchingShadow",function(){

        var url = $(this).attr("data-url");
        window.location.href = url;
    });
});