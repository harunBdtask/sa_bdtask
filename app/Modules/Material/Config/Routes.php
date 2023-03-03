<?php

if(!isset($routes))
{ 
    $routes = \Config\Services::routes(true);
}

$routes->group('material', ['namespace' => 'App\Modules\Material\Controllers'], function($subroutes){

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

});