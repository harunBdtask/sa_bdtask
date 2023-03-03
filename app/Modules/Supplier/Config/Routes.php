<?php

if(!isset($routes))
{ 
    $routes = \Config\Services::routes(true);
}

$routes->group('supplier', ['namespace' => 'App\Modules\Supplier\Controllers'], function($subroutes){

    // routes of suppliers
    $subroutes->add('suppliers', 'Bdtaskt1m12c14Supplier::index');
    $subroutes->add('getAllSupplier', 'Bdtaskt1m12c14Supplier::bdtaskt1m12c14_01_getList');
    $subroutes->add('addSupplier', 'Bdtaskt1m12c14Supplier::bdtaskt1m12c14_02_addSupplier');
    $subroutes->add('getSupplierById/(:num)', 'Bdtaskt1m12c14Supplier::bdtaskt1m12c14_04_getSupplierById/$1');
    $subroutes->add('deleteSupplier/(:num)', 'Bdtaskt1m12c14Supplier::bdtaskt1m12c14_03_deleteSupplier/$1');
    $subroutes->add('supplierDetailsById/(:num)', 'Bdtaskt1m12c14Supplier::bdtaskt1m12c14_05_getDetailsById/$1');


    //supplier payment routes  
    $subroutes->add('supplier_payment', 'Bdtaskt1m12c16SupplierPayment::index');
    $subroutes->add('getSupplierPayment', 'Bdtaskt1m12c16SupplierPayment::bdtaskt1m12c16_01_getList');
    $subroutes->add('deleteSupplierPayment/(:num)', 'Bdtaskt1m12c16SupplierPayment::bdtaskt1m12c16_02_deleteSupplierPayment/$1');
    $subroutes->add('getSupplierPaymentById/(:num)', 'Bdtaskt1m12c16SupplierPayment::bdtaskt1m12c16_04_getSupplierPaymentById/$1');
    $subroutes->add('add_supplier_payment', 'Bdtaskt1m12c16SupplierPayment::bdtaskt1m12c16_03_addSupplierPayment');
    $subroutes->add('getSupplierPaymentDetailsById/(:num)', 'Bdtaskt1m12c16SupplierPayment::bdtaskt1m12c16_05_getSupplierPaymentDetailsById/$1');
    //$subroutes->add('getItemPricingDetailsById', 'Bdtaskt1m12c16SupplierPayment::bdtaskt1m12c16_06_getItemPricingDetailsById'); 
    //$subroutes->add('getReturnDetailsById/(:num)', 'Bdtaskt1m12c16SupplierPayment::bdtaskt1m12c16_07_getReturnDetailsById/$1');
    //$subroutes->add('getReturnItemDetailsById', 'Bdtaskt1m12c16SupplierPayment::bdtaskt1m12c16_08_getReturnItemDetailsById');
    $subroutes->add('getPaymentDetailsById', 'Bdtaskt1m12c16SupplierPayment::bdtaskt1m12c16_10_getPaymentDetailsById');
    $subroutes->add('getPaymentDueById', 'Bdtaskt1m12c16SupplierPayment::bdtaskt1m12c16_11_getPaymentDueById');

   
});