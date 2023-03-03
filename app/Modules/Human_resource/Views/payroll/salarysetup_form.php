<style type="text/css">
    caption {
    caption-side: top;
    }

    #basic_salary_percent{display: none;}
</style>

<div class="row">
    <div class="col-md-12 col-lg-12">
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
                       <?php if($permission->method('salary_setup_list','read')->access()){?>
                       <a href="<?php echo base_url('human_resources/payroll/salary_setup_list')?>" class="btn btn-success btn-sm mr-1"><i class="fas fa-align-justify mr-1"></i><?php echo get_phrases(['salary','setup','list'])?></a>
                     <?php }?>
                      
                    </div>
                </div>
            </div>

             <div class="card-body">

                <?php echo  form_open('human_resources/payroll/save_salarysetup','id="validate"') ?>

                    <div class="form-group row">
                          <label for="employee_id" class="col-md-3 col-form-label"><?php echo get_phrases(['employee']) ?> <span class="text-danger">*</span></label>
                          <div class="col-md-6">
                            <?php echo form_dropdown('employee_id',$employee,null,'class="form-control select2" id="employee_id" onchange="employechange(this.value)" required') ?>
                         </div>
                    </div>

                    <div class="form-group row">
                        <label for="payment_period" class="col-md-3 col-form-label"><?php echo get_phrases(['salary','type']) ?> <span class="text-danger">*</span></label>

                        <div class="col-md-6">
                             <input type="text" class="form-control " name="sal_type_name" id="sal_type_name" readonly="">
                             <input type="hidden" class="form-control" name="sal_type" id="sal_type">
                        </div>

                    </div>

                    <table  border="1" class="" width="100%">
                       <tr>

                        <td>  
                          <table id="add"> 
                          <caption class="text-center"><u><?php echo get_phrases(['addition'])?></u></caption>   

                          <tr>
                            <th  class="pl-4"><?php echo get_phrases(['basic'])?></th>
                            <td>
                                <input type="number" id="basic" name="basic" class="form-control" disabled="">
                                <input type="hidden" id="gross_salary" name="" class="form-control">
                                <input type="hidden" id="basic_percent" name="basic_percent" class="form-control">
                            </td>
                            <td colspan="2">
                                <p type="button" class="badge badge-primary" id="basic_salary_percent"></p>
                            </td>
                          </tr>  

                         <?php
                         $x=0;
                         foreach ($slname as $ab){
                          ?>
                          <tr>
                            <th class="pl-4"><?php echo $ab->benefit_name ;?>(%)</th>
                            <td><input type="number" name="amount[<?php echo $ab->id; ?>]" class="form-control addamount valid_number" onkeyup="summary()" id="add_<?php echo $x;?>" required></td>
                          </tr>
                          <?php
                        $x++;}
                        ?>

                        </table>

                        </td>

                        <td> 
                            <table id="dduct">
                               <caption class="text-center"><u><?php echo get_phrases(['deduction'])?></u></caption> 

                                <?php
                                $y=0;

                                foreach ($sldname as $row){
                                  ?>
                                  <tr>
                                    <th class="pl-4"><?php echo $row->benefit_name ;?> (%)</th>
                                    <td><input type="number" name="amount[<?php echo $row->id; ?>]" onkeyup="summary()" class="form-control deducamount valid_number" id="dd_<?php echo $y;?>"></td><td></td>
                                    </tr><?php
                                $y++; }
                                ?>

                                <tr>
                                  <th class="pl-4"><?php echo get_phrases(['tax'])?> (%)</th>
                                  <td><input type="number" name="amount[]"  onkeyup="summary()"  class="form-control deducamount valid_number" id="taxinput"></td>
                                  <td class="pl-4"><input type="checkbox" name="tax_manager" id="taxmanager" onchange='//handletax(this);' value="1">Tax Manager</td>
                                </tr>

                            </table>
                        </td>

                        </tr> 


                    </table>


                    <br>
                    <div class="form-group row">
                        <label for="payable" class="col-sm-3 col-form-label text-center"><?php echo get_phrases(['gross','salary'])?></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="gross_salary" id="grsalary" readonly="">
                        </div>
                    </div>

                    <div class="form-group text-right">
                        <button type="reset" class="btn btn-primary w-md m-b-5"><?php echo get_phrases(['reset']) ?></button>
                        <button type="submit" class="btn btn-success w-md m-b-5"><?php echo get_phrases(['save']) ?></button>
                    </div>

                <?php echo form_close() ?>

            </div>

            </div>
        </div>
