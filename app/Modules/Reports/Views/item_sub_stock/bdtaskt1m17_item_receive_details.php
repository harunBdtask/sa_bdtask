<div class="text-center">
  <h5>Stock In</h5>
</div>
<div class="table-responsive">
    <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th width="20%"><?php echo get_phrases(['receive', 'from']);?></th>
                <th width="20%"><?php echo get_phrases(['date']);?></th>
                <th width="20%" class="text-right"><?php echo get_phrases(['total','qty']);?></th>
                <th width="10%"><?php echo get_phrases(['base','unit']);?></th>
            </tr>
        </thead>
        <tbody>
           <?php 
           $unit_name = '';
           $total = 0;
           $total_qty = 0;
           $i=0;
           if(!empty($results)){
                foreach ($results as $value) { 
                  if($value->type == 'in' && $value->aqty >0 ){
                  //$total += $value->qty*$value->price;
                    $total_qty += $value->aqty;
                    $unit_name = $value->unit_name;
                  
                  ?>
                   <tr>
                       <td><?php echo esc($value->store_name);?></td>
                       <td><?php echo esc($value->approved_date);?></td>
                       <td class="text-right"><?php echo esc(number_format($value->aqty, 2));?></td>
                       <td><?php echo esc($value->unit_name);?></td>
                   </tr>
            <?php $i++;
              }
            } 
          } else { ?>
                <tr>
                    <th colspan="4" class="text-center text-muted text-danger"><?php echo get_notify('data_is_not_available');?></th>
                </tr>
          <?php } ?>
        </tbody>
    </table>
</div>
<?php if($total_qty > 0 ){ ?>
<div class="row mt-2">
  <div class="col-sm-10 text-right">
    <strong><?php echo get_phrases(['total','stock', 'in']);?>: </strong>
  </div>
  <div class="col-sm-2 text-right"><?php echo number_format($total_qty, 2); echo ' '.$unit_name; ?></div>
</div>
<?php } ?>


<?php 
$total2 = 0; 
$total2_qty = 0; 

if(count($results) > $i ){ 
  ?>
<br>
<div class="text-center">
  <h5><?php echo get_phrases(['stock', 'out']);?></h5>
</div>
<div class="table-responsive">
    <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th width="20%"><?php echo get_phrases(['transfer', 'to']);?></th>
                <th width="20%"><?php echo get_phrases(['date']);?></th>
                <th width="20%" class="text-right"><?php echo get_phrases(['total','qty']);?></th>
                <th width="10%"><?php echo get_phrases(['base','unit']);?></th>
            </tr>
        </thead>
        <tbody>
           <?php 
           //$total = 0;
           if(!empty($results)){
                foreach ($results as $value) { 
                  if($value->type == 'out' && $value->aqty >0 ){
                    //$total += $value->qty*$value->price;
                    $total2_qty += $value->aqty;
                    $unit_name = $value->unit_name;
                  
                  ?>
                   <tr>
                       <td><?php echo esc($value->store_name);?></td>
                       <td><?php echo esc($value->approved_date);?></td>
                       <td class="text-right"><?php echo esc(number_format($value->aqty, 2));?></td>
                       <td><?php echo esc($value->unit_name);?></td>
                   </tr>
            <?php }
            } 
          } else { ?>
                <tr>
                    <th colspan="4" class="text-center text-muted text-danger"><?php echo get_phrases(['data', 'not', 'available']);?></th>
                </tr>
          <?php } ?>
        </tbody>
    </table>
</div>

<?php } ?>
<?php 
if( $total2_qty > 0 ){ ?>
<div class="row mt-2">
  <div class="col-sm-10 text-right">
    <strong><?php echo get_phrases(['total','stock', 'out']);?>: </strong>
  </div>
  <div class="col-sm-2 text-right"><?php echo number_format($total2_qty, 2); echo ' '.$unit_name; ?></div>
</div>
<?php }
  /*
if( $total_qty > 0 && $total2_qty > 0 ){ 

<div class="row mt-2">
  <div class="col-sm-10 text-right">
    <strong><?php echo get_phrases(['stock', 'balance']);?>: </strong>
  </div>
  <div class="col-sm-2 text-right"><?php echo number_format($total_qty-$total2_qty, 2); echo ' '.$unit_name; ?></div>
</div>
 } */ ?>