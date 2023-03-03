<?php

if(!isset($routes))
{ 
    $routes = \Config\Services::routes(true);
}

$routes->group('production', ['namespace' => 'App\Modules\Production\Controllers'], function($subroutes){
    
    //item request approve routes  
    $subroutes->add('item_approve', 'Bdtaskt1c1ItemConsumption::index');
    $subroutes->add('getItemApprove', 'Bdtaskt1c1ItemConsumption::bdtaskt1m12c13_01_getList');
    $subroutes->add('deleteItemApprove/(:num)', 'Bdtaskt1c1ItemConsumption::bdtaskt1m12c13_02_deleteItemApprove/$1');
    $subroutes->add('getItemApproveById/(:num)', 'Bdtaskt1c1ItemConsumption::bdtaskt1m12c13_04_getItemApproveById/$1');
    $subroutes->add('add_item_approve', 'Bdtaskt1c1ItemConsumption::bdtaskt1m12c13_03_addItemApprove');
    $subroutes->add('getItemApproveDetailsById/(:num)', 'Bdtaskt1c1ItemConsumption::bdtaskt1m12c13_05_getItemApproveDetailsById/$1'); 
    $subroutes->add('getItemApproveQuantityDetailsById', 'Bdtaskt1c1ItemConsumption::bdtaskt1m12c13_06_getItemPricingDetailsById');     
    $subroutes->add('getItemRequestDetailsById/(:num)', 'Bdtaskt1c1ItemConsumption::bdtaskt1m12c13_05_getItemApproveDetailsById/$1');     
    $subroutes->add('getItemRequestQuantityDetailsById', 'Bdtaskt1c1ItemConsumption::bdtaskt1m12c9_06_getItemPricingDetailsById'); 

    /*//item request routes  
    $subroutes->add('item_request', 'Bdtaskt1c1ItemRequest::index');
    $subroutes->add('getItemRequest', 'Bdtaskt1c1ItemRequest::bdtaskt1m12c9_01_getList');
    $subroutes->add('getItemDetailsById/(:num)', 'Bdtaskt1c1ItemRequest::bdtaskt1m12c9_08_getItemDetailsById/$1');
    $subroutes->add('deleteItemRequest/(:num)', 'Bdtaskt1c1ItemRequest::bdtaskt1m12c9_02_deleteItemRequest/$1');
    $subroutes->add('getItemRequestById/(:num)', 'Bdtaskt1c1ItemRequest::bdtaskt1m12c9_04_getItemRequestById/$1');
    $subroutes->add('add_item_request', 'Bdtaskt1c1ItemRequest::bdtaskt1m12c9_03_addItemRequest');
    $subroutes->add('item_collect', 'Bdtaskt1c1ItemRequest::bdtaskt1m12c9_07_itemCollect');
    $subroutes->add('getItemRequestDetailsById/(:num)', 'Bdtaskt1c1ItemRequest::bdtaskt1m12c9_05_getItemRequestDetailsById/$1'); 
    $subroutes->add('getItemRequestQuantityDetailsById', 'Bdtaskt1c1ItemRequest::bdtaskt1m12c9_06_getItemPricingDetailsById'); 
    $subroutes->add('getProductionRecipeDetailsById', 'Bdtaskt1c1ItemRequest::bdtaskt1m12c9_09_getItemProductionItemDetailsById');
    
    //item request approve routes  
    $subroutes->add('item_approve', 'Bdtaskt1c2ItemApprove::index');
    $subroutes->add('getItemApprove', 'Bdtaskt1c2ItemApprove::bdtaskt1m12c13_01_getList');
    $subroutes->add('deleteItemApprove/(:num)', 'Bdtaskt1c2ItemApprove::bdtaskt1m12c13_02_deleteItemApprove/$1');
    $subroutes->add('getItemApproveById/(:num)', 'Bdtaskt1c2ItemApprove::bdtaskt1m12c13_04_getItemApproveById/$1');
    $subroutes->add('add_item_approve', 'Bdtaskt1c2ItemApprove::bdtaskt1m12c13_03_addItemApprove');
    $subroutes->add('getItemApproveDetailsById/(:num)', 'Bdtaskt1c2ItemApprove::bdtaskt1m12c13_05_getItemApproveDetailsById/$1'); 
    $subroutes->add('getItemApproveQuantityDetailsById', 'Bdtaskt1c2ItemApprove::bdtaskt1m12c13_06_getItemPricingDetailsById'); */
    
    //bag approve routes  
    $subroutes->group('bags', ['namespace' => 'App\Modules\Production\Controllers'], function($subroutes){    
        $subroutes->add('item_approve', 'Bdtaskt1c2BagConsumption::index');
        $subroutes->add('getItemApprove', 'Bdtaskt1c2BagConsumption::bdtaskt1m12c13_01_getList');
        $subroutes->add('deleteItemApprove/(:num)', 'Bdtaskt1c2BagConsumption::bdtaskt1m12c13_02_deleteItemApprove/$1');
        $subroutes->add('getItemApproveById/(:num)', 'Bdtaskt1c2BagConsumption::bdtaskt1m12c13_04_getItemApproveById/$1');
        $subroutes->add('add_item_approve', 'Bdtaskt1c2BagConsumption::bdtaskt1m12c13_03_addItemApprove');
        $subroutes->add('getItemApproveDetailsById/(:num)', 'Bdtaskt1c2BagConsumption::bdtaskt1m12c13_05_getItemApproveDetailsById/$1'); 
        $subroutes->add('getItemApproveQuantityDetailsById', 'Bdtaskt1c2BagConsumption::bdtaskt1m12c13_06_getItemPricingDetailsById'); 
        $subroutes->add('getItemRequestDetailsById/(:num)', 'Bdtaskt1c2BagConsumption::bdtaskt1m12c13_05_getItemApproveDetailsById/$1');  
        $subroutes->add('getItemRequestQuantityDetailsById', 'Bdtaskt1c2BagConsumption::bdtaskt1m12c9_06_getItemPricingDetailsById'); 

    });

    //department warehouse routes  
    $subroutes->add('plant', 'Bdtaskt1c3MachineStore::index');
    $subroutes->add('getSubStore', 'Bdtaskt1c3MachineStore::bdtaskt1m12c3_01_getList');
    $subroutes->add('deleteSubStore/(:num)', 'Bdtaskt1c3MachineStore::bdtaskt1m12c3_02_deleteSubStore/$1');
    $subroutes->add('getSubStoreById/(:num)', 'Bdtaskt1c3MachineStore::bdtaskt1m12c3_04_getSubStoreById/$1');
    $subroutes->add('add_sub_store', 'Bdtaskt1c3MachineStore::bdtaskt1m12c3_03_addSubStore');
    $subroutes->add('getSubStoreDetailsById/(:num)', 'Bdtaskt1c3MachineStore::bdtaskt1m12c3_05_getSubStoreDetailsById/$1');

    //item production routes  
    $subroutes->add('item_production', 'Bdtaskt1c1ItemProduction::index');
    $subroutes->add('getItemProduction', 'Bdtaskt1c1ItemProduction::bdtaskt1m12c10_01_getList');
    $subroutes->add('item_production_approve', 'Bdtaskt1c1ItemProduction::bdtaskt1m12c10_07_approveItemProduction');
    $subroutes->add('deleteItemProduction/(:num)', 'Bdtaskt1c1ItemProduction::bdtaskt1m12c10_02_deleteItemProduction/$1');
    $subroutes->add('getItemProductionById/(:num)', 'Bdtaskt1c1ItemProduction::bdtaskt1m12c10_04_getItemProductionById/$1');
    $subroutes->add('add_item_production', 'Bdtaskt1c1ItemProduction::bdtaskt1m12c10_03_addItemProduction');
    $subroutes->add('get_item_list', 'Bdtaskt1c1ItemProduction::bdtaskt1m12c10_08_get_item_list');
    $subroutes->add('getSupplierItemDetailsById', 'Bdtaskt1c1ItemProduction::bdtaskt1m12c10_09_getSupplierItemDetailsById');
    $subroutes->add('getItemProductionDetailsById/(:num)', 'Bdtaskt1c1ItemProduction::bdtaskt1m12c10_05_getItemProductionDetailsById/$1');
    $subroutes->add('getItemProductionPricingDetailsById', 'Bdtaskt1c1ItemProduction::bdtaskt1m12c10_06_getItemProductionPricingDetailsById'); 
    $subroutes->add('getItemProductionItemDetailsById', 'Bdtaskt1c1ItemProduction::bdtaskt1m12c10_07_getItemProductionItemDetailsById'); 

    //item receive routes  
    $subroutes->add('item_receive', 'Bdtaskt1c2ItemReceive::index');
    $subroutes->add('getItemReceive', 'Bdtaskt1c2ItemReceive::bdtaskt1m12c12_01_getList');
    $subroutes->add('deleteItemReceive/(:num)', 'Bdtaskt1c2ItemReceive::bdtaskt1m12c12_02_deleteItemReceive/$1');
    $subroutes->add('getItemReceiveById/(:num)', 'Bdtaskt1c2ItemReceive::bdtaskt1m12c12_04_getItemReceiveById/$1');
    $subroutes->add('add_item_receive', 'Bdtaskt1c2ItemReceive::bdtaskt1m12c12_03_addItemReceive');
    $subroutes->add('getItemReceiveDetailsById/(:num)', 'Bdtaskt1c2ItemReceive::bdtaskt1m12c12_05_getItemReceiveDetailsById/$1');
    $subroutes->add('getItemPurchaseDetailsById', 'Bdtaskt1c2ItemReceive::bdtaskt1m12c12_07_getItemPurchaseDetailsById/$1'); 
    //$subroutes->add('add_item_purchase', 'Bdtaskt1c2ItemReceive::bdtaskt1m12c12_03_addItemPurchase');
    $subroutes->add('getItemPurchasePricingDetailsById', 'Bdtaskt1c2ItemReceive::bdtaskt1m12c12_08_getItemPurchasePricingDetailsById/$1');

    //item request approve routes  
    /*$subroutes->add('material_approve', 'Bdtaskt1c2MaterialApprove::index');
    $subroutes->add('getItemApprove', 'Bdtaskt1c2MaterialApprove::bdtaskt1m12c13_01_getList');
    $subroutes->add('deleteItemApprove/(:num)', 'Bdtaskt1c2MaterialApprove::bdtaskt1m12c13_02_deleteItemApprove/$1');
    $subroutes->add('getItemApproveById/(:num)', 'Bdtaskt1c2MaterialApprove::bdtaskt1m12c13_04_getItemApproveById/$1');
    $subroutes->add('add_item_approve', 'Bdtaskt1c2MaterialApprove::bdtaskt1m12c13_03_addItemApprove');
    $subroutes->add('getItemApproveDetailsById/(:num)', 'Bdtaskt1c2MaterialApprove::bdtaskt1m12c13_05_getItemApproveDetailsById/$1'); 
    $subroutes->add('getItemApproveQuantityDetailsById', 'Bdtaskt1c2MaterialApprove::bdtaskt1m12c13_06_getItemPricingDetailsById'); 
    $subroutes->add('getItemCollectQuantityDetailsById', 'Bdtaskt1c2MaterialApprove::bdtaskt1m12c13_07_getItemCollectQuantityDetailsById'); 
    $subroutes->add('getItemRequestDetailsById/(:num)', 'Bdtaskt1c2MaterialApprove::bdtaskt1m12c9_05_getItemRequestDetailsById/$1'); 
    $subroutes->add('getItemRequestQuantityDetailsById', 'Bdtaskt1c2MaterialApprove::bdtaskt1m12c9_06_getItemPricingDetailsById'); */

    //item purchase routes  
    $subroutes->add('production_voucher', 'Bdtaskt1c3ProductionVoucher::index');
    $subroutes->add('getProductionVoucher', 'Bdtaskt1c3ProductionVoucher::bdtaskt1m12c7_01_getList');
    $subroutes->add('deleteProductionVoucher/(:num)', 'Bdtaskt1c3ProductionVoucher::bdtaskt1m12c7_02_deleteProductionVoucher/$1');
    $subroutes->add('getProductionVoucherById/(:num)', 'Bdtaskt1c3ProductionVoucher::bdtaskt1m12c7_04_getProductionVoucherById/$1');
    //$subroutes->add('add_item_purchase', 'Bdtaskt1c3ProductionVoucher::bdtaskt1m12c7_03_addProductionVoucher');
    $subroutes->add('getProductionVoucherDetailsById/(:num)', 'Bdtaskt1c3ProductionVoucher::bdtaskt1m12c7_05_getProductionVoucherDetailsById/$1');
    $subroutes->add('getItemPricingDetailsById', 'Bdtaskt1c3ProductionVoucher::bdtaskt1m12c7_06_getItemPricingDetailsById'); 
    $subroutes->add('getReturnDetailsById/(:num)', 'Bdtaskt1c3ProductionVoucher::bdtaskt1m12c7_07_getReturnDetailsById/$1');

    $subroutes->group('recipe', ['namespace' => 'App\Modules\Production\Controllers'], function($subroutes){

        //item production recipe routes  
        $subroutes->add('/', 'Bdtaskt1c4ProductionRecipe::index');
        $subroutes->add('getProductionRecipe', 'Bdtaskt1c4ProductionRecipe::bdtaskt1m12c10_01_getList');
        $subroutes->add('item_production_approve', 'Bdtaskt1c4ProductionRecipe::bdtaskt1m12c10_07_approveProductionRecipe');
        $subroutes->add('deleteProductionRecipe/(:num)', 'Bdtaskt1c4ProductionRecipe::bdtaskt1m12c10_02_deleteProductionRecipe/$1');
        $subroutes->add('getProductionRecipeById/(:num)', 'Bdtaskt1c4ProductionRecipe::bdtaskt1m12c10_04_getProductionRecipeById/$1');
        $subroutes->add('add_item_production', 'Bdtaskt1c4ProductionRecipe::bdtaskt1m12c10_03_addProductionRecipe');
        $subroutes->add('get_item_list', 'Bdtaskt1c4ProductionRecipe::bdtaskt1m12c10_08_get_item_list');
        $subroutes->add('getSupplierItemDetailsById', 'Bdtaskt1c4ProductionRecipe::bdtaskt1m12c10_09_getSupplierItemDetailsById');
        $subroutes->add('getProductionRecipeDetailsById/(:num)', 'Bdtaskt1c4ProductionRecipe::bdtaskt1m12c10_05_getProductionRecipeDetailsById/$1');
        $subroutes->add('getProductionRecipePricingDetailsById', 'Bdtaskt1c4ProductionRecipe::bdtaskt1m12c10_06_getProductionRecipePricingDetailsById'); 
        $subroutes->add('getProductionRecipeItemDetailsById', 'Bdtaskt1c4ProductionRecipe::bdtaskt1m12c10_07_getProductionRecipeItemDetailsById'); 
    });
});