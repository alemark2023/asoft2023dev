<?php

namespace Modules\Restaurant\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\Models\Tenant\Item;
use Modules\Inventory\Models\InventoryConfiguration;
use Modules\Item\Models\Category;
use Modules\Restaurant\Http\Resources\ItemCollection;

class RestaurantController extends Controller
{
    public function menu()
    {
      $dataPaginate = Item::where([['apply_restaurant', 1], ['internal_id','!=', null]])->paginate(15);
      $configuration = InventoryConfiguration::first();
      return view('restaurant::index', ['dataPaginate' => $dataPaginate, 'configuration' => $configuration->stock_control]);
    }

    /*
     * vista privada
     */
    public function list_items()
    {
        return view('restaurant::items.index');
    }

    public function is_visible(Request $request)
    {
        $item = Item::find($request->id);

        if(!$item->internal_id && $request->apply_restaurant){
            return [
                'success' => false,
                'message' =>'Para habilitar la visibilidad, debe asignar un codigo interno al producto',
            ];
        }

        $visible = $request->apply_restaurant == true ? 1 : 0 ;
        $item->apply_restaurant = $visible;
        $item->save();

        return [
            'success' => true,
            'message' => ($visible > 0 )?'El Producto ya es visible en restaurante' : 'El Producto ya no es visible en restaurante',
            'id' => $request->id
        ];

    }

    public function items(Request $request){
        $records = new ItemCollection(Item::where([['apply_restaurant', 1], ['internal_id','!=', null]])->get());
        return [
            'success' => true,
            'data' => $records
        ]; 
    }

    public function categories(Request $request){
        $records = Category::all();
        return [
            'success' => true,
            'data' => $records
        ]; 
    }
}
