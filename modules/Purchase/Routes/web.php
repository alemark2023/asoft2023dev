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

        });
    });
}
