
<div class="row">
     <div class="col-md-12 col-lg-12">
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
                       <?php if($permission->method('employee_list','read')->access()){?>  
                       <a href="<?php echo base_url('human_resources/employees/employee_list')?>" class="btn btn-success btn-sm mr-1"><i class="fas fa-align-justify mr-1"></i><?php echo get_phrases(['employee','list'])?></a>
                     <?php }?>
                      
                    </div>
                </div>
            </div>

         <div class="card-body">


            <?php echo form_open_multipart("human_resources/employees/create_employee/", 'class="needs-validation" id="employeeForm" novalidate="" data="jobCallBackData"') ?>
          
                <div class="form-group row">
                   
                    <label for="firstname" class="col-md-2 text-right col-form-label"><?php echo get_phrases(['first','name'])?> <i class="text-danger"> * </i>:</label>

                    <div class="col-md-4">
                        <div class="">
                         <input type="text" name="firstname" class="form-control" id="firstname" placeholder="<?php echo get_phrases(['first','name'])?>" value="" required>
                        </div>
                    </div>
            
                    <label for="lastname" class="col-md-2 text-right col-form-label"><?php echo get_phrases(['last','name'])?> <i class="text-danger"> * </i>:</label>
                    <div class="col-md-4">
                        <div class="">
                            <input type="text" name="lastname" class="form-control" id="lastname" placeholder="<?php echo get_phrases(['last','name'])?>" value="" required>
                        </div>
                       
                    </div>
                   
                </div>

                <div class="form-group row">
                   
                    <label for="firstname" class="col-md-2 text-right col-form-label"><?php echo get_phrases(['bangla','name'])?> <i class="text-danger"> * </i>:</label>
                    
                    <div class="col-md-4">
                        <div class="">
                         <input type="text" name="banglaname" class="form-control" id="banglaname" placeholder="<?php echo get_phrases(['bangla','name'])?>" value="" required>
                        </div>
                    </div>
            
                    <label for="lastname" class="col-md-2 text-right col-form-label"><?php echo get_phrases(['designation'])?> <i class="text-danger"> * </i>:</label>
                    <div class="col-md-4">
                        <div class="">
                            <?php echo  form_dropdown('designation',$designation_list,'', 'class="form-control custom-select select2" required') ?>
                        </div>
                    </div>
                   
                </div>

                <div class="form-group row">
                   
                    <label for="father_name" class="col-md-2 text-right col-form-label"><?php echo get_phrases(['father','name'])?> <i class="text-danger"> * </i>:</label>

                    <div class="col-md-4">
                        <div class="">
                         <input type="text" name="father_name" class="form-control" id="father_name" placeholder="<?php echo get_phrases(['father','name'])?>" value="" required>
                        </div>
                    </div>
            
                    <label for="mother_name" class="col-md-2 text-right col-form-label"><?php echo get_phrases(['mother','name'])?> <i class="text-danger"> * </i>:</label>
                    <div class="col-md-4">
                        <div class="">
                            <input type="text" name="mother_name" class="form-control" id="mother_name" placeholder="<?php echo get_phrases(['mother','name'])?>" value="" required>
                        </div>
                       
                    </div>
                   
                </div>

                 <div class="form-group row">

                    <label for="designation" class="col-md-2 text-right col-form-label"><?php echo get_phrases(['employee','type'])?> <i class="text-danger"> * </i>:</label>
                    <div class="col-md-4">
                        <div class="">
                           
                            <?php echo  form_dropdown('employee_type',$employee_types,'', 'class="form-control custom-select select2" required') ?>

                        </div>
                       
                    </div>

                     <label for="designation" class="col-md-2 text-right col-form-label"><?php echo get_phrases(['department'])?> <i class="text-danger"> * </i>:</label>
                    <div class="col-md-4">
                        <div class="">
                            
                            <?php echo  form_dropdown('department',$departments_list,'', 'class="form-control custom-select select2" required') ?>

                        </div>
                       
                    </div>
                  
                </div>

                <div class="form-group row">

                    <label for="salary_type" class="col-md-2 text-right col-form-label"><?php echo get_phrases(['salary','type'])?><i class="text-danger"> * </i>:</label>
                    <div class="col-md-4">
                        <div class="">
                            <select class="form-control custom-select select2" name="salary_type" id="salary_type" required>
                              <option value="">Select Option</option>}
                              <option value="hourly"><?php echo get_phrases(['hourly'])?></option>
                              <option value="salary"><?php echo get_phrases(['salary'])?></option>
                            </select>
                        </div>
                       
                    </div>
                   
                    <label for="gross_salary" class="col-md-2 text-right col-form-label"><?php echo get_phrases(['gross','salary'])?> <i class="text-danger"> * </i>:</label>
                    <div class="col-md-4">
                        <div class="">
                            
                         <input type="text" class="form-control onlyNumber" name="gross_salary" id="gross_salary" placeholder="<?php echo get_phrases(['gross','salary'])?>" required value="">    

                        </div>
                       
                    </div>
                   
                </div>

                <div class="form-group row">
                    <label for="educational_qualification" class="col-md-2 text-right col-form-label"><?php echo get_phrases(['educational','qualification'])?><i class="text-danger"> * </i>:</label>
                    <div class="col-md-4">
                        <div class="">
                            <input type="text" class="form-control" name="educational_qualification" id="educational_qualification" placeholder="<?php echo get_phrases(['educational','qualification'])?>" required value="">  
                        </div>
                       
                    </div>
                   
                    <label for="work_place" class="col-md-2 text-right col-form-label"><?php echo get_phrases(['work','place'])?> <i class="text-danger"> * </i>:</label>
                    <div class="col-md-4">
                        <div class="">
                            
                         <input type="text" class="form-control" name="work_place" id="work_place" placeholder="<?php echo get_phrases(['work','place'])?>" required value="">    

                        </div>
                       
                    </div>
                   
                </div>
     
                <div class="form-group row">
                   
                    <label for="email" class="col-md-2 text-right col-form-label"><?php echo get_phrases(['email'])?><i class="text-danger"> * </i>:</label>
                    <div class="col-md-4">
                        <div class="">
                            
                         <input type="email" class="form-control" name="email" id="email" placeholder="<?php echo get_phrases(['email'])?>"  required value="">    

                        </div>
                       
                    </div>

                    <label for="nationality" class="col-md-2 text-right col-form-label"><?php echo get_phrases(['nationality'])?> <i class="text-danger"> * </i>:</label>
                    <div class="col-md-4">
                        <div class="">
                            
                         <!-- <input type="text" class="form-control" name="country" id="country" placeholder="<?php //echo get_phrases(['country'])?>" value="">   -->

                         <select class="form-control custom-select select2" name="nationality" id="nationality" required>

                          <option value="">Select Option</option>
                          <?php foreach ($country_list as $key => $value) {?>
                            <option value="<?php echo $key;?>" <?php if(strtolower($value) == 'bangladesh'){echo "selected";}?>><?php echo $value;?></option>
                          <?php }?>

                        </select>  

                        </div>
                       
                    </div>
                   
                </div>

               <div class="form-group row">
                    <label for="city" class="col-md-2 text-right col-form-label"><?php echo get_phrases(['city'])?>:</label>
                    <div class="col-md-4">
                        <div class="">
                            
                          <input class="form-control" id="city" type="text" name="city" placeholder="<?php echo get_phrases(['city'])?>" value="">

                        </div>
                       
                    </div>

                   <label for="zip_code" class="col-md-2 text-right col-form-label"><?php echo get_phrases(['zip','code'])?>:</label>
                    <div class="col-md-4">
                        <div class="">
                           
                            <input type="text" name="zip_code" class="form-control" id="zip_code" placeholder="<?php echo get_phrases(['zip','code'])?>"  value="">

                        </div>
                       
                    </div>

                </div>

                <div class="form-group row">

                    <label for="birth_date" class="col-md-2 text-right col-form-label"><?php echo get_phrases(['birth','date'])?>  <i class="text-danger"> * </i>:</label>
                    <div class="col-md-4">
                        <div class="">
                            
                          <input class="form-control birth_datepicker" id="birth_date" type="text" name="birth_date" autocomplete="off" placeholder="<?php echo get_phrases(['birth','date'])?>" value="" required>

                        </div>
                       
                    </div>

                    <label for="joining_date" class="col-md-2 text-right col-form-label"><?php echo get_phrases(['joining','date'])?>  <i class="text-danger"> * </i>:</label>
                    <div class="col-md-4">
                        <div class="">
                            
                          <input class="form-control joining_datepicker" id="joining_date" type="text" name="joining_date" autocomplete="off" placeholder="<?php echo get_phrases(['joining','date'])?>" value="" required>

                        </div>
                       
                    </div>

                </div>


               <div class="form-group row">

                    <label for="mobile_no1" class="col-md-2 text-right col-form-label"><?php echo get_phrases(['mobile','no','1'])?> <i class="text-danger"> * </i>:</label>
                    <div class="col-md-4">
                        <div class="">
                            
                          <input class="form-control onlyNumber" id="mobile_no1" type="text" name="mobile_no1" placeholder="<?php echo get_phrases(['mobile','no','1'])?>" value="" required>

                        </div>
                       
                    </div>

                    <label for="mobile_no2" class="col-md-2 text-right col-form-label"><?php echo get_phrases(['emergency','no'])?> <i class="text-danger"> * </i>:</label>
                    <div class="col-md-4">
                        <div class="">
                            
                          <input class="form-control onlyNumber" id="mobile_no2" type="text" name="mobile_no2" placeholder="<?php echo get_phrases(['emergency','no'])?>" value="" required>

                        </div>
                       
                    </div>
                    
                </div>

                <div class="form-group row"> 
              

                    <label for="present_address" class="col-md-2 text-right col-form-label"><?php echo get_phrases(['present','Address'])?><i class="text-danger"> * </i>:</label>
                    <div class="col-md-4">
                        <div class="">
                            
                           <textarea name="present_address" id="present_address" class="form-control" required></textarea>

                        </div>
                    </div>

                    <label for="permanent_address" class="col-md-2 text-right col-form-label"><?php echo get_phrases(['permanent','address'])?><i class="text-danger">  </i>:</label>
                    <div class="col-md-4">
                        <div class="">
                            
                         <textarea name="permanent_address" id="permanent_address" class="form-control"></textarea>

                        </div>
                       
                    </div>
                  
                </div>

                <div class="form-group row">
            
                    <label for="nid_no" class="col-md-2 text-right col-form-label"><?php echo get_phrases(['nid','no'])?> <i class="text-danger"> * </i>:</label>
                    <div class="col-md-4">
                        <div class="">
                            <input type="text" name="nid_no" class="form-control" id="nid_no" placeholder="<?php echo get_phrases(['nid','no'])?>"  value="" required>
                        </div>
                      
                    </div>

                    <label for="gender" class="col-md-2 text-right col-form-label"><?php echo get_phrases(['gender'])?> <i class="text-danger"> * </i>:</label>
                    <div class="col-md-4">
                        <div class="">
                           
                            <?php 
                             $gender = array('' => '', 'Male'=> get_phrases(['male']), 'Female'=> get_phrases(['female']));
                             echo form_dropdown('gender', $gender, '', 'class="form-control custom-select" id="gender" required');?>

                        </div>
                    </div>

                </div> 

                <div class="form-group row">
            
                    <label for="nid_file" class="col-md-2 text-right col-form-label"><?php echo get_phrases(['nid','file'])?>:</label>
                    <div class="col-md-4">
                        <div class="">
                           
                            <input name="nid_file" type="file" class="form-control" id="nid_file" placeholder="<?php echo get_phrases(['nid','file'])?>" value="">

                        </div>
                       
                    </div>

                    <label for="image" class="col-md-2 text-right col-form-label"><?php echo get_phrases(['image'])?> <i class="text-danger"> * </i>:</label>
                    <div class="col-md-4">
                        <div class="">
                           
                            <input name="image" type="file" class="form-control" id="image" placeholder="<?php echo get_phrases(['image'])?>" value="" required>

                        </div>
                       
                    </div>

                </div> 

                <div class="form-group row">
                    
                    <label for="nid_file" class="col-md-2 text-right col-form-label"><?php echo get_phrases(['signature','file'])?>:</label>
                    <div class="col-md-4">
                        <div class="">
                           
                            <input name="signature_file" type="file" class="form-control" id="signature_file" placeholder="<?php echo get_phrases(['signature','file'])?>" value="">

                        </div>
                       
                    </div>

                    <label for="preview" class="col-md-2 text-right col-form-label"><?php echo get_phrases(['preview'])?>:</label>    
                    <div class="col-md-4">
                        <div class="">
                            
                          <img id="blah" class="img-thambnail" src="<?php echo base_url('assets/dist/img/employee/employee.png');?>" alt="your image" height="70px" width="70px;" />

                        </div>
                       
                    </div>

                </div>

                <div class="form-group row">

                    <label for="in_duty_roster" class="col-md-2 text-right col-form-label"></label>
                    <div class="col-md-4">

                       <div class="form-check">
                          <input name="in_duty_roster" type="checkbox" class="form-check-input" value="0" id="in_duty_roster" onchange='indutyroster(this);'>
                          <label class="form-check-label" for="in_duty_roster">
                            <?php echo get_phrases(['in', 'duty', 'roster']);?>
                          </label>
                        </div>
                       
                    </div>

                    <label for="status" class="col-md-2 text-right col-form-label"><?php echo get_phrases(['status'])?> <i class="text-danger"> * </i>:</label>
                    <div class="col-md-4">
                        <div class="">
                           
                           <label class="radio-inline my-2">
                                <?php echo form_radio('status', '1',true, 'id="status" required'); ?>Active
                            </label>
                            <label class="radio-inline my-2">
                                <?php echo form_radio('status', '0', '' , 'id="status" required'); ?>Inactive
                            </label> 

                        </div>
                       
                    </div>
                </div>

                <div class="form-group row">

                    <label for="blood_group" class="col-md-2 text-right col-form-label"><?php echo get_phrases(['blood','group'])?><i class="text-danger"> * </i>:</label>
                    <div class="col-md-4">
                        <div class="">
                            <input type="text" name="blood_group" class="form-control" id="blood_group" placeholder="<?php echo get_phrases(['blood','group'])?>" required value="">
                        </div>
                      
                    </div>

                    <label for="superior" class="col-md-2 text-right col-form-label"><?php echo get_phrases(['superior'])?><i class="<?php if(count($employee_list) > 1){echo 'text-danger';}?>"> * </i>:</label>
                    <div class="col-md-4">
                        <div class="">
                            <select class="form-control custom-select select2" name="superior" id="superior" <?php if(count($employee_list) > 1){echo "required";}?>>

                              <option value="">Select Option</option>
                              <?php foreach ($employee_list as $key => $value) {?>
                                <option value="<?php echo $key;?>"><?php echo $value;?></option>
                              <?php }?>

                            </select>
                        </div>
                       
                    </div>
                    
                </div>

                <div class="form-group row">

                    <label for="branch" class="col-md-2 text-right col-form-label"><?php echo get_phrases(['branch'])?><i class="text-danger"> * </i>:</label>
                    <div class="col-md-4">
                        <div class="">
                            <select class="form-control custom-select select2" name="branch" id="branch" required>

                              <option value="">Select Option</option>
                              <?php foreach ($branch_list as $key => $value) {?>
                                <option value="<?php echo $key;?>"><?php echo $value;?></option>
                              <?php }?>

                            </select>
                        </div>
                       
                    </div>
                    
                </div>

                <!-- Start attachments section -->

                <div class="row mb-2">

                    <div class="col-md-12 col-sm-12">
                            
                        <p>
                            <button class="btn btn-success dAppS" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                          <i class="fa"></i> <?php echo get_phrases(['add', 'attachments']);?>
                            </button>
                        </p>

                        <div class="collapse" id="collapseExample">
                            
                            <div class="card">
                                
                                <div class="card-header card-h-custom bg-info text-white">
                                    <?php echo get_notify('add_all_types_of_attachments');?><b>(only jpg, jpeg, png, gif, pdf files are allowd)</b>
                                </div>

                                <div class="card-body">
                                    
                                    <!-- <p>This is for initial work</p> -->

                                    <table class="table table-sm table-stripped w-100" id="attachment_table">
                                        <thead>
                                            <tr>
                                                <th colspan="5" class="text-right">
                                                    <button type="button" class="btn btn-success addRow" ><i class="fa fa-plus"></i></button>
                                                </th>
                                            </tr> 
                                            <tr>
                                                <th width="20%" class="text-center"><?php echo get_phrases(['attachment','type'])?><i class="text-danger">*</i></th>
                                                <th width="30%" class="text-center"><?php echo get_phrases(['attachment'])?><i class="text-danger">*</i></th>
                                                <th width="20%" class="text-center"><?php echo get_phrases(['description'])?></th>
                                                <th width="20%" class="text-center"><?php echo get_phrases(['date'])?><i class="text-danger">*</i></th>
                                                <th width="10%" style="display:none;"><?php echo get_phrases(['action'])?></th>

                                            </tr>
                                        </thead>
                                        <tbody id="attachment_div">
                                            
                                        </tbody>

                                     </table>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- End attachments section -->
    
                <div class="form-group row">
                   <div class="col-md-6 text-right">
                   </div>
                    <div class="col-md-6 text-right">
                        <div class="">
                           
                            <button type="submit"  class="btn btn-success"><?php echo (empty($m_id)?get_phrases(['save']):get_phrases(['update'])) ?></button>

                        </div>
                       
                    </div>
                </div>

            <?php echo form_close();?>

            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

    var jobCallBackData = function () { 

        $('#employeeForm')[0].reset();       
        $('#employeeForm').removeClass('was-validated');

        // Load page after 5 seconds
        setTimeout(function(){

            location.reload();

        }, 5000);
    }

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#blah').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]); // convert to base64 string
        }
    }

    $("#image").change(function() {
        readURL(this);
    });

    

    $(document).ready(function() { 
        "use strict";

        // Getting date using daterangepicker and also setting auto start date as blank using autoUpdateInput: false ans showing the selected date using the callback function ... on("apply.daterangepicker", function (e, picker){}
        
        //Single Date Picker with month and year selections
        $('.joining_datepicker').daterangepicker({
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
        });

        //Single Date Picker with month and year selections
        $('.birth_datepicker').daterangepicker({
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
        });

        // For first row ////////////////////////////////////////////
        // var html = '<tr><td><select name="attachment_type[]" class="form-control custom-select attachment-select"><option value="">Select</option>'+<?php foreach($attachment_type_list as $key => $value){?>
        //     '<option value="<?php echo $key;?>"><?php echo $value;?></option>'+
        //    <?php }?>
        //    '</select></td><td><input type="file" name="file[]" class="form-control text-right" multiple=""></td>'+

        //    '<td><input type="text" name="description[]" class="form-control text-right"></td>'+

        //    '<td><input type="text" name="date[]" class="form-control new_datepicker" autocomplete="off"></td>'+

        //    '<td><button type="button" class="btn btn-danger removeRow" ><i class="fa fa-minus"></i></button></td></tr>';

        //     $("#attachment_div").html(html); 
        //     $('#attachment_div select').select2({
        //         placeholder: '<?php echo get_phrases(['select','attachment','type']);?>'                
        //     });

        // For adding new row ////////////////////////////////////////////
        $('body').on('click', '.addRow', function() {

            // Hirarchy departments add
            var html = '<tr><td><select name="attachment_type[]" class="form-control custom-select attachment-select"><option value="">Select</option>'+<?php foreach($attachment_type_list as $key => $value){?>
            '<option value="<?php echo $key;?>"><?php echo $value;?></option>'+
           <?php }?>
           '</select></td><td><input type="file" name="file[]" class="form-control text-right" multiple=""></td>'+

           '<td><input type="text" name="description[]" class="form-control text-right"></td>'+

           '<td><input type="text" name="date[]" class="form-control new_datepicker_change" autocomplete="off"></td>'+

           '<td><button type="button" class="btn btn-danger removeRow" ><i class="fa fa-minus"></i></button></td></tr>';

            $("#attachment_div").append(html); 
            $('#attachment_div select').select2({
                placeholder: '<?php echo get_phrases(['select','attachment','type']);?>'                
            });

            //Single Date Picker with month and year selections
            $('.new_datepicker_change').daterangepicker({
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
            });

        });

        // For deleting new row ////////////////////////////////////////////
        $('body').on('click', '.removeRow', function() {

            $(this).parent().parent().remove();

            // var rowCount = $('#attachment_table >tbody >tr').length;

            // if(rowCount > 1){
            //     $(this).parent().parent().remove();
            // }else{
            //     toastr.warning('<?php echo get_notify("There_only_one_row_you_can_not_delete."); ?>');
            // }

        });


        //Single Date Picker with month and year selections
        $('.new_datepicker').daterangepicker({
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
        });

    });

    "use strict"; 
     function indutyroster(checkbox){

        if(checkbox.checked==true){

         document.getElementById('in_duty_roster').value = 1;

        }else{

          document.getElementById('in_duty_roster').value = 0;

        }   
     }

</script>

                 
                     

                 