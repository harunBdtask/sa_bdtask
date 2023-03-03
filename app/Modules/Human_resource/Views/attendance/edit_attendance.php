<div class="row">
	<div class="col-sm-12">
		<?php if($permission->method('attendance_form', 'update')->access()){ ?>
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
	                       <a href="javascript:void(0);" onclick="goBackOnePage()" class="btn btn-warning btn-sm mr-1 ml-2"><i class="fas fa-angle-double-left mr-1"></i><?php echo get_phrases(['back']) ?></a>
	                    </div>
	                </div>
	            </div>

	            <div class="card-body">

	            	<?php echo form_open_multipart('human_resources/attendances/attendance_update', 'class="ajaxForm needs-validation" id="ajaxForm" novalidate="" data="showCallBackData"');?>

						<input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
						<input type="hidden" name="atten_his_id" id="atten_his_id" value="<?php echo $att_emp->atten_his_id;?>"/>
						<input type="hidden" name="action" id="action" value="edit" />

						<div class="form-group row">
	                        <label for="atten_date" class="col-sm-2 col-form-label"><?php echo get_phrases(['attendance','date']) ?> <i class="text-danger">*</i></label> 
	                        <div class="col-sm-7">
	                            <input name="atten_date" type="text" class="form-control" id="atten_date" placeholder="<?php echo get_phrases(['attendance','date']) ?>" value="<?php echo $att_emp->atten_date;?>" readonly>
	                        </div>
	                    </div>

						<div class="form-group row">
	                        <label for="uid" class="col-sm-2 col-form-label"><?php echo get_phrases(['employee']) ?> <i class="text-danger">*</i></label> 
	                        <div class="col-sm-7">
	                            <?php echo form_dropdown('uid', $employees, $att_emp->employee_id, 'class="form-control select2" id="employee_id" required');?>
	                        </div>
	                    </div>

						<div class="form-group row">
	                        <label for="time" class="col-sm-2 col-form-label"><?php echo get_phrases(['punch','time']) ?> <i class="text-danger">*</i></label> 
	                        <div class="col-sm-7">
	                            <input name="time" type="text" class="form-control onlyattendatetime" id="time" placeholder="<?php echo get_phrases(['punch','time']) ?>" value="<?php echo $att_emp->time;?>" required>
	                        </div>
	                    </div>
	                    
	                    <div class="form-group text-center">
	                        <button type="submit" class="btn btn-success w-md m-b-5 modal_action"><?php echo get_phrases(['save']) ?></button>
	                    </div>

	            	<?php echo form_close();?>
	            	
	            </div>
			</div>
		<?php }else{ ?>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <strong class="fs-20 text-danger"><?php echo get_phrases(['you_do_not_have_permission_to_access_please_contact_with_administrator']);?></strong>
                    </div>
                </div>
            </div>
        </div>
        <?php }?>
	</div>
</div>


<script type="text/javascript">

	var showCallBackData = function () {       
	    $('.ajaxForm').removeClass('was-validated');
	    $('.ajaxForm')[0].reset(); 
	    // $('#employee_id').val('').trigger('change');
	    location.reload();
	}

	$(document).ready(function() { 
	   "use strict";
	   	$('option:first-child').val('').trigger('change');

	   	var element = document.getElementById('attendance_form_menu');
        element.classList.add('mm-active');

	    // search employee
    //     $('#employee_id').select2({
    //         placeholder: '<?php //echo get_phrases(['search', 'employee']);?>',
    //         minimumInputLength: 2,
    //             ajax: {
    //                 url: _baseURL+'auth/searchEmployee',
    //                 dataType: 'json',
    //                 delay: 250,
    //                 processResults: function (data) {
    //                   return {
    //                     results:  $.map(data, function (item) {
    //                           return {
    //                               text: item.text,
    //                               id: item.id
    //                           }
    //                       })
    //                   };
    //                 },
    //                 cache: true
    //            }
    //     });
    //     var newOption = new Option('<?php //echo $att_emp->text;?>', '<?php //echo $att_emp->emp_id;?>', true, true);
  		// $('#employee_id').append(newOption).trigger('change');


  		        //Single DateTime Picker
	    $('.onlyattendatetime').daterangepicker({
	        "autoApply":true,
	        timePicker: true,
	        singleDatePicker:true,
	        timePicker24Hour : true,
	        timePickerIncrement : 5,
	        timePickerSeconds : true,
	        locale : {
	            format : 'YYYY-MM-DD HH:mm:ss'
	        }

	    }).on("change", function (e, picker) {

	    	// var atten_date = $('#atten_date').val();
	    	var atten_his_id= $('#atten_his_id').val();
	    	var employee_id = $('#employee_id').val();
	    	var time 		= $('#time').val();

	    	if(employee_id == "" || atten_date == ""){

	    		var msg = "Please select attendance date and employee first";
	    		var title = "Attendnace";

	    		// $('#time').val("");
	    		toastr.warning(msg, title);

	    	}else{

	    		var submit_url = _baseURL+"human_resources/attendances/upPunchTimeUnderTwoRemainingTime";
	    		console.log(submit_url);

	            $.ajax({
	                type: 'POST',
	                url: submit_url,
	                data: {'csrf_stream_name':csrf_val,'time':time,'uid':employee_id,'atten_his_id':atten_his_id},
	                dataType: 'JSON',
	                success: function(response) {

	                    console.log(response);

	                    if(response.success==false){

	                    	$('#time').val(response.time);
                            toastr.error(response.message, response.title);

                        }
                        if(response.success=="exist"){

                            toastr.warning(response.message, response.title);
                        }
	                }
	            }); 
	    		
	    	}
	    	
        });


  		//Single Date Picker
	    $('.onlyattendate').daterangepicker({
	        singleDatePicker: true,
	        locale : {
	            format : 'YYYY-MM-DD'
	        }
	    });

	});

</script>