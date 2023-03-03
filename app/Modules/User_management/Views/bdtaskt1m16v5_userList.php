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
                        <?php if($permission->method('sys_users', 'create')->access()){ ?>
                        <button type="button" class="btn btn-success btn-sm mr-1 addShowModal"><i class="fas fa-plus mr-1"></i><?php echo get_phrases(['add', 'new', 'user']);?></button>
                        <?php } ?>
                        <a href="<?php echo previous_url();?>" class="btn btn-warning btn-sm mr-1 ml-2"><i class="fas fa-angle-double-left mr-1"></i><?php echo get_phrases(['back']);?></a>
                           
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row form-group">                    
                  <!--   <div class="col-sm-4">
                        <label for="filter_branch_id" class="font-weight-600"><?php //echo get_phrases(['branch']) ?> </label>
                         <?php 
                            //echo form_dropdown('filter_branch_id','','','class="custom-select" id="filter_branch_id"');
                        ?>
                    </div> -->
                    <div class="col-sm-3">
                        <label for="filter_department" class="font-weight-600"><?php echo get_phrases(['department']) ?> </label>
                        <select name="filter_department" id="filter_department" class="custom-select form-control">
                            <option value=""></option>
                            <?php if(!empty($dept_list)){ ?>
                                <?php foreach ($dept_list as $key => $value) {?>
                                    <option value="<?php echo $value->id;?>"><?php echo $value->type;?></option>
                                <?php }?>
                            <?php }?>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <label for="filter_user_role" class="font-weight-600"><?php echo get_phrases(['user','role']) ?> </label>
                        <select name="filter_user_role" id="filter_user_role" class="custom-select form-control" >
                            <option value=""></option>
                            <?php if(!empty($sec_role_list)){ ?>
                                <?php foreach ($sec_role_list as $key => $value) {?>
                                    <option value="<?php echo $value->id;?>"><?php echo $value->type.' - '.$value->title;?></option>
                                <?php }?>
                            <?php }?>
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <br>
                        <button type="button" class="btn btn-success mt-2" onclick="reload_table()"><?php echo get_phrases(['filter']);?></button>
                        <button type="button" class="btn btn-warning mt-2" onclick="reset_table()"><?php echo get_phrases(['reset']);?></button>
                    </div>
                </div>
                 <table id="userList" class="table display table-bordered table-striped table-hover compact" width="100%">
                    <thead>
                        <tr>
                            <th># <?php echo get_phrases(['sl']);?></th>
                            <th><?php echo get_phrases(['full', 'name']);?></th>
                            <th><?php echo get_phrases(['user', 'name']);?></th>
                            <th><?php echo get_phrases(['user', 'role']);?></th>
                            <th><?php echo get_phrases(['department']);?></th>
                            <th><?php echo get_phrases(['mobile', 'number']);?></th>
                            <th><?php echo get_phrases(['created', 'date']);?></th>
                            <th><?php echo get_phrases(['status']);?></th>
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

