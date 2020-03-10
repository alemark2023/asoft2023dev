<?php

namespace Modules\Finance\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class GlobalPaymentCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->collection->transform(function($row, $key) {
 
            return [
                'id' => $row->id, 
                'destination_description' => $row->destination_description, 
            ];
        });
    }

}
