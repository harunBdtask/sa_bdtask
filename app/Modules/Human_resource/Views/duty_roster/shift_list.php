<link href="<?php echo base_url()?>/assets/plugins/datetimepicker/jquery.datetimepicker.css" rel="stylesheet">

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
                        <?php if( $hasCreateAccess ){ ?>
                            <button type="button" class="btn btn-success btn-sm mr-1 addShowModal"><i class="fas fa-plus mr-1"></i><?php echo get_phrases(['add', 'shift']);?></button>
                        <?php } ?>
                       <a href="javascript:void(0);" onclick="goBackOnePage()" class="btn btn-warning btn-sm mr-1 ml-2"><i class="fas fa-angle-double-left mr-1"></i><?php echo get_phrases(['back']);?></a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table id="shiftList" class="table display table-bordered table-striped table-hover compact" width="100%">
                    <thead>
                        <tr>
                            <th><?php echo get_phrases(['sl']);?></th>
                            <th><?php echo get_phrases(['shift', 'name']);?></th>
                            <th><?php echo get_phrases(['department']);?></th>
                            <th><?php echo get_phrases(['shift', 'start']);?></th>
                            <th><?php echo get_phrases(['shift', 'end']);?></th>
                            <th><?php echo get_phrases(['shift', 'hour']);?></th>
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

<div class="modal fade bd-example-modal-lg" id="shift-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel3" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="shiftModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
           <?php echo form_open_multipart('human_resources/duty_roster/create_shift', 'class="ajaxForm needs-validation" id="ajaxForm" novalidate="" data="showCallBackData"');?>
            <div class="modal-body">
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                <input type="hidden" name="id" id="id" />
                <input type="hidden" name="action" id="action" value="add" />

                <div class="row">
                    <div class="col-md-6 col-sm-12">
                         <div class="form-group">
                            <label class="font-weight-600"><?php echo get_phrases(['shift','name']);?> <i class="text-danger">*</i></label>
                            <input type="text" name="shift_name" placeholder="<?php echo get_phrases(['enter', 'shift', 'name']);?>" class="form-control" id="shift_name" required>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                         <div class="form-group">
                            <label class="font-weight-600"><?php echo get_phrases(['department']);?> <i class="text-danger">*</i></label>
                            <?php echo  form_dropdown('department',$departments_list,'', 'class="form-control select2" id="department" required') ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 col-sm-12">
                         <div class="form-group">
                            <label class="font-weight-600"><?php echo get_phrases(['shift', 'start']);?> <i class="text-danger">*</i></label>
                            <input type="time" name="shift_start" placeholder="<?php echo get_phrases(['shift', 'start']);?>" class="form-control" onchange="shifttimechk(),check_inshift(),checkduplicateshift()" id="shift_start" autocomplete="off" required>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                         <div class="form-group">
                            <label class="font-weight-600"><?php echo get_phrases(['shift', 'end']);?> <i class="text-danger">*</i></label>
                            <input type="time" name="shift_end" placeholder="<?php echo get_phrases(['shift', 'end']);?>" class="form-control" onchange="shifttimechk(),check_inshift(),checkduplicateshift()" id="shift_end" autocomplete="off" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class=" col-sm-12">
                         <div class="form-group">
                            <label class="font-weight-600"><?php echo get_phrases(['shift', 'duration']);?> <i class="text-danger">*</i></label>
                            <input type="text" name="shift_duration" placeholder="<?php echo get_phrases(['shift', 'duration']);?>" class="form-control" id="shifttimetotal" readonly required>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo get_phrases(['close']);?></button>
                <button type="submit" class="btn btn-success modal_action actionBtn"></button>
            </div>
            <?php echo form_close();?>
        </div>
    </div>
</div>

<!-- Modal for editing -->

