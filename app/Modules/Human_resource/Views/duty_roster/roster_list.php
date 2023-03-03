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
                        <?php if( $hasCreateAccess ){ ?>
                            <button type="button" class="btn btn-success btn-sm mr-1 addShowModal"><i class="fas fa-plus mr-1"></i><?php echo get_phrases(['add', 'roster']);?></button>

                        <?php } ?>
                       <a href="javascript:void(0);" onclick="goBackOnePage()" class="btn btn-warning btn-sm mr-1 ml-2"><i class="fas fa-angle-double-left mr-1"></i><?php echo get_phrases(['back']);?></a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table id="rostertList" class="table display table-bordered table-striped table-hover compact" width="100%">
                    <thead>
                        <tr>
                            <th><?php echo get_phrases(['sl']);?></th>
                            <th><?php echo get_phrases(['shift', 'name']);?></th>
                            <th><?php echo get_phrases(['department', 'name']);?></th>
                            <th><?php echo get_phrases(['roster','start','date']);?></th>
                            <th><?php echo get_phrases(['roster','end','date']);?></th>
                            <th><?php echo get_phrases(['roster','days']);?></th>
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
<div class="modal fade bd-example-modal-lg" id="roster-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel3" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="shiftModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
           <?php echo form_open_multipart('human_resources/duty_roster/create_roster', 'class="ajaxForm needs-validation" id="ajaxForm" novalidate="" data="showCallBackData"');?>
            <div class="modal-body">
                <input type="hidden" name="id" id="id" />
                <input type="hidden" name="action" id="action" value="add" />

                <div class="row">
                    <div class="col-sm-12">
                         <div class="form-group">
                            <label class="font-weight-600"><?php echo get_phrases(['department']);?> <i class="text-danger">*</i></label>
                            <?php echo form_dropdown('department_id', $departments_list, '', 'class="form-control custom-select" id="department_id" required');?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                         <div class="form-group">
                            <label class="font-weight-600"><?php echo get_phrases(['shift','name']);?> <i class="text-danger">*</i></label>
                           <select name="shift_id[]" class="form-control custom-select" id="shift_id" multiple required>

                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 col-sm-12">
                         <div class="form-group">
                            <label class="font-weight-600"><?php echo get_phrases(['roster', 'start']);?> <i class="text-danger">*</i></label>
                            <input type="text" name="roster_start" placeholder="<?php echo get_phrases(['roster', 'start']);?>" class="form-control onlydate-roster" onchange="rosterdatechk(),rosterdatechk2(),rostdaychk()" id="roster_start" autocomplete="off" required>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                         <div class="form-group">
                            <label class="font-weight-600"><?php echo get_phrases(['roster', 'end']);?> <i class="text-danger">*</i></label>
                            <input type="text" name="roster_end" placeholder="<?php echo get_phrases(['roster', 'end']);?>" class="form-control onlydate-roster" onchange="rosterdatechk(),rosterdatechk2(),rostdaychk()" id="roster_end" autocomplete="off" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class=" col-sm-12">
                         <div class="form-group">
                            <label class="font-weight-600"><?php echo get_phrases(['roster', 'duration']);?> <i class="text-danger">*</i></label>
                            <input type="text" name="roster_duration" placeholder="<?php echo get_phrases(['roster', 'duration']);?>" class="form-control" id="roster_duration" readonly required>
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


