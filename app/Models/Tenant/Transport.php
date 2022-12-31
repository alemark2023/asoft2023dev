<?php

namespace App\Models\Tenant;

class Transport extends ModelTenant
{
    protected $fillable = [
        'model',
        'brand',
        'plate_number',
        'is_default',
        'is_active'
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'is_active' => 'boolean',
    ];
}