<div class="modal fade bd-example-modal-lg" id="shift-modal-edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel3" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="shiftModalLabelEdit"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
           <?php echo form_open_multipart('human_resources/duty_roster/create_shift', 'class="ajaxForm needs-validation" id="ajaxForm" novalidate="" data="showCallBackData"');?>
            <div class="modal-body">
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                <input type="hidden" name="id" id="id-edit" />
                <input type="hidden" name="action" id="action-edit" value="add" />

                <div class="row">
                    <div class="col-md-6 col-sm-12">
                         <div class="form-group">
                            <label class="font-weight-600"><?php echo get_phrases(['shift','name']);?> <i class="text-danger">*</i></label>
                            <input type="text" name="shift_name" placeholder="<?php echo get_phrases(['enter', 'shift', 'name']);?>" class="form-control" id="shift_name_edit" required>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                         <div class="form-group">
                            <label class="font-weight-600"><?php echo get_phrases(['department']);?> <i class="text-danger">*</i></label>
                            <?php echo  form_dropdown('department',$departments_list,'', 'class="form-control select2" id="department_edit" disabled') ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 col-sm-12">
                         <div class="form-group">
                            <label class="font-weight-600"><?php echo get_phrases(['shift', 'start']);?> <i class="text-danger">*</i></label>
                            <input type="time" name="shift_start" placeholder="<?php echo get_phrases(['shift', 'start']);?>" class="form-control" id="shift_start_edit" autocomplete="off" onchange="shifttimechk_edit()" required>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                         <div class="form-group">
                            <label class="font-weight-600"><?php echo get_phrases(['shift', 'end']);?> <i class="text-danger">*</i></label>
                            <input type="time" name="shift_end" placeholder="<?php echo get_phrases(['shift', 'end']);?>" class="form-control" id="shift_end_edit" autocomplete="off" onchange="shifttimechk_edit()" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class=" col-sm-12">
                         <div class="form-group">
                            <label class="font-weight-600"><?php echo get_phrases(['shift', 'duration']);?> <i class="text-danger">*</i></label>
                            <input type="text" name="shift_duration" placeholder="<?php echo get_phrases(['shift', 'duration']);?>" class="form-control" id="shifttimetotal_edit" readonly required>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo get_phrases(['close']);?></button>
                <button type="submit" class="btn btn-success modal_action_edit actionBtn"></button>
            </div>
            <?php echo form_close();?>
        </div>
    </div>
</div>

<!-- Ends of modal for editing -->

<script src="<?php echo base_url()?>/assets/plugins/datetimepicker/jquery.datetimepicker.full.js" type="text/javascript"></script>

