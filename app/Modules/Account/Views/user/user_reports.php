<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="panel panel-bd lobidrag">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4>
                        <?php echo makeString(['user', 'wise', 'reports'])?>
                    </h4>
                </div>
            </div> 
            <div class="panel-body">
                <?= form_open_multipart('accounts/accounts/userReports') ?>
                <div class="row" id="">
                    <div class="col-sm-8">
                        <div class="form-group row">
                            <label for="date" class="col-sm-3 col-form-label"><?php echo makeString(['employee', 'name'])?> <i class="text-danger">*</i></label>
                            <div class="col-sm-9">
                                <?= form_dropdown('user_id', $userList, '', 'class="form-control"');?>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                          <div class="form-group text-left">
                            <button type="submit" class="btn btn-success w-md m-b-5"><?php echo display('find') ?></button>
                        </div>
                    </div>
                </div>
                <?php echo form_close() ?>
            </div>
        </div>
    </div>
</div>