<?php

namespace Modules\Report\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class OrderNoteConsolidatedCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        
        return $this->collection->transform(function($row, $key){ 
             
            return [
                'id' => $row->id,
                'delivery_date' => $row->order_note->delivery_date,  
                'item_description' => $row->item->description,  
                'item_quantity' => $row->quantity,  
            ];
        });
    }
    
}
