<?php

namespace Modules\Document\Models;
 
use App\Models\Tenant\ModelTenant;
use App\Models\Tenant\Series;

class SeriesConfiguration extends ModelTenant
{
    protected $fillable = [
        'series_id',
        'series',
        'number',
    ];
  
    public function relationSeries()
    {
        return $this->belongsTo(Series::class,'series_id');
    }

}