<?php

namespace Modules\Salud\Models;

use App\Models\Tenant\ModelTenant;

class Especialidad extends ModelTenant
{
    protected $table = 'especialidad';
    protected $fillable = ['name','description','user_created','user_updated','created_at','updated_at'];
}
