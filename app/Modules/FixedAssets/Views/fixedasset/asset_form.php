  
        <div class="row">
             <div class="col-md-12 col-lg-12">
                <div class="card">
        <div class="card-header py-2">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 font-weight-600 mb-0"><?php echo get_phrases(['add','fixedasset'])?></h6>
                </div>
                <div class="text-right">
                   <?php if($permission->method('fixedasset_list','read')->access()){?>  
                   <a href="<?php echo base_url('fixedasset/fixedasset_list')?>" class="btn btn-success btn-sm mr-1"><i class="fas fa-align-justify mr-1"></i><?php echo get_phrases(['fixedasset','list'])?></a>
                 <?php }?>
                  
                </div>
            </div>
        </div>
                 <div class="card-body">
  
   
      <?php echo form_open_multipart("fixedasset/add_fixedasset/".$assets->HeadCode) ?>            
                <?php echo form_hidden('id',$assets->HeadCode) ?>
                      <div class="form-group row">
                      <label for="asset_name" class="col-md-2 text-right col-form-label"><?php echo get_phrases(['parent','asset','name'])?> <i class="text-danger">  </i>:</label>
                    <div class="col-md-4">
                     <?php echo form_dropdown('parent_head',$asset_list,($assets?$assets->PHeadName:null),'class="form-control select2" id=""')?>
                    </div>
                    </div>

                    <div class="form-group row">
                      <label for="asset_name" class="col-md-2 text-right col-form-label"><?php echo get_phrases(['asset','name'])?> <i class="text-danger"> * </i>:</label>
                    <div class="col-md-4">
                      <input type="text" class="form-control" name="asset_name" id="asset_name" placeholder="<?php echo get_phrases(['asset','name'])?>" value="<?php echo $assets->HeadName?>">
                      <input type="hidden" name="old_name" value="<?php echo $assets->HeadName?>">
                    </div>
                    </div>

   <div class="form-group row"> 
   <div class="col-md-4"></div>      
   <div class="col-md-2">
                  <button type="submit"  class="btn btn-success form-control">
                    <?php echo (empty($assets->HeadCode)?get_phrases(['save']):get_phrases(['update'])) ?></button>
                  </div>
</div>
                <?php echo form_close();?>
                    </div>
                    </div>
                </div>
                    </div>

                 
                 