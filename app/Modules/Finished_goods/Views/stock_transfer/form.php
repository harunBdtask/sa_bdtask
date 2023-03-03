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
                     <a href="<?php echo base_url('finished_goods/store_transfer/store_transfer_list'); ?>" class="btn btn-warning btn-sm mr-1 ml-2"><i class="fas fa-angle-double-left mr-1"></i><?php echo get_phrases(['transfer', 'list']); ?></a>
                  <?php } ?>
               </div>
            </div>
         </div>
         <div class="card-body">
            <?php echo form_open('finished_goods/store_transfer/save_store_transfer', 'id="stock_transfer_form"') ?>
            <div class="row form-group">
               <label for="from_store" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['from', 'store']) ?> <i class="text-danger">*</i></label>
               <div class="col-sm-4">
                  <?php echo form_dropdown('from_store', $goods_store, null, 'class="form-control select2" id="from_store" onchange="FromStoreTostore(this.value)"') ?>

               </div>
               <label for="date" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['date']) ?> <i class="text-danger">*</i></label>
               <div class="col-sm-4">
                  <input type="text" name="date" placeholder="<?php echo get_phrases(['enter', 'new', 'date']); ?>" class="form-control" id="date" value="<?php echo date('Y-m-d') ?>" required readonly>

               </div>
            </div>
            <div class="row">
               <table class="table table-bordered" id="store_transfer_table">
                  <thead>
                     <tr>
                        <th class="text-center"><?php echo get_phrases(['item', 'name']); ?></th>
                        <th class="text-center"><?php echo get_phrases(['to', 'store']); ?></th>
                        <th class="text-center"><?php echo get_phrases(['batch']); ?></th>
                        <th class="text-center"><?php echo get_phrases(['avail', 'qty']); ?></th>
                        <th class="text-center"><?php echo get_phrases(['transfer', 'qty']) . '( Bag )'; ?></th>
                        <th class="text-center"><?php echo get_phrases(['total', 'kg']); ?></th>
                        <th class="text-center"><?php echo get_phrases(['action']); ?></th>


                     </tr>
                  </thead>

                  <tbody id="trasnfer_tbody">

                     <tr>
                        <td><?php echo form_dropdown('product_id[]', $goods_list, null, 'class="form-control select2" onchange="goodsBatchStock(1)" id="product_id_1"') ?></td>
                        <td><select class="form-control select2" id="to_store_1" name="to_store[]" required>
                              <option value=""></option>
                           </select></td>
                        <td><select class="form-control select2" id="batch_id_1" name="batch_id[]" onchange="batchAvailStock(1,this.value)" required>
                              <option value=""></option>
                           </select></td>
                        <td><input type="text" class="form-control text-right" name="avail_qty[]" id="avail_qty_1" readonly></td>
                        <td><input type="text" value="<?= old('trans_qty.0'); ?>" class="form-control text-right total_bag" name="trans_qty[]" id="trans_qty_1" onkeyup="qtyCalculation(1)"></td>
                        <td><input type="hidden" id="bag_size_1" name="bag_size[]"><input type="text" class="form-control text-right total_kg" name="total_kg[]" id="total_kg_1" readonly></td>
                        <td><a href="#" class="btn btn-danger" onclick="deleteRow(this,' + count + ')"><i class="fas fa-trash-alt"></i></a></td>

                     </tr>

                  </tbody>
                  <tfoot>
                     <tr>
                        <th colspan="4" class="text-right"><?php echo get_phrases(['total']); ?></th>
                        <th><input type="text" class="form-control text-right" name="total_bag" id="total_bagqty" readonly></th>
                        <th><input type="text" class="form-control text-right" name="total_weight" id="total_kg_weght" readonly></th>
                        <th><a href="javascript:void(0)" class="btn btn-info" tabindex="7" id="add_new_item" onclick="addnewRow('trasnfer_tbody')"><i class="fas fa-plus"></i></a></th>
                     </tr>
                  </tfoot>
               </table>


            </div>
            <div class="row form-group">
               <label for="from_store" class="col-sm-11 col-form-label font-weight-600"></label>
               <div class="col-sm-1">
                  <button type="submit" class="btn btn-success"><?php echo get_phrases(['save']); ?></button>

               </div>
            </div>
            <?php echo form_close(); ?>
         </div>
      </div>
   </div>
</div>