<!-- user modal info -->
<div class="modal fade bd-example-modal-lg" id="userModal" tabindex="-1" role="dialog" aria-labelledby="moduleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="userModalLabel"><?php echo get_phrases(['update', 'module']);?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
           <?php echo form_open_multipart('permission/users/add', 'class="ajaxForm needs-validation" novalidate="" data="showCallBackData"');?>
            <div class="modal-body">
                <input type="hidden" name="user_id" id="user_id" />
                <input type="hidden" name="action" id="action" />
                 <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label class="font-weight-600"><?php echo get_phrases(['employee', 'name']);?> <i class="text-danger">*</i></label>
                            <?php echo form_dropdown('emp_id','','','class="custom-select" id="emp_id" required="required" data-toggle="tooltip" title="This Flaticon view on websit"');?>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label class="font-weight-600"><?php echo get_phrases(['full', 'name']);?> <i class="text-danger">*</i></label>
                            <input type="text" name="fullname" id="fullname" class="form-control" placeholder="<?php echo get_phrases(['enter', 'full', 'name']);?>" maxlength="80" required />
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label class="font-weight-600"><?php echo get_phrases(['user', 'name']);?> <i class="text-danger">*</i></label>
                            <input type="text" name="username" id="username" class="form-control" placeholder="<?php echo get_phrases(['enter', 'user', 'name']);?>" maxlength="80" required />
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label class="font-weight-600"><?php echo get_phrases(['password']);?> <i class="text-danger"></i></label>
                            <input type="password" name="password" id="password" class="form-control" placeholder="<?php echo get_phrases(['enter', 'password']);?>" maxlength="32" autocomplet="off" />
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label class="font-weight-600"><?php echo get_phrases(['mobile', 'no']);?> <i class="text-danger">*</i></label>
                            <input type="text" name="mobile" id="mobile" class="form-control" placeholder="<?php echo get_phrases(['enter', 'mobile', 'no']);?>" maxlength="20" autocomplet="off" required />
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label class="font-weight-600"><?php echo get_phrases(['user', 'role', 'permission']);?> <i class="text-danger">*</i></label>
                            <?php echo form_dropdown('role_id','','','class="custom-select" id="role_id" required="required"');?>
                            <input type="hidden" name="exist_role_id" id="exist_role_id" />
                        </div>
                    </div>
                </div>

                <div class="row">
                 <!--    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label class="font-weight-600"><?php //echo get_phrases(['branch']) ?> <i class="text-danger">*</i></label>                            
                            <select name="branch_id[]" id="branch_id" class="custom-select form-control" multiple required>
                                <?php //if(!empty($branch_list)){ ?>
                                    <?php //foreach ($branch_list as $key => $value) {?>
                                        <option value="<?php //echo $value->id;?>"><?php //echo $value->nameE;?></option>
                                    <?php //}?>
                                <?php // }?>
                            </select>                               
                            
                        </div>
                    </div> -->
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label class="font-weight-600"><?php echo get_phrases(['department', 'name']);?> <i class="text-danger">*</i></label>
                            <?php echo form_dropdown('department_id', '', '', 'class="form-control custom-select" id="department_id" required');?>
                        </div>
                    </div>
                </div>
                <div class="row" id="store_div">
                    <div class="col-md-6 col-sm-12" id="inventory_div">
                        <div class="form-group">
                            <label class="font-weight-600"><?php echo get_phrases(['inventory','store']) ?> </label>                            
                            <select name="wh_store_id" id="wh_store_id" class="custom-select form-control" >
                                <option value=""></option>
                                <?php if(!empty($wh_store_list)){ ?>
                                    <?php foreach ($wh_store_list as $key => $value) {?>
                                        <option value="<?php echo $value->id;?>"><?php echo $value->nameE;?></option>
                                    <?php }?>
                                <?php }?>
                            </select>                               
                            
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12" id="pharmacy_div">
                        <div class="form-group">
                            <label class="font-weight-600"><?php echo get_phrases(['pharmacy','store']) ?> </label>                            
                            <select name="ph_store_id" id="ph_store_id" class="custom-select form-control" >
                                <option value=""></option>
                                <?php if(!empty($ph_store_list)){ ?>
                                    <?php foreach ($ph_store_list as $key => $value) {?>
                                        <option value="<?php echo $value->id;?>"><?php echo $value->nameE;?></option>
                                    <?php }?>
                                <?php }?>
                            </select>                               
                            
                        </div>
                    </div>
                </div>
                <div class="row" id="warehouse_div">
                    <div class="col-sm-6">
                        <label class="font-weight-600"><?php echo get_phrases(['store','name']) ?> </label>
                        <input type="text" name="store_name" id="store_name" class="form-control" readonly>
                        <input type="hidden" name="store_id" id="store_id" value="0" >
                    </div>
                </div>
                <div class="row" >
                    <div class="col-sm-6">
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" name="status" id="active" value="1">
                          <label class="form-check-label text-success" for="active"><?php echo get_phrases(['active']) ?></label>
                        </div>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" name="status" id="inactive" value="0">
                          <label class="form-check-label text-danger" for="inactive"><?php echo get_phrases(['inactive']) ?></label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo get_phrases(['close']);?></button>
                <button type="submit" class="btn btn-success action_btn"></button>
            </div>
            <?php echo form_close();?>
        </div>
    </div>
