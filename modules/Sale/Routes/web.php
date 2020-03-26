<?php


$current_hostname = app(Hyn\Tenancy\Contracts\CurrentHostname::class);

if($current_hostname) {
    Route::domain($current_hostname->fqdn)->group(function () {
        Route::middleware(['auth', 'locked.tenant'])->group(function () {

            Route::prefix('sale-opportunities')->group(function() {

                Route::get('', 'SaleOpportunityController@index')->name('tenant.sale_opportunities.index');
                Route::post('', 'SaleOpportunityController@store');

                Route::get('columns', 'SaleOpportunityController@columns');
                Route::get('records', 'SaleOpportunityController@records');
                Route::get('record/{id}', 'SaleOpportunityController@record');
                Route::get('create/{id?}', 'SaleOpportunityController@create')->name('tenant.sale_opportunities.create');
                // Route::get('edit/{id}', 'SaleOpportunityController@edit');
                Route::get('search/customers', 'SaleOpportunityController@searchCustomers');
                Route::get('search/customer/{id}', 'SaleOpportunityController@searchCustomerById');

                Route::get('tables', 'SaleOpportunityController@tables');
                Route::get('table/{table}', 'SaleOpportunityController@table');
                Route::get('item/tables', 'SaleOpportunityController@item_tables');
                Route::post('email', 'SaleOpportunityController@email');

                Route::get('download/{external_id}/{format?}', 'SaleOpportunityController@download');
                Route::get('print/{external_id}/{format?}', 'SaleOpportunityController@toPrint');
                
                Route::post('uploads', 'SaleOpportunityFileController@uploadFile');

            });
 
        });
    });
}
