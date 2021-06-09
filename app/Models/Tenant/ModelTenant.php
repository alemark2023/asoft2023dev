<?php

namespace App\Models\Tenant;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ModelTenant
 *
 * @package App\Models\Tenant
 * @mixin  Model
 * @mixin \Illuminate\Database\Query\Builder
 */
class ModelTenant extends Model
{
    use UsesTenantConnection;
}
