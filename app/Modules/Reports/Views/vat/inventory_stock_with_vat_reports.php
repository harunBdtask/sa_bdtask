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
<h4 class="mb-0"><center><?php echo get_notify('purchase_with_vat_reports');?></center></h4>
<h5><center id="title"></center></h5>
<div class="table-responsive">
    <table class="table table-stripped table-sm">
        <thead> 
            </tr>
                <th width="8%"><?php echo get_phrases(['voucher', 'no']);?></th>
                <th width="10%"><?php echo get_phrases(['supplier', 'name']);?></th>
                <th width="8%"><?php echo get_phrases(['vat', 'no']);?></th>
                <th width="8%"><?php echo get_phrases(['cr', 'no']);?></th>
                <th width="10%"><?php echo get_phrases(['to', 'store']);?></th>
                <th class="text-right" width="10%"><?php echo get_phrases(['price', 'without', 'vat']);?></th>
                <th class="text-right" width="8%"><?php echo get_phrases(['vat']);?></th>
                <th class="text-right" width="10%"><?php echo get_phrases(['price', 'with', 'vat']);?></th>
                <th class="text-right" width="10%"><?php echo get_phrases(['paid', 'amount', 'with', 'vat']);?></th>
                <th width="10%"><?php echo get_phrases(['created', 'by']);?></th>
                <th width="8%"><?php echo get_phrases(['created', 'date']);?></th>
            </tr>
        </thead>
        <tbody> 
           <?php 
           $total = 0;
           $totalVat = 0;
           $totalGrand = 0;
           $totalPaid = 0;
           if(!empty($results)){
                foreach ($results as $value) { 
                  $total += $value->sub_total;
                  $totalVat += $value->vat;
                  $totalGrand += $value->grand_total;
                  $totalPaid += $value->receipt;
                  if($value->type=='RV'){
                    $class = 'viewVoucher';
                  }else{
                    $class = 'viewReturn';
                  }
                  ?>
                   <tr>
                       <td data-id="<?php echo esc($value->purchase_id);?>" class="clickable-row <?php echo $class;?> text-success un-none"><?php echo esc($value->voucher_no);?></td>
                       <td><?php echo esc($value->supplier_name);?></td>
                       <td><?php echo esc($value->vat_no);?></td>
                       <td><?php echo esc($value->cr_no);?></td>
                       <td><?php echo esc($value->warehouse);?></td>
                       <td data-id="<?php echo esc($value->purchase_id);?>" class="text-right clickable-row <?php echo $class;?> text-success un-none"><?php echo esc(number_format($value->sub_total, 2));?></td>
                       <td data-id="<?php echo esc($value->purchase_id);?>" class="text-right clickable-row <?php echo $class;?> text-success un-none"><?php echo esc(number_format($value->vat, 2));?></td>
                       <td data-id="<?php echo esc($value->purchase_id);?>" class="text-right clickable-row <?php echo $class;?> text-success un-none"><?php echo esc(number_format($value->grand_total, 2));?></td>
                       <td data-id="<?php echo esc($value->purchase_id);?>" class="text-right clickable-row <?php echo $class;?> text-success un-none"><?php echo esc(number_format($value->receipt, 2));?></td>
                       <td><?php echo esc($value->createdBy);?></td>
                       <td><?php echo esc(date('d/m/Y H:i:s', strtotime($value->created_at)));?></td>
                   </tr>
            <?php } }else{?>
                <tr>
                    <th colspan="11" class="text-center text-muted text-danger"><?php echo get_notify('data_is_not_available');?></th>
                </tr>
            <?php }?>
        </tbody>
        <tr>
            <th colspan="5" class="text-right"><?php echo get_phrases(['total']);?></th>
            <th class="text-right"><?php echo number_format($total, 2);?></th>
            <th class="text-right"><?php echo number_format($totalVat, 2);?></th>
            <th class="text-right"><?php echo number_format($totalGrand, 2);?></th>
            <th class="text-right"><?php echo number_format($totalPaid, 2);?></th>
            <th colspan="2"></th>
        </tr>
    </table>
</div>

<div class="card-footer no-print">
  <?php if($hasPrintAccess){ ?>
    <button type="button" class="btn btn-info" onclick="printContent('printC')"><span class="fa fa-print"></span> <?php echo get_phrases(['print']);?></button>
  <?php } if($hasExportAccess){ ?>
    <button type="button" class="btn btn-purple export"><span class="fa fa-download"></span> <?php echo get_phrases(['export','excel']);?></button>
  <?php }?>
</div>