<?php

namespace Modules\Order\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DispatchResource extends JsonResource
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
            'number' => $this->number_full,
            'date_of_issue' => $this->date_of_issue->format('Y-m-d'),
            'customer_email' => $this->customer->email,
            'customer_telephone' => optional($this->person)->telephone,
            'message_text' => "Su guía {$this->number_full} ha sido generada correctamente, puede revisarla en el siguiente enlace: ".url('')."/downloads/dispatch/pdf/{$this->external_id}".""
        ];
    }
}