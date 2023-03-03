<?php 
  $segs = explode('/', uri_string());
  $seg_1 = isset($segs[0])?$segs[0]:'';
  $seg_2 = isset($segs[1])?$segs[1]:'';
  $seg_3 = isset($segs[2])?$segs[2]:'';
  $seg_4 = isset($segs[3])?$segs[3]:'';
  if(session('defaultLang')=='english'){
    $left = '';
  }else{
    $left = 'ml-2';
  }
?>
<nav class="sidebar sidebar-bunker">
  <div class="sidebar-headers text-center">
    <a href="<?php echo base_url()?>" class="logo"><img src="<?php echo base_url().$settings_info->admin_logo?>" class=""
        alt="logo" height="50"></a>
  </div>
  <!--/.sidebar header-->
  <div class="profile-element d-flex align-items-center flex-shrink-0">
    <div class="avatar online">
      <img
        src="<?php echo (!empty(session('image')))?base_url().session('image'):base_url('assets/dist/img/avatar/avatar-1.png')?>"
        class="img-fluid rounded-circle" alt="">
    </div>
    <div class="profile-text">
      <h6 class="m-0"><?php echo session('fullname')?></h6>
      <span><?php echo session('user_level')?></span>
    </div>
  </div>
  <!--/.profile element-->
  <div class="search sidebar-form">
    <div class="search__inner sidebar-search">
      <input id="search" type="text" class="form-control search__text" placeholder="Menu Search..." autocomplete="off">
      <i class="typcn typcn-zoom-outline search__helper" data-sa-action="search-close"></i>
    </div>
  </div>

  <div class="sidebar-body">
    <nav class="sidebar-nav">
      <ul class="metismenu" id="sidebarmenu">

        <?php if($permission->check_label('dashboard')->access()){ ?>
        <li class="<?php echo (($seg_2=="home")?"active":'') ?>">
          <a href="<?php echo base_url('dashboard/home')?>"><i class="fas fa-home"></i>
            <?php echo get_phrases(['dashboard'])?></a>
        </li>
        <?php }?>


        <!-- date export module -->
        <?php if($permission->check_label('dashboard_chart')->access()){ ?>
        <li class="<?php echo (($seg_1=="dashboard")?"mm-active":'') ?>">
          <a class="has-arrow material-ripple" href="#">
            <i class="far fa-chart-bar"></i>
            <?php echo get_phrases(['dashboard','chart'])?>
          </a>
          <ul class="nav-second-level <?php echo (($seg_1=="dashboard")?"mm-collapse mm-show":'') ?> ">
            <?php if($permission->module('accounts_chart')->access()){ ?>
            <li class="<?php echo (($seg_2=="accounts_chart")?"mm-active":'') ?>">
              <a
                href="<?php echo base_url('dashboard/accounts_chart')?>"><?php echo get_phrases(['accounts','chart'])?></a>
            </li>
            <?php } ?>
            <?php if($permission->module('hrm_chart')->access()){ ?>
            <li class="<?php echo (($seg_2=="hrm_chart")?"mm-active":'') ?>">
              <a href="<?php echo base_url('dashboard/hrm_chart')?>"><?php echo get_phrases(['hrm','chart'])?></a>
            </li>
            <?php } ?>
            <?php if($permission->module('procurement_chart')->access()){ ?>
            <li class="<?php echo (($seg_2=="procurement_chart")?"mm-active":'') ?>">
              <a
                href="<?php echo base_url('dashboard/procurement_chart')?>"><?php echo get_phrases(['procurement','chart'])?></a>
            </li>
            <?php } ?>
            <?php if($permission->module('production_chart')->access()){ ?>
            <li class="<?php echo (($seg_2=="production_chart")?"mm-active":'') ?>">
              <a
                href="<?php echo base_url('dashboard/production_chart')?>"><?php echo get_phrases(['production','chart'])?></a>
            </li>
            <!-- <li class="<?php //echo (($seg_2=="production2_chart")?"mm-active":'') ?>">
                                <a href="<?php //echo base_url('dashboard/production2_chart')?>" ><?php //echo get_phrases(['production2','chart'])?></a>
                            </li> -->
            <?php } ?>
            <?php if($permission->module('sale_chart')->access()){ ?>
            <li class="<?php echo (($seg_2=="sale_chart")?"mm-active":'') ?>">
              <a href="<?php echo base_url('dashboard/sale_chart')?>"><?php echo get_phrases(['sales','chart'])?></a>
            </li>
            <?php } ?>
            <?php if($permission->module('store_chart')->access()){ ?>
            <li class="<?php echo (($seg_2=="store_chart")?"mm-active":'') ?>">
              <a href="<?php echo base_url('dashboard/store_chart')?>"><?php echo get_phrases(['store','chart'])?></a>
            </li>
            <?php } ?>
          </ul>
        </li>
        <?php } ?>
        <?php if($permission->check_label('material')->access() || $permission->module('wh_material')->access()){ ?>
        <li class="<?php echo (($seg_1=="material" && ($seg_2=="categories" || $seg_2=="items"))?"mm-active":'') ?>">
          <a class="has-arrow material-ripple" href="#">
            <i class="far fa-list-alt"></i>
            <?php echo get_phrases(['raw','material'])?>
          </a>
          <ul
            class="nav-second-level <?php echo (($seg_1=="material" && ($seg_2=="categories" || $seg_2=="items"))?"mm-collapse mm-show":'') ?> ">
            <?php if($permission->module('categories')->access()){ ?>
            <li class="<?php echo (($seg_1=="material" && $seg_2=="categories")?"mm-active":'') ?>">
              <a
                href="<?php echo base_url('material/categories')?>"><?php echo get_phrases(['material','category'])?></a>
            </li>
            <?php } if($permission->module('wh_material_items')->access()){ ?>
            <li class="<?php echo (($seg_1=="material" && $seg_2=="items")?"mm-active":'') ?>">
              <a href="<?php echo base_url('material/items')?>"><?php echo get_phrases(['material','list'])?></a>
            </li>
            <?php } ?>
            <!-- supplier -->
            <?php if($permission->module('wh_material_supplier')->access()){ ?>
            <li class="<?php echo (($seg_1=="supplier" && $seg_2=="suppliers")?"mm-active":'') ?>">
              <a
                href="<?php echo base_url('supplier/suppliers')?>"><?php echo get_phrases(['party', '(', 'supplier', ')', 'list']); ?></a>
            </li>
            <?php } ?>
            <!-- store -->
            <?php if($permission->module('wh_material_store')->access()){ ?>
            <li class="<?php echo (($seg_1=="store" && $seg_2=="material_store")?"mm-active":'') ?>">
              <a
                href="<?php echo base_url('store/material_store')?>"><?php echo get_phrases(['material','store','list'])?></a>
            </li>
            <?php } if($permission->module('wh_material_stock')->access()){ ?>
            <li class="<?php echo (($seg_1=="store" && $seg_2=="material_stock")?"mm-active":'') ?>">
              <a
                href="<?php echo base_url('store/material_stock')?>"><?php echo get_phrases(['material','store','balance'])?></a>
            </li>
            <?php } ?>

          </ul>
        </li>
        <?php }?>
        <!-- END data export module -->


        <?php if($permission->check_label('purchase')->access() || $permission->module('wh_purchases')->access() || $permission->module('wh_receive')->access() || $permission->module('wh_print_invoice')->access()){?>
        <li
          class="<?php echo (($seg_1=="purchase" && ($seg_2=="requisition" || $seg_2=="purchase_order" || $seg_2=="item_receive" || $seg_2=="quatation" || $seg_2=="item_purchase"))?"mm-active":'') ?>">
          <a class="has-arrow material-ripple" href="#">
            <i class="fas fa-shopping-cart"></i>
            <?php echo get_phrases(['local','purchase'])?>
          </a>
          <ul
            class="nav-second-level <?php echo (($seg_1=="purchase" && ($seg_2=="purchase_order" || $seg_2=="item_receive" || $seg_2=="item_purchase"))?"mm-collapse mm-show":'') ?> ">
            <?php if($permission->module('wh_material_requisition')->access()){ ?>
            <li class="<?php echo (($seg_1=="purchase" && $seg_2=="requisition")?"mm-active":'') ?>">
              <a
                href="<?php echo base_url('purchase/requisition')?>"><?php echo get_phrases(['store','purchase','requisition'])?></a>
            </li>
            <?php } if($permission->module('wh_material_quatation')->access()){ ?>
            <li class="<?php echo (($seg_1=="purchase" && $seg_2=="quatation")?"mm-active":'') ?>">
              <a href="<?php echo base_url('purchase/quatation')?>"><?php echo get_phrases(['quotation'])?></a>
            </li>
            <?php } if($permission->module('wh_material_purchase')->access()){ ?>
            <li class="<?php echo (($seg_1=="purchase" && $seg_2=="quotation_cs")?"mm-active":'') ?>">
              <a href="<?php echo base_url('purchase/quotation_cs')?>"><?php echo get_phrases(['quotation','CS'])?></a>
            </li>
            <li class="<?php echo (($seg_1=="purchase" && $seg_2=="purchase_order")?"mm-active":'') ?>">
              <a
                href="<?php echo base_url('purchase/purchase_order')?>"><?php echo get_phrases(['purchase','order'])?></a>
            </li>
            <?php } if($permission->module('wh_material_receive')->access()){ ?>
            <li class="<?php echo (($seg_1=="purchase" && $seg_2=="item_receive")?"mm-active":'') ?>">
              <a
                href="<?php echo base_url('purchase/item_receive')?>"><?php echo get_phrases(['material','receive'])?></a>
            </li>
            <?php } if($permission->module('wh_material_received_voucher')->access()){ ?>
            <li class="<?php echo (($seg_1=="purchase" && $seg_2=="item_purchase")?"mm-active":'') ?>">
              <a
                href="<?php echo base_url('purchase/item_purchase')?>"><?php echo get_phrases(['MR','list']).' / '.get_phrases(['gate','pass']); ?></a>
            </li>
            <?php }?>
          </ul>
        </li>
        <?php }?>

        <?php if($permission->check_label('foreign_purchase')->access() || $permission->module('foreign_purchase')->access()){ ?>
        <li
          class="<?php echo (( $seg_1=="lc" && ($seg_2=="lcs" || $seg_2=="lc_info") || $seg_1=="foreign_purchase" && ($seg_2=="purchase_order") || $seg_1=="foreign_purchase" && ($seg_2=="item_receive") || $seg_1=="foreign_purchase" && ($seg_2=="item_purchase") )?"mm-active":'') ?>">
          <a class="has-arrow material-ripple" href="#">
            <i class="fas fa-cart-plus"></i>
            <?php echo get_phrases(['foreign','purchase'])?>
          </a>
          <ul
            class="nav-second-level <?php echo (( $seg_1=="lc" && ($seg_2=="lcs") || $seg_1=="foreign_purchase" && ($seg_2=="purchase_order") || $seg_1=="foreign_purchase" && ($seg_2=="item_receive") || $seg_1=="foreign_purchase" && ($seg_2=="item_purchase") )?"mm-collapse mm-show":'') ?> ">

            <?php if($permission->module('wh_lc')->access()){ ?>
            <li class="<?php echo (($seg_1=="lc" && ($seg_2=="lcs"))?"mm-active":'') ?>">
              <a href="<?php echo base_url('lc/lcs')?>">Letter of Credit</a>
            </li>
            <?php }?>
            <?php if($permission->module('wh_foreign_purchase_receive')->access()){ ?>
            <li class="<?php echo (($seg_1=="lc" && ($seg_2=="item_receive"))?"mm-active":'') ?>">
              <a href="<?php echo base_url('lc/item_receive')?>"><?php echo get_phrases(['PO', 'receive'])?></a>
            </li>
            <?php }?>
            <?php if($permission->module('wh_foreign_purchase_received_voucher')->access()){ ?>
            <li class="<?php echo (($seg_1=="lc" && ($seg_2=="item_purchase"))?"mm-active":'') ?>">
              <a href="<?php echo base_url('lc/item_purchase')?>"><?php echo get_phrases(['receipt', 'voucher'])?></a>
            </li>
            <?php }?>
            <?php if($permission->module('wh_lc')->access()){ ?>
            <li class="<?php //echo (($seg_1=="lc" && ($seg_2=="lc_payment"))?"mm-active":'') ?>">
              <a href="<?php //echo base_url('lc/lc_payment')?>"><?php //echo get_phrases(['LC', 'payment'])?></a>
            </li>
            <?php }?>
          </ul>
        </li>
        <?php } ?>


        <!-- bag -->
        <?php if($permission->check_label('bag')->access() || $permission->module('wh_material_categories')->access() || $permission->module('wh_material')->access()){ ?>
        <li class="<?php echo (($seg_1=="bag" && ($seg_2=="categories" || $seg_2=="items"))?"mm-active":'') ?>">
          <a class="has-arrow material-ripple" href="#">
            <i class="far fa-list-alt"></i>
            <?php echo get_phrases(['bag'])?>
          </a>
          <ul
            class="nav-second-level <?php echo (($seg_1=="bag" && ($seg_2=="categories" || $seg_2=="items"))?"mm-collapse mm-show":'') ?> ">
            <?php if($permission->module('wh_bag_items')->access()){ ?>
            <li class="<?php echo (($seg_1=="bag" && $seg_2=="items")?"mm-active":'') ?>">
              <a href="<?php echo base_url('bag/items')?>"><?php echo get_phrases(['bag','list'])?></a>
            </li>
            <?php } ?>
            <!-- supplier -->
            <?php if($permission->module('wh_bag_supplier')->access()){ ?>
            <li class="<?php echo (($seg_1=="bag" && $seg_2=="suppliers")?"mm-active":'') ?>">
              <a
                href="<?php echo base_url('bag/suppliers')?>"><?php echo get_phrases(['bag','party', '(', 'supplier', ')', 'list']); ?></a>
            </li>
            <?php } ?>
            <!-- store -->
            <?php if($permission->module('wh_bag_store')->access()){ ?>
            <li class="<?php echo (($seg_1=="bag_store" && $seg_2=="material_store")?"mm-active":'') ?>">
              <a
                href="<?php echo base_url('bag_store/material_store')?>"><?php echo get_phrases(['bag','store','list'])?></a>
            </li>
            <?php } if($permission->module('wh_bag_stock')->access()){ ?>
            <li class="<?php echo (($seg_1=="bag_store" && $seg_2=="material_stock")?"mm-active":'') ?>">
              <a
                href="<?php echo base_url('bag_store/material_stock')?>"><?php echo get_phrases(['bag','store','balance'])?></a>
            </li>
            <?php } ?>
          </ul>
        </li>
        <?php } ?>


        <?php if($permission->check_label('bag_purchase')->access() || $permission->module('wh_purchases')->access() || $permission->module('wh_receive')->access() || $permission->module('wh_print_invoice')->access()){?>
        <li
          class="<?php echo (($seg_1=="bag_purchase" && ($seg_2=="requisition" || $seg_2=="purchase_order" || $seg_2=="item_receive" || $seg_2=="quatation" || $seg_2=="item_purchase"))?"mm-active":'') ?>">
          <a class="has-arrow material-ripple" href="#">
            <i class="fas fa-shopping-cart"></i>
            <?php echo get_phrases(['bag', 'purchase'])?>
          </a>
          <ul
            class="nav-second-level <?php echo (($seg_1=="bag_purchase" && ($seg_2=="purchase_order" || $seg_2=="item_receive" || $seg_2=="item_purchase"))?"mm-collapse mm-show":'') ?> ">
            <?php if($permission->module('wh_bag_requisition')->access()){ ?>
            <li class="<?php echo (($seg_1=="bag_purchase" && $seg_2=="requisition")?"mm-active":'') ?>">
              <a href="<?php echo base_url('bag_purchase/requisition')?>"><?php echo get_phrases(['SPR'])?></a>
            </li>
            <?php } if($permission->module('wh_bag_quatation')->access()){ ?>
            <li class="<?php echo (($seg_1=="bag_purchase" && $seg_2=="quatation")?"mm-active":'') ?>">
              <a href="<?php echo base_url('bag_purchase/quatation')?>"><?php echo get_phrases(['quotation'])?></a>
            </li>
            <li class="<?php echo (($seg_1=="bag_purchase" && $seg_2=="quotation_cs")?"mm-active":'') ?>">
              <a
                href="<?php echo base_url('bag_purchase/quotation_cs')?>"><?php echo get_phrases(['quotation','CS'])?></a>
            </li>
            <?php } if($permission->module('wh_bag_purchase')->access()){ ?>
            <li class="<?php echo (($seg_1=="bag_purchase" && $seg_2=="purchase_order")?"mm-active":'') ?>">
              <a
                href="<?php echo base_url('bag_purchase/purchase_order')?>"><?php echo get_phrases(['purchase','order'])?></a>
            </li>
            <?php } if($permission->module('wh_bag_receive')->access()){ ?>
            <li class="<?php echo (($seg_1=="bag_purchase" && $seg_2=="item_receive")?"mm-active":'') ?>">
              <a
                href="<?php echo base_url('bag_purchase/item_receive')?>"><?php echo get_phrases(['bag','receive'])?></a>
            </li>
            <?php } if($permission->module('wh_bag_received_voucher')->access()){ ?>
            <li class="<?php echo (($seg_1=="bag_purchase" && $seg_2=="item_purchase")?"mm-active":'') ?>">
              <a
                href="<?php echo base_url('bag_purchase/item_purchase')?>"><?php echo get_phrases(['received','voucher'])?></a>
            </li>
            <?php }?>
          </ul>
        </li>
        <?php }?>

        <!-- assets module -->
        <!--                    
                   <?php //if($permission->check_label('assets')->access() ){ ?>
                    <li class="<?php //echo (($seg_1=="wh_assets" && ($seg_2=="categories" || $seg_2=="items"))?"mm-active":'') ?>">
                          <a class="has-arrow material-ripple" href="#">
                              <i class="far fa-list-alt"></i>
                              <?php //echo get_phrases(['assets'])?> 
                          </a>
                      <ul class="nav-second-level <?php //echo (($seg_1=="wh_assets" && ($seg_2=="categories" || $seg_2=="items"))?"mm-collapse mm-show":'') ?> ">
                          <?php //if($permission->module('assets_categories')->access()){ ?>
                            <li class="<?php //echo (($seg_1=="wh_assets" && $seg_2=="categories")?"mm-active":'') ?>">
                                <a href="<?php //echo base_url('wh_assets/categories')?>" ><?php echo get_phrases(['assets','category'])?></a>
                            </li>
                            <?php //} if($permission->module('wh_assets_items')->access()){ ?>
                            <li class="<?php //echo (($seg_1=="wh_assets" && $seg_2=="items")?"mm-active":'') ?>">
                                <a href="<?php //echo base_url('wh_assets/items')?>" ><?php echo get_phrases(['assets','list'])?></a>
                            </li>
                          <?php //} ?>
                          
                          
                      </ul>
                    </li>
                   <?php //}?>
                   <?php //if($permission->check_label('assets_purchase')->access()){?>
                    <li class="<?php //echo (($seg_1=="assets_purchase" && ($seg_2=="requisition" || $seg_2=="purchase_order" || $seg_2=="item_receive" || $seg_2=="quatation" || $seg_2=="item_purchase"))?"mm-active":'') ?>">
                          <a class="has-arrow material-ripple" href="#">
                              <i class="fas fa-shopping-cart"></i>
                              <?php //echo get_phrases(['assets', 'purchase'])?> 
                          </a>
                      <ul class="nav-second-level <?php //echo (($seg_1=="assets_purchase" && ($seg_2=="purchase_order" || $seg_2=="item_receive" || $seg_2=="item_purchase"))?"mm-collapse mm-show":'') ?> ">
                              <?php if($permission->module('wh_bag_requisition')->access()){ ?>
                              <li class="<?php echo (($seg_1=="assets_purchase" && $seg_2=="requisition")?"mm-active":'') ?>">
                                  <a href="<?php echo base_url('assets_purchase/requisition')?>" ><?php echo get_phrases(['SPR'])?></a>
                              </li>
                              <?php } if($permission->module('wh_bag_quatation')->access()){ ?>
                              <li class="<?php echo (($seg_1=="assets_purchase" && $seg_2=="quatation")?"mm-active":'') ?>">
                                  <a href="<?php echo base_url('assets_purchase/quatation')?>" ><?php echo get_phrases(['quotation'])?></a>
                              </li>
                              <?php } if($permission->module('wh_bag_purchase')->access()){ ?>
                              <li class="<?php echo (($seg_1=="assets_purchase" && $seg_2=="purchase_order")?"mm-active":'') ?>">
                                  <a href="<?php echo base_url('assets_purchase/purchase_order')?>" ><?php echo get_phrases(['purchase','order'])?></a>
                              </li>
                              <?php } if($permission->module('wh_bag_receive')->access()){ ?>
                              <li class="<?php echo (($seg_1=="assets_purchase" && $seg_2=="item_receive")?"mm-active":'') ?>">
                                  <a href="<?php echo base_url('assets_purchase/item_receive')?>" ><?php echo get_phrases(['purchase','receive'])?></a>
                              </li>
                              <?php } if($permission->module('wh_bag_received_voucher')->access()){ ?>
                              <li class="<?php echo (($seg_1=="assets_purchase" && $seg_2=="item_purchase")?"mm-active":'') ?>">
                                  <a href="<?php echo base_url('assets_purchase/item_purchase')?>" ><?php echo get_phrases(['received','voucher'])?></a>
                              </li>
                            <?php }?>
                      </ul>
                    </li>
                   <?php //}?> 
                  -->



        <!-- END assets module -->

        <!-- date export module -->
        <?php /*if($permission->module('wh_machine_request')->access() || $permission->module('wh_machine_approve')->access() || $permission->module('wh_machine_store')->access() || $permission->module('wh_machine_stock')->access() ){?>
        <li
          class="<?php echo (($seg_1=="machine" && ($seg_2=="item_request" || $seg_2=="item_approve" || $seg_2=="sub_store" || $seg_2=="sub_stock"))?"mm-active":'') ?>">
          <a class="has-arrow material-ripple" href="#">
            <i class="fas fa-cash-register"></i>
            <?php echo get_phrases(['plant'])?>
          </a>
          <ul
            class="nav-second-level <?php echo (($seg_1=="machine" && ($seg_2=="item_request" || $seg_2=="item_approve" || $seg_2=="sub_store" || $seg_2=="sub_stock" ))?"mm-collapse mm-show":'') ?> ">
            <?php if($permission->module('wh_machine_store')->access()){ ?>
            <li class="<?php echo (($seg_1=="machine" && $seg_2=="sub_store")?"mm-active":'') ?>">
              <a href="<?php echo base_url('machine/sub_store')?>"><?php echo get_phrases(['plant','list'])?></a>
            </li>
            <?php } if($permission->module('wh_machine_request')->access()){ ?>
            <li class="<?php echo (($seg_1=="machine" && $seg_2=="item_request")?"mm-active":'') ?>">
              <a
                href="<?php echo base_url('machine/item_request')?>"><?php echo get_phrases(['consumption','entry'])?></a>
            </li>
            <?php } 
                             if($permission->module('wh_machine_approve')->access()){ ?>
            <li class="<?php echo (($seg_1=="machine" && $seg_2=="item_approve")?"mm-active":'') ?>">
              <a
                href="<?php echo base_url('machine/item_approve')?>"><?php echo get_phrases(['approve','consumption','request'])?></a>
            </li>
            <?php } if($permission->module('wh_machine_stock')->access()){ ?>
            <li class="<?php echo (($seg_1=="machine" && $seg_2=="sub_stock")?"mm-active":'') ?>">
              <a
                href="<?php echo base_url('machine/sub_stock')?>"><?php echo get_phrases(['plant','stock','quantity'])?></a>
            </li>
            <?php }?>
          </ul>
        </li>
        <?php }*/?>
        <!-- END data export module -->


        <!-- date export module -->
        <?php if($permission->check_label('production')->access()){?>
        <li
          class="<?php echo (($seg_1=="production" && ($seg_2=="plant" || $seg_2=="recipe" || $seg_2=="item_production" || $seg_2=="item_request" || $seg_2=="item_receive" || $seg_2=="production_voucher"))?"mm-active":'') ?>">
          <a class="has-arrow material-ripple" href="#">
            <i class="fas fa-faucet"></i>
            <?php echo get_phrases(['production'])?>
          </a>
          <ul
            class="nav-second-level <?php echo (($seg_1=="production" && ($seg_2=="plant" || $seg_2=="recipe" || $seg_2=="item_production" || $seg_2=="item_request" || $seg_2=="item_receive" || $seg_2=="production_voucher"))?"mm-collapse mm-show":'') ?> ">
            <?php if($permission->module('plant_list')->access()){ ?>
            <li class="<?php echo (($seg_1=="production" && $seg_2=="plant")?"mm-active":'') ?>">
              <a href="<?php echo base_url('production/plant')?>"><?php echo get_phrases(['plant','list'])?></a>
            </li>
            <?php } if($permission->module('recipe_list')->access()){ ?>
            <li class="<?php echo (($seg_1=="production" && $seg_2=="recipe")?"mm-active":'') ?>">
              <a href="<?php echo base_url('production/recipe')?>"><?php echo get_phrases(['recipe','list'])?></a>
            </li>
            <?php } if($permission->module('production_plan')->access()){ ?>
            <li class="<?php echo (($seg_1=="production" && $seg_2=="item_production")?"mm-active":'') ?>">
              <a
                href="<?php echo base_url('production/item_production')?>"><?php echo get_phrases(['production','plan'])?></a>
            </li>
            <?php } /*if($permission->module('material_request')->access()){ ?>
            <li class="<?php echo (($seg_1=="production" && $seg_2=="item_request")?"mm-active":'') ?>">
              <a
                href="<?php echo base_url('production/item_request')?>"><?php echo get_phrases(['consumption','request'])?></a>
            </li>
            <?php }*/ if($permission->module('approve_material_req')->access()){ ?>
            <li class="<?php echo (($seg_1=="production" && $seg_2=="item_approve")?"mm-active":'') ?>">
              <a
                href="<?php echo base_url('production/item_approve')?>"><?php echo get_phrases(['material','consumption'])?></a>
            </li>
            <?php } if($permission->module('production_entry')->access()){ ?>
            <li class="<?php echo (($seg_1=="production" && $seg_2=="item_receive")?"mm-active":'') ?>">
              <a
                href="<?php echo base_url('production/item_receive')?>"><?php echo get_phrases(['production','entry'])?></a>
            </li>
            <?php } /*if($permission->module('bag_request')->access()){ ?>
            <li
              class="<?php echo (($seg_1=="production" && $seg_2=="bags" && $seg_3=="item_request")?"mm-active":'') ?>">
              <a
                href="<?php echo base_url('production/bags/item_request')?>"><?php echo get_phrases(['bag','request'])?></a>
            </li>
            <?php }*/ if($permission->module('approve_bag_request')->access()){ ?>
            <li
              class="<?php echo (($seg_1=="production" && $seg_2=="bags" && $seg_3=="item_approve")?"mm-active":'') ?>">
              <a
                href="<?php echo base_url('production/bags/item_approve')?>"><?php echo get_phrases(['bag','consumption'])?></a>
            </li>
            <?php } if($permission->module('production_voucher')->access()){ ?>
            <li class="<?php echo (($seg_1=="production" && $seg_2=="production_voucher")?"mm-active":'') ?>">
              <a
                href="<?php echo base_url('production/production_voucher')?>"><?php echo get_phrases(['production','voucher'])?></a>
            </li>
            <?php } /*if($permission->module('wh_material_approve')->access()){ ?>
            <li class="<?php echo (($seg_1=="production" && $seg_2=="material_approve")?"mm-active":'') ?>">
              <a
                href="<?php echo base_url('production/material_approve')?>"><?php echo get_phrases(['material','consumption'])?></a>
            </li>
            <?php } if($permission->module('wh_bag_approve')->access()){ ?>
            <li
              class="<?php echo (($seg_1=="production" && $seg_2=="bags" && $seg_3=="item_approve")?"mm-active":'') ?>">
              <a
                href="<?php echo base_url('production/bags/item_approve')?>"><?php echo get_phrases(['bag','consumption'])?></a>
            </li>
            <?php }*/ ?>
          </ul>
        </li>
        <?php }?>
        <!-- END data export module -->

        <!-- date export module -->
        <?php if($permission->check_label('finished_goods')->access()){?>
        <li
          class="<?php echo (($seg_1=="finished_goods" && ($seg_2=="categories" || $seg_2=="items" || $seg_2=="main_store" || $seg_2=="main_stock" || $seg_2=="store_transfer"))?"mm-active":'') ?>">
          <a class="has-arrow material-ripple" href="#">
            <i class="far fa-list-alt"></i>
            <?php echo get_phrases(['finished','goods'])?>
          </a>
          <ul
            class="nav-second-level <?php echo (($seg_1=="finished_goods" && ($seg_2=="categories" || $seg_2=="items" || $seg_2=="main_store" || $seg_2=="main_stock"))?"mm-collapse mm-show":'') ?> ">
            <?php if($permission->module('fg_categories')->access()){ ?>
            <li class="<?php echo (($seg_1=="finished_goods" && $seg_2=="categories")?"mm-active":'') ?>">
              <a
                href="<?php echo base_url('finished_goods/categories')?>"><?php echo get_phrases(['category','list'])?></a>
            </li>
            <?php } if($permission->module('fg_items')->access()){ ?>
            <li class="<?php echo (($seg_1=="finished_goods" && $seg_2=="items")?"mm-active":'') ?>">
              <a href="<?php echo base_url('finished_goods/items')?>"><?php echo get_phrases(['item','list'])?></a>
            </li>
            <?php } if($permission->module('fg_store')->access()){ ?>
            <li class="<?php echo (($seg_1=="finished_goods" && $seg_2=="main_store")?"mm-active":'') ?>">
              <a
                href="<?php echo base_url('finished_goods/main_store')?>"><?php echo get_phrases(['store','list'])?></a>
            </li>
            <?php } if($permission->module('fg_stock')->access()){ ?>
            <li class="<?php echo (($seg_1=="finished_goods" && $seg_2=="main_stock")?"mm-active":'') ?>">
              <a
                href="<?php echo base_url('finished_goods/main_stock')?>"><?php echo get_phrases(['stock','balance'])?></a>
            </li>
            <?php } ?>

            <?php if($permission->module('goods_pricing')->access()){ ?>
            <li class="<?php echo (($seg_1=="finished_goods" && $seg_2=="goods_pricing")?"mm-active":'') ?>">
              <a
                href="<?php echo base_url('finished_goods/goods_pricing')?>"><?php echo get_phrases(['goods','pricing'])?></a>
            </li>
            <?php } ?>

            <?php if($permission->module('store_transfer')->access()){ ?>
            <li class="<?php echo (($seg_1=="finished_goods" && $seg_3=="add_store_transfer")?"mm-active":'') ?>">
              <a
                href="<?php echo base_url('finished_goods/store_transfer/add_store_transfer')?>"><?php echo get_phrases(['store','transfer'])?></a>
            </li>
            <?php } ?>
            <?php if($permission->module('store_transfer_list')->access()){ ?>
            <li class="<?php echo (($seg_1=="finished_goods" && $seg_3=="store_transfer_list")?"mm-active":'') ?>">
              <a
                href="<?php echo base_url('finished_goods/store_transfer/store_transfer_list')?>"><?php echo get_phrases(['store','transfer','list'])?></a>
            </li>
            <?php } ?>
          </ul>
        </li>
        <?php }?>
        <!-- END data export module -->

        <!-- date export module -->
        <?php /*if($permission->module('wh_main_store')->access() || $permission->module('wh_main_stock')->access()){ ?>
        <li
          class="<?php echo (($seg_1=="goods_store" && ($seg_2=="main_store" || $seg_2=="main_stock"))?"mm-active":'') ?>">
          <a class="has-arrow material-ripple" href="#">
            <i class="fas fa-store-alt"></i>
            <?php echo get_phrases(['production','store'])?>
          </a>
          <ul
            class="nav-second-level <?php echo (($seg_1=="goods_store" && ($seg_2=="main_store" || $seg_2=="main_stock"))?"mm-collapse mm-show":'') ?> ">
            <?php if($permission->module('wh_main_store')->access()){ ?>
            <li class="<?php echo (($seg_1=="goods_store" && $seg_2=="main_store")?"mm-active":'') ?>">
              <a href="<?php echo base_url('goods_store/main_store')?>"><?php echo get_phrases(['store','list'])?></a>
            </li>
            <?php } if($permission->module('wh_main_stock')->access()){ ?>
            <li class="<?php echo (($seg_1=="goods_store" && $seg_2=="main_stock")?"mm-active":'') ?>">
              <a
                href="<?php echo base_url('goods_store/main_stock')?>"><?php echo get_phrases(['stock','balance'])?></a>
            </li>
            <?php } 
                              if($permission->module('wh_sub_store')->access()){ ?>
            <li class="<?php echo (($seg_1=="goods_store" && $seg_2=="sub_store")?"mm-active":'') ?>">
              <a
                href="<?php echo base_url('goods_store/sub_store')?>"><?php echo get_phrases(['sale','store','list'])?></a>
            </li>
            <?php }if($permission->module('wh_sub_stock')->access()){ ?>
            <li class="<?php echo (($seg_1=="goods_store" && $seg_2=="sub_stock")?"mm-active":'') ?>">
              <a
                href="<?php echo base_url('goods_store/sub_stock')?>"><?php echo get_phrases(['sale','store','balance'])?></a>
            </li>
            <?php } if($permission->module('wh_stock_adjust')->access()){ ?>
            <li class="<?php echo (($seg_1=="goods_store" && $seg_2=="stock_adjust")?"mm-active":'') ?>">
              <a
                href="<?php echo base_url('goods_store/stock_adjust/list')?>"><?php echo get_phrases(['stock','adjustment'])?></a>
            </li>
            <?php }  ?>
          </ul>
        </li>
        <?php }*/?>
        <!-- END data export module -->

        <!-- date export module -->
        <?php /*if($permission->module('wh_goods_transfer')->access() || $permission->module('wh_goods_transfer_approve')->access() || $permission->module('wh_stock_transfer')->access() || $permission->module('wh_transfer')->access()){?>
        <li
          class="<?php echo (($seg_1=="goods_transfer" && ($seg_2=="item_request" || $seg_2=="item_approve" || $seg_2=="transfer" || $seg_2=="item_transfer"))?"mm-active":'') ?>">
          <a class="has-arrow material-ripple" href="#">
            <i class="fas fa-exchange-alt"></i>
            <?php echo get_phrases(['goods','transfer'])?>
          </a>
          <ul
            class="nav-second-level <?php echo (($seg_1=="goods_transfer" && ($seg_2=="item_request" || $seg_2=="item_approve" || $seg_2=="transfer" || $seg_2=="item_transfer"))?"mm-collapse mm-show":'') ?> ">
            <?php if($permission->module('wh_item_request')->access()){ ?>
            <li class="<?php echo (($seg_1=="goods_transfer" && $seg_2=="item_request")?"mm-active":'') ?>">
              <a
                href="<?php echo base_url('goods_transfer/item_request')?>"><?php echo get_phrases(['transfer','request'])?></a>
            </li>
            <?php } if($permission->module('wh_request_approve')->access()){ ?>
            <li class="<?php echo (($seg_1=="goods_transfer" && $seg_2=="item_approve")?"mm-active":'') ?>">
              <a
                href="<?php echo base_url('goods_transfer/item_approve')?>"><?php echo get_phrases(['approve','transfer','request'])?></a>
            </li>
            <?php } if($permission->module('wh_stock_transfer')->access()){ ?>
            <li class="<?php echo (($seg_1=="goods_transfer" && $seg_2=="transfer")?"mm-active":'') ?>">
              <a
                href="<?php echo base_url('goods_transfer/transfer/stock_transfer')?>"><?php echo get_phrases(['production','store','transfer'])?></a>
            </li>
            <?php } if($permission->module('wh_transfer')->access()){ ?>
            <li class="<?php echo (($seg_1=="goods_transfer" && $seg_2=="item_transfer")?"mm-active":'') ?>">
              <a
                href="<?php echo base_url('goods_transfer/item_transfer')?>"><?php echo get_phrases(['sale','store','transfer'])?></a>
            </li>
            <?php }?>
          </ul>
        </li>
        <?php }*/ ?>
        <!-- END data export module -->

        <!-- date export module -->
        <?php /*if($permission->module('wh_delivery_approve')->access() || $permission->module('wh_item_delivery_request')->access()){?>
        <li
          class="<?php echo (($seg_1=="order" && ($seg_2=="request_approve" || $seg_2=="item_request"))?"mm-active":'') ?>">
          <a class="has-arrow material-ripple" href="#">
            <i class="fas fa-people-carry"></i>
            <?php echo get_phrases(['staff','order'])?>
          </a>
          <ul
            class="nav-second-level <?php echo (($seg_1=="order" && ($seg_2=="request_approve" || $seg_2=="item_request"))?"mm-collapse mm-show":'') ?> ">
            <?php if($permission->module('wh_delivery_approve')->access()){ ?>
            <li class="<?php echo (($seg_1=="order" && $seg_2=="request_approve")?"mm-active":'') ?>">
              <a href="<?php echo base_url('order/request_approve')?>"><?php echo get_phrases(['approve','order'])?></a>
            </li>

            <?php } if($permission->module('wh_item_delivery_request')->access()){ ?>
            <li class="<?php echo (($seg_1=="order" && $seg_2=="item_request")?"mm-active":'') ?>">
              <a href="<?php echo base_url('order/item_request')?>"><?php echo get_phrases(['new','order'])?></a>
            </li>
            <?php }?>
          </ul>
        </li>
        <?php } */ ?>
        <!-- END data export module -->


        <!--   sale menu start -->
        <?php if($permission->check_label('sale')->access()){?>
        <li class="<?php echo (($seg_1=="sale")?"mm-active":'') ?>">
          <a class="has-arrow material-ripple" href="#">
            <i class="fas fa-balance-scale-left"></i>
            <?php echo get_phrases(['sale'])?>
          </a>
          <ul class="nav-second-level <?php echo (($seg_1=="sale")?"mm-collapse mm-show":'') ?> ">
            <?php if($permission->module('zone_list')->access()){ ?>
            <li class="<?php echo (($seg_3=="zone_list")?"mm-active":'') ?>">
              <a href="<?php echo base_url('sale/zone/zone_list')?>"><?php echo get_phrases(['region','list'])?></a>
            </li>
            <?php }?>
            <?php if($permission->module('dealer_list')->access()){ ?>
            <li class="<?php echo (($seg_3=="dealer_list")?"mm-active":'') ?>">
              <a href="<?php echo base_url('sale/dealer/dealer_list')?>"><?php echo get_phrases(['dealer','list'])?></a>
            </li>
            <?php }?>
            <?php if($permission->module('add_do')->access()){ ?>
            <li class="<?php echo (($seg_3=="add_do")?"mm-active":'') ?>">
              <a href="<?php echo base_url('sale/deliver_order/add_do')?>"><?php echo get_phrases(['add','dO'])?></a>
            </li>
            <?php }?>
            <?php if($permission->module('do_list')->access()){ ?>
            <li class="<?php echo (($seg_3=="do_list")?"mm-active":'') ?>">
              <a
                href="<?php echo base_url('sale/deliver_order/do_list')?>"><?php echo get_phrases(['sales','admin','dO','list'])?></a>
            </li>
            <?php }?>
            <?php if($permission->module('account_do_list')->access()){ ?>
            <li class="<?php echo (($seg_3=="account_do_list")?"mm-active":'') ?>">
              <a
                href="<?php echo base_url('sale/deliver_order/account_do_list')?>"><?php echo get_phrases(['sales','accounts','dO','list'])?></a>
            </li>
            <?php }?>
            <?php if($permission->module('deliverysection_do_list')->access()){ ?>
            <li class="<?php echo (($seg_3=="deliverySection_do_list")?"mm-active":'') ?>">
              <a
                href="<?php echo base_url('sale/deliver_order/deliverySection_do_list')?>"><?php echo get_phrases(['delivery','section','dO','list'])?></a>
            </li>
            <?php }?>

            <?php if($permission->module('storesection_do_list')->access()){ ?>
            <li class="<?php echo (($seg_3=="storeSection_do_list")?"mm-active":'') ?>">
              <a
                href="<?php echo base_url('sale/deliver_order/storeSection_do_list')?>"><?php echo get_phrases(['store','section','dO','list'])?></a>
            </li>
            <?php }?>
            <?php if($permission->module('factorymanager_do_list')->access()){ ?>
            <li class="<?php echo (($seg_3=="factorymanager_do_list")?"mm-active":'') ?>">
              <a
                href="<?php echo base_url('sale/deliver_order/factorymanager_do_list')?>"><?php echo get_phrases(['factory','manager','dO','list'])?></a>
            </li>
            <?php }?>

            <?php if($permission->module('gate_pass')->access()){ ?>
            <li class="<?php echo (($seg_3=="gate_pass")?"mm-active":'') ?>">
              <a
                href="<?php echo base_url('sale/deliver_order/gate_pass')?>"><?php echo get_phrases(['gate','pass'])?></a>
            </li>
            <?php }?>
          </ul>
        </li>
        <?php }?>

        <?php if($permission->check_label('commission')->access()){?>
        <li class="<?php echo (($seg_1=="commission")?"mm-active":'') ?>">
          <a class="has-arrow material-ripple" href="#">
            <i class="fas fa-hand-holding-medical"></i>
            <?php echo get_phrases(['commission'])?>
          </a>
          <ul class="nav-second-level <?php echo (($seg_1=="commission")?"mm-collapse mm-show":'') ?> ">
            <?php if($permission->module('commission_setting')->access()){ ?>
            <li class="<?php echo (($seg_2=="commission_setting")?"mm-active":'') ?>">
              <a
                href="<?php echo base_url('commission/commission_setting')?>"><?php echo get_phrases(['commission','setting'])?></a>
            </li>
            <?php }?>

            <?php if($permission->module('monthly_dealer_target_range_commission')->access()){ ?>
            <li class="<?php echo (($seg_2=="monthly_dealer_target_range_commission")?"mm-active":'') ?>">
              <a
                href="<?php echo base_url('commission/monthly_dealer_target_range_commission')?>"><?php echo get_phrases(['monthly','target','sale','commission'])?></a>
            </li>
            <?php }?>

            <?php if($permission->module('monthly_dealer_sales_commission')->access()){ ?>
            <li class="<?php echo (($seg_2=="monthly_dealer_sales_commission")?"mm-active":'') ?>">
              <a
                href="<?php echo base_url('commission/monthly_dealer_sales_commission')?>"><?php echo get_phrases(['monthly','sale','commission'])?></a>
            </li>
            <?php }?>
          </ul>
        </li>
        <?php }?>

        <!-- sale menu end -->

        <!-- sales return menu start -->
        <?php if($permission->check_label('sales_return')->access()){ ?>
        <li class="<?php echo (($seg_1=="return")?"mm-active":'') ?>">
          <a class="has-arrow material-ripple" href="#">
            <i class="fas fa-exchange-alt"></i>
            <?php echo get_phrases(['sales','return'])?>
          </a>
          <ul class="nav-second-level <?php echo (($seg_1=="return")?"mm-collapse mm-show":'') ?> ">
            <?php if($permission->method('sales_return','create')->access()){ ?>
            <li
              class="<?php echo (($seg_1=="return" && ($seg_3=="find_return" ||$seg_3=="return_form"))?"mm-active":'') ?>">
              <a href="<?php echo base_url('return/sales_return/find_return')?>">
                <?php echo get_phrases(['add','return'])?></a></li>
            <?php } ?>

            <?php if($permission->method('sales_return_list','read')->access()){ ?>
            <li class="<?php echo (($seg_1=="return" && $seg_3=="sales_return_list")?"mm-active":'') ?>"><a
                href="<?php echo base_url('return/sales_return/sales_return_list')?>">
                <?php echo get_phrases(['return','list'])?></a></li>
            <?php } ?>
          </ul>
        </li>
        <?php } ?>

        <!-- sales return menu end -->
        <!-- 
                    <?php //if($permission->check_label('bank')->access()){ ?>
                      <li class="<?php //echo (($seg_1=="bank")?"mm-active":'') ?>">
                            <a class="has-arrow material-ripple" href="#">
                                <i class="fas fa-dollar-sign"></i>
                                <?php //echo get_phrases(['bank'])?> 
                            </a>
                        <ul class="nav-second-level <?php //echo (($seg_1=="bank")?"mm-collapse mm-show":'') ?> ">
                          <?php //if($permission->method('bank','create')->access()){ ?>
                            <li class="<?php //echo (($seg_1=="bank" & $seg_2=="banks")?"mm-active":'') ?>"><a href="<?php //echo base_url('bank/banks')?>"> <?php //echo get_phrases(['bank'])?></a></li>
                          <?php //} ?>
                        </ul>
                      </li>
                    <?php //} ?> 
                    -->

        <!-- Account module -->
        <?php if($permission->check_label('accounts')->access()){ ?>
        <li class="<?php echo (($seg_1=="account")?"mm-active":'') ?>">
          <a class="has-arrow material-ripple" href="#">
            <i class="fas fa-dollar-sign"></i>
            <?php echo get_phrases(['accounts'])?>
          </a>
          <ul class="nav-second-level <?php echo (($seg_1=="account")?"mm-collapse mm-show":'') ?> ">
            <?php if($permission->module('financial_year')->access()){ ?>
            <li class="<?php echo (($seg_3=="set_financial_year")?"mm-active":'') ?>"><a
                href="<?php echo base_url('account/financial_year/set_financial_year')?>">
                <?php echo get_phrases(['set', 'financial', 'year'])?></a></li>
            <?php }?>
            <?php if($permission->module('predefine_account')->access()){ ?>
            <li class="<?php echo (($seg_3=="predefine_account")?"mm-active":'') ?>"><a
                href="<?php echo base_url('account/accounts/predefine_account')?>">
                <?php echo get_phrases(['predefine', 'accounts'])?></a></li>
            <?php }?>
            <?php if($permission->module('chart_of_account')->access()){ ?>
            <li class="<?php echo (($seg_1=="account" && $seg_2=="accounts" && $seg_3=="")?"mm-active":'') ?>"><a
                href="<?php echo base_url('account/accounts')?>">
                <?php echo get_phrases(['chart', 'of', 'account'])?></a></li>
            <?php } if($permission->method('add_opening_balance', 'create')->access()){ ?>
            <li
              class="<?php echo (($seg_1=="account" && $seg_2=="accounts" && $seg_3=="addOpeningBalance")?"mm-active":'') ?>">
              <a href="<?php echo base_url('account/accounts/addOpeningBalance')?>">
                <?php echo get_phrases(['opening','balance'])?></a></li>
            <?php }  if($permission->module('debit_voucher')->access()){ ?>
            <li class="<?php echo (($seg_1=="account" && $seg_2=="vouchers" && ($seg_3=="debit"))?"mm-active":'') ?>"><a
                href="<?php echo base_url('account/vouchers/debit')?>">
                <?php echo get_phrases(['debit', 'voucher'])?></a></li>
            <?php } if($permission->module('credit_voucher')->access()){ ?>
            <li class="<?php echo (($seg_1=="account" && $seg_2=="vouchers" && ($seg_3=="credit"))?"mm-active":'') ?>">
              <a href="<?php echo base_url('account/vouchers/credit')?>">
                <?php echo get_phrases(['credit', 'voucher'])?></a></li>
            <?php } if($permission->module('journal_voucher')->access()){ ?>
            <li class="<?php echo (($seg_1=="account" && $seg_2=="vouchers" && ($seg_3=="journal"))?"mm-active":'') ?>">
              <a href="<?php echo base_url('account/vouchers/journal')?>">
                <?php echo get_phrases(['journal', 'voucher'])?></a></li>
            <!-- End vouchers -->
            <?php }if($permission->module('contra_voucher')->access()){ ?>
            <li class="<?php echo (($seg_1=="account" && $seg_2=="vouchers" && ($seg_3=="contra"))?"mm-active":'') ?>">
              <a href="<?php echo base_url('account/vouchers/contra')?>">
                <?php echo get_phrases(['contra', 'voucher'])?></a></li>
            <!-- End vouchers -->
            <?php } if($permission->module('voucher_approval')->access()){ ?>
            <li class="<?php echo (($seg_1=="account" && $seg_3=="voucher_approval")?"mm-active":'') ?>"><a
                href="<?php echo base_url('account/accounts/voucher_approval')?>">
                <?php echo get_phrases(['pending', 'vouchers'])?></a></li>
            <?php } ?>
            <?php if($permission->module('check_manager')->access()){ ?>
            <li class="<?php echo (($seg_1=="account" && $seg_3=="add_checkinfo")?"mm-active":'') ?>"><a
                href="<?php echo base_url('account/accounts/add_checkinfo')?>">
                <?php echo get_phrases(['checque', 'manager'])?></a></li>
            <?php } ?>
            <li>
              <a class="has-arrow" href="#" aria-expanded="false"><?php echo get_phrases(['account', 'reports'])?></a>
              <ul class="nav-third-level">
                <?php if($permission->module('cash_book')->access()){ ?>
                <li
                  class="<?php echo (($seg_3 =="cash_book")?"mm-active":'') ?>">
                  <a href="<?php echo base_url('account/reports/cash_book')?>">
                    <?php echo get_phrases(['cash', 'book']); ?></a></li>
                <?php } ?>
                <?php if($permission->module('bank_book')->access()){ ?>
                <li
                  class="<?php echo (($seg_1=="account" && $seg_2=="reports" && $seg_3=="bank_book")?"mm-active":'') ?>">
                  <a href="<?php echo base_url('account/reports/bank_book')?>">
                    <?php echo get_phrases(['bank', 'book'])?></a></li>
                <?php } ?>

                <?php  if($permission->module('gl_reports')->access()){ ?>
                <li
                  class="<?php echo (($seg_1=="account" && $seg_2=="reports" && $seg_3=="GeneralLForm" || $seg_3=="GeneralLedgerReport")?"mm-active":'') ?>">
                  <a href="<?php echo base_url('account/reports/GeneralLForm')?>">
                    <?php echo get_phrases(['general', 'ledger'])?></a></li>
                <?php } ?>
                <li
                  class="<?php echo (($seg_1=="account" && $seg_3=="sub_ledger" )?"mm-active":'') ?>">
                  <a href="<?php echo base_url('account/reports/sub_ledger')?>">
                    <?php echo get_phrases(['sub', 'ledger'])?></a></li>
                <?php if($permission->module('profit_loss')->access()){ ?>
                <li
                  class="<?php echo (($seg_1=="account" && $seg_2=="reports" && $seg_3=="profitLossForm")?"mm-active":'') ?>">
                  <a href="<?php echo base_url('account/reports/profitLossForm')?>">
                    <?php echo get_phrases(['profit', 'loss'])?></a></li>
                <?php } if($permission->module('balance_sheet')->access()){ ?>
                <li
                  class="<?php echo (($seg_1=="account" && $seg_2=="reports" && $seg_3=="balanceSheetForm")?"mm-active":'') ?>">
                  <a href="<?php echo base_url('account/reports/balanceSheetForm')?>">
                    <?php echo get_phrases(['balance', 'sheet'])?></a></li>
                <?php } if($permission->module('cash_flow')->access()){ ?>
                <!-- <li class="<?php //echo (($seg_1=="account" && $seg_2=="reports" && ($seg_3=="cashFlowForm" || $seg_3=="cashFlowReports"))?"mm-active":'') ?>"><a href="<?php //echo base_url('account/reports/cashFlowForm')?>"> <?php //echo get_phrases(['cash', 'flow', 'statement'])?></a></li> -->
                <?php } if($permission->module('trial_balance')->access()){ ?>
                <li
                  class="<?php echo (($seg_1=="account" && $seg_2=="reports" && ($seg_3=="trialBalanceForm" || $seg_3=="trialBalanceReport"))?"mm-active":'') ?>">
                  <a href="<?php echo base_url('account/reports/trialBalanceForm')?>">
                    <?php echo get_phrases(['trial', 'balance'])?></a></li>
                <?php } ?>
              </ul>
            </li>
          </ul>
        </li>
        <!-- END Account module -->
        <?php }?>

        <!-- Human Resource module -->
        <?php if ($permission->check_label('human_resources')->access()) { ?>
        <li class="<?php echo (($seg_1 == "human_resources" || $seg_1 == "employee") ? "mm-active" : '') ?>">
          <a class="has-arrow material-ripple" href="#">
            <i class="fas fa-users"></i>
            <?php echo get_phrases(['human', 'resource']) ?>
          </a>
          <ul class="nav-second-level <?php echo (($seg_1 == "human_resources") ? "mm-collapse mm-show" : '') ?> ">

            <?php if ($permission->module('branch')->access()) { ?>
            <li
              class="<?php echo (($seg_1 == "human_resources" && $seg_2 == "branch" && $seg_3 == "branch_list") ? "mm-active" : '') ?>">
              <a href="<?php echo base_url('human_resources/branch/branch_list') ?>">
                <?php echo get_phrases(['branch', 'list']) ?></a></li>
            <?php } ?>

            <?php if ($permission->module('departments')->access()) { ?>
            <li
              class="<?php echo (($seg_1 == "human_resources" && $seg_2 == "departments" && $seg_3 == "department_list") ? "mm-active" : '') ?>">
              <a href="<?php echo base_url('human_resources/departments/department_list') ?>">
                <?php echo get_phrases(['department', 'list']) ?></a></li>
            <?php } ?>

            <?php if ($permission->module('employee')->access() || $permission->module('employee_type')->access() || $permission->module('designations')->access() || $permission->module('best_employee')->access()) { ?>
            <li class="<?php echo (($seg_1 == "employee") ? "mm-active" : '') ?>">
              <a class="has-arrow" href="#" aria-expanded="false"> <?php echo get_phrases(['employee']) ?></a>
              <ul class="nav-third-level">

                <?php if ($permission->module('employee')->access()) { ?>
                <li
                  class="<?php echo (($seg_1 == "human_resources" && $seg_2 == "employees" && $seg_3 == "employee_list") ? "mm-active" : '') ?>"
                  id="employee_list_menu"><a href="<?php echo base_url('human_resources/employees/employee_list') ?>">
                    <?php echo get_phrases(['employee', 'list']) ?></a></li>
                <?php } ?>

                <?php if ($permission->module('employee_type')->access()) { ?>
                <li
                  class="<?php echo (($seg_1 == "human_resources" && $seg_2 == "employees" && $seg_3 == "employee_type") ? "mm-active" : '') ?>">
                  <a
                    href="<?php echo base_url('human_resources/employees/employee_type') ?>"><?php echo get_phrases(['employee', 'types']) ?></a>
                </li>
                <?php } ?>

                <?php if ($permission->module('designations')->access()) { ?>
                <li
                  class="<?php echo (($seg_1 == "human_resources" && $seg_2 == "designations" && $seg_3 == "designations_list") ? "mm-active" : '') ?>">
                  <a href="<?php echo base_url('human_resources/designations/designations_list') ?>">
                    <?php echo get_phrases(['designation', 'list']) ?></a></li>
                <?php } ?>

                <?php if ($permission->module('best_employee')->access()) { ?>
                <li
                  class="<?php echo (($seg_1 == "human_resources" && $seg_2 == "employees" && $seg_3 == "best_employee_list") ? "mm-active" : '') ?>">
                  <a href="<?php echo base_url('human_resources/employees/best_employee_list') ?>">
                    <?php echo get_phrases(['best','employee', 'list']) ?></a></li>
                <?php } ?>

              </ul>
            </li>
            <?php } ?>

            <!-- Start Attendance -->
            <?php if ($permission->module('attendance_form')->access() || $permission->module('attendance_log')->access() || $permission->module('attendance_setup')->access()) { ?>
            <li>
              <a class="has-arrow" href="#" aria-expanded="false"><?php echo get_phrases(['attendance']) ?></a>
              <ul class="nav-third-level">

                <?php if ($permission->module('attendance_form')->access()) { ?>
                <li
                  class="<?php echo (($seg_1 == "human_resources" && $seg_2 == "attendances" && $seg_3 == "attendance_form") ? "mm-active" : '') ?>"
                  id="attendance_form_menu">
                  <a
                    href="<?php echo base_url('human_resources/attendances/attendance_form') ?>"><?php echo get_phrases(['attendance', 'form']) ?></a>
                </li>
                <?php } ?>

                <?php if ($permission->module('attendance_log')->access()) { ?>
                <li
                  class="<?php echo (($seg_1 == "human_resources" && $seg_2 == "attendances" && $seg_3 == "attendance_log") ? "mm-active" : '') ?>"
                  id="attendance_log_menu">
                  <a
                    href="<?php echo base_url('human_resources/attendances/attendance_log') ?>"><?php echo get_phrases(['attendance', 'log']) ?></a>
                </li>
                <?php } ?>

                <?php if ($permission->module('attendance_setup')->access()) { ?>
                <li
                  class="<?php echo (($seg_1 == "human_resources" && $seg_2 == "attendances" && $seg_3 == "attendance_setup") ? "mm-active" : '') ?>"
                  id="attendance_setup_menu">
                  <a
                    href="<?php echo base_url('human_resources/attendances/attendance_setup') ?>"><?php echo get_phrases(['attendance', 'setup']) ?></a>
                </li>
                <?php } ?>

              </ul>
            </li>

            <?php } ?>

            <!-- End Attendnace -->


            <!-- Start Weekends & Holidays -->
            <?php if ($permission->module('week_ends')->access() || $permission->module('govt_holidays')->access()) { ?>
            <li>
              <a class="has-arrow" href="#" aria-expanded="false"><?php echo get_phrases(['holidays']) ?></a>
              <ul class="nav-third-level">

                <?php if ($permission->module('week_ends')->access()) { ?>
                <li
                  class="<?php echo (($seg_1 == "human_resources" && $seg_2 == "holidays" && $seg_3 == "weekends") ? "mm-active" : '') ?>">
                  <a
                    href="<?php echo base_url('human_resources/holidays/weekends') ?>"><?php echo get_phrases(['weekends']) ?></a>
                </li>
                <?php } ?>

                <?php if ($permission->module('govt_holidays')->access()) { ?>
                <li
                  class="<?php echo (($seg_1 == "human_resources" && $seg_2 == "holidays" && $seg_3 == "govt_holidays") ? "mm-active" : '') ?>">
                  <a
                    href="<?php echo base_url('human_resources/holidays/govt_holidays') ?>"><?php echo get_phrases(['govt', 'holidays']) ?></a>
                </li>
                <?php } ?>

              </ul>
            </li>

            <?php } ?>

            <!-- End Weekends & Holidays -->

            <!-- Start Leave Management -->
            <?php if ($permission->module('leave_type')->access() || $permission->module('leave_application')->access() || $permission->module('superior_approval')->access() || $permission->module('leave_approval')->access() || $permission->module('earned_leave')->access() || $permission->module('cpl_leave')->access()) { ?>
            <li>
              <a class="has-arrow" href="#" aria-expanded="false"><?php echo get_phrases(['leave','management']) ?></a>
              <ul class="nav-third-level">
                <?php if ($permission->module('earned_leave')->access()) { ?>
                <li
                  class="<?php echo (($seg_1 == "human_resources" && $seg_2 == "leave_management" && $seg_3 == "earned_leave") ? "mm-active" : '') ?>">
                  <a
                    href="<?php echo base_url('human_resources/leave_management/earned_leave') ?>"><?php echo get_phrases(['earned', 'leave']) ?></a>
                </li>
                <?php } ?>
                <?php if ($permission->module('cpl_leave')->access()) { ?>
                <!-- <li class="<?php echo (($seg_1 == "human_resources" && $seg_2 == "leave_management" && $seg_3 == "cpl_leave") ? "mm-active" : '') ?>">
                        <a href="<?php echo base_url('human_resources/leave_management/cpl_leave') ?>"><?php echo get_phrases(['cpl', 'leave']) ?></a>
                      </li> -->
                <?php } ?>
                <?php if ($permission->module('leave_type')->access()) { ?>
                <li
                  class="<?php echo (($seg_1 == "human_resources" && $seg_2 == "leave_management" && $seg_3 == "leave_type") ? "mm-active" : '') ?>">
                  <a
                    href="<?php echo base_url('human_resources/leave_management/leave_type') ?>"><?php echo get_phrases(['leave', 'type']) ?></a>
                </li>
                <?php } ?>

                <!-- <?php if ($permission->module('leave_application')->access()) { ?>
                      <li class="<?php echo (($seg_1 == "human_resources" && $seg_2 == "leave_management" && $seg_3 == "leave_application") ? "mm-active" : '') ?>">
                        <a href="<?php echo base_url('human_resources/leave_management/leave_application') ?>"><?php echo get_phrases(['leave', 'application']) ?></a>
                      </li>
                    <?php } ?> -->


                <?php if($permission->module('leave_application')->access() || $permission->module('superior_approval')->access() || $permission->module('leave_approval')->access()){ ?>

                <li
                  class="<?php echo (($seg_3=="leave_application" || $seg_3=="superior_approval" || $seg_3=="leave_approval")?"mm-collapse mm-show":'') ?>">

                  <a class="has-arrow" href="#" aria-expanded="false"><?php echo get_phrases(['leave'])?></a>
                  <ul class="nav-fourth-level">

                    <?php if($permission->module('leave_application')->access() && !session('isAdmin')){ ?>

                    <li
                      class="<?php echo (($seg_1 == "human_resources" && $seg_2 == "leave_management" && $seg_3 == "leave_application")?"mm-active":'') ?>">
                      <a href="<?php echo base_url('human_resources/leave_management/leave_application')?>">
                        <?php echo get_phrases(['leave','application'])?></a></li>

                    <?php } ?>

                    <?php if($permission->module('superior_approval')->access()){ ?>

                    <li
                      class="<?php echo (($seg_1 == "human_resources" && $seg_2 == "leave_management" && $seg_3 == "superior_approval")?"mm-active":'') ?>">
                      <a href="<?php echo base_url('human_resources/leave_management/superior_approval')?>">
                        <?php echo get_phrases(['speriror','approval'])?></a></li>

                    <?php } ?>

                    <?php if($permission->module('leave_approval')->access()){ ?>

                    <li
                      class="<?php echo (($seg_1 == "human_resources" && $seg_2 == "leave_management" && $seg_3 == "leave_approval")?"mm-active":'') ?>">
                      <a href="<?php echo base_url('human_resources/leave_management/leave_approval')?>">
                        <?php echo get_phrases(['leave','approval'])?></a></li>

                    <?php } ?>

                  </ul>
                </li>
                <?php } ?>


              </ul>
            </li>
            <?php } ?>

            <!-- End Leave Management -->

            <!-- Duty Roster module -->
            <?php if($permission->module('shift')->access() || $permission->module('roster')->access() || $permission->module('roster_assign')->access() || $permission->module('attendance_dashboard')->access()){ ?>

            <li
              class="<?php echo (($seg_1=="human_resources" && $seg_2=="duty_roster" && ($seg_3=="shift" || $seg_3=="roster" || $seg_3=="roster_assign" || $seg_3=="attendance_dashboard" || $seg_3=="roster_shift_assign" || $seg_3 == "shiftRosterList" || $seg_3 == "update_shiftAssignForm"))?"mm-active":'') ?>">

              <a class="has-arrow" href="#" aria-expanded="false"><?php echo get_phrases(['duty','roster'])?></a>
              <ul class="nav-third-level">

                <?php if($permission->module('shift')->access()){ ?>
                <li
                  class="<?php echo (($seg_1=="human_resources" && $seg_2=="duty_roster" && $seg_3=="shift")?"mm-active":'') ?>">
                  <a
                    href="<?php echo base_url('human_resources/duty_roster/shift')?>"><?php echo get_phrases(['shift'])?></a>
                </li>
                <?php }?>

                <?php if($permission->module('roster')->access()){ ?>
                <li
                  class="<?php echo (($seg_1=="human_resources" && $seg_2=="duty_roster" && $seg_3=="roster")?"mm-active":'') ?>">
                  <a
                    href="<?php echo base_url('human_resources/duty_roster/roster')?>"><?php echo get_phrases(['roster'])?></a>
                </li>
                <?php }?>

                <?php if($permission->module('roster_assign')->access()){ ?>
                <li
                  class="<?php echo (($seg_1=="human_resources" && $seg_2=="duty_roster" && $seg_3=="roster_assign" || $seg_3=="roster_shift_assign" || $seg_3 == "shiftRosterList" || $seg_3 == "update_shiftAssignForm")?"mm-active":'') ?>">
                  <a
                    href="<?php echo base_url('human_resources/duty_roster/roster_assign')?>"><?php echo get_phrases(['roster','assign'])?></a>
                </li>
                <?php }?>

                <?php if($permission->module('attendance_dashboard')->access()){ ?>
                <li
                  class="<?php echo (($seg_1=="human_resources" && $seg_2=="duty_roster" && $seg_3=="attendance_dashboard")?"mm-active":'') ?>">
                  <a
                    href="<?php echo base_url('human_resources/duty_roster/attendance_dashboard')?>"><?php echo get_phrases(['attendance','dashboard'])?></a>
                </li>
                <?php }?>

              </ul>
            </li>

            <?php } ?>
            <!-- End of Duty Roster module -->

            <!-- Start Setup -->
            <?php if ($permission->module('basic_salary_setup')->access() || $permission->module('allowance_setup')->access()) { ?>
            <li>
              <a class="has-arrow" href="#" aria-expanded="false"><?php echo get_phrases(['setup']) ?></a>
              <ul class="nav-third-level">

                <?php if ($permission->module('attendance_form')->access()) { ?>
                <li
                  class="<?php echo (($seg_1 == "human_resources" && $seg_2 == "basic_salary_setup" && $seg_3 == "basic_salary_setup_list") ? "mm-active" : '') ?>">
                  <a
                    href="<?php echo base_url('human_resources/basic_salary_setup/basic_salary_setup_list') ?>"><?php echo get_phrases(['basic', 'salary', 'setup']) ?></a>
                </li>
                <?php } ?>

                <?php if ($permission->module('allowance_setup')->access()) { ?>
                <li
                  class="<?php echo (($seg_1 == "human_resources" && $seg_2 == "allowance_setup" && $seg_3 == "allowance_setup_list") ? "mm-active" : '') ?>">
                  <a
                    href="<?php echo base_url('human_resources/allowance_setup/allowance_setup_list') ?>"><?php echo get_phrases(['allowance', 'setup']) ?></a>
                </li>
                <?php } ?>

              </ul>
            </li>

            <?php } ?>

            <!-- End Setup -->

            <?php if ($permission->method('payroll', 'read')->access() || $permission->method('add_benefits', 'read')->access() || $permission->method('benefit_list', 'read')->access() || $permission->method('add_salarysetup', 'read')->access() || $permission->method('salary_setup_list', 'read')->access() || $permission->method('over_time', 'read')->access() || $permission->method('salary_generate', 'read')->access()) { ?>

            <li class="<?php echo (($seg_1 == "payroll") ? "mm-active" : '') ?>">
              <a class="has-arrow" href="#" aria-expanded="false"> <?php echo get_phrases(['payroll']) ?></a>
              <ul class="nav-third-level">
                <?php if ($permission->method('add_benefits', 'create')->access()) { ?>
                <li
                  class="<?php echo (($seg_1 == "human_resources" && $seg_2 == "payroll" && $seg_3 == "add_benefits") ? "mm-active" : '') ?>">
                  <a
                    href="<?php echo base_url('human_resources/payroll/add_benefits') ?>"><?php echo get_phrases(['add', 'benefits']) ?></a>
                </li>
                <?php } ?>
                <?php if ($permission->method('benefit_list', 'read')->access()) { ?>
                <li
                  class="<?php echo (($seg_1 == "human_resources" && $seg_2 == "payroll" && $seg_3 == "benefit_list") ? "mm-active" : '') ?>">
                  <a
                    href="<?php echo base_url('human_resources/payroll/benefit_list') ?>"><?php echo get_phrases(['benefit', 'list']) ?></a>
                </li>
                <?php } ?>
                <?php if ($permission->method('add_salarysetup', 'create')->access()) { ?>

                <li
                  class="<?php echo (($seg_1 == "human_resources" && $seg_2 == "payroll" && $seg_3 == "add_salarysetup") ? "mm-active" : '') ?>">
                  <a
                    href="<?php echo base_url('human_resources/payroll/add_salarysetup') ?>"><?php echo get_phrases(['add', 'salarysetup']) ?></a>
                </li>
                <?php } ?>
                <?php if ($permission->method('salary_setup_list', 'read')->access()) { ?>
                <li
                  class="<?php echo (($seg_1 == "human_resources" && $seg_2 == "payroll" && $seg_3 == "salary_setup_list") ? "mm-active" : '') ?>">
                  <a
                    href="<?php echo base_url('human_resources/payroll/salary_setup_list') ?>"><?php echo get_phrases(['salary', 'setup_list']) ?></a>
                </li>
                <?php } ?>

                <?php if ($permission->method('over_time', 'read')->access()) { ?>
                <li
                  class="<?php echo (($seg_1 == "human_resources" && $seg_2 == "payroll" && $seg_3 == "over_time") ? "mm-active" : '') ?>">
                  <a
                    href="<?php echo base_url('human_resources/payroll/over_time') ?>"><?php echo get_phrases(['over','time']) ?></a>
                </li>
                <?php } ?>

                <?php if ($permission->method('salary_generate', 'create')->access()) { ?>
                <li
                  class="<?php echo (($seg_1 == "human_resources" && $seg_2 == "payroll" && $seg_3 == "salary_generate") ? "mm-active" : '') ?>">
                  <a
                    href="<?php echo base_url('human_resources/payroll/salary_generate') ?>"><?php echo get_phrases(['salary', 'generate']) ?></a>
                </li>
                <?php } ?>
                <?php if ($permission->method('salary_sheet', 'read')->access()) { ?>
                <li
                  class="<?php echo (($seg_1 == "human_resources" && $seg_2 == "payroll" && $seg_3 == "salary_sheet") ? "mm-active" : '') ?>">
                  <a
                    href="<?php echo base_url('human_resources/payroll/salary_sheet') ?>"><?php echo get_phrases(['salary', 'sheet']) ?></a>
                </li>
                <?php } ?>

                <?php if ($permission->method('salary_payment', 'read')->access()) { ?>
                <li
                  class="<?php echo (($seg_1 == "human_resources" && $seg_2 == "payroll" && $seg_3 == "salary_payment") ? "mm-active" : '') ?>">
                  <a
                    href="<?php echo base_url('human_resources/payroll/salary_payment') ?>"><?php echo get_phrases(['salary', 'payment']) ?></a>
                </li>
                <?php } ?>

              </ul>
            </li>
            <?php } ?>

            <!-- Start Reports -->
            <?php if ($permission->module('attendance_sheet')->access() || $permission->module('overtime_sheet')->access()) { ?>
            <li>
              <a class="has-arrow" href="#" aria-expanded="false"><?php echo get_phrases(['reports']) ?></a>
              <ul class="nav-third-level">

                <?php if ($permission->module('attendance_sheet')->access()) { ?>
                <li
                  class="<?php echo (($seg_1 == "human_resources" && $seg_2 == "reports" && $seg_3 == "attendance_sheet") ? "mm-active" : '') ?>">
                  <a
                    href="<?php echo base_url('human_resources/reports/attendance_sheet') ?>"><?php echo get_phrases(['attendance', 'sheet']) ?></a>
                </li>
                <?php } ?>

                <!-- Write another <li></li> here like above -->

              </ul>
            </li>

            <?php } ?>

            <!-- End Reports -->

            <!-- <?php if ($permission->check_label('expense', 'read')->access()) { ?>
                      <li>
                       <a class="has-arrow" href="#" aria-expanded="false"> <?php echo get_phrases(['expense']) ?></a>
                        <ul class="nav-third-level">
                          <?php if ($permission->check_label('add_expense_item', 'create')->access()) { ?>
                          <li class="<?php echo (($seg_3 == "add_expense_item") ? "mm-active" : '') ?>"><a href="<?php echo base_url('expense/add_expense_item') ?>"><?php echo get_phrases(['add_expense_item']) ?></a></li>
                          <?php } ?>
                          <?php if ($permission->check_label('expense_item_list', 'read')->access()) { ?>
                          <li class="<?php echo (($seg_3 == "expense_item_list") ? "mm-active" : '') ?>"><a href="<?php echo base_url('expense/expense_item_list') ?>"><?php echo get_phrases(['expense_item_list']) ?></a></li>
                          <?php } ?>
                          <?php if ($permission->check_label('add_expense', 'create')->access()) { ?>
                          <li class="<?php echo (($seg_3 == "add_expense") ? "mm-active" : '') ?>"><a href="<?php echo base_url('expense/add_expense') ?>"><?php echo get_phrases(['add_expense']) ?></a></li>
                          <?php } ?>
                          <?php if ($permission->check_label('expense_list', 'read')->access()) { ?>
                          <li class="<?php echo (($seg_3 == "expense_list") ? "mm-active" : '') ?>"><a href="<?php echo base_url('expense/expense_list') ?>"><?php echo get_phrases(['expense_list']) ?></a></li>
                          <?php } ?>
                           <?php if ($permission->check_label('expense_statement', 'read')->access()) { ?>
                          <li class="<?php echo (($seg_3 == "expense_statement") ? "mm-active" : '') ?>"><a href="<?php echo base_url('expense/expense_statement') ?>"><?php echo get_phrases(['expense_statement']) ?></a></li>
                          <?php } ?>
                          </ul>
                       </li>
                     <?php } ?> -->

            <!-- End Employee salary -->

          </ul>
        </li>
        <?php } ?>
        <!-- END Human Resource module -->

        <!-- Reports module -->
        <?php if($permission->check_label('all_reports')->access()){ ?>
        <li class="<?php echo (($seg_1=="reports")?"mm-active":'') ?>">
          <a class="has-arrow material-ripple" href="#">
            <i class="fas fa-signal"></i>
            <?php echo get_phrases(['reports'])?>
          </a>
          <ul class="nav-second-level <?php echo (($seg_1=="reports")?"mm-collapse mm-show":'') ?> ">
            <?php 
                        if($permission->module('sales_reports')->access()){ ?>
            <li>
              <a class="has-arrow" href="#" aria-expanded="false"><?php echo get_phrases(['sales'])?></a>
              <ul class="nav-third-level">
                <li class="<?php echo (($seg_2=="sales" && $seg_3=="daily_sales")?"mm-active":'') ?>"><a
                    href="<?php echo base_url('reports/sales/daily_sales')?>">
                    <?php echo get_phrases(['daily', 'sales', 'report']); ?></a></li>
                <li class="<?php echo (($seg_2=="sales" && $seg_3=="do_summary")?"mm-active":'') ?>"><a
                    href="<?php echo base_url('reports/sales/do_summary')?>">
                    <?php echo get_phrases(['DO', 'summary', 'report']); ?></a></li>
                <li class="<?php echo (($seg_2=="sales" && $seg_3=="long_credit")?"mm-active":'') ?>"><a
                    href="<?php echo base_url('reports/sales/long_credit')?>">
                    <?php echo get_phrases(['long', 'credit', 'report']); ?></a></li>
                <li class="<?php echo (($seg_2=="sales" && $seg_3=="short_credit")?"mm-active":'') ?>"><a
                    href="<?php echo base_url('reports/sales/short_credit')?>">
                    <?php echo get_phrases(['short', 'credit', 'report']); ?></a></li>
                <li class="<?php echo (($seg_2=="sales" && $seg_3=="purchase_dept")?"mm-active":'') ?>"><a
                    href="<?php echo base_url('reports/sales/purchase_dept')?>">
                    <?php echo get_phrases(['purchase', 'department', 'report']); ?></a></li>
                <li class="<?php echo (($seg_2=="sales" && $seg_3=="distributor_wise_sales")?"mm-active":'') ?>"><a
                    href="<?php echo base_url('reports/sales/distributor_wise_sales')?>">
                    <?php echo get_phrases(['distributor', 'wise', 'sales', 'report']); ?></a></li>
                <li class="<?php echo (($seg_2=="sales" && $seg_3=="officer_wise_sales")?"mm-active":'') ?>"><a
                    href="<?php echo base_url('reports/sales/officer_wise_sales')?>">
                    <?php echo get_phrases(['officer', 'wise', 'sales', 'report']); ?></a></li>
              </ul>
            </li>
            <?php }
                        if($permission->module('production_reports')->access()){ ?>
            <li>
              <a class="has-arrow" href="#" aria-expanded="false"><?php echo get_phrases(['production'])?></a>
              <ul class="nav-third-level">
                <li class="<?php echo (($seg_2=="production" && $seg_3=="daily_production")?"mm-active":'') ?>"><a
                    href="<?php echo base_url('reports/production/daily_production')?>">
                    <?php echo get_phrases(['production','statement'])?></a></li>
              </ul>
            </li>
            <?php }
                        if($permission->module('machine_reports')->access()){ ?>
            <li>
              <a class="has-arrow" href="#" aria-expanded="false"><?php echo get_phrases(['raw','material'])?></a>
              <ul class="nav-third-level">
                <li class="<?php echo (($seg_2=="machine" && $seg_3=="daily_consumption")?"mm-active":'') ?>"><a
                    href="<?php echo base_url('reports/machine/daily_consumption')?>">
                    <?php echo get_phrases(['daily','stock','position'])?></a></li>
                <li class="<?php echo (($seg_2=="machine" && $seg_3=="plant_consumption")?"mm-active":'') ?>"><a
                    href="<?php echo base_url('reports/machine/plant_consumption')?>">
                    <?php echo get_phrases(['daily','consumption'])?></a></li>
              </ul>
            </li>
            <?php } 
                        if($permission->module('bag_reports')->access()){ ?>
            <li>
              <a class="has-arrow" href="#" aria-expanded="false"><?php echo get_phrases(['bag'])?></a>
              <ul class="nav-third-level">
                <li class="<?php echo (($seg_2=="bag" && $seg_3=="bag_stock")?"mm-active":'') ?>"><a
                    href="<?php echo base_url('reports/bag/bag_stock')?>">
                    <?php echo get_phrases(['bag','consumption'])?></a></li>
              </ul>
            </li>
            <?php } ?>

          </ul>
        </li>
        <?php } ?>
        <!-- END Reports module -->



        <!-- permission module -->
        <?php if($permission->check_label('user_management')->access()){ ?>
        <li class="<?php echo (($seg_1=="permission" && $seg_2 !="checker")?"mm-active":'') ?>">
          <a class="has-arrow material-ripple" href="#">
            <i class="fas fa-user"></i>
            <?php echo get_phrases(['user', 'management'])?>
          </a>
          <ul
            class="nav-second-level <?php echo (($seg_1=="permission" && $seg_2 !="checker")?"mm-collapse mm-show":'') ?> ">
            <?php if($permission->module('role')->access()){ ?>
            <li class="<?php echo (($seg_2=="roles")?"mm-active":'') ?>"><a
                href="<?php echo base_url('permission/roles')?>"> <?php echo get_phrases(['role', 'list'])?></a></li>
            <?php } if($permission->module('module')->access()){ ?>
            <li class="<?php echo (($seg_2=="modules")?"mm-active":'') ?>"><a
                href="<?php echo base_url('permission/modules')?>"> <?php echo get_phrases(['module', 'list'])?></a>
            </li>
            <?php } if($permission->module('sys_users')->access()){ ?>
            <li class="<?php echo (($seg_2=="users")?"mm-active":'') ?>"><a
                href="<?php echo base_url('permission/users')?>"> <?php echo get_phrases(['user', 'list'])?></a></li>
            <?php } if($permission->module('activities')->access()){ ?>
            <li class="<?php echo (($seg_2=="activities")?"mm-active":'') ?>"><a
                href="<?php echo base_url('permission/activities')?>">
                <?php echo get_phrases(['activities', 'list'])?></a></li>
            <?php }?>
          </ul>
        </li>
        <?php }?>
        <!-- END permission module -->

        <li class="<?php echo (($seg_2=="checker")?"mm-active":'') ?>"><a
            href="<?php echo base_url('permission/checker')?>"><i
              class="fas fa-user-lock"></i><?php echo get_phrases(['permission', 'checker'])?></a></li>





        <!-- date export module -->
        <?php if($permission->check_label('data_export')->access()){ ?>
        <li class="<?php echo (($seg_1=="export")?"mm-active":'') ?>">
          <a class="has-arrow material-ripple" href="#">
            <i class="fas fa-file-export"></i>
            <?php echo get_phrases(['data', 'backup'])?>
          </a>
          <ul class="nav-second-level <?php echo (($seg_1=="export")?"mm-collapse mm-show":'') ?> ">
            <?php /*if($permission->module('data_export')->access()){ ?>
            <li class="<?php echo (($seg_2=="export")?"mm-active":'') ?>"><a
                href="<?php echo base_url('data_export/export')?>"> <?php echo get_phrases(['partial','backup'])?></a>
            </li>
            <?php }*/ if($permission->module('data_export')->access()){ ?>
            <li class="<?php echo (($seg_2=="backup")?"mm-active":'') ?>"><a
                href="<?php echo base_url('data_export/backup')?>"> <?php echo get_phrases(['backup','list'])?></a></li>
            <?php } ?>
          </ul>
        </li>
        <?php }?>
        <!-- END data export module -->

        <!-- settings module -->
        <?php if($permission->check_label('app_setting')->access()){ ?>
        <li class="<?php echo (($seg_1=="settings")?"mm-active":'') ?>">
          <a class="has-arrow material-ripple" href="#">
            <i class="fas fa-cog"></i>
            <?php echo get_phrases(['application', 'settings'])?>
          </a>
          <ul class="nav-second-level <?php echo (($seg_1=="settings")?"mm-collapse mm-show":'') ?> ">
            <?php if($permission->module('setting')->access()){ ?>
            <li class="<?php echo (($seg_2=="application")?"mm-active":'') ?>"><a
                href="<?php echo base_url('settings/application')?>">
                <?php echo get_phrases(['application','setting'])?></a></li>
            <?php }if($permission->module('referral_commissions')->access()){ ?>
            <li class="<?php echo (($seg_2=="referral_commissions")?"mm-active":'') ?>"><a
                href="<?php echo base_url('settings/referral_commissions')?>">
                <?php echo get_phrases(['referral','commission'])?></a></li>
            <?php } if($permission->module('financial_year')->access()){ ?>

            <?php } if($permission->module('setting')->access()){ ?>
            <li class="<?php echo (($seg_2=="lists")?"mm-active":'') ?>"><a
                href="<?php echo base_url('settings/lists')?>"> <?php echo get_phrases(['parameter', 'list'])?></a></li>
            <?php } if($permission->method('template','create')->access()){ ?>
            <li class="<?php echo (($seg_1=="template" & $seg_2=="template_list")?"mm-active":'') ?>"><a
                href="<?php echo base_url('template/template_list')?>"> <?php echo get_phrases(['template'])?></a></li>
            <?php } if($permission->method('languages','create')->access()){ ?>
            <li class="<?php echo (($seg_1=="settings" & $seg_2=="languages")?"mm-active":'') ?>"><a
                href="<?php echo base_url('settings/languages')?>"> <?php echo get_phrases(['languages'])?></a></li>
            <?php }?>
          </ul>
        </li>
        <?php }?>
        <!-- END settings module -->
      </ul>
    </nav>
  </div><!-- sidebar-body -->
</nav>