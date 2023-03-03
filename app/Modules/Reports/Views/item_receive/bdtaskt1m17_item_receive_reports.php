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
                <th width="10%"><?php echo get_phrases(['supplier', 'name']);?></th>
                <th width="10%"><?php echo get_phrases(['receive', 'date']);?></th>
                <th width="5%" class="text-right"><?php echo get_phrases(['carton']);?></th>
                <th width="5%" class="text-right"><?php echo get_phrases(['box']);?></th>
                <th width="5%" class="text-right"><?php echo get_phrases(['box','qty']);?></th>
                <th width="10%" class="text-right"><?php echo get_phrases(['quantity']);?></th>
                <th width="5%"><?php echo get_phrases(['base','unit']);?></th>
                <th width="10%" class="text-right"><?php echo get_phrases(['price']);?></th>
                <th width="10%" class="text-right"><?php echo get_phrases(['total','amount']);?></th>
            </tr>
        </thead>
        <tbody>
           <?php 
           $total = 0;
           if(!empty($results)){
                foreach ($results as $value) { 
                  $total += $value->qty*$value->price;
                  
                  ?>
                   <tr onclick="onclick_change_bg('.detailsTable', this, 'cyan')">
                       <td><?php echo esc($value->company_code);?></td>
                       <td><?php echo esc($value->item_name);?></td>
                       <td><?php echo esc($value->supplier_name);?></td>
                       <td><?php echo esc($value->approved_date);?></td>
                       <td class="text-right"><?php echo esc(number_format($value->carton));?></td>
                       <td class="text-right"><?php echo esc(number_format($value->box));?></td>
                       <td class="text-right"><?php echo esc(number_format($value->box_qty, 2));?></td>
                       <td class="text-right"><a href="#" onclick="preview(this)" purchase-id="<?php echo esc($value->purchase_id);?>"><?php echo esc(number_format($value->qty, 2));?></a></td>
                       <td><?php echo esc($value->unit_name);?></td>
                       <td class="text-right"><?php echo esc(number_format($value->price, 2));?></td>
                       <td class="text-right"><a href="#" onclick="preview(this)" purchase-id="<?php echo esc($value->purchase_id);?>"><?php echo esc(number_format($value->qty*$value->price, 2));?></a></td>
                   </tr>
            <?php } 
          } else { ?>
                <tr>
                    <th colspan="11" class="text-center text-muted text-danger"><?php echo get_notify('data_is_not_available');?></th>
                </tr>
          <?php } ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="10" class="text-right"><?php echo get_phrases(['total']);?></th>
                <th class="text-right"><?php echo number_format($total, 2);?></th>
            </tr>
        </tfoot>
    </table>
</div>
<?php if($hasPrintAccess){ ?>
<div class="card-footer no-print">
    <button type="button" class="btn btn-info" onclick="printContent('printC')"><span class="fa fa-print"></span> <?php echo get_phrases(['print']);?></button>
</div><?php } ?>
