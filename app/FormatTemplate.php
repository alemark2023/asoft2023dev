<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class FormatTemplate extends ModelTenant
{
    //
    use Notifiable, UsesTenantConnection;

    protected $table = 'format_templates';

    protected $fillable = [
    	'id',
    	'formats'
    ];
}
