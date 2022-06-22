<?php

namespace Modules\Offline\Models;

use App\Models\Tenant\ModelTenant;

class OfflineConfiguration extends ModelTenant
{

    protected $fillable = [
        'is_client',
        'token_server',
        'url_server',
    ];

    protected $casts = [
        'is_client' => 'boolean'
    ];
}
