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
<h4><center><?php echo get_phrases(['statement', 'of']);?> <b id="title"></b></center></h4>

<table class="table table-bordered table-sm">
    <thead>
        <tr>
            <th class="text-right" width="10%"><?php echo get_phrases(['account', 'number']);?></th>
            <td class="text-left" width="35%" id="acc_number"></td>
            <th class="text-right" width="10%"><?php echo get_phrases(['account', 'name']);?></th>
            <td class="text-left" colspan="2" width="22%" id="acc_name"></td>
            <th class="text-right" width="11%"><?php echo get_phrases(['date']);?></th>
            <td class="text-left" width="13%"><?php echo date('d/m/Y');?></td>
        </tr>
        <tr>
            <th width="10%"><?php echo get_phrases(['date', 'of', 'transaction']);?></th>
            <th width="35%"><?php echo get_phrases(['description']);?></th>
            <th width="10%"><?php echo get_phrases(['voucher', 'type']);?></th>
            <th width="10%"><?php echo get_phrases(['voucher', 'number']);?></th>
            <th class="text-right" width="11%"><?php echo get_phrases(['debit']);?></th>
            <th class="text-right" width="11%"><?php echo get_phrases(['credit']);?></th>
            <th class="text-right" width="13%"><?php echo get_phrases(['balance']);?></th>
        </tr>
    </thead>
    <tbody>
       <?php 
       $CurBalance = $prebalance;
        $totalDebit = 0;
        $totalCredit = 0;
        $totalBalance = 0;
        if(!empty($results)){
          foreach ($results as $value) { 
            // $totalDebit += $value->Debit;
            // $CurBalance -= $value->Debit;

            // $totalCredit += $value->Credit;
            // $CurBalance += $value->Credit;
            // $balance = $value->Credit - $value->Debit;
            // $totalBalance += $balance;

            $totalDebit += $value->Debit;
            $CurBalance += $value->Debit;

            $totalCredit += $value->Credit;
            $CurBalance -= $value->Credit;
            $balance = $value->Debit - $value->Credit;
            $totalBalance += $balance;
            ?>
            <tr>
              <td><?php echo esc(date('d/m/Y', strtotime($value->VDate)));?></td>
              <td><?php echo esc($value->Narration);?></td>
              <td><?php echo esc($value->type);?></td>
              <td><a href="javascript:void(0);" data-id="<?php echo esc($value->VNo);?>" class="text-success un-none viewVoucher"><?php echo esc($value->VNo);?></a></td>
              <td class="text-right"><a href="javascript:void(0);" data-id="<?php echo esc($value->VNo);?>" class="text-success un-none viewVoucher"><?php echo esc(number_format($value->Debit, 2));?></a></td>
              <td class="text-right"><a href="javascript:void(0);" data-id="<?php echo esc($value->VNo);?>" class="text-success un-none viewVoucher"><?php echo esc(number_format($value->Credit, 2));?></a></td>
              <td class="text-right"><a href="javascript:void(0);" data-id="<?php echo esc($value->VNo);?>" class="text-success un-none viewVoucher"><?php echo esc(number_format($totalBalance, 2));?></a></td>
            </tr>
       <?php } }else{ ?>
        <tr>
            <th colspan="7" class="text-center text-danger"><?php echo get_notify('data_is_not_available');?></th>
        </tr>
       <?php } $totalB = $totalBalance + $prebalance; $color = $totalB >= 0 ?'text-success':'text-danger'; ?>
    </tbody>
    <tfoot>
        <tr>
            <th colspan="4" class="text-right"><?php echo get_phrases(['total']);?></th>
            <th class="text-right"><?php echo number_format($totalDebit, 2);?></th>
            <th class="text-right"><?php echo number_format($totalCredit, 2);?></th>
            <th class="text-right <?php echo $color;?>"><?php echo number_format($totalB, 2);?></th>
        </tr>
    </tfoot>
    <div class="row">
        <div class="col-md-6">
            <h5>
                <?php echo get_phrases(['opening','balance'])?> : <b><?php echo number_format($prebalance,2,'.',','); ?></b>
            </h5>
        </div>
        <div class="col-md-6">
            <h5 class="text-right">
                <?php echo get_phrases(['current','balance'])?> : <b><?php echo number_format($totalB,2,'.',','); ?></b>
            </h5>
        </div>
    </div>
</table>


<div class="card-footer no-print">
    <button type="button" class="btn btn-info" onclick="printContent('printC')"><span class="fa fa-print"></span> <?php echo get_phrases(['print']);?></button>
</div>