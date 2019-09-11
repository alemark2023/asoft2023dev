<?php

namespace App\Http\ViewComposers\Tenant\Ecommerce;

use App\Models\Tenant\Item;

class FeaturedProductsViewComposer
{
    public function compose($view)
    {
        $view->items = Item::where('apply_store', 1)->get();
    }
}