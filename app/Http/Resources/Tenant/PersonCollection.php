<?php

namespace App\Http\Resources\Tenant;

use App\Models\Tenant\Person;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PersonCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function toArray($request)
    {
        return $this->collection->transform(function(Person $row, $key) {

            return  $row->getCollectionData();
            /** Pasado al modelo  */
            return [
                'id' => $row->id,
                'number' => $row->number,
                'name' => $row->name,
                'internal_code' => $row->internal_code,
                'document_type' => $row->identity_document_type->description,
                'enabled' => (bool) $row->enabled,
                'created_at' => $row->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $row->updated_at->format('Y-m-d H:i:s'),
            ];
        });
    }
}
