<?php

namespace App\Http\ViewComposers\Tenant;

use App\Http\Resources\Tenant\ConfigurationResource;
use App\Models\Tenant\Configuration;

class ConfigurationVisualViewComposer
{
    public function compose($view)
    {
    	$configuration = Configuration::first();
        $record = new ConfigurationResource($configuration);
    	// $config = Configuration::first();
        $view->visual = $record->visual;
    }
}
