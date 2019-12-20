<?php

namespace Modules\Dashboard\Traits;

use App\Models\Tenant\Document;
use App\Models\Tenant\DocumentPayment;
use App\Models\Tenant\SaleNote;
use App\Models\Tenant\SaleNotePayment;
use Carbon\Carbon;
use App\Models\Tenant\Person;
use App\Models\Tenant\Purchase;
use Modules\Expense\Models\Expense;

trait TotalsTrait
{

    public function get_purchase_totals($establishment_id, $date_start, $date_end)
    {

        if($date_start && $date_end){
            
            $purchases = Purchase::query()->whereIn('state_type_id', ['01','03','05','07','13'])
                                        ->where('establishment_id', $establishment_id)
                                        ->whereBetween('date_of_issue', [$date_start, $date_end])
                                        ->get();

        }else{

            $purchases = Purchase::query()->whereIn('state_type_id', ['01','03','05','07','13'])
                                        ->where('establishment_id', $establishment_id)
                                        ->get();
        }

        
        $purchases_total = $purchases->sum('total') + $purchases->sum('total_perception');

        $purchase_total_payment = 0;

        foreach ($purchases as $purchase)
        {
            $purchase_total_payment += collect($purchase->purchase_payments)->sum('payment');
        }

        return [
            'totals' => [
                'total_payment' => round($purchase_total_payment,2),
                'total' => round($purchases_total,2),
            ] 
        ];
    }

    
    public function get_expense_totals($establishment_id, $date_start, $date_end)
    {

        
        if($date_start && $date_end){
            
            $expenses = Expense::query()->where('establishment_id', $establishment_id)
                                        ->whereBetween('date_of_issue', [$date_start, $date_end])
                                        ->get();

        }else{

            $expenses = Expense::query()->where('establishment_id', $establishment_id)
                                        ->get();
        }
        
        $expenses_total = $expenses->sum('total');

        $expense_total_payment = 0;

        foreach ($expenses as $expense)
        {
            $expense_total_payment += collect($expense->payments)->sum('payment');
        }

        return [
            'totals' => [
                'total_payment' => round($expense_total_payment,2),
                'total' => round($expenses_total,2),
            ] 
        ];

    }

    
    public function get_sale_note_totals($establishment_id, $date_start, $date_end)
    {

        if($date_start && $date_end){
            $sale_notes = SaleNote::query()->where('establishment_id', $establishment_id)
                                           ->where('changed', false)
                                           ->whereBetween('date_of_issue', [$date_start, $date_end])->get();
        }else{
            $sale_notes = SaleNote::query()->where('establishment_id', $establishment_id)
                                           ->where('changed', false)->get();
        }

        $sale_note_total = collect($sale_notes)->sum('total');

        $sale_note_total_payment = 0;
        foreach ($sale_notes as $sale_note)
        {
            $sale_note_total_payment += collect($sale_note->payments)->sum('payment');
        }

        return [
            'totals' => [
                'total_payment' => round($sale_note_total_payment,2),
                'total' => round($sale_note_total,2),
            ] 
        ];
    }


    public function get_document_totals($establishment_id, $date_start, $date_end)
    {

        if($date_start && $date_end){
            $documents = Document::query()->where('establishment_id', $establishment_id)->whereBetween('date_of_issue', [$date_start, $date_end])->get();
        }else{
            $documents = Document::query()->where('establishment_id', $establishment_id)->get();
        }
        $document_total = collect($documents->whereIn('state_type_id', ['01','03','05','07','13'])->whereIn('document_type_id', ['01','03','08']))->sum('total');

        $document_total_note_credit = 0;
        $document_total_payment = 0;

        foreach ($documents as $document)
        {
            if(in_array($document->state_type_id,['01','03','05','07','13']))
                $document_total_payment += collect($document->payments)->sum('payment');

            $document_total_note_credit += ($document->document_type_id == '07') ? $document->total:0; //nota de credito
        }

        $document_total = round(($document_total - $document_total_note_credit),2);

        return [
            'totals' => [
                'total_payment' => round($document_total_payment,2),
                'total' => round($document_total,2),
            ] 
        ];
    }
 
}