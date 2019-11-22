<?php

namespace Modules\Inventory\Traits;

use Modules\Inventory\Models\{
    ItemWarehouse,
    Warehouse,
    InventoryConfiguration,
    InventoryTransaction,
    Inventory
};
use App\Models\Tenant\{
    Configuration,
    Establishment,
    SaleNoteItem,
    Item
};
use Exception;

trait InventoryTrait
{
    public function optionsEstablishment() {
        $records = Establishment::all();
        
        return collect($records)->transform(function($row) {
            return  [
                'id' => $row->id,
                'description' => $row->description
            ];
        });
    }
    
    public function optionsItem() {
        $records = Item::where([['item_type_id', '01'], ['unit_type_id', '!=','ZZ']])->whereNotIsSet()->get();
        
        return collect($records)->transform(function($row) {
            return  [
                'id' => $row->id,
                'description' => $row->description
            ];
        });
    }

    
    public function optionsItemFull() {
        $records = Item::where([['item_type_id', '01'], ['unit_type_id', '!=','ZZ']])->whereNotIsSet()->get();
        
        return collect($records)->transform(function($row) {
            return  [
                'id' => $row->id,
                'description' => ($row->internal_id) ? "{$row->internal_id} - {$row->description}" :$row->description
            ];
        });
    }

    public function findInventoryTransaction($id) {

        return InventoryTransaction::findOrFail($id);
        
    }
    

    public function optionsInventoryTransaction($type) {

        $records = InventoryTransaction::where('type', $type)->get();
        
        return $records;
    }
    
    public function optionsWarehouse() {
        $records = Warehouse::all();
        
        return collect($records)->transform(function($row) {
            return  [
                'id' => $row->id,
                'description' => $row->description
            ];
        });
    }
    
    public function findItem($item_id) {
        return Item::find($item_id);
    }
    
    public function findWarehouse($establishment_id = null) {
        if ($establishment_id) {
            $establishment = Establishment::find($establishment_id);
        }
        else {
            $establishment = auth()->user()->establishment;
        }
        
        return Warehouse::firstOrCreate([
            'establishment_id' => $establishment->id
        ], [
            'description' => 'Almacén '.$establishment->description
        ]);
    }
    
    private function createInitialInventory($item_id, $quantity, $warehouse_id) {
        return Inventory::create([
            'type' => 1,
            'description' => 'Stock inicial',
            'item_id' => $item_id,
            'warehouse_id' => $warehouse_id,
            'quantity' => $quantity
        ]);
    }
    
    private function createInventoryKardex($model, $item_id, $quantity, $warehouse_id) {
        $model->inventory_kardex()->create([
            'date_of_issue' => date('Y-m-d'),
            'item_id' => $item_id,
            'warehouse_id' => $warehouse_id,
            'quantity' => $quantity,
        ]);
    }
    

    private function updateStock($item_id, $quantity, $warehouse_id) {

        $inventory_configuration = InventoryConfiguration::firstOrFail();
        
        $item_warehouse = ItemWarehouse::firstOrNew(['item_id' => $item_id, 'warehouse_id' => $warehouse_id]);
        $item_warehouse->stock = $item_warehouse->stock + $quantity;
        
        // dd($item_warehouse->item->unit_type_id);

        if($quantity < 0 && $item_warehouse->item->unit_type_id !== 'ZZ'){
            if (($inventory_configuration->stock_control) && ($item_warehouse->stock < 0)){             
                throw new Exception("El producto {$item_warehouse->item->description} no tiene suficiente stock!");
            }
        }
        $item_warehouse->save();
    }
    
    public function checkInventory($item_id, $warehouse_id) {
        $inventory = Inventory::where('item_id', $item_id)
            ->where('warehouse_id', $warehouse_id)
            ->first();

        return ($inventory)?true:false;
    }
    
    public function initializeInventory() {
//        $establishments = Establishment::all();
//        foreach ($establishments as $establishment)
//        {
//            Warehouse::firstOrCreate(['establishment_id' => $establishment->id],
//                                     ['description' => $establishment->description]);
//        }
        
        $warehouse = $this->findWarehouse();
        $items = Item::all();
        
        foreach ($items as $item) {
            if (!$this->checkInventory($item->id, $warehouse->id)) {
                $inventory = $this->createInitialInventory($item->id, $item->stock, $warehouse->id);
//                $this->createInventoryKardex($inventory, $item->id, $item->stock, $warehouse->id);
//                $this->updateStock($item->id, $item->stock, $warehouse->id);
            }
        }
    }

    public function findWarehouseById($warehouse_id) {
        return Warehouse::findOrFail($warehouse_id);
    }

    
    ////kardex sale note
    public function findSaleNoteItem($sale_note_item_id) {
        return SaleNoteItem::find($sale_note_item_id);
    }
    
    private function createInventoryKardexSaleNote($model, $item_id, $quantity, $warehouse_id, $sale_note_item_id) {

        $sale_note_kardex = $model->inventory_kardex()->create([
            'date_of_issue' => date('Y-m-d'),
            'item_id' => $item_id,
            'warehouse_id' => $warehouse_id,
            'quantity' => $quantity,
        ]);

        $sale_note_item = $this->findSaleNoteItem($sale_note_item_id);
        $sale_note_item->inventory_kardex_id = $sale_note_kardex->id;
        $sale_note_item->update();
    }

    private function deleteInventoryKardex($model, $inventory_kardex_id) {
        $model->inventory_kardex()->where('id',$inventory_kardex_id)->delete();
    }
    ////kardex sale note

    private function deleteAllInventoryKardexByModel($model) {
        $model->inventory_kardex()->delete();
    }

}
