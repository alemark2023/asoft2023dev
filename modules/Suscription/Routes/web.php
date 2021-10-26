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
                        Route::get('/', 'ClientSuscriptionController@clients_index')
                            ->name('tenant.suscription.client.index')
                            ->middleware(['redirect.level']);

                        Route::get('/columns', 'ClientSuscriptionController@clientColumns');
                        Route::post('/records', 'ClientSuscriptionController@clientRecords');
                        Route::post('/tables', 'ClientSuscriptionController@clientTables');
                        Route::post('/record', 'ClientSuscriptionController@clientRecord');


                    });
                    /**
                     * suscription/service
                     */
                    Route::prefix('service')->group(function () {
                        Route::get('/', 'ServiceSuscriptionController@services_index')
                            ->name('tenant.suscription.service.index')
                            ->middleware(['redirect.level']);


                        Route::get('/columns', 'ServiceSuscriptionController@serviceColumns');
                        Route::post('/records', 'ServiceSuscriptionController@serviceRecords');
                        Route::post('/tables', 'ServiceSuscriptionController@serviceTables');
                        Route::post('/record', 'ServiceSuscriptionController@serviceRecord');
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
