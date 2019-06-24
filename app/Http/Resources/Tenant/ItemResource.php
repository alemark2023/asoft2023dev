<?php

namespace App\Http\Resources\Tenant;

use Illuminate\Http\Resources\Json\JsonResource;

class ItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'description' => $this->description,
            'internal_id' => $this->internal_id,
            'item_code' => $this->item_code,
            'item_code_gsl' => $this->item_code_gsl,
            'currency_type_id' => $this->currency_type_id,
            'sale_unit_price' => $this->sale_unit_price,
            'purchase_unit_price' => $this->purchase_unit_price,
            'unit_type_id' => $this->unit_type_id,
            'has_isc' => (bool) $this->has_isc,
            'system_isc_type_id' => $this->system_isc_type_id,
            'percentage_isc' => $this->percentage_isc,
            'suggested_price' => $this->suggested_price,
            'stock' => $this->getStockByWarehouse(),
            'stock_min' => $this->stock_min,
            'percentage_of_profit' => $this->percentage_of_profit,
            'sale_affectation_igv_type_id' => $this->sale_affectation_igv_type_id,
            'purchase_affectation_igv_type_id' => $this->purchase_affectation_igv_type_id,
            'calculate_quantity' => (bool) $this->calculate_quantity,
            'has_igv' => (bool) $this->has_igv,
            'item_unit_types' => $this->item_unit_types
            // 'warehouses' => collect($this->warehouses)->transform(function($row) {
            //     return [
            //         'warehouse_description' => $row->warehouse->description,
            //         'stock' => $row->stock,
            //     ];
            // })
        ];
    }
}