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
           <?php 
           $total_in = 0;
           $total_out = 0;
           $i=1;
          if(!empty($results)){
            foreach($results as $row){
              if($row->trans_type=='in'){
                $total_in += $row->quantity;
              }
              if($row->trans_type=='out'){
                $total_out += $row->quantity;
              }
              if($i == 1){
                echo '<thead>
                        <tr class="bg-info text-white"><th>Current Stock: '.$row->stock.'</th>
                        <tr class="bg-secondary text-white">
                            <th width="10%" class="text-right">'.get_phrases(['in','qty']).'</th>
                            <th width="10%" class="text-right">'.get_phrases(['out','qty']).'</th>
                            <th width="5%">'.get_phrases(['base', 'unit']).'</th>
                            <th width="5%">'.get_phrases(['voucher', 'no']).'</th>
                            <th width="10%">'.get_phrases(['transaction', 'date']).'</th>
                            <th width="20%">'.get_phrases(['receive', 'from']).'</th>
                            <th width="20%">'.get_phrases(['transfer', 'to']).'</th>
                            <th width="5%">'.get_phrases(['batch', 'no']).'</th>
                            <th width="10%">'.get_phrases(['batch', 'avail','qty']).'</th>
                            <th width="5%">'.get_phrases(['batch', 'reference']).'</th>
                        </tr>
                    </thead>
                    <tbody>';
                $i++;
              }
                  ?>
                   <tr onclick="onclick_change_bg('.detailsTable', this, 'cyan')">
                       <td class="text-right"><?php echo ($row->trans_type=='in')?esc($row->quantity):'';?></td>            
                       <td class="text-right"><?php echo ($row->trans_type=='out')?esc($row->quantity):'';?></td>            
                       <td><?php echo esc($row->unit_name);?></td>            
                       <td><?php echo esc($row->voucher_no);?></td> 
                       <td><?php echo esc($row->transfer_date);?></td>     
                       <td><?php echo esc($row->receive_from);?></td>
                       <td><?php echo esc($row->transfer_to);?></td>      
                       <td><?php echo esc($row->batch_no);?></td>            
                       <td><?php echo esc($row->ref_qty);?></td>            
                       <td><?php echo esc($row->ref_no);?></td>            
                   </tr>
            <?php } 
            echo '</tbody>';
          } else { ?>
                <tr>
                    <th colspan="14" class="text-center text-muted text-danger"><?php echo get_notify('data_is_not_available');?></th>
                </tr>
          <?php } ?>
        
    </table>
</div>
<?php if($hasPrintAccess){ ?>
<div class="card-footer no-print">
    <button type="button" class="btn btn-info" onclick="printContent('printC')"><span class="fa fa-print"></span> <?php echo get_phrases(['print']);?></button>
</div><?php } ?>
