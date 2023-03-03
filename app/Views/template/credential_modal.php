<!-- Small modal -->
<div class="modal fade bd-example-modal-md" id="alertModal" tabindex="-1" role="dialog" aria-labelledby="alertModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-body">
                <div class="d-flex align-items-center justify-content-center text-center">
                    <div class="form-wrapper m-auto">
                        <div class="form-container my-4">
                            <div class="panel">
                                <div class="alert-custom alert-danger-custom mb-2">
                                    <strong><?php echo get_phrases(['credentials', 'required']);?> !</strong>
                                    <span><?php echo get_notify('long_time_no_activities_in_system');?></span>
                                </div>
                                <?php echo form_open('auth/activityLogin', 'class="activitiesAuth needs-validation" novalidate data="activityAuthCallBack"')?>
                                    <div class="form-group">
                                            <input type="text" class="form-control" id="username" name="username" placeholder="<?php echo get_phrases(['enter', 'user', 'name']);?>" value="<?php echo session('username');?>" required>
                                    </div>
                                    <div class="form-group">
                                            <input type="password" class="form-control" id="pass" name="password" placeholder="<?php echo get_phrases(['enter', 'password']);?>" required>
                                    </div>
                                    <button type="submit" class="btn btn-success btn-block"><?php echo get_phrases(['continue']);?> ...</button>
                                <?php echo form_close();?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>