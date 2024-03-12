<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<div id="kt_app_content_container" class="app-container ">

    <div class="row gx-9 gy-6">
        <div class="card col-xl-12" data-kt-billing-element="card">
            <div class="col-xl-12 h-xl-100  p-3">

                <div class="d-flex flex-row flex-stack flex-wrap pb-1">
                    <div class="d-flex flex-column py-2">
                        <div class="d-flex align-items-center">
                            <img src="<?= MEDIA . '/marketplace/tokopedia-icon.png' ?>" alt="" class="me-4"
                                width="50" />
                            <div>
                                <div class="fs-3 fw-bold"><?= $detail['recipient_name'] ?> |
                                    <?= format_number_to_idr($detail['total_price']) ?></div>
                                <div class="fs-6 fw-semibold text-gray-700"><?= $detail['source_name'] ?> | Order ID
                                    : <?= $detail['local_order_id'] ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <input type="hidden" id="local_order_id" value="<?= $detail['local_order_id'] ?>">
                <!--begin:Order summary-->
                <div class="d-flex justify-content-between flex-column">
                    <!--begin::Table-->
                    <div class="table-responsive border-bottom mb-9">
                        <table class="table align-middle table-row-dashed fs-6 gy-5 mb-0">
                            <thead>
                                <tr class="border-bottom fs-6 fw-bold text-muted">
                                    <th class="min-w-175px pb-2">Products</th>
                                    <th class="min-w-80px text-end pb-2">Qty</th>
                                    <th class="min-w-100px text-end pb-2">Price</th>
                                    <th class="min-w-100px text-end pb-2">Sub Total</th>
                                </tr>
                            </thead>
                            <tbody class="fw-semibold text-gray-600">
                                <?php foreach ($detail['detail'] as $row) { ?>
                                <!--begin::Products-->
                                <tr>
                                    <!--begin::Product-->
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <!--begin::Thumbnail-->
                                            <img src="<?= $row->local_image ?>" alt="" class="me-4" width="50" />
                                            <!--end::Thumbnail-->
                                            <!--begin::Title-->
                                            <div class="ms-5">
                                                <div class="fw-bold">
                                                    <?= $row->local_item_name ?>
                                                </div>
                                                <div class="fs-7 text-muted">SKU :
                                                    <?= $row->local_item_sku ?></div>
                                            </div>
                                            <!--end::Title-->
                                        </div>
                                    </td>
                                    <!--end::Product-->
                                    <!--begin::SKU-->
                                    <td class="text-end">
                                        x<?= $row->quantity_purchased ?>
                                    </td>
                                    <!--end::SKU-->
                                    <!--begin::Quantity-->
                                    <td class="text-end">
                                        <?php
											if ($row->product_discount_price > 0) {
												echo '
											<span class="text-decoration-line-through text-muted">' . format_number_to_idr($row->product_original_price) . '</span><br>
											<span class="">' . format_number_to_idr($row->product_discount_price) . '</span>';
											} else {
												echo '<span class="">' . format_number_to_idr($row->product_original_price) . '</span>';
											}
											?>
                                    </td>
                                    <!--end::Quantity-->
                                    <!--begin::Total-->
                                    <td class="text-end">
                                        <?= format_number_to_idr($row->product_discount_price * $row->quantity_purchased) ?>
                                    </td>
                                    <!--end::Total-->
                                </tr>
                                <?php } ?>
                                <!--end::Products-->
                            </tbody>
                        </table>
                    </div>
                    <!--end::Table-->
                </div>
                <!--end:Order summary-->
            </div>
        </div>
    </div>





</div>