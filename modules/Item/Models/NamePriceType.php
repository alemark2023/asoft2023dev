<?php

namespace Modules\Item\Models;

use App\Models\Tenant\ModelTenant;
use Modules\Item\Models\ItemPriceType;

class NamePriceType extends ModelTenant
{

    protected $fillable = [ 
        'name',
    ];
 
    public function item_price_type()
    {
        return $this->hasMany(ItemPriceType::class,'name_price_id');
    }
 

}