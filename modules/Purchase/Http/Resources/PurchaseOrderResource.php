<?php

namespace Modules\Purchase\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Purchase\Models\PurchaseOrder; 

class PurchaseOrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $purchase_order = PurchaseOrder::with(['items'])->find($this->id);

        return [
            'id' => $this->id,
            'external_id' => $this->external_id,  
            'number_full' => $this->number_full,
            'date_of_issue' => $this->date_of_issue->format('Y-m-d'), 
            'purchase_order' => $purchase_order
        ];
    }
}
