<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<div id="kt_app_header" class="app-header">
	<!--begin::Brand-->
	<div class="app-header-brand ps-6">
		<!--begin::Mobile toggle-->
		<div class="d-flex align-items-center d-lg-none ms-n2 me-2" title="Show sidebar menu">
			<div class="btn btn-icon btn-color-gray-500 btn-active-color-primary w-35px h-35px" id="kt_app_sidebar_mobile_toggle">
				<!--begin::Svg Icon | path: icons/duotune/abstract/abs015.svg-->
				<span class="svg-icon svg-icon-2">
					<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M21 7H3C2.4 7 2 6.6 2 6V4C2 3.4 2.4 3 3 3H21C21.6 3 22 3.4 22 4V6C22 6.6 21.6 7 21 7Z" fill="currentColor" />
						<path opacity="0.3" d="M21 14H3C2.4 14 2 13.6 2 13V11C2 10.4 2.4 10 3 10H21C21.6 10 22 10.4 22 11V13C22 13.6 21.6 14 21 14ZM22 20V18C22 17.4 21.6 17 21 17H3C2.4 17 2 17.4 2 18V20C2 20.6 2.4 21 3 21H21C21.6 21 22 20.6 22 20Z" fill="currentColor" />
					</svg>
				</span>
				<!--end::Svg Icon-->
			</div>
		</div>
		<!--end::Mobile toggle-->
		<!--begin::Logo-->
		<a class="app-sidebar-secondary-collapse-d-none ms-4" href="<?= base_url() ?>dashboard_inventory">
			<img alt="Logo" src="<?= MEDIA ?>/logos/omnilogos.svg" class="h-30px" />
		</a>
		<!--end::Logo-->
		<!--begin::Sidebar toggle-->
		<button id="kt_app_sidebar_secondary_toggle" class="btn btn-sm btn-icon bg-body btn-color-gray-400 btn-active-color-primary d-none d-lg-flex ms-2 rotate" data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body" data-kt-toggle-name="app-sidebar-secondary-collapse">
			<!--begin::Svg Icon | path: icons/duotune/text/txt011.svg-->
			<span class="svg-icon svg-icon-2 rotate-180">
				<svg width="24" height="21" viewBox="0 0 24 21" fill="none" xmlns="http://www.w3.org/2000/svg">
					<rect width="14" height="3" rx="1.5" transform="matrix(-1 0 0 1 24 0)" fill="currentColor" />
					<rect width="24" height="3" rx="1.5" transform="matrix(-1 0 0 1 24 9)" fill="currentColor" />
					<rect width="18" height="3" rx="1.5" transform="matrix(-1 0 0 1 24 18)" fill="currentColor" />
				</svg>
			</span>
			<!--end::Svg Icon-->
		</button>
		<!--end::Sidebar toggle-->
	</div>
	<!--end::Brand-->
	<!--begin::Header wrapper-->
	<div class="app-header-wrapper">
		<div class="app-container container-fluid">
			<div class="app-navbar-item d-flex align-items-stretch flex-lg-grow-1">

			</div>
			<!--begin::Navbar-->
			<div class="app-navbar flex-shrink-0">
				<!--begin::User menu-->
				<div class="app-navbar-item m-4">
					<div class="d-flex align-items-center">
						<div class="d-flex justify-content-start flex-column">
							<div class="d-flex">
								<span class="card-label fw-bold text-gray-800"><?= (isset($company_name['company_name'])) ? $company_name['company_name'] : $profil_users['fullname']; ?>
								</span>
							</div>
							<span class="text-gray-400 fw-semibold fs-7"></span>
						</div>
					</div>
				</div>
				<div class="app-navbar-item ms-1 ms-md-3">
					<!--begin::Menu wrapper-->
					<div class="cursor-pointer symbol symbol-circle symbol-30px symbol-md-40px" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
						<img src="<?= MEDIA ?>/avatars/blank.png" alt="user" />
					</div>
					<!--begin::User account menu-->
					<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px" data-kt-menu="true">
						<!--begin::Menu item-->
						<div class="menu-item px-3">
							<div class="menu-content d-flex align-items-center px-3">
								<!--begin::Avatar-->
								<div class="symbol symbol-50px me-5">
									<img alt="Logo" src="<?= MEDIA ?>/avatars/blank.png" />
								</div>
								<!--end::Avatar-->
								<!--begin::Username-->
								<div class="d-flex flex-column">
									<div class="fw-bold d-flex align-items-center fs-5 fullname">
										<?= (isset($profil_users['fullname'])) ? $profil_users['fullname'] : 'Guest'; ?>
									</div>
									<a class="fw-semibold text-muted text-hover-primary fs-7 email_account"><?= (isset($profil_users['email'])) ? $profil_users['email'] : ''; ?></a>
								</div>
								<!--end::Username-->
							</div>
						</div>
						<!--end::Menu item-->
						<!--begin::Menu separator-->
						<div class="separator my-2"></div>
						<!--end::Menu separator-->

						<!--begin::Menu item-->
						<div class="menu-item px-5 my-1">
							<a data-type="modal" id="btnAccount" data-fullscreenmodal="0" data-url="<?= $account_setting ?>" class="menu-link px-5">Account Settings</a>
						</div>
						<!--end::Menu item-->
						<!--begin::Menu item-->
						<div class="menu-item px-5">
							<a href="<?= $logout_url ?>" class="menu-link px-5">Sign Out</a>
						</div>
						<!--end::Menu item-->
					</div>
					<!--end::User account menu-->
					<!--end::Menu wrapper-->
				</div>
				<!--end::User menu-->
			</div>
			<!--end::Navbar-->
		</div>
	</div>
	<!--end::Header wrapper-->
</div>