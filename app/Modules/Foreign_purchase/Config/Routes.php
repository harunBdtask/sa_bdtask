<?php

if(!isset($routes))
{ 
    $routes = \Config\Services::routes(true);
}

$routes->group('foreign_purchase', ['namespace' => 'App\Modules\Foreign_purchase\Controllers'], function($subroutes){

    // //item spr routes  
    // $subroutes->add('requisition', 'Bdtaskt1m12c10Requisition::index');
    // $subroutes->add('getRequisition', 'Bdtaskt1m12c10Requisition::bdtaskt1m12c10_01_getList');

    // $subroutes->add('requisition_approve', 'Bdtaskt1m12c10Requisition::bdtaskt1m12c10_07_approvePurchaseOrder');

    // $subroutes->add('deleteRequisition/(:num)', 'Bdtaskt1m12c10Requisition::bdtaskt1m12c10_02_deletePurchaseOrder/$1');
    // $subroutes->add('getRequisitionById/(:num)', 'Bdtaskt1m12c10Requisition::bdtaskt1m12c10_04_getPurchaseOrderById/$1');
    // $subroutes->add('add_requisition', 'Bdtaskt1m12c10Requisition::bdtaskt1m12c10_03_addPurchaseOrder');
    // $subroutes->add('getRequisitionDetailsById/(:num)', 'Bdtaskt1m12c10Requisition::bdtaskt1m12c10_05_getPurchaseOrderDetailsById/$1');
    // $subroutes->add('getRequisitionPricingDetailsById', 'Bdtaskt1m12c10Requisition::bdtaskt1m12c10_06_getPurchaseOrderPricingDetailsById'); 
    // $subroutes->add('getRequisitionItemDetailsById', 'Bdtaskt1m12c10Requisition::bdtaskt1m12c10_07_getPurchaseOrderItemDetailsById'); 
    
  
    //item receive routes  
    $subroutes->add('item_receive', 'BdtaskttItemReceive::index');
    $subroutes->add('getItemReceive', 'BdtaskttItemReceive::bdtaskt1m12c12_01_getList');
    $subroutes->add('deleteItemReceive/(:num)', 'BdtaskttItemReceive::bdtaskt1m12c12_02_deleteItemReceive/$1');
    $subroutes->add('getItemReceiveById/(:num)', 'BdtaskttItemReceive::bdtaskt1m12c12_04_getItemReceiveById/$1');
    $subroutes->add('add_item_receive', 'BdtaskttItemReceive::bdtaskt1m12c12_03_addItemReceive');
    $subroutes->add('getItemReceiveDetailsById/(:num)', 'BdtaskttItemReceive::bdtaskt1m12c12_05_getItemReceiveDetailsById/$1');
    $subroutes->add('getItemPurchaseDetailsById', 'BdtaskttItemReceive::bdtaskt1m12c12_07_getItemPurchaseDetailsById/$1'); 
    $subroutes->add('getItemPurchasePricingDetailsById', 'BdtaskttItemReceive::bdtaskt1m12c12_08_getItemPurchasePricingDetailsById/$1');


    //Received Voucher  
    $subroutes->add('item_purchase', 'Bdtaskt1m12c7ItemPurchase::index');
    $subroutes->add('getItemPurchase', 'Bdtaskt1m12c7ItemPurchase::bdtaskt1m12c7_01_getList');
    $subroutes->add('deleteItemPurchase/(:num)', 'Bdtaskt1m12c7ItemPurchase::bdtaskt1m12c7_02_deleteItemPurchase/$1');
    $subroutes->add('getItemPurchaseById/(:num)', 'Bdtaskt1m12c7ItemPurchase::bdtaskt1m12c7_04_getItemPurchaseById/$1');
    $subroutes->add('getMaterialReceiveDetailsById/(:num)', 'Bdtaskt1m12c7ItemPurchase::bdtaskt1m12c7_05_getItemReceiveDetailsById/$1');
    $subroutes->add('getItemPurchaseDetailsById/(:num)', 'Bdtaskt1m12c7ItemPurchase::bdtaskt1m12c7_05_getItemPurchaseDetailsById/$1');
    $subroutes->add('getItemPricingDetailsById', 'Bdtaskt1m12c7ItemPurchase::bdtaskt1m12c7_06_getItemPricingDetailsById'); 
    $subroutes->add('getReturnDetailsById/(:num)', 'Bdtaskt1m12c7ItemPurchase::bdtaskt1m12c7_07_getReturnDetailsById/$1');
    $subroutes->add('getReturnItemDetailsById', 'Bdtaskt1m12c7ItemPurchase::bdtaskt1m12c7_08_getReturnItemDetailsById');


    //PO  
    $subroutes->add('purchase_order', 'BdtaskttPurchase::index');
    $subroutes->add('getPo', 'BdtaskttPurchase::bdtasktt_ahpo_getList');
    $subroutes->add('get_item_list', 'BdtaskttPurchase::bdtasktt_get_item_list');
    $subroutes->add('add_po', 'BdtaskttPurchase::bdtasktt_create_po');
    $subroutes->add('getItemInfo', 'BdtaskttPurchase::bdtasktt_get_item_info');
    $subroutes->add('getPurchaseDetailsById/(:num)', 'BdtaskttPurchase::bdtaskttt_getPurchaseDetailsById/$1');
    $subroutes->add('getPurchaseItemDetails', 'BdtaskttPurchase::getPurchaseItemDetails');
    $subroutes->add('get_purchase_item_details', 'BdtaskttPurchase::get_purchase_item_details');
    $subroutes->add('po_approve', 'BdtaskttPurchase::bdtasktt_approvePurchaseOrder');
    $subroutes->add('delete_po/(:num)', 'BdtaskttPurchase::bdtasktt_delete_po/$1');



});