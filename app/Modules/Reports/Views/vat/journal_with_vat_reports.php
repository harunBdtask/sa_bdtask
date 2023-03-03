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
            (<?php echo $setting->nameA; ?>)<br>
            <?php echo $setting->address; ?>
        </address>
    </div>
</div> <hr>
<h4 class="mb-0"><center><?php echo get_phrases(['journal', 'with', 'vat']);?></center></h4>
<h5><center id="title"></center></h5>
<div class="table-responsive">
    <table class="table table-stripped table-sm table-hover">
        <thead> 
            </tr>
                <th width="10%"><?php echo get_phrases(['voucher', 'no']);?></th>
                <th width="10%"><?php echo get_phrases(['voucher', 'date']);?></th>
                <th class="text-right" width="15%"><?php echo get_phrases(['total', 'debit']);?></th>
                <th class="text-right" width="15%"><?php echo get_phrases(['total', 'credit']);?></th>
                <th class="text-right" width="15%"><?php echo get_phrases(['vat']);?></th>
                <th width="20%"><?php echo get_phrases(['created', 'by']);?></th>
                <th width="15%"><?php echo get_phrases(['created', 'date']);?></th>
            </tr>
        </thead>
        <tbody id="jvVatReport"> 
           <?php 
           $total = 0;
           $totalVat = 0;
           
           if(!empty($results)){
                foreach ($results as $value) { 
                  $total += $value->totalDebit;
                  $totalVat += $value->vat;
                  $voucher_no = $value->vtype.'-'.$value->id;
                  ?>
                   <tr>
                       <td data-id="<?php echo esc($value->id);?>" class="clickable-row text-success un-none"><?php echo esc($voucher_no);?></td>
                       <td><?php echo date('d/m/Y', strtotime(esc($value->voucher_date)));?></td>
                       <td data-id="<?php echo esc($value->id);?>" class="text-right clickable-row text-success un-none"><?php echo esc(number_format($value->totalDebit, 2));?></td>
                       <td data-id="<?php echo esc($value->id);?>" class="text-right clickable-row text-success un-none"><?php echo esc(number_format($value->totalCredit, 2));?></td>
                       <td data-id="<?php echo esc($value->id);?>" class="text-right clickable-row text-success un-none"><?php echo esc(number_format($value->vat, 2));?></td>
                       <td><?php echo esc($value->createdBy);?></td>
                       <td><?php echo esc(date('d/m/Y H:i:s A', strtotime($value->created_date)));?></td>
                   </tr>
            <?php } }else{?>
                <tr>
                    <th colspan="7" class="text-center text-muted text-danger"><?php echo get_notify('data_is_not_available');?></th>
                </tr>
            <?php }?>
        </tbody>
        <tr>
            <th colspan="2" class="text-right"><?php echo get_phrases(['total']);?></th>
            <th class="text-right"><?php echo number_format($total, 2);?></th>
            <th class="text-right"><?php echo number_format($total, 2);?></th>
            <th class="text-right"><?php echo number_format($totalVat, 2);?></th>
            <th colspan="2"></th>
        </tr>
    </table>
</div>

<div class="card-footer no-print">
  <?php if($hasPrintAccess){ ?>
    <button type="button" class="btn btn-info" onclick="printContent('printC')"><span class="fa fa-print"></span> <?php echo get_phrases(['print']);?></button>
  <?php } if($hasExportAccess){ ?>
    <a href="<?php echo base_url('reports/vat/exportJournalVat?type='.$type.'&status='.$status.'&from='.$from.'&to='.$to);?>" class="btn btn-purple"><span class="fa fa-download"></span> <?php echo get_phrases(['export','excel']);?></a>
  <?php }?>
</div>