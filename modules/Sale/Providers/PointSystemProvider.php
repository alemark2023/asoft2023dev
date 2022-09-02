<?php

namespace Modules\Sale\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Tenant\{
    Document,
    Configuration,
};
use Exception;


class PointSystemProvider extends ServiceProvider
{

    private $configuration;

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
        $this->setPointsToDocument(); 
    }

    private function setConfiguration()
    {
        // $this->configuration = Configuration::select('')
    }
 
    

    private function setPointsToDocument()
    {

        Document::created(function ($document) {

            $total = $document->total;
            // dd($document->total);

        });
        
    }
 
}
