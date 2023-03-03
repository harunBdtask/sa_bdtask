<link href="<?php echo base_url() ?>/assets/plugins/jquery-ui-1.12.1/jquery-ui.min.css" rel="stylesheet">
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
                     <a href="<?php echo base_url('sale/deliver_order/do_list'); ?>" class="btn btn-warning btn-sm mr-1 ml-2"><i class="fas fa-angle-double-left mr-1"></i><?php echo get_phrases(['sales', 'list']); ?></a>
                  <?php } ?>
               </div>
            </div>
         </div>
         <div class="card-body">
            <?php echo form_open('sale/deliver_order/save_do', 'id="do_form"') ?>
            <div class="row form-group">
               <label for="name" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['dealer', 'name']) ?> <i class="text-danger">*</i></label>
               <div class="col-sm-4">
                  <?php echo  form_dropdown('dealer_id', $dealer_list, null, 'class="form-control select2" id="dealer_id" tabindex="1" required') ?>
               </div>
               <label for="date" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['date']) ?> <i class="text-danger">*</i></label>
               <div class="col-sm-4">
                  <input type="text" name="date" placeholder="<?php echo get_phrases(['date']); ?>" class="form-control" id="name" autocomplete="off" tabindex="2" required value="<?php echo date('Y-m-d') ?>" readonly>
               </div>
            </div>
            <div class="row form-group">
               <label for="dealer_phone" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['dealer', 'phone','no']) ?> <i class="text-danger">*</i></label>
               <div class="col-sm-4">
               
                     <input type="text" name="dealer_phone_no" placeholder="<?php echo get_phrases(['dealer', 'phone','no']); ?>" class="form-control" id="dealer_phone_no" autocomplete="off" required value="" readonly>
                   
                 
               </div>
               <label for="dealer_address" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['dealer','address']) ?> <i class="text-danger"></i></label>
               <div class="col-sm-4">
                  <textarea id="address" class="form-control" placeholder="<?php echo get_phrases(['address']) ?>" readonly></textarea>
                  <input type="hidden" id="dealer_commission_rate" name="dealer_commission_rate">
               </div>
            </div>

              <div class="row form-group">
               <label for="date" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['sales', 'person']) ?> <i class="text-danger">*</i></label>
               <div class="col-sm-4">
                  <?php if (session('isAdmin') == 1) { ?>
                     <?php echo form_dropdown('sales_man', '', '', 'class="custom-select" id="sales_man" required');?>
                     <?php //echo form_dropdown('sales_man', $sales_persons, null, 'class="form-control select2" id="sales_person" required') ?>
                  <?php } else { ?>
                     <input type="text" name="sales_man_name" placeholder="<?php echo get_phrases(['sales', 'person']); ?>" class="form-control" id="sales_man_name" autocomplete="off" required value="<?php echo session('fullname') ?>" readonly>
                     <input type="hidden" name="sales_man" placeholder="<?php echo get_phrases(['sales', 'person']); ?>" class="form-control" id="sales_man" autocomplete="off" required value="<?php echo session('id') ?>" readonly>
                  <?php } ?>
               </div>
               <label for="sales_person_address" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['person','address']) ?> <i class="text-danger"></i></label>
               <div class="col-sm-4">
                  <input type="text" id="sales_person_address" class="form-control" name="sales_person_address" readonly="" value="<?php echo (session('isAdmin') == 1?'':$persons_info->present_address)?>">
               </div>
            </div>
              <div class="row form-group">
               <label for="dealer_previous" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['dealer', 'previous','balance']) ?> <i class="text-danger"></i></label>
               <div class="col-sm-4" >
               
                   <p class="form-control" id="dealer_previous" readonly></p>
                   
                 
               </div>
            </div>
            
            <div class="">
               <table class="table table-bordered" id="doinvoice">
                  <thead>
                     <tr>
                        <th class="text-center" style="width: 200px;">
                           <nobr><?php echo get_phrases(['item', 'name']); ?><i class="text-danger">*</i></nobr>
                        </th>
                        <th class="text-center">
                           <nobr><?php echo get_phrases(['order']) .' '. get_phrases(['quantity']) . ' ' . '(' . get_phrases(['bag']) . ')'; ?><i class="text-danger">*</i></nobr>
                        </th>
                        <th class="text-center"><?php echo get_phrases(['bag', 'size']); ?></th>
                        <th class="text-center"><?php echo get_phrases(['kg']); ?></th>
                        <th class="text-center"><?php echo get_phrases(['unit', 'price']); ?><i class="text-danger">*</i></th>
                        <th class="text-center">
                           <nobr><?php echo get_phrases(['total', 'amount']); ?></nobr>
                        </th>
                        <th class="text-center"><?php echo get_phrases(['action']); ?></th>
                     </tr>
                  </thead>
                  <tbody id="do_tbl_body">
                     <tr>
                        <td>
                           <select class="form-control select2" id="product_id_1" name="product_id[]" required onchange="checkitem_stock(1)">
                              <option value=""></option>
                           </select>
                        </td>
                        <td><input type="text" class="form-control text-right" name="quantity[]" id="quantity_1" onkeyup="do_calculation(1)" onchange="do_calculation(1)" tabindex="5" required autocomplete="off"></td>
                        <td> <input type="text" class="form-control text-right" name="bag_weight[]" id="bag_weight_1" readonly=""></td>
                        <td><input type="text" class="form-control text-right total_kg" id="kg_1" name="kg[]" readonly="" placeholder="0.00"></td>
                        <td><input type="text" class="form-control text-right" name="price[]" id="price_1" onkeyup="do_calculation(1)" onchange="do_calculation(1)" tabindex="6" required autocomplete="off" readonly=""></td>
                        <td><input type="text" class="form-control text-right total_price_t" name="total_pricen[]" id="total_price_t_1" readonly="" placeholder="0.00">
                           <input type="hidden" class="form-control text-right total_price" name="total_price[]" id="total_price_1" readonly="" placeholder="0.00">
                           <input type="hidden" class="form-control text-right dealer_commission" name="dealer_commission[]" id="dealer_commission_1" readonly="" placeholder="0.00">

                           <input type="hidden" class="form-control text-right commission_rate" id="commission_rate_1" readonly="" placeholder="0.00" name="commission_rate[]"><input type="hidden" class="form-control text-right commission_am" id="commission_amount_1" readonly="" placeholder="0.00" name="commission_amount[]">
                        </td>
                        <td><a href="#" class="btn btn-danger" onclick="deleteRow(this,1)"><i class="fas fa-trash-alt"></i></a></td>
                     </tr>
                  </tbody>
                  <tfoot>
                     <tr>
                        <th colspan="5" class="text-right"><?php echo get_phrases(['dO', 'value']) ?></th>
                        <th class="text-right"><input type="hidden" name="grandcommission" id="grandcommission" class="form-control text-right" placeholder="0.00" readonly="" value=""><input type="text" name="grnadtotalt" id="grandTotalt" class="form-control text-right" placeholder="0.00" readonly=""><input type="hidden" name="grnadtotal" id="grandTotal" class="form-control text-right" placeholder="0.00" readonly=""><input type="hidden" id="grand_kg" name="grand_kg"> </th>
                        <th><a href="javascript:void(0)" class="btn btn-info" tabindex="7" id="add_new_item" onclick="addnewRow('do_tbl_body')"><i class="fas fa-plus"></i></a></th>
                     </tr>
                  </tfoot>
               </table>
               <div class="row form-group">
                  <div class="col-sm-12 text-right">
                     <button type="submit" class="btn btn-success" id="save_do"><?php echo get_phrases(['save']) ?></button>
                  </div>
               </div>
            </div>
            <?php echo form_close() ?>
         </div>
      </div>
   </div>
