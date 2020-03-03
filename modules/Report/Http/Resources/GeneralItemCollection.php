<?php

namespace Modules\Report\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class GeneralItemCollection extends ResourceCollection
{
     

    public function toArray($request) {
        

        return $this->collection->transform(function($row, $key){ 
             
               
            return [
                'id' => $row->id,
                'unit_type_id' => $row->item->unit_type_id,
                'internal_id' => $row->item->internal_id ?? $row->item->item_code,
                'description' => $row->item->description,
                'date_of_issue' => $row->document ? $row->document->date_of_issue->format('Y-m-d'):$row->purchase->date_of_issue->format('Y-m-d'),
                'customer_name' => $row->document ? $row->document->customer->name:$row->purchase->supplier->name,
                'customer_number' => $row->document ? $row->document->customer->number:$row->purchase->supplier->number,
                'series' => $row->document ? $row->document->series: $row->purchase->series,
                'alone_number' => $row->document ? $row->document->number:$row->purchase->number,
                'quantity' => number_format($row->quantity,2),
                'total' => number_format($row->total,2),
                'document_type_description' => $row->document ? $row->document->document_type->description :$row->purchase->document_type->description,
                'document_type_id' => $row->document ? $row->document->document_type->id:$row->purchase->document_type->id,   
            ];
        });
    }
}
