<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6" style="margin-bottom: -30px;">
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                    <?= $titlePage ?>
                </h1>
            </div>
        </div>
    </div>

    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-fluid">

            <div class="py-5">
                <div class="d-flex flex-column flex-md-row rounded border p-10">
                    <ul
                        class="nav nav-tabs nav-pills flex-row border-0 flex-md-column me-5 mb-3 mb-md-0 fs-6 min-w-lg-200px mr-5">

                        <?php
						$firstRow = true;

						foreach ($all_data as $row) {
							$activeClass = $firstRow ? 'active' : '';
							$firstRow = false;

							if ($row['source_key_status'] == 1 && $row['source_status'] == 1) {
								?>
                        <li class="nav-item w-100 me-0 mb-md-2">
                            <a class="nav-link  w-100 <?= $activeClass ?>  btn btn-flex btn-active-primary"
                                data-bs-toggle="tab" href="#kt_vtab_pane_<?= $row['id_source'] ?>">
                                <span class="d-flex flex-column align-items-start">
                                    <img src="<?= $row['source_icon'] ?>" class="mw-55px" />
                                </span>

                                <span class="fs-4 pt-3 px-3 fw-bold text-start">
                                    <?= $row['source_name'] ?>
                                </span>
                            </a>
                        </li>

                        <?php
							} else {
								?>

                        <li class="nav-item w-100 me-0 mb-md-2 ">
                            <p class="nav-link notactive w-100 btn btn-flex">
                                <span class="d-flex flex-column align-items-start blur-content">
                                    <img src="<?= $row['source_icon'] ?>" class="mw-55px"
                                        style="filter: grayscale(100%);" />
                                </span>

                                <span class="fs-4 pt-3 px-3 fw-bold text-start text-muted blur-content">
                                    <?= $row['source_name'] ?>
                                </span>
                            </p>
                        </li>

                        <?php
							}
						}
						?>
                    </ul>

                    <div class="rounded border p-10 w-100">
                        <div class="tab-content" id="myTabContent">
                            <?php
							$firstRowChannel = true;
							foreach ($all_data as $source) {
								if ($source['all_status'] == 0) {
									?>
                            <div class="tab-pane fade show active" role="tabpanel">
                                <div class="card border-0 " style="height: 50vh;">
                                    <div class="card-body d-flex justify-content-center align-items-center">
                                        <p class="fs-3 text-gray-600">Contact support for activation</p>
                                    </div>
                                </div>
                            </div>

                            <?php
									break;
								} else {
									$activeClassChannel = $firstRowChannel ? 'show active' : '';
									$firstRowChannel = false;
									?>
                            <div class="tab-pane fade <?= $activeClassChannel ?>"
                                id="kt_vtab_pane_<?= $source['id_source'] ?>" role="tabpanel">

                                <span class="fw-bold fs-1">Channel</span>
                                <div class="row mt-5">
                                    <?php
											foreach ($source['channel'] as $channel) {
												if ($channel['id_source'] == $source['id_source']) {

													?>
                                    <div class="col-md-4 mb-5 ">
                                        <div class="card shadow-sm custom-card"
                                            data-status="<?= $channel['channel_auth_name'] ?>" style="--card-bg-color: #<?= $channel['channel_auth_color'] ?>;
	background: linear-gradient(112.14deg, #00D2FF 0%, #3A7BD5 100%)">
                                            <div class="card-body">
                                                <div class="rounded-circle"
                                                    style="width: 50px; height: 50px; overflow: hidden; position: relative;">
                                                    <img src="<?= MEDIA . '/custom/channels.png' ?>"
                                                        alt="<?= $channel['channel_name'] ?>"
                                                        style="width: 100%; height: 100%; position: relative;">
                                                </div>
                                                <div class="d-flex align-items">
                                                    <span class="text-white parent-hover-primary fs-4 fw-bold mt-2">
                                                        <?= $channel['channel_name'] ?>
                                                    </span>
                                                </div>
                                                <div class="d-flex align-items justify-content-end mt-5">
                                                    <?= $channel['button'] ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php }
											}
											?>
                                </div>

                            </div>

                            <?php
								}
							}
							?>

                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
</div>


<style>
.nav-link.notactive {
    position: relative;
}

.nav-link.notactive:hover .blur-content,
.nav-link.notactive:hover img {
    filter: blur(5px);
}

.nav-link.notactive:hover .blur-content {
    color: transparent;
}

.nav-link.notactive::before {
    content: "Contact Support";
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    padding: 10px 5px;
    background-color: #fff;
    border-radius: 5px;
    font-size: 0.8rem;
    font-weight: bold;
    color: #36454F;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.3s ease-in-out;
    z-index: 1;

    box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
}

.nav-link.notactive:hover::before {
    opacity: 1;
}

.custom-card::before {
    position: absolute;
    top: 2rem;
    right: -0.64rem;
    content: '';
    background: var(--card-bg-color);
    height: 28px;
    width: 28px;
    transform: rotate(45deg);
    z-index: -1;
}

.custom-card::after {
    position: absolute;
    content: attr(data-status);
    top: 11px;
    right: -14px;
    padding: 0.5rem;
    width: 10rem;
    background: var(--card-bg-color);
    color: white;
    text-align: center;
    font-family: 'Roboto', sans-serif;
    box-shadow: 4px 4px 15px rgba(26, 35, 126, 0.2);
}
</style>
