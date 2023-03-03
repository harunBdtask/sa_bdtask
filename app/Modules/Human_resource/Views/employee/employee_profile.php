 <div class="body-content">
     
    <div class="row">
        <div class="col-sm-12 col-xl-8">
            <div class="media m-1 ">
                <div class="align-left p-1">
                    <a href="#" class="profile-image">
                        <img src="<?php echo base_url('/');?><?php echo $employee_profile_info->image?$employee_profile_info->image:'/assets/dist/img/avatar/avatar-1.png';?>" class="avatar avatar-xl rounded-circle img-border height-100" alt="card image">
                    </a>
                </div>
                <div class="media-body ms-3 mt-1">
                    <h3 class="font-large-1 white"><?php echo $employee_profile_info->first_name.' '.$employee_profile_info->last_name;?>
                        <span class="font-medium-1 white">(<?php echo $employee_profile_info->emp_designation;?>)</span>
                    </h3>
                    <p class="white">
                        <i class="fas fa-map-marker-alt"></i> <?php echo $employee_profile_info->permanent_address?$employee_profile_info->permanent_address:'';?> </p>
                    <!-- <p class="white text-bold-300 d-none d-sm-block">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc sed odio risus. Integer sit amet dolor elit. Suspendisse
                        ac neque in lacus venenatis convallis. Sed eu lacus odio</p> -->
                    <!-- <ul class="list-inline">
                        <li class="list-inline-item pr-1 line-height-1">
                            <a href="#" class="fs-26 ">
                                <i class="fab fa-facebook"></i>
                            </a>
                        </li>
                        <li class="list-inline-item pr-1 line-height-1">
                            <a href="#" class="fs-26 ">
                                <i class="fab fa-twitter"></i>
                            </a>
                        </li>
                        <li class="list-inline-item line-height-1">
                            <a href="#" class="fs-26 ">
                                <i class="fab fa-instagram"></i>
                            </a>
                        </li>
                    </ul> -->
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4">
            <div class="card mb-4">

                <div class="card-header card-h-custom bg-info text-white">
                    <h6 class="fs-17 fw-semi-bold mb-0"><?php echo get_notify('personal_information');?></h6>
                </div>

                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="mb-0 fw-semi-bold">Name</h6>
                        </div>
                        <div class="col-auto">
                            <span class="fs-13 fw-semi-bold text-muted"><?php echo $employee_profile_info->first_name.' '.$employee_profile_info->last_name;?></span>
                        </div>
                    </div> 
                    <hr>
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="mb-0 fw-semi-bold">Bangla Name</h6>
                        </div>
                        <div class="col-auto">
                            <span class="fs-13 fw-semi-bold text-muted"><?php echo $employee_profile_info->bangla_name;?></span>
                        </div>
                    </div> 
                    <hr>
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="mb-0 fw-semi-bold">Mobile No</h6>
                        </div>
                        <div class="col-auto">
                            <span class="fs-13 fw-semi-bold text-muted"><?php echo $employee_profile_info->mobile_no1;?></span>
                        </div>
                    </div>
                    <hr>
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="mb-0 fw-semi-bold">Email Address</h6>
                        </div>
                        <div class="col-auto">
                            <span class="fs-13 fw-semi-bold text-muted"><?php echo $employee_profile_info->email;?></span>
                        </div>
                    </div>
                    <hr>
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="mb-0 fw-semi-bold">City</h6>
                        </div>
                        <div class="col-auto">
                            <span class="fs-13 fw-semi-bold text-muted"><?php echo $employee_profile_info->city;?></span>
                        </div>
                    </div>
                    <hr>
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="mb-0 fw-semi-bold">Zip Code</h6>
                        </div>
                        <div class="col-auto">
                            <span class="fs-13 fw-semi-bold text-muted"><?php echo $employee_profile_info->zip_code;?></span>
                        </div>
                    </div>

                </div>

            </div>
            <div class="card mb-4">

                <div class="card-header card-h-custom bg-info text-white">
                    <h6 class="fs-17 fw-semi-bold mb-0"><?php echo get_notify('bio_-_graphical_information');?></h6>
                </div>

                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="mb-0 fw-semi-bold">Birthday</h6>
                        </div>
                        <div class="col-auto">
                            <span class="fs-13 fw-semi-bold text-muted"><?php echo $employee_profile_info->birth_date;?></span>
                        </div>
                    </div> 
                    <hr>
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="mb-0 fw-semi-bold">Father Name</h6>
                        </div>
                        <div class="col-auto">
                            <span class="fs-13 fw-semi-bold text-muted"><?php echo $employee_profile_info->father_name;?></span>
                        </div>
                    </div>
                    <hr>
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="mb-0 fw-semi-bold">Mother Name</h6>
                        </div>
                        <div class="col-auto">
                            <span class="fs-13 fw-semi-bold text-muted"><?php echo $employee_profile_info->mother_name;?></span>
                        </div>
                    </div>
                    <hr>
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="mb-0 fw-semi-bold">Nationality</h6>
                        </div>
                        <div class="col-auto">
                            <span class="fs-13 fw-semi-bold text-muted"><?php echo $employee_profile_info->emp_nationality;?></span>
                        </div>
                    </div>
                    

                </div>
            </div>
        </div>

        <div class="col-lg-8">

            <div class="card mb-8">

                <div class="card-header card-h-custom bg-info text-white">
                    <h6 class="fs-17 fw-semi-bold mb-0"><?php echo get_notify('positional_information');?></h6>
                </div>

                <div class="card-body">

                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="mb-0 fw-semi-bold">Designiation</h6>
                        </div>
                        <div class="col-auto">
                            <span class="fs-13 fw-semi-bold text-muted"><?php echo $employee_profile_info->emp_designation;?></span>
                        </div>
                    </div> 
                    <hr>
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="mb-0 fw-semi-bold">Employee Type</h6>
                        </div>
                        <div class="col-auto">
                            <span class="fs-13 fw-semi-bold text-muted"><?php echo $employee_profile_info->emp_type;?></span>
                        </div>
                    </div>
                    <hr>
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="mb-0 fw-semi-bold">Department</h6>
                        </div>
                        <div class="col-auto">
                            <span class="fs-13 fw-semi-bold text-muted"><?php echo $employee_profile_info->dept_name;?></span>
                        </div>
                    </div>
                    <hr>
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="mb-0 fw-semi-bold">Salary Type</h6>
                        </div>
                        <div class="col-auto">
                            <span class="fs-13 fw-semi-bold text-muted"><?php echo $employee_profile_info->salary_type;?></span>
                        </div>
                    </div>
                    <hr>
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="mb-0 fw-semi-bold">Gross Salary</h6>
                        </div>
                        <div class="col-auto">
                            <span class="fs-13 fw-semi-bold text-muted"><?php echo $employee_profile_info->gross_salary;?></span>
                        </div>
                    </div>
                    <hr>
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="mb-0 fw-semi-bold">Work Place</h6>
                        </div>
                        <div class="col-auto">
                            <span class="fs-13 fw-semi-bold text-muted"><?php echo $employee_profile_info->work_place;?></span>
                        </div>
                    </div>
                </div>
            </div>

            <br>

            <div class="card mb-8">

                <div class="card-header card-h-custom bg-info text-white">
                    <h6 class="fs-17 fw-semi-bold mb-0"><?php echo get_notify('contacts');?></h6>
                </div>

                <div class="card-body">

                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="mb-0 fw-semi-bold">Mobile No</h6>
                        </div>
                        <div class="col-auto">
                            <span class="fs-13 fw-semi-bold text-muted"><?php echo $employee_profile_info->mobile_no1;?></span>
                        </div>
                    </div> 
                    <hr>
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="mb-0 fw-semi-bold">Emergency No</h6>
                        </div>
                        <div class="col-auto">
                            <span class="fs-13 fw-semi-bold text-muted"><?php echo $employee_profile_info->mobile_no2;?></span>
                        </div>
                    </div>
                    <hr>
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="mb-0 fw-semi-bold">Present Address</h6>
                        </div>
                        <div class="col-auto">
                            <span class="fs-13 fw-semi-bold text-muted"><?php echo $employee_profile_info->present_address;?></span>
                        </div>
                    </div>
                    <hr>
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="mb-0 fw-semi-bold">Permanent Address</h6>
                        </div>
                        <div class="col-auto">
                            <span class="fs-13 fw-semi-bold text-muted"><?php echo $employee_profile_info->permanent_address;?></span>
                        </div>
                    </div>
                </div>
            </div>

            <br>

        </div>

    </div>

    <!-- Start attachments section -->

    <?php if(count($employee_attachments) > 0){ ?>

    <div class="row mb-2">
        <div class="col-md-12 col-sm-12">
            <div class="card">
                <div class="card-header card-h-custom bg-info text-white">
                    <?php echo get_notify('existing_uploaded_attachments');?>
                </div>
                <div class="card-body">
                    <!-- <p>This is for initial work</p> -->
                    <table class="table table-sm table-stripped w-100" id="emp_attachment_table">
                        <thead>
                            <tr>
                                <th width="20%" class="text-center"><?php echo get_phrases(['attachment','type'])?><i class="text-danger">*</i></th>
                                <th width="30%" class="text-center"><?php echo get_phrases(['attachment'])?><i class="text-danger">*</i></th>
                                <th width="20%" class="text-center"><?php echo get_phrases(['description'])?></th>
                                <th width="20%" class="text-center"><?php echo get_phrases(['date'])?><i class="text-danger">*</i></th>
                                <th width="10%" style="display:none;"><?php echo get_phrases(['action'])?></th>

                            </tr>
                        </thead>
                        <tbody id="emp_attachments_div">

                        <?php if(!empty($employee_attachments)){

                            foreach ($employee_attachments as $key => $row) { ?>

                                <tr>
                                    <td>
                                        <select name="" class="form-control custom-select attachment-select" disabled="true">
                                            <?php foreach($attachment_type_list as $key => $value){?>
                                            <option value="<?php echo $key;?>" <?php if($key == $row->attachment_type){echo "selected";}?>><?php echo $value;?></option>
                                           <?php }?>
                                        </select>
                                    </td>
                                    <td class="text-center">
                                        <p class="btn btn-info"><a class="text-white" href="<?php echo base_url().$row->attachment_path;?>" target="_blank">Click to open the uploaded attachment</a></p>
                                    </td>
                                    <td>
                                        <input type="text" name="" class="form-control text-right" value="<?php echo $row->description;?>" readonly>
                                    </td>
                                    <td>
                                        <input type="text" name="" class="form-control" autocomplete="off" value="<?php echo $row->date?$row->date:'';?>" readonly>
                                    </td>
                                    <td></td>
                                </tr>

                            <?php }

                        }?>
                            
                        </tbody>
                     </table>
                </div>
            </div>
        </div>
    </div>

    <?php } ?>


 </div>

 <script type="text/javascript">
     
     $(document).ready(function() {

        var element = document.getElementById('employee_list_menu');
        element.classList.add('mm-active');

     });

 </script>
