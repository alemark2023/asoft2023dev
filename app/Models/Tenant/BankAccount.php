<?php

namespace App\Models\Tenant;

use App\Models\Tenant\Catalogs\CurrencyType;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class BankAccount extends ModelTenant
{
    use UsesTenantConnection;

    public $timestamps = false;

    protected $fillable = [
        'bank_id',
        'description',
        'number',
        'currency_type_id'
    ];

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

    public function currency_type()
    {
        return $this->belongsTo(CurrencyType::class, 'currency_type_id');
    }
}