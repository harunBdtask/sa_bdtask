<div class="row">
    <div class="col-sm-12">
        <?php if($permission->module('week_ends')->access()){ ?>
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
                        <?php if($permission->method('week_ends', 'create')->access()){ ?>
                        <!-- <a href="javascript:void(0);" class="btn btn-success btn-sm mr-1 addWeekends"><i class="fas fa-plus mr-1"></i><?php //echo get_phrases(['add', 'weekends']);?></a> -->
                        <?php }?>
                       <a href="javascript:void(0);" onclick="goBackOnePage()" class="btn btn-warning btn-sm mr-1 ml-2"><i class="fas fa-angle-double-left mr-1"></i><?php echo get_phrases(['back']);?></a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table id="weekendsList" class="table display table-bordered table-striped table-hover compact" width="100%">
                    <thead>
                        <tr>
                            <th><?php echo get_phrases(['sl']);?></th>
                            <th><?php echo get_phrases(['weekends']);?></th>
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
            <?php echo form_open_multipart('human_resources/holidays/add_weekEndDays', 'class="needs-validation" id="weekendsForm" novalidate="" data="jobCallBackData"');?>

            <div class="modal-body">
                <input type="hidden" name="id" id="id">
                <input type="hidden" name="action" id="action">

                <div class="row">
                  <div class="col-md-12 col-sm-12">
                        <div class="form-group">
                            <label class="font-weight-600"><?php echo get_phrases(['weekends']);?> <i class="text-danger">*</i></label>
                            <div class="form-check">
                                <div class="form-check form-check-inline">
                                   <input class="form-check-input" type="checkbox" name="week_ends[]" id="week_ends1" value="Friday">
                                   <label class="form-check-label" for="week_ends1"><?php echo get_phrases(['friday']);?></label>
                                </div>
                                <div class="form-check form-check-inline">
                                   <input class="form-check-input" type="checkbox" name="week_ends[]" id="week_ends2" value="Saturday">
                                   <label class="form-check-label" for="week_ends2"><?php echo get_phrases(['saturday']);?></label>
                                </div>

                                <div class="form-check form-check-inline">
                                   <input class="form-check-input" type="checkbox" name="week_ends[]" id="week_ends3" value="Sunday">
                                   <label class="form-check-label" for="week_ends3"><?php echo get_phrases(['sunday']);?></label>
                                </div>

                                <div class="form-check form-check-inline">
                                   <input class="form-check-input" type="checkbox" name="week_ends[]" id="week_ends4" value="Monday">
                                   <label class="form-check-label" for="week_ends4"><?php echo get_phrases(['monday']);?></label>
                                </div>

                                <div class="form-check form-check-inline">
                                   <input class="form-check-input" type="checkbox" name="week_ends[]" id="week_ends5" value="Tuesday">
                                   <label class="form-check-label" for="week_ends5"><?php echo get_phrases(['tuesday']);?></label>
                                </div>

                                <div class="form-check form-check-inline">
                                   <input class="form-check-input" type="checkbox" name="week_ends[]" id="week_ends6" value="Wednesday">
                                   <label class="form-check-label" for="week_ends6"><?php echo get_phrases(['wednesday']);?></label>
                                </div>

                                <div class="form-check form-check-inline">
                                   <input class="form-check-input" type="checkbox" name="week_ends[]" id="week_ends7" value="Thursday">
                                   <label class="form-check-label" for="week_ends7"><?php echo get_phrases(['thursday']);?></label>
                                </div>
                            </div>
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
        $('#weekendsForm')[0].reset();       
        $('#weekendsForm').removeClass('was-validated');    
        // $('#weekendsList').DataTable().ajax.reload(null, false);

        location.reload();
    }

    $(document).ready(function() { 
        "use strict";

        $('#weekendsList').DataTable({ 
             responsive: true,
             lengthChange: false,
             "aaSorting": [[ 1, "asc" ]],
             "columnDefs": [
                { "bSortable": false, "aTargets": [0, 3] },
            ],
            dom: "<'row'<'col-md-6'B><'col-md-6'f>>rt<'bottom'<'row'<'col-md-6'i><'col-md-6'p>>><'clear'>",
            buttons: [
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
                {
                    extend: 'copyHtml5',
                    text: '<i class="far fa-copy custool" title="Copy"></i>',
                    titleAttr: 'Copy',
                    className: 'btn-light',
                    title : 'Weekends-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3]
                    }
                },
                {
                    extend: 'excelHtml5',
                    text: '<i class="far fa-file-excel custool" title="Excel"></i>',
                    titleAttr: 'Excel',
                    className: 'btn-light',
                    title : 'Weekends-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3]
                    }
                },
                {
                    extend: 'csvHtml5',
                    text: '<i class="far fa-file-alt custool" title="CSV"></i>',
                    titleAttr: 'CSV',
                    className: 'btn-light',
                    title : 'Weekends-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3]
                    }
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="far fa-file-pdf custool" title="PDF"></i>',
                    titleAttr: 'PDF',
                    className: 'btn-light',
                    title : 'Weekends-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3]
                    }
                }
            ],
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'ajax': {
               'url': _baseURL + 'human_resources/holidays/weekends_list',
               'data':{'csrf_stream_name':csrf_val}
            },
          'columns': [
             { data: 'sl' },
             { data: 'weekend_days'},
             { data: 'CreateDate' },
             { data: 'button' }
          ],
        });

        $('.addWeekends').on('click', function(){

            $('#weekendsForm').removeClass('was-validated');
            $('#id').val('');
            $('#action').val('add');

            $('#week_ends1').attr('checked', false);
            $('#week_ends2').attr('checked', false);
            $('#week_ends3').attr('checked', false);
            $('#week_ends4').attr('checked', false);
            $('#week_ends5').attr('checked', false);
            $('#week_ends6').attr('checked', false);
            $('#week_ends7').attr('checked', false);

            $('#saveBtn').text('<?php echo get_phrases(['add', 'weekends']);?>');
            $('#addModalLabel').text('<?php echo get_phrases(['add', 'new', 'weekends']);?>');

            $('#add-modal').modal('show');

        });


        $('#weekendsList').on('click', '.actionEdit', function(e){
            e.preventDefault();
            var id = $(this).attr('data-id');

            var submit_url = _baseURL+"human_resources/holidays/getWeekEndDaysById/"+id;

            // console.log(submit_url);

            $.ajax({
                type: 'POST',
                url: submit_url,
                data: {'csrf_stream_name':csrf_val},
                dataType: 'JSON',
                success: function(response) {

                    // console.log(response);

                    $('#week_ends1').attr('checked', false);
                    $('#week_ends2').attr('checked', false);
                    $('#week_ends3').attr('checked', false);
                    $('#week_ends4').attr('checked', false);
                    $('#week_ends5').attr('checked', false);
                    $('#week_ends6').attr('checked', false);
                    $('#week_ends7').attr('checked', false);

                    $.each(response.week_ends_days, function(key, value){

                        value = $.trim(value);

                        if(value=='Friday'){
                            $('#week_ends1').attr('checked', true);
                        }else if(value=='Saturday'){
                            $('#week_ends2').attr('checked', true);
                        }else if(value=='Sunday'){
                            $('#week_ends3').attr('checked', true);
                        }else if(value=='Monday'){
                            $('#week_ends4').attr('checked', true);
                        }else if(value=='Tuesday'){
                            $('#week_ends5').attr('checked', true);
                        }else if(value=='Wednesday'){
                            $('#week_ends6').attr('checked', true);
                        }else if(value=='Thursday'){
                            $('#week_ends7').attr('checked', true);
                        }else{

                        }
                    });

                    $('#id').val(response.weekend_id);

                    $('#action').val('update');
                    $('#saveBtn').text('<?php echo get_phrases(['update', 'weekends']);?>');
                    $('#addModalLabel').text('<?php echo get_phrases(['update', 'weekends']);?>');
                    $('#add-modal').modal('show');
                }
            }); 

        });

        // // delete department
        // $('#weekendsList').on('click', '.actionDelete', function(e){
        //     e.preventDefault();

        //     var id = $(this).attr('data-id');
            
        //     var submit_url = _baseURL+"human_resources/employee_salary/deleteWeekEndDaysById/"+id;
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
        //                     // $('#weekendsList').DataTable().ajax.reload(null, false);
        //                     location.reload();
        //                 }else{
        //                     toastr.error(res.message, res.title);
        //                 }
        //             },error: function() {

        //             }
        //         });
        //     }   
        // });

        
    });
</script>