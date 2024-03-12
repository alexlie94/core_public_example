<?php
defined('BASEPATH') or exit('No direct script access allowed');
$socket_url = 'https://websocket.onedeca.com';
?>

<!DOCTYPE html>
<html lang="en">
<!--begin::Head-->

<head>
    <base href="" />
    <title><?= $template['title'] ?></title>
    <meta charset="utf-8" />
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="article" />
    <meta property="og:title" content="" />
    <meta property="og:url" content="" />
    <meta property="og:site_name" content="" />
    <link rel="canonical" href="" />
    <link rel="shortcut icon" href="<?= MEDIA ?>/logos/omni.ico" />
    <!--begin::Fonts(mandatory for all pages)-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <!--end::Fonts-->
    <!--begin::Vendor Stylesheets(used for this page only)-->
    <?php
	//generate external css
	if (isset($pageCSS) && count($pageCSS) > 0) {
		for ($i = 0; $i < count($pageCSS); $i++) {
			$url = strtolower(substr($pageCSS[$i], 0, 4)) == 'http' ? $pageCSS[$i] : base_url() . '' . $pageCSS[$i];
			echo "<link rel=\"stylesheet\" href=\"" . $url . "\" />" . "\r\n\x20\x20\x20\x20";
		}
	}
	?>
    <!--end::Vendor Stylesheets-->
    <!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
    <link href="<?= PLUGINS ?>/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
    <link href="<?= CSS ?>/style.bundle.css" rel="stylesheet" type="text/css" />
    <link href="<?= CSS ?>/mobile.css" rel="stylesheet" type="text/css" />
    <!--end::Global Stylesheets Bundle-->
</head>
<!--end::Head-->
<!--begin::Body-->

<!-- <body id="kt_app_body" data-kt-app-header-fixed="true" data-kt-app-header-fixed-mobile="true"
    data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-push-toolbar="true"
    data-kt-app-sidebar-push-footer="true" data-kt-app-sidebar-stacked="true"
    data-kt-app-sidebar-secondary-enabled="true" data-kt-app-sidebar-secondary-collapse="on" class="app-default"> -->

