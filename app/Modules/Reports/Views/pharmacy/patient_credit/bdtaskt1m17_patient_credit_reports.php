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
    <table class="table table-stripped table-sm">
        <thead>
            <tr>
                <th width="20%"><?php echo get_phrases(['doctor','name']);?></th>
                <th width="20%"><?php echo get_phrases(['patient', 'name']);?></th>
                <th width="15%"><?php echo get_phrases(['voucher','no']);?></th>
                <th width="15%"><?php echo get_phrases(['sale','date']);?></th>  
                <th width="15%" class="text-right"><?php echo get_phrases(['credit','amount']);?></th>  
            </tr>
        </thead>
        <tbody>
           <?php 
           $total = 0;
           if(!empty($results)){
                foreach ($results as $value) { 
                  if($value->credit_amount >0 ){
                    $total += $value->credit_amount;
                    
                    ?>
                     <tr>
                         <td><?php echo esc($value->doctor_name);?></td>
                         <td><?php echo esc($value->patient_name);?></td>
                         <td><?php echo esc($value->voucher_no);?></td>
                         <td><?php echo esc($value->date);?></td>
                         <td class="text-right"><?php echo esc(number_format($value->credit_amount, 2));?></td>
                     </tr>
            <?php }
                } 
          } else { ?>
                <tr>
                    <th colspan="5" class="text-center text-muted text-danger"><?php echo get_notify('data_is_not_available');?></th>
                </tr>
          <?php } ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="4" class="text-right"><?php echo get_phrases(['total']);?></th>
                <th class="text-right"><?php echo number_format($total, 2);?></th>
            </tr>
        </tfoot>
    </table>
</div>
<?php if($hasPrintAccess){ ?>
<div class="card-footer no-print">
    <button type="button" class="btn btn-info" onclick="printContent('printC')"><span class="fa fa-print"></span> <?php echo get_phrases(['print']);?></button>
</div><?php } ?>