<?php

if(!isset($routes))
{ 
    $routes = \Config\Services::routes(true);
}

$routes->group('section', ['namespace' => 'App\Modules\Section\Controllers'], function($subroutes){
    // branchs routes
    $subroutes->add('branchs', 'Bdtaskt1c4Branchs::index');
    $subroutes->add('getBranchs', 'Bdtaskt1c4Branchs::bdtaskt1c4_01_getList');
    $subroutes->add('deleteBranch/(:num)', 'Bdtaskt1c4Branchs::bdtaskt1c4_02_deleteDepart/$1');
    $subroutes->add('getBranchById/(:num)', 'Bdtaskt1c4Branchs::bdtaskt1c4_04_getDepartById/$1');
    $subroutes->add('add_branch', 'Bdtaskt1c4Branchs::bdtaskt1c4_03_addBranch');

	/*** Route for department ***/
    $subroutes->add('departments', 'Bdtaskt1c1Departments::index');
    $subroutes->add('getDepartments', 'Bdtaskt1c1Departments::bdtaskt1c1_01_getList');
    $subroutes->add('deleteDepartment/(:num)', 'Bdtaskt1c1Departments::bdtaskt1c1_02_deleteDepart/$1');
    $subroutes->add('getDepartById/(:num)', 'Bdtaskt1c1Departments::bdtaskt1c1_04_getDepartById/$1');
    $subroutes->add('add_department', 'Bdtaskt1c1Departments::bdtaskt1c1_03_addDepartment');
    $subroutes->add('departmentList', 'Bdtaskt1c1Departments::bdtaskt1c1_05_getDepartmentList');
    $subroutes->add('departmentListByBranch/(:num)', 'Bdtaskt1c1Departments::bdtaskt1c1_06_getDepartmentsByBranch/$1');

    /*** Route for department ***/
    $subroutes->add('dealer', 'Bdtaskt1c3Dealer::index');
    $subroutes->add('getDealers', 'Bdtaskt1c3Dealer::bdtaskt1c1_01_getList');
    $subroutes->add('deleteDealer/(:num)', 'Bdtaskt1c3Dealer::bdtaskt1c1_02_deleteDealer/$1');
    $subroutes->add('getDealerById/(:num)', 'Bdtaskt1c3Dealer::bdtaskt1c1_04_getDealerById/$1');
    $subroutes->add('add_dealer', 'Bdtaskt1c3Dealer::bdtaskt1c1_03_addDealer');
    $subroutes->add('dealerList', 'Bdtaskt1c3Dealer::bdtaskt1c1_05_getDealerList');
    $subroutes->add('dealerListByBranch/(:num)', 'Bdtaskt1c3Dealer::bdtaskt1c1_06_getDealersByBranch/$1');
   
});