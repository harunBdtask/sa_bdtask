<?php namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php'))
{
	require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Modules\Dashboard\Controllers');
$routes->setDefaultController('Bdtaskt1c2Dashboard');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
// $routes->set404Override();
$routes->setAutoRoute(true);
 // Would execute the show404 method of the App\Errors class
$routes->set404Override('App\Errors::show404');

// Will display a custom view
$routes->set404Override(function()
{
    echo view('App\Views\errors\html\custom_404.php');
});
/**
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Bdtaskt1c2Dashboard::index');
$routes->get('login', 'Bdtaskt1c1Auth::index');
$routes->get('logout', 'Bdtaskt1c1Auth::bdtaskt1c1_01_logout');

/*Employee Attendance Api By Misor from 23-9-2021*/
$routes->group('api_handler_v2', ['namespace' => 'App\Controllers'], function($subroutes){
    $subroutes->add('check_status', 'Bdtask1c1AttendanceEmployee::bdtaskt1c1_01_check_status');
    $subroutes->add('insert_purchase_info', 'Bdtask1c1AttendanceEmployee::bdtaskt1c1_02_insert_purchase_info');
    $subroutes->add('get_purchase_info', 'Bdtask1c1AttendanceEmployee::bdtaskt1c1_03_get_purchase_info');
    $subroutes->add('insert_zkt_ip', 'Bdtask1c1AttendanceEmployee::bdtaskt1c1_04_insert_zkt_ip');
    $subroutes->add('get_all_ip_address', 'Bdtask1c1AttendanceEmployee::bdtaskt1c1_05_get_all_ip_address');
    $subroutes->add('delete_zkt_ip', 'Bdtask1c1AttendanceEmployee::bdtaskt1c1_06_delete_zkt_ip');
    $subroutes->add('create_attendence', 'Bdtask1c1AttendanceEmployee::bdtaskt1c1_07_create_attendence');
    $subroutes->add('bulk_attendance_push', 'Bdtask1c1AttendanceEmployee::bdtaskt1c1_08_bulk_attendance_push');
    $subroutes->post('get_employee_by_id', 'Bdtask1c1AttendanceEmployee::bdtaskt1c1_09_get_employee_by_id');
    $subroutes->get('setting_info', 'Bdtask1c1AttendanceEmployee::bdtaskt1c1_10_setting_info');
});
/*End*/


/**
 * --------------------------------------------------------------------
 * HMVC Routing
 * --------------------------------------------------------------------
 */

foreach(glob(APPPATH . 'Modules/*', GLOB_ONLYDIR) as $item_dir)
{
	if (file_exists($item_dir . '/Config/Routes.php'))
	{
		require_once($item_dir . '/Config/Routes.php');
	}	
}
/**
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php'))
{
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
