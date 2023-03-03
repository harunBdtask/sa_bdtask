<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12 text-center">
                        <img src="<?php echo base_url() . $settings_info->logo; ?>" alt="Logo" height="40px"><br><br>
                        <h5><?php echo $settings_info->title . ' ( ' . $settings_info->nameA . ' )'; ?></h5>
                        <h6><?php echo 'Return Id :' . ($main ? $main->return_id : ''); ?></h6>
                        <strong><?php echo get_phrases(['date']) . ': ' . ($main ? $main->date : ''); ?></strong>
                        <hr>
                    </div>
                </div>
                <div class="row">
                <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['dO', 'no']) ?> : </label>
               <div class="col-sm-4">
                  <div id="dealer_name"><?php echo ($main ? $main->do_no : '') ?></div>
               </div>
               <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['challan', 'no']) ?> : </label>
               <div class="col-sm-4">
                  <div id="dealer_name"><?php echo ($main ? $main->challan_no : '') ?></div>
               </div>
                <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['return', 'by']) ?> : </label>
               <div class="col-sm-4">
                  <div id="dealer_name"><?php echo ($main ? $main->return_by : '') ?></div>
               </div>

                </div>

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center"><?php echo get_phrases(['sl', 'no']); ?></th>
                            <th class="text-center"><?php echo get_phrases(['item', 'name']); ?></th>
                            <th class="text-center"><?php echo get_phrases(['store']); ?></th>
                            <th class="text-center"><?php echo get_phrases(['return','qty']); ?></th>
                            <th class="text-center"><?php echo get_phrases(['price']); ?></th>
                            <th class="text-center"><?php echo get_phrases(['vat']); ?> %</th>
                            <th class="text-center"><?php echo get_phrases(['deduct']); ?> %</th>
                            <th class="text-center"><?php echo get_phrases(['total', 'amount']); ?></th>


                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($details) {
                            $sl = 1;
                            foreach ($details as $detail) { ?>
                                <tr>
                                    <td class="text-center"><?php echo $sl++; ?></td>
                                    <td class="text-center"><?php echo $detail->item_name ?></td>
                                    <td class="text-center"><?php echo $detail->store_name ?></td>
                                    <td class="text-center"><?php echo $detail->return_qty ?></td>
                                    <td class="text-center"><?php echo $detail->price ?></td>
                                    <td class="text-center"><?php echo $detail->vat_per ?></td>
                                    <td class="text-center"><?php echo $detail->deduct_per ?></td>
                                    <td class="text-center"><?php echo $detail->row_total ?></td>
                                </tr>
                        <?php }
                        } ?>
                    </tbody>
                    <tfoot>

<tr>
   <td colspan="6" rowspan="3">
      <center><label for="details" class="  col-form-label text-center"><?php echo get_phrases(['reason']) ?></label></center>
      <textarea class="form-control" name="details" id="details" placeholder="<?php echo get_phrases(['reason']) ?>"><?php echo ($main ? $main->reason : '') ?></textarea> <br>

   </td>
   <td class="text-right" colspan="1"><b><?php echo get_phrases(['total', 'vat']) ?>:</b></td>
   <td class="text-center">
   <?php echo ($main ? $main->total_vat : '') ?>
   </td>
</tr>

<tr>
   <td class="text-right" colspan="1"><b><?php echo get_phrases(['total', 'deduction']) ?>:</b></td>
   <td class="text-center">
   <?php echo ($main ? $main->total_deduction	 : '') ?>
   </td>
</tr>
<tr>
   <td colspan="1" class="text-right"><b><?php echo get_phrases(['net', 'return', 'amount']) ?>:</b></td>
   <td class="text-center">
   <?php echo ($main ? $main->net_amount	 : '') ?>
   </td>

</tr>

</tfoot>
                </table>

  


            </div>
        </div>
    </div>
</div>