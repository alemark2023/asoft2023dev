<?php

namespace Modules\Inventory\Models;

use App\Models\Tenant\Item;
use App\Models\Tenant\ModelTenant;

/**
 * Modules\Inventory\Models\InventoryKardex
 *
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $inventory_kardexable
 * @property-read Item $item
 * @property-read \Modules\Inventory\Models\Warehouse $warehouse
 * @method static \Illuminate\Database\Eloquent\Builder|InventoryKardex newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InventoryKardex newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InventoryKardex query()
 * @mixin ModelTenant
 */
class InventoryKardex extends ModelTenant
{
    protected $table = 'inventory_kardex';

    protected $fillable = [ 
        'date_of_issue',
        'item_id',
        'inventory_kardexable_id',
        'inventory_kardexable_type',
        'warehouse_id',
        'quantity', 
    ];

    public function inventory_kardexable()
    {
        return $this->morphTo();
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}