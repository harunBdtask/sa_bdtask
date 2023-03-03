<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12 text-center">
                        <img src="<?php echo base_url() . $settings_info->logo; ?>" alt="Logo" height="40px"><br><br>
                        <h5><?php echo $settings_info->title . ' ( ' . $settings_info->nameA . ' )'; ?></h5>
                        <h6><?php echo 'Transfer Id :' . ($main ? $main->transfer_id : ''); ?></h6>
                        <strong><?php echo get_phrases(['date']) . ': ' . ($main ? $main->date : ''); ?></strong>
                        <hr>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['from', 'store']) ?> : </label>
               <div class="col-sm-4">
                  <div id="dealer_name"><?php echo ($main ? $main->storename : '') ?></div>
               </div>
                <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['transfer', 'by']) ?> : </label>
               <div class="col-sm-4">
                  <div id="dealer_name"><?php echo ($main ? $main->create_by : '') ?></div>
               </div>

                </div>

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center"><?php echo get_phrases(['sl', 'no']); ?></th>
                            <th class="text-center"><?php echo get_phrases(['item', 'name']); ?></th>
                            <th class="text-center"><?php echo get_phrases(['to','store']); ?></th>
                            <th class="text-center"><?php echo get_phrases(['batch','no']); ?></th>
                            <th class="text-center"><?php echo get_phrases(['qty']); ?></th>
                            <th class="text-center"><?php echo get_phrases(['total', 'weight']).'(kg)'; ?></th>


                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($details) {
                            $sl = 1;
                            foreach ($details as $detail) { ?>
                                <tr>
                                    <td class="text-center"><?php echo $sl++; ?></td>
                                    <td class="text-center"><?php echo $detail->item_name ?></td>
                                    <td class="text-center"><?php echo $detail->to_storename ?></td>
                                    <td class="text-center"><?php echo $detail->batch_id ?></td>
                                    <td class="text-center"><?php echo $detail->transfer_qty ?></td>
                                    <td class="text-center"><?php echo $detail->total_weight ?></td>
                                </tr>
                        <?php }
                        } ?>
                    </tbody>
                    <tfoot>
                        

                    </tfoot>
                </table>

  


            </div>
        </div>
    </div>
</div>