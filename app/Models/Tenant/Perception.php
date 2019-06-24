<?php

namespace App\Models\Tenant;

use App\Models\Tenant\Catalogs\CurrencyType;
use App\Models\Tenant\Catalogs\DocumentType;
use App\Models\Tenant\Catalogs\PerceptionType;

class Perception extends ModelTenant
{
    protected $with = ['user', 'establishment', 'soap_type', 'state_type', 'document_type', 'series',
                       'customer', 'currency_type', 'system_code_perception', 'details'];

    protected $fillable = [
        'user_id',
        'establishment_id',
        'external_id',
        'soap_type_id',
        'state_type_id',
        'ubl_version',
        'document_type_id',
        'series_id',
        'number',
        'date_of_issue',
        'customer_id',
        'currency_type_id',
        'observation',
        'system_code_perception_id',
        'percent',
        'total_perception',
        'total',

        'filename',
        'hash',
        'has_xml',
        'has_pdf',
        'has_cdr'
    ];

    protected $casts = [
        'date_of_issue' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function establishment()
    {
        return $this->belongsTo(Establishment::class);
    }

    public function soap_type()
    {
        return $this->belongsTo(SoapType::class);
    }

    public function state_type()
    {
        return $this->belongsTo(StateType::class);
    }

    public function document_type()
    {
        return $this->belongsTo(DocumentType::class);
    }

    public function series()
    {
        return $this->belongsTo(Series::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function currency_type()
    {
        return $this->belongsTo(CurrencyType::class);
    }

    public function perception_type()
    {
        return $this->belongsTo(PerceptionType::class);
    }

    public function details()
    {
        return $this->hasMany(PerceptionDetail::class);
    }

}