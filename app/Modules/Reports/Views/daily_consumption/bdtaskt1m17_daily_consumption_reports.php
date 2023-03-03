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
                <th width="10%"><?php echo get_phrases(['sl']);?></th>
                <th width="20%">Ingridients</th>
                <th width="10%" class="text-right">Opening Stock (Kg)</th>
                <th width="10%" class="text-right">Received (Kg)</th>
                <th width="10%" class="text-right">Total (Kg)</th>
                <th width="10%" class="text-right">Consumption</th>
                <th width="10%" class="text-right">Closing Balance (Kg)</th>
                <th width="10%">Remarks</th>
            </tr>
        </thead>
        <tbody>
           <?php 
           $total = 0;
           $sl = 1;
           if(!empty($results)){
                foreach ($results as $value) { 
                  
                  //$total += $value->qty;

                  ?>
                   <tr onclick="onclick_change_bg('.detailsTable', this, 'cyan')">
                       <td><?php echo esc($sl);?></td>
                       <td><?php echo esc($value->item_name);?></td>
                       <td class="text-right"><?php echo esc(number_format($value->stock, 2));?></td>
                       <td class="text-right"><?php echo esc(number_format($value->received, 2));?></td>
                       <td class="text-right"><?php echo esc(number_format($value->stock+$value->received, 2));?></td>
                       <td class="text-right"><?php echo esc(number_format($value->consumed, 2));?></td>
                       <td class="text-right"><?php echo esc(number_format($value->stock+$value->received-$value->consumed, 2));?></td>
                       <td></td>
                   </tr>
            <?php } 
          } else { ?>
                <tr>
                    <th colspan="8" class="text-center text-muted text-danger"><?php echo get_notify('data_is_not_available');?></th>
                </tr>
          <?php } ?>
        </tbody>
        <!-- <tfoot>
            <tr>
                <th colspan="2" class="text-right"><?php //echo get_phrases(['total']);?></th>
                <th class="text-right"></th>
                <th class="text-right"></th>
                <th class="text-right"></th>
                <th class="text-right"></th>
                <th class="text-right"></th>
                <th></th>
            </tr>
        </tfoot> -->
    </table>
</div>
<div class="row form-group">
  <div class="col-sm-3 text-center">
    <br><br>
    ----------------<br>
    Prepared By
  </div>
  <div class="col-sm-3 text-center">
    <br><br>
    ------------------<br>
    Executive Distribution
  </div>
  <div class="col-sm-3 text-center">
    <br><br>
    --------------------<br>
    Executive ( Accounts )
  </div>
  <div class="col-sm-3 text-center">
    <br><br>
    --------------------<br>
    Asst. Manager ( Plant )
  </div>
</div>
<div class="row form-group">
  <div class="col-sm-4 text-center">
    <br><br>
    ----------------<br>
    Factory Manager 
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