</div>
<script src="<?php echo base_url() ?>/assets/plugins/jquery-ui-1.12.1/jquery-ui.min.js" type="text/javascript"></script>
<script type="text/javascript">
   $(document).ready(function() {
      "use strict";

      $('option:first-child').val('').trigger('change');

      $('#dealer_id').on('change', function (e) {
         var dealer_id = $(this).val(); 
         $.ajax({
               url: _baseURL+"sale/deliver_order/get_sales_man",
               type: 'POST',
               data: {'csrf_stream_name':csrf_val, dealer_id: dealer_id},
               dataType:"json",
               async: true,
               success: function (data) {
                  $('#sales_man option:first-child').val('').trigger('change');
                  $('#sales_man').empty();
                  $('#sales_man').select2({
                     placeholder: '<?php echo get_phrases(['select','option']); ?>' ,
                     data : data
                  });
                  var option = new Option('', '', true, true);
                  $("#sales_man").append(option).trigger('change');

               }
         });
      }); 

      $('.datepic').datepicker({
         dateFormat: 'yy-mm-dd'
      });

      $.ajax({
         type: 'GET',
         url: _baseURL + 'sale/deliver_order/getItemDropdown',
         dataType: 'json',
         data: {
            'csrf_stream_name': csrf_val
         },
      }).done(function(data) {
         $("#product_id_1").select2({
            placeholder: '<?php echo get_phrases(['select', 'Item']); ?>',
            data: data
         });
      });


   });



   function checkitem_stock(sl) {
      var dealers = $("#dealer_id").val();
      if (dealers != '') {
         var item_id = $("#product_id_" + sl).val();
         $.ajax({
            url: _baseURL + "sale/deliver_order/search_item_stock",
            method: 'post',
            dataType: "json",
            data: {
               product_id: item_id,
               csrf_stream_name: csrf_val
            },
            success: function(data) {
               if (data.price == 0) {
                  toastr.error('Price not set, please set price for this item');
                  return false;
               } else if (data.bag_weight == 0) {
                  toastr.error('Bag Weight not set, please set bag weight for this item');
                  return false;
               } else {
                  $("#available_qty_" + sl).val(data.avail_stock);
                  $("#price_" + sl).val(data.price);
                  $("#bag_weight_" + sl).val(data.bag_weight);
                  $("#commission_rate_" + sl).val(data.com_rate);
                  do_calculation(sl);
               }
            }
         });
      }
      // else{
      //    toastr.error('Please Select Dealer');
      //    return false;
      // }
   }

   function do_calculation(sl) {
      var quantity = $("#quantity_" + sl).val();
      var avai_qty = $("#available_qty_" + sl).val();
      var quantity = $("#quantity_" + sl).val();
      var price = $("#price_" + sl).val();
      var bag_weight = $("#bag_weight_" + sl).val();
      var kg = (bag_weight ? parseFloat(bag_weight) : 0) * (quantity ? parseFloat(quantity) : 0);
      $("#kg_" + sl).val(kg);
      var commission_rate = $("#commission_rate_" + sl).val();
      var dealer_commission_rate = $("#dealer_commission_rate").val();
      var total_commission = (kg ? parseFloat(kg) : 0) * (commission_rate ? parseFloat(commission_rate) : 0);
      var total_dealer_commission = (kg ? parseFloat(kg) : 0) * (dealer_commission_rate ? parseFloat(dealer_commission_rate) : 0);
      $("#commission_amount_" + sl).val(total_commission.toFixed(2, 2));
      $("#dealer_commission_" + sl).val(total_dealer_commission.toFixed(2, 2));

      var total_amount = (quantity ? parseFloat(quantity) : 0) * (price ? parseFloat(price) : 0);

      var totalwithout_commission = ((total_amount ? parseFloat(total_amount) : 0) - (total_commission ? parseFloat(total_commission) : 0)) - (total_dealer_commission ? parseFloat(total_dealer_commission) : 0);
      $("#total_price_" + sl).val(totalwithout_commission.toFixed(2, 2));
      $("#total_price_t_" + sl).val(total_amount);

      var kg_tot = 0;
        $(".total_kg").each(function() {
            isNaN(this.value) || 0 == this.value.length || (kg_tot += parseFloat(this.value))
        });
        $("#grand_kg").val(kg_tot.toFixed(2, 2));

      var gr_com = 0;
      $(".commission_am").each(function() {
         isNaN(this.value) || 0 == this.value.length || (gr_com += parseFloat(this.value))
      });

      $("#grandcommission").val(gr_com.toFixed(2, 2));


      var gr_tot = 0;
      var grt_t = 0;
      $(".total_price").each(function() {
         isNaN(this.value) || 0 == this.value.length || (gr_tot += parseFloat(this.value))
      });

      $(".total_price_t").each(function() {
         isNaN(this.value) || 0 == this.value.length || (grt_t += parseFloat(this.value))
      });



      $("#grandTotal").val(gr_tot.toFixed(2, 2));
      $("#grandTotalt").val(grt_t.toFixed(2, 2));
   }

   var count = 1;

   function addnewRow(t) {

      var row = $("#doinvoice tbody tr").length;
      var count = row + 1;
      var commission_rate = $("#dealer_commission_rate").val();

      var limits = 500;
      if (count == limits) alert("You have reached the limit of adding " + count + " inputs");
      else {
         var a = "product_id_" + count,
            e = document.createElement("tr");
         e.innerHTML = '<td><select class="form-control select2" id="product_id_' + count + '" name="product_id[]" required onchange="checkitem_stock(' + count + ')"><option value=""></option></select></td><td><input type="text" class="form-control text-right" name="quantity[]" id="quantity_' + count + '" onkeyup="do_calculation(' + count + ')" onchange="do_calculation(' + count + ')" required autocomplete="off"></td><td><input type="text" class="form-control text-right" name="bag_weight[]" id="bag_weight_' + count + '" readonly></td><td><input type="text" class="form-control text-right total_kg"  id="kg_' + count + '" name="kg[]" readonly="" placeholder="0.00"></td><td><input type="text" class="form-control text-right" name="price[]" id="price_' + count + '" onkeyup="do_calculation(' + count + ')" onchange="do_calculation(' + count + ')" required autocomplete="off" readonly></td><td><input type="hidden" class="form-control text-right dealer_commission" name="dealer_commission[]" id="dealer_commission_' + count + '" readonly="" placeholder="0.00"><input type="hidden" class="form-control text-right commission_rate"  id="commission_rate_' + count + '" readonly="" placeholder="0.00" name="commission_rate[]" value="' + (commission_rate ? commission_rate : 0) + '"><input type="hidden" class="form-control text-right commission_am"  id="commission_amount_' + count + '" readonly="" placeholder="0.00" name="commission_amount[]"><input type="text" class="form-control text-right total_price_t" name="total_pricen[]" id="total_price_t_' + count + '" readonly="" placeholder="0.00"><input type="hidden" class="form-control text-right total_price" name="total_price[]" id="total_price_' + count + '" readonly="" placeholder=""></td><td><a href="#" class="btn btn-danger" onclick="deleteRow(this,' + count + ')"><i class="fas fa-trash-alt"></i></a></td>',
            document.getElementById(t).appendChild(e),
            document.getElementById(a).focus();

         $(".select2").select2();
         var a = $("#doinvoice > tbody > tr").length;
         var max = 0;
         $('#do_form [tabindex]').attr('tabindex', function(a, b) {
            max = Math.max(max, +b);
         });
         if (a == 1) {
            var starttab = 2;
         } else {
            var starttab = max - 2;
         }

         var rnum = count;
         $.ajax({
            type: 'GET',
            url: _baseURL + 'sale/deliver_order/getItemDropdown',
            dataType: 'json',
            data: {
               'csrf_stream_name': csrf_val
            },
         }).done(function(data) {
            $("#product_id_" + rnum).select2({
               placeholder: '<?php echo get_phrases(['select', 'Item']); ?>',
               data: data
            });
         });

         document.getElementById("product_id_" + count).setAttribute("tabindex", starttab + 1);
         document.getElementById("quantity_" + count).setAttribute("tabindex", starttab + 2);
         document.getElementById("price_" + count).setAttribute("tabindex", starttab + 3);
         document.getElementById("add_new_item").setAttribute("tabindex", starttab + 4);
         document.getElementById("save_do").setAttribute("tabindex", starttab + 5);


         count++;

      }

   }

   function deleteRow(t, sl) {
      var a = $("#doinvoice > tbody > tr").length;
      if (1 == a) {
         alert("There only one row you can't delete.");
         document.getElementById('add_new_item').focus();
      } else {

         var e = t.parentNode.parentNode;
         e.parentNode.removeChild(e),
            do_calculation(sl)
         document.getElementById('add_new_item').focus();

      }
   }

   $('body').on('change', '#dealer_id', function(e) {
      e.preventDefault();
      var id = this.value;

      $.ajax({
         type: 'POST',
         url: _baseURL + 'sale/dealer/getDealerDetailsById/' + id,
         dataType: 'JSON',
         data: {
            'csrf_stream_name': csrf_val
         },
         success: function(data) {
            $('#address').val(data.dealer.address);
            $('#dealer_commission_rate').val(data.dealer.commission_rate);
            $('#dealer_phone_no').val(data.dealer.phone_no);
            $('#dealer_previous').html(data.previous_balance);
             
            //         $(".commission_rate").each(function() {
            // this.value = data.dealer.commission_rate;
            //     });
         },
         error: function() {

         }
      });
   });

      $('body').on('change', '#sales_person', function(e) {
      e.preventDefault();
      var id = this.value;

      $.ajax({
         type: 'POST',
         url: _baseURL + 'sale/deliver_order/getSalePersonDetailsById/' + id,
         dataType: 'JSON',
         data: {
            'csrf_stream_name': csrf_val
         },
         success: function(data) {
            $('#sales_person_address').val(data.persons.present_address);
         },
         error: function() {

         }
      });
   });
</script>