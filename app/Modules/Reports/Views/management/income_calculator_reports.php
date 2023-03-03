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
<h4><center id="title"></center></h4>
<div class="table-responsive">
    <table class="table table-stripped table-sm">
        <thead>
            <tr>
                <th width="18%"><?php echo get_phrases(['doctor', 'name']);?></th>
                <th class="text-right" width="10%"><?php echo get_phrases(['credit', 'sales']);?></th>
                <th class="text-right" width="10%"><?php echo get_phrases(['paid', 'advance']);?></th>
                <th class="text-right" width="10%"><?php echo get_phrases(['bank', 'transfer']);?></th>
                <th class="text-right" width="10%"><?php echo get_phrases(['services', 'without', 'commission']);?></th>
                <th class="text-right" width="10%"><?php echo get_phrases(['over', 'limit', 'discount']);?></th>
                <th class="text-right" width="10%"><?php echo get_phrases(['normal', 'discount']);?></th>
                <th class="text-right" width="10%"><?php echo get_phrases(['refund']);?></th>
                <th class="text-right" width="12%"><?php echo get_phrases(['net', 'revenue']);?></th>
            </tr>
        </thead>
        <tbody>
           <?php
           $totalReve = 0;
           $totalNoCom = 0;
           $totalRefund = 0;
           $totalCredit = 0;
           $totalBtrans = 0;
           $totalPaidAdv = 0;
           $totalOvrLimit = 0;
           $totalNormalDis = 0;
           $totalNetRevenue = 0;
           if(!empty($results)){
                foreach ($results as $value) { 
                  $total = $value->price;
                  $totalRef = !empty($value->totalPay)?$value->totalPay:0;
                  $normalDis = $value->offer + $value->doctor_discount;
                  $overLimit = $value->over_limit;
                  $netRevenue = $total - ($normalDis + $overLimit + $totalRef);
                  $totalCredit += $value->creditSale;
                  $totalBtrans += $value->bankTransfer;
                  $totalPaidAdv += $value->paidAdvance;
                  $totalNoCom += $value->no_commission;
                  $totalReve += $total;
                  $totalRefund += $totalRef; 
                  $totalOvrLimit += $overLimit;
                  $totalNormalDis += $normalDis;
                  $totalNetRevenue += $netRevenue;
                  $ref_voucher = str_replace(',', '-', !empty($value->ref_voucher)?$value->ref_voucher:0);
                  $advIds = implode('-',array_unique(explode(',', $value->advIds)));
                  $creditIds = implode('-',array_unique(explode(',', $value->creditIds)));
                  ?>
                   <tr>
                       <td><?php echo esc($value->doctor);?></td>
                       <td data-doctor="<?php echo esc($creditIds);?>" data-type="paidAdv" class="text-right clickable-row allInvoices text-success un-none"><?php echo esc(number_format($value->creditSale, 2));?></td>
                       <td data-doctor="<?php echo esc($advIds);?>" data-type="paidAdv" class="text-right clickable-row allInvoices text-success un-none"><?php echo esc(number_format($value->paidAdvance, 2));?></td>
                       <td class="text-right"><?php echo esc(number_format($value->bankTransfer, 2));?></td>
                       <td data-doctor="<?php echo esc($value->doctor_id);?>" data-type="noCom" class="text-right clickable-row allInvoices text-success un-none"><?php echo esc(number_format($value->no_commission, 2));?></td>
                       <td data-doctor="<?php echo esc($value->doctor_id);?>" data-type="overLimit" class="text-right clickable-row allInvoices text-success un-none"><?php echo esc(number_format($overLimit, 2));?></td>
                       <td data-doctor="<?php echo esc($value->doctor_id);?>" data-type="discount" class="text-right clickable-row allInvoices text-success un-none"><?php echo esc(number_format($normalDis, 2));?></td>
                       <td data-doctor="<?php echo esc($ref_voucher);?>" data-type="refund" class="text-right clickable-row allInvoices text-success un-none"><?php echo esc(number_format($totalRef, 2));?></td>
                       <td data-doctor="<?php echo esc($value->doctor_id);?>" data-type="revenue" class="text-right clickable-row allInvoices text-success un-none"><?php echo esc(number_format($netRevenue, 2));?></td>
                   </tr>
            <?php } }else{?>
                <tr>
                    <th colspan="7" class="text-center text-muted text-danger"><?php echo get_notify('data_is_not_available');?></th>
                </tr>
            <?php }?>
        </tbody>
        <tfoot>
            <tr>
                <th class="text-right"><?php echo get_phrases(['total']);?></th>
                <th class="text-right"><?php echo number_format($totalCredit, 2);?></th>
                <th class="text-right"><?php echo number_format($totalPaidAdv, 2);?></th>
                <th class="text-right"><?php echo number_format($totalBtrans, 2);?></th>
                <th class="text-right"><?php echo number_format($totalNoCom, 2);?></th>
                <th class="text-right"><?php echo number_format($totalOvrLimit, 2);?></th>
                <th class="text-right"><?php echo number_format($totalNormalDis, 2);?></th>
                <th class="text-right"><?php echo number_format($totalRefund, 2);?></th>
                <th class="text-right"><?php echo number_format($totalNetRevenue, 2);?></th>
            </tr>
        </tfoot>
    </table>
</div>

<div class="card-footer no-print">
  <?php if($hasPrintAccess){ ?>
    <button type="button" class="btn btn-info" onclick="printContent('printC')"><span class="fa fa-print"></span> <?php echo get_phrases(['print']);?></button>
  <?php } if($hasExportAccess){ ?>
    <button type="button" class="btn btn-purple export"><span class="fa fa-download"></span> <?php echo get_phrases(['export','excel']);?></button>
  <?php }?>
</div>