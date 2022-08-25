<?php

$hostname = app(Hyn\Tenancy\Contracts\CurrentHostname::class);

if($hostname) {
    Route::domain($hostname->fqdn)->group(function () {
        Route::middleware(['auth', 'locked.tenant'])->group(function() {

            Route::prefix('system-activity-logs')->group(function () {

                Route::prefix('access')->group(function () {

                    Route::get('', 'SystemActivityLogAccessController@index')->name('tenant.system_activity_logs.access.index');
                    Route::get('records', 'SystemActivityLogAccessController@records');
                    Route::get('columns', 'SystemActivityLogAccessController@columns');
                });

            });

        });
    });
}
