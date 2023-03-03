<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="card ">
            <div class="card-header py-2">
                 <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <nav aria-label="breadcrumb" class="order-sm-last p-0">
                            <ol class="breadcrumb d-inline-flex font-weight-600 fs-17 bg-white mb-0 float-sm-left p-0">
                                <li class="breadcrumb-item"><a href="#"><?php echo $moduleTitle;?></a></li>
                                <li class="breadcrumb-item active"><?php echo $title;?></li>
                            </ol>
                        </nav>
                        
                    </div>
                    <div class="text-right">
             
                    </div>
                </div>
            </div>
            <div class="card-body">
               
                    <!-- home tab -->
                   
                        
                        <?php echo form_open_multipart('settings/update_referral','class="form-inner"') ?>
                            <?php echo form_hidden('id',$referal->id) ?>

                            <div class="form-group row">
                                <label for="f_h_percentage" class="col-sm-2 col-form-label"><?php echo get_phrases(['first','hand','percentage']) ?> <i class="text-danger">*</i></label>
                                <div class="col-sm-7">
                                    <input name="f_h_percentage" type="text" class="form-control" id="f_h_percentage" placeholder="<?php echo get_phrases(['first','hand','percentage']) ?>" value="<?php echo $referal->f_h_percentage ?>">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="s_h_percentage" class="col-sm-2 col-form-label"><?php echo get_phrases(['second','hand','percentage']) ?><i class="text-danger">*</i></label>
                                <div class="col-sm-7">
                                    <input name="s_h_percentage" type="text" class="form-control" id="s_h_percentage" placeholder="<?php echo get_phrases(['second','hand','percentage']) ?>"  value="<?php echo $referal->s_h_percentage ?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                 <label for="s_h_percentage" class="col-sm-2 col-form-label"></label>
                                 <div class="col-sm-7">
                             <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="percentage" name="commission_type" class="custom-control-input" value="1" <?php echo ($referal->commission_type == 1 ?'checked':'') ?>>
                                            <label class="custom-control-label" for="percentage">Percentage</label>
                        </div>
                         <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="fixed" name="commission_type" class="custom-control-input" value="2" <?php echo ($referal->commission_type == 2 ?'checked':'') ?>>
                                            <label class="custom-control-label" for="fixed">Fixed</label>
                        </div>
                    </div>

                        </div>  

                        
                            <div class="form-group text-center">
                                <button type="reset" class="btn btn-primary w-md m-b-5"><?php echo get_phrases(['reset']) ?></button>
                                <button type="submit" class="btn btn-success w-md m-b-5"><?php echo get_phrases(['update']) ?></button>
                            </div>
                        <?php echo form_close() ?>
                    
                
                
            </div> 
        </div>
    </div>
</div>


