<?php

namespace Modules\LevelAccess\Models;

use App\Models\Tenant\{
    ModelTenant,
    User,
    Company,
};


class SystemActivityLog extends ModelTenant 
{

    protected $fillable = [
        'user_id',
        'transaction_type',
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
    
    
    /**
     * 
     * Transacciones asociadas al registro de actividad del sistema
     *
     * @return array
     */
    public static function getTransactionTypes()
    {
        $login_transactions = [
            'failed' => 'Error de inicio de sesión',
            'logout' => 'Cerrar sesión',
            'login' => 'Iniciar sesión',
        ];

        $company_transactions = (new Company)->getTransactionTypesForSystemActivity();

        return array_merge($login_transactions, $company_transactions);
    }
    
        
    /**
     * 
     * Dispositivos
     *
     * @return array
     */
    public function getDeviceTypesAttribute()
    {
        return [
            'desktop' => 'Computadora de escritorio',
            'phone' => 'Teléfono',
            'tablet' => 'Tablet',
            'robot' => 'Robot',
        ];
    }
        

    /**
     * Descripcion de la transaccion
     *
     * @param  array $transaction_types
     * @return string
     */
    public function getTransactionTypeDescription($transaction_types)
    {
        return $transaction_types[$this->transaction_type] ?? null;
    }

    
    /**
     *
     * @return string
     */
    public function getDeviceTypeDescriptionAttribute()
    {
        return $this->device_types[$this->device_type];
    }
    
    
    /**
     * 
     * Retorna datos para mostrar en vista
     *
     * @return array
     */
    public function getDataLocation()
    {
        if(!$this->location) return null;

        return [
            'country_code' => $this->location->country_code,
            'country_name' => $this->location->country_name,
            'region_code' => $this->location->region_code,
            'region_name' => $this->location->region_name,
            'city_name' => $this->location->city_name,
            'timezone' => $this->location->timezone,
        ];
    }

    
    /**
     * 
     * Datos para mostrar en vista - listado
     *
     * @param  array $transaction_types
     * @return array
     */
    public function getRowResourceAccess($transaction_types)
    {
        return [
            'user_id' => $this->user_id,
            'transaction_type' => $this->transaction_type,
            'transaction_type_description' => $this->getTransactionTypeDescription($transaction_types),
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
            'location' => $this->getDataLocation(),
        ];
    }

}
