<?php

namespace Modules\Report\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CommercialAnalysisCollection extends ResourceCollection
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
             
            $customer = $row;
            $documents = $row->documents;

            $country =($customer->country_id)? $customer->country->description : '' ;
            $district = ($customer->district_id)? '-'.$customer->district->description : '' ;
            $province = ($customer->province_id)? '-'.$customer->province->description : '' ;
            $department = ($customer->department_id)? '-'.$customer->department->description : '' ;
 
               
            return [
                'id' => $row->id, 
                'number' => $row->number_full,
                'customer_name' => $row->name,
                'customer_doc' => $row->identity_document_type->description,
                'customer_number' => $row->number,
                'zone' => "{$country} {$department} {$province} {$district}",
                'telephone' => $row->telephone,
                'first_document' => ($documents) ? $documents->first():'-',
                'last_document' => ($documents) ? $documents->last():'-',

 
 
 

            ];
        });
    }
    
}
