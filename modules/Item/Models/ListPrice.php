<?php

namespace Modules\Item\Models;

use App\Models\Tenant\ModelTenant;
use Modules\Item\Models\NamePrice;

class ListPrice extends ModelTenant
{
    protected $with = ['name_price'];
    public $timestamps = false;

    protected $fillable = [ 
        'price',
        'name_price_id'
    ];
 
    public function name_price() {
        return $this->belongsTo(NamePrice::class);
    }
 

}