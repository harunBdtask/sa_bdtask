<?php

if(!isset($routes))
{ 
    $routes = \Config\Services::routes(true);
}

$routes->group('finished_goods', ['namespace' => 'App\Modules\Finished_goods\Controllers'], function($subroutes){

    // item category routes
    $subroutes->add('categories', 'Bdtaskt1m12c1Categories::index');
    $subroutes->add('getCategories', 'Bdtaskt1m12c1Categories::bdtaskt1m12c1_01_getList');
    $subroutes->add('deleteCategories/(:num)', 'Bdtaskt1m12c1Categories::bdtaskt1m12c1_02_deleteCategories/$1');
    $subroutes->add('getCategoriesById/(:num)', 'Bdtaskt1m12c1Categories::bdtaskt1m12c1_04_getCategoriesById/$1');
    $subroutes->add('add_categories', 'Bdtaskt1m12c1Categories::bdtaskt1m12c1_03_addCategories');
    $subroutes->add('getCategoryDetailsById/(:num)', 'Bdtaskt1m12c1Categories::bdtaskt1m12c1_05_getCategoryDetailsById/$1'); 
    //item list routes  
    $subroutes->add('items', 'Bdtaskt1m12c2Items::index');
    $subroutes->add('getItems', 'Bdtaskt1m12c2Items::bdtaskt1m12c2_01_getList');
    $subroutes->add('deleteItems/(:num)', 'Bdtaskt1m12c2Items::bdtaskt1m12c2_02_deleteItems/$1');
    $subroutes->add('getItemsById/(:num)', 'Bdtaskt1m12c2Items::bdtaskt1m12c2_04_getItemsById/$1');
    $subroutes->add('add_items', 'Bdtaskt1m12c2Items::bdtaskt1m12c2_03_addItems');
    $subroutes->add('getItemDetailsById/(:num)', 'Bdtaskt1m12c2Items::bdtaskt1m12c2_05_getItemDetailsById/$1'); 

    //branch warehouse routes  
    $subroutes->add('main_store', 'Bdtaskt1m12c4MainStore::index');
    $subroutes->add('getMainStore', 'Bdtaskt1m12c4MainStore::bdtaskt1m12c4_01_getList');
    $subroutes->add('deleteMainStore/(:num)', 'Bdtaskt1m12c4MainStore::bdtaskt1m12c4_02_deleteMainStore/$1');
    $subroutes->add('getMainStoreById/(:num)', 'Bdtaskt1m12c4MainStore::bdtaskt1m12c4_04_getMainStoreById/$1');
    $subroutes->add('add_main_store', 'Bdtaskt1m12c4MainStore::bdtaskt1m12c4_03_addMainStore');
    $subroutes->add('getMainStoreDetailsById/(:num)', 'Bdtaskt1m12c4MainStore::bdtaskt1m12c4_05_getMainStoreDetailsById/$1');  

    //branch stock routes  
    $subroutes->add('main_stock', 'Bdtaskt1m12c5MainStock::index');
    $subroutes->add('getMainStock', 'Bdtaskt1m12c5MainStock::bdtaskt1m12c5_01_getList');
    $subroutes->add('deleteMainStock/(:num)', 'Bdtaskt1m12c5MainStock::bdtaskt1m12c5_02_deleteMainStock/$1');
    $subroutes->add('getMainStockById/(:num)', 'Bdtaskt1m12c5MainStock::bdtaskt1m12c5_04_getMainStockById/$1');
    $subroutes->add('add_main_stock', 'Bdtaskt1m12c5MainStock::bdtaskt1m12c5_03_addMainStock');
    $subroutes->add('getMainStockDetailsById/(:num)', 'Bdtaskt1m12c5MainStock::bdtaskt1m12c5_05_getMainStockDetailsById/$1');


    $subroutes->add('goods_pricing', 'Bdtaskt1m12c1GoodsPricing::index');
    $subroutes->add('getpricings', 'Bdtaskt1m12c1GoodsPricing::bdtaskt1m12c1_01_getList');
    $subroutes->add('deletepricings/(:num)', 'Bdtaskt1m12c1GoodsPricing::bdtaskt1m12c1_02_deletePricing/$1');
    $subroutes->add('getpricingsById/(:num)', 'Bdtaskt1m12c1GoodsPricing::bdtaskt1m12c1_04_getPricingById/$1');
    $subroutes->add('add_pricing', 'Bdtaskt1m12c1GoodsPricing::bdtaskt1m12c1_03_addPricing');
    $subroutes->add('getpricingDetailsById/(:num)', 'Bdtaskt1m12c1GoodsPricing::bdtaskt1m12c1_05_getPricingDetailsById/$1');    
    $subroutes->add('goods_previousprice/(:num)', 'Bdtaskt1m12c1GoodsPricing::bdtaskt1m12c1_03_goodspreviousPrice/$1');


    $subroutes->group('store_transfer', function($subroutes)
    {
    $subroutes->add('add_store_transfer', 'Bdtaskt1m12c5Stocktransfer::bdtaskt1m12c5_03_addTransfer');
    $subroutes->add('getItemDropdown', 'Bdtaskt1m12c5Stocktransfer::bdtaskt1c1_06_getItemDropdown');
    $subroutes->add('getStoreList', 'Bdtaskt1m12c5Stocktransfer::bdtaskt1c1_07_getStoreDropdown');
    $subroutes->add('getItemBatchstock', 'Bdtaskt1m12c5Stocktransfer::bdtaskt1c1_08_getItemBatchstock');
    $subroutes->add('getBatchstock', 'Bdtaskt1m12c5Stocktransfer::bdtaskt1c1_09_getBatchstock');
    $subroutes->add('save_store_transfer', 'Bdtaskt1m12c5Stocktransfer::bdtaskt1c1_10_saveStockTransfer');
    $subroutes->add('store_transfer_list', 'Bdtaskt1m12c5Stocktransfer::index');
    $subroutes->add('checkTransferList', 'Bdtaskt1m12c5Stocktransfer::bdtaskt1m12c5_01_getList');
    $subroutes->add('transfer_detials/(:num)', 'Bdtaskt1m12c5Stocktransfer::bdtaskt1m12c5_12_transferDetails/$1');
    
    });
});