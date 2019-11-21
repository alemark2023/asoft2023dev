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
            'tenant.layouts.partials.header',
            'Modules\Document\Http\ViewComposers\DocumentViewComposer'
        );

        view()->composer(
            'tenant.layouts.partials.header',
            'App\Http\ViewComposers\Tenant\ModuleViewComposer'
        );

        view()->composer(
            'tenant.layouts.partials.sidebar',
            'App\Http\ViewComposers\Tenant\CompanyViewComposer'
        );

        view()->composer(
            'tenant.layouts.partials.sidebar',
            'App\Http\ViewComposers\Tenant\ModuleViewComposer'
        );

        view()->composer(
            'tenant.layouts.partials.sidebar',
            'Modules\BusinessTurn\Http\ViewComposers\BusinessTurnViewComposer'
        );

        //Ecommerce

        view()->composer(
            'ecommerce::layouts.partials_ecommerce.header',
            'App\Http\ViewComposers\Tenant\CompanyViewComposer'
        );

       /* view()->composer(
            'tenant.layouts.partials_ecommerce.header_options',
            'App\Http\ViewComposers\Tenant\CompanyViewComposer'
        );*/

        view()->composer(
            'ecommerce::layouts.partials_ecommerce.featured_products',
            'Modules\Ecommerce\Http\ViewComposers\FeaturedProductsViewComposer'
        );
        view()->composer(
            'ecommerce::layouts.partials_ecommerce.featured_products_bottom',
            'Modules\Ecommerce\Http\ViewComposers\FeaturedProductsViewComposer'
        );
        view()->composer(
            'ecommerce::layouts.partials_ecommerce.widget_products',
            'Modules\Ecommerce\Http\ViewComposers\FeaturedProductsViewComposer'
        );
        view()->composer(
            'ecommerce::layouts.partials_ecommerce.list_products',
            'Modules\Ecommerce\Http\ViewComposers\FeaturedProductsViewComposer'
        );
        view()->composer(
            'ecommerce::layouts.partials_ecommerce.sidemenu',
            'Modules\Ecommerce\Http\ViewComposers\MenuViewComposer'
        );
        view()->composer(
            'ecommerce::layouts.partials_ecommerce.header_bottom_sticky',
            'Modules\Ecommerce\Http\ViewComposers\MenuViewComposer'
        );
        view()->composer(
            'ecommerce::layouts.partials_ecommerce.home_slider',
            'Modules\Ecommerce\Http\ViewComposers\PromotionsViewComposer'
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
