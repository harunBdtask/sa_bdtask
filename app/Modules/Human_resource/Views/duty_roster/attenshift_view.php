<div class="card">
    <div class="card-header tab_hotel ">
        <ul class="nav nav-pills justify-content-center" id="pills-tab" role="tablist">
            <?php

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

                $cr_time =  date("Y-m-d H:i");
                $today = date('Y-m-d');
                if ($cur_shlist) {
                    
                foreach($cur_shlist as $cush_list){

                    $shdata = $this->db->table('hrm_empwork_shift')->select("hrm_empwork_shift.*,hrm_empwork_shift.shift_name")->where('shiftid', $cush_list->shift_id)->get()->getRow();

                    $rosterdate = $this->db->table('hrm_emproster_assign')->select('emp_startroster_date')->where('emp_startroster_date', $today)->get()->getRow();

                    $department = $this->db->table('hrm_departments')->select('name')->where('id', $shdata->department_id)->get()->getRow();
                      
                    $dstart = $rosterdate->emp_startroster_date.' '.$shdata->shift_start;
                    $dend   = $rosterdate->emp_startroster_date.' '.$shdata->shift_end;
                    
                    $curent = DateTime::createFromFormat('Y-m-d H:i', $cr_time);
                    $start  = DateTime::createFromFormat('Y-m-d H:i', $dstart);
                    $end    = DateTime::createFromFormat('Y-m-d H:i', $dend);
                    
                    ?>
                    
                    <li class="nav-item">
                        <a class="nav-link <?php if($start <= $curent && $curent <= $end ){echo 'active';}else {echo '';}?>"
                            id="pills-<?php echo $shdata->shiftid?>-tab" data-toggle="pill"
                            href="#pills-<?php echo $shdata->shiftid?>" role="tab"
                            onclick="clickedshift(<?php echo $shdata->shiftid?>)" aria-controls="pills-<?php echo $shdata->shiftid?>"
                            aria-selected="true"><?php echo $shdata->shift_name.'-'.$department->name.' department';?><input type="hidden" name="clk_shiftid" id="clk_shiftid_<?php echo $shdata->shiftid?>" value="<?php echo $shdata->shiftid?>"></a>
                    </li>

            <?php } }?>
        </ul>
    </div>

    <div class="card-body" id="empdatashow">
        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                <div class="row">
                    <?php

                    $cr_time =  date("Y-m-d H:i");
                    $cr_date =  date("Y-m-d");
                    if ($cur_emplist) {
                    foreach($cur_emplist as $current_emp){
                    ?>
                     <div class="col-md-6 col-lg-4 col-xl-3">
                        <div class="member_inner my-3 d-flex align-items-center rounded">

                            <?php $is_compchk = $this->db->table('hrm_emproster_assign')->select('is_complete, emp_startroster_date')->where('emp_id', $current_emp->emp_id)->where('emp_startroster_date', $today)->get()->getRow(); ?>

                            <div class="<?php if($is_compchk->is_complete == 1){echo 'status present';}
                            elseif($is_compchk->is_complete == 2){ echo 'status leave';}
                            elseif($is_compchk->is_complete == 3 && $is_compchk->emp_startroster_date <= $cr_date){ echo 'status absent';}
                            else{echo '';}?>"></div>
                            <div class="img_wrapper mr-3">
                                <img src="<?php echo base_url(!empty($current_emp->image)?$current_emp->image:'assets/dist/img/manage_employee.png'); ?>" class="img-fluid rounded" alt="">
                            </div>
                            <div class="info_wrapper">
                                <h6 class="member_name"><?php echo $current_emp->first_name.' '.$current_emp->last_name?></h6>
                                <h6 class="member_position"><?php echo $current_emp->type?></h6>
                                
                            </div>
                        </div>
                    </div> 
                    <?php  } 
                 }else {
                    
                    echo '<strong style="margin-left: 700px;">No Data Found !!</strong>';
                    
                 }?>
                    
                </div>
            </div>
            
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
        margin-bottom: -14px;
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


function clickedshift(id) {
    "use strict";

    $('#pills-tabContent').hide();

    var cngedate 	   = $('#changedrsdate').val();
    var date    = new Date(cngedate),
    yr      = date.getFullYear(),
    newmonth   = date.getMonth() + 1,
    month   = newmonth < 10 ? '0' + newmonth : newmonth,
    day     = date.getDate()  < 10 ? '0' + date.getDate()  : date.getDate(),
    newDate = yr + '-' + month + '-' + day;

    var clk_shiftid = $('#clk_shiftid_'+id).val();

    var submit_url = _baseURL+"human_resources/duty_roster/load_clkshftemp";
    
    $.ajax({

        type: "POST",
        url: submit_url,
        data: {
            csrf_stream_name: csrf_val,
            clk_shiftid: clk_shiftid,
            clickdate: newDate,
        },
        success: function(data) {

            $('#empdatashow').hide().html(data).fadeIn();

        }
    });

}
</script>