<div class="row">
    <div class="col-md-12 text-center">
        <img src="<?php echo base_url().$setting->logo;?>" class="img-responsive" alt=""><strong><?php echo $setting->title; ?></strong><br>
        <?php echo $setting->address; ?>
        
    </div>
</div> <hr>
<h4><center id="title"></center></h4>
<div class="table-responsive">
    <table class="table table-stripped table-sm table-hover detailsTable">
        <thead>
        </thead>
        <tbody>
           <?php 
           $sub_total = 0;
           $grand_total = 0;

           $sl = 1;
           if(!empty($results)){
                $plant_name = '';
                foreach ($results as $value) { 
                    if($plant_name != $value->plant_name && $plant_name != ''){
                        
                        ?>

                        <tr>
                            <td colspan="2" class="text-right font-weight-bold"><?php echo get_phrases(['sub','total']);?></td>
                            <td class="text-right font-weight-bold"><?php echo number_format($sub_total, 2);?></td>
                        </tr>

                        <tr>
                            <td colspan="3" class="text-center font-weight-bold"><?php echo $value->plant_name; ?></td>
                        </tr>
                        <tr>
                            <th width="10%"><?php echo get_phrases(['sl']);?></th>
                            <th width="30%">Material Name</th>
                            <th width="20%" class="text-right">Amount (Kg)</th>
                        </tr>
                        <?php
                        $sub_total =0;
                    } else if( empty($plant_name) ){ ?>

                        <tr>
                            <td colspan="3" class="text-center font-weight-bold"><?php echo $value->plant_name; ?></td>
                        </tr>
                        <tr>
                            <th width="10%"><?php echo get_phrases(['sl']);?></th>
                            <th width="30%">Material Name</th>
                            <th width="20%" class="text-right">Amount (Kg)</th>
                        </tr>
                    <?php
                    }
                 $plant_name = $value->plant_name;
                  
                  $sub_total += $value->consume_qty;
                  $grand_total += $value->consume_qty;

                  ?>
                   <tr onclick="onclick_change_bg('.detailsTable', this, 'cyan')">
                       <td><?php echo esc($sl);?></td>
                       <td><?php echo esc($value->item_name);?></td>
                       <td class="text-right"><?php echo esc(number_format($value->consume_qty, 2));?></td>
                   </tr>
            <?php } 
          } else { ?>
                <tr>
                    <th colspan="3" class="text-center text-muted text-danger"><?php echo get_notify('data_is_not_available');?></th>
                </tr>
          <?php } ?>

                        <tr>
                            <td colspan="2" class="text-right font-weight-bold"><?php echo get_phrases(['sub','total']);?></td>
                            <td class="text-right font-weight-bold"><?php echo number_format($sub_total, 2);?></td>
                        </tr>
        </tbody>
        <tfoot>            
            <tr>
                <th colspan="2" class="text-right"><?php echo get_phrases(['grand','total']);?></th>
                <th class="text-right"><?php echo number_format($grand_total, 2);?></th>
            </tr>
        </tfoot>
    </table>
</div>
<div class="row form-group">
  <div class="col-sm-4 text-center">
    <br><br>
    ------------------<br>
    Executive Distribution
  </div>
  <div class="col-sm-4 text-center">
    <br><br>
    --------------------<br>
    Executive ( Accounts )
  </div>
  <div class="col-sm-4 text-center">
    <br><br>
    --------------------<br>
    Asst. Manager ( Plant )
  </div>
</div>
<div class="row form-group">
  <div class="col-sm-4 text-center">
    <br><br>
    ----------------<br>
    AGM (A&F) 
  </div>
  <div class="col-sm-4 text-center">
    <br><br>
    ------------------<br>
    GM ( Operations )
  </div>
  <div class="col-sm-4 text-center">
    <br><br>
    --------------------<br>
    Project Coordinator
  </div>
</div>
<?php if($hasPrintAccess){ ?>
<div class="card-footer no-print">
    <button type="button" class="btn btn-info" onclick="printContent('printC')"><span class="fa fa-print"></span> <?php echo get_phrases(['print']);?></button>
</div><?php } ?>
