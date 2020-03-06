<?php

$hostname = app(Hyn\Tenancy\Contracts\CurrentHostname::class);

if($hostname) {
    Route::domain($hostname->fqdn)->group(function () {
        Route::middleware(['auth'])->group(function() {

 
            Route::prefix('finances')->group(function () {

                Route::get('records', 'FinanceController@records'); 

            });


        });
    });
}
