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
                       
                       <a href="<?php echo previous_url(); ?>" class="btn btn-warning btn-sm mr-1 ml-2"><i class="fas fa-angle-double-left mr-1"></i><?php echo get_phrases(['back']);?></a>
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
                <table id="request_approveList" class="table display table-bordered table-striped table-hover compact" width="100%">
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

<!-- approve request modal button -->
<div class="modal fade bd-example-modal-xl" id="RequestApproveDetails-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel3" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="RequestApproveDetailsModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
            <?php echo form_open_multipart('order/add_request_approve', 'class="ajaxForm needs-validation" id="ajaxForm" novalidate="" data="showCallBackData"');?>
            <div class="modal-body">
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                <input type="hidden" name="id" id="id" />
                <input type="hidden" name="sub_store_id" id="sub_store_id" />
                <input type="hidden" name="dealer_id" id="dealer_id" />
                <input type="hidden" name="action" id="action" value="" />
                
                <div class="row">
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['dealer']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="RequestApproveDetails_dealer_id"></div>                        
                    </div>
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['store']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="RequestApproveDetails_sub_store_id" ></div>                        
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['voucher', 'no']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="RequestApproveDetails_voucher_no" ></div>                        
                    </div>
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['date']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="RequestApproveDetails_date"></div>                        
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['notes']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="RequestApproveDetails_notes" ></div>                        
                    </div>
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['request','by']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="RequestApproveDetails_request_by" ></div>                        
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-sm-12">
                        <div id="item_details_preview"></div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal"><?php echo get_phrases(['close']);?></button>
                <button id="reject" data-id="reject" class="btn btn-danger actionBtn" ><?php echo get_phrases(['reject']);?></button>
                <button id="approve" data-id="approve" class="btn btn-success actionBtn" ><?php echo get_phrases(['approve']);?></button>
                <button id="collect" data-id="collect" class="btn btn-success actionBtn" ><?php echo get_phrases(['receive']);?></button>
            </div>
            <?php echo form_close();?>
        </div>
    </div>
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

