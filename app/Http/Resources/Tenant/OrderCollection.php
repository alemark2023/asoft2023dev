<?php

namespace App\Http\Resources\Tenant;

use Illuminate\Http\Resources\Json\ResourceCollection;

class OrderCollection extends ResourceCollection
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
                'external_id' => $row->external_id,
                'customer' => $row->customer->apellidos_y_nombres_o_razon_social,
                'customer_email' => $row->customer->correo_electronico,
                'items' => $row->items,
                'total' => $row->total,
                'reference_payment' => strtoupper($row->reference_payment),
                'document_external_id' => $row->document_external_id,
                'created_at' => $row->created_at->format('Y-m-d H:i:s'),
            ];
        });
    }
}