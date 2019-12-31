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
        $products = ItemWarehouse::whereHas('item',function($query){

                        $query->whereNotIsSet();
                        $query->where('status',true);
                        $query->where('unit_type_id','!=', 'ZZ');
                        
                    })
                    ->where('stock','<=', 20)
                    ->orderBy('stock')
                    ->paginate(config('tenant.items_per_page_simple_d_table'));
                            
        return new DashboardStockCollection($products);
    }
 
}