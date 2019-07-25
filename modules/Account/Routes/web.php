<?php

$hostname = app(Hyn\Tenancy\Contracts\CurrentHostname::class);

if($hostname) {
    Route::domain($hostname->fqdn)->group(function () {
        Route::middleware('auth')->group(function() {

            Route::prefix('account')->group(function () {
                Route::get('/', 'AccountController@index')->name('tenant.account.index');
                Route::get('download', 'AccountController@download');
                Route::get('format', 'FormatController@index')->name('tenant.account_format.index');
                Route::get('format/download', 'FormatController@download');
            });

        });
    });
}