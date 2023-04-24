<?php

namespace Modules\Dispatch\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class DispatcherCollection extends ResourceCollection
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
                'number' => $row->number,
                'name' => $row->name,
                'document_type' => $row->identity_document_type->description,
                'number_mtc' => $row->number_mtc,
                'is_default' => $row->is_default?'SI':'',
                'is_active' => $row->is_active,
                'created_at' => $row->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $row->updated_at->format('Y-m-d H:i:s'),
            ];
        });
    }
}
