<div class="d-flex flex-column flex-column-fluid">
	<!--begin::Toolbar-->
	<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6 mb-0" style="margin-bottom: -30px;">
		<!--begin::Toolbar container-->
		<div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
			<!--begin::Page title-->
			<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
				<!--begin::Title-->
				<h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0" style="margin-left: 5px;">
					<?= $titlePage ?>
				</h1>
				<!--end::Title-->
			</div>
			<!--end::Page title-->
		</div>
		<!--end::Toolbar container-->
	</div>
	<!--end::Toolbar-->

	<!--begin::Content-->
	<div id="kt_app_content" class="app-content flex-column-fluid">
		<!--begin::Content container-->
		<div id="kt_app_content_container" class="app-container container-fluid ">

			<?= (isset($formSearch) ? $formSearch : '') ?>

			<div class="card shadow-sm" style="margin-top: -10px;">
				<div class="card-body py-4">
					<div class="d-flex flex-wrap flex-sm-nowrap" style="margin-bottom: -20px;">
						<div class="flex-grow-1">
							<div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
								<div class="d-flex flex-column"></div>
								<!--begin::Actions-->
								<div class="d-flex my-4">
									<?= $buttonHeader ?>
								</div>
								<!--end::Actions-->
							</div>
						</div>
					</div>
					<hr>
					<!--begin::Datatable-->
					<?= $table ?>
					<div class="paginationDatatables"></div>
					<!--end::Datatable-->
				</div>
			</div>

		</div>
		<!--end::Content container-->
	</div>
	<!--end::Content-->
</div>