</div>



<script type="text/javascript">
    "use strict";

function employechange(id) {

    var base_url = $("#base_url").val();
    var csrf_test_name = $('[name="csrf_test_name"]').val();

    $.ajax({
        url: _baseURL + "human_resources/payroll/employee_basic_info/",
        method: 'post',
        dataType: 'json',
        data: {
            'employee_id': id,
            csrf_stream_name: csrf_val,
        },
        success: function(data) {

            // console.log(data);

            if(data && data.status == true){

                console.log(data);

                document.getElementById('basic').value = parseFloat(data.basic.replace(/,/g, ''));
                document.getElementById('gross_salary').value = parseFloat(data.gross.replace(/,/g, ''));
                document.getElementById('sal_type').value = data.rate_type;
                document.getElementById('sal_type_name').value = data.stype;
                document.getElementById('grsalary').value = parseFloat(data.gross.replace(/,/g, ''));

                document.getElementById('basic_percent').value = parseFloat(data.basic_percent.replace(/,/g, ''));

                //Show message badge that in what percent basic salary is calculating
                $('#basic_salary_percent').html('Basic is '+parseFloat(data.basic_percent.replace(/,/g, ''))+' % of Gross');
                $('#basic_salary_percent').css('display','block');
                //Ends

                if (data.rate_type == 'hourly') {

                    document.getElementById("taxinput").disabled = true;
                    document.getElementById("taxmanager").checked = true;
                    document.getElementById("taxmanager").setAttribute('disabled', 'disabled');

                }else{

                    document.getElementById("taxinput").disabled = false;
                    document.getElementById("taxmanager").checked = false;
                    document.getElementById("taxmanager").removeAttribute('disabled');

                }

                // Make all the additional fields empty
                var i;
                var count = $('#add tr').length;

                for (i = 0; i < count; i++) {
                    $("#add_" + i).val('');
                }
                // End

            }else{

                var title = "Salary Setup";
                toastr.error(data.msg, title);

                document.getElementById('basic').value = "";
                document.getElementById('gross_salary').value = "";
                document.getElementById('sal_type').value = "";
                document.getElementById('sal_type_name').value = "";
                document.getElementById('grsalary').value = "";

                document.getElementById('basic_percent').value = "";

                //Show message badge that in what percent basic salary is calculating
                $('#basic_salary_percent').html('');

                // Make all the additional fields empty
                var i;
                var count = $('#add tr').length;

                for (i = 0; i < count; i++) {
                    $("#add_" + i).val('');
                }
                // End

                // Load page after 5 seconds
                setTimeout(function(){

                    location.reload();

                }, 2000);
            }

        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert('Error get data from ajax');
        }
    });
}

"use strict";

function summary() {

    var basic_percent = parseInt($('#basic_percent').val());

    var addper = basic_percent;

    $(".addamount").each(function() {
        isNaN(this.value) || 0 == this.value.length || (addper += parseFloat(this.value))
    });

    if (addper > 100) {

        alert('You Can Not input more than 100%');

        // Make all the additional fields empty
        var i;
        var count = $('#add tr').length;

        for (i = 0; i < count; i++) {
            $("#add_" + i).val('');
        }
        // End
    }

    var b = parseInt($('#basic').val());
    var g = parseInt($('#gross_salary').val());
    var add = 0;
    var deduct = 0;

    // $(".addamount").each(function() {
    //     var value = this.value;
    //     var basic = parseInt($('#basic').val());
    //     isNaN(value * basic / 100) || 0 == (value * basic / 100).length || (add += parseFloat(value * basic / 100))
    // });

    $(".deducamount").each(function() {
        var value = this.value;
        var basic = parseInt($('#basic').val());

        isNaN(value * basic / 100) || 0 == (value * basic / 100).length || (deduct += parseFloat(value * basic / 100))
    });

    // document.getElementById('grsalary').value = add + b - (deduct);
    document.getElementById('grsalary').value = g - (deduct);
}

</script>
                 