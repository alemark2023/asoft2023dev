<?php

namespace Modules\Production\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Production\Http\Requests\ProductionRequest;
use Modules\Production\Http\Resources\ProductionCollection;
use Modules\Production\Models\Production;
use Illuminate\Support\Facades\DB;
use Modules\Inventory\Models\ItemWarehouse;
use Modules\Inventory\Models\InventoryTransaction;
use Modules\Inventory\Models\Inventory;
use App\Models\Tenant\Item;
use Modules\Inventory\Traits\InventoryTrait;


class ProductionController extends Controller
{
    use InventoryTrait;
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('production::production.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('production::production.form');
    }

    /**
     * Store a newly created resource in storage.
     * @param ProductionRequest $request
     * @return Response
     */
    public function store(ProductionRequest $request)
    {


        $result = DB::connection('tenant')->transaction(function () use ($request) {

			$item_id = $request->input('item_id');
			$warehouse_id = $request->input('warehouse_id');
			//$inventory_transaction_id = '19';  //Ingreso de producción
			$quantity = $request->input('quantity');

			$inventory_transaction = InventoryTransaction::findOrFail(19); //debe ser Ingreso de producción

			$inventory = new Inventory();
			$inventory->type = null;
			$inventory->description = $inventory_transaction->name;
			$inventory->item_id = $item_id;
			$inventory->warehouse_id = $warehouse_id;
			$inventory->quantity = $quantity;
			$inventory->inventory_transaction_id = $inventory_transaction->id;
			$inventory->save();

            $production = Production::firstOrNew(['id' => null]);
            $production->fill($request->all());
            $production->inventory_id_reference = $inventory->id;
            $production->user_id = auth()->user()->id;
            $production->save();

            /*
            $item = Item::find($item_id);

            $items_supplies = $item->supplies;
            */
            $items_supplies = $request->supplies;

            foreach ($items_supplies as $item) {

                $supplyWarehouseId = (int)($item['warehouse_id'] ?? $warehouse_id);
                $supplyWarehouseId = $supplyWarehouseId !== 0?$supplyWarehouseId :$warehouse_id;
                $qty = $item['quantity'] ??0;
                $inventory_transaction_item = InventoryTransaction::findOrFail('101'); //Salida insumos por molino
                $inventory_it = new Inventory();
                $inventory_it->type = null;
                $inventory_it->description = $inventory_transaction_item->name;
                $inventory_it->item_id = $item['individual_item_id'];
                $inventory_it->warehouse_id = $supplyWarehouseId;
                $inventory_it->quantity = (float)($qty * $quantity);
                $inventory_it->inventory_transaction_id = $inventory_transaction_item->id;
                $inventory_it->save();
            }

			return  [
				'success' => true,
				'message' => 'Ingreso registrado correctamente'
			];
		});

		return $result;

    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('production::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('production::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }


    public static function optionsItemProduction()
    {
        return Item::ProductEnded()
            ->get()
            ->transform(function (Item $row) {
            return  $row->getCollectionData();

        });
    }

    public static function optionsItemFullProduction($search = null, $take = null)
    {
        $query = Item::query()
            ->ProductEnded()
            ->with('item_lots', 'item_lots.item_loteable', 'lots_group')
            ;
        if ($search) {
            $query->where('description', 'like', "%{$search}%")
                ->orWhere('barcode', 'like', "%{$search}%")
                ->orWhere('internal_id', 'like', "%{$search}%");
        }
        if ($take) {
            $query->take($take);
        }
        return $query->get()->transform(function (Item $row) {
            return $row->getCollectionData();
        });
    }

    public function tables()
	{
		return [
			'items'      => self::optionsItemProduction(),
			'warehouses' => $this->optionsWarehouse()
		];
	}

    public function searchItems(Request $request)
	{
		$search = $request->input('search');

		return [
			'items' => self::optionsItemFullProduction($search, 20),
		];
	}

    public function records()
    {
        $records = Production::query();
        return new ProductionCollection($records->paginate(config('tenant.items_per_page')));

    }

}
