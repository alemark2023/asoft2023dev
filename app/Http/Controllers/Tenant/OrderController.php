<?php
namespace App\Http\Controllers\Tenant;

use Exception;

use App\Models\Tenant\Order;
use Illuminate\Http\Request;
use App\Models\Tenant\Series;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Establishment;
use App\Models\Tenant\ItemWarehouse;

use App\Http\Resources\Tenant\OrderCollection;
use App\CoreFacturalo\Helpers\Storage\StorageDocument;
use App\Http\Resources\Tenant\ItemWarehouseCollection;

class OrderController extends Controller
{

  use StorageDocument;

  protected $company;

    public function index()
    {
        return view('tenant.orders.index');
    }

    public function columns()
    {
        return [
            'id' => 'Codigo de Pedido',
            'number_document' => 'Comprobante Electronico',
        ];
    }

    public function tables()
    {
      $establishments = Establishment::where('id', auth()->user()->establishment_id)->get();
      $series = collect(Series::all())->transform(function($row) {
          return [
              'id' => $row->id,
              'contingency' => (bool) $row->contingency,
              'document_type_id' => $row->document_type_id,
              'establishment_id' => $row->establishment_id,
              'number' => $row->number
          ];
      });

      return compact('series', 'establishments');

    }

    public function records(Request $request)
    {
        $records = Order::where($request->column, 'like', "%{$request->value}%")->latest();

        return new OrderCollection($records->paginate(config('tenant.items_per_page')));
    }

    public function updateStatusOrders(Request $request)
    {
      
      if ($request->record['status_order_id'] == 3) {
        for ($i=0; $i <= count($request->discount)-1; $i++) {
          if (isset($request->discount[$i]['id'])) {
            $itemWarehouse = ItemWarehouse::where('id', $request->discount[$i]['id'])->first();

            //if ($itemWarehouse->stock >= $request->discount[$i]['cantidad']) {
              ItemWarehouse::where('id', $itemWarehouse->id)->update(['stock' => ($itemWarehouse->stock - $request->discount[$i]['cantidad'])]);

            //}
          }
        }
        Order::where('id', $request->record['id'])->update(['status_order_id' => $request->record['status_order_id']]);

        return [
          'message' => 'Estatus y Stock actualizado'
        ];
      }

      Order::where('id', $request->record['id'])->update(['status_order_id' => $request->record['status_order_id']]);
      return [
        'message' => 'Estatus actualizado'
      ];

    }

    public function searchWarehouse(Request $request)
    {
      $product = ItemWarehouse::whereIn('item_id', $request->item_id)->orderBy('item_id')->get();
      return new ItemWarehouseCollection($product);
    }
}

