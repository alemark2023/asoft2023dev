<?php

namespace Modules\MobileApp\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Modules\MobileApp\Http\Resources\Api\AppConfigurationResource;
use Modules\MobileApp\Http\Requests\Api\AppConfigurationRequest;
use Modules\MobileApp\Models\AppConfiguration;


class AppConfigurationController extends Controller
{
    
    /**
     * @return AppConfigurationResource
     */
    public function record()
    {
        return new AppConfigurationResource(AppConfiguration::firstOrFail());
    }

    
    /**
     * 
     * Actualizar configuracion de la app
     *
     * @param  AppConfigurationRequest $request
     * @return array
     */
    public function store(AppConfigurationRequest $request)
    {
        $record = AppConfiguration::firstOrFail();
        $record->fill($request->all());
        $record->save();

        return [
            'success' => true,
            'message' => 'Configuración actualizada',
            'data' => $record->getRowResource(),
        ];
    }

    
}