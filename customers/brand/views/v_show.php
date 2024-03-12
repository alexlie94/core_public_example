<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<div class="d-flex flex-column flex-column-fluid">

	<div id="kt_app_content" class="app-content flex-column-fluid">
		<div id="kt_app_content_container" class="app-container container-fluid">


			<div x-data="{ isMobile: $isMobile() }" class=" mb-15">
				<template x-if="isMobile">
					<div class="bg-primary py-3 px-5 mobile-menu-top d-flex align-items-center">
						<h3 class="text-white"><?= $titlePage ?></h3>
						<div class="ms-auto">
							<button class="btn btn-sm btn-icon btn-secondary" type="button" id="open-modal" expand="block">
								<i class="fa-solid fa-search"></i>
							</button>
							<button class="btn btn-sm btn-success" type="button" id="btnAdd" data-type="modal" data-url="<?= base_url('brand/insert') ?>" data-fullscreenmodal="0">
								<i class="fa-solid fa-plus"></i> Create Brand
							</button>
						</div>
					</div>

				</template>

				<ion-content class="ion-padding">
					<ion-modal trigger="open-modal">
						<form id="formSearch" class="form formSearch block" autocomplete="off">
							<div class="row p-5 ">

								<div class="col-12">
									<p class="fs-5 fw-semibold mb-2">Search By</p>
								</div>
								<div class="col-5">
									<ion-select name="searchBy" id="searchBy" placeholder="Please Select">
										<ion-select-option value="" disabled selected hidden>Please Select
										</ion-select-option>
										<?php
										foreach ($searchBy as $key => $value) {
											$selected = $key == "productid" ? "selected" : "";
											echo "<ion-select-option value='{$key}' {$selected}>{$value}</ion-select-option>";
										}
										?>
										</select>
								</div>

								<div class="col-7">
									<input type="text" class="form-control " id="searchValue" name="searchValue" placeholder="" autocomplete="off" data-type='input'>
								</div>

								<div class="row mt-3">
									<div class="d-flex flex-end gap-2 gap-lg-3">
										<button type="button" id="btnSearchReset" class="btn btn-outline btn-outline-dashed btn-outline-danger btn-active-light-danger hover-scale btn-sm fw-bold">
											<i class="fa-solid fa-refresh fs-4 me-2"></i>Reset</button>
										<button type="button" id="btnSearch" @click="mobileModal1.dismiss()" onclick="reloadDatatables()" class="btn btn-outline btn-outline-dashed btn-outline-primary btn-active-light-primary hover-scale btn-sm fw-bold">
											<i class="fa-solid fa-search fs-4 "></i>Search</button>
									</div>
								</div>
							</div>
						</form>
					</ion-modal>
				</ion-content>


				<template class="row mt-3" x-if="!isMobile">
					<div class="card shadow-sm">
						<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6" style="margin-bottom: -30px;">
							<div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
								<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
									<h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
										<?= $titlePage ?>
									</h1>
								</div>
							</div>
						</div>

						<div class="card-body">
							<form id="formSearch" class="form formSearch" autocomplete="off">
								<div class="row mb-10 mt-5">

									<div class="col-md-2">
										<label class="fs-5 fw-semibold mb-2">Search By</label>
										<select class="form-select" name="searchBy" id="searchBy" data-control="select2" data-hide-search="true" data-type='select' data-placeholder="Please Select">
											<option value="" disabled selected hidden>Please Select</option>
											<?php
											foreach ($searchBy as $key => $value) {
												$selected = $key == "productid" ? "selected" : "";
												echo "<option value='{$key}' {$selected}>{$value}</option>";
											}
											?>
										</select>
									</div>

									<div class="col-md-3">
										<input type="text" class="form-control mt-9" id="searchValue" name="searchValue" placeholder="" autocomplete="off" data-type='input'>
									</div>

									<div class="col-md-7">
										<div class="d-flex flex-end gap-2 gap-lg-3 mt-9">
											<button class="btn btn-outline btn-outline-dashed btn-outline-success btn-active-light-success hover-scale btn-sm fw-bold" type="button" id="btnAdd" data-type="modal" data-url="<?= base_url('brand/insert') ?>" data-fullscreenmodal="0">
												<i class="fa-solid fa-plus fs-4 me-2"></i>Create
												Brand</button>
										</div>
									</div>
								</div>
							</form>

							<div class="row mb-5">
								<div class="d-flex flex-end gap-2 gap-lg-3">
									<button type="button" id="btnSearchReset" class="btn btn-outline btn-outline-dashed btn-outline-danger btn-active-light-danger hover-scale btn-sm fw-bold">
										<i class="fa-solid fa-refresh fs-4 me-2"></i>Reset</button>
									<button type="button" id="btnSearch" onclick="reloadDatatables()" class="btn btn-outline btn-outline-dashed btn-outline-primary btn-active-light-primary hover-scale btn-sm fw-bold">
										<i class="fa-solid fa-search fs-4 me-2"></i>Search</button>
								</div>
							</div>
						</div>
					</div>
				</template>
			</div>

			<div class="row mt-3">
				<div class="card shadow-sm">
					<div class="card-body">
						<?= $table ?>
					</div>
				</div>
			</div>

		</div>
	</div>

</div>









<style>
	.sticky-action {
		position: flex;
		top: 100px;
		margin-top: 100px;
		/* Atur atribut lain sesuai kebutuhan */
	}
</style>






















































































































<!-- <style>

	.sticky-top {
		position: sticky;
		top: 1;
		z-index: 1000;
	}
</style> -->