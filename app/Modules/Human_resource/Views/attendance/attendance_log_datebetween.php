<link href="<?php echo base_url()?>/assets/plugins/jquery-ui-1.12.1/jquery-ui.min.css" rel="stylesheet">

<style type="text/css">

	.page-item.active .page-link {
	    background-color: #337ab7;
	    border-color: #337ab7;
	}

</style>

<div class="row">
	<div class="col-sm-12">
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
	                       <a href="javascript:void(0);" onclick="goBackOnePage()" class="btn btn-warning btn-sm mr-1 ml-2"><i class="fas fa-angle-double-left mr-1"></i><?php echo get_phrases(['back']) ?></a>
	                    </div>
	                </div>
	            </div>

	            <div class="card-body" id="attenLogsSearch">
	            	<!-- Search by employee and date -->
	            	<div class="row form-group">

	            		<div class="col-sm-2">
	                        <?php echo form_dropdown('emp_id',$employees,$id,'class="form-control select2" id="emp_id" required="required" method="get"');?>
	                    </div>

	            		<div class="col-sm-2">
	                        <input type="text" name="from_date" id="from_date" class="form-control" placeholder="<?php echo get_phrases(['from','date']); ?>" autocomplete="off" value="<?php echo $from_date;?>">
	                    </div>

	                    <div class="col-sm-2">
	                        <input type="text" name="to_date" id="to_date" class="form-control" placeholder="<?php echo get_phrases(['to','date']); ?>" autocomplete="off" value="<?php echo $to_date;?>">
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

							<div class="text-right" id="print">

								<button type="button" class="btn btn-warning" id="btnPrint" onclick="printDiv();"><i class="fa fa-print"></i></button>

							</div>

							<div id="printArea">

								<center><img src="<?php echo base_url().$company->logo;?>" style="width: 100px;"></center>

								<?php

									$this->db = db_connect();
								?>

								<h2><center>  <?php echo $user->first_name.' '.$user->last_name;?></center></h2>

								<table width="100%" class="table table-striped table-bordered table-hover">
									<tr>
										<th colspan="7" class="text-center"><?php echo get_phrases(['attendance','history']); ?> <?php echo '( From '.$from_date.' To '.$to_date.' )';?> </th>
									</tr>

									<tr>
										<th><?php echo get_phrases(['sl']); ?></th>
										<th><?php echo get_phrases(['date']); ?></th>
										<th><?php echo get_phrases(['in','time']); ?></th>
										<th><?php echo get_phrases(['out','time']); ?></th>
										<th><?php echo get_phrases(['worked','hours']); ?></th>
										<th><?php echo get_phrases(['wastage','hours']); ?></th>
										<th><?php echo get_phrases(['net','hours']); ?></th>
									</tr>

								<?php

									$idx=1;
									$totalhour=[];
									$totalwasthour = [];
									$totalnetworkhour = [];

									// echo "<pre>";
									// print_r($atten_in);

								foreach ($atten_in as $attendancedata) {
								?>
									<tr>

										<td><?php echo $idx ?></td>
										<td><?php echo date( "F d, Y", strtotime($attendancedata[0]->time));?></td>
										<td><?php echo $attendancedata[0]->intime; //echo date( "H:i:s", strtotime($attendancedata[0]->intime)) ?></td>
										<td><?php echo $attendancedata[0]->outtime; //echo date( "H:i:s", strtotime($attendancedata[0]->outtime)) ?></td>
										<td><?php

											$date_a = new DateTime($attendancedata[0]->outtime);
											$date_b = new DateTime($attendancedata[0]->intime);
											$interval = date_diff($date_a,$date_b);

											echo $totalwhour = $interval->format('%h:%i:%s');
											$totalhour[$idx] = $totalwhour;
										?>
										</td>

										<td> <?php

											$att_dates = date( "Y-m-d", strtotime($attendancedata[0]->atten_date));  

											$att_in = $this->db->table("hrm_attendance_history a")
											->select('a.*,b.first_name,b.last_name')
											->join('hrm_employees b','a.uid = b.employee_id','left')
											// ->like('a.time',$att_dates,'after')
											->like('a.atten_date',$att_dates)
											->where('a.uid',$attendancedata[0]->uid)
											->orderBy('a.time','ASC')
											->get()
											->getResult();

											$ix=1;
											$in_data = [];
											$out_data = [];

											foreach ($att_in as $attendancedata) {

												if($ix % 2){
												$status = "IN";
												$in_data[$ix] = $attendancedata->time;

												}else{
												$status = "OUT";
												$out_data[$ix] = $attendancedata->time;
												}
												$ix++;
											}


											$result_in = array_values($in_data);
											$result_out = array_values($out_data);
											$total = [];
											$count_out = count($result_out);

											if($count_out >= 2){
												$n_out = $count_out-1;
											}else{
												$n_out = 0;   
											}

											for($i=0;$i < $n_out; $i++) {

												$date_a = new DateTime($result_in[$i+1]);
												$date_b = new DateTime($result_out[$i]);
												$interval = date_diff($date_a,$date_b);

												$total[$i] =  $interval->format('%h:%i:%s');
											}

											$hou = 0;
											$min = 0;
											$sec = 0;

											$totaltime = '00:00:00';
											$length = sizeof($total);

											for($x=0; $x <= $length; $x++){

												$split = explode(":", @$total[$x]); 
												$hou += @(integer)$split[0];
												$min += @$split[1];
												$sec += @$split[2];

											}

											$seconds = $sec % 60;
											$minutes = $sec / 60;
											$minutes = (integer)$minutes;
											$minutes += $min;
											$hours = $minutes / 60;
											$minutes = $minutes % 60;
											$hours = (integer)$hours;
											$hours += $hou % 24;

											echo  $totalwastage = $hours.":".$minutes.":".$seconds;
											$totalwasthour[$idx] = $totalwastage;

											?>
											
										</td>

										<td><?php 

											$date_a = new DateTime($totalwhour);
											$date_b = new DateTime($totalwastage);
											$networkhours = date_diff($date_a,$date_b);

											echo $ntworkh = $networkhours->format('%H:%I:%S');
											$totalnetworkhour[$idx] = $ntworkh;

										?></td>
									</tr>
									<?php
									$idx++; }

								?>

								<tr><td colspan="4" class="text-center"><b><?php echo get_phrases(['working','hours','summary']); ?></b></td><td><b>
								<?php 

								$seconds = 0;
								foreach($totalhour as $t)
								{
									$timeArr = array_reverse(explode(":", $t));

									foreach ($timeArr as $key => $value)
									{
										if ($key > 2) break;
											$seconds += pow(60, $key) * $value;
									}

								}

								$hours = floor($seconds / 3600);
								$mins = floor(($seconds - ($hours*3600)) / 60);
								$secs = floor($seconds % 60);

								echo $hours.':'.$mins.':'.$secs;

								?></b>
								</td>
								<td><b>
								<?php 

								$wastsecond = 0;
								foreach($totalwasthour as $wastagetime)
								{
									$wastimearray = array_reverse(explode(":", $wastagetime));

									foreach ($wastimearray as $key => $value)
									{
										if ($key > 2) break;
											$wastsecond += pow(60, $key) * $value;
									}

								}

								$wasthours = floor($wastsecond / 3600);
								$wastmin = floor(($wastsecond - ($wasthours*3600)) / 60);
								$wastsc = floor($wastsecond % 60);

								echo $wasthours.':'.$wastmin.':'.$wastsc;

								?></b>
								</td>
								<td><b>
								<?php 

								$netsecond = 0;
								foreach($totalnetworkhour as $nettime)
								{
									$nettimearray = array_reverse(explode(":", $nettime));

									foreach ($nettimearray as $key => $value)
									{
										if ($key > 2) break;
											$netsecond += pow(60, $key) * $value;
									}

								}

								$nettlehour = floor($netsecond / 3600);
								$netmin = floor(($netsecond - ($nettlehour*3600)) / 60);
								$ntsec = floor($netsecond % 60);

								echo $nettlehour.':'.$netmin.':'.$ntsec;

								?>  </b> 
								</td>
								</tr>
								</table>
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
	</div>
