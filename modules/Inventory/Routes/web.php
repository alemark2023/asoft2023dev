<?php

use Illuminate\Support\Facades\Route;

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
                /**
                 * inventory/
                 * inventory/records
                 * inventory/columns
                 * inventory/tables
                 * inventory/tables/transaction/{type}
                 * inventory/record/{inventory}
                 * inventory/
                 * inventory/transaction
                 * inventory/move
                 * inventory/move-multilple
                 */
                Route::get('/', 'InventoryController@index')->name('inventory.index');
                Route::get('records', 'InventoryController@records');
                Route::get('columns', 'InventoryController@columns');
                Route::get('tables', 'InventoryController@tables');
                Route::get('tables/transaction/{type}', 'InventoryController@tables_transaction');
                Route::get('record/{inventory}', 'InventoryController@record');
                Route::post('/', 'InventoryController@store');
                Route::post('/transaction', 'InventoryController@store_transaction');
                Route::post('move', 'InventoryController@move');
                Route::post('move-multilple', 'InventoryController@moveMultiples');
                /**
                 * inventory/moves
                 * inventory/remove
                 * inventory/initialize
                 * inventory/regularize_stock
                 * inventory/search_items
                 */
                Route::get('moves', 'MovesController@index')->name('inventory.moves.index');

                Route::post('remove', 'InventoryController@remove');
                Route::get('initialize', 'InventoryController@initialize');
                Route::get('regularize_stock', 'InventoryController@regularize_stock');

                Route::post('search_items', 'InventoryController@searchItems');
                /*
                 * inventory/report/tables
                 * inventory/report/records
                 * inventory/report/export
                 */
                Route::prefix('report')->group(function () {
                    Route::get('tables', 'ReportInventoryController@tables');
                    Route::post('records', 'ReportInventoryController@records');
                    Route::post('export', 'ReportInventoryController@export');
                });

            });

            Route::prefix('reports')->group(function () {
                Route::get('inventory', 'ReportInventoryController@index')->name('reports.inventory.index');
                Route::post('inventory/search', 'ReportInventoryController@search')->name('reports.inventory.search');
                Route::post('inventory/pdf', 'ReportInventoryController@pdf')->name('reports.inventory.pdf');
                Route::post('inventory/excel', 'ReportInventoryController@excel')->name('reports.inventory.report_excel');

                // Route::get('kardex', 'ReportKardexController@index')->name('reports.kardex.index');
                // Route::get('kardex/search', 'ReportKardexController@search')->name('reports.kardex.search');
                // Route::post('kardex/pdf', 'ReportKardexController@pdf')->name('reports.kardex.pdf');
                // Route::post('kardex/excel', 'ReportKardexController@excel')->name('reports.kardex.report_excel');


                /**
                 * reports/kardex/
                 * reports/kardex/pdf
                 * reports/kardex/excel
                 * reports/kardex/filter
                 * reports/kardex/records
                 * reports/kardex/lots/filter
                 **/
                Route::prefix('kardex')->group(function () {
                    Route::get('', 'ReportKardexController@index')->name('reports.kardex.index');
                    Route::get('/pdf', 'ReportKardexController@pdf')->name('reports.kardex.pdf');
                    Route::get('/excel', 'ReportKardexController@excel')->name('reports.kardex.excel');
                    Route::get('/filter', 'ReportKardexController@filter')->name('reports.kardex.filter');
                    Route::get('/records', 'ReportKardexController@records')->name('reports.kardex.records');
                    Route::get('/lots/filter', 'ReportKardexController@records_lots');
                });
                Route::get('kardex_lots/filter', 'ReportKardexController@filter')->name('reports.kardex.filter');
                Route::get('kardex_series/filter', 'ReportKardexController@filter')->name('reports.kardex.filter');


                Route::get('kardex_lots/records', 'ReportKardexController@records_lots_kardex')->name('reports.kardex_lots.records');
                Route::get('kardex_lots/pdf', 'ReportKardexLotsController@pdf');
                Route::get('kardex_lots/excel', 'ReportKardexLotsController@excel');


                Route::get('kardex_series/records', 'ReportKardexController@records_series_kardex')->name('reports.kardex_series.records');
                Route::get('kardex_series/pdf', 'ReportKardexSeriesController@pdf');
                Route::get('kardex_series/excel', 'ReportKardexSeriesController@excel');

                /**
                 * reports/valued-kardex/
                 * reports/valued-kardex/excel
                 * reports/valued-kardex/filter
                 * reports/valued-kardex/records
                 **/
                Route::prefix('valued-kardex')->group(function () {
                    Route::get('', 'ReportValuedKardexController@index')->name('reports.valued_kardex.index');
                    Route::get('/excel', 'ReportValuedKardexController@excel');
                    Route::get('/filter', 'ReportValuedKardexController@filter');
                    Route::get('/records', 'ReportValuedKardexController@records');

                });
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


            Route::prefix('devolutions')->group(function () {

                Route::get('/', 'DevolutionController@index')->name('devolutions.index');
                Route::get('records', 'DevolutionController@records');
                Route::get('columns', 'DevolutionController@columns');
                Route::get('item/tables', 'DevolutionController@item_tables');
                Route::get('tables', 'DevolutionController@tables');
                Route::get('record/{id}', 'DevolutionController@record');
                Route::post('/', 'DevolutionController@store');
                Route::get('create', 'DevolutionController@create')->name('devolutions.create');
                Route::get('search-items', 'DevolutionController@searchItems');
                Route::get('download/{external_id}/{format?}', 'DevolutionController@download');

            });

        });
    });
}
