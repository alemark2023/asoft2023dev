<?php

namespace Modules\Item\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Item\Models\NamePrice;
use Modules\Item\Models\ListPrice;
use Modules\Item\Http\Resources\NamePriceCollection;
use Modules\Item\Http\Resources\NamePriceResource;
use Modules\Item\Http\Requests\NamePriceRequest;
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
        return new NamePriceCollection($records->paginate(config('tenant.items_per_page')));
    }


    public function record($id)
    {
        $record = ListPrice::with('name_price')->where('name_price_id', $id)->get();
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
        $name_price = NamePrice::where('type_customer_id', $id)->get();
        
        $records = ListPrice::where('name_price_id', $name_price[0]->id)->get();
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
        $item_price=$request->prices;

        $length_prices = count($item_price);

        if ($request->id==null) {
            $record_id = NamePrice::WhereIdPrice();
            $record_id+=1;
            $name_price = NamePrice::firstOrNew(['id'=>$record_id]);
            $name_price->description = $request->description;
            $name_price->unit_type_id = $request->unit_type_id;
            $name_price->quantity_unit = $request->quantity_unit;
            $name_price->price_default = $request->price_default;
            $name_price->save();

            
        
            foreach ($item_price as $value) {
                $item_unit_type = new ListPrice;
                $item_unit_type->name_price_id=$name_price->id;
                $item_unit_type->price = $value['price'];
                $item_unit_type->save();
                
            }
        }else{
            $name_price = NamePrice::firstOrNew(['id'=>$request->id]);
            $name_price->description = $request->description;
            $name_price->unit_type_id = $request->unit_type_id;
            $name_price->quantity_unit = $request->quantity_unit;
            $name_price->price_default = $request->price_default;
            $name_price->save();

            $list_prices = ListPrice::where('name_price_id',$request->id)->get();

            
            

            for ($i=0; $i < $length_prices; $i++) { 

                $list_prices[$i]->price = $item_price[$i]->price;
            }

            $list_prices->save();

            
        }
        

        $data = [
            'success' => true,
            'message' => ($request->id)?'Listado de precio editado con éxito':'Listado de precio registrado con éxito',
            'data' => $name_price->description
        ];
        return $data;

    }

    public function destroy($id)
    {
        try {

            $itemprices = ListPrice::where('name_price_id', $id)->delete();
         
            $nameprices = NamePrice::find($id);
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

            $category = NamePrice::findOrFail($id);
            $category->delete();

            return [
                'success' => true,
            ];

        } catch (Exception $e) {

            return ($e->getCode() == '23000') ? ['success' => false,'message' => "La categoría esta siendo usada por otros registros, no puede eliminar"] : ['success' => false,'message' => "Error inesperado, no se pudo eliminar la categoría"];

        }

    }




}
