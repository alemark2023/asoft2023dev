<?php

namespace Modules\Sale\Models;

use App\Models\Tenant\User;
use App\Models\Tenant\SoapType;
use App\Models\Tenant\Person;
use App\Models\Tenant\ModelTenant;

class TechnicalService extends ModelTenant
{

    protected $fillable = [
        'id',
        'user_id',
        'soap_type_id',
        'date_of_issue',
        'time_of_issue',
        'customer_id',
        'customer',
        'cellphone',
        'description',
        'state',
        'reason',
        'serial_number',
        'filename',
        'cost',
        'prepayment',
        'activities',

    ];

    protected $casts = [
        'date_of_issue' => 'date',
    ];
 

    public function getCustomerAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setCustomerAttribute($value)
    {
        $this->attributes['customer'] = (is_null($value))?null:json_encode($value);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function soap_type()
    {
        return $this->belongsTo(SoapType::class);
    }

    public function person() {
        return $this->belongsTo(Person::class, 'customer_id');
    }

    public function scopeWhereTypeUser($query)
    {
        $user = auth()->user();
        return ($user->type == 'seller') ? $query->where('user_id', $user->id) : null;
    }

}
