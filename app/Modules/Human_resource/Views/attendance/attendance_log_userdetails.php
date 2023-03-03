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

	            <div class="card-body">

                 <div class="text-right" id="print">
               <button type="button" class="btn btn-warning" id="btnPrint" onclick="printDiv();"><i class="fa fa-print"></i></button>
                
            </div>
                <div id="printArea" class="attenLogs">
                   <center><img src="<?php echo base_url().$company->logo;?>" style="width: 100px;"></center>
				                <h2><center style="padding-bottom: 15px;">  <?php

				           $this->db = db_connect();

				           echo $user->first_name.' '.$user->last_name;?></center></h2>
				                 <?php
					foreach ($attn_logs as $attendance) { ?>
				               <table class="table table-striped table-bordered table-hover">
				                <caption style="caption-side: top;"><?php echo get_phrases(['attendance','history','of']) ?> <?php echo date( "F d, Y", strtotime($attendance['mydate']));?></caption>
				    
					            <thead>
					            <tr>
					                <th><?php echo get_phrases(['sl']) ?></th>
					                <th><?php echo get_phrases(['time']) ?></th>
					                <th><?php echo get_phrases(['status']) ?></th>
					                <th class="action"><?php echo get_phrases(['action']) ?></th>  
					            </tr>
					            </thead>
					            <tbody>
					           <?php

								$att_in = $this->db->table("hrm_attendance_history a")->select('a.*,b.first_name,b.last_name')
								->join('hrm_employees b','a.uid = b.employee_id','left')
								// ->like('a.time',date( "Y-m-d", strtotime($attendance['mydate'])),'after')
								->like('a.atten_date',date( "Y-m-d", strtotime($attendance['mydate'])))
								->where('a.uid',$id)
								->orderBy('a.time','ASC')
								->get()
								->getResult();

					             $idx=1;
					             $in_data = [];
					             $out_data = [];
					           foreach ($att_in as $attendancedata) {

					            if($idx % 2){

							       $status = "IN";
							       $in_data[$idx] = $attendancedata->time;

								    }else{
								        $status = "OUT";
								        $out_data[$idx] = $attendancedata->time;
								    }

					            	?>
						            <tr>
						                <td><?php echo $idx ?></td>
						                <td><?php echo $attendancedata->time; //echo date( "H:i:s", strtotime($attendancedata->time)) ?></td>
						                <td><?php echo $status ?></td>
						                <td class="action">
						                     <?php if($permission->method('attendance_log','delete')->access()): ?>
						                    <a href="javascript:void(0);" class="btn btn-danger actionDelete" data-id="<?php echo $attendancedata->atten_his_id;?>"><i class="far fa-trash-alt"></i></a>
						               <?php endif; ?>
						               <?php if($permission->method('attendance_log','update')->access()): ?>
						                    <a href="<?php echo base_url("human_resources/attendances/attendanc_edit/$attendancedata->atten_his_id") ?>" class="btn btn-info"><i class="fa fa-edit"></i></a>
						               <?php endif; ?>
						                </td>
						            </tr>
					                
					            	<?php

									$idx++; 

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
						                    $hou += @(int)$split[0];
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
						            $totaltime = $hours.":".$minutes.":".$seconds;
					            ?>
					            </tbody>

					            <tfoot>
					                <tr><td colspan="4"><b>N.B: <?php echo get_phrases(['you','spent']) ?> <?php echo $totaltime;?> <?php echo get_phrases(['hours','out','of','working','hour']) ?></b></td></tr>
					            </tfoot>
				        </table>
				    <?php } ?>
				    </div>
	            	
	            	<!-- Paginate -->
			        <div style='margin-top: 10px;float: right;'>
			            <?= $pager->links() ?>
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

<script type="text/javascript">

	$(document).ready(function() { 
       "use strict";

       var element = document.getElementById('attendance_log_menu');
       element.classList.add('mm-active');

     // delete branch
        $('.attenLogs').on('click', '.actionDelete', function(e){
            e.preventDefault();

            var id = $(this).attr('data-id');
            
            var submit_url = _baseURL+"human_resources/attendances/atten_delete/"+id;
            var check = confirm('<?php echo get_phrases(["are_you_sure"])?>');  
            if(check == true){  

            	// console.log(location.href);

                $.ajax({
                    type: 'POST',
                    url: submit_url,
                    data: {'csrf_stream_name':csrf_val},
                    dataType: 'JSON',
                    success: function(res) {
                        if(res.success==true){

                            toastr.success(res.message, '<?php echo get_phrases(["attendance","record"])?>');
                            $("#printArea").load(location.href+" #printArea>*","");

                        }else{
                            toastr.error(res.message, '<?php echo get_phrases(["attendance","record"])?>');
                        }
                    },error: function() {

                    	toastr.error("Error Occured", '<?php echo get_phrases(["attendance","record"])?>');
                    }
                });
            }   
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