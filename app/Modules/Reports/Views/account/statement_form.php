<div class="row">
    <div class="col-sm-12">
        <?php if($permission->method('account_statement', 'create')->access() || $permission->method('account_statement', 'read')->access()){ ?>
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
                <div class="row">
                    <div class="col-md-4 col-sm-12">
                        <div class="row">
                            <div class="col-md-10 col-sm-12">
                                 <label for="branch_id" class="col-form-label-custom font-weight-600"><?php echo get_phrases(['select', 'branch', 'name']) ?> <i class="text-danger">*</i></label>
                                <div class="form-group">
                                    <?php echo form_dropdown('branch_id','','','class="custom-select" id="branch_id" required="required"');?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8 col-sm-12">
                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="head_code" class="col-form-label-custom font-weight-600"><?php echo get_phrases(['select', 'account', 'name']) ?> <i class="text-danger">*</i></label>
                                    <?php echo form_dropdown('head_code','','','class="custom-select" id="head_code" required="required"');?>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label for="account_date" class="col-form-label-custom font-weight-600"><?php echo get_phrases(['date', 'range']) ?> <i class="text-danger">*</i></label>
                                    <input type="text" name="account_date" id="account_date" class="form-control reportrange1" autocomplete="off" placeholder="<?php echo get_phrases(['select', 'date']);?>" required>
                                </div>
                            </div>
                            <div class="col-md-2 col-sm-12">
                                <div class="form-group mt-3 py-1">
                                    <button type="submit" class="btn btn-sm btn-success rounded-pill mt-1 accountBtn"><?php echo get_phrases(['filter']);?></button>
                                </div>
                                <input type="hidden" name="account_number" id="account_number">
                                <input type="hidden" name="account_name" id="account_name">
                                <input type="hidden" name="full_name" id="full_name">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row" id="printC">
                    <div class="col-md-12">
                        <div id="results"></div>
                    </div>
                </div>
            </div>

        </div>
        <?php }else{ ?>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <strong class="fs-20 text-danger"><?php echo get_phrases(['you_do_not_have_permission_to_access_please_contact_with_administrator']);?></strong>
                    </div>
                </div>
            </div>
        </div>
        <?php }?>
    </div>
</div>

<!-- view voucher details modal -->
<div class="modal fade bd-example-modal-xl" id="viewVoucherModal" tabindex="-1" role="dialog" aria-labelledby="moduleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="viewVoucherModalLabel"><?php echo get_phrases(['voucher', 'details']);?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
            <div class="modal-body">
               <div class="row">
                   <div class="col-md-12" id="viewVoucherResult"></div>
               </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo get_phrases(['close']);?></button>
            </div>
            
        </div>
    </div>
</div>


