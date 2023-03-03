
<nav class="navbar-custom-menu navbar navbar-expand-xl m-0">
    <div class="sidebar-toggle-icon" id="sidebarCollapse">
        sidebar toggle<span></span>
    </div><!--/.sidebar toggle icon-->
    <?php 
    if(session('defaultLang')=='english'){
        $left = '';
      }else{
        $left = 'ml-2';
      }
    ?>
    <!-- Collapse -->
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <!-- Toggler -->
        <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="true" aria-label="Toggle navigation"><span></span> <span></span></button>
        <ul class="navbar-nav hidden">
            <?php if($permission->module('wh_purchases')->access()){ ?>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo base_url('purchase/requisition');?>"><i class="typcn typcn-plus-outline top-menu-icon <?php echo $left;?>"></i>SPR <?php echo get_phrases(['entry']);?> <span class="sr-only">(current)</span></a>
            </li>
            <?php } if($permission->module('wh_machine_request')->access()){ ?>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo base_url('machine/item_request');?>"><i class="typcn typcn-plus-outline top-menu-icon <?php echo $left;?>"></i><?php echo get_phrases(['consumption', 'request']);?> <span class="sr-only">(current)</span></a>
            </li>
            <?php } if($permission->module('wh_production')->access()){ ?>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo base_url('production/item_production');?>"><i class="typcn typcn-plus-outline top-menu-icon <?php echo $left;?>"></i><?php echo get_phrases(['production', 'entry']);?> <span class="sr-only">(current)</span></a>
            </li>
            <?php } if($permission->module('wh_goods_transfer')->access()){ ?>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo base_url('goods_transfer/item_request');?>"><i class="typcn typcn-plus-outline top-menu-icon <?php echo $left;?>"></i><?php echo get_phrases(['goods', 'transfer']);?> <span class="sr-only">(current)</span></a>
            </li>
            <?php } if($permission->module('wh_delivery_approve')->access()){ ?>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo base_url('order/request_approve');?>"><i class="typcn typcn-plus-outline top-menu-icon <?php echo $left;?>"></i><?php echo get_phrases(['approve','order']);?> <span class="sr-only">(current)</span></a>
            </li>
            <?php } if($permission->module('wh_item_delivery_request')->access()){ ?>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo base_url('order/item_request');?>"><i class="typcn typcn-plus-outline top-menu-icon <?php echo $left;?>"></i><?php echo get_phrases(['staff','order']);?> <span class="sr-only">(current)</span></a>
            </li>
            <?php } ?>
             <li class="nav-item">
                <div class="visit_website">
                    <img  class="hidden content-placeholder" src="<?php echo base_url('assets/dist/img/loader.gif'); ?>" alt="" height="35px;">
                </div>
            </li>
        </ul>
    </div>
    <div class="navbar-icon d-flex">
        <ul class="navbar-nav flex-row align-items-center">
            <!-- <li class="nav-item mr-2"><i class="typcn typcn-home-outline top-menu-icon <?php //echo $left;?>"></i> <?php //echo get_phrases(['branch']);?></li>
            <li class="nav-item mr-2">
                <?php
                    //echo form_dropdown('top_branch', $top_branch_list, session('branchId'),'class="form-control" id="top_branch" ');
                ?>
            </li> 
            <li class="nav-item">
                <?php
                    /*$arrayName = array('' => '', 'english'=> 'English', 'arabic'=>'Arabic');
                    echo form_dropdown('change_language',$arrayName,session('defaultLang'),'class="form-control" id="change_language" required="required"');*/
                ?>
            </li>-->
            <li class="nav-item dropdown quick-actions hidden">
                <a class="nav-link dropdown-toggle material-ripple" href="#" data-toggle="dropdown">
                    <i class="typcn typcn-th-large-outline"></i>
                </a>
                <div class="dropdown-menu">
                    <div class="nav-grid-row row">
                        <?php if($permission->module('setting')->access()){ ?>
                        <a href="<?php echo base_url('settings/application')?>" class="icon-menu-item col-4">
                            <i class="typcn typcn-cog-outline d-block"></i>
                            <span><?php echo get_phrases(['settings']);?></span>
                        </a>
                        <?php } if($permission->module('sys_users')->access()){ ?>
                        <a href="<?php echo base_url('permission/users')?>" class="icon-menu-item col-4">
                            <i class="typcn typcn-group-outline d-block"></i>
                            <span><?php echo get_phrases(['users']);?></span>
                        </a>
                        <?php }?>
                        <a href="#" class="icon-menu-item col-4">
                            <i class="typcn typcn-puzzle-outline d-block"></i>
                            <span>Components</span>
                        </a>
                        <a href="#" class="icon-menu-item col-4">
                            <i class="typcn typcn-chart-bar-outline d-block"></i>
                            <span>Profits</span>
                        </a>
                        <a href="#" class="icon-menu-item col-4">
                            <i class="typcn typcn-time d-block"></i>
                            <span>New Event</span>
                        </a>
                        <a href="#" class="icon-menu-item col-4">
                            <i class="typcn typcn-edit d-block"></i>
                            <span>Tasks</span>
                        </a>
                    </div>
                </div>
            </li><!--/.dropdown-->
            <li class="nav-item">
                <a class="nav-link material-ripple" href="#" id="btnFullscreen"><i class="full-screen_icon typcn typcn-arrow-move-outline"></i></a>
            </li>
            
            <li class="nav-item dropdown user-menu">
                <a class="nav-link dropdown-toggle material-ripple" href="#" data-toggle="dropdown">
                    <!--<img src="<?php echo base_url();?>/assets/dist/img/user2-160x160.png" alt="">-->
                    <i class="typcn typcn-user-add-outline"></i>
                </a>
                <div class="dropdown-menu" >
                    <div class="dropdown-header d-sm-none">
                        <a href="" class="header-arrow"><i class="icon ion-md-arrow-back"></i></a>
                    </div>
                    <div class="user-header">
                        <div class="img-user">
                            <img src="<?php echo (!empty(session('image')))?base_url().session('image'):base_url('/assets/dist/img/avatar/avatar-1.png')?>" alt="">
                        </div><!-- img-user -->
                        <h6><?php echo session('fullname')?></h6>
                        <span><?php echo session('user_level')?></span>
                    </div><!-- user-header -->
                    <!-- <a href="" class="dropdown-item"><i class="typcn typcn-user-outline"></i> My Profile</a> -->
                    <a href="javascript:void(0);" class="dropdown-item userPrUpdate" data-id="<?php echo session('id')?>"><i class="typcn typcn-edit"></i> <?php echo get_phrases(['edit', 'profile']);?></a>
                    <!-- <a href="<?php //echo base_url('permission/users/userActivityLogs');?>" class="dropdown-item"><i class="typcn typcn-arrow-shuffle"></i> <?php //echo get_phrases(['activity', 'logs']);?></a>
                    <a href="" class="dropdown-item"><i class="typcn typcn-cog-outline"></i> Account Settings</a> -->
                    <a href="<?php echo base_url('logout');?>" class="dropdown-item"><i class="typcn typcn-key-outline"></i> Sign Out</a>
                </div><!--/.dropdown-menu -->
            </li>
        </ul><!--/.navbar nav-->
        
    </div>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <i class="typcn typcn-th-menu-outline"></i>
    </button>
</nav>