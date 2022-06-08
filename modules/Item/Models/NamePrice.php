<?php

namespace Modules\Item\Models;

use App\Models\Tenant\ModelTenant;
use App\Models\Tenant\Catalogs\AffectationIgvType;
use App\Models\Tenant\Catalogs\CurrencyType;
use App\Models\Tenant\Catalogs\SystemIscType;
use App\Models\Tenant\Catalogs\UnitType;
use App\Models\Tenant\PersonType;
use Modules\Item\Models\ListPrice;

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
class NamePrice extends ModelTenant
{
     protected $with = ['person_type','list_price'];
    public $timestamps = false;

    protected $fillable = [
        'type_customer_id',
        'price_default'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function unit_type() {
        return $this->belongsTo(UnitType::class, 'unit_type_id');
    }

    public function person_type(){
        return $this->belongsTo(UnitType::class, 'type_customer_id');
    }

    public function list_price()
    {
        return $this->hasMany(ListPrice::class,'name_price_id','name_price_id');
    }

    public function scopeWhereIdPrice()
    {
        $name_id=NamePrice::latest('id')->first();
        $id = 1;
        if(empty($name_id->id)){
            $id=1;
        }else{
            $id=$name_id->id;
        }
        return $id;
    }


    /**
     * Retorna un standar de nomenclatura para el modelo
     *
     * @param int $decimal_units
     *
     * @return array
     */
    public function getCollectionData($decimal_units = 2){

        return [
            'id'            => $this->id,
            'price_default' => $this->price_default,
        ];
    }

}
