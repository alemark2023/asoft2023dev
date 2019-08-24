<?php

namespace App\Http\Resources\Tenant;

use Illuminate\Http\Resources\Json\ResourceCollection;

class QuotationCollection extends ResourceCollection
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
        
            $btn_generate = (count($row->documents) > 0 || count($row->sale_notes) > 0)?false:true;

            return [
                'id' => $row->id, 
                'soap_type_id' => $row->soap_type_id,
                'external_id' => $row->external_id,
                'date_of_issue' => $row->date_of_issue->format('Y-m-d'),
                'identifier' => $row->identifier,
                'customer_name' => $row->customer->name,
                'customer_number' => $row->customer->number,
                'currency_type_id' => $row->currency_type_id,
                // 'total_exportation' => $row->total_exportation,
                // 'total_free' => $row->total_free,
                // 'total_unaffected' => $row->total_unaffected,
                // 'total_exonerated' => $row->total_exonerated,
                'total_taxed' => $row->total_taxed,
                'total_igv' => $row->total_igv,
                'total' => $row->total,
                'state_type_id' => $row->state_type_id, 
                'state_type_description' => $row->state_type->description, 
                'documents' => $row->documents->transform(function($row) {
                    return [
                        'number_full' => $row->number_full, 
                    ];
                }),
                'sale_notes' => $row->sale_notes->transform(function($row) {
                    return [
                        'identifier' => $row->identifier,
                    ];
                }),
                'btn_generate' => $btn_generate,
                'created_at' => $row->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $row->updated_at->format('Y-m-d H:i:s'),
            ];
        });
    }
    
}