<script type="text/javascript">
 
    function reload_table(){
        $('#request_approveList').DataTable().ajax.reload();
    }

    function reset_table(){
        $('#filter_dealer_id').val('').trigger('change');
        //$('#filter_doctor_id').val('').trigger('change');
        $('#filter_store_id').val('').trigger('change');
        $('#filter_voucher_no').val('');
        $('#filter_date').val('');

        $('#request_approveList').DataTable().ajax.reload();
    }

    var showCallBackData = function () {
        $('#id').val('');        
        $('#action').val('update');        
        $('.ajaxForm')[0].reset();        
        $('#RequestApproveDetails-modal').modal('hide');
        $('#request_approveList').DataTable().ajax.reload(null, false);
    }
    $(document).ready(function() { 
       "use strict";
        $('option:first-child').val('').trigger('change');

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
        });*/

        $('#reject,#approve,#collect').on('click', function(e) {
            var type = $(this).attr('data-id');
            $('#action').val(type);

           if(type=='approve') {
                var result = check_quantity();
                if( !result ){
                    e.preventDefault();
                    return false;
                }                
            }
            if(type=='reject'){                
                var item_counter = parseInt($('#item_counter').val());
                for(var i=1; i<=item_counter; i++){
                    $('#request_details_id'+i).removeAttr('required');                    
                }                
            }
        });

        $('body').on('click', '.addRow', function() {
            var item_counter = parseInt($("#item_counter").val()); 
            item_counter += 1;
            $("#item_counter").val(item_counter);

            var html = ' <tr><td><select name="item_id[]" class="form-control custom-select" onchange="item_info(this.value,'+item_counter+')" required><option value=""></option>'+<?php foreach($item_list as $items){?>
                                        '<option value="<?php echo $items->id;?>"><?php echo $items->nameE;?></option>'+
                                       <?php }?>
                                    '</select></td><td><input type="text" name="qty[]" class="form-control text-right onlyNumber" id="qty'+item_counter+'" required autocomplete="off" ></td><td class="valign-middle"><span id="unit'+item_counter+'"></span></td><td><button type="button" class="btn btn-danger removeRow" ><i class="fa fa-minus"></i></button></td></tr>';

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

            $('#sub_store_id').val('').trigger('change');
            $('#dealer_id').val('').trigger('change');
            //$('#payment_method').val('').trigger('change');
            $('#voucher_no').val('');   
            $('#notes').val('');   

            var html = ' <tr><td><select name="item_id[]" class="form-control custom-select" onchange="item_info(this.value,1)" required><option value=""></option>'+<?php foreach($item_list as $items){?>
                                        '<option value="<?php echo $items->id;?>"><?php echo $items->nameE;?></option>'+
                                       <?php }?>
                                    '</select></td><td><input type="text" name="qty[]" class="form-control text-right onlyNumber" id="qty1" required autocomplete="off" ></td><td class="valign-middle"><span id="unit1"></span></td><td><button type="button" class="btn btn-success addRow" ><i class="fa fa-plus"></i></button></td></tr>';      
            $("#item_div").html(html); 
            $("#item_counter").val(1);
            //calculation(1);
            $('select').select2({
                placeholder: '<?php echo get_phrases(['select','item']);?>'                
            });
            $('#request_approveModalLabel').text('<?php echo get_phrases(['request', 'item']);?>');
            $('.modal_action').text('<?php echo get_phrases(['send']);?>');
            $('#request_approve-modal').modal('show');
        });

        $('#request_approveList').on('click', '.actionPreview', function(e){
            e.preventDefault();
            $('.ajaxForm').removeClass('was-validated');
            //$('#thumbpic').next('span').text('');
            $('#item_details_preview').html('');
            
            var id = $(this).attr('data-id');
            var submit_url = _baseURL+'order/getRequestApproveDetailsById/'+id;

            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'JSON',
                data: {'csrf_stream_name':csrf_val},
                async: false,
                success: function(data) {
                    $('#RequestApproveDetails-modal').modal('show');
                    $('#RequestApproveDetailsModalLabel').text('<?php echo get_phrases(['order','request','approval']);?>');
                    //$('.modal_action').text('<?php //echo get_phrases(['approve']);?>');
                    $('#id').val(data.id);

                    $('#RequestApproveDetails_sub_store_id').text(data.sub_store_name);
                    $('#RequestApproveDetails_date').text(data.date);
                    $('#RequestApproveDetails_voucher_no').text(data.voucher_no);
                    $('#RequestApproveDetails_dealer_id').text(data.dealer_name);
                    $('#RequestApproveDetails_notes').text(data.notes);
                    $('#RequestApproveDetails_request_by').text(data.request_by_name);
                    
                    
                    $('#sub_store_id').val(data.sub_store_id);
                    $('#dealer_id').val(data.dealer_id);
                    
                    $('#reject').show();
                    $('#reject').prop('disabled', false);
                    $('#approve').show();
                    $('#approve').prop('disabled', true);
                    $('#collect').hide();

                    get_item_details(id, data.sub_store_id, 'approve');


                },error: function() {

                }
            });   

        });

        $('#request_approveList').on('click', '.actionCollect', function(e){
            e.preventDefault();
            $('.ajaxForm').removeClass('was-validated');
            //$('#thumbpic').next('span').text('');
            $('#item_details_preview').html('');
            
            var id = $(this).attr('data-id');
            var submit_url = _baseURL+'order/getRequestApproveDetailsById/'+id;

            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'JSON',
                data: {'csrf_stream_name':csrf_val },
                async: false,
                success: function(data) {
                    $('#RequestApproveDetails-modal').modal('show');
                    $('#RequestApproveDetailsModalLabel').text('<?php echo get_phrases(['item','return','confirmation']);?>');
                    //$('.modal_action').text('<?php //echo get_phrases(['approve']);?>');
                    $('#id').val(data.id);

                    $('#RequestApproveDetails_sub_store_id').text(data.sub_store_name);
                    $('#RequestApproveDetails_date').text(data.date);
                    $('#RequestApproveDetails_voucher_no').text(data.voucher_no);
                    $('#RequestApproveDetails_dealer_id').text(data.dealer_name);
                    $('#RequestApproveDetails_notes').text(data.notes);
                    $('#RequestApproveDetails_request_by').text(data.request_by_name);
                    
                    
                    $('#sub_store_id').val(data.sub_store_id);
                    $('#dealer_id').val(data.dealer_id);

                    $('#reject').hide();
                    $('#approve').hide();
                    $('#collect').show();
                    $('#collect').prop('disabled', false);
                    
                    get_item_details(id, data.sub_store_id, 'collect');


                },error: function() {

                }
            });   

        });

         $('#request_approveList').on('click', '.actionView', function(e){
            e.preventDefault();
            $('.ajaxForm').removeClass('was-validated');
            //$('#thumbpic').next('span').text('');
            $('#item_details_preview').html('');
            
            var id = $(this).attr('data-id');
            var submit_url = _baseURL+'order/getRequestApproveDetailsById/'+id;

            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'JSON',
                data: {'csrf_stream_name':csrf_val },
                async: false,
                success: function(data) {
                    $('#RequestApproveDetails-modal').modal('show');
                    $('#RequestApproveDetailsModalLabel').text('<?php echo get_phrases(['order','request','details']);?>');
                    //$('.modal_action').text('<?php //echo get_phrases(['approve']);?>');
                    $('#id').val(data.id);

                    $('#RequestApproveDetails_sub_store_id').text(data.sub_store_name);
                    $('#RequestApproveDetails_date').text(data.date);
                    $('#RequestApproveDetails_voucher_no').text(data.voucher_no);
                    $('#RequestApproveDetails_dealer_id').text(data.dealer_name);
                    $('#RequestApproveDetails_notes').text(data.notes);
                    $('#RequestApproveDetails_request_by').text(data.request_by_name);
                    
                    
                    $('#sub_store_id').val(data.sub_store_id);
                    $('#dealer_id').val(data.dealer_id);

                    $('#reject').hide();
                    $('#approve').hide();
                    $('#collect').hide();
                    
                    get_item_details(id, data.sub_store_id, 'preview');


                },error: function() {

                }
            });   

        });

        $('#request_approveList').DataTable({ 
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
                    title : 'RequestApprove_List-<?php echo date('Y-m-d');?>',
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
                    title : 'RequestApprove_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5, 6 ]
                    }
                },
                {
                    extend: 'excelHtml5',
                    text: '<i class="far fa-file-excel custool" title="Excel"></i>',
                    titleAttr: 'Excel',
                    className: 'btn-light',
                    title : 'RequestApprove_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5, 6 ]
                    }
                },
                {
                    extend: 'csvHtml5',
                    text: '<i class="far fa-file-alt custool" title="CSV"></i>',
                    titleAttr: 'CSV',
                    className: 'btn-light',
                    title : 'RequestApprove_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5, 6 ]
                    }
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="far fa-file-pdf custool" title="PDF"></i>',
                    titleAttr: 'PDF',
                    className: 'btn-light',
                    title : 'RequestApprove_List-<?php echo date('Y-m-d');?>',
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
               'url': _baseURL + 'order/getRequestApprove',
               'data':function ( d ) {
                        d.csrf_stream_name = csrf_val;
                        d.dealer_id = $('#filter_dealer_id').val();
                        d.doctor_id = 0;//$('#filter_doctor_id').val();
                        d.store_id = $('#filter_store_id').val();
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

        $('#request_approveList').on('draw.dt', function() {
             $('.custool').tooltip(); 
        });
        
        $('#picture').on('change', function () {
            var fileExtension = ['jpeg', 'jpg', 'png', 'gif'];
            imgPreview($(this), fileExtension);
        }); 

        $('#request_approveList').on('click', '.actionEdit', function(e){
            e.preventDefault();
            $('.ajaxForm').removeClass('was-validated');
            //$('#thumbpic').next('span').text('');
            
            var id = $(this).attr('data-id');
            var submit_url = _baseURL+'order/getRequestApproveById/'+id;

            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'JSON',
                data: {'csrf_stream_name':csrf_val},
                async: false,
                success: function(data) {
                    $('#request_approve-modal').modal('show');
                    $('#id').val(data.id);
                    $('#action').val('update');
                    $('#request_approveModalLabel').text('<?php echo get_phrases(['update', 'request']);?>');
                    $('.modal_action').text('<?php echo get_phrases(['update']);?>');

                    $('#nameE').val(data.nameE);
                    $('#nameA').val(data.nameA);
                    $('#cat_id').val(data.cat_id).trigger('change');
                    $('#unit_id').val(data.unit_id).trigger('change');
                    $('#price').val(data.price);

                    

                },error: function() {

                }
            });   

        });
        // delete request_approve
        $('#request_approveList').on('click', '.actionDelete', function(e){
            e.preventDefault();

            var id = $(this).attr('data-id');
            
            var submit_url = _baseURL+"order/deleteRequestApprove/"+id;
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
                            $('#request_approveList').DataTable().ajax.reload(null, false);
                        }else{
                            toastr.error(res.message, '<?php echo get_phrases(["record"])?>');
                        }
                    },error: function() {

                    }
                });
            }   
        });
    });

    function change_avail_qty(id, item_counter){
        var avail_qty = $('#receive_avail_qty_'+id).val();
        $('#avail_qty'+item_counter).val(avail_qty);

        check_quantity();
    }
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


    function item_info(item_id,sl){
        //return;

        $.ajax({
            url: _baseURL+"order/getItemDetailsById/"+item_id,
            type: 'POST',
            data: {'csrf_stream_name':csrf_val,item_id: item_id},
            dataType:"JSON",
            async: false,
            success: function (data) {
                $('#unit'+sl).html(data.item.unit_name);
            }
        });
    }

    function get_item_details(request_id, sub_store_id, action){

        if(request_id !='' ){
            var submit_url = _baseURL+'order/getRequestApproveQuantityDetailsById';

            preloader_ajax();
            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'html',
                async: false,
                data: {'csrf_stream_name':csrf_val, 'request_id':request_id, 'sub_store_id':sub_store_id, 'action':action },
                success: function(data) {
                    $('#item_details_preview').html(data);
                    check_quantity();
                }
            });
        } else {
            $('#item_details_preview').html('');
        }
    }

    function check_quantity_exceed(sl){
        var req_qty = ($('#req_qty'+sl).val())?parseFloat($('#req_qty'+sl).val()):0;
        var aqty = ($('#aqty'+sl).val())?parseFloat($('#aqty'+sl).val()):0;
        var avail_qty = ($('#avail_qty'+sl).val())?parseFloat($('#avail_qty'+sl).val()):0;
        var min_qty = (avail_qty < req_qty)?avail_qty:req_qty;
        if(aqty > min_qty){
            toastr.warning('<?php echo get_notify('Approve_quantity_should_be_less_than_or_equal_to_stock_and_request_quantity'); ?>');
            $('#aqty'+sl).val(min_qty);
        }

        check_quantity();
    }

    function check_quantity(){
        var item_counter = parseInt($('#item_counter').val());
        var tot_qty = 0;
        var aqty = 0;
        for(var i=1; i<=item_counter; i++){
            aqty = ($('#aqty'+i).val()=='')?0:parseFloat($('#aqty'+i).val());
            tot_qty += aqty;

            if(aqty >0 ){
                $('#request_details_id'+i).attr('required','required');
            } else {
                $('#request_details_id'+i).removeAttr('required');
            }
        }
        if(tot_qty >0 ){
            $('#approve').prop('disabled', false);
            return true;
        } else {
            $('#approve').prop('disabled', true);
            return false;
        }
    }
</script>