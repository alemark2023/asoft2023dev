<?php

namespace App\Models\Tenant;

use App\Models\Tenant\Catalogs\CurrencyType;
use App\Models\Tenant\Catalogs\DocumentType;

class PerceptionDetail extends ModelTenant
{
    public $timestamps = false;
    protected $with = ['document_type', 'currency_type'];
    protected $fillable = [
        'perception_id',
        'document_type_id',
        'number',
        'date_of_issue',
        'date_of_perception',
        'currency_type_id',
        'total_document',
        'total_perception',
        'total',
        'exchange',
        'payments',
    ];

    protected $casts = [
        'date_of_issue' => 'date',
        'date_of_perception' => 'date'
    ];

    public function getPaymentsAttribute($value)
    {
        return (object)json_decode($value);
    }

    public function setPaymentsAttribute($value)
    {
        $this->attributes['payments'] = json_encode($value);
    }

    public function document_type()
    {
        return $this->belongsTo(DocumentType::class);
    }

    public function currency_type()
    {
        return $this->belongsTo(CurrencyType::class);
    }
}