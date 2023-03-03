<!doctype html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="Responsive Bootstrap 4 Admin &amp; Dashboard Template">
        <meta name="author" content="Bdtask">
        <title><?php echo $title.' :: '.$setting->title;?></title>
        <!-- App favicon -->
        <link rel="shortcut icon" href="<?php echo !empty($setting->favicon)?base_url().$setting->favicon:base_url()."/assets/dist/img/favicon.png";?>">
        <!--Global Styles(used by all pages)-->
        <link href="<?php echo base_url()?>/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="<?php echo base_url()?>/assets/plugins/metisMenu/metisMenu.min.css" rel="stylesheet">
        <link href="<?php echo base_url()?>/assets/plugins/fontawesome/css/all.min.css" rel="stylesheet">
        <link href="<?php echo base_url()?>/assets/plugins/typicons/src/typicons.min.css" rel="stylesheet">
        <link href="<?php echo base_url()?>/assets/plugins/themify-icons/themify-icons.min.css" rel="stylesheet">
        <!--Third party Styles(used by this page)--> 

        <!--Start Your Custom Style Now-->
        <link href="<?php echo base_url()?>/assets/dist/css/style.css" rel="stylesheet">
    </head>
    <body class="bg-white">
        <!-- Page Loader -->
        <div class="page-loader-wrapper">
            <div class="loader">
                <div class="preloader">
                    <div class="spinner-layer pl-green">
                        <div class="circle-clipper left">
                            <div class="circle"></div>
                        </div>
                        <div class="circle-clipper right">
                            <div class="circle"></div>
                        </div>
                    </div>
                </div>
                <p><?php echo get_phrases(['please', 'wait']);?>...</p>
            </div>
        </div>
        <div class="d-flex align-items-center justify-content-center text-center h-100vh">
            <div class="form-wrapper m-auto">
                <div class="form-container my-4">
                    <div class="register-logo text-center mb-4">
                        <img src="<?php echo !empty($setting->logo)?base_url().$setting->logo:base_url()."/assets/dist/img/logo.png";?>" class="" alt="<?php echo get_phrases(['logo'])?>" height="150">
                    </div>
                    <div class="panel">
                        <div class="panel-header text-center mb-3">
                            <h3 class="fs-24"><?php echo $setting->title;?></h3>
                            <p class="text-muted text-center mb-0"><?php echo get_notify('Nice_to_see_you!_Please_log_in_with_your_account');?>.</p>
                        </div>
                      <?php if (isset($validation)): ?>
                        <div class="col-12">
                          <div class="alert alert-danger" role="alert">
                            <?php echo $validation->listErrors() ?>
                          </div>
                        </div>
                      <?php endif; ?>
                        <?php if (isset($exception)): ?>
                        <div class="col-12">
                          <div class="alert alert-danger" role="alert">
                            <?php echo $exception; ?>
                          </div>
                        </div>
                      <?php endif; ?>

                        <?php echo form_open('auth/login', 'novalidate')?>
                            <div class="form-group">
                                <div class="icon-addon addon-md">
                                    <input type="text" class="form-control" id="username" name="username" placeholder="<?php echo get_phrases(['enter', 'user', 'name']);?>">
                                    <label class="ti-user" title="<?php echo get_phrases(['user', 'name']);?>"></label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="icon-addon addon-md">
                                    <input type="password" class="form-control" id="pass" name="password" placeholder="<?php echo get_phrases(['enter', 'password']);?>">
                                    <label class="ti-lock" title="<?php echo get_phrases(['password']);?>"></label>
                                </div>
                            </div>
                            <!-- <div class="form-group">
                                <div class="icon-addon addon-md">
                                    <?php //echo form_dropdown('branch_id', $branch_list, '','id="branch_id" class="form-control"'); ?>
                                    <label class="ti-home" title="<?php echo get_phrases(['branch']);?>"></label>
                                </div>
                            </div> -->
                            <div class="custom-control custom-checkbox mb-3">
                                <input type="hidden" name="branch_id" id="branch_id" value="7">
                                <input type="checkbox" class="custom-control-input" id="customCheck1">
                                <label class="custom-control-label" for="customCheck1">Remember me next time </label>
                            </div>
                            <button type="submit" class="btn btn-success btn-block">Sign in</button>
                        <?php echo form_close();?>
                        <div class="panel-footer m-t-10">
                            <table style="cursor:pointer;font-size:12px" class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>Email</th>
                                    <th>Password</th>
                                    <th>Role</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>admin123</td>
                                    <td>123456</td>
                                    <td>Admin</td>
                                </tr>
                                <!-- <tr>
                                    <td>doctor123</td>
                                    <td>123456</td>
                                    <td>Doctor</td>
                                </tr>
                                <tr>
                                    <td>nurse123</td>
                                    <td>123456</td>
                                    <td>Nurse</td>
                                </tr>
                                <tr>
                                    <td>ahad123</td>
                                    <td>123456</td>
                                    <td>Hrm</td>
                                </tr>
                                <tr>
                                    <td>alianizi123</td>
                                    <td>123456</td>
                                    <td>Hrm</td>
                                </tr>
                                <tr>
                                    <td>pharmacist@gmail.com</td>
                                    <td>123456</td>
                                    <td>Pharmacist</td>
                                </tr>
                                <tr>
                                    <td>receptionist@demo.com</td>
                                    <td>123456</td>
                                    <td>Receptionist</td>
                                </tr>
                                <tr>
                                    <td>representative@demo.com</td>
                                    <td>123456</td>
                                    <td>Representative</td>
                                </tr>
                                 <tr>
                                    <td>radiologist@demo.com</td>
                                    <td>123456</td>
                                    <td>Radiologist</td>
                                </tr>
                                <tr>
                                    <td>case@demo.com</td>
                                    <td>123456</td>
                                    <td>Case Manager</td>
                                </tr> -->
                                
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
        <!-- /.End of form wrapper -->
        <!--Global script(used by all pages)-->
        <script src="<?php echo base_url()?>/assets/plugins/jQuery/jquery.min.js"></script>
        <script src="<?php echo base_url()?>/assets/dist/js/popper.min.js"></script>
        <script src="<?php echo base_url()?>/assets/plugins/bootstrap/js/bootstrap.min.js"></script>
        <script src="<?php echo base_url()?>/assets/plugins/metisMenu/metisMenu.min.js"></script>
        <script src="<?php echo base_url()?>/assets/plugins/perfect-scrollbar/dist/perfect-scrollbar.min.js"></script>
        <!-- Third Party Scripts(used by this page)-->

        <!--Page Scripts(used by all page)-->
        <script src="<?php echo base_url()?>/assets/dist/js/sidebar.js"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                setTimeout(function () {
                $('.page-loader-wrapper').fadeOut();
            }, 50);
                var info = $('table tbody tr');
                info.click(function() {
                    var username    = $(this).children().first().text();
                    var password = $(this).children().first().next().text();
                   
                    $("input[type=text]").val(username);
                    $("input[type=password]").val(password);
                });
            });
        </script>
       
    </body>
</html>