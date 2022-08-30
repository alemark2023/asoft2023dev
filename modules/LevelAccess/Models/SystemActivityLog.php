<?php

namespace Modules\LevelAccess\Models;

use App\Models\Tenant\ModelTenant;
use App\Models\Tenant\User;


class SystemActivityLog extends ModelTenant 
{

    protected $fillable = [
        'user_id',
        'auth_transaction_type',
        'date',
        'time',
        'origin_id',
        'origin_type',
        
        'ip',
        'device_name',
        'device_type',
        'platform_name',
        'platform_version',
        'browser_name',
        'browser_version',
        'location',
    ];

    
    public function getLocationAttribute($value)
    {
        return (is_null($value)) ? null : (object)json_decode($value);
    }

    public function setLocationAttribute($value)
    {
        $this->attributes['location'] = (is_null($value)) ? null : json_encode($value);
    }

    public function user() 
    {
        return $this->belongsTo(User::class);
    }


    public function origin()
    {
        return $this->morphTo();
    }

    public function getAuthTransactionTypesAttribute()
    {
        return [
            'failed' => 'Error de inicio de sesión',
            'logout' => 'Cerrar sesión',
            'login' => 'Iniciar sesión',
        ];
    }
    
    public function getDeviceTypesAttribute()
    {
        return [
            'desktop' => 'Computadora de escritorio',
            'phone' => 'Teléfono',
            'tablet' => 'Tablet',
            'robot' => 'Robot',
        ];
    }
    
    public function getAuthTransactionTypeDescriptionAttribute()
    {
        return $this->auth_transaction_types[$this->auth_transaction_type];
    }

    public function getDeviceTypeDescriptionAttribute()
    {
        return $this->device_types[$this->device_type];
    }

    public function getRowResourceAccess()
    {
        return [
            'user_id' => $this->user_id,
            'auth_transaction_type' => $this->auth_transaction_type,
            'auth_transaction_type_description' => $this->auth_transaction_type_description,
            'date_time' => "{$this->date} - {$this->time}",
            'user_name' => optional($this->user)->name,
            'date' => $this->date,
            'time' => $this->time,
            'ip' => $this->ip,
            'device_name' => $this->device_name,
            'device_type' => $this->device_type,
            'device_type_description' => $this->device_type_description,
            'platform_name' => $this->platform_name,
            'platform_version' => $this->platform_version,
            'platform_description' => "{$this->platform_name} - {$this->platform_version}",
            'browser_name' => $this->browser_name,
            'browser_version' => $this->browser_version,
            'browser_description' => "{$this->browser_name} - {$this->browser_version}",
            'location' => $this->location,
        ];
    }

}
