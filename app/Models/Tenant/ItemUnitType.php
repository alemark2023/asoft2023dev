<?php

namespace App\Models\Tenant;

use App\Models\Tenant\Catalogs\AffectationIgvType;
use App\Models\Tenant\Catalogs\CurrencyType;
use App\Models\Tenant\Catalogs\SystemIscType;
use App\Models\Tenant\Catalogs\UnitType;

/**
 * App\Models\Tenant\ItemUnitType
 *
 * @property-read \App\Models\Tenant\Item $item
 * @property-read UnitType $unit_type
 * @method static \Illuminate\Database\Eloquent\Builder|ItemUnitType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ItemUnitType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ItemUnitType query()
 * @mixin \Eloquent
 */
class ItemUnitType extends ModelTenant
{
     protected $with = ['unit_type'];
    public $timestamps = false;

    protected $fillable = [
        'description',
        'item_id',
        'unit_type_id',
        'quantity_unit',
        'price1',
        'price2',
        'price3',
        'price_default',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function unit_type() {
        return $this->belongsTo(UnitType::class, 'unit_type_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function item() {
        return $this->belongsTo(Item::class);
    }

}
