<?php

use Illuminate\Support\Facades\Route;

$current_hostname = app(Hyn\Tenancy\Contracts\CurrentHostname::class);

if ($current_hostname) {
    Route::domain($current_hostname->fqdn)->group(function () {
        Route::middleware(['auth', 'locked.tenant'])->group(function () {
            Route::prefix('salud')->group(function () {
                Route::get('/', 'SaludController@index')->name('tenant.salud.index');
                Route::get('/specialty', 'SaludController@specialty')->name('tenant.salud.specialty');
                Route::post('', 'SaludController@store');
                Route::get('/records', 'SaludController@records');
                Route::get('/record/{specialty}', 'SaludController@record');
                Route::get('/{type}', 'SaludController@patient')->name('tenant.patients.patient');
                Route::get('/enabled/{type}/{specialty}', 'SaludController@enabled');
            });
        });
    });
}

// Route::prefix('salud')->group(function() {
//     Route::get('/', 'SaludController@index');
// });
