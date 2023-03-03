<link href="<?php echo base_url()?>/assets/plugins/jquery-ui-1.12.1/jquery-ui.min.css" rel="stylesheet">
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
                        <?php 
                        $hasCreateAccess = $permission->method('financial_year', 'create')->access();
                        $hasPrintAccess = $permission->method('financial_year', 'print')->access();
                        $hasExportAccess = $permission->method('financial_year', 'export')->access();
                        if( $hasCreateAccess ){ ?>
                            <button type="button" class="btn btn-success btn-sm mr-1 addShowModal"><i class="fas fa-plus mr-1"></i><?php echo get_phrases(['add', 'new']);?></button>
                       <?php } ?>
                       <a href="<?php echo previous_url(); ?>" class="btn btn-warning btn-sm mr-1 ml-2"><i class="fas fa-angle-double-left mr-1"></i><?php echo get_phrases(['back']);?></a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table id="fyList" class="table display table-bordered table-striped table-hover compact" width="100%">
                    <thead>
                        <tr>
                            <th><?php echo get_phrases(['sl']);?></th>
                            <th><?php echo get_phrases(['name']);?></th>
                            <th><?php echo get_phrases(['start', 'date']);?></th>
                            <th><?php echo get_phrases(['end', 'date']);?></th>
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
<!-- Large modal button -->
<div class="modal fade bd-example-modal-lg" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel3" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="listsModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
           <?php echo form_open_multipart('account/financial_year/addFinancialYear', 'class="ajaxForm needs-validation" id="ajaxForm" novalidate="" data="showCallBackData"');?>
            <div class="modal-body">
                <input type="hidden" name="fy_id" id="fy_id" />
                <input type="hidden" name="action" id="action" />

                <div class="row form-group">
                    <label for="yearName" class="col-sm-4 col-form-label font-weight-600"><?php echo get_phrases(['fianncial', 'year', 'name']) ?> <i class="text-danger">*</i></label>
                    <div class="col-sm-8">
                        <input type="text" name="yearName" id="yearName" class="form-control" autocomplete="off" required>
                    </div>
                </div>
                <div class="row form-group">
                    <label for="startDate" class="col-sm-4 col-form-label font-weight-600"><?php echo get_phrases(['start','date']) ?> <i class="text-danger">*</i></label>
                    <div class="col-sm-8">
                        <input type="text" name="startDate" class="form-control datepic" id="startDate" autocomplete="off" required>  
                    </div>
                </div>
                <div class="row form-group">
                    <label for="endDate" class="col-sm-4 col-form-label font-weight-600"><?php echo get_phrases(['end','date']) ?> <i class="text-danger">*</i></label>
                    <div class="col-sm-8">
                        <input type="text" name="endDate" class="form-control datepic" id="endDate" autocomplete="off" required>  
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo get_phrases(['close']);?></button>
                <button type="submit" class="btn btn-success saveBtn"></button>
            </div>
            <?php echo form_close();?>
        </div>
    </div>
</div>

