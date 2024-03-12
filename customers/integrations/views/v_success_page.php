<!DOCTYPE html>
<!--
Author: Keenthemes
Product Name: Metronic - Bootstrap 5 HTML, VueJS, React, Angular. Laravel, Asp.Net Core, Ruby on Rails, Spring Boot, Blazor, Django, Express.js, Node.js, Flask Admin Dashboard Theme & Template
Product Version: 8.1.6
Purchase: https://1.envato.market/EA4JP
Website: http://www.keenthemes.com
Contact: support@keenthemes.com
Follow: www.twitter.com/keenthemes
Dribbble: www.dribbble.com/keenthemes
Like: www.facebook.com/keenthemes
License: For each use you must have a valid license purchased only from above link in order to legally use the theme for your project.
-->
<html lang="en">
<!--begin::Head-->

<head>
	<base href="../../" />
	<title>IMS</title>
	<meta charset="utf-8" />
	<link rel="shortcut icon" href="<?= MEDIA ?>/logos/nextonecode.png" />
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

<body id="kt_body" class="app-blank bgi-size-cover bgi-position-center bgi-no-repeat">
	<!--begin::Theme mode setup on page load-->
	<script>
		var defaultThemeMode = "light";
		var themeMode;
		if (document.documentElement) {
			if (document.documentElement.hasAttribute("data-theme-mode")) {
				themeMode = document.documentElement.getAttribute("data-theme-mode");
			} else {
				if (localStorage.getItem("data-theme") !== null) {
					themeMode = localStorage.getItem("data-theme");
				} else {
					themeMode = defaultThemeMode;
				}
			}
			if (themeMode === "system") {
				themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
			}
			document.documentElement.setAttribute("data-theme", themeMode);
		}
	</script>
	<!--end::Theme mode setup on page load-->
	<!--begin::Root-->
	<div class="d-flex flex-column flex-root" id="kt_app_root">
		<!--begin::Page bg image-->
		<style>
			body {
				background-image: url('<?= MEDIA ?>/auth/bg3.jpg');
			}

			[data-theme="dark"] body {
				background-image: url('<?= MEDIA ?>/auth/bg3-dark.jpg');
			}
		</style>
		<!--end::Page bg image-->
		<!--begin::Authentication - Signup Welcome Message -->
		<div class="d-flex flex-column flex-center flex-column-fluid">
			<!--begin::Content-->
			<div class="d-flex flex-column flex-center text-center p-10">
				<!--begin::Wrapper-->
				<div class="card card-flush w-lg-650px py-5">
					<div class="card-body py-15 py-lg-20">
						<!--begin::Logo-->
						<div class="mb-14">
							<a href="#" class="">
								<img alt="Logo" src="<?= MEDIA ?>/logos/nextonecode.png" class="h-40px" />
							</a>
						</div>
						<!--end::Logo-->
						<!--begin::Title-->
						<h1 class="fw-bolder text-gray-900 mb-5">
							<?php
							if ($this->session->flashdata('msg_auth_success')) {
								echo $this->session->flashdata('msg_auth_success');
							} else {
								echo 'Your account has been integrated';
							}
							?>
						</h1>
						<!--end::Title-->
						<!--begin::Text-->

						<div class="fw-semibold fs-6 text-gray-500 mb-8">
							Embrace effortless product management at your fingertips! Ignite greater visibility and
							unlock your sales potential starting today.
						</div>
						<!--end::Text-->
						<!--begin::Link-->
						<div class="mb-11">
							<a href="<?= BASE_URL . 'integrations' ?>" class="btn btn-sm btn-primary">Go to Home
								Page</a>
						</div>
						<!--end::Link-->
						<!--begin::Illustration-->
						<div class="mb-0">
							<img src="<?= MEDIA ?>/auth/membership.png" class="mw-100 mh-300px theme-light-show"
								alt="" />
							<img src="<?= MEDIA ?>/auth/membership-dark.png" class="mw-100 mh-300px theme-dark-show"
								alt="" />
						</div>
						<!--end::Illustration-->
					</div>
				</div>
				<!--end::Wrapper-->
			</div>
			<!--end::Content-->
		</div>
		<!--end::Authentication - Signup Welcome Message-->
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
	<!--end::Javascript-->
</body>
<!--end::Body-->



</html>