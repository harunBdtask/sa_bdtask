<?php

if(!isset($routes))
{ 
    $routes = \Config\Services::routes(true);
}
/*** Route for Human Resources ***/
$routes->group('permission', ['namespace' => 'App\Modules\User_management\Controllers'], function($subroutes){

	/*** Route for roles ***/
    $subroutes->group('roles', function($subroutes)
    {
        $subroutes->add('/', 'Bdtaskt1m16c1Permissions::index');
        $subroutes->add('add', 'Bdtaskt1m16c1Permissions::bdtaskt1m16c1_01_addRole');
        $subroutes->add('save', 'Bdtaskt1m16c1Permissions::bdtaskt1m16c1_02_createRole');
        $subroutes->add('edit/(:num)', 'Bdtaskt1m16c1Permissions::bdtaskt1m16c1_03_editRole/$1');
        $subroutes->add('update', 'Bdtaskt1m16c1Permissions::bdtaskt1m16c1_04_updateRole');
        $subroutes->add('delete/(:num)', 'Bdtaskt1m16c1Permissions::bdtaskt1m16c1_05_deleteRole/$1');
    });

	/*** Route for roles ***/
    $subroutes->group('activities', function($subroutes)
    {
        $subroutes->add('/', 'Bdtaskt1m1c1Activities::index');
        $subroutes->add('getList', 'Bdtaskt1m1c1Activities::bdtaskt1m1c1_01_getList');
    });

    /*** Route for modules ***/
    $subroutes->group('modules', function($subroutes)
    {
        $subroutes->add('/', 'Bdtaskt1m16c3Modules::index');
        $subroutes->add('getList', 'Bdtaskt1m16c3Modules::bdtaskt1m16c3_01_getModules');
        $subroutes->add('mList', 'Bdtaskt1m16c3Modules::bdtaskt1m16c3_07_moduleList');
        $subroutes->add('getInfoById/(:num)', 'Bdtaskt1m16c3Modules::bdtaskt1m16c3_02_moduleById/$1');
        $subroutes->add('update', 'Bdtaskt1m16c3Modules::bdtaskt1m16c3_03_updateModule');
        $subroutes->add('updateSub', 'Bdtaskt1m16c3Modules::bdtaskt1m16c3_06_updateModuleMenu');
        $subroutes->add('getModules', 'Bdtaskt1m16c3Modules::bdtaskt1m16c3_04_allModules');
        $subroutes->add('getMenuById/(:num)', 'Bdtaskt1m16c3Modules::bdtaskt1m16c3_05_moduleMenuById/$1');
    });

    /*** Route for users ***/
    $subroutes->group('users', function($subroutes)
    {
        $subroutes->add('/', 'Bdtaskt1m16c2Users::index');
        $subroutes->add('getList', 'Bdtaskt1m16c2Users::bdtaskt1m16c2_01_list');
        $subroutes->add('getUserById/(:num)', 'Bdtaskt1m16c2Users::bdtaskt1m16c2_02_userById/$1');
        $subroutes->add('getEmpList', 'Bdtaskt1m16c2Users::bdtaskt1m16c2_06_empList');
        $subroutes->add('getRoles', 'Bdtaskt1m16c2Users::bdtaskt1m16c2_04_getRoles');
        $subroutes->add('add', 'Bdtaskt1m16c2Users::bdtaskt1m16c2_05_addUser');
        $subroutes->add('isActivity', 'Bdtaskt1m16c2Users::bdtaskt1m16c2_07_isUserActive');
        $subroutes->add('empRoles/(:num)', 'Bdtaskt1m16c2Users::bdtaskt1m16c2_08_empRoles/$1');
        $subroutes->add('addMoreRole', 'Bdtaskt1m16c2Users::bdtaskt1m16c2_09_addMoreRole');
        $subroutes->add('userActivityLogs', 'Bdtaskt1m16c2Users::bdtaskt1m16c2_10_activityLogs');
        $subroutes->add('getEmployeeById/(:num)', 'Bdtaskt1m16c2Users::bdtaskt1m16c2_11_getEmployeeById/$1');
        $subroutes->add('get_store_name', 'Bdtaskt1m16c2Users::bdtaskt1m16c2_12_get_store_name/0');
        $subroutes->add('get_store_name/(:num)', 'Bdtaskt1m16c2Users::bdtaskt1m16c2_12_get_store_name/$1');
        $subroutes->add('getUserForEdit/(:num)', 'Bdtaskt1m16c2Users::bdtaskt1m16c2_13_getUserForEdit/$1');
        $subroutes->add('getDepartments', 'Bdtaskt1m16c2Users::bdtaskt1c1_05_getDepartments');
        $subroutes->add('updateUser', 'Bdtaskt1m16c2Users::bdtaskt1m16c2_14_updateUser');
        $subroutes->add('delete/(:num)', 'Bdtaskt1m16c2Users::bdtaskt1m16c2_16_delete/$1');
    });

    /*** Route for users ***/
    $subroutes->group('checker', function($subroutes)
    {
        $subroutes->add('/', 'Bdtaskt1m16c4PerCheck::index');
        $subroutes->add('get_check_result', 'Bdtaskt1m16c4PerCheck::bdtaskt1m16c2_01_get_check_result');
        $subroutes->add('getSubModule/(:num)', 'Bdtaskt1m16c4PerCheck::bdtaskt1m16c2_02_getSubModule/$1');
    });

});