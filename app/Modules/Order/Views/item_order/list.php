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
                        <?php if( $hasCreateAccess ){ ?>
                            <button type="button" class="btn btn-success btn-sm mr-1 addShowModal"><i class="fas fa-plus mr-1"></i><?php echo get_phrases(['new','request']);?></button>
                       <?php } ?>
                       <a href="<?php echo previous_url(); ?>" class="btn btn-warning btn-sm mr-1 ml-2"><i class="fas fa-angle-double-left mr-1"></i>Back</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row form-group">                    
                    <div class="col-sm-3">
                        <label for="filter_dealer_id" class="font-weight-600"><?php echo get_phrases(['dealer']) ?> </label>
                        <select name="filter_dealer_id" id="filter_dealer_id" class="custom-select form-control" >
                            <option value=""></option>
                            <?php if(!empty($dealer_list)){ ?>
                                <?php foreach ($dealer_list as $key => $value) {?>
                                    <option value="<?php echo $value->id;?>"><?php echo $value->name;?></option>
                                <?php }?>
                            <?php }?>
                        </select>
                    </div>  
                    <div class="col-sm-3">
                        <label for="filter_store_id" class="font-weight-600"><?php echo get_phrases(['store']) ?> </label>
                        <select name="filter_store_id" id="filter_store_id" class="custom-select form-control">
                            <option value=""></option>
                            <?php if(!empty($sub_store_list)){ ?>
                                <?php foreach ($sub_store_list as $key => $value) {?>
                                    <option value="<?php echo $value->id;?>"><?php echo $value->nameE;?></option>
                                <?php }?>
                            <?php }?>
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <label for="filter_voucher_no" class="font-weight-600"><?php echo get_phrases(['voucher','no']) ?> </label>
                        <input type="text" name="filter_voucher_no" id="filter_voucher_no" class="form-control">
                    </div>
                    <div class="col-sm-2">
                        <label for="filter_date" class="font-weight-600"><?php echo get_phrases(['date']) ?> </label>
                        <input type="date" name="filter_date" id="filter_date" class="form-control">
                    </div>
                    <div class="col-sm-2">
                        <br>
                        <button type="button" class="btn btn-sm btn-success mt-2" onclick="reload_table()"><?php echo get_phrases(['filter']);?></button>
                        <button type="button" class="btn btn-sm btn-warning mt-2" onclick="reset_table()"><?php echo get_phrases(['reset']);?></button>
                    </div>
                </div>
                <table id="item_requestList" class="table display table-bordered table-striped table-hover compact" width="100%">
                    <thead>
                        <tr>
                            <th><?php echo get_phrases(['sl']);?></th>
                            <th><?php echo get_phrases(['voucher']);?></th>
                            <th><?php echo get_phrases(['date']);?></th>
                            <th><?php echo get_phrases(['dealer']);?></th>
                            <th><?php echo get_phrases(['store']);?></th>
                            <th><?php echo get_phrases(['request','by']);?></th>
                            <th><?php echo get_phrases(['status']);?></th>
                            <th><?php echo get_phrases(['action']);?></th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- item modal button -->
<div class="modal fade bd-example-modal-xl" id="item_request-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel3" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="item_requestModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
           <?php echo form_open_multipart('order/add_item_request', 'class="ajaxForm needs-validation" id="ajaxForm" novalidate="" data="showCallBackData"');?>
            <div class="modal-body">
                <input type="hidden" name="id" id="id" />
                <input type="hidden" name="action" id="action" value="add" />

                    <div class="form-group row">        
                        <label for="dealer_id" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['dealer']) ?> </label>
                        <div class="col-sm-4">
                            <select name="dealer_id" id="dealer_id" class="form-control custom-select" required>
                                <option value=""></option>
                                <?php if(!empty($dealer_list)){ ?>
                                    <?php foreach ($dealer_list as $key => $value) {?>
                                        <option value="<?php echo $value->id;?>"><?php echo $value->name;?></option>
                                    <?php }?>
                                <?php }?>
                            </select>
                        </div>                
                        <label for="sub_store_id" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['store'])?><i class="text-danger">*</i></label>
                        <div class="col-sm-4">
                            <select name="sub_store_id" id="sub_store_id" class="form-control custom-select" required>
                                <option value="">Select</option>
                                <?php foreach($sub_store_list as $value){?>
                                <option value="<?php echo $value->id;?>"><?php echo $value->nameE;?></option>
                               <?php }?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="voucher_no" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['voucher','no'])?> <i class="text-danger">*</i></label>
                        <div class="col-sm-4">
                            <input name="voucher_no"  type="text" class="form-control" id="voucher_no" placeholder="<?php echo get_phrases(['voucher','no'])?>" autocomplete="off" readonly >
                        </div>
                        <label for="date" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['date'])?> <i class="text-danger">*</i></label>
                        <div class="col-sm-4">
                            <input name="date"  type="text" class="form-control datepicker1" id="date" placeholder="<?php echo get_phrases(['date'])?>"  value="<?php echo date('d/m/Y')?>" autocomplete="off" readonly>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="notes" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['notes'])?> </label>
                        <div class="col-sm-4">
                            <textarea name="notes" class="form-control" id="notes" placeholder="<?php echo get_phrases(['notes'])?>" autocomplete="off" rows="3"></textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-12">
                            <input type="hidden" name="item_counter" id="item_counter" value="1">
                            <div class="table-responsive">
                                 <table class="table table-stripped w-100" id="request_table">
                                    <thead>
                                        <tr>
                                            <th width="20%" class="text-center"><?php echo get_phrases(['item', 'name'])?><i class="text-danger">*</i></th>
                                            <th width="10%" class="text-center"><?php echo get_phrases(['quantity'])?></th>
                                            <th width="10%"></th>
                                            <th width="5%"><?php echo get_phrases(['action'])?></th>

                                        </tr>
                                    </thead>
                                    <tbody id="item_div">
                                        
                                    </tbody>
                                 </table>   
                            </div>
                        </div>
                    </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo get_phrases(['close']);?></button>
                <button type="submit" class="btn btn-success modal_action actionBtn"></button>
            </div>
            <?php echo form_close();?>
        </div>
    </div>
