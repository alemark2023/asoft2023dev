<?php

namespace App\Http\Resources\Tenant;

use App\Models\Tenant\Configuration;
use App\Models\Tenant\ItemWarehousePrice;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PosCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function toArray($request)
    {
        $establishment_id = 0;
        if(null !== auth()->user()) {
            $establishment_id = auth()->user()->establishment_id;
        }
        return $this->collection->transform(function ($row, $key) use($establishment_id) {
            $configuration = Configuration::first();
            $real_price = $row->sale_unit_price;

            $item_warehouse_price = ItemWarehousePrice::GetItemByWarehouseAndId ($establishment_id,$row->id);
            if (!empty($item_warehouse_price) && !empty($item_warehouse_price->price)) {
                $real_price = $item_warehouse_price->price;
            }
            $price = number_format($real_price, $configuration->decimal_quantity, '.', '');
            $edit_price = $price;
            $aux_price = $price;

            return [
                'stock' => $row->getStockByWarehouse(),
                'id' => $row->id,
                'item_id' => $row->id,
                'full_description' => ($row->internal_id) ? $row->internal_id . ' - ' . $row->description : $row->description,
                'name' => $row->name,
                'second_name' => $row->second_name,
                'description' => $row->description,
                'currency_type_id' => $row->currency_type_id,
                'internal_id' => $row->internal_id,
                'currency_type_symbol' => $row->currency_type->symbol,
                'sale_unit_price' => $price,
                'purchase_unit_price' => $row->purchase_unit_price,
                'unit_type_id' => $row->unit_type_id,
                'sale_affectation_igv_type_id' => $row->sale_affectation_igv_type_id,
                'purchase_affectation_igv_type_id' => $row->purchase_affectation_igv_type_id,
                'calculate_quantity' => (bool) $row->calculate_quantity,
                'has_igv' => (bool) $row->has_igv,
                'is_set' => (bool) $row->is_set,
                'edit_unit_price' => false,
                'aux_quantity' => 1,
                'edit_sale_unit_price' => $edit_price,
                'aux_sale_unit_price' => $aux_price,
                'image_url' => ($row->image !== 'imagen-no-disponible.jpg') ? asset('storage' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'items' . DIRECTORY_SEPARATOR . $row->image) : asset("/logo/{$row->image}"),
                'warehouses' => collect($row->warehouses)->transform(function ($row) {
                    return [
                        'warehouse_description' => $row->warehouse->description,
                        'stock' => $row->stock,
                    ];
                }),
                'category_id' => ($row->category) ? $row->category->id : null,
                'sets' => collect($row->sets)->transform(function ($r) {
                    return [
                        $r->individual_item->description,
                    ];
                }),
                'unit_type' => $row->item_unit_types,
                'category' => ($row->category) ? $row->category->name : null,
                'brand' => ($row->brand) ? $row->brand->name : null,
                'has_plastic_bag_taxes' => (bool) $row->has_plastic_bag_taxes,
                'amount_plastic_bag_taxes' => $row->amount_plastic_bag_taxes,
            ];
        });
    }
}
