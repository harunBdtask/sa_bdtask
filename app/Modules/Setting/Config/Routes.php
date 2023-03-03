<?php

if(!isset($routes))
{ 
    $routes = \Config\Services::routes(true);
}

$routes->group('settings', ['namespace' => 'App\Modules\Setting\Controllers'], function($subroutes){

	/*** Route for Settings ***/
	$subroutes->add('application', 'Bdtaskt1c1Setting::index');
	$subroutes->add('add', 'Bdtaskt1c1Setting::bdtaskt1c1_01_update');
	$subroutes->add('basicList', 'Bdtaskt1c1Setting::bdtaskt1c1_03_basicList');
	$subroutes->add('idCard', 'Bdtaskt1c1Setting::bdtaskt1c1_04_idCard');
	$subroutes->add('recaptcha', 'Bdtaskt1c1Setting::bdtaskt1c1_05_reCaptcha');
	$subroutes->add('sendMail', 'Bdtaskt1c1Setting::bdtaskt1c1_06_sendMail');
	$subroutes->add('addFinancialType', 'Bdtaskt1c1Setting::bdtaskt1c1_12_addFinancialType');
	$subroutes->add('financialTypeList', 'Bdtaskt1c1Setting::bdtaskt1c1_11_financialTypeList');
	$subroutes->add('getFinanTypeById', 'Bdtaskt1c1Setting::bdtaskt1c1_13_getFinanTypeById');
	$subroutes->add('deleteFinanType', 'Bdtaskt1c1Setting::bdtaskt1c1_14_deleteFinanTypeById');

	// languages routes
	$subroutes->add('languages', 'Bdtaskt1c2Language::index');
	$subroutes->add('edit_phrase/(:any)', 'Bdtaskt1c2Language::bdtaskt1c2_03_editPhrase/$1');
	$subroutes->add('update_phrase', 'Bdtaskt1c2Language::bdtaskt1c2_07_addLabel');
	$subroutes->add('saveLangJson/(:any)', 'Bdtaskt1c2Language::bdtaskt1c2_09_langData/$1');
	$subroutes->add('updatePhrase', 'Bdtaskt1c2Language::bdtaskt1c2_10_updatePhrase');
	$subroutes->add('edit_message_phrase/(:any)/(:any)', 'Bdtaskt1c2Language::bdtaskt1c2_06_editNotifyMessage/$1/$2');
	$subroutes->add('updateMsgPhrase', 'Bdtaskt1c2Language::bdtaskt1c2_07_updateMsgPhrase');

    $subroutes->add('lists', 'Bdtaskt1m15c3Lists::index');
    $subroutes->add('getLists', 'Bdtaskt1m15c3Lists::bdtaskt1m15c3_01_getList');
    $subroutes->add('deleteLists/(:num)', 'Bdtaskt1m15c3Lists::bdtaskt1m15c3_02_deleteLists/$1');
    $subroutes->add('getListsById/(:num)', 'Bdtaskt1m15c3Lists::bdtaskt1m15c3_04_getListsById/$1');
    $subroutes->add('add_lists', 'Bdtaskt1m15c3Lists::bdtaskt1m15c3_03_addLists');

    // financial year routes
    $subroutes->add('set_financial_year', 'Bdtaskt1m15c4FinancialYear::index');
    $subroutes->add('getFinancialYear', 'Bdtaskt1m15c4FinancialYear::bdtaskt1m15c4_01_getFinYearList');
    $subroutes->add('addFinancialYear', 'Bdtaskt1m15c4FinancialYear::bdtaskt1m15c4_02_addFinancialYear');
    $subroutes->add('getFyById', 'Bdtaskt1m15c4FinancialYear::bdtaskt1m15c4_03_getFyById');
    $subroutes->add('activateFyById', 'Bdtaskt1m15c4FinancialYear::bdtaskt1m15c4_04_activeFinancialYear');

    // referal commission setting
    $subroutes->add('referral_commissions', 'Bdtaskt1c1ReferalCommission::index');
    $subroutes->add('update_referral', 'Bdtaskt1c1ReferalCommission::bdtaskt1c1_03_update');

});