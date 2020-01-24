<?php

$hostname = app(Hyn\Tenancy\Contracts\CurrentHostname::class);

if($hostname) {
    Route::domain($hostname->fqdn)->group(function () {
        Route::middleware(['auth', 'redirect.module', 'locked.tenant'])->group(function() {
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
                Route::get('tables/transaction/{type}', 'InventoryController@tables_transaction');
                Route::get('record/{inventory}', 'InventoryController@record');
                Route::post('/', 'InventoryController@store');
                Route::post('/transaction', 'InventoryController@store_transaction');
                Route::post('move', 'InventoryController@move');

                Route::get('moves', 'MovesController@index')->name('inventory.moves.index');

                Route::post('remove', 'InventoryController@remove');
                Route::get('initialize', 'InventoryController@initialize');
            });

            Route::prefix('reports')->group(function () {
                Route::get('inventory', 'ReportInventoryController@index')->name('reports.inventory.index');
                Route::post('inventory/search', 'ReportInventoryController@search')->name('reports.inventory.search');
                Route::post('inventory/pdf', 'ReportInventoryController@pdf')->name('reports.inventory.pdf');
                Route::post('inventory/excel', 'ReportInventoryController@excel')->name('reports.inventory.report_excel');

                Route::get('kardex', 'ReportKardexController@index')->name('reports.kardex.index');
                Route::get('kardex/search', 'ReportKardexController@search')->name('reports.kardex.search');
                Route::post('kardex/pdf', 'ReportKardexController@pdf')->name('reports.kardex.pdf');
                Route::post('kardex/excel', 'ReportKardexController@excel')->name('reports.kardex.report_excel');
            });


            Route::prefix('inventories')->group(function () {

                Route::get('configuration', 'InventoryConfigurationController@index')->name('tenant.inventories.configuration.index');
                Route::get('configuration/record', 'InventoryConfigurationController@record');
                Route::post('configuration', 'InventoryConfigurationController@store');
            });

            Route::prefix('moves')->group(function () {

                Route::get('/', 'MovesController@index')->name('moves.index');
                Route::get('records', 'MovesController@records');
                Route::get('columns', 'MovesController@columns');

            });


            Route::prefix('transfers')->group(function () {
                Route::get('/', 'TransferController@index')->name('transfers.index');
                Route::get('records', 'TransferController@records');
                Route::get('columns', 'TransferController@columns');
                Route::get('tables', 'TransferController@tables');
                Route::get('record/{inventory}', 'TransferController@record');
                Route::post('/', 'TransferController@store');

                Route::delete('{inventory}', 'TransferController@destroy');

                Route::get('create', 'TransferController@create')->name('transfer.create');

                Route::get('stock/{item_id}/{warehouse_id}', 'TransferController@stock');

                Route::get('items/{warehouse_id}', 'TransferController@items');


            });

        });
    });
}
