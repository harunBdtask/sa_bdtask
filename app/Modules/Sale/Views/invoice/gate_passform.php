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
            <?php echo form_open_multipart('sale/deliver_order/save_gatepass', '') ?>
            <div class="row form-group">
               <label for="name" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['select', 'store']) ?> <i class="text-danger">*</i></label>
               <div class="col-sm-4">
                  <?php echo form_dropdown('store_id', $store_list, ($scaler ? $scaler->store_id : ''), 'class="form-control select2" id="store_id" required') ?>
               </div>
               <label for="name" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['date']) ?> <i class="text-danger">*</i></label>
               <div class="col-sm-4">
                  <input type="text" class="form-control" name="date" value="<?php echo date('Y-m-d') ?>" readonly>
                  <input type="hidden" name="challan_no" value="<?php echo $delivery_main->challan_no ?>">
               </div>
            </div>
            <div class="row form-group">
               <label for="name" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['dealer', 'name']) ?> <i class="text-danger">*</i></label>
               <div class="col-sm-4">
                  <input type="text" class="form-control" name="dealer_name" value="<?php echo $delivery_main->dealer_name ?>" readonly>
               </div>
               <label for="name" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['dO', 'no']) ?> <i class="text-danger">*</i></label>
               <div class="col-sm-4">
                  <input type="text" class="form-control" name="do_no" value="<?php echo $delivery_main->vouhcer_no ?>" readonly>
               </div>
            </div>

            <div class="row form-group">
               <label for="name" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['driver', 'name']) ?> <i class="text-danger">*</i></label>
               <div class="col-sm-4">
                  <input type="text" class="form-control" name="driver_name" value="<?php echo ($scaler ? $scaler->driver_name : '') ?>" autocomplete="off" required>
               </div>
               <label for="name" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['driver', 'mobile', 'no']) ?> <i class="text-danger">*</i></label>
               <div class="col-sm-4">
                  <input type="text" class="form-control" name="driver_mobile" value="<?php echo ($scaler ? $scaler->driver_mobile_no : '') ?>" autocomplete="off" required>
               </div>
            </div>

            <div class="row form-group">
               <label for="name" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['truck', 'no']) ?> <i class="text-danger">*</i></label>
               <div class="col-sm-4">
                  <input type="text" class="form-control" name="truck_no" value="<?php echo ($scaler ? $scaler->truck_no : '') ?>" autocomplete="off" required>
               </div>
               <label for="name" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['truck', 'weight']) ?> <i class="text-danger">*</i></label>
               <div class="col-sm-4">
                  <input type="text" class="form-control valid_number " id="truck_weight" name="truck_weight" value="<?php echo ($scaler ? $scaler->truck_weight : '') ?>" autocomplete="off" onkeyup="weightCalculation()" required>
               </div>
            </div>

            <div class="row form-group">
               <label for="name" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['truck', 'weight', 'with', 'items']) ?> <i class="text-danger">*</i></label>
               <div class="col-sm-4">
                  <input type="text" class="form-control valid_number " id="truck_weight_withitem" name="truck_weight_withitem" value="<?php echo ($scaler ? $scaler->truck_weight_with_items : '') ?>" autocomplete="off" onkeyup="weightCalculation()">
               </div>
               <label for="name" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['item', 'weight']) ?> <i class="text-danger">*</i></label>
               <div class="col-sm-4">
                  <input type="text" class="form-control" name="item_weight" id="item_weight" value="<?php $itemweight =  ($scaler ? $scaler->truck_weight_with_items : 0) - ($scaler ? $scaler->truck_weight : 0);
                                                                                                      echo ($scaler ? $itemweight : '') ?>" readonly>
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
            <div class="row form-group">

               <div class="col-sm-12 text-right">

                  <button type="submit" class="btn btn-success" id="save_do"><?php echo get_phrases(['save']) ?></button>
               </div>

            </div>
            <?php echo form_close() ?>
         </div>

      </div>
   </div>
</div>
<script type="text/javascript">
   $(document).ready(function() {
      "use strict";
      $(".select2").select2();
   });

   $('body').on('keypress', '.valid_number', function(event) {
      var charCode = (event.which) ? event.which : event.keyCode;
      if (charCode != 46 && charCode != 45 && charCode > 31 &&
         (charCode < 48 || charCode > 57)) {
         toastr["error"]('Please Input Valid Number');
         return false;
      }


      return true;

   });

   function weightCalculation() {
      var truck_weight = $("#truck_weight").val();
      var truck_weight_items = $("#truck_weight_withitem").val();
      var item_weight = (truck_weight_items ? parseFloat(truck_weight_items) : 0) - (truck_weight ? parseFloat(truck_weight) : 0);
      $("#item_weight").val(item_weight);
   }
</script>