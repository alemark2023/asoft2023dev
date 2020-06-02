<?php

namespace App\Models\Tenant;


use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Tenant\StatusOrder;


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
        'document_external_id',
        'number_document',
        'status_order_id'
    ];

    protected $casts = [
        'customer' => 'object',
        'items' => 'object'
    ];

    public function status_order()
    {
        return $this->belongsTo(StatusOrder::class);
    }


}
