<?php

namespace App\Models\Tenant;

class Configuration extends ModelTenant
{
    protected $fillable = [
        'send_auto',
        'cron',
        'stock',
        'locked_emission',
        'locked_users',
        'limit_documents',
        'sunat_alternate_server',
        'plan',
        'limit_users',
        'quantity_documents',
        'date_time_start',
        'locked_tenant',
        'decimal_quantity'
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
