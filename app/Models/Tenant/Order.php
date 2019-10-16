<?php

namespace App\Models\Tenant;


use Illuminate\Database\Eloquent\SoftDeletes;


class Order extends ModelTenant
{
    use SoftDeletes;
  
    protected $fillable = [
        'external_id',
        'customer',
        'shipping_address',
        'items',        
        'total',
        'reference_payment',
        'document_external_id'
    ];

    protected $casts = [
        'customer' => 'object',
        'items' => 'object'
    ];


}