<?php


    namespace App\Models\Tenant;


    use Hyn\Tenancy\Traits\UsesTenantConnection;

    /**
     * Class Zone
     *
     * @property int    $id
     * @property string $name
     * @package  App\Models\Tenant
     */
    class Zone extends ModelTenant
    {
        use UsesTenantConnection;

        public $timestamps = false;
        protected $perPage = 25;
        protected $fillable = [
            'name'
        ];

    }
