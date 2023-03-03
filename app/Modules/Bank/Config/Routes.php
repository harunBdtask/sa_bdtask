<?php

if(!isset($routes))
{ 
    $routes = \Config\Services::routes(true);
}

$routes->group('bank', ['namespace' => 'App\Modules\Bank\Controllers'], function($subroutes){
    // routes
    $subroutes->add('banks', 'Bdtaskahbank::index');
    $subroutes->add('getAllBank', 'Bdtaskahbank::getAllBank');
    $subroutes->add('add_bank', 'Bdtaskahbank::bdtasktt_add_bank');
    $subroutes->add('getBankById/(:num)', 'Bdtaskahbank::bdtasktt_get_bank/$1');

    $subroutes->add('deleteBank/(:num)/(:num)', 'Bdtaskahbank::bdtasktt_delete_bank/$1/$2');
   
});