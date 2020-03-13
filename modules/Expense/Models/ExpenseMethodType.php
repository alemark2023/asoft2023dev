<?php

namespace Modules\Expense\Models;
use App\Models\Tenant\ModelTenant;

class ExpenseMethodType extends ModelTenant
{
    public $timestamps = false;

    protected $fillable = [
        'description',
        'has_card', 
    ];

    public function expense_payments()
    {
        return $this->hasMany(ExpensePayment::class,  'expense_method_type_id');
    }
}