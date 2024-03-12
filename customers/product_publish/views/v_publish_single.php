<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<div class="d-flex flex-column flex-column-fluid modal-upload">

    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6" style="margin-bottom: -30px;">
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                    <div class="badge badge-light-info" style="font-size: 20px;"><?= $titlePage ?></div>
                </h1>
            </div>
        </div>
    </div>

    <div id=" kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container ">

            <div class="card shadow-sm sticky-custom p-4">
                <div id="kt_app_toolbar_container" class="app-container d-flex flex-stack flex-wrap">
                    <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
                        <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">

                            <div class="d-flex justify-content-between">
                                <span>
                                    <h2>
                                        <img alt="" src="<?= check_image_source($sources) ?>" width="30" style="margin-top: -10px;">
                                        <span><?= $channels ?></span>
                                    </h2>
                                </span>
                            </div>

                        </div>

                        <div class="d-flex align-items-center gap-2 gap-lg-3">
                            <button type="button" id="proccess_publish" data-url="<?= $url_form ?>" class="btn btn-outline btn-outline-dashed btn-outline-success btn-active-light-success hover-scale btn-sm">
                                <i class="fa-solid fa-cloud-upload fs-4 me-2"></i>
                                Publish Product
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-content mt-6" id="myTabContent">
                <div class="tab-pane fade show active" id="<?= str_replace(' ', '_', $sources) ?>" role="tabpanel">
                    <form id="form" data-url="<?= $url_form ?>" class="form">

                        <input type="hidden" class="form-control" id="id" name="id" value="<?= isset($id) ? $id : '' ?>" />
                        <input type="hidden" id="products_id" name="products_id" value="<?= $product_id ?>">
                        <input type="hidden" id="products_name" name="products_name" value="<?= $product['product_name'] ?>">
                        <input type="hidden" id="sources_id" name="sources_id" value="<?= $sources_id ?>">
                        <input type="hidden" id="channels_id" name="channels_id" value="<?= $channels_id ?>">

                        <div class="card shadow-sm p-10">
                            <div id="kt_account_settings_email_preferences" class="collapse show">

                                <div class="row gy-0 mb-6 mb-xl-12">
                                    <div class="col-md-6">
                                        <div class="card card-md-stretch mb-md-0 mb-6">
                                            <div class="card-body">
                                                <div class="d-flex flex-stack">
                                                    <div class="card-body">
                                                        <h3 class="card-title align-items-start flex-column mb-7">
                                                            <span class="card-label fw-bold text-dark">Product Information</span>
                                                        </h3>

                                                        <div class="d-flex flex-stack">
                                                            <div class="d-flex align-items-center">
                                                                <div class="symbol symbol-60px me-5">
                                                                    <a href="#" class="symbol symbol-60px me-2" data-bs-toggle="tooltip" aria-label="Ana Stone" data-bs-original-title="Ana Stone" data-kt-initialized="1">
                                                                        <img src="<?= $image ?>" alt="">
                                                                    </a>
                                                                </div>

                                                                <div class="d-flex flex-column flex-grow-1 my-lg-0 my-2 pr-3">
                                                                    <a href="#" class="text-dark fw-bold text-hover-primary fs-5"><?= $product['product_name'] ?></a>
                                                                    <span class="text-muted fw-bold">
                                                                        <div class="badge badge-light-info">Incoming</div>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="d-flex flex-column w-100 mt-6">
                                                            <div class="text-muted fw-bold">
                                                                <a href="#">Category</a>
                                                                <span class="mx-3">|</span>
                                                                <?= $product['category_name'] ?>
                                                                <span class="mx-3">|</span>
                                                                <a href="#">Brand</a>
                                                                <span class="mx-3">|</span>
                                                                <?= $product['brand_name'] ?>
                                                                <span class="mx-3">|</span>
                                                                <a href="#">Price</a>
                                                                <span class="mx-3">|</span>Rp.
                                                            </div>
                                                        </div>

                                                        <div class="d-flex flex-column mt-8">
                                                            <div class="text-dark me-2 fw-bold pb-4">Image List</div>
                                                            <div class="d-flex">
                                                                <?php
                                                                $toLowerConvert = strtolower($sources);
                                                                $source_name = str_replace(' ', '_', $toLowerConvert);

                                                                if (isset($image_arr[$source_name][$channels])) {

                                                                    $res_image_name = $image_arr[$source_name][$channels];

                                                                    foreach ($res_image_name as $rImage) {

                                                                        $imageExplode = explode(':', $rImage);

                                                                        $associativeArray = [$imageExplode[0] => $imageExplode[1]];

                                                                        foreach ($associativeArray as $key_image => $val_status) {

                                                                            $image_file = check_image_file('./assets/uploads/products_image/', $key_image);
                                                                ?>
                                                                            <input type="hidden" value="<?= $key_image ?>" name="image_name[]">
                                                                            <div class="symbol symbol-40px mb-6" style="display: inline-block; margin-right: 6px;">
                                                                                <div class="symbol-label" style="background-image:url(<?= $image_file ?>)"></div>
                                                                            </div>
                                                                <?php }
                                                                    }
                                                                } ?>

                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="card card-md-stretch me-xl-3 mb-md-0 mb-6">
                                            <div class="card-body mt-7">
                                                <div class="d-flex flex-stack mb-7">
                                                    <div class="ms-0">
                                                        <h3 class="card-title align-items-start flex-column">
                                                            <span class="card-label fw-bold text-dark">Product Variant</span>
                                                        </h3>

                                                        <table class="table align-middle gy-3" id="kt_table_widget_5_table">
                                                            <thead>
                                                                <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                                                    <th>No</th>
                                                                    <th class="min-w-150px">SKU Code</th>
                                                                    <th>Stok</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="fw-bold text-gray-600">
                                                                <?php
                                                                $no = 1;
                                                                foreach ($product_variants as $res_sku) {
                                                                ?>
                                                                    <input type="hidden" value="<?= $res_sku->sku ?>" name="sku[]">
                                                                    <input type="hidden" value="<?= empty($res_sku->qty) ? 0 : $res_sku->qty ?>" name="qty[]">
                                                                    <input type="hidden" value="<?= empty($res_sku->product_size) ? 0 : $res_sku->product_size ?>" name="size[]">
                                                                    <input type="hidden" value="<?= $res_sku->variant_color ?>" name="color_sku[]">
                                                                    <input type="hidden" value="<?= $res_sku->price ?>" name="price[]">
                                                                    <tr>
                                                                        <td style="vertical-align: middle;"><?= $no++ ?></td>
                                                                        <td class="text-dark text-hover-primary" style="vertical-align: middle;">
                                                                            <?= $res_sku->sku ?>
                                                                        </td>
                                                                        <td style="vertical-align: middle;">
                                                                            <?= empty($res_sku->qty) ? 0 : $res_sku->qty ?>
                                                                        </td>
                                                                    </tr>
                                                                <?php } ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="card card-md-stretch me-xl-3 mb-md-0 mb-6">
                                            <div class="card-body mt-7">
                                                <div class="d-flex flex-stack mb-7">
                                                    <div class="ms-0">
                                                        <h3 class="card-title align-items-start flex-column mb-6">
                                                            <span class="card-label fw-bold text-dark">Image Variant Color</span>
                                                        </h3>
                                                        <?php
                                                        foreach ($variants as $rImage) {

                                                            $default_color = $rImage->default_color;
                                                            $variant_color = $rImage->variant_color;
                                                            $image_file = check_image_file('./assets/uploads/products_image/', $rImage->image_name);
                                                        ?>
                                                            <input type="hidden" value="<?= $rImage->variant_color ?>" name="color_variation[]">
                                                            <input type="hidden" value="<?= $rImage->image_name ?>" name="image_color_variation[]">
                                                            <div class="d-flex align-items-center mb-7">
                                                                <div class="symbol symbol-40px me-5">
                                                                    <img src="<?= $image_file ?>" class="" alt="">
                                                                </div>

                                                                <div class="flex-grow-1">
                                                                    <a href="#" class="text-dark fw-bold text-hover-primary fs-6"><?= $variant_color ?></a>
                                                                </div>
                                                            </div>
                                                        <?php
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="fv-row mb-7">
                                        <label class="required fw-semibold fs-6 mb-4">Category</label>

                                        <div>
                                            <input type="hidden" id="ctg_id" name="ctg_id">

                                            <input type="text" id="category" name="category" class="form-control mb-3 mb-lg-0" placeholder="Select Category" value="" data-type="input" autocomplete="off" readonly />
                                            <div id="menu" class="rc-cascader-dropdown rc-cascader-dropdown-placement-bottomRight" style="display: none;width: fit-content;">
                                                <div>
                                                    <div class="rc-cascader-menus">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12 mb-7">
                                    <label class="required fw-semibold fs-6 mb-4">Brand</label>
                                    <select data-ctg_id="" class="form-select brand" id="brand" name="brand" data-control="select2" data-placeholder="Select an option">
                                    </select>
                                </div>

                                <div class="col-md-12 mb-7">
                                    <label class="required fw-semibold fs-6 mb-4">Condition</label>
                                    <select class="form-select condition" data-type='select' id="condition" name="condition" data-control="select2" data-kt-repeater="select2" data-hide-search="true" data-placeholder="Select an option" name="select_gender">
                                        <option value=""></option>
                                        <option value="new">
                                            New
                                        </option>
                                        <option value="used">
                                            Used
                                        </option>
                                    </select>
                                </div>

                                <div class="col-md-12 mb-7">
                                    <label class="required fw-semibold fs-6 mb-4">Description</label>
                                    <textarea type="text" id="desc" name="desc" class="form-control mb-3 mb-lg-0" rows="4" placeholder="Description" data-type="input" autocomplete="off"></textarea>
                                    <div class="text-muted fs-7" align="right">Please enter a minimum of 20 characters.</div>
                                </div>
                            </div>
                        </div>

                        <div class="card shadow-sm p-10 mt-6">
                            <div id="kt_account_settings_email_preferences" class="collapse show">
                                <div class="d-flex flex-wrap flex-stack">
                                    <h2 class="fs-2 fw-semibold my-2 text-dark">
                                        Product Shipping
                                    </h2>
                                </div>

                                <div class="col-md-3 mb-7">
                                    <label class="required fw-semibold fs-6 mb-4">Weight</label>
                                    <div class="input-group mb-5">
                                        <input type="text" onkeyup="formatCurrency(this)" id="weight" name="weight" class="form-control" aria-label="Recipient's username" aria-describedby="basic-addon2" />
                                        <span class="input-group-text" id="basic-addon2">gr</span>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4 mb-7">
                                        <label class="required fw-semibold fs-6 mb-4">Width</label>
                                        <div class="input-group mb-5">
                                            <input type="text" onkeyup="formatCurrency(this)" id="width" name="width" class="form-control" aria-label="Recipient's username" aria-describedby="basic-addon2" />
                                            <span class="input-group-text" id="basic-addon2">cm</span>
                                        </div>
                                    </div>

                                    <div class="col-md-4 mb-7">
                                        <label class="required fw-semibold fs-6 mb-4">Length</label>
                                        <div class="input-group mb-5">
                                            <input type="text" onkeyup="formatCurrency(this)" id="length" name="length" class="form-control" aria-label="Recipient's username" aria-describedby="basic-addon2" />
                                            <span class="input-group-text" id="basic-addon2">cm</span>
                                        </div>
                                    </div>

                                    <div class="col-md-4 mb-7">
                                        <label class="required fw-semibold fs-6 mb-4">Height</label>
                                        <div class="input-group mb-5">
                                            <input type="text" onkeyup="formatCurrency(this)" id="height" name="height" class="form-control" aria-label="Recipient's username" aria-describedby="basic-addon2" />
                                            <span class="input-group-text" id="basic-addon2">cm</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12 mb-7">
                                    <label class="required fw-semibold fs-6 mb-4">Shipping List</label>
                                    <select class="form-select shipping" id="shipping" name="shipping[]" data-control="select2" data-close-on-select="false" data-placeholder="Select an option" data-allow-clear="true" multiple="multiple">
                                    </select>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    #floatingToolbar {
        display: none;
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        background: rgb(255, 121, 121);
        background: linear-gradient(277deg, rgba(255, 121, 121, 1) 0%, rgba(254, 197, 167, 1) 100%);
        padding: 10px;
        border: 0;
        box-shadow: 0px 0px 2px rgba(0, 0, 0, 0.2);
    }

    .rc-cascader-dropdown {
        font-size: 12.5px;
        line-height: 1;
        background-color: #fff;
        box-shadow: 0 1px 6px rgba(0, 0, 0, .2);
        border-radius: 2px;
        box-sizing: border-box;
    }

    @-webkit-keyframes antCheckboxEffect {
        0% {
            transform: scale(1);
            opacity: 0.5;
        }

        100% {
            transform: scale(1.6);
            opacity: 0;
        }
    }

    @keyframes antCheckboxEffect {
        0% {
            transform: scale(1);
            opacity: 0.5;
        }

        100% {
            transform: scale(1.6);
            opacity: 0;
        }
    }

    .rc-cascader {
        width: 184px;
    }

    .rc-cascader-menus {
        display: flex;
        flex-wrap: nowrap;
        align-items: flex-start;
    }

    .rc-cascader-menus.rc-cascader-menu-empty .rc-cascader-menu {
        width: 100%;
        height: auto;
    }

    .rc-cascader-menu {
        font-size: 12.5px;
        min-width: 111px;
        height: 180px;
        margin: 0;
        margin: -4px 0;
        padding: 4px 0;
        overflow: auto;
        vertical-align: top;
        list-style: none;
        border-right: 1px solid #f0f0f0;
        -ms-overflow-style: -ms-autohiding-scrollbar;
    }

    .rc-cascader-menu-item {
        display: flex;
        flex-wrap: nowrap;
        align-items: center;
        padding: 5px 12px;
        overflow: hidden;
        line-height: 22px;
        white-space: nowrap;
        text-overflow: ellipsis;
        cursor: pointer;
        transition: all 0.3s;
    }

    .rc-cascader-menu-item:hover {
        background: #f5f5f5;
    }

    .rc-cascader-menu-item-disabled {
        color: rgba(0, 0, 0, 0.25);
        cursor: not-allowed;
    }

    .rc-cascader-menu-item-disabled:hover {
        background: transparent;
    }

    .rc-cascader-menu-empty .rc-cascader-menu-item {
        color: rgba(0, 0, 0, 0.25);
        cursor: default;
        pointer-events: none;
    }

    .rc-cascader-menu-item-active:not(.rc-cascader-menu-item-disabled),
    .rc-cascader-menu-item-active:not(.rc-cascader-menu-item-disabled):hover {
        font-weight: 600;
        background-color: #e6f7ff;
    }

    .rc-cascader-menu-item-content {
        flex: auto;
    }

    .rc-cascader-menu-item-expand .rc-cascader-menu-item-expand-icon,
    .rc-cascader-menu-item-loading-icon {
        margin-left: 4px;
        color: rgba(0, 0, 0, 0.45);
        font-size: 10px;
    }

    .rc-cascader-menu-item-disabled.rc-cascader-menu-item-expand .rc-cascader-menu-item-expand-icon,
    .rc-cascader-menu-item-disabled.rc-cascader-menu-item-loading-icon {
        color: rgba(0, 0, 0, 0.25);
    }

    .rc-cascader-menu-item-keyword {
        color: #ff4d4f;
    }

    .rc-cascader-rtl .rc-cascader-menu-item-expand-icon,
    .rc-cascader-rtl .rc-cascader-menu-item-loading-icon {
        margin-right: 4px;
        margin-left: 0;
    }

    .rc-cascader-rtl .rc-cascader-checkbox {
        top: 0;
        margin-right: 0;
        margin-left: 8px;
    }

    .rc-cascader-menu-item {
        line-height: 15px;
        padding: 5px 8px;
        letter-spacing: -0.01em;
    }

    .sticky-custom {
        position: sticky;
        top: 0px;
        z-index: 1000;
    }
</style>