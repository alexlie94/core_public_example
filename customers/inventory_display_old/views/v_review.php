<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<div class="row flex-nowrap overflow-auto">
    <table id="tableUpload" class="table table-striped border rounded gy-5 gs-7">
        <thead>
            <tr class="fw-semibold fs-6 text-gray-800">
                <th>Sequence</th>
                <th>Product ID</th>
                <th class="required">Product Name</th>
                <th class="required">Source Name</th>
                <th class="required">Channel Name</th>
                <th>Default Image</th>
                <th>Image Name</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 1;
            foreach ($data as $ky => $val) {
                echo "<tr id=\"id_{$i}\">";
                echo "<td data-id=\"id_{$i}\" style=\"vertical-align: middle;\"><input type=\"hidden\" name=\"\" class=\"form-control\" value=\"{$val['sequence']}\"><span style=\"font-size:1.1rem\">{$val['sequence']}</span></td>";
                echo "<td style=\"vertical-align: middle;\"><input type=\"hidden\" name=\"\" class=\"form-control\" value=\"{$val['productID']}\"><span style=\"font-size:1.1rem\">{$val['productID']}</span></td>";
                echo "<td><select class=\"form-select selectIn\" data-library='select2-single' name='productName'><option value=\"tes\">tes</option><option value=\"1\">2</option></select><div class=\"fv-plugins-message-container invalid-feedback\"></div></td>";
                echo "<td><select class=\"form-select selectIn\" data-library='select2-single' name='productName'><option value=\"tes\">tes</option><option value=\"1\">2</option></select></td>";
                echo "<td><select class=\"form-select selectIn\" data-library='select2-single' name='productName'><option value=\"tes\">tes</option><option value=\"1\">2</option></select></td>";
                echo "<td><select class=\"form-select selectIn\" data-library='select2-single' name='productName'><option value=\"tes\">tes</option><option value=\"1\">2</option></select></td>";
                echo "<td><input type=\"text\" name=\"\" class=\"form-control\" value=\"{$val['imageName']}\"></td>";
                echo "<td><select class=\"form-select selectIn\" data-library='select2-single' name='productName'><option value=\"tes\">tes</option><option value=\"1\">2</option></select></td>";
                echo '<td style=\"vertical-align: middle;\"><a href="#" class="btn btn-icon btn-danger me-2 mb-2">
                        <i class="bi bi-trash fs-4"></i>
                    </a></td>';
                echo '<td><span class="svg-icon svg-icon-2x">
                        <svg xmlns="http://www.w3.org/2000/svg" style="margin-top: 9px;" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <polygon points="0 0 24 0 24 24 0 24"/>
                                        <path d="M9.26193932,16.6476484 C8.90425297,17.0684559 8.27315905,17.1196257 7.85235158,16.7619393 C7.43154411,16.404253 7.38037434,15.773159 7.73806068,15.3523516 L16.2380607,5.35235158 C16.6013618,4.92493855 17.2451015,4.87991302 17.6643638,5.25259068 L22.1643638,9.25259068 C22.5771466,9.6195087 22.6143273,10.2515811 22.2474093,10.6643638 C21.8804913,11.0771466 21.2484189,11.1143273 20.8356362,10.7474093 L17.0997854,7.42665306 L9.26193932,16.6476484 Z" fill="#008000" fill-rule="nonzero" opacity="0.3" transform="translate(14.999995, 11.000002) rotate(-180.000000) translate(-14.999995, -11.000002) "/>
                                        <path d="M4.26193932,17.6476484 C3.90425297,18.0684559 3.27315905,18.1196257 2.85235158,17.7619393 C2.43154411,17.404253 2.38037434,16.773159 2.73806068,16.3523516 L11.2380607,6.35235158 C11.6013618,5.92493855 12.2451015,5.87991302 12.6643638,6.25259068 L17.1643638,10.2525907 C17.5771466,10.6195087 17.6143273,11.2515811 17.2474093,11.6643638 C16.8804913,12.0771466 16.2484189,12.1143273 15.8356362,11.7474093 L12.0997854,8.42665306 L4.26193932,17.6476484 Z" fill="#008000" fill-rule="nonzero" transform="translate(9.999995, 12.000002) rotate(-180.000000) translate(-9.999995, -12.000002) "/>
                                </g>
                        </svg>
                    </span></td>';
                echo '</tr>';

                $i++;
            }
            ?>
        </tbody>
    </table>
</div>
