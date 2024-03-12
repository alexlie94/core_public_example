<?php
foreach ($data_order as $row) {
?>
<input type="hidden" id="countOrder" value="<?= $count_data_order ?>">
<div class="row gx-9 gy-6 p-4 ">
    <div class="card card-dashed shadow-sm col-xl-12 " data-kt-billing-element="card">
        <div class="col-xl-12 h-xl-100  p-3">

            <div class="d-flex flex-row flex-stack flex-wrap pb-1">
                <div class="d-flex flex-column py-2">

                    <div class="d-flex align-items-center">
                        <?php
							if ($row->local_order_status == 'READY_TO_SHIP') { ?>
                        <div class="form-check form-check-custom form-check-solid form-check-sm">
                            <input class="form-check-input" type="checkbox" value="" id="selectOrder" />
                            <label class="form-check-label text-gray-700 fw-bold" for="selectOrder">
                            </label>
                        </div>
                        <?php } else { ?>
                        <div class="pe-7"></div>
                        <?php } ?>
                        <img src="<?= MEDIA . '/marketplace/tokopedia-icon.png' ?>" alt="" class="me-4" width="40" />

                        <div>
                            <div class="fs-5 fw-bold">
                                <?= $row->recipient_name == '' ? '-' : $row->recipient_name ?>
                                <!-- | <?= format_number_to_idr($row->total_price) ?> -->
                            </div>
                            <div class="fs-6 fw-semibold text-gray-700">
                                <?= $row->source_name ?> | Order ID :
                                <a href="<?= base_url("sales_orders_tokopedia/detail/") . $row->id ?>">
                                    <?= $row->local_order_id ?>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class=" d-flex align-items-right">
                    <div class="text-end px-3">
                        <div class="fs-7 fw-semibold text-gray-700">Order Date</div>
                        <div class="fs-6 fw-bold ">
                            <?= timestamp_to_date($row->local_updated_at) ?>
                        </div>
                    </div>
                    <?php
						if ($row->local_order_status == 400) {
							if ($row->pickup_selected === null) {
								echo '
							<button class="btn btn-sm btn-primary mx-3 btnCreateShipment" data-type="modal" data-type="modal"
							data-url="' . base_url() . 'sales_orders_tokopedia/form_create_shipment/' . $row->id . '">Create
							Shipment</button>
							';
							} else {
								echo '
							<button class="btn btn-sm btn-dark mx-3" disabled>Shipment Created</button>
							';
							}
						} ?>
                    <?php
						if ($row->order_status_id == 3 || $row->order_status_id == 4) {
							echo '
								<a type="button" target="_blank" href="' . base_url() . 'sales_orders_tokopedia/print_label/' . $row->local_order_id . '/' . $row->users_ms_channels_id . '" class="btn btn-sm btn-primary" onclick="printLabel()"  >Print Label</a>
								';
						}
						?>
                    <div class="dropdown ps-2">
                        <button class="btn btn-info btn-icon" type="button" id="dropdownMenuButton1"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-three-dots-vertical fs-1"></i>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                            <li><a class="dropdown-item" href="#" onclick="sync_order(this)"
                                    data-order-id="<?= $row->local_order_id ?>">Sync Order</a></li>
                        </ul>
                    </div>
                </div>

            </div>

            <div class="card bg-light p-3 d-flex flex-row flex-wrap ms-7 ">
                <div class="d-flex pe-4">
                    <span class="fs-6 fw-bold text-gray-700 pe-2">Shipping Courier : </span>
                    <span class="fs-6 fw-semibold text-gray-700">
                        <?= $row->shipping_provider_name != "" ? $row->shipping_provider_name : "-" ?>
                    </span>
                </div>
                <div class="d-flex pe-4">
                    <span class=" fs-6 fw-bold text-gray-700 pe-2">AWB : </span>
                    <span class="fs-6 fw-semibold text-gray-700">
                        <?= $row->tracking_number != "" ? $row->tracking_number : "-" ?>
                    </span>
                </div>

                <div class="d-flex pe-4">
                    <span class=" fs-6 fw-bold text-gray-700 pe-2">Channel : </span>
                    <span class="fs-6 fw-semibold text-gray-700">
                        <?= $row->channel_name ?>
                    </span>
                </div>
            </div>
            <?php
				foreach ($row->detail as $index => $detail) {
				?>
            <div class="py-5 ms-7 detailOrder<?= $row->id ?>" style="<?= ($index >= 2) ? 'display:none' : ''; ?> ">
                <table class="border-1 w-100">
                    <tr>
                        <td width="35%" class="align-top">
                            <div class="d-flex align-items-start">
                                <img src="<?= $detail->local_image ?>" alt="" class="me-4" width="50" />
                                <div class="fs-6 fw-bold text-gray-900">
                                    <?= $detail->local_item_name ?>
                                    <div>
                                        <span class="fs-6 fw-bold text-gray-500">SKU :
                                        </span>
                                        <span class="fs-6 fw-bold text-gray-700">
                                            <?= $detail->local_item_sku ?>
                                        </span>

                                    </div>
                                </div>
                        </td>
                        <td width="5%" class="align-top">
                            <?= $detail->quantity_purchased ?>x
                        </td>
                        <td width="10%" class="align-top">
                            <?php
									if ($detail->product_discount_price > 0) {
										echo '
									<span class="text-decoration-line-through">' . format_number_to_idr($detail->product_original_price) . '</span><br>
									<span class="fw-bold">' . format_number_to_idr($detail->product_discount_price) . '</span>';
									} else {
										echo '<span class="fw-bold">' . format_number_to_idr($detail->product_original_price) . '</span>';
									}
									?>
                        </td>
                        <td width="15%" class="align-top">
                            <div class="fs-7 fw-semibold text-gray-700">Ship from</div>
                            <div class="fs-6 fw-bold text-primary-700">Primary Warehouse
                            </div>
                        </td>
                        <td width="10%" class="align-top">
                            <div class="fs-7 fw-semibold text-gray-700">Status</div>
                            <?php
									switch ($row->order_status_id) {
										case 1:
											$status_color = 'text-danger';
											break;
										case 2:
											$status_color = 'text-warning';
											break;
										case 3:
											$status_color = 'text-info';
											break;
										case 4:
											$status_color = 'text-primary';
											break;
										case 5:
											$status_color = 'text-success';
											break;
										case 6:
											$status_color = 'text-success';
											break;
										case 7:
											$status_color = 'text-danger';
											break;
										case 8:
											$status_color = 'text-danger';
											break;
										default:
											$status_color = 'text-gray-900';
											break;
									}
									echo '<div class="fs-6 fw-bold ' . $status_color . '">' . $row->status_order_name . '</div>';
									?>
            </div>
            </td>
            <td width="20%" class="align-top">
                <div class="fs-7 fw-semibold text-gray-700 ps-2">Message</div>
                <ul class="fs-6 fw-bold text-gray-700">
                    <li>
                        <?= $detail->error_message ?>
                    </li>
                </ul>
            </td>
            </tr>
            </table>
        </div>

        <?php } ?>

        <div class="d-flex justify-content-center align-items-center py-2">
            <span id="see-more" onclick="toggleSeeMore(<?= $row->id ?>) " data-value="<?= count($row->detail) ?>"
                class="seeMore cursor-pointer text-primary">
                See More <i class="bi bi-arrow-down-short"></i>
            </span>
        </div>
    </div>

</div>
</div>








<?php } ?>