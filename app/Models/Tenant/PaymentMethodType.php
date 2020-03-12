<?php

namespace App\Models\Tenant;

use App\Models\Tenant\{
    DocumentPayment,
    SaleNotePayment,
    PurchasePayment
};

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

    public function payments()
    {
        return $this->hasManyThrough(
            DocumentPayment::class, 
            SaleNotePayment::class,
            PurchasePayment::class,
            'payment_method_type_id',
            'payment_method_type_id',
            'payment_method_type_id',
            'id', 
            'id', 
            'id'
        );
    }

}