<div class="text-center">
  <h5><?php echo get_phrases(['main','store']);?></h5>
</div>
<div class="table-responsive">
    <table class="table table-stripped table-sm">
        <thead>
            <tr>
                <th width="30%"><?php echo get_phrases(['store', 'name']);?></th>
                <th width="20%" class="text-right"><?php echo get_phrases(['in', 'qty']);?></th>
                <th width="20%" class="text-right"><?php echo get_phrases(['out', 'qty']);?></th>
                <th width="20%" class="text-right"><?php echo get_phrases(['stock']);?></th>
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
                  if($value->type=='main' && $value->stock >=0 ){
                    //$total += $value->qty*$value->price;
                    $total_qty += $value->stock;
                    $unit_name = $value->unit_name;
                  
                  ?>
                   <tr>
                       <td><?php echo esc($value->store_name);?></td>
                       <td class="text-right"><?php echo esc(number_format($value->stock_in,2));?></td>
                       <td class="text-right"><?php echo esc(number_format($value->stock_out,2));?></td>
                       <td class="text-right"><?php echo esc(number_format($value->stock, 2));?></td>
                       <td><?php echo esc($value->unit_name);?></td>
                   </tr>
            <?php $i++;
              }
            } 
          } else { ?>
                <tr>
                    <th colspan="5" class="text-center text-muted text-danger"><?php echo get_notify('data_is_not_available');?></th>
                </tr>
          <?php } ?>
        </tbody>
    </table>
</div>

<?php if($total_qty > 0 ){ ?>
<div class="row mt-2">
  <div class="col-sm-10 text-right">
    <strong><?php echo get_phrases(['total']);?>: </strong>
  </div>
  <div class="col-sm-2 text-right"><?php echo number_format($total_qty, 2); echo ' '.$unit_name; ?></div>
</div>
<?php } 

$total2 = 0; 
$total2_qty = 0; 

if(count($results) > $i ){ 
  ?>
<br>
<div class="text-center">
  <h5><?php echo get_phrases(['sub','store']);?></h5>
</div>
<div class="table-responsive">
    <table class="table table-stripped table-sm">
        <thead>
            <tr>
                <th width="30%"><?php echo get_phrases(['sub','store', 'name']);?></th>
                <th width="20%" class="text-right"><?php echo get_phrases(['in', 'qty']);?></th>
                <th width="20%" class="text-right"><?php echo get_phrases(['out', 'qty']);?></th>
                <th width="20%" class="text-right"><?php echo get_phrases(['stock']);?></th>
                <th width="10%"><?php echo get_phrases(['base','unit']);?></th>
            </tr>
        </thead>
        <tbody>
           <?php 
           //$total2 = 0;
           if(!empty($results)){
                foreach ($results as $value) { 
                  if($value->type=='sub' && $value->stock >=0 ){
                    //$total2 += $value->qty*$value->price;
                    $total2_qty += $value->stock;
                  
                  
                  ?>
                   <tr>
                       <td><?php echo esc($value->store_name);?></td>
                       <td class="text-right"><?php echo esc(number_format($value->stock_in,2));?></td>
                       <td class="text-right"><?php echo esc(number_format($value->stock_out,2));?></td>
                       <td class="text-right"><?php echo esc(number_format($value->stock, 2));?></td>
                       <td><?php echo esc($value->unit_name);?></td>
                   </tr>
            <?php }
            } 
          } else { ?>
                <tr>
                    <th colspan="5" class="text-center text-muted text-danger"><?php echo get_phrases(['data', 'not', 'available']);?></th>
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
    <strong><?php echo get_phrases(['total']);?>: </strong>
  </div>
  <div class="col-sm-2 text-right"><?php echo number_format($total2_qty, 2); echo ' '.$unit_name; ?></div>
</div>
<?php }
/*
if( $total_qty > 0 && $total2_qty > 0 ){ ?>
<div class="row mt-2">
  <div class="col-sm-10 text-right">
    <strong><?php echo get_phrases(['stock', 'balance']);?>: </strong>
  </div>
  <div class="col-sm-2 text-right"><?php echo number_format($total_qty-$total2_qty, 2); echo ' '.$unit_name; ?></div>
</div>
<?php } */ ?>