<?php

namespace Modules\Report\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use PhpParser\Node\Stmt\Return_;

class GeneralItemCollection extends ResourceCollection
{


    public function toArray($request) {


        return $this->collection->transform(function($row, $key){

            $resource = self::getDocument($row);
            return [
                'id' => $row->id,
                'unit_type_id' => $row->item->unit_type_id,
                //'internal_id' => $row->item->internal_id ?? $row->item->item_code,
                'description' => $row->item->description,

                'lot_has_sale' => self::getLotsHasSale($row),

                'date_of_issue' => $resource['date_of_issue'],
                'customer_name' => $resource['customer_name'],
                'customer_number' => $resource['customer_number'],

                'series' => $resource['series'],
                'alone_number' => $resource['alone_number'],
                'quantity' => number_format($row->quantity,2),

                'total' => number_format($row->total,2),
                'document_type_description' => $resource['document_type_description'],
                'document_type_id' => $resource['document_type_id'],
            ];
        });
    }


    public static function getLotsHasSale($row)
    {
        if(isset($row->item->lots) )
        {
            return collect($row->item->lots)->where('has_sale', 1);
        }
        else
        {
            return [];
        }
    }

    public static function getDocument($row){

        $data = [];
        /*$data['quantity'] = number_format($row->quantity,2);
        $data['total'] = number_format($row->total,2);
        $data['unit_type_id'] = $row->item->unit_type_id;
        $data['description'] = $row->item->description;*/

        if($row->document){

            $data['date_of_issue'] = $row->document->date_of_issue->format('Y-m-d');
            $data['customer_name'] = $row->document->customer->name;
            $data['customer_number'] = $row->document->customer->number;
            $data['series'] = $row->document->series;
            $data['alone_number'] =  $row->document->number;
            $data['document_type_description'] = $row->document->document_type->description;
            $data['document_type_id'] = $row->document->document_type->id;

        }
        else if($row->purchase)
        {
            $data['date_of_issue'] = $row->purchase->date_of_issue->format('Y-m-d');
            $data['customer_name'] = $row->purchase->supplier->name;
            $data['customer_number'] = $row->purchase->supplier->number;
            $data['series'] = $row->purchase->series;
            $data['alone_number'] =  $row->purchase->number;
            $data['document_type_description'] = $row->purchase->document_type->description;
            $data['document_type_id'] = $row->purchase->document_type->id;

        }
        else if($row->sale_note)
        {
            $data['date_of_issue'] = $row->sale_note->date_of_issue->format('Y-m-d');
            $data['customer_name'] = $row->sale_note->customer->name;
            $data['customer_number'] = $row->sale_note->customer->number;
            $data['series'] = $row->sale_note->series;
            $data['alone_number'] =  $row->sale_note->number;
            $data['document_type_description'] = 'NOTA DE VENTA';
            $data['document_type_id'] = 80;
        }

        return $data;
    }


}
