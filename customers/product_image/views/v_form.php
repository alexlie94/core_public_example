<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<!--begin::Repeater-->
<div id="kt_docs_repeater_nested">

    <input type="hidden" id="id" name="id" class="form-control mb-3 mb-lg-0"
        value="<?= isset($dataProduct['id']) ? $dataProduct['id'] : '' ?>" data-type="input" />

    <!--begin::Form group-->
    <div class="form-group">
        <div data-repeater-list="kt_docs_repeater_nested_outer">
            <div data-repeater-item>

                <div class="form-group row mb-5">


                    <input type="hidden" data-type="input" id="imageChild_1" name="imageChild" />


                </div>

                <div class="form-group row mb-5 ">

                    <div class="inner-repeater">

                        <div data-repeater-list="kt_docs_repeater_nested_inner" class="mb-5">

                            <div class="form-group row mb-3 mt-3">

                                <div class="col-md-12">

                                    <div data-repeater-item>

                                        <div class="fv-row mb-2">

                                            <div class="dropzone kt_ecommerce_add_product_media"
                                                id="kt_ecommerce_add_product_media_1">

                                                <div class="dz-message needsclick">

                                                    <i class="bi bi-file-earmark-arrow-up text-primary fs-3x"></i>

                                                    <div class="ms-4">
                                                        <h3 class="fs-5 fw-bold text-gray-900 mb-1">Drop files here or
                                                            click to upload.</h3>
                                                        <span class="fs-7 fw-semibold text-gray-400">Upload up to 10
                                                            files</span>
                                                    </div>

                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>


                </div>


            </div>
        </div>
    </div>
</div>
</div>