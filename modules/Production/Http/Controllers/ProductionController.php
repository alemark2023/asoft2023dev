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

class ProductionController extends Controller
{
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
        return view('production::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $result = DB::connection('tenant')->transaction(function () use ($request) {
           
			$type = $request->input('type');
			$item_id = $request->input('item_id');
			$warehouse_id = $request->input('warehouse_id');
			$inventory_transaction_id = $request->input('inventory_transaction_id');
			$quantity = $request->input('quantity');

			$inventory_transaction = InventoryTransaction::findOrFail($inventory_transaction_id); //debe ser ingreso por produccion

			$inventory = new Inventory();
			$inventory->type = null;
			$inventory->description = $inventory_transaction->name;
			$inventory->item_id = $item_id;
			$inventory->warehouse_id = $warehouse_id;
			$inventory->quantity = $quantity;
			$inventory->inventory_transaction_id = $inventory_transaction_id;
			$inventory->save();

            $production = Production::firstOrNew(['id' => null]);
            $production->fill($request->all());
            $production->inventory_id_reference = $inventory->id;
            $production->save();


            $items_supplies = $production->items_supplies();

            foreach ($items_supplies as $item) {

                $inventory_transaction = InventoryTransaction::findOrFail('100'); //Ingreso insumos por molino







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
}
