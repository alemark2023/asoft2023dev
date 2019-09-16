<?php

namespace App\Models\Tenant\Catalogs;
use App\Models\Tenant\ModelTenant;

class Tag extends ModelTenant
{
    //use UsesTenantConnection;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'description'];
}
