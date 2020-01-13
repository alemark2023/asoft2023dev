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

    public function store(Request $request) {

        $request->validate([
            'commission_amount' => 'required|numeric|min:0.01',
        ]);

        $id = $request->input('id');
        $item = Item::findOrFail($id);
        $item->commission_amount = $request->commission_amount;
        $item->update();

        return [
            'success' => true,
            'message' => 'Incentivo registrado con éxito',
            'id' => $item->id
        ];
    }

    public function destroy($id)
    {

        $item = Item::findOrFail($id);
        $item->commission_amount = null;
        $item->update();

        return [
            'success' => true,
            'message' => 'Incentivo eliminado con éxito'
        ];

    }
 


}
