<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<div class="d-flex flex-column flex-lg-row-fluid w-lg-50 p-10 order-2 order-lg-1">
    <!--begin::Form-->
    <div class="d-flex flex-center flex-column flex-lg-row-fluid">
        <!--begin::Wrapper-->
        <div class="w-lg-500px p-10">
            <!--begin::Form-->
            <form id="login" class="form w-100">
                <!--begin::Heading-->
                <div class="text-center mb-11">
                    <!--begin::Title-->
                    <h1 class="text-dark fw-bolder mb-3">Sign In</h1>
                    <!--end::Title-->
                    <!--begin::Subtitle-->
                    <div class="text-gray-500 fw-semibold fs-6">Start Your Inventory Management Journey</div>
                    <!--end::Subtitle=-->
                </div>
                <!--begin::Heading-->
                <!--begin::Login options-->
                <!-- <div class="row g-3 mb-9">
					<div class="col-md-6">
						<a href="#" class="btn btn-flex btn-outline btn-text-gray-700 btn-active-color-primary bg-state-light flex-center text-nowrap w-100">
							<img alt="Logo" src="<?= MEDIA ?>/svg/brand-logos/google-icon.svg" class="h-15px me-3" />Sign in with Google</a>
					
					</div>
					<div class="col-md-6">
						<a href="#" class="btn btn-flex btn-outline btn-text-gray-700 btn-active-color-primary bg-state-light flex-center text-nowrap w-100">
							<img alt="Logo" src="<?= MEDIA ?>/svg/brand-logos/apple-black.svg" class="theme-light-show h-15px me-3" />
							<img alt="Logo" src="<?= MEDIA ?>/svg/brand-logos/apple-black-dark.svg" class="theme-dark-show h-15px me-3" />Sign in with Apple</a>
					
					</div>
				</div> -->
                <!--begin::Separator-->
                <!-- <div class="separator separator-content my-14">
					<span class="w-125px text-gray-500 fw-semibold fs-7">Or with email</span>
				</div> -->
                <!--end::Separator-->

                <!--begin::Alert-->
                <div class="fv-row mb-8" id="alert-messages">

                </div>
                <!--end::Alert-->

                <!--begin::Input group=-->
                <div class="fv-row mb-8 fv-plugins-icon-container">
                    <!--begin::Email-->
                    <input type="text" placeholder="Email" name="email" autocomplete="off"
                        class="form-control form-control-solid" id="email" />
                    <!--end::Email-->
                </div>
                <!--end::Input group=-->
                <div class="fv-row mb-3 fv-plugins-icon-container">
                    <!--begin::Password-->
                    <input type="password" placeholder="Password" name="password" autocomplete="off"
                        class="form-control form-control-solid" id="password" />
                    <!--end::Password-->
                </div>
                <!--end::Input group=-->
                <!--begin::Wrapper-->
                <div class="d-flex flex-stack flex-wrap gap-3 fs-base fw-semibold mb-8">
                    <div></div>
                    <!--begin::Link-->

                    <!--end::Link-->
                </div>
                <!--end::Wrapper-->
                <!--begin::Submit button-->
                <div class="d-grid mb-10">
                    <button type="submit" class="btn btn-primary" id="btnSubmit">
                        <!--begin::Indicator label-->
                        <span class="indicator-label">Sign In</span>
                        <!--end::Indicator label-->
                    </button>
                </div>
                <!--end::Submit button-->
                <!--begin::Sign up-->

                <!--end::Sign up-->
            </form>
            <!--end::Form-->
        </div>
        <!--end::Wrapper-->
    </div>
    <!--end::Form-->
    <!--begin::Footer-->
    <div class="d-flex flex-center flex-wrap px-5">
        <!--begin::Links-->
        <div class="d-flex fw-semibold text-primary fs-base">
            <a href="" class="px-5" target="_blank">Terms</a>
            <a href="" class="px-5" target="_blank">Plans</a>
            <a href="" class="px-5" target="_blank">Contact Us</a>
        </div>
        <!--end::Links-->
    </div>
    <!--end::Footer-->
</div>