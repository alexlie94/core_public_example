$(document).ready(function(){
    var baseurl = base_url() + 'lookup_values/show';
    var column = [
        { "data": "id" },
        { "data": "lookup_code"},
        { "data": "lookup_name" },
        { "data": "lookup_config" },
        { "data": "action" ,"width" : "17%"},
    ];

    ajax_crud_table(baseurl,column);
    sweetAlertConfirm();
    libraryInput();
    
    $(document).on("click", "#btnAdd", function () {
        buttonAction($(this));

        $('#show_mass_upload').hide();
            
        $('#button_mass_upload').click(function(){
            $('#show_mass_upload').show();
            $('#form_lookup_values').hide();
            $('#btnProcessModal').hide();
            $('#button_mass_upload').hide();
            $('#formatError').hide();
        });

    });
    
    $(document).on("change", "#upload_data", function () {
        var file = this.files[0];
        $("#btnProcessModal").hide();
        $('#kt_datatable_vertical_scroll tbody').empty();

        if(typeof file != "undefined" ){
        var reader = new FileReader();
        reader.readAsBinaryString(file);
        reader.onload = function(dataAll) {

            var rows = dataAll.target.result.split("\n");
                var jsonData = [];
                var headers = [];
                for (var i = 0; i < rows.length; i++) {
                    var cells = rows[i].split(",");
                    var rowData = {};
                    for(var j=0;j<cells.length;j++){
                        if(i==0){
                            var headerName = cells[j].trim();
                            headers.push(headerName);
                        }else{
                            var key = headers[j];
                            if(key){
                                rowData[key] = cells[j].trim();
                            }
                        }
                    }
                    
                    if(i!=0){
                        jsonData.push(rowData);
                    }
                }

            var headersFormatCsv = [
                'LOOKUP CODE',
                'LOOKUP NAME',
                'LOOKUP CONFIG',
            ];

            var no =1;
            var no2 =1;

            var dataUpload=[];

            dataUpload.push({ name: "_token", value: getCookie() });
            dataUpload.push({ name: "dataUpload", value: JSON.stringify(jsonData) });
            $.ajax({
                url: base_url() + 'lookup_values/upload_data',
                method: "POST",
                dataType: "JSON",
                async: false,
                data: dataUpload,
                success: function (result) {
                    
                    var getJsonData = result.data;
                    console.log(result.data);

                    if(headersFormatCsv.toString() == headers.toString()){
    
                        $('#formatError').hide();
                
                        var check_validate = [];

                        for (let i = 0; i < getJsonData.length; i++) {
                            check_validate.push(getJsonData[i].validate);
                        }

                        for (let i = 0; i < getJsonData.length; i++) {
                            var tr_table =`<tr>`;
                                tr_table +=`
                                    <td>`+no+`</td>
                                    <td> <input type="hidden" name="lookup_code_1[]" value="`+getJsonData[i].lookup_code+`">`+getJsonData[i].lookup_code+`</td>
                                    <td> <input type="hidden" name="lookup_name_1[]" value="`+getJsonData[i].lookup_name+`">`+getJsonData[i].lookup_name+`</td>
                                    <td> <input type="hidden" name="lookup_config_1[]" value="`+getJsonData[i].lookup_config+`">`+getJsonData[i].lookup_config+`</td>
                                </tr>`;

                            var tr_table2 =`<tr>`;
                                tr_table2 +=`
                                    <td>`+no2+`</td>
                                    <td>` +getJsonData[i].lookup_code +`</td>
                                    <td>` +getJsonData[i].lookup_name+`</td>
                                    <td>` +getJsonData[i].lookup_config+`</td>
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

                    }else{
                        tableRows.clear().draw();
                        $('#formatError').show();
                    }
                }
            });
            
            }
        }else {
            $('#show_data_preview').html('');
        }
    });

    $(document).on("click", ".btnEdit", function () {
        buttonAction($(this));
    });

    $(document).on("click", "#buttonDeleted", function () {

        $(this).parent().parent().addClass('deleted');

        $('#kt_datatable_vertical_scroll').DataTable().rows('.deleted').remove().draw();
    });

    modalClose();
    process();

});