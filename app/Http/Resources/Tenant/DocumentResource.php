<?php

namespace App\Http\Resources\Tenant;

use Illuminate\Http\Resources\Json\JsonResource;

class DocumentResource extends JsonResource
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
            'external_id' => $this->external_id,
            'group_id' => $this->group_id,
            'number' => $this->number_full,
            'date_of_issue' => $this->date_of_issue->format('Y-m-d'),
            'customer_email' => $this->customer->email,
            'download_pdf' => $this->download_external_pdf,
            'print_ticket' => url('')."/print/document/{$this->external_id}/ticket",
            'print_a4' => url('')."/print/document/{$this->external_id}/a4",
            'print_a5' => url('')."/print/document/{$this->external_id}/a5",
        ];
    }
}