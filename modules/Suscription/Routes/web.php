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
                    });
                    /**
                     * suscription/service
                     */
                    Route::prefix('service')->group(function () {
                        Route::get('/', 'SuscriptionController@services_index')
                            ->name('tenant.suscription.service.index')
                            ->middleware(['redirect.level']);
                    });
                    /**
                     * suscription/products
                     */
                    Route::prefix('products')->group(function () {
                        Route::get('/', 'SuscriptionController@products_index')
                            ->name('tenant.suscription.products.index')
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
