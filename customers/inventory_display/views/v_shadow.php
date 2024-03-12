<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<div class="d-flex flex-column flex-column-fluid">
	<!--begin::Toolbar-->
	<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6" style="margin-bottom: -30px;">
		<!--begin::Toolbar container-->
		<div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
			<!--begin::Page title-->
			<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
				<!--begin::Title-->
				<h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
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
			<div class="row mb-10">
				<div class="col-md-10">
					<?= $headerTable ?>
				</div>
			</div>

            <div class="row mb-10">
				<div class="col-md-12">
					<?= $table ?>
				</div>
			</div>

			<div class="row mb-10">
				<div class="d-flex flex-end gap-2 gap-lg-3">
					<button type="button" data-type="modal" id="addShadow" data-url="<?=$urlAddShadow?>" data-fullscreenmodal= 0 data-type="insert" data-id="<?=$productID?>"  class="btn btn-primary"><i class="bi bi-plus-lg fs-4 me-2"></i> Add Shadow</button>
				</div>
			</div>

			<div class="row">
				<div class="d-flex flex-end gap-2 gap-lg-3">
					<a href="<?= $backUrl ?>" class="btn btn-warning">Back</a>
				</div>
			</div>

		</div>
		<!--end::Content container-->
	</div>
	<!--end::Content-->
</div>

<script>

</script>