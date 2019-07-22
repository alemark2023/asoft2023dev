<?php

namespace App\Models\Tenant;
 

class CashDocument extends ModelTenant
{
    protected $with = ['document'];

    public $timestamps = false;
    
    protected $fillable = [
        'cash_id',
        'document_id',  
    ];
 

  
    public function cash()
    {
        return $this->belongsTo(Cash::class);
    }

    public function document()
    {
        return $this->belongsTo(Document::class);
    }
 
 
}