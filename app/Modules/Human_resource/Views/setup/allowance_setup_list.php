<div class="row">
    <div class="col-sm-12">
        <?php if($permission->module('allowance_setup')->access()){ ?>
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
                        <?php if($permission->method('allowance_setup', 'create')->access()){ ?>
                        <a href="javascript:void(0);" class="btn btn-success btn-sm mr-1 addBasicSalarySetupForm"><i class="fas fa-plus mr-1"></i><?php echo get_phrases(['add', 'allowance', 'setup']);?></a>
                        <?php }?>
                       <a href="<?php echo previous_url(); ?>" class="btn btn-warning btn-sm mr-1 ml-2"><i class="fas fa-angle-double-left mr-1"></i><?php echo get_phrases(['back']);?></a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table id="allowanceSetupList" class="table display table-bordered table-striped table-hover compact" width="100%">
                    <thead>
                        <tr>
                            <th><?php echo get_phrases(['serial']);?></th>
                            <th><?php echo get_phrases(['title']);?></th>
                            <th><?php echo get_phrases(['amount']);?></th>
                            <th><?php echo get_phrases(['employee', 'type']);?></th>
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
            <?php echo form_open_multipart('human_resources/allowance_setup/allowance_create', 'class="needs-validation" id="allowanceSetupForm" novalidate="" data="jobCallBackData"');?>
            <div class="modal-body">
                <input type="hidden" name="id" id="id">
                <input type="hidden" name="action" id="action">

                 <div class="row" id="type_name_add">
                    <div class="col-sm-10">
                        <div class="form-group">
                            <label for="employee_type" class="font-weight-600"><?php echo get_phrases(['employee', 'type']) ?> <i class="text-black">*</i></label>
                            <select name="employee_type" id="employee_type" class="custom-select form-control" required>
                                <?php if(!empty($employee_types)){ ?>
                                    <option value=""></option>
                                    <?php foreach ($employee_types as $key => $value) {?>
                                        <option value="<?php echo $key;?>"><?php echo $value;?></option>
                                    <?php }?>
                                <?php }?>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-2 pt-4">
                        <button type="button" class="btn btn-secondary" onclick="parent_reset()"><?php echo get_phrases(['reset']) ?></button>
                    </div>
                </div>

                <div class="row" id="type_name_edit">
                   <div class="col-md-12 col-sm-12">
                       <div class="form-group">
                            <label class="font-weight-600 mb-0"><?php echo get_phrases(['employee','type']);?> <i class="text-danger">*</i></label>
                             <input type="text" name="type_name" id="type_name" class="form-control" placeholder="<?php echo get_phrases(['employee','type']);?>" autocomplete="off" readonly>
                        </div>
                   </div>
                </div>


                <div class="row">
                   <div class="col-md-12 col-sm-12">
                       <div class="form-group">
                            <label class="font-weight-600 mb-0"><?php echo get_phrases(['title']);?> <i class="text-danger">*</i></label>
                             <input type="text" name="title" id="title" class="form-control" placeholder="<?php echo get_phrases(['title']);?>" autocomplete="off" required>
                        </div>
                   </div>
                </div>

                 <div class="row">
                   <div class="col-md-12 col-sm-12">
                       <div class="form-group">
                            <label class="font-weight-600 mb-0"><?php echo get_phrases(['amount']);?> <i class="text-danger">*</i></label>
                             <input type="text" name="amount" id="amount" class="form-control onlyNumber" placeholder="<?php echo get_phrases(['amount']);?>" autocomplete="off" required>
                        </div>
                   </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <label class="font-weight-600 mb-0"><?php echo get_phrases(['details']);?></label>
                            <textarea name="details" class="form-control" id="details" rows="2"></textarea>
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

     function parent_reset(){
        $('#employee_type').val('').trigger('change');
    }

    var jobCallBackData = function () { 
        $('#add-modal').modal('hide');   
        $('#allowanceSetupForm')[0].reset();       
        $('#allowanceSetupForm').removeClass('was-validated');    
        $('#allowanceSetupList').DataTable().ajax.reload(null, false);
        // location.reload();
    }

    $(document).ready(function() { 
        "use strict";

        // console.log(_baseURL);

        $('#allowanceSetupList').DataTable({ 
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
                    title : 'basic_salary_setup_list<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4]
                    }
                },
                {
                    extend: 'excelHtml5',
                    text: '<i class="far fa-file-excel custool" title="Excel"></i>',
                    titleAttr: 'Excel',
                    className: 'btn-light',
                    title : 'basic_salary_setup_list<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4]
                    }
                },
                {
                    extend: 'csvHtml5',
                    text: '<i class="far fa-file-alt custool" title="CSV"></i>',
                    titleAttr: 'CSV',
                    className: 'btn-light',
                    title : 'basic_salary_setup_list<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [  0, 1, 2, 3, 4]
                    }
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="far fa-file-pdf custool" title="PDF"></i>',
                    titleAttr: 'PDF',
                    className: 'btn-light',
                    title : 'basic_salary_setup_list<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4]
                    }
                }
            ],
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'ajax': {
               'url': _baseURL + 'human_resources/allowance_setup/get_allowance_setup_list',
               'data':{'csrf_stream_name':csrf_val}
            },
          'columns': [
             { data: 'sl' ,
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
             },
             { data: 'title'},
             { data: 'amount'},
             { data: 'type'},
             { data: 'CreateDate' },
             { data: 'button' }
          ],
        });

        $('.addBasicSalarySetupForm').on('click', function(){
            $('#allowanceSetupForm').removeClass('was-validated');
            $('#id').val('');
            $('#action').val('add');
            $('#employee_type').val('').trigger('change');
            $('#title').val('');
            $('#amount').val('');
            $('#details').val('');

            $('#type_name_add').show();
            $('#type_name_edit').hide();

            $('#saveBtn').text('<?php echo get_phrases(['add']);?>');
            $('#addModalLabel').text('<?php echo get_phrases(['add', 'allowance', 'type', 'setup']);?>');
            $('#add-modal').modal('show');

        });


        $('#allowanceSetupList').on('click', '.actionEdit', function(e){
            e.preventDefault();
            var id = $(this).attr('data-id');

            //console.log(id);

            var submit_url = _baseURL+"human_resources/allowance_setup/allowance_by_id/"+id; 
            $.ajax({
                type: 'POST',
                url: submit_url,
                data: {'csrf_stream_name':csrf_val},
                dataType: 'JSON',
                success: function(response) {

                    // console.log(response);

                    $('#id').val(response.id);
                    $('#title').val(response.title);
                    $('#amount').val(response.amount);
                    $('#details').val(response.details);
                    $('#type_name').val(response.type_name);

                    // $('#employee_type').val(response.employee_type).trigger('change');

                    $('#type_name_add').hide();
                    $('#type_name_edit').show()
                    $('#employee_type').prop('required',false);

                    $('#action').val('update');
                    $('#saveBtn').text('<?php echo get_phrases(['update']);?>');
                    $('#addModalLabel').text('<?php echo get_phrases(['update', 'allowance', 'type', 'setup']);?>');
                    $('#add-modal').modal('show');

                }
            });  
        });


        // delete department
        $('#allowanceSetupList').on('click', '.actionDelete', function(e){
            e.preventDefault();

            var id = $(this).attr('data-id');
            
            var submit_url = _baseURL+"human_resources/allowance_setup/delete_allowance/"+id;
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
                            $('#allowanceSetupList').DataTable().ajax.reload(null, false);
                            // location.reload();
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