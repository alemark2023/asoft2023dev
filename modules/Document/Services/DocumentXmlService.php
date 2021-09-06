<?php

namespace Modules\Document\Services;

class DocumentXmlService
{
    
    public function getGlobalChargesNoBase($document)
    { 
        // Cargos globales que no afectan la base imponible del IGV/IVAP
        $tot_charges = 0;

        if($document->charges){

            $tot_charges = collect($document->charges)->sum(function($charge){
                return (in_array($charge->charge_type_id, ['50', '46'])) ? $charge->amount : 0;
            });
        }

        return $tot_charges;
    }


    public function getGlobalDiscountsNoBase($document)
    { 

        //descuentos globales que no afectan la base
        $allowance_total_amount = 0;
    
        if($document->discounts){
            
            $allowance_total_amount = collect($document->discounts)->sum(function($discount){
                return (in_array($discount->discount_type_id, ['03', '63'])) ? $discount->amount : 0;
            });

        }

        return $allowance_total_amount;
    }

    public function getItemsDiscountsNoBase($document)
    { 

        return $document->items->sum(function($row){
            return $row->discounts ? collect($row->discounts)->sum(function($discount){
                return $discount->discount_type_id == '01' ? $discount->amount : 0;
            }) : 0;
        });

    }

}
