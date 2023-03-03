<link href="<?php echo base_url()?>/assets/plugins/jquery-ui-1.12.1/jquery-ui.min.css" rel="stylesheet">
<div class="row">
    <div class="col-sm-12">
    <div class="card">
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
                  <?php echo form_open('account/accounts/checkprint')?>
                  <div class="row form-group">
               <label for="pay_to" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['pay', 'to']) ?> <i class="text-danger">*</i></label>
               <div class="col-sm-4">
                 <input type="text" name="payto" class="form-control" id="payto" placeholder="<?php echo get_phrases(['pay', 'to']) ?>" autocomplete="off">
               </div>
               <label for="date" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['date']) ?> <i class="text-danger">*</i></label>
               <div class="col-sm-4">
                  <input type="text" name="date" placeholder="<?php echo get_phrases(['date']); ?>" class="form-control datepicker" id="date" autocomplete="off" tabindex="2" required value="<?php echo date('Y-m-d') ?>">
               </div>
            </div>
            <div class="row form-group">
               <label for="amount" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['amount']) ?> <i class="text-danger">*</i></label>
               <div class="col-sm-4">
                 <input type="text" class="form-control" name="amounts" id="amountss" placeholder="<?php echo get_phrases(['amount']) ?>">
               </div>
               <div class="col-sm-4"><button type="submit" class="btn btn-success">Save</button></div>
            </div>
                  <?php echo form_close()?>
            </div>
    </div>
    </div>
</div>
<script src="<?php echo base_url()?>/assets/plugins/jquery-ui-1.12.1/jquery-ui.min.js" type="text/javascript"></script>
<script>
       $(document).ready(function() {
            $('.datepicker').datepicker({dateFormat: 'yy-mm-dd'});     
        });
</script>