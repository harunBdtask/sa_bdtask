
<div class="card-header tab_hotel py-4">
    <ul class="nav nav-pills justify-content-center" id="pills-tab" role="tablist">
    <input type="hidden" id="changedate" value="<?php echo $change_date?>">
             <input type="hidden" id="todaydate" value="<?php echo $cn_date = date("Y-m-d");?>">
             <input type="hidden" id="currentshift" value="<?php if(!empty($cuuentshiftid->shift_id)){echo $cuuentshiftid->shift_id;}?>">
        <?php
        
            //date_default_timezone_set("Asia/Dhaka");

            $this->db = db_connect();

            // $lang_shift_name = '';
            // $empLangColumn = '';

            // if(session('defaultLang')=='english'){

            //     $lang_shift_name = 'shift_nameE';
            //     $empLangColumn = 'nameE';

            // }else{

            //     $empLangColumn = 'nameA';
            //     $lang_shift_name = 'shift_nameA';
            // }

            $current_date =  date("Y-m-d");
            $cr_time =  $change_date;
            $today = $change_date;
           
            if ($cng_shlist) {
                
                $i = 0;
                foreach($cng_shlist as $rstrid_list){

                $cndtsh_id = $this->db->table('hrm_duty_roster')->select('shift_id')->where('roster_id', $rstrid_list->roster_id)->get()->getRow();

                $shdata = $this->db->table('hrm_empwork_shift')->select("hrm_empwork_shift.*,hrm_empwork_shift.shift_name")->where('shiftid', $cndtsh_id->shift_id)->get()->getRow();

                $department = $this->db->table('hrm_departments')->select('name')->where('id', $shdata->department_id)->get()->getRow();
            
        ?>
            <li class="nav-item click-<?php echo $shdata->shiftid?>-curtab">
                <a class="nav-link <?php if($i == 0 ){echo 'active';}else {echo '';}?>"
                    id="pills-<?php echo $shdata->shiftid?>-tab" data-toggle="pill"
                    href="#pills-<?php echo $shdata->shiftid?>" role="tab"
                    onclick="clickedshift(<?php echo $shdata->shiftid?>)" aria-controls="pills-<?php echo $shdata->shiftid?>"
                    aria-selected="true"><?php echo $shdata->shift_name.'-'.$department->name.' department';?><input type="hidden" name="clk_shiftid" id="clk_shiftid_<?php echo $shdata->shiftid?>" value="<?php echo $shdata->shiftid?>"></a>
            </li>
        
        <?php $i++; } }else{
            echo '<b>No Data Found !!</b>';
        }?>
    </ul>
</div>

<div class="card-body" id="empdatashow">
    <div class="tab-content" id="pills-tabContent">
        <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
            <div class="row">
                <?php
                $cr_time =  $change_date;
                if ($cng_emplist) {
                    foreach($cng_emplist as $cng_emp){

                    $emp_details = $this->db->table('hrm_employees')
                    ->select("hrm_employees.*,hrm_employees.image,hrm_employees.first_name,hrm_employees.last_name,hrm_employee_types.type")
                    ->join("hrm_employee_types", "hrm_employee_types.id=hrm_employees.employee_type", "left")
                    ->where('hrm_employees.employee_id', $cng_emp->emp_id)
                    ->get()
                    ->getRow();

                ?>
                <div class="col-md-6 col-lg-4 col-xl-3">
                    <div class="member_inner my-3 d-flex align-items-center rounded">

                    <?php $is_compchk = $this->db->table('hrm_emproster_assign')->select("is_complete, emp_startroster_date")->where('emp_id', $emp_details->employee_id)->where('emp_startroster_date', $change_date)->get()->getRow();?>

                        <div class="<?php if ($is_compchk) { if($is_compchk->is_complete == 1){echo 'status present';}
                            elseif($is_compchk->is_complete == 2){ echo 'status leave';}
                            elseif($change_date < $current_date){
                                if ($is_compchk->is_complete == 3|| $is_compchk->is_complete == 0) {
                                    
                                    echo 'status absent';
                                }
                            }
                        }
                            ?>"></div>
                        <div class="img_wrapper mr-3">
                            <img src="<?php echo base_url(!empty($emp_details->image)?$emp_details->image:'assets/dist/img/manage_employee.png'); ?>" class="img-fluid rounded" alt="">
                        </div>
                        <div class="info_wrapper">
                            <h6 class="member_name"><?php echo $emp_details->first_name.' '.$emp_details->last_name;?></h6>
                            <h6 class="member_position"><?php echo $emp_details->type;?></h6>
                            <?php if($current_date < $change_date){?>
                            <div class="member_info">

                            <input name="url" type="hidden" id="url_<?php echo $emp_details->employee_id; ?>" value="<?php echo base_url("human_resources/duty_roster/updtempshift_frm") ?>" />
                            <a type="button" onclick="edit_shift_info1('<?php echo $emp_details->employee_id; ?>', '<?php echo $change_date?>')" data-target="#exampleModal" class="btn member_shift" data-toggle="tooltip" data-placement="left" title="Update">
                            change shift</a>
                            </div>
                            <?php }?>
                        </div>
                    </div>
                </div>
                <?php  } }?>
                
            </div>
        </div>
           
    </div>
</div>

<div id="edit" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5><?php echo 'Change Shift';?></h5>
                 <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body editinfo">
               
            
    		</div>
     
            </div>
            <div class="modal-footer">

            </div>

        </div>

    </div>
<style>

    .tab_hotel  .nav-link {
        padding: 15px 25px;
    }

    .tab_hotel .nav .nav-link {
        
        padding: 15px 25px;
        color: #868686;
        font-weight: 500;
        font-size: 16px;
        }

    .tab_hotel  .nav-item.show .nav-link,
    .tab_hotel  .nav-link.active {
        
        background-color: #fff;
        box-shadow: 0 0 10px 1px rgb(247 247 247 / 70%);
        border-color: #dee2e6 #dee2e6 #fff;
    }
    .nav-pills .nav-link{
        margin-bottom: -26px;
        border: 1px solid transparent;
        border-top-left-radius: .25rem;
        border-top-right-radius: .25rem;
    }
    .card-header{
        padding: .75rem 1.25rem;
        margin-bottom: 0;
        background-color: #f9f9f9;
        
        border-bottom: 1px solid rgba(0,0,0,.125) !important;
    }

    .img_wrapper img{height:  70px;}
}
    
</style>


<script>

function edit_shift_info1(id, cldate){
'use strict'

   //console.log('id: '+id+' ,cldate: '+cldate);

   var submit_url=$("#url_"+id).val();
   // var submit_url =geturl+'/'+id+'/'+cldate;

    // var dataString = "id="+id;

     $.ajax({
     type: "POST",
     url: submit_url,
     data: {
        csrf_stream_name: csrf_val,
        id: id,
        cldate: cldate,
     },
     success: function(data) {

          // console.log(data);

         $('.editinfo').html(data);
         $('#edit').modal('show');

          // $('select').selectpicker();
          // $('.datepicker').bootstrapMaterialDatePicker({
          //   format: 'YYYY-MM-DD',
          //   shortTime: false,
          //   date: true,
          //   time: false,
          //   monthPicker: false,
          //   year: false,
          //   switchOnClick: true,
          // });

        } 
    });

}

$(document).ready(function() {

    var changedate = $('#changedate').val();
    var todaydate = $('#todaydate').val();
    var currentshift = $('#currentshift').val();
    if (changedate == todaydate) {
        $('.nav-link').removeClass("active");
        $('#pills-'+currentshift+'-tab').addClass("active");
        
    }
})
</script>