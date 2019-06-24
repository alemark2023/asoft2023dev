<?php

namespace Modules\Inventory\Providers;

use App\Models\Tenant\Document;  
use Illuminate\Support\ServiceProvider;
use Modules\Inventory\Traits\InventoryTrait;

class InventoryVoidedServiceProvider extends ServiceProvider
{
    use InventoryTrait;

    public function register()
    {
    }
    
    public function boot()
    {
        $this->voided();
    }

    private function voided()
    {
        //Revisar los tipos de documentos, ello varia el control de stock en las anulaciones.
        Document::updated(function ($document) {
            if($document['document_type_id'] == '01' || $document['document_type_id'] == '03'){
                if(in_array($document['state_type_id'], [ '09', '11' ], true)){
                    $warehouse = $this->findWarehouse($document['establishment_id']);
                    foreach ($document['items'] as $detail) {
                        $this->createInventoryKardex($document, $detail['item_id'], $detail['quantity'], $warehouse->id);
                        $this->updateStock($detail['item_id'], $detail['quantity'], $warehouse->id);
                    }
                }
            }         
        });
    }
}
