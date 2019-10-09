<?php

 
$current_hostname = app(Hyn\Tenancy\Contracts\CurrentHostname::class);

if($current_hostname) {
    Route::domain($current_hostname->fqdn)->group(function () {
        Route::middleware(['auth', 'locked.tenant'])->group(function () {
            
            Route::prefix('documents/not-sent')->group(function() {
                Route::get('', 'DocumentController@index')->name('tenant.documents.not_sent');
                Route::get('records', 'DocumentController@records');
                Route::get('data_table', 'DocumentController@data_table');

            });

        });
    });
}
