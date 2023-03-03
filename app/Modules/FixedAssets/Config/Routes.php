<?php

if(!isset($routes))
{ 
    $routes = \Config\Services::routes(true);
}


$routes->group('fixedasset', ['namespace' => 'App\Modules\FixedAssets\Controllers'], function($subroutes){

         /***  Fixed Assets part ***/
    $subroutes->add('add_fixedasset', 'Fixedasset::bdtask_0001_fixedasset_form');
    $subroutes->add('add_fixedasset/(:num)', 'Fixedasset::bdtask_0001_fixedasset_form/$1');
    $subroutes->add('edit_fixedasset/(:num)', 'Fixedasset::bdtask_0001_fixedasset_form/$1');
    $subroutes->add('fixedasset_list', 'Fixedasset::index');
    $subroutes->add('delete_fixedasset/(:num)', 'Fixedasset::delete_fixedasset/$1');
    $subroutes->add('assets_purchase', 'Fixedasset::bdtask_0001_assets_purchase');
    $subroutes->add('assets_purchase_list', 'Fixedasset::fixed_asset_purchase_list');
});