<script>
   var count = 1;

   function addnewRow(t) {

      var row = $("#store_transfer_table tbody tr").length;
      var from_store = $("#from_store").val();
      var count = row + 1;
      var commission_rate = $("#dealer_commission_rate").val();
      var preitems = [];
      var inps = document.getElementsByName('product_id[]');
      for (var i = 0; i < inps.length; i++) {
         var inp = inps[i];
         preitems.push(inp.value);
      }
      var limits = 500;
      if (count == limits) alert("You have reached the limit of adding " + count + " inputs");
      else {
         var a = "product_id_" + count,
            e = document.createElement("tr");
         e.innerHTML = '<td><select class="form-control select2" id="product_id_' + count + '" name="product_id[]" required onchange="goodsBatchStock(' + count + ')"><option value=""></option></select></td><td><select class="form-control select2" id="to_store_' + count + '" name="to_store[]" required><option value=""></option></select></td><td><select class="form-control select2" id="batch_id_' + count + '" name="batch_id[]" onchange="batchAvailStock(' + count + ',this.value)" required><option value=""></option></select></td><td><input type="text" class="form-control text-right" name="avail_qty[]" id="avail_qty_' + count + '" readonly></td><td><input type="text" class="form-control text-right total_bag" name="trans_qty[]" id="trans_qty_' + count + '" onkeyup="qtyCalculation(' + count + ')"></td><td><input type="hidden" id="bag_size_' + count + '" name="bag_size[]"><input type="text" class="form-control text-right total_kg" name="total_kg[]" id="total_kg_' + count + '" readonly></td><td><a href="#" class="btn btn-danger" onclick="deleteRow(this,' + count + ')"><i class="fas fa-trash-alt"></i></a></td>',
            document.getElementById(t).appendChild(e),
            document.getElementById(a).focus();

         $(".select2").select2();
         var a = $("#store_transfer_table > tbody > tr").length;

         var rnum = count;
         $.ajax({
            type: 'POST',
            url: _baseURL + 'finished_goods/store_transfer/getItemDropdown',
            dataType: 'json',
            data: {
               'csrf_stream_name': csrf_val,
               'pre_items': preitems,

            },
         }).done(function(data) {
            $("#product_id_" + rnum).select2({
               placeholder: '<?php echo get_phrases(['select', 'Item']); ?>',
               data: data
            });
         });

         var rnum = count;
         $.ajax({
            type: 'POST',
            url: _baseURL + 'finished_goods/store_transfer/getStoreList',
            dataType: 'json',
            data: {
               'csrf_stream_name': csrf_val,
               'from_store': from_store,

            },
         }).done(function(data) {
            $("#to_store_" + rnum).select2({
               placeholder: '<?php echo get_phrases(['select', 'Store']); ?>',
               data: data
            });
         });

         // document.getElementById("product_id_" + count).setAttribute("tabindex", starttab + 1);
         // document.getElementById("quantity_" + count).setAttribute("tabindex", starttab + 2);
         // document.getElementById("price_" + count).setAttribute("tabindex", starttab + 3);
         // document.getElementById("add_new_item").setAttribute("tabindex", starttab + 4);
         // document.getElementById("save_do").setAttribute("tabindex", starttab + 5);


         count++;

      }

   }

   function deleteRow(t, sl) {
      var a = $("#store_transfer_table > tbody > tr").length;
      if (1 == a) {
         alert("There only one row you can't delete.");
         document.getElementById('add_new_item').focus();
      } else {

         var e = t.parentNode.parentNode;
         e.parentNode.removeChild(e),
            document.getElementById('add_new_item').focus();

      }
   }

   function FromStoreTostore(from_store) {
      $.ajax({
         type: 'POST',
         url: _baseURL + 'finished_goods/store_transfer/getStoreList',
         dataType: 'json',
         data: {
            'csrf_stream_name': csrf_val,
            'from_store': from_store,

         },
      }).done(function(data) {
         $("#to_store_" + 1).select2({
            placeholder: '<?php echo get_phrases(['select', 'Store']); ?>',
            data: data
         });
      });
   }


   function goodsBatchStock(sl) {
      var product_id = $("#product_id_" + sl).val();
      var store_id = $("#from_store").val();
      $("#batch_id_" + sl).empty();
      $('#batch_id_' + sl).append('<option value="">Select Batch</option>');

      $.ajax({
         type: 'POST',
         url: _baseURL + 'finished_goods/store_transfer/getItemBatchstock',
         data: {
            'csrf_stream_name': csrf_val,
            'product_id': product_id,
            'store_id': store_id,
         },
         dataType: 'json',
         success: function(data) {
            $("#batch_id_" + sl).select2({
               placeholder: '<?php echo get_phrases(['select', 'Batch']); ?>',
               data: data
            });

         },
         error: function() {

         }
      });

   }

   function batchAvailStock(sl, batch_id) {
      var product_id = $("#product_id_" + sl).val();
      var store_id = $("#from_store").val();
      $.ajax({
         type: 'POST',
         url: _baseURL + 'finished_goods/store_transfer/getBatchstock',
         data: {
            'csrf_stream_name': csrf_val,
            'product_id': product_id,
            'store_id': store_id,
            'batch_id': batch_id
         },
         dataType: 'json',
         success: function(data) {
            $("#avail_qty_" + sl).val(data.stock);
            $("#bag_size_" + sl).val(data.bag_size);
         },
         error: function() {

         }
      });

   }

   function qtyCalculation(sl) {
      var bag_weight = $("#bag_size_" + sl).val();
      var total_bag  = $("#trans_qty_" + sl).val();
      var avai_qty   = $("#avail_qty_" + sl).val();
      if(parseFloat(total_bag) > parseFloat(avai_qty)){
       toastr.error('You Can not Transfer More Than Available Quantity');
       $("#trans_qty_" + sl).val(0);
       $("#trans_qty_" + sl).focus();
      }
      var total_bag  = $("#trans_qty_" + sl).val();
      var total_weight = (bag_weight ? parseFloat(bag_weight) : 0) * (total_bag ? parseFloat(total_bag) : 0);
      $("#total_kg_" + sl).val(total_weight);
      var tot_bag = 0;
      $(".total_bag").each(function() {
         isNaN(this.value) || 0 == this.value.length || (tot_bag += parseFloat(this.value))
      });
      var tot_kg = 0;
      $(".total_kg").each(function() {
         isNaN(this.value) || 0 == this.value.length || (tot_kg += parseFloat(this.value))
      });

      $("#total_bagqty").val(tot_bag);
      $("#total_kg_weght").val(tot_kg);
   }
</script>