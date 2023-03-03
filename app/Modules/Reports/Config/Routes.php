<?php

if(!isset($routes))
{ 
    $routes = \Config\Services::routes(true);
}
/*** Route for Reports ***/
$routes->group('reports', ['namespace' => 'App\Modules\Reports\Controllers'], function($subroutes){

    $subroutes->group('production', ['namespace' => 'App\Modules\Reports\Controllers'], function($subroutes){
       
        $subroutes->add('daily_production', 'Bdtaskt1m17c8ProductionReports::bdtaskt1m17c8_01_dailyProduction');
        $subroutes->add('get_daily_production', 'Bdtaskt1m17c8ProductionReports::bdtaskt1m17c8_02_getDailyProduction');

    }); 

    $subroutes->group('machine', ['namespace' => 'App\Modules\Reports\Controllers'], function($subroutes){
       
        $subroutes->add('daily_consumption', 'Bdtaskt1m17c9MachineReports::bdtaskt1m17c9_01_dailyConsumption');
        $subroutes->add('get_daily_consumption', 'Bdtaskt1m17c9MachineReports::bdtaskt1m17c9_02_getDailyConsumption');

        $subroutes->add('plant_consumption', 'Bdtaskt1m17c9MachineReports::bdtaskt1m17c9_03_plantConsumption');
        $subroutes->add('get_plant_consumption', 'Bdtaskt1m17c9MachineReports::bdtaskt1m17c9_04_getPlantConsumption');

    }); 

    $subroutes->group('bag', ['namespace' => 'App\Modules\Reports\Controllers'], function($subroutes){
       
        $subroutes->add('bag_stock', 'Bdtaskt1m17c9BagReports::bdtaskt1m17c9_01_bag_stock');
        $subroutes->add('get_bag_stock', 'Bdtaskt1m17c9BagReports::bdtaskt1m17c9_02_get_bag_stock');

    }); 
      
	// Route of accounts reports 
	$subroutes->group('account', ['namespace' => 'App\Modules\Reports\Controllers'], function($subroutes){
        $subroutes->add('statements', 'Bdtaskt1m17c1Accounts::bdtaskt1m17c1_01_accStatements');
	    $subroutes->add('getStatements', 'Bdtaskt1m17c1Accounts::bdtaskt1m17c1_02_getAccStatements');
	    $subroutes->add('searchPatientAcc', 'Bdtaskt1m17c1Accounts::bdtaskt1m17c1_03_searchPatientAcc');
        $subroutes->add('searchSupplierAcc', 'Bdtaskt1m17c1Accounts::bdtaskt1m17c1_04_searchSupplierAcc');
        $subroutes->add('getPatientInfoById', 'Bdtaskt1m17c1Accounts::bdtaskt1m17c1_06_getPatientInfoById');
        $subroutes->add('getSupplierInfoById', 'Bdtaskt1m17c1Accounts::bdtaskt1m17c1_07_getSupplierInfoById');
	    $subroutes->add('accountList', 'Bdtaskt1m17c1Accounts::bdtaskt1m17c1_08_getAccountList');
        $subroutes->add('getReceiveInfo', 'Bdtaskt1m17c1Accounts::bdtaskt1m17c1_09_getReceiveInfo');
        $subroutes->add('getReturnInfo', 'Bdtaskt1m17c1Accounts::bdtaskt1m17c1_10_getReturnInfo');
        $subroutes->add('getPaymentInfo', 'Bdtaskt1m17c1Accounts::bdtaskt1m17c1_11_getPaymentInfo');
        $subroutes->add('getConsumptionInfo', 'Bdtaskt1m17c1Accounts::bdtaskt1m17c1_15_getConsumptionInfo');
        $subroutes->add('journal_reports', 'Bdtaskt1m17c1Accounts::bdtaskt1m17c1_12_journalReports');
        $subroutes->add('getJournalReport', 'Bdtaskt1m17c1Accounts::bdtaskt1m17c1_13_getJournalReports');
        $subroutes->add('exportJVReports', 'Bdtaskt1m17c1Accounts::bdtaskt1m17c1_14_exportJournalReports');
        $subroutes->add('patientStatements', 'Bdtaskt1m17c1Accounts::bdtaskt1m17c1_15_patientStatements');
        $subroutes->add('supplierStatements', 'Bdtaskt1m17c1Accounts::bdtaskt1m17c1_16_supplierStatements');
        $subroutes->add('searchAccounts', 'Bdtaskt1m17c1Accounts::bdtaskt1m17c1_17_searchAccounts');
    });

    
    //SalesReport
    $subroutes->group('sales', ['namespace' => 'App\Modules\Reports\Controllers'], function($subroutes){
        $subroutes->add('daily_sales', 'Bdtaskt1m1c1SalesReports::index');
        $subroutes->add('daily_sales_table', 'Bdtaskt1m1c1SalesReports::bdtaskt1m1c1_01_getDailySales');
        $subroutes->add('do_summary', 'Bdtaskt1m1c1SalesReports::bdtaskt1m1c1_02_indexDoSummary');
        $subroutes->add('do_summary_table', 'Bdtaskt1m1c1SalesReports::bdtaskt1m1c1_03_getDoSummary');
        $subroutes->add('long_credit', 'Bdtaskt1m1c1SalesReports::bdtaskt1m1c1_04_indexLongCredit');
        $subroutes->add('long_credit_table', 'Bdtaskt1m1c1SalesReports::bdtaskt1m1c1_05_getLongCredit');
        $subroutes->add('short_credit', 'Bdtaskt1m1c1SalesReports::bdtaskt1m1c1_06_indexShortCredit');
        $subroutes->add('short_credit_table', 'Bdtaskt1m1c1SalesReports::bdtaskt1m1c1_07_getShortCredit');
        $subroutes->add('purchase_dept', 'Bdtaskt1m1c1SalesReports::bdtaskt1m1c1_08_indexPurchaseDepartment');
        $subroutes->add('purchase_dept_table', 'Bdtaskt1m1c1SalesReports::bdtaskt1m1c1_09_getPurchaseDepartment');
        $subroutes->add('get_item_list', 'Bdtaskt1m1c1SalesReports::bdtaskt1m1c1_10_get_item_list');
        $subroutes->add('distributor_wise_sales', 'Bdtaskt1m1c1SalesReports::bdtaskt1m1c1_11_indexDistributorWiseSales');
        $subroutes->add('distributor_wise_sales_table', 'Bdtaskt1m1c1SalesReports::bdtaskt1m1c1_12_getDistributorWiseSales');
        $subroutes->add('officer_wise_sales', 'Bdtaskt1m1c1SalesReports::bdtaskt1m1c1_13_indexOfficerWiseSales');
        $subroutes->add('officer_wise_sales_table', 'Bdtaskt1m1c1SalesReports::bdtaskt1m1c1_14_getOfficerWiseSales');

    }); 


  
});