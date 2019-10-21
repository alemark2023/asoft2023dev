<?php

namespace App\Http\Resources\System;

use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
                'hostname' => $this->hostname->fqdn,
                'name' => $this->name,
                'email' => $this->email,
                'token' => $this->token,
                'number' => $this->number,
                'plan_id' => $this->plan_id,
                'locked' => (bool) $this->locked,
                'locked_emission' => (bool) $this->locked_emission,
                //'count_doc' => $this->count_doc,
               // 'max_documents' => (int) $this->plan->limit_documents,
                //'count_user' => $this->count_user,
                //'max_users' => (int) $this->plan->limit_users,
                'created_at' => $this->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}