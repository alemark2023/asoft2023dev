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
     
     
    public function getTypeRecordAttribute()
    {
        return $this->destination_type === Cash::class ? 'cash':'bank_account';
    }

}