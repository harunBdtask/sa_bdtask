<div class="row">
    <div class="col-sm-12">
        <?php if($permission->module('leave_application')->access()){ ?>
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
                        <?php if($permission->method('leave_application', 'create')->access()){ ?>
                        <a href="javascript:void(0);" class="btn btn-success btn-sm mr-1 addLeaveApplication"><i class="fas fa-plus mr-1"></i><?php echo get_phrases(['add', 'leave', 'application']);?></a>
                        <?php }?>
                       <a href="<?php echo previous_url(); ?>" class="btn btn-warning btn-sm mr-1 ml-2"><i class="fas fa-angle-double-left mr-1"></i><?php echo get_phrases(['back']);?></a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table id="leaveFormList" class="table display table-bordered table-striped table-hover compact" width="100%">
                    <thead>
                        <tr>
                            <th><?php echo get_phrases(['serial']);?></th>
                            <th><?php echo get_phrases(['employee']);?></th>
                            <th><?php echo get_phrases(['leave', 'type']);?></th>
                            <th><?php echo get_phrases(['application','start']);?></th>
                            <th><?php echo get_phrases(['application','end']);?></th>
                            <th><?php echo get_phrases(['approval','start']);?></th>
                            <th><?php echo get_phrases(['approval','end']);?></th>
                            <th><?php echo get_phrases(['apply', 'days']);?></th>
                            <th><?php echo get_phrases(['approved', 'days']);?></th>
                            <th><?php echo get_phrases(['status']);?></th>
                            <th><?php echo get_phrases(['action']);?></th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
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

<div class="modal fade bd-example-modal-xl" id="add-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel3" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="addModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
            <?php echo form_open_multipart('human_resources/leave_management/create_leave_application', 'class="needs-validation" id="leaveApplicationForm" novalidate="" data="jobCallBackData"');?>
            <div class="modal-body">
                <input type="hidden" name="id" id="id">
                <input type="hidden" name="action" id="action">

                <div class="row">

                    
                    <div class="col-md-6 col-sm-12">
                       <div class="form-group">
                            <div class="form-group">
                                <label class="font-weight-600 mb-0"><?php echo get_phrases(['employee']);?> <i class="text-danger">*</i></label>
                                <?php echo form_dropdown('employee_id', $employees, '', 'class="form-control select2" id="employee_id" required disabled');?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-sm-12">
                       <div class="form-group">
                            <label class="font-weight-600 mb-0"><?php echo get_phrases(['leave','type']);?> <i class="text-danger">*</i></label>
                             <!-- <select name="leave_type" id="leave_type" class="custom-select form-control">
                               <option value=""><?php echo get_phrases(['select','leave','type']);?></option>
                               <option value="cpl"><?php echo get_phrases(['cpl','leave']);?></option>
                               <option value="earned"><?php echo get_phrases(['earned','leave']);?></option>
                               <option value="other"><?php echo get_phrases(['other']);?></option>
                            </select> -->
                            <?php echo form_dropdown('leave_type', $leave_types, '', 'class="form-control select2" id="leave_type" required');?>
                        </div>
                   </div>
                   
                </div>

                <div class="row">

                   <div class="col-md-6 col-sm-12">
                       <div class="form-group">
                            <label class="font-weight-600 mb-0"><?php echo get_phrases(['application','start', 'date']);?> <i class="text-danger">*</i></label>
                            <input type="text" name="apply_strt_date" id="apply_strt_date" class="form-control only_apply_strt_date" placeholder="<?php echo get_phrases(['application','start', 'date']);?>" autocomplete="off" required>
                        </div>
                   </div>

                   <div class="col-md-6 col-sm-12">
                       <div class="form-group">
                            <label class="font-weight-600 mb-0"><?php echo get_phrases(['application','end', 'date']);?> <i class="text-danger">*</i></label>
                            <input type="text" name="apply_end_date" id="apply_end_date" class="form-control only_apply_end_date" placeholder="<?php echo get_phrases(['application','end', 'date']);?>" autocomplete="off" required>
                        </div>
                   </div>

                </div>

                <div class="row">

                    <div class="col-md-6 col-sm-12">
                       <div class="form-group">
                            <label class="font-weight-600 mb-0"><?php echo get_phrases(['apply','day']);?> <i class="">*</i></label>
                            <input type="text" name="apply_sday" id="apply_sday" class="form-control onlyNumber" placeholder="<?php echo get_phrases(['apply','day']);?>" autocomplete="off" readonly>
                        </div>
                   </div>

                    <div class="col-md-6 col-sm-12">
                       <div class="form-group">
                            <label class="font-weight-600 mb-0"><?php echo get_phrases(['application','hard', 'copy']);?> </label>
                            <input type="file" name="application_hard_copy" id="application_hard_copy" class="form-control">
                        </div>
                   </div>

                </div>

                <div class="row">

                   <div class="col-md-6 col-sm-12">
                       <div class="form-group">
                            <label class="font-weight-600 mb-0"><?php echo get_phrases(['approve','start', 'date']);?> <i class="text-danger">*</i></label>
                            <input type="text" name="leave_aprv_strt_date" id="leave_aprv_strt_date" class="form-control" placeholder="<?php echo get_phrases(['approve','start', 'date']);?>" autocomplete="off" readonly>
                        </div>
                   </div>

                   <div class="col-md-6 col-sm-12">
                       <div class="form-group">
                            <label class="font-weight-600 mb-0"><?php echo get_phrases(['approved','end', 'date']);?> <i class="text-danger">*</i></label>
                            <input type="text" name="leave_aprv_end_date" id="leave_aprv_end_date" class="form-control" placeholder="<?php echo get_phrases(['approved','end', 'date']);?>" autocomplete="off" readonly>
                        </div>
                   </div>

                </div>

                <div class="row">

                   <div class="col-md-6 col-sm-12">
                       <div class="form-group">
                            <label class="font-weight-600 mb-0"><?php echo get_phrases(['approved','day']);?> <i class="">*</i></label>
                            <input type="text" name="num_aprv_day" id="num_aprv_day" class="form-control" placeholder="<?php echo get_phrases(['number','of','days','approved']);?>" autocomplete="off" readonly>
                        </div>
                   </div>

                   <div class="col-md-6 col-sm-12">
                       <div class="form-group">
                            <label class="font-weight-600 mb-0"><?php echo get_phrases(['reason']);?> <i class="">*</i></label>
                            <textarea name="reason" class="form-control" id="reason" rows="2"></textarea>
                        </div>
                   </div>

                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo get_phrases(['close']);?></button>
                <button type="submit" class="btn btn-success" id="saveBtn"></button>
            </div>
            <?php echo form_close();?>
        </div>
    </div>
</div>

<script type="text/javascript">
    var jobCallBackData = function () { 
        $('#add-modal').modal('hide');   
        $('#leaveApplicationForm')[0].reset();       
        $('#leaveApplicationForm').removeClass('was-validated');    
        $('#leaveFormList').DataTable().ajax.reload(null, false);
    }

    $(document).ready(function() { 
        "use strict";

        // console.log(_baseURL);

        $('#leaveFormList').DataTable({ 
             responsive: true,
             lengthChange: false,
             "aaSorting": [[ 3, "asc" ]],
             "columnDefs": [
                { "bSortable": false, "aTargets": [9,10] },
            ],
            dom: "<'row'<'col-md-6'B><'col-md-6'f>>rt<'bottom'<'row'<'col-md-6'i><'col-md-6'p>>><'clear'>",
            buttons: [{
                    extend: 'copyHtml5',
                    text: '<i class="far fa-copy custool" title="Copy"></i>',
                    titleAttr: 'Copy',
                    className: 'btn-light',
                    title : 'Leave_Application<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9,10]
                    }
                },
                {
                    extend: 'excelHtml5',
                    text: '<i class="far fa-file-excel custool" title="Excel"></i>',
                    titleAttr: 'Excel',
                    className: 'btn-light',
                    title : 'Leave_Application<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9,10]
                    }
                },
                {
                    extend: 'csvHtml5',
                    text: '<i class="far fa-file-alt custool" title="CSV"></i>',
                    titleAttr: 'CSV',
                    className: 'btn-light',
                    title : 'Leave_Application<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9,10]
                    }
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="far fa-file-pdf custool" title="PDF"></i>',
                    titleAttr: 'PDF',
                    className: 'btn-light',
                    title : 'Leave_Application<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9,10]
                    }
                }
            ],
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'ajax': {
               'url': _baseURL + 'human_resources/leave_management/emp_leave_application_list',
               'data':{'csrf_stream_name':csrf_val}
            },
          'columns': [
             { data: 'sl' },
             { data: 'first_name', 
                render: function (data, type, row) {
                    return row.first_name + ' ' + row.last_name;
                }
             },
             { data: 'leave_type_name'},
             { data: 'apply_strt_date'},
             { data: 'apply_end_date'},
             { data: 'leave_aprv_strt_date'},
             { data: 'leave_aprv_end_date'},
             { data: 'apply_day'},
             { data: 'num_aprv_day'},
             { data: 'status' },
             { data: 'button' }
          ],

        });


        $('.addLeaveApplication').on('click', function(){

            $('#leaveApplicationForm').removeClass('was-validated');

            $('#id').val('');
            $('#action').val('add');

            $('#employee_id').val('<?php echo $employee_id;?>').trigger("change");
             // $('#leave_type').val("").trigger("change");
            $('#leave_type').val('');

            $('#apply_strt_date').val('');
            $('#apply_end_date').val('');

            $('#apply_sday').val('');

            $('#leave_aprv_strt_date').val('');
            $('#leave_aprv_end_date').val('');

            $('#saveBtn').text('<?php echo get_phrases(['save']);?>');
            $('#addModalLabel').text('<?php echo get_phrases(['leave', 'application']);?>');
            $('#add-modal').modal('show');
        });


        $('#leaveFormList').on('click', '.actionEdit', function(e){
            e.preventDefault();
            var id = $(this).attr('data-id');

            console.log(id);

            var submit_url = _baseURL+"human_resources/leave_management/leave_application_by_Id/"+id; 
            $.ajax({
                type: 'POST',
                url: submit_url,
                data: {'csrf_stream_name':csrf_val},
                dataType: 'JSON',
                success: function(response) {

                    console.log(response);

                    $('#id').val(response.leave_appl_id);

                     $('#employee_id').val(response.employee_id).trigger("change");
                     $('#leave_type').val(response.leave_type).trigger("change");

                    $('#apply_strt_date').val(response.apply_strt_date);
                    $('#apply_end_date').val(response.apply_end_date);
                    $('#apply_sday').val(response.apply_day);

                    if(response.leave_aprv_strt_date && response.leave_aprv_strt_date){

                        $('#leave_aprv_strt_date').val(response.leave_aprv_strt_date);
                        $('#leave_aprv_end_date').val(response.leave_aprv_end_date);
                        $('#num_aprv_day').val(response.num_aprv_day);
                    }

                    $('#reason').val(response.reason);

                    $('#action').val('update');
                    $('#saveBtn').text('<?php echo get_phrases(['update']);?>');
                    $('#addModalLabel').text('<?php echo get_phrases(['update', 'leave', 'application']);?>');
                    $('#add-modal').modal('show');
                }
            });  
        });


        // delete department
        $('#leaveFormList').on('click', '.actionDelete', function(e){
            e.preventDefault();

            var id = $(this).attr('data-id');
            
            var submit_url = _baseURL+"human_resources/leave_management/del_lve_apli_by_final_approv/"+id;
            // console.log(submit_url);

            var check = confirm('<?php echo get_phrases(["are_you_sure"])?>');  
            if(check == true){  
                $.ajax({
                    type: 'POST',
                    url: submit_url,
                    data: {'csrf_stream_name':csrf_val},
                    dataType: 'JSON',
                    success: function(res) {
                        if(res.success==true){
                            toastr.success(res.message, res.title);
                            $('#leaveFormList').DataTable().ajax.reload(null, false);
                            // location.reload();
                        }else{
                            toastr.error(res.message, res.title);
                        }
                    },error: function() {

                    }
                });
            }   
        });

        $('#employee_id').on('change', function(e){

            // console.log($(this).val());

            $('#apply_strt_date').val('');
            $('#apply_end_date').val('');
            $('#apply_sday').val('');
            $('#leave_aprv_strt_date').val('');
            $('#leave_aprv_end_date').val('');
            $('#num_aprv_day').val('');
        });

        $('#leave_type').on('change', function(e){

            // console.log($(this).val());

            $('#apply_strt_date').val('');
            $('#apply_end_date').val('');
            $('#apply_sday').val('');
            $('#leave_aprv_strt_date').val('');
            $('#leave_aprv_end_date').val('');
            $('#num_aprv_day').val('');

            // Check employee selected 
            var msg = "Please select employee first";
            var title = "Leave application";

            var employee_id = $('#employee_id').val();
            if(employee_id == "" || !employee_id){

                // $(this).val("").trigger("change");
                toastr.warning(msg, title);
            }
        });


        // Single Date Picker
        $('.only_apply_strt_date').daterangepicker({
            "autoApply":false,
            singleDatePicker: true,
            locale : {
                format : 'YYYY-MM-DD'
            }
        }).on("change", function (e, picker) {

            // console.log($('#apply_strt_date').val());

            $('#apply_end_date').val('');
            $('#apply_sday').val('');
            $('#leave_aprv_strt_date').val('');
            $('#leave_aprv_end_date').val('');
            $('#num_aprv_day').val('');

            // Check employee selected 
            var msg = "Please select employee and leave type first";
            var title = "Leave application";

            var employee_id = $('#employee_id').val();
            var leave_type = $('#leave_type').val();
            if(employee_id == "" || leave_type == ""){

                $('#apply_strt_date').val('');
                $('#apply_end_date').val('');
                $('#apply_sday').val('');

                toastr.warning(msg, title);
            }
            
        });

        $('.only_apply_end_date').daterangepicker({
            "autoApply":false,
            singleDatePicker: true,
            locale : {
                format : 'YYYY-MM-DD'
            }
        }).on("change", function (e, picker) {

            // console.log($('#apply_end_date').val());

            $('#apply_sday').val('');
            $('#leave_aprv_strt_date').val('');
            $('#leave_aprv_end_date').val('');
            $('#num_aprv_day').val('');

            // Check employee selected 
            var msg = "Please fill up employee, leave type and  apply start date first";
            var title = "Leave application";

            var employee_id = $('#employee_id').val();
            var leave_type = $('#leave_type').val();
            var apply_strt_date = $('#apply_strt_date').val();
            var apply_end_date = $('#apply_end_date').val();

            if(employee_id == "" || leave_type == "" || apply_strt_date == ""){

                $('#apply_strt_date').val('');
                $('#apply_end_date').val('');
                
                toastr.warning(msg, title);
            }

            if(apply_strt_date != "" || apply_end_date != ""){

                var submit_url = _baseURL+"human_resources/leave_management/calculateDays";
                // console.log(submit_url);
                $.ajax({
                    type: 'POST',
                    url: submit_url,
                    data: {'csrf_stream_name':csrf_val,'apply_strt_date':apply_strt_date,'apply_end_date':apply_end_date},
                    dataType: 'JSON',
                    success: function(response) {

                        // console.log(response);

                        if(response.success==false){

                            $('#apply_strt_date').val('');
                            $('#apply_end_date').val('');
                            $('#apply_sday').val('');
                            
                            toastr.error(response.message, response.title);
                        }
                        else{

                            $('#apply_sday').val(response.days);
                        }
                    }
                }); 
                
            }
            
        });


        // Single Date Picker
        $('.only_leave_aprv_strt_date').daterangepicker({
            "autoApply":false,
            singleDatePicker: true,
            locale : {
                format : 'YYYY-MM-DD'
            }
        }).on("change", function (e, picker) {

            // console.log($('#leave_aprv_strt_date').val());

            $('#leave_aprv_end_date').val('');
            $('#num_aprv_day').val('');

            // Check employee selected 
            var msg = "Please fill up employee, leave type and others fields";
            var title = "Leave application";

            var employee_id = $('#employee_id').val();
            var leave_type = $('#leave_type').val();
            var apply_strt_date = $('#apply_strt_date').val();
            var apply_end_date = $('#apply_end_date').val();

            if(employee_id == "" || leave_type == "" || apply_strt_date == "" || apply_end_date == ""){

                $('#leave_aprv_strt_date').val('');
                $('#leave_aprv_end_date').val('');
                $('#num_aprv_day').val('');

                toastr.warning(msg, title);
            }
            
        });

        $('.only_leave_aprv_end_date').daterangepicker({
            "autoApply":false,
            singleDatePicker: true,
            locale : {
                format : 'YYYY-MM-DD'
            }
        }).on("change", function (e, picker) {

            // console.log($('#leave_aprv_end_date').val());

             // Check employee selected 
            var msg = "Please select employee, leave type, apply start date, end date and other fields first";
            var title = "Leave application";

            var employee_id = $('#employee_id').val();
            var leave_type = $('#leave_type').val();
            var apply_strt_date = $('#apply_strt_date').val();
            var apply_end_date = $('#apply_end_date').val();
            var leave_aprv_strt_date = $('#leave_aprv_strt_date').val();
            var leave_aprv_end_date = $('#leave_aprv_end_date').val();

            if(employee_id == "" || leave_type == "" || apply_strt_date == "" || apply_strt_date == "" || apply_end_date == "" || leave_aprv_strt_date == ""){

                $('#leave_aprv_strt_date').val('');
                $('#leave_aprv_end_date').val('');
                $('#num_aprv_day').val('');
                
                toastr.warning(msg, title);
            }

            if(leave_aprv_strt_date != "" || leave_aprv_end_date != ""){

                var submit_url = _baseURL+"human_resources/leave_management/calculateDaysOthers";
                // console.log(submit_url);
                $.ajax({
                    type: 'POST',
                    url: submit_url,
                    data: {'csrf_stream_name':csrf_val,'leave_aprv_strt_date':leave_aprv_strt_date,'leave_aprv_end_date':leave_aprv_end_date},
                    dataType: 'JSON',
                    success: function(response) {

                        // console.log(response);

                        if(response.success==false){

                            $('#leave_aprv_strt_date').val('');
                            $('#leave_aprv_end_date').val('');
                            $('#num_aprv_day').val('');
                            
                            toastr.error(response.message, response.title);
                        }
                        else{

                            $('#num_aprv_day').val(response.days);
                        }
                    }
                });  
            } 
        });

        
    });

</script>