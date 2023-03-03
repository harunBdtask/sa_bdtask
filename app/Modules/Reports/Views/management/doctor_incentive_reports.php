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
    <table class="table table-bordered table-sm table-hover">
        <thead>
            <tr>
                <th><?php echo get_phrases(['doctor', 'code']);?></th>
                <th><?php echo get_phrases(['service', 'code']);?></th>
                <th><?php echo get_phrases(['service', 'name']);?></th>
                <th><?php echo get_phrases(['qty']);?></th>
                <th class="text-right"><?php echo get_phrases(['total', 'service', 'amount']);?></th>
                <th class="text-right"><?php echo get_phrases(['allowed', 'discount']);?></th>
                <th class="text-right"><?php echo get_phrases(['over', 'limit', 'discount']);?></th>
                <th class="text-right"><?php echo get_phrases(['offer', 'discount']);?></th>
                <th class="text-right"><?php echo get_phrases(['refund']);?></th>
                <th class="text-right"><?php echo get_phrases(['net', 'revenue']);?></th>
                <th class="text-right"><?php echo get_phrases(['incentive']);?>%</th>
                <th class="text-right"><?php echo get_phrases(['total', 'incentive']);?></th>
            </tr>
        </thead>
        <tbody>
           <?php 
           $totalReve = 0;
           $totalOffer = 0;
           $totalRefund = 0;
           $totalOvrLimit = 0;
           $totalAllowDis = 0;
           $totalIncentive = 0;
           $totalNetRevenue = 0;
           if(!empty($results)){
                foreach ($results as $value) { 
                  $total = $value->qty_price;
                  $normalDis = $value->over_limit_discount + $value->doctor_discount + $value->offer_discount;
                  $totalRef = !empty($value->totalPay)?$value->totalPay:0;
                  // Cost calculation

                  $netRevenue = $total - ($totalRef + $normalDis);
                  $totalReve += $total;
                  $totalRefund += $totalRef;
                  $totalOvrLimit += $value->over_limit_discount;
                  $totalOffer += $value->offer_discount;
                  $totalAllowDis += $value->doctor_discount;
                  $totalNetRevenue += $netRevenue;
                  $incentive = ($netRevenue*$value->incentive_percent)/100;
                  $totalIncentive += $incentive;
                  $invoicesIds = str_replace(',', '-', $value->invoiceIds);
                  
                  $refIds = str_replace(',', '-', !empty($value->ref_voucher)?$value->ref_voucher:0);
                  ?>
                   <tr class="clickable-row">
                       <td><?php echo esc($value->short_name);?></td>
                       <td><?php echo esc($value->code_no);?></td>
                       <td><?php echo esc($value->service_name);?></td>
                       <td><?php echo $value->qty;?></td>
                       <td class="text-right"><?php echo esc(number_format($total, 2));?></td>
                       <td class="text-right"><?php echo esc(number_format($value->doctor_discount, 2));?></td>
                       <td data-doctor="<?php echo esc($value->doctor_id);?>" data-type="overLimit" class="text-right clickable-row allInvoices text-success un-none"><?php echo esc(number_format($value->over_limit_discount, 2));?></td>
                       <td class="text-right"><?php echo esc(number_format($value->offer_discount, 2));?></td>
                       <td data-doctor="<?php echo esc($refIds);?>" data-type="refund" class="text-right clickable-row allInvoices text-success un-none"><?php echo esc(number_format($totalRef, 2));?></td>
                       <?php if($value->sids > 1){ ?>
                       <td data-ids="<?php echo esc($invoicesIds);?>" data-type="SINV" class="text-right clickable-row invoicesIds text-success un-none"><?php echo esc(number_format($netRevenue, 2));?></td>
                      <?php }else{ ?>
                        <td data-id="<?php echo esc($value->invoice_id);?>" data-type="SINV" class="text-right clickable-row viewDetails text-success un-none"><?php echo esc(number_format($netRevenue, 2));?></td>
                      <?php }?>
                       
                       <td class="text-right"><?php echo $value->incentive_percent;?></td>
                       <td class="text-right"><?php echo esc(number_format($incentive, 2));?></td>
                   </tr>
            <?php } }else{?>
                <tr>
                    <th colspan="18" class="text-center text-muted text-danger"><?php echo get_notify('data_is_not_available');?></th>
                </tr>
            <?php }?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="4" class="text-right"><?php echo get_phrases(['total']);?></th>
                <th class="text-right"><?php echo number_format($totalReve, 2);?></th>
                <th class="text-right"><?php echo number_format($totalAllowDis, 2);?></th>
                <th class="text-right"><?php echo number_format($totalOvrLimit, 2);?></th>
                <th class="text-right"><?php echo number_format($totalOffer, 2);?></th>
                <th class="text-right"><?php echo number_format($totalRefund, 2);?></th>
                <th class="text-right"><?php echo number_format($totalNetRevenue, 2);?></th>
                <th class="text-right"></th>
                <th class="text-right"><?php echo number_format($totalIncentive, 2);?></th>
            </tr>
        </tfoot>
    </table>
</div>

<div class="card-footer no-print">
  <?php if($hasPrintAccess){ ?>
    <button type="button" class="btn btn-info allPrint" onclick="printContent('printC')"><span class="fa fa-print"></span> <?php echo get_phrases(['print']);?></button>
  <?php } if($hasExportAccess){ ?>
    <button type="button" class="btn btn-purple export"><span class="fa fa-download"></span> <?php echo get_phrases(['export','excel']);?></button>
  <?php }?>
</div>