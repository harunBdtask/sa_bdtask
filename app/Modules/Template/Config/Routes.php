<?php

if(!isset($routes))
{ 
    $routes = \Config\Services::routes(true);
}

$routes->group('template', ['namespace' => 'App\Modules\Template\Controllers'], function($subroutes){

    // routes
    $subroutes->add('template_list', 'Bdtaskt1m12c14Template::index');
    $subroutes->add('getList', 'Bdtaskt1m12c14Template::bdtaskt1m12c14_01_getList');
    $subroutes->add('approveAction', 'Bdtaskt1m12c14Template::bdtaskt1m12c14_02_approveAction');
    $subroutes->add('getDetailsById/(:num)', 'Bdtaskt1m12c14Template::bdtaskt1m12c14_05_getDetailsById/$1');
    $subroutes->add('deleteRecord/(:num)', 'Bdtaskt1m12c14Template::bdtaskt1m12c14_08_deleteRecord/$1');
    $subroutes->add('getHtml', 'Bdtaskt1m12c14Template::bdtaskt1m12c14_06_getHtml');
    $subroutes->add('addTemplate', 'Bdtaskt1m12c14Template::bdtaskt1m12c14_07_addTemplate');


   
});