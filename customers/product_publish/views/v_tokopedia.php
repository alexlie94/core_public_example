<div class="card shadow-sm p-5">
	<div class="notice d-flex bg-light-warning rounded border-warning border border-dashed p-6">
		<!--begin::Icon-->
		<!--begin::Svg Icon | path: icons/duotune/general/gen044.svg-->
		<span class="svg-icon svg-icon-2tx svg-icon-warning me-4">
			<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
				<rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="currentColor" />
				<rect x="11" y="14" width="7" height="2" rx="1" transform="rotate(-90 11 14)" fill="currentColor" />
				<rect x="11" y="17" width="2" height="2" rx="1" transform="rotate(-90 11 17)" fill="currentColor" />
			</svg>
		</span>
		<div class="d-flex flex-stack flex-grow-1">
			<!--begin::Content-->
			<div class="fw-semibold">
				<h4 class="text-gray-900 fw-bold">We need your attention!</h4>
				<div class="fs-6 text-gray-700">Your product is currently not registered on the marketplace. To start using tools, please
				<a class="fw-bold popovers" href="javascript:void(0)" data-bs-toggle="popover" data-bs-html="true" data-bs-dismiss="true" title="<span><b>Please choose your channel</b></span>" 
				data-bs-content=' '>Choose channel</a>.</div>
			</div>
			<!--end::Content-->
		</div>
	</div>
</div>
<!--begin::Modal - Create Campaign-->
<div class="modal fade" id="kt_modal_create_campaign2" tabindex="-1" aria-hidden="true" role="dialog" aria-labelledby="myModalLabel160">
	<!--begin::Modal dialog-->
	<div class="modal-dialog modal-fullscreen p-9">
		<!--begin::Modal content-->
		<div class="modal-content modal-rounded">
			<!--begin::Modal header-->
			<div class="modal-header py-7 d-flex justify-content-between">
				<!--begin::Modal title-->
				<span><h2>Create Campaign</h2></span>
				<!--end::Modal title-->
				<!--begin::Close-->
				<div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
					<!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
					<span class="svg-icon svg-icon-1">
						<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
							<rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor" />
							<rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="currentColor" />
						</svg>
					</span>
					<!--end::Svg Icon-->
				</div>
				<!--end::Close-->
			</div>
			<!--begin::Modal header-->
			<!--begin::Modal body-->
			<div class="modal-body scroll-y m-5">

			</div>
			<!--begin::Modal body-->
		</div>
	</div>
</div>
<!--end::Modal - Create Campaign-->
<script>
var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
  return new bootstrap.Popover(popoverTriggerEl);
});

var dataFromPHP = <?php echo json_encode($channel); ?>;

// // Fungsi untuk menambahkan tombol ke popover saat popover ditampilkan
document.addEventListener('shown.bs.popover', function (event) {
  var popover = bootstrap.Popover.getInstance(event.target);
  var content = popover.tip.querySelector('.popover-body');
  var html = "";

  for (let index = 0; index < dataFromPHP.length; index++) {
		html +=`<input type="radio" class="btn-check" name="radio_buttons_2" data-channel_name="`+dataFromPHP[index].channel_name+`" value="`+dataFromPHP[index].users_ms_channels_id+`" id="kt_radio_buttons_2_option_`+index+`" onclick="buttonClick(this)"/>
					<label class="btn btn-outline btn-outline-dashed btn-active-light-primary p-4 d-flex align-items-center mb-5" for="kt_radio_buttons_2_option_`+index+`">
						<span class="d-block fw-semibold text-start">
							<span class="text-dark fw-bold d-block fs-3">`+dataFromPHP[index].channel_name+`</span>
						</span>
					</label>`;
  }
  $(content).html(html);
});


// Fungsi yang akan dijalankan saat tombol di klik
function buttonClick(event) {
	$("a.popovers").popover("hide");
	console.log($(event).val());
	$("#kt_modal_create_campaign2").modal('show');
	$("#kt_modal_create_campaign2 h2").html('<img alt="" src="<?= check_image_source('tokopedia') ?>" width="30" style="margin-top: -10px;"> <span>'+$(event).attr('data-channel_name')+'</span>');
}
</script>
