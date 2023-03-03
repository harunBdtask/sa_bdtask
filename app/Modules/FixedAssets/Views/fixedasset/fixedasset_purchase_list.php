<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="card ">
            <div class="card-header py-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="fs-17 font-weight-600 mb-0"><?php echo lan('purchase_list')?></h6>
                                </div>
                                <div class="text-right">
                                 
                                 
                                </div>
                            </div>
                        </div>
            <div class="card-body">

                <div class="table">
                    <table class="table display table-bordered table-striped table-hover custom-table" id="example">
                        <thead>
                            <tr>
                            <th><?php echo lan('sl_no') ?></th>
                            <th><?php echo lan('asset_name') ?></th>
                            <th><?php echo lan('date') ?></th>
                            <th><?php echo lan('amount') ?></th>
                            <th><?php echo lan('payment_type') ?></th>
                            <th><?php echo lan('create_by') ?></th>
                           
                            </tr>
                        </thead>
                        <tbody>
                          <?php
                          $sl = 1;
                           if($purchase_list){?>
                            <?php foreach($purchase_list as $item){?>
                            <tr>
                              <td><?php echo $sl;?></td>
                              <td><?php echo $item->HeadName;?></td>
                              <td><?php echo $item->date;?></td>
                              <td><?php echo $item->amount;?></td>
                              <td><?php echo ($item->payment_type==1?'Cash payment':'Bank Payment ('.$item->bank_name.')');?></td>
                              <td><?php echo $item->create_by;?></td>
                             
                            </tr>
                          <?php $sl++;}?>
                          <?php }else{?>
                   <tr><td colspan="3" class="text-center"><b>No Data Found</b></td></tr>
                          <?php }?>
                        </tbody>
                         
                    </table>
                    
                </div>
            </div> 
        </div>
    </div>
</div>

  