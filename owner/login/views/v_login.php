<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<!--begin::Logo-->
<a href="../../demo33/dist/index.html" class="d-block d-lg-none mx-auto py-20">
    <img alt="Logo" src="<?=$logo_image?>" class="theme-light-show h-25px" />
</a>
<!--end::Logo-->
<!--begin::Aside-->
<div class="d-flex flex-column flex-column-fluid flex-center w-lg-50 p-10">
    <!--begin::Wrapper-->
    <div class="d-flex justify-content-between flex-column-fluid flex-column w-100 mw-450px">
        <!--begin::Header-->
        <div class="d-flex flex-stack py-2">
            <!--begin::Back link-->
            <div class="me-2"></div>
            <!--end::Back link-->
        </div>
        <!--end::Header-->
        <!--begin::Body-->
        <div class="py-20">
            <!--begin::Form-->
            <form class="form w-100" novalidate="novalidate" id="login">
                <!--begin::Body-->
                <div class="card-body">
                    <!--begin::Heading-->
                    <div class="text-start mb-10">
                        <!--begin::Title-->
                        <h1 class="text-dark mb-3 fs-3x" data-kt-translate="sign-in-title">Admin Login</h1>
                        <!--end::Title-->
                        <!--begin::Text-->
                        <div class="text-gray-400 fw-semibold fs-6" data-kt-translate="general-desc">Get unlimited access & earn money</div>
                        <!--end::Link-->
                    </div>
                    <!--begin::Heading-->
                    <!--begin::Alert-->
				    <div class="fv-row mb-8" id="alert-messages">
					
                    </div>
                    <!--end::Alert-->
                    <!--begin::Input group=-->
                    <div class="fv-row mb-8">
                        <!--begin::Email-->
                        <input type="text" placeholder="Email" name="email" id="email" autocomplete="off" data-kt-translate="sign-in-input-email" class="form-control form-control-solid" />
                        <!--end::Email-->
                    </div>
                    <!--end::Input group=-->
                    <div class="fv-row mb-7">
                        <!--begin::Password-->
                        <input type="password" placeholder="Password" name="password" id="password" autocomplete="off" data-kt-translate="sign-in-input-password" class="form-control form-control-solid" />
                        <!--end::Password-->
                    </div>
                    <!--end::Input group=-->
                    <!--begin::Wrapper-->
                    <div class="d-flex flex-stack flex-wrap gap-3 fs-base fw-semibold mb-10">
                        <div></div>
                        
                    </div>
                    <!--end::Wrapper-->
                    <!--begin::Actions-->
                    <div class="d-flex flex-stack">
                        <!--begin::Submit-->
                        <button id="btnSubmit" type="submit" class="btn btn-primary me-2 flex-shrink-0">
                            <!--begin::Indicator label-->
                            <span class="indicator-label">Sign In</span>
                            <!--end::Indicator label-->

                        </button>
                        <!--end::Submit-->
                        <!--begin::Social-->
                        <div class="d-flex align-items-center">
                            <div class="text-gray-400 fw-semibold fs-6 me-3 me-md-6" data-kt-translate="general-or">Or</div>
                            <!--begin::Symbol-->
                            <a href="#" class="symbol symbol-circle symbol-45px w-45px bg-light me-3">
                                <img alt="Logo" src="<?=MEDIA?>/svg/brand-logos/google-icon.svg" class="p-4" />
                            </a>
                            <!--end::Symbol-->
                            <!--begin::Symbol-->
                            <a href="#" class="symbol symbol-circle symbol-45px w-45px bg-light me-3">
                                <img alt="Logo" src="<?=MEDIA?>/svg/brand-logos/facebook-3.svg" class="p-4" />
                            </a>
                            <!--end::Symbol-->
                            <!--begin::Symbol-->
                            <a href="#" class="symbol symbol-circle symbol-45px w-45px bg-light">
                                <img alt="Logo" src="<?=MEDIA?>/svg/brand-logos/apple-black.svg" class="theme-light-show p-4" />
                                <img alt="Logo" src="<?=MEDIA?>/svg/brand-logos/apple-black-dark.svg" class="theme-dark-show p-4" />
                            </a>
                            <!--end::Symbol-->
                        </div>
                        <!--end::Social-->
                    </div>
                    <!--end::Actions-->
                </div>
                <!--begin::Body-->
            </form>
            <!--end::Form-->
        </div>
        <!--end::Body-->
        <!--begin::Footer-->
        <div class="m-0">
            <!--begin::Toggle-->
            <button class="btn btn-flex btn-link rotate" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start" data-kt-menu-offset="0px, 0px">
                <img data-kt-element="current-lang-flag" class="w-25px h-25px rounded-circle me-3" src="<?=MEDIA?>/flags/united-states.svg" alt="" />
                <span data-kt-element="current-lang-name" class="me-2">English</span>
                <!--begin::Svg Icon | path: icons/duotune/arrows/arr072.svg-->
                <span class="svg-icon svg-icon-3 svg-icon-muted rotate-180 m-0">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z" fill="currentColor" />
                    </svg>
                </span>
                <!--end::Svg Icon-->
            </button>
            <!--end::Toggle-->
            <!--begin::Menu-->
            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px py-4" data-kt-menu="true" id="kt_auth_lang_menu">
                <!--begin::Menu item-->
                <div class="menu-item px-3">
                    <a href="#" class="menu-link d-flex px-5" data-kt-lang="English">
                        <span class="symbol symbol-20px me-4">
                            <img data-kt-element="lang-flag" class="rounded-1" src="<?=MEDIA?>/flags/united-states.svg" alt="" />
                        </span>
                        <span data-kt-element="lang-name">English</span>
                    </a>
                </div>
                <!--end::Menu item-->
                <!--begin::Menu item-->
                <div class="menu-item px-3">
                    <a href="#" class="menu-link d-flex px-5" data-kt-lang="Spanish">
                        <span class="symbol symbol-20px me-4">
                            <img data-kt-element="lang-flag" class="rounded-1" src="<?=MEDIA?>/flags/spain.svg" alt="" />
                        </span>
                        <span data-kt-element="lang-name">Spanish</span>
                    </a>
                </div>
                <!--end::Menu item-->
                <!--begin::Menu item-->
                <div class="menu-item px-3">
                    <a href="#" class="menu-link d-flex px-5" data-kt-lang="German">
                        <span class="symbol symbol-20px me-4">
                            <img data-kt-element="lang-flag" class="rounded-1" src="<?=MEDIA?>/flags/germany.svg" alt="" />
                        </span>
                        <span data-kt-element="lang-name">German</span>
                    </a>
                </div>
                <!--end::Menu item-->
                <!--begin::Menu item-->
                <div class="menu-item px-3">
                    <a href="#" class="menu-link d-flex px-5" data-kt-lang="Japanese">
                        <span class="symbol symbol-20px me-4">
                            <img data-kt-element="lang-flag" class="rounded-1" src="<?=MEDIA?>/flags/japan.svg" alt="" />
                        </span>
                        <span data-kt-element="lang-name">Japanese</span>
                    </a>
                </div>
                <!--end::Menu item-->
                <!--begin::Menu item-->
                <div class="menu-item px-3">
                    <a href="#" class="menu-link d-flex px-5" data-kt-lang="French">
                        <span class="symbol symbol-20px me-4">
                            <img data-kt-element="lang-flag" class="rounded-1" src="<?=MEDIA?>/flags/france.svg" alt="" />
                        </span>
                        <span data-kt-element="lang-name">French</span>
                    </a>
                </div>
                <!--end::Menu item-->
            </div>
            <!--end::Menu-->
        </div>
        <!--end::Footer-->
    </div>
    <!--end::Wrapper-->
</div>
<!--end::Aside-->