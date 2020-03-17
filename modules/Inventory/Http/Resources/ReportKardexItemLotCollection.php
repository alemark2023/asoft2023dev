<?php

namespace Modules\Inventory\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Carbon\Carbon;


class ReportKardexItemLotCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function toArray($request)
    {
        return $this->collection->transform(function($row, $key)  {

            return [
                'id' => $row->id,
                'series' => $row->series,
                'name_item' => $row->item->description,
                'und_item' => $row->item->unit_type_id,
                'code_item' => $row->item->internal_id,
                'status' => ($row->has_sale == 1 ? 'VENDIDO' : 'DISPONIBLE'),
                'date' => $row->date
            ];
        });
    }




}
