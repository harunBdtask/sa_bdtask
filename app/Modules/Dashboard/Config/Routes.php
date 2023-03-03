<?php

if (!isset($routes)) {
	$routes = \Config\Services::routes(true);
}

$routes->group('dashboard', ['namespace' => 'App\Modules\Dashboard\Controllers'], function ($subroutes) {

	/*** Route for Dashboard ***/
	$subroutes->add('/', 'Bdtaskt1c2Dashboard::index');
	$subroutes->add('home', 'Bdtaskt1c2Dashboard::index');
	$subroutes->add('accounts_chart', 'Bdtaskt1c2Dashboard::accounts_chart');
	$subroutes->add('hrm_chart', 'Bdtaskt1c2Dashboard::hrm_chart');
	$subroutes->add('procurement_chart', 'Bdtaskt1c2Dashboard::procurement_chart');
	$subroutes->add('production_chart', 'Bdtaskt1c2Dashboard::production_chart');
	$subroutes->add('production2_chart', 'Bdtaskt1c2Dashboard::production2_chart');
	$subroutes->add('sale_chart', 'Bdtaskt1c2Dashboard::sale_chart');
	$subroutes->add('finished_goods_store_list', 'Bdtaskt1c2Dashboard::finished_goods_store_getList');
	$subroutes->add('getSalesList', 'Bdtaskt1c2Dashboard::getSalesList');
	$subroutes->add('store_chart', 'Bdtaskt1c2Dashboard::store_chart');
	$subroutes->add('store_chart_list', 'Bdtaskt1c2Dashboard::store_chart_getList');
	$subroutes->add('po_list', 'Bdtaskt1c2Dashboard::po_getList');

	// Routes for the Graphicial chart data of HRM part
	$subroutes->add('daily_attendnace_emp', 'Bdtaskt1c2Dashboard::daily_attendnace_employee');
	$subroutes->add('deptmnt_wise_strength', 'Bdtaskt1c2Dashboard::department_wise_strength');
	$subroutes->add('emp_type_wise_data', 'Bdtaskt1c2Dashboard::employee_type_wise_data');
	$subroutes->add('emp_status_wise_data', 'Bdtaskt1c2Dashboard::employee_status_wise_data');
});