<script type="text/javascript">

    var showCallBackData = function () {
        $('#id').val('');        
        $('#action').val('add');        
        $('.ajaxForm')[0].reset();        
        $('#invoices-modal').modal('hide');
        $('#invoicesList').DataTable().ajax.reload(null, false);
    }

    $(document).ready(function() { 
       "use strict";
        $('option:first-child').val('').trigger('change');

        // search employee
        $('#head_code').select2({
            placeholder: '<?php echo get_phrases(['search', 'account', 'name']);?>',
            minimumInputLength: 2,
                ajax: {
                    url: _baseURL+'reports/account/accountList',
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
        }).on('select2:select', function(e){
            var dat = e.params.data;
            var fullName = dat.text;
            var name = fullName.split('-');
            $('#account_number').val(dat.id);
            $('#account_name').val(name[1].trim());
        });


        // get account statement
        $('.accountBtn').on('click', function(e){
            e.preventDefault();

            var branchId  = $('#branch_id').val();
            var userId    = $('#head_code').val();
            var date      = $('#account_date').val();
            var accNumber = $('#account_number').val();
            var accName   = $('#account_name').val();
    
            if(userId && date){
                preloader_ajax();
                var submit_url = _baseURL+"reports/account/getStatements"; 
                $.ajax({
                    type: 'POST',
                    url: submit_url,
                    data: {'csrf_stream_name':csrf_val, branch_id:branchId, user_id:userId, date_range:date},
                    dataType: 'JSON',
                    success: function(res) {
                        $('#results').html('');
                        $('#results').html(res.data);
                        $('#title').text('');
                        $('#acc_number').text('');
                        $('#acc_name').text('');
                        $('#acc_number').text(accNumber);
                        $('#acc_name').text(accName);
                        $('#title').text(date);
                    }
                });  
            }else{
                toastr.warning('<?php echo get_notify('Please_fillup_all_fields'); ?>');
            }
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
            $("#branch_id").val('<?php echo session('branchId');?>').trigger('change');
            $("#branch_id").attr('readonly', true);
        });

         // view voucher info
        $(document).on('click', '.viewVoucher', function(e){
            e.preventDefault();
            
            var data = $(this).attr('data-id');
            var arr = data.split('-');
            
            if(arr[0]=='GRECI'){
                get_receive_info(data);
            } else if(arr[0]=='GRETI'){
                get_return_info(data);
            } else if(arr[0]=='SUPI'){
                get_payment_info(data);
            } else if(arr[0]=='CONS'){
                get_consumption_info(data);
            } else if(data){
                $('#viewVoucherModal').modal('show');
                var submit_url = _baseURL+"reports/management/getVoucherDetails"; 
                $.ajax({
                    type: 'POST',
                    url: submit_url,
                    data: {'csrf_stream_name':csrf_val, voucherId:data},
                    dataType: 'JSON',
                    success: function(res) {
                        $('#viewVoucherResult').html('');
                        $('#viewVoucherResult').html(res.data);
                    }
                });  
            } else{
                toastr.warning('<?php echo get_notify('Wrong_voucher'); ?>');
            }
        });

         // view details invoice info
        $(document).on('click', '.viewSupVoucher', function(e){
             e.preventDefault();
            
            var data = $(this).attr('data-id');
            var arr = data.split('-');
            
            if(arr[0]=='GRECI'){
                get_receive_info(data);
            } else if(arr[0]=='GRETI'){
                get_return_info(data);
            } else {
                get_payment_info(data);
            }

        });


        // view journal voucher
        $('.viewJV').on('click', function(e){
            e.preventDefault();
            var VNo = $(this).attr('data-id');
            $('#jv-modal').modal('show');
            var submit_url = _baseURL+"account/vouchers/getVoucherDetails/"+VNo; 
            $.ajax({
                type: 'POST',
                url: submit_url,
                data: {'csrf_stream_name':csrf_val},
                dataType: 'JSON',
                success: function(response) {
                    $('#viewDetails').html('');
                    $('#viewDetails').html(response.data);
                }
            });  
        });

    });

    function get_receive_info(voucher_no){
        if(voucher_no){
            $('#itemReceiveDetails-modal').modal('show');
            $('#itemReceiveDetails-modal .modal-body').addClass('dimmer');
            $('#itemReceiveDetailsModalLabel').text('<?php echo get_phrases(['print','preview']);?>');

            var submit_url = _baseURL+"reports/account/getReceiveInfo"; 
            $.ajax({
                type: 'POST',
                url: submit_url,
                data: {'csrf_stream_name':csrf_val, voucher_no:voucher_no},
                dataType: 'JSON',
                success: function(res) {
                    itemReceiveDetails(res.purchase_id);
                }
            });  
        }else{
            toastr.warning('<?php echo get_notify('Wrong_voucher'); ?>');
        }
    }

    function get_return_info(voucher_no){
        if(voucher_no){
            $('#itemReturnDetails-modal').modal('show');
            $('#itemReturnDetails-modal .modal-body').addClass('dimmer');
            $('#itemReturnDetailsModalLabel').text('<?php echo get_phrases(['print','preview']);?>');

            var submit_url = _baseURL+"reports/account/getReturnInfo"; 
            $.ajax({
                type: 'POST',
                url: submit_url,
                data: {'csrf_stream_name':csrf_val, voucher_no:voucher_no},
                dataType: 'JSON',
                success: function(res) {
                    itemReturnDetails(res.purchase_id);
                }
            });  
        }else{
            toastr.warning('<?php echo get_notify('Wrong_voucher'); ?>');
        }
    }

    function get_payment_info(voucher_no){
        if(voucher_no){
            $('#supplierPaymentDetails-modal').modal('show');
            $('#supplierPaymentDetails-modal .modal-body').addClass('dimmer');
            $('#supplierPaymentDetailsModalLabel').text('<?php echo get_phrases(['print','preview']);?>');

            var submit_url = _baseURL+"reports/account/getPaymentInfo"; 
            $.ajax({
                type: 'POST',
                url: submit_url,
                data: {'csrf_stream_name':csrf_val, voucher_no:voucher_no},
                dataType: 'JSON',
                success: function(res) {
                    supplierPaymentDetails(res.id, res.purchase_id);
                }
            });  
        }else{
            toastr.warning('<?php echo get_notify('Wrong_voucher'); ?>');
        }
    }

    function get_consumption_info(voucher_no){
        if(voucher_no){
            $('#itemRequestDetails-modal').modal('show');
            $('#itemRequestDetails-modal .modal-body').addClass('dimmer');
            $('#itemRequestDetailsModalLabel').text('<?php echo get_phrases(['print','preview']);?>');

            var submit_url = _baseURL+"reports/account/getConsumptionInfo"; 
            $.ajax({
                type: 'POST',
                url: submit_url,
                data: {'csrf_stream_name':csrf_val, voucher_no:voucher_no},
                dataType: 'JSON',
                success: function(res) {
                    consumptionDetails(res.id);
                }
            });  
        }else{
            toastr.warning('<?php echo get_notify('Wrong_voucher'); ?>');
        }
    }

    function consumptionDetails(id){
        //e.preventDefault();
        //$('.ajaxForm').removeClass('was-validated');
        
        //var id = $(this).attr('data-id');
        var submit_url = _baseURL+'inventory/consumption/getItemRequestDetailsById/'+id;

        $.ajax({
            type: 'POST',
            url: submit_url,
            dataType : 'JSON',
            data: {'csrf_stream_name':csrf_val},
            success: function(data) {

                $('#itemRequestDetails-modal .modal-body').removeClass('dimmer');
                //$('#itemRequestDetails-modal').modal('show');
                //$('#itemRequestDetailsModalLabel').text('<?php //echo get_phrases(['print','preview']);?>');

                $('#itemRequestDetails_sub_store_id').text(data.sub_store_name);
                $('#itemRequestDetails_date').text(data.date);
                $('#itemRequestDetails_voucher_no').text(data.voucher_no);
                $('#itemRequestDetails_from_department_id').text(data.department_name);
                $('#itemRequestDetails_notes').text(data.notes);
                $('#itemRequestDetails_status').text(data.status_text+' '+data.status_collected+' '+data.status_returned);
                $('#itemRequestDetails_request_by').text(data.request_by_name);
                $('#itemRequestDetails_consumed_by').text(data.consumed_by);
                if(data.consumed_by == 'service'){
                    $('.consumed_service_info').show();
                    $('#itemRequestDetails_invoice_id').html('SINV-'+data.invoice_id);
                    $('#itemRequestDetails_invoice_id').attr('data-id','SINV-'+data.invoice_id);
                    $('#itemRequestDetails_service').text(data.service);
                    $('#itemRequestDetails_patient').text(data.patient);
                    $('#itemRequestDetails_doctor').text(data.doctor_name);
                } else {
                    $('.consumed_service_info').hide();
                }
                
                $('#cons_print').show();
                $('.printing_info').show();
                $('.printing_info_return').hide();
                $('#cons_journal_voucher').show();
                $('#cons_journal_voucher').attr('data-id', data.voucher_no);

                get_cons_item_details(id, 'print');

            },error: function() {
                toastr.error('<?php echo get_notify('error'); ?>');
            }
        }); 
        
    }

    function get_cons_item_details(request_id, action){

        if(request_id !='' ){
            var submit_url = _baseURL+'inventory/consumption/getItemRequestQuantityDetailsById';
            //var action = $('#action2').val();

            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'html',
                data: {'csrf_stream_name':csrf_val, 'request_id':request_id, 'action':action },
                success: function(data) {
                    $('#cons_item_details_preview').html(data);
                    if(action == 'print'){
                        $('.return_info').hide();
                    } else {
                        $('.return_info').show();
                    }
                    /*if(action == 'print_return'){
                        $('.consume_info').hide();
                    } else {
                        $('.consume_info').show();
                    }*/

                    <?php if( $permission->method('item_consume_price', 'read')->access() ){ ?>         
                        $('.price_text').show();
                    <?php } else { ?>
                        $('.price_text').hide();
                    <?php } ?>

                }
            });
        } else {
            $('#cons_item_details_preview').html('');
        }
    }

    function supplierPaymentDetails(id, purchase_id){

        //$('#supplier_paymentList').on('click', '.printPreview', function(e){
            //e.preventDefault();
            //$('.ajaxForm').removeClass('was-validated');
            //$('#thumbpic').next('span').text('');
                       
            //var receive_id = $(this).attr('receive-id');
            //var id = $(this).attr('data-id');
            //var purchase_id = $(this).attr('purchase-id');
            var submit_url = _baseURL+'inventory/getItemReceiveDetailsById/'+purchase_id;

            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'JSON',
                data: {'csrf_stream_name':csrf_val},
                success: function(data) {
                    $('#supplierPaymentDetails-modal .modal-body').removeClass('dimmer');
                    // $('#supplierPaymentDetails-modal').modal('show');
                    // $('#supplierPaymentDetailsModalLabel').text('<?php //echo get_phrases(['print','preview']);?>');

                    $('#supplierPaymentDetails_supplier_id').text(data.supplier_name);
                    $('#supplierPaymentDetails_store_id').text(data.store_name);
                    $('#supplierPaymentDetails_receive_voucher_no').text(data.receive_voucher_no);
                    $('#supplierPaymentDetails_receive_date').text(data.receive_date);         

                    $('.payment_details').show();  

                    get_payment_details_preview(id);

                },error: function() {

                }
            });  

        //});
    }

    function get_payment_details_preview(id){

        if(id !='' ){
            var submit_url = _baseURL+'inventory/getPaymentDetailsById';

            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'json',
                data: {'csrf_stream_name':csrf_val, 'id':id },
                success: function(data) {
                    $("#preview_paid").html(data.paid_amount);
                    $("#preview_due").html(data.due);
                    $('#preview_voucher_no').html(data.voucher_no);
                    $('#preview_date').html(data.paid_date);
                    
                    $('#sup_journal_voucher').attr('data-id', data.voucher_no);

                    $('#print_payment_preview').html(data.html);
                    
                }
            });
        } else {
            $('#print_payment_preview').html('');
        }
    }

    function itemReceiveDetails(id){

        //$('#item_purchaseList').on('click', '.actionPreview', function(e){
             //e.preventDefault();
            //$('.ajaxForm').removeClass('was-validated');
            //$('#thumbpic').next('span').text('');
            
            //var id = $(this).attr('data-id');
            var submit_url = _baseURL+'inventory/getItemReceiveDetailsById/'+id;

            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'JSON',
                data: {'csrf_stream_name':csrf_val},
                success: function(data) {
                    $('#itemReceiveDetails-modal .modal-body').removeClass('dimmer');
                    //$('#itemReceiveDetails-modal').modal('show');
                    //$('#itemReceiveDetailsModalLabel').text('<?php //echo get_phrases(['print','preview']);?>');

                    $('#itemReceiveDetails_supplier_id').text(data.supplier_name);
                    $('#itemReceiveDetails_store_id').text(data.store_name);

                    $('#journal_voucher').attr('data-id', data.receive_voucher_no);
                    $('#itemReceiveDetails_receive_voucher_no').text(data.receive_voucher_no);
                    $('#itemReceiveDetails_receive_date').text(data.receive_date);                    
                    $('#itemReceiveDetails_vat').text(data.vat);                    
                    $('#itemReceiveDetails_due').text(data.due);                    
                    $('#itemReceiveDetails_receipt').text(data.receipt);                    
                    //$('#itemReceiveDetails_payment_method').text(data.payment_method);                    
                    $('#itemReceiveDetails_receive_sub_total').text((data.receive_sub_total)?data.receive_sub_total:0);
                    $('#itemReceiveDetails_receive_grand_total').text((data.receive_grand_total)?data.receive_grand_total:0);

                    get_item_details(id);

                },error: function() {

                }
            });  

        //});

    }

    function itemReturnDetails(id){

        //$('#item_purchaseList').on('click', '.actionPreviewReturn', function(e){
             //e.preventDefault();
            //$('.ajaxForm').removeClass('was-validated');
            //$('#thumbpic').next('span').text('');
            
            //var id = $(this).attr('data-id');
            var submit_url = _baseURL+'inventory/getReturnDetailsById/'+id;

            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'JSON',
                data: {'csrf_stream_name':csrf_val},
                success: function(data) {
                    $('#itemReturnDetails-modal .modal-body').removeClass('dimmer');
                    //$('#itemReturnDetails-modal').modal('show');
                    //$('#itemReturnDetailsModalLabel').text('<?php //echo get_phrases(['print','preview']);?>');

                    $('#itemReturnDetails_supplier_id').text(data.supplier_name);
                    $('#itemReturnDetails_store_id').text(data.store_name);

                    $('#journal_voucher_return').attr('data-id', data.return_voucher_no);
                    $('#itemReturnDetails_return_voucher_no').text(data.return_voucher_no);
                    $('#itemReturnDetails_return_date').text(data.return_date);   
                    //$('#itemReturnDetails_due').text(data.due);                    
                    //$('#itemReturnDetails_receipt').text(data.receipt);                    
                    //$('#itemReturnDetails_payment_method').text(data.payment_method);                    
                    $('#itemReturnDetail_return_sub_total').text((data.return_sub_total)?data.return_sub_total:0);
                    $('#itemReturnDetails_return_vat').text(data.return_vat);                    
                    $('#itemReturnDetails_return_grand_total').text((data.return_grand_total)?data.return_grand_total:0);

                    get_return_item_details(id);

                },error: function() {

                }
            });  

        //});
    }

    function get_item_details(purchase_id){

        if(purchase_id !='' ){
            var submit_url = _baseURL+'inventory/getItemPricingDetailsById';

            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'html',
                data: {'csrf_stream_name':csrf_val, 'purchase_id':purchase_id },
                success: function(data) {
                    $('#item_details_preview').html(data);
                }
            });
        } else {
            $('#item_details_preview').html('');
        }
    }


    function get_return_item_details(purchase_id){

        if(purchase_id !='' ){
            var submit_url = _baseURL+'inventory/getReturnItemDetailsById';

            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'html',
                data: {'csrf_stream_name':csrf_val, 'purchase_id':purchase_id },
                success: function(data) {
                    $('#return_item_details_preview').html(data);
                }
            });
        } else {
            $('#return_item_details_preview').html('');
        }
    }

    // view details invoice info
    $(document).on('click', '.invoiceDetails', function(e){
        e.preventDefault();
        $('#viewDModal').modal('show');
        var voucherId = $(this).attr('data-id');
        //var Id = $(this).attr('data-id');
        //var type = $(this).attr('data-type');
        //var typeId = type+'-'+Id;
        //if(Id && type){
        $('#invoiceDetails').html('');
        if(voucherId){
            var submit_url = _baseURL+"reports/management/getVoucherDetails"; 
            $.ajax({
                type: 'POST',
                url: submit_url,
                data: {'csrf_stream_name':csrf_val, voucherId:voucherId},
                dataType: 'JSON',
                success: function(res) {
                    $('#invoiceDetails').html('');
                    $('#invoiceDetails').html(res.data);
                }
            });  
        }else{
            toastr.warning('<?php echo get_notify('Wrong_Invoice!'); ?>');
        }
    });
