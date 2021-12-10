<?php

namespace Modules\Production\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Inventory\Traits\InventoryTrait;
use Modules\Production\Models\Mill;
use Modules\Inventory\Models\Warehouse;
use App\Models\Tenant\Item;


class MillController extends Controller
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
        return view('production::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $model = Mill::firstOrNew(['id' => null]);
        $model->fill($request->all());
        $model->save();

        foreach ($request->items as $item) {

            $id = $item['id'];
            $quantity = $item['quantity'];
            $presentation = $item['presentation'];
            $warehouse_id = $item['warehouse_id'];

            $presentationQuantity = (!empty($presentation)) ? $presentation->quantity_unit : 1;

            $warehouse = ($warehouse_id) ? $this->findWarehouse($this->findWarehouseById($warehouse_id)->establishment_id) : $this->findWarehouse();

            $this->createInventoryKardex($model, $id, ($quantity * $presentationQuantity), $warehouse->id);
            $this->updateStock($id, ($quantity * $presentationQuantity), $warehouse->id);

        }

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

    public function tables() {
        $warehouses = Warehouse::all();
        return compact('warehouses');

    }

    public function records() {
        $records = Mill::all();
        return compact('records');
    }
}
