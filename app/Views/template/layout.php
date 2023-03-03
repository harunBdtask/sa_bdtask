<!DOCTYPE html>
<html lang="en">
<head>
<?php echo view('template/head') ?>
</head>
<body class="fixed" onFocus="parent_window_disable();" onclick="parent_window_disable();">
    <input type ="hidden" id="CSRF_TOKEN" value="<?php echo csrf_hash(); ?>">
    <script src="<?php echo base_url()?>/assets/dist/js/base.active.js?v=<?php echo date('YmdHi'); ?>"></script>
	 <!-- Page Loader -->
    <div id="preloader-wrapper" class="page-loader-wrapper hidden">
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
    <!-- #END# Page Loader -->
    

    <!-- HEADER: MENU -->

    <div class="wrapper">
    	<?php echo view('template/sidebar') ?>
    <!-- CONTENT -->
     <div class="content-wrapper">
        <div class="main-content">

        	<?php echo view('template/header') ?>

        	<div class="body-content py-2 px-2">
                <?php echo view('template/messages') ?>
            <?php
            try
            {
                $path = 'App\Modules\"'.ucfirst($module).'"\Views\"'.$page.'"';
                $withourbackpath = str_replace('/\/', '/', $path);
                $viewpath = str_replace('"', '', $withourbackpath);
                 echo view($viewpath);
            }
            catch (Exception $e)
            {
                echo "<pre><code>$e</code></pre>";
            }
            ?>
            </div>
        </div>
        <?php //echo view('template/credential_modal') ?>

        <!-- user profile -->
        <!-- user modal info -->
        <div class="modal fade bd-example-modal-lg" id="userPrModal" tabindex="-1" role="dialog" aria-labelledby="moduleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title font-weight-600" id="userPrModalLabel"><?php echo get_phrases(['update', 'user', 'information']);?></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div> 
                   <?php echo form_open_multipart('permission/users/updateUser', 'class="userPrForm needs-validation" novalidate="" data="userPrCallBackData"');?>
                    <div class="modal-body">
                        <input type="hidden" name="user_id" id="pr_user_id" />
                        <input type="hidden" name="emp_id" id="pr_emp_id" />
                        <input type="hidden" name="action" id="pr_action" value="update" />
                         <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="form-group">
                                    <label class="font-weight-600"><?php echo get_phrases(['full', 'name']);?> <i class="text-danger">*</i></label>
                                    <input type="text" name="fullname" id="pr_fullname" class="form-control" placeholder="<?php echo get_phrases(['enter', 'full', 'name']);?>" maxlength="80" required readonly/>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label class="font-weight-600"><?php echo get_phrases(['user', 'name']);?> <i class="text-danger">*</i></label>
                                    <input type="text" name="username" id="pr_username" class="form-control" placeholder="<?php echo get_phrases(['enter', 'user', 'name']);?>" maxlength="80" required readonly/>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label class="font-weight-600"><?php echo get_phrases(['password']);?> <i class="text-danger"></i></label>
                                    <input type="password" name="password" id="pr_password" class="form-control" placeholder="<?php echo get_phrases(['enter', 'password']);?>" minlength="6" maxlength="32" autocomplete="off" data-toggle="tooltip" data-field="Password" title="Please enter minlength 6 characters" />
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo get_phrases(['close']);?></button>
                        <button type="submit" class="btn btn-success"><?php echo get_phrases(['update']);?></button>
                    </div>
                    <?php echo form_close();?>
                </div>
            </div>
        </div>

    <!-- FOOTER: DEBUG INFO + COPYRIGHTS -->
    <?php echo view('template/footer') ?>
    <script>
// function menuSearch() {
//   var input, filter, ul, li, a, i;
//   input = document.getElementById("menuSearchbar");
//   filter = input.value.toUpperCase();
//   ul = document.getElementById("sidebarmenu");
//   li = ul.getElementsByTagName("li");
//   for (i = 0; i < li.length; i++) {
//     a = li[i].getElementsByTagName("a")[0];
//     if (a.innerHTML.toUpperCase().indexOf(filter) > -1) {
//       li[i].style.display = "";
//     } else {
//       li[i].style.display = "none";
//     }
//   }

// }

$(document).ready(function () {
    $("#search").keyup(function () {
        var filter = $(this).val();
        $(`nav li:not(.sidebar-search)`).each(function (index, element) {
          const item = $(element);
          const parentListIsNested = item.closest('ul').hasClass('nav-second-level');
        
          if (item.text().match(new RegExp(filter, 'gi'))) {
            item.fadeIn();
            if (parentListIsNested){
              item.closest('ul').addClass('in');
            }
          } else {
            item.fadeOut();
            if (parentListIsNested){
              item.closest('ul').removeClass('in');
            }
          }
        });
    });
});
</script>
</body>
</html>
