    
<div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
    <div class="row">
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

        $cr_date =  date("Y-m-d");
        $cr_time =  date("H:i");

        if ($clsh_datalist) {

        foreach($clsh_datalist as $empdata){ 

            $emp_details = $this->db->table('hrm_employees')
            ->select("hrm_employees.*,hrm_employees.image,hrm_employees.first_name,hrm_employees.last_name,hrm_employee_types.type")
            ->join("hrm_employee_types", "hrm_employee_types.id=hrm_employees.employee_type", "left")
            ->where('hrm_employees.employee_id', $empdata->emp_id)
            ->get()
            ->getRow();
        ?>
        <div class="col-md-6 col-lg-4 col-xl-3">
            <div class="member_inner my-3 d-flex align-items-center rounded">

            <?php 

            $is_compchk = $this->db->table('hrm_emproster_assign')->select('*')->where('emp_id', $emp_details->employee_id)->where('emp_startroster_date', $clkcngdate)->get()->getRow();

            ?>
                <div class="<?php if($is_compchk->is_complete == 1){echo 'status present';}
                    if($is_compchk->is_complete == 2){ echo 'status leave';}
                    elseif($clkcngdate < $cr_date){
                        if ($is_compchk->is_complete == 3 || $is_compchk->is_complete == 0) {
                            
                            echo 'status absent';
                        }
                    }
                    
                    ?>"></div>
                <div class="img_wrapper mr-3">
                    <img src="<?php echo base_url(!empty($emp_details->image)?$emp_details->image:'assets/dist/img/manage_employee.png'); ?>" class="img-fluid rounded" alt="">
                </div>
                <div class="info_wrapper">

                    <h6 class="member_name"><?php echo $emp_details->first_name.' '.$emp_details->last_name;?></h6>
                    <h6 class="member_position"><?php echo $emp_details->type?></h6>

                    <?php if($cr_date < $clkcngdate){?>
                        <div class="member_info">
                            <input name="url" type="hidden" id="url_<?php echo $emp_details->employee_id; ?>" value="<?php echo base_url("human_resources/duty_roster/updtempshift_frm") ?>" />
                            <a type="button" onclick="edit_shift_info2('<?php echo $emp_details->employee_id; ?>', '<?php echo $clkcngdate?>')" data-target="#exampleModal" class="btn member_shift" data-toggle="tooltip" data-placement="left" title="Update">
                            change shift</a>
                            </div>
                    <?php }?>

                </div>
            </div>
        </div>
        <?php  } }else {
                    echo '<strong style="margin-left: 700px;">No Data Found !!</strong>';
                 }?>
        
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


<style type="text/css">
    
    .img_wrapper img{height:  70px;}

</style>
           
<script type="text/javascript">
    
    function edit_shift_info2(id, cldate){
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

</script>
        
    
