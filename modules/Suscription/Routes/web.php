<?php


    use Illuminate\Support\Facades\Route;

    $current_hostname = app(Hyn\Tenancy\Contracts\CurrentHostname::class);

    if ($current_hostname) {
        Route::domain($current_hostname->fqdn)->group(function () {
            Route::middleware(['auth', 'locked.tenant'])
                ->prefix('suscription')
                ->group(function () {
                    /**
                     * suscription/client
                     */
                    Route::prefix('client')->group(function () {
                        Route::get('/', 'SuscriptionController@clients_index')
                            ->name('tenant.suscription.client.index')
                            ->middleware(['redirect.level']);

                        Route::get('/columns', 'SuscriptionController@clientColumns');
                        Route::post('/records', 'SuscriptionController@clientRecords');
                        Route::post('/tables', 'SuscriptionController@clientTables');
                        Route::post('/record', 'SuscriptionController@clientRecord');


                    });
                    /**
                     * suscription/service
                     */
                    Route::prefix('service')->group(function () {
                        Route::get('/', 'SuscriptionController@services_index')
                            ->name('tenant.suscription.service.index')
                            ->middleware(['redirect.level']);


                        Route::get('/columns', 'SuscriptionController@serviceColumns');
                        Route::post('/records', 'SuscriptionController@serviceRecords');
                        Route::post('/tables', 'SuscriptionController@serviceTables');
                        Route::post('/record', 'SuscriptionController@serviceRecord');
                    });
                    /**
                     * suscription/payments
                     */
                    Route::prefix('payments')->group(function () {
                        Route::get('/', 'SuscriptionController@payments_index')
                            ->name('tenant.suscription.payments.index')
                            ->middleware(['redirect.level']);
                    });
                    /**
                     * suscription/plans
                     */
                    Route::prefix('plans')->group(function () {
                        Route::get('/', 'SuscriptionController@plans_index')
                            ->name('tenant.suscription.plans.index')
                            ->middleware(['redirect.level']);
                    });
                });
        });
    }
