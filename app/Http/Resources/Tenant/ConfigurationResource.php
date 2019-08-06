<?php

namespace App\Http\Resources\Tenant;

use Illuminate\Http\Resources\Json\JsonResource;

class ConfigurationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request) {
        return [
            'id' => $this->id,
            'send_auto' => (bool) $this->send_auto,
            'stock' => (bool) $this->stock,
            'cron' => (bool) $this->cron,
            'subtotal_account' => $this->subtotal_account,
        ];
    }
}