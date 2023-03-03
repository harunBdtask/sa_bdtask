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
</div> <hr class="mb-0">
<center>
  <b class="fs-20"><?php echo get_phrases(['activity', 'logs', 'report']);?></b>
  <h6 id="title"></h6>
</center>
<div class="table-responsive">
    <table class="table table-stripped table-sm">
        <thead>
            <tr>
                <th width="10%"><?php echo get_phrases(['serial', 'no']);?></th>
                <th width="15%"><?php echo get_phrases(['type']);?></th>
                <th width="15%"><?php echo get_phrases(['action', 'type']);?></th>
                <th width="10%"><?php echo get_phrases(['action', 'id']);?></th>
                <th width="20%"><?php echo get_phrases(['description']);?></th>
                <th width="10%"><?php echo get_phrases(['action', 'by']);?></th>
                <th width="10%"><?php echo get_phrases(['action', 'date']);?></th>
            </tr>
        </thead>
        <tbody>
           <?php
           if(!empty($results)){
                foreach ($results as $value) { 
                  ?>
                   <tr>
                       <td><?php echo esc($value->id);?></td>
                       <td><?php echo esc($value->type);?></td>
                       <td><?php echo esc($value->action);?></td>
                       <td><?php echo esc($value->action_id);?></td>
                       <td><?php echo esc($value->slug);?></td>
                       <td><?php echo esc($value->created_by);?></td>
                       <td><?php echo esc(date('d/m/Y h:i:s A', strtotime($value->created_date)));?></td>
                   </tr>
            <?php } }else{?>
                <tr>
                    <th colspan="7" class="text-center text-muted text-danger"><?php echo get_notify('data_is_not_available');?></th>
                </tr>
            <?php }?>
        </tbody>
    </table>
</div>

<div class="card-footer no-print">
    <?php if($hasPrintAccess){ ?>
    <button type="button" class="btn btn-info" onclick="printContent('printC')"><span class="fa fa-print"></span> <?php echo get_phrases(['print']);?></button>
  <?php } if($hasExportAccess){ ?>
    <a href="<?php echo base_url('reports/management/exportActivityLogs?user_id='.$user_id.'&from='.$from.'&to='.$to);?>" class="btn btn-purple"><span class="fa fa-download"></span> <?php echo get_phrases(['export','excel']);?></a>
  <?php }?>
</div>