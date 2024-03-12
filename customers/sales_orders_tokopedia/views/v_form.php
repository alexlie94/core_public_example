<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<input type="hidden" id="channelStatus" value="">


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

        <div id="kt_app_content_container" class="app-container">

            <ul class="nav nav-pills mb-5 fs-3 sticky-custom1">
                <?php
				$active_channel = true;
				foreach ($channel as $header) { ?>
                <li class="nav-item">
                    <a class="nav-link <?= $active_channel ? 'active' : '' ?> rounded-pill me-2 tabChannel"
                        data-bs-toggle="tab" data-id="<?= $header['id'] ?>" href="#" onclick="getChannel(this)">
                        <?= $header['channel_name'] ?>
                    </a>
                </li>
                <?php
					$active_channel = false;
				} ?>
            </ul>

            <div class="content"></div>

        </div>
    </div>
</div>

<div class=" toolbar floating-menu" id="floatingToolbar">
    <div class="row">
        <div class="col-md-12">

            <div class="d-flex justify-content-center">
                <a href=" #" class="btn btn-light-danger mx-3">Danger</a>
                <a href="#" class="btn btn-light-primary mx-3">Danger</a>
            </div>
        </div>
    </div>
</div>


<style>
#toastr-container>.toastr-info {
    background-image: none !important;
    padding: 15px 0 15px 20px !important;
}

#floatingToolbar {
    display: none;
    align-items: center;
    justify-content: center;
    position: fixed;
    bottom: 0;
    left: 70px;
    margin: 0 auto;
    width: 70%;
    background: #7239ea;
    background: rgb(255, 121, 121);
    background: linear-gradient(277deg, rgba(255, 121, 121, 1) 0%, rgba(254, 197, 167, 1) 100%);
    padding: 10px;
    border: 0;
    box-shadow: 0px 0px 2px rgba(0, 0, 0, 0.2);
}

.sticky-custom1 {
    position: sticky;
    top: 15px;
    z-index: 1000;
}
</style>