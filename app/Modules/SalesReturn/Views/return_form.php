<div class="row">
   <div class="col-sm-12">
      <div class="card">
         <div class="card-header py-2">
            <div class="d-flex justify-content-between align-items-center">
               <div>
                  <nav aria-label="breadcrumb" class="order-sm-last p-0">
                     <ol class="breadcrumb d-inline-flex font-weight-600 fs-17 bg-white mb-0 float-sm-left p-0">
                        <li class="breadcrumb-item"><a href="#"><?php echo $moduleTitle; ?></a></li>
                        <li class="breadcrumb-item active"><?php echo $title; ?></li>
                     </ol>
                  </nav>
               </div>
               <div class="text-right">
                  <?php if ($permission->module('do_list')->access()) { ?>
                     <a href="<?php echo base_url('sale/deliver_order/do_list'); ?>" class="btn btn-warning btn-sm mr-1 ml-2"><i class="fas fa-angle-double-left mr-1"></i><?php echo get_phrases(['return', 'list']); ?></a>
                  <?php } ?>
               </div>
            </div>
         </div>
         <div class="card-body">
            <?php echo form_open('return/sales_return/save_sales_return', 'id="do_form"') ?>
            <div class="row form-group">
               <label for="dealer_name" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['dealer', 'name']) ?> <i class="text-danger">*</i></label>
               <div class="col-sm-4">
                  <input type="text" name="dealer_name" placeholder="<?php echo get_phrases(['dealer', 'name']); ?>" class="form-control" id="dealer_name" autocomplete="off" tabindex="1" required value="<?= ($delivery_main->dealer_name ? $delivery_main->dealer_name : old('dealer_name')); ?>" readonly>
                  <input type="hidden" name="dealer_id" placeholder="" class="form-control" id="dealer_id" autocomplete="off" tabindex="1" required value="<?= ($delivery_main->dealer_id ? $delivery_main->dealer_id : old('dealer_id')); ?>" readonly>
               </div>
               <label for="dealer_address" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['dealer', 'address']) ?> <i class="text-danger">*</i></label>
               <div class="col-sm-4">
                  <input type="text" name="dealer_address" placeholder="<?php echo get_phrases(['dealer', 'address']); ?>" class="form-control" id="dealer_address" autocomplete="off" tabindex="1" required value="<?= ($delivery_main->dealer_address ? $delivery_main->dealer_address : old('dealer_address')); ?>" readonly>
                  <input type="hidden" name="do_id" placeholder="<?php echo get_phrases(['do', 'no']); ?>" class="form-control" id="do_id" autocomplete="off" tabindex="1" required value="<?= ($delivery_main->do_id ? $delivery_main->do_id : old('do_id')); ?>" readonly>
               </div>
            </div>

            <div class="row form-group">
               <label for="challan_no" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['challan', 'no']) ?> <i class="text-danger">*</i></label>
               <div class="col-sm-4">
                  <input type="text" name="challan_no" placeholder="<?php echo get_phrases(['challan', 'no']); ?>" class="form-control" id="challan_no" autocomplete="off" tabindex="1" required value="<?= ($delivery_main->challan_no ? $delivery_main->challan_no : old('challan_nos')); ?>" readonly>
               </div>
               <label for="date" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['date']) ?> <i class="text-danger">*</i></label>
               <div class="col-sm-4">
                  <input type="text" name="date" placeholder="<?php echo get_phrases(['date']); ?>" class="form-control" id="date" autocomplete="off" tabindex="1" required value="<?= date('Y-m-d') ?>" readonly>
               </div>
            </div>
            <div class="row form-group">
               <label for="do_no" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['dO', 'no']) ?> <i class="text-danger">*</i></label>
               <div class="col-sm-4">
                  <input type="text" name="do_no" placeholder="<?php echo get_phrases(['dO', 'no']); ?>" class="form-control" id="do_no" autocomplete="off" tabindex="1" required value="<?= ($delivery_main->vouhcer_no ? $delivery_main->vouhcer_no : old('do_no')); ?>" readonly>
                  <input type="hidden" name="delivery_id" value="<?= ($delivery_main->delivery_id ? $delivery_main->delivery_id : old('delivery_id')); ?>">
               </div>

            </div>


            <div class="form-table">
               <table class="table table-bordered" id="sales_return_table">
                  <thead>
                     <tr>
                        <th>
                           <nobr><?php echo get_phrases(['sl', 'no']) ?></nobr>
                        </th>
                        <th class="text-center"><?php echo get_phrases(['item', 'name']) ?></th>
                        <th class="text-center"><?php echo get_phrases(['store', 'name']) ?></th>
                        <th class="text-center"><?php echo get_phrases(['delivered', 'quantity']) ?></th>
                        <th class="text-center"><?php echo get_phrases(['return', 'quantity']) ?><i class="text-danger">*</i></th>
                        <th class="text-center"><?php echo get_phrases(['bag', 'weight']) ?></th>
                        <th class="text-center"><?php echo get_phrases(['price']) ?></th>
                        <th class="text-center"><?php echo get_phrases(['vat']) ?> %</th>
                        <th class="text-center"><?php echo get_phrases(['deduction']) ?> %</th>
                        <th class="text-center"><?php echo get_phrases(['total', 'amount']) ?></th>
                        <th class="text-center"><?php echo get_phrases(['action']) ?></th>
                     </tr>
                  </thead>
                  <tbody>
                     <?php 
                     $retuernModel = new \App\Modules\SalesReturn\Models\Bdtaskt1m12ReturnModel();
                     if ($delivery_details) {
                        $sl = 1;
                        $i = 0;
                        foreach ($delivery_details as $details) {
                           $check_previous = $retuernModel->check_previous_return($details->item_id,$delivery_main->delivery_id);
                           $total_prev_retu = ($check_previous?$check_previous->totalreturn:0);
                           $avail_qty = $details->quantity - $total_prev_retu;
                     ?>
                           <tr>
                              <td><?php echo $sl; ?></td>
                              <td><input type="text" class="form-control" name="item_name[]" value="<?php echo ($details->item_name ? $details->item_name : old('item_name.' . $i++))  ?>" readonly><input type="hidden" class="form-control" name="product_id[]" value="<?php echo ($details->item_id ? $details->item_id : old('product_id.' . $i++))  ?>"> </td>
                              <td><input type="text" class="form-control" name="store_name[]" id="store_name" value="<?php echo ($details->store_name ? $details->store_name : old('store_name.' . $i++)) ?>" readonly><input type="hidden" class="form-control" name="store_id[]" id="store_id" value="<?php echo ($details->store_id ? $details->store_id : old('store_id.' . $i++)) ?>"></td>
                              <td><input type="text" class="form-control text-right" name="delivered_qty[]" value="<?php echo ($details->quantity ? $avail_qty: old('delivered_qty.' . $i++))  ?>" id="delivered_quantity_<?php echo $sl; ?>" readonly></td>
                              <td><input type="text" class="form-control text-right onlyNumber" name="quantity[]" value="<?php echo old('quantity.' . $i++)  ?>" id="return_quantity_<?php echo $sl; ?>" onkeyup="returnCalculation(<?php echo $sl; ?>)" autocomplete="off" required></td>
                              <td><input type="text" class="form-control text-right" id="bag_weight_<?php echo $sl; ?>" name="bag_weight[]" value="<?php echo ($details->bag_weight ? $details->bag_weight : old('bag_weight.' . $i++)) ?>" readonly></td>
                              <td><input type="text" class="form-control text-right" id="unit_price_<?php echo $sl; ?>" name="unit_price[]" value="<?php echo ($details->unit_price ? $details->unit_price : old('unit_price.' . $i++)) ?>" readonly></td>
                              <td><input type="text" class="form-control text-right onlyNumber" id="vat_percentage_<?php echo $sl; ?>" name="vat_percentage[]" value="<?php echo ($settings_info ? $settings_info->default_vat : 0) ?>" onkeyup="returnCalculation(<?php echo $sl; ?>)"></td>
                              <td><input type="hidden" class="vatamount" id="vat_amount_<?php echo $sl; ?>">
                              <input type="hidden" class="deductamount" id="deduct_amount_<?php echo $sl; ?>"><input type="text" class="form-control text-right onlyNumber" id="deduction_percentage_<?php echo $sl; ?>" name="deduction_percentage[]" onkeyup="returnCalculation(<?php echo $sl; ?>)"></td>
                              <td><input type="text" class="form-control text-right row_total" id="total_amount_<?php echo $sl; ?>" name="total_amount[]" readonly></td>
                              <td><a href="#" class="btn btn-danger" onclick="deleteRow(this)"><i class="fas fa-trash-alt"></i></a></td>
                           </tr>
                     <?php $sl++;}
                     } ?>
                  </tbody>
                  <tfoot>

                     <tr>
                        <td colspan="8" rowspan="3">
                           <center><label for="details" class="  col-form-label text-center"><?php echo get_phrases(['reason']) ?></label></center>
                           <textarea class="form-control" name="details" id="details" placeholder="<?php echo get_phrases(['reason']) ?>"></textarea> <br>
                           <span class="usablity"><u style="font-size: 20px;color: green;"><?php echo get_phrases(['usablilties']) ?></u> </span><br>
                           <label for="adjust_stock">
                              <input type="radio" checked="checked" name="return_type" id="adjust_stock" value="1">
                              <?php echo get_phrases(['adjust', 'with', 'stock']) ?>
                           </label><br>


                           <label for="wastage">
                              <input type="radio" name="return_type" value="3" id="wastage">
                              <?php echo get_phrases(['wastage']) ?>

                           </label>

                        </td>
                        <td class="text-right" colspan="1"><b><?php echo get_phrases(['total', 'vat']) ?>:</b></td>
                        <td class="text-right">
                           <input id="total_vat_ammount" class="form-control text-right valid valid_number" name="total_vat_amount" value="" readonly="readonly" type="text">
                        </td>
                     </tr>
                  
                     <tr>
                        <td class="text-right" colspan="1"><b><?php echo get_phrases(['total', 'deduction']) ?>:</b></td>
                        <td class="text-right">
                           <input id="total_deduct_amount"  class="form-control text-right valid valid_number" name="total_deduction" value="" readonly="readonly"  type="text">
                        </td>
                     </tr>
                     <tr>
                        <td colspan="1" class="text-right"><b><?php echo get_phrases(['net', 'return', 'amount']) ?>:</b></td>
                        <td class="text-right">
                           <input type="text" id="grandTotal" class="form-control text-right valid_number" name="grand_total" value="" readonly="readonly" />
                        </td>

                     </tr>

                  </tfoot>
               </table>
            </div>


            <div class="row form-group mt-3">
               <div class="col-sm-12 text-right">
                  <button type="submit" class="btn btn-success" tabindex="2"><?php echo get_phrases(['return']); ?></button>
               </div>
            </div>

            <?php echo form_close() ?>
         </div>
      </div>
   </div>