</div>

<!-- user modal info -->
<div class="modal fade bd-example-modal-lg" id="addRoleModal" tabindex="-1" role="dialog" aria-labelledby="moduleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="roleModalLabel"><?php echo get_phrases(['update', 'user', 'role', 'permission']);?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
           <?php echo form_open_multipart('permission/users/addMoreRole', 'class="needs-validation" id="addMoreRole" novalidate="" data="roleCallBackData"');?>
            <div class="modal-body">
                <input type="hidden" name="userId" id="userId" />
                <input type="hidden" name="employee_id" id="employee_id" />

                 <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-600"><?php echo get_phrases(['full', 'name']);?></label>
                            <input type="text" name="fullname" id="full_name" class="form-control" readonly="" />
                        </div>
                    </div>
                     <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-600"><?php echo get_phrases(['main', 'role']);?></label>
                            <input type="text" name="default_role" id="default_role" class="form-control" readonly="" />
                            <input type="hidden" name="main_role" id="main_role" />
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="font-weight-600"><?php echo get_phrases(['add', 'more', 'user', 'role']);?></label>
                            <?php echo form_dropdown('role_ids[]','','','class="custom-select form-control" id="role_ids" multiple');?>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo get_phrases(['close']);?></button>
                <button type="submit" class="btn btn-success action_btn"><?php echo get_phrases(['update', 'role']);?></button>
            </div>
            <?php echo form_close();?>
        </div>
    </div>
</div>

