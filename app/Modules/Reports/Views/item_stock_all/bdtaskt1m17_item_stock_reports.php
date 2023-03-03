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
    <table class="table table-stripped table-sm table-hover detailsTable">
        <thead>
            <tr>
                <th width="5%"><?php echo get_phrases(['item', 'code']);?></th>
                <th width="20%"><?php echo get_phrases(['item', 'name']);?></th>
                <th width="5%" class="text-right"><?php echo get_phrases(['carton']);?></th>
                <th width="5%" class="text-right"><?php echo get_phrases(['carton','qty']);?></th>
                <th width="5%" class="text-right"><?php echo get_phrases(['box']);?></th>
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
           $all_data = array();
          foreach ($results as $value) { 
                  
              /* main store */
              /*$in_qty = $value->in_qty - $value->return_qty;
              if( $in_qty <0 ){
                $in_qty = 0;
              }

              $out_qty = $value->req_in_qty - $value->req_ret_qty;
              if( $out_qty <0 ){
                $out_qty = 0;
              }
              if($out_qty > $in_qty){
                $out_qty = $in_qty;
              }

              $stock_qty = $in_qty - $out_qty;
              if( $stock_qty <0 ){
                $stock_qty = 0;
              }

              // sub store 
              $cons_qty = ($value->cons_rec_qty-$value->cons_ret_qty);
              if($cons_qty <0 ){
                $cons_qty = 0;
              }
              $trans_in_qty = 0;
              $trans_out_qty = ($value->trans_out_qty-$value->trans_in_qty);
              if($trans_out_qty <0 ){
                $trans_in_qty = $value->trans_in_qty-$value->trans_out_qty;
                $trans_out_qty = 0;
              }
              $dept_in_qty = $value->req_in_qty + $trans_in_qty;
              $dept_out_qty = $value->req_ret_qty + $cons_qty + $trans_out_qty;

              if($dept_out_qty > $dept_in_qty){
                $dept_out_qty = $dept_in_qty;
              }
              $sub_stock_qty = $dept_in_qty - $dept_out_qty;
              
              if($sub_stock_qty <0 ){
                $sub_stock_qty = 0;
              }

              if($store == 'all'){
                $stock_qty = $stock_qty + $sub_stock_qty;
                $in_qty = $in_qty + $dept_in_qty;
                $out_qty = $out_qty + $dept_out_qty;

              } else if($store == 'sub'){
                $stock_qty = $sub_stock_qty;
                $in_qty = $dept_in_qty;
                $out_qty = $dept_out_qty;
              }*/

              if($store == 'all'){
                $stock_qty = ($value->main_stock_in + $value->sub_stock_in)-($value->main_stock_out + $value->sub_stock_out);
                $in_qty = $value->main_stock_in + $value->sub_stock_in;
                $out_qty = $value->main_stock_out + $value->sub_stock_out;

              } else if($store == 'main'){
                $stock_qty = $value->main_stock_in - $value->main_stock_out;
                $in_qty = $value->main_stock_in;
                $out_qty = $value->main_stock_out;
                
              } else if($store == 'sub'){
                $stock_qty = $value->sub_stock_in - $value->sub_stock_out;
                $in_qty = $value->sub_stock_in;
                $out_qty = $value->sub_stock_out;
              }
              if( $stock_qty <0 ){
                $stock_qty = 0;
              }

              $total += $stock_qty * $value->price;

              $data['id'] = $value->id;
              $data['supplier_name'] = $value->supplier_name;
              $data['company_code'] = $value->company_code;
              $data['item_name'] = $value->item_name;
              $data['carton'] = ($value->carton_qty)?number_format($stock_qty/$value->carton_qty, 2):'';
              $data['carton_qty'] = ($value->carton_qty)?number_format($value->carton_qty, 2):'';
              $data['box'] = ($value->box_qty)?number_format($stock_qty/$value->box_qty, 2):'';
              $data['box_qty'] = ($value->box_qty)?number_format($value->box_qty, 2):'';
              $data['in_qty'] = number_format($in_qty, 2);
              $data['out_qty'] = number_format($out_qty, 2);
              $data['stock_qty'] = number_format($stock_qty, 2);
              $data['unit_name'] = $value->unit_name;
              $data['used_30'] = ($value->used_30 >0)?number_format(($stock_qty/$value->used_30), 2):'';
              $data['used_90'] = ($value->used_90 >0)?number_format(($stock_qty/($value->used_90/3)), 2):'';
              $data['price'] = number_format($value->price, 2);
              $data['total'] = $stock_qty * $value->price;

              if($sorting =='price'){
                $needle[] = $data['total'];
              }
              else if($sorting =='mfs30'){
                $needle[] = $data['used_30'];
              }
              else if($sorting =='mfs90'){
                $needle[] = $data['used_90'];
              }
              else {
                $needle[] = $data['id'];
              }

              $all_data[] = $data;
          }

          if($sorting !=''){
            if($direction =='DESC'){
              $direction = SORT_DESC;
            } else {              
              $direction = SORT_ASC;
            }
            array_multisort($needle, $direction, $all_data);
          }

          if(!empty($all_data)){
            foreach($all_data as $arr){

                  ?>
                   <tr onclick="onclick_change_bg('.detailsTable', this, 'cyan')">
                       <td><?php echo esc($arr['company_code']);?></td>
                       <td title="<?php echo $arr['supplier_name']; ?>" class="custool"><?php echo esc($arr['item_name']);?></td>                       
                       <td class="text-right"><?php echo esc($arr['carton']);?></td>
                       <td class="text-right"><?php echo esc($arr['carton_qty']);?></td>
                       <td class="text-right"><?php echo esc($arr['box']);?></td>
                       <td class="text-right"><?php echo esc($arr['box_qty']);?></td>
                       <td class="text-right"><a href="javascript:void(0);" onclick="preview(this)" data-id="<?php echo esc($arr['id']); ?>"><?php echo esc($arr['in_qty']);?></a></td>
                       <td class="text-right"><a href="javascript:void(0);" onclick="preview(this)" data-id="<?php echo esc($arr['id']); ?>"><?php echo esc($arr['out_qty']);?></a></td>
                       <td class="text-right"><a href="javascript:void(0);" onclick="preview(this)" data-id="<?php echo esc($arr['id']); ?>"><?php echo esc($arr['stock_qty']);?></a></td>
                       <td><?php echo esc($arr['unit_name']);?></td>
                       <td class="text-right"><?php echo esc($arr['used_30']);?></td>
                       <td class="text-right"><?php echo esc($arr['used_90']);?></td>
                       <td class="text-right"><?php echo esc($arr['price']);?></td>
                       <td class="text-right"><?php echo esc(number_format($arr['total'],2));?></td>
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
</div><?php } ?>