</div>

<script src="<?php echo base_url()?>/assets/plugins/jquery-ui-1.12.1/jquery-ui.min.js" type="text/javascript"></script>

<script type="text/javascript">
	
	 $(document).ready(function() { 
        "use strict";
        //$('option:first-child').val('').trigger('change');

        $('#date').datepicker({minDate: 0, dateFormat: 'yy-mm-dd'});

        $('#from_date').datepicker({dateFormat: 'yy-mm-dd'});
        $('#to_date').datepicker({dateFormat: 'yy-mm-dd'});


        // reset fields
        $('.resetBtn').on('click', function(e){

            $('#from_date').val('');
            $('#to_date').val('');
            $('#emp_id').val('').trigger('change');
        });

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


    //     var newOption = new Option('<?php //echo $user->text;?>', '<?php //echo $user->emp_id;?>', true, true);
  		// $('#emp_id').append(newOption).trigger('change');

        // Searching using all three inputs
        $('#attenLogsSearch').on('click', '#searching', function (e) {

        	var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            var emp_id = $('#emp_id').val();

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
                    
                    if(res.success == true){

                    	toastr.success(res.message, res.title);
                    }
                    else if(res.flag == true){

                    	window.location.href = _baseURL+'human_resources/attendances/searched_atten_log?emp_id='+emp_id+'&from_date='+from_date+'&to_date='+to_date;
                    }
                    else{

                    	toastr.error(res.message, res.title);
                    }
                }
            });

        });

    });

	 function printDiv() {

		$('.action').hide();
        
        var divName = "printArea";
        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();

        location.reload();

        document.body.innerHTML = originalContents;
    }

</script>