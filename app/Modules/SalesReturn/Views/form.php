
<div class="row">
   <div class="col-sm-12">
      <div class="card">
         <div class="card-header py-2">
            <div class="d-flex justify-content-between align-items-center">
               <div>
                  <nav aria-label="breadcrumb" class="order-sm-last p-0">
                     <ol class="breadcrumb d-inline-flex font-weight-600 fs-17 bg-white mb-0 float-sm-left p-0">
                        <li class="breadcrumb-item"><a href="#"><?php echo $moduleTitle; ?></a></li>
                        <li class="breadcrumb-item active"><?php echo $title; ?></li>
                     </ol>
                  </nav>
               </div>
               <div class="text-right">
                  <?php if ($permission->module('do_list')->access()) { ?>
                     <a href="<?php echo base_url('sale/deliver_order/do_list'); ?>" class="btn btn-warning btn-sm mr-1 ml-2"><i class="fas fa-angle-double-left mr-1"></i><?php echo get_phrases(['return', 'list']); ?></a>
                  <?php } ?>
               </div>
            </div>
         </div>
         <div class="card-body">
            <?php echo form_open('return/sales_return/return_form', 'id="do_form"') ?>
            <div class="row form-group">
               <label for="challan_no" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['challan', 'no']) ?> <i class="text-danger">*</i></label>
               <div class="col-sm-4">
               <input type="text" name="challan_no" placeholder="<?php echo get_phrases(['challan','no']); ?>" class="form-control" id="challan_no" autocomplete="off" tabindex="1" required  value="<?= old('challan_no'); ?>">
               </div>
               <div class="col-sm-2">
                 <button type="submit" class="btn btn-success" tabindex="2"><?php echo get_phrases(['find']); ?></button>
               </div>
            </div>
    
            <?php echo form_close() ?>
         </div>
      </div>
   </div>
</div>