<script src="<?php echo base_url()?>/assets/plugins/jquery-ui-1.12.1/jquery-ui.min.js" type="text/javascript"></script>
<script type="text/javascript">
    var showCallBackData = function () {     
        $('#addModal').modal('hide');
        $('#fyList').DataTable().ajax.reload(null, false);
    }
    $(document).ready(function() { 
       "use strict";
    
        $('.datepic').datepicker({dateFormat: 'yy-mm-dd'});
        $('#fyList').DataTable({ 
             responsive: true,
             lengthChange: true,
             "aaSorting": [[ 0, "desc" ]],
             "columnDefs": [
                { "bSortable": false, "aTargets": [5] }
            ],
            dom: "<'row'<?php if($hasExportAccess || $hasPrintAccess){ echo "<'col-md-4'l><'col-md-4'B><'col-md-4'f>"; } else { echo "<'col-md-6'l><'col-md-6'f>"; } ?>>rt<'bottom'<'row'<'col-md-6'i><'col-md-6'p>>><'clear'>",
            buttons: [
                <?php if($hasPrintAccess){ ?>
                {
                    extend: 'print',
                    text: '<i class="fa fa-print custool" title="Print"></i>',
                    titleAttr: 'Print',
                    className: 'btn-light',
                    title : 'List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4 ]
                    }
                },
                <?php } if($hasExportAccess){ ?>
                {
                    extend: 'copyHtml5',
                    text: '<i class="far fa-copy custool" title="Copy"></i>',
                    titleAttr: 'Copy',
                    className: 'btn-light',
                    title : 'List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4 ]
                    }
                },
                {
                    extend: 'excelHtml5',
                    text: '<i class="far fa-file-excel custool" title="Excel"></i>',
                    titleAttr: 'Excel',
                    className: 'btn-light',
                    title : 'List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4 ]
                    }
                },
                {
                    extend: 'csvHtml5',
                    text: '<i class="far fa-file-alt custool" title="CSV"></i>',
                    titleAttr: 'CSV',
                    className: 'btn-light',
                    title : 'List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4 ]
                    }
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="far fa-file-pdf custool" title="PDF"></i>',
                    titleAttr: 'PDF',
                    className: 'btn-light',
                    title : 'List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4 ]
                    }
                }
            <?php }?>
            ],
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'ajax': {
               'url': _baseURL + 'account/financial_year/getFinancialYear',
               'data':{'csrf_stream_name':csrf_val}
            },
          'columns': [
             { data: 'id' },
             { data: 'yearName' },
             { data: 'startDate' },
             { data: 'endDate' },
             { data: 'status' },
             { data: 'button'}
          ]
        });

        $('.addShowModal').on('click', function(){
            resetForm('add');
            $('#addModal').modal('show');
        });

        function resetForm(action){
            $('.ajaxForm').removeClass('was-validated');
            $('#fy_id').val('');
            $('#action').val(action);
            $('#startDate').val('');
            $('#endDate').val('');
            if(action=='add'){
                $('#listsModalLabel').text('<?php echo get_phrases(['add', 'financial', 'year']);?>');
                $('.saveBtn').text('<?php echo get_phrases(['add']);?>');
            }else{
                $('#listsModalLabel').text('<?php echo get_phrases(['update', 'financial', 'year']);?>');
                $('.saveBtn').text('<?php echo get_phrases(['update']);?>');
            }
        }

        $('#fyList').on('click', '.actionEdit', function(e){
            e.preventDefault();
            resetForm('update');
            
            var fy_id = $(this).attr('data-id');
            var submit_url = _baseURL+'account/financial_year/getFyById';

            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'JSON',
                data: {'csrf_stream_name':csrf_val, fy_id:fy_id},
                success: function(data) {
                    $('#addModal').modal('show');
                    $('#fy_id').val(data.id);
                    $('#yearName').val(data.yearName);
                    $('#startDate').val(data.startDate);
                    $('#endDate').val(data.endDate);
                   
                },error: function() {

                }
            });   

        });

        // activate financial year
        $('#fyList').on('click', '.actionActive', function(e){
            e.preventDefault();

            var fy_id = $(this).attr('data-id');
           
            var submit_url = _baseURL+"account/financial_year/activateFyById";
            var check = confirm('<?php echo get_phrases(["are_you_sure"])?>');  
            if(check == true){  
                $.ajax({
                    type: 'POST',
                    url: submit_url,
                    data: {'csrf_stream_name':csrf_val, fy_id:fy_id, action:'activate'},
                    dataType: 'JSON',
                    success: function(res) {
                        if(res.success==true){
                            toastr.success(res.message, res.title);
                            $('#fyList').DataTable().ajax.reload(null, false);
                        }else{
                            toastr.error(res.message, res.title);
                        }
                    },error: function() {

                    }
                });
            }   
        });

        // send close request
        $('#fyList').on('click', '.actionClose', function(e){
            e.preventDefault();

            var fy_id = $(this).attr('data-id');
            var submit_url = _baseURL+"account/financial_year/activateFyById";
            var check = confirm('<?php echo get_phrases(["are_you_sure"])?>');  
            if(check == true){  
                $.ajax({
                    type: 'POST',
                    url: submit_url,
                    data: {'csrf_stream_name':csrf_val, fy_id:fy_id, action:'close_request'},
                    dataType: 'JSON',
                    success: function(res) {
                        if(res.success==true){
                            toastr.success(res.message, res.title);
                            $('#fyList').DataTable().ajax.reload(null, false);
                        }else{
                            toastr.error(res.message, res.title);
                        }
                    },error: function() {

                    }
                });
            }   
        });

        // undo close request
        $('#fyList').on('click', '.actionCloseUndo', function(e){
            e.preventDefault();

            var fy_id = $(this).attr('data-id');
            var submit_url = _baseURL+"account/financial_year/activateFyById";
            var check = confirm('<?php echo get_phrases(["are_you_sure"])?>');  
            if(check == true){  
                $.ajax({
                    type: 'POST',
                    url: submit_url,
                    data: {'csrf_stream_name':csrf_val, fy_id:fy_id, action:'undo'},
                    dataType: 'JSON',
                    success: function(res) {
                        if(res.success==true){
                            toastr.success(res.message, res.title);
                            $('#fyList').DataTable().ajax.reload(null, false);
                        }else{
                            toastr.error(res.message, res.title);
                        }
                    },error: function() {

                    }
                });
            }   
        });

        // send close request
        $('#fyList').on('click', '.actionAppClReq', function(e){
            e.preventDefault();

            var fy_id = $(this).attr('data-id');
            var submit_url = _baseURL+"account/financial_year/closeFinancialYear";
            var check = confirm('<?php echo get_phrases(["are_you_sure"])?>'+' Need 30-60 seconds!');  
            if(check == true){  
                preloader_ajax();
                $.ajax({
                    type: 'POST',
                    url: submit_url,
                    data: {'csrf_stream_name':csrf_val, fy_id:fy_id},
                    dataType: 'JSON',
                    success: function(res) {
                        if(res.success==true){
                            toastr.success(res.message, res.title);
                            $('#fyList').DataTable().ajax.reload(null, false);
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