<script type="text/javascript">

    $('.timepicker').datetimepicker({
        datepicker:false,
        format:'H:i',
        step:5
    });
    
    function parent_reset(){
        $('#parent_id').val('').trigger('change');
    }

    var showCallBackData = function () {
        $('#id').val('');        
        $('#action').val('add');        
        $('.ajaxForm')[0].reset();
        $('#shift-modal').modal('hide');
        $('#shift-modal-edit').modal('hide');
        $('#shiftList').DataTable().ajax.reload(null, false);
    }

    $(document).ready(function() { 
       "use strict";

        $('option:first-child').val('').trigger('change');

        $('#shiftList').DataTable({ 
             responsive: true,
             lengthChange: true,
             "aaSorting": [[ 1, "desc" ]],
             "columnDefs": [
                { "bSortable": false, "aTargets": [0,6] },
            ],
            dom: "<'row'<?php if($hasExportAccess || $hasPrintAccess){ echo "<'col-md-4'l><'col-md-4'B><'col-md-4'f>"; } else { echo "<'col-md-6'l><'col-md-6'f>"; } ?>>rt<'bottom'<'row'<'col-md-6'i><'col-md-6'p>>><'clear'>",
            buttons: [
                <?php if($hasPrintAccess){ ?>
                {
                    extend: 'print',
                    text: '<i class="fa fa-print custool" title="Print"></i>',
                    titleAttr: 'Print',
                    className: 'btn-light',
                    title : 'Department_List-<?php echo date('Y-m-d');?>',
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
                    title : 'Department_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5]
                    }
                },
                {
                    extend: 'excelHtml5',
                    text: '<i class="far fa-file-excel custool" title="Excel"></i>',
                    titleAttr: 'Excel',
                    className: 'btn-light',
                    title : 'Department_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5]
                    }
                },
                {
                    extend: 'csvHtml5',
                    text: '<i class="far fa-file-alt custool" title="CSV"></i>',
                    titleAttr: 'CSV',
                    className: 'btn-light',
                    title : 'Department_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5]
                    }
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="far fa-file-pdf custool" title="PDF"></i>',
                    titleAttr: 'PDF',
                    className: 'btn-light',
                    title : 'Department_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5]
                    }
                }
                <?php } ?>
            ],
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'ajax': {
               'url': _baseURL + 'human_resources/duty_roster/shiftDataList',
               'data':{'csrf_stream_name':csrf_val}
            },
          'columns': [
            { data: 'sl' ,
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
             },
            { data: 'shift_name'},
            { data: 'department_name'},
            { data: 'shift_start'},
            { data: 'shift_end'},
            { data: 'shift_duration'},
            { data: 'button'},
          ],
        });


        $('.addShowModal').on('click', function(){
            $('.ajaxForm').removeClass('was-validated');
            $('#id').val('');
            $('#action').val('add');
            $('#shift_name').val('');
            $('#department').val("").trigger("change");
            $('#shift_start').val('');
            $('#shift_end').val('');
            $('#shifttimetotal').val('');
            $('#shiftModalLabel').text('<?php echo get_phrases(['add', 'shift']);?>');
            $('.modal_action').text('<?php echo get_phrases(['add']);?>');
            $('.modal_action').prop('disabled', false);
            $('#shift-modal').modal('show');
        });

        //Update shift

        $('#shiftList').on('click', '.actionEdit', function(e){
            e.preventDefault();
            $('.ajaxForm').removeClass('was-validated');
            // $('#thumbpic').next('span').text('');
            
            var id = $(this).attr('data-id');

            // console.log(id);

            var submit_url = _baseURL+'human_resources/duty_roster/getShiftById/'+id;

            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'JSON',
                data: {'csrf_stream_name':csrf_val},
                success: function(data) {

                    // console.log(data);

                    $('#shift-modal-edit').modal('show');
                    $('#id-edit').val(data.shiftid);
                    $('#action-edit').val('update');
                    $('#shiftModalLabelEdit').text('<?php echo get_phrases(['update', 'shift']);?>');
                    $('.modal_action_edit').text('<?php echo get_phrases(['update']);?>');
                    $('.modal_action_edit').prop('disabled', false);

                    $('#shift_name_edit').val(data.shift_name);
                    $('#department_edit').val(data.department_id).trigger("change");
                    $('#shift_start_edit').val(data.shift_start);
                    $('#shift_end_edit').val(data.shift_end);
                    $('#shifttimetotal_edit').val(data.shift_duration);

                },error: function() {

                }
            });   

        });


        // Delete department

        $('#shiftList').on('click', '.actionDelete', function(e){
            e.preventDefault();

            var id = $(this).attr('data-id');
            // console.log(id);
            
            var submit_url = _baseURL+"human_resources/duty_roster/deleteShiftById/"+id;
            var check = confirm('<?php echo get_phrases(["are_you_sure"])?>');  
            if(check == true){  
                $.ajax({
                    type: 'POST',
                    url: submit_url,
                    data: {'csrf_stream_name':csrf_val},
                    dataType: 'JSON',
                    success: function(res) {

                        // console.log(res);

                        if(res.success==true){
                            toastr.success(res.message, 'Shift Record');
                            $('#shiftList').DataTable().ajax.reload(null, false);
                        }else{
                            toastr.error(res.message, 'Shift Record');
                        }
                        
                    },error: function() {

                    }
                });
            }   
        });

        $('#department').on('change', function(e){

            // console.log($(this).val());
            var department = $(this).val();

            if(department != ""){

                $('#shift_start').val("");
                $('#shift_end').val("");
            }

        });

    });


    function shifttimechk() {
        "use strict";
        
        var leave_from1  = $('#shift_start').val();
        var leave_to1  = $('#shift_end').val();

        if (leave_from1 < leave_to1) {
            
            var hours = ( new Date("1970-1-1 " + leave_to1) - new Date("1970-1-1 " + leave_from1) ) / 1000 / 60 / 60;  
            var minutes =Math.abs(( new Date("1970-1-1 " + leave_to1) - new Date("1970-1-1 " + leave_from1) ) / 1000 / 60 % 60);  
        }
        else if (leave_from1 > leave_to1) {
            
            var hours = ( new Date("1970-1-2 " + leave_to1) - new Date("1970-1-1 " + leave_from1) ) / 1000 / 60 / 60;  
            var minutes =Math.abs(( new Date("1970-1-2 " + leave_to1) - new Date("1970-1-1 " + leave_from1) ) / 1000 / 60 % 60);  
        }
        
        if (minutes == 0) {
          minutes = '00'
        }
        var onlyhours = Math.abs(parseInt(hours));
        if (onlyhours == 0) {
          onlyhours = '00'
          
        }

        $('#shifttimetotal').val(onlyhours+':'+minutes);
    }

    function shifttimechk_edit() {
        "use strict";
        
        var leave_from1  = $('#shift_start_edit').val();
        var leave_to1  = $('#shift_end_edit').val();

        if (leave_from1 < leave_to1) {
            
            var hours = ( new Date("1970-1-1 " + leave_to1) - new Date("1970-1-1 " + leave_from1) ) / 1000 / 60 / 60;  
            var minutes =Math.abs(( new Date("1970-1-1 " + leave_to1) - new Date("1970-1-1 " + leave_from1) ) / 1000 / 60 % 60);  
        }
        else if (leave_from1 > leave_to1) {
            
            var hours = ( new Date("1970-1-2 " + leave_to1) - new Date("1970-1-1 " + leave_from1) ) / 1000 / 60 / 60;  
            var minutes =Math.abs(( new Date("1970-1-2 " + leave_to1) - new Date("1970-1-1 " + leave_from1) ) / 1000 / 60 % 60);  
        }
        
        if (minutes == 0) {
          minutes = '00'
        }
        var onlyhours = Math.abs(parseInt(hours));
        if (onlyhours == 0) {
          onlyhours = '00'
          
        }

        $('#shifttimetotal_edit').val(onlyhours+':'+minutes);
    }

    function check_inshift(){
        "use strict";

        var department  = $('#department').val();
        var shift_start  = $('#shift_start').val();
        var shift_end  = $('#shift_end').val();
    
        $.ajax({
            type: "POST",
            url: _baseURL+"human_resources/duty_roster/check_inshift",
            data:{
               csrf_stream_name:csrf_val,
               department:department,
               shift_start:shift_start,
               shift_end:shift_end
           },
           success: function(res) {

            // console.log(res);

                if(res == 1){

                    var msg = '<?php echo get_phrases(['duplicate','shift','time'])?>';

                    $('#shift_start').val("");
                    $('#shift_end').val("");
                    $('#shifttimetotal').val("");
                    toastr.error(msg, 'Shift Record');
                }
           } 
       });
        
    }

    function checkduplicateshift(){
        "use strict";

        var department  = $('#department').val();
        var msg = "First need to select department";
        // console.log(department);

        if(department == ""){

            $('#shift_start').val("");
            $('#shift_end').val("");
            toastr.error(msg, 'Shift Record');

        }else{

            var shift_start = $('#shift_start').val();
            var shift_end   = $('#shift_end').val();

            $.ajax({
                type: "POST",
                url: _baseURL+"human_resources/duty_roster/chkduplicateshift",
                data:{
                   csrf_stream_name:csrf_val,
                   department:department,
                   shift_start:shift_start,
                   shift_end:shift_end
                },
                success: function(res) {

                    if(res == 1){

                        var msg2 = '<?php echo get_phrases(['duplicate','shift','time'])?>';

                        $('#shift_start').val("");
                        $('#shift_end').val("");
                        $('#shifttimetotal').val("");
                        toastr.error(msg2, 'Shift Record');
                    }
                } 
           });
        }

    }

</script>