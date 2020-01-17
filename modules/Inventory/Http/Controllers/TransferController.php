<?php

namespace Modules\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Inventory\Http\Resources\TransferCollection;
use Modules\Inventory\Http\Resources\TransferResource;
use Modules\Inventory\Models\Inventory;
use Modules\Inventory\Traits\InventoryTrait;
use Modules\Inventory\Models\ItemWarehouse;
use Modules\Inventory\Models\Warehouse;
use Modules\Inventory\Http\Requests\InventoryRequest;
use Modules\Item\Models\ItemLot;

class TransferController extends Controller
{
    use InventoryTrait;

    public function index()
    {
        return view('inventory::transfers.index');
    }

    public function columns()
    {
        return [
            'description' => 'Producto',
        ];
    }

    public function records(Request $request)
    {
        $records = Inventory::with(['item', 'warehouse', 'warehouse_destination'])
                            ->where('type', 2)
                            ->whereHas('warehouse_destination')
                            ->whereHas('item', function($query) use($request) {
                                $query->where('description', 'like', '%' . $request->value . '%');

                            })
                            ->latest();


        return new TransferCollection($records->paginate(config('tenant.items_per_page')));
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
        $record = new TransferResource(Inventory::findOrFail($id));

        return $record;
    }


    public function store(Request $request)
    {

        // dd($request->all());
        
        $result = DB::connection('tenant')->transaction(function () use ($request) {

            $id = $request->input('id');
            $item_id = $request->input('item_id');
            $warehouse_id = $request->input('warehouse_id');
            $warehouse_destination_id = $request->input('warehouse_destination_id');
            $stock = $request->input('stock');
            $quantity = $request->input('quantity');
            $detail = $request->input('detail');

            if($warehouse_id === $warehouse_destination_id) {
                return  [
                    'success' => false,
                    'message' => 'El almacén destino no puede ser igual al de origen'
                ];
            }
            if($stock < $quantity) {
                return  [
                    'success' => false,
                    'message' => 'La cantidad a trasladar no puede ser mayor al que se tiene en el almacén.'
                ];
            }

            $re_it_warehouse = ItemWarehouse::where([['item_id',$item_id],['warehouse_id', $warehouse_destination_id]])->first();
            
            if(!$re_it_warehouse) {
                return  [
                    'success' => false,
                    'message' => 'El producto no se encuentra registrado en el almacén destino.'
                ];
            }

            
            $inventory = Inventory::findOrFail($id);
            
            //proccess stock
            $origin_inv_kardex = $inventory->inventory_kardex->first();
            $origin_item_warehouse = ItemWarehouse::where([['item_id',$origin_inv_kardex->item_id],['warehouse_id', $origin_inv_kardex->warehouse_id]])->first();
            $origin_item_warehouse->stock += $inventory->quantity;
            $origin_item_warehouse->stock -= $quantity;
            $origin_item_warehouse->update();


            $destination_inv_kardex = $inventory->inventory_kardex->last(); 
            $destination_item_warehouse = ItemWarehouse::where([['item_id',$destination_inv_kardex->item_id],['warehouse_id', $destination_inv_kardex->warehouse_id]])->first();
            $destination_item_warehouse->stock -= $inventory->quantity;
            $destination_item_warehouse->update();
 

            $new_item_warehouse = ItemWarehouse::where([['item_id',$item_id],['warehouse_id', $warehouse_destination_id]])->first();
            $new_item_warehouse->stock += $quantity;
            $new_item_warehouse->update();

            //proccess stock

            //proccess kardex
            $origin_inv_kardex->quantity = -$quantity;
            $origin_inv_kardex->update();

            $destination_inv_kardex->quantity = $quantity;
            $destination_inv_kardex->warehouse_id = $warehouse_destination_id;
            $destination_inv_kardex->update();
            //proccess kardex

            $inventory->warehouse_destination_id = $warehouse_destination_id;
            $inventory->quantity = $quantity;
            $inventory->detail = $detail;


            $inventory->update(); 
 
            return  [
                'success' => true,
                'message' => 'Traslado actualizado con éxito'
            ];
        });

        return $result;
    }


    public function destroy($id)
    {

        DB::connection('tenant')->transaction(function () use ($id) {

            $record = Inventory::findOrFail($id);

            $origin_inv_kardex = $record->inventory_kardex->first();
            $destination_inv_kardex = $record->inventory_kardex->last(); 

            $destination_item_warehouse = ItemWarehouse::where([['item_id',$destination_inv_kardex->item_id],['warehouse_id', $destination_inv_kardex->warehouse_id]])->first();
            $destination_item_warehouse->stock -= $record->quantity;
            $destination_item_warehouse->update();

            $origin_item_warehouse = ItemWarehouse::where([['item_id',$origin_inv_kardex->item_id],['warehouse_id', $origin_inv_kardex->warehouse_id]])->first();
            $origin_item_warehouse->stock += $record->quantity;
            $origin_item_warehouse->update();

            $record->inventory_kardex()->delete();
            $record->delete();

        });


        return [
            'success' => true,
            'message' => 'Traslado eliminado con éxito'
        ];



    }









}
