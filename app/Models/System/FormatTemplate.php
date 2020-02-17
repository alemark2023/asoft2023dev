<?php

namespace App\Models\System;


use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class FormatTemplate extends Model
{
    //
    use UsesTenantConnection;

    protected $table = 'format_templates';

    protected $fillable = [
    	'id',
    	'formats'
    ];
}
