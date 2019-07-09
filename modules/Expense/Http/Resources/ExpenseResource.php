<?php

namespace Modules\Expense\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ExpenseResource extends JsonResource
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
            'number' => $this->number,
            'date_of_issue' => $this->date_of_issue->format('Y-m-d'), 
        ];
    }
}
