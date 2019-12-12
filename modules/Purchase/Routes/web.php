<?php

$current_hostname = app(Hyn\Tenancy\Contracts\CurrentHostname::class);

if($current_hostname) {
    Route::domain($current_hostname->fqdn)->group(function () {
        Route::middleware(['auth', 'locked.tenant'])->group(function () {

            // Route::redirect('/', '/dashboard');

            Route::prefix('purchase-quotations')->group(function () {

                Route::get('', 'PurchaseQuotationController@index')->name('tenant.purchase-quotations.index');
                Route::get('columns', 'PurchaseQuotationController@columns');
                Route::get('records', 'PurchaseQuotationController@records');
                Route::get('create/{id?}', 'PurchaseQuotationController@create')->name('tenant.purchase-quotations.create');
                Route::get('tables', 'PurchaseQuotationController@tables');
                Route::get('table/{table}', 'PurchaseQuotationController@table');
                Route::post('', 'PurchaseQuotationController@store');
                Route::get('record/{expense}', 'PurchaseQuotationController@record');
                Route::get('item/tables', 'PurchaseQuotationController@item_tables');
                Route::get('download/{external_id}/{format?}', 'PurchaseQuotationController@download');
                Route::get('print/{external_id}/{format?}', 'PurchaseQuotationController@toPrint');
            });

            Route::prefix('purchase-orders')->group(function () {

                Route::get('', 'PurchaseOrderController@index')->name('tenant.purchase-orders.index');
                Route::get('columns', 'PurchaseOrderController@columns');
                Route::get('records', 'PurchaseOrderController@records');
                Route::get('create/{id?}', 'PurchaseOrderController@create')->name('tenant.purchase-orders.create');
                Route::get('generate/{id}', 'PurchaseOrderController@generate')->name('tenant.purchase-orders.generate');
                Route::get('tables', 'PurchaseOrderController@tables');
                Route::get('table/{table}', 'PurchaseOrderController@table');
                Route::post('', 'PurchaseOrderController@store');
                Route::get('record/{expense}', 'PurchaseOrderController@record');
                Route::get('item/tables', 'PurchaseOrderController@item_tables');
                Route::get('download/{external_id}/{format?}', 'PurchaseOrderController@download');
                Route::get('print/{external_id}/{format?}', 'PurchaseOrderController@toPrint');
            });

        });
    });
}