</script>

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
            <div class="modal-body" id="printContent_cons">

                <div class="row printing_info">
                    <div class="col-sm-12 text-center">
                        <img src="<?php echo base_url().$settings_info->logo; ?>" alt="Logo" height="40px" ><br><br>
                        <h6><?php echo $settings_info->title.' ( '.$settings_info->nameA.' )'; ?></h6>
                        <h5><?php echo get_phrases(['item', 'consumption','voucher']) ?></h5>
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
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['department']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemRequestDetails_from_department_id"></div>                        
                    </div>
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['from','store']) ?> : </label>
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
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['consumed','by']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemRequestDetails_consumed_by" ></div>                        
                    </div>
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['request','by']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemRequestDetails_request_by" ></div>                        
                    </div>
                </div>

                <div class="row">
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['notes']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemRequestDetails_notes" ></div>                        
                    </div>
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['status']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemRequestDetails_status" ></div>                        
                    </div>
                </div>

                <div class="row consumed_service_info" >
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['patient']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemRequestDetails_patient" ></div>                        
                    </div>
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['doctor']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemRequestDetails_doctor" ></div>                        
                    </div>
                </div>

                <div class="row consumed_service_info">
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['invoice','no']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemRequestDetails_invoice_id" data-id=""></div>                        
                    </div>
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['service']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemRequestDetails_service" ></div>                        
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

                <div class="row form-group">
                    <div class="col-sm-12">
                        <div id="cons_item_details_preview"></div>
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
                    <button type="button" class="btn btn-info viewJV" id="cons_journal_voucher" data-id=""><span class="fa fa-eye"></span> <?php echo get_phrases(['journal', 'voucher']); ?></button>
                <?php } ?>
                <button type="button" id="cons_print" class="btn btn-success" onclick="printContent('printContent_cons')"><?php echo get_phrases(['print']);?></button>
            </div>
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
                   <div class="col-md-12" id="invoiceDetails">
                        
                   </div>
               </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo get_phrases(['close']);?></button>
            </div>
            
        </div>
    </div>
