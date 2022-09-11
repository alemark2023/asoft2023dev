<?php

namespace Modules\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Inventory\Models\{
    Warehouse,
    ItemWarehouse,
};
use Modules\Item\Models\{
    Category,
};
use App\Models\Tenant\{
    Establishment,
    CatItemSize,
};
use App\Models\Tenant\Catalogs\{
    CatColorsItem
};
    


class InventoryReviewController extends Controller
{

    public function index()
    {
        return view('inventory::inventory-review.index');
    }


    public function filters()
    {
        $warehouses = Warehouse::getDataForFilters();
        $categories = Category::getDataForFilters();
        $item_sizes = CatItemSize::get();
        $item_colors = CatColorsItem::get();

        return compact('warehouses', 'categories', 'item_sizes', 'item_colors');
    }


    public function records(Request $request)
    {

        $records = ItemWarehouse::with([
                                    'item' => function($q){
                                        $q->whereFilterWithOutRelations();
                                    }
                                ])
                                ->whereHas('item', function ($query) use ($request) {

                                    $query->whereIsNotService()
                                            ->whereNotIsSet()
                                            ->whereIsActive()
                                            ;

                                    if($request->has('category_id') && $request->category_id)
                                    {
                                        $query->where('category_id', $request->category_id);
                                    }

                                    return $query;
                                })
                                ->where('warehouse_id', $request->warehouse_id)
                                ->orderBy('item_id')
                                ->get()
                                ->transform(function($row, $index){
                                    return [
                                        'index' => $index + 1,
                                        'id' => $row->id,
                                        'item_id' => $row->item_id,
                                        'item_description' => $row->item->description,
                                        'item_barcode' => $row->item->barcode,
                                        'stock' => $row->stock,
                                        'input_stock' => 0,
                                        'difference' => null,
                                    ];
                                });

        return [
            'data' => $records
        ];
        // $records = CatColorsItem::where('id', '!=', 0);
        // return $records->paginate(config('tenant.items_per_page'));
    }


}
