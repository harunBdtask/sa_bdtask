<?php

if(!isset($routes))
{ 
    $routes = \Config\Services::routes(true);
}

$routes->group('bag_purchase', ['namespace' => 'App\Modules\Bag_purchase\Controllers'], function($subroutes){

    //item spr routes  
    $subroutes->add('requisition', 'Bdtaskt1m12c10Requisition::index');
    $subroutes->add('getRequisition', 'Bdtaskt1m12c10Requisition::bdtaskt1m12c10_01_getList');
    $subroutes->add('requisition_approve', 'Bdtaskt1m12c10Requisition::bdtaskt1m12c10_07_requisition_approve');
    $subroutes->add('deleteRequisition/(:num)', 'Bdtaskt1m12c10Requisition::bdtaskt1m12c10_02_deletePurchaseOrder/$1');
    $subroutes->add('getRequisitionById/(:num)', 'Bdtaskt1m12c10Requisition::bdtaskt1m12c10_04_getPurchaseOrderById/$1');
    $subroutes->add('add_requisition', 'Bdtaskt1m12c10Requisition::bdtaskt1m12c10_03_addPurchaseOrder');
    $subroutes->add('getRequisitionDetailsById/(:num)', 'Bdtaskt1m12c10Requisition::bdtaskt1m12c10_05_getPurchaseOrderDetailsById/$1');
    $subroutes->add('getRequisitionPricingDetailsById', 'Bdtaskt1m12c10Requisition::bdtaskt1m12c10_06_getRequisitionPricingDetailsById'); 
    $subroutes->add('getRequisitionItemDetailsById', 'Bdtaskt1m12c10Requisition::bdtaskt1m12c10_07_getPurchaseOrderItemDetailsById'); 
    $subroutes->add('get_item_list_bag', 'Bdtaskt1m12c10Requisition::bdtaskt1m12c10_08_get_item_list'); 
    $subroutes->add('getSupplierItemDetailsByIdBag', 'Bdtaskt1m12c10Requisition::bdtaskt1m12c10_09_getSupplierItemDetailsById'); 
    $subroutes->add('quotation_cs', 'Bdtaskt1m12c10Requisition::quotation_cs_index');
    $subroutes->add('get_quatation_cs', 'Bdtaskt1m12c10Requisition::bdtaskt1m12c10_12_get_quatation_cs');
    
    // //quatation
    // $subroutes->add('quatation', 'Bdtaskt1m12c1Quatations::index');
    // $subroutes->add('getQuatation', 'Bdtaskt1m12c1Quatations::bdtaskt1m12c1_01_getList');
    // $subroutes->add('deleteQuatation/(:num)', 'Bdtaskt1m12c1Quatations::bdtaskt1m12c1_02_deleteCategories/$1');
    // $subroutes->add('getQuatationById/(:num)', 'Bdtaskt1m12c1Quatations::bdtaskt1m12c1_04_getCategoriesById/$1');
    // $subroutes->add('add_quatation', 'Bdtaskt1m12c1Quatations::bdtaskt1m12c1_03_addQuatation');
    // $subroutes->add('getQuatationDetailsById/(:num)', 'Bdtaskt1m12c1Quatations::bdtaskt1m12c1_05_getCategoryDetailsById/$1'); 
    // $subroutes->add('getSprDetailsById/(:num)', 'Bdtaskt1m12c1Quatations::bdtaskt1m12c10_05_getPurchaseOrderDetailsById/$1');
    // $subroutes->add('quatation_approve', 'Bdtaskt1m12c1Quatations::bdtaskt1m12c10_07_approvePurchaseOrder');
    // $subroutes->add('getQuatationPricingDetailsById', 'Bdtaskt1m12c1Quatations::bdtaskt1m12c10_06_getPurchaseOrderPricingDetailsById');


     //quatation
     $subroutes->add('quatation', 'Bdtaskt1m12c1Quatations::index');
     $subroutes->add('getQuatation', 'Bdtaskt1m12c1Quatations::bdtaskt1m12c1_01_getList');
     $subroutes->add('get_spr_list', 'Bdtaskt1m12c1Quatations::bdtaskt1m12c10_05_get_spr_list');
     $subroutes->add('deleteQuatation/(:num)', 'Bdtaskt1m12c1Quatations::bdtaskt1m12c1_02_deleteCategories/$1');
     $subroutes->add('getQuatationById/(:num)', 'Bdtaskt1m12c1Quatations::bdtaskt1m12c1_04_getCategoriesById/$1');
     $subroutes->add('add_quatation', 'Bdtaskt1m12c1Quatations::bdtaskt1m12c1_03_addQuatation');
     $subroutes->add('getQuatationDetailsById/(:num)', 'Bdtaskt1m12c1Quatations::bdtaskt1m12c1_05_getCategoryDetailsById/$1'); 
     $subroutes->add('getSprDetailsById/(:num)', 'Bdtaskt1m12c1Quatations::bdtaskt1m12c10_05_getPurchaseOrderDetailsById/$1');
     $subroutes->add('quatation_approve', 'Bdtaskt1m12c1Quatations::bdtaskt1m12c10_07_approvePurchaseOrder');
     $subroutes->add('getQuatationPricingDetailsById', 'Bdtaskt1m12c1Quatations::bdtaskt1m12c10_06_getPurchaseOrderPricingDetailsById');
     $subroutes->add('getSprItemDetailsById', 'Bdtaskt1m12c1Quatations::bdtaskt1m12c10_09_getSprItemDetailsById');
     $subroutes->add('get_quatation_details_by_id', 'Bdtaskt1m12c1Quatations::bdtaskt1m12c10_05_get_quatation_details_by_id');
     $subroutes->add('getSpr', 'Bdtaskt1m12c1Quatations::bdtaskt1m12c1_12_getSpr');
     $subroutes->add('testPdf', 'Bdtaskt1m12c1Quatations::dompdf');
     $subroutes->add('quatation_dompdf', 'Bdtaskt1m12c1Quatations::bdtaskt1m12c1_13_quatation_dompdf');
 

    //PO routes  
    $subroutes->add('purchase_order', 'Bdtaskt1m12c10PurchaseOrder::index');
    $subroutes->add('purchase_order_test', 'Bdtaskt1m12c10PurchaseOrder::test');
    $subroutes->add('getPurchaseOrder', 'Bdtaskt1m12c10PurchaseOrder::bdtaskt1m12c10_01_getList');
    $subroutes->add('purchase_order_approve', 'Bdtaskt1m12c10PurchaseOrder::bdtaskt1m12c10_07_approvePurchaseOrder');
    $subroutes->add('deletePurchaseOrder/(:num)', 'Bdtaskt1m12c10PurchaseOrder::bdtaskt1m12c10_02_deletePurchaseOrder/$1');
    $subroutes->add('getPurchaseOrderById/(:num)', 'Bdtaskt1m12c10PurchaseOrder::bdtaskt1m12c10_04_getPurchaseOrderById/$1');
    $subroutes->add('addPurchaseOrder', 'Bdtaskt1m12c10PurchaseOrder::bdtaskt1m12c10_03_addPurchaseOrder');
    $subroutes->add('get_item_list', 'Bdtaskt1m12c10PurchaseOrder::bdtaskt1m12c10_08_get_item_list');
    $subroutes->add('getPurchaseOrderDetailsById/(:num)', 'Bdtaskt1m12c10PurchaseOrder::bdtaskt1m12c10_05_getPurchaseOrderDetailsById/$1');
    $subroutes->add('getPurchaseOrderPricingDetailsById', 'Bdtaskt1m12c10PurchaseOrder::bdtaskt1m12c10_06_getPurchaseOrderPricingDetailsById'); 
    $subroutes->add('getPurchaseOrderItemDetailsById', 'Bdtaskt1m12c10PurchaseOrder::bdtaskt1m12c10_07_getPurchaseOrderItemDetailsById'); 
    $subroutes->add('getSupplierItemDetailsById', 'Bdtaskt1m12c10PurchaseOrder::bdtaskt1m12c10_09_getSupplierItemDetailsById');
    $subroutes->add('getPrintView', 'Bdtaskt1m12c10PurchaseOrder::bdtaskt1m12c10_10_getPrintView');
    $subroutes->add('get_quatation_list', 'Bdtaskt1m12c10PurchaseOrder::bdtaskt1m12c10_11_get_quatation_list'); 
    $subroutes->add('get_spr_item_list', 'Bdtaskt1m12c10PurchaseOrder::bdtaskt1m12c10_12_get_spr_item_list'); 
    
    //item receive routes  
    $subroutes->add('item_receive', 'Bdtaskt1m12c12ItemReceive::index');
    $subroutes->add('getItemReceive', 'Bdtaskt1m12c12ItemReceive::bdtaskt1m12c12_01_getList');
    $subroutes->add('deleteItemReceive/(:num)', 'Bdtaskt1m12c12ItemReceive::bdtaskt1m12c12_02_deleteItemReceive/$1');
    $subroutes->add('getItemReceiveById/(:num)', 'Bdtaskt1m12c12ItemReceive::bdtaskt1m12c12_04_getItemReceiveById/$1');
    $subroutes->add('add_item_receive', 'Bdtaskt1m12c12ItemReceive::bdtaskt1m12c12_03_addItemReceive');
    $subroutes->add('getItemReceiveDetailsById/(:num)', 'Bdtaskt1m12c12ItemReceive::bdtaskt1m12c12_05_getItemReceiveDetailsById/$1');
    $subroutes->add('getItemPurchaseDetailsById', 'Bdtaskt1m12c12ItemReceive::bdtaskt1m12c12_07_getItemPurchaseDetailsById'); 
    $subroutes->add('getItemPurchasePricingDetailsById', 'Bdtaskt1m12c12ItemReceive::bdtaskt1m12c12_08_getItemPurchasePricingDetailsById');
    $subroutes->add('get_po_list', 'Bdtaskt1m12c12ItemReceive::bdtaskt1m12c12_12_get_po_list');


    //Received Voucher routes  
    $subroutes->add('item_purchase', 'Bdtaskt1m12c7ItemPurchase::index');
    $subroutes->add('getItemPurchase', 'Bdtaskt1m12c7ItemPurchase::bdtaskt1m12c7_01_getList');
    $subroutes->add('deleteItemPurchase/(:num)', 'Bdtaskt1m12c7ItemPurchase::bdtaskt1m12c7_02_deleteItemPurchase/$1');
    $subroutes->add('getItemPurchaseById/(:num)', 'Bdtaskt1m12c7ItemPurchase::bdtaskt1m12c7_04_getItemPurchaseById/$1');
    $subroutes->add('getMaterialReceiveDetailsById/(:num)', 'Bdtaskt1m12c7ItemPurchase::bdtaskt1m12c7_05_getItemReceiveDetailsById/$1');
    $subroutes->add('getItemPurchaseDetailsById/(:num)', 'Bdtaskt1m12c7ItemPurchase::bdtaskt1m12c7_05_getItemPurchaseDetailsById/$1');
    $subroutes->add('getItemPricingDetailsById', 'Bdtaskt1m12c7ItemPurchase::bdtaskt1m12c7_06_getItemPricingDetailsById'); 
    $subroutes->add('getReturnDetailsById/(:num)', 'Bdtaskt1m12c7ItemPurchase::bdtaskt1m12c7_07_getReturnDetailsById/$1');
    $subroutes->add('getReturnItemDetailsById', 'Bdtaskt1m12c7ItemPurchase::bdtaskt1m12c7_08_getReturnItemDetailsById');
    
    //truncate route
    $subroutes->add('truncate_item_stock', 'Bdtaskt1m12c12ItemReceive::bdtaskt1m12c12_04_truncate_item_stock');

});