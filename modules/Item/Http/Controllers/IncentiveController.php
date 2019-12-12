<?php
namespace Modules\Item\Http\Controllers;

use App\Models\Tenant\Item;
use App\Http\Controllers\Controller;
use App\Http\Resources\Tenant\ItemCollection;
use App\Http\Resources\Tenant\ItemResource;
use Exception;
use Illuminate\Http\Request;
use Modules\Item\Http\Resources\IncentiveCollection;


class IncentiveController extends Controller
{
    public function index()
    {
        return view('item::incentives.index');
    }
 
    public function columns()
    {
        return [
            'description' => 'Nombre',
            'internal_id' => 'Código interno',
            // 'description' => 'Descripción'
        ];
    }

    public function records(Request $request)
    {
        $records = Item::whereTypeUser()
                        ->whereNotIsSet()
                        ->where($request->column, 'like', "%{$request->value}%")
                        ->orderBy('description');

        return new IncentiveCollection($records->paginate(config('tenant.items_per_page')));
    }
  

    public function record($id)
    {
        $record = new ItemResource(Item::findOrFail($id));

        return $record;
    }

    public function store(ItemRequest $request) {
        //return 'no';
        $id = $request->input('id');
        $item = Item::firstOrNew(['id' => $id]);
        $item->item_type_id = '01';
        $item->fill($request->all());

        $item->save();
 



        return [
            'success' => true,
            'message' => ($id)?'Producto editado con éxito':'Producto registrado con éxito',
            'id' => $item->id
        ];
    }

    public function destroy($id)
    {
        try {

            $item = Item::findOrFail($id);
            $item->delete();

            return [
                'success' => true,
                'message' => 'Producto eliminado con éxito'
            ];

        } catch (Exception $e) {

            return ($e->getCode() == '23000') ? ['success' => false,'message' => 'El producto esta siendo usado por otros registros, no puede eliminar'] : ['success' => false,'message' => 'Error inesperado, no se pudo eliminar el producto'];

        }


    }
 


}
