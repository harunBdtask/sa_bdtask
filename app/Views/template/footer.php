                <footer class="footer-content">
                    <div class="footer-text d-flex align-items-center justify-content-between">
                        <div class="copy"><?php echo $settings_info->footer_text;?></div>
                    </div>
                </footer><!--/.footer content-->
                <div class="overlay"></div>
        </div>
    </div>

        <script src="<?php echo base_url()?>/assets/dist/js/popper.min.js"></script>
        <script src="<?php echo base_url()?>/assets/plugins/bootstrap/js/bootstrap.min.js"></script>
        <script src="<?php echo base_url()?>/assets/plugins/metisMenu/metisMenu.min.js"></script>
        <script src="<?php echo base_url()?>/assets/plugins/perfect-scrollbar/dist/perfect-scrollbar.min.js"></script>
        <!-- Third Party Scripts(used by this page)-->
       
        <script src="<?php echo base_url()?>/assets/plugins/sparkline/sparkline.min.js"></script>

        <!-- Toastr js -->
        <script src="<?php echo base_url()?>/assets/plugins/toastr/toastr.min.js" type="text/javascript"></script>
       
        <!--Page Active Scripts(used by this page)-->
        <!-- <script src="<?php //echo base_url()?>/assets/dist/js/pages/dashboard.js"></script> -->
        <!--Page Scripts(used by all page)-->
         <script src="<?php echo base_url()?>/assets/plugins/chartJs/Chart.min.js"></script>
         <script src="<?php echo base_url()?>/assets/plugins/select2/dist/js/select2.min.js"></script>

        <?php if(isset($summernote) && !empty($summernote)){ ?>
        <link href="<?php echo base_url()?>/assets/plugins/summernote/summernote.css" rel="stylesheet">
        <link href="<?php echo base_url()?>/assets/plugins/summernote/summernote-bs4.css" rel="stylesheet">
        <script src="<?php echo base_url()?>/assets/plugins/summernote/summernote.min.js"></script>
        <script src="<?php echo base_url()?>/assets/plugins/summernote/summernote-bs4.min.js"></script>
        <script src="<?php echo base_url()?>/assets/plugins/summernote/summernote.active.js"></script>
        <?php }if(isset($isDTables) && !empty($isDTables)){ ?>
        <script src="<?php echo base_url()?>/assets/plugins/datatables/dataTables.min.js"></script>
        <script src="<?php echo base_url()?>/assets/plugins/datatables/dataTables.bootstrap4.min.js"></script>
        <script src="<?php echo base_url()?>/assets/plugins/datatables/dataTables.responsive.min.js"></script>
        <script src="<?php echo base_url()?>/assets/plugins/datatables/responsive.bootstrap4.min.js"></script>
        <script src="<?php echo base_url()?>/assets/plugins/datatables/dataTables.buttons.min.js"></script>
        <script src="<?php echo base_url()?>/assets/plugins/datatables/buttons.bootstrap4.min.js"></script>
        <script src="<?php echo base_url()?>/assets/plugins/datatables/jszip.min.js"></script>
        <script src="<?php echo base_url()?>/assets/plugins/datatables/pdfmake.min.js"></script>
        <script src="<?php echo base_url()?>/assets/plugins/datatables/vfs_fonts.js"></script>
        <script src="<?php echo base_url()?>/assets/plugins/datatables/buttons.html5.min.js"></script>
        <script src="<?php echo base_url()?>/assets/plugins/datatables/buttons.print.min.js"></script>
        <script src="<?php echo base_url()?>/assets/plugins/datatables/buttons.colVis.min.js"></script>
        <script src="<?php echo base_url()?>/assets/plugins/datatables/dataTables.rowGroup.min.js"></script>
        <?php } if(isset($isDateTimes) && !empty($isDateTimes)){ ?>
        <script src="<?php echo base_url()?>/assets/plugins/moment/moment.min.js"></script>
        <script src="<?php echo base_url()?>/assets/plugins/daterangepicker/daterangepicker.js"></script>
        <script src="<?php echo base_url()?>/assets/plugins/daterangepicker/daterangepicker.active.js?v=1.23"></script>
        <?php }?>
        <!-- Third Party Scripts(used by this page)-->
        <?php if(isset($isAmChart) && !empty($isAmChart)){ ?>
        <script src="<?php echo base_url()?>/assets/plugins/amcharts4/core.js"></script>
        <script src="<?php echo base_url()?>/assets/plugins/amcharts4/charts.js"></script>
        <script src="<?php echo base_url()?>/assets/plugins/amcharts4/plugins/wordCloud.js"></script>
        <script src="<?php echo base_url()?>/assets/plugins/amcharts4/themes/animated.js"></script>
        <?php } ?>
        <script src="<?php echo base_url()?>/assets/plugins/jquery.counterup/jquery.waypoints.min.js"></script>
        <script src="<?php echo base_url()?>/assets/plugins/jquery.counterup/jquery.counterup.min.js"></script>
        <!--Page Active Scripts(used by this page)-->
        <!--Page Active Scripts(used by this page)-->
        <script src="<?php echo base_url()?>/assets/dist/js/print.js"></script>
        <script src="<?php echo base_url()?>/assets/dist/js/ajax_form_submission.js?v=<?php echo date('YmdH'); ?>"></script>
        <script src="<?php echo base_url()?>/assets/dist/js/common.active.js?v=<?php echo date('YmdH'); ?>"></script>
        <script src="<?php echo base_url()?>/assets/dist/js/tooltip_template.js"></script>
        <script src="<?php echo base_url()?>/assets/dist/js/sidebar.js?v=1.4"></script>
        <script src="<?php echo base_url()?>/assets/dist/js/content-placeholder.js?v=1.4"></script>
        <script type="text/javascript">
            var activityAuthCallBack = function () {
                $('#alertModal').modal('hide');
                $('.activitiesAuth')[0].reset();
                $('.activitiesAuth').removeClass('was-validated');
            }

            var userPrCallBackData = function () {
                $('#userPrModal').modal('hide');
                $('.userPrForm')[0].reset();
                $('.userPrForm').removeClass('was-validated');
            }

            $(document).ajaxStart(function() {
              //checkLoggedin();
            });

            $(document).ready(function(){
                "use strict";
                
                // checkUserActivities(); 

                /*setInterval(function(){
                    checkUserActivities();
                }, 10000);*/

                //checkLoggedin();
                //setInterval(function(){ checkLoggedin(); }, 30000);

                // // update user activities
                // $('body').on('click', function(e){
                //     $.ajax({
                //         type: 'GET',
                //         url: _baseURL+'auth/checkActivity',
                //         dataType : 'JSON',
                //         data: {'csrf_stream_name':csrf_val},
                //         success: function(data) {
                //             if(data.success===0){
                //                 window.location.href = _baseURL+ "logout";
                //             }

                //             // if(data.success===false){
                //             //     if($('#alertModal').hasClass('show')){
                //             //     }else{
                //             //         $('#alertModal').modal({backdrop:'static', keyboard:true, show:true});
                //             //     }
                //             // }else if(data.success==3){
                //             //     window.location.href = _baseURL+ "logout";
                //             // }else{
                //             // }
                //         }
                //     });   
                // });

                $('.userPrUpdate').on('click', function(e){
                    var id = $(this).attr('data-id');
                    $.ajax({
                        type: 'GET',
                        url: _baseURL+'permission/users/getUserForEdit/'+id,
                        dataType : 'JSON',
                        data: {'csrf_stream_name':csrf_val},
                        success: function(data) {
                            $('#pr_user_id').val(data.id);
                            $('#pr_emp_id').val(data.emp_id);
                            $('#pr_fullname').val(data.fullname);
                            $('#pr_username').val(data.username);
                            $('#userPrModal').modal('show');
                        }
                    });  
                });
            });

            function checkUserActivities(){
                $.ajax({
                    type: 'GET',
                    url: _baseURL+'auth/isActivity',
                    dataType : 'JSON',
                    data: {'csrf_stream_name':csrf_val},
                    success: function(data) {
                        if(data.success===0){
                            window.location.href = _baseURL+ "logout";
                        }
            
                        //console.log(data);
                        // if(data.success===false){
                        //     if($('#alertModal').hasClass('show')){
                        //     }else{
                        //         $('#alertModal').modal({backdrop:'static', keyboard:true, show:true});
                        //     }
                        // }else if(data.success==3){
                        //     window.location.href = _baseURL+ "logout";
                        // }else{
                        //     if($('#alertModal').hasClass('show')){
                        //         $('#alertModal').modal('hide');
                        //     }
                        // }
                    }
                });   
            }

            // Going back one page
            function goBackOnePage() {
              window.history.back();
            }

        </script>
        <?php
        if(ENVIRONMENT==='production'){ ?>
           <script src="<?php echo base_url()?>/assets/dist/js/element-hidden.js"></script>
        <?php }?>