<?php

namespace Modules\Production\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
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
        return view('production::index');
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
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
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

            $item = Item::find($item_id);

            $items_supplies = $item->supplies();

            foreach ($items_supplies as $item) {

                $inventory_transaction_item = InventoryTransaction::findOrFail('101'); //Salida insumos por molino
                $inventory_it = new Inventory();
                $inventory_it->type = null;
                $inventory_it->description = $inventory_transaction_item->name;
                $inventory_it->item_id = $item->individual_item_id;
                $inventory_it->warehouse_id = $warehouse_id;
                $inventory_it->quantity = $item->$quantity * $quantity;
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

    public function tables()
	{
		return [
			'items'      => $this->optionsItemProduction(),
			'warehouses' => $this->optionsWarehouse()
		];
	}

    public function searchItems(Request $request)
	{
		$search = $request->input('search');

		return [
			'items' => $this->optionsItemFullProduction($search, 20),
		];
	}
}
