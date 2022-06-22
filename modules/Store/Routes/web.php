<?php

use Illuminate\Support\Facades\Route;

$current_hostname = app(Hyn\Tenancy\Contracts\CurrentHostname::class);

if($current_hostname) {
    Route::domain($current_hostname->fqdn)->group(function () {
        Route::middleware(['auth', 'locked.tenant'])->group(function () {
            Route::prefix('store')->group(function () {
//                Route::post('get_items', 'StoreController@getItems');
                Route::get('get_quotation_tables', 'StoreController@getQuotationTables');
                Route::get('get_item_tables', 'StoreController@getItemTables');
                Route::post('search_customers', 'StoreController@searchCustomers');
                Route::post('search_items', 'StoreController@searchItems');

                Route::get('search_item/{item}', 'StoreController@searchItem');
                Route::get('search_customer/{customer}', 'StoreController@searchCustomer');
            });
        });
    });
}
