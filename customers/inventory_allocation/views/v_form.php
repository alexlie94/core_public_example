<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<form id="form" class="form" data-url="<?=$form?>">
    <div class="form-group">
        <div class="fv-row form-group row mb-5">
            <div class="col-md-3">
                <label class="form-label">Product ID</label>
                <input type="text" class="form-control form-control-solid mb-2 mb-md-0" value="<?=$productid?>" name="productID" id="productID" readonly>
            </div>
        </div>
        <div class="fv-row form-group row mb-5">
            <div class="col-md-3">
                <label class="form-label">Product</label>
                <input type="text" class="form-control form-control-solid mb-2 mb-md-0" value="<?=$productName?>" name="productName" id="productName" readonly>
            </div>
        </div>
    </div>
    <div class="row">
        <?=$table?>
    </div>
</form>