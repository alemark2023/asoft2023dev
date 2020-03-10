<?php

namespace Modules\Finance\Models;

use App\Models\Tenant\ModelTenant;
use App\Models\Tenant\Cash;
use App\Models\Tenant\BankAccount;
use App\Models\Tenant\SoapType;

class GlobalPayment extends ModelTenant
{

    protected $fillable = [
        'soap_type_id',
        'destination_id',
        'destination_type',
        'payment_id',
        'payment_type', 
    ];
 

    public function soap_type()
    {
        return $this->belongsTo(SoapType::class);
    }

    public function destination()
    {
        return $this->morphTo();
    }
     

    public function payment()
    {
        return $this->morphTo();
    }

    public function getDestinationDescriptionAttribute()
    {
        return $this->destination_type === Cash::class ? 'CAJA CHICA': "{$this->destination->bank->description} - {$this->destination->currency_type_id} - {$this->destination->description}";
    }
     
    public function getTypeRecordAttribute()
    {
        return $this->destination_type === Cash::class ? 'cash':'bank_account';
    }

}