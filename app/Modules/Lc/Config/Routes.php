<?php

if(!isset($routes))
{ 
    $routes = \Config\Services::routes(true);
}

$routes->group('lc', ['namespace' => 'App\Modules\Lc\Controllers'], function($subroutes){
    // routes of Lc
    $subroutes->add('lcs', 'Bdtaskt1m12c1Lc::index');
    $subroutes->add('lc_info', 'Bdtaskt1m12c1Lc::bdtaskt1m12c1_01_lcInfo');
    $subroutes->add('lc_info/(:num)', 'Bdtaskt1m12c1Lc::bdtaskt1m12c1_01_lcInfo/$1');
    $subroutes->add('get_item_list', 'Bdtaskt1m12c1Lc::bdtaskt1m12c1_01_get_item_list');
    $subroutes->add('getItemDetailsById', 'Bdtaskt1m12c1Lc::bdtaskt1m12c1_01_getItemDetailsById');
    $subroutes->add('getPurchasedItemDetailsById', 'Bdtaskt1m12c1Lc::bdtaskt1m12c1_01_getPurchasedItemDetailsById');
    $subroutes->add('getList', 'Bdtaskt1m12c1Lc::bdtaskt1m12c1_01_getList');
    $subroutes->add('approve', 'Bdtaskt1m12c1Lc::bdtaskt1m12c1_01_approve');
    $subroutes->add('add_lc', 'Bdtaskt1m12c1Lc::bdtaskt1m12c1_01_add_lc');
    $subroutes->add('get_lcrow/(:num)', 'Bdtaskt1m12c1Lc::bdtaskt1m12c1_01_get_lc/$1');
    $subroutes->add('deleteLc', 'Bdtaskt1m12c1Lc::bdtaskt1m12c1_01_delete_lc');
    $subroutes->add('view_cost/(:num)', 'Bdtaskt1m12c1Lc::view_cost/$1');
    $subroutes->add('add_lc_cost', 'Bdtaskt1m12c1Lc::bdtaskt1m12c1_01_add_lc_cost');
    $subroutes->add('getAllLcCost', 'Bdtaskt1m12c1Lc::bdtaskt1m12c1_01_getAllLcCost');
    $subroutes->add('get_lc_details/(:num)', 'Bdtaskt1m12c1Lc::bdtaskt1m12c1_01_lc_view/$1');
    $subroutes->add('lc_payment', 'Bdtaskt1m12c1Lc::bdtaskt1m12c1_01_lcPayment');
    $subroutes->add('lc_loan_info', 'Bdtaskt1m12c1Lc::bdtaskt1m12c1_01_lc_loan_info');
    $subroutes->add('loan_repay', 'Bdtaskt1m12c1Lc::bdtaskt1m12c1_01_loan_repay');
    $subroutes->add('truncate_lc', 'Bdtaskt1m12c1Lc::bdtaskt1m12c12_04_truncate_lc');

    
    //item receive routes  
    $subroutes->add('item_receive', 'Bdtaskt1m12c12ItemReceive::index');
    $subroutes->add('getItemReceive', 'Bdtaskt1m12c12ItemReceive::bdtaskt1m12c12_01_getList');
    $subroutes->add('deleteItemReceive/(:num)', 'Bdtaskt1m12c12ItemReceive::bdtaskt1m12c12_02_deleteItemReceive/$1');
    $subroutes->add('getItemReceiveById/(:num)', 'Bdtaskt1m12c12ItemReceive::bdtaskt1m12c12_04_getItemReceiveById/$1');
    $subroutes->add('add_item_receive', 'Bdtaskt1m12c12ItemReceive::bdtaskt1m12c12_03_addItemReceive');
    $subroutes->add('getItemReceiveDetailsById/(:num)', 'Bdtaskt1m12c12ItemReceive::bdtaskt1m12c12_05_getItemReceiveDetailsById/$1');
    $subroutes->add('getItemPurchaseDetailsById', 'Bdtaskt1m12c12ItemReceive::bdtaskt1m12c12_07_getItemPurchaseDetailsById'); 
    $subroutes->add('getItemPurchasePricingDetailsById', 'Bdtaskt1m12c12ItemReceive::bdtaskt1m12c12_08_getItemPurchasePricingDetailsById');
    $subroutes->add('get_po_list', 'Bdtaskt1m12c12ItemReceive::bdtaskt1m12c10_get_po_list');
    $subroutes->add('select_shipment', 'Bdtaskt1m12c12ItemReceive::bdtaskt1m12c10_select_shipment');

    $subroutes->add('item_purchase', 'Bdtaskt1m12c7ItemPurchase::index');
});