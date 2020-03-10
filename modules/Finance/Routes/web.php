<?php

$hostname = app(Hyn\Tenancy\Contracts\CurrentHostname::class);

if($hostname) {
    Route::domain($hostname->fqdn)->group(function () {
        Route::middleware(['auth'])->group(function() {

 
            Route::prefix('finances')->group(function () {

                Route::get('records', 'FinanceController@records'); 

                Route::get('global-payments', 'GlobalPaymentController@index')->name('tenant.finances.global_payments.index');
                Route::get('global-payments/pdf', 'GlobalPaymentController@pdf');
                Route::get('global-payments/excel', 'GlobalPaymentController@excel');
                Route::get('global-payments/filter', 'GlobalPaymentController@filter');
                Route::get('global-payments/records', 'GlobalPaymentController@records');
            });


        });
    });
}
