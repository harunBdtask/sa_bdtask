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
                            <button type="button" class="btn btn-success btn-sm mr-1 addShowModal"><i class="fas fa-plus mr-1"></i><?php echo get_phrases(['new','entry']);?></button>
                       <?php } ?>
                       <a href="<?php echo previous_url(); ?>" class="btn btn-warning btn-sm mr-1 ml-2"><i class="fas fa-angle-double-left mr-1"></i><?php echo get_phrases(['back']);?></a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row form-group">                    
                    <div class="col-sm-3">
                        <label for="filter_store_id" class="font-weight-600"><?php echo get_phrases(['material','store']) ?> </label>
                        <select name="filter_store_id" id="filter_store_id" class="custom-select form-control" >
                            <option value=""></option>
                            <?php if(!empty($main_store_list)){ ?>
                                <?php foreach ($main_store_list as $key => $value) {?>
                                    <option value="<?php echo $value->id;?>"><?php echo $value->nameE;?></option>
                                <?php }?>
                            <?php }?>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <label for="filter_sub_store_id" class="font-weight-600"><?php echo get_phrases(['plant']) ?> </label>
                        <select name="filter_sub_store_id" id="filter_sub_store_id" class="custom-select form-control">
                            <option value=""></option>
                            <?php if(!empty($sub_store_list)){ ?>
                                <?php foreach ($sub_store_list as $key => $value) {?>
                                    <option value="<?php echo $value->id;?>"><?php echo $value->nameE;?></option>
                                <?php }?>
                            <?php }?>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <label for="filter_voucher_no" class="font-weight-600"><?php echo get_phrases(['voucher','no']) ?> </label>
                        <input type="text" name="filter_voucher_no" id="filter_voucher_no" class="form-control">
                    </div>
                    <div class="col-sm-2">
                        <label for="filter_date" class="font-weight-600"><?php echo get_phrases(['date']) ?> </label>
                        <input type="date" name="filter_date" id="filter_date" class="form-control">
                    </div>
                    <div class="col-sm-1">
                        <br>
                        <button type="button" class="btn btn-success mt-2" onclick="reload_table()"><?php echo get_phrases(['filter']);?></button>
                    </div>
                </div>
                <table id="item_requestList" class="table display table-bordered table-striped table-hover compact" width="100%">
                    <thead>
                        <tr>
                            <th><?php echo get_phrases(['sl']);?></th>
                            <th><?php echo get_phrases(['voucher']);?></th>
                            <th><?php echo get_phrases(['date']);?></th>
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
    <div class="modal-dialog modal-xl" >
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="item_requestModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
           <?php echo form_open_multipart('machine/add_item_request', 'class="ajaxForm needs-validation" id="ajaxForm" novalidate="" data="showCallBackData"');?>
            <div class="modal-body">
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                <input type="hidden" name="id" id="id" />
                <input type="hidden" name="sub_store_id" id="sub_store_id" value="0" />
                <input type="hidden" name="action" id="action" value="add" />
                    <div class="form-group row">
                        <!-- <label for="sub_store_id" class="col-sm-1 col-form-label font-weight-600"><?php //echo get_phrases(['plant'])?><i class="text-danger">*</i></label>
                        <div class="col-sm-3">
                            <select name="sub_store_id" id="sub_store_id" class="form-control custom-select" required>
                                <option value="">Select</option>
                                <?php //foreach($sub_store_list as $value){?>
                                <option value="<?php //echo $value->id;?>"><?php //echo $value->nameE;?></option>
                               <?php //}?>
                            </select>
                        </div> -->
                        <label for="voucher_no" class="col-sm-3 col-form-label font-weight-600"><?php echo get_phrases(['voucher','no'])?> <i class="text-danger">*</i></label>
                        <div class="col-sm-3">
                            <input name="voucher_no" type="text" class="form-control" id="voucher_no" placeholder="<?php echo get_phrases(['voucher','no'])?>" autocomplete="off" readonly >
                        </div>
                        <label for="date" class="col-sm-3 col-form-label font-weight-600"><?php echo get_phrases(['date'])?> <i class="text-danger">*</i></label>
                        <div class="col-sm-3">
                            <input name="date"  type="text" class="form-control datepicker1" id="date" placeholder="<?php echo get_phrases(['date'])?>"  value="<?php echo date('d/m/Y')?>" autocomplete="off" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <input type="hidden" name="item_counter" id="item_counter" value="1">
                            <div class="table-responsive">
                                 <table class="table table-stripped w-100" id="request_table">
                                    <thead>
                                        <tr>
                                            <th width="40%" class="text-center"><?php echo get_phrases(['item', 'name'])?><i class="text-danger">*</i></th>
                                            <th width="20%" class="text-center"><?php echo get_phrases(['store','batch','no'])?></th>
                                            <th width="20%" class="text-center"><?php echo get_phrases(['required','qty'])?></th>
                                            <th width="10%" ><?php echo get_phrases(['unit'])?></th>
                                            <th width="10%" ><?php echo get_phrases(['action'])?></th>

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
    <div class="modal-dialog modal-xl" >
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="itemRequestDetailsModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
            <?php echo form_open_multipart('machine/approve_consumption', 'class="ajaxForm needs-validation" id="ajaxForm2" novalidate="" data="showCallBackData2"');?>
            <div class="modal-body" id="printContent">

                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                <input type="hidden" name="id" id="id2" />
                <input type="hidden" name="sub_store_id" id="sub_store_id2" />
                <input type="hidden" name="main_store_id" id="main_store_id2" />
                <input type="hidden" name="action" id="action2" value="approve" />

                <div class="row printing_info">
                    <div class="col-sm-12 text-center">
                        <img src="<?php echo base_url().$settings_info->logo; ?>" alt="Logo" height="40px" ><br><br>
                        <h6><?php echo $settings_info->title.' ( '.$settings_info->nameA.' )'; ?></h6>
                        <h5><?php echo get_phrases(['material','consumption', 'voucher']) ?></h5>
                        <strong><?php echo get_phrases(['date']).': '.date('d/m/Y'); ?></strong>
                        <hr>
                    </div>
                </div>

                <!-- <div class="row">
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['plant']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemRequestDetails_sub_store_id" ></div>
                        
                    </div>
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['material','store']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemRequestDetails_main_store_id"></div>
                        
                    </div>
                </div> -->

                <div class="row">
                    <label class="col-sm-3 text-right font-weight-600"><?php echo get_phrases(['voucher', 'no']) ?> : </label>
                    <div class="col-sm-3">
                        <div id="itemRequestDetails_voucher_no" ></div>
                        
                    </div>
                    <label class="col-sm-3 text-right font-weight-600"><?php echo get_phrases(['date']) ?> : </label>
                    <div class="col-sm-3">
                        <div id="itemRequestDetails_date"></div>
                        
                    </div>
                </div>

                <!-- <div class="row">
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['notes']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemRequestDetails_notes" ></div>
                        
                    </div>
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['status']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemRequestDetails_status" ></div>
                        
                    </div>
                </div> -->
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

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal"><?php echo get_phrases(['close']);?></button>
                <button type="button" id="print" class="btn btn-success"  onclick="printContent('printContent')"><?php echo get_phrases(['print']);?></button>
                <button type="submit" id="approve" class="btn btn-success actionBtn"></button>
            </div>
            <?php echo form_close();?>
        </div>
    </div>