$routes->group('auth', ['namespace' => 'App\Modules\Dashboard\Controllers'], function ($subroutes) {

	/*** Route for Authentications ***/
	$subroutes->add('login', 'Bdtaskt1c1Auth::index');
	$subroutes->add('loggedin', 'Bdtaskt1c1Auth::isLogIn');
	$subroutes->add('changelangauge', 'Bdtaskt1c1Auth::bdtaskt1c1_02_changelangauge');
	$subroutes->add('changeBranch', 'Bdtaskt1c1Auth::bdtaskt1c1_04_changeBranch');
	$subroutes->add('isActivity', 'Bdtaskt1m1c3ActivitiesLog::Bdtaskt1m1c3_01_isUserActive');
	$subroutes->add('checkActivity', 'Bdtaskt1m1c3ActivitiesLog::Bdtaskt1m1c3_02_checkActivities');
	$subroutes->add('activityLogin', 'Bdtaskt1m1c3ActivitiesLog::Bdtaskt1m1c3_03_accessValidation');
	$subroutes->add('select2List/(:any)', 'Bdtaskt1c1Common::bdtaskt1c1_01_select2List/$1');
	$subroutes->add('select2Name/(:any)', 'Bdtaskt1c1Common::bdtaskt1c1_02_select2Name/$1');
	$subroutes->add('searchPatient', 'Bdtaskt1c1Common::bdtaskt1c1_03_searchPatient');
	$subroutes->add('searchDoctor', 'Bdtaskt1c1Common::bdtaskt1c1_04_searchDoctor');
	$subroutes->add('deptByDoctors/(:num)', 'Bdtaskt1c1Common::bdtaskt1c1_05_deptDoctors/$1');
	$subroutes->add('searchDocServ', 'Bdtaskt1c1Common::bdtaskt1c1_06_searchDocServices');
	$subroutes->add('getDiagnosis', 'Bdtaskt1c1Common::bdtaskt1c1_07_getDiagnosis');
	$subroutes->add('packageList', 'Bdtaskt1c1Common::bdtaskt1c1_08_searchPackages');
	$subroutes->add('searchMedicine', 'Bdtaskt1c1Common::bdtaskt1c1_09_searchMedicines');
	$subroutes->add('activeOffers', 'Bdtaskt1c1Common::bdtaskt1c1_10_getActiveOffers');
	$subroutes->add('searchAppoint', 'Bdtaskt1c1Common::bdtaskt1c1_11_searchAppoint');
	$subroutes->add('doctorList', 'Bdtaskt1c1Common::bdtaskt1c1_12_getDoctorList');
	$subroutes->add('departmentList', 'Bdtaskt1c1Common::bdtaskt1c1_13_getDepartList');
	$subroutes->add('mnTempList', 'Bdtaskt1c1Common::bdtaskt1c1_14_getMNTempList');
	$subroutes->add('searchPntWithFile', 'Bdtaskt1c1Common::bdtaskt1c1_15_searchPntWithFile');
	$subroutes->add('searchAllWithFile', 'Bdtaskt1c1Common::bdtaskt1c1_28_searchAllPatient');
	$subroutes->add('searchRVoucher', 'Bdtaskt1c1Common::bdtaskt1c1_16_searchRVoucher');
	$subroutes->add('allPackList', 'Bdtaskt1c1Common::bdtaskt1c1_17_packageList');
	$subroutes->add('searchInvoiceNo', 'Bdtaskt1c1Common::bdtaskt1c1_18_searchInvoiceNo');
	$subroutes->add('searchEmployee', 'Bdtaskt1c1Common::bdtaskt1c1_19_searchEmployee');
	$subroutes->add('patientList', 'Bdtaskt1c1Common::bdtaskt1c1_20_patientList');
	$subroutes->add('getMaxId/(:any)/(:any)', 'Bdtaskt1c1Common::bdtaskt1c1_21_getTableMaxId/$1/$2');
	$subroutes->add('branchList', 'Bdtaskt1c1Common::bdtaskt1c1_22_getBranchList');
	$subroutes->add('procedureRoom', 'Bdtaskt1c1Common::bdtaskt1c1_23_procedureRoom');
	$subroutes->add('countryPhoneCode', 'Bdtaskt1c1Common::bdtaskt1c1_24_countryPhoneCode');
	$subroutes->add('searchUserName', 'Bdtaskt1c1Common::bdtaskt1c1_25_searchUserName');
	$subroutes->add('searchPharmacyCustomer', 'Bdtaskt1c1Common::bdtaskt1c1_26_searchPharmacyCustomer');
	$subroutes->add('getPharmacyItem', 'Bdtaskt1c1Common::bdtaskt1c1_27_getPharmacyItem');
	$subroutes->add('deleteExcel', 'Bdtaskt1c1Common::bdtaskt1c1_29_deleteExportExFile');
	$subroutes->add('getAssignDoctorList', 'Bdtaskt1c1Common::bdtaskt1c1_30_getAssignDoctorList');
	$subroutes->add('invoice/(:num)', 'Bdtaskt1c1Auth::invoicePDF/$1');
	$subroutes->add('invoice_pos/(:num)', 'Bdtaskt1c1Auth::invoicePOS/$1');
	$subroutes->add('servInvoice/(:num)', 'Bdtaskt1c1Auth::generatePDF/$1');
	$subroutes->add('getInvoiceList', 'Bdtaskt1c1Common::bdtaskt1c1_31_getPharmacyInvoice');
	$subroutes->add('clearLog', 'Bdtaskt1c1Auth::clearLog');
});
//$routes->resource('api');
$routes->group('api', ['namespace' => 'App\Modules\Dashboard\Controllers'], function ($subroutes) {
	$subroutes->post("login", "Api::login");
	// $subroutes->get("user_list", "Api::userList", ['filter' => 'auth']);
	$subroutes->patch('show/(:num)', 'Api::show/$1', ['filter' => 'auth']);
	// $subroutes->put('update','Api::update');
	// $subroutes->delete('delete','Api::delete');
	$subroutes->add('products', 'Api::products');
	$subroutes->add('products_batch', 'Api::products_batchstockdata');
	$subroutes->add('save_do', 'Api::save_dodata');
	$subroutes->add('dealer_info', 'Api::dealerByEmployee');
	$subroutes->add('sales_list', 'Api::doList');
	$subroutes->add('check_attendence', 'Api::emp_attendence');
	$subroutes->add('emp_attendence', 'Api::emp_attendence');
	$subroutes->add('tour_plan_list', 'Api::tour_plan_list');
	$subroutes->add('revised_tour_plan', 'Api::revised_tour_plan');
	$subroutes->add('employee_sales', 'Api::employee_sales');
});