</div>


<!-- item request modal button -->
<div class="modal fade bd-example-modal-xl" id="itemRequestDetails-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel3" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="itemRequestDetailsModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
            <?php echo form_open_multipart('order/item_return', 'class="ajaxForm needs-validation" id="ajaxForm" novalidate="" data="showCallBackDataReturn"');?>
            <div class="modal-body" id="printContent">
                <input type="hidden" name="id2" id="id2" />
                <input type="hidden" name="action2" id="action2" value="return" />

                <div class="row printing_info">
                    <div class="col-sm-12 text-center">
                        <img src="<?php echo base_url().$settings_info->logo; ?>" alt="Logo" height="40px" ><br><br>
                        <h6><?php echo $settings_info->title.' ( '.$settings_info->nameA.' )'; ?></h6>
                        <h5><?php echo get_phrases(['item', 'order','voucher']) ?></h5>
                        <strong><?php echo get_phrases(['date']).': '.date('d/m/Y'); ?></strong>
                        <hr>
                    </div>
                </div>

                <div class="row printing_info_return">
                    <div class="col-sm-12 text-center">
                        <img src="<?php echo base_url().$settings_info->logo; ?>" alt="Logo" height="40px" ><br><br>
                        <h6><?php echo $settings_info->title.' ( '.$settings_info->nameA.' )'; ?></h6>
                        <h5><?php echo get_phrases(['item', 'return','voucher']) ?></h5>
                        <strong><?php echo get_phrases(['date']).': '.date('d/m/Y'); ?></strong>
                        <hr>
                    </div>
                    <hr>
                </div>
                
                <div class="row">
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['dealer']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemRequestDetails_dealer_id" ></div>                        
                    </div>
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['store']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemRequestDetails_sub_store_id" ></div>                        
                    </div>
                </div>

                <div class="row">
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['voucher', 'no']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemRequestDetails_voucher_no" ></div>                        
                    </div>
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['date']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemRequestDetails_date"></div>                        
                    </div>
                </div>
                
                <div class="row">
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['request','by']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemRequestDetails_request_by" ></div>                        
                    </div>
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['request','status']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemRequestDetails_status" ></div>                        
                    </div>
                </div>

                <div class="row">
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['notes']) ?> : </label>
                    <div class="col-sm-10">
                        <div id="itemRequestDetails_notes" ></div>                        
                    </div>

                </div>

                <div class="row printing_info_return">
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['return','voucher', 'no']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemRequestDetails_return_voucher_no" ></div>                        
                    </div>
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['returned','date']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemRequestDetails_return_date"></div>                        
                    </div>
                </div>

                <div class="form-group row return_input">
                    <label for="return_voucher_no" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['voucher','no'])?> <i class="text-danger">*</i></label>
                    <div class="col-sm-4">
                        <input name="return_voucher_no" type="text" class="form-control" id="return_voucher_no" placeholder="<?php echo get_phrases(['voucher','no'])?>" autocomplete="off" readonly >
                    </div>
                    <label for="return_date" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['date'])?> <i class="text-danger">*</i></label>
                    <div class="col-sm-4">
                        <input name="return_date" type="text" class="form-control" id="return_date" value="<?php echo date('d/m/Y')?>" autocomplete="off" readonly>
                    </div>
                </div>

                <div class="row form-group">
                    <div class="col-sm-12">
                        <div id="item_details_preview"></div>
                    </div>
                </div>
               
                <div class="row printing_info">
                    <div class="col-sm-6 ">
                        <h6><?php echo get_phrases(['signature']).' ('.get_phrases(['received', 'by']).')' ?></h6>
                        -----------------------------
                        <br>
                        <br>
                    </div>
                    <div class="col-sm-6 text-right">
                        <h6><?php echo get_phrases(['signature']).' ('.get_phrases(['authorized', 'by']).')' ?></h6>
                        -----------------------------
                        <br>
                        <br>
                    </div>
                </div>

                <div class="row printing_info_return">
                    <div class="col-sm-6 ">
                        <h6><?php echo get_phrases(['signature']).' ('.get_phrases(['returned', 'by']).')' ?></h6>
                        -----------------------------
                        <br>
                        <br>
                    </div>
                    <div class="col-sm-6 text-right">
                        <h6><?php echo get_phrases(['signature']).' ('.get_phrases(['authorized', 'by']).')' ?></h6>
                        -----------------------------
                        <br>
                        <br>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal"><?php echo get_phrases(['close']);?></button>
                <?php if($permission->method('journal_voucher', 'read')->access()){ ?>
                    <button type="button" class="btn btn-info viewJV" id="journal_voucher" data-id=""><span class="fa fa-eye"></span> <?php echo get_phrases(['journal', 'voucher']); ?></button>
                <?php } ?>
                <button type="submit" id="return" class="btn btn-success actionBtn" ><?php echo get_phrases(['return']);?></button>
                <button type="button" id="print" class="btn btn-success" onclick="printContent('printContent')"><?php echo get_phrases(['print']);?></button>
            </div>
            <?php echo form_close();?>
        </div>
    </div>
