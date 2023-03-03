<div class="row">
    <div class="col-4">
        <img src="<?php echo base_url().$setting->logo;?>" class="img-responsive" alt=""></br>
        <?php echo get_phrases(['phone']) ?>: <?php echo $setting->phone; ?></br>
        <?php echo get_phrases(['email']) ?>: <?php echo $setting->email; ?>
        <br>
    </div>
    <div class="col-4 text-right mt-4">
        <h5><center><?php echo get_phrases(['appointment', 'details', 'no', 'show', 'report']);?></center></h5>
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
                <th width="15%"><?php echo get_phrases(['doctor', 'name']);?></th>
                <th width="8%"><?php echo get_phrases(['appointment', 'type']);?></th>
                <th width="10%"><?php echo get_phrases(['appointment', 'booked', 'no']);?></th>
                <th width="6%"><?php echo get_phrases(['no', 'show']);?> %</th>
                <th width="5%"><?php echo get_phrases(['first', 'visit']);?></th>
                <th width="6%"><?php echo get_phrases(['no', 'show']);?> %</th>
                <th width="5%"><?php echo get_phrases(['follow', 'up']);?></th>
                <th width="6%"><?php echo get_phrases(['no', 'show']);?> %</th>
                <th width="5%"><?php echo get_phrases(['return']);?></th>
                <th width="6%"><?php echo get_phrases(['no', 'show']);?> %</th>
                <th width="8%"><?php echo get_phrases(['clinic', 'procedure']);?></th>
                <th width="6%"><?php echo get_phrases(['no', 'show']);?> %</th>
                <th width="8%"><?php echo get_phrases(['room', 'procedure']);?></th>
                <th width="6%"><?php echo get_phrases(['no', 'show']);?> %</th>
            </tr>
        </thead>
        <tbody id="no_show_details">
            <?php
            $doctor = implode('-', $doctor_id);
            if(!empty($reports)){
                foreach ($reports as $value) { 
                    if($value->total_appointment > 0){
                        $no_show = ($value->no_show*100)/$value->total_appointment;
                    }else{
                        $no_show = 0;
                    }
                    if($value->first_visit > 0){
                        $no_show_first = ($value->no_show_first*100)/$value->first_visit;
                    }else{
                        $no_show_first = 0;
                    }
                    if($value->follow > 0){
                        $no_show_follow = ($value->no_show_follow*100)/$value->follow;
                    }else{
                        $no_show_follow = 0;
                    }
                    if($value->return1 > 0){
                        $no_show_return = ($value->no_show_return*100)/$value->return1;
                    }else{
                        $no_show_return = 0;
                    }
                    if($value->pr_room > 0){
                        $no_show_procedure = ($value->no_show_procedure*100)/$value->pr_room;
                    }else{
                        $no_show_procedure = 0;
                    }
                    if($value->total_appointment > 0){
                        
                    }
                    if($value->pr_clinic > 0){
                        $no_show_clinic = ($value->no_show_clinic*100)/$value->pr_clinic;
                    }else{
                        $no_show_clinic = 0;
                    }
                 
                ?>
                    <tr class="noShowTr">
                        <td><?php echo esc($value->doctor_name);?></td>
                        <td><?php echo esc($value->type);?></td>
                        <td><?php echo esc($value->total_appointment);?></td>
                        <td><?php echo number_format($no_show, 2);?>%</td>
                        <td><?php echo esc($value->first_visit);?></td>
                        <td><?php echo number_format($no_show_first, 2);?>%</td>
                        <td><?php echo esc($value->follow);?></td>
                        <td><?php echo number_format($no_show_follow, 2);?>%</td>
                        <td><?php echo esc($value->return1);?></td>
                        <td><?php echo number_format($no_show_return, 2);?>%</td>
                        <td><?php echo esc($value->pr_clinic);?></td>
                        <td><?php echo number_format($no_show_clinic, 2);?>%</td>
                        <td><?php echo esc($value->pr_room);?></td>
                        <td><?php echo number_format($no_show_procedure, 2);?>%</td>
                    </tr>
            <?php } }else{ ?>
                <tr>
                    <th colspan="14" class="text-center text-danger"><?php echo get_notify('data_is_not_available');?></th>
                </tr>
            <?php }?>
        </tbody>
    </table>
</div>
<div class="no-print">
  <?php if($print_access){ ?>
    <button type="button" class="btn btn-info" onclick="printContent('resultData')"><span class="fa fa-print"></span> <?php echo get_phrases(['print']);?></button>
  <?php } if($export_access){ ?>
    <a href="<?php echo base_url('reports/appointment/exportNoShow?doctor_id='.$doctor.'&from='.$from.'&to='.$to);?>" class="btn btn-purple"><span class="fa fa-download"></span> <?php echo get_phrases(['export','excel']);?></a>
  <?php }?>
</div>