</div>

<!-- item receive modal button -->
<div class="modal fade bd-example-modal-xl" id="itemReceiveDetails-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel3" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="itemReceiveDetailsModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
            <div class="modal-body" id="printContent_rec">
                <div class="row">
                    <div class="col-sm-12 text-center">
                        <h5><?php echo get_phrases(['received', 'item', 'invoice']) ?></h5>
                        <strong><?php echo get_phrases(['date']).': '.date('d/m/Y'); ?></strong>
                        <hr>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['from','supplier']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemReceiveDetails_supplier_id" ></div>                        
                    </div>
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['to','store']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemReceiveDetails_store_id"></div>                        
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['voucher', 'no']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemReceiveDetails_receive_voucher_no" ></div>                        
                    </div>
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['date']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemReceiveDetails_receive_date"></div>                        
                    </div>
                </div>

                <div id="item_details_preview"></div>

                <div class="row">                    
                    <label class="col-sm-10 text-right font-weight-600"><?php echo get_phrases(['sub', 'total']) ?> : </label>
                    <div class="col-sm-2 text-right">
                        <div id="itemReceiveDetails_receive_sub_total" ></div>                        
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-10 text-right font-weight-600"><?php echo get_phrases(['vat']) ?> : </label>
                    <div class="col-sm-2 text-right">
                        <div id="itemReceiveDetails_vat" ></div>                        
                    </div>
                </div>
                <div class="row">                    
                    <label class="col-sm-10 text-right font-weight-600"><?php echo get_phrases(['grand', 'total']) ?> : </label>
                    <div class="col-sm-2 text-right">
                        <div id="itemReceiveDetails_receive_grand_total" ></div>                        
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-10 text-right font-weight-600"><?php echo get_phrases(['total', 'paid']) ?> : </label>
                    <div class="col-sm-2 text-right">
                        <div id="itemReceiveDetails_receipt" ></div>                        
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-10 text-right font-weight-600"><?php echo get_phrases(['total', 'due']) ?> : </label>
                    <div class="col-sm-2 text-right">
                        <div id="itemReceiveDetails_due" ></div>                        
                    </div>
                </div>
                

                <hr>
                <div class="row">
                    <div class="col-sm-6">
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
                <?php if($permission->method('journal_voucher', 'read')->access()){ ?>
                    <button type="button" class="btn btn-info viewJV" id="journal_voucher" data-id=""><span class="fa fa-eye"></span> <?php echo get_phrases(['journal', 'voucher']); ?></button>
                <?php } ?>
                <button type="button" class="btn btn-success" onclick="printContent('printContent_rec')"><?php echo get_phrases(['print']);?></button>
                
            </div>
            
        </div>
    </div>
