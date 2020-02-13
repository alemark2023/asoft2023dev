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
                        // dd($detail['item']->presentation);
                        $presentationQuantity = (!empty($detail['item']->presentation)) ? $detail['item']->presentation->quantity_unit : 1;

                        $this->createInventoryKardex($document, $detail['item_id'], $detail['quantity'] * $presentationQuantity, $warehouse->id);
                        $this->updateStock($detail['item_id'], $detail['quantity'] * $presentationQuantity, $warehouse->id);

                    }

                    $this->voidedWasDeductedPrepayment($document);

                }
            }         
        });
    }

    
    private function voidedWasDeductedPrepayment($document)
    {

        if($document->prepayments){
            
            foreach ($document->prepayments as $row) {
                $fullnumber = explode('-', $row->number);
                $series = $fullnumber[0];
                $number = $fullnumber[1];

                $doc = Document::where([['series',$series],['number',$number]])->first();
                if($doc){
                    $doc->was_deducted_prepayment = false;
                    $doc->save();
                }
            }
        }
        
    }
}
