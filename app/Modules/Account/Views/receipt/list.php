<link href="<?php echo base_url()?>/assets/plugins/jquery-ui-1.12.1/jquery-ui.min.css" rel="stylesheet">
<link href="<?php echo base_url()?>/assets/dist/css/lity.css" rel="stylesheet">
<div class="row">
    <div class="col-sm-12">
        <?php 
        $hasPrintAccess  = $permission->method('receipt_voucher', 'print')->access();
        $hasExportAccess = $permission->method('receipt_voucher', 'export')->access();
        if($permission->method('receipt_voucher', 'read')->access() || $permission->method('receipt_voucher', 'update')->access()){ ?>
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
                        <?php if($permission->method('receipt_voucher', 'create')->access()){ ?>
                       <a href="<?php echo base_url('account/accounts/receipt_voucher');?>" class="btn btn-success btn-sm mr-1"><i class="fas fa-plus mr-1"></i><?php echo get_phrases(['add', 'new', 'voucher']);?></a>
                   <?php }?>
                       <a href="<?php echo previous_url(); ?>" class="btn btn-warning btn-sm mr-1 ml-2"><i class="fas fa-angle-double-left mr-1"></i><?php echo get_phrases(['back']);?></a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <ul class="nav nav-pills mb-2" id="pills-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="voucher-tab" data-toggle="pill" href="#voucher" role="tab" aria-controls="voucher" aria-selected="false"><?php echo get_phrases(['voucher', 'list']);?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="deleteVoucher-tab" data-toggle="pill" href="#deleteVoucher" role="tab" aria-controls="deleteVoucher" aria-selected="false"><?php echo get_phrases(['delete', 'voucher', 'list']);?></a>
                    </li>
                </ul>
                <div class="tab-content" id="pills-tabContent">
                    <!-- voucher list -->
                    <div class="tab-pane fade show active" id="voucher" role="tabpanel" aria-labelledby="voucher-tab">
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
                                    <th><?php echo get_phrases(['patient', 'balance']);?></th>
                                    <th><?php echo get_phrases(['package', 'name']);?></th>
                                    <th><?php echo get_phrases(['receipt', 'amount']);?></th>
                                    <th><?php echo get_phrases(['doctor', 'name']);?></th>
                                    <th><?php echo get_phrases(['voucher', 'date']);?></th>
                                    <th><?php echo get_phrases(['created', 'by']);?></th>
                                    <th><?php echo get_phrases(['status']);?></th>
                                    <th><?php echo get_phrases(['action']);?></th>
                                </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                        </table>
                    </div>
                     <!-- delete voucher list -->
                    <div class="tab-pane fade" id="deleteVoucher" role="tabpanel" aria-labelledby="deleteVoucher-tab">
                        <table id="deleteList" class="table display table-bordered table-striped table-hover compact" width="100%">
                            <thead>
                                <tr>
                                    <th><?php echo get_phrases(['voucher', 'no']);?></th>
                                    <th><?php echo get_phrases(['patient', 'name']);?></th>
                                    <th><?php echo get_phrases(['receipt', 'amount']);?></th>
                                    <th><?php echo get_phrases(['doctor', 'name']);?></th>
                                    <th><?php echo get_phrases(['delete', 'reasons']);?></th>
                                    <th><?php echo get_phrases(['voucher', 'date']);?></th>
                                    <th><?php echo get_phrases(['created', 'by']);?></th>
                                    <th><?php echo get_phrases(['reference', 'voucher']);?></th>
                                    <th><?php echo get_phrases(['requested', 'by']);?></th>
                                    <th><?php echo get_phrases(['deleted', 'by']);?></th>
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

<div class="modal fade bd-example-modal-lg" id="updateV-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel3" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="updateVModalLabel"><?php echo get_phrases(['voucher', 'update']);?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
            <?php echo form_open_multipart('account/accounts/updateVoucher', 'class="needs-validation" id="updateForm" novalidate="" data="showCallBackData"');?>
            <div class="modal-body">
                <input type="hidden" name="voucher_id" id="voucher_update_id">
                <input type="hidden" name="branch_id" id="update_voucher_branch_id">
                <input type="hidden" name="patient_id" id="update_voucher_patient_id">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="font-weight-600"><?php echo get_phrases(['doctor', 'name']);?></label>
                           <?php echo form_dropdown('doctor_id','','','class="custom-select" id="up_doctor_id"');?>
                       </div>
                    </div>
                </div>
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
                            <input type="text" class="form-control" id="total_payable" readonly>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="font-weight-600"><?php echo get_phrases(['voucher', 'date']);?></label>
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

<div class="modal fade bd-example-modal-lg" id="deleteV-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel3" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="ideleteVModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
            <?php echo form_open_multipart('account/accounts/deleteVoucher', 'class="needs-validation" id="statusForm" novalidate="" data="showCallBackData"');?>
            <div class="modal-body">
                <input type="hidden" name="voucher_id" id="voucher_delete_id">
                <input type="hidden" name="type" id="tye" value="request">
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
                    <label class="font-weight-600"><?php echo get_phrases(['reference', 'voucher', 'no']);?></label>
                    <input type="text" name="delete_ref_id" class="form-control" placeholder="<?php echo get_phrases(['reference', 'voucher', 'no']);?>" autocomplete="off">
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

<div class="modal fade" id="resonsV-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel3" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="reasonVModalLabel"><?php echo get_phrases(['reasons', 'of', 'deleting']);?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
            <div class="modal-body" id="view_reasons">
               
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo get_phrases(['close']);?></button>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo base_url()?>/assets/dist/js/lity.js" type="text/javascript"></script>
<script src="<?php echo base_url()?>/assets/plugins/jquery-ui-1.12.1/jquery-ui.min.js" type="text/javascript"></script>
<script type="text/javascript">

    var showCallBackData = function () {         
        $('#statusForm')[0].reset();        
        $('#deleteV-modal, #updateV-modal').modal('hide');
        $('#rvList').DataTable().ajax.reload(null, false);
    }

    $(document).ready(function() { 
       "use strict";
       $('option:first-child').val('').trigger('change');
       $('#search_date, #old_date').datepicker({dateFormat: 'yy-mm-dd'});

        $('#rvList').DataTable({ 
             responsive: true,
             lengthChange: true,
             "lengthMenu": [ [15, 30, 50, 100, 500, 1000], [15, 30, 50, 100, 500, 1000] ],
             "aaSorting": [[ 0, "desc" ]],
             "columnDefs": [
                { "bSortable": false, "aTargets": [10] },
            ],
            dom: "<'row'<?php if($hasExportAccess || $hasPrintAccess){ echo "<'col-md-4'l><'col-md-4'B><'col-md-4'f>"; } else { echo "<'col-md-6'l><'col-md-6'f>"; } ?>>rt<'bottom'<'row'<'col-md-6'i><'col-md-6'p>>><'clear'>",
            buttons: [
                <?php if($hasPrintAccess){ ?>
                {
                    extend: 'print',
                    text: '<i class="fa fa-print custool" title="Print"></i>',
                    className: 'btn-light',
                    title : 'Receipt_Voucher_list<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9 ]
                    }
                },
                <?php } if($hasExportAccess){ ?>
                {
                    extend: 'copyHtml5',
                    text: '<i class="far fa-copy custool" title="Copy"></i>',
                    className: 'btn-light',
                    title : 'Receipt_Voucher_list<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9 ]
                    }
                },
                {
                    extend: 'excelHtml5',
                    text: '<i class="far fa-file-excel custool" title="Excel"></i>',
                    className: 'btn-light',
                    title : 'Receipt_Voucher_list<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9 ]
                    }
                },
                {
                    extend: 'csvHtml5',
                    text: '<i class="far fa-file-alt custool" title="CSV"></i>',
                    className: 'btn-light',
                    title : 'Receipt_Voucher_list<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9 ]
                    }
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="far fa-file-pdf custool" title="PDF"></i>',
                    className: 'btn-light',
                    title : 'Receipt_Voucher_list<?php echo date('Y-m-d');?>',
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
               'url': _baseURL + 'account/accounts/getRVList',
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
             { data: 'balance'},
             { data: 'package_name'},
             { data: 'receipt' },
             { data: 'doctor_name' },
             { data: 'voucher_date' },
             { data: 'created_by' },
             { data: 'isPaid' },
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

        // deleting list
        $('#deleteList').DataTable({ 
             responsive: true,
             lengthChange: true,
             "lengthMenu": [ [15, 30, 50, 100, 500, 1000], [15, 30, 50, 100, 500, 1000] ],
             "aaSorting": [[ 0, "desc" ]],
             "columnDefs": [
                { "bSortable": false, "aTargets": [3] },
            ],
            dom: "<'row'<?php if($hasExportAccess || $hasPrintAccess){ echo "<'col-md-4'l><'col-md-4'B><'col-md-4'f>"; } else { echo "<'col-md-6'l><'col-md-6'f>"; } ?>>rt<'bottom'<'row'<'col-md-6'i><'col-md-6'p>>><'clear'>",
            buttons: [
            <?php if($hasPrintAccess){ ?>
                {
                    extend: 'print',
                    text: '<i class="fa fa-print custool" title="Print"></i>',
                    titleAttr: 'Print',
                    className: 'btn-light',
                    title : 'Receipt_Voucher_list<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8 ]
                    }
                },
            <?php } if($hasExportAccess){ ?>
                {
                    extend: 'copyHtml5',
                    text: '<i class="far fa-copy custool" title="Copy"></i>',
                    titleAttr: 'Copy',
                    className: 'btn-light',
                    title : 'Receipt_Voucher_list<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8 ]
                    }
                },
                {
                    extend: 'excelHtml5',
                    text: '<i class="far fa-file-excel custool" title="Excel"></i>',
                    titleAttr: 'Excel',
                    className: 'btn-light',
                    title : 'Receipt_Voucher_list<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8 ]
                    }
                },
                {
                    extend: 'csvHtml5',
                    text: '<i class="far fa-file-alt custool" title="CSV"></i>',
                    titleAttr: 'CSV',
                    className: 'btn-light',
                    title : 'Receipt_Voucher_list<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8 ]
                    }
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="far fa-file-pdf custool" title="PDF"></i>',
                    titleAttr: 'PDF',
                    className: 'btn-light',
                    title : 'Receipt_Voucher_list<?php echo date('Y-m-d');?>',
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
               'url': _baseURL + 'account/accounts/deleteRVList',
               'data':{'csrf_stream_name':csrf_val}
            },
          'columns': [
             { data: 'id' },
             { data: 'nameE', 
                render: function (data, type, row) {
                    return row.patient_id +'-'+row.nameE + ' ' + row.nameA;
                }
            },
             { data: 'receipt' },
             { data: 'doctor_name' },
             { data: 'remarks' },
             { data: 'voucher_date' },
             { data: 'created_by' },
             { data: 'delete_ref_id'},
             { data: 'requested_by'},
             { data: 'deleted_by'}
          ],
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
            $("#filter_doc_id, #up_doctor_id").select2({
                placeholder: '<?php echo get_phrases(['select', 'doctor']);?>',
                data: data
            });
        });

        var payNoDate = '<div class="row"><div class="col-md-6 mt-1"><input type="text" name="ac_no[]" class="form-control form-control-small onlyNumber" placeholder="<?php echo get_phrases(['card', 'number']);?>"></div><div class="col-md-6 mt-1"><input type="text" name="edate[]" class="form-control form-control-small cardDate" autocomplete="off"></div></div>';
         var payNoName = '<div class="row"><div class="col-md-6 mt-1"><input type="text" name="ac_no[]" class="form-control form-control-small onlyNumber" placeholder="<?php echo get_phrases(['account', 'no']);?>"></div><div class="col-md-6 mt-1"><input type="text" name="bank_name[]" class="form-control form-control-small" placeholder="<?php echo get_phrases(['bank', 'name']);?>"></div></div>';

        // update voucher
        $('#rvList').on('click', '.editAction', function(e){
            e.preventDefault();
            var id = $(this).attr('data-id');
            var CurDate = '<?php echo date('Y-m-d');?>';
            var submit_url = _baseURL+"account/accounts/getVoucherInfo/"+id;
            $.ajax({
                type: 'POST',
                url: submit_url,
                data: {'csrf_stream_name':csrf_val},
                dataType: 'JSON',
                success: function(res) {
                    var date = res.voucher_date;
                    $('#voucher_update_id').val(res.id);
                    $('#update_voucher_branch_id').val(res.branch_id);
                    $('#update_voucher_patient_id').val(res.patient_id);
                    $('#up_doctor_id').val(res.doctor_id).trigger('change');
                    $('#old_date').val(date);

                    var addDate = moment(date).add(30, 'days').format('YYYY-MM-DD');
                    if(CurDate >= addDate){
                        $('#old_date').attr('disabled', true);
                        $('#updateDateBtn').attr('disabled', true);
                    }else{
                        $('#old_date').attr('disabled', false);
                        $('#updateDateBtn').attr('disabled', false);
                    }

                    var countPayId = 0;
                    var totalAmt = 0;
                    $("#editPaymentMethod").empty();
                    var selectedValues = new Array();
                    var sValues = new Array();
                    $.each(res.payments, function(key, value){
                        totalAmt +=parseFloat(value.amount);
                        selectedValues[countPayId] = value.pay_method_id;
                        var tt = {'card':value.card_or_cheque_no, 'date':value.expiry_date, 'bank':value.bank_name};
                        sValues.push(tt);
                        $("#editPaymentMethod").append('<tr>'+
                               '<td><select name="pm_name[]" id="pm_name_'+countPayId+'" class="custom-select pm_name"></select><div class="others"></div></td>'+
                               '<td><input type="text" name="pay_amount[]" id="pay_amount_'+countPayId+'" class="form-control form-control-small pay_amount text-right" value="'+value.amount+'" readonly></td>'+
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
                    $('#total_payable').val(totalAmt);

                    $('.pm_name').select2();
                    var docOption = new Option('', '', true, true);
                    $('.pm_name').append(docOption).trigger('change');
                    
                    $('#updateV-modal').modal('show');
                    setTimeout(function(){
                         var j=0;
                        $.each($(".pm_name"), function(){
                            $(this).val(selectedValues[j]).trigger('change');
                            
                            $(this).closest('td').find('.others').html('');
                            if(selectedValues[j]=='125'){
                                var payNoName1 = '<div class="row"><div class="col-md-6 mt-1"><input type="text" name="ac_no[]" class="form-control form-control-small onlyNumber" placeholder="<?php echo get_phrases(['account', 'no']);?>" value="'+sValues[j]['card']+'"></div><div class="col-md-6 mt-1"><input type="text" name="bank_name[]" class="form-control form-control-small" placeholder="<?php echo get_phrases(['bank', 'name']);?>" value="'+sValues[j]['bank']+'"></div></div>';
                                $(this).closest('td').find('.others').html(payNoName1);
                            }else if(selectedValues[j]=='120' || selectedValues[j]=='127'){
                                var payNoDate1 = '<input type="hidden" name="ac_no[]"><input type="hidden" name="edate[]">';
                                $(this).closest('td').find('.others').html(payNoDate1);
                            }else{
                                var payNoDate1 = '<div class="row"><div class="col-md-6 mt-1"><input type="text" name="ac_no[]" class="form-control form-control-small onlyNumber" placeholder="<?php echo get_phrases(['card', 'number']);?>" value="'+sValues[j]['card']+'"></div><div class="col-md-6 mt-1"><input type="text" name="edate[]" class="form-control form-control-small cardDate" autocomplete="off" value="'+sValues[j]['date']+'"></div></div>';
                                $(this).closest('td').find('.others').html(payNoDate1);
                            }
                            j++;
                            $('.cardDate').datepicker({dateFormat: 'yy-mm-dd'});
                        });
                    }, 300);
                    
                }
            });
        });

        //payment calculation
        $(document).on('keyup', '.pay_amount', function() {
            var total = parseFloat($('#total_payable').val());
            //total   
            var pay_amount =0;
            $('.pay_amount').each(function(){ 
                var rest = pay_amount - total;
                pay_amount  += parseFloat($(this).val());
                if(pay_amount > total){
                    $(this).val(rest);
                    return true;
                }else{
                    return true;
                }
            }); 
        });


        // delete request undo
        $('#rvList').on('click', '.undoAction', function(e){
            e.preventDefault();
            var check = confirm('<?php echo get_phrases(["are_you_sure"])?>');  
            if(check){ 
                var id = $(this).attr('data-id');
                var submit_url = _baseURL+"account/accounts/deleteVoucher";
                $.ajax({
                    type: 'POST',
                    url: submit_url,
                    data: {'csrf_stream_name':csrf_val, type:'undo', voucher_id:id},
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
        $('#rvList').on('click', '.reqAppAction', function(e){
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
                    var submit_url = _baseURL+"account/accounts/appReceiptDelReq";
                    $.ajax({
                        type: 'POST',
                        url: submit_url,
                        data: {'csrf_stream_name':csrf_val, patient_id:patient_id, voucher_id:voucher_id},
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

        // delete voucher
        $('#rvList').on('click', '.deleteAction', function(e){
            e.preventDefault();
            var id = $(this).attr('data-id');
            var CurDate    = '<?php echo date('Y-m-d');?>';
            if(id !=''){
                var date = $(this).attr('data-date');
                $('#voucher_delete_id').val(id);
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
                $('#deleteV-modal').modal('show');
            }
        });

        // show delete voucher status
        $('#rvList').on('click', '.viewReason', function(e){
            e.preventDefault();
            var id = $(this).attr('data-id');
            
            var submit_url = _baseURL+"account/accounts/getVoucherInfo/"+id;
            $.ajax({
                type: 'POST',
                url: submit_url,
                data: {'csrf_stream_name':csrf_val},
                dataType: 'JSON',
                success: function(res) {
                    var reason = res.remarks;
                    var reasons = reason.split(',');
                    var list = '<ul>';
                    if(reasons.length>0){
                        $.each(reasons, function(key, value){
                            list += '<li>'+value+'</li>';
                        });
                    }
                    list += '</ul>';
                    $('#view_reasons').html(list);
                    $('#resonsV-modal').modal('show');
                }
            });
        });

        // approved voucher
        $('#rvList').on('click', '.approvedAction', function(e){
            e.preventDefault();
            var id = $(this).attr('data-id');
            
            var submit_url = _baseURL+"account/accounts/apprVoucher/"+id;
            var check = confirm('<?php echo get_phrases(['are_you_sure']);?>');
            if(check){
                $.ajax({
                    type: 'POST',
                    url: submit_url,
                    data: {'csrf_stream_name':csrf_val},
                    dataType: 'JSON',
                    success: function(res) {
                        if(res.success==true){
                            toastr.success(res.message, res.title);
                            $('#rvList').DataTable().ajax.reload(null, false);
                        }else{
                            toastr.error(res.message, res.title);
                        }
                    }
                });
            }
        });

        // get service list by appointment Id
        $(document).on('change', '.pm_name', function(e){
            e.preventDefault();
            var id = $(this).val();
            
            if(id=='120'){
                $(this).closest('td').find('.others').html('<input type="hidden" name="ac_no[]"><input type="hidden" name="edate[]">');
            }else if(id=='122'){
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
                $(this).closest('td').find('.others').html('<input type="hidden" name="ac_no[]"><input type="hidden" name="edate[]">');
            }else if(id=='130' || id=='150'){
                $(this).closest('td').find('.others').html('');
                
            }else{
                $(this).closest('td').find('.others').html('');
            }
            //Single Date Picker
            $('.cardDate').datepicker({dateFormat: 'yy-mm-dd'});
        });
    });
</script>