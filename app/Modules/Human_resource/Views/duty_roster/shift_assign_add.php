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

                <?php echo form_open_multipart('human_resources/duty_roster/create_shift_assign');?>

                    <div class="row">
                        <div class="col-sm-12">

                            <div class="form-group row">
                                <label for="select_roster"
                                    class="col-sm-2 col-form-label"><?php echo get_phrases(['select','roster']); ?>
                                    <span class="text-danger">*</span></label>
                                <div class="col-sm-4 customesl pl-0">
                                    <?php echo form_dropdown('roster_id',$roster_list,'', 'class="form-control" required data-live-search="true" id="roster_id"') ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />

                    <input type="hidden" name="rstr_start_date" id="rstr_start_date" value="">
                    <input type="hidden" name="rstr_end_date" id="rstr_end_date" value="">

                    <input type="hidden" name="rstr_start_time" id="rstr_start_time" value="">
                    <input type="hidden" name="rstr_end_time" id="rstr_end_time" value="">
                    
                
                    <div class="col-sm-12" id="employeedatalistshow">
                        


                    </div>
                    <div class="form-group text-right">
                        <button type="submit" id="sbmit"
                            class="btn btn-success w-md m-b-5"><?php echo get_phrases(['add']); ?></button>
                    </div>

                <?php echo form_close();?>

            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    "use strict";


});

//Get all the employess who are assigned for Duty roster from employees table .... based on the selection of roster
$(document).on("change", "#roster_id", function(){
    
    "use strict";
    var roster_id  = $('#roster_id').val();
    // console.log(roster_id);

    var submit_url = _baseURL+"human_resources/duty_roster/empdatashow";
    
    $.ajax({
         type: "POST",
         url: submit_url,
         data:{
            'csrf_stream_name':csrf_val,
            'roster_id':roster_id,
        },
        success: function(data) {

            // console.log(data);

            $('#employeedatalistshow').hide().html(data).fadeIn();
            
        } 
    });

});

// Get the selected roster start and end date... along with it's shift start and end and assign into hidden fileds... in the above form
$(document).on("change", "#roster_id", function(){
        "use strict";
        var roster_id  = $('#roster_id').val();

        var submit_url = _baseURL+"human_resources/duty_roster/rosteDateTimedata";

        $.ajax({
             type: "POST",
             dataType: "json",
             url: submit_url,
             data:{
                'csrf_stream_name':csrf_val,
                'roster_id':roster_id,
            },
            success: function(data) {

                // console.log(data);

                $('#rstr_start_date').val(data.roster_start);
                $('#rstr_end_date').val(data.roster_end);
                $('#rstr_start_time').val(data.shift_start);
                $('#rstr_end_time').val(data.shift_end);
            } 
        });

    });


//End of fetching employees

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

        $("#sbmit").attr("disabled", false);
    } else {
        $("#sbmit").attr("disabled", true);
    }
});
</script>