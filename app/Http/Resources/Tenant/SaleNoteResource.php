<?php

namespace App\Http\Resources\Tenant;
 
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Tenant\SaleNote;

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

        $sale_note = SaleNote::find($this->id);

        return [
            'id' => $this->id,
            'external_id' => $this->external_id, 
            'number' => $this->number_full,
            'identifier' => $this->identifier,
            'date_of_issue' => $this->date_of_issue->format('Y-m-d'), 
            'print_ticket' => url('')."/sale-notes/print/{$this->external_id}/ticket",
            'print_a4' => url('')."/sale-notes/print/{$this->external_id}/a4",
            'print_a5' => url('')."/sale-notes/print/{$this->external_id}/a5",
            // 'print_a5' => url('')."/sale-notes/print-a5/{$this->id}/a5",
            'sale_note' => $sale_note
        ];
    }
}
