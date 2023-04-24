<?php

//Route::get('generate_token', 'ServiceController@dispatch');
//Route::get('generate_token', 'ServiceDispatchController@generateToken');
//Route::get('status_ticket/{ticket}', 'ServiceDispatchController@statusTicket');

use Illuminate\Support\Facades\Route;

$current_hostname = app(Hyn\Tenancy\Contracts\CurrentHostname::class);

if($current_hostname) {
    Route::domain($current_hostname->fqdn)->group(function () {
        Route::middleware(['auth:api'])->group(function () {
            Route::prefix('service')->group(function () {
                Route::get('{type}/{number}', 'ServiceController@service');
            });
        });
    });
}
