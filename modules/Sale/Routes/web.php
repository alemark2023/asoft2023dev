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
                Route::get('download-file/{filename}', 'SaleOpportunityFileController@download');

            });
 
            Route::prefix('payment-method-types')->group(function () {

                Route::get('/records', 'PaymentMethodTypeController@records');
                Route::get('/record/{id}', 'PaymentMethodTypeController@record');
                Route::post('', 'PaymentMethodTypeController@store');
                Route::delete('/{id}', 'PaymentMethodTypeController@destroy');

            });


            Route::prefix('contracts')->group(function () {

                Route::get('', 'ContractController@index')->name('tenant.contracts.index');
                Route::get('/columns', 'ContractController@columns');
                Route::get('/records', 'ContractController@records');
                Route::get('/create/{id?}', 'ContractController@create')->name('tenant.contracts.create');
    
                Route::get('/state-type/{state_type_id}/{id}', 'ContractController@updateStateType');
                Route::get('/filter', 'ContractController@filter');
                Route::get('/tables', 'ContractController@tables');
                Route::get('/table/{table}', 'ContractController@table');
                Route::post('', 'ContractController@store');
                Route::get('/record/{contract}', 'ContractController@record');
                Route::get('/voided/{id}', 'ContractController@voided');
                Route::get('/item/tables', 'ContractController@item_tables');
                Route::get('/option/tables', 'ContractController@option_tables');
                Route::get('/search/customers', 'ContractController@searchCustomers');
                Route::get('/search/customer/{id}', 'ContractController@searchCustomerById');
                Route::get('/download/{external_id}/{format?}', 'ContractController@download');
                Route::get('/print/{external_id}/{format?}', 'ContractController@toPrint');
                Route::post('/email', 'ContractController@email');
                Route::get('/record2/{contract}', 'ContractController@record2');
                Route::get('/changed/{contract}', 'ContractController@changed');
                Route::get('/generate-quotation/{quotation}', 'ContractController@generateContract');

            });

        });

        
        Route::prefix('production-orders')->group(function () {

            Route::get('', 'ProductionOrderController@index')->name('tenant.production_orders.index');
            Route::get('/columns', 'ProductionOrderController@columns');
            Route::get('/records', 'ProductionOrderController@records');

        });

    });
}
