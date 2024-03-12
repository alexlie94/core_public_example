<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<div class="row">
	<div class="col-md-12">
		<div class="position-relative w-100">
			<!--begin::Svg Icon | path: icons/duotune/general/gen021.svg-->
			<span class="svg-icon svg-icon-3 svg-icon-gray-500 position-absolute top-50 translate-middle ms-6">
				<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
					<rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
					<path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="currentColor" />
				</svg>
			</span>
			<!--end::Svg Icon-->
			<input type="text" class="form-control fs-4 py-4 ps-14 text-gray-700 placeholder-gray-400 mw-50" name="search_brand" id="search_brand" value="" placeholder="Search Here">
		</div>
	</div>
</div>

<table id="kt_datatable_product_list" class="table table-striped table-row-bordered gy-5 gs-7">
	<thead>
		<tr class="fw-semibold fs-6 text-gray-800">
			<th>#</th>
			<th class="min-w-100px">SKU</th>
			<th class="min-w-200px">PRODUCT NAME</th>
			<th class="min-w-50px">BRAND</th>
			<th class="min-w-50px">LOCATION</th>
			<th class="min-w-50px">QTY STORAGE</th>
			<th>QTY OUT</th>
		</tr>
	</thead>
	<tbody>
	</tbody>
</table>