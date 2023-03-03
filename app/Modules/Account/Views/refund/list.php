<link href="<?php echo base_url()?>/assets/plugins/jquery-ui-1.12.1/jquery-ui.min.css" rel="stylesheet">
<link href="<?php echo base_url()?>/assets/dist/css/lity.css" rel="stylesheet">
<div class="row">
    <div class="col-sm-12">
        <?php 
        $hasPrintAccess  = $permission->method('refund_voucher', 'print')->access();
        $hasExportAccess = $permission->method('refund_voucher', 'export')->access();
        if($permission->method('refund_voucher', 'read')->access() || $permission->method('payment_voucher', 'update')->access()){ ?>
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
                        <?php if($permission->method('refund_voucer', 'create')->access()){ ?>
                       <a href="<?php echo base_url('account/accounts/refund_voucher');?>" class="btn btn-success btn-sm mr-1"><i class="fas fa-plus mr-1"></i><?php echo get_phrases(['new', 'refund', 'voucher']);?></a>
                        <?php }?>
                       <a href="<?php echo previous_url(); ?>" class="btn btn-warning btn-sm mr-1 ml-2"><i class="fas fa-angle-double-left mr-1"></i><?php echo get_phrases(['back']);?></a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row form-group">  
                    <div class="col-sm-2">
                        <input type="text" name="search_date" id="search_date" class="form-control" placeholder="<?php echo get_phrases(['select', 'voucher', 'date']); ?>" autocomplete="off">
                    </div>
                    <div class="col-sm-2">
                        <?php echo form_dropdown('patient_id','','','class="custom-select" id="patient_id"');?>
                    </div>
                    <div class="col-sm-2">
                        <?php echo form_dropdown('filter_doc_id','','','class="custom-select" id="filter_doc_id"');?>
                    </div>
                    <div class="col-sm-2">
                        <input type="text" name="file_no" id="file_no" class="form-control" placeholder="<?php echo get_phrases(['enter', 'file', 'number']); ?>" autocomplete="off">
                    </div>   
                    <div class="col-sm-2">
                        <input type="text" name="search_invoice_id" id="search_invoice_id" class="form-control" placeholder="<?php echo get_phrases(['enter', 'voucher', 'id']); ?>" autocomplete="off">
                    </div>
                    <div class="col-sm-2">
                        <button type="button" class="btn btn-success text-white" id="filtering"><?php echo get_phrases(['filter']);?></button>
                        <button type="button" class="btn btn-warning resetBtn text-white"><?php echo get_phrases(['reset']);?></button>
                    </div> 
                </div>
                <table id="rvList" class="table display table-bordered table-striped table-hover compact" width="100%">
                    <thead>
                        <tr>
                            <th><?php echo get_phrases(['voucher', 'no']);?></th>
                            <th><?php echo get_phrases(['file', 'number']);?></th>
                            <th><?php echo get_phrases(['patient', 'name']);?></th>
                            <th><?php echo get_phrases(['total', 'amount']);?></th>
                            <th><?php echo get_phrases(['payment', 'amount']);?></th>
                            <th><?php echo get_phrases(['doctor', 'name']);?></th>
                            <th><?php echo get_phrases(['created', 'by']);?></th>
                            <th><?php echo get_phrases(['created', 'date']);?></th>
                            <th><?php echo get_phrases(['invoice', 'no']);?></th>
                            <th><?php echo get_phrases(['status']);?></th>
                            <th><?php echo get_phrases(['action']);?></th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
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

<div class="modal fade bd-example-modal-lg" id="deleteInv-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel3" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="deleteInvModalLabel"><?php echo get_phrases(['delete', 'voucher', 'no']);?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
            <?php echo form_open_multipart('account/accounts/deletePaymentReq', 'class="needs-validation" id="delReqForm" novalidate="" data="delReqCallBackData"');?>
            <div class="modal-body">
                <input type="hidden" name="voucher_id" id="voucher_id">
                <input type="hidden" name="type" id="type" value="request">
                <input type="hidden" name="action" id="action" value="<?php echo get_phrases(['refun', 'voucher']);?>">
                <div class="form-group">
                    <label class="font-weight-600"><?php echo get_phrases(['reasons', 'of', 'deleting']);?></label>
                    <?php 
                        $reasons = array('Deduct the consultation fees from the procedure fees', 'Wrong file number', 'Wrong doctor code', 'Wrong service code', 'Wrong item code', 'Wrong payment method', 'Wrong amount', 'Procedure not done');
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
                    <label class="font-weight-600"><?php echo get_phrases(['reference', 'voucher', 'no']);?> </label>
                    <input type="text" name="delete_ref_id" class="form-control onlyNumber" placeholder="<?php echo get_phrases(['reference', 'voucher', 'no']);?>" autocomplete="off">
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
<script src="<?php echo base_url()?>/assets/dist/js/lity.js" type="text/javascript"></script>
<script src="<?php echo base_url()?>/assets/plugins/jquery-ui-1.12.1/jquery-ui.min.js" type="text/javascript"></script>
<script type="text/javascript">

    var delReqCallBackData = function () {
        $('#voucher_id').val('');               
        $('#delReqForm')[0].reset();        
        $('#deleteInv-modal').modal('hide');
        $('#rvList').DataTable().ajax.reload(null, false);
    }

    $(document).ready(function() { 
       "use strict";
       $('option:first-child').val('').trigger('change');
       $('#search_date').datepicker({dateFormat: 'yy-mm-dd'});

        $('#rvList').DataTable({ 
             responsive: true,
             lengthChange: true,
             "lengthMenu": [ [15, 30, 50, 100, 500, 1000], [15, 30, 50, 100, 500, 1000] ],
             "aaSorting": [[ 0, "desc" ]],
             "columnDefs": [
                { "bSortable": false, "aTargets": [9] },
            ],
            dom: "<'row'<?php if($hasExportAccess || $hasPrintAccess){ echo "<'col-md-4'l><'col-md-4'B><'col-md-4'f>"; } else { echo "<'col-md-6'l><'col-md-6'f>"; } ?>>rt<'bottom'<'row'<'col-md-6'i><'col-md-6'p>>><'clear'>",
            buttons: [
                <?php if($hasPrintAccess){ ?>
                {
                    extend: 'print',
                    text: '<i class="fa fa-print custool" title="Print"></i>',
                    className: 'btn-light',
                    title : 'Refund_Voucher_list<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8 ]
                    }
                },
                <?php } if($hasExportAccess){ ?>
                {
                    extend: 'copyHtml5',
                    text: '<i class="far fa-copy custool" title="Copy"></i>',
                    className: 'btn-light',
                    title : 'Refund_Voucher_list<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8 ]
                    }
                },
                {
                    extend: 'excelHtml5',
                    text: '<i class="far fa-file-excel custool" title="Excel"></i>',
                    className: 'btn-light',
                    title : 'Refund_Voucher_list<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8 ]
                    }
                },
                {
                    extend: 'csvHtml5',
                    text: '<i class="far fa-file-alt custool" title="CSV"></i>',
                    className: 'btn-light',
                    title : 'Refund_Voucher_list<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8 ]
                    }
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="far fa-file-pdf custool" title="PDF"></i>',
                    className: 'btn-light',
                    title : 'Refund_Voucher_list<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8 ]
                    }
                }
            <?php }?>
            ],
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'ajax': {
               'url': _baseURL + 'account/accounts/getRefundList',
               'data': function ( d ) {
                    d.csrf_stream_name = csrf_val;
                    d.search_date      = $('#search_date').val();
                    d.doctor_id        = $('#filter_doc_id').val();
                    d.patient_id       = $('#patient_id').val();
                    d.file_no          = $('#file_no').val();
                    d.invoice_id       = $('#search_invoice_id').val();
                }
            },
          'columns': [
             { data: 'id' },
             { data: 'file_no' },
             { data: 'patient_name'},
             { data: 'total' },
             { data: 'payment' },
             { data: 'doctor_name' },
             { data: 'created_by' },
             { data: 'created_date' },
             { data: 'receipt_voucher' },
             { data: 'status' },
             { data: 'button'}
          ],
        });

        // custom search
        $("#filtering").on('click', function(){
            $('#rvList').DataTable().ajax.reload();
        });

        // reset button
        $('.resetBtn').on('click', function(e){
            $('#patient_id').val('').trigger('change');
            $('#filter_doc_id').val('').trigger('change');
            $('#search_date').val('');
            $('#file_no').val('');
            $('#search_invoice_id').val('');
            $('#rvList').DataTable().ajax.reload();
        });

        // search patient
        $('#patient_id').select2({
            placeholder: '<?php echo get_phrases(['search', 'patient']);?>',
            minimumInputLength: 2,
                ajax: {
                    url: _baseURL+'auth/searchAllWithFile',
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
        });


        // doctor list
        $.ajax({
            type:'GET',
            url: _baseURL+'auth/doctorList',
            dataType: 'json',
            data:{'csrf_stream_name':csrf_val},
        }).done(function(data) {
            $("#filter_doc_id").select2({
                placeholder: '<?php echo get_phrases(['select', 'doctor']);?>',
                data: data
            });
        });

        // delete voucher 
        $('#rvList').on('click', '.deleteAction', function(e){
            e.preventDefault();
            var CurDate = '<?php echo date('Y-m-d');?>';
            var check = confirm('<?php echo get_phrases(["are_you_sure"])?>');  
            if(check){ 
                var id = $(this).attr('data-id');
                var date = $(this).attr('data-date');
                if(id !=''){
                    $('#voucher_id').val(id);
                    var addDate = moment(date).add(30, 'days').format('YYYY-MM-DD');
                    if(CurDate >= addDate){
                        $('#sendDelReqBtn').attr('disabled', true);
                    }else{
                        $('#sendDelReqBtn').attr('disabled', false);
                    }
                    $('#deleteInv-modal').modal('show');
                }
            }
        });

        // delete request undo
        $('#rvList').on('click', '.undoAction', function(e){
            e.preventDefault();
            var check = confirm('<?php echo get_phrases(["are_you_sure"])?>');  
            var action = '<?php echo get_phrases(['refun', 'voucher']);?>';
            if(check){ 
                var id = $(this).attr('data-id');
                var submit_url = _baseURL+"account/accounts/deletePaymentReq";
                $.ajax({
                    type: 'POST',
                    url: submit_url,
                    data: {'csrf_stream_name':csrf_val, type:'undo', voucher_id:id, action:action},
                    dataType: 'JSON',
                    success: function(res) {
                        if(res.success==true){
                            toastr.success(res.message, res.title);
                            $('#rvList').DataTable().ajax.reload(null, false);
                        }else{
                            toastr.warning(res.message, res.title);
                        }
                    }
                });
            }
        });

        // delete approved 
        $('#rvList').on('click', '.approvedAction', function(e){
            e.preventDefault();
            var CurDate    = '<?php echo date('Y-m-d');?>';
            var voucher_id = $(this).attr('data-id');
            var patient_id = $(this).attr('data-p');
            var date       = $(this).attr('data-date');
            var addDate = moment(date).add(30, 'days').format('YYYY-MM-DD');
            if(CurDate >= addDate){
                alert('Passed more than 15 days from request date!');
            }else{
                var check = confirm('<?php echo get_phrases(["are_you_sure"])?>');  
                if(check){ 
                    var submit_url = _baseURL+"account/accounts/approvedPaymentReq";
                    $.ajax({
                        type: 'POST',
                        url: submit_url,
                        data: {'csrf_stream_name':csrf_val, patient_id:patient_id, voucher_id:voucher_id, action:'refund'},
                        dataType: 'JSON',
                        success: function(res) {
                            if(res.success==true){
                                toastr.success(res.message, res.title);
                                $('#rvList').DataTable().ajax.reload(null, false);
                            }else{
                                toastr.warning(res.message, res.title);
                            }
                        }
                    });
                }
            }
        });
    });
</script>