<div class="row">
    <div class="col-sm-12">
        <?php if($permission->module('govt_holidays')->access()){ ?>
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
                        <?php if($permission->method('govt_holidays', 'create')->access()){ ?>
                        <a href="javascript:void(0);" class="btn btn-success btn-sm mr-1 addGovtHoliday"><i class="fas fa-plus mr-1"></i><?php echo get_phrases(['add', 'govt', 'holiday']);?></a>
                        <?php }?>
                       <a href="<?php echo previous_url(); ?>" class="btn btn-warning btn-sm mr-1 ml-2"><i class="fas fa-angle-double-left mr-1"></i><?php echo get_phrases(['back']);?></a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table id="govtHolidayList" class="table display table-bordered table-striped table-hover compact" width="100%">
                    <thead>
                        <tr>
                            <th><?php echo get_phrases(['serial']);?></th>
                            <th><?php echo get_phrases(['holiday', 'name']);?></th>
                            <th><?php echo get_phrases(['start', 'date']);?></th>
                            <th><?php echo get_phrases(['end', 'date']);?></th>
                            <th><?php echo get_phrases(['number', 'of', 'days']);?></th>
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
            <?php echo form_open_multipart('human_resources/holidays/addGovtHoliday', 'class="needs-validation" id="govtHolidayForm" novalidate="" data="jobCallBackData"');?>
            <div class="modal-body">
                <input type="hidden" name="id" id="id">
                <input type="hidden" name="action" id="action">

                <div class="row">
                   <div class="col-md-12 col-sm-12">
                       <div class="form-group">
                            <label class="font-weight-600 mb-0"><?php echo get_phrases(['holiday', 'name']);?> <i class="text-danger">*</i></label>
                             <input type="text" name="holiday_name" id="holiday_name" class="form-control" placeholder="<?php echo get_phrases(['enter', 'holiday', 'name']);?>" autocomplete="off" required>
                        </div>
                   </div>
                </div>

                <div class="row">
                   <div class="col-md-12 col-sm-12">
                       <div class="form-group">
                            <label class="font-weight-600 mb-0"><?php echo get_phrases(['start', 'date']);?> <i class="text-danger">*</i></label>
                             <input type="text" name="start_date" id="start_date" class="form-control start_end_datepicker" placeholder="<?php echo get_phrases(['enter', 'start', 'date']);?>" autocomplete="off" required>
                        </div>
                   </div>
                </div>

                <div class="row">
                   <div class="col-md-12 col-sm-12">
                       <div class="form-group">
                            <label class="font-weight-600 mb-0"><?php echo get_phrases(['end', 'date']);?> <i class="text-danger">*</i></label>
                             <input type="text" name="end_date" id="end_date" class="form-control start_end_datepicker" placeholder="<?php echo get_phrases(['enter', 'end', 'date']);?>" autocomplete="off" required>
                        </div>
                   </div>

                </div>
                
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <label class="font-weight-600 mb-0"><?php echo get_phrases(['number', 'of', 'days']);?></label>
                        <input type="text" name="no_of_days" id="no_of_days" class="form-control" placeholder="<?php echo get_phrases(['number', 'of', 'days']);?>" autocomplete="off" readonly>
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
        $('#govtHolidayForm')[0].reset();       
        $('#govtHolidayForm').removeClass('was-validated');    
        $('#govtHolidayList').DataTable().ajax.reload(null, false);
    }

    $(document).ready(function() { 
        "use strict";

        // console.log(_baseURL);

        $('#govtHolidayList').DataTable({ 
             responsive: true,
             lengthChange: false,
             "aaSorting": [[ 1, "asc" ]],
             "columnDefs": [
                { "bSortable": false, "aTargets": [6] },
            ],
            dom: "<'row'<'col-md-6'B><'col-md-6'f>>rt<'bottom'<'row'<'col-md-6'i><'col-md-6'p>>><'clear'>",
            buttons: [{
                    extend: 'copyHtml5',
                    text: '<i class="far fa-copy custool" title="Copy"></i>',
                    titleAttr: 'Copy',
                    className: 'btn-light',
                    title : 'Govt_holidays<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5]
                    }
                },
                {
                    extend: 'excelHtml5',
                    text: '<i class="far fa-file-excel custool" title="Excel"></i>',
                    titleAttr: 'Excel',
                    className: 'btn-light',
                    title : 'Govt_holidays<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5]
                    }
                },
                {
                    extend: 'csvHtml5',
                    text: '<i class="far fa-file-alt custool" title="CSV"></i>',
                    titleAttr: 'CSV',
                    className: 'btn-light',
                    title : 'Govt_holidays<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5]
                    }
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="far fa-file-pdf custool" title="PDF"></i>',
                    titleAttr: 'PDF',
                    className: 'btn-light',
                    title : 'Govt_holidays<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5]
                    }
                }
            ],
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'ajax': {
               'url': _baseURL + 'human_resources/holidays/govt_holidays_list',
               'data':{'csrf_stream_name':csrf_val}
            },
          'columns': [
             { data: 'sl' ,
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
             },
             { data: 'holiday_name'},
             { data: 'start_date'},
             { data: 'end_date'},
             { data: 'no_of_days'},
             { data: 'CreateDate' },
             { data: 'button' }
          ],
        });

        $('.addGovtHoliday').on('click', function(){
            $('#govtHolidayForm').removeClass('was-validated');

            $('#id').val('');
            $('#action').val('add');

            $('#holiday_name').val('');
            $('#start_date').val('');
            $('#end_date').val('');
            $('#no_of_days').val('');

            $('#saveBtn').text('<?php echo get_phrases(['add','type']);?>');
            $('#addModalLabel').text('<?php echo get_phrases(['add', 'government', 'holiday']);?>');
            $('#add-modal').modal('show');
        });


        $('#govtHolidayList').on('click', '.actionEdit', function(e){
            e.preventDefault();
            var id = $(this).attr('data-id');

            //console.log(id);

            var submit_url = _baseURL+"human_resources/holidays/getGovtHolidayById/"+id; 
            $.ajax({
                type: 'POST',
                url: submit_url,
                data: {'csrf_stream_name':csrf_val},
                dataType: 'JSON',
                success: function(response) {

                    console.log(response);

                    $('#id').val(response.holiday_id);
                    
                    $('#holiday_name').val(response.holiday_name);
                    $('#start_date').val(response.start_date);
                    $('#end_date').val(response.end_date);
                    $('#no_of_days').val(response.no_of_days);

                    $('#action').val('update');
                    $('#saveBtn').text('<?php echo get_phrases(['update', 'holiday']);?>');
                    $('#addModalLabel').text('<?php echo get_phrases(['update', 'government', 'holiday']);?>');
                    $('#add-modal').modal('show');
                }
            });  
        });


        // delete department
        $('#govtHolidayList').on('click', '.actionDelete', function(e){
            e.preventDefault();

            var id = $(this).attr('data-id');
            
            var submit_url = _baseURL+"human_resources/holidays/deleteGovtHoliday/"+id;
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
                            $('#govtHolidayList').DataTable().ajax.reload(null, false);
                            // location.reload();
                        }else{
                            toastr.error(res.message, res.title);
                        }
                    },error: function() {

                    }
                });
            }   
        });


        // Getting date using daterangepicker and also setting auto start date as blank using autoUpdateInput: false ans showing the selected date using the callback function ... on("apply.daterangepicker", function (e, picker){}
                
        //Single Date Picker with month and year selections
        $('.start_end_datepicker').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            minYear: 1970,
            maxYear: parseInt(moment().format('YYYY'), 10),
            autoUpdateInput: false,
            locale : {
                format : 'YYYY-MM-DD'
            }
        }).on("apply.daterangepicker", function (e, picker) {

            picker.element.val(picker.startDate.format(picker.locale.format));

            // Get the no_of_days holiday after all calculations

            var start_date = $('#start_date').val();
            var end_date = $('#end_date').val();

            if(end_date){

                var submit_url = _baseURL+"human_resources/holidays/getNoOfGovtHolidays";

                $.ajax({
                    type: 'POST',
                    url: submit_url,
                    data: {'csrf_stream_name':csrf_val,'start_date':start_date,'end_date':end_date},
                    dataType: 'JSON',
                    success: function(res) {

                        // console.log(res);

                        $('#no_of_days').val(res.no_of_days);

                    },error: function() {

                    }
                });

            }
        });


        // $('.start_datepicker').daterangepicker({
        //         singleDatePicker: true,
        //         "timePicker": true,
        //         showDropdowns: true,
        //          autoclose: false,
        //         "timePicker24Hour": true,
        //     timePickerIncrement: 1,
        //         maxDate: '2200',
        //         minYear: (new Date).getFullYear(),
        //         "drops": "down",
        //         locale: {
        //             format: 'YYYY-MM-DD HH:mm'
        //         },
        //         maxYear: parseInt(moment().format('YYYY'), 10)
        //     }, function(start, end, label) {
        //         var years = moment().diff(start, 'years');
        //     });

        // $('.end_datepicker').daterangepicker({
        //            singleDatePicker: true,
        //     showDropdowns: true,
        //     minYear: 1970,
        //     maxYear: parseInt(moment().format('YYYY'), 10),
        //     autoUpdateInput: false,
        //     locale : {
        //         format : 'YYYY-MM-DD'
        //     },
        //     }, function(start, end, label) {

        //         alert('sddddddddd');
        //         var years = moment().diff(start, 'years');
        //     });

        
    });
</script>