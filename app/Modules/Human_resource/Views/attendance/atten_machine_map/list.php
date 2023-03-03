<div class="row">
    <div class="col-sm-12">
        <?php if($permission->module('attendance_machine_map')->access()){ ?>
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
                        <?php if($permission->method('attendance_machine_map', 'create')->access()){ ?>
                        <a href="javascript:void(0);" class="btn btn-success btn-sm mr-1 addAttenMapSetup"><i class="fas fa-plus mr-1"></i><?php echo get_phrases(['add', 'zkt','machine', 'id']);?></a>
                        <?php }?>
                       <a href="javascript:void(0);" onclick="goBackOnePage()" class="btn btn-warning btn-sm mr-1 ml-2"><i class="fas fa-angle-double-left mr-1"></i><?php echo get_phrases(['back']);?></a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table id="addAttenMapList" class="table display table-bordered table-striped table-hover compact" width="100%">
                    <thead>
                        <tr>
                            <th><?php echo get_phrases(['sl']);?></th>
                            <th><?php echo get_phrases(['employee']);?></th>
                            <th><?php echo get_phrases(['branch', 'name']);?></th>
                            <th><?php echo get_phrases(['zkt', 'machine', 'id']);?></th>
                            <th><?php echo get_phrases(['created', 'date']);?></th>
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
            <?php echo form_open_multipart('human_resources/attendance/add_atten_machine_map', 'class="needs-validation" id="addAttenMapForm" novalidate="" data="jobCallBackData"');?>

            <div class="modal-body">

                <input type="hidden" name="id" id="id">
                <input type="hidden" name="action" id="action">

                <div class="row" id="employee_dropdwn">
                    <div class="col-md-12 col-sm-12">
                        <div class="form-group">
                            <label class="font-weight-600 mb-0"><?php echo get_phrases(['employee']);?> <i class="text-danger">*</i></label>

                             <select name="emp_id" id="emp_id" class="custom-select form-control">
                                <?php if(!empty($employees)){ ?>
                                    <option value=""><?php echo get_phrases(['select','employee']);?></option>
                                    <?php foreach ($employees as $key => $value) {?>
                                        <option value="<?php echo $value['emp_id'];?>"><?php echo $value['emp_name'];?></option>
                                    <?php }?>
                                <?php }?>
                            </select>
                        </div>
                   </div>
                </div>

                <div class="row"  id="employee_name">
                    <div class="col-md-12 col-sm-12">
                        <div class="form-group">
                            <label class="font-weight-600 mb-0"><?php echo get_phrases(['employee']);?></label>
                            <input type="text" name="emp_name" class="form-control" id="emp_name" placeholder="<?php echo get_phrases(['employee','name']);?>" readonly>
                        </div>
                    </div>
                </div>

                <div class="row">
                   <div class="col-md-12 col-sm-12">
                       <div class="form-group">
                            <label class="font-weight-600 mb-0"><?php echo get_phrases(['zkt','machine','id']);?> <i class="text-danger">*</i></label>
                             <input type="text" name="zkt_machine_id" id="zkt_machine_id" class="form-control onlyNumber" placeholder="<?php echo get_phrases(['zkt','machine','id']);?>" autocomplete="off" required>
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
        $('#addAttenMapForm')[0].reset();       
        $('#addAttenMapForm').removeClass('was-validated');    
        // $('#addAttenMapList').DataTable().ajax.reload(null, false);

        location.reload();
    }

    $(document).ready(function() { 
        "use strict";

        $('#addAttenMapList').DataTable({ 
             responsive: true,
             lengthChange: false,
             "aaSorting": [[ 1, "asc" ]],
             "columnDefs": [
                { "bSortable": false, "aTargets": [0, 5] },
            ],
            dom: "<'row'<?php if($hasExportAccess || $hasPrintAccess){ echo "<'col-md-4'l><'col-md-4'B><'col-md-4'f>"; } else { echo "<'col-md-6'l><'col-md-6'f>"; } ?>>rt<'bottom'<'row'<'col-md-6'i><'col-md-6'p>>><'clear'>",
            buttons: [
                <?php if($hasPrintAccess){ ?>
                {
                    extend: 'print',
                    text: '<i class="fa fa-print custool" title="Print"></i>',
                    titleAttr: 'Print',
                    className: 'btn-light',
                    title : 'Weekends-<?php echo date('Y-m-d');?>',
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
                    title : 'Weekends-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4]
                    }
                },
                {
                    extend: 'excelHtml5',
                    text: '<i class="far fa-file-excel custool" title="Excel"></i>',
                    titleAttr: 'Excel',
                    className: 'btn-light',
                    title : 'Weekends-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4]
                    }
                },
                {
                    extend: 'csvHtml5',
                    text: '<i class="far fa-file-alt custool" title="CSV"></i>',
                    titleAttr: 'CSV',
                    className: 'btn-light',
                    title : 'Weekends-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4]
                    }
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="far fa-file-pdf custool" title="PDF"></i>',
                    titleAttr: 'PDF',
                    className: 'btn-light',
                    title : 'Weekends-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4]
                    }
                }
                <?php } ?>
            ],
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'ajax': {
               'url': _baseURL + 'human_resources/attendance/add_atten_machine_map_list',
               'data':{'csrf_stream_name':csrf_val}
            },
          'columns': [
             { data: 'sl' },
             { data: 'emp_name'},
             { data: 'branch_name' },
             { data: 'zkt_machine_id' },
             { data: 'CreateDate' },
             { data: 'button' }
          ],
        });

        $('.addAttenMapSetup').on('click', function(){
            $('#addAttenMapForm').removeClass('was-validated');
            $('#id').val('');
            $('#action').val('add');
            $('#zkt_machine_id').val('');
            $('#emp_id').val('').trigger('change');

            $('#saveBtn').text('<?php echo get_phrases(['add']);?>');
            $('#addModalLabel').text('<?php echo get_phrases(['add', 'attendance','map']);?>');
            $('#add-modal').modal('show');

            // $("#emp_id").prop('required',true);
            $("#employee_dropdwn").show();
            $("#employee_name").hide();

        });


        $('#addAttenMapList').on('click', '.actionEdit', function(e){
            e.preventDefault();
            var id = $(this).attr('data-id');

            var submit_url = _baseURL+"human_resources/attendance/atten_machine_map_by_id/"+id;

            // console.log(submit_url);

            $.ajax({
                type: 'POST',
                url: submit_url,
                data: {'csrf_stream_name':csrf_val},
                dataType: 'JSON',
                success: function(response) {

                    // console.log(response);

                    $("#emp_id").prop('required',false);
                    $("#employee_dropdwn").hide();

                    $("#employee_name").show();
                    $("#emp_name").val(response.emp_name);

                    $('#zkt_machine_id').val(response.zkt_machine_id);

                    $('#id').val(response.id);
                    $('#action').val('update');
                    $('#saveBtn').text('<?php echo get_phrases(['update']);?>');
                    $('#addModalLabel').text('<?php echo get_phrases(['update', 'attendance','map']);?>');
                    $('#add-modal').modal('show');
                }
            }); 

        });

        // delete department
        $('#addAttenMapList').on('click', '.actionDelete', function(e){
            e.preventDefault();

            var id = $(this).attr('data-id');
            
            var submit_url = _baseURL+"human_resources/attendance/del_atten_machine_map_by_id/"+id;
            console.log(submit_url);

            var check = confirm('<?php echo get_phrases(["are_you_sure"]).', '. get_phrases(["if_you_delete_then_no_more_attendance_data_will_push_for_this_employee"])?>');  
            if(check == true){  
                $.ajax({
                    type: 'POST',
                    url: submit_url,
                    data: {'csrf_stream_name':csrf_val},
                    dataType: 'JSON',
                    success: function(res) {
                        if(res.success==true){
                            toastr.success(res.message, res.title);
                            // $('#addAttenMapList').DataTable().ajax.reload(null, false);
                            location.reload();
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