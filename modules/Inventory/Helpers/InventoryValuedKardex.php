<?php

namespace Modules\Inventory\Helpers;


use App\Models\Tenant\Item;
use App\Models\Tenant\{
    DocumentItem,
    DispatchItem,
    PurchaseItem,
};

class InventoryValuedKardex
{

    public static function getTransformRecords($records)
    {

        return $records->transform(function ($row, $key) {
            /** @var Item $row */
            return $row->getReportValuedKardexCollection();
            /*** Movido al modelo **/
            $values_records = self::getValuesRecords($row->document_items, $row->sale_note_items);

            $quantity_sale = $values_records['quantity_sale'];
            $total_sales = $values_records['total_sales'];

            $item_cost = $quantity_sale * $row->purchase_unit_price;
            $valued_unit = $total_sales - $item_cost;

            return [

                'id' => $row->id,
                'item_description' => $row->description,
                'category_description' => optional($row->category)->name,
                'brand_description' => optional($row->brand)->name,
                'unit_type_id' => $row->unit_type_id,
                'quantity_sale' => number_format($quantity_sale,2, ".", ""),
                'purchase_unit_price' => number_format($row->purchase_unit_price,2, ".", ""),
                'total_sales' => number_format($total_sales,2, ".", ""),
                'item_cost' => number_format($item_cost,2, ".", ""),
                'valued_unit' => number_format($valued_unit,2, ".", ""),
                'warehouses' => $row->warehouses->transform(function($row, $key){
                    return [
                        'id' => $row->id,
                        'stock' => $row->stock,
                        'warehouse_description' => $row->warehouse->description,
                        'description' => "{$row->warehouse->description} | {$row->stock}",
                    ];
                }),

            ];

        });

    }

    public static function getValuesRecords($document_items, $sale_note_items)
    {

        //quantity
        $quantity_doc_items = $document_items->sum(function($row){
            return ($row->document->document_type_id == '07') ? -$row->quantity : $row->quantity;
        });

        $quantity_sln_items = $sale_note_items->sum('quantity');

        $quantity_sale = $quantity_doc_items + $quantity_sln_items;


        //totals
        $sales_documents = $document_items->sum(function($row){
            $value_currency = self::calculateTotalCurrencyType($row->document, $row->total);
            return ($row->document->document_type_id == '07') ? -$value_currency : $value_currency;
        });

        $sales_sale_notes = $sale_note_items->sum(function($row){
            return self::calculateTotalCurrencyType($row->sale_note, $row->total);
        });

        $total_sales = $sales_documents + $sales_sale_notes;


        return [
            'quantity_sale' => $quantity_sale,
            'total_sales' => $total_sales,
        ];

    }


    public static function calculateTotalCurrencyType($record, $amount)
    {
        return ($record->currency_type_id === 'USD') ? $amount * $record->exchange_rate_sale : $amount;
    }


    public static function getRecordsFormatSunat($params)
    {

        // dd($params);
        $item = Item::whereFilterValuedKardexFormatSunat($params)->findOrFail($params->item_id);

        $purchase_items = $item->purchase_item;
        $document_items = $item->document_items;
        $dispatch_items = $item->dispatch_items;
        
        $all_record_items = ($document_items->merge($dispatch_items))->merge($purchase_items);

        // dd(self::transformRecordItems($all_record_items)->pluck('balance_quantity'));

        return self::transformRecordItems($all_record_items);

    }
 

    private static function transformRecordItems($collection)
    {

        return $collection->transform(function($record_item){

            $data = [];

            if($record_item instanceof DocumentItem){

                $document = $record_item->document;

                $data = [
                    'type' => 'output',
                    'model_type' => 'document',
                    'date_of_issue' => $document->date_of_issue->format('d-m-Y'),
                    'document_type_id' => $document->document_type_id,
                    'series' => $document->series,
                    'number' => $document->number,
                    'operation_type' => 'VENTA',
                    'operation_type_code' => '01',
                    'input_quantity' => null,
                    'input_unit_price' => null,
                    'input_total' => null,
                    'output_quantity' => $record_item->quantity,
                    'output_unit_price' => $record_item->unit_price,
                    'output_total' => $record_item->total,
                ];


            }else if($record_item instanceof PurchaseItem){

                $document = $record_item->purchase;

                $data = [
                    'type' => 'input',
                    'model_type' => 'purchase',
                    'date_of_issue' => $document->date_of_issue->format('d-m-Y'),
                    'document_type_id' => $document->document_type_id,
                    'series' => $document->series,
                    'number' => $document->number,
                    'operation_type' => 'COMPRA',
                    'operation_type_code' => '02',
                    'input_quantity' => $record_item->quantity,
                    'input_unit_price' => $record_item->unit_price,
                    'input_total' => $record_item->total,
                    'output_quantity' => null,
                    'output_unit_price' => null,
                    'output_total' => null,
                ];

            }else if($record_item instanceof DispatchItem){

                $type = ($record_item->dispatch->transfer_reason_type_id == '01') ? 'output' : 'input';
                $document = $record_item->dispatch;


                $input_quantity = null;
                $input_unit_price = null;
                $input_total = null;
                $output_quantity = null;
                $output_unit_price = null;
                $output_total = null;
                $operation_type = null;
                
                if($type == 'input'){
                    
                    $input_quantity =  $record_item->quantity;
                    $input_unit_price =  $record_item->relation_item->purchase_unit_price;
                    $input_total = $record_item->quantity * $record_item->relation_item->purchase_unit_price;
                    $operation_type = 'COMPRA';

                }else{

                    $output_quantity =  $record_item->quantity;
                    $output_unit_price =  $record_item->relation_item->sale_unit_price;
                    $output_total =  $record_item->quantity * $record_item->relation_item->sale_unit_price;
                    $operation_type = 'VENTA';

                }
                
                $data = [
                    'type' => $type,
                    'model_type' => 'dispatch',
                    'date_of_issue' => $document->date_of_issue->format('d-m-Y'),
                    'document_type_id' => $document->document_type_id,
                    'series' => $document->series,
                    'number' => $document->number,
                    'operation_type' => $operation_type,
                    'operation_type_code' => $record_item->dispatch->transfer_reason_type_id,
                    'input_quantity' =>  $input_quantity,
                    'input_unit_price' => $input_unit_price,
                    'input_total' => $input_total,
                    'output_quantity' => $output_quantity,
                    'output_unit_price' => $output_unit_price,
                    'output_total' => $output_total,
                ];

            }

            return $data;
        });
    }

}
