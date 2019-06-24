<?php

namespace Modules\Inventory\Providers;

use App\Models\Tenant\Item; 
use Illuminate\Support\ServiceProvider;
use Modules\Inventory\Models\Inventory;
use Modules\Inventory\Traits\InventoryTrait;

class InventoryChangeServiceProvider extends ServiceProvider
{
    use InventoryTrait;
    
    public function register()
    {
    }

    public function boot()
    {
        $this->createdItem();
        $this->inventory();
    }

    private function createdItem()
    {
        Item::created(function ($item) {
            $warehouse = $this->findWarehouse();
            $this->createInitialInventory($item->id, $item->stock, $warehouse->id);
        });
    }

    private function inventory()
    {
        Inventory::created(function ($inventory) {
            switch ($inventory->type) {
                case 1:
                    $this->createInventoryKardex($inventory, $inventory->item_id, $inventory->quantity, $inventory->warehouse_id);
                    $this->updateStock($inventory->item_id, $inventory->quantity, $inventory->warehouse_id);
                    break;
                case 2:
                    //Origin
                    $this->createInventoryKardex($inventory, $inventory->item_id, -1 * $inventory->quantity, $inventory->warehouse_id);
                    $this->updateStock($inventory->item_id, -1 * $inventory->quantity, $inventory->warehouse_id);
                    //Arrival
                    $this->createInventoryKardex($inventory, $inventory->item_id, $inventory->quantity, $inventory->warehouse_destination_id);
                    $this->updateStock($inventory->item_id, $inventory->quantity, $inventory->warehouse_destination_id);
                    break;
                case 3:
                    $this->createInventoryKardex($inventory, $inventory->item_id, -1 * $inventory->quantity, $inventory->warehouse_id);
                    $this->updateStock($inventory->item_id, -1 * $inventory->quantity, $inventory->warehouse_id);
                    break;
            }
        });
    }

}