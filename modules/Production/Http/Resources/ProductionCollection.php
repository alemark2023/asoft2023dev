<?php

    namespace Modules\Production\Http\Resources;


use Illuminate\Http\Resources\Json\ResourceCollection;
use Modules\Production\Models\Mill;
use Modules\Production\Models\Production;

class ProductionCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->collection->transform(function( Production $row, $key) {

            //$data = $row->toArray();
            //$data['item'] = $row->item->getDataToItemModal();
            //return $data;
            // @todo coleccion de descarga


            return [
                'id' => $row->id,
                'user' => $row->user->name,
                'quantity' => $row->quantity,
                'item_name' => $row->item->description,
                'created_at' => $row->created_at->format('Y-m-d H:i:s'),
            ];
        });
    }

}
