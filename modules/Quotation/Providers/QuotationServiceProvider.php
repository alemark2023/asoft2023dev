<?php

namespace Modules\Quotation\Providers;

use App\Models\Tenant\Quotation;
use Illuminate\Support\ServiceProvider;
use Modules\Quotation\Observers\QuotationObserver;

class QuotationServiceProvider extends ServiceProvider
{
    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        Quotation::observe(QuotationObserver::class);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
