
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="Responsive Bootstrap 4 Admin &amp; Dashboard Template">
        <meta name="author" content="<?php echo $settings_info->title;?>">
        <title><?php echo $title.' | '.$moduleTitle;?></title>
        <!-- App favicon -->
        <link rel="shortcut icon" href="<?php echo base_url().$settings_info->favicon?>">
        <!--Global Styles(used by all pages)-->
        <?php if(session('site_align')=='right-to-left'){ ?>
            <link href="<?php echo base_url()?>/assets/plugins/bootstrap/css/rtl/bootstrap-rtl.min.css" rel="stylesheet">
            <link href="<?php echo base_url()?>/assets/plugins/metisMenu/metisMenu-rtl.css" rel="stylesheet" type="text/css"/>
        <?php }else{ ?>
            <link href="<?php echo base_url()?>/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
            <link href="<?php echo base_url()?>/assets/plugins/metisMenu/metisMenu.min.css" rel="stylesheet">
        <?php }?>
        <link href="<?php echo base_url()?>/assets/plugins/fontawesome/css/all.min.css" rel="stylesheet">
        <link href="<?php echo base_url()?>/assets/plugins/typicons/src/typicons.min.css" rel="stylesheet">
        <link href="<?php echo base_url()?>/assets/plugins/themify-icons/themify-icons.min.css" rel="stylesheet">
        <!--Third party Styles(used by this page)--> 
        <!-- Toastr css  -->
        <link href="<?php echo base_url()?>/assets/plugins/toastr/toastr.css" rel="stylesheet" type="text/css"/>
        <!-- select2 dropdown -->
        <link href="<?php echo base_url()?>/assets/plugins/select2/dist/css/select2.min.css" rel="stylesheet">
        <link href="<?php echo base_url()?>/assets/plugins/select2-bootstrap4/dist/select2-bootstrap4.min.css" rel="stylesheet">

        <!--Start Your Custom Style Now-->
        <link href="<?php echo base_url()?>/assets/dist/css/style.css" rel="stylesheet">
        <link href="<?php echo base_url()?>/assets/dist/css/custom.css?v=<?php echo date('Ymd'); ?>" rel="stylesheet">
        <link href="<?php echo base_url()?>/assets/dist/css/custom.style.css?v=<?php echo date('Ymd'); ?>" rel="stylesheet">
        <!-- <link href="<?php //echo base_url()?>/assets/dist/css/style.rtl.css" rel="stylesheet"> -->

        <?php if(isset($isDTables) && !empty($isDTables)){ ?>
         <link href="<?php echo base_url()?>/assets/plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
        <link href="<?php echo base_url()?>/assets/plugins/datatables/responsive.bootstrap4.min.css" rel="stylesheet">
        <link href="<?php echo base_url()?>/assets/plugins/datatables/buttons.bootstrap4.min.css" rel="stylesheet">
        <?php } if(isset($isDateTimes) && !empty($isDateTimes)){ ?>
        <link href="<?php echo base_url()?>/assets/plugins/daterangepicker/daterangepicker.css" rel="stylesheet">
        <?php }?>
         
        <script src="<?php echo base_url()?>/assets/plugins/jQuery/jquery.min.js"></script>

        <script src="<?php echo base_url()?>/assets/plugins/canvas-pdf/jspdf.min.js"></script>
        <script src="<?php echo base_url()?>/assets/plugins/canvas-pdf/html2canvas.js"></script>
            
        <script type="text/javascript">
            var parentWindow=null;
            function parent_window_disable() {
                if(parentWindow && !parentWindow.closed)
                    parentWindow.focus();
            }
        </script>