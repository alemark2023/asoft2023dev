<?php

namespace Modules\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
//use App\Models\Tenant\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Inventory\Http\Resources\InventoryCollection;
use Modules\Inventory\Http\Resources\InventoryResource;
use Modules\Inventory\Models\Inventory;
use Modules\Inventory\Traits\InventoryTrait;
use Modules\Inventory\Models\ItemWarehouse;
use Modules\Inventory\Models\Warehouse;

class InventoryController extends Controller
{
    use InventoryTrait;

    public function index()
    {
        return view('inventory::inventory.index');
    }
    
    public function columns()
    {
        return [
            'item_id' => 'Producto'
        ];
    }
    
    public function records(Request $request)
    {
        $item_description = $request->input('value');
        $records = ItemWarehouse::with(['item', 'warehouse'])
                                ->whereHas('item', function($query) use($item_description) {
                                    $query->where('description', 'like', '%' . $item_description . '%');
                                })->orderBy('item_id');

        return new InventoryCollection($records->paginate(config('tenant.items_per_page')));
    }

    public function tables()
    {
        return [
            'items' => $this->optionsItem(),
            'warehouses' => $this->optionsWarehouse()
        ];
    }

    public function record($id)
    {
        $record = new InventoryResource(ItemWarehouse::with(['item', 'warehouse'])->findOrFail($id));

        return $record;
    }

    public function store(Request $request)
    {
        $result = DB::connection('tenant')->transaction(function () use ($request) {
            $item_id = $request->input('item_id');
            $warehouse_id = $request->input('warehouse_id');
            $quantity = $request->input('quantity');

            $item_warehouse = ItemWarehouse::firstOrNew(['item_id' => $item_id,
                                                         'warehouse_id' => $warehouse_id]);
            if($item_warehouse->id) {
                return [
                    'success' => false,
                    'message' => 'El producto ya se encuentra registrado en el almacén indicado.'
                ];
            }

            // $item_warehouse->stock = $quantity;
            // $item_warehouse->save();

            $inventory = new Inventory();
            $inventory->type = 1;
            $inventory->description = 'Stock inicial';
            $inventory->item_id = $item_id;
            $inventory->warehouse_id = $warehouse_id;
            $inventory->quantity = $quantity;
            $inventory->save();

            return  [
                'success' => true,
                'message' => 'Producto registrado en almacén'
            ];
        });

        return $result;
    }

    public function move(Request $request)
    {
        $result = DB::connection('tenant')->transaction(function () use ($request) {
            $id = $request->input('id');
            $item_id = $request->input('item_id');
            $warehouse_id = $request->input('warehouse_id');
            $warehouse_new_id = $request->input('warehouse_new_id');
            $quantity = $request->input('quantity');
            $quantity_move = $request->input('quantity_move');

            if($warehouse_id === $warehouse_new_id) {
                return  [
                    'success' => false,
                    'message' => 'El almacén destino no puede ser igual al de origen'
                ];
            }
            if($quantity < $quantity_move) {
                return  [
                    'success' => false,
                    'message' => 'La cantidad a trasladar no puede ser mayor al que se tiene en el almacén.'
                ];
            }
            
            //Transaction
            // $item_warehouse_new = ItemWarehouse::firstOrNew(['item_id' => $item_id,
            //                                                  'warehouse_id' => $warehouse_new_id]);

            // $stock_new = ($item_warehouse_new)?$item_warehouse_new->stock + $quantity_move:$quantity_move;
            // $item_warehouse_new->stock = $stock_new;
            // $item_warehouse_new->save();

            // $item_warehouse = ItemWarehouse::find($id);
            // $item_warehouse->stock = (float) $quantity - (float)$quantity_move;
            // $item_warehouse->save();

            $inventory = new Inventory();
            $inventory->type = 2;
            $inventory->description = 'Traslado';
            $inventory->item_id = $item_id;
            $inventory->warehouse_id = $warehouse_id;
            $inventory->warehouse_destination_id = $warehouse_new_id;
            $inventory->quantity = $quantity_move;
            $inventory->save();

            return  [
                'success' => true,
                'message' => 'Producto trasladado con éxito'
            ];
        });

        return $result;
    }

    public function remove(Request $request)
    {
        $result = DB::connection('tenant')->transaction(function () use ($request) {
            $item_id = $request->input('item_id');
            $warehouse_id = $request->input('warehouse_id');
            $quantity = $request->input('quantity');
            $quantity_remove = $request->input('quantity_remove');

            //Transaction
            $item_warehouse = ItemWarehouse::where('item_id', $item_id)
                                           ->where('warehouse_id', $warehouse_id)
                                           ->first();
            if(!$item_warehouse) {
                return [
                    'success' => false,
                    'message' => 'El producto no se encuentra en el almacén indicado'
                ];
            }

            if($quantity < $quantity_remove) {
                return  [
                    'success' => false,
                    'message' => 'La cantidad a retirar no puede ser mayor al que se tiene en el almacén.'
                ];
            }

            // $item_warehouse->stock = $quantity - $quantity_remove;
            // $item_warehouse->save();

            $inventory = new Inventory();
            $inventory->type = 3;
            $inventory->description = 'Retirar';
            $inventory->item_id = $item_id;
            $inventory->warehouse_id = $warehouse_id;
            $inventory->quantity = $quantity_remove;
            $inventory->save();

            return  [
                'success' => true,
                'message' => 'Producto trasladado con éxito'
            ];
        });

        return $result;
    }

    public function initialize()
    {
        $this->initializeInventory();
    }
    
    
}
