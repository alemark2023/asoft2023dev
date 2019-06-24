<?php

namespace App\Http\Resources\Tenant;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PerceptionCollection extends ResourceCollection
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
                'date_of_issue' => $row->date_of_issue->format('Y-m-d'),
                'customer_name' => $row->customer->name,
                'customer_number' => $row->customer->number,
                'number' => $row->number,
                'document_type_description' => $row->document_type->description,
                'state_type_description' => $row->state_type->description,
                'total_perception' => $row->total_perception,
                'total' => $row->total,
                'created_at' => $row->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $row->updated_at->format('Y-m-d H:i:s'),
            ];
        });
    }
}