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
            <tr>
                <th width="2%"><?php echo get_phrases(['sl']);?></th>
                <th width="18%"><?php echo get_phrases(['item', 'name']);?></th>
                <th width="10%" class="text-right"><?php echo get_phrases(['bag', 'size']);?></th>
                <th width="10%" class="text-right"><?php echo get_phrases(['input']);?> KG</th>
                <th width="10%" class="text-right"><?php echo get_phrases(['old', 'feed']);?></th>
                <th width="10%" class="text-right"><?php echo get_phrases(['wastage']);?></th>
                <th width="10%" class="text-right"><?php echo get_phrases(['bags']);?></th>
                <th width="10%" class="text-right"><?php echo get_phrases(['output']);?> KG</th>
                <th width="10%" class="text-right"><?php echo get_phrases(['loss']);?> KG</th>
                <th width="5%" class="text-right"><?php echo get_phrases(['production']);?> %</th>
                <th width="5%" class="text-right"><?php echo get_phrases(['loss']);?> %</th>
            </tr>
        </thead>
        <tbody>
           <?php 
           $input_kg = 0;
           $old_feed = 0;
           $wastage = 0;
           $act_bags = 0;
           $qty = 0;
           $loss_kg = 0;
           $sl = 1;
           if(!empty($results)){
                foreach ($results as $value) { 
                  
                  $input_kg += $value->input_kg;
                  $old_feed += $value->old_feed;
                  $wastage += $value->wastage;
                  $act_bags += $value->act_bags;
                  $qty += $value->qty;
                  $loss_kg += $value->loss_kg;

                  ?>
                   <tr onclick="onclick_change_bg('.detailsTable', this, 'cyan')">
                       <td><?php echo esc($sl);?></td>
                       <td><?php echo esc($value->item_name);?></td>
                       <td class="text-right"><?php echo esc(number_format($value->bag_size, 2));?></td>
                       <td class="text-right"><?php echo esc(number_format($value->input_kg, 2));?></td>
                       <td class="text-right"><?php echo esc(number_format($value->old_feed, 2));?></td>
                       <td class="text-right"><?php echo esc(number_format($value->wastage, 2));?></td>
                       <td class="text-right"><?php echo esc(number_format($value->act_bags, 2));?></td>
                       <td class="text-right"><?php echo esc(number_format($value->qty, 2));?></td>
                       <td class="text-right"><?php echo esc(number_format($value->loss_kg, 2));?></td>
                       <td class="text-right"><?php echo esc(number_format($value->prod_percent, 2));?>%</td>
                       <td class="text-right"><?php echo esc(number_format($value->loss_percent, 2));?>%</td>
                   </tr>
            <?php } 
          } else { ?>
                <tr>
                    <th colspan="10" class="text-center text-muted text-danger"><?php echo get_notify('data_is_not_available');?></th>
                </tr>
          <?php } ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3" class="text-right"><?php echo get_phrases(['total']);?></th>
                <th class="text-right"><?php echo number_format($input_kg, 2);?></th>
                <th class="text-right"><?php echo number_format($old_feed, 2);?></th>
                <th class="text-right"><?php echo number_format($wastage, 2);?></th>
                <th class="text-right"><?php echo number_format($act_bags, 2);?></th>
                <th class="text-right"><?php echo number_format($qty, 2);?></th>
                <th class="text-right"><?php echo number_format($loss_kg, 2);?></th>
                <th colspan="2"></th>
            </tr>
        </tfoot>
    </table>
</div>
<div class="row form-group">
  <div class="col-sm-4 text-center">
    <br><br>
    ----------------<br>
    Supervisor
  </div>
  <div class="col-sm-4 text-center">
    <br><br>
    ------------------<br>
    Control Room Eng:
  </div>
  <div class="col-sm-4 text-center">
    <br><br>
    --------------------<br>
    Asst. Manager Plant
  </div>
</div>
<?php if($hasPrintAccess){ ?>
<div class="card-footer no-print">
    <button type="button" class="btn btn-info" onclick="printContent('printC')"><span class="fa fa-print"></span> <?php echo get_phrases(['print']);?></button>
</div><?php } ?>
