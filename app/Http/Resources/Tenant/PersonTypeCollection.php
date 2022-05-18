<?php

namespace App\Http\Resources\Tenant;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PersonTypeCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function toArray($request)
    {
        $name='hola';
            
        return $this->collection->transform(function($row, $key) {
            
            $name=isset($row->item_price_type[0])? $row->item_price_type[0]['name'] : '' ;
            return [
                'id' => $row->id,
                'description' => $row->description,
                'price_name' => $name,
                'created_at' => $row->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $row->updated_at->format('Y-m-d H:i:s'),
            ];
        });
    }
}