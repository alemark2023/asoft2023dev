<?php

namespace App\Http\Resources\Tenant;

use Illuminate\Http\Resources\Json\ResourceCollection;

class UserCollection extends ResourceCollection
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
                'email' => $row->email,
                'name' => $row->name,
                'api_token' => $row->api_token,
                'establishment_description' => optional($row->establishment)->description,
                'type' => ($row->type == 'admin') ? 'Administrador' : 'Vendedor',
                'locked' => (bool) $row->locked,

            ];
        });
    }
}