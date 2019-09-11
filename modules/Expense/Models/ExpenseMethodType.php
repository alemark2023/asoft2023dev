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
}