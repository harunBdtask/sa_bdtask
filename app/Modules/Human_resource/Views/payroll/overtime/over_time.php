<div class="row">
    <div class="col-sm-12">
        <?php if($permission->module('over_time')->access()){ ?>
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
                        <?php if($permission->method('over_time', 'create')->access()){ ?>
                        <a href="javascript:void(0);" class="btn btn-success btn-sm mr-1 generateOverTime"><i class="fas fa-plus mr-1"></i><?php echo get_phrases(['generate', 'over','time']);?></a>
                        <?php }?>
                       <a href="javascript:void(0);" onclick="goBackOnePage()" class="btn btn-warning btn-sm mr-1 ml-2"><i class="fas fa-angle-double-left mr-1"></i><?php echo get_phrases(['back']);?></a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table id="overTimeList" class="table display table-bordered table-striped table-hover compact" width="100%">
                    <thead>
                        <tr>
                            <th><?php echo get_phrases(['sl']);?></th>
                            <th><?php echo get_phrases(['employee']);?></th>
                            <th><?php echo get_phrases(['time']);?></th>
                            <th><?php echo get_phrases(['over','time','month']);?></th>
                            <th><?php echo get_phrases(['status']);?></th>
                            <th><?php echo get_phrases(['created', 'by']);?></th>
                            <th><?php echo get_phrases(['created', 'date']);?></th>
                            <th><?php echo get_phrases(['updated', 'by']);?></th>
                            <th><?php echo get_phrases(['updated', 'date']);?></th>
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
            <?php echo form_open_multipart('human_resources/payroll/save_over_times', 'class="needs-validation" id="oveTimeForm" novalidate="" data="jobCallBackData"');?>
            <div class="modal-body">
                <input type="hidden" name="id" id="id">
                <input type="hidden" name="action" id="action">

                <div class="row" id="employee_dropdwn">
                    <div class="col-md-12 col-sm-12">
                        <label class="font-weight-600 mb-0"><?php echo get_phrases(['employee']);?> <i class="text-danger">*</i></label>

                         <select name="emp_id" id="emp_id" class="custom-select form-control">
                            <?php if(!empty($employees)){ ?>
                                <option value=""><?php echo get_phrases(['select','employee']);?></option>
                                <?php foreach ($employees as $key => $value) {?>
                                    <option value="<?php echo $value['employee_id'];?>"><?php echo $value['first_name'].' '.$value['last_name'];?></option>
                                <?php }?>
                            <?php }?>
                        </select>

                   </div>
                </div>

                <div class="row"  id="employee_name">
                    <div class="col-md-12 col-sm-12">
                        <label class="font-weight-600 mb-0"><?php echo get_phrases(['employee']);?></label>
                        <input type="text" name="emp_name" class="form-control" id="emp_name" placeholder="<?php echo get_phrases(['employee','name']);?>" readonly>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <label class="font-weight-600 mb-0"><?php echo get_phrases(['time']);?></label>
                        <input type="text" name="time" class="form-control onlyNumber" id="time" placeholder="<?php echo get_phrases(['time']);?>" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <label class="font-weight-600 mb-0"><?php echo get_phrases(['status']);?></label>
                        
                        <select name="status" id="status" class="custom-select form-control">
                            <option value=""><?php echo get_phrases(['select','status']);?></option>
                            <option value="accept"><?php echo get_phrases(['accept']);?></option>
                            <option value="decline"><?php echo get_phrases(['decline']);?></option>
                        </select>

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
        $('#oveTimeForm')[0].reset();       
        $('#oveTimeForm').removeClass('was-validated');    
        // $('#overTimeList').DataTable().ajax.reload(null, false);
        location.reload();
    }

    $(document).ready(function() { 
        "use strict";

        $('#overTimeList').DataTable({ 
             responsive: true,
             lengthChange: false,
             "aaSorting": [[ 1, "asc" ]],
             "columnDefs": [
                { "bSortable": false, "aTargets": [0, 9] },
            ],
            dom: "<'row'<'col-md-6'B><'col-md-6'f>>rt<'bottom'<'row'<'col-md-6'i><'col-md-6'p>>><'clear'>",
            buttons: [{
                    extend: 'copyHtml5',
                    text: '<i class="far fa-copy custool" title="Copy"></i>',
                    titleAttr: 'Copy',
                    className: 'btn-light',
                    title : 'penalty_list<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5,6,7,8]
                    }
                },
                {
                    extend: 'excelHtml5',
                    text: '<i class="far fa-file-excel custool" title="Excel"></i>',
                    titleAttr: 'Excel',
                    className: 'btn-light',
                    title : 'penalty_list<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5,6,7,8]
                    }
                },
                {
                    extend: 'csvHtml5',
                    text: '<i class="far fa-file-alt custool" title="CSV"></i>',
                    titleAttr: 'CSV',
                    className: 'btn-light',
                    title : 'penalty_list<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5,6,7,8]
                    }
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="far fa-file-pdf custool" title="PDF"></i>',
                    titleAttr: 'PDF',
                    className: 'btn-light',
                    title : 'penalty_list<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5,6,7,8]
                    }
                }
            ],
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'ajax': {
               'url': _baseURL + 'human_resources/payroll/over_times_list',
               'data':{'csrf_stream_name':csrf_val}
            },
          'columns': [
             { data: 'sl' ,
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
             },
             { data: 'emp_name'},
             { data: 'time' },
             { data: 'overtime_for' },
             { data: 'status' },
             { data: 'created_by' },
             { data: 'CreateDate' },
             { data: 'updated_by' },
             { data: 'UpdateDate' },
             { data: 'button' }
          ],
        });

        $('.generateOverTime').on('click', function(){
            $('#oveTimeForm').removeClass('was-validated');
            $('#id').val('');
            $('#action').val('add');
            $('#time').val('');
            $('#emp_id').val('').trigger('change');
            $('#status').val('').trigger('change');
            $('#saveBtn').text('<?php echo get_phrases(['save']);?>');
            $('#addModalLabel').text('<?php echo get_phrases(['over','time']);?>');
            $('#active').attr('checked', true);
            $('#add-modal').modal('show');

            $("#emp_id").prop('required',true);
            $("#employee_dropdwn").show();
            $("#employee_name").hide();

        });

         $('#emp_id').on('change', function(e){

             var emp_id = $('#emp_id').val();

             if(!emp_id > 0 || emp_id == ''){
                return false;
             }

             var submit_url = _baseURL+"human_resources/payroll/getOverTimeByEmpId/"+emp_id;

             var respo = getJsonData(submit_url);

            if (respo.success == true) {

                $('#time').val(respo.over_time);
            }else{

                toastr.error(respo.message, respo.title);
            }

            console.log(respo);

        });

        // Function to fetch data from penalty table
        function getJsonData(submit_url){

            var respoData = null;
            

            $.ajax({
                type: 'POST',
                url: submit_url,
                data: {'csrf_stream_name':csrf_val},
                dataType: 'JSON',
                async: false,
                success: function(res) {
                    respoData = res;
                }
            }); 

            return respoData;
        }

        // Formattig time in hours:minutes format
        $("#time").change(function(){

            var time_input = $('#time').val();
            var time_array = time_input.split(':');

            if(time_array[0].length == 1){

                var time = time_input.replace(/:/g,'');
                var hours = time.substring(0, 1);
                var minutes = time.substring(1, 3);

                if(minutes.length > 1){
                    if(parseInt(minutes) > 59){
                        var minutes = 59;
                    }
                }else{
                    var minutes = '00';
                }
                $('#time').val('0'+hours+':'+minutes);

            }else{
            
                var time = time_input.replace(/:/g,'');
                
                if(time.length <= 2){
                    $('#time').val(time+':00');
                }else{
                
                    var hours = time.substring(0, 2);
                    var minutes = time.substring(2, 4);
                    if(parseInt(minutes) > 59){
                        var minutes = 59;
                    }
                    $('#time').val(hours+':'+minutes);
                }
            }
            
        });


        $('#overTimeList').on('click', '.actionEdit', function(e){
            e.preventDefault();
            var id = $(this).attr('data-id');

            var submit_url = _baseURL+"human_resources/payroll/over_times_by_id/"+id;

            $.ajax({
                type: 'POST',
                url: submit_url,
                data: {'csrf_stream_name':csrf_val},
                dataType: 'JSON',
                success: function(response){

                    // console.log(response);

                    $("#emp_id").prop('required',false);
                    $("#employee_dropdwn").hide();

                    $("#employee_name").show();
                    $("#emp_name").val(response.emp_name);

                    $('#id').val(response.over_time_id);
                    $('#emp_id').val(response.emp_id).trigger('change');
                    $('#status').val(response.status).trigger('change');
                    $('#time').val(response.time);

                    $('#action').val('update');
                    $('#saveBtn').text('<?php echo get_phrases(['update']);?>');
                    $('#addModalLabel').text('<?php echo get_phrases(['update', 'over', 'time']);?>');
                    $('#add-modal').modal('show');
                }
            }); 

        });

        // delete department
        $('#overTimeList').on('click', '.actionDelete', function(e){
            e.preventDefault();

            var id = $(this).attr('data-id');
            
            var submit_url = _baseURL+"human_resources/payroll/delete_over_time_by_id/"+id;
            console.log(submit_url);

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
                            // $('#overTimeList').DataTable().ajax.reload(null, false);
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