<?php

namespace Modules\Inventory\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Modules\Inventory\Models\InventoryTransaction;

class ReportKardexCollection extends ResourceCollection
{

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
                    'item_name' => $row->item->description,
                    'date_time' => $row->created_at->format('Y-m-d H:i:s'),
                    'date_of_issue' => isset($row->inventory_kardexable->date_of_issue) ? $row->inventory_kardexable->date_of_issue->format('Y-m-d') : '',
                    'type_transaction' => ($row->quantity < 0) ? "Venta":"Anulación Venta",
                    'number' => optional($row->inventory_kardexable)->series.'-'.optional($row->inventory_kardexable)->number,
                    'input' => ($row->quantity > 0) ?  $row->quantity:"-",
                    'output' => ($row->quantity < 0) ?  $row->quantity:"-",
                    'balance' => self::$balance+= $row->quantity,
                    'sale_note_asoc' => isset($row->inventory_kardexable->sale_note_id)  ? optional($row->inventory_kardexable)->sale_note->prefix.'-'.optional($row->inventory_kardexable)->sale_note->id:"-",
                ];
            
            case $models[1]:
                return [
                    'id' => $row->id,
                    'item_name' => $row->item->description,
                    'date_time' => $row->created_at->format('Y-m-d H:i:s'),
                    'date_of_issue' => isset($row->inventory_kardexable->date_of_issue) ? $row->inventory_kardexable->date_of_issue->format('Y-m-d') : '',
                    'type_transaction' => ($row->quantity < 0) ? "Anulación Compra":"Compra",
                    'number' => optional($row->inventory_kardexable)->series.'-'.optional($row->inventory_kardexable)->number,
                    'input' => ($row->quantity > 0) ?  $row->quantity:"-",
                    'output' => ($row->quantity < 0) ?  $row->quantity:"-",
                    'balance' => self::$balance+= $row->quantity,
                    'sale_note_asoc' => '-',
                ]; 
            
            case $models[2]: 
                return [
                    'id' => $row->id,
                    'item_name' => $row->item->description,
                    'date_time' => $row->created_at->format('Y-m-d H:i:s'),
                    'type_transaction' => "Nota de venta",
                    'date_of_issue' => isset($row->inventory_kardexable->date_of_issue) ? $row->inventory_kardexable->date_of_issue->format('Y-m-d') : '',
                    'number' => optional($row->inventory_kardexable)->prefix.'-'.optional($row->inventory_kardexable)->id,
                    'input' => "-",
                    'output' => $row->quantity,
                    'balance' => self::$balance+= $row->quantity,
                    'sale_note_asoc' => '-',
                ]; 
            
            case $models[3]:{

                $transaction = '';
                $input = '';
                $output = '';

                if(!$row->inventory_kardexable->type){
                    $transaction = InventoryTransaction::findOrFail($row->inventory_kardexable->inventory_transaction_id);
                }
                
                if($row->inventory_kardexable->type != null){
                    $input = ($row->inventory_kardexable->type == 1) ? $row->quantity : "-";                                                    
                }
                else{
                    $input = ($transaction->type == 'input') ? $row->quantity : "-" ; 
                }

                if($row->inventory_kardexable->type != null){
                    $output = ($row->inventory_kardexable->type == 2 || $row->inventory_kardexable->type == 3) ? $row->quantity : "-"; 
                }
                else{
                    $output = ($transaction->type == 'output') ? $row->quantity : "-";
                }

                return [
                    'id' => $row->id,
                    'item_name' => $row->item->description,
                    'date_time' => $row->created_at->format('Y-m-d H:i:s'),
                    'date_of_issue' => '-',
                    'type_transaction' => $row->inventory_kardexable->description,
                    'number' => "-",
                    'input' => $input,
                    'output' => $output,
                    'balance' => self::$balance+= $row->quantity,
                    'sale_note_asoc' => '-',
                ]; 
            } 

        }
        

    }
}