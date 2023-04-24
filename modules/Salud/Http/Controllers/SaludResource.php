<?php

namespace Modules\Salud\Http\Controllers;

use Illuminate\Http\Resources\Json\JsonResource;

class SaludResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        /** @var \App\Models\Tenant\Person $this */
        // return $this->getCollectionData(true);
        /** Pasado al modelo  */
        return [
            "id" => $this->id,
            "name" => $this->name,
            "description" => $this->description,
            "user_created" => $this->user_created,
            "user_updated" => $this->user_updated,
            "enabled" => $this->enabled,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at
        ];
    }
}
