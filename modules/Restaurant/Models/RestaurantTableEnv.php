<?php

namespace Modules\Restaurant\Models;

use App\Models\Tenant\ModelTenant;

class RestaurantTableEnv extends ModelTenant
{
    protected $fillable = [
        'name',
        'active',
        'tables_quantity',
    ];
}
