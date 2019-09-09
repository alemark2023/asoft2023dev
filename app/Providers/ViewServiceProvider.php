<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer(
            'tenant.layouts.partials.header',
            'App\Http\ViewComposers\Tenant\CompanyViewComposer'
        );

        view()->composer(
            'tenant.layouts.partials.header',
            'App\Http\ViewComposers\Tenant\UserViewComposer'
        );

        view()->composer(
            'tenant.layouts.partials.sidebar',
            'App\Http\ViewComposers\Tenant\ModuleViewComposer'
        );

        view()->composer(
            'tenant.layouts.partials.header',
            'Modules\Document\Http\ViewComposers\DocumentViewComposer'
        );
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}