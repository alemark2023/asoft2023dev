<?php

namespace App\Http\Resources\Tenant;
 
use Illuminate\Http\Resources\Json\JsonResource;

class SaleNoteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    { 

        return [
            'id' => $this->id,
            'external_id' => $this->external_id, 
            'identifier' => $this->identifier,
            'date_of_issue' => $this->date_of_issue->format('Y-m-d'), 
        ];
    }
}
