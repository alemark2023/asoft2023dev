<?php

namespace App\Models\Tenant;
 
use Modules\Expense\Models\Expense;

class CashDocument extends ModelTenant
{
    // protected $with = ['document'];

    public $timestamps = false;
    
    protected $fillable = [
        'cash_id',
        'document_id',  
        'sale_note_id',  
        'purchase_id',  
        'expense_id',  
    ];
 

  
    public function cash()
    {
        return $this->belongsTo(Cash::class);
    }

    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    public function sale_note()
    {
        return $this->belongsTo(SaleNote::class);
    }
 
    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }
 
    public function expense()
    {
        return $this->belongsTo(Expense::class);
    }
 
}