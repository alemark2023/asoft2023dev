<?php

namespace Modules\Inventory\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ReportKardexCollection extends ResourceCollection
{
    //funciona correctamente, usar cuando se cambie a vue
    protected static $balance = 0;

    public function toArray($request)
    { 
        return $this->collection->transform(function($row, $key) {

            return self::determinateRow($row);

        });
    }

    public static function determinateRow($row){

        $models = [
            "App\Models\Tenant\Document", 
            "App\Models\Tenant\Purchase", 
            "App\Models\Tenant\SaleNote", 
            "Modules\Inventory\Models\Inventory"
        ];

        switch ($row->inventory_kardexable_type) {
            case $models[0]: 
                return [
                    'id' => $row->id,
                    'date_time' => $row->created_at,
                    'type_transaction' => ($row->quantity < 0) ? "Venta":"Anulación",
                    'number' => "{$row->inventory_kardexable->series}-{$row->inventory_kardexable->number}",
                    'input' => "-",
                    'output' => $row->quantity,
                    'balance' => self::$balance+= $row->quantity,
                ];
            
            case $models[1]:
                return [
                    'id' => $row->id,
                    'date_time' => $row->created_at,
                    'type_transaction' => "Compra",
                    'number' => "{$row->inventory_kardexable->series}-{$row->inventory_kardexable->number}",
                    'input' => $row->quantity,
                    'output' => "-",
                    'balance' => self::$balance+= $row->quantity,
                ]; 
            
            case $models[2]: 
                return [
                    'id' => $row->id,
                    'date_time' => $row->created_at,
                    'type_transaction' => "Nota de venta",
                    'number' => "{$row->inventory_kardexable->prefix}-{$row->inventory_kardexable->id}",
                    'input' => "-",
                    'output' => $row->quantity,
                    'balance' => self::$balance+= $row->quantity,
                ]; 
            
            case $models[3]:
                return [
                    'id' => $row->id,
                    'date_time' => $row->created_at,
                    'type_transaction' => $row->inventory_kardexable->description,
                    'number' => "-",
                    'input' => ($row->inventory_kardexable->type == 1) ? $row->quantity : "-",
                    'output' => ($row->inventory_kardexable->type == 2 || $row->inventory_kardexable->type == 3) ? $row->quantity : "-",
                    'balance' => self::$balance+= $row->quantity,
                ];  

        }
        

    }
}