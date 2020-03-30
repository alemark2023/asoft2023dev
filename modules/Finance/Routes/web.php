<?php

$hostname = app(Hyn\Tenancy\Contracts\CurrentHostname::class);

if($hostname) {
    Route::domain($hostname->fqdn)->group(function () {
        Route::middleware(['auth', 'redirect.module', 'locked.tenant'])->group(function() {

 
            Route::prefix('finances')->group(function () {

                Route::get('global-payments', 'GlobalPaymentController@index')->name('tenant.finances.global_payments.index');
                Route::get('global-payments/pdf', 'GlobalPaymentController@pdf');
                Route::get('global-payments/excel', 'GlobalPaymentController@excel');
                Route::get('global-payments/filter', 'GlobalPaymentController@filter');
                Route::get('global-payments/records', 'GlobalPaymentController@records');

                
                Route::get('balance', 'BalanceController@index')->name('tenant.finances.balance.index');
                Route::get('balance/pdf', 'BalanceController@pdf');
                Route::get('balance/excel', 'BalanceController@excel');
                Route::get('balance/filter', 'BalanceController@filter');
                Route::get('balance/records', 'BalanceController@records');
                
                Route::get('payment-method-types', 'PaymentMethodTypeController@index')->name('tenant.finances.payment_method_types.index');
                Route::get('payment-method-types/pdf', 'PaymentMethodTypeController@pdf');
                Route::get('payment-method-types/excel', 'PaymentMethodTypeController@excel');
                Route::get('payment-method-types/filter', 'PaymentMethodTypeController@filter');
                Route::get('payment-method-types/records', 'PaymentMethodTypeController@records');

                Route::get('unpaid', 'UnpaidController@index')->name('tenant.finances.unpaid.index');
                // Route::post('unpaid', 'UnpaidController@unpaid');
                Route::get('unpaid/filter', 'UnpaidController@filter');
                Route::post('unpaid/records', 'UnpaidController@records');
                Route::get('unpaid/unpaidall', 'UnpaidController@unpaidall')->name('unpaidall');

            });


        });
    });
}
