<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>



<div class="d-flex flex-column flex-lg-row-fluid gap-7 gap-lg-10">
    <!--begin::Order details-->
    <div class="card card-flush py-4">
        <!--begin::Card body-->
        <div class="card-body pt-0">
            <!--begin::Billing address-->
            <div class="row">
                <?php 
				if(empty($all_data)){
					echo'
					<div class="col text-center text-gray-600 fs-4">
						Please Contact Support to Manage API
					</div>
					';
				
				}else{
				?>
                <table id="kt_datatable_manage_list" class="table table-row-bordered gy-5">
                    <thead>
                        <tr class="fw-semibold fs-6 text-muted">
                            <th width="10%">No</th>
                            <th width="70%">API</th>
                            <th width="70%">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
						$no = 1;
						foreach($all_data as $row){
							$checked = $row->status ? 'checked="checked"' : '';
							$disabled = !$row->enabled_by_admin ? 'disabled="disabled"' : '';
							echo'
							<tr class="fw-semibold fs-6 text-muted">
								<th width="10%">'.$no++.'</th>
								<th width="70%">'.$row->title.'</th>
								<th width="70%">
									<div class="form-check form-switch mb-10">
										<input class="form-check-input checkedStatus" type="checkbox" role="switch" value="'.$row->status.'" data-id="'.$row->id.'" id="flexSwitchCheckDefault" '.$checked.'  '.$disabled.' />
									</div>
								</th>
                        	</tr>
							';
						}
						?>
                    </tbody>
                </table>
                <?php } ?>
            </div>
            <!--end::Billing address-->
        </div>
        <!--end::Card body-->
    </div>
</div>