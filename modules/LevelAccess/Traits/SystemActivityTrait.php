<?php

namespace Modules\LevelAccess\Traits;

use Jenssegers\Agent\Agent;
use Modules\LevelAccess\Models\{
    SystemActivityLog
};


trait SystemActivityTrait
{ 
    
    /**
     * 
     * Registrar datos en log de actividades para el usuario, inicio y cierre de sesión
     * Para cada Tenant
     *
     * @param  $event
     * @param  string $auth_transaction_type
     * @return void
     */
    public function saveSystemActivityUser($event, $auth_transaction_type)
    {
        if($this->isGuardWeb($event))
        {
            $client_data = $this->getClientData();
            $user = $event->user;
            $origin_id = null;
            $origin_type = null;

            if($user)
            {
                $origin_id = $user->id;
                $origin_type = get_class($user);
            }

            $base_data = [
                'user_id' => $user->id ?? null,
                'auth_transaction_type' => $auth_transaction_type,
                'date' => date('Y-m-d'),
                'time' => date('H:i:s'),
                'origin_id' => $origin_id,
                'origin_type' => $origin_type,
            ];

            $this->onlyCreateSystemActivityLog($this->getParamsSystemActivity($client_data, $base_data));
        }
    }

    
    /**
     * 
     * Registro en bd
     *
     * @param  array $data
     * @return void
     */
    public function onlyCreateSystemActivityLog($data)
    {
        SystemActivityLog::create($data);
    }

        
    /**
     * 
     * Data para registro de actividades
     *
     * @param  array $new_data
     * @param  array $base_data
     * @return array
     */
    public function getParamsSystemActivity($new_data, $base_data)
    {
        return array_merge($new_data, $base_data);
    }

    /**
     * 
     * Obtener información del cliente
     *
     * @return array
     */
    public function getClientData()
    {
        $agent = new Agent();
        $browser = $agent->browser();
        $platform = $agent->platform();

        return [
            'platform_name' => $platform,
            'platform_version' => $agent->version($platform),
            'browser_name' => $browser,
            'browser_version' => $agent->version($browser),
            'device_name' => $agent->device(),
            'device_type' => $agent->deviceType(),
            'ip' => request()->ip(),
        ];
    }

    
    /**
     *
     * @return bool
     */
    public function isGuardWeb($event)
    {
        return $event->guard === 'web';
    }
    
    
    /**
     *
     * @param  string $connection
     * @return bool
     */
    public function isTenantConnection($connection)
    {
        return $connection === 'tenant';
    }

}
