<?php

namespace Modules\Dashboard\Helpers;

use App\Models\Tenant\Document;
use App\Models\Tenant\SaleNote;
use App\Models\Tenant\Person;
use App\Models\Tenant\DocumentItem;
use App\Models\Tenant\SaleNoteItem;
use App\Models\Tenant\Purchase;
use App\Models\Tenant\Item;
use Carbon\Carbon;

class DashboardSalePurchase
{
    public function data()
    {
        return [
            'purchase' => $this->purchase_totals(),
            'items_by_sales' => $this->items_by_sales(),
            'top_customers' => $this->top_customers(),
        ];
    }

    private function top_customers(){

        $documents = Document::get(); 
        $sale_notes = SaleNote::get(); 

        foreach ($sale_notes as $sn) { 
            $documents->push($sn);
        }

        $all_records = $documents;

        $group_customers = $all_records->groupBy('customer_id');

        $top_customers = collect([]);

        foreach ($group_customers as $customers) {  

            $customer = Person::where('type','customers')->find($customers[0]->customer_id);

            $top_customers->push([
                'total' => round($customers->sum('total'),2),
                'name' => $customer->name,
                'number' => $customer->number,
            ]);    
        
        }

        $sorted = $top_customers->sortByDesc('total');

        return $sorted->values()->take(10);
        
    }
 
    private function purchase_totals()
    {
        $purchases = Purchase::get();

        $purchases_total = round($purchases->sum('total'),2);
 
      
        $data_array = ['Ene', 'Feb','Mar', 'Abr','May', 'Jun','Jul', 'Ago','Sep', 'Oct', 'Nov', 'Dic'];

        $purchases_by_month = $purchases->groupBy(function($date) { 
                                return Carbon::parse($date->date_of_issue)->format('m');  
                            }); 
 
                            
        return [
            'totals' => [ 
                'total' => $purchases_total,
            ],
            'graph' => [
                'labels' => $data_array,
                'datasets' => [ 
                    [
                        'label' => 'Total compras',
                        'data' => $this->arrayPurchasesbyMonth($purchases_by_month),
                        'backgroundColor' => 'rgb(54, 162, 235)',
                        'borderColor' => 'rgb(54, 162, 235)',
                        'borderWidth' => 1,
                        'fill' => false,
                        'lineTension' => 0,
                    ],
                    [
                        'label' => 'Total',
                        'data' => $data_array,
                        'backgroundColor' => 'rgb(201, 203, 207)',
                        'borderColor' => 'rgb(201, 203, 207)',
                        'borderWidth' => 1,
                        'fill' => false,
                        'lineTension' => 0,
                    ]

                ],
            ]
        ];
    }


    
    private function items_by_sales(){

        $document_items = DocumentItem::get(); 
        $sale_note_items = SaleNoteItem::get(); 

        foreach ($sale_note_items as $sni) { 
            $document_items->push($sni);
        }

        $all_items = $document_items;
        $group_items = $all_items->groupBy('item_id');

        $items_by_sales = collect([]);

        foreach ($group_items as $items) {  

            $item = Item::find($items[0]->item_id);

            $items_by_sales->push([
                'total' => round($items->sum('total'),2),
                'description' => $item->description,
                'internal_id' => $item->internal_id,
            ]);    
        
        }

        $sorted = $items_by_sales->sortByDesc('total');

        return $sorted->values()->take(10);

    }

    /**
     * @param $purchases
     * @return array
     */
    private function arrayPurchasesbyMonth($purchases_by_month){

        return [
            isset($purchases_by_month['01']) ? round($purchases_by_month['01']->sum('total'), 2) : 0, 
            isset($purchases_by_month['02']) ? round($purchases_by_month['02']->sum('total'), 2) : 0, 
            isset($purchases_by_month['03']) ? round($purchases_by_month['03']->sum('total'), 2) : 0, 
            isset($purchases_by_month['04']) ? round($purchases_by_month['04']->sum('total'), 2) : 0, 
            isset($purchases_by_month['05']) ? round($purchases_by_month['05']->sum('total'), 2) : 0, 
            isset($purchases_by_month['06']) ? round($purchases_by_month['06']->sum('total'), 2) : 0, 
            isset($purchases_by_month['07']) ? round($purchases_by_month['07']->sum('total'), 2) : 0, 
            isset($purchases_by_month['08']) ? round($purchases_by_month['08']->sum('total'), 2) : 0, 
            isset($purchases_by_month['09']) ? round($purchases_by_month['09']->sum('total'), 2) : 0, 
            isset($purchases_by_month['10']) ? round($purchases_by_month['10']->sum('total'), 2) : 0, 
            isset($purchases_by_month['11']) ? round($purchases_by_month['11']->sum('total'), 2) : 0, 
            isset($purchases_by_month['12']) ? round($purchases_by_month['12']->sum('total'), 2) : 0
        ];

    }
 
 
 
}