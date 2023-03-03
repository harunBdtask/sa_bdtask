<div class="row">
    <div class="col-md-6">
        <img src="<?php echo base_url().$setting->logo;?>" class="img-responsive" alt=""></br>
        <?php echo get_phrases(['phone']) ?>: <?php echo $setting->phone; ?></br>
        <?php echo get_phrases(['email']) ?>: <?php echo $setting->email; ?>
        <br>
    </div>
    <div class="col-md-6 text-right mt-3">
         <address>
            <strong><?php echo $setting->title; ?></strong><br>
            (<?php echo $setting->nameA; ?>)<br>
            <?php echo $setting->address; ?>
        </address>
    </div>
</div> <hr>

<div class="row">
    <div class="col-md-12">
        <center>
          <b class="fs-20"><?php echo get_phrases(['journal', 'report']);?></b><br>
          <?php echo $userInfo->short_name.' '.$userInfo->nameE;?>
          <div id="title"></div>
        </center>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-stripped table-sm">
        <thead>
            <tr>
                <th width="10%"><?php echo get_phrases(['voucher', 'no']);?></th>
                <th width="10%"><?php echo get_phrases(['voucher', 'type']);?></th>
                <th width="10%"><?php echo get_phrases(['date']);?></th>
                <th width="40%"><?php echo get_phrases(['description']);?></th>
                <th class="text-right" width="10%"><?php echo get_phrases(['debit']);?></th>
                <th class="text-right" width="10%"><?php echo get_phrases(['credit']);?></th>
                <th class="text-right" width="10%"><?php echo get_phrases(['amount']);?></th>
            </tr>
        </thead>
        <tbody>
           <?php 
           $Debit = 0;
           $Credit = 0;
           $amount = 0;
           if(!empty($results)){
                foreach ($results as $value) { 
                  $Debit += $value->Debit;
                  $Credit += $value->Credit;
                  $amount += $value->Debit - $value->Credit;
                  ?>
                   <tr>
                       <td><?php echo esc($value->VNo);?></td>
                       <td><?php echo esc($value->type);?></td>
                       <td><?php echo esc($value->VDate);?></td>
                       <td><?php echo esc($value->Narration);?></td>
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
                <th colspan="4" class="text-right"><?php echo get_phrases(['total']);?></th>
                <th class="text-right"><?php echo number_format($Debit, 2);?></th>
                <th class="text-right"><?php echo number_format($Credit, 2);?></th>
                <th class="text-right"><?php echo number_format($amount, 2);?></th>
            </tr>
        </tfoot>
    </table>
</div>

<div class="card-footer no-print">
    <button type="button" class="btn btn-purple" onclick="printContent('printC')"><span class="fa fa-print"></span> <?php echo get_phrases(['print']);?></button>
     <button type="button" class="btn btn-info export"><span class="fa fa-download"></span> <?php echo get_phrases(['export','excel']);?></button>
</div>