$(document).ready(function(){
    var baseurl = base_url() + 'master_warehouse/show';
    var column = [
        { "data": "id" },
        { "data": "warehouse_code" },
        { "data": "warehouse_name" },
        { "data": "email" },
        { "data": "address" },
        { "data": "phone" },
        { "data": "action" ,"width" : "17%"},
    ];

    ajax_crud_table(baseurl,column);

    sweetAlertConfirm();
    libraryInput();

    $(document).on("click", "#buttonDeleted", function () {

        $(this).parent().parent().addClass('deleted');

        $('#kt_datatable_vertical_scroll').DataTable().rows('.deleted').remove().draw();
    });

    $(document).on("click", ".btnEdit", function () {
        buttonAction($(this));
    });

    $(document).on("click", "#btnAdd", function () {
        buttonAction($(this));
        $('#show_mass_upload').hide();
        
        $('#button_mass_upload').click(function(){
            $('#show_mass_upload').show();
            $('#form_master_warehouse').hide();
            $('#btnProcessModal').hide();
            $('#button_mass_upload').hide();
            $('#formatError').hide();
        });

    });

    function customSplit(text) {
        const parts = [];
        let currentPart = "";
        let withinQuotes = false;
    
        for (let i = 0; i < text.length; i++) {
          const char = text[i];
    
          if (char === '"') {
            withinQuotes = !withinQuotes;
            currentPart += char;
          } else if (char === "," && !withinQuotes) {
            parts.push(currentPart.trim().replace(/^"(.*)"$/, "$1"));
            currentPart = "";
          } else {
            currentPart += char;
          }
        }
    
        if (currentPart !== "") {
          parts.push(currentPart.trim().replace(/^"(.*)"$/, "$1"));
        }
    
        return parts;
    }

    $(document).on("change", "#upload_data", function () {
        $("#btnProcessModal").hide();
    
        $("#kt_datatable_vertical_scroll tbody").remove();
    
        let file = this.files[0];
    
        if (typeof file != "undefined") {
            const reader = new FileReader();
            reader.readAsBinaryString(file);
            reader.onload = function (event) {
                const csvData = event.target.result;
                const lines = csvData.split("\n");
        
                const headers = lines[0].split(",");
                const output = [];
                const headerSplit = [];
        
                for (let i = 0; i < headers.length; i++) {
                const header = headers[i].replace(/\s+/g, "_");
                headerSplit.push(header);
                }
        
                for (let i = 0; i < headerSplit.length; i++) {
                headerSplit[i] = headerSplit[i].replace(/_+$/, "");
                }
        
                for (let i = 1; i < lines.length; i++) {
                const data = lines[i];
                const row = {};
        
                for (let j = 0; j < headerSplit.length; j++) {
                    row[headerSplit[j]] = customSplit(data)[j];
                }
                output.push(row);
                }
        
                const headersFormatCsv = [
                    'WAREHOUSE_NAME',
                    'EMAIL',
                    'ADDRESS',
                    'PHONE',
                ];

                var no =1;
                var no2 =1;
        
                let dataUpload = [];
        
                if (headersFormatCsv.toString() == headerSplit.toString()) {
                    dataUpload.push({ name: "_token", value: getCookie() });
                    dataUpload.push({
                        name: "dataUpload",
                        value: JSON.stringify(output),
                    });
                    $.ajax({
                        url: base_url() + "master_warehouse/upload_data",
                        method: "POST",
                        dataType: "JSON",
                        async: false,
                        data: dataUpload,
                        success: function (result) {
                            let getJsonData = result.data;
            
                            $("#formatError").hide();
                            let check_validate = [];
                            for (let i = 0; i < getJsonData.length; i++) {
                                check_validate.push(getJsonData[i].validate);
                            }
                            for (let i = 0; i < getJsonData.length; i++) {
                                var tr_table =`<tr>`;
                                    tr_table +=`
                                        <td>`+no+`</td>
                                        <td> <input type="hidden" name="warehouse_name_1[]" value="`+getJsonData[i].warehouse_name+`">`+getJsonData[i].warehouse_name+`</td>
                                        <td> <input type="hidden" name="email_1[]" value="`+getJsonData[i].email+`">`+getJsonData[i].email+`</td>
                                        <td> <input type="hidden" name="address_1[]" value="`+getJsonData[i].address+`">`+getJsonData[i].address+`</td>
                                        <td> <input type="hidden" name="phone_1[]" value="`+getJsonData[i].phone+`">`+getJsonData[i].phone+`</td>
                                    </tr>`;

                                var tr_table2 =`<tr>`;
                                    tr_table2 +=`
                                        <td>`+no2+`</td>
                                        <td>` +getJsonData[i].warehouse_name+`</td>
                                        <td>` +getJsonData[i].email+`</td>
                                        <td>` +getJsonData[i].address+`</td>
                                        <td>` +getJsonData[i].phone+`</td>
                                    </tr>`;

                            

                                if(check_validate.includes(2)){
                                    $("#kt_datatable_vertical_scroll").append(tr_table2);
                                    $('#btnProcessModal').hide();
                                }else{
                                    $("#kt_datatable_vertical_scroll").append(tr_table);
                                    $('#btnProcessModal').show();
                                }

                                no++;
                                no2++;
                            }
                        },
                    });
                } else {
                    $("#formatError").show();
                }
            };
        } else {
          $("#show_data_preview").html("");
        }
    });
    
    modalClose();
    process();
    
});