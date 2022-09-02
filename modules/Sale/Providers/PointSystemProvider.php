<?php

namespace Modules\Sale\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Tenant\{
    Document,
    Configuration,
    Person
};
use Exception;


class PointSystemProvider extends ServiceProvider
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
        $this->setPointsToDocument(); 
    }
    
    
    /**
     * 
     * Validar si el documento se usara para sistema por puntos y actualizar puntos del cliente
     *
     * @return void
     */
    private function setPointsToDocument()
    {
        
        // para registro del cpe
        Document::created(function ($document) {

            if($document->isDocumentTypeInvoice() && $document->isPointSystem())
            {
                $this->setPointsToCustomer($document, 1);
            }

        });


        // para anulaciones o rechazo del cpe
        Document::updated(function ($document) {
            
            if($document->isDocumentTypeInvoice() && $document->isVoidedOrRejected() && $document->isPointSystem())
            {
                $this->setPointsToCustomer($document, -1);
            }
        });
        
    }

    
    /**
     * 
     * Asignar puntos al cliente
     *
     * @param  Document $document
     * @param  int $factor
     * @return void
     */
    private function setPointsToCustomer($document, $factor)
    {
        $point_system_data = $document->point_system_data;

        $total = $document->total;
        $calculate_quantity_points = round(($total / $point_system_data->point_system_sale_amount) * $point_system_data->quantity_of_points, 2);
        $customer = $document->person;
        $customer->accumulated_points = $customer->accumulated_points  + ($calculate_quantity_points * $factor);
        $customer->save();
    }

 
    /**
     * 
     * Configuracion del sistema de puntos
     *
     * @return Configuration
     */
    // private function getConfiguration()
    // {
    //     return Configuration::getDataPointSystem();
    // }
    
 
}
