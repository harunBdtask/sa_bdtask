<?php

if(!isset($routes))
{ 
    $routes = \Config\Services::routes(true);
}

$routes->group('commission', ['namespace' => 'App\Modules\Commission\Controllers'], function($subroutes){
    // routes of Commissioin
    $subroutes->add('commission_setting', 'Bdtaskcommission::index');
    $subroutes->add('getcommission_list', 'Bdtaskcommission::bdtaskt1m13c2_01_getSettingList');
    $subroutes->add('add_new_commission_setting', 'Bdtaskcommission::bdtaskt1m13c2_02_addnewSetting');
    $subroutes->add('getCommissionDetailsById/(:num)', 'Bdtaskcommission::bdtaskt1m12c2_04_getcommissionById/$1');
    $subroutes->add('deletecommission_setting/(:num)', 'Bdtaskcommission::bdtaskt1m12c2_05_deleteCommissionSetting/$1');
    $subroutes->add('monthly_dealer_target_range_commission', 'Bdtaskcommission::bdtask003_monthly_dealer_targetRange_commission');
    $subroutes->add('monthly_dealer_sales_commission', 'Bdtaskcommission::bdtask004_monthly_dealer_sales_commission');
    $subroutes->add('getDealerSalesCommissininfo/(:num)', 'Bdtaskcommission::bdtaskt1m12c2_06_getdealercommissionInfo/$1');
    $subroutes->add('save_dealer_target_commission', 'Bdtaskcommission::bdtask005_save_monthly_dealer_commission');
    $subroutes->add('getdealer_sales_commission', 'Bdtaskcommission::bdtaskt1m13c2_02_getSaleCommission');
    $subroutes->add('deletemonthly_commission/(:num)', 'Bdtaskcommission::bdtaskt1m12c2_08_deletemonthly_saleCommission/$1');
   $subroutes->add('getdealer_target_sales_commission', 'Bdtaskcommission::bdtaskt1m13c2_04_getTargetSaleCommission');

});