<?php

$hostname = app(Hyn\Tenancy\Contracts\CurrentHostname::class);

if($hostname) {
    Route::domain($hostname->fqdn)->group(function () {
        Route::middleware(['auth', 'locked.tenant'])->group(function() {

            Route::prefix('system-activity-logs')->group(function () {

                Route::prefix('generals')->group(function () {

                    Route::get('', 'SystemActivityLogGeneralController@index')->name('tenant.system_activity_logs.generals.index');
                    Route::get('records', 'SystemActivityLogGeneralController@records');
                    Route::get('columns', 'SystemActivityLogGeneralController@columns');
                });

                Route::prefix('transactions')->group(function () {

                    Route::get('', 'SystemActivityLogTransactionController@index')->name('tenant.system_activity_logs.transactions.index');
                    Route::get('records', 'SystemActivityLogTransactionController@records');
                    Route::get('columns', 'SystemActivityLogTransactionController@columns');
                });
            });

        });
    });
}
