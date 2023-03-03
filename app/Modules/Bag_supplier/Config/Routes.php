<?php

if(!isset($routes))
{ 
    $routes = \Config\Services::routes(true);
}

$routes->group('bag', ['namespace' => 'App\Modules\Bag_supplier\Controllers'], function($subroutes){

    // routes of suppliers
    $subroutes->add('suppliers', 'Bdtaskt1m12c14BagSupplier::index');
    $subroutes->add('getAllSupplier', 'Bdtaskt1m12c14BagSupplier::bdtaskt1m12c14_01_getList');
    $subroutes->add('addSupplier', 'Bdtaskt1m12c14BagSupplier::bdtaskt1m12c14_02_addSupplier');
    $subroutes->add('getSupplierById/(:num)', 'Bdtaskt1m12c14BagSupplier::bdtaskt1m12c14_04_getSupplierById/$1');
    $subroutes->add('deleteSupplier/(:num)/(:num)', 'Bdtaskt1m12c14BagSupplier::bdtaskt1m12c14_03_deleteSupplier/$1/$2');
    $subroutes->add('supplierDetailsById/(:num)', 'Bdtaskt1m12c14BagSupplier::bdtaskt1m12c14_05_getDetailsById/$1');


   
});