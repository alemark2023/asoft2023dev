<?php

namespace App\Traits;

use Hyn\Tenancy\Contracts\Hostname;
use Hyn\Tenancy\Environment;
use App\Models\System\{
    Client,
    Plan
};
use App\Models\Tenant\{
    Configuration
};
use Exception;


trait LockedEmissionTrait
{

    /**
     * 
     * Obtener registro asociado al tenant desde la tabla hostname de system
     * 
     * @return Hostname
     */
    public function getTenantHostname()
    {
        return app(Environment::class)->hostname();
    }


    /**
     *
     * @param  string $column
     * @return mixed
     */
    public function getConfigurationColumn($column)
    {
        return Configuration::getRecordIndividualColumn($column);
    }

        
    /**
     *
     * @param  int $hostname_id
     * @return Client
     */
    public function getClientByHostname($hostname_id)
    {
        return Client::where('hostname_id', $hostname_id)
                    ->whereFilterWithOutRelations()
                    ->select([
                        'id',
                        'name',
                        'plan_id'
                    ])
                    ->first();
    }

    
    /**
     *
     * @param  array $columns
     * @param  int $plan_id
     * @return Plan
     */
    public function getPlan($columns, $plan_id)
    {
        return Plan::select($columns)->find($plan_id);
    }
    
    
    /**
     * 
     * Buscar cliente y obtener plan
     *
     * @param  array $columns
     * @return Plan
     */
    public function getClientPlan($columns)
    {
        $tenant_hostname = $this->getTenantHostname();
        $client = $this->getClientByHostname($tenant_hostname->id);

        return $this->getPlan($columns, $client->plan_id);
    }

    
    /**
     *
     * @param  string $model
     * @return int
     */
    public function getQuantityByModel($model)
    {
        return $model::count();
    }

    
    /**
     *
     * @param  string $message
     * @return void
     */
    public function throwException($message)
    {
        throw new Exception($message);
    }

}
