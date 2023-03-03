<div class="row">
    <div class="col-sm-12">
        <?php if($permission->module('earned_leave')->access()){ ?>
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
                        <?php if($permission->method('earned_leave', 'create')->access()){ ?>
                        <a href="javascript:void(0);" class="btn btn-success btn-sm mr-1 genEarnedLeave"><i class="fas fa-plus mr-1"></i><?php echo get_phrases(['generate', 'earned', 'leave']);?></a>
                        <?php }?>
                       <a href="<?php echo previous_url(); ?>" class="btn btn-warning btn-sm mr-1 ml-2"><i class="fas fa-angle-double-left mr-1"></i><?php echo get_phrases(['back']);?></a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table id="earnedLeaveList" class="table display table-bordered table-striped table-hover compact" width="100%">
                    <thead>
                        <tr>
                            <th><?php echo get_phrases(['serial']);?></th>
                            <th><?php echo get_phrases(['employee', 'name']);?></th>
                            <th><?php echo get_phrases(['earned', 'leave']);?></th>
                            <th><?php echo get_phrases(['from','date']);?></th>
                            <th><?php echo get_phrases(['to','date']);?></th>
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

<div class="modal fade bd-example-modal-lg" id="add-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel3" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="addModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
            <?php echo form_open_multipart('', 'class="needs-validation" id="earnedLeaveForm" novalidate="" data="jobCallBackData"');?>
            <div class="modal-body">

                <div class="row">
                   <div class="col-md-12 col-sm-12">
                       <div class="form-group">
                            <label class="font-weight-600 mb-0"><?php echo get_phrases(['employee', 'name']);?> <!-- <i class="text-danger">*</i> --></label>
                             <input type="text" name="employee_name" id="employee_name" class="form-control" placeholder="<?php echo get_phrases(['employee', 'name']);?>" readonly>
                        </div>
                   </div>
                </div>

                 <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <label class="font-weight-600 mb-0"><?php echo get_phrases(['from','date']);?></label>
                        <input type="text" name="from_date" id="from_date_view" class="form-control onlyNumber" placeholder="<?php echo get_phrases(['year']);?>" readonly>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <label class="font-weight-600 mb-0"><?php echo get_phrases(['to','date']);?></label>
                        <input type="text" name="to_date" id="to_date_view" class="form-control onlyNumber" placeholder="<?php echo get_phrases(['year']);?>" readonly>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <label class="font-weight-600 mb-0"><?php echo get_phrases(['total', 'earned', 'leave']);?></label>
                        <input type="text" name="total_earned_leave" id="total_earned_leave_view" class="form-control onlyNumber" placeholder="<?php echo get_phrases(['total','leave']);?>" readonly>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo get_phrases(['close']);?></button>
            </div>
            <?php echo form_close();?>
        </div>
    </div>
</div>


<div class="modal fade bd-example-modal-lg" id="add-modal-earned-leave" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel3" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="addModalLabel2"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
            <?php echo form_open_multipart('human_resources/leave_management/generate_earned_leave', 'class="needs-validation" id="genEarnedLeaveForm" novalidate="" data="jobCallBackData"');?>
            <div class="modal-body">
                <input type="hidden" name="id" id="id">
                <input type="hidden" name="action" id="action">

                <div class="row" id="employee_name_dropdown">
                   <div class="col-md-12 col-sm-12">
                       <div class="form-group">
                            <label class="font-weight-600 mb-0"><?php echo get_phrases(['employee']);?> <i class="text-danger">*</i></label>
                            <?php echo form_dropdown('employee_id', $employees, '', 'class="form-control select2" id="employee_id" required');?>
                        </div>
                   </div>
                </div>

                <div class="row" id="employee_name_field">
                   <div class="col-md-12 col-sm-12">
                       <div class="form-group">
                            <label class="font-weight-600 mb-0"><?php echo get_phrases(['employee', 'name']);?> <!-- <i class="text-danger">*</i> --></label>
                             <input type="text" name="employee_name" id="employee_name_edit" class="form-control" placeholder="<?php echo get_phrases(['employee', 'name']);?>" readonly>
                        </div>
                   </div>
                </div>

                 <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <label class="font-weight-600 mb-0"><?php echo get_phrases(['from','date']);?></label>
                        <input type="text" name="from_date" id="from_date" class="form-control only_earned_leave_date" placeholder="<?php echo get_phrases(['from','date']);?>" required autocomplete="off">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <label class="font-weight-600 mb-0"><?php echo get_phrases(['to','date']);?></label>
                        <input type="text" name="to_date" id="to_date" class="form-control only_earned_leave_date" placeholder="<?php echo get_phrases(['to','date']);?>" required autocomplete="off">
                    </div>
                </div>

                <div class="row" id="total_earned_leave_field">
                    <div class="col-md-12 col-sm-12">
                        <label class="font-weight-600 mb-0"><?php echo get_phrases(['total', 'earned', 'leave']);?></label>
                        <input type="text" name="total_earned_leave" id="total_earned_leave" class="form-control onlyNumber" placeholder="<?php echo get_phrases(['total','leave']);?>" readonly>
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
        $('#add-modal-earned-leave').modal('hide');   
        $('#genEarnedLeaveForm')[0].reset();       
        $('#genEarnedLeaveForm').removeClass('was-validated');    
        $('#earnedLeaveList').DataTable().ajax.reload(null, false);
    }

    $(document).ready(function() { 
        "use strict";

        // console.log(_baseURL);

        $('#earnedLeaveList').DataTable({ 
             responsive: true,
             lengthChange: false,
             "aaSorting": [[ 1, "asc" ]],
             "columnDefs": [
                { "bSortable": false, "aTargets": [5] },
            ],
            dom: "<'row'<'col-md-6'B><'col-md-6'f>>rt<'bottom'<'row'<'col-md-6'i><'col-md-6'p>>><'clear'>",
            buttons: [{
                    extend: 'copyHtml5',
                    text: '<i class="far fa-copy custool" title="Copy"></i>',
                    titleAttr: 'Copy',
                    className: 'btn-light',
                    title : 'Earned_Leave<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4]
                    }
                },
                {
                    extend: 'excelHtml5',
                    text: '<i class="far fa-file-excel custool" title="Excel"></i>',
                    titleAttr: 'Excel',
                    className: 'btn-light',
                    title : 'Earned_Leave<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4]
                    }
                },
                {
                    extend: 'csvHtml5',
                    text: '<i class="far fa-file-alt custool" title="CSV"></i>',
                    titleAttr: 'CSV',
                    className: 'btn-light',
                    title : 'Earned_Leave<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4]
                    }
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="far fa-file-pdf custool" title="PDF"></i>',
                    titleAttr: 'PDF',
                    className: 'btn-light',
                    title : 'Earned_Leave<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4]
                    }
                }
            ],
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'ajax': {
               'url': _baseURL + 'human_resources/leave_management/earned_leave_list',
               'data':{'csrf_stream_name':csrf_val}
            },
          'columns': [
             { data: 'sl' ,
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
             },
             { data: 'first_name', 
                render: function (data, type, row) {
                    return row.first_name + ' ' + row.last_name;
                }
             },
             { data: 'total_leave'},
             { data: 'start_date'},
             { data: 'end_date'},
             { data: 'button' }
          ],
        });

        // Generate earned for an employee...
        $('.genEarnedLeave').on('click', function(){
            $('#genEarnedLeaveForm').removeClass('was-validated');

            $('#id').val('');
            $('#action').val('add');

            $('#employee_name_dropdown').show();
            $('#employee_name_field').hide();

            $('#employee_id').val("").trigger("change");
            $('#total_earned_leave_field').hide();
            $('#from_date').val('');
            $('#to_date').val('');

            $('#action').val('add');

            $('#saveBtn').text('<?php echo get_phrases(['save']);?>');
            $('#addModalLabel2').text('<?php echo get_phrases(['generate', 'earned', 'leave']);?>');
            $('#add-modal-earned-leave').modal('show');
        });


        // View earned for an employee...
        $('#earnedLeaveList').on('click', '.actionGenerateEdit', function(e){
            e.preventDefault();
            var id = $(this).attr('data-id');

            // console.log(id);

            var submit_url = _baseURL+"human_resources/leave_management/earned_leave_by_Id/"+id; 
            $.ajax({
                type: 'POST',
                url: submit_url,
                data: {'csrf_stream_name':csrf_val},
                dataType: 'JSON',
                success: function(response) {

                    console.log(response);

                    $('#id').val(response.id);
                    
                    $('#employee_id').val(response.employee_id).trigger("change");
                    $('#employee_name_dropdown').hide();

                    $('#employee_name_edit').val(response.employee_name);
                    $('#employee_name_field').show();

                    $('#total_earned_leave_field').show();
                    $('#total_earned_leave').val(response.total_leave);

                    $('#from_date').val(response.start_date);
                    $('#to_date').val(response.end_date);

                    $('#action').val('update');
                    $('#saveBtn').text('<?php echo get_phrases(['update']);?>');
                    $('#addModalLabel2').text('<?php echo get_phrases(['earned', 'leave','update']);?>');
                    $('#add-modal-earned-leave').modal('show');
                }
            }); 

        });


        // View earned for an employee...
        $('#earnedLeaveList').on('click', '.actionView', function(e){
            e.preventDefault();
            var id = $(this).attr('data-id');

            // console.log(id);

            var submit_url = _baseURL+"human_resources/leave_management/earned_leave_by_Id/"+id; 
            $.ajax({
                type: 'POST',
                url: submit_url,
                data: {'csrf_stream_name':csrf_val},
                dataType: 'JSON',
                success: function(response) {

                    // console.log(response);

                    // $('#id').val(response.leave_type_id);
                    
                    $('#employee_name').val(response.employee_name);
                    $('#total_earned_leave_view').val(response.total_leave);
                    $('#from_date_view').val(response.start_date);
                    $('#to_date_view').val(response.end_date);

                    // $('#action').val('update');
                    $('#saveBtn').text('<?php echo get_phrases(['earned', 'leave']);?>');
                    $('#addModalLabel').text('<?php echo get_phrases(['earned', 'leave']);?>');
                    $('#add-modal').modal('show');
                }
            }); 

        });


        // delete department
        // $('#earnedLeaveList').on('click', '.actionDelete', function(e){
        //     e.preventDefault();

        //     var id = $(this).attr('data-id');
            
        //     var submit_url = _baseURL+"human_resources/leave_management/delete_leave_type/"+id;
        //     // console.log(submit_url);

        //     var check = confirm('<?php echo get_phrases(["are_you_sure"])?>');  
        //     if(check == true){  
        //         $.ajax({
        //             type: 'POST',
        //             url: submit_url,
        //             data: {'csrf_stream_name':csrf_val},
        //             dataType: 'JSON',
        //             success: function(res) {
        //                 if(res.success==true){
        //                     toastr.success(res.message, res.title);
        //                     $('#earnedLeaveList').DataTable().ajax.reload(null, false);
        //                     // location.reload();
        //                 }else{
        //                     toastr.error(res.message, res.title);
        //                 }
        //             },error: function() {

        //             }
        //         });
        //     }   
        // });

        $('#from_date').val("");
        $('#to_date').val("");

        //Single Date Picker
        $('.only_earned_leave_date').daterangepicker({
            "autoApply":false,
            singleDatePicker: true,
            locale : {
                format : 'YYYY-MM-DD'
            }
        });

        
    });

</script>