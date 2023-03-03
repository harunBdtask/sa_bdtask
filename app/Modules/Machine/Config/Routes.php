<?php

if(!isset($routes))
{ 
    $routes = \Config\Services::routes(true);
}

$routes->group('machine', ['namespace' => 'App\Modules\Machine\Controllers'], function($subroutes){

    //item request routes  
    $subroutes->add('item_request', 'Bdtaskt1c1ItemConsumption::index');
    $subroutes->add('getItemRequest', 'Bdtaskt1c1ItemConsumption::bdtaskt1m12c9_01_getList');
    $subroutes->add('getItemDetailsById/(:num)', 'Bdtaskt1c1ItemConsumption::bdtaskt1m12c9_08_getItemDetailsById/$1');
    $subroutes->add('deleteItemRequest/(:num)', 'Bdtaskt1c1ItemConsumption::bdtaskt1m12c9_02_deleteItemRequest/$1');
    $subroutes->add('getItemRequestById/(:num)', 'Bdtaskt1c1ItemConsumption::bdtaskt1m12c9_04_getItemRequestById/$1');
    $subroutes->add('add_item_request', 'Bdtaskt1c1ItemConsumption::bdtaskt1m12c9_03_addItemRequest');
    $subroutes->add('approve_consumption', 'Bdtaskt1c1ItemConsumption::bdtaskt1m12c9_07_approveConsumption');
    $subroutes->add('getItemRequestDetailsById/(:num)', 'Bdtaskt1c1ItemConsumption::bdtaskt1m12c9_05_getItemRequestDetailsById/$1'); 
    $subroutes->add('getItemRequestQuantityDetailsById', 'Bdtaskt1c1ItemConsumption::bdtaskt1m12c9_06_getItemPricingDetailsById'); 
    $subroutes->add('getItemReturnQuantityDetailsById', 'Bdtaskt1c1ItemConsumption::bdtaskt1m12c9_07_getItemReturnQuantityDetailsById');
    
    //item request routes  
    /*$subroutes->add('item_request', 'Bdtaskt1c1ItemRequest::index');
    $subroutes->add('getItemRequest', 'Bdtaskt1c1ItemRequest::bdtaskt1m12c9_01_getList');
    $subroutes->add('getItemDetailsById/(:num)', 'Bdtaskt1c1ItemRequest::bdtaskt1m12c9_08_getItemDetailsById/$1');
    $subroutes->add('deleteItemRequest/(:num)', 'Bdtaskt1c1ItemRequest::bdtaskt1m12c9_02_deleteItemRequest/$1');
    $subroutes->add('getItemRequestById/(:num)', 'Bdtaskt1c1ItemRequest::bdtaskt1m12c9_04_getItemRequestById/$1');
    $subroutes->add('add_item_request', 'Bdtaskt1c1ItemRequest::bdtaskt1m12c9_03_addItemRequest');
    $subroutes->add('item_collect', 'Bdtaskt1c1ItemRequest::bdtaskt1m12c9_07_itemCollect');
    $subroutes->add('getItemRequestDetailsById/(:num)', 'Bdtaskt1c1ItemRequest::bdtaskt1m12c9_05_getItemRequestDetailsById/$1'); 
    $subroutes->add('getItemRequestQuantityDetailsById', 'Bdtaskt1c1ItemRequest::bdtaskt1m12c9_06_getItemPricingDetailsById'); 
    $subroutes->add('getItemReturnQuantityDetailsById', 'Bdtaskt1c1ItemRequest::bdtaskt1m12c9_07_getItemReturnQuantityDetailsById');*/

    //item request approve routes  
    $subroutes->add('item_approve', 'Bdtaskt1c2ItemApprove::index');
    $subroutes->add('getItemApprove', 'Bdtaskt1c2ItemApprove::bdtaskt1m12c13_01_getList');
    $subroutes->add('deleteItemApprove/(:num)', 'Bdtaskt1c2ItemApprove::bdtaskt1m12c13_02_deleteItemApprove/$1');
    $subroutes->add('getItemApproveById/(:num)', 'Bdtaskt1c2ItemApprove::bdtaskt1m12c13_04_getItemApproveById/$1');
    $subroutes->add('add_item_approve', 'Bdtaskt1c2ItemApprove::bdtaskt1m12c13_03_addItemApprove');
    $subroutes->add('getItemApproveDetailsById/(:num)', 'Bdtaskt1c2ItemApprove::bdtaskt1m12c13_05_getItemApproveDetailsById/$1'); 
    $subroutes->add('getItemApproveQuantityDetailsById', 'Bdtaskt1c2ItemApprove::bdtaskt1m12c13_06_getItemPricingDetailsById'); 
    $subroutes->add('getItemCollectQuantityDetailsById', 'Bdtaskt1c2ItemApprove::bdtaskt1m12c13_07_getItemCollectQuantityDetailsById'); 

    //department warehouse routes  
    $subroutes->add('sub_store', 'Bdtaskt1c3MachineStore::index');
    $subroutes->add('getSubStore', 'Bdtaskt1c3MachineStore::bdtaskt1m12c3_01_getList');
    $subroutes->add('deleteSubStore/(:num)', 'Bdtaskt1c3MachineStore::bdtaskt1m12c3_02_deleteSubStore/$1');
    $subroutes->add('getSubStoreById/(:num)', 'Bdtaskt1c3MachineStore::bdtaskt1m12c3_04_getSubStoreById/$1');
    $subroutes->add('add_sub_store', 'Bdtaskt1c3MachineStore::bdtaskt1m12c3_03_addSubStore');
    $subroutes->add('getSubStoreDetailsById/(:num)', 'Bdtaskt1c3MachineStore::bdtaskt1m12c3_05_getSubStoreDetailsById/$1');      

    //department stock routes  
    $subroutes->add('sub_stock', 'Bdtaskt1c4MachineStock::index');
    $subroutes->add('getSubStock', 'Bdtaskt1c4MachineStock::bdtaskt1m12c6_01_getList');
    $subroutes->add('deleteSubStock/(:num)', 'Bdtaskt1c4MachineStock::bdtaskt1m12c6_02_deleteSubStock/$1');
    $subroutes->add('getSubStockById/(:num)', 'Bdtaskt1c4MachineStock::bdtaskt1m12c6_04_getSubStockById/$1');
    $subroutes->add('add_sub_stock', 'Bdtaskt1c4MachineStock::bdtaskt1m12c6_03_addSubStock');
    $subroutes->add('getSubStockDetailsById/(:num)', 'Bdtaskt1c4MachineStock::bdtaskt1m12c6_05_getSubStockDetailsById/$1');
});