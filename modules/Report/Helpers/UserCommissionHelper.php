<?php

namespace Modules\Report\Helpers;

use App\Models\Tenant\Document;
use App\Models\Tenant\SaleNote;
use App\Models\Tenant\Person;
use App\Models\Tenant\DocumentItem;
use App\Models\Tenant\PurchaseItem;
use App\Models\Tenant\SaleNoteItem;
use App\Models\Tenant\Purchase;
use App\Models\Tenant\Item;
use Carbon\Carbon;

class UserCommissionHelper
{

    public static function utilities_totals($document_items, $sale_note_items){

 
        $getTotalDocumentItems = $this->getTotalDocumentItems($document_items);
        $getTotalSaleNoteItems = $this->getTotalSaleNoteItems($sale_note_items);


        $total_income = $getTotalDocumentItems['document_sale_total'] + $getTotalSaleNoteItems['sale_note_sale_total'];
        $total_egress = $getTotalDocumentItems['document_purchase_total'] + $getTotalSaleNoteItems['sale_note_purchase_total'] ;
        $utility = $total_income - $total_egress;


        return [
            'totals' => [
                'total_income' => number_format($total_income,2, ".", ""),
                'total_egress' => number_format($total_egress,2, ".", ""),
                'utility' => number_format($utility,2, ".", ""),
            ] 
        ];

    }

 
    public static function getPurchaseUnitPrice($record){

        $purchase_unit_price = 0;

        if($record->item->unit_type_id != 'ZZ'){

            if($record->relation_item->purchase_unit_price > 0){

                $purchase_unit_price = $record->relation_item->purchase_unit_price;

            }else{

                $purchase_item = PurchaseItem::select('unit_price')->where('item_id', $record->item_id)->latest('id')->first();
                $purchase_unit_price = ($purchase_item) ? $purchase_item->unit_price : $record->unit_price;

            }

        }

        return $purchase_unit_price;
    }
    
    public function calculateTotalCurrencyType($record, $total)
    {
        return ($record->currency_type_id === 'USD') ? $total * $record->exchange_rate_sale : $total;
    }

    public static function getTotalSaleNoteItems($sale_note_items){

        $purchase_unit_price = 0;

        $sale_note_items->sum(function($row){
            

            return $this->calculateTotalCurrencyType($row->payment->associated_record_payment, $row->payment->payment);

        });

        //PEN
        $sale_note_sale_total_pen = 0;
        $sale_note_purchase_total_pen = 0;
        $sale_note_utility_total_pen = 0;

        //USD
        $sale_note_sale_total_usd = 0;
        $sale_note_purchase_total_usd = 0;
        $sale_note_utility_total_usd = 0;

        foreach ($sale_note_items as $sln) {

            $purchase_unit_price = $this->getPurchaseUnitPrice($sln);

            $sln_total_purchase = $purchase_unit_price * $sln->quantity;

            if($sln->sale_note->currency_type_id === 'PEN'){

                    $sale_note_purchase_total_pen += $sln_total_purchase;
                    $sale_note_sale_total_pen += $sln->total;

            }else{

                $sale_note_purchase_total_usd += $sln_total_purchase;
                $sale_note_sale_total_usd += $sln->total * $sln->sale_note->exchange_rate_sale;

            }

        }

        $sale_note_utility_total_pen = $sale_note_sale_total_pen - $sale_note_purchase_total_pen;
        $sale_note_utility_total_usd = $sale_note_sale_total_usd - $sale_note_purchase_total_usd;


        return [

            // 'sale_note_sale_total_pen' => round($sale_note_sale_total_pen, 2),
            // 'sale_note_purchase_total_pen' => round($sale_note_purchase_total_pen, 2),

            // 'sale_note_purchase_total_usd' => round($sale_note_purchase_total_usd, 2),
            // 'sale_note_sale_total_usd' => round($sale_note_sale_total_usd, 2),

            // 'sale_note_utility_total_pen' => round($sale_note_utility_total_pen, 2),
            // 'sale_note_utility_total_usd' => round($sale_note_utility_total_usd, 2),

            'sale_note_sale_total' => $sale_note_sale_total_usd + $sale_note_sale_total_pen,
            'sale_note_purchase_total' => $sale_note_purchase_total_usd + $sale_note_purchase_total_pen,

        ];
    }

    public static function getTotalDocumentItems($document_items){

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
            // if($doc_it->relation_item->purchase_unit_price > 0){

            //     $purchase_unit_price = $doc_it->relation_item->purchase_unit_price;
            //     // $purchase_currency_type = $doc_it->relation_item->currency_type_id;

            // }else{

            //     $purchase_item = PurchaseItem::select('unit_price')->where('item_id', $doc_it->item_id)->last();
            //     $purchase_unit_price = ($purchase_item) ? $purchase_item->unit_price : $doc_it->unit_price;
            //     // $purchase_currency_type = ($purchase_item) ? $purchase_item->purchase->currency_type_id : $doc_it->document->currency_type_id;

            // }
            $purchase_unit_price = $this->getPurchaseUnitPrice($doc_it);


            $doc_total_purchase = $purchase_unit_price * $doc_it->quantity;

            if($doc_it->document->currency_type_id === 'PEN'){

                // $doc_total_purchase_pen = ($purchase_currency_type == 'PEN') ? ($purchase_unit_price * $doc_it->quantity):($purchase_unit_price * $doc_it->quantity * $doc_it->document->exchange_rate_sale);

                if(in_array($doc_it->document->document_type_id,['01','03','08'])){

                    $document_purchase_total_pen += $doc_total_purchase;
                    $document_sale_total_pen += $doc_it->total;

                }else{

                    $document_purchase_total_pen -= $doc_total_purchase;
                    $document_sale_total_pen -= $doc_it->total;

                }

            }else{


                if(in_array($doc_it->document->document_type_id,['01','03','08'])){

                    $document_purchase_total_usd += $doc_total_purchase;
                    $document_sale_total_usd += $doc_it->total * $doc_it->document->exchange_rate_sale;

                }else{

                    $document_purchase_total_usd -= $doc_total_purchase;
                    $document_sale_total_usd -= $doc_it->total * $doc_it->document->exchange_rate_sale;
                }

            }



        }

        $document_utility_total_pen = $document_sale_total_pen - $document_purchase_total_pen;
        $document_utility_total_usd = $document_sale_total_usd - $document_purchase_total_usd;


        return [

            'document_sale_total_pen' => round($document_sale_total_pen, 2),
            'document_purchase_total_pen' => round($document_purchase_total_pen, 2),

            'document_purchase_total_usd' => round($document_purchase_total_usd, 2),
            'document_sale_total_usd' => round($document_sale_total_usd, 2),

            'document_utility_total_pen' => round($document_utility_total_pen, 2),
            'document_utility_total_usd' => round($document_utility_total_usd, 2),

            'document_sale_total' => $document_sale_total_usd + $document_sale_total_pen,
            'document_purchase_total' => $document_purchase_total_usd + $document_purchase_total_pen,

        ];
    }


}