<body id="kt_app_body" data-kt-app-page-loading-enabled="true" data-kt-app-page-loading="on"
    data-kt-app-header-fixed="true" data-kt-app-header-fixed-mobile="true" data-kt-app-sidebar-enabled="true"
    data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-push-toolbar="true" data-kt-app-sidebar-push-footer="true"
    data-kt-app-sidebar-stacked="true" data-kt-app-sidebar-secondary-enabled="true" class="app-default">
    <!-- <body id="kt_app_body" data-kt-app-header-fixed="true" data-kt-app-header-fixed-mobile="true" data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-push-toolbar="true" data-kt-app-sidebar-push-footer="true" data-kt-app-sidebar-stacked="true" data-kt-app-sidebar-secondary-enabled="true" class="app-default"> -->
    <!--begin::App-->
    <!--begin::loader-->
    <div class="page-loader flex-column">
        <img alt="Logo" class="theme-light-show max-h-50px" src="<?= MEDIA ?>/logos/omni.svg" />
        <img alt="Logo" class="theme-dark-show max-h-50px" src="<?= MEDIA ?>/logos/keenthemes-dark.svg" />
        <div class="d-flex align-items-center mt-5">
            <span class="spinner-border text-primary" role="status"></span>
            <span class="text-muted fs-6 fw-semibold ms-5">Loading...</span>
        </div>
    </div>
    <!--end::Loader-->
    <div class="d-flex flex-column flex-root app-root" id="kt_app_root">
        <!--begin::Page-->
        <div class="app-page flex-column flex-column-fluid" id="kt_app_page">
            <!--begin::Header-->
            <?= isset($template['partials'][HEADER]) ? $template['partials'][HEADER] : '' ?>
            <!--end::Header-->
            <!--begin::Wrapper-->
            <div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
                <!--begin::Sidebar-->
                <?= isset($template['partials'][SIDEBAR]) ? $template['partials'][SIDEBAR] : '' ?>

                <!--end::Sidebar-->
                <!--begin::Main-->
                <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
                    <!--begin::Content wrapper-->
                    <?= $template['body'] ?>
                    <!--end::Content wrapper-->

                    <!--begin::Footer-->
                    <?= isset($template['partials'][FOOTER]) ? $template['partials'][FOOTER] : '' ?>
                    <!--end::Footer-->
                </div>
                <!--end:::Main-->
            </div>
            <!--end::Wrapper-->
        </div>
        <!--end::Page-->
    </div>
    <!--end::App-->

    <!--begin::Modal - Add task-->
    <div class="modal fade" id="modalLarge" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <!--begin::Modal dialog-->
        <div class="modal-dialog modal-dialog-centered mw-650px modal-dialog-scrollable">
            <!--begin::Modal content-->
            <div class="modal-content modal-rounded">

            </div>
            <!--end::Modal content-->
        </div>
        <!--end::Modal dialog-->
    </div>
    <!--end::Modal - Add task-->

    <!--begin::Modal - Add task-->
    <div class="modal fade" id="modalLarge2" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <!--begin::Modal dialog-->
        <div class="modal-dialog modal-fullscreen modal-dialog-centered modal-dialog-scrollable p-9">
            <!--begin::Modal content-->
            <div class="modal-content modal-rounded">

            </div>
            <!--end::Modal content-->
        </div>
        <!--end::Modal dialog-->
    </div>
    <!--end::Modal - Add task-->

    <div class="modal fade" id="modalLarge3" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <!--begin::Modal dialog-->
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable mw-100 w-75 h-50">
            <!--begin::Modal content-->
            <div class="modal-content modal-rounded" style=" border: 3px solid #888;">

            </div>
            <!--end::Modal content-->
        </div>
        <!--end::Modal dialog-->
    </div>

    <div class="modal fade" id="modalLarge4" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <!--begin::Modal dialog-->
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <!--begin::Modal content-->
            <div class="modal-content modal-rounded" style=" border: 2px solid #888;">

            </div>
            <!--end::Modal content-->
        </div>
        <!--end::Modal dialog-->
    </div>

    <div class="modal fade" id="modalLarge5" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <!--begin::Modal dialog-->
        <div class="modal-dialog modal-dialog-centered mw-650px modal-dialog-scrollable ">
            <!--begin::Modal content-->
            <div class="modal-content modal-rounded" style=" border: 3px solid #888;">

            </div>
            <!--end::Modal content-->
        </div>
        <!--end::Modal dialog-->
    </div>

    <!--begin::Scrolltop-->
    <div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
        <!--begin::Svg Icon | path: icons/duotune/arrows/arr066.svg-->
        <span class="svg-icon">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect opacity="0.5" x="13" y="6" width="13" height="2" rx="1" transform="rotate(90 13 6)"
                    fill="currentColor" />
                <path
                    d="M12.5657 8.56569L16.75 12.75C17.1642 13.1642 17.8358 13.1642 18.25 12.75C18.6642 12.3358 18.6642 11.6642 18.25 11.25L12.7071 5.70711C12.3166 5.31658 11.6834 5.31658 11.2929 5.70711L5.75 11.25C5.33579 11.6642 5.33579 12.3358 5.75 12.75C6.16421 13.1642 6.83579 13.1642 7.25 12.75L11.4343 8.56569C11.7467 8.25327 12.2533 8.25327 12.5657 8.56569Z"
                    fill="currentColor" />
            </svg>
        </span>
        <!--end::Svg Icon-->
    </div>
    <!--end::Scrolltop-->

    <!--begin::Javascript-->
    <!--begin::Global Javascript Bundle(mandatory for all pages)-->
    <script src="<?= PLUGINS ?>/global/plugins.bundle.js"></script>
    <script src="<?= JS ?>/scripts.bundle.js"></script>
    <!--end::Global Javascript Bundle-->

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@2.3.0/dist/alpine-ie11.min.js" defer></script>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        if ($isMobile()) {
            // Jika perangkat adalah mobile, muat file CSS dari CDN untuk mobile
            // let mobileCSS = document.createElement('link');
            // mobileCSS.rel = 'stylesheet';
            // mobileCSS.href = 'https://cdn.jsdelivr.net/npm/@ionic/core/css/ionic.bundle.css';
            // document.head.appendChild(mobileCSS);

            // Muat file JavaScript dari CDN untuk mobile
            let mobileJS1 = document.createElement('script');
            mobileJS1.src = 'https://cdn.jsdelivr.net/npm/@ionic/core/dist/ionic/ionic.esm.js';
            document.head.appendChild(mobileJS1);

            let mobileJS2 = document.createElement('script');
            mobileJS2.src = 'https://cdn.jsdelivr.net/npm/@ionic/core/dist/ionic/ionic.js';
            document.head.appendChild(mobileJS2);
        }
    })
    </script>
    <!--begin::Vendors Javascript(used for this page only)-->
    <?php
	//generate external js
	if (isset($pageJS) && count($pageJS) > 0) {
		for ($i = 0; $i < count($pageJS); $i++) {
			$url = strtolower(substr($pageJS[$i], 0, 4)) == 'http' ? $pageJS[$i] : base_url() . '' . $pageJS[$i];
			echo "<script src=\"" . $url . "\" ></script>" . "\r\n\x20\x20\x20\x20";
		}
	}
	?>
    <!--end::Vendors Javascript-->
    <!--begin::Custom Javascript(used for this page only)-->
    <?= "<script src=\"" . JS_GENERAL . "\" ></script>" . "\r\n\x20\x20\x20\x20"; ?>
    <?= "<script src=\"" . $jsType . "\" ></script>" . "\r\n\x20\x20\x20\x20"; ?>
    <?= (isset($js) ? '<script src="' . $js . '"></script>' : '') . "\r\n\x20\x20\x20\x20" ?>
    <!--end::Custom Javascript-->
    <!--end::Javascript-->
    <script src="<?= $socket_url ?>/socket.io/socket.io.js"></script>
    <script>
    var socket = io('<?= $socket_url ?>')

    socket.on('new_order', function(data) {
        console.log(data);
        if (data) {
            // alert("data Updated");
            notifyMe();
        }
    });

    function notifyMe() {
        if (!("Notification" in window)) {
            alert("New Order");
        } else if (Notification.permission === "granted") {
            var options = {
                body: "This is the body of the notification",
                icon: "icon.jpg",
                dir: "ltr"
            };
            var notification = new Notification("Hi there", options);
        } else if (Notification.permission !== 'denied') {
            Notification.requestPermission(function(permission) {
                if (!('permission' in Notification)) {
                    Notification.permission = permission;
                }

                if (permission === "granted") {
                    var options = {
                        body: "This is the body of the notification",
                        icon: "icon.jpg",
                        dir: "ltr"


                    };

                    var notification = new Notification("Hi there", options);







                }
            });

        }
    }
    </script>

</body>
<!--end::Body-->

</html>