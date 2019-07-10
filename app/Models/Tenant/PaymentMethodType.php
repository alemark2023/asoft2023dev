<?php

namespace App\Models\Tenant;

class PaymentMethodType extends ModelTenant
{
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'description',
        'has_card',
        'charge',
        'number_days',
    ];
}