<?php

namespace App\Models\Tenant;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ModelTenant
 *
 * @package App\Models\Tenant
 * @mixin Model
 * @mixin \Eloquent
 * @mixin \Illuminate\Database\Query\Builder as Builder
 * @mixin \Illuminate\Database\Eloquent\Collection
 * @method static \Illuminate\Database\Eloquent\Builder|ModelTenant newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ModelTenant newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ModelTenant query()
 */
class ModelTenant extends Model
{
    use UsesTenantConnection;
}
