<?php

namespace Modules\MercadoPago\Models;

use App\Models\Tenant\{
    ModelTenant,
};
use Modules\Payment\Models\{
    PaymentLink
};


class Transaction extends ModelTenant
{

    protected $fillable = [
        'date',
        'time',
        'uuid',
        'description',
        'payment_id',
        'amount',
        'transaction_state_id',
        'payment_link_id',
    ];

 
    public function payment_link()
    {
        return $this->belongsTo(PaymentLink::class);
    } 

    public function transaction_state()
    {
        return $this->belongsTo(TransactionState::class, 'transaction_state_id');
    } 

    public function transaction_queries()
    {
        return $this->hasMany(TransactionQuery::class);
    } 
 

}
