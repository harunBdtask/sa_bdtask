<style type="text/css">
    @media (max-width: 768px) {
        .carousel-inner .carousel-item>div {
            display: none;
        }

        .carousel-inner .carousel-item>div:first-child {
            display: block;
        }
    }

    .carousel-inner .carousel-item.active,
    .carousel-inner .carousel-item-next,
    .carousel-inner .carousel-item-prev {
        display: flex;
    }

    /* display 3 */
    @media (min-width: 768px) {

        .carousel-inner .carousel-item-right.active,
        .carousel-inner .carousel-item-next {
            transform: translateX(33.333%);
        }

        .carousel-inner .carousel-item-left.active,
        .carousel-inner .carousel-item-prev {
            transform: translateX(-33.333%);
        }
    }

    .carousel-inner .carousel-item-right,
    .carousel-inner .carousel-item-left {
        transform: translateX(0);
    }

    .page-loader-wrapper {
        display: none;
    }
</style>

<?php if(session('isAdmin')){ ?>

<div class="fluid-container">
    <div class="col-lg-12 col-xl-12">
        <div class="row">
            <?php if ($permission->module('store')->access()) { ?>
                <div class="col-md-3 col-sm-12 d-flex">
                    <!--Feedback-->
                    <div class="d-flex position-relative overflow-hidden flex-column p-3 mb-3 bg-gradient-custom-orange shadow-sm rounded flex-fill w-100">
                        <div class="header-pretitle text-white fs-11 fw-bold text-uppercase mb-2"><?php echo get_phrases(['raw', 'material']); ?></div>
                        <i class="fas fa fa-smile opacity-25 fa-5x text-white decorative-icon"></i>
                        <div class="align-items-center d-flex mt-3">
                            <div>
                                <i class="d-block rounded-circle fas fa-list-alt fa-4x opacity-75 text-white"></i>
                            </div>
                            <div class="pl-15">
                                <a class="opacity-75 fs-20 font-weight-600 text-white" href="<?php echo base_url('store/material_stock') ?>"><?php echo get_phrases(['material', 'stock']); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php }
            if ($permission->module('purchase')->access()) { ?>
                <div class="col-md-3 col-sm-12 d-flex">
                    <!--Feedback-->
                    <div class="d-flex position-relative overflow-hidden flex-column p-3 mb-3 bg-gradient-custom-purple shadow-sm rounded flex-fill w-100">
                        <div class="header-pretitle text-white fs-11 fw-bold text-uppercase mb-2"><?php echo get_phrases(['local', 'purchase']); ?></div>
                        <i class="fas fa fa-smile opacity-25 fa-5x text-white decorative-icon"></i>
                        <div class="align-items-center d-flex mt-3">
                            <div>
                                <i class="d-block rounded-circle fas fa-shopping-cart fa-4x opacity-75 text-white"></i>
                            </div>
                            <div class="pl-15">
                                <a class="opacity-75 fs-20 font-weight-600 text-white" href="<?php echo base_url('purchase/requisition') ?>"><?php echo get_phrases(['purchase', 'requisition']); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php }
            if ($permission->module('lc')->access()) { ?>
                <div class="col-md-3 col-sm-12 d-flex">
                    <!--Feedback-->
                    <div class="d-flex position-relative overflow-hidden flex-column p-3 mb-3 bg-gradient-custom-pink shadow-sm rounded flex-fill w-100">
                        <div class="header-pretitle text-white fs-11 fw-bold text-uppercase mb-2"><?php echo get_phrases(['foreign', 'purchase']); ?></div>
                        <i class="fas fa fa-smile opacity-25 fa-5x text-white decorative-icon"></i>
                        <div class="align-items-center d-flex mt-3">
                            <div>
                                <i class="d-block rounded-circle fas fa-cart-plus fa-4x opacity-75 text-white"></i>
                            </div>
                            <div class="pl-15">
                                <a class="opacity-75 fs-20 font-weight-600 text-white" href="<?php echo base_url('lc/lcs') ?>">Letter of Credit</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php }
            if ($permission->module('wh_machine_request')->access()) { ?>
                <div class="col-md-3 col-sm-12 d-flex">
                    <!--Feedback-->
                    <div class="d-flex position-relative overflow-hidden flex-column p-3 mb-3 bg-gradient-custom-teal shadow-sm rounded flex-fill w-100">
                        <div class="header-pretitle text-white fs-11 fw-bold text-uppercase mb-2"><?php echo get_phrases(['production']); ?></div>
                        <i class="fas fa fa-smile opacity-25 fa-5x text-white decorative-icon"></i>
                        <div class="align-items-center d-flex mt-3">
                            <div>
                                <i class="d-block rounded-circle fas fa-exchange-alt fa-4x opacity-75 text-white"></i>
                            </div>
                            <div class="pl-15">
                                <a class="opacity-75 fs-20 font-weight-600 text-white" href="<?php echo base_url('production/recipe') ?>"><?php echo get_phrases(['production', 'recipe']); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php }
            if ($permission->module('wh_production')->access()) { ?>
                <div class="col-md-3 col-sm-12 d-flex">
                    <!--Feedback-->
                    <div class="d-flex position-relative overflow-hidden flex-column p-3 mb-3 bg-gradient-custom-blue shadow-sm rounded flex-fill w-100">
                        <div class="header-pretitle text-white fs-11 fw-bold text-uppercase mb-2"><?php echo get_phrases(['finished', 'goods']); ?></div>
                        <i class="fas fa fa-smile opacity-25 fa-5x text-white decorative-icon"></i>
                        <div class="align-items-center d-flex mt-3">
                            <div>
                                <i class="d-block rounded-circle fas fa-list fa-4x opacity-75 text-white"></i>
                            </div>
                            <div class="pl-15">
                                <a class="opacity-75 fs-20 font-weight-600 text-white" href="<?php echo base_url('finished_goods/main_stock') ?>"><?php echo get_phrases(['stock', 'balance']); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php }
            if ($permission->module('sale')->access()) { ?>
                <div class="col-md-3 col-sm-12 d-flex">
                    <!--Feedback-->
                    <div class="d-flex position-relative overflow-hidden flex-column p-3 mb-3 bg-gradient-custom-yellow shadow-sm rounded flex-fill w-100">
                        <div class="header-pretitle text-white fs-11 fw-bold text-uppercase mb-2"><?php echo get_phrases(['sales']); ?></div>
                        <i class="fas fa fa-smile opacity-25 fa-5x text-white decorative-icon"></i>
                        <div class="align-items-center d-flex mt-3">
                            <div>
                                <i class="d-block rounded-circle fas fa-people-carry fa-4x opacity-75 text-white"></i>
                            </div>
                            <div class="pl-15">
                                <a class="opacity-75 fs-20 font-weight-600 text-white" href="<?php echo base_url('sale/deliver_order/do_list') ?>"><?php echo get_phrases(['sales', 'admin']); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php }
            if ($permission->module('account')->access()) { ?>
                <div class="col-md-3 col-sm-12 d-flex">
                    <!--Feedback-->
                    <div class="d-flex position-relative overflow-hidden flex-column p-3 mb-3 bg-gradient-custom-green shadow-sm rounded flex-fill w-100">
                        <div class="header-pretitle text-white fs-11 fw-bold text-uppercase mb-2"><?php echo get_phrases(['accounts']); ?></div>
                        <i class="fas fa fa-smile opacity-25 fa-5x text-white decorative-icon"></i>
                        <div class="align-items-center d-flex mt-3">
                            <div>
                                <i class="d-block rounded-circle fas fa-file-invoice-dollar fa-4x opacity-75 text-white"></i>
                            </div>
                            <div class="pl-15">
                                <a class="opacity-75 fs-20 font-weight-600 text-white" href="<?php echo base_url('account/vouchers/journal') ?>"><?php echo get_phrases(['journal', 'vouchers']); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php }
            if ($permission->module('wh_item_delivery_request')->access()) { ?>
                <div class="col-md-3 col-sm-12 d-flex">
                    <!--Feedback-->
                    <div class="d-flex position-relative overflow-hidden flex-column p-3 mb-3 bg-gradient-custom-light-1 shadow-sm rounded flex-fill w-100">
                        <div class="header-pretitle text-white fs-11 fw-bold text-uppercase mb-2"><?php echo get_phrases(['human', 'resource']); ?></div>
                        <i class="fas fa fa-smile opacity-25 fa-5x text-white decorative-icon"></i>
                        <div class="align-items-center d-flex mt-3">
                            <div>
                                <i class="d-block rounded-circle fas fa-file-invoice fa-4x opacity-75 text-white"></i>
                            </div>
                            <div class="pl-15">
                                <a class="opacity-75 fs-20 font-weight-600 text-white" href="<?php echo base_url('payroll/salary_payment') ?>"><?php echo get_phrases(['salary', 'payment']); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>

<?php } ?>

<!-- For employee dashboard tab reports -->

<?php

 // echo "<pre>";
 // print_r(session('id'));

 if(!session('isAdmin') && session('id') > 0){

    if(!$is_hrm) {

?>

    <div class="fluid-container">
        <div class="col-lg-12 col-xl-12">
            <div class="row">
                    <div class="col-md-3 col-sm-12 d-flex">
                        <!--Feedback-->
                        <div class="d-flex position-relative overflow-hidden flex-column p-3 mb-3 bg-gradient-custom-orange shadow-sm rounded flex-fill w-100">
                            <div class="header-pretitle text-white fs-11 fw-bold text-uppercase mb-2"><?php echo get_notify('total_leave_taken'); ?> (Current year)</div>
                            <i class="fas fa fa-smile opacity-25 fa-5x text-white decorative-icon"></i>
                            <div class="align-items-center d-flex mt-3">
                                <div>
                                    <i class="d-block rounded-circle fas fa-list-alt fa-4x opacity-75 text-white"></i>
                                </div>
                                <div class="pl-15">
                                    <p class="opacity-75 fs-20 font-weight-600 text-white" id="total_leave_taken"><?php echo $total_leave_taken;?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-12 d-flex">
                        <!--Feedback-->
                        <div class="d-flex position-relative overflow-hidden flex-column p-3 mb-3 bg-gradient-custom-purple shadow-sm rounded flex-fill w-100">
                            <div class="header-pretitle text-white fs-11 fw-bold text-uppercase mb-2"><?php echo get_notify('total_leave_remaining'); ?> (Current year)</div>
                            <i class="fas fa fa-smile opacity-25 fa-5x text-white decorative-icon"></i>
                            <div class="align-items-center d-flex mt-3">
                                <div>
                                    <i class="d-block rounded-circle fas fa-list-alt fa-4x opacity-75 text-white"></i>
                                </div>
                                <div class="pl-15">
                                    <p class="opacity-75 fs-20 font-weight-600 text-white" id="total_leave_remaining"><?php echo $total_leave_remaining;?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-12 d-flex">
                        <!--Feedback-->
                        <div class="d-flex position-relative overflow-hidden flex-column p-3 mb-3 bg-gradient-custom-pink shadow-sm rounded flex-fill w-100">
                            <div class="header-pretitle text-white fs-11 fw-bold text-uppercase mb-2"><?php echo get_notify('total_present'); ?> (Current month)</div>
                            <i class="fas fa fa-smile opacity-25 fa-5x text-white decorative-icon"></i>
                            <div class="align-items-center d-flex mt-3">
                                <div>
                                    <i class="d-block rounded-circle fas fa-user fa-4x opacity-75 text-white"></i>
                                </div>
                                <div class="pl-15">
                                    <p class="opacity-75 fs-20 font-weight-600 text-white" id="total_present"><?php echo $total_present;?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-12 d-flex">
                        <!--Feedback-->
                        <div class="d-flex position-relative overflow-hidden flex-column p-3 mb-3 bg-gradient-custom-teal shadow-sm rounded flex-fill w-100">
                            <div class="header-pretitle text-white fs-11 fw-bold text-uppercase mb-2"><?php echo get_notify('total_absent'); ?> (Current month)</div>
                            <i class="fas fa fa-smile opacity-25 fa-5x text-white decorative-icon"></i>
                            <div class="align-items-center d-flex mt-3">
                                <div>
                                    <i class="d-block rounded-circle fas fa-user fa-4x opacity-75 text-white"></i>
                                </div>
                                <div class="pl-15">
                                    <p class="opacity-75 fs-20 font-weight-600 text-white" id="total_absent"><?php echo $total_absent;?></p>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>

    <?php }else{?>

    <div class="fluid-container">
        <div class="col-lg-12 col-xl-12">
            <div class="row">

                    <div class="col-md-3 col-sm-12 d-flex">
                        <!--Feedback-->
                        <div class="d-flex position-relative overflow-hidden flex-column p-3 mb-3 bg-gradient-custom-pink shadow-sm rounded flex-fill w-100">
                            <div class="header-pretitle text-white fs-11 fw-bold text-uppercase mb-2"><?php echo get_notify('total_employees'); ?></div>
                            <i class="fas fa fa-smile opacity-25 fa-5x text-white decorative-icon"></i>
                            <div class="align-items-center d-flex mt-3">
                                <div>
                                    <i class="d-block rounded-circle fas fa-users fa-4x opacity-75 text-white"></i>
                                </div>
                                <div class="pl-15">
                                    <p class="opacity-75 fs-20 font-weight-600 text-white" id="total_present"><?php echo $total_employees;?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-12 d-flex">
                        <!--Feedback-->
                        <div class="d-flex position-relative overflow-hidden flex-column p-3 mb-3 bg-gradient-custom-teal shadow-sm rounded flex-fill w-100">
                            <div class="header-pretitle text-white fs-11 fw-bold text-uppercase mb-2"><?php echo get_notify('hired_employees'); ?> (Current month)</div>
                            <i class="fas fa fa-smile opacity-25 fa-5x text-white decorative-icon"></i>
                            <div class="align-items-center d-flex mt-3">
                                <div>
                                    <i class="d-block rounded-circle fas fa-users fa-4x opacity-75 text-white"></i>
                                </div>
                                <div class="pl-15">
                                    <p class="opacity-75 fs-20 font-weight-600 text-white" id="total_absent"><?php echo $hired_employees;?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-12 d-flex">
                        <!--Feedback-->
                        <div class="d-flex position-relative overflow-hidden flex-column p-3 mb-3 bg-gradient-custom-orange shadow-sm rounded flex-fill w-100">
                            <div class="header-pretitle text-white fs-11 fw-bold text-uppercase mb-2"><?php echo get_notify('total_attendance'); ?> (Today)</div>
                            <i class="fas fa fa-smile opacity-25 fa-5x text-white decorative-icon"></i>
                            <div class="align-items-center d-flex mt-3">
                                <div>
                                    <i class="d-block rounded-circle fas fa-list-alt fa-4x opacity-75 text-white"></i>
                                </div>
                                <div class="pl-15">
                                    <p class="opacity-75 fs-20 font-weight-600 text-white" id="total_leave_taken"><?php echo $total_present_employees;?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-12 d-flex">
                        <!--Feedback-->
                        <div class="d-flex position-relative overflow-hidden flex-column p-3 mb-3 bg-gradient-custom-purple shadow-sm rounded flex-fill w-100">
                            <div class="header-pretitle text-white fs-11 fw-bold text-uppercase mb-2"><?php echo get_notify('total_absent'); ?> (Today)</div>
                            <i class="fas fa fa-smile opacity-25 fa-5x text-white decorative-icon"></i>
                            <div class="align-items-center d-flex mt-3">
                                <div>
                                    <i class="d-block rounded-circle fas fa-list-alt fa-4x opacity-75 text-white"></i>
                                </div>
                                <div class="pl-15">
                                    <p class="opacity-75 fs-20 font-weight-600 text-white" id="total_leave_remaining"><?php echo $total_absent_employees;?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-12 d-flex">
                        <!--Feedback-->
                        <div class="d-flex position-relative overflow-hidden flex-column p-3 mb-3 bg-gradient-custom-green shadow-sm rounded flex-fill w-100">
                            <div class="header-pretitle text-white fs-11 fw-bold text-uppercase mb-2"><?php echo get_notify('birth_date'); ?> (Current month)</div>
                            <i class="fas fa fa-smile opacity-25 fa-5x text-white decorative-icon"></i>
                            <div class="align-items-center d-flex mt-3">
                                <div>
                                    <i class="d-block rounded-circle fas fa-birthday-cake fa-4x opacity-75 text-white"></i>
                                </div>
                                <div class="pl-15">
                                    <p class="opacity-75 fs-20 font-weight-600 text-white" id="total_leave_remaining"><?php echo $birth_dates;?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-12 d-flex">
                        <!--Feedback-->
                        <div class="d-flex position-relative overflow-hidden flex-column p-3 mb-3 bg-gradient-custom-yellow shadow-sm rounded flex-fill w-100">
                            <div class="header-pretitle text-white fs-11 fw-bold text-uppercase mb-2"><?php echo get_notify('departments'); ?></div>
                            <i class="fas fa fa-smile opacity-25 fa-5x text-white decorative-icon"></i>
                            <div class="align-items-center d-flex mt-3">
                                <div>
                                    <i class="d-block rounded-circle fas fa-users-cog fa-4x opacity-75 text-white"></i>
                                </div>
                                <div class="pl-15">
                                    <p class="opacity-75 fs-20 font-weight-600 text-white" id="total_leave_remaining"><?php echo $departments;?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
            </div>
        </div>
    </div>

    <!-- <div class="fluid-container">
        <div class="col-lg-12 col-xl-12">
            <div class="row">

                <?php echo form_open_multipart("dashboard/home/", 'class="" id="filterForm"') ?>

                    <div class="form-group row">

                         <label for="designation" class="col-md-6 text-right col-form-label"><?php echo get_phrases(['department'])?> <i class="text-danger"> * </i>:</label>
                        <div class="col-md-6">
                            <div class="">
                                
                                <?php echo  form_dropdown('department',$departments,'', 'class="form-control custom-select select2" required') ?>

                            </div>
                           
                        </div>
                      
                    </div>

                <?php echo form_close();?>

            </div>
        </div>
    </div> -->


    <?php } ?>


    <div class="row">
        <div class="col-lg-12">
            <div class="row" id="appointResultInfo">
                <div class="col-md-12 col-sm-12">
                    <!--Time on site indicator-->
                    <?php if(!empty($best_employees)){  ?>
                    <div class="text-center my-3">
                        <h4 class="font-weight-light"><?php echo get_notify('best_employees'); ?></h4>
                        <div class="row mx-auto my-auto">
                            <div id="recipeCarousel4" class="carousel slide w-100" data-ride="carousel">
                                <div class="carousel-inner w-100" role="listbox" id="section-5">

                                    <?php

                                    $i = 1;

                                    foreach ($best_employees as $best_employee) {
                                    
                                    ?>

                                        <div class="carousel-item <?php if($i == 1){echo 'active';}else{echo '';}?>">
                                            <div class="col-md-4">
                                                <div class="card card-body p-2">
                                                    <strong class="text-secondary"><?php echo $best_employee['month_name'].' '.$best_employee['year'];?></strong>
                                                    <a href="#" class="btn btn-success"><?php echo $best_employee['employee_name'];?></a>
                                                    <strong class="text-success p-2"><!-- 23% --></strong>
                                                </div>
                                            </div>
                                        </div>

                                    <?php 

                                        $i++;

                                        } 

                                    ?>

                                </div>
                                <a class="carousel-control-prev bg-transparent w-aut" href="#recipeCarousel4" role="button" data-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Previous</span>
                                </a>
                                <a class="carousel-control-next bg-transparent w-aut" href="#recipeCarousel4" role="button" data-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Next</span>
                                </a>

                            </div>
                        </div>
                    </div>
                <?php } ?>
                </div>
            </div>
        </div>
    </div>

<?php }?>

<?php if($permission->check_label('wh_material_stock')->access() || $permission->module('wh_material_receive')->access() || $permission->module('wh_main_stock')->access() || $permission->module('deliverysection_do_list')->access() || $permission->module('attendance_log')->access()){ ?>
<!-- End for employee dashboard tab reports -->
<div class="modal fade bd-example-modal-xl" id="alertmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel3" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="itemPurchaseDetailsModalLabel"><?php echo get_phrases(['notification', 'alert']); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php if ($permission->module('wh_material_stock')->access()) {
                    if ($alertdata) { ?>
                        <h5 class="font-weight-300"><?php echo get_phrases(['Raw', 'materials', 'low', 'stock']); ?>:</h5>
                        <table class="table display table-bordered table-striped  table-sm">
                            <thead>
                                <tr class="bg-success text-white">
                                    <th class="text-center"><?php echo get_phrases(['sl']); ?></th>
                                    <th class="text-center"><?php echo get_phrases(['item', 'code']); ?></th>
                                    <th class="text-center"><?php echo get_phrases(['item', 'name']); ?></th>
                                    <th class="text-center"><?php echo get_phrases(['stock']); ?></th>
                                    <th class="text-center"><?php echo get_phrases(['store']); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($alertdata as $key => $v) { ?>
                                    <tr>
                                        <td width="5%" align="center"><?php echo $v['sl']; ?></td>
                                        <td width="5%" align="center"><?php echo $v['item_code']; ?></td>
                                        <td width="5%" align="center"><?php echo $v['item_name']; ?></td>
                                        <td width="5%" align="center"><?php echo $v['stock']; ?></td>
                                        <td width="5%" align="center"><?php echo $v['store_name']; ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    <?php }
                }
                if ($permission->module('wh_material_receive')->access()) {
                    if (($supplier_ad)) {
                    ?>
                        <h5 class="font-weight-300"><?php echo get_phrases(['supplier', 'delivery', 'pending', 'status']); ?>:</h5>
                        <table class="table display table-bordered table-striped  table-sm">
                            <thead>
                                <tr class="bg-success text-white">
                                    <th class="text-center"><?php echo get_phrases(['sl']); ?></th>
                                    <th class="text-center"><?php echo get_phrases(['po']); ?></th>
                                    <th class="text-center"><?php echo get_phrases(['order', 'date']); ?></th>
                                    <th class="text-center"><?php echo get_phrases(['supplier', 'name']); ?></th>
                                    <th class="text-center"><?php echo get_phrases(['item']); ?></th>
                                    <th class="text-center"><?php echo get_phrases(['total', 'qty']); ?></th>
                                    <th class="text-center"><?php echo get_phrases(['receive', 'qty']); ?></th>
                                    <th class="text-center"><?php echo get_phrases(['remain', 'qty']); ?></th>
                                    <th class="text-center"><?php echo get_phrases(['unit']); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($supplier_ad as $key => $v) { ?>
                                    <tr>
                                        <td width="5%" align="center"><?php echo $v['sl']; ?></td>
                                        <td width="5%" align="center"><?php echo $v['po']; ?></td>
                                        <td width="5%" align="center"><?php echo $v['o_date']; ?></td>
                                        <td width="5%" align="center"><?php echo $v['supplier']; ?></td>
                                        <td width="5%" align="center"><?php echo $v['item_name']; ?></td>
                                        <td width="5%" align="center"><?php echo $v['total_qty']; ?></td>
                                        <td width="5%" align="center"><?php echo $v['receive_qty']; ?></td>
                                        <td width="5%" align="center"><?php echo $v['avail_qty']; ?></td>
                                        <td width="5%" align="center"><?php echo $v['unit']; ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    <?php }
                }
                if ($permission->module('wh_main_stock')->access()) {
                    if (($prodcudtion_ad)) {
                    ?>
                        <h5 class="font-weight-300"><?php echo get_phrases(['Finished', 'goods', 'low', 'stock']); ?>:</h5>
                        <table class="table display table-bordered table-striped  table-sm">
                            <thead>
                                <tr class="bg-success text-white">
                                    <th class="text-center"><?php echo get_phrases(['sl']); ?></th>
                                    <th class="text-center"><?php echo get_phrases(['item', 'code']); ?></th>
                                    <th class="text-center"><?php echo get_phrases(['item', 'name']); ?></th>
                                    <th class="text-center"><?php echo get_phrases(['stock']); ?></th>
                                    <th class="text-center"><?php echo get_phrases(['store']); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($prodcudtion_ad as $key => $v) { ?>
                                    <tr>
                                        <td width="5%" align="center"><?php echo $v['sl']; ?></td>
                                        <td width="5%" align="center"><?php echo $v['item_code']; ?></td>
                                        <td width="5%" align="center"><?php echo $v['item_name']; ?></td>
                                        <td width="5%" align="center"><?php echo $v['stock']; ?></td>
                                        <td width="5%" align="center"><?php echo $v['store_name']; ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    <?php }
                }
                if ($permission->module('deliverysection_do_list')->access()) {
                    if (($sales_ad)) {
                    ?>
                        <h5 class="font-weight-300"><?php echo get_phrases(['sales', 'delivery', 'pending', 'status']); ?>:</h5>
                        <table class="table display table-bordered table-striped  table-sm">
                            <thead>
                                <tr class="bg-success text-white">
                                    <th class="text-center"><?php echo get_phrases(['sl']); ?></th>
                                    <th class="text-center"><?php echo get_phrases(['dealer', 'name']); ?></th>
                                    <th class="text-center"><?php echo get_phrases(['item']); ?></th>
                                    <th class="text-center"><?php echo get_phrases(['total', 'qty']); ?></th>
                                    <th class="text-center"><?php echo get_phrases(['receive', 'qty']); ?></th>
                                    <th class="text-center"><?php echo get_phrases(['remain', 'qty']); ?></th>
                                    <th class="text-center"><?php echo get_phrases(['unit']); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($sales_ad as $key => $v) { ?>
                                    <tr>
                                        <td width="5%" align="center"><?php echo $v['sl']; ?></td>
                                        <td width="5%" align="center"><?php echo $v['dealer_name']; ?></td>
                                        <td width="5%" align="center"><?php echo $v['item_name']; ?></td>
                                        <td width="5%" align="center"><?php echo $v['qty']; ?></td>
                                        <td width="5%" align="center"><?php echo $v['rcv_qty']; ?></td>
                                        <td width="5%" align="center"><?php echo $v['rm_qty']; ?></td>
                                        <td width="5%" align="center"><?php echo $v['unit']; ?></td>
                                    </tr>
                                <?php } ?>

                            </tbody>
                        </table>
                    <?php }
                }
                if ($permission->module('attendance_log')->access()) {
                    if (($attendance)) {
                    ?>
                        <h5 class="font-weight-300"><?php echo get_phrases(['todays', 'Attendance', 'log']); ?>:</h5>
                        <table class="table display table-bordered table-striped  table-sm">
                            <thead>
                                <tr class="bg-success text-white">
                                    <th class="text-center"><?php echo get_phrases(['total', 'employees']); ?></th>
                                    <th class="text-center"><?php echo get_phrases(['total', 'employees', 'attend']); ?></th>
                                    <th class="text-center"><?php echo get_phrases(['total', 'employees', 'absent']); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td align="center"><?php echo $attendance['total_emp']; ?></td>
                                    <td align="center"><?php echo $attendance['attend']; ?></td>
                                    <td align="center"><?php echo $attendance['absent']; ?></td>
                                </tr>
                            </tbody>
                        </table>
                <?php }
                } ?>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal"><?php echo get_phrases(['close']); ?></button>

            </div>

        </div>
    </div>
</div>
<?php } ?>

<?php if (!empty($alertdata)) { ?>

<script type="text/javascript">
    $(document).ready(function() {
        "use strict";
        var alertdata = "<?php echo count($alertdata); ?>";
        $('#alertmodal').modal('show');

    });
</script>

<?php } ?>

<script type="text/javascript">
    let items = document.querySelectorAll('.carousel .carousel-item')
    items.forEach((el) => {
        const minPerSlide = 4
        let next = el.nextElementSibling
        for (var i = 1; i < minPerSlide; i++) {
            if (!next) {
                // wrap carousel by using first child
                next = items[0]
            }
            let cloneChild = next.cloneNode(true)
            el.appendChild(cloneChild.children[0])
            next = next.nextElementSibling
        }
    })


    /*Get data based on ajax call*/


</script>