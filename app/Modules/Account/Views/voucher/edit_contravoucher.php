     <div class="row">
               <div class="col-md-6 col-sm-6">
                  <div class="form-group">
                     <label class="font-weight-600 mb-0"><?php echo get_phrases(['reverse', 'account', 'head']); ?> <i class="text-danger">*</i></label>
                     <select name="reverse_head" class="form-control custom-select select2" id="reverse_head" required>
            <option value="">Select Head</option>
        <?php if ($dbtcrthead) {
foreach ($dbtcrthead as $dbtcrt) { ?>
        <option value="<?php echo $dbtcrt->id ?>" <?php echo ($results->RevCodde == $dbtcrt->id ?'selected':'')?>>
          <?php echo $dbtcrt->text ?>
        </option>
        <?php }
} ?>
      </select>
                  </div>
               </div>

               <div class="col-md-6 col-sm-6">
                  <div class="form-group">
                     <label class="font-weight-600 mb-0"><?php echo get_phrases(['voucher', 'date']); ?></label>
                     <input type="text" name="voucher_date" id="voucher_date" class="form-control" value="<?php echo $results->VDate; ?>" readonly="">
                     <input type="hidden" name="voucher_no" id="voucher_no" class="form-control" readonly="" value="<?php echo $results->VNo; ?>">
                  </div>
               </div>
      
            <div class="table-responsive">
               <table class="table table-stripped table-sm">
                  <thead>
                     <tr>
                        <th class="text-center" width="45%"><?php echo get_phrases(['account', 'head']); ?><i class="text-danger">*</i></th>
                        <th class="text-center" width="20%"><?php echo get_phrases(['ledger', 'comments']); ?></th>
                        <th class="text-center" width="25%"><?php echo get_phrases(['debit']); ?></th>
                        <th class="text-center" width="25%"><?php echo get_phrases(['credit']); ?></th>
                        <th class="text-center" width="10%"></th>
                     </tr>
                  </thead>
                  <tbody id="service_div">
                  <tr><td><select name="account_name" id="account_name" class="custom-select form-control account_name" required="required">
                  <?php if ($dbtcrthead) {
                  foreach ($dbtcrthead as $dbtcrt) { ?>
                        <option value="<?php echo $dbtcrt->id ?>" <?php echo ($results->COAID == $dbtcrt->id ?'selected':'')?>>
                        <?php echo $dbtcrt->text ?>
                        </option>
                        <?php }
                  } ?>
                  </select><input type="hidden" name="head_code" class="form-control head_code" value="<?php echo $results->COAID; ?>" readonly><input type="hidden" name="sub_type" id="sub_type_id" class="form-control sub_type" value="<?php echo $results->subType ;?>" readonly></td><td><input type="text" name="ledger_comments" class="form-control " autocomplete="off" value="<?php echo $results->ledgerComment ;?>"></td><td><input type="text" name="debit" class="form-control debit onlyNumber text-right" value="<?php echo $results->Debit ;?>" autocomplete="off" required></td><td><input type="text" name="credit" class="form-control credit onlyNumber text-right" value="<?php echo $results->Credit ;?>" autocomplete="off" required></td></tr>
                  </tbody>
                  <tfoot>
               </table>
            </div>

           
               <div class="col-md-12 col-sm-12">
                  <div class="row mb-2">
                     <div class="col-md-12 col-sm-12">
                        <label class="font-weight-600 mb-0"><?php echo get_phrases(['remarks']); ?></label>
                        <textarea name="remarks" class="form-control" id="remarks" rows="2"><?php echo $results->Narration ;?></textarea>
                     </div>
                  </div>
               </div>
               <div class="col-sm-12 text-right">
    <button type="submit" class="btn btn-success"><?php echo get_phrases(['update', 'voucher']); ?></button>
  
  </div>
            </div>

            <script>
 $(document).ready(function() {
       "use strict";
$('#account_name').select2({
               placeholder: '<?php echo get_phrases(['select', 'debit', 'account']); ?>',
               minimumInputLength: 1,
               ajax: {
                   url: _baseURL + 'account/vouchers/debOrCHead',
                   dataType: 'json',
                   delay: 250,
                   processResults: function(data) {
   
                       return {
                           results: $.map(data, function(item) {
   
                               return {
                                   text: item.text,
                                   id: item.id
                               }
                           })
                       };
                   },
                   cache: true
               }
           });
      });
            </script>