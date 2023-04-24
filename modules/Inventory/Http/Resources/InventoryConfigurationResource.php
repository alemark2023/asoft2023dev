<?php

namespace Modules\Inventory\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InventoryConfigurationResource extends JsonResource
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
            'stock_control' => (bool) $this->stock_control,             
            'generate_internal_id' => (bool) $this->generate_internal_id,
            'inventory_review' => $this->inventory_review,
            'validate_stock_add_item' => $this->validate_stock_add_item,
        ];
    }
}