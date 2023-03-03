<div class="row">
    <div class="col-md-6">
        <img src="<?php echo base_url().$setting->logo;?>" class="img-responsive" alt=""></br>
        <?php echo get_phrases(['phone']) ?>: <?php echo $setting->phone; ?></br>
        <?php echo get_phrases(['email']) ?>: <?php echo $setting->email; ?>
        <br>
    </div>
    <div class="col-md-6 text-right">
         <address>
            <strong><?php echo $setting->title; ?></strong><br>
            <?php echo $setting->address; ?>
        </address>
    </div>
</div> <hr>
<h4><center id="title"></center></h4>
<div class="table-responsive">
    <table class="table table-stripped table-sm table-hover detailsTable" >
        <thead>
            <tr>
                <th width="10%"><?php echo get_phrases(['account', 'head']);?></th>
                <th width="20%"><?php echo get_phrases(['supplier', 'name']);?></th>
                <th width="10%" class="text-right"><?php echo get_phrases(['total','purchase']);?></th>
                <th width="10%" class="text-right"><?php echo get_phrases(['total','paid']);?></th>
                <th width="10%" class="text-right"><?php echo get_phrases(['on','credit']);?></th>
                <th width="10%" class="text-right"><?php echo get_phrases(['credit','payment']);?></th>
                <th width="10%" class="text-right"><?php echo get_phrases(['current','due']);?></th>
                <th width="10%" class="text-right"><?php echo get_phrases(['manual','jv']);?></th>
                <th width="10%" class="text-right"><?php echo get_phrases(['current','stock']);?></th>
            </tr>
        </thead>
        <tbody>
           <?php 
           $total = 0;
           if(!empty($results)){
                foreach ($results as $value) { 
                  //$total += $value->paid_amount;
                   $stock_amount = $value->stock_amount;
                  if($value->stock_amount <0){
                    $stock_amount = 0;
                  }

                  ?>
                   <tr onclick="onclick_change_bg('.detailsTable', this, 'cyan')">
                       <td><?php echo esc($value->acc_head);?></td>
                       <td><?php echo esc($value->supplier_name);?></td>
                       <td class="text-right"><?php echo esc(number_format($value->purchase_total, 2));?></td>
                       <td class="text-right"><?php echo esc(number_format($value->paid_total, 2));?></td>
                       <td class="text-right"><?php echo esc(number_format($value->due_total, 2));?></td>
                       <td class="text-right"><?php echo esc(number_format($value->credit_paid_total, 2));?></td>
                       <td class="text-right"><?php echo esc(number_format($value->due_total - $value->credit_paid_total, 2));?></td>
                       <td class="text-right"><?php echo esc(number_format($value->jv_amount, 2));?></td>
                       <td class="text-right"><?php echo esc(number_format($stock_amount, 2));?></td>
                   </tr>
            <?php } 
          } else { ?>
                <tr>
                    <th colspan="9" class="text-center text-muted text-danger"><?php echo get_notify('data_is_not_available');?></th>
                </tr>
          <?php } ?>
        </tbody>
        <!-- <tfoot>
            <tr>
                <th colspan="6" class="text-right"><?php //echo get_phrases(['total']);?></th>
                <th class="text-right"><?php //echo number_format($total, 2);?></th>
            </tr>
        </tfoot> -->
    </table>
</div>
<?php if($hasPrintAccess){ ?>
<div class="card-footer no-print">
    <button type="button" class="btn btn-info" onclick="printContent('printC')"><span class="fa fa-print"></span> <?php echo get_phrases(['print']);?></button>
</div><?php } ?>