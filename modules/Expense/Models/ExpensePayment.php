<?php

namespace Modules\Expense\Models;
use App\Models\Tenant\ModelTenant;
use App\Models\Tenant\CardBrand;

class ExpensePayment extends ModelTenant
{
    // protected $with = ['payment_method_type', 'card_brand'];
    public $timestamps = false;

    protected $fillable = [
        'expense_id',
        'date_of_payment',
        'expense_method_type_id',
        'has_card',
        'card_brand_id',
        'reference',
        'payment',
    ];

    protected $casts = [
        'date_of_payment' => 'date',
    ];

    public function expense()
    {
        return $this->belongsTo(Expense::class);
    }

    public function expense_method_type()
    {
        return $this->belongsTo(ExpenseMethodType::class);
    }

    public function card_brand()
    {
        return $this->belongsTo(CardBrand::class);
    }
}