<!-- Large modal button -->
<div class="modal fade bd-example-modal-lg" id="roster-modal-view" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel3" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="shiftModalLabel-view"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
           <?php echo form_open_multipart('human_resources/duty_roster/create_roster', 'class="ajaxForm needs-validation" id="ajaxForm" novalidate="" data="showCallBackData"');?>
            <div class="modal-body">
                <!-- <input type="hidden" name="id" id="id" />
                <input type="hidden" name="action" id="action" value="add" /> -->

                <div class="row">
                    <div class="col-sm-12">
                         <div class="form-group">
                            <label class="font-weight-600"><?php echo get_phrases(['department']);?> <i class="text-danger">*</i></label>
                            <?php echo form_dropdown('department_id', $departments_list, '', 'class="form-control custom-select" id="department_id_view" disabled');?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                         <div class="form-group">
                            <label class="font-weight-600"><?php echo get_phrases(['shift','name']);?> <i class="text-danger">*</i></label>
                           <input type="text" name="" id="shift_id_view"class="form-control" readonly>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 col-sm-12">
                         <div class="form-group">
                            <label class="font-weight-600"><?php echo get_phrases(['roster', 'start']);?> <i class="text-danger">*</i></label>
                            <input type="text" name="roster_start" placeholder="<?php echo get_phrases(['roster', 'start']);?>" class="form-control onlydate-roster" onchange="rosterdatechk(),rosterdatechk2(),rostdaychk()" id="roster_start_view" autocomplete="off" readonly>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                         <div class="form-group">
                            <label class="font-weight-600"><?php echo get_phrases(['roster', 'end']);?> <i class="text-danger">*</i></label>
                            <input type="text" name="roster_end" placeholder="<?php echo get_phrases(['roster', 'end']);?>" class="form-control onlydate-roster" onchange="rosterdatechk(),rosterdatechk2(),rostdaychk()" id="roster_end_view" autocomplete="off" readonly>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class=" col-sm-12">
                         <div class="form-group">
                            <label class="font-weight-600"><?php echo get_phrases(['roster', 'duration']);?> <i class="text-danger">*</i></label>
                            <input type="text" name="roster_duration" placeholder="<?php echo get_phrases(['roster', 'duration']);?>" class="form-control" id="roster_duration_view" readonly>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo get_phrases(['close']);?></button>
                <!-- <button type="submit" class="btn btn-success modal_action actionBtn"></button> -->

            </div>
            <?php echo form_close();?>
        </div>
    </div>
</div>

<script src="<?php echo base_url()?>/assets/plugins/jquery-ui-1.12.1/jquery-ui.min.js" type="text/javascript"></script>



