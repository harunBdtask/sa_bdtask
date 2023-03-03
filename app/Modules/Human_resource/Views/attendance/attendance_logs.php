<link href="<?php echo base_url()?>/assets/plugins/jquery-ui-1.12.1/jquery-ui.min.css" rel="stylesheet">

<style type="text/css">

	.page-item.active .page-link {
	    background-color: #337ab7;
	    border-color: #337ab7;
	}

</style>

<div class="row">
	<div class="col-sm-12">

		<?php //if((int)session('branchId') > 0 && session('branchId') != ''){?>

			<?php if($permission->method('attendance_log', 'read')->access()){ ?>
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

		                    	<a href="javascript:void(0);" class="btn btn-primary btn-sm mr-1 bulkAttenImport"><i class="fas fa-plus mr-1"></i><?php echo get_phrases(['bulk','import']);?></a>

		                       <a href="javascript:void(0);" onclick="goBackOnePage()" class="btn btn-warning btn-sm mr-1 ml-2"><i class="fas fa-angle-double-left mr-1"></i><?php echo get_phrases(['back']) ?></a>
		                    </div>
		                </div>
		            </div>

		            <div class="card-body" id="attenLogsSearch">
		            	<!-- Search by employee and date -->
		            	<div class="row form-group">

		            		<div class="col-sm-2">
		                        <?php echo form_dropdown('employee_id',$employees,'','class="form-control select2" id="emp_id" required="required" method="get"');?>
		                    </div>

		            		<div class="col-sm-2">
		                        <input type="text" name="from_date" id="from_date" class="form-control" placeholder="<?php echo get_phrases(['from','date']); ?>" autocomplete="off">
		                    </div>

		                    <div class="col-sm-2">
		                        <input type="text" name="to_date" id="to_date" class="form-control" placeholder="<?php echo get_phrases(['to','date']); ?>" autocomplete="off">
		                    </div>

		                    <div class="col-sm-2">
		                        <button type="button" class="btn btn-success text-white" id="searching"><?php echo get_phrases(['search']);?></button>
		                        <button type="button" class="btn btn-warning resetBtn text-white"><?php echo get_phrases(['reset']);?></button>
		                    </div> 

		            	</div>
		            </div>
				</div>

				<div class="card hrm-atten-logs-card">
					<div class="card-header py-2">
		                <div class="d-flex justify-content-between align-items-center">
		                    
		                	<div class="card-body">

						        <div class="table-responsive">
				                 <?php
				                 	$this->db = db_connect();

									foreach ($attn_logs as $attendance) {?>
									        <table width="100%" class="table table-striped table-bordered table-hover">
									            <tr>
									                <th colspan="8" class="text-center"><?php echo get_phrases(['attendance','history','of']) ?> <?php echo date( "F d, Y", strtotime($attendance['mydate']));?></th>
									            </tr>
									            <tr>
									                <th><?php echo get_phrases(['sl']) ?></th>
									                <th><?php echo get_phrases(['employee','name']) ?></th>
									                <th><?php echo get_phrases(['employee','id']) ?></th>
									                <th title="First in time for today."><?php echo get_phrases(['in','time']) ?></th>
									                <th title="Last in time for today."><?php echo get_phrases(['last','in','time']) ?></th>
									                <th title="Last out time for today."><?php echo get_phrases(['last','out','time']) ?></th>
									                <th><?php echo get_phrases(['worked','hour']) ?></th>
									                <th><?php echo get_phrases(['action']) ?></th>
									            </tr>
									           <?php
									                $att_in = $this->db->table("hrm_attendance_history a")->select('MIN(a.time) as intime,MAX(a.time) as outtime,a.uid,a.atten_date,b.first_name,b.last_name')
									                ->join('hrm_employees b','a.uid = b.employee_id','left')
									                // ->like('a.time',date( "Y-m-d", strtotime($attendance['mydate'])),'after')
									                ->where('a.atten_date',date( "Y-m-d", strtotime($attendance['mydate'])))
									                ->groupBy('a.uid')
									                ->orderBy('a.uid','ASC')
									                ->get()
									                ->getResult();

									                // echo "<pre>";
									                // print_r($att_in);

									            $idx=1;
									           foreach ($att_in as $attendancedata) {

									            $attn_recs = $this->db->table("hrm_attendance_history")->select('*')
									                // ->like('time',date( "Y-m-d", strtotime($attendancedata->intime)),'after')
									            	->like('atten_date',date( "Y-m-d", strtotime($attendancedata->atten_date)))
									                ->where('uid',$attendancedata->uid)
									                ->orderBy('time','DESC')
									                ->get()
									                ->getResult();

									            $last_in_time = "";
									            $last_out_time = "-- : -- : --";
									            if(count($attn_recs) == 1){
									                // $last_in_time = date( "H:i:s", strtotime($attn_recs[0]->time));
									                $last_in_time = $attn_recs[0]->time;
									            }else{
									                if(count($attn_recs) % 2 == 0){
									                    // $last_out_time = date( "H:i:s", strtotime($attn_recs[0]->time));
									                    // $last_in_time = date( "H:i:s", strtotime($attn_recs[1]->time));
									                    $last_out_time = $attn_recs[0]->time;
									                    $last_in_time = $attn_recs[1]->time;
									                }else{
									                    // $last_in_time = date( "H:i:s", strtotime($attn_recs[0]->time));
									                    // $last_out_time = date( "H:i:s", strtotime($attn_recs[1]->time));
									                    $last_in_time = $attn_recs[0]->time;
									                    $last_out_time = $attn_recs[1]->time;
									                }
									            }

									            ?>
									            <tr>
									                <td><?php echo $idx ?></td>
									                <td><a href="<?php echo base_url('human_resources/attendances/user_attendanc_details/'.$attendancedata->uid)?>"><?php echo $attendancedata->first_name.' '.$attendancedata->last_name ?></a></td>
									                <td><?php echo $attendancedata->uid; ?></td>
									                <td><?php echo $attendancedata->intime; //echo date( "H:i:s", strtotime($attendancedata->intime)) ?></td>
									                <td><?php echo $last_in_time; ?></td>
									                <td><?php echo $last_out_time; ?></td>
									                <td><?php 
									                $date_a = new DateTime($attendancedata->outtime);
									                $date_b = new DateTime($attendancedata->intime);
									                $interval = date_diff($date_a,$date_b);

									            echo $interval->format('%h:%i:%s');?></td>
									            <td><a class="btn btn-info" href="<?php echo base_url('human_resources/attendances/user_attendanc_details/'.$attendancedata->uid)?>"><i class="fa fa-eye"></i><?php echo get_phrases(['details']) ?></a></td>
									            </tr>
									            <?php
									         $idx++; }
									        
									            ?>
									        </table>
									   <?php } ?>
						           </div>

						        <!-- Paginate -->
						        <div style='margin-top: 10px;float: right;'>
						            <?= $pager->links() ?>
						        </div>
				            	
				            </div>

		                </div>
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

    	<?php //}else{?>
<!-- 
        	<div class="card">
	            <div class="card-body">
	                <div class="row">
	                    <div class="col-md-12">
	                        <strong class="fs-20 text-danger"><?php //echo get_phrases(['you_have_to_switch_to_a_specific_branch']);?></strong>
	                    </div>
	                </div>
	            </div>
	        </div> -->

        <?php //}?>
	</div>
</div>


<div class="modal fade bd-example-modal-lg" id="add-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel3" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="addModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
            <?php echo form_open_multipart('human_resources/attendances/bulkImportAttenLogs', 'class="needs-validation" id="bulkImportAttenForm" novalidate="" data="jobCallBackData"');?>
            <div class="modal-body">
                <input type="hidden" name="id" id="id">
                <input type="hidden" name="action" id="action">
                <div class="row">
                   <div class="col-md-12 col-sm-12">
                      <div class="form-group">
                            <label class="font-weight-600"><?php echo get_phrases(['attach', 'attendance','logs', 'list']);?></label>
                            <input type="file" name="atten_logs_list" id="atten_logs_list" class="form-control">
                        </div>
                   </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <div class="form-group">
                             <a href="<?php echo base_url().'/assets/dist/documents/employee/attendnace_logs.csv'; ?>" class="btn btn-sm btn-primary" download><?php echo get_phrases(['download', 'sample']);?>(CSV Format)</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo get_phrases(['close']);?></button>
                <button type="submit" class="btn btn-success" id="saveBtn"></button>
            </div>
            <?php echo form_close();?>
        </div>
    </div>
</div>

<script src="<?php echo base_url()?>/assets/plugins/jquery-ui-1.12.1/jquery-ui.min.js" type="text/javascript"></script>

<script type="text/javascript">

	var jobCallBackData = function () { 
        $('#add-modal').modal('hide');   
        $('#bulkImportAttenForm')[0].reset();       
        $('#bulkImportAttenForm').removeClass('was-validated');    
        // $('#employeeList').DataTable().ajax.reload(null, false);

        location.reload();
    }
	
	 $(document).ready(function() { 
        "use strict";
        $('option:first-child').val('').trigger('change');

        $('#date').datepicker({minDate: 0, dateFormat: 'yy-mm-dd'});

        $('#from_date').datepicker({dateFormat: 'yy-mm-dd'});
        $('#to_date').datepicker({dateFormat: 'yy-mm-dd'});


        // reset fields
        $('.resetBtn').on('click', function(e){

            $('#from_date').val('');
            $('#to_date').val('');
            $('#emp_id').val('').trigger('change');
        });

        // To open the modal for bulk import attendance logs
        $('.bulkAttenImport').on('click', function(){

            $('#action').val('add');

            $('#saveBtn').text('<?php echo get_phrases(['save']);?>');
            $('#addModalLabel').text('<?php echo get_phrases(['bulk', 'import', 'attendance', 'logs']);?>');
            $('#add-modal').modal('show');
        });

     	// employees list
        // $.ajax({
        //     type:'GET',
        //     url: _baseURL+'human_resources/attendances/getEmployees/',
        //     dataType: 'json',
        //     data:{'csrf_stream_name':csrf_val},
        // }).done(function(data) {
        //     $("#emp_id").empty();
        //     $("#emp_id").select2({
        //         placeholder: '<?php echo get_phrases(['select', 'employee']);?>',
        //         data: data
        //     });
        //     var dpt = new Option('', '', true, true);
        //     $('#emp_id').append(dpt).trigger('change');
        // });

        // search employee
        // $('#emp_id').select2({
        //     placeholder: '<?php echo get_phrases(['search', 'employee']);?>',
        //     minimumInputLength: 2,
        //         ajax: {
        //             url: _baseURL+'auth/searchEmployee',
        //             dataType: 'json',
        //             delay: 250,
        //             processResults: function (data) {
        //               return {
        //                 results:  $.map(data, function (item) {
        //                       return {
        //                           text: item.text,
        //                           id: item.id
        //                       }
        //                   })
        //               };
        //             },
        //             cache: true
        //        }
        // });

        var newOption = new Option('', '', true, true);
  		$('#emp_id').append(newOption).trigger('change');

        // Searching using all three inputs
        $('#attenLogsSearch').on('click', '#searching', function (e) {

        	var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            var emp_id = $('#emp_id').val();

            //console.log("from_date: "+from_date+" , to_date: "+to_date+" , emp_id: "+emp_id);

            var submit_url = _baseURL+'human_resources/attendances/atten_log_search';

            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'JSON',
                data: {
                	'csrf_stream_name':csrf_val,
                	emp_id:emp_id,
                	from_date:from_date,
                	to_date:to_date
                },
                success: function(res) {
                    
                    // console.log(res);
                    if(res.success == true){

                    	toastr.success(res.message, res.title);
                    }
                    else if(res.flag == true){
                    	// console.log(res);
                    	window.location.href = _baseURL+'human_resources/attendances/searched_atten_log?emp_id='+emp_id+'&from_date='+from_date+'&to_date='+to_date;
                    }
                    else{

                    	toastr.error(res.message, res.title);
                    }
                }
            });

        });

    });

</script>