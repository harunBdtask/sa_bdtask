<!--   <link href="<?php echo base_url()?>/assets/plugins/daterangepicker/daterangepicker.css" rel="stylesheet"> -->
  <link href="<?php echo base_url()?>/assets/plugins/jquery-ui-1.12.1/jquery-ui.min.css" rel="stylesheet">


<div class="row">
    <div class="col-md-12 col-lg-12">
        <div class="card">

            <div class="card-header py-2">
                <div class="d-flex justify-content-between align-items-center">

                    <div>
                        <h6 class="fs-17 font-weight-600 mb-0"><?php echo get_phrases(['salary','generate'])?></h6>
                    </div>

                    <div class="text-right">
                        <?php if($permission->method('benefit_list','read')->access()){?>

                            <a href="<?php echo base_url('human_resources/payroll/salary_sheet')?>" class="btn btn-success btn-sm mr-1"><i class="fas fa-align-justify mr-1"></i><?php echo get_phrases(['generate','list'])?></a>

                        <?php }?>

                    </div>
                </div>
            </div>

            <div class="card-body">
                <?php echo form_open_multipart("human_resources/payroll/create_salary_sheet/") ?>  

                    <div class="form-group row">

                        <label for="salary_month" class="col-md-2 text-right col-form-label"><?php echo get_phrases(['department'])?> <i class="text-danger">  </i>:</label>
                        <div class="col-md-4">
                            <div class="">

                                <?php echo form_dropdown('department_id',$departments,null,'class="form-control select2" id="department_id" required') ?>

                            </div>
                        </div>
                    </div>          

                    <div class="form-group row">

                        <label for="salary_month" class="col-md-2 text-right col-form-label"><?php echo get_phrases(['salary','month'])?> <i class="text-danger">  </i>:</label>
                        <div class="col-md-4">
                            <div class="">

                                <input type="text" class="form-control monthpicker" name="myDate" id="salary_month" placeholder="<?php echo get_phrases(['enter','salary','month'])?>" value="" autocomplete="off" required>

                            </div>
                        </div>
                    </div>

                    <br><br>

                    <div class="form-group row">

                        <div class="col-md-2 text-right"></div>

                         <div class="col-md-4 text-right">
                            <div class="">
                            
                                <button type="submit"  class="btn btn-success form-control">
                                    <?php echo get_phrases(['save']); ?></button>
                            </div>
                           
                        </div>
                    </div>

                <?php echo form_close();?>
            </div>
        </div>
    </div>
</div>

<style type="text/css">
    
    .ui-datepicker-calendar {
        display: none;
    }

</style>


<!-- <script src="<?php echo base_url()?>/assets/plugins/moment/moment.min.js"></script>
<script src="<?php echo base_url()?>/assets/plugins/daterangepicker/daterangepicker.js"></script> -->

<script src="<?php echo base_url()?>/assets/plugins/jquery-ui-1.12.1/jquery-ui.min.js" type="text/javascript"></script>

<script type="text/javascript">

        // $('.monthpicker').daterangepicker({

        //     singleDatePicker: true,
        //     showDropdowns: true,
        //     minDate: moment().format("YYYY-MM"),
        //     maxDate: moment().format("YYYY-MM"),      
        //     locale : {
        //     format : 'MMMM YYYY'
        // }
        // }).on('hide.daterangepicker', function (ev, picker) {

        // $('.table-condensed tbody tr:nth-child(2) td').click();

        // });



        $(document).ready(function() {

           $('.monthpicker').datepicker({
             changeMonth: true,
             changeYear: true,
             dateFormat: 'MM yy',
               
             onClose: function() {
                var iMonth = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
                var iYear = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
                $(this).datepicker('setDate', new Date(iYear, iMonth, 1));
             },
               
             beforeShow: function() {
               if ((selDate = $(this).val()).length > 0) 
               {
                  iYear = selDate.substring(selDate.length - 4, selDate.length);
                  iMonth = jQuery.inArray(selDate.substring(0, selDate.length - 5), $(this).datepicker('option', 'monthNames'));
                  $(this).datepicker('option', 'defaultDate', new Date(iYear, iMonth, 1));
                   $(this).datepicker('setDate', new Date(iYear, iMonth, 1));
               }
            }
          });
        });

</script>

                 
                 