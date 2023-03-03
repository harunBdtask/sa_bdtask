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
                <th width="15%"><?php echo get_phrases(['doctor', 'name']);?></th>
                <th width="5%"><?php echo get_phrases(['invoice','no']);?></th>
                <th width="10%"><?php echo get_phrases(['service','code']);?></th>
                <th width="15%"><?php echo get_phrases(['service', 'name']);?></th>
                <th width="5%"><?php echo get_phrases(['patient','file']);?></th>
                <th width="15%"><?php echo get_phrases(['patient','name']);?></th>
                <th width="10%"><?php echo get_phrases(['voucher','no']);?></th>
                <th width="10%" class="text-right"><?php echo get_phrases(['actual','cons.']);?></th>
                <th width="10%" class="text-right"><?php echo get_phrases(['default','cons.']);?></th>
                <th width="5%"><?php echo get_phrases(['status']);?></th>
            </tr>
        </thead>
        <tbody>
           <?php 
           $total = 0;
           $total_qty = 0;
           if(!empty($results)){
                foreach ($results as $value) { 
                  
                  /*$qty = $value->aqty - $value->return_qty;
                  if($qty <0 ){
                    $qty = 0;
                  }

                  $class = '';
                  if($value->return_qty >0 ){
                    $class = 'text-danger';
                  }

                  $total += $qty*$value->price;
                  $total_qty += $qty;*/

                  $data['id'] = $value->id;
                  $data['consumed_by'] = $value->consumed_by;

                  $data['doctor_name'] = $value->doctor_name;
                  $data['invoice_id'] = $value->invoice_id;
                  $data['code_no'] = $value->code_no;
                  $data['service_name'] = $value->service_name;
                  $data['file_no'] = $value->file_no;
                  $data['patient_name'] = $value->patient_name;
                  //$data['company_code'] = $value->company_code;
                  //$data['approved_date'] = $value->approved_date;
                  //$data['store_name'] = $value->store_name;
                  //$data['dept_name'] = $value->dept_name;
                  $data['voucher_no'] = $value->voucher_no;
                  $data['act_consumed'] = $value->act_consumed;
                  $data['def_consumed'] = $value->def_consumed;
                  $data['status'] = ($value->act_consumed > $value->def_consumed)?"excess":(($value->act_consumed < $value->def_consumed)?"short":"same");
                
                 $all_data[] = $data;
                }

                /*if($sorting !='' && !empty($all_data)){
                  if($direction =='DESC'){
                    $direction = SORT_DESC;
                  } else {              
                    $direction = SORT_ASC;
                  }
                  array_multisort($needle, $direction, $all_data);
                }*/

                foreach ($all_data as $value) 
                {
                  if($status ==''|| $status == $value['status']){
                  ?>
                   <tr onclick="onclick_change_bg('.detailsTable', this, 'cyan')">
                       <td><?php echo esc($value['doctor_name']);?></td>
                       <td><?php if($value['consumed_by'] == 'service' && $value['invoice_id'] >0 ){ ?><a href="#" class="viewDetails" data-id="<?php echo 'SINV-'.$value['invoice_id']; ?>"><?php echo esc('SINV-'.$value['invoice_id']); ?></a><?php } ?></td>
                       <td><?php echo esc($value['code_no']);?></td>
                       <td><?php echo esc($value['service_name']);?></td>
                       <td><?php echo esc($value['file_no']);?></td>
                       <td><?php echo esc($value['patient_name']);?></td>
                       <td><a href="javascript:void(0)" onclick="preview(this)" data-id="<?php echo esc($value['id']);?>"><?php echo esc($value['voucher_no']);?></a></td>
                       <td class="text-right"><a href="javascript:void(0)" onclick="preview(this)" data-id="<?php echo esc($value['id']);?>"><?php echo esc(number_format($value['act_consumed'], 2));?></a></td>
                       <td class="text-right"><a href="javascript:void(0)" onclick="preview(this)" data-id="<?php echo esc($value['id']);?>"><?php echo esc(number_format($value['def_consumed'], 2));?></a></td>
                       <td><?php echo esc($value['status']);?></td>
                   </tr>
            <?php }
              } 
          } else { ?>
                <tr>
                    <th colspan="10" class="text-center text-muted text-danger"><?php echo get_notify('data_is_not_available');?></th>
                </tr>
          <?php } ?>
        </tbody>
        <!-- <tfoot>
            <tr>
                <th colspan="10" class="text-right"><?php //echo get_phrases(['total']);?></th>
                <th class="text-right"><?php //echo number_format($total_qty, 2);?></th>
                <th class="text-right"></th>
                <th class="text-right"></th>
                <th class="text-right"><?php //echo number_format($total, 2);?></th>
            </tr>
        </tfoot> -->
    </table>
</div>
<div class="card-footer no-print">
  <?php if($hasPrintAccess){ ?>
    <button type="button" class="btn btn-info" onclick="printContent('printC')"><span class="fa fa-print"></span> <?php echo get_phrases(['print']);?></button>
  <?php } if($hasExportAccess){ ?>
    <!-- <button type="button" class="btn btn-purple export"><span class="fa fa-download"></span> <?php //echo get_phrases(['export','excel']);?></button> -->
  <?php } ?>
</div>
