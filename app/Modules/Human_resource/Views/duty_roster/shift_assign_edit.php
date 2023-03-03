<style>
.room-design {
    text-align: center;
    border: 1px solid #767676;
    padding-top: 20px;
    position: relative;
}

.room-design .form-check-inline {
    margin-right: 0;
}

.room-check {
    position: absolute;
    top: 0;
    right: 6px;
}
</style>

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
                       <a href="javascript:void(0);" onclick="goBackOnePage()" class="btn btn-warning btn-sm mr-1 ml-2"><i class="fas fa-angle-double-left mr-1"></i><?php echo get_phrases(['back']);?></a>
                    </div>
                </div>
            </div>
            <div class="card-body">

                <!-- <?php //echo form_open_multipart('human_resources/duty_roster/create_shift_assign');?> -->

                    <div class="row">
                        <div class="col-sm-12">

                            <div class="form-group row">

                                <label for="select_roster" class="col-sm-2 col-form-label"><?php echo get_phrases(['select','roster']); ?>
                                    <span class="text-danger">*</span>
                                </label>

                                <div class="col-sm-3 customesl pl-0">
                                    <!-- <?php //echo form_dropdown('roster_id',$roster_list,'', 'class="form-control" required data-live-search="true" id="roster_id"') ?> -->

                                    <input type="hidden" name="uproster_id" id="uproster_id" class=" form-control" value="<?php echo $rstasninfo->roster_id?>">
                                    <input type="text" disabled name="roster_idshow" id="roster_id" class=" form-control" value="<?php echo '('.$rstasninfo->roster_start.' - '.$rstasninfo->roster_end.') '.$rstasninfo->shift_name.'-'.$rstasninfo->department_name.' department';?>">

                                </div>

                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="rstr_start_date" id="rstr_start_date" value="<?php echo $rstasninfo->roster_start?>">
                    <input type="hidden" name="rstr_end_date" id="rstr_end_date" value="<?php echo $rstasninfo->roster_end?>">

                    <input type="hidden" name="rstr_start_time" id="rstr_start_time" value="<?php echo $rstasninfo->shift_start?>">
                    <input type="hidden" name="rstr_end_time" id="rstr_end_time" value="<?php echo $rstasninfo->shift_end?>">
                    
                
                    <div class="col-sm-12" id="employeedatalistshow">
                        <div class="col-sm-12 row">
                                
                            <?php 

                                $this->db = db_connect();

                                //$query= $this->db->table('hrm_duty_roster')->select('*')->where('roster_id', $rstasninfo->roster_id)->get()->getRow();

                                $builder3 = $this->db->table('hrm_emproster_assign');
                                $builder3->select("emp_id,roster_id");
                                $builder3->where('emp_startroster_date >=', $rstasninfo->roster_start);
                                $builder3->where('emp_startroster_date <=', $rstasninfo->roster_end);
                                // $builder3->where('roster_id', $rstasninfo->roster_id);
                                $builder3->groupBy('emp_id');

                                $chechedemp = $builder3->get()->getResult();

                                if(!empty($editemp_list)){

                                    $chekbox = 0;
                                    $disable = 0;

                                    foreach($editemp_list as $emp_list){

                                    $disable = 0;
                                    $chekbox = 0;

                                    foreach($chechedemp as $asn_emp_list){
                                        if ($asn_emp_list->emp_id == $emp_list->employee_id && $asn_emp_list->roster_id == $rstasninfo->roster_id) {

                                            $chekbox =1;

                                        }else if($asn_emp_list->emp_id == $emp_list->employee_id){

                                            $disable=1;
                                        }
                                    }
                                        
                            ?>

                            <!-- Material inline 1 -->
                             <div class="col-xl-2 col-lg-6 col-md-6 col-sm-6 pb-2">
                                <div class="room-design">
                                    <input type="checkbox" <?php if($chekbox == 1){echo 'checked';} ?><?php if($disable == 1){echo 'disabled';} ?> class="form-check-input test room-check" name="emp_id[]"
                                      id="empid_<?php echo $emp_list->employee_id;?>"
                                        value="<?php echo $emp_list->employee_id;?>"
                                        
                                        >


                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label" data-toggle="tooltip" data-placement="top"
                                            title="<?php echo $emp_list->first_name.' '. $emp_list->last_name;?>"
                                            for="materialInline"><?php echo $emp_list->first_name.' '. $emp_list->last_name;?></label>
                                    </div>
                                    <p><?php echo $emp_list->type;?></p>
                                </div>
                            </div> 
                            <?php } }?>

                        </div>
                    </div>

                    <!-- <div class="form-group text-right">
                        <button type="submit" id="sbmit"
                            class="btn btn-success w-md m-b-5"><?php echo get_phrases(['add']); ?></button>
                    </div> -->

                <!-- <?php //echo form_close();?> -->

            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    "use strict";
   

});

"use strict";

$('input[type="checkbox"]').click(function(){
        
    var clickedemp =$(this).val();
    
    var roster_id = $('#uproster_id').val();
    var rstr_start_date = $('#rstr_start_date').val();
    var rstr_end_date = $('#rstr_end_date').val();
    var rstr_start_time = $('#rstr_start_time').val();
    var rstr_end_time = $('#rstr_end_time').val();

    // console.log(rstr_end_time);
    
    if($(this).is(":checked")){
        
        var ischeck=1;
        var up_url = _baseURL+"human_resources/duty_roster/update_addSingleEmpRoster";
        var dataString = 'roster_id='+roster_id+'&emp_id='+clickedemp+'&rstr_start_date='+rstr_start_date+
        '&rstr_end_date='+rstr_end_date+'&rstr_start_time='+rstr_start_time+'&rstr_end_time='+rstr_end_time+'&csrf_stream_name='+csrf_val;
    }
    else if($(this).is(":not(:checked)")){
        
        var ischeck=0;
        var up_url = _baseURL+"human_resources/duty_roster/update_romoveSingleEmpRoster";
        var dataString = 'roster_id='+roster_id+'&emp_id='+clickedemp+
        '&rstr_start_date='+rstr_start_date+'&rstr_end_date='+rstr_end_date+'&csrf_stream_name='+csrf_val;
    }

    // console.log(dataString);

    $.ajax({
    type: "POST",
    url: up_url,
    data: dataString,
    success: function(data){

            // console.log(data);

            var res = JSON.parse(data);

            if(ischeck==1){

                toastr.success(res.message, 'Roster Assign');
            }
            else{

                toastr.error(res.message, 'Roster Assign');
            }

        }
    });

});
</script>