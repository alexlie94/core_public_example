<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<div id="kt_app_sidebar" class="app-sidebar" data-kt-drawer="true" data-kt-drawer-name="app-sidebar" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="auto" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">
	<!--begin::Sidebar secondary-->
	<div class="app-sidebar-secondary">
		<!--begin::Sidebar menu-->
		<div id="kt_app_sidebar_secondary_wrapper" class="hover-scroll-y" data-kt-scroll="true" data-kt-scroll-activate="{default: true, lg: true}" data-kt-scroll-height="auto" data-kt-scroll-wrappers="#kt_app_sidebar_secondary_menu, #kt_app_sidebar_secondary_tags" style="background-color: white;" data-kt-scroll-offset="5px">
			<!--begin::Sidebar menu-->
			<div class="app-sidebar-menu menu menu-sub-indention menu-rounded menu-column" id="kt_app_sidebar_secondary_menu" data-kt-menu="true">

				<?php
				foreach ($menu as $ky => $val) {
					$menu_id = $val['menu_id'];
					$controller = $val['controller'];
					$menu_name = $val['menu_name'];
					$icon = $val['icon'];
					$active = $val['active'];

					$count_child = (isset($val['child'])) ? count($val['child']) : 0;
					if ($count_child > 0) {
						$subMenu = "<div class=\"menu-sub menu-sub-accordion\">";
						foreach ($val['child'] as $kyC => $valC) {
							if ($valC['controller'] == "dashboard_shopee" || $valC['controller'] == "dashboard_tokopedia" || $valC['controller'] == "dashboard_zalora" || $valC['controller'] == "dashboard_tiktok" || $valC['controller'] == "dashboard_blibli") {
								$subMenu .= "";
							} else {
								$subMenu .= "<div class=\"menu-item\">
                                                <!--begin:Menu link-->
                                                <a class=\"menu-link " . (isControllerExist() == $valC['controller'] ? 'active' : '') . "\" href=\"" . base_url() . $valC['controller'] . "\">
                                                    <span class=\"menu-bullet\">
                                                        <span class=\"bullet bullet-dot\"></span>
                                                    </span>
                                                    <span class=\"menu-title\">" . ucwords($valC['menu_name']) . "</span>
                                                </a>
                                                <!--end:Menu link-->
                                            </div>";
							}
						}
						$subMenu .= "</div>";

						echo    "<div data-kt-menu-trigger=\"click\" class=\"menu-item " . (isset($active) && $active == 'active' ? "here show" : "") . " menu-accordion\">
                                    <span class=\"menu-link\">
                                        <span class=\"menu-icon\">
                                            <i class=\"{$icon} fs-2\"></i>
                                        </span>
                                        <span class=\"menu-title\">" . ucwords($menu_name) . "</span>
                                        <span class=\"menu-arrow\"></span>
                                    </span> 
                                    {$subMenu}
                                </div>";
					} else {

						echo    "<div class=\"menu-item " . (isset($active) && $active == 'active' ? "here" : "") . "\">
                                        <a class=\"menu-link {$active}\" href=\"" . base_url() . $controller . "\">
                                        <span class=\"menu-icon\">
                                            <i class=\"{$icon} fs-2\"></i>
                                        </span>
                                        <span class=\"menu-title\">" . ucwords($menu_name) . "</span>
                                    </a>
                                </div> ";
					}
				}
				?>
				<!--end:Menu item-->

			</div>
			<!--end::Sidebar menu-->
		</div>
		<!--end::Sidebar menu-->
	</div>
	<!--end::Sidebar secondary-->
</div>