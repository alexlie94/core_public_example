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
				<div class="col-md-6">
					<?= $headerTable ?>
				</div>
			</div>
			<div class="row mb-10">
				<div class="col-md-12">
					<?= $headerDefault ?>
				</div>
			</div>
			<div class="row mb-10">
				<div class="col-md-12">
					<?= $headerSource ?>
				</div>
			</div>

			<div class="row mb-10">
				<div class="d-flex flex-end gap-2 gap-lg-3">
					<button type="button" id="addSource" data-id="<?=$productID?>" data-url="<?=$addSourceUrl?>" class="btn btn-primary"><i class="bi bi-plus-lg fs-4 me-2"></i> Add Source</button>
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

<div class="modal fade" id="modalSource" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" >
        <div class="modal-content" style=" border: 2px solid #888;">
            <div class="modal-header">
                <h4 class="modal-title">Add Source</h4>
            </div>

            <div class="modal-body">
				<form id="form">
					<div class="d-flex flex-column scroll-y me-n7 pe-7">
						<div class="fv-row mb-7">
							<label class="required fw-semibold fs-6 mb-4">Source</label>
							<select name="source" id="source" class="form-select validateSource" data-url="<?=$showChannelSourceUrl?>">
								
							</select>
						</div>

						<div class="fv-row mb-7">
							<label class="required fw-semibold fs-6 mb-4">Channel</label>
							<select name="channel" id="channel" class="form-select validateSource">
								
							</select>
						</div>
					</div>
				</form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
				<button class="btn btn-primary btn-rounded ml-2" type="button" id="sendSource">Add</button>
			</div>
        </div>
    </div>
</div>

<script>
	var dataLaunching = '<?=$dataLaunching?>';
	dataLaunching = JSON.parse(dataLaunching);

	var dataLookupDisplayLaunching = '<?=$lookupDisplayLaunching?>';
	dataLookupDisplayLaunching = JSON.parse(dataLookupDisplayLaunching);

	var dataLookupLaunchStatus = '<?=$lookupLaunchStatusLaunching?>';
	dataLookupLaunchStatus = JSON.parse(dataLookupLaunchStatus);

	var dataLookupColourLaunching = '<?=$lookupDisplayColourLaunching?>';
	dataLookupColourLaunching = JSON.parse(dataLookupColourLaunching);

</script>