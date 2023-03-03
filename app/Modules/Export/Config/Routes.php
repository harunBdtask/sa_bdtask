<?php

if(!isset($routes))
{ 
    $routes = \Config\Services::routes(true);
}

$routes->group('data_export', ['namespace' => 'App\Modules\Export\Controllers'], function($subroutes){
    // Route for department 
    $subroutes->add('export', 'Bdtaskt1c1Export::index');
    $subroutes->add('get_user_data', 'Bdtaskt1c1Export::bdtaskt1c1_01_get_user_data');
    $subroutes->add('get_employee_data', 'Bdtaskt1c1Export::bdtaskt1c1_02_get_employee_data');
    $subroutes->add('get_service_data', 'Bdtaskt1c1Export::bdtaskt1c1_03_get_service_data');
    $subroutes->add('get_offer_data', 'Bdtaskt1c1Export::bdtaskt1c1_04_get_offer_data');
    $subroutes->add('get_package_data', 'Bdtaskt1c1Export::bdtaskt1c1_05_get_package_data');
    $subroutes->add('get_item_data', 'Bdtaskt1c1Export::bdtaskt1c1_06_get_item_data');

    // branchs routes
    $subroutes->add('backup', 'Bdtaskt1c2Backup::index');    
    $subroutes->add('getBackup', 'Bdtaskt1c2Backup::bdtaskt1c2_01_getList');    
    $subroutes->add('deleteBackup/(:num)', 'Bdtaskt1c2Backup::bdtaskt1c2_02_deleteBackup/$1');    
    $subroutes->add('sql_backup', 'Bdtaskt1c2Backup::sql_backup');    

});