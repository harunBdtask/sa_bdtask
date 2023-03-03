<div class="row">
    <div class="col-sm-12">
        <?php if($permission->module('employee')->access()){ ?>
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
                        <?php if($permission->method('employee', 'create')->access()){ ?>

                        <a href="<?php echo base_url('human_resources/employees/add_employee')?>" class="btn btn-success btn-sm mr-1"><i class="fas fa-plus mr-1"></i><?php echo get_phrases(['add', 'employee']);?></a>

                        <a href="javascript:void(0);" class="btn btn-primary btn-sm mr-1 bulkImport"><i class="fas fa-plus mr-1"></i><?php echo get_phrases(['bulk','import']);?></a>

                        <?php }?>
                       <a href="<?php echo previous_url(); ?>" class="btn btn-warning btn-sm mr-1 ml-2"><i class="fas fa-angle-double-left mr-1"></i><?php echo get_phrases(['back']);?></a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table id="employeeList" class="table display table-bordered table-striped table-hover compact" width="100%">
                    <thead>
                        <tr>
                            <th><?php echo get_phrases(['serial']);?></th>
                            <th><?php echo get_phrases(['employee', 'name']);?></th>
                            <th><?php echo get_phrases(['employee', 'id']);?></th>
                            <th><?php echo get_phrases(['employee', 'type']);?></th>
                            <th><?php echo get_phrases(['department']);?></th>
                            <th><?php echo get_phrases(['email']);?></th>
                            <th><?php echo get_phrases(['nid']);?></th>
                            <th><?php echo get_phrases(['created', 'date']);?></th>
                            <th><?php echo get_phrases(['action']);?></th><!-- Test -->
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
            <?php echo form_open_multipart('human_resources/employees/bulkImportEmployees', 'class="needs-validation" id="bulkImportEmpForm" novalidate="" data="jobCallBackData"');?>
            <div class="modal-body">
                <input type="hidden" name="id" id="id">
                <input type="hidden" name="action" id="action">
                <div class="row">
                   <div class="col-md-12 col-sm-12">
                      <div class="form-group">
                            <label class="font-weight-600"><?php echo get_phrases(['attach', 'employee', 'list']);?></label>
                            <input type="file" name="employee_list" id="employee_list" class="form-control">
                        </div>
                   </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <div class="form-group">
                             <a href="<?php echo base_url().'/assets/dist/documents/employee/employee.csv'; ?>" class="btn btn-sm btn-primary" download><?php echo get_phrases(['download', 'sample']);?>(CSV Format)</a>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <div class="form-group">
                             <p>NB: Please be sure before import that there is no dulicate NID.</p>
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
        $('#bulkImportEmpForm')[0].reset();       
        $('#bulkImportEmpForm').removeClass('was-validated');    
        $('#employeeList').DataTable().ajax.reload(null, false);
    }

    $(document).ready(function() { 
        "use strict";

        // console.log(_baseURL);

        $('#employeeList').DataTable({ 
             responsive: true,
             lengthChange: false,
             "aaSorting": [[ 7, "desc" ]],
             "columnDefs": [
                { "bSortable": false, "aTargets": [0,2,8] },
            ],
            dom: "<'row'<'col-md-6'B><'col-md-6'f>>rt<'bottom'<'row'<'col-md-6'i><'col-md-6'p>>><'clear'>",
            buttons: [{
                    extend: 'copyHtml5',
                    text: '<i class="far fa-copy custool" title="Copy"></i>',
                    titleAttr: 'Copy',
                    className: 'btn-light',
                    title : 'employee_list<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5, 6, 7]
                    }
                },
                {
                    extend: 'excelHtml5',
                    text: '<i class="far fa-file-excel custool" title="Excel"></i>',
                    titleAttr: 'Excel',
                    className: 'btn-light',
                    title : 'employee_list<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7]
                    }
                },
                {
                    extend: 'csvHtml5',
                    text: '<i class="far fa-file-alt custool" title="CSV"></i>',
                    titleAttr: 'CSV',
                    className: 'btn-light',
                    title : 'employee_list<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5, 6, 7]
                    }
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="far fa-file-pdf custool" title="PDF"></i>',
                    titleAttr: 'PDF',
                    className: 'btn-light',
                    title : 'employee_list<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7]
                    }
                }
            ],
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'ajax': {
               'url': _baseURL + 'human_resources/employees/get_employee_list',
               'data':{'csrf_stream_name':csrf_val}
            },
          'columns': [
             { data: 'sl',
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
             },
             { data: 'first_name', 
                render: function (data, type, row) {
                    return row.first_name + ' ' + row.last_name;
                }
             },
             { data: 'employee_id' },
             { data: 'emp_type' },
             { data: 'dept_name' },
             { data: 'email' },
             { data: 'nid_no' },
             { data: 'CreateDate' },
             { data: 'button' }
          ],
        });

        // To open the modal for bulk import employees
        $('.bulkImport').on('click', function(){

            $('#action').val('add');

            $('#saveBtn').text('<?php echo get_phrases(['save']);?>');
            $('#addModalLabel').text('<?php echo get_phrases(['bulk', 'import', 'employees']);?>');
            $('#add-modal').modal('show');
        });

        $('#employeeList').on('click', '.actionPrintView', function(e){
            e.preventDefault();
            var id = $(this).attr('data-id');

            //console.log(id);

            var submit_url = _baseURL+"human_resources/employees/employee_details_print/"+id; 
            $.ajax({
                type: 'POST',
                url: submit_url,
                data: {'csrf_stream_name':csrf_val},
                //dataType: 'JSON',
                async: false,
                success: function(response) {

                    // console.log(response);

                    printRawHtml(response)
                }
            });  
        });

        function printRawHtml(view) {

          printJS({
              printable: view,
              type: 'raw-html',

          });
        }


        // $('#departmentList').on('click', '.actionEdit', function(e){
        //     e.preventDefault();
        //     var id = $(this).attr('data-id');

        //     console.log(id);

        //     var submit_url = _baseURL+"human_resources/departments/getdepartmentById/"+id; 
        //     $.ajax({
        //         type: 'POST',
        //         url: submit_url,
        //         data: {'csrf_stream_name':csrf_val},
        //         dataType: 'JSON',
        //         success: function(response) {

        //             // console.log(response);

        //             $('#id').val(response.id);
        //             $('#name').val(response.name);
        //             $('#details').val(response.details);

        //             $('#action').val('update');
        //             $('#saveBtn').text('<?php echo get_phrases(['update', 'department']);?>');
        //             $('#addModalLabel').text('<?php echo get_phrases(['update', 'department', 'name']);?>');
        //             $('#add-modal').modal('show');
        //         }
        //     });  
        // });


        // // delete department
        // $('#departmentList').on('click', '.actionDelete', function(e){
        //     e.preventDefault();

        //     var id = $(this).attr('data-id');
            
        //     var submit_url = _baseURL+"human_resources/departments/deleteDepartmentById/"+id;
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
        //                     $('#departmentList').DataTable().ajax.reload(null, false);
        //                     // location.reload();
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