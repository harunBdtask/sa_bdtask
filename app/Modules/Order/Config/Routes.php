<?php

if(!isset($routes))
{ 
    $routes = \Config\Services::routes(true);
}

$routes->group('order', ['namespace' => 'App\Modules\Order\Controllers'], function($subroutes){

    //employee request approve routes  
    $subroutes->add('request_approve', 'Bdtaskt1c2ApproveOrder::index');
    $subroutes->add('getRequestApprove', 'Bdtaskt1c2ApproveOrder::bdtaskt1m12c11_01_getList');
    $subroutes->add('deleteRequestApprove/(:num)', 'Bdtaskt1c2ApproveOrder::bdtaskt1m12c11_02_deleteRequestApprove/$1');
    $subroutes->add('getRequestApproveById/(:num)', 'Bdtaskt1c2ApproveOrder::bdtaskt1m12c11_04_getRequestApproveById/$1');
    $subroutes->add('add_request_approve', 'Bdtaskt1c2ApproveOrder::bdtaskt1m12c11_03_addRequestApprove');
    $subroutes->add('getRequestApproveDetailsById/(:num)', 'Bdtaskt1c2ApproveOrder::bdtaskt1m12c11_05_getRequestApproveDetailsById/$1'); 
    $subroutes->add('getRequestApproveQuantityDetailsById', 'Bdtaskt1c2ApproveOrder::bdtaskt1m12c11_06_getItemPricingDetailsById/$1'); 

     
    $subroutes->add('item_request', 'Bdtaskt1c1ItemOrder::index');
    $subroutes->add('getItemRequest', 'Bdtaskt1c1ItemOrder::bdtaskt1m4c9_01_getList');
    $subroutes->add('deleteItemRequest/(:num)', 'Bdtaskt1c1ItemOrder::bdtaskt1m4c9_02_deleteItemRequest/$1');
    $subroutes->add('getItemRequestById/(:num)', 'Bdtaskt1c1ItemOrder::bdtaskt1m4c9_04_getItemRequestById/$1');
    $subroutes->add('add_item_request', 'Bdtaskt1c1ItemOrder::bdtaskt1m4c9_03_addItemRequest');
    $subroutes->add('item_return', 'Bdtaskt1c1ItemOrder::bdtaskt1m4c9_09_addItemReturn');
    $subroutes->add('getItemRequestDetailsById/(:num)', 'Bdtaskt1c1ItemOrder::bdtaskt1m4c9_05_getItemRequestDetailsById/$1'); 
    $subroutes->add('getItemRequestQuantityDetailsById', 'Bdtaskt1c1ItemOrder::bdtaskt1m4c9_06_getItemPricingDetailsById/$1');
    $subroutes->add('getItemDetailsById/(:num)', 'Bdtaskt1c1ItemOrder::bdtaskt1m4c9_07_getItemDetailsById/$1'); 
    $subroutes->add('getItemReturnDetailsById', 'Bdtaskt1c1ItemOrder::bdtaskt1m4c9_08_getItemReturnDetailsById');
    $subroutes->add('getInvoicesByPatientId/(:num)', 'Bdtaskt1c1ItemOrder::bdtaskt1m4c9_09_getInvoicesByPatientId/$1');
    $subroutes->add('getInvoicesByDoctorId/(:num)/(:num)', 'Bdtaskt1c1ItemOrder::bdtaskt1m4c9_13_getInvoicesByDoctorId/$1/$2');
    $subroutes->add('getServicesByInvoiceId/(:num)', 'Bdtaskt1c1ItemOrder::bdtaskt1m4c9_10_getServicesByInvoiceId/$1');

    $subroutes->add('getDefaultItemByServiceId', 'Bdtaskt1c1ItemOrder::bdtaskt1m12c17_12_getDefaultItemByServiceId');
    $subroutes->add('getItemList/(:any)', 'Bdtaskt1c1ItemOrder::bdtaskt1m12c17_13_getItemList/$1');
    

});