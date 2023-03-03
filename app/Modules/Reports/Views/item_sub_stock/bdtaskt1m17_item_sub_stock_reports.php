<div class="row">
    <div class="col-md-6">
        <img src="<?php echo base_url().$setting->logo;?>" class="img-responsive" alt=""></br>
        <?php echo get_phrases(['phone']) ?>: <?php echo $setting->phone; ?></br>
        <?php echo get_phrases(['email']) ?>: <?php echo $setting->email; ?>
        <br>
    </div>
    <div class="col-md-6 text-right">
         <address>
            <strong><?php echo $setting->title; ?></strong><br>
            <?php echo $setting->address; ?>
        </address>
    </div>
</div> <hr>
<h4><center id="title"></center></h4>
<div class="table-responsive">
    <table class="table table-striped table-sm table-hover detailsTable">
        <thead>
            <tr>
                <th width="5%"><?php echo get_phrases(['item', 'code']);?></th>
                <th width="20%"><?php echo get_phrases(['item', 'name']);?></th>
                <th width="5%" class="text-right"><?php echo get_phrases(['no','of','carton']);?></th>
                <th width="5%" class="text-right"><?php echo get_phrases(['carton','qty']);?></th>
                <th width="5%" class="text-right"><?php echo get_phrases(['no','of','box']);?></th>
                <th width="5%" class="text-right"><?php echo get_phrases(['box','qty']);?></th>
                <th width="5%" class="text-right"><?php echo get_phrases(['in','qty']);?></th>
                <th width="5%" class="text-right"><?php echo get_phrases(['out','qty']);?></th>
                <th width="10%" class="text-right"><?php echo get_phrases(['stock','qty']);?></th>
                <th width="5%"><?php echo get_phrases(['base', 'unit']);?></th>
                <th width="5%" class="text-right"><?php echo get_phrases(['mfs', '30']);?></th>
                <th width="5%" class="text-right"><?php echo get_phrases(['mfs', '90']);?></th>
                <th width="10%" class="text-right"><?php echo get_phrases(['avg', 'cost']);?></th>
                <th width="10%" class="text-right"><?php echo get_phrases(['total','amount']);?></th>
            </tr>
        </thead>
        <tbody>
           <?php 
           $total = 0;
           if(!empty($results)){
                foreach ($results as $value) { 

                  /*$cons_qty = ($value->cons_rec_qty-$value->cons_ret_qty);
                  if(($value->cons_rec_qty-$value->cons_ret_qty) <0 ){
                    $cons_qty = 0;
                  }
                  $trans_in_qty = 0;
                  $trans_out_qty = ($value->trans_out_qty-$value->trans_in_qty);
                  if(($value->trans_out_qty-$value->trans_in_qty) <0 ){
                    $trans_in_qty = $value->trans_in_qty-$value->trans_out_qty;
                    $trans_out_qty = 0;
                  }
                  $stock_qty = ($value->req_in_qty+$trans_in_qty)-($value->req_ret_qty+$cons_qty+$trans_out_qty);*/

                  $in_qty = $value->stock_in + $value->stock_adjust_in;
                  if( $in_qty <0 ){
                    $in_qty = 0;
                  }

                  $out_qty = $value->stock_out + $value->stock_adjust_out;
                  if( $out_qty <0 ){
                    $out_qty = 0;
                  }

                  $stock_qty = $in_qty - $out_qty;

                  if($stock_qty <0 ){
                    $stock_qty = 0;
                  }
                  
                  $total += $stock_qty*$value->price;
                  ?>
                   <tr onclick="onclick_change_bg('.detailsTable', this, 'cyan')">
                       <td><?php echo esc($value->company_code);?></td>
                       <td><?php echo esc($value->item_name);?></td>
                       <td class="text-right"><?php echo esc(($value->carton_qty>0 && $value->box_qty>0)?number_format(($stock_qty/($value->carton_qty*$value->box_qty)), 2):'');?></td>
                       <td class="text-right"><?php echo esc(($value->carton_qty>0)?number_format($value->carton_qty,2):'');?></td>
                       <td class="text-right"><?php echo esc(($value->box_qty>0)?number_format(($stock_qty/$value->box_qty), 2):'');?></td>
                       <td class="text-right"><?php echo esc(($value->box_qty>0)?number_format($value->box_qty, 2):'');?></td>
                       <td class="text-right"><a href="javascript:void(0)" onclick="preview(this)" data-id="<?php echo esc($value->item_id);?>" warehouse-id="<?php echo esc($value->sub_store_id);?>"><?php echo esc(number_format($in_qty, 2));?></a></td>
                       <td class="text-right"><a href="javascript:void(0)" onclick="preview(this)" data-id="<?php echo esc($value->item_id);?>" warehouse-id="<?php echo esc($value->sub_store_id);?>"><?php echo esc(number_format($out_qty, 2));?></a></td>
                       <td class="text-right"><a href="javascript:void(0)" onclick="preview(this)" data-id="<?php echo esc($value->item_id);?>" warehouse-id="<?php echo esc($value->sub_store_id);?>"><?php echo esc(number_format($stock_qty, 2));?></a></td>
                       <td><?php echo esc($value->unit_name);?></td>
                       <td class="text-right"><?php echo esc(($value->used_30 >0)?number_format(($stock_qty/$value->used_30), 2):'');?></td>
                       <td class="text-right"><?php echo esc(($value->used_90 >0)?number_format(($stock_qty/($value->used_90/3)), 2):'');?></td>
                       <td class="text-right"><?php echo esc(number_format($value->price, 2));?></td>
                       <td class="text-right"><?php echo esc(number_format($stock_qty*$value->price, 2));?></td>
                   </tr>
            <?php } 
          } else { ?>
                <tr>
                    <th colspan="14" class="text-center text-muted text-danger"><?php echo get_notify('data_is_not_available');?></th>
                </tr>
          <?php } ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="13" class="text-right"><?php echo get_phrases(['total']);?></th>
                <th class="text-right"><?php echo number_format($total, 2);?></th>
            </tr>
        </tfoot>
    </table>
</div>
<?php if($hasPrintAccess){ ?>
<div class="card-footer no-print">
    <button type="button" class="btn btn-info" onclick="printContent('printC')"><span class="fa fa-print"></span> <?php echo get_phrases(['print']);?></button>
</div>
<?php } ?>
