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
                <th width="20%"><?php echo get_phrases(['supplier', 'name']);?></th>
                <th width="10%"><?php echo get_phrases(['voucher','no']);?></th>
                <th width="10%"><?php echo get_phrases(['receive', 'date']);?></th>
                <th width="10%"><?php echo get_phrases(['aging', 'in','days']);?></th>
                <th width="10%" class="text-right"><?php echo get_phrases(['grand','total']);?></th>
                <th width="10%" class="text-right"><?php echo get_phrases(['total','paid']);?></th>
                <th width="10%" class="text-right"><?php echo get_phrases(['current','due']);?></th>
            </tr>
        </thead>
        <tbody>
           <?php 
           $grand_total = 0;
           $total_paid = 0;
           $total_due = 0;
           if(!empty($results)){
                foreach ($results as $value) { 
                  $grand_total += $value->grand_total;
                  $total_paid += $value->receipt+$value->paid_amount;
                  $total_due += $value->grand_total-($value->receipt+$value->paid_amount);

                  $due = $value->grand_total-($value->receipt+$value->paid_amount);
                  if($due <0 ){
                    $due = 0;
                  }
                  
                  ?>
                   <tr onclick="onclick_change_bg('.detailsTable', this, 'cyan')">
                       <td><?php echo esc($value->supplier_name);?></td>
                       <td><a href="#" onclick="preview(this)" receive-id="<?php echo esc($value->id);?>" data-id="<?php echo esc($value->purchase_id);?>" purchase-id="<?php echo esc($value->purchase_id);?>"><?php echo esc($value->voucher_no);?></a></td>
                       <td><?php echo esc($value->date);?></td>
                       <td><?php echo esc($value->aging);?></td>
                       <td class="text-right"><?php echo esc(number_format($value->grand_total, 2));?></td>
                       <td class="text-right"><?php echo esc(number_format($value->receipt+$value->paid_amount, 2));?></td>
                       <td class="text-right"><?php echo esc(number_format($due, 2));?></td>
                   </tr>
            <?php } 
          } else { ?>
                <tr>
                    <th colspan="7" class="text-center text-muted text-danger"><?php echo get_notify('data_is_not_available');?></th>
                </tr>
          <?php } ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="4" class="text-right"><?php echo get_phrases(['total']);?></th>
                <th class="text-right"><?php echo number_format($grand_total, 2);?></th>
                <th class="text-right"><?php echo number_format($total_paid, 2);?></th>
                <th class="text-right"><?php echo number_format($total_due, 2);?></th>
            </tr>
        </tfoot>
    </table>
</div>
<?php if($hasPrintAccess){ ?>
<div class="card-footer no-print">
    <button type="button" class="btn btn-info" onclick="printContent('printC')"><span class="fa fa-print"></span> <?php echo get_phrases(['print']);?></button>
</div><?php } ?>