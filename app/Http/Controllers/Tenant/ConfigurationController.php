<?php
namespace App\Http\Controllers\Tenant;

use App\Http\Requests\Tenant\ConfigurationRequest;
use App\Http\Resources\Tenant\ConfigurationResource;
use App\Models\Tenant\Configuration;
use App\Http\Controllers\Controller;

class ConfigurationController extends Controller
{
    public function create() {
        return view('tenant.configurations.form');
    }
    
    public function record() {
        $configuration = Configuration::first();
        $record = new ConfigurationResource($configuration);
        
        return $record;
    }
    
    public function store(ConfigurationRequest $request) {
        $id = $request->input('id');
        $configuration = Configuration::find($id);
        $configuration->fill($request->all());
        $configuration->save();
        
        return [
            'success' => true,
            'message' => 'Configuración actualizada'
        ];
    }
}