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
<h4 class="mb-0"><center><?php echo get_notify('income_with_vat_reports');?></center></h4>
<h5><center id="title"></center></h5>
<div class="table-responsive">
    <table class="table table-stripped table-sm">
        <thead>
            </tr>
                <th width="6%"><?php echo get_phrases(['voucher', 'no']);?></th>
                <th width="8%"><?php echo get_phrases(['voucher', 'no']);?></th>
                <th width="12%"><?php echo get_phrases(['patient', 'name']);?></th>
                <th width="8%"><?php echo get_phrases(['nationality']);?></th>
                <th width="9%"><?php echo get_phrases(['id', 'no']);?></th>
                <th width="12%"><?php echo get_phrases(['doctor', 'name']);?></th>
                <th class="text-right" width="8%"><?php echo get_phrases(['amount']);?></th>
                <th class="text-right" width="7%"><?php echo get_phrases(['total', 'net']);?></th>
                <th class="text-right" width="7%"><?php echo get_phrases(['vat']);?></th>
                <th class="text-right" width="8%"><?php echo get_phrases(['paid']);?></th>
                <th width="8%"><?php echo get_phrases(['created', 'by']);?></th>
                <th width="8%"><?php echo get_phrases(['created', 'date']);?></th>
            </tr>
        </thead>
        <tbody> 
           <?php 
            $total = 0;
            $totalVat = 0;
            $totalPaid = 0;
            $totalNet = 0;
            if(!empty($results)){
                foreach ($results as $value) { 
                    $net = $value->total - $value->total_discount;
                    $total += $value->total;
                    $totalNet +=  $net;
                    $totalVat += $value->vat;
                    $totalPaid += $value->receipt;
                  ?>
                   <tr>
                       <td data-id="<?php echo esc($value->id);?>" data-type="<?php echo $value->vtype;?>" class="clickable-row viewVoucher text-success un-none"><?php echo esc($value->id);?></td>
                       <td><?php echo esc($value->type);?></td>
                       <td><?php echo esc($value->patient_name);?></td>
                       <td><?php echo esc($value->nationality);?></td>
                       <td><?php echo esc($value->nid_no);?></td>
                       <td><?php echo esc($value->doctor_name);?></td>
                       <td data-id="<?php echo esc($value->id);?>" data-type="<?php echo $value->vtype;?>" class="text-right clickable-row viewVoucher text-success un-none"><?php echo esc(number_format($value->total, 2));?></td>
                       <td data-id="<?php echo esc($value->id);?>" data-type="<?php echo $value->vtype;?>" class="text-right clickable-row viewVoucher text-success un-none"><?php echo esc(number_format($net, 2));?></td>
                       <td data-id="<?php echo esc($value->id);?>" data-type="<?php echo $value->vtype;?>" class="text-right clickable-row viewVoucher text-success un-none"><?php echo esc(number_format($value->vat, 2));?></td>
                       <td data-id="<?php echo esc($value->id);?>" data-type="<?php echo $value->vtype;?>" class="text-right clickable-row viewVoucher text-success un-none"><?php echo esc(number_format($value->receipt, 2));?></td>
                       <td><?php echo esc($value->createdBy);?></td>
                       <td><?php echo esc(date('d/m/Y H:i:s', strtotime($value->created_date)));?></td>
                   </tr>
            <?php } }else {?>
                <tr>
                    <th colspan="12" class="text-center text-muted text-danger"><?php echo get_notify('data_is_not_available');?></th>
                </tr>
            <?php }?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="6" class="text-right"><?php echo get_phrases(['total']);?></th>
                <th class="text-right"><?php echo number_format($total, 2);?></th>
                <th class="text-right"><?php echo number_format($totalNet, 2);?></th>
                <th class="text-right"><?php echo number_format($totalVat, 2);?></th>
                <th class="text-right"><?php echo number_format($totalPaid, 2);?></th>
                <th colspan="2"></th>
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