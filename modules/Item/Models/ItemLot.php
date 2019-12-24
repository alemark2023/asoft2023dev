<?php

namespace Modules\Item\Models;

use App\Models\Tenant\Item;
use App\Models\Tenant\ModelTenant;
use Modules\Inventory\Models\Warehouse;

class ItemLot extends ModelTenant
{

    protected $fillable = [ 
        'series',
        'date',
        'item_id',
        'warehouse_id',
        'item_loteable_type',
        'item_loteable_id',
        'has_sale',
    ];
 
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
 
    public function item_loteable()
    {
        return $this->morphTo();
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

}