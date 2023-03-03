<div class="row">
    <div class="col-6">
        <img src="<?php echo base_url().$setting->logo;?>" class="img-responsive" alt=""></br>
        <?php echo get_phrases(['phone']) ?>: <?php echo $setting->phone; ?></br>
        <?php echo get_phrases(['email']) ?>: <?php echo $setting->email; ?>
        <br>
    </div>
    <div class="col-6 text-right mt-3">
         <address>
            <strong><?php echo $setting->title; ?></strong><br>
            (<?php echo $setting->nameA; ?>)<br>
            <?php echo $setting->address; ?>
        </address>
    </div>
</div> <hr>
<h4><center id="title"></center></h4>
<div class="table-responsive">
    <table class="table table-stripped table-sm">
        <thead>
            <tr>
                <th width="15%"><?php echo get_phrases(['user', 'name']);?></th>
                <th width="10%"><?php echo get_phrases(['payment', 'method']);?></th>
                <th width="7%"><?php echo get_phrases(['date']);?></th>
                <th width="10%"><?php echo get_phrases(['voucher', 'type']);?></th>
                <th width="8%"><?php echo get_phrases(['voucher', 'number']);?></th>
                <th width="11%"><?php echo get_phrases(['patient', 'name']);?></th>
                <th width="15%"><?php echo get_phrases(['doctor', 'name']);?></th>
                <th class="text-right" width="7%"><?php echo get_phrases(['debit']);?></th>
                <th class="text-right" width="7%"><?php echo get_phrases(['credit']);?></th>
                <th class="text-right" width="10%"><?php echo get_phrases(['amount']);?></th>
            </tr>
        </thead>
        <tbody>
           <?php 
           $Debit = 0;
           $Credit = 0;
           $amount = 0;
           if(!empty($results[0])){
                foreach ($results[0] as $value) { 
                  $Debit += $value->Debit;
                  $Credit += $value->Credit;
                  $amount += $value->Debit - $value->Credit;
                  ?>
                   <tr>
                       <td><?php echo esc($value->created_by);?></td>
                       <td><?php echo esc($value->payment_method);?></td>
                       <td><?php echo esc($value->VDate);?></td>
                       <td><?php echo esc($value->vtype_name);?></td>
                       <td><a href="javascript:void(0);" data-id="<?php echo esc($value->VNo);?>" class="text-success un-none viewVoucher"><?php echo esc($value->VNo);?></a></td>
                       <td><?php echo esc($value->patient_name);?></td>
                       <td><?php echo esc($value->doctor_name);?></td>
                       <td class="text-right"><a href="javascript:void(0);" data-id="<?php echo esc($value->VNo);?>" class="text-success un-none viewVoucher"><?php echo esc(number_format($value->Debit, 2));?></a></td>
                       <td class="text-right"><a href="javascript:void(0);" data-id="<?php echo esc($value->VNo);?>" class="text-success un-none viewVoucher"><?php echo esc(number_format($value->Credit, 2));?></a></td>
                       <td class="text-right"><a href="javascript:void(0);" data-id="<?php echo esc($value->VNo);?>" class="text-success un-none viewVoucher"><?php echo esc(number_format($amount, 2));?></a></td>
                   </tr>
            <?php } }else{?>
                <tr>
                    <th colspan="10" class="text-center text-muted text-danger"><?php echo get_notify('data_is_not_available');?></th>
                </tr>
            <?php }?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="7" class="text-right"><?php echo get_phrases(['total']);?></th>
                <th class="text-right"><?php echo number_format($Debit, 2);?></th>
                <th class="text-right"><?php echo number_format($Credit, 2);?></th>
                <th class="text-right"><?php echo number_format($amount, 2);?></th>
            </tr>
        </tfoot>
    </table>
</div>
<div class="row">
    <div class="col-md-4"></div>
    <div class="col-md-4"></div>
    <div class="col-md-4">
        <div class="table-responsive">
            <table class="table table-stripped table-sm">
                <thead>
                    <tr>
                        <th><?php echo get_phrases(['payment', 'method']);?></th>
                        <th class="text-right"><?php echo get_phrases(['debit']);?></th>
                        <th class="text-right"><?php echo get_phrases(['credit']);?></th>
                        <th class="text-right"><?php echo get_phrases(['amount']);?></th>
                    </tr>
                </thead>
                <tbody>
                   <?php 
                   $totalAmount = 0;
                   if(!empty($results[1])){
                        foreach ($results[1] as $value) { 
                          $Total = $value->Debit - $value->Credit;
                          $totalAmount += $Total;
                          ?>
                           <tr>
                               <td><?php echo esc($value->payment_method);?></td>
                               <td class="text-right"><?php echo esc(number_format($value->Debit, 2));?></td>
                               <td class="text-right"><?php echo esc(number_format($value->Credit, 2));?></td>
                               <td class="text-right"><?php echo esc(number_format($Total, 2));?></td>
                           </tr>
                    <?php } }?>
                        <tr>
                          <th class="text-right" colspan="3"><?php echo get_phrases(['balance']);?></th>
                          <th class="text-right"><?php echo esc(number_format($totalAmount, 2));?></th>
                        </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card-footer no-print">
  <?php if($hasPrintAccess){ ?>
    <button type="button" class="btn btn-info" onclick="printContent('printC')"><span class="fa fa-print"></span> <?php echo get_phrases(['print']);?></button>
  <?php } if($hasExportAccess){ ?>
    <button type="button" class="btn btn-purple export"><span class="fa fa-download"></span> <?php echo get_phrases(['export','excel']);?></button>
  <?php }?>
</div>