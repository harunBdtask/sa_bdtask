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
<div class="col-sm-12 row">
    <?php 

        $this->db = db_connect();

        $builder3 = $this->db->table('hrm_emproster_assign');
        // $builder3->select("*");
        $builder3->select("emp_id,roster_id");
        $builder3->where('emp_startroster_date >=', $rstrt_date);
        $builder3->where('emp_endroster_date <=', $rend_date);
        // $builder3->where('roster_id', $roster_id);
        $builder3->groupBy('emp_id');

        $chechedemp = $builder3->get()->getResult();

        // print_r($chechedemp);
        // echo $roster_id.'  roster_id';
        // echo $rstrt_date.'  rstrt_date';
        // echo $rend_date.'  rend_date';

        if(!empty($emp_list)){
            $i = 1;
            foreach($emp_list as $emp_list){
            $disable = 0;
            foreach($chechedemp as $asn_emp_list){
                if ($asn_emp_list->emp_id == $emp_list->employee_id) {

                    $disable=1;
                }
            }
                
    ?>
    <!-- Material inline 1 -->
    <div class="col-xl-2 col-lg-6 col-md-6 col-sm-6 pb-2">
    <input type="hidden" name="chkempids" id="chkempids<?php echo $i;?>" value="<?php echo $emp_list->employee_id;?>">
        <div class="room-design">
            <input type="checkbox" <?php if($disable == 1){echo 'disabled';} ?> class="form-check-input test room-check" name="emp_id[]"
                id="empid_<?php echo $emp_list->employee_id;?>"
                value="<?php echo $emp_list->employee_id;?>">


            <div class="form-check form-check-inline">
                <label class="form-check-label" data-toggle="tooltip" data-placement="top"
                    title="<?php echo $emp_list->first_name.' '. $emp_list->last_name;?>"
                    for="materialInline"><?php echo $emp_list->first_name.' '. $emp_list->last_name;?></label>
            </div>
            <p><?php echo $emp_list->type;?></p>
        </div>
    </div>
    <?php $i++; } }?>
</div>

<script>

"use strict";
$("#sbmit").attr("hidden", true);
var i = 0;
$("input[type='checkbox']").on("change", function() {
    
    if (this.checked) {
        i++;
        
    } else {
        i--;
    }
    if (i > 0) {
        
         $("#sbmit").attr("hidden", false);
    } else {
        $("#sbmit").attr("hidden", true);
    }
});


</script>

