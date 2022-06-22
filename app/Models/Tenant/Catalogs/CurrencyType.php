<?php

namespace App\Models\Tenant\Catalogs;

use Hyn\Tenancy\Traits\UsesTenantConnection;

class CurrencyType extends ModelCatalog
{
  use UsesTenantConnection;

  protected $table = "cat_currency_types";
  public $incrementing = false;
  public $timestamps = false;

  protected $fillable = [
    'id',
    'active',
    'symbol',
    'description',
  ];

  protected $casts = [
    'active' => true,
  ];

  public function scopeActives($query)
  {
    return $query->where('active', 1);
  }
}
