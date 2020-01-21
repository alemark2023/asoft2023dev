<?php

namespace Modules\Dashboard\Helpers;

use App\Models\Tenant\Document;
use App\Models\Tenant\SaleNote;
use App\Models\Tenant\Person;
use App\Models\Tenant\DocumentItem;
use App\Models\Tenant\PurchaseItem;
use App\Models\Tenant\SaleNoteItem;
use App\Models\Tenant\Purchase;
use App\Models\Tenant\Item;
use Carbon\Carbon;
use Modules\Dashboard\Traits\TotalsTrait;

class DashboardUtility
{

    // use TotalsTrait;
    
    public function data($establishment_id, $d_start, $d_end)
    { 
        return $this->utilities_totals($establishment_id, $d_start, $d_end);
    }
  

    
    private function utilities_totals($establishment_id, $d_start, $d_end){
 

        if($d_start && $d_end){

            $document_items = DocumentItem::whereHas('document',function($query) use($establishment_id, $d_start, $d_end){

                                                $query->where('establishment_id', $establishment_id)
                                                        ->whereIn('state_type_id', ['01','03','05','07','13'])
                                                        ->whereBetween('date_of_issue', [$d_start, $d_end]);
                                            })
                                            ->get();


            $sale_note_items = SaleNoteItem::whereHas('sale_note', function($query) use($establishment_id, $d_start, $d_end){

                                                $query->where([['establishment_id', $establishment_id],['changed',false]])
                                                        ->whereIn('state_type_id', ['01','03','05','07','13'])
                                                        ->whereBetween('date_of_issue', [$d_start, $d_end]);
                                            })
                                            ->get();

        }else{

            $document_items = DocumentItem::whereHas('document', function($query) use($establishment_id){

                                                $query->where('establishment_id', $establishment_id)
                                                        ->whereIn('state_type_id', ['01','03','05','07','13']);
                                            })
                                            ->get(); 


            $sale_note_items = SaleNoteItem::whereHas('sale_note', function($query) use($establishment_id){

                                                $query->where([['establishment_id', $establishment_id],['changed',false]])
                                                        ->whereIn('state_type_id', ['01','03','05','07','13']);
                                            })
                                            ->get();

        }          

        // dd($document_items);
        $getTotalDocumentItems = $this->getTotalDocumentItems($document_items);

        return [
            'getTotalDocumentItems' => $getTotalDocumentItems
        ];

    } 

    private function getTotalDocumentItems($document_items){

        $purchase_unit_price = 0;
        $purchase_currency_type = null;

        //PEN
        $document_total_note_credit_pen = 0;

        $document_sale_total_pen = 0;
        $document_purchase_total_pen = 0;
        $document_utility_total_pen = 0;

        //USD
        $document_total_note_credit_usd = 0;
        $document_sale_total_usd = 0;
        $document_purchase_total_usd = 0;
        $document_utility_total_usd = 0;

        foreach ($document_items as $doc_it) {

            //compra por item
            if($doc_it->relation_item->purchase_unit_price > 0){

                $purchase_unit_price = $doc_it->relation_item->purchase_unit_price;
                $purchase_currency_type = $doc_it->relation_item->currency_type_id;

            }else{

                $purchase_item = PurchaseItem::select('unit_price')->where('item_id', $doc_it->item_id)->last();
                $purchase_unit_price = ($purchase_item) ? $purchase_item->unit_price : $doc_it->unit_price;
                $purchase_currency_type = ($purchase_item) ? $purchase_item->purchase->currency_type_id : $doc_it->document->currency_type_id;

            }


            if($doc_it->document->currency_type_id == 'PEN'){

                $doc_total_purchase_pen = ($purchase_currency_type == 'PEN') ? ($purchase_unit_price * $doc_it->quantity):($purchase_unit_price * $doc_it->quantity * $doc_it->document->exchange_rate_sale);
                
                if(in_array($doc_it->document->document_type_id,['01','03','08'])){
    
                    $document_purchase_total_pen += $doc_total_purchase_pen;
                    $document_sale_total_pen += $doc_it->total;
    
                }else{

                    $document_purchase_total_pen -= $doc_total_purchase_pen;
                    $document_sale_total_pen -= $doc_it->total;

                } 

            }else{

                $doc_total_purchase_usd = ($purchase_currency_type == 'PEN') ? ($purchase_unit_price * $doc_it->quantity):(($purchase_unit_price / $doc_it->document->exchange_rate_sale) * $doc_it->quantity);
                
                if(in_array($doc_it->document->document_type_id,['01','03','08'])){

                    $document_purchase_total_usd += $doc_total_purchase_usd;
                    $document_sale_total_usd += $doc_it->total * $doc_it->document->exchange_rate_sale;
    
                }else{

                    $document_purchase_total_usd -= $doc_total_purchase_usd;
                    $document_sale_total_usd -= $doc_it->total * $doc_it->document->exchange_rate_sale;
                } 

            }


            
        }

        $document_utility_total_pen = $document_sale_total_pen - $document_purchase_total_pen;
        $document_utility_total_usd = $document_sale_total_usd - $document_purchase_total_usd;

        // dd($document_utility_total_pen, $document_utility_total_usd);
 
        return [

            'document_sale_total_pen' => round($document_sale_total_pen, 2),
            'document_purchase_total_pen' => round($document_purchase_total_pen, 2),

            'document_purchase_total_usd' => round($document_purchase_total_usd, 2),
            'document_sale_total_usd' => round($document_sale_total_usd, 2),
            
            'document_utility_total_pen' => round($document_utility_total_pen, 2),
            'document_utility_total_usd' => round($document_utility_total_usd, 2),

        ];
    }
 
 
}