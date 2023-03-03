<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="card ">
            <div class="card-header">
                <ul class="nav nav-pills" id="pills-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="settings-home-tab" data-toggle="pill" href="#settings-home" role="tab" aria-controls="settings-home" aria-selected="true"><?php echo get_phrases(['application']);?></a>
                    </li>
                    
                    <!-- <li class="nav-item">
                        <a class="nav-link" id="settings-payment-tab" data-toggle="pill" href="#settings-payment" role="tab" aria-controls="settings-payment" aria-selected="false"><?php //echo get_phrases(['payment', 'gateway']);?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="settings-captcha-tab" data-toggle="pill" href="#settings-captcha" role="tab" aria-controls="settings-captcha" aria-selected="false"><?php //echo get_phrases(['google', 'recaptcha']);?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="settings-idCard-tab" data-toggle="pill" href="#settings-idCard" role="tab" aria-controls="settings-idCard" aria-selected="false"><?php //echo get_phrases(['id', 'card']);?></a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" id="financial-type-tab" data-toggle="pill" href="#financial-type" role="tab" aria-controls="financial-type" aria-selected="false"><?php //echo get_phrases(['financial', 'type']);?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="settings-mail-tab" data-toggle="pill" href="#settings-mail" role="tab" aria-controls="settings-mail" aria-selected="false"><?php //echo get_phrases(['mail']);?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="settings-frontend-tab" data-toggle="pill" href="#settings-frontend" role="tab" aria-controls="settings-frontend" aria-selected="false"><?php //echo get_phrases(['frontend']);?></a>
                    </li> -->
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="pills-tabContent">
                    <!-- home tab -->
                    <div class="tab-pane fade show active" id="settings-home" role="tabpanel" aria-labelledby="settings-home-tab">
                        
                        <?php echo form_open_multipart('settings/add','class="form-inner"') ?>
                            <?php echo form_hidden('id',$setting->id) ?>

                            <div class="form-group row">
                                <label for="title" class="col-sm-2 col-form-label"><?php echo get_phrases(['application','title']) ?> <i class="text-danger">*</i></label>
                                <div class="col-sm-7">
                                    <input name="title" type="text" class="form-control" id="title" placeholder="<?php echo get_phrases(['application','title']) ?>" value="<?php echo $setting->title ?>">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="address" class="col-sm-2 col-form-label"><?php echo get_phrases(['address']) ?></label>
                                <div class="col-sm-7">
                                    <input name="address" type="text" class="form-control" id="address" placeholder="<?php echo get_phrases(['address']) ?>"  value="<?php echo $setting->address ?>">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="email" class="col-sm-2 col-form-label"><?php echo get_phrases(['email'])?></label>
                                <div class="col-sm-7">
                                    <input name="email" type="text" class="form-control" id="email" placeholder="<?php echo get_phrases(['email'])?>"  value="<?php echo $setting->email ?>">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="phone" class="col-sm-2 col-form-label"><?php echo get_phrases(['phone']) ?></label>
                                <div class="col-sm-7">
                                    <input name="phone" type="text" class="form-control" id="phone" placeholder="<?php echo get_phrases(['phone']) ?>"  value="<?php echo $setting->phone ?>" >
                                </div>
                            </div>
                             <?php if(!empty($setting->favicon)) {  ?>
                            <div class="form-group row">
                                <label for="faviconPreview" class="col-sm-2 col-form-label"></label>
                                <div class="col-sm-7">
                                    <img src="<?php echo base_url().$setting->favicon ?>" alt="Favicon" class="img-thumbnail" height="70" width="70"/>
                                </div>
                            </div>
                            <?php } ?>

                            <div class="form-group row">
                                <label for="favicon" class="col-sm-2 col-form-label"><?php echo get_phrases(['favicon']) ?> </label>
                                <div class="col-sm-7">
                                     <div class="custom-file">
                                        <input type="file" name="favicon" class="custom-file-input" id="favicon" accept=".png, .jpg, .jpeg, .gif">
                                        <label class="custom-file-label" for="favicon"><?php echo get_phrases(['please', 'choose', 'file']);?></label>
                                    </div>
                                    <input type="hidden" name="old_favicon" value="<?php echo $setting->favicon ?>">
                                </div>
                            </div>


                            <!-- if setting logo is already uploaded -->
                            <?php if(!empty($setting->logo)) {  ?>
                            <div class="form-group row">
                                <label for="logoPreview" class="col-sm-2 col-form-label"></label>
                                <div class="col-sm-7">
                                    <img src="<?php echo base_url().$setting->logo ?>" alt="Picture" class="img-thumbnail" height="70" width="70"/>
                                </div>
                            </div>
                            <?php } ?>
                              <div class="form-group row">
                                <label for="logo" class="col-sm-2 col-form-label"><?php echo get_phrases(['logo']) ?></label>
                                <div class="col-sm-7">
                                    <div class="custom-file">
                                        <input type="file" name="logo" class="custom-file-input" id="logo" accept=".png, .jpg, .jpeg, .gif">
                                        <label class="custom-file-label" for="logo"><?php echo get_phrases(['please', 'choose', 'file']);?></label>
                                    </div>
                                    <input type="hidden" name="old_logo" value="<?php echo $setting->logo ?>">
                                </div>
                            </div>

                            <!-- if setting admin_logo is already uploaded -->
                            <?php if(!empty($setting->admin_logo)) {  ?>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label"></label>
                                <div class="col-sm-7">
                                    <img src="<?php echo base_url().$setting->admin_logo ?>" alt="Picture" class="img-thumbnail" height="70" width="70"/>
                                </div>
                            </div>
                            <?php } ?>
                              <div class="form-group row">
                                <label class="col-sm-2 col-form-label"><?php echo get_phrases(['admin','logo']) ?></label>
                                <div class="col-sm-7">
                                    <div class="custom-file">
                                        <input type="file" name="admin_logo" class="custom-file-input" accept=".png, .jpg, .jpeg, .gif">
                                        <label class="custom-file-label"><?php echo get_phrases(['please', 'choose', 'file']);?></label>
                                    </div>
                                    <input type="hidden" name="old_admin_logo" value="<?php echo $setting->admin_logo ?>">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="language" class="col-sm-2 col-form-label"><?php echo get_phrases(['language']) ?></label>
                                <div class="col-sm-7">
                                    <?php echo  form_dropdown('language',$languageList,$setting->language, 'class="form-control"') ?> 
                                </div>
                            </div> 
                            
                            <div class="form-group row">
                                <label for="language" class="col-sm-2 col-form-label"><?php echo get_phrases(['application', 'align']) ?></label>
                                <div class="col-sm-7">
                                    <?php
                                    $align = array('' => '', 'left-to-right'=>'Left To Right', 'right-to-left'=>'Right To Left');
                                     echo form_dropdown('site_align',$align,$setting->site_align, 'class="form-control"');
                                    ?> 
                                </div>
                            </div> 
                            <div class="form-group row">
                                <label for="currency_name" class="col-sm-2 col-form-label"><?php echo get_phrases(['currency', 'name'])?></label>
                                <div class="col-sm-7">
                                    <input name="currency_name" type="text" class="form-control" id="currency_name" placeholder="<?php echo get_phrases(['currency','name'])?>"  value="<?php echo $setting->currency ?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="currency_symbol" class="col-sm-2 col-form-label"><?php echo get_phrases(['currency', 'symbol'])?></label>
                                <div class="col-sm-7">
                                    <input name="currency_symbol" type="text" class="form-control" id="currency_symbol" placeholder="<?php echo get_phrases(['currency', 'symbol'])?>"  value="<?php echo $setting->currency_symbol ?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="currency_position" class="col-sm-2 col-form-label"><?php echo get_phrases(['currency', 'position'])?></label>
                                <div class="col-sm-7">
                                    <?php
                                    $currency_position = array('' => '', 'left'=>'Left', 'left-space'=>'Left with space', 'right'=>'Right', 'right-space'=>'Right with space');
                                     echo form_dropdown('currency_position',$currency_position,$setting->currency_position, 'class="form-control"'); 
                                    ?> 
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="time_zone" class="col-sm-2 col-form-label"><?php echo get_phrases(['time', 'zone'])?></label>
                                <div class="col-sm-7">
                                    <select id="time_zone" name="time_zone" class="form-control">
                                        <option value=""></option>
                                        <?php foreach (timezone_identifiers_list() as $value) { ?>
                                            <option value="<?php echo $value ?>" <?php echo (($setting->time_zone==$value)?'selected':null) ?>><?php echo $value ?></option>";
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="voucher_notes" class="col-sm-2 col-form-label"><?php echo get_phrases(['voucher','notes']) ?></label>
                                <div class="col-sm-7">
                                    <textarea name="voucher_notes" class="form-control"  placeholder="Footer Text" maxlength="150" rows="3"><?php echo $setting->voucher_notes ?></textarea>
                                </div>
                            </div>   

                            <div class="form-group row">
                                <label for="default_vat" class="col-sm-2 col-form-label"><?php echo get_phrases(['default', 'vat'])?></label>
                                <div class="col-sm-7">
                                    <input name="default_vat" type="text" class="form-control" id="default_vat" placeholder="<?php echo get_phrases(['default', 'vat'])?>"  value="<?php echo $setting->default_vat ?>">
                                </div>
                            </div>


                            <div class="form-group row">
                                <label for="footer_text" class="col-sm-2 col-form-label"><?php echo get_phrases(['footer','text']) ?></label>
                                <div class="col-sm-7">
                                    <textarea name="footer_text" class="form-control"  placeholder="Footer Text" maxlength="150" rows="3"><?php echo $setting->footer_text ?></textarea>
                                </div>
                            </div>  

                            <div class="form-group text-center">
                                <button type="reset" class="btn btn-primary w-md m-b-5"><?php echo get_phrases(['reset']) ?></button>
                                <button type="submit" class="btn btn-success w-md m-b-5"><?php echo get_phrases(['save']) ?></button>
                            </div>
                        <?php echo form_close() ?>
                    </div>
                   
                    <!-- payment gateway -->
                    <div class="tab-pane fade" id="settings-payment" role="tabpanel" aria-labelledby="settings-payment-tab">
                    </div>
                    <!-- google recaptcha -->
                    <div class="tab-pane fade" id="settings-captcha" role="tabpanel" aria-labelledby="settings-captcha-tab">
                            <div class="row mb-3">
                                <div class="col-sm-10 offset-sm-2">
                                    <a href="" class="btn btn-info custool" title="<?php echo get_notify('recaptcha');?>"><?php echo get_phrases(['visit', 'google', 'recaptcha']);?></a>
                                </div>
                            </div>
                         <?php echo form_open_multipart('settings/recaptcha','class="form-inner needs-validation" novalidate="" data="reCCallBackData" id="recaptchaForm"') ?>
                            <?php echo form_hidden('recaptch_id',$reCaptcha->id) ?>

                            <div class="form-group row">
                                <label for="site_key" class="col-sm-2 col-form-label"><?php echo get_phrases(['site', 'key'])?> <i class="text-danger">*</i></label>
                                <div class="col-sm-7">
                                    <input name="site_key" type="text" class="form-control" id="site_key" placeholder="<?php echo get_phrases(['site', 'key'])?>"  value="<?php echo $reCaptcha->site_key ?>" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="secret_key" class="col-sm-2 col-form-label"><?php echo get_phrases(['secret', 'key']) ?> <i class="text-danger">*</i></label>
                                <div class="col-sm-7">
                                    <input name="secret_key" type="text" class="form-control" id="secret_key" placeholder="<?php echo get_phrases(['secret', 'key']) ?>"  value="<?php echo $reCaptcha->secret_key ?>" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="visibility" class="col-sm-2 col-form-label"><?php echo get_phrases(['visibility']) ?> <i class="text-danger">*</i></label>
                                <div class="col-sm-2">
                                    <div class="checkbox checkbox-success">
                                        <input class="reCp" name="status" id="rec_on" type="checkbox" value="1" <?php echo $reCaptcha->status==1?'checked':'';?>>
                                        <label for="rec_on"><?php echo get_phrases(['on']);?></label>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="checkbox checkbox-success">
                                        <input class="reCp" id="rec_off" type="checkbox" value="0" <?php echo $reCaptcha->status==0?'checked':'';?>>
                                        <label name="status" for="rec_off"><?php echo get_phrases(['off']);?></label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-sm-10 offset-sm-2">
                                    <button type="reset" class="btn btn-primary w-md m-b-5"><?php echo get_phrases(['reset']) ?></button>
                                    <button type="submit" class="btn btn-success w-md m-b-5"><?php echo get_phrases(['update']) ?></button>
                                </div>
                            </div>
                        <?php echo form_close() ?>
                    </div>
                    <!-- id card -->
                    <div class="tab-pane fade" id="settings-idCard" role="tabpanel" aria-labelledby="settings-idCard-tab">
                        <?php echo form_open_multipart('settings/idCard','class="form-inner needs-validation" novalidate="" data="cardCallBackData" id="idCardForm"') ?>
                            <?php echo form_hidden('card_id',$cardinfo->id) ?>

                            <div class="form-group row">
                                <label for="emp_instruct" class="col-sm-2 col-form-label"><?php echo get_phrases(['employee', 'instruction'])?> <i class="fa fa-info-circle custool text-info" title="Max 100 character"></i></label>
                                <div class="col-sm-7">
                                    <input name="emp_instruct" type="text" class="form-control" id="emp_instruct" placeholder="<?php echo get_phrases(['employee', 'card', 'instruction'])?>"  value="<?php echo $cardinfo->emp_instruction ?>" maxlength="100" required>
                                </div>
                            </div>

                            <?php if(!empty($cardinfo->emp_logo)) {  ?>
                            <div class="form-group row">
                                <label for="faviconPreview" class="col-sm-2 col-form-label"></label>
                                <div class="col-sm-7">
                                    <img src="<?php echo base_url().$cardinfo->emp_logo ?>" alt="Favicon" class="img-thumbnail" height="70" width="70"/>
                                </div>
                            </div>
                            <?php } ?>

                            <div class="form-group row">
                                <label for="emp_logo" class="col-sm-2 col-form-label"><?php echo get_phrases(['employee', 'logo']) ?> <i class="fa fa-info-circle custool text-info" title="300x20 pixels"></i></label>
                                <div class="col-sm-7">
                                    <div class="custom-file">
                                        <input type="file" name="emp_logo" class="custom-file-input" id="emp_logo" accept=".png, .jpg, .jpeg, .gif">
                                        <label class="custom-file-label" for="picture"><?php echo get_phrases(['please', 'choose', 'file']);?></label>
                                    </div>
                                    <input type="hidden" name="old_emp_logo" value="<?php echo $cardinfo->emp_logo ?>">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="pa_instruct" class="col-sm-2 col-form-label"><?php echo get_phrases(['patient', 'instruction']) ?> <i class="fa fa-info-circle custool text-info" title="Max 75 character"></i></label>
                                <div class="col-sm-7">
                                    <input name="pa_instruct" type="text" class="form-control" id="pa_instruct" placeholder="<?php echo get_phrases(['patient', 'card', 'instruction']) ?>"  value="<?php echo $cardinfo->pa_instruction ?>" maxlength="75" required>
                                </div>
                            </div>
                            
                            <!-- if setting logo is already uploaded -->
                            <?php if(!empty($cardinfo->pa_logo)) {  ?>
                            <div class="form-group row">
                                <label for="logoPreview" class="col-sm-2 col-form-label"></label>
                                <div class="col-sm-7">
                                    <img src="<?php echo base_url().$cardinfo->pa_logo ?>" alt="Picture" class="img-thumbnail" height="70" width="70"/>
                                </div>
                            </div>
                            <?php } ?>
                              <div class="form-group row">
                                <label for="pa_logo" class="col-sm-2 col-form-label"><?php echo get_phrases(['patient', 'logo']) ?> <i class="fa fa-info-circle custool text-info" title="150x100 pixels"></i></label>
                                <div class="col-sm-7">
                                    <div class="custom-file">
                                        <input type="file" name="pa_logo" class="custom-file-input" id="pa_logo" accept=".png, .jpg, .jpeg, .gif">
                                        <label class="custom-file-label" for="picture"><?php echo get_phrases(['please', 'choose', 'file']);?></label>
                                    </div>
                                    <input type="hidden" name="old_pa_logo" value="<?php echo $cardinfo->pa_logo ?>">
                                </div>
                            </div>

                            <!-- if setting logo is already uploaded -->
                            <?php if(!empty($cardinfo->signature)) {  ?>
                            <div class="form-group row">
                                <label for="logoPreview" class="col-sm-2 col-form-label"></label>
                                <div class="col-sm-7">
                                    <img src="<?php echo base_url().$cardinfo->signature ?>" alt="Picture" class="img-thumbnail" height="70" width="70"/>
                                </div>
                            </div>
                            <?php } ?>
                              <div class="form-group row">
                                <label for="signature" class="col-sm-2 col-form-label"><?php echo get_phrases(['signature']) ?> <i class="fa fa-info-circle custool text-info" title="100x30 pixels"></i></label>
                                <div class="col-sm-7">
                                    <div class="custom-file">
                                        <input type="file" name="signature" class="custom-file-input" id="signature" accept=".png, .jpg, .jpeg, .gif">
                                        <label class="custom-file-label" for="picture"><?php echo get_phrases(['please', 'choose', 'signature']);?></label>
                                    </div>
                                    <input type="hidden" name="old_signature" value="<?php echo $cardinfo->signature ?>">
                                </div>
                            </div>

                            <div class="form-group text-center">
                                <button type="reset" class="btn btn-primary w-md m-b-5"><?php echo get_phrases(['reset']) ?></button>
                                <button type="submit" class="btn btn-success w-md m-b-5"><?php echo get_phrases(['update']) ?></button>
                            </div>
                        <?php echo form_close() ?>
                    </div>
                    <!-- SMS gateway -->
                    <div class="tab-pane fade" id="financial-type" role="tabpanel" aria-labelledby="financial-type-tab">
                        <div class="row ml-3 mb-2">
                            <button class="btn btn-success btn-sm btnC addFinancial"><i class="fa fa-plus"></i> <?php echo get_phrases(['add', 'new']);?></button>
                        </div>
                         <div class="row">
                            <div class="col-12">
                                <table class="table table-bordered table-sm table-hover-custom">
                                    <thead>
                                        <tr>
                                            <th><?php echo get_phrases(['branch', 'name']);?></th>
                                            <th><?php echo get_phrases(['type', 'name', 'english']);?></th>
                                            <th><?php echo get_phrases(['type', 'name', 'arabic']);?></th>
                                            <th><?php echo get_phrases(['amount', 'range']);?></th>
                                            <th><?php echo get_phrases(['action']);?></th>
                                        </tr>
                                    </thead>
                                    <tbody id="showFinanTypes">
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                     <!-- mail -->
                    <div class="tab-pane fade" id="settings-mail" role="tabpanel" aria-labelledby="settings-mail-tab">
                    </div>
                     <!-- Frontend -->
                    <div class="tab-pane fade" id="settings-frontend" role="tabpanel" aria-labelledby="settings-frontend-tab">
                    </div>
                </div>
            </div> 
        </div>
    </div>
</div>

<!-- add gallery modal -->
<div class="modal fade" id="addFinancialModal" tabindex="-1" role="dialog" aria-labelledby="moduleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="addFinancialLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
            <?php echo form_open_multipart('settings/addFinancialType', 'id="typeForm" class="needs-validation"  novalidate="" data="typeCallBackData"');?>
            <div class="modal-body">
                <input type="hidden" name="financial_type_id" id="financial_type_id">
                <input type="hidden" name="action" id="action">
                 <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <div class="form-group">
                            <label class="font-weight-600"><?php echo get_phrases(['branch', 'name']);?> <i class="text-danger">*</i></label>
                            <?php 
                             echo form_dropdown('branch_id','','','class="custom-select form-control" id="branch_id" required="required"');?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <div class="form-group">
                            <label class="font-weight-600"><?php echo get_phrases(['type', 'name', 'english']);?> <i class="text-danger">*</i></label>
                            <input type="text" name="type_english" class="form-control" id="type_english" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-8 col-sm-12">
                        <div class="form-group">
                            <label class="font-weight-600"><?php echo get_phrases(['type', 'name', 'arabic']);?></label>
                            <input type="text" name="type_arabic" class="form-control" id="type_arabic">
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <label class="font-weight-600"><?php echo get_phrases(['text', 'color']);?> <i class="text-danger">*</i></label>
                        <input type="color" name="type_color" class="form-control form-control-color" id="type_color" value="#37a000" title="Choose your color" required="">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label class="font-weight-600"><?php echo get_phrases(['start', 'amount']);?> <i class="text-danger">*</i></label>
                            <input type="text" name="start_amount" class="form-control" id="start_amount" required>
                        </div>
                    </div>
                     <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label class="font-weight-600"><?php echo get_phrases(['end', 'amount']);?> <i class="text-danger">*</i></label>
                            <input type="text" name="end_amount" class="form-control" id="end_amount" required>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo get_phrases(['close']);?></button>
                <button type="submit" class="btn btn-success saveBtnType"></button>
            </div>
            <?php echo form_close();?>
        </div>
    </div>
</div>
<script type="text/javascript">
    var showCallBackData = function(){
        $('.basicAjaxForm').removeClass('was-validated');
    }
    var cardCallBackData = function(){
        $('#idCardForm').removeClass('was-validated');
    }

    var reCCallBackData =function(){
        $('#recaptchaForm').removeClass('was-validated');
    }
    var typeCallBackData = function(){
        $('#addFinancialModal').modal('hide');
        get_financial_type();
        $('#typeForm').removeClass('was-validated');
    }

    function get_financial_type(){
        preloader_ajax();
        var submit_url = _baseURL+"settings/financialTypeList";
        $.ajax({
            type: 'POST',
            url: submit_url,
            data: {'csrf_stream_name':csrf_val},
            dataType: 'JSON',
            success: function(res) {
               $('#showFinanTypes').html('');
               $('#showFinanTypes').html(res.info);
            },error: function() {

            }
        });
    }

    $(document).ready(function(){
        "use strict";
        $('option:first-child').val('').trigger('change');

        var gender_html = '<tr><td><div class="input-group"><input type="text" name="gender[]" class="form-control" placeholder="<?php echo get_phrases(['enter', 'gender','name']); ?>"><div class="input-group-append"><button class="btn btn-danger removeBtn" type="button"><i class="fa fa-minus"></i></button></div></div></td></tr>'

        $('body').on('click', '.gAddBtn', function() {
            $("#genderBody").append(gender_html); 

        });

         var marital_html = '<tr><td><div class="input-group"><input type="text" name="marital[]" class="form-control" placeholder="<?php echo get_phrases(['enter', 'marital','name']); ?>"><div class="input-group-append"><button class="btn btn-danger removeBtn" type="button"><i class="fa fa-minus"></i></button></div></div></td></tr>'

        $('body').on('click', '.mAddBtn', function() {
            $("#maritalBody").append(marital_html); 

        });

         var blood_html = '<tr><td><div class="input-group"><input type="text" name="blood[]" class="form-control" placeholder="<?php echo get_phrases(['enter', 'blood','name']); ?>"><div class="input-group-append"><button class="btn btn-danger removeBtn" type="button"><i class="fa fa-minus"></i></button></div></div></td></tr>'

        $('body').on('click', '.bAddBtn', function() {
            $("#bloodBody").append(blood_html); 

        });


        $('body').on('click', '.removeBtn', function() {
            $(this).parent().parent().parent().remove();
        });

        $('#favicon, #logo, #emp_logo, #pa_logo, #signature').on('change', function () {
            var fileExtension = ['png', 'jpg', 'jpeg'];
            imgPreview($(this), fileExtension);
        });  

        // branch list
        $.ajax({
            type:'GET',
            url: _baseURL+'auth/branchList',
            dataType: 'json',
            data:{'csrf_stream_name':csrf_val},
        }).done(function(data) {
            $("#branch_id").select2({
                placeholder: '<?php echo get_phrases(['select', 'branch', 'name']);?>',
                data: data
            });
        });

        // recaptcha on off
        $('input.reCp').on('change', function() {
            $('input.reCp').not(this).prop('checked', false);  
        });

        $('#financial-type-tab').on('click', function(e){
            get_financial_type();
        });

        $('.addFinancial').on('click', function(){
            $('#typeForm')[0].reset();
            $('#addFinancialLabel').text('<?php echo get_phrases(['add', 'financial', 'type']);?>');
            $('.saveBtnType').text('<?php echo get_phrases(['add']);?>');
            $('#financial_type_id').val('');
            $('#branch_id').val('').trigger('change');
            $('#action').val('add');
            $('#addFinancialModal').modal('show');
        });

        $(document).on('click', '.typeEditAction', function(e){
            e.preventDefault();
            var type_id = $(this).attr('data-id');
            $('#addFinancialModal').modal('show');
             $('#addFinancialLabel').text('<?php echo get_phrases(['update', 'financial', 'type']);?>');
            $('.saveBtnType').text('<?php echo get_phrases(['update']);?>');
            var submit_url = _baseURL+"settings/getFinanTypeById";
            $.ajax({
                type: 'POST',
                url: submit_url,
                data: {'csrf_stream_name':csrf_val, type_id:type_id},
                dataType: 'JSON',
                success: function(res) {
                    //console.log(res);
                    $('#action').val('update');
                    $('#financial_type_id').val(res.id);
                    $('#branch_id').val(res.branch_id).trigger('change');
                    $('#type_english').val(res.nameE);
                    $('#type_arabic').val(res.nameA);
                    $('#type_color').val(res.color);
                    $('#start_amount').val(res.start_amount);
                    $('#end_amount').val(res.end_amount);
                },error: function() {

                }
            });
        });

        $(document).on('click', '.typeDeleteAction', function(e){
            e.preventDefault();
            var type_id = $(this).attr('data-id');
            var submit_url = _baseURL+"settings/deleteFinanType";
            if(confirm('<?php echo get_phrases(['are_you_sure']);?>')){
                $.ajax({
                    type: 'POST',
                    url: submit_url,
                    data: {'csrf_stream_name':csrf_val, type_id:type_id},
                    dataType: 'JSON',
                    success: function(res) {
                        if(res.success==true){
                          toastr.success(res.message, res.title);
                          get_financial_type();
                        }else{
                          toastr.error(res.message, res.title);
                        }
                    },error: function() {

                    }
                });
            }
        });
    });
</script>
