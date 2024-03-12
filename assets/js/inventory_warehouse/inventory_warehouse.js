function getData(response) {
    var getReprog = response.receiving.inProgress;
    var getReclos = response.receiving.closed;
    var getPutprog = response.putAway.inProgress;
    var getPutclos = response.putAway.closed;
    var getStoratot = response.storage.total;
    var getPickprog = response.picking.inProgress;
    var getPickclos = response.picking.closed
    var getPackprog = response.packing.inProgress;
    var getPackclos = response.packing.closed;
    var getShipprog = response.shipping.inProgress;
    var getShipclos = response.shipping.closed;
    $('#receive_inpro').text(getReprog);
    $('#receive_close').text(getReclos);
    $('#put_prog').text(getPutprog);
    $('#put_clos').text(getPutclos);
    $('#stora_tot').text(getStoratot);
    $('#pick_prog').text(getPickprog);
    $('#pick_clos').text(getPickclos);
    $('#pack_prog').text(getPackprog);
    $('#pack_clos').text(getPackclos);
    $('#ship_prog').text(getShipprog);
    $('#ship_clos').text(getShipclos);

}

$( document ).ready(function() {
    var test = $('#warehouse').val();
    var data = [];
    data.push({name:"id",value:test});
    var url = $("#search").data("url");
    requestUrlNotLoadingButton(data,url,function(response){
        getData(response);
    });
});

$(document).on("click", "#search", function () {
    
    var btn = $(this);
    var btnText = $(this).text();
    var test = $('#warehouse').val();
    var data = [];
    data.push({name:"id",value:test});
    request(data,btn,function(response){
        getData(response);
        loadingButtonOff(btn,btnText);
    });

});

$(document).on("click", "#btnReceiving", function () {
    buttonAction($(this),"#modalLarge2");
    $('select').select2({
        minimumResultsForSearch: Infinity,
    });
    $("#search_by1").hide();
    $("#search_created_at").hide();
    list_inv('receiving');
    $("h2").css("color", "#5cb85c");
    $(document).on("change", "#search_by", function () {
        var getSelect = $("#search_by").val();
        if (getSelect != null) {
            $("#search_by1").show();
        } else {
            $("#search_by1").hide();
        }
        if (getSelect == "created_at") {
            $("#search_by1").hide();
            $("#search_created_at").show();
        } else {
            $("#search_created_at").hide();
        }
    });
    $("#kt_datepicker_3").flatpickr({
        enableTime: true,
        dateFormat: "Y-m-d H:i:s",
    });
    $("#created_at").flatpickr({
        enableTime: false,
        dateFormat: "Y-m-d",
        mode: "range",
    });
    $("#kt_datepicker_4").flatpickr({
        enableTime: true,
        dateFormat: "Y-m-d H:i:s",
    });
});

$(document).on("click", "#btnPutaway", function () {
    buttonAction($(this),"#modalLarge2");
    $('select').select2({
        minimumResultsForSearch: Infinity,
    });
    $("#search_by1").hide();
    $("#search_created_at").hide();
    list_inv('putaway');
    $("h2").css("color", "#FFAE42");
    $(document).on("change", "#search_by", function () {
        var getSelect = $("#search_by").val();
        if (getSelect != null) {
            $("#search_by1").show();
        } else {
            $("#search_by1").hide();
        }
        if (getSelect == "created_at") {
            $("#search_by1").hide();
            $("#search_created_at").show();
        } else {
            $("#search_created_at").hide();
        }
    });
    $("#kt_datepicker_3").flatpickr({
        enableTime: true,
        dateFormat: "Y-m-d H:i:s",
    });
    $("#created_at").flatpickr({
        enableTime: false,
        dateFormat: "Y-m-d",
        mode: "range",
    });
    $("#kt_datepicker_4").flatpickr({
        enableTime: true,
        dateFormat: "Y-m-d H:i:s",
    });
});

