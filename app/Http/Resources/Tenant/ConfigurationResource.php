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
            'sunat_alternate_server' => (bool) $this->sunat_alternate_server,
            'subtotal_account' => $this->subtotal_account,
            'decimal_quantity' => $this->decimal_quantity
        ];
    }
}
