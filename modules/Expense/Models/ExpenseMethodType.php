<?php

namespace Modules\Expense\Models;
use App\Models\Tenant\ModelTenant;

class ExpenseMethodType extends ModelTenant
{
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'description',
        'has_card', 
    ];
}