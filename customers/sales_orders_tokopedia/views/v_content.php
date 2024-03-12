<?php
foreach ($channel_status as $row) {
?>

<div class="tab-content" id="tabChannel">
    <div class="tab-pane fade show active shadow-sm rounded-4 " role="tabpanel">
        <div class="card border-bottom-3 rounded-0 sticky-custom2 ">
            <div class="card-body pt-0 pb-0 ">
                <div class="d-flex flex-wrap flex-sm-nowrap mb-3">
                    <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bold ">
                        <?php
							$active_status = true;
							foreach ($row['status'] as $status) {
							?>
                        <li class="nav-item ">
                            <a class="nav-link text-active-primary ms-0 me-10 py-5 tabStatus <?= $active_status ? 'active' : '' ?>"
                                data-bs-toggle="tab" data-id="<?= $status['status_code'] ?>" href="#"
                                onclick="switch_status(this,<?= $row['id'] ?>)">
                                <?= $status['status_name'] ?>
                            </a>
                        </li>
                        <?php
								$active_status = false;
							}
							?>

                    </ul>
                </div>


            </div>
        </div>
        <div class="tab-content px-5" id="t abStatus">
            <div class="tab-pane fade show active" role="tabpanel">
                <div class="d-flex flex-wrap flex-stack">

                    <div class="d-flex flex-wrap align-items-center  my-1">
                        <!-- <h3 class="fw-bold me-5 my-1">All</h3> -->
                        <div class="d-flex align-items-center position-relative my-1">
                            <select name="filter_select" id="filter_select" data-control="select2"
                                data-hide-search="true" data-placeholder="Filter"
                                class="form-select form-select-sm form-select-solid w-180px ">
                                <option value="local_order_id" selected>Order ID</option>
                                <option value="sku">SKU</option>
                            </select>

                            <div class="position-relative">
                                <input type="text" id="filter_input" name="filter_input"
                                    class="form-control form-control-sm form-control-solid w-200px"
                                    placeholder="Search" />

                                <span
                                    class="svg-icon svg-icon-3 position-absolute top-50 end-0 translate-middle-y me-3">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1"
                                            transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
                                        <path
                                            d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                                            fill="currentColor" />
                                    </svg>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex flex-wrap my-1">
                        <div class="d-flex my-0">
                            <div class="mb-0">
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-calendar" viewBox="0 0 16 16">
                                            <path
                                                d="M0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2V13a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zM1 0a1 1 0 0 1 1 1v11a1 1 0 0 1-1 1h14a1 1 0 0 1-1-1V1a1 1 0 0 1-1-1H2a1 1 0 0 1-1 1zm4 12h1V7H5v5zm2 0h1V7H7v5zm2 0h1V7H9v5zm2 0h1V7h-1v5z" />
                                        </svg>
                                    </span>
                                    <input class="form-control form-control-sm form-control-solid"
                                        placeholder="Pick date range" id="filter_date" />

                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="d-flex flex-wrap flex-stack pb-7">

                    <div class="d-flex flex-wrap align-items-center my-1">
                        <!-- <div class="form-check form-check-custom form-check-solid form-check-sm">
							<input class="form-check-input" type="checkbox" value="" id="bulkSelect"
								onclick="floatMenu(this)" />
							<label class="form-check-label text-gray-700 fw-bold" for="bulkSelect">
								Bulk Select
							</label>
						</div> 
					-->
                        <div class="d-flex  align-items-center position-relative ms-2 ">
                            <span class="text-gray-700 fw-bold w-100">Sort by : </span>
                            <select name="filter_order_by" id="filter_order_by" data-control="select2"
                                data-hide-search="true" data-placeholder="Filter"
                                class="form-select form-select-sm  w-150px border-0 ms-2 ">
                                <option value="last_updated" selected>Last updated</option>
                                <option value="first_updated">First updated</option>
                            </select>
                        </div>

                        <div class="d-flex  align-items-center position-relative  ms-15">
                            <span class="text-gray-700 fw-bold w-100">Show data : </span>
                            <select name="filter_limit" id="filter_limit" data-control="select2" data-hide-search="true"
                                data-placeholder="Filter" class="form-select form-select-sm  w-150px border-1 ms-2 ">
                                <option value="5" selected>5</option>
                                <option value="10">10</option>
                                <option value="20">20</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                                <option value="200">200</option>
                            </select>
                        </div>


                        <div class="d-flex align-items-end position-relative  ms-15">
                            <button class="btn btn-sm btn-danger" onclick="reset()">Reset Filter</button>
                        </div>
                    </div>
                </div>
                <div class="contentOrder"></div>
                <div class="orderNotFound">
                </div>


                <div class="pagination" style="display:none">
                </div>


            </div>
        </div>


    </div>
</div>

<?php
} ?>

<style>
.sticky-custom2 {
    position: sticky;
    top: 70px;
    z-index: 1000;
}
</style>

<script>
default_date_filter()
</script>