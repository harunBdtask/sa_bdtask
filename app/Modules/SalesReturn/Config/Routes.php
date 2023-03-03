<?php

if(!isset($routes))
{ 
    $routes = \Config\Services::routes(true);
}

$routes->group('return', ['namespace' => 'App\Modules\SalesReturn\Controllers'], function($subroutes){
    $subroutes->group('sales_return', function($subroutes)
    {
    $subroutes->add('find_return', 'Bdtaskt1m12c2Return::index');
    $subroutes->add('return_form', 'Bdtaskt1m12c2Return::return_form');
    $subroutes->add('return_form/(:any)', 'Bdtaskt1m12c2Return::return_form/$1');
    $subroutes->add('save_sales_return', 'Bdtaskt1m12c2Return::bdtask002_save_sls_return');
    $subroutes->add('sales_return_list', 'Bdtaskt1m12c2Return::salesReturnList');
    $subroutes->add('getSalesreturnList', 'Bdtaskt1m12c2Return::bdtaskt1m12c1_getReturnList');
    $subroutes->add('returnDetailsbyId/(:num)', 'Bdtaskt1m12c2Return::bdtaskt1m12c1_getReturnDetailsById/$1');
    
    });

    
});