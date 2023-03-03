<?php

if(!isset($routes))
{ 
    $routes = \Config\Services::routes(true);
}

$routes->group('store', ['namespace' => 'App\Modules\Store\Controllers'], function($subroutes){

    //branch warehouse routes  
    $subroutes->add('material_store', 'Bdtaskt1c1MaterialStore::index');
    $subroutes->add('getMainStore', 'Bdtaskt1c1MaterialStore::bdtaskt1m12c4_01_getList');
    $subroutes->add('deleteMainStore/(:num)', 'Bdtaskt1c1MaterialStore::bdtaskt1m12c4_02_deleteMainStore/$1');
    $subroutes->add('getMainStoreById/(:num)', 'Bdtaskt1c1MaterialStore::bdtaskt1m12c4_04_getMainStoreById/$1');
    $subroutes->add('add_main_store', 'Bdtaskt1c1MaterialStore::bdtaskt1m12c4_03_addMainStore');
    $subroutes->add('getMainStoreDetailsById/(:num)', 'Bdtaskt1c1MaterialStore::bdtaskt1m12c4_05_getMainStoreDetailsById/$1');  

    //branch stock routes  
    $subroutes->add('material_stock', 'Bdtaskt1c2MaterialStock::index');
    $subroutes->add('getMainStock', 'Bdtaskt1c2MaterialStock::bdtaskt1m12c5_01_getList');
    $subroutes->add('deleteMainStock/(:num)', 'Bdtaskt1c2MaterialStock::bdtaskt1m12c5_02_deleteMainStock/$1');
    $subroutes->add('getMainStockById/(:num)', 'Bdtaskt1c2MaterialStock::bdtaskt1m12c5_04_getMainStockById/$1');
    $subroutes->add('add_main_stock', 'Bdtaskt1c2MaterialStock::bdtaskt1m12c5_03_addMainStock');
    $subroutes->add('getMainStockDetailsById/(:num)', 'Bdtaskt1c2MaterialStock::bdtaskt1m12c5_05_getMainStockDetailsById/$1');      


});