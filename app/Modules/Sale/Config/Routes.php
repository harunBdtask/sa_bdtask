<?php

if(!isset($routes))
{ 
    $routes = \Config\Services::routes(true);
}

$routes->group('sale', ['namespace' => 'App\Modules\Sale\Controllers'], function($subroutes){
    $subroutes->group('zone', function($subroutes)
    {
        $subroutes->add('add_zones', 'Bdtaskt1m12c1Zones::bdtaskt1m12c1_03_addZones');
        $subroutes->add('zone_list', 'Bdtaskt1m12c1Zones::index');
        $subroutes->add('getzones', 'Bdtaskt1m12c1Zones::bdtaskt1m12c1_01_getList');
        $subroutes->add('getzoneDetailsById/(:num)', 'Bdtaskt1m12c1Zones::bdtaskt1m12c1_05_getZoneDetailsById/$1');
        $subroutes->add('getzonesById/(:num)', 'Bdtaskt1m12c1Zones::bdtaskt1m12c1_04_getZonesById/$1');
        $subroutes->add('deletezones/(:num)', 'Bdtaskt1m12c1Zones::bdtaskt1m12c1_02_deleteZones/$1');


        

    });

    $subroutes->group('dealer', function($subroutes)
    {
        $subroutes->add('add_dealer', 'Bdtaskt1m12c2Dealers::bdtaskt1m12c2_03_adddealers');
        $subroutes->add('dealer_list', 'Bdtaskt1m12c2Dealers::index');
        $subroutes->add('getdealers', 'Bdtaskt1m12c2Dealers::bdtaskt1m13c2_01_getList');
        $subroutes->add('getDealerDetailsById/(:num)', 'Bdtaskt1m12c2Dealers::bdtaskt1m12c2_04_getdealersById/$1');
        $subroutes->add('deletedealers/(:num)', 'Bdtaskt1m12c2Dealers::bdtaskt1m12c2_02_deleteDealers/$1');
        $subroutes->add('getDealersDropdown', 'Bdtaskt1m12c2Dealers::bdtaskt1c1_05_getDealersDropdown');
        $subroutes->add('check_assigned_dealer', 'Bdtaskt1m12c2Dealers::bdtaskt1c1_07_checkAssignedSalesofficer');
        $subroutes->add('assignedOfficers/(:num)', 'Bdtaskt1m12c2Dealers::bdtaskt1m1c1_08_assignedOfficers/$1');
        $subroutes->add('getRoles', 'Bdtaskt1m12c2Dealers::bdtaskt1m1c1_09_getRoles');
        $subroutes->add('addMoreRole', 'Bdtaskt1m12c2Dealers::bdtaskt1m1c1_10_addMoreRole');


    });

    $subroutes->group('deliver_order', function($subroutes)
    {
        $subroutes->add('add_do', 'Bdtaskt1m12c2DeliveryOrder::bdtask_sale_001DoForm');
        $subroutes->add('do_list', 'Bdtaskt1m12c2DeliveryOrder::index');
        $subroutes->add('search_product', 'Bdtaskt1m12c2DeliveryOrder::bdtask_002item_search');
        $subroutes->add('product_details_data', 'Bdtaskt1m12c2DeliveryOrder::bdtask_003item_detailsdata');
        $subroutes->add('search_item_stock', 'Bdtaskt1m12c2DeliveryOrder::bdtask_004item_stockdata');
        $subroutes->add('save_do', 'Bdtaskt1m12c2DeliveryOrder::bdtask_save_dodata');
        $subroutes->add('getsalesadmindo', 'Bdtaskt1m12c2DeliveryOrder::bdtaskt1m12c1_getDoList');
        $subroutes->add('getdoDetailsById/(:num)', 'Bdtaskt1m12c2DeliveryOrder::bdtaskt1m12c2_07_getdodealersById/$1');
        $subroutes->add('sales_admin_approve', 'Bdtaskt1m12c2DeliveryOrder::bdtaskt1m12c_confirm_do');
        $subroutes->add('do_edit', 'Bdtaskt1m12c2DeliveryOrder::bdtaskt1m13c_do_edit');
        $subroutes->add('update_do', 'Bdtaskt1m12c2DeliveryOrder::bdtask_update_dodata');
        $subroutes->add('do_delivery', 'Bdtaskt1m12c2DeliveryOrder::bdtaskt1m13c_do_delivery');
        $subroutes->add('save_delivered', 'Bdtaskt1m12c2DeliveryOrder::bdtaskt1m13c_save_deliver');
        $subroutes->add('getItemDropdown', 'Bdtaskt1m12c2DeliveryOrder::bdtaskt1c1_06_getItemDropdown');
        $subroutes->add('get_sales_man', 'Bdtaskt1m12c2DeliveryOrder::get_sales_man');

        /*accounts do list*/
        $subroutes->add('account_do_list', 'Bdtaskt1m12c2DeliveryOrder::account_do_list');
        $subroutes->add('getaccountsdo', 'Bdtaskt1m12c2DeliveryOrder::bdtaskt1m12c1_getAccountDoList');
        $subroutes->add('accounts_do_approval', 'Bdtaskt1m12c2DeliveryOrder::bdtaskt1m12c_do_accounts_approval');
        $subroutes->add('accounts_do_paid', 'Bdtaskt1m12c2DeliveryOrder::bdtaskt1m13c_accounts_do_paid');
        $subroutes->add('dealer_payment/(:num)', 'Bdtaskt1m12c2DeliveryOrder::bdtaskt1m15c_dealer_payment/$1');
        $subroutes->add('getaccountdoDetailsById/(:num)', 'Bdtaskt1m12c2DeliveryOrder::bdtaskt1m12c2_08_getaccountdodealersById/$1');

           /*factory manager do list*/
        $subroutes->add('factorymanager_do_list', 'Bdtaskt1m12c2DeliveryOrder::factory_manager_do_list');
        $subroutes->add('getfactorymando', 'Bdtaskt1m12c2DeliveryOrder::bdtaskt1m12c1_getFactoryManagerDoList');
        $subroutes->add('fmanager_do_approval', 'Bdtaskt1m12c2DeliveryOrder::bdtaskt1m13c_do_FactoryManager_approval');
        $subroutes->add('getdoDetailsFactrysection/(:num)', 'Bdtaskt1m12c2DeliveryOrder::bdtaskt1m12c2_09_getdodealersFactorySection/$1');
        $subroutes->add('getdoDetailsdeliveryection/(:num)', 'Bdtaskt1m12c2DeliveryOrder::bdtaskt1m12c2_09_getdodealersdeliverySection/$1');
                /*Delivery Section do list*/
        $subroutes->add('deliverySection_do_list', 'Bdtaskt1m12c2DeliveryOrder::deliverySection_do_list');
        $subroutes->add('getdeliverysectiondo', 'Bdtaskt1m12c2DeliveryOrder::bdtaskt1m12c1_getDeliverySectionDoList');
        $subroutes->add('deliverysection_do_approval', 'Bdtaskt1m12c2DeliveryOrder::bdtaskt1m14c_do_DeliverySection_approval');

             /*Store Section do list*/
        $subroutes->add('storeSection_do_list', 'Bdtaskt1m12c2DeliveryOrder::storeSection_do_list');
        $subroutes->add('getstoresectiondo', 'Bdtaskt1m12c2DeliveryOrder::bdtaskt1m12c1_getStoreSectionDoList');
        $subroutes->add('storesection_do_approval', 'Bdtaskt1m12c2DeliveryOrder::bdtaskt1m15c_do_StoreSection_approval');
       $subroutes->add('gate_pass', 'Bdtaskt1m12c2DeliveryOrder::bdtaskt1m15c_do_gate_pass');
       $subroutes->add('getgate_pass', 'Bdtaskt1m12c2DeliveryOrder::bdtaskt1m12c1_getGatePassList');
       $subroutes->add('gatepass_form', 'Bdtaskt1m12c2DeliveryOrder::bdtaskt1m12c1_gatepass_form');
       $subroutes->add('save_gatepass', 'Bdtaskt1m12c2DeliveryOrder::bdtaskt1m12c1_save_gatepass');
       $subroutes->add('approve_gatepass', 'Bdtaskt1m12c2DeliveryOrder::bdtaskt1m12c1_approve_gatepass');
       $subroutes->add('getdoGatePassdata/(:num)', 'Bdtaskt1m12c2DeliveryOrder::bdtaskt1m12c2_09_getGatepassinfo/$1');
       $subroutes->add('getSalePersonDetailsById/(:num)', 'Bdtaskt1m12c2DeliveryOrder::bdtaskt1m12c2_09_getSalespersonDetails/$1');
       

    });
});