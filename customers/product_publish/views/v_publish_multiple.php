<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<div class="d-flex flex-column flex-column-fluid">

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

            <div class="card shadow-sm mb-6">
                <div class="card-body pt-0 pb-0">
                    <div class="d-flex flex-wrap flex-sm-nowrap p-10">

                        <div class="me-7 mb-4">
                            <div class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative">
                                <img src="<?= $image ?>" alt="image">
                            </div>
                        </div>

                        <div class="flex-grow-1">

                            <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                                <div class="d-flex flex-column">
                                    <div class="d-flex align-items-center">
                                        <p class="text-gray-900 text-hover-primary fs-2 fw-bold me-1">
                                            <?= $product['product_name'] ?>
                                        </p>
                                    </div>

                                    <div class="breadcrumb breadcrumb-separatorless fw-semibold fs-6 my-0 mb-3">
                                        <div class="badge badge-light-info">Incoming</div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex flex-wrap flex-stack">

                                <div class="d-flex flex-column flex-grow-1 pe-8">

                                    <div class="d-flex flex-wrap">

                                        <div class="border border-2 border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3"
                                            style="border: 2px dashed purple !important;">
                                            <div class="fw-semibold fs-6 text-gray-400">Total SKU</div>
                                            <div class="d-flex align-items-center">
                                                <div class="fs-2 fw-bold counted" data-kt-countup="true"
                                                    data-kt-countup-value="<?= $product['total_sku'] ?>"
                                                    data-kt-initialized="1"><?= $product['total_sku'] ?></div>
                                            </div>
                                        </div>

                                        <div class="border border-2 border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3"
                                            style="border: 2px dashed purple !important;">
                                            <div class="fw-semibold fs-6 text-gray-400">Category</div>
                                            <div class="d-flex align-items-center">
                                                <div class="fs-2 fw-bold counted" data-kt-countup="true"
                                                    data-kt-countup-value="80" data-kt-initialized="1">
                                                    <div class="fs-2 fw-bold counted" data-kt-countup="true"
                                                        data-kt-countup-value="4500" data-kt-countup-prefix="$"
                                                        data-kt-initialized="1"><?= $product['category_name'] ?></div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="border border-2 border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3"
                                            style="border: 2px dashed purple !important;">
                                            <div class="fw-semibold fs-6 text-gray-400">Brand</div>
                                            <div class="d-flex align-items-center">
                                                <div class="fs-2 fw-bold counted" data-kt-countup="true"
                                                    data-kt-countup-value="80" data-kt-initialized="1">
                                                    <div class="fs-2 fw-bold counted" data-kt-countup="true"
                                                        data-kt-countup-value="4500" data-kt-countup-prefix="$"
                                                        data-kt-initialized="1"><?= $product['brand_name'] ?></div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="border border-2 border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3"
                                            style="border: 2px dashed purple !important;">
                                            <div class="fw-semibold fs-6 text-gray-400">Price</div>
                                            <div class="d-flex align-items-center">
                                                <div class="fs-2 fw-bold counted" data-kt-countup="true"
                                                    data-kt-countup-value="80" data-kt-initialized="1">IDR. 220.000
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm sticky-custom p-5">
                <div id="kt_app_toolbar_container" class="app-container d-flex flex-stack flex-wrap">
                    <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
                        <div class="page-title d-flex flex-column justify-content-center gap-1">
                            <ul class="nav nav-pills" role="tablist">
                                <?php
                                    $first = true;
                                    $shopeeIndex = array_search("Shopee", $sources);

                                    if ($shopeeIndex !== false) {
                                        unset($sources[$shopeeIndex]);
                                        array_unshift($sources, "Shopee");
                                    }

                                    foreach ($sources as $res) {
                                ?>

                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link rounded-pill me-2 <?= $first ? 'active' : '' ?>" data-bs-toggle="tab" data-id="2" data-source="<?= $res ?>"
                                            href="#<?= str_replace(' ', '_', $res) ?>" aria-selected="false" role="tab" tabindex="-1">
                                            <img alt="" src="<?= check_image_source($res) ?>" width="20">
                                            <?= $res ?>
                                        </a>
                                    </li>

                                <?php
                                    $first = false;
                                }
                                ?>
                            </ul>
                        </div>

                        <div class="d-flex align-items-center gap-2 gap-lg-3">
							<a href="<?= base_url() ?>product_publish"><button type="button" id="proccess_publish"
                                class="btn btn-outline btn-outline-dashed btn-outline-warning btn-active-light-warning hover-scale btn-sm">
                                <i class="fa-solid fa-arrow-left fs-4 me-2"></i>
                                Back
                            </button></a>
                            <!-- <button type="button" id="proccess_publish"
                                class="btn btn-outline btn-outline-dashed btn-outline-success btn-active-light-success hover-scale btn-sm">
                                <i class="fa-solid fa-cloud-upload fs-4 me-2"></i>
                                Publish Product
                            </button> -->
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-content mt-2" id="myTabContent">
                <?php
				$first = true;
				foreach ($sources as $res) {
                    $source_name_convert = str_replace(' ', '_', $res);
				?>
                <div class="tab-pane fade <?= $first ? 'show active' : '' ?>" id="<?= $source_name_convert ?>" role="tabpanel">
					<div id="form_publish_<?= $source_name_convert ?>">
					
					</div>
                </div>
                <?php
					$first = false;
				} ?>
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
