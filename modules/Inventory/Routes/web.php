<?php

$hostname = app(Hyn\Tenancy\Contracts\CurrentHostname::class);

if($hostname) {
    Route::domain($hostname->fqdn)->group(function () {
        Route::middleware('auth')->group(function() {
            // Config inventory

            Route::prefix('warehouses')->group(function () {
                Route::get('/', 'WarehouseController@index')->name('warehouses.index');
                Route::get('records', 'WarehouseController@records');
                Route::get('columns', 'WarehouseController@columns');
                Route::get('tables', 'WarehouseController@tables');
                Route::get('record/{warehouse}', 'WarehouseController@record');
                Route::post('/', 'WarehouseController@store');
                Route::get('initialize', 'WarehouseController@initialize');
            });

            Route::prefix('inventory')->group(function () {
                Route::get('/', 'InventoryController@index')->name('inventory.index');
                Route::get('records', 'InventoryController@records');
                Route::get('columns', 'InventoryController@columns');
                Route::get('tables', 'InventoryController@tables');
                Route::get('record/{inventory}', 'InventoryController@record');
                Route::post('/', 'InventoryController@store');
                Route::post('move', 'InventoryController@move');
                Route::post('remove', 'InventoryController@remove');
                Route::get('initialize', 'InventoryController@initialize'); 
            });

            Route::prefix('reports')->group(function () {
                Route::get('inventory', 'ReportInventoryController@index')->name('reports.inventory.index');
                Route::post('inventory/search', 'ReportInventoryController@search')->name('reports.inventory.search');
                Route::post('inventory/pdf', 'ReportInventoryController@pdf')->name('reports.inventory.pdf');
                Route::post('inventory/excel', 'ReportInventoryController@excel')->name('reports.inventory.report_excel');

                Route::get('kardex', 'ReportKardexController@index')->name('reports.kardex.index');
                Route::post('kardex/search', 'ReportKardexController@search')->name('reports.kardex.search');
                Route::post('kardex/pdf', 'ReportKardexController@pdf')->name('reports.kardex.pdf');
                Route::post('kardex/excel', 'ReportKardexController@excel')->name('reports.kardex.report_excel');
            });
 

            Route::prefix('inventories')->group(function () {
                
                Route::get('configuration', 'InventoryConfigurationController@index')->name('tenant.inventories.configuration.index');
                Route::get('configuration/record', 'InventoryConfigurationController@record');
                Route::post('configuration', 'InventoryConfigurationController@store');

            });

        });
    });
}