<script type="text/javascript">
    var showCallBackData = function () {
        $('#userModal').modal('hide');
        $('.ajaxForm')[0].reset();
        $('#userList').DataTable().ajax.reload(null, false);
        $('.ajaxForm').removeClass('was-validated');
    }

    var roleCallBackData = function () {
        $('#addRoleModal').modal('hide');
        $('#userList').DataTable().ajax.reload(null, false);
        $('#addMoreRole').removeClass('was-validated');
    }

    function reload_table(){
        $('#userList').DataTable().ajax.reload();
    }
    function reset_table(){
        $('#filter_department').val('').trigger('change');
        $('#filter_user_role').val('').trigger('change');
        $('#userList').DataTable().ajax.reload();
    }

    $(document).ready(function() { 
       "use strict";
       $('select:not(#branch_id) option:first-child').val('').trigger('change');

       $('#inventory_div').hide();
       $('#pharmacy_div').hide();
       $('#warehouse_div').hide();
       
       // delete exist role
       $('#userList').on('click', '.deleteAction', function(e){
            e.preventDefault();
            var tr = $(this).parent().parent();
            var id = $(this).attr('data-id');
            var emp = $(this).attr('data-emp');
            $('.custool').tooltip('hide');

            var submit_url = _baseURL+"permission/users/delete/"+id;
            var check = confirm('<?php echo get_phrases(["are_you_sure"])?>');  
            if(check == true){  
                $.ajax({
                    type: 'POST',
                    url: submit_url,
                    data: {'csrf_stream_name':csrf_val, 'emp':emp},
                    dataType: 'JSON',
                    success: function(res) {
                        if(res.success==true){
                            toastr.success(res.message, res.title);
                            tr.remove();
                        }else{
                            toastr.error(res.message, res.title);
                        }
                    },error: function() {

                    }
                });
            }   
        });
        

        /*$('#branch_id').on('select2:select', function(e){
            get_store_name();
        });*/

        $('#role_id').on('select2:select', function(e){
            store_show();
        });

        $('#role_ids').on('select2:unselecting', function (e) {
            var unselected_value = $('#role_ids').val();
            if(confirm('<?php echo get_phrases(['are_you_sure']);?>')){
        
            }else{
                var tt = unselected_value.split(',');
                console.log(tt);
                $('#role_ids').val(tt).trigger('change');
            }
        });

       // role list
        $.ajax({
            type:'GET',
            url: _baseURL+'permission/users/getRoles',
            dataType: 'json',
            data:{'csrf_stream_name':csrf_val},
        }).done(function(data) {
            $("#role_id, #role_ids").select2({
                placeholder: '<?php echo get_phrases(['select', 'role']);?>',
                data: data
            });
        });

        // department list
        $.ajax({
            type:'GET',
            url: _baseURL+'permission/users/getDepartments',
            dataType: 'json',
            data:{'csrf_stream_name':csrf_val},
        }).done(function(data) {
            $("#department_id").select2({
                placeholder: '<?php echo get_phrases(['select', 'department']);?>',
                data: data
            });
        });

        // department list
        $.ajax({
            type:'GET',
            url: _baseURL+'permission/users/getEmpList',
            dataType: 'json',
            data:{'csrf_stream_name':csrf_val},
        }).done(function(data) {
            $("#emp_id").select2({
                placeholder: '<?php echo get_phrases(['select', 'user', 'code']);?>',
                data: data
            });
        });

        $('.addShowModal').on('click', function(){
            $('.ajaxForm').removeClass('was-validated');
            $('.ajaxForm')[0].reset();
            $('#user_id').val('');
            $('#emp_id').prop('readonly',false);
            $('#emp_id').val('').trigger('change');
            $('#fullname').val('');
            $('#username').val('');
            $('#mobile').val('');
            $('#branch_id').val('').trigger('change');
            $('#password').val('');
            $('#password').attr('required', 'required').prev().find('i').text('*');
            $('#department_id').val('').trigger('change');
            $('#role_id').val('').trigger('change');

           $('#inventory_div').hide();
           $('#pharmacy_div').hide();
           $('#warehouse_div').hide();
           
            employee_info();

            $('#userModalLabel').text('<?php echo get_phrases(['create', 'new', 'user']);?>');
            $('#action').val('add');
            $('#active').attr('checked', true);
            $('#inactive').attr('checked', false);
            $('.action_btn').text('<?php echo get_phrases(['create', 'user']);?>');
            $('#userModal').modal('show');
        })

        // update module info
        $('#userList').on('click', '.editAction', function(e){
            e.preventDefault();
            $('.ajaxForm').removeClass('was-validated');
            
            var id = $(this).attr('data-id');
            var submit_url = _baseURL+'permission/users/getUserById/'+id;

            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'JSON',
                data: {'csrf_stream_name':csrf_val},
                success: function(data) {
                    // console.log(data);
                    // var date = data.date_of_birth;
                    // var dateAr = date.split('-');
                    // var newDate = dateAr[2] + '/' + dateAr[1] + '/' + dateAr[0];
        
                    $('.ajaxForm')[0].reset();
                    $('#user_id').val(data.id);
                    $('#emp_id').val(data.emp_id).trigger('change');
                    $('#emp_id').prop('readonly',true);

                    $('#fullname').val(data.fullname);
                    $('#username').val(data.username);
                    //$('#gender').val(data.gender).trigger('change');
                    $('#mobile').val(data.mobile);
                    $('#password').removeAttr('required').prev().find('i').text('');
                    //$('#email').val(data.email);
                    //$('#dob').val(newDate);
                    //$('#branch_id').val(data.branch_id).trigger('change');

                    var branch_id = data.branch_id.split(",");
                    $('#branch_id').val(branch_id).trigger('change');

                    $('#department_id').val(data.department_id).trigger('change');
                    $('#store_id').val(data.store_id);
                    $('#wh_store_id').val(data.store_id).trigger('change');
                    $('#ph_store_id').val(data.store_id).trigger('change');
                    $('#role_id').val(data.user_role).trigger('change');
                    $('#exist_role_id').val(data.user_role);
                    store_show('update');
                    if(data.status==1){
                        $('#active').attr('checked', true);
                        $('#inactive').attr('checked', false);
                    }else{
                        $('#inactive').attr('checked', true);
                        $('#active').attr('checked', false);
                    }

                    $('#userModalLabel').text('<?php echo get_phrases(['update', 'user']);?>');
                    $('#action').val('update');
                    $('.action_btn').text('<?php echo get_phrases(['update', 'user']);?>');
                    $('#userModal').modal('show');
                },error: function() {

                }
            });   
        });

        // add more user role
        $('#userList').on('click', '.roleAction', function(e){
            e.preventDefault();
            $('#addMoreRole')[0].reset();
            $('#addMoreRole').removeClass('was-validated');
            $('#addRoleModal').modal('show');
            var emp = $(this).attr('data-emp');

            var submit_url = _baseURL+'permission/users/empRoles/'+emp;
            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'JSON',
                data: {'csrf_stream_name':csrf_val},
                success: function(data) {
                    $('#userId').val(data.id);
                    $('#employee_id').val(data.emp_id);
                    $('#full_name').val(data.fullname);
                    $('#default_role').val(data.type);
                    $('#main_role').val(data.user_role);
                    $('#role_ids').val(data.roleIds).trigger('change');
                    //$('option:first-child').val('').trigger('change');
                },error: function() {

                }
            });   
        });

        // GET ALL USERS
        $('#userList').DataTable({ 
             responsive: true,
             lengthChange: true,
             "aaSorting": [[ 0, "desc" ]],
             "columnDefs": [
                { "bSortable": false, "aTargets": [3,4,8] }
            ],
            dom: "<'row'<'col-md-4'l><'col-md-4'B><'col-md-4'f>>rt<'bottom'<'row'<'col-md-6'i><'col-md-6'p>>><'clear'>",
            buttons: [{
                    extend: 'print',
                    text: '<i class="fa fa-print custool" title="Print"></i>',
                    titleAttr: 'Print',
                    className: 'btn-light',
                    title : 'User_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: 'th:not(:last-child)'
                    }
                },{
                    extend: 'copyHtml5',
                    text: '<i class="far fa-copy custool" title="Copy"></i>',
                    titleAttr: 'Copy',
                    className: 'btn-light',
                    title : 'User_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [0, 2, 3, 4, 5, 6, 7, 8 ]
                    }
                },
                {
                    extend: 'excelHtml5',
                    text: '<i class="far fa-file-excel custool" title="Excel"></i>',
                    titleAttr: 'Excel',
                    className: 'btn-light',
                    title : 'User_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [0, 2, 3, 4, 5, 6, 7, 8 ]
                    }
                },
                {
                    extend: 'csvHtml5',
                    text: '<i class="far fa-file-alt custool" title="CSV"></i>',
                    titleAttr: 'CSV',
                    className: 'btn-light',
                    title : 'User_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [0, 2, 3, 4, 5, 6, 7, 8 ]
                    }
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="far fa-file-pdf custool" title="PDF"></i>',
                    titleAttr: 'PDF',
                    className: 'btn-light',
                    title : 'User_List-<?php echo date('Y-m-d');?>',
                    exportOptions: {
                        columns: [0, 2, 3, 4, 5, 6, 7, 8]
                    }
                }
            ],
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'ajax': {
               'url': _baseURL + 'permission/users/getList',
               'data':function ( d ) {
                        d.csrf_stream_name = csrf_val;
                        d.department = $('#filter_department').val();
                        d.user_role = $('#filter_user_role').val();
                    }
            },
            'columns': [
                { data: 'id' },
                { data: 'fullname' },
                { data: 'username' },
                { data: 'role' },
                { data: 'nameE' },
                { data: 'mobile' },
                { data: 'created_date' },
                { data: 'status' },
                { data: 'button'}
            ]
        });

        $('#userList').on('draw.dt', function() {
             $('.custool').tooltip(); 
        });
        // branch list
        $.ajax({
            type:'GET',
            url: _baseURL+'auth/branchList',
            dataType: 'json',
            data:{'csrf_stream_name':csrf_val},
        }).done(function(data) {
            $("#filter_branch_id").select2({
                placeholder: '<?php echo get_phrases(['select', 'branch', 'name']);?>',
                data: data
            });
            $("#filter_branch_id").val('<?php echo session('branchId');?>').trigger('change');
            
        });
        

        $("#filter_branch_id").on('change', function(){
            var result = change_top_branch($(this).val());
            if(result == true){
                $('#userList').DataTable().ajax.reload();
            }
        });
    });

    function employee_info(){

             // GET EMPLOYEE INFO BY ID
            $('#emp_id').on('change', function(e){
                e.preventDefault();
                $('.ajaxForm').removeClass('was-validated');
                
                var id = $(this).val();
                var submit_url = _baseURL+'permission/users/getEmployeeById/'+id;

                $.ajax({
                    type: 'POST',
                    url: submit_url,
                    dataType : 'JSON',
                    data: {'csrf_stream_name':csrf_val},
                    success: function(data) {
                        console.log(data);
                        if(data != null){
                            //$('#emp_id').val(data.emp_id).trigger('change');
                            $('#fullname').val(data.fullname);
                            $('#username').val(data.email);
                            //$('#gender').val(data.gender).trigger('change');
                            $('#mobile').val(data.phone);
                            $('#password').removeAttr('required').prev().find('i').text('');
                            //$('#email').val(data.email);
                            //$('#dob').val(newDate);
                            $('#branch_id').val(data.branch_id).trigger('change');
                            $('#department_id').val(data.designation).trigger('change');
                            //$('#role_id').val(data.roleIds).trigger('change');
                            //$('#exist_role_id').val(data.roleIds);
                        }
                    }
                });   
            });
    }

    function get_store_name(action, type){
        if(action =='update'){
            var id = $('#store_id').val();
        } else {
            var id = '<?php echo session('branchId'); ?>';//$('#branch_id').val();
        }
        var submit_url = _baseURL+'permission/users/get_store_name/'+id;
        if(id !=''){
            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'JSON',
                data: {'csrf_stream_name':csrf_val, 'type':type},
                success: function(data) {
                    if(data != null){
                        $('#store_name').val(data.nameE);
                        $('#store_id').val(data.id);
                    } else {
                        $('#store_name').val('');
                        $('#store_id').val(0);
                    }
                }
            });
        } else {
            $('#store_name').val('');
            $('#store_id').val(0);
        }
    }

    function store_show(action=null){
        var data =  $('#role_id').find(':selected');
        //alert(data.text());
        //console.log(data);
        if(data) {
            var role = data.text();
            var arr = role.split('-');
            if( arr.length >1 ){
                if( arr[0].trim() =='Store Manager' && arr[1].trim() =='Inventory'){
                    $('#inventory_div').show();  
                    $('#wh_store_id').attr('required','required');
                    //$('#wh_store_id').val('').trigger('change');

                    $('#pharmacy_div').hide();  
                    $('#ph_store_id').val('').trigger('change');
                    $('#ph_store_id').removeAttr('required');

                } else if(arr[0].trim() =='Store Manager' && arr[1].trim() =='Pharmacy'){
                    $('#pharmacy_div').show();
                    $('#ph_store_id').attr('required','required');
                    //$('#ph_store_id').val('').trigger('change');

                    $('#inventory_div').hide();
                    $('#wh_store_id').val('').trigger('change');
                    $('#wh_store_id').removeAttr('required');   

                } else {
                    $('#inventory_div').hide();
                    $('#wh_store_id').removeAttr('required');   
                    $('#wh_store_id').val('').trigger('change');

                    $('#pharmacy_div').hide();
                    $('#ph_store_id').removeAttr('required');
                    $('#ph_store_id').val('').trigger('change');
                }

                if(arr[0].trim() =='Warehouse Manager' && arr[1].trim() =='Inventory'){
                    $('#warehouse_div').show();
                    get_store_name(action, 'wh');
                } else if(arr[0].trim() =='Warehouse Manager' && arr[1].trim() =='Pharmacy'){
                    $('#warehouse_div').show();
                    get_store_name(action, 'ph');
                } else {
                    $('#store_name').val('');
                    $('#store_id').val('');
                    $('#warehouse_div').hide();
                }
            }
        }
    }
</script>