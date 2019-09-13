<?php

namespace Modules\Dashboard\Helpers;

use Modules\Inventory\Models\ItemWarehouse;
use Modules\Dashboard\Http\Resources\DashboardStockCollection;

class DashboardStock
{

    public function data($request)
    { 
        return $this->stock_by_products($request);
    }
    
    private function stock_by_products($request)
    {
        $products = new DashboardStockCollection(ItemWarehouse::where('stock','<=', 20)->orderBy('stock')->paginate(5));
                 
        return $products;
    }
 
}