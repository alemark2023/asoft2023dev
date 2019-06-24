<?php

namespace Modules\Inventory\Models;

use App\Models\Tenant\Item;
use App\Models\Tenant\ModelTenant;

class Inventory extends ModelTenant
{
    protected $fillable = [
        'type',
        'description',
        'item_id',
        'warehouse_id',
        'warehouse_destination_id',
        'quantity',
    ];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function warehouse_destination()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_destination_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function inventory_kardex()
    {
        return $this->morphMany(InventoryKardex::class, 'inventory_kardexable');
    }
}