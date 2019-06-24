<?php

namespace App\Models\System;

use Hyn\Tenancy\Models\Hostname; 
use Hyn\Tenancy\Traits\UsesSystemConnection;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{ 
    use UsesSystemConnection;

    protected $with = ['hostname','plan'];
    protected $fillable = [
        'hostname_id',
        'number',
        'name',
        'email',
        'token',
        'locked',
        'plan_id'
    ];

    public function hostname()
    {
        return $this->belongsTo(Hostname::class)->with(['website']);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
}
