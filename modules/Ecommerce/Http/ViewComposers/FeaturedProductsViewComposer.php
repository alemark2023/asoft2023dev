<?php

namespace Modules\Ecommerce\Http\ViewComposers;


use App\Models\Tenant\Item;
//use App\Http\Resources\Tenant\ItemEcommerceCollection;


class FeaturedProductsViewComposer
{
    public function compose($view)
    {
        $view->items = Item::where([['apply_store', 1], ['internal_id','!=', null]])->get()->transform(function($row, $key){
            return (object)[
                'id' => $row->id,
                'internal_id' => $row->internal_id,
                'unit_type_id' => $row->unit_type_id,
                'description' => $row->description,
                'name' => $row->name,
                'second_name' => $row->second_name,
                'sale_unit_price' => $row->sale_unit_price,
                'currency_type_id' => $row->currency_type_id,
                'has_igv' => (bool) $row->has_igv,
                'sale_affectation_igv_type_id' => $row->sale_affectation_igv_type_id,
                'currency_type_symbol' => $row->currency_type->symbol,
                'image' =>  $row->image,
                'image_medium' => $row->image_medium,
                'image_small' => $row->image_small,
                'tags' => $row->tags->pluck('tag_id')->toArray()
            ];
        });
    }
}