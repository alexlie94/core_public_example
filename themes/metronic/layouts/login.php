<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<!DOCTYPE html>

<html lang="en">
<!--begin::Head-->

<head>
    <title>
        <?= $template['title'] ?>
    </title>
    <meta charset="utf-8" />
    <meta name="description" content="IMS Integrations" />
    <meta name="keywords" content="IMS Integrations" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="article" />
    <meta property="og:title" content="IMS Integrations" />
    <meta property="og:url" content="" />
    <meta property="og:site_name" content="IMS Integrations" />
    <link rel="canonical" href="" />
    <link rel="shortcut icon" href="<?= MEDIA ?>/logos/nextonecode.ico" />
    <!--begin::Fonts(mandatory for all pages)-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <!--end::Fonts-->
    <!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
    <link href="<?= PLUGINS ?>/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
    <link href="<?= CSS ?>/style.bundle.css" rel="stylesheet" type="text/css" />
    <!--end::Global Stylesheets Bundle-->
</head>
<!--end::Head-->
<!--begin::Body-->

<body id="kt_body" class="app-blank">
    <!--begin::Root-->
    <div class="d-flex flex-column flex-root" id="kt_app_root">
        <!--begin::Authentication - Sign-in -->
        <div class="d-flex flex-column flex-lg-row flex-column-fluid">
            <!--begin::Body-->
            <?= $template['body'] ?>
            <!--end::Body-->
            <!--begin::Aside-->
            <div class="d-flex flex-lg-row-fluid w-lg-40 bgi-size-cover bgi-position-center order-1 order-lg-2"
                style="background-image: url(<?= $background_login ?>); ">
                <!--begin::Content-->
                <div class="d-flex flex-column flex-center py-7 py-lg-15 px-5 px-md-15 w-100">
                    <!--begin::Logo-->
                    <a href="" class="mb-0 mb-lg-12">
                        <img alt="Logo" src="<?= $logo_image ?>" class="h-60px h-lg-150px" />
                    </a>
                    <!--end::Logo-->
                    <!--begin::Image-->
                    <img class="d-none d-lg-block mx-auto w-275px w-md-50 w-xl-500px mb-10 mb-lg-10"
                        src="<?= $form_image ?>" alt="" />
                    <!--end::Image-->
                    <!--begin::Title-->
                    <!-- <h1 class="d-none d-lg-block text-white fs-2qx fw-bolder text-center mb-7">Inventory Management
						System</h1> -->
                    <!--end::Title-->
                    <!--begin::Text-->
                    <div class="d-none d-lg-block text-white fs-base text-center">
                        <a href="#" class="opacity-75-hover text-warning fw-bold me-1">Inventory
                            Management System </a> efficiently tracks, manages, and optimizes your inventory levels.
                        <br>
                        With
                        real-time updates, reporting, and automation, it enhances efficiency,<br> reduces costs, and
                        ensures
                        product availability
                    </div>
                    <!--end::Text-->
                </div>
                <!--end::Content-->
            </div>
            <!--end::Aside-->
        </div>
        <!--end::Authentication - Sign-in-->
    </div>
    <!--end::Root-->
    <!--begin::Javascript-->
    <script>
    var hostUrl = "assets/";
    </script>
    <!--begin::Global Javascript Bundle(mandatory for all pages)-->
    <script src="<?= PLUGINS ?>/global/plugins.bundle.js"></script>
    <script src="<?= JS ?>/scripts.bundle.js"></script>
    <!--end::Global Javascript Bundle-->
    <!--begin::Custom Javascript(used for this page only)-->
    <?= (isset($js) ? '<script src="' . $js . '"></script>' : '') . "\r\n\x20\x20\x20\x20" ?>
    <!--end::Custom Javascript-->
    <!--end::Javascript-->
</body>
<!--end::Body-->

</html>