</div>

<script type="text/javascript">

    function reload_table(){
        $('#item_requestList').DataTable().ajax.reload();
    }

    var showCallBackData = function () {
        $('#id').val('');        
        $('#action').val('add');        
        $('.ajaxForm')[0].reset();        
        $('#item_request-modal').modal('hide');
        $('#item_requestList').DataTable().ajax.reload(null, false);
    }

    var showCallBackData2 = function () {
        $('#id2').val('');        
        $('#action2').val('approve');        
        $('.ajaxForm')[0].reset();        
        $('#itemRequestDetails-modal').modal('hide');
        $('#item_requestList').DataTable().ajax.reload(null, false);
    }


    $(document).on('keyup', '.qty', function() {
        var qty = ($(this).val() == '')?0:parseFloat($(this).val()); 
        var item_counter = $(this).parent().parent().attr('data-counter'); 
        var item_id = $("#item_id"+item_counter).val();
        var receive_details_id = $("#receive_details_id"+item_counter).val();
        var avail_qty = ($("#receive_avail_qty"+item_id+receive_details_id).val() == '')?0:parseFloat($("#receive_avail_qty"+item_id+receive_details_id).val());
        if(qty > avail_qty){
            $(this).val(avail_qty);
        }
    });

    $(document).on('select2:select', '.receive_details_id', function() {
        var item_counter = $(this).parent().parent().attr('data-counter'); 
        $('#qty'+item_counter).val('');
    });

    $(document).ready(function() { 
       "use strict";

        $('body').on('click', '.addRow', function() {
            var item_counter = parseInt($("#item_counter").val()); 
            item_counter += 1;
            $("#item_counter").val(item_counter);

            var html = '<tr data-counter="'+item_counter+'"><td><select name="item_id[]" id="item_id'+item_counter+'" class="form-control custom-select" onchange="item_info(this.value,'+item_counter+')" required><option value=""></option>'+<?php foreach($item_list as $items){?>
                                        '<option value="<?php echo $items->id;?>"><?php echo $items->nameE;?></option>'+
                                       <?php }?>
                                    '</select></td><td><select name="receive_details_id[]" id="receive_details_id'+item_counter+'" class="form-control custom-select receive_details_id" required><option value=""></option></select><span id="avail_qty'+item_counter+'"></span></td><td><input type="text" name="qty[]" class="form-control text-right onlyNumber qty" id="qty'+item_counter+'" required autocomplete="off" ></td><td class="valign-middle"><span id="unit'+item_counter+'"></span></td><td><button type="button" class="btn btn-danger removeRow" ><i class="fa fa-minus"></i></button></td></tr>';

            $("#item_div").append(html); 
            $('select').select2({
                placeholder: '<?php echo get_phrases(['select','item']);?>'                
            });
        });


        $('body').on('click', '.removeRow', function() {
            var rowCount = $('#request_table >tbody >tr').length;
            if(rowCount > 1){
                $(this).parent().parent().remove();
                calculation_total();
            }else{
                toastr.warning('<?php echo get_notify("There_only_one_row_you_can_not_delete."); ?>');
            } 
        });

        $('.addShowModal').on('click', function(){
            $('.ajaxForm').removeClass('was-validated');
            $('#id').val('');
            $('#action').val('add');

            $('#sub_store_id').val('<?php echo $default_store_id; ?>').trigger('change');
            //$('#payment_method').val('').trigger('change');
            //$('#voucher_no').val('REQ-<?php //echo $voucher_no; ?>');   
            getMAXID('wh_machine_requests','id','voucher_no','REQ-');

            $('#main_store_id').val('').trigger('change');   
            $('#notes').val('');   

            var item_counter = 1;

            var html = '<tr data-counter="'+item_counter+'"><td><select name="item_id[]" id="item_id'+item_counter+'" class="form-control custom-select" onchange="item_info(this.value,'+item_counter+')" required><option value=""></option>'+<?php foreach($item_list as $items){?>
                                        '<option value="<?php echo $items->id;?>"><?php echo $items->nameE;?></option>'+
                                       <?php }?>
                                    '</select></td><td><select name="receive_details_id[]" id="receive_details_id'+item_counter+'" class="form-control custom-select receive_details_id" required><option value=""></option></select><span id="avail_qty'+item_counter+'"></span></td><td><input type="text" name="qty[]" class="form-control text-right onlyNumber qty" id="qty'+item_counter+'" required autocomplete="off" ></td><td class="valign-middle"><span id="unit'+item_counter+'"></span></td><td><button type="button" class="btn btn-success addRow" ><i class="fa fa-plus"></i></button></td></tr>';      
            $("#item_div").html(html); 
            $("#item_counter").val(item_counter);
            //calculation(1);
            $('select').select2({
                placeholder: '<?php echo get_phrases(['select','item']);?>'                
            });
            $('#item_requestModalLabel').text('<?php echo get_phrases(['manual','consumption','entry']);?>');
            $('.modal_action').text('<?php echo get_phrases(['send']);?>');
            $('.modal_action').prop('disabled', false);
            $('#item_request-modal').modal('show');
        });

        $('#item_requestList').on('click', '.actionPreview', function(e){
            e.preventDefault();
            $('.ajaxForm').removeClass('was-validated');
            //$('#thumbpic').next('span').text('');
            $('#item_details_preview').html('');
            
            var id = $(this).attr('data-id');
            var submit_url = _baseURL+'machine/getItemRequestDetailsById/'+id;

            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'JSON',
                data: {'csrf_stream_name':csrf_val},
                async: false,
                success: function(data) {
                    $('#itemRequestDetails-modal').modal('show');
                    $('#itemRequestDetailsModalLabel').text('<?php echo get_phrases(['consumption','entry','details']);?>');

                    //$('#itemRequestDetails_sub_store_id').text(data.sub_store_name);
                    $('#itemRequestDetails_date').text(data.date);
                    $('#itemRequestDetails_voucher_no').text(data.voucher_no);
                    //$('#itemRequestDetails_main_store_id').text(data.main_store_name);
                    //$('#itemRequestDetails_notes').text(data.notes);
                    //$('#itemRequestDetails_status').text(data.status_text+' '+data.status_collected);

                    $('#print').hide();
                    $('#approve').hide();
                    $('.printing_info').hide();
                    
                    get_item_details(id);

                },error: function() {

                }
            });   

        });

        $('#item_requestList').on('click', '.actionApprove', function(e){
            e.preventDefault();
            $('.ajaxForm').removeClass('was-validated');
            //$('#thumbpic').next('span').text('');
            $('#item_details_preview').html('');
            
            var id = $(this).attr('data-id');
            var submit_url = _baseURL+'machine/getItemRequestDetailsById/'+id;

            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'JSON',
                data: {'csrf_stream_name':csrf_val},
                async: false,
                success: function(data) {
                    $('#itemRequestDetails-modal').modal('show');
                    $('#itemRequestDetailsModalLabel').text('<?php echo get_phrases(['consumption','entry','details']);?>');

                    //$('#itemRequestDetails_sub_store_id').text(data.sub_store_name);
                    $('#itemRequestDetails_date').text(data.date);
                    $('#itemRequestDetails_voucher_no').text(data.voucher_no);
                    //$('#itemRequestDetails_main_store_id').text(data.main_store_name);
                    //$('#itemRequestDetails_notes').text(data.notes);
                    //$('#itemRequestDetails_status').text(data.status_text+' '+data.status_collected);

                    $('#sub_store_id2').val(data.sub_store_id);
                    $('#main_store_id2').val(data.main_store_id);

                    $('#id2').val(id);
                    $('#action2').val('approve');
                    $('#print').hide();
                    $('#approve').show();
                    $('#approve').text('<?php echo get_phrases(['confirm']);?>');
                    $('#approve').prop('disabled', false);
                    $('.printing_info').hide();
                    
                    get_item_details(id);

                },error: function() {

                }
            });   

        });


        $('#item_requestList').on('click', '.actionPrint', function(e){
            e.preventDefault();
            $('.ajaxForm').removeClass('was-validated');
            //$('#thumbpic').next('span').text('');
            $('#item_details_preview').html('');
            
            var id = $(this).attr('data-id');
            var submit_url = _baseURL+'machine/getItemRequestDetailsById/'+id;

            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'JSON',
                data: {'csrf_stream_name':csrf_val},
                async: false,
                success: function(data) {
                    $('#itemRequestDetails-modal').modal('show');
                    $('#itemRequestDetailsModalLabel').text('<?php echo get_phrases(['print','preview']);?>');

                    //$('#itemRequestDetails_sub_store_id').text(data.sub_store_name);
                    $('#itemRequestDetails_date').text(data.date);
                    $('#itemRequestDetails_voucher_no').text(data.voucher_no);
                    //$('#itemRequestDetails_main_store_id').text(data.main_store_name);
                    //$('#itemRequestDetails_notes').text(data.notes);
                    //$('#itemRequestDetails_status').text(data.status_text+' '+data.status_collected);

                    $('#print').show();
                    $('#approve').hide();
                    $('.printing_info').show();
                    
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
                { "bSortable": false, "aTargets": [4] },
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
                        columns: [ 0, 1, 2, 3 ]
                    }
                },
                {
                    extend: 'excelHtml5',
                    text: '<i class="far fa-file-excel custool" title="Excel"></i>',
                    titleAttr: 'Excel',
                    className: 'btn-light',
                    title : 'ItemRequest_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3 ]
                    }
                },
                {
                    extend: 'csvHtml5',
                    text: '<i class="far fa-file-alt custool" title="CSV"></i>',
                    titleAttr: 'CSV',
                    className: 'btn-light',
                    title : 'ItemRequest_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3 ]
                    }
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="far fa-file-pdf custool" title="PDF"></i>',
                    titleAttr: 'PDF',
                    className: 'btn-light',
                    title : 'ItemRequest_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3 ]
                    }
                }
                <?php } ?>
            ],
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'ajax': {
               'url': _baseURL + 'machine/getItemRequest',
               'data':function ( d ) {
                        d.csrf_stream_name = csrf_val;
                        d.store_id = $('#filter_store_id').val();
                        d.sub_store_id = $('#filter_sub_store_id').val();
                        d.voucher_no = $('#filter_voucher_no').val();
                        d.date = $('#filter_date').val();
                    }
            },
          'columns': [
             { data: 'id' },
             { data: 'voucher_no' },
             { data: 'date' },
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
            var submit_url = _baseURL+'machine/getItemRequestById/'+id;

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
            
            var submit_url = _baseURL+"machine/deleteItemRequest/"+id;
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

        var sub_store_id = $('#sub_store_id').val();
        var main_store_id = $('#main_store_id').val();
        
        $.ajax({
            url: _baseURL+"machine/getItemDetailsById/"+item_id,
            type: 'POST',
            data: {'csrf_stream_name':csrf_val, item_id: item_id, sub_store_id: sub_store_id, main_store_id: main_store_id},
            dataType:"JSON",
            async: false,
            success: function (data) {
                $('#unit'+sl).html(data.unit_name);

                $('#receive_details_id'+sl).html(data.batch);
                $('#avail_qty'+sl).html(data.hidden);
                
                $('#qty'+sl).val('');
                $('#qty'+sl).prop('readonly', false);

            }
        });
    }

    function get_item_details(request_id){

        if(request_id !='' ){
            var submit_url = _baseURL+'machine/getItemRequestQuantityDetailsById';

            preloader_ajax();
            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'html',
                async: false,
                data: {'csrf_stream_name':csrf_val, 'request_id':request_id },
                success: function(data) {
                    $('#item_details_preview').html(data);
                }
            });
        } else {
            $('#item_details_preview').html('');
        }
    }

</script>