</div>


<!-- item receive modal button -->
<div class="modal fade bd-example-modal-xl" id="itemReturnDetails-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel3" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="itemReturnDetailsModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
            <div class="modal-body" id="printContent_ret">
                <div class="row">
                    <div class="col-sm-12 text-center">
                        <h5><?php echo get_phrases(['supplier','return', 'invoice']) ?></h5>
                        <strong><?php echo get_phrases(['date']).': '.date('d/m/Y'); ?></strong>
                        <hr>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['from','store']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemReturnDetails_store_id"></div>                        
                    </div>
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['to','supplier']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemReturnDetails_supplier_id" ></div>                        
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['voucher', 'no']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemReturnDetails_return_voucher_no" ></div>                        
                    </div>
                    <label class="col-sm-2 text-right font-weight-600"><?php echo get_phrases(['date']) ?> : </label>
                    <div class="col-sm-4">
                        <div id="itemReturnDetails_return_date"></div>                        
                    </div>
                </div>

                <div id="return_item_details_preview"></div>

                <div class="row">                    
                    <label class="col-sm-10 text-right font-weight-600"><?php echo get_phrases(['sub', 'total']) ?> : </label>
                    <div class="col-sm-2 text-right">
                        <div id="itemReturnDetail_return_sub_total" ></div>                        
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-10 text-right font-weight-600"><?php echo get_phrases(['vat']) ?> : </label>
                    <div class="col-sm-2 text-right">
                        <div id="itemReturnDetails_return_vat" ></div>                        
                    </div>
                </div>
                <div class="row">                    
                    <label class="col-sm-10 text-right font-weight-600"><?php echo get_phrases(['grand', 'total']) ?> : </label>
                    <div class="col-sm-2 text-right">
                        <div id="itemReturnDetails_return_grand_total" ></div>                        
                    </div>
                </div>
                

                <hr>
                <div class="row">
                    <div class="col-sm-6">
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
                    <button type="button" class="btn btn-info viewJV" id="journal_voucher_return" data-id=""><span class="fa fa-eye"></span> <?php echo get_phrases(['journal', 'voucher']); ?></button>
                <?php } ?>
                <button type="button" class="btn btn-success" onclick="printContent('printContent_ret')"><?php echo get_phrases(['print']);?></button>
                
            </div>
            
        </div>
    </div>
