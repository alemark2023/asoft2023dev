<?php

namespace Modules\Report\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ReportCommissionCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->collection->transform(function($row, $key) {
            
            $total_commision = 0;
            $total_commision_document = 0;
            $total_commision_sale_note = 0;

            foreach ($row->documents as $document) {
                // dd($document->items);

                // $total_commision_document += $document->items->sum('relation_item.commission_amount'); 

                $document->items->each(function ($item, $key) use($total_commision_document){
                    if ($item->relation_item->commission_amount) {
                        $total_commision_document += $item->quantity * $item->relation_item->commission_amount;
                    }
                });
            }

            foreach ($row->sale_notes as $sale_note) {
                // dd($document->items);
                // $total_commision_sale_note += $sale_note->items->sum('relation_item.commission_amount'); 

                $sale_note->items->each(function ($item, $key) use ($total_commision_sale_note){
                    if ($item->relation_item->commission_amount) {
                        $total_commision_sale_note += $item->quantity * $item->relation_item->commission_amount;
                    }
                });
            }

            return [
                'id' => $row->id,  
                'total_commision_sale_note' => $total_commision_sale_note,
                'total_commision_document' => $total_commision_document,
            ];
        });
    }
    
}