<script type="text/javascript">

    var showCallBackData = function () {

        $('#id').val('');        
        $('#action').val('add');        
        $('.ajaxForm')[0].reset();
        $('#roster-modal').modal('hide');
        // $('#rostertList').DataTable().ajax.reload(null, false);

        location.reload();
    }

    $(document).ready(function() { 
       "use strict";

        $('#rostertList').DataTable({ 
             responsive: true,
             lengthChange: true,
             "aaSorting": [[ 1, "desc" ]],
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
                        columns: [ 0, 1, 2, 3]
                    }
                },
                {
                    extend: 'excelHtml5',
                    text: '<i class="far fa-file-excel custool" title="Excel"></i>',
                    titleAttr: 'Excel',
                    className: 'btn-light',
                    title : 'Department_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3]
                    }
                },
                {
                    extend: 'csvHtml5',
                    text: '<i class="far fa-file-alt custool" title="CSV"></i>',
                    titleAttr: 'CSV',
                    className: 'btn-light',
                    title : 'Department_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3]
                    }
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="far fa-file-pdf custool" title="PDF"></i>',
                    titleAttr: 'PDF',
                    className: 'btn-light',
                    title : 'Department_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3]
                    }
                }
                <?php } ?>
            ],
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'ajax': {
               'url': _baseURL + 'human_resources/duty_roster/rosterDataList',
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
            { data: 'roster_start'},
            { data: 'roster_end'},
            { data: 'roster_dsys'},
            { data: 'button'},
          ],
        });


        $('.addShowModal').on('click', function(){
            $('.ajaxForm').removeClass('was-validated');
            $('#id').val('');
            $('#action').val('add');

            $('#department_id').val('').trigger('change');
            $('#shift_id').val('').trigger('change');

            $('#roster_start').val('');
            $('#roster_end').val('');
            $('#roster_duration').val('');

            $('#shiftModalLabel').text('<?php echo get_phrases(['add', 'roster']);?>');
            $('.modal_action').text('<?php echo get_phrases(['add']);?>');
            $('.modal_action').prop('disabled', false);
            $('#roster-modal').modal('show');

            // $("#shift_id option:selected").prop("selected", false);

            // //shift list
            // $.ajax({
            //     type:'GET',
            //     url: _baseURL+'human_resources/duty_roster/shift_list',
            //     dataType: 'json',
            //     data:{'csrf_stream_name':csrf_val},
            // }).done(function(data) {

            //     $("#shift_id").select2({
            //         placeholder: '<?php echo get_phrases(['select', 'shift']);?>',
            //         data: data
            //     });

            // });
        });

        //Update Roster

        $('#rostertList').on('click', '.actionEdit', function(e){

            e.preventDefault();
            
            $('.ajaxForm').removeClass('was-validated');
            $('#thumbpic').next('span').text('');
            
            var id = $(this).attr('data-id');

            console.log(id);

            var submit_url = _baseURL+'human_resources/duty_roster/getRosterById/'+id;

            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'JSON',
                data: {'csrf_stream_name':csrf_val},
                success: function(data) {

                    console.log(data);

                    $('#roster-modal-view').modal('show');
                    // $('#id').val(duty_roster.shiftid);
                    $('#department_id_view').val(data.department_id).trigger('change');
                    $('#shift_id_view').val(data.shift_name);

                    // // $('#action').val('update');
                    $('#shiftModalLabel-view').text('<?php echo get_phrases(['view', 'roster']);?>');
                    // $('.modal_action').text('<?php //echo get_phrases(['view']);?>');
                    // // $('.modal_action').prop('disabled', false);

                    $('#roster_start_view').val(data.roster_start);
                    $('#roster_end_view').val(data.roster_end);
                    $('#roster_duration_view').val(data.roster_dsys);

                },error: function() {

                }
            });   

        });


        // Delete Roster

        $('#rostertList').on('click', '.actionDelete', function(e){
            e.preventDefault();

            var id = $(this).attr('data-id');
            var start_date = $(this).attr('data-startdate');
            var end_date = $(this).attr('data-enddate');
            // console.log('start_date: '+start_date+' , end_date'+end_date);
            
            var submit_url = _baseURL+"human_resources/duty_roster/deleteRosterByDate";
            var check = confirm('<?php echo get_phrases(["are_you_sure"])?>');  
            if(check == true){  
                $.ajax({
                    type: 'POST',
                    url: submit_url,
                    data: {
                        'csrf_stream_name':csrf_val,
                        'id':id,
                        'start_date':start_date,
                        'end_date':end_date,
                    },
                    dataType: 'JSON',
                    success: function(res) {

                        // console.log(res);

                        if(res.success==true){
                            toastr.success(res.message, 'Shift Record');
                            $('#rostertList').DataTable().ajax.reload(null, false);
                        }else{
                            toastr.error(res.message, 'Shift Record');
                        }
                        
                    },error: function() {

                    }
                });
            }   
        });


        // append shift list on select department

        $('#department_id').on('change', function(e){
            e.preventDefault();

            var department_id = $(this).val();

            $("#shift_id option").remove();

            $('#roster_start').val('');
            $('#roster_end').val('');
            $('#roster_duration').val('');
            
            var submit_url = _baseURL+"human_resources/duty_roster/getShiftsByDepartmentId/"+department_id;

            $.ajax({
                type: 'GET',
                url: submit_url,
                data: {},
                success: function(res) {

                    // console.log(res);

                    $("#shift_id").html(res);
                    
                },error: function() {

                }
            });

        });

        // $('.onlydate-roster').daterangepicker({
        //     singleDatePicker: true,
        //     locale : {
        //         format : 'YYYY-MM-DD'
        //     }
        // });

        // //Single Date Picker
        // $('.onlydate-roster').daterangepicker({
        //     singleDatePicker: true,
        //     minDate: moment(),
        //     useCurrent: false,
        //     locale : {
        //         format : 'YYYY-MM-DD'
        //     }
        // });

        // $('.onlydate-roster').val('');

        // function recallDatePicker(){

        //      $('.onlydate-roster').daterangepicker({
        //         singleDatePicker: true,
        //         minDate: moment(),
        //         useCurrent: false,
        //         locale : {
        //             format : 'YYYY-MM-DD'
        //         }
        //     });
        // }

        $('.onlydate-roster').datepicker({
            minDate: 0,
            dateFormat: 'yy-mm-dd'
        });

        function recallDatePicker(){

            $('.onlydate-roster').datepicker({
                minDate: 0,
                dateFormat: 'yy-mm-dd'
            });
        }

    });


    function rosterdatechk(){
        "use strict";

        var department_id = $('#department_id').val();
        // var shift_id   = $('#shift_id').val();
        
        var start_date = $('#roster_start').val();
        var end_date   = $('#roster_end').val();

        $.ajax({
            type: "POST",
            
            url: _baseURL+"human_resources/duty_roster/checkshift_data1",
            data:{
            csrf_stream_name:csrf_val,
            start_date:start_date,
            end_date:end_date,
            department_id:department_id,
            // shift_id:shift_id,
        },
            success: function(data) {
                if (data == 1) {
                    
                    $('#roster_start').val('');
                    $('#roster_end').val('');
                    $('#roster_duration').val('');
                    // alert("This schedule is Already Taken");
                    var msg_dulicate = '<?php echo get_phrases(['this','schedule','is','already','taken']);?>';
                    toastr.error(msg_dulicate, 'Roster Record');
                    
                }else{
                    var roster_days = $('#roster_duration').val();
                    if (roster_days < 0 ) {
                        $('#roster_start').val('');
                        $('#roster_end').val('');
                        $('#roster_duration').val('');
                        // alert("Please Reset Your Roster");
                        var msg_reset = '<?php echo get_phrases(['please','reset','your','roster']);?>';
                        toastr.error(msg_reset, 'Roster Record');
                    }
                }
            } 
        });
    }

    function rosterdatechk2(){
        "use strict";

        var department_id = $('#department_id').val();
        // var shift_id      = $('#shift_id').val();
        
        var start_date = $('#roster_start').val();
        var end_date   = $('#roster_end').val();

        $.ajax({
            type: "POST",
            
            url: _baseURL+"human_resources/duty_roster/checkshift_data2",
            data:{
            csrf_stream_name:csrf_val,
            start_date:start_date,
            end_date:end_date,
            department_id:department_id,
            // shift_id:shift_id,
        },
            success: function(data) {

                if (data == 1) {
                    
                    $('#roster_start').val('');
                    $('#roster_end').val('');
                    $('#roster_duration').val('');
                    var msg_dulicate = '<?php echo get_phrases(['this','schedule','is','already','taken']);?>';
                    toastr.error(msg_dulicate, 'Roster Record');
                    
                }else{
                    var roster_days = $('#roster_duration').val();
                    if (roster_days < 0 ) {
                        $('#roster_start').val('');
                        $('#roster_end').val('');
                        $('#roster_duration').val('');
                        var msg_reset = '<?php echo get_phrases(['please','reset','your','roster']);?>';
                        toastr.error(msg_reset, 'Roster Record');
                    }
                }
            } 
        });
    }

    function rostdaychk() {
        "use strict";

        var department_id = $('#department_id').val();
        var shift_id   = $('#shift_id').val();

        // console.log(shift_id);

        if(department_id == "" || shift_id == ""){

            $('#roster_start').val("");
            $('#roster_end').val("");

            var msg_validity = '<?php echo get_notify('department_and_shift_need_to_select_first');?>';
            toastr.error(msg_validity, 'Roster Record');

        }else{

            var roster_days = '';
            var roster_start_date  = '';
            var roster_end_date  = '';
            roster_start_date  = new Date($('#roster_start').val());
            roster_end_date  = new Date($('#roster_end').val());
            var Difference_In_Time = roster_end_date.getTime() - roster_start_date.getTime();
            var Difference_In_Days = Difference_In_Time / (1000 * 3600 * 24);
            if(isNaN(Difference_In_Days)) {
                roster_days;
                }else{
                    if (Difference_In_Days < 0) {
                        
                        roster_days = Difference_In_Days;
                    }else{
                        roster_days = Difference_In_Days+1;

                    }
                }
            $('#roster_duration').val(roster_days);

        }
    }

</script>