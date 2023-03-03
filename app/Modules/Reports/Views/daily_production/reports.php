<div class="row">
    <div class="col-md-12 text-center">
        <img src="<?php echo base_url().$setting->logo;?>" class="img-responsive" alt=""><strong><?php echo $setting->title; ?></strong><br>
        <?php echo $setting->address; ?>
        
    </div>
</div> <hr>
<h4><center id="title"></center></h4>
<div class="table-responsive">
    <table class="table table-bordered table-stripped table-sm table-hover detailsTable">
        <thead>
            <tr>
                <th rowspan="2"><?php echo get_phrases(['sl']);?></th>
                <th rowspan="2"><?php echo get_phrases(['item', 'name']);?></th>
                <th class="text-center" rowspan="2"><?php echo get_phrases(['bag', 'size']);?>(KG)</th>
                <th class="text-center" colspan="2"><?php echo get_phrases(['opening','stock']);?></th>
                <th class="text-center" colspan="2"><?php echo get_phrases(['production']);?></th>
                <th class="text-center" colspan="2"><?php echo get_phrases(['total','stock']);?></th>
                <th class="text-center" colspan="2"><?php echo get_phrases(['delivery','order']);?></th>
                <th class="text-center" colspan="2"><?php echo get_phrases(['delivery']);?></th>
                <th class="text-center" colspan="2"><?php echo get_phrases(['undelivered']);?></th>
                <th class="text-center" colspan="2"><?php echo get_phrases(['closing','stock']);?></th>
            </tr>
            <tr>
                <th class="text-center"><?php echo get_phrases(['bag']);?></th>
                <th class="text-center">MT</th>
                <th class="text-center"><?php echo get_phrases(['bag']);?></th>
                <th class="text-center">MT</th>
                <th class="text-center"><?php echo get_phrases(['bag']);?></th>
                <th class="text-center">MT</th>
                <th class="text-center"><?php echo get_phrases(['bag']);?></th>
                <th class="text-center">MT</th>
                <th class="text-center"><?php echo get_phrases(['bag']);?></th>
                <th class="text-center">MT</th>
                <th class="text-center"><?php echo get_phrases(['bag']);?></th>
                <th class="text-center">MT</th>
                <th class="text-center"><?php echo get_phrases(['bag']);?></th>
                <th class="text-center">MT</th>
            </tr>
        </thead>
        <tbody>
           <?php 
           $total_opening_stock = 0;
           $total_opening_stock_mt = 0;
           $total_prod_qty = 0;
           $total_prod_qty_mt = 0;
           $total_total_stock = 0;
           $total_total_stock_mt = 0;
           $total_delivery_order = 0;
           $total_delivery_order_mt = 0;
           $total_delivery_qty = 0;
           $total_delivery_qty_mt = 0;
           $total_undelivered_qty = 0;
           $total_undelivered_qty_mt = 0;
           $total_closing_stock = 0;
           $total_closing_stock_mt = 0;

           $sl = 1;
           if(!empty($results)){
                foreach ($results as $value){ 
                  
                  $bag_size = $value->bag_size;

                  $opening_stock = $value->opening_stock - $value->prev_delivery_qty;
                  $opening_stock_mt = ($opening_stock * $bag_size)/1000;

                  $prod_qty = $value->prod_qty;
                  $prod_qty_mt = ($prod_qty * $bag_size)/1000;

                  $total_stock = $value->opening_stock - $value->prev_delivery_qty + $value->prod_qty;
                  $total_stock_mt = ($total_stock * $bag_size)/1000;

                  $delivery_order = $value->delivery_order;
                  $delivery_order_mt = ($delivery_order * $bag_size)/1000;

                  $delivery_qty = $value->delivery_qty;
                  $delivery_qty_mt = ($delivery_qty * $bag_size)/1000;
                  
                  $undelivered_qty = $value->undelivered_qty;
                  $undelivered_qty_mt = ($undelivered_qty * $bag_size)/1000;

                  $closing_stock = $value->opening_stock - $value->prev_delivery_qty + $value->prod_qty - $value->delivery_qty;
                  $closing_stock_mt = ($closing_stock * $bag_size)/1000;


                  ?>
                   <tr onclick="onclick_change_bg('.detailsTable', this, 'cyan')">
                       <td><?php echo esc($sl);?></td>
                       <td><?php echo esc($value->item_name);?></td>
                       <td class="text-right"><?php echo esc(number_format($bag_size, 2));?></td>
                       <td class="text-right"><?php echo esc(number_format($opening_stock, 2));?></td>
                       <td class="text-right"><?php echo esc(number_format($opening_stock_mt, 2));?></td>
                       <td class="text-right"><?php echo esc(number_format($prod_qty, 2));?></td>
                       <td class="text-right"><?php echo esc(number_format($prod_qty_mt, 2));?></td>
                       <td class="text-right"><?php echo esc(number_format($total_stock, 2));?></td>
                       <td class="text-right"><?php echo esc(number_format($total_stock_mt, 2));?></td>
                       <td class="text-right"><?php echo esc(number_format($delivery_order, 2));?></td>
                       <td class="text-right"><?php echo esc(number_format($delivery_order_mt, 2));?></td>
                       <td class="text-right"><?php echo esc(number_format($delivery_qty, 2));?></td>
                       <td class="text-right"><?php echo esc(number_format($delivery_qty_mt, 2));?></td>
                       <td class="text-right"><?php echo esc(number_format($undelivered_qty, 2));?></td>
                       <td class="text-right"><?php echo esc(number_format($undelivered_qty_mt, 2));?></td>
                       <td class="text-right"><?php echo esc(number_format($closing_stock, 2));?></td>
                       <td class="text-right"><?php echo esc(number_format($closing_stock_mt, 2));?></td>
                   </tr>
            <?php 

                  $total_opening_stock += $opening_stock;
                  $total_opening_stock_mt += $opening_stock_mt;
                  $total_prod_qty += $prod_qty;
                  $total_prod_qty_mt += $prod_qty_mt;
                  $total_total_stock += $total_stock;
                  $total_total_stock_mt += $total_stock_mt;
                  $total_delivery_order += $delivery_order;
                  $total_delivery_order_mt += $delivery_order_mt;
                  $total_delivery_qty += $delivery_qty;
                  $total_delivery_qty_mt += $delivery_qty_mt;
                  $total_undelivered_qty += $undelivered_qty;
                  $total_undelivered_qty_mt += $undelivered_qty_mt;
                  $total_closing_stock += $closing_stock;
                  $total_closing_stock_mt += $closing_stock_mt;
                } 
          } else { ?>
                <tr>
                    <th colspan="15" class="text-center text-muted text-danger"><?php echo get_notify('data_is_not_available');?></th>
                </tr>
          <?php } ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3" class="text-right"><?php echo get_phrases(['total']);?></th>
                <th class="text-right"><?php echo number_format($total_opening_stock, 2);?></th>
                <th class="text-right"><?php echo number_format($total_opening_stock_mt, 2);?></th>
                <th class="text-right"><?php echo number_format($total_prod_qty, 2);?></th>
                <th class="text-right"><?php echo number_format($total_prod_qty_mt, 2);?></th>
                <th class="text-right"><?php echo number_format($total_total_stock, 2);?></th>
                <th class="text-right"><?php echo number_format($total_total_stock_mt, 2);?></th>
                <th class="text-right"><?php echo number_format($total_delivery_order, 2);?></th>
                <th class="text-right"><?php echo number_format($total_delivery_order_mt, 2);?></th>
                <th class="text-right"><?php echo number_format($total_delivery_qty, 2);?></th>
                <th class="text-right"><?php echo number_format($total_delivery_qty_mt, 2);?></th>
                <th class="text-right"><?php echo number_format($total_undelivered_qty, 2);?></th>
                <th class="text-right"><?php echo number_format($total_undelivered_qty_mt, 2);?></th>
                <th class="text-right"><?php echo number_format($total_closing_stock, 2);?></th>
                <th class="text-right"><?php echo number_format($total_closing_stock_mt, 2);?></th>
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