$(document).on("click", "#btnStorage", function () {
    buttonAction($(this),"#modalLarge2");
    $('select').select2({
        minimumResultsForSearch: Infinity,
    });
    $("#search_by1").hide();
    list_inv('storage');
    $("h2").css("color", "#17a2b8");
    $(document).on("change", "#search_by", function () {
        var getSelect = $("#search_by").val();
        if (getSelect != null) {
            $("#search_by1").show();
        } else {
            $("#search_by1").hide();
        }
    });
    $("#kt_datepicker_3").flatpickr({
        enableTime: true,
        dateFormat: "Y-m-d H:i:s",
    });
    $("#kt_datepicker_4").flatpickr({
        enableTime: true,
        dateFormat: "Y-m-d H:i:s",
    });
});

$(document).on("click", "#btnPicking", function () {
    buttonAction($(this),"#modalLarge2");
    $('select').select2({
        minimumResultsForSearch: Infinity,
    });
    $("#search_by1").hide();
    $("#search_created_at").hide();
    list_inv('picking');
    $("h2").css("color", "#a020f0");
    $(document).on("change", "#search_by", function () {
        var getSelect = $("#search_by").val();
        if (getSelect != null) {
            $("#search_by1").show();
        } else {
            $("#search_by1").hide();
        }
        if (getSelect == "created_at") {
            $("#search_by1").hide();
            $("#search_created_at").show();
        } else {
            $("#search_created_at").hide();
        }
    });
    $("#kt_datepicker_3").flatpickr({
        enableTime: true,
        dateFormat: "Y-m-d H:i:s",
    });
    $("#created_at").flatpickr({
        enableTime: false,
        dateFormat: "Y-m-d",
        mode: "range",
    });
    $("#kt_datepicker_4").flatpickr({
        enableTime: true,
        dateFormat: "Y-m-d H:i:s",
    });
});

$(document).on("click", "#btnPacking", function () {
    buttonAction($(this),"#modalLarge2");
    $('select').select2({
        minimumResultsForSearch: Infinity,
    });
    $("#search_by1").hide();
    $("#search_created_at").hide();
    list_inv('packing');
    $("h2").css("color", "#E75480");
    $(document).on("change", "#search_by", function () {
        var getSelect = $("#search_by").val();
        if (getSelect != null) {
            $("#search_by1").show();
        } else {
            $("#search_by1").hide();
        }
        if (getSelect == "created_at") {
            $("#search_by1").hide();
            $("#search_created_at").show();
        } else {
            $("#search_created_at").hide();
        }
    });
    $("#kt_datepicker_3").flatpickr({
        enableTime: true,
        dateFormat: "Y-m-d H:i:s",
    });
    $("#created_at").flatpickr({
        enableTime: false,
        dateFormat: "Y-m-d",
        mode: "range",
    });
    $("#kt_datepicker_4").flatpickr({
        enableTime: true,
        dateFormat: "Y-m-d H:i:s",
    });
});

$(document).on("click", "#btnShipping", function () {
    buttonAction($(this),"#modalLarge2");
    $('select').select2({
        minimumResultsForSearch: Infinity,
    });
    $("#search_by1").hide();
    $("#search_created_at").hide();
    list_inv('shipping');
    $("h2").css("color", "#29465B");
    $(document).on("change", "#search_by", function () {
        var getSelect = $("#search_by").val();
        if (getSelect != null) {
            $("#search_by1").show();
        } else {
            $("#search_by1").hide();
        }
        if (getSelect == "created_at") {
            $("#search_by1").hide();
            $("#search_created_at").show();
        } else {
            $("#search_created_at").hide();
        }
    });
    $("#kt_datepicker_3").flatpickr({
        enableTime: true,
        dateFormat: "Y-m-d H:i:s",
    });
    $("#created_at").flatpickr({
        enableTime: false,
        dateFormat: "Y-m-d",
        mode: "range",
    });
    $("#kt_datepicker_4").flatpickr({
        enableTime: true,
        dateFormat: "Y-m-d H:i:s",
    });
});