</div>



<div class="modal fade bd-example-modal-xl" id="supplierPaymentDetails-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel3" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="supplierPaymentDetailsModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
            <div class="modal-body" id="printContent_sup">
                
                <div class="row">
                    <div class="col-sm-12 text-center">
                        <h5><?php echo get_phrases(['supplier', 'payment', 'invoice']) ?></h5>
                        <strong><?php echo get_phrases(['date']).': '.date('d/m/Y'); ?></strong>
                        <hr>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['supplier'])?>: </label>
                    <div class="col-sm-4 mt-2">
                        <div id="supplierPaymentDetails_supplier_id" ></div>
                    </div>
                    <label class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['store'])?>: </label>
                    <div class="col-sm-4 mt-2">
                        <div id="supplierPaymentDetails_store_id" ></div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['receive','voucher','no'])?>: </label>
                    <div class="col-sm-4 mt-2">
                        <div id="supplierPaymentDetails_receive_voucher_no" ></div>
                    </div>
                    <label class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['receive','date'])?>: </label>
                    <div class="col-sm-4 mt-2">
                        <div id="supplierPaymentDetails_receive_date" ></div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['payment','voucher','no'])?>: </label>
                    <div class="col-sm-4 mt-2">
                        <div id="preview_voucher_no" ></div>
                    </div>
                    <label class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['payment','date'])?>: </label>
                    <div class="col-sm-4 mt-2">
                        <div id="preview_date" ></div>
                    </div>
                </div>
                <div class="row payment_details">
                    <div class="col-sm-12">                        
                        <div id="print_payment_preview"></div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-10 text-right font-weight-600"><?php echo ucwords(get_phrases(['total', 'payment'])) ?> : </label>
                    <div class="col-sm-2 text-right">
                        <div id="preview_paid" ></div>                        
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-10 text-right font-weight-600"><?php echo get_phrases(['due','amount']) ?> : </label>
                    <div class="col-sm-2 text-right">
                        <div id="preview_due" ></div>                        
                    </div>
                </div>              

                <hr>
                <div class="row">
                    <div class="col-sm-6">
                        <h6><?php echo get_phrases(['signature']).' ('.get_phrases(['payment', 'by']).')' ?></h6>
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
                <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo get_phrases(['close']);?></button>
                <button type="button" id="print" class="btn btn-success" onclick="printContent('printContent_sup')"><?php echo get_phrases(['print']);?></button>
                <?php if($permission->method('journal_voucher', 'read')->access()){ ?>
                 <button type="button" id="sup_journal_voucher" class="btn btn-info viewJV" data-id=""><span class="fa fa-eye"></span> <?php echo get_phrases(['journal', 'voucher']);?></button>
                <?php } ?>
            </div>
        </div>
    </div>
</div>


<!-- view voucher details -->
<div class="modal fade bd-example-modal-lg" id="jv-modal" tabindex="-1" role="dialog" aria-labelledby="detailsModalLabel3" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="detailsModalLabel"><?php echo get_phrases(['view', 'voucher', 'details']);?></h5>
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