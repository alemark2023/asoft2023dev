<?php

namespace App\Http\Resources\Tenant;

use App\Models\Tenant\Quotation;
use Illuminate\Http\Resources\Json\JsonResource;

class QuotationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $quotation = Quotation::find($this->id);

        return [
            'id' => $this->id,
            'external_id' => $this->external_id,  
            'identifier' => $this->identifier,
            'date_of_issue' => $this->date_of_issue->format('Y-m-d'), 
            'quotation' => $quotation
        ];
    }
}
