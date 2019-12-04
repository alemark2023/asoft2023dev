<?php

 
$current_hostname = app(Hyn\Tenancy\Contracts\CurrentHostname::class);

if($current_hostname) {
    Route::domain($current_hostname->fqdn)->group(function () {
        Route::middleware(['auth', 'locked.tenant'])->group(function () {
            
            Route::prefix('pos')->group(function() {

                Route::get('history-sales/records', 'HistoryController@recordsSales');
                Route::get('history-purchases/records', 'HistoryController@recordsPurchases');

            }); 

        });
    });
}
