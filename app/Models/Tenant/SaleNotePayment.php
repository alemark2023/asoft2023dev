<?php

namespace App\Models\Tenant;

use Modules\Finance\Models\GlobalPayment;

class SaleNotePayment extends ModelTenant
{
    protected $with = ['payment_method_type', 'card_brand'];
    public $timestamps = false;

    protected $fillable = [
        'sale_note_id',
        'date_of_payment',
        'payment_method_type_id',
        'has_card',
        'card_brand_id',
        'reference',
        'change',
        'payment',
    ];

    protected $casts = [
        'date_of_payment' => 'date',
    ];

    public function payment_method_type()
    {
        return $this->belongsTo(PaymentMethodType::class);
    }

    public function card_brand()
    {
        return $this->belongsTo(CardBrand::class);
    }

    public function global_payment()
    {
        return $this->morphOne(GlobalPayment::class, 'payment');
    }
 
}