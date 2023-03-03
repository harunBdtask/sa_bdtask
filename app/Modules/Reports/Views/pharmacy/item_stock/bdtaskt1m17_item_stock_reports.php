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
                <th width="15%"><?php echo get_phrases(['item', 'name']);?></th>
                <th width="5%" class="text-right"><?php echo get_phrases(['no','of','carton']);?></th>
                <th width="5%" class="text-right"><?php echo get_phrases(['carton','qty']);?></th>
                <th width="5%" class="text-right"><?php echo get_phrases(['no','of','box']);?></th>
                <th width="10%" class="text-right"><?php echo get_phrases(['box','qty']);?></th>
                <th width="10%" class="text-right"><?php echo get_phrases(['in','qty']);?></th>
                <th width="10%" class="text-right"><?php echo get_phrases(['out','qty']);?></th>
                <th width="10%" class="text-right"><?php echo get_phrases(['stock','qty']);?></th>
                <th width="5%"><?php echo get_phrases(['base', 'unit']);?></th>
                <th width="10%" class="text-right"><?php echo get_phrases(['avg', 'cost']);?></th>
                <th width="10%" class="text-right"><?php echo get_phrases(['total','amount']);?></th>
            </tr>
        </thead>
        <tbody>
           <?php 
           $total = 0;
           if(!empty($results)){
                foreach ($results as $value) { 
                  $total += $value->stock*$value->price;
                  
                  ?>
                   <tr onclick="onclick_change_bg('.detailsTable', this, 'cyan')">
                       <td><?php echo esc($value->company_code);?></td>
                       <td><?php echo esc($value->item_name);?></td>
                       <td class="text-right"><?php echo esc(number_format(($value->carton_qty && $value->box_qty)?($value->stock/($value->carton_qty*$value->box_qty)):0,2));?></td>
                       <td class="text-right"><?php echo esc(number_format($value->carton_qty));?></td>
                       <td class="text-right"><?php echo esc(number_format(($value->box_qty)?($value->stock/$value->box_qty):0, 2));?></td>
                       <td class="text-right"><?php echo esc(number_format($value->box_qty, 2));?></td>
                       <td class="text-right"><?php echo esc(number_format($value->in_qty-$value->return_qty, 2));?></td>
                       <td class="text-right"><?php echo esc(number_format($value->out_qty, 2));?></td>
                       <td class="text-right"><a href="#" onclick="preview(this)" data-id="<?php echo esc($value->item_id);?>" warehouse-id="<?php echo esc($value->store_id);?>"><?php echo esc(number_format($value->stock, 2));?></a></td>
                       <td><?php echo esc($value->unit_name);?></td>
                       <td class="text-right"><?php echo esc(number_format($value->price, 2));?></td>
                       <td class="text-right"><a href="#" onclick="preview(this)" data-id="<?php echo esc($value->item_id);?>" warehouse-id="<?php echo esc($value->store_id);?>"><?php echo esc(number_format($value->stock*$value->price, 2));?></a></td>
                   </tr>
            <?php } 
          } else { ?>
                <tr>
                    <th colspan="12" class="text-center text-muted text-danger"><?php echo get_notify('data_is_not_available');?></th>
                </tr>
          <?php } ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="11" class="text-right"><?php echo get_phrases(['total']);?></th>
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