</div>

<div id="item_list" style="display: none">
    <option value=""></option>
    <?php foreach($item_list as $items){?>
    <option value="<?php echo $items->id;?>"><?php echo $items->nameE;?></option>
   <?php }?>
</div>


<!-- view voucher details modal -->
<div class="modal fade bd-example-modal-xl" id="viewDModal" tabindex="-1" role="dialog" aria-labelledby="moduleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="viewDModalLabel"><?php echo get_phrases(['view', 'details']);?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
            <div class="modal-body">
               <div class="row">
                   <div class="col-md-12" id="viewDetails">
                        
                   </div>
               </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo get_phrases(['close']);?></button>
            </div>
            
        </div>
    </div>
</div>


<!-- view voucher details -->
<div class="modal fade bd-example-modal-lg" id="jv-modal" tabindex="-1" role="dialog" aria-labelledby="detailsModalLabel3" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="jvDetailsModalLabel"><?php echo get_phrases(['view', 'voucher', 'details']);?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12" id="jvDetails">
                        
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo get_phrases(['close']);?></button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

    function reload_table(){
        $('#item_requestList').DataTable().ajax.reload();
    }

    function reset_table(){
        $('#filter_dealer_id').val('').trigger('change');
        //$('#filter_doctor_id').val('').trigger('change');
        $('#filter_store_id').val('').trigger('change');
        $('#filter_voucher_no').val('');
        $('#filter_date').val('');

        $('#item_requestList').DataTable().ajax.reload();
    }

    var showCallBackData = function () {
        $('#id').val('');        
        $('#action').val('add');        
        $('.ajaxForm')[0].reset();        
        $('#item_request-modal').modal('hide');
        $('#item_requestList').DataTable().ajax.reload(null, false);
    }

    var showCallBackDataReturn = function () {
        $('.ajaxForm')[0].reset();        
        $('#itemRequestDetails-modal').modal('hide');
        $('#item_requestList').DataTable().ajax.reload(null, false);
    }

    $(document).ready(function() { 
       "use strict";
       
   
       $('#return').on('click', function(e) {
            var action = $('#action2').val();
            if( action =='return' ){
                var result = check_quantity();
                if( !result){
                    e.preventDefault();
                    return false;
                }
            }
        });

        $('option:first-child').val('').trigger('change');
        
        //$('#doctor_list').hide();

        // doctor list
        /*$.ajax({
            type:'GET',
            url: _baseURL+'auth/doctorList',
            dataType: 'json',
        }).done(function(data) {
            $("#filter_doctor_id").select2({
                placeholder: '<?php //echo get_phrases(['select', 'doctor']);?>',
                data: data
            });
        });

        $('#patient_id').select2({
            placeholder: '<?php echo get_phrases(['search', 'patient']);?>',
            minimumInputLength: 1,
                ajax: {
                    url: _baseURL+'auth/searchPntWithFile',
                    dataType: 'json',
                    delay: 250,
                    processResults: function (data) {
                      return {
                        results:  $.map(data, function (item) {
                              return {
                                  text: item.text,
                                  id: item.id
                              }
                          })
                      };
                    },
                    cache: true
               }
        });*/


        $('body').on('click', '.addRow', function() {
            var item_counter = parseInt($("#item_counter").val()); 
            item_counter += 1;
            $("#item_counter").val(item_counter);

            var item_list = $('#item_list').html();
            

            var html = ' <tr><td><select name="item_id[]" id="item_id'+item_counter+'" class="form-control custom-select" onchange="item_info(this.value,'+item_counter+')" required>'+item_list+'</select></td><td><input type="text" name="qty[]" class="form-control text-right onlyNumber" id="qty'+item_counter+'" required autocomplete="off" ></td><td class="valign-middle"><span id="unit'+item_counter+'"></span></td><td><button type="button" class="btn btn-danger removeRow" ><i class="fa fa-minus"></i></button></td></tr>';

            $("#item_div").append(html); 
            $('#item_div select').select2({
                placeholder: '<?php echo get_phrases(['select','item']);?>'                
            });
        });


        $('.addShowModal').on('click', function(){
            $('.ajaxForm').removeClass('was-validated');
            $('#id').val('');
            $('#action').val('add');

            $('#sub_store_id').val('<?php echo $default_store_id; ?>').trigger('change');
            $('#notes').val('');   
            var item_list = $('#item_list').html();

            var html = ' <tr><td><select name="item_id[]" id="item_id1" class="form-control custom-select" onchange="item_info(this.value,1)" required>'+item_list+'</select></td><td><input type="text" name="qty[]" class="form-control text-right onlyNumber" id="qty1" required autocomplete="off" ></td><td class="valign-middle"><span id="unit1"></span></td><td><button type="button" class="btn btn-success addRow" ><i class="fa fa-plus"></i></button></td></tr>';      

            $("#item_div").html(html); 
            $("#item_counter").val(1);
            //calculation(1);
            $('#item_div select').select2({
                placeholder: '<?php echo get_phrases(['select','item']);?>'                
            });

            getMAXID('wh_order','id','voucher_no','ORD-');

            $('#item_requestModalLabel').text('<?php echo get_phrases(['new','order','request']);?>');
            $('.modal_action').text('<?php echo get_phrases(['send']);?>');
            $('.modal_action').prop('disabled', false);
            $('#item_request-modal').modal('show');
        });

        $('#item_requestList').on('click', '.actionPreview', function(e){
            e.preventDefault();
            $('.ajaxForm').removeClass('was-validated');
            $('#item_details_preview').html('');
            
            var id = $(this).attr('data-id');
            var submit_url = _baseURL+'order/getItemRequestDetailsById/'+id;

            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'JSON',
                data: {'csrf_stream_name':csrf_val},
                async: false,
                success: function(data) {
                    $('#itemRequestDetails-modal').modal('show');
                    $('#itemRequestDetailsModalLabel').text('<?php echo get_phrases(['order','request','details']);?>');

                    $('#itemRequestDetails_sub_store_id').text(data.sub_store_name);
                    $('#itemRequestDetails_date').text(data.date);
                    $('#itemRequestDetails_voucher_no').text(data.voucher_no);
                    $('#itemRequestDetails_dealer_id').text(data.dealer_name);
                    $('#itemRequestDetails_notes').text(data.notes);
                    $('#itemRequestDetails_status').text(data.status_text+' '+data.status_collected+' '+data.status_returned);
                    $('#itemRequestDetails_request_by').text(data.request_by_name);
                    /*$('#itemRequestDetails_consumed_by').text(data.consumed_by);
                    if(data.consumed_by == 'service'){
                        $('.consumed_service_info').show();
                        $('#itemRequestDetails_invoice_id').html('SINV-'+data.invoice_id);
                        $('#itemRequestDetails_invoice_id').attr('data-id','SINV-'+data.invoice_id);
                        $('#itemRequestDetails_service').text(data.service);
                        $('#itemRequestDetails_patient').text(data.patient);
                        $('#itemRequestDetails_doctor').text(data.doctor_name);
                    } else {
                        $('.consumed_service_info').hide();
                    }*/
                    
                    $('#return').hide();
                    $('#print').hide();
                    $('.return_input').hide();
                    $('.printing_info').hide();
                    $('.printing_info_return').hide();
                    $('#journal_voucher').hide();
                    $('#journal_voucher').attr('data-id', '');
                    
                    get_item_details(id);

                },error: function() {

                }
            });   

        });

        $('#item_requestList').on('click', '.actionCollect', function(e){
            e.preventDefault();
            $('.ajaxForm').removeClass('was-validated');
            $('#item_details_preview').html('');
            
            var id = $(this).attr('data-id');
            var submit_url = _baseURL+'order/getItemRequestDetailsById/'+id;

            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'JSON',
                data: {'csrf_stream_name':csrf_val},
                async: false,
                success: function(data) {
                    $('#itemRequestDetails-modal').modal('show');
                    $('#itemRequestDetailsModalLabel').text('<?php echo get_phrases(['item','collect','confirmation']);?>');
                    
                    $('#itemRequestDetails_sub_store_id').text(data.sub_store_name);
                    $('#itemRequestDetails_date').text(data.date);
                    $('#itemRequestDetails_voucher_no').text(data.voucher_no);
                    $('#itemRequestDetails_dealer_id').text(data.dealer_name);
                    $('#itemRequestDetails_notes').text(data.notes);
                    $('#itemRequestDetails_status').text(data.status_text+' '+data.status_collected+' '+data.status_returned);
                    $('#itemRequestDetails_request_by').text(data.request_by_name);
                    /*$('#itemRequestDetails_consumed_by').text(data.consumed_by);
                    if(data.consumed_by == 'service'){
                        $('.consumed_service_info').show();
                        $('#itemRequestDetails_invoice_id').html('SINV-'+data.invoice_id);
                        $('#itemRequestDetails_invoice_id').attr('data-id','SINV-'+data.invoice_id);
                        $('#itemRequestDetails_service').text(data.service);
                        $('#itemRequestDetails_patient').text(data.patient);
                        $('#itemRequestDetails_doctor').text(data.doctor_name);
                    } else {
                        $('.consumed_service_info').hide();
                    }*/
                    
                    $('#return').show();
                    $('#return').text('<?php echo get_phrases(['collect']);?>');
                    $('#return').prop('disabled', false);
                    $('#action2').val('collect');
                    $('#id2').val(id);
                    $('#print').hide();
                    $('.return_input').hide();
                    $('#return_voucher_no').prop('required', false);
                    $('.printing_info').hide();
                    $('.printing_info_return').hide();
                    $('#journal_voucher').hide();
                    $('#journal_voucher').attr('data-id', '');
                    
                    get_item_details(id);

                },error: function() {

                }
            });   

        });

        $('#item_requestList').on('click', '.actionResend', function(e){
            e.preventDefault();
            $('.ajaxForm').removeClass('was-validated');
            $('#item_details_preview').html('');
            
            var id = $(this).attr('data-id');
            var submit_url = _baseURL+'order/getItemRequestDetailsById/'+id;

            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'JSON',
                data: {'csrf_stream_name':csrf_val},
                async: false,
                success: function(data) {
                    $('#itemRequestDetails-modal').modal('show');
                    $('#itemRequestDetailsModalLabel').text('<?php echo get_phrases(['item','collect','confirmation']);?>');
                    
                    $('#itemRequestDetails_sub_store_id').text(data.sub_store_name);
                    $('#itemRequestDetails_date').text(data.date);
                    $('#itemRequestDetails_voucher_no').text(data.voucher_no);
                    $('#itemRequestDetails_dealer_id').text(data.dealer_name);
                    $('#itemRequestDetails_notes').text(data.notes);
                    $('#itemRequestDetails_status').text(data.status_text+' '+data.status_collected+' '+data.status_returned);
                    $('#itemRequestDetails_request_by').text(data.request_by_name);
                    /*$('#itemRequestDetails_consumed_by').text(data.consumed_by);
                    if(data.consumed_by == 'service'){
                        $('.consumed_service_info').show();
                        $('#itemRequestDetails_invoice_id').html('SINV-'+data.invoice_id);
                        $('#itemRequestDetails_invoice_id').attr('data-id','SINV-'+data.invoice_id);
                        $('#itemRequestDetails_service').text(data.service);
                        $('#itemRequestDetails_patient').text(data.patient);
                        $('#itemRequestDetails_doctor').text(data.doctor_name);
                    } else {
                        $('.consumed_service_info').hide();
                    }*/
                    
                    $('#return').show();
                    $('#return').text('<?php echo get_phrases(['resend']);?>');
                    $('#return').prop('disabled', false);
                    $('#action2').val('resend');
                    $('#id2').val(id);
                    $('#print').hide();
                    $('.return_input').hide();
                    $('#return_voucher_no').prop('required', false);
                    $('.printing_info').hide();
                    $('.printing_info_return').hide();
                    $('#journal_voucher').hide();
                    $('#journal_voucher').attr('data-id', '');
                    
                    get_item_details(id);

                },error: function() {

                }
            });   

        });

        $('#item_requestList').on('click', '.actionReturn', function(e){
            e.preventDefault();
            $('.ajaxForm').removeClass('was-validated');
            $('#item_details_preview').html('');
            
            var id = $(this).attr('data-id');
            var submit_url = _baseURL+'order/getItemRequestDetailsById/'+id;

            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'JSON',
                data: {'csrf_stream_name':csrf_val},
                async: false,
                success: function(data) {
                    $('#itemRequestDetails-modal').modal('show');
                    $('#itemRequestDetailsModalLabel').text('<?php echo get_phrases(['return','item','to','sale','store']);?>');

                    $('#itemRequestDetails_sub_store_id').text(data.sub_store_name);
                    $('#itemRequestDetails_date').text(data.date);
                    $('#itemRequestDetails_voucher_no').text(data.voucher_no);
                    $('#itemRequestDetails_dealer_id').text(data.dealer_name);
                    $('#itemRequestDetails_notes').text(data.notes);
                    $('#itemRequestDetails_status').text(data.status_text+' '+data.status_collected+' '+data.status_returned);
                    $('#itemRequestDetails_request_by').text(data.request_by_name);
                    /*$('#itemRequestDetails_consumed_by').text(data.consumed_by);
                    if(data.consumed_by == 'service'){
                        $('.consumed_service_info').show();
                        $('#itemRequestDetails_invoice_id').html('SINV-'+data.invoice_id);
                        $('#itemRequestDetails_invoice_id').attr('data-id','SINV-'+data.invoice_id);
                        $('#itemRequestDetails_service').text(data.service);
                        $('#itemRequestDetails_patient').text(data.patient);
                        $('#itemRequestDetails_doctor').text(data.doctor_name);
                    } else {
                        $('.consumed_service_info').hide();
                    }*/

                    $('#return').show();
                    $('#return').text('<?php echo get_phrases(['return']);?>');
                    $('#return').prop('disabled', false);
                    $('#action2').val('return');
                    $('#id2').val(id);
                    $('#print').hide();
                    $('.return_input').show();
                    $('#return_voucher_no').prop('required', true);
                    $('.printing_info').hide();
                    $('.printing_info_return').hide();
                    $('#journal_voucher').hide();
                    $('#journal_voucher').attr('data-id', '');

                    getMAXID('wh_order','id','return_voucher_no','CONSRET-');

                    get_item_details_return(id);

                },error: function() {

                }
            });   

        });

        $('#item_requestList').on('click', '.actionPrint', function(e){
            e.preventDefault();
            $('.ajaxForm').removeClass('was-validated');
            $('#item_details_preview').html('');
            
            var id = $(this).attr('data-id');
            var submit_url = _baseURL+'order/getItemRequestDetailsById/'+id;

            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'JSON',
                data: {'csrf_stream_name':csrf_val},
                async: false,
                success: function(data) {
                    $('#itemRequestDetails-modal').modal('show');
                    $('#itemRequestDetailsModalLabel').text('<?php echo get_phrases(['print','preview']);?>');

                    $('#itemRequestDetails_sub_store_id').text(data.sub_store_name);
                    $('#itemRequestDetails_date').text(data.date);
                    $('#itemRequestDetails_voucher_no').text(data.voucher_no);
                    $('#itemRequestDetails_dealer_id').text(data.dealer_name);
                    $('#itemRequestDetails_notes').text(data.notes);
                    $('#itemRequestDetails_status').text(data.status_text+' '+data.status_collected+' '+data.status_returned);
                    $('#itemRequestDetails_request_by').text(data.request_by_name);
                    /*$('#itemRequestDetails_consumed_by').text(data.consumed_by);
                    if(data.consumed_by == 'service'){
                        $('.consumed_service_info').show();
                        $('#itemRequestDetails_invoice_id').html('SINV-'+data.invoice_id);
                        $('#itemRequestDetails_invoice_id').attr('data-id','SINV-'+data.invoice_id);
                        $('#itemRequestDetails_service').text(data.service);
                        $('#itemRequestDetails_patient').text(data.patient);
                        $('#itemRequestDetails_doctor').text(data.doctor_name);
                    } else {
                        $('.consumed_service_info').hide();
                    }*/
                    
                    $('#return').hide();
                    $('#action2').val('print');
                    $('#id2').val(id);
                    $('#print').show();
                    $('.return_input').hide();
                    $('.printing_info').show();
                    $('.printing_info_return').hide();
                    $('#journal_voucher').show();
                    $('#journal_voucher').attr('data-id', data.voucher_no);

                    get_item_details(id);

                },error: function() {

                }
            });   

        });

        $('#item_requestList').on('click', '.actionPrintReturn', function(e){
            e.preventDefault();
            $('.ajaxForm').removeClass('was-validated');
            $('#item_details_preview').html('');
            
            var id = $(this).attr('data-id');
            var submit_url = _baseURL+'order/getItemRequestDetailsById/'+id;

            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'JSON',
                data: {'csrf_stream_name':csrf_val},
                async: false,
                success: function(data) {
                    $('#itemRequestDetails-modal').modal('show');
                    $('#itemRequestDetailsModalLabel').text('<?php echo get_phrases(['print','preview']);?>');

                    $('#itemRequestDetails_sub_store_id').text(data.sub_store_name);
                    $('#itemRequestDetails_date').text(data.date);
                    $('#itemRequestDetails_voucher_no').text(data.voucher_no);
                    $('#itemRequestDetails_dealer_id').text(data.dealer_name);
                    $('#itemRequestDetails_notes').text(data.notes);
                    $('#itemRequestDetails_status').text(data.status_text+' '+data.status_collected+' '+data.status_returned);
                    $('#itemRequestDetails_request_by').text(data.request_by_name);

                    $('#itemRequestDetails_return_date').text(data.return_date);
                    $('#itemRequestDetails_return_voucher_no').text(data.return_voucher_no);

                    /*$('#itemRequestDetails_consumed_by').text(data.consumed_by);
                    if(data.consumed_by == 'service'){
                        $('.consumed_service_info').show();
                        $('#itemRequestDetails_invoice_id').html('SINV-'+data.invoice_id);
                        $('#itemRequestDetails_invoice_id').attr('data-id','SINV-'+data.invoice_id);
                        $('#itemRequestDetails_service').text(data.service);
                        $('#itemRequestDetails_patient').text(data.patient);
                        $('#itemRequestDetails_doctor').text(data.doctor_name);
                    } else {
                        $('.consumed_service_info').hide();
                    }*/
                    
                    $('#return').hide();
                    $('#action2').val('print_return');
                    $('#id2').val(id);
                    $('#print').show();
                    $('.return_input').hide();
                    $('.printing_info').hide();
                    $('.printing_info_return').show();
                    $('#journal_voucher').show();
                    $('#journal_voucher').attr('data-id', data.voucher_no);

                    get_item_details(id);

                },error: function() {

                }
            });   

        });

        $('#item_requestList').DataTable({ 
             responsive: true,
             lengthChange: true,
             "aaSorting": [[ 0, "desc" ]],
             "columnDefs": [
                { "bSortable": false, "aTargets": [7] },
            ],
            dom: "<'row'<?php if($hasExportAccess || $hasPrintAccess){ echo "<'col-md-4'l><'col-md-4'B><'col-md-4'f>"; } else { echo "<'col-md-6'l><'col-md-6'f>"; } ?>>rt<'bottom'<'row'<'col-md-6'i><'col-md-6'p>>><'clear'>",
            buttons: [
                <?php if($hasPrintAccess){ ?>
                {
                    extend: 'print',
                    text: '<i class="fa fa-print custool" title="Print"></i>',
                    titleAttr: 'Print',
                    className: 'btn-light',
                    title : 'ItemRequest_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: 'th:not(:last-child)'
                    }
                },
                <?php } if($hasExportAccess){ ?>
                {
                    extend: 'copyHtml5',
                    text: '<i class="far fa-copy custool" title="Copy"></i>',
                    titleAttr: 'Copy',
                    className: 'btn-light',
                    title : 'ItemRequest_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5, 6 ]
                    }
                },
                {
                    extend: 'excelHtml5',
                    text: '<i class="far fa-file-excel custool" title="Excel"></i>',
                    titleAttr: 'Excel',
                    className: 'btn-light',
                    title : 'ItemRequest_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5, 6 ]
                    }
                },
                {
                    extend: 'csvHtml5',
                    text: '<i class="far fa-file-alt custool" title="CSV"></i>',
                    titleAttr: 'CSV',
                    className: 'btn-light',
                    title : 'ItemRequest_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5, 6 ]
                    }
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="far fa-file-pdf custool" title="PDF"></i>',
                    titleAttr: 'PDF',
                    className: 'btn-light',
                    title : 'ItemRequest_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5, 6 ]
                    }
                }
                <?php } ?>
            ],
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'ajax': {
               'url': _baseURL + 'order/getItemRequest',
               'data':function ( d ) {
                        d.csrf_stream_name = csrf_val;
                        d.store_id = $('#filter_store_id').val();
                        d.dealer_id = $('#filter_dealer_id').val();
                        d.doctor_id = 0;//$('#filter_doctor_id').val();
                        d.voucher_no = $('#filter_voucher_no').val();
                        d.date = $('#filter_date').val();
                    }
            },
          'columns': [
             { data: 'id' },
             { data: 'voucher_no' },
             { data: 'date' },
             { data: 'dealer_name' },
             { data: 'sub_store_name' },
             { data: 'request_by_name' },
             { data: 'status' },
             { data: 'button'}
          ],
        });

        $('#item_requestList').on('draw.dt', function() {
             $('.custool').tooltip(); 
        });
        
        $('#picture').on('change', function () {
            var fileExtension = ['jpeg', 'jpg', 'png', 'gif'];
            imgPreview($(this), fileExtension);
        }); 

        $('#item_requestList').on('click', '.actionEdit', function(e){
            e.preventDefault();
            $('.ajaxForm').removeClass('was-validated');
            //$('#thumbpic').next('span').text('');
            
            var id = $(this).attr('data-id');
            var submit_url = _baseURL+'order/getItemRequestById/'+id;

            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'JSON',
                data: {'csrf_stream_name':csrf_val},
                async: false,
                success: function(data) {
                    $('#item_request-modal').modal('show');
                    $('#id').val(data.id);
                    $('#action').val('update');
                    $('#item_requestModalLabel').text('<?php echo get_phrases(['update', 'request']);?>');
                    $('.modal_action').text('<?php echo get_phrases(['update']);?>');
                    $('.modal_action').prop('disabled', false);

                    $('#nameE').val(data.nameE);
                    $('#nameA').val(data.nameA);
                    $('#cat_id').val(data.cat_id).trigger('change');
                    $('#unit_id').val(data.unit_id).trigger('change');
                    $('#price').val(data.price);

                    

                },error: function() {

                }
            });   

        });
        // delete item_request
        $('#item_requestList').on('click', '.actionDelete', function(e){
            e.preventDefault();

            var id = $(this).attr('data-id');
            
            var submit_url = _baseURL+"order/deleteItemRequest/"+id;
            var check = confirm('<?php echo get_phrases(["are_you_sure"])?>');  
            if(check == true){  
                $.ajax({
                    type: 'POST',
                    url: submit_url,
                    data: {'csrf_stream_name':csrf_val},
                    dataType: 'JSON',
                    async: false,
                    success: function(res) {
                        if(res.success==true){
                            toastr.success(res.message, '<?php echo get_phrases(["record"])?>');
                            $('#item_requestList').DataTable().ajax.reload(null, false);
                        }else{
                            toastr.error(res.message, '<?php echo get_phrases(["record"])?>');
                        }
                    },error: function() {

                    }
                });
            }   
        });
    });

    function get_item_list(consumed_by){

        var submit_url = _baseURL+"order/getItemList/"+consumed_by; 
        $.ajax({
            type: 'POST',
            url: submit_url,
            data: {'csrf_stream_name':csrf_val },
            dataType: 'JSON',
            async: false,
            success: function(data){
                if(data != null && data.length >0){
                    var html = '<option value=""></option>';
                    $.each(data, function(index, value){
                        html +='<option value="'+value.id+'">'+value.nameE+'</option>';;
                    });
                    $('#'+consumed_by+'_item_list').html(html);
                }
                    
            }
        });  
    }


    function add_item_row(item_id, quantity){

        var item_counter = parseInt($("#item_counter").val()); 
        item_counter += 1;
        $("#item_counter").val(item_counter);
        
        var item_list = $('#item_list').html();

        var button_color = 'danger';
        var button_class = 'removeRow';
        var button_type = 'minus';
        if(item_counter == 1){
            button_color = 'success';
            button_class = 'addRow';
            button_type = 'plus';
        }

        var html = ' <tr><td><select name="item_id[]" id="item_id'+item_counter+'" class="form-control custom-select" onchange="item_info(this.value,'+item_counter+')" required>'+item_list+'</select></td><td><input type="text" name="qty[]" class="form-control text-right onlyNumber" id="qty'+item_counter+'" required autocomplete="off" ></td><td class="valign-middle"><span id="unit'+item_counter+'"></span></td><td><button type="button" class="btn btn-'+button_color+' '+button_class+'" ><i class="fa fa-'+button_type+'"></i></button></td></tr>';

        $("#item_div").append(html); 
        $('#item_div select').select2({
            placeholder: '<?php echo get_phrases(['select','item']);?>'                
        });
        
        $("#item_id"+item_counter).val(item_id).trigger('change');
        $("#qty"+item_counter).val(quantity);
    }

    $(document).on('click', '.viewJV', function(e){
        e.preventDefault();
        var VNo = $(this).attr('data-id');
        $('#jv-modal').modal('show');
        var submit_url = _baseURL+"account/vouchers/getVoucherDetails/"+VNo; 
        $.ajax({
            type: 'POST',
            url: submit_url,
            data: {'csrf_stream_name':csrf_val},
            dataType: 'JSON',
            async: false,
            success: function(response) {
                $('#jvDetails').html('');
                $('#jvDetails').html(response.data);
            }
        });  
    });

    // view details invoice info
    $(document).on('click', '.viewDetails', function(e){
        e.preventDefault();
        $('#viewDModal').modal('show');
        var voucherId = $(this).attr('data-id');
        //var Id = $(this).attr('data-id');
        //var type = $(this).attr('data-type');
        //var typeId = type+'-'+Id;
        //if(Id && type){
        $('#viewDetails').html('');
        if(voucherId){
            var submit_url = _baseURL+"reports/management/getVoucherDetails"; 
            $.ajax({
                type: 'POST',
                url: submit_url,
                data: {'csrf_stream_name':csrf_val, voucherId:voucherId},
                dataType: 'JSON',
                async: false,
                success: function(res) {
                    $('#viewDetails').html('');
                    $('#viewDetails').html(res.data);
                }
            });  
        }else{
            toastr.warning('<?php echo get_notify('Wrong_Invoice!'); ?>');
        }
    });

    function calculation(sl){
        
        var return_qty = ($("#return_qty"+sl).val()=='')?0:parseInt($("#return_qty"+sl).val());
        var aqty = ($("#aqty"+sl).val()=='')?0:parseInt($("#aqty"+sl).val()); 
        if(return_qty > aqty){
            toastr.warning('<?php echo get_notify('Return_quantity_should_be_less_than_or_equal_approved_quantity'); ?>');
            $("#return_qty"+sl).val(aqty);
            //qty = aqty;
        }
       check_quantity();
    }

    function check_quantity(){
        var item_counter = parseInt($('#return_item_counter').val());
        var tot_qty = 0;
        var return_qty = 0;
        for(var i=1; i<=item_counter; i++){
            return_qty = ($('#return_qty'+i).val()=='')?0:parseFloat($('#return_qty'+i).val());
            tot_qty += return_qty;

        }
        if(tot_qty >0 ){
            $('#return').prop('disabled', false);
            return true;
        } else {
            $('#return').prop('disabled', true);
            return false;
        }
    }

    function item_info(item_id,sl){
        var item_counter = parseInt($("#item_counter").val()); 
        var item_id_each = 0;
        for(var i=1; i<=item_counter; i++){
            item_id_each = $("#item_id"+i).val();
            if(item_id == item_id_each &&  i!=sl){
                toastr.warning('<?php echo get_notify('Same_item_can_not_be_added'); ?>');
                $("#item_id"+sl).val('').trigger('change');
                return false;
            }
        }
        //return;

        $.ajax({
            url: _baseURL+"order/getItemDetailsById/"+item_id,
            type: 'POST',
            data: {'csrf_stream_name':csrf_val, item_id: item_id},
            dataType:"JSON",
            async: false,
            success: function (data) {
                $('#unit'+sl).html(data.unit_name);
            }
        });
    }

    function get_item_details(request_id){

        if(request_id !='' ){
            var submit_url = _baseURL+'order/getItemRequestQuantityDetailsById';
            var action = $('#action2').val();

            preloader_ajax();
            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'html',
                data: {'csrf_stream_name':csrf_val, 'request_id':request_id, 'action':action },
                async: false,
                success: function(data) {
                    $('#item_details_preview').html(data);
                    if(action == 'print'){
                        $('.return_info').hide();
                    } else {
                        $('.return_info').show();
                    }
                    if(action == 'print_return'){
                        $('.consume_info').hide();
                    } else {
                        $('.consume_info').show();
                    }

                    <?php if( $hasPriceAccess ){ ?>         
                        $('.price_text').show();
                    <?php } else { ?>
                        $('.price_text').hide();
                    <?php } ?>

                }
            });
        } else {
            $('#item_details_preview').html('');
        }
    }

    function get_item_details_return(request_id){

        if(request_id !='' ){
            var submit_url = _baseURL+'order/getItemReturnDetailsById';

            preloader_ajax();
            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'html',
                data: {'csrf_stream_name':csrf_val, 'request_id':request_id },
                async: false,
                success: function(data) {
                    $('#item_details_preview').html(data);

                    <?php if( $hasPriceAccess ){ ?>         
                        $('.price_text').show();
                    <?php } else { ?>
                        $('.price_text').hide();
                    <?php } ?>

                }
            });
        } else {
            $('#item_details_preview').html('');
        }
    }

</script>