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

                $dataDefaultImage = [
                    "","Y","N"
                ];

                $dataStatus = [
                    "",'MAIN','SELECTED',
                ];
                
                $i = 1;

                foreach($data['tbody'] as $ky => $val){

                    $defaultImage = $val['defaultImage'];
                    $optionDefaultImage = "";
                    for($k=0;$k < count($dataDefaultImage);$k++){
                        $optionDefaultImage .= "<option value=\"{$dataDefaultImage[$k]}\" ".($defaultImage == $dataDefaultImage[$k] ? "selected" : "").">{$dataDefaultImage[$k]}</option>";
                    }

                    $status = $val['status'];
                    $optionStatus = "";
                    for($j=0;$j < count($dataStatus);$j++){
                        $optionStatus .= "<option value=\"{$dataStatus[$j]}\" ".($status == $dataStatus[$j] ? "selected" : "").">{$dataStatus[$j]}</option>";
                    }

                    $id = "id_{$i}";
                    $action = str_replace("{{dataInput}}","data-id=\"{$id}\" data-title=\"Delete Row\" data-textconfirm = \"Are You Sure Want to Remove This Row Product ID {$val['productId']} in Sequence {$val['sequence']} ? \" data-type=\"confirm\" ",$val['action']);

                    echo "<tr id=\"{$id}\" class=\"fs-6\">";
                    echo "<td><input type=\"text\" class=\"form-control\" name=\"sequence[]\" value=\"{$val['sequence']}\">{$val['errorSequence']}</td>";
                    echo "<td><input type=\"text\" class=\"form-control\" name=\"productid[]\" value=\"{$val['productId']}\">{$val['errorProduct']}</td>";
                    echo "<td><input type=\"text\" class=\"form-control form-control-solid productName\" name=\"productName[]\" value=\"{$val['productName']}\" readonly=\"readonly\"></td>";
                    echo "<td><input type=\"text\" class=\"form-control\" name=\"sourceName[]\" value=\"{$val['sourceName']}\">{$val['errorSourceName']}</td>";
                    echo "<td><input type=\"text\" class=\"form-control\" name=\"channelName[]\" value=\"{$val['channelName']}\">{$val['errorChannelName']}</td>";
                    echo "<td><select class=\"form-select selectIn\" data-library='select2-single' name='defaultImage[]'>{$optionDefaultImage}</select>{$val['errorDefaultImage']}</td>";
                    echo "<td><input type=\"text\" class=\"form-control\" name=\"imageName[]\" value=\"{$val['imageName']}\">{$val['errorImageName']}</td>";
                    echo "<td><select class=\"form-select selectIn\" data-library='select2-single' name='status[]'>{$optionStatus}</select>{$val['errorStatus']}</td>";
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
