<div class="row">
    <div class="col-4">
        <img src="<?php echo base_url().$setting->logo;?>" class="img-responsive" alt=""></br>
        <?php echo get_phrases(['phone']) ?>: <?php echo $setting->phone; ?></br>
        <?php echo get_phrases(['email']) ?>: <?php echo $setting->email; ?>
        <br>
    </div>
    <div class="col-4 text-right mt-4">
        <h5><center><?php echo get_phrases(['redeem', 'points', 'report']);?></center></h5>
        <h6><center><b><?php echo $range;?></b></center></h6>
    </div>
    <div class="col-4 text-right mt-3">
         <address>
            <strong><?php echo $setting->title; ?></strong><br>
            (<?php echo $setting->nameA; ?>)<br>
            <?php echo $setting->address; ?>
        </address>
    </div>
</div> <hr>
<div class="table-responsive">
    <table class="table table-bordered table-sm table-hover">
        <thead>
            <tr>
                <th width="17%"><?php echo get_phrases(['patient', 'name']);?></th>
                <th width="6%"><?php echo get_phrases(['file', 'no']);?></th>
                <th width="10%"><?php echo get_phrases(['invoice', 'no']);?></th>
                <th width="20%"><?php echo get_phrases(['service', 'name']);?></th>
                <th width="8%"><?php echo get_phrases(['code', 'no']);?></th>
                <th width="7%"><?php echo get_phrases(['invoice', 'date']);?></th>
                <th width="8%"><?php echo get_phrases(['redeem', 'points']);?></th>
                <th width="10%"><?php echo get_phrases(['value', 'of','amount']);?></th>
                <th width="14%"><?php echo get_phrases(['remaining', 'points']);?></th>
            </tr>
        </thead>
        <tbody>
            <?php
            if(!empty($reports)){
                foreach ($reports as $value) {
                    $amount = $value->redeem_per_point_value*$value->gain_points;
                ?>
                    <tr>
                        <td><?php echo esc($value->patient_name);?></td>
                        <td><?php echo $value->file_no;?></td>
                        <td><?php echo $value->invoice_id;?></td>
                        <td><?php echo esc($value->service_name);?></td>
                        <td><?php echo esc($value->code_no);?></td>
                        <td><?php echo date('d/m/Y', strtotime(esc($value->invoice_date)));?></td>
                        <td><?php echo esc($value->redeem_points);?></td>
                        <td><?php echo number_format($value->redeem_discount, 2);?></td>
                        <td><?php echo esc($value->remaining_points);?></td>
                    </tr>
            <?php } }else{ ?>
                <tr>
                    <th colspan="9" class="text-center text-danger"><?php echo get_notify('data_is_not_available');?></th>
                </tr>
            <?php }?>
        </tbody>
    </table>
</div>
<div class="no-print">
  <?php if($print_access){ ?>
    <button type="button" class="btn btn-info" onclick="printContent('resultData')"><span class="fa fa-print"></span> <?php echo get_phrases(['print']);?></button>
  <?php } if($export_access){ ?>
    <a href="<?php echo base_url('reports/pointing/exportRedeem?doctor_id='.$doctor_id.'&patient_id='.$patient_id.'&service_id='.$service_id.'&column_name='.$column_name.'&sorting='.$sorting.'&from='.$from.'&to='.$to);?>" class="btn btn-purple"><span class="fa fa-download"></span> <?php echo get_phrases(['export','excel']);?></a>
  <?php }?>
</div>