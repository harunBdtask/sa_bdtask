<div class="row">
    <div class="col-sm-12">
        <?php if($permission->module('patient_balance_transfer')->access()){ ?>
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
                        <?php if($permission->method('patient_balance_transfer', 'create')->access()){ ?>
                        <a href="javascript:void(0);" class="btn btn-success btn-sm mr-1 addBlnc"><i class="fas fa-plus mr-1"></i><?php echo get_phrases(['balance', 'transfer']);?></a>
                        <?php }?>
                       <a href="<?php echo previous_url(); ?>" class="btn btn-warning btn-sm mr-1 ml-2"><i class="fas fa-angle-double-left mr-1"></i><?php echo get_phrases(['back']);?></a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table id="approvalList" class="table display table-bordered table-striped table-hover compact" width="100%">
                    <thead>
                        <tr>
                            <th><?php echo get_phrases(['serial', 'no']);?></th>
                            <th><?php echo get_phrases(['from', 'patient']);?></th>
                            <th><?php echo get_phrases(['to', 'patient']);?></th>
                            <th><?php echo get_phrases(['amount']);?></th>
                            <th><?php echo get_phrases(['transfer', 'date']);?></th>
                            <th><?php echo get_phrases(['created', 'by']);?></th>
                            <th><?php echo get_phrases(['status']);?></th>
                            <th width="8%"><?php echo get_phrases(['action']);?></th>
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

<div class="modal fade bd-example-modal-lg" id="add-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel3" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="addModalLabel"><?php echo get_phrases(['add', 'balance', 'transfer']);?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
            <?php echo form_open_multipart('account/balanceTransfer/savePntTrans', 'class="needs-validation" id="transForm" novalidate="" data="transCallBackData"');?>
            <div class="modal-body">
                <input type="hidden" name="from_head" id="from_head">
                <input type="hidden" name="to_head" id="to_head">
                <input type="hidden" name="from_name" id="from_name">
                <input type="hidden" name="to_name" id="to_name">
                <div class="row">
                    <div class="col-md-4 col-sm-12">
                       <div class="form-group">
                            <label class="font-weight-600 mb-0"><?php echo get_phrases(['transfer', 'id']);?></label>
                            <input type="text" name="trans_id" id="trans_id" class="form-control" required readonly="">
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                       <div class="form-group">
                            <label class="font-weight-600 mb-0"><?php echo get_phrases(['transfer', 'date']);?></label>
                            <input type="text" name="trans_date" id="trans_date" class="form-control" value="<?php echo date('Y-m-d');?>" readonly="">
                        </div>
                    </div>
                     <div class="col-md-4 col-sm-12">
                       <div class="form-group">
                            <label class="font-weight-600 mb-0"><?php echo get_phrases(['branch', 'name']);?></label>
                            <input type="text" name="branch_name" id="branch_name" class="form-control" value="<?php echo $top_branch_name;?>" readonly="">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-8 col-sm-12">
                       <div class="form-group">
                            <label class="font-weight-600 mb-0"><?php echo get_phrases(['from', 'patient']);?> <i class="text-danger">*</i></label>
                            <?php echo form_dropdown('from_patient', '', '', 'class="custom-select" id="from_patient" required');?>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                       <div class="form-group">
                            <label class="font-weight-600 mb-0"><?php echo get_phrases(['current', 'balance']);?></label>
                            <input type="text" name="from_current_blnc" id="from_current_blnc" class="form-control" value="0.00" readonly="">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8 col-sm-12">
                       <div class="form-group">
                            <label class="font-weight-600 mb-0"><?php echo get_phrases(['to', 'patient']);?> <i class="text-danger">*</i></label>
                            <?php echo form_dropdown('to_patient', '', '', 'class="custom-select" id="to_patient" required');?>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                       <div class="form-group">
                            <label class="font-weight-600 mb-0"><?php echo get_phrases(['current', 'balance']);?></label>
                            <input type="text" name="to_current_blnc" id="to_current_blnc" class="form-control" value="0.00" readonly="">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 col-sm-12">
                       <div class="form-group">
                            <label class="font-weight-600 mb-0"><?php echo get_phrases(['transfer', 'account']);?> <i class="text-danger">*</i></label>
                            <input type="text" name="trans_amount" id="trans_amount" class="form-control onlyNumber" placeholder="<?php echo get_phrases(['enter', 'transfer', 'amount']);?>" required>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <label class="font-weight-600 mb-0"><?php echo get_phrases(['remarks']);?></label>
                            <textarea name="remarks" class="form-control" id="remarks" rows="2"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo get_phrases(['close']);?></button>
                <button type="submit" class="btn btn-success"><?php echo get_phrases(['transfer']);?></button>
            </div>
            <?php echo form_close();?>
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-lg" id="status-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel3" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="ModalLabel"><?php echo get_phrases(['balance', 'transfer']);?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
            <?php echo form_open_multipart('account/balanceTransfer/approvedPntTrans', 'class="needs-validation" id="statusForm" novalidate="" data="transCallBackData"');?>
            <div class="modal-body">
                <input type="hidden" name="from_head" id="up_from_head">
                <input type="hidden" name="to_head" id="up_to_head">
                <input type="hidden" name="from_name" id="up_from_name">
                <input type="hidden" name="to_name" id="up_to_name">
                <div class="row">
                    <div class="col-md-4 col-sm-12">
                       <div class="form-group">
                            <label class="font-weight-600 mb-0"><?php echo get_phrases(['transfer', 'id']);?></label>
                            <input type="hidden" name="transId" id="transId">
                            <input type="text" name="trans_id" id="up_trans_id" class="form-control" required readonly="">
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                       <div class="form-group">
                            <label class="font-weight-600 mb-0"><?php echo get_phrases(['transfer', 'date']);?></label>
                            <input type="text" name="trans_date" id="up_trans_date" class="form-control" readonly="">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-8 col-sm-12">
                       <div class="form-group">
                            <label class="font-weight-600 mb-0"><?php echo get_phrases(['from', 'patient']);?></label>
                            <input type="hidden" name="from_id" id="from_id">
                            <input type="text" name="from_patient" id="up_from_patient" class="form-control" readonly="">
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                       <div class="form-group">
                            <label class="font-weight-600 mb-0"><?php echo get_phrases(['current', 'balance']);?></label>
                            <input type="text" name="from_current_blnc" id="up_from_current_blnc" class="form-control" value="0.00" readonly="">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8 col-sm-12">
                       <div class="form-group">
                            <label class="font-weight-600 mb-0"><?php echo get_phrases(['to', 'patient']);?></label>
                            <input type="hidden" name="to_id" id="to_id">
                            <input type="text" name="to_patient" id="up_to_patient" class="form-control" readonly="">
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                       <div class="form-group">
                            <label class="font-weight-600 mb-0"><?php echo get_phrases(['current', 'balance']);?></label>
                            <input type="text" name="to_current_blnc" id="up_to_current_blnc" class="form-control" value="0.00" readonly="">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-8 col-sm-12">
                       <div class="form-group">
                            <label class="font-weight-600 mb-0"><?php echo get_phrases(['transfer', 'account']);?></label>
                            <input type="text" name="trans_amount" id="up_trans_amount" class="form-control" readonly="">
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <label class="font-weight-600 mb-0"><?php echo get_phrases(['transfer', 'status']);?> <i class="text-danger">*</i></label>
                        <?php
                        $app = array(
                            '' => '', 
                            '1'=> get_phrases(['approved']),
                            '2'=> get_phrases(['rejected']),
                        );
                        echo form_dropdown('approval', $app, '', 'class="custom-select form-control" id="approval" required');?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo get_phrases(['close']);?></button>
                <button type="submit" class="btn btn-success"><?php echo get_phrases(['approved']);?></button>
            </div>
            <?php echo form_close();?>
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-xl" id="view-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel3" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="ModalLabel"><?php echo get_phrases(['balance', 'transfer']);?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <thead>
                                    <tr>
                                        <th><?php echo get_phrases(['transfer', 'id']);?></th>
                                        <th><?php echo get_phrases(['from', 'patient']);?></th>
                                        <th><?php echo get_phrases(['to', 'patient']);?></th>
                                        <th><?php echo get_phrases(['transfer', 'amount']);?></th>
                                        <th><?php echo get_phrases(['remarks']);?></th>
                                        <th><?php echo get_phrases(['transfer', 'date']);?></th>
                                        <th><?php echo get_phrases(['status']);?></th>
                                    </tr>
                                </thead>
                                <tbody id="viewTransfer">
                                    
                                </tbody>
                            </table>
                        </div>
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
    var transCallBackData = function () { 
        $('#add-modal, #status-modal').modal('hide');   
        $('#transForm')[0].reset();       
        $('#transForm').removeClass('was-validated');    
        $('#approvalList').DataTable().ajax.reload(null, false);
    }

    $(document).ready(function() { 
        "use strict";
        $('option:first-child').val('').trigger('change');

        $('#approvalList').DataTable({ 
             responsive: true,
             lengthChange: false,
             "aaSorting": [[ 0, "desc" ]],
             "columnDefs": [
                { "bSortable": false, "aTargets": [7] },
            ],
            dom: "<'row'<'col-md-6'B><'col-md-6'f>>rt<'bottom'<'row'<'col-md-6'i><'col-md-6'p>>><'clear'>",
            buttons: [{
                    extend: 'copyHtml5',
                    text: '<i class="far fa-copy custool" title="Copy"></i>',
                    titleAttr: 'Copy',
                    className: 'btn-light',
                    title : 'Pending_Rejected_list<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5 ]
                    }
                },
                {
                    extend: 'excelHtml5',
                    text: '<i class="far fa-file-excel custool" title="Excel"></i>',
                    titleAttr: 'Excel',
                    className: 'btn-light',
                    title : 'Pending_Rejected_list<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5 ]
                    }
                },
                {
                    extend: 'csvHtml5',
                    text: '<i class="far fa-file-alt custool" title="CSV"></i>',
                    titleAttr: 'CSV',
                    className: 'btn-light',
                    title : 'Pending_Rejected_list<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5 ]
                    }
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="far fa-file-pdf custool" title="PDF"></i>',
                    titleAttr: 'PDF',
                    className: 'btn-light',
                    title : 'Pending_Rejected_list<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5 ]
                    }
                }
            ],
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'ajax': {
               'url': _baseURL + 'account/balanceTransfer/getPntBTransfer',
               'data':{'csrf_stream_name':csrf_val}
            },
          'columns': [
             { data: 'id' },
             { data: 'from_patient'},
             { data: 'to_patient' },
             { data: 'amount' },
             { data: 'trans_date' },
             { data: 'created_by' },
             { data: 'button'},
             { data: 'action' }
          ],
        });

        function uuidv4() {
          return 'Txxx-xxxx'.replace(/[xy]/g, function(c) {
            var r = Math.random() * 16 | 0, v = c == 'x' ? r : (r & 0x3 | 0x8);
            return v.toString(16);
          });
        }

        $('.addBlnc').on('click', function(){
            $('#transForm').removeClass('was-validated');
            $('#from_patient').val('').trigger('change');
            $('#to_patient').val('').trigger('change');
            $('#trans_id').val(uuidv4());
            $('#add-modal').modal('show');
        });

        $('#approvalList').on('click', '.statusAction', function(e){
            e.preventDefault();
            var id = $(this).attr('data-id');

            var submit_url = _baseURL+"account/balanceTransfer/pntTransById/"+id; 
            $.ajax({
                type: 'POST',
                url: submit_url,
                data: {'csrf_stream_name':csrf_val},
                dataType: 'JSON',
                success: function(res) {
                    $('#transId').val(res.id);
                    $('#up_from_patient').val(res.fromPatient);
                    $('#up_to_patient').val(res.toPatient);
                    $('#up_from_name').val(res.fromPatient);
                    $('#up_to_name').val(res.toPatient);
                    $('#from_id').val(res.from_patient);
                    $('#to_id').val(res.to_patient);
                    $('#up_from_head').val(res.acc_head);
                    $('#up_to_head').val(res.toAccHead);
                    $('#up_trans_id').val(res.trans_id);
                    $('#up_trans_date').val(res.trans_date);
                    $('#up_from_current_blnc').val(res.balance);
                    $('#up_to_current_blnc').val(res.toBalance);
                    $('#up_trans_amount').val(res.amount);
                    $('#status-modal').modal('show');
                }
            });  
        });

         $('#approvalList').on('click', '.viewAction', function(e){
            e.preventDefault();
            var id = $(this).attr('data-id');

            var submit_url = _baseURL+"account/balanceTransfer/pntTransById/"+id; 
            $.ajax({
                type: 'POST',
                url: submit_url,
                data: {'csrf_stream_name':csrf_val},
                dataType: 'JSON',
                success: function(res) {
                    var status;
                    if(res.isApproved==1){
                        status = '<a href="javascript:void(0);" class="badge badge-success">Approved</a>'
                    }else if(res.isApproved==0){
                        status = '<a href="javascript:void(0);" class="badge badge-warning text-white">Pending</a>'
                    }else{
                        status = '<a href="javascript:void(0);" class="badge badge-danger">Rejected</a>'
                    }
                    var html = '<tr>'+
                                '<td>'+res.trans_id+'</td>'+
                                '<td>'+res.fromPatient+'</td>'+
                                '<td>'+res.toPatient+'</td>'+
                                '<td>'+res.amount+'</td>'+
                                '<td>'+res.remarks+'</td>'+
                                '<td>'+res.trans_date+'</td>'+
                                '<td>'+status+'</td>'+
                                '</tr>';
                    $('#viewTransfer').html(html);
                    $('#view-modal').modal('show');
                }
            });  
        });

        // search patient
        $('#from_patient, #to_patient').select2({
            placeholder: '<?php echo get_phrases(['search', 'patient', 'by', 'file', 'no', 'or', 'name']);?>',
            minimumInputLength: 3,
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
        });

        // check transfer amount limit
        $('#trans_amount').on('keyup', function(e){
            e.preventDefault();
            var currB = parseFloat($('#from_current_blnc').val());
            var amount = parseFloat($(this).val());
            if(amount >currB){
                alert('You can not exceed current Balance '+currB);
                $('#trans_amount').val(currB);
            // }else{
            //     if(amount > 500.00){
            //         if(confirm('Transfer amount more than 500. Are you agreed this '+amount+' amount and needs to approval?')){

            //         }else{
            //             $('#trans_amount').val(0);
            //         }
            //     }
            }
        });

        // get patient current balance
        $('#from_patient').on('change', function(e){
            e.preventDefault();
            var id = $(this).val();
            var branch_id = '<?php echo session('branchId');?>';
            
            var submit_url = _baseURL+"account/balanceTransfer/pntBalance/"+id+'/'+branch_id; 
            $.ajax({
                type: 'POST',
                url: submit_url,
                data: {'csrf_stream_name':csrf_val},
                dataType: 'JSON',
                success: function(res) {
                    $('#from_head').val(res.acc_head);
                    $('#from_current_blnc').val(res.balance);
                    $('#from_name').val(res.nameE);
                }
            });  
        });

        // get patient current balance
        $('#to_patient').on('change', function(e){
            e.preventDefault();
            var id = $(this).val();
            var branch_id = '<?php echo session('branchId');?>';
            
            var submit_url = _baseURL+"account/balanceTransfer/pntBalance/"+id+'/'+branch_id; 
            $.ajax({
                type: 'POST',
                url: submit_url,
                data: {'csrf_stream_name':csrf_val},
                dataType: 'JSON',
                success: function(res) {
                    $('#to_head').val(res.acc_head);
                    $('#to_current_blnc').val(res.balance);
                    $('#to_name').val(res.nameE);
                }
            });  
        });

    });
</script>