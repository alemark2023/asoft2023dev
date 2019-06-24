<?php

namespace App\Http\Resources\Tenant;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ItemCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function toArray($request)
    {
        return $this->collection->transform(function($row, $key) {
            return [
                'id' => $row->id,
                'unit_type_id' => $row->unit_type_id,
                'description' => $row->description,
                'internal_id' => $row->internal_id,
                'item_code' => $row->item_code,
                'item_code_gs1' => $row->item_code_gs1,
                'stock' => $row->getStockByWarehouse(),
                'stock_min' => $row->stock_min,
                'calculate_quantity' => (bool) $row->calculate_quantity,
                'has_igv' => (bool) $row->has_igv,
                'sale_unit_price' => "{$row->currency_type->symbol} {$row->sale_unit_price}",
                'purchase_unit_price' => "{$row->currency_type->symbol} {$row->purchase_unit_price}",
                'created_at' => $row->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $row->updated_at->format('Y-m-d H:i:s'),
                'warehouses' => collect($row->warehouses)->transform(function($row) {
                    return [
                        'warehouse_description' => $row->warehouse->description,
                        'stock' => $row->stock,
                    ];
                })
            ];
        });
    }
}