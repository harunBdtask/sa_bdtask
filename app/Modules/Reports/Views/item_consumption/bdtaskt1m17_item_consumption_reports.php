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
    <table class="table table-stripped table-sm table-hover detailsTable" >
        <thead>
            <tr>
                <th width="5%"><?php echo get_phrases(['item', 'code']);?></th>
                <th width="15%"><?php echo get_phrases(['item', 'name']);?></th>
                <th width="5%"><?php echo get_phrases(['voucher','no']);?></th>
                <th width="10%"><?php echo get_phrases(['approve','date']);?></th>
                <th width="10%"><?php echo get_phrases(['store']);?></th>
                <th width="10%"><?php echo get_phrases(['department']);?></th>
                <th width="10%"><?php echo get_phrases(['doctor']);?></th>
                <th width="5%"><?php echo get_phrases(['service','code']);?></th>
                <th width="5%"><?php echo get_phrases(['patient','file']);?></th>
                <th width="5%"><?php echo get_phrases(['invoice','no']);?></th>
                <th width="5%" class="text-right"><?php echo get_phrases(['quantity']);?></th>
                <th width="5%"><?php echo get_phrases(['base','unit']);?></th>
                <th width="5%" class="text-right"><?php echo get_phrases(['unit','cost']);?></th>
                <th width="5%" class="text-right"><?php echo get_phrases(['total','cost']);?></th>
            </tr>
        </thead>
        <tbody>
           <?php 
           $total = 0;
           $total_qty = 0;
           if(!empty($results)){
                foreach ($results as $value) { 
                  
                  $qty = $value->aqty - $value->return_qty;
                  if($qty <0 ){
                    $qty = 0;
                  }

                  $class = '';
                  if($value->return_qty >0 ){
                    $class = 'text-danger';
                  }

                  $total += $qty*$value->price;
                  $total_qty += $qty;

                  $data['id'] = $value->id;
                  $data['consumed_by'] = $value->consumed_by;
                  $data['company_code'] = $value->company_code;
                  $data['item_name'] = $value->item_name;
                  $data['approved_date'] = $value->approved_date;
                  $data['store_name'] = $value->store_name;
                  $data['dept_name'] = $value->dept_name;
                  $data['doctor_name'] = $value->doctor_name;
                  $data['code_no'] = $value->code_no;
                  $data['file_no'] = $value->file_no;
                  $data['voucher_no'] = $value->voucher_no;
                  $data['invoice_id'] = $value->invoice_id;
                  $data['qty'] = $qty;
                  $data['unit_name'] = $value->unit_name;
                  $data['price'] = $value->price;
                  $data['total'] = $qty * $value->price;
                
                  if($sorting =='company_code'){
                    $needle[] = $data['company_code'];
                  } else if($sorting =='item_name'){
                    $needle[] = $data['item_name'];
                  } else if($sorting =='approved_date'){
                    $needle[] = $data['approved_date'];
                  } else if($sorting =='store_name'){
                    $needle[] = $data['store_name'];
                  } else if($sorting =='dept_name'){
                    $needle[] = $data['dept_name'];
                  } else if($sorting =='doctor_name'){
                    $needle[] = $data['doctor_name'];
                  } else if($sorting =='code_no'){
                    $needle[] = $data['code_no'];
                  } else if($sorting =='file_no'){
                    $needle[] = $data['file_no'];
                  } else if($sorting =='voucher_no'){
                    $needle[] = $data['voucher_no'];
                  } else if($sorting =='quantity'){
                    $needle[] = $data['qty'];
                  } else if($sorting =='price'){
                    $needle[] = $data['price'];
                  } else if($sorting =='total'){
                    $needle[] = $data['total'];
                  } else {
                    $needle[] = $data['id'];
                  }

                 $all_data[] = $data;
                }

                if($sorting !='' && !empty($all_data)){
                  if($direction =='DESC'){
                    $direction = SORT_DESC;
                  } else {              
                    $direction = SORT_ASC;
                  }
                  array_multisort($needle, $direction, $all_data);
                }

                foreach ($all_data as $value) 
                {
                  ?>
                   <tr class="<?php echo $class; ?>" onclick="onclick_change_bg('.detailsTable', this, 'cyan')">
                       <td><?php echo esc($value['company_code']);?></td>
                       <td><?php echo esc($value['item_name']);?></td>
                       <td><a href="javascript:void(0)" onclick="preview(this)" data-id="<?php echo esc($value['id']);?>"><?php echo esc($value['voucher_no']);?></a></td>
                       <td><?php echo esc($value['approved_date']);?></td>
                       <td><?php echo esc($value['store_name']);?></td>
                       <td><?php echo esc($value['dept_name']);?></td>
                       <td><?php echo esc($value['doctor_name']);?></td>
                       <td><?php echo esc($value['code_no']);?></td>
                       <td><?php echo esc($value['file_no']);?></td>
                       <td><?php if($value['consumed_by'] == 'service' && $value['invoice_id'] >0 ){ ?><a href="#" class="viewDetails" data-id="<?php echo 'SINV-'.$value['invoice_id']; ?>"><?php echo esc('SINV-'.$value['invoice_id']); ?></a><?php } ?></td>
                       <td class="text-right"><a href="javascript:void(0)" onclick="preview(this)" data-id="<?php echo esc($value['id']);?>"><?php echo esc(number_format($value['qty'], 2));?></a></td>
                       <td><?php echo esc($value['unit_name']);?></td>
                       <td class="text-right"><?php echo esc(number_format($value['price'], 2));?></td>
                       <td class="text-right"><a href="javascript:void(0)" onclick="preview(this)" data-id="<?php echo esc($value['id']);?>"><?php echo esc(number_format($value['qty']*$value['price'], 2));?></a></td>
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
                <th colspan="10" class="text-right"><?php echo get_phrases(['total']);?></th>
                <th class="text-right"><?php echo number_format($total_qty, 2);?></th>
                <th class="text-right"></th>
                <th class="text-right"></th>
                <th class="text-right"><?php echo number_format($total, 2);?></th>
            </tr>
        </tfoot>
    </table>
</div>
<div class="card-footer no-print">
  <?php if($hasPrintAccess){ ?>
    <button type="button" class="btn btn-info" onclick="printContent('printC')"><span class="fa fa-print"></span> <?php echo get_phrases(['print']);?></button>
  <?php } if($hasExportAccess){ ?>
    <button type="button" class="btn btn-purple export"><span class="fa fa-download"></span> <?php echo get_phrases(['export','excel']);?></button>
  <?php } ?>
</div>
