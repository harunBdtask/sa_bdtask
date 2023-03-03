<link href="<?php echo base_url()?>/assets/plugins/jquery-ui-1.12.1/jquery-ui.min.css" rel="stylesheet">
<link href="<?php echo base_url()?>/assets/dist/css/lity.css" rel="stylesheet">
<div class="row">
    <div class="col-sm-12">
        <?php 
        $hasPrintAccess  = $permission->method('service_invoices', 'print')->access();
        $hasExportAccess = $permission->method('service_invoices', 'export')->access();
        if($permission->module('service_invoices')->access()){ ?>
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
                        <?php if($permission->method('service_invoices', 'create')->access()){ ?>
                       <a href="<?php echo base_url('account/services/addInvoice');?>" class="btn btn-success btn-sm mr-1"><i class="fas fa-plus mr-1"></i><?php echo get_phrases(['add', 'new', 'invoice']);?></a>
                        <?php }?>
                       <a href="<?php echo previous_url(); ?>" class="btn btn-warning btn-sm mr-1 ml-2"><i class="fas fa-angle-double-left mr-1"></i><?php echo get_phrases(['back']);?></a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <ul class="nav nav-pills mb-2" id="pills-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="invoice-tab" data-toggle="pill" href="#invoice" role="tab" aria-controls="invoice" aria-selected="false"><?php echo get_phrases(['invoice', 'list']);?></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="deleteInvoice-tab" data-toggle="pill" href="#deleteInvoice" role="tab" aria-controls="deleteInvoice" aria-selected="false"><?php echo get_phrases(['delete', 'invoice', 'list']);?></a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6 col-sm-12"></div>
                </div>
                <div class="tab-content" id="pills-tabContent">
                    <!-- active invoice list --> 
                    <div class="tab-pane fade show active" id="invoice" role="tabpanel" aria-labelledby="invoice-tab">
                        <div class="row form-group">
                            <div class="col-sm-2">
                                <?php 
                                    echo form_dropdown('branch_id','','','class="custom-select" id="branch_id"');
                                ?>             
                            </div>
                            <div class="col-sm-2">
                                <input type="text" name="search_date" id="search_date" class="form-control" placeholder="<?php echo get_phrases(['select', 'date']); ?>" autocomplete="off">
                            </div>
                            <div class="col-sm-2">
                                <input type="text" name="file_no" id="file_no" class="form-control" placeholder="<?php echo get_phrases(['enter', 'file', 'number']); ?>" autocomplete="off">
                            </div>   
                            <div class="col-sm-2">
                                <input type="text" name="search_invoice_id" id="search_invoice_id" class="form-control" placeholder="<?php echo get_phrases(['enter', 'invoice', 'id']); ?>" autocomplete="off">
                            </div>
                            <div class="col-sm-2">
                                <?php $arr = array(''=>'', '1'=>get_phrases(['paid']), '0'=>get_phrases(['unpaid']), '2'=>get_phrases(['credited']), '3'=>get_phrases(['delete', 'request'])) ;
                                echo form_dropdown('paid',$arr,'','class="custom-select" id="paid"');?>
                            </div>
                            <div class="col-sm-2">
                                <button type="button" class="btn btn-success text-white" id="filtering"><?php echo get_phrases(['filter']);?></button>
                                <button type="button" class="btn btn-warning resetBtn text-white"><?php echo get_phrases(['reset']);?></button>
                            </div>   
                        </div>

                        <table id="serInvList" class="table display table-bordered table-striped table-hover compact" width="100%">
                            <thead>
                                <tr>
                                    <th><?php echo get_phrases(['invoice', 'id']);?></th>
                                    <th><?php echo get_phrases(['branch', 'name']);?></th>
                                    <th><?php echo get_phrases(['file', 'number']);?></th>
                                    <th><?php echo get_phrases(['patient', 'name']);?></th>
                                    <th><?php echo get_phrases(['grand', 'total']);?></th>
                                    <th><?php echo get_phrases(['doctor', 'name']);?></th>
                                    <th><?php echo get_phrases(['payment','doctor', 'selected']);?></th>
                                    <th><?php echo get_phrases(['created', 'by']);?></th>
                                    <th><?php echo get_phrases(['created', 'date']);?></th>
                                    <th><?php echo get_phrases(['status']);?></th>
                                    <th><?php echo get_phrases(['action']);?></th>
                                </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                        </table>
                    </div>
                     <!-- delete invoice list -->
                    <div class="tab-pane fade" id="deleteInvoice" role="tabpanel" aria-labelledby="deleteInvoice-tab">
                        <table id="deleteList" class="table display table-bordered table-striped table-hover compact" width="100%">
                            <thead>
                                <tr>
                                    <th><?php echo get_phrases(['invoice', 'id']);?></th>
                                    <th><?php echo get_phrases(['branch', 'name']);?></th>
                                    <th><?php echo get_phrases(['patient', 'name']);?></th>
                                    <th><?php echo get_phrases(['grand', 'total']);?></th>
                                    <th><?php echo get_phrases(['payment','doctor', 'selected']);?></th>
                                    <th><?php echo get_phrases(['created', 'by']);?></th>
                                    <th><?php echo get_phrases(['created', 'date']);?></th>
                                    <th><?php echo get_phrases(['deleted', 'reason']);?></th>
                                    <th><?php echo get_phrases(['reference', 'no']);?></th>
                                    <th><?php echo get_phrases(['requested', 'by']);?></th>
                                    <th><?php echo get_phrases(['deleted', 'by']);?></th>
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
        <?php } else if( session('branchId') == '' || session('branchId') == 0 ){ ?>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <strong class="fs-20 text-danger"><?php echo get_notify('you_have_to_switch_to_a_specific_branch');?></strong>
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

<!-- modify invoice date -->
<div class="modal fade bd-example-modal-lg" id="updateV-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel3" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="updateVModalLabel"><?php echo get_phrases(['invoice', 'update']);?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
            <?php echo form_open_multipart('account/services/invDateModify', 'class="needs-validation" id="updateForm" novalidate="" data="showCallBackData"');?>
            <div class="modal-body">
                <input type="hidden" name="invoice_id" id="invoice_update_id">
                <input type="hidden" name="branch_id" id="update_invoice_branch_id">
                <input type="hidden" name="patient_id" id="update_invoice_patient_id">
                <input type="hidden" name="doctor_id" id="update_invoice_doctor_id">
                <input type="hidden" name="pay_total" id="pay_total">
                <div class="row">
                     <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-600"><?php echo get_phrases(['payment', 'method']);?></label>
                            <table class="table table-bordered">
                                <tbody id="editPaymentMethod">
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="font-weight-600"><?php echo get_phrases(['total', 'receipt']);?></label>
                            <input type="text" class="form-control" id="total_receipt" readonly>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="font-weight-600"><?php echo get_phrases(['invoice', 'date']);?></label>
                            <input type="text" name="old_date" class="form-control" id="old_date">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo get_phrases(['close']);?></button>
                <button type="submit" class="btn btn-success" id="updateDateBtn"><?php echo get_phrases(['update']);?></button>
            </div>
            <?php echo form_close();?>
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-lg" id="deleteInv-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel3" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="deleteInvModalLabel"><?php echo get_phrases(['delete', 'invoice', 'no']);?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
            <?php echo form_open_multipart('account/services/deleteInvoice', 'class="needs-validation" id="deleteForm" novalidate="" data="showCallBackData"');?>
            <div class="modal-body">
                <input type="hidden" name="invoice_id" id="invoice_id">
                <input type="hidden" name="type" id="type" value="request">
                <div class="form-group">
                    <label class="font-weight-600"><?php echo get_phrases(['reasons', 'of', 'deleting']);?></label>
                    <?php 
                        $reasons = array('Deduct the consultation fees from the procedure fees', 'Wrong file number', 'Wrong doctor code', 'Wrong service code', 'Wrong item code', 'Wrong payment method', 'Wrong amount', 'Procedure not done', 'Duplicated voucher for receipt vouchers');
                        foreach ($reasons as $key => $value) { ?>
                                <div class="form-check">
                                    <input name="reasons[]" class="form-check-input" type="checkbox" value="<?php echo $value;?>" id="r<?php echo $key;?>">
                                    <label class="form-check-label text-muted" for="r<?php echo $key;?>">
                                        <?php echo $value;?>
                                    </label>
                                </div>
                            <?php
                        }
                    ?>
                    
                </div>
                <div class="form-group">
                    <label class="font-weight-600"><?php echo get_phrases(['reference', 'invoice', 'no']);?> </label>
                    <input type="text" name="delete_ref_id" class="form-control onlyNumber" placeholder="<?php echo get_phrases(['reference', 'invoice', 'no']);?>" autocomplete="off">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo get_phrases(['close']);?></button>
                <button type="submit" class="btn btn-success" id="sendDelReqBtn"><?php echo get_phrases(['send', 'delete', 'request']);?></button>
            </div>
            <?php echo form_close();?>
        </div>
    </div>
</div>
<script src="<?php echo base_url()?>/assets/plugins/jquery-ui-1.12.1/jquery-ui.min.js" type="text/javascript"></script>
<script src="<?php echo base_url()?>/assets/dist/js/lity.js" type="text/javascript"></script>
<script type="text/javascript">
    var showCallBackData = function () {    
        $('#deleteInv-modal, #updateV-modal').modal('hide');
        $('#deleteForm')[0].reset();        
        $('#serInvList').DataTable().ajax.reload(null, false);
    }

    $(document).ready(function() { 
       "use strict";

       $('#search_date, #old_date').datepicker({dateFormat: 'yy-mm-dd'});
       $('option:first-child').val('').trigger('change');

        var table = $('#serInvList').DataTable({ 
             responsive: true,
             lengthChange: true,
             "lengthMenu": [ [15, 30, 50, 100, 500, 1000], [15, 30, 50, 100, 500, 1000] ],
             "aaSorting": [[ 0, "desc" ]],
             "columnDefs": [
                { "bSortable": false, "aTargets": [6, 9, 10] },
            ],
            dom: "<'row'<?php if($hasExportAccess || $hasPrintAccess){ echo "<'col-md-4'l><'col-md-4'B><'col-md-4'f>"; } else { echo "<'col-md-6'l><'col-md-6'f>"; } ?>>rt<'bottom'<'row'<'col-md-6'i><'col-md-6'p>>><'clear'>",
            buttons: [
                <?php if($hasPrintAccess){ ?>
                {
                    extend: 'print',
                    text: '<i class="fa fa-print custool" title="Print"></i>',
                    titleAttr: 'Print',
                    className: 'btn-light',
                    title : 'invoices_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5, 6, 7 ]
                    }
                },
                <?php } if($hasExportAccess){ ?>
                {
                    extend: 'copyHtml5',
                    text: '<i class="far fa-copy custool" title="Copy"></i>',
                    titleAttr: 'Copy',
                    className: 'btn-light',
                    title : 'invoices_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5, 6, 7 ]
                    }
                },
                {
                    extend: 'excelHtml5',
                    text: '<i class="far fa-file-excel custool" title="Excel"></i>',
                    titleAttr: 'Excel',
                    className: 'btn-light',
                    title : 'invoices_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5, 6, 7 ]
                    }
                },
                {
                    extend: 'csvHtml5',
                    text: '<i class="far fa-file-alt custool" title="CSV"></i>',
                    titleAttr: 'CSV',
                    className: 'btn-light',
                    title : 'invoices_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5, 6, 7 ]
                    }
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="far fa-file-pdf custool" title="PDF"></i>',
                    titleAttr: 'PDF',
                    className: 'btn-light',
                    title : 'invoices_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5, 6, 7 ]
                    }
                }
                <?php } ?>
            ],
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'ajax': {
               'url': _baseURL + 'account/services/getAllList',
               'type':'POST',
               'data': function ( d ) {
                    d.csrf_stream_name = csrf_val;
                    d.paid        = $('#paid').val();
                    d.search_date = $('#search_date').val();
                    d.branch_id   = $('#branch_id').val();
                    d.file_no     = $('#file_no').val();
                    d.invoice_id  = $('#search_invoice_id').val();
                }
            },
          'columns': [
             { data: 'id' },
             { data: 'branch_name' },
             { data: 'file_no' },
             { data: 'patient_name' },
             { data: 'grand_total' },
             { data: 'doctor_name' },
             { data: 'payment_by' },
             { data: 'username' },
             { data: 'invoice_date' },
             { data: 'isPaid' },
             { data: 'button'}
          ],
        });

        $("#paid").select2({
            placeholder: '<?php echo get_phrases(['select', 'payment', 'status']);?>',
        });

        $('#filtering').on('click', function(e){
            $('#serInvList').DataTable().ajax.reload();
        });

        // reset fields
        $('.resetBtn').on('click', function(e){
            $('#search_date').val('');
            $('#file_no').val('');
            $('#search_invoice_id').val('');
            $('#branch_id').val('<?php echo session('branchId');?>').trigger('change');
            $('#paid').val('').trigger('change');
            $('#serInvList').DataTable().ajax.reload();
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
            $('#branch_id').val('<?php echo session('branchId');?>').trigger('change');
        });

        $("#branch_id").on('change', function(){
            var result = change_top_branch($(this).val());
            if(result == true){
                $('#serInvList').DataTable().ajax.reload();
            }
        });

        // update Invoice 
        $('#serInvList').on('click', '.editAction', function(e){
            e.preventDefault();
            var id = $(this).attr('data-id');
            var CurDate = '<?php echo date('Y-m-d');?>';
            
            var submit_url = _baseURL+"account/services/getInvoicePayInfo/"+id;
            $.ajax({
                type: 'POST',
                url: submit_url,
                data: {'csrf_stream_name':csrf_val},
                dataType: 'JSON',
                success: function(res) {
                    var date = res.invoice_date;
                    //var date1 = date.split('-');
                    //var old = date1[2] + '/' + date1[1]  + '/' + date1[0];
                    $('#invoice_update_id').val(res.id);
                    $('#update_invoice_branch_id').val(res.branch_id);
                    $('#update_invoice_patient_id').val(res.patient_id);
                    $('#update_invoice_doctor_id').val(res.doctor_id);
                    $('#pay_total').val(res.receipt);
                    $('#total_receipt').val(res.receipt);
                    $('#old_date').val(date);
                    var addDate = moment(date).add(30, 'days').format('YYYY-MM-DD');
                    if(CurDate >= addDate){
                        $('#old_date').attr('disabled', true);
                        $('#updateDateBtn').attr('disabled', true);
                    }else{
                        $('#old_date').attr('disabled', false);
                        $('#updateDateBtn').attr('disabled', false);
                    }
                    //getExistPaymentInfo(res.payments);
                    var countPayId = 1;
                    $("#editPaymentMethod").empty();
                    var selectedValues = new Array();
                    $.each(res.payments, function(key, value){
                        selectedValues[countPayId] = value.payment_name;
                        $("#editPaymentMethod").append('<tr>'+
                               '<td><select name="pm_name[]" id="pm_name_'+countPayId+'" class="custom-select pm_name"></select><div class="others"></div></td>'+
                               '<td><input type="hidden" name="exist_payment[]" value="'+value.amount+'"><input type="text" name="pay_amount[]" id="pay_amount_'+countPayId+'" class="form-control form-control-small pay_amount text-right" value="'+value.amount+'" readonly></td>'+
                           '</tr>'); 
                       
                            // payment method list
                            $.ajax({
                                type:'GET',
                                url: _baseURL+'auth/select2List/list_payment_method/127-129-130-150-368',
                                dataType: 'json',
                                data:{'csrf_stream_name':csrf_val},
                            }).done(function(data) {
                                //$("#pm_name_'"+countPayId+"' option:first-child").val('').trigger('change');
                                $('.pm_name').select2({
                                    placeholder: '<?php echo get_phrases(['select', 'payment', 'method']);?>',
                                    data: data
                                });
                            });
                      
                        countPayId++;
                    });

                    $('.pm_name').select2();
                    var docOption = new Option('', '', true, true);
                    $('.pm_name').append(docOption).trigger('change');
                    
                    $('#updateV-modal').modal('show');
                    setTimeout(function(){
                         var j=1;
                        $.each($(".pm_name"), function(){
                            if(selectedValues[j]==127){
                                var pay = new Option('Paid Advance', selectedValues[j], true, true);
                                $(this).append(pay).trigger('change');
                                $(this).attr('disabled', true);
                                $(this).parent().parent().find('.pay_amount').attr('disabled', true);
                            }else if(selectedValues[j]==130){
                                var pay = new Option('Credit by doctor', selectedValues[j], true, true);
                                $(this).append(pay).trigger('change');
                                $(this).attr('disabled', true);
                                $(this).parent().parent().find('.pay_amount').attr('disabled', true);
                            }else if(selectedValues[j]==150){
                                var pay = new Option('Credit by patient', selectedValues[j], true, true);
                                $(this).append(pay).trigger('change');
                                $(this).attr('disabled', true);
                                $(this).parent().parent().find('.pay_amount').attr('disabled', true);
                            }else{
                                $(this).val(selectedValues[j]).trigger('change');
                            }
                            j++;
                        });
                    }, 300);
                }
            });
        });

        // delete voucher
        $('#serInvList').on('click', '.deleteAction', function(e){
            e.preventDefault();
            var CurDate = '<?php echo date('Y-m-d');?>';
            var check = confirm('<?php echo get_phrases(["are_you_sure"])?>');  
            if(check){ 
                var id = $(this).attr('data-id');
                var date = $(this).attr('data-date');
                if(id !=''){
                    var userId = '<?php echo session('id');?>';
                    var user_role = '<?php echo session('user_role');?>';
                    $('#invoice_id').val(id);
                    var addDate = moment(date).add(30, 'days').format('YYYY-MM-DD');
                    if(userId==2 && user_role==1){
                        $('#sendDelReqBtn').attr('disabled', false);
                    }else{
                        if(CurDate >= addDate){
                            $('#sendDelReqBtn').attr('disabled', true);
                        }else{
                            $('#sendDelReqBtn').attr('disabled', false);
                        }
                    }
                    $('#deleteInv-modal').modal('show');
                }
            }
        });

        // delete request undo
        $('#serInvList').on('click', '.undoAction', function(e){
            e.preventDefault();
            var check = confirm('<?php echo get_phrases(["are_you_sure"])?>');  
            if(check){ 
                var id = $(this).attr('data-id');
                var submit_url = _baseURL+"account/services/deleteInvoice";
                $.ajax({
                    type: 'POST',
                    url: submit_url,
                    data: {'csrf_stream_name':csrf_val, type:'undo', invoice_id:id},
                    dataType: 'JSON',
                    success: function(res) {
                        if(res.success==true){
                            toastr.success(res.message, res.title);
                            $('#serInvList').DataTable().ajax.reload(null, false);
                        }else{
                            toastr.warning(res.message, res.title);
                        }
                    }
                });
            }
        });

        // delete approved 
        $('#serInvList').on('click', '.approvedAction', function(e){
            e.preventDefault();
            var CurDate    = '<?php echo date('Y-m-d');?>';
            var invoice_id = $(this).attr('data-id');
            var patient_id = $(this).attr('data-p');
            var date       = $(this).attr('data-date');
            var addDate = moment(date).add(30, 'days').format('YYYY-MM-DD');
            if(CurDate >= addDate){
                toastr.warning('<?php echo get_notify('Passed_more_than_30_days_from_request_date!');?>');
            }else{
                var check = confirm('<?php echo get_phrases(["are_you_sure"])?>');  
                if(check){ 
                    var submit_url = _baseURL+"account/services/approvedDelReq";
                    $.ajax({
                        type: 'POST',
                        url: submit_url,
                        data: {'csrf_stream_name':csrf_val, patient_id:patient_id, invoice_id:invoice_id},
                        dataType: 'JSON',
                        success: function(res) {
                            if(res.success==true){
                                toastr.success(res.message, res.title);
                                $('#serInvList').DataTable().ajax.reload(null, false);
                            }else{
                                toastr.warning(res.message, res.title);
                            }
                        }
                    });
                }
            }
        });

        // deleted invoices
        $('#deleteList').DataTable({ 
             responsive: true,
             lengthChange: true,
             "lengthMenu": [ [15, 30, 50, 100, 500, 1000], [15, 30, 50, 100, 500, 1000] ],
             "aaSorting": [[ 0, "desc" ]],
             "columnDefs": [
                { "bSortable": false, "aTargets": [5, 7, 11] },
            ],
            dom: "<'row'<?php if($hasExportAccess || $hasPrintAccess){ echo "<'col-md-4'l><'col-md-4'B><'col-md-4'f>"; } else { echo "<'col-md-6'l><'col-md-6'f>"; } ?>>rt<'bottom'<'row'<'col-md-6'i><'col-md-6'p>>><'clear'>",
            buttons: [
                <?php if($hasPrintAccess){ ?>
                {
                    extend: 'copyHtml5',
                    text: '<i class="far fa-copy custool" title="Copy"></i>',
                    titleAttr: 'Copy',
                    className: 'btn-light',
                    title : 'invoices_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9 ]
                    }
                },
                <?php } if($hasExportAccess){ ?>
                {
                    extend: 'excelHtml5',
                    text: '<i class="far fa-file-excel custool" title="Excel"></i>',
                    titleAttr: 'Excel',
                    className: 'btn-light',
                    title : 'invoices_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9 ]
                    }
                },
                {
                    extend: 'csvHtml5',
                    text: '<i class="far fa-file-alt custool" title="CSV"></i>',
                    titleAttr: 'CSV',
                    className: 'btn-light',
                    title : 'invoices_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9 ]
                    }
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="far fa-file-pdf custool" title="PDF"></i>',
                    titleAttr: 'PDF',
                    className: 'btn-light',
                    title : 'invoices_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9 ]
                    }
                }
            <?php }?>
            ],
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'ajax': {
               'url': _baseURL + 'account/services/getDelInvList',
               'data':{'csrf_stream_name':csrf_val}
            },
          'columns': [
             { data: 'id' },
             { data: 'branch_name' },
             { data: 'patient_name' },
             { data: 'grand_total' },
             { data: 'payment_by' },
             { data: 'created_by' },
             { data: 'invoice_date' },
             { data: 'delete_reason' },
             { data: 'ref_no' },
             { data: 'requested_user' },
             { data: 'approved_user' },
             { data: 'button'}
          ],
        });

        var payNoDate = '<div class="row"><div class="col-md-6 mt-1"><input type="text" name="ac_no[]" class="form-control form-control-small onlyNumber" placeholder="<?php echo get_phrases(['card', 'number']);?>"></div><div class="col-md-6 mt-1"><input type="text" name="edate[]" class="form-control form-control-small onlyD"></div></div>';
        var payNoName = '<div class="row"><div class="col-md-6 mt-1"><input type="text" name="ac_no[]" class="form-control form-control-small onlyNumber" placeholder="<?php echo get_phrases(['account', 'no']);?>"></div><div class="col-md-6 mt-1"><input type="text" name="bank_name[]" class="form-control form-control-small" placeholder="<?php echo get_phrases(['bank', 'name']);?>"></div></div>';
        // get service list by appointment Id
        $(document).on('change', '.pm_name', function(e){
            e.preventDefault();
            var id = $(this).val();
            
            if(id=='122'){
                $(this).closest('td').find('.others').html(payNoDate);
            }else if(id=='121'){
                $(this).closest('td').find('.others').html(payNoDate);
            }else if(id=='123'){
                $(this).closest('td').find('.others').html(payNoDate);
            }else if(id=='124'){
               $(this).closest('td').find('.others').html(payNoDate);
            }else if(id=='125'){
                $(this).closest('td').find('.others').html(payNoName);
            }else if(id=='126'){
                $(this).closest('td').find('.others').html(payNoDate);
            }else if(id=='127'){
                $(this).closest('td').find('.others').html('');
            }else if(id=='130' || id=='150'){
                $(this).closest('td').find('.others').html('');
                
            }else{
                $(this).closest('td').find('.others').html('');
            }
            //Single Date Picker
            $('.onlyD').daterangepicker({
                singleDatePicker: true,
                locale : {
                    format : 'YYYY-MM-DD'
                }
            });
        });

    });
</script>