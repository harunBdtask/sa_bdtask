<div class="row">
    <div class="col-4">
        <img src="<?php echo base_url().$setting->logo;?>" class="img-responsive" alt=""></br>
        <?php echo get_phrases(['phone']) ?>: <?php echo $setting->phone; ?></br>
        <?php echo get_phrases(['email']) ?>: <?php echo $setting->email; ?>
        <br>
    </div>
    <div class="col-4 text-right mt-4">
        <h5><center><?php echo get_phrases(['appointment', 'summary', 'report']);?></center></h5>
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
                <th width="12%"><?php echo get_phrases(['doctor', 'name']);?></th>
                <th width="10%"><?php echo get_phrases(['appointment', 'opened']);?></th>
                <th width="10%"><?php echo get_phrases(['appointment', 'booked']);?></th>
                <th width="8%"><?php echo get_phrases(['booking', 'radio']);?></th>
                <th width="10%"><?php echo get_phrases(['appointment', 'booked', 'no']);?></th>
                <th width="6%"><?php echo get_phrases(['first', 'visit']);?></th>
                <th width="6%"><?php echo get_phrases(['follow', 'up']);?></th>
                <th width="5%"><?php echo get_phrases(['return']);?></th>
                <th width="8%"><?php echo get_phrases(['clinic', 'procedure']);?></th>
                <th width="8%"><?php echo get_phrases(['room', 'procedure']);?></th>
                <th width="6%"><?php echo get_phrases(['no', 'show']);?></th>
                <th width="6%"><?php echo get_phrases(['no', 'show']);?></th>
                <th width="10%"><?php echo get_phrases(['appointment', 'waiting']);?></th>
            </tr>
        </thead>
        <tbody>
            <?php
            $doctor = implode('-', $doctor_id);

            if(!empty($reports)){
                foreach ($reports as $value) { 
                    if($value['radio'] >= 95){
                        $bg = 'bg-purple';
                    }else if($value['radio'] >= 85 && $value['radio'] <= 94.99){
                        $bg = 'bg-green';
                    }else if($value['radio'] >= 70 && $value['radio'] <= 84.99){
                        $bg = 'bg-yellow'; //orange
                    }else if($value['radio'] >= 50 && $value['radio'] <= 69.99){
                        $bg = 'bg-yellow1';
                    }else{
                        $bg = 'bg-red';
                    }
                ?>
                    <tr class="<?php echo $bg;?>">
                        <td><?php echo esc($value['doctor_name']);?></td>
                        <td><?php echo $value['opened'];?></td>
                        <td><?php echo $value['real_duration'];?></td>
                        <td><?php echo number_format($value['radio'], 2);?> %</td>
                        <td><?php echo esc($value['total_appointment']);?></td>
                        <td><?php echo esc($value['first_visit']);?></td>
                        <td><?php echo esc($value['follow']);?></td>
                        <td><?php echo esc($value['return1']);?></td>
                        <td><?php echo esc($value['pr_clinic']);?></td>
                        <td><?php echo esc($value['pr_room']);?></td>
                        <td><?php echo esc($value['no_show']);?></td>
                        <td><?php echo number_format($value['no_show_per'], 2);?> %</td>
                        <td><?php echo esc($value['waiting']);?></td>
                    </tr>
            <?php } }else{ ?>
                <tr>
                    <th colspan="13" class="text-center text-danger"><?php echo get_notify('data_is_not_available');?></th>
                </tr>
            <?php }?>
        </tbody>
    </table>
</div>
<div class="no-print">
  <?php if($print_access){ ?>
    <button type="button" class="btn btn-info" onclick="printContent('resultData')"><span class="fa fa-print"></span> <?php echo get_phrases(['print']);?></button>
  <?php } if($export_access){ ?>
    <a href="<?php echo base_url('reports/appointment/exportSummary?doctor_id='.$doctor.'&from='.$from.'&to='.$to);?>" class="btn btn-purple"><span class="fa fa-download"></span> <?php echo get_phrases(['export','excel']);?></a>
  <?php }?>
</div>