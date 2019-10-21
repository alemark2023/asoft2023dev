<?php

namespace App\Models\Tenant;

class Configuration extends ModelTenant
{
    protected $fillable = [
        'send_auto',
        'cron', 
        'stock',
        'locked_emission',
        'limit_documents',
        'plan'
    ];

    public function setPlanAttribute($value)
    {
        $this->attributes['plan'] = (is_null($value))?null:json_encode($value);
    }

    public function getPlanAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

}