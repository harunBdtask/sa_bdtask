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
                        <!-- <h6 class="fs-17 font-weight-600 mb-0">Bootstrap 4 Print button</h6> -->
                    </div>
                    <div class="text-right">
                       <a href="<?php echo previous_url();?>" class="btn btn-warning btn-sm mr-1 ml-2"><i class="fas fa-angle-double-left mr-1"></i><?php echo get_phrases(['back']);?></a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-3">
                        <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                            <?php if($permission->method('export_user_list', 'read')->access()){ ?>
                            <a class="nav-link active" id="v-pills-user-tab" data-toggle="pill" href="#v-pills-user" role="tab" aria-controls="v-pills-user" aria-selected="false"><?php echo get_phrases(['user','list']);?></a>
                            <?php } ?>
                            <?php if($permission->method('export_employee_list', 'read')->access()){ ?>
                            <a class="nav-link" id="v-pills-employee-tab" data-toggle="pill" href="#v-pills-employee" role="tab" aria-controls="v-pills-employee" aria-selected="false"><?php echo get_phrases(['employee','list']);?></a>
                            <?php } ?>
                            <?php if($permission->method('export_service_list', 'read')->access()){ ?>
                            <a class="nav-link" id="v-pills-service-tab" data-toggle="pill" href="#v-pills-service" role="tab" aria-controls="v-pills-service" aria-selected="true"><?php echo get_phrases(['service','list']);?></a>
                            <?php } ?>
                            <?php if($permission->method('export_offer_list', 'read')->access()){ ?>
                            <a class="nav-link" id="v-pills-offer-tab" data-toggle="pill" href="#v-pills-offer" role="tab" aria-controls="v-pills-offer" aria-selected="false"><?php echo get_phrases(['offer','list']);?></a>
                            <?php } ?>
                            <?php if($permission->method('export_package_list', 'read')->access()){ ?>
                            <a class="nav-link" id="v-pills-package-tab" data-toggle="pill" href="#v-pills-package" role="tab" aria-controls="v-pills-package" aria-selected="false"><?php echo get_phrases(['offer','package','list']);?></a>
                            <?php } ?>
                            <?php if($permission->method('export_item_list', 'read')->access()){ ?>
                            <a class="nav-link" id="v-pills-item-tab" data-toggle="pill" href="#v-pills-item" role="tab" aria-controls="v-pills-item" aria-selected="false"><?php echo get_phrases(['item','list']);?></a>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="col-sm-9">
                        <div class="tab-content" id="v-pills-tabContent">

                            <?php if($permission->method('export_user_list', 'read')->access()){ ?>

                            <div class="tab-pane fade" id="v-pills-user" role="tabpanel" aria-labelledby="v-pills-user-tab">
                                <?php echo form_open_multipart('data_export/get_user_data', ' class="" id="" ');?>

                                <div class="row form-group">                    
                                    <div class="col-sm-4">
                                        <label for="user_branch_id" class="font-weight-600"><?php echo get_phrases(['branch']) ?> </label>
                                        <?php echo form_dropdown('user_branch_id','','','class="custom-select" id="user_branch_id"');?>
                                    </div>
                                    <div class="col-sm-3">
                                        <label for="user_department" class="font-weight-600"><?php echo get_phrases(['department']) ?> </label>
                                        <select name="user_department" id="user_department" class="custom-select form-control">
                                            <option value=""></option>
                                            <?php if(!empty($dept_list)){ ?>
                                                <?php foreach ($dept_list as $key => $value) {?>
                                                    <option value="<?php echo $value->id;?>"><?php echo $value->nameE;?></option>
                                                <?php }?>
                                            <?php }?>
                                        </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <label for="user_role" class="font-weight-600"><?php echo get_phrases(['user','role']) ?> </label>
                                        <select name="user_role" id="user_role" class="custom-select form-control" >
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
                                        <?php if($permission->method('export_user_list', 'create')->access()){ ?>
                                        <button type="submit" class="btn btn-success mt-2" ><?php echo get_phrases(['export']);?></button>
                                        <?php } ?>
                                    </div>
                                </div>

                                <?php echo form_close();?>
                            </div>

                            <?php } ?>
                            <?php if($permission->method('export_employee_list', 'read')->access()){ ?>

                            <div class="tab-pane fade" id="v-pills-employee" role="tabpanel" aria-labelledby="v-pills-employee-tab">
                                <?php echo form_open_multipart('data_export/get_employee_data', ' class="" id="" ');?>

                                <div class="row form-group">                    
                                    <div class="col-sm-4">
                                        <label for="emp_branch_id" class="font-weight-600"><?php echo get_phrases(['branch']) ?> </label>
                                        <?php echo form_dropdown('emp_branch_id','','','class="custom-select" id="emp_branch_id"');?>
                                    </div>
                                    <div class="col-sm-3">
                                        <label for="emp_department" class="font-weight-600"><?php echo get_phrases(['department']) ?> </label>
                                        <select name="emp_department" id="emp_department" class="custom-select form-control">
                                            <option value=""></option>
                                            <?php if(!empty($dept_list)){ ?>
                                                <?php foreach ($dept_list as $key => $value) { ?>
                                                    <option value="<?php echo $value->id;?>"><?php echo $value->nameE;?></option>
                                                <?php }?>
                                            <?php }?>
                                        </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <label for="emp_job_title" class="font-weight-600"><?php echo get_phrases(['job','title']) ?> </label>
                                        <select name="emp_job_title" id="emp_job_title" class="custom-select form-control" >
                                            <option value=""></option>
                                            <?php if(!empty($job_title_list)){ ?>
                                                <?php foreach ($job_title_list as $key => $value) {?>
                                                    <option value="<?php echo $value->id;?>"><?php echo $value->nameE;?></option>
                                                <?php }?>
                                            <?php }?>
                                        </select>
                                    </div>
                                    <div class="col-sm-2">
                                        <br>
                                        <?php if($permission->method('export_employee_list', 'create')->access()){ ?>
                                        <button type="submit" class="btn btn-success mt-2" ><?php echo get_phrases(['export']);?></button>
                                        <?php } ?>
                                    </div>
                                </div>

                                <?php echo form_close();?>
                            </div>

                            <?php } ?>
                            <?php if($permission->method('export_service_list', 'read')->access()){ ?>

                            <div class="tab-pane fade active show" id="v-pills-service" role="tabpanel" aria-labelledby="v-pills-service-tab">
                                <?php echo form_open_multipart('data_export/get_service_data', ' class="" id="" ');?>

                                <div class="row form-group">                    
                                    <div class="col-sm-4">
                                        <label for="ser_branch_id" class="font-weight-600"><?php echo get_phrases(['branch']) ?> </label>
                                        <?php echo form_dropdown('ser_branch_id','','','class="custom-select" id="ser_branch_id"');?>
                                    </div>
                                    <div class="col-sm-3">
                                        <label for="ser_department" class="font-weight-600"><?php echo get_phrases(['department']) ?> </label>
                                        <select name="ser_department" id="ser_department" class="custom-select form-control">
                                            <option value=""></option>
                                            <?php if(!empty($dept_list)){ ?>
                                                <?php foreach ($dept_list as $key => $value) { ?>
                                                    <option value="<?php echo $value->id;?>"><?php echo $value->nameE;?></option>
                                                <?php }?>
                                            <?php }?>
                                        </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <label for="ser_sub_dept" class="font-weight-600"><?php echo get_phrases(['sub','department']) ?> </label>
                                        <select name="ser_sub_dept" id="ser_sub_dept" class="custom-select form-control" >
                                            <option value=""></option>
                                            <?php if(!empty($sub_dept_list)){ ?>
                                                <?php foreach ($sub_dept_list as $key => $value) {?>
                                                    <option value="<?php echo $value->id;?>"><?php echo $value->nameE;?></option>
                                                <?php }?>
                                            <?php }?>
                                        </select>
                                    </div>
                                    <div class="col-sm-2">
                                        <br>
                                        <?php if($permission->method('export_service_list', 'create')->access()){ ?>
                                        <button type="submit" class="btn btn-success mt-2" ><?php echo get_phrases(['export']);?> </button>
                                        <?php } ?>
                                    </div>
                                </div>

                                <?php echo form_close();?>
                            </div>

                            <?php } ?>
                            <?php if($permission->method('export_offer_list', 'read')->access()){ ?>

                            <div class="tab-pane fade" id="v-pills-offer" role="tabpanel" aria-labelledby="v-pills-offer-tab">
                                <?php echo form_open_multipart('data_export/get_offer_data', ' class="" id="" ');?>

                                <div class="row form-group"> 
                                    <div class="col-sm-2">
                                        <br>
                                        <?php if($permission->method('export_offer_list', 'create')->access()){ ?>
                                        <button type="submit" class="btn btn-success mt-2" ><?php echo get_phrases(['export']);?> </button>
                                        <?php } ?>
                                    </div>
                                </div>

                                <?php echo form_close();?>
                            </div>

                            <?php } ?>
                            <?php if($permission->method('export_package_list', 'read')->access()){ ?>

                            <div class="tab-pane fade" id="v-pills-package" role="tabpanel" aria-labelledby="v-pills-package-tab">
                               <?php echo form_open_multipart('data_export/get_package_data', ' class="" id="" ');?>

                                <div class="row form-group"> 
                                    <div class="col-sm-2">
                                        <br>
                                        <?php if($permission->method('export_package_list', 'create')->access()){ ?>
                                        <button type="submit" class="btn btn-success mt-2" ><?php echo get_phrases(['export']);?> </button>
                                        <?php } ?>
                                    </div>
                                </div>

                                <?php echo form_close();?>
                            </div>

                            <?php } ?>
                            <?php if($permission->method('export_item_list', 'read')->access()){ ?>

                            <div class="tab-pane fade" id="v-pills-item" role="tabpanel" aria-labelledby="v-pills-item-tab">
                                <?php echo form_open_multipart('data_export/get_item_data', ' class="" id="" ');?>

                                <div class="row form-group"> 

                                    <div class="col-sm-3">
                                        <label for="item_category" class="font-weight-600"><?php echo get_phrases(['item','category']) ?> </label>
                                        <select name="item_category" id="item_category" class="custom-select form-control" >
                                            <option value=""></option>
                                            <?php if(!empty($categories)){ ?>
                                                <?php foreach ($categories as $key => $value) {?>
                                                    <option value="<?php echo $value->id;?>"><?php echo $value->nameE;?></option>
                                                <?php }?>
                                            <?php }?>
                                        </select>
                                    </div>
                                    <div class="col-sm-2">
                                        <br>
                                        <?php if($permission->method('export_item_list', 'create')->access()){ ?>
                                        <button type="submit" class="btn btn-success mt-2" ><?php echo get_phrases(['export']);?> </button>
                                        <?php } ?>
                                    </div>
                                </div>

                                <?php echo form_close();?>
                            </div>
                            <?php } ?>

                        </div>
                    </div>
                </div>
                

            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

    function parent_reset(){
        $('#parent_id').val('').trigger('change');
    }

    var showCallBackData = function () {
        //$('#id').val('');        
        //$('#action').val('add');        
        $('.ajaxForm')[0].reset();
        //$('#department-modal').modal('hide');
        //$('#picture').val('');
        //$('#thumbpic').attr('src', '');
        //$('#thumbpic').next('span').text('');
        //$('#departmentList').DataTable().ajax.reload(null, false);
    }
    $(document).ready(function() { 
       "use strict";

        $('option:first-child').val('').trigger('change');
        

        // branch list
        $.ajax({
            type:'GET',
            url: _baseURL+'auth/branchList',
            dataType: 'json',
            data:{'csrf_stream_name':csrf_val},
        }).done(function(data) {
            $("#user_branch_id,#emp_branch_id,#ser_branch_id").select2({
                placeholder: '<?php echo get_phrases(['select', 'branch', 'name']);?>',
                data: data
            });
            $("#user_branch_id,#emp_branch_id,#ser_branch_id").val('<?php echo session('branchId');?>').trigger('change');
        });


        $('.addShowModal').on('click', function(){
            $('.ajaxForm').removeClass('was-validated');
            //$('#thumbpic').next('span').text('');
            //$('#picture').val('');
            //$('#thumbpic').attr('src', '');
            $('#id').val('');
            $('#action').val('add');
            $('#nameE').val('');
            $('#nameA').val('');
            //$('#description').val('');
            $('#branch_id').val('').trigger('change');
            $('#parent_id').val('').trigger('change');
            $('#store_id').val('').trigger('change');
            //$('#flaticon').val('').trigger('change');
            $('#departmentModalLabel').text('<?php echo get_phrases(['add', 'department']);?>');
            $('.modal_action').text('<?php echo get_phrases(['add']);?>');
            $('#department-modal').modal('show');
        });

    });
</script>