$(document).on("click", "#btnExportReceiving", function () {
    if ($("select[name=warehouse_id]").val() != null) {
        var warehouseid = $("select[name=warehouse_id]").val();
    } else {
        var warehouseid = "";
    }

    if ($("select[name=status_id]").val() != null) {
        var statusid = $("select[name=status_id]").val();
    } else {
        var statusid = "";
    }

    if ($("select[name=search_by]").val() != null) {
        var searchby = $("select[name=search_by]").val();
    } else {
        var searchby = "";
    }
    
    var searchby1 = $("input[name=search_by1]").val();
    var from = $("input[name=from_val]").val();
    var to = $("input[name=to_val]").val();

    window.location.href = $(this).parent().parent().parent().find('form').data("url") + '?warehouseid='+ warehouseid +'&statusid='+ statusid +'&searchby='+ searchby +'&searchby1=' + searchby1 +'&from=' + from +'&to=' + to;

});

$(document).on("click", "#btnExportPutaway", function () {
    if ($("select[name=warehouse_id]").val() != null) {
        var warehouseid = $("select[name=warehouse_id]").val();
    } else {
        var warehouseid = "";
    }

    if ($("select[name=status_id]").val() != null) {
        var statusid = $("select[name=status_id]").val();
    } else {
        var statusid = "";
    }

    if ($("select[name=search_by]").val() != null) {
        var searchby = $("select[name=search_by]").val();
    } else {
        var searchby = "";
    }
    var searchby1 = $("input[name=search_by1]").val();
    var from = $("input[name=from_val]").val();
    var to = $("input[name=to_val]").val();

    window.location.href = $(this).parent().parent().parent().find('form').data("url") + '?warehouseid='+ warehouseid +'&statusid='+ statusid +'&searchby='+ searchby +'&searchby1=' + searchby1 +'&from=' + from +'&to=' + to;

});

$(document).on("click", "#btnExportStorage", function () {
    if ($("select[name=warehouse_id]").val() != null) {
        var warehouseid = $("select[name=warehouse_id]").val();
    } else {
        var warehouseid = "";
    }

    if ($("select[name=search_by]").val() != null) {
        var searchby = $("select[name=search_by]").val();
    } else {
        var searchby = "";
    }
    var searchby1 = $("input[name=search_by1]").val();

    window.location.href = $(this).parent().parent().parent().find('form').data("url") + '?warehouseid='+ warehouseid +'&searchby='+ searchby +'&searchby1=' + searchby1;
});

$(document).on("click", "#btnExportPicking", function () {
    if ($("select[name=warehouse_id]").val() != null) {
        var warehouseid = $("select[name=warehouse_id]").val();
    } else {
        var warehouseid = "";
    }

    if ($("select[name=status_id]").val() != null) {
        var statusid = $("select[name=status_id]").val();
    } else {
        var statusid = "";
    }

    if ($("select[name=search_by]").val() != null) {
        var searchby = $("select[name=search_by]").val();
    } else {
        var searchby = "";
    }
    var searchby1 = $("input[name=search_by1]").val();
    var from = $("input[name=from_val]").val();
    var to = $("input[name=to_val]").val();

    window.location.href = $(this).parent().parent().parent().find('form').data("url") + '?warehouseid='+ warehouseid +'&statusid='+ statusid +'&searchby='+ searchby +'&searchby1=' + searchby1 +'&from=' + from +'&to=' + to;
});

$(document).on("click", "#btnExportPacking", function () {
    if ($("select[name=warehouse_id]").val() != null) {
        var warehouseid = $("select[name=warehouse_id]").val();
    } else {
        var warehouseid = "";
    }

    if ($("select[name=status_id]").val() != null) {
        var statusid = $("select[name=status_id]").val();
    } else {
        var statusid = "";
    }

    if ($("select[name=search_by]").val() != null) {
        var searchby = $("select[name=search_by]").val();
    } else {
        var searchby = "";
    }
    var searchby1 = $("input[name=search_by1]").val();
    var from = $("input[name=from_val]").val();
    var to = $("input[name=to_val]").val();

    window.location.href = $(this).parent().parent().parent().find('form').data("url") + '?warehouseid='+ warehouseid +'&statusid='+ statusid +'&searchby='+ searchby +'&searchby1=' + searchby1 +'&from=' + from +'&to=' + to;
});

$(document).on("click", "#btnExportShipping", function () {
    if ($("select[name=warehouse_id]").val() != null) {
        var warehouseid = $("select[name=warehouse_id]").val();
    } else {
        var warehouseid = "";
    }

    if ($("select[name=status_id]").val() != null) {
        var statusid = $("select[name=status_id]").val();
    } else {
        var statusid = "";
    }

    if ($("select[name=search_by]").val() != null) {
        var searchby = $("select[name=search_by]").val();
    } else {
        var searchby = "";
    }
    var searchby1 = $("input[name=search_by1]").val();
    var from = $("input[name=from_val]").val();
    var to = $("input[name=to_val]").val();

    window.location.href = $(this).parent().parent().parent().find('form').data("url") + '?warehouseid='+ warehouseid +'&statusid='+ statusid +'&searchby='+ searchby +'&searchby1=' + searchby1 +'&from=' + from +'&to=' + to;
});

$(document).on('click','#btnCloseModalFullscreen',function(){
    $("#modalLarge2").modal("hide");
});

function list_inv(type){
    switch (type) {
        case 'receiving':
        var baseurl = base_url() + 'inventory_warehouse/show_receiving';
        var tableYudha = 'show_receiving';
        var column = [
            { "data": "po_number" },
            { "data": "brand_name" },
            { "data": "supplier_name" },
            { "data": "publisher_name" },
            { "data": "created_at" },
            { "data": "qty" },
            { "data": "qty_receiving" },
            { "data": "lookup_name" },
        ];
        ajax_crud_table_without_number_Custom(baseurl,column,tableYudha);
        break;
        case 'putaway':
        var baseurl = base_url() + 'inventory_warehouse/show_putaway';
        var tableYudha = 'show_putaway';
        var column = [
            { "data": "po_number" },
            { "data": "brand_name" },
            { "data": "supplier_name" },
            { "data": "publisher_name" },
            { "data": "created_at" },
            { "data": "qty" },
            { "data": "qty_receiving" },
            { "data": "qty_putaway" },
            { "data": "lookup_name" },
        ];
        ajax_crud_table_without_number_Custom(baseurl,column,tableYudha);
        break;
        case 'storage':
        var baseurl = base_url() + 'inventory_warehouse/show_storage';
        var tableYudha = 'show_storage';
        var column = [
            { "data": "sku" },
            { "data": "product_name" },
            { "data": "brand_name" },
            { "data": "category_name" },
            { "data": "product_size" },
            { "data": "qty" },
        ];
        ajax_crud_table_without_number_Custom(baseurl,column,tableYudha);
        break;
        case 'packing':
        var baseurl = base_url() + 'inventory_warehouse/show_packing';
        var tableYudha = 'show_packing';
        var column = [
            { "data": "purchase_code" },
            { "data": "customer_name" },
            { "data": "customer_email" },
            { "data": "created_at" },
            { "data": "qty" },
            { "data": "qty_packing" },
            { "data": "assignee" },
            { "data": "lookup_name" },
        ];
        ajax_crud_table_without_number_Custom(baseurl,column,tableYudha);
        break;
        case 'picking':
        var baseurl = base_url() + 'inventory_warehouse/show_picking';
        var tableYudha = 'show_picking';
        var column = [
            { "data": "purchase_code" },
            { "data": "customer_name" },
            { "data": "customer_email" },
            { "data": "created_at" },
            { "data": "qty" },
            { "data": "qty_picking" },
            { "data": "assignee" },
            { "data": "lookup_name" },
        ];
        ajax_crud_table_without_number_Custom(baseurl,column,tableYudha);
        break;
        case 'shipping':
        var baseurl = base_url() + 'inventory_warehouse/show_shipping';
        var tableYudha = 'show_shipping';
        var column = [
            { "data": "purchase_code" },
            { "data": "customer_name" },
            { "data": "customer_email" },
            { "data": "created_at" },
            { "data": "qty" },
            { "data": "qty_shipping" },
            { "data": "assignee" },
            { "data": "lookup_name" },
        ];
        ajax_crud_table_without_number_Custom(baseurl,column,tableYudha);
        break;
        default:
    break;
    }
    
}

function reloadDatatables1() {
	addDraw();
	tbl1.ajax.reload();
}

$(document).on("click", "#btnStorageLog", function () {
    buttonAction($(this),"#modalLarge2");
    $("h2").css("color", "#17a2b8");
    var baseurl = base_url() + 'inventory_warehouse/show_storage_log';
        var tableYudha = 'storage_log';
        var column = [
            { "data": "trx_number" },
            { "data": "created_at" },
            { "data": "trx_type" },
            { "data": "sku" },
            { "data": "qty_trx" },
            { "data": "qty_old" },
            { "data": "qty_new" },
        ];
        ajax_crud_table_without_number_Custom(baseurl,column,tableYudha);
});

$(document).on("click", "#btnReceivingLog", function () {
    buttonAction($(this),"#modalLarge2");
    $("h2").css("color", "#5cb85c");
    var baseurl = base_url() + 'inventory_warehouse/show_receiving_log';
        var tableYudha = 'receiving_log';
        var column = [
            { "data": "po_number" },
            { "data": "created_at" },
            { "data": "sku" },
            { "data": "qty" },
            { "data": "qty_receiving" },
        ];
        ajax_crud_table_without_number_Custom(baseurl,column,tableYudha);
    
});

$(document).on("click", "#btnPutawayLog", function () {
    buttonAction($(this),"#modalLarge2");
    $("h2").css("color", "#FFAE42");
    var baseurl = base_url() + 'inventory_warehouse/show_putaway_log';
        var tableYudha = 'putaway_log';
        var column = [
            { "data": "po_number" },
            { "data": "created_at" },
            { "data": "sku" },
            { "data": "qty" },
            { "data": "qty_receiving" },
            { "data": "qty_putaway" },
        ];
        ajax_crud_table_without_number_Custom(baseurl,column,tableYudha);

});

$(document).on("click", "#btnPackingLog", function () {
    buttonAction($(this),"#modalLarge2");
    $("h2").css("color", "#E75480");
    var baseurl = base_url() + 'inventory_warehouse/show_packing_log';
        var tableYudha = 'packing_log';
        var column = [
            { "data": "purchase_code" },
            { "data": "created_at" },
            { "data": "sku" },
            { "data": "qty" },
            { "data": "qty_packing" },
        ];
        ajax_crud_table_without_number_Custom(baseurl,column,tableYudha);
    
});

$(document).on("click", "#btnPickingLog", function () {
    buttonAction($(this),"#modalLarge2");
    $("h2").css("color", "#a020f0");
    var baseurl = base_url() + 'inventory_warehouse/show_picking_log';
        var tableYudha = 'picking_log';
        var column = [
            { "data": "purchase_code" },
            { "data": "created_at" },
            { "data": "sku" },
            { "data": "qty" },
            { "data": "qty_picking" },
        ];
        ajax_crud_table_without_number_Custom(baseurl,column,tableYudha);
    
});

$(document).on("click", "#btnShippingLog", function () {
    buttonAction($(this),"#modalLarge2");
    $("h2").css("color", "#29465B");
    var baseurl = base_url() + 'inventory_warehouse/show_shipping_log';
        var tableYudha = 'shipping_log';
        var column = [
            { "data": "purchase_code" },
            { "data": "created_at" },
            { "data": "sku" },
            { "data": "qty" },
            { "data": "qty_shipping" },
        ];
        ajax_crud_table_without_number_Custom(baseurl,column,tableYudha);
    
});