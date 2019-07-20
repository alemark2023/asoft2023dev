<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Tenant\Document;
use App\Models\Tenant\Configuration;
use Exception;

class LockedEmissionProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
         
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->locked_emission();
    }


    private function locked_emission()
    {

        Document::created(function ($document) {
            
            $configuration = Configuration::first();
            $quantity_documents = Document::count();

            if($configuration->locked_emission && $configuration->limit_documents !== 0){
                if($quantity_documents > $configuration->limit_documents)
                    throw new Exception("Ha superado el límite permitido para la emisión de comprobantes");
                    
            }
        
        });
    }
}
