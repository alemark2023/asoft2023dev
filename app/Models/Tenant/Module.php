<?php

namespace App\Models\Tenant;

use Hyn\Tenancy\Traits\UsesTenantConnection;

class Module extends ModelTenant
{
    use UsesTenantConnection;

    protected $fillable = [
        'description',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}