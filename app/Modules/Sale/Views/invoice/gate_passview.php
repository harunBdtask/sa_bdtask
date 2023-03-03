<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="card card-bd lobidrag">
            <div class="card-heading">
                <div class="card-title">
                    <h4>
                    </h4>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12 text-center">
                        <img src="<?php echo base_url() . $settings_info->logo; ?>" alt="Logo" height="40px"><br><br>
                        <h5><?php echo $settings_info->title; ?></h5>
                        <h6><?php echo 'CHALLAN NO :' . ($scaler ? $scaler->challan_no : ''); ?></h6>
                        <strong><?php echo get_phrases(['date']) . ': ' . date('d/m/Y'); ?></strong>
                        <hr>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['store', 'name']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="driver_name"><?php echo ($scaler ? $scaler->store_name : '') ?></div>
                    </div>
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['driver', 'mobile', 'no']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="mobile_no"><?php echo ($scaler ? $scaler->driver_mobile_no : '') ?></div>
                    </div>
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['driver', 'name']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="driver_name"><?php echo ($scaler ? $scaler->driver_name : '') ?></div>
                    </div>
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['truck', 'no']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="mobile_no"><?php echo ($scaler ? $scaler->truck_no : '') ?></div>
                    </div>

                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['truck', 'weight']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="truck_weight"><?php echo ($scaler ? $scaler->truck_weight : '') ?></div>
                    </div>
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['truck', 'weight', 'with', 'item']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="mobile_no"><?php echo ($scaler ? $scaler->truck_weight_with_items : '') ?></div>
                    </div>
                </div>

                <div class="table">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th rowspan="2" class="text-center"><?php echo get_phrases(['sl', 'no']) ?></th>
                                <th rowspan="2" class="text-center"><?php echo get_phrases(['name', 'of', 'items']) ?></th>
                                <th rowspan="2" class="text-center"><?php echo get_phrases(['quantity']) . '(' . get_phrases(['bag']) . ')' ?></th>
                                <th colspan="2" class="text-center"><?php echo get_phrases(['total', 'quantity']) ?></th>
                                <th rowspan="2" class="text-center"><?php echo get_phrases(['remarks']) ?></th>

                            </tr>
                            <tr>
                                <th class="text-center"><?php echo get_phrases(['kg']) ?></th>
                                <th class="text-center"><?php echo get_phrases(['M.T']) ?></th>

                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            $sl = 1;
                            $total_qty = 0;
                            $total_kg = 0;
                            $total_mt = 0;
                            foreach ($delivery_details as $details) { ?>
                                <tr>
                                    <td class="text-center"><?php echo $sl++; ?></td>
                                    <td class="text-center"><?php echo $details->item_name . '(' . $details->company_code . ')'; ?></td>
                                    <td class="text-center"><?php echo $details->quantity;
                                                            $total_qty += $details->quantity  ?></td>
                                    <td class="text-center"><?php echo $details->total_kg;
                                                            $total_kg += $details->total_kg;  ?></td>
                                    <td class="text-center"><?php echo ($details->total_kg ? ($details->total_kg / 1000) : 0);
                                                            $total_mt += ($details->total_kg ? ($details->total_kg / 1000) : 0); ?></td>
                                    <td class="text-center"><?php echo get_phrases(['remarks']) ?></td>

                                </tr>
                            <?php } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="2" class="text-right">Total</th>
                                <th class="text-center"><?php echo $total_qty; ?></th>
                                <th class="text-center"><?php echo $total_kg; ?></th>
                                <th class="text-center"><?php echo $total_mt; ?></th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="form-group row">
                    <?php if ($delivery_main->sls_admin_signature) { ?>
                        <label for="name" class="col-sm-2 col-form-label text-center"> <img src="<?php echo base_url($delivery_main->sls_admin_signature) ?>" height="70px;" width="80px;">
                            <div class="border-top"><?php echo get_phrases(['sales', 'admin']) ?></div>
                        </label>
                    <?php } ?>

                    <?php if ($delivery_main->accountant_sig) { ?>
                        <label for="name" class="col-sm-2 col-form-label text-center"> <img src="<?php echo base_url($delivery_main->accountant_sig) ?>" height="70px;" width="80px;">
                            <div class="border-top"><?php echo get_phrases(['accountant', 'signature']) ?></div>
                        </label>
                    <?php } ?>

                    <?php if ($delivery_main->fc_m_sig) { ?>
                        <label for="name" class="col-sm-2 col-form-label text-center"> <img src="<?php echo base_url($delivery_main->fc_m_sig) ?>" height="70px;" width="80px;">
                            <div class="border-top"><?php echo get_phrases(['factory', 'manager']) ?></div>
                        </label>
                    <?php } ?>


                    <?php if ($delivery_main->dl_s_sig) { ?>
                        <label for="name" class="col-sm-2 col-form-label text-center"> <img src="<?php echo base_url($delivery_main->dl_s_sig) ?>" height="70px;" width="80px;">
                            <div class="border-top"><?php echo get_phrases(['delivery', 'section', 'admin', 'signature']) ?></div>
                        </label>
                    <?php } ?>

                    <?php if ($delivery_main->str_s_sig) { ?>
                        <label for="name" class="col-sm-2 col-form-label text-center"> <img src="<?php echo base_url($delivery_main->str_s_sig) ?>" height="70px;" width="80px;">
                            <div class="border-top"><?php echo get_phrases(['store', 'admin', 'signature']) ?></div>
                        </label>
                    <?php } ?>

                    <?php if ($scaler->gate_pass_signature) { ?>
                        <label for="name" class="col-sm-2 col-form-label text-center"> <img src="<?php echo base_url($scaler->gate_pass_signature) ?>" height="70px;" width="80px;">
                            <div class="border-top"><?php echo get_phrases(['gatepass']) ?></div>
                        </label>
                    <?php } ?>
                </div>

            </div>

        </div>
    </div>
</div>