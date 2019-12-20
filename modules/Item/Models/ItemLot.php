<?php

namespace Modules\Item\Models;

use App\Models\Tenant\Item;
use App\Models\Tenant\ModelTenant;

class ItemLot extends ModelTenant
{

    protected $fillable = [ 
        'series',
        'date',
    ];
 
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
 

}