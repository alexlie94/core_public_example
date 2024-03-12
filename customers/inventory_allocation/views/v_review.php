<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<div class="row flex-nowrap overflow-auto">
    <form id="formUpload">
        <table id="tableUpload" class="table table-striped border rounded gy-5 gs-5">
            <thead>
                <tr class="fw-bold fs-6 text-gray-800">
                <?php 
                    for($i=0;$i < count($data['thead']); $i++){
                        echo "<th scope=\"col\">{$data['thead'][$i]}</th>";
                    }
                ?>
                </tr>
            </thead>
            <tbody>
                <?php

                $i = 1;
                foreach($data['tbody'] as $ky => $val){
                    $id = "id_{$i}";
                    $action = str_replace("{{dataInput}}","data-id=\"{$id}\" data-title=\"Delete Row\" data-textconfirm = \"Are You Sure Want to Remove This Row ? \" data-type=\"confirm\" ",$val['action']);

                    $getSource = $data['getSource'];
                    $optionSource = "<option value=\"\"></option>";
                    foreach($getSource as $key => $value){
                        $optionSource .= "<option value=\"{$value->id}\" ".($val['sourceName'] == $value->id ? "selected" : "").">{$value->source_name}</option>";
                    }

                    $getChannel = $data['getChannel'];
                    $optionChannel = "<option value=\"\"></option>";
                    foreach($getChannel as $key => $value){
                        $optionChannel .= "<option value=\"{$value->id}\" ".($val['channelName'] == $value->id ? "selected" : "").">{$value->channel_name}</option>";
                    }

                    echo "<tr id=\"{$id}\" class=\"fs-6\">";
                    echo "<td><input type=\"text\" class=\"form-control\" name=\"sku[]\" value=\"{$val['sku']}\">{$val['errorSku']}</td>";
                    echo "<td><input type=\"text\" class=\"form-control form-control-solid productSize\" name=\"size[]\" value=\"{$val['productSize']}\" readonly=\"readonly\"></td>";
                    echo "<td><input type=\"text\" class=\"form-control form-control-solid productStorage\" name=\"storage[]\" value=\"{$val['storage']}\" readonly=\"readonly\"></td>";
                    echo "<td><select class=\"form-select selectIn\" data-library='select2-single' name='sourceName[]'>{$optionSource}</select>{$val['errorSourceName']}</td>";
                    echo "<td><select class=\"form-select selectIn\" data-library='select2-single' name='channelName[]'>{$optionChannel}</select>{$val['errorChannelName']}</td>";
                    echo "<td><input type=\"text\" class=\"form-control form-control-solid productAvailable\" name=\"available[]\" value=\"{$val['availableQty']}\" readonly=\"readonly\"></td>";
                    echo "<td><input type=\"text\" class=\"form-control\" name=\"reserved[]\" value=\"{$val['reserved']}\">{$val['errorReserved']}</td>";
                    echo "<td>{$action}</td>";
                    echo "<td class=\"icontd\">{$val['statusRow']}</td>";
                    echo "</tr>";
                    $i++;
                }

                ?>
            </tbody>
        </table>
    </form>
</div>
