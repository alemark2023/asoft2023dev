<?php

namespace App\Models\Tenant;

use Hyn\Tenancy\Traits\UsesTenantConnection;

class Bank extends ModelTenant
{
    use UsesTenantConnection;

    protected $fillable = [
        'description',
    ];
}