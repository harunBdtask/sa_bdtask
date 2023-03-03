<link href="<?php echo base_url()?>/assets/plugins/jquery-ui-1.12.1/jquery-ui.min.css" rel="stylesheet">
<style>
    .dataTable tbody input, .dataTable tbody select, .dataTable tfoot input, .dataTable tfoot select {
     height: auto; 
}
</style>
<div class="row">
    <div class="col-sm-12">
        <?php 
        $hasPrintAccess  = $permission->method('voucher_approval', 'print')->access();
        $hasExportAccess = $permission->method('voucher_approval', 'export')->access();
        if($permission->method('voucher_approval', 'read')->access() || $permission->method('voucher_approval', 'update')->access()){ ?>
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
                       <a href="<?php echo previous_url(); ?>" class="btn btn-warning btn-sm mr-1 ml-2"><i class="fas fa-angle-double-left mr-1"></i>Back</a>
                    </div>
                </div>
            </div>
            <div class="card-body">

                <div class="row form-group">  
                    <div class="col-sm-2"></div>
                    <div class="col-sm-2">
                        <input type="text" name="search_date" id="search_date" class="form-control" placeholder="<?php echo get_phrases(['select', 'voucher', 'date']); ?>" autocomplete="off">
                    </div>
          
                    <div class="col-sm-2">
                        <button type="button" class="btn btn-warning resetBtn text-white"><?php echo get_phrases(['reset']);?></button>
                    </div> 
                </div>


               
                
                <?php echo form_open_multipart('account/accounts/approve_multiple_voucher', 'class="" id="creditForm" novalidate="" data="creditCallBackData"');?>

                <table id="approvalList" class="table display table-bordered  table-hover compact" width="100%">
                    
                    <thead>
                        <tr>
                            <th><div class="checkbox-success">
                    <input type="checkbox" class="checkbox select_all" id="selectall">  
                    <label for="selectall">All</label>
                </div></th>
                            <th><?php echo get_phrases(['voucher', 'no']);?></th>
                            <th><?php echo get_phrases(['voucher', 'type']);?></th>
                            <th><?php echo get_phrases(['description']);?></th>
                            <th><?php echo get_phrases(['debit']);?></th>
                            <th><?php echo get_phrases(['credit']);?></th>
                            <th><?php echo get_phrases(['created', 'by']);?></th>
                            <th><?php echo get_phrases(['created', 'date']);?></th>
                            <th><?php echo get_phrases(['status']);?></th>
                            <th><?php echo get_phrases(['action']);?></th>
                        </tr>
                    </thead>

                    <tbody>
                    </tbody>
                    <tfoot >
                        <tr>
                            <td colspan="10" class="text-left" style="float-left"><button type ="submit" class="btn btn-success">Approve</button></td>
                        </tr>
                    </tfoot>
                    
                </table>
                <?php echo form_close();?>
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

<!-- view voucher details -->
<div class="modal fade bd-example-modal-lg" id="details-modal" tabindex="-1" role="dialog" aria-labelledby="detailsModalLabel3" aria-hidden="true">
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

<script src="<?php echo base_url()?>/assets/plugins/jquery-ui-1.12.1/jquery-ui.min.js" type="text/javascript"></script>
<script type="text/javascript">
    var statusCallBackData = function () { 
        $('#status-modal').modal('hide');       
        $('#approvalList').DataTable().ajax.reload(null, false);
    }

    $(document).ready(function() { 
       "use strict";
        $('option:first-child').val('').trigger('change');
        $('#search_date').datepicker({dateFormat: 'yy-mm-dd'});

        $('#approvalList').DataTable({ 
             responsive: true,
             lengthChange: true,
             "lengthMenu": [ [15, 30, 50, 100, 500, 1000], [15, 30, 50, 100, 500, 1000] ],
             "aaSorting": [[ 7, "desc" ]],
             "columnDefs": [
                { "bSortable": false, "aTargets": [0,9] },
            ],
            dom: "<'row'<?php if($hasExportAccess || $hasPrintAccess){ echo "<'col-md-4'l><'col-md-4'B><'col-md-4'f>"; } else { echo "<'col-md-6'l><'col-md-6'f>"; } ?>>rt<'bottom'<'row'<'col-md-6'i><'col-md-6'p>>><'clear'>",
           
            buttons: [
               
                <?php if($hasPrintAccess){ ?>{
                    extend: 'print',
                    text: '<i class="far fa-copy custool" title="Print"></i>',
                    titleAttr: 'Print',
                    className: 'btn-light',
                    title : 'Voucher_Approval_list<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8 ]
                    }
                },
                <?php } if($hasExportAccess){ ?> {
                    extend: 'copyHtml5',
                    text: '<i class="far fa-print custool" title="print"></i>',
                    titleAttr: 'print',
                    className: 'btn-light',
                    title : 'Voucher_Approval_list<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8]
                    }
                },
                {
                    extend: 'excelHtml5',
                    text: '<i class="far fa-file-excel custool" title="Excel"></i>',
                    titleAttr: 'Excel',
                    className: 'btn-light',
                    title : 'Voucher_Approval_list<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8 ]
                    }
                },
                {
                    extend: 'csvHtml5',
                    text: '<i class="far fa-file-alt custool" title="CSV"></i>',
                    titleAttr: 'CSV',
                    className: 'btn-light',
                    title : 'Voucher_Approval_list<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8]
                    }
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="far fa-file-pdf custool" title="PDF"></i>',
                    titleAttr: 'PDF',
                    className: 'btn-light',
                    title : 'Voucher_Approval_list<?php echo date('Y-m-d');?>',
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
               'url': _baseURL + 'account/accounts/getVApprovalList',
               'data': function ( d ) {
                    d.csrf_stream_name = csrf_val;
                    d.search_date      = $('#search_date').val();
                    d.branch_id        = $('#branch_id').val();
                }
            },
          'columns': [
             { data: 'checkBtn' ,class: "checkbox-success" },
             { data: 'VNo' },
             { data: 'typeName'},
             { data: 'Narration' },
             { data: 'Debit' },
             { data: 'Credit' },
             { data: 'created_by' },
             { data: 'CreateDate' },
             { data: 'button'},
             { data: 'action'}
          ],
        });

        // custom search
        $("#search_date, #branch_id").on('change', function(){
            $('#approvalList').DataTable().ajax.reload();
        });

        // reset button
        $('.resetBtn').on('click', function(e){
            $('#branch_id').val('').trigger('change');
            $('#search_date').val('');
        });

        // branch list
        $.ajax({
            type:'GET',
            url: _baseURL+'auth/branchList',
            dataType: 'json',
            data:{'csrf_stream_name':csrf_val},
        }).done(function(data) {
            $("#branch_id").select2({
                placeholder: '<?php echo get_phrases(['select', 'branch']);?>',
                data: data
            });
        });

     
        $('#approvalList').on('click', '.statusAction', function(e) {
            e.preventDefault();
            var id = $(this).attr('data-id');

            var submit_url = _baseURL + "account/vouchers/approvedVoucher/" + id;
            if (confirm('<?php echo get_phrases(['are_you_sure', 'approved', 'this', 'voucher']); ?>')) {
                $.ajax({
                    type: 'POST',
                    url: submit_url,
                    data: {
                        'csrf_stream_name': csrf_val,
                        status: 1
                    },
                    dataType: 'JSON',
                    success: function(response) {
                        if (response.success == true) {
                            toastr.success(response.message, response.title);
                            $('#approvalList').DataTable().ajax.reload(null, false);
                        } else {
                            toastr.warning(response.message, response.title);
                        }
                    }
                });
            }
            return true;
        });

        $('#approvalList').on('click', '.deleteAction', function(e) {
            e.preventDefault();
            var id = $(this).attr('data-id');

            var submit_url = _baseURL + "account/vouchers/deleteVoucher/" + id;
            if (confirm('<?php echo get_phrases(['are_you_sure', 'delete', 'this', 'voucher']); ?>')) {
                $.ajax({
                    type: 'POST',
                    url: submit_url,
                    data: {
                        'csrf_stream_name': csrf_val,
                        status: 1
                    },
                    dataType: 'JSON',
                    success: function(response) {
                        if (response.success == true) {
                            toastr.success(response.message, response.title);
                            $('#approvalList').DataTable().ajax.reload(null, false);
                        } else {
                            toastr.warning(response.message, response.title);
                        }
                    }
                });
            }
            return true;
        });

        $('#approvalList').on('click', '.viewAction', function(e) {
            e.preventDefault();
            var VNo = $(this).attr('data-id');
            $('#details-modal').modal('show');
            var submit_url = _baseURL + "account/vouchers/getVoucherDetails/" + VNo;
            $.ajax({
                type: 'POST',
                url: submit_url,
                data: {
                    'csrf_stream_name': csrf_val
                },
                dataType: 'JSON',
                success: function(response) {
                    $('#viewDetails').html('');
                    $('#viewDetails').html(response.data);
                }
            });
        });


        $(".select_all").on("change",function(){  
            //var id = $(this).val();
            $(".all").prop("checked", $(this).prop("checked"));
        });

    });
</script>