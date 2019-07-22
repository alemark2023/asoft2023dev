<?php

namespace App\Models\Tenant;

class CardBrand extends ModelTenant
{
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'description',        
        'id',

    ];
}