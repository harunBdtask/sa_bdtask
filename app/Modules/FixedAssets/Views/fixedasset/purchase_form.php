<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="card ">
            <div class="card-header py-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="fs-17 font-weight-600 mb-0"><?php echo get_phrases(['assets','purchase'])?></h6>
                                </div>
                                <div class="text-right">
                                    <?php if($permission->method('assets_purchase_list','create')->access()){?>  
                                   <a href="<?php echo base_url('fixedasset/assets_purchase_list')?>" class="btn btn-success btn-sm mr-1"><i class="fas fa-plus mr-1"></i><?php echo get_phrases(['purchase','list'])?></a>
                                 <?php }?>
                                </div>
                            </div>
                        </div>
            <div class="card-body">
                <?php echo  form_open('fixedasset/assets_purchase') ?>
                                <div class="row">
                          <div class="col-sm-12">
                                <div class="form-group row">
                                    <label for="date" class="col-sm-3 col-form-label"><?php echo get_phrases(['date']) ?>
                                        <i class="text-danger">*</i>
                                    </label>
                                    <div class="col-sm-8">
                                        <input type="text" name="dtpDate" id="dtpDate" class="form-control datepicker" value="<?php  echo date('Y-m-d');?>">

                                    </div>
                                </div>
                            </div>
                             <div class="col-sm-12" id="payment_from_1">
                                <div class="form-group row">
                                    <label for="expense_type" class="col-sm-3 col-form-label"><?php
                                        echo get_phrases(['fixed','assets']);
                                        ?> <i class="text-danger">*</i></label>
                                    <div class="col-sm-8">
                                     
                                        <?php echo form_dropdown('asset_name',$asset_list,null,'class="form-control select2"')?>
                                    </div>
                                 
                                </div>
                            </div>

                          <div class="col-sm-12">
                                <div class="form-group row">
                                    <label for="supplier_id" class="col-sm-3 col-form-label"><?php echo get_phrases(['supplier']) ?>
                                        <i class="text-danger">*</i>
                                    </label>
                                    <div class="col-sm-8">
                                       <?php echo  form_dropdown('supplier_id',$supplier_list,null, 'class="form-control select2" id="supplier_id"') ?> 

                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-12" id="payment_from_1">
                                <div class="form-group row">
                                    <label for="payment_type" class="col-sm-3 col-form-label"><?php
                                        echo get_phrases(['payment','type']);
                                        ?> <i class="text-danger">*</i></label>
                                    <div class="col-sm-8">
                                        <select name="paytype" class="form-control select2"  id="paytype" onchange="bank_payment(this.value)" >
                                            <option value="">Select Payment Option</option>
                                            <option value="1">Cash Payment</option>
                                            <option value="2">Bank Payment</option>
                                        </select>
                                    </div>
                                 
                                </div>
                            </div>
                                <div class="col-sm-12 bank_div" id="bank_div">
                                <div class="form-group row">
                                     <label for="bank" class="col-sm-3 text-right bank_div col-form-label"><?php echo get_phrases(['bank'])?><i class="text-danger">*</i></label>
                    <div class="col-sm-8">
                       
                            
                         <?php echo  form_dropdown('bank_id',$bank_list,null, 'class="form-control select2" id="bank_id"') ?> 
                        
                       
                    </div>
                                 
                                </div>
                            </div>
                        <div class="col-sm-12" id="payment_from_1">
                         <div class="form-group row">
                        <label for="date" class="col-sm-3 col-form-label"><?php echo get_phrases(['total','amount'])?><i class="text-danger">*</i></label>
                        <div class="col-sm-8">
                             <input type="text" name="amount" id="amount" class="form-control valid_number" >
                            
                        </div>
                    </div> 
                    </div>
           
                        <div class="col-sm-12" id="">
                         <div class="form-group row">
                        <label for="paid_amount" class="col-sm-3 col-form-label"><?php echo get_phrases(['paid','amount'])?><i class="text-danger"></i></label>
                        <div class="col-sm-8">
                             <input type="text" name="paid_amount" id="paid_amount" class="form-control valid_number" >
                            
                        </div>
                    </div> 
                    </div>
                           
                            <div class="col-sm-12 text-right">
                               <div class="form-group row">
                                <label class="col-sm-3"></label>
                                <div class="col-sm-8">
                                <input type="submit" id="add_receive" class="btn btn-success btn-large" name="save" value="<?php echo get_phrases(['save']) ?>" tabindex="9"/>
                               </div>
                            </div>
                        </div>
                  <?php echo form_close() ?>
                
            </div> 
        </div>
    </div>
</div>
</div>
