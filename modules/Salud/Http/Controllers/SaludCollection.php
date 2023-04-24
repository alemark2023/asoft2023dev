<?php

namespace Modules\Salud\Http\Controllers;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Modules\Salud\Models\Especialidad;

class SaludCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function toArray($request)
    {
        return $this->collection->transform(function(Especialidad $row, $key) {

            // return  $row->getCollectionData(true);
            /** Pasado al modelo  */
            return [
                'id' => $row->id,
                'name' => $row->name,
                'description' => $row->description,
                'enabled' => (bool) $row->enabled,
                'created_at' => $row->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $row->updated_at->format('Y-m-d H:i:s'),
            ];
        });
    }
}
