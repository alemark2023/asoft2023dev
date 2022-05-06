<?php

namespace Modules\Item\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Item\Models\ItemPriceType;
use Modules\Item\Http\Resources\ItemPriceTypeCollection;
use Modules\Item\Http\Resources\ItemPriceTypeResource;
use Modules\Item\Http\Requests\ItemPriceTypeRequest;
use App\Models\Tenant\Catalogs\UnitType;

class ItemPriceTypeController extends Controller
{

    public function index()
    {
        return view('item::price.index');
    }


    public function columns()
    {
        return [
            'name' => 'nombre',
        ];
    }

    public function records(Request $request)
    {


        $records = ItemPriceType::distinct()->select('name')->where($request->column, 'like', "%{$request->value}%");

        return new ItemPriceTypeCollection($records->paginate(config('tenant.items_per_page')));
    }


    public function record($id)
    {
        $record = ItemPriceType::where('name', 'like', $id)->get();
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
        $name= $request->input('name');
        $item_price=$request->item_unit_types;

        $price=null;
        foreach ($item_price as $value) {
            $price_id= $value['id'];
            $price= $value['description'];
            $item_unit_type = ItemPriceType::firstOrNew(['id' => $value['id']]);
            $item_unit_type->name = $name;
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

        /* $id = (int)$request->input('id');
        $name = $request->input('name');
        $error = null;
        $category = null;
        if(!empty($name)){
            $category = ItemPriceType::where('name', $name);
            if(empty($id)) {
                $category= $category->first();
                if (!empty($category)) {
                    $error = 'El nombre de categoría ya existe';
                }
            }else{
                $category = $category->where('id','!=',$id)->first();
                if (!empty($category)) {
                    $error = 'El nombre de categoría ya existe para otro registro';
                }
            }
        } */
        
        /* if(empty($error)){
            $category = ItemPriceType::firstOrNew(['id' => $id]);
            $category->fill($request->all());
            $category->save();
            
        } */
        return $data;

    }

    public function destroy($id)
    {
        try {

            $category = ItemPriceType::findOrFail($id);
            $category->delete();

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
