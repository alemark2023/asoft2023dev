<?php

namespace App\Models\System;

use Hyn\Tenancy\Traits\UsesSystemConnection;
use Illuminate\Database\Eloquent\Model;

class Configuration extends Model
{ 
    use UsesSystemConnection;

    protected $fillable = [
        'locked_admin',
    ];
 

}
