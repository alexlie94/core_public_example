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
		<div id="kt_app_content_container" class="app-container container-fluid">

			<form id="formSearch" class="form formSearch" autocomplete="off">
				<div class="row mb-10 mt-9">
					<div class="col-md-2">
						<label class="fs-5 fw-semibold mb-2">Search By</label>
						<select class="form-select" name="searchBy" id="searchBy" aria-label="Please Select">
							<option value="">Please Select</option>
							<?php 
							foreach ($searchBy as $key => $value) {
								$selected = $key == "productid" ? "selected" : "";
								echo "<option value='{$key}' {$selected}>{$value}</option>";
							}
							?>
						</select>
					</div>
					<div class="col-md-5" id="placeSearch">
						<input type="text" class="form-control mt-9" id="searchValue" name="searchValue" placeholder="Product ID" autocomplete="off">
					</div>
					<div class="col-md-2">
						<label class="fs-5 fw-semibold mb-2">Source</label>
						<select class="form-select" name="source" id="source" data-url="<?=$sourceUrl?>" aria-label="Please Select">
							<option value="">Please Select</option>
							<?php 
							foreach ($source as $key => $value) {
								echo "<option value='{$value->id}'>{$value->source_name}</option>";
							}
							?>
						</select>
					</div>
					<div class="col-md-2">
						<label class="fs-5 fw-semibold mb-2">Channel</label>
						<select class="form-select" name="channel" id="channel" aria-label="Please Select">
							<option value="">Please Select</option>
						</select>
					</div>
				</div>
			</form>

			<div class="row mb-10">
				<div class="d-flex flex-end gap-2 gap-lg-3">
					<button class="btn btn-flex btn-primary h-40px fs-7 fw-bold" onclick="reloadDatatables()">Search</button>
					<button id="btnSearchResetInventory" class="btn btn-flex btn-secondary h-40px fs-7 fw-bold" type="button">Reset</button>
					<button class="btn btn-flex btn-outline btn-color-gray-700 btn-active-color-primary bg-body h-40px fs-7 fw-bold" id="btnMassUpload" data-type="modal" data-url="<?=base_url('inventory_display/upload')?>">Mass Upload</button>
					<button class="btn btn-flex btn-success h-40px fs-7 fw-bold" id="btnExport" data-type="redirect" data-url="<?=base_url('inventory_display/download');?>">Download View</button>
				</div>
			</div>

			<div class="row">
				<?=$table?>
			</div>

			

		</div>
		<!--end::Content container-->
	</div>
	<!--end::Content-->
</div>

<script>
var permission = <?=$getPermission?>;
var vlookup = '<?=$getLookupArray?>';
vlookup = JSON.parse(vlookup);
var searchInput = '<?=$searchInput?>';
searchInput = JSON.parse(searchInput);
</script>