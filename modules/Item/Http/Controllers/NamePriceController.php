<?php

namespace Modules\Item\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Item\Models\NamePrice;
use Modules\Item\Models\ListPrice;
use Modules\Item\Http\Resources\ItemPriceTypeCollection;
use Modules\Item\Http\Resources\ItemPriceTypeResource;
use Modules\Item\Http\Requests\ItemPriceTypeRequest;
use App\Models\Tenant\Catalogs\UnitType;

class NamePriceController extends Controller
{

    public function index()
    {
        return view('item::price.index');
    }


    public function columns()
    {
        return [
            'description' => 'descripcion',
        ];
    }

    public function records(Request $request)
    {
        $records = NamePrice::where($request->column, 'like', "%{$request->value}%");
        
        return new ItemPriceTypeCollection($records->paginate(config('tenant.items_per_page')));
    }


    public function record($id)
    {
        $record = NamePriceType::with('item_price_type')->where('id', $id)->get();
       
        
        return $record;
    }

    public function tables()
    {
        $unit_types = UnitType::whereActive()->orderByDescription()->get();

        return compact(
            'unit_types'
        );

    }

    public function searchPrices($id)
    {
        $records = ItemPriceType::where('type_customer_id', $id)->get();

        return $records;
    }

    /**
     * Crea o edita una nueva categoría.
     * El nombre de categoría debe ser único, por lo tanto se valida cuando el nombre existe.
     *
     * @param CategoryRequest $request
     *
     * @return array
     */
    public function store(Request $request)
    {
        /* dd($request->item_unit_types); */
        $name_price=$request->input('name');
        $name=NamePriceType::firstOrNew(['name' => $name_price]);
        $name->save();
        $item_price=$request->item_unit_types;

        $price=null;
        foreach ($item_price as $value) {
            $price_id= $value['id'];
            $price= $value['description'];
            $item_unit_type = ItemPriceType::firstOrNew(['id' => $value['id']]);
            $item_unit_type->name_price_id = $name->id;
            $item_unit_type->description = $value['description'];
            $item_unit_type->unit_type_id = $value['unit_type_id'];
            $item_unit_type->quantity_unit = $value['quantity_unit'];
            $item_unit_type->price1 = $value['price1'];
            $item_unit_type->price2 = $value['price2'];
            $item_unit_type->price3 = $value['price3'];
            $item_unit_type->price4 = $value['price4'];
            $item_unit_type->price_default = $value['price_default'];
            $item_unit_type->save();
            $data = [
                'success' => true,
                'message' => ($price_id)?'Listado de precio editado con éxito':'Listado de precio registrado con éxito',
                'data' => $price
            ];
        }
        return $data;

    }

    public function destroy($id)
    {
        try {

            $itemprices = ItemPriceType::where('name_price_id', $id)->delete();
         
            $nameprices = NamePriceType::find($id);
            $nameprices->delete();
            return [
                'success' => true,
                'message' => 'Precio eliminada con éxito'
            ];

        } catch (Exception $e) {

            return ($e->getCode() == '23000') ? ['success' => false,'message' => "La categoría esta siendo usada por otros registros, no puede eliminar"] : ['success' => false,'message' => "Error inesperado, no se pudo eliminar la categoría"];

        }

    }

    public function list($id)
    {
        try {

            $category = ItemPriceType::findOrFail($id);
            $category->delete();

            return [
                'success' => true,
            ];

        } catch (Exception $e) {

            return ($e->getCode() == '23000') ? ['success' => false,'message' => "La categoría esta siendo usada por otros registros, no puede eliminar"] : ['success' => false,'message' => "Error inesperado, no se pudo eliminar la categoría"];

        }

    }




}