</div>
<script>
   function deleteRow(t) {
      var a = $("#sales_return_table > tbody > tr").length;
      if (1 == a) {
         alert("There only one row you can't delete.");

      } else {

         var e = t.parentNode.parentNode;
         e.parentNode.removeChild(e);

      }
   }

   function returnCalculation(sl){
   var ret_quantity     = $("#return_quantity_"+sl).val();
   var deliver_quantity = $("#delivered_quantity_"+sl).val();
   var vat_per          = $("#vat_percentage_"+sl).val();
   var deduct_per       = $("#deduction_percentage_"+sl).val();
   var item_price       = $("#unit_price_"+sl).val();
   if(parseFloat(ret_quantity) > parseFloat(deliver_quantity)){
   toastr.error('Return Quantity Can not more than Delivered Quantity');
   $("#return_quantity_"+sl).val(0);
   $("#return_quantity_"+sl).focus();
   //return false;
   }
   var ret_quantity      = $("#return_quantity_"+sl).val();
   var total_amount      = (item_price?parseFloat(item_price):0) * (ret_quantity?parseFloat(ret_quantity):0);
   var vat_amount        = ((total_amount?parseFloat(total_amount):0) * (vat_per?parseFloat(vat_per):0))/100;
   var price_without_vat = (total_amount?parseFloat(total_amount):0) - (vat_amount?parseFloat(vat_amount):0);
   var deduct_amount     = ((price_without_vat?parseFloat(price_without_vat):0) * (deduct_per?parseFloat(deduct_per):0))/100;
   $("#vat_amount_"+sl).val(vat_amount);
   $("#deduct_amount_"+sl).val(deduct_amount);
   var row_total         = (price_without_vat?parseFloat(price_without_vat):0) - (deduct_amount?parseFloat(deduct_amount):0);
   $("#total_amount_"+sl).val(row_total);
   var tot_vat = 0;
      $(".vatamount").each(function() {
         isNaN(this.value) || 0 == this.value.length || (tot_vat += parseFloat(this.value))
      });

      var tot_deduct = 0;
      $(".deductamount").each(function() {
         isNaN(this.value) || 0 == this.value.length || (tot_deduct += parseFloat(this.value))
      }); 

      $("#total_vat_ammount").val(tot_vat);
      $("#total_deduct_amount").val(tot_deduct);
   
   var tot_row = 0;
      $(".row_total").each(function() {
         isNaN(this.value) || 0 == this.value.length || (tot_row += parseFloat(this.value))
      }); 
      $("#grandTotal").val(tot_row);
      
   }


</script>