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

// $edempdata =  $this->db->select('*')->from('tbl_emproster_assign')->where('emp_startroster_date', $clk_date)->where('emp_id', $emp_id)->get()->row();////////
$edempdata = $this->db->table('hrm_emproster_assign')->select('*')->where('emp_startroster_date', $clk_date)->where('emp_id', $emp_id)->get()->getRow();

// $edrsentry_id =  $this->db->select('rostentry_id, shift_id')->from('tbl_duty_roster')->where('roster_id', $edempdata->roster_id)->get()->row();///////////
$edrsentry_id = $this->db->table('hrm_duty_roster')->select('rostentry_id, shift_id')->where('roster_id', $edempdata->roster_id)->get()->getRow();

// $eshiftdata =  $this->db->select('tbl_empwork_shift.*')
// ->join('tbl_empwork_shift','tbl_empwork_shift.shiftid=tbl_duty_roster.shift_id', 'left')
// ->from('tbl_duty_roster')
// ->where('tbl_duty_roster.rostentry_id', $edrsentry_id->rostentry_id)->get()->result();//////////////////////////////

$eshiftdata = $this->db->table('hrm_duty_roster')
->select("hrm_empwork_shift.*,hrm_empwork_shift.shift_name,hrm_departments.name as department_name")
->join("hrm_empwork_shift", "hrm_empwork_shift.shiftid=hrm_duty_roster.shift_id", "left")
->join("hrm_departments", "hrm_departments.id=hrm_duty_roster.department_id", "left")
->where('hrm_duty_roster.rostentry_id', $edrsentry_id->rostentry_id)
->get()
->getResult();

?>
<?php echo  form_open('human_resources/duty_roster/emp_shift_update') ?>
<ul class="pl-0 mb-0">
    <?php
    if($eshiftdata){
        $i=1;
     foreach($eshiftdata as $eshiftdata_list){?>
    <li class="radio">
        <input type="radio" onclick="checkdshift('<?php echo $i?>')" name="shiftchk" id="ch<?php echo $i?>"
            value="<?php echo $eshiftdata_list->shiftid?>"
            <?php if($edrsentry_id->shift_id == $eshiftdata_list->shiftid){echo 'checked';}else{echo '';}?>>
        <label for="ch<?php echo $i?>"><?php echo $eshiftdata_list->shift_name.' '. $eshiftdata_list->department_name.' department';?></label>
    </li>
    <?php $i++; }}?>
</ul>

<input type="hidden" name="sftasnid" id="sftasnid" value="<?php echo $edempdata->sftasnid;?>">
<input type="hidden" name="roster_id" id="roster_id" value="">
<input type="hidden" name="emp_id" id="emp_id" value="<?php echo $edempdata->emp_id;?>">
<input type="hidden" name="emp_startroster_date"  id="emp_startroster_date" value="<?php echo $edempdata->emp_startroster_date;?>">
<input type="hidden" name="emp_endroster_date"  id="emp_endroster_date" value="<?php echo $edempdata->emp_endroster_date;?>">
<input type="hidden" name="emp_startroster_time"  id="emp_startroster_time" value="">
<input type="hidden" name="emp_endroster_time" id="emp_endroster_time" value="">
<div class="text-right">
    <button type="button" class="btn btn-sm px-3 btn-secondary" data-dismiss="modal">Close</button>
    <button type="submit" class="btn btn-sm px-3 btn-primary">Save changes</button>
</div>
<?php echo form_close() ?>

<style>
    .radio {
        display: grid;
        grid-template-columns: auto 1fr;
        gap: 1em;
    }

    input[type="radio"] {
        width: 1.25em;
        height: 1.25em;
    }

    input[type="radio"]:checked {
        background-image: url("data:image/svg+xml,%3Csvg height='32' width='32' viewBox='0 0 32 32' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='m11.4 21.5-5.93-5.93-2.01 2.01 7.94 7.94 17.1-17.1-2.01-2.01z' fill='%23fff'/%3E%3C/svg%3E");
        background-size: 80% 80%;
        background-position: center;
        background-repeat: no-repeat;
        background-color: #245884;
        border-color: #245884;
    }
</style>

<script>
function checkdshift(id) {
    "use strict";

    var base = $('#base_url').val();
    var csrf = $('#csrf_token').val();

    var chksh_id = $('input[name="shiftchk"]:checked').val();
    // $('#sdf').html(chsh_id);



    $.ajax({
        type: "POST",
        dataType: "json",
        url: _baseURL + "human_resources/duty_roster/load_checkedshift",
        data: {
            csrf_stream_name: csrf_val,
            chksh_id: chksh_id,
        },
        success: function(data) {

            // console.log(data);

            $('#emp_startroster_time').val(data.shift_start).trigger('change');
            $('#emp_endroster_time').val(data.shift_end).trigger('change');

        }
    });

}

$('#emp_startroster_time').change(function(){

    "use strict";
    
    var base = $('#base_url').val();
    var csrf = $('#csrf_token').val();

    var cng_date = $('#emp_startroster_date').val();
    var chksh_id = $('input[name="shiftchk"]:checked').val();
    // $('#sdf').html(chsh_id);



    $.ajax({
        type: "POST",
        dataType: "json",
        url: _baseURL + "human_resources/duty_roster/load_checkedroster",
        data: {
            csrf_stream_name: csrf_val,
            chksh_id: chksh_id,
            cng_date: cng_date,
        },
        success: function(data) {

             // console.log(data);
            
              $('#roster_id').val(data.roster_id);

            //////////////// $('#emp_endroster_time').val(data.shift_end);

        }